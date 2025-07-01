<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailUser' => 'required|email',
            'senhaUser' => 'required|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }
    
        $user = User::where('emailUser', $request->emailUser)->first();
    
        if (!$user || !Hash::check($request->senhaUser, $user->senhaUser)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
        
        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Seu e-mail ainda não foi verificado.'], 403);
        }

        if ($user->ativo === 0) {
            return response()->json(['message' => 'Sua conta foi bloqueada pelo administrador.'], 403);
        }
    
        $token = $user->createToken('AppName')->plainTextToken;
    
        return response()->json([
            'message' => 'Login bem-sucedido',
            'token' => $token,
            'user' => $user
        ], 200);
    }
        public function reenviarEmailVerificacao(Request $request)
{
    $user = User::where('emailUser', $request->emailUser)->first();

    if (!$user) {
        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    if ($user->email_verified) {
        return response()->json(['message' => 'E-mail já verificado'], 400);
    }

    // Supondo que você tenha um sistema de envio de verificação:
    event(new \Illuminate\Auth\Events\Registered($user));

    return response()->json(['message' => 'E-mail de verificação reenviado com sucesso.']);
}

}

