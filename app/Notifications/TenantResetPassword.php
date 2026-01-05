<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tenant; // Importante

class TenantResetPassword extends Notification
{
    use Queueable;

    public $token;
    public $tenantSlug;
    public $tenantName; // Novo

    public function __construct($token, $tenantSlug)
    {
        $this->token = $token;
        $this->tenantSlug = $tenantSlug;
        
        // Busca o nome do tenant para personalizar o e-mail
        $tenant = Tenant::where('slug', $tenantSlug)->first();
        $this->tenantName = $tenant ? $tenant->name : 'Portal do Paciente';
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('paciente.password.reset', [
            'tenant_slug' => $this->tenantSlug,
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Redefinição de Senha - ' . $this->tenantName) // Assunto Personalizado
            ->greeting('Olá!')
            ->line('Recebemos uma solicitação para redefinir sua senha no portal da ' . $this->tenantName . '.')
            ->action('Redefinir Minha Senha', $url)
            ->line('Se não foi você, ignore este e-mail.')
            ->salutation('Equipe ' . $this->tenantName);
    }
}