<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use App\Models\SalesTarget;

class SalesTargetProgressNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $target;
    protected $progressPercentage;
    protected $notificationType;

    /**
     * Create a new notification instance.
     */
    public function __construct(SalesTarget $target, float $progressPercentage, string $notificationType = 'progress')
    {
        $this->target = $target;
        $this->progressPercentage = $progressPercentage;
        $this->notificationType = $notificationType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        // Add email for important milestones
        if ($this->progressPercentage >= 80) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->getEmailSubject();
        $greeting = $this->getEmailGreeting($notifiable);
        $message = $this->getEmailMessage();
        $actionText = 'عرض تفاصيل الهدف';
        $actionUrl = route('tenant.sales.targets.show', $this->target);

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($message)
                    ->line($this->getProgressDetails())
                    ->action($actionText, $actionUrl)
                    ->line('شكراً لك على جهودك المستمرة!')
                    ->salutation('مع أطيب التحيات، فريق MaxCon ERP');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'sales_target_progress',
            'target_id' => $this->target->id,
            'target_title' => $this->target->title,
            'target_entity_name' => $this->target->target_entity_name,
            'target_type' => $this->target->target_type,
            'progress_percentage' => $this->progressPercentage,
            'notification_type' => $this->notificationType,
            'message' => $this->getDatabaseMessage(),
            'action_url' => route('tenant.sales.targets.show', $this->target),
            'icon' => $this->getNotificationIcon(),
            'color' => $this->getNotificationColor(),
            'priority' => $this->getNotificationPriority()
        ];
    }

    /**
     * Get email subject based on progress
     */
    private function getEmailSubject(): string
    {
        if ($this->progressPercentage >= 100) {
            return '🎉 تهانينا! تم تحقيق الهدف بنجاح';
        } elseif ($this->progressPercentage >= 90) {
            return '🔥 أنت على وشك تحقيق الهدف!';
        } elseif ($this->progressPercentage >= 80) {
            return '⚡ تقدم ممتاز في تحقيق الهدف';
        } else {
            return '📊 تحديث حول تقدم الهدف';
        }
    }

    /**
     * Get email greeting
     */
    private function getEmailGreeting(object $notifiable): string
    {
        $name = $notifiable->name ?? 'عزيزي المستخدم';
        
        if ($this->progressPercentage >= 100) {
            return "مبروك {$name}! 🎉";
        } elseif ($this->progressPercentage >= 80) {
            return "أحسنت {$name}! 👏";
        } else {
            return "مرحباً {$name}";
        }
    }

    /**
     * Get email message
     */
    private function getEmailMessage(): string
    {
        $targetName = $this->target->title;
        $entityName = $this->target->target_entity_name;
        $progress = number_format($this->progressPercentage, 1);

        if ($this->progressPercentage >= 100) {
            return "لقد تم تحقيق الهدف '{$targetName}' للكيان '{$entityName}' بنسبة {$progress}%! 🎯";
        } elseif ($this->progressPercentage >= 90) {
            return "أنت على بُعد خطوات قليلة من تحقيق الهدف '{$targetName}' للكيان '{$entityName}'. التقدم الحالي: {$progress}%";
        } elseif ($this->progressPercentage >= 80) {
            return "تقدم رائع! لقد حققت {$progress}% من الهدف '{$targetName}' للكيان '{$entityName}'.";
        } else {
            return "تحديث حول تقدم الهدف '{$targetName}' للكيان '{$entityName}': {$progress}%";
        }
    }

    /**
     * Get progress details for email
     */
    private function getProgressDetails(): string
    {
        $details = [];
        
        if ($this->target->measurement_type === 'quantity' || $this->target->measurement_type === 'both') {
            $achieved = number_format($this->target->achieved_quantity);
            $target = number_format($this->target->target_quantity);
            $unit = $this->target->unit;
            $details[] = "الكمية: {$achieved} من {$target} {$unit}";
        }
        
        if ($this->target->measurement_type === 'value' || $this->target->measurement_type === 'both') {
            $achieved = number_format($this->target->achieved_value);
            $target = number_format($this->target->target_value);
            $currency = $this->target->currency;
            $details[] = "القيمة: {$achieved} من {$target} {$currency}";
        }
        
        $remainingDays = $this->target->remaining_days;
        $details[] = "الأيام المتبقية: {$remainingDays} يوم";
        
        return implode(' | ', $details);
    }

    /**
     * Get database message
     */
    private function getDatabaseMessage(): string
    {
        $targetName = $this->target->title;
        $progress = number_format($this->progressPercentage, 1);

        if ($this->progressPercentage >= 100) {
            return "تم تحقيق الهدف '{$targetName}' بنسبة {$progress}%! 🎯";
        } elseif ($this->progressPercentage >= 90) {
            return "أنت على وشك تحقيق الهدف '{$targetName}' - {$progress}%";
        } elseif ($this->progressPercentage >= 80) {
            return "تقدم ممتاز في الهدف '{$targetName}' - {$progress}%";
        } else {
            return "تحديث تقدم الهدف '{$targetName}' - {$progress}%";
        }
    }

    /**
     * Get notification icon
     */
    private function getNotificationIcon(): string
    {
        if ($this->progressPercentage >= 100) {
            return 'fas fa-trophy';
        } elseif ($this->progressPercentage >= 90) {
            return 'fas fa-fire';
        } elseif ($this->progressPercentage >= 80) {
            return 'fas fa-chart-line';
        } else {
            return 'fas fa-bullseye';
        }
    }

    /**
     * Get notification color
     */
    private function getNotificationColor(): string
    {
        if ($this->progressPercentage >= 100) {
            return 'success';
        } elseif ($this->progressPercentage >= 90) {
            return 'warning';
        } elseif ($this->progressPercentage >= 80) {
            return 'info';
        } else {
            return 'primary';
        }
    }

    /**
     * Get notification priority
     */
    private function getNotificationPriority(): string
    {
        if ($this->progressPercentage >= 100) {
            return 'high';
        } elseif ($this->progressPercentage >= 80) {
            return 'medium';
        } else {
            return 'low';
        }
    }
}
