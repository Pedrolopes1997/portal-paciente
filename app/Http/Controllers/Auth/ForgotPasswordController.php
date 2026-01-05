<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    // 1. Tela "Esqueci minha senha" (Pede E-mail)
    public function showLinkRequestForm($tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();
        return view('auth.forgot-password', compact('tenant'));
    }

    // 2. Enviar o E-mail
    public function sendResetLinkEmail(Request $request, $tenant_slug)
    {
        $request->validate(['email' => 'required|email']);

        // Forçamos o envio usando o Broker padrão, mas nossa notificação personalizada no Model User fará o resto
        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link de redefinição enviado para seu e-mail!');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // 3. Tela "Nova Senha" (Clica no E-mail e cai aqui)
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