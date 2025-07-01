<?php

namespace App\Http\Controllers;

use App\Models\Raca;
use Illuminate\Http\Request;

class RacaController extends Controller
{
    public function index()
    {
        $racas = Raca::orderBy('nomeRaca', 'asc')->get();
        return view('races', compact('racas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomeRaca' => 'required|string|max:50|unique:tbraca,nomeRaca',
            'idEspecie' => 'required|integer|exists:tbespecie,idEspecie',
        ]);
    
        try {
            $raca = new Raca();
            $raca->nomeRaca = $request->nomeRaca;
            $raca->idEspecie = $request->idEspecie;
            $raca->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Raça cadastrada com sucesso!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar raça: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $raca = Raca::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $raca
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Raça não encontrada'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomeRaca' => 'required|string|max:50|unique:tbRaca,nomeRaca,'.$id.',idRaca'
        ]);

        try {
            $raca = Raca::findOrFail($id);
            $raca->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Raça atualizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar raça: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $raca = Raca::findOrFail($id);
            $raca->delete();
            return response()->json([
                'success' => true,
                'message' => 'Raça excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir raça: ' . $e->getMessage()
            ], 500);
        }
    }
}