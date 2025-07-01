<?php

namespace App\Http\Controllers;

use App\Models\Interesse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Notificacao;
use App\Models\Animal;

class InteresseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function registrarInteresse(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $validated = $request->validate([
                'idAnimal' => 'required|integer',
                'observacoes' => 'nullable|string',
            ]);

            // Verifica se já existe interesse
            $jaExiste = Interesse::where('idUser', $user->id ?? $user->idUser)
                ->where('idAnimal', $validated['idAnimal'])
                ->first();

            if ($jaExiste) {
                return response()->json([
                    'success' => false,
                    'message' => 'Interesse já registrado.'
                ], 409);
            }

            $interesse = Interesse::create([
                'idUser' => $user->id ?? $user->idUser,
                'idAnimal' => $validated['idAnimal'],
                'observacoes' => $validated['observacoes'] ?? null,
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Interesse registrado com sucesso.',
                'interesse' => $interesse
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao registrar interesse: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao registrar interesse.'
            ], 500);
        }
    }



    public function cancelarInteresse(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado',
                ], 401);
            }

            $validated = $request->validate([
                'idAnimal' => 'required|integer',
            ]);

            Log::info('Requisição para cancelar interesse recebida', [
                'user_id' => $user->id ?? $user->idUser,
                'idAnimal' => $validated['idAnimal']
            ]);

            $interesse = Interesse::where('idUser', $user->id ?? $user->idUser)
                ->where('idAnimal', $validated['idAnimal'])
                ->first();

            if (!$interesse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Interesse não encontrado',
                ], 404);
            }

            $interesse->delete();

            return response()->json([
                'success' => true,
                'message' => 'Interesse cancelado com sucesso',
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar interesse: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao cancelar interesse',
            ], 500);
        }
    }



    public function verificarInteresse(Request $request)
    {
        try {
            $user = auth()->user();
            $idAnimal = $request->query('idAnimal');

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            if (!$idAnimal) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID do animal não fornecido'
                ], 400);
            }

            // Usa o id padrão do usuário (não idUser, a não ser que esteja explícito no seu model)
            $interesse = Interesse::where('idUser', $user->id ?? $user->idUser)
                ->where('idAnimal', $idAnimal)
                ->first();

            return response()->json([
                'success' => true,
                'temInteresse' => $interesse !== null,
                'interesse' => $interesse
            ]);
        } catch (\Exception $e) {
            // Loga o erro para debug
            Log::error('Erro ao verificar interesse: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao verificar interesse'
            ], 500);
        }
    }
}
