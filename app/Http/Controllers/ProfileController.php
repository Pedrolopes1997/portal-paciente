<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Apenas renderiza a view, o Livewire faz o resto
    public function edit()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        return view('paciente.profile', compact('user', 'tenant'));
    }

    // O método update() pode ser removido ou deixado vazio, pois não é mais chamado pela rota
}