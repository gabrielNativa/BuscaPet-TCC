<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function enviarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tbuser,emailUser'
        ]);

        $user = User::where('emailUser', $request->email)->first();

        // Gerar um código de 6 dígitos
        $resetCode = strtoupper(Str::random(6));
        
        // Salvar o código no usuário
        $user->reset_code = $resetCode;
        $user->reset_code_expires_at = now()->addHour(); // Código válido por 1 hora
        $user->save();

        // Enviar e-mail com o código
        Mail::to($user->emailUser)->send(new PasswordResetMail($resetCode));

        return response()->json([
            'message' => 'Código de redefinição enviado para seu e-mail'
        ]);
    }

    public function redefinirSenha(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tbuser,emailUser',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::where('emailUser', $request->email)->first();

        // Verificar se o código é válido
        if ($user->reset_code !== $request->code || 
            now()->gt($user->reset_code_expires_at)) {
            return response()->json([
                'message' => 'Código inválido ou expirado'
            ], 422);
        }

        // Atualizar a senha
        $user->senhaUser = Hash::make($request->password);
        $user->reset_code = null;
        $user->reset_code_expires_at = null;
        $user->save();

        return response()->json([
            'message' => 'Senha redefinida com sucesso'
        ]);
    }
}