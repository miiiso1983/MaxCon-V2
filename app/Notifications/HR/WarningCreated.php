<?php

namespace App\Notifications\HR;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Tenant\HR\Warning;

class WarningCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Warning $warning)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تم إصدار إنذار جديد')
            ->greeting('مرحباً،')
            ->line('تم تسجيل إنذار جديد للموظف: ' . ($this->warning->employee?->first_name . ' ' . $this->warning->employee?->last_name))
            ->line('النوع: ' . $this->warning->type)
            ->line('الشدة: ' . $this->warning->severity)
            ->line('التاريخ: ' . optional($this->warning->date)->format('Y-m-d'))
            ->line('السبب: ' . ($this->warning->reason ?? '-'))
            ->action('عرض الإنذار', url()->route('tenant.hr.warnings.index'))
            ->line('هذه رسالة آلية من نظام MaxCon.');
    }
}

