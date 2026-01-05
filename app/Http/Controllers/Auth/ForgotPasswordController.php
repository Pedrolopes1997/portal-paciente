<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // 1. Tela "Esqueci minha senha"
    public function showLinkRequestForm($tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();
        return view('auth.forgot-password', compact('tenant'));
    }

    // 2. Enviar o E-mail
    public function sendResetLinkEmail(Request $request, $tenant_slug)
    {
        $request->validate(['email' => 'required|email']);
        
        // Busca o Tenant para garantir que o usuário pertence a ele
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();

        // Verifica se o usuário existe NESTE tenant
        $user = User::where('email', $request->email)
                    ->where('tenant_id', $tenant->id)
                    ->first();

        if (!$user) {
            // Retorna erro genérico por segurança, ou específico se preferir
            return back()->withErrors(['email' => 'E-mail não encontrado nesta clínica.']);
        }

        // Envia o link (A notificação 'TenantResetPassword' no Model User fará a mágica do link correto)
        // Passamos o tenant_slug explicitamente para o contexto da notificação se necessário
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) use ($tenant_slug) {
                // Aqui garantimos que a notificação saiba o slug
                $user->sendPasswordResetNotification($token, $tenant_slug);
            }
        );

        return back()->with('status', 'Link de redefinição enviado para seu e-mail!');
    }

    // 3. Tela "Nova Senha" (Link do E-mail cai aqui)
    public function showResetForm(Request $request, $tenant_slug, $token = null)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();
        
        return view('auth.reset-password', [
            'token' => $token, 
            'email' => $request->email, 
            'tenant' => $tenant
        ]);
    }

    // 4. Salvar Nova Senha
    public function reset(Request $request, $tenant_slug)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();

        // Reseta a senha
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('paciente.login', ['tenant_slug' => $tenant_slug])
                             ->with('status', 'Sua senha foi redefinida com sucesso!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}