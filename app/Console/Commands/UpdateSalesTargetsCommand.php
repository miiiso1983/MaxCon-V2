<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalesTarget;
use App\Models\User;
use App\Notifications\SalesTargetProgressNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UpdateSalesTargetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sales-targets:update 
                            {--check-notifications : Check and send progress notifications}
                            {--update-status : Update target statuses based on dates}
                            {--tenant= : Process specific tenant only}';

    /**
     * The console command description.
     */
    protected $description = 'Update sales targets progress, check notifications, and update statuses';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting sales targets update process...');
        
        $tenantId = $this->option('tenant');
        $checkNotifications = $this->option('check-notifications');
        $updateStatus = $this->option('update-status');
        
        // If no specific options provided, run all updates
        if (!$checkNotifications && !$updateStatus) {
            $checkNotifications = true;
            $updateStatus = true;
        }
        
        try {
            if ($updateStatus) {
                $this->updateTargetStatuses($tenantId);
            }
            
            if ($checkNotifications) {
                $this->checkAndSendNotifications($tenantId);
            }
            
            $this->info('Sales targets update completed successfully!');
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Error updating sales targets: ' . $e->getMessage());
            Log::error('Sales targets update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
    
    /**
     * Update target statuses based on dates
     */
    private function updateTargetStatuses(?string $tenantId): void
    {
        $this->info('Updating target statuses...');
        
        $query = SalesTarget::query();
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        $targets = $query->where('status', 'active')->get();
        $updatedCount = 0;
        
        foreach ($targets as $target) {
            $originalStatus = $target->status;
            $newStatus = $this->determineTargetStatus($target);
            
            if ($originalStatus !== $newStatus) {
                $target->update(['status' => $newStatus]);
                $updatedCount++;
                
                $this->line("Updated target '{$target->title}' status from {$originalStatus} to {$newStatus}");
                
                Log::info('Target status updated', [
                    'target_id' => $target->id,
                    'title' => $target->title,
                    'old_status' => $originalStatus,
                    'new_status' => $newStatus
                ]);
            }
        }
        
        $this->info("Updated {$updatedCount} target statuses.");
    }
    
    /**
     * Determine target status based on current state
     */
    private function determineTargetStatus(SalesTarget $target): string
    {
        $today = Carbon::today();
        
        // If target is completed (100% or more)
        if ($target->progress_percentage >= 100) {
            return 'completed';
        }
        
        // If target end date has passed and not completed
        if ($target->end_date->lt($today)) {
            return 'paused'; // or 'overdue' if you have that status
        }
        
        // If target hasn't started yet
        if ($target->start_date->gt($today)) {
            return 'active'; // Keep as active for future targets
        }
        
        // Default to active for current targets
        return 'active';
    }
    
    /**
     * Check and send progress notifications
     */
    private function checkAndSendNotifications(?string $tenantId): void
    {
        $this->info('Checking for notification triggers...');
        
        $query = SalesTarget::query();
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        $targets = $query->where('status', 'active')->get();
        $notificationsSent = 0;
        
        foreach ($targets as $target) {
            $notifications = $this->checkTargetNotifications($target);
            $notificationsSent += count($notifications);
        }
        
        $this->info("Sent {$notificationsSent} notifications.");
    }
    
    /**
     * Check if target needs notifications
     */
    private function checkTargetNotifications(SalesTarget $target): array
    {
        $notifications = [];
        
        // Check 80% milestone
        if ($target->progress_percentage >= 80 && !$target->notification_80_sent) {
            $this->sendTargetNotification($target, 80, 'milestone_80');
            $target->update(['notification_80_sent' => true]);
            $notifications[] = '80%';
        }
        
        // Check 100% milestone
        if ($target->progress_percentage >= 100 && !$target->notification_100_sent) {
            $this->sendTargetNotification($target, 100, 'milestone_100');
            $target->update(['notification_100_sent' => true]);
            $notifications[] = '100%';
        }
        
        // Check overdue notification (if target is past due date and progress < 100%)
        if ($target->end_date->lt(Carbon::today()) && $target->progress_percentage < 100) {
            $this->sendTargetNotification($target, $target->progress_percentage, 'overdue');
            $notifications[] = 'overdue';
        }
        
        // Check weekly progress notification (every Monday for active targets)
        if (Carbon::today()->isMonday() && $target->is_active) {
            $this->sendTargetNotification($target, $target->progress_percentage, 'weekly_update');
            $notifications[] = 'weekly';
        }
        
        if (!empty($notifications)) {
            $this->line("Sent notifications for target '{$target->title}': " . implode(', ', $notifications));
        }
        
        return $notifications;
    }
    
    /**
     * Send notification for a target
     */
    private function sendTargetNotification(SalesTarget $target, float $progress, string $type): void
    {
        try {
            // Get users to notify based on target type
            $usersToNotify = $this->getUsersToNotify($target);
            
            if ($usersToNotify->isNotEmpty()) {
                Notification::send(
                    $usersToNotify,
                    new SalesTargetProgressNotification($target, $progress, $type)
                );
                
                Log::info('Sales target notification sent', [
                    'target_id' => $target->id,
                    'target_title' => $target->title,
                    'progress' => $progress,
                    'type' => $type,
                    'recipients_count' => $usersToNotify->count()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to send sales target notification', [
                'target_id' => $target->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get users to notify based on target type
     */
    private function getUsersToNotify(SalesTarget $target)
    {
        $users = collect();
        
        switch ($target->target_type) {
            case 'sales_rep':
                // Notify the specific sales rep and their manager
                $salesRep = User::find($target->target_entity_id);
                if ($salesRep) {
                    $users->push($salesRep);
                    // Add manager if exists
                    if ($salesRep->manager_id) {
                        $manager = User::find($salesRep->manager_id);
                        if ($manager) {
                            $users->push($manager);
                        }
                    }
                }
                break;
                
            case 'sales_team':
                // Notify team members and team leader
                $teamMembers = User::where('team_id', $target->target_entity_id)->get();
                $users = $users->merge($teamMembers);
                break;
                
            case 'department':
                // Notify department head and managers
                $departmentUsers = User::where('department_id', $target->target_entity_id)
                                      ->whereIn('role', ['manager', 'department_head'])
                                      ->get();
                $users = $users->merge($departmentUsers);
                break;
                
            case 'product':
            case 'vendor':
                // Notify sales managers and admins
                $managers = User::where('tenant_id', $target->tenant_id)
                               ->whereIn('role', ['admin', 'sales_manager'])
                               ->get();
                $users = $users->merge($managers);
                break;
        }
        
        // Always include the target creator
        $creator = User::find($target->created_by);
        if ($creator) {
            $users->push($creator);
        }
        
        return $users->unique('id');
    }
}
