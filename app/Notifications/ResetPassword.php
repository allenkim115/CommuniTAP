<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $logoPath = public_path('images/communitaplogo1.png');
        $logoCid = 'logo-' . uniqid();

        $mailMessage = (new MailMessage)
            ->subject('Reset Your Password - CommuniTap');

        // Embed logo as attachment using Symfony message
        if (file_exists($logoPath)) {
            $mailMessage->withSymfonyMessage(function ($message) use ($logoPath, $logoCid) {
                $message->embedFromPath($logoPath, $logoCid);
            });
            $logoUrl = 'cid:' . $logoCid;
        } else {
            $logoUrl = null;
        }

        return $mailMessage->view('emails.reset-password', [
            'resetUrl' => $url,
            'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            'logoUrl' => $logoUrl,
        ]);
    }
}

