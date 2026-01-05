<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // 1. Exibir a tela de Perfil
    public function edit()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        return view('paciente.profile', compact('user', 'tenant'));
    }

    // 2. Salvar as alterações
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validação
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email,'.$user->id], // Único, exceto para o próprio usuário
            'current_password' => ['nullable', 'required_with:password', 'current_password'], // Obrigatório só se for mudar a senha
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()], // Nova senha (opcional)
        ]);

        // Atualiza E-mail
        $user->email = $validated['email'];

        // Se o usuário preencheu o campo de senha nova, atualiza
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}