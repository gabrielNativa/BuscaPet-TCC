<?php

namespace App\Http\Controllers;

use App\Models\HistoricoAnimal;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HistoricoAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $historico = HistoricoAnimal::with(['animal'])->get();
        return response()->json([
            'success' => true,
            'data' => $historico
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // Formata a hora antes da validação, se necessário
        $horaFormatada = $this->formatarHora($request->horaHistoricoAnimal);
        $request->merge(['horaHistoricoAnimal' => $horaFormatada]);

        $validator = Validator::make($request->all(), [
            'dataHistoricoAnimal' => 'required|date',
            'horaHistoricoAnimal' => [
                'required',
                'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/' // Valida formato HH:MM (24h)
            ],
            'detalhesHistoricoAnimal' => 'required|string|max:300',
            'idAnimal' => 'required|integer|exists:tbanimal,idAnimal',
        ], [
            'horaHistoricoAnimal.regex' => 'O formato da hora deve ser HH:MM (24 horas)'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $historicoData = [
            'dataHistoricoAnimal' => $request->dataHistoricoAnimal,
            'horaHistoricoAnimal' => $request->horaHistoricoAnimal,
            'detalhesHistoricoAnimal' => $request->detalhesHistoricoAnimal,
            'idAnimal' => $request->idAnimal,
        ];

        $historico = HistoricoAnimal::create($historicoData);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Histórico cadastrado com sucesso',
            'data' => $historico->load('animal')
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erro ao cadastrar Histórico: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erro ao cadastrar Histórico',
            'error' => config('app.debug') ? $e->getMessage() : 'Ocorreu um erro interno'
        ], 500);
    }
}

/**
 * Formata a hora para o padrão HH:MM
 */
private function formatarHora($hora)
{
    // Se a hora já estiver no formato correto, retorna sem alteração
    if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $hora)) {
        return $hora;
    }

    // Tenta converter de outros formatos
    try {
        return date('H:i', strtotime($hora));
    } catch (\Exception $e) {
        return $hora; // Retorna original para ser pego pela validação
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $historico = HistoricoAnimal::with(['animal'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $historico
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Histórico não encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'dataHistoricoAnimal' => 'sometimes|date',
                'horaHistoricoAnimal' => 'sometimes|date_format:H:i',
                'detalhesHistoricoAnimal' => 'sometimes|string|max:300',
                'idAnimal' => 'sometimes|integer|exists:tbanimal,idAnimal',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $historico = HistoricoAnimal::findOrFail($id);
            $historico->update($request->all());
    
            DB::commit();
    
            $historico->load(['animal']);
    
            return response()->json([
                'success' => true,
                'message' => 'Histórico atualizado com sucesso',
                'data' => $historico,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar Histórico: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar Histórico',
                'error' => config('app.debug') ? $e->getMessage() : 'Ocorreu um erro interno'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $historico = HistoricoAnimal::findOrFail($id);
            $historico->delete();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Histórico removido com sucesso'
            ], 204);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao remover Histórico: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover Histórico',
                'error' => config('app.debug') ? $e->getMessage() : 'Ocorreu um erro interno'
            ], 500);
        }
    }
}