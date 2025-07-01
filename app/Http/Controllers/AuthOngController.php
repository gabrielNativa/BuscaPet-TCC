<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Ong;

class AuthOngController extends Controller
{
    // Exibe o formulário de login
    public function showLoginForm()
    {
        return view('auth.loginOng');
    }

    // Realiza a autenticação da ONG
    public function login(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string',
            'password' => 'required|min:6',
        ]);
    
        $ong = Ong::where('cnpjOng', $request->cnpj)->first();
    
        if (!$ong || !Hash::check($request->password, $ong->senhaOng)) {
            return back()->withErrors([
                'cnpj' => 'CNPJ ou Senha inválidos.',
            ]);
        }
    
        // Verifica o status da ONG
        if ($ong->status === 'pendente') {
            return back()->withErrors([
                'cnpj' => 'Seu cadastro ainda não foi aprovado. Aguarde a análise.',
            ]);
        }
    
        // Adiciona verificação para ONG rejeitada
        if ($ong->status === 'rejeitado') {
            return back()->withErrors([
                'cnpj' => 'Seu cadastro foi rejeitado. Em breve receberá um email sobre',
            ]);
        }
    
        Auth::guard('ong')->login($ong);
    
        return redirect()->route('ong.dashboard');
    }

    // Logout
    public function logout()
    {
        Auth::guard('ong')->logout();
        return redirect()->route('ong.login');
    }
}
