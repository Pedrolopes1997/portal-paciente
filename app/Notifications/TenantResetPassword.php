<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantResetPassword extends Notification
{
    use Queueable;

    public $token;
    public $tenantSlug;

    public function __construct($token, $tenantSlug)
    {
        $this->token = $token;
        $this->tenantSlug = $tenantSlug;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Gera o link contendo o SLUG do hospital
        $url = route('paciente.password.reset', [
            'tenant_slug' => $this->tenantSlug,
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Redefinição de Senha - Portal do Paciente')
            ->greeting('Olá!')
            ->line('Você está recebendo este e-mail porque recebemos um pedido de redefinição de senha para sua conta.')
            ->action('Redefinir Senha', $url)
            ->line('Se você não solicitou uma redefinição de senha, nenhuma ação é necessária.')
            ->salutation('Atenciosamente, Equipe de Suporte.');
    }
}