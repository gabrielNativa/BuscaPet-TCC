<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Adm;

class AuthController extends Controller
{
    
    // Exibe a página de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Faz a autenticação do usuário
    public function login(Request $request)
    {
        // Validação
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Busca o usuário
        $adm = Adm::where('emailAdm', $request->email)->first();

        // Verifica se o usuário existe e a senha está correta
        if ($adm && Hash::check($request->password, $adm->senhaAdm)) {
            // Autentica o usuário
            Auth::login($adm);

            // Redireciona para a página inicial ou outra página
            return redirect()->route('home');
        }

        // Se falhar, redireciona de volta com erro
        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ]);
    }

    // Faz o logout do usuário
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
