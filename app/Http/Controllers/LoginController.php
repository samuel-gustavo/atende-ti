<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Exibe tela de login
    public function create()
    {

    }

    // Validação do login
    public function store(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credenciais['email'])->first();
        if(!$user || !$user->ativo) {
            return "E-mail ou senha inválidos.";
        }

        if(Auth::attempt($credenciais, $request->boolean('remember'))) {
            return "E-mail ou senha inválidos.";
        }

        $request->session()->regenerate();

        if(Auth::user()->role === 'tecnico') {
            return "tecnico.dashboard";
        }
        return "cliente.dashboard";
    }

    // Realiza logout
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return "login";
    }
}
