<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $email = $request->email;
            
            // Gerar código de 4 dígitos
            $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // Armazenar o código no cache por 10 minutos
            Cache::put('verification_code_' . $email, $code, now()->addMinutes(10));

            // Enviar email com o código
            Mail::raw("Seu código de verificação é: " . $code, function($message) use ($email) {
                $message->to($email)
                        ->subject('Código de Verificação - BuscaPet');
            });

            Log::info('Código de verificação enviado para: ' . $email);

            return response()->json([
                'message' => 'Código de verificação enviado com sucesso'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar código de verificação: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao enviar o código de verificação: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyCode(Request $request)
    {
        try {
            Log::info('Tentativa de verificação de código', $request->all());

            $request->validate([
                'email' => 'required|email',
                'code' => 'required|string'
            ]);

            $email = $request->email;
            $code = $request->code;
            
            // Recuperar o código armazenado
            $storedCode = Cache::get('verification_code_' . $email);
            
            Log::info('Código armazenado: ' . $storedCode);
            Log::info('Código recebido: ' . $code);
            
            if (!$storedCode) {
                return response()->json([
                    'error' => 'Código expirado ou inválido',
                    'verified' => false
                ], 400);
            }

            if ($code !== $storedCode) {
                return response()->json([
                    'error' => 'Código incorreto',
                    'verified' => false
                ], 400);
            }

            // Código correto - marcar usuário como verificado
            $user = User::where('emailUser', $email)->first();
            
            if ($user) {
                $user->email_verified_at = now();
                $user->save();
                
                Log::info('Email verificado com sucesso: ' . $email);
            } else {
                Log::warning('Usuário não encontrado para o email: ' . $email);
            }

            // Remover código do cache
            Cache::forget('verification_code_' . $email);

            return response()->json([
                'message' => 'Email verificado com sucesso',
                'verified' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao verificar código: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao verificar código: ' . $e->getMessage(),
                'verified' => false
            ], 500);
        }
    }
} 