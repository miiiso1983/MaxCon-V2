<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendUserCredentialsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $email,
        public string $plainPassword
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('بيانات الدخول إلى نظام MaxCon ERP')
            ->greeting('مرحباً ' . ($notifiable->name ?? ''))
            ->line('تم إنشاء حساب لك في نظام MaxCon ERP.')
            ->line('البريد الإلكتروني: ' . $this->email)
            ->line('كلمة المرور: ' . $this->plainPassword)
            ->action('تسجيل الدخول', url('/login'))
            ->line('ننصحك بتغيير كلمة المرور بعد تسجيل الدخول لأول مرة.')
            ->salutation('مع تحيات فريق MaxCon ERP');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'email' => $this->email,
            'message' => 'تم إرسال بيانات الدخول للمستخدم الجديد',
        ];
    }
}

