<?php

namespace App\Http\Controllers;

use App\Models\PostAnimalPerdido;
use App\Models\HistoricoAnimal;
use Illuminate\Http\Request;

class PostAnimalPerdidoController extends Controller
{
    // Listar todos os posts
    public function index()
    {
        return response()->json(PostAnimalPerdido::with(['animal', 'usuario'])->get());
    }

    // Mostrar um post específico
    public function show($id)
    {
        $post = PostAnimalPerdido::with(['animal', 'usuario', 'historico'])->findOrFail($id);
        return response()->json($post);
    }

    // Criar novo post
    public function store(Request $request)
    {
        $request->validate([
            'idAnimal' => 'required|exists:tbanimal,idAnimal',
            'idUser' => 'required|exists:tbuser,idUser',
            'dataDesaparecimento' => 'required|date',
            'localDesaparecimento' => 'required|string|max:255',
            'detalhes' => 'nullable|string'
        ]);

        $post = PostAnimalPerdido::create($request->all());
        
        return response()->json([
            'message' => 'Post criado com sucesso',
            'data' => $post
        ], 201);
    }

    // Atualizar post
    public function update(Request $request, $id)
    {
        $post = PostAnimalPerdido::findOrFail($id);
        
        $request->validate([
            'dataDesaparecimento' => 'sometimes|date',
            'localDesaparecimento' => 'sometimes|string|max:255',
            'detalhes' => 'nullable|string'
        ]);

        $post->update($request->all());
        
        return response()->json([
            'message' => 'Post atualizado com sucesso',
            'data' => $post
        ]);
    }

    // Remover post
    public function destroy($id)
    {
        $post = PostAnimalPerdido::findOrFail($id);
        $post->delete();
        
        return response()->json(['message' => 'Post removido com sucesso'], 204);
    }

    // Marcar como encontrado
    public function marcarEncontrado($id)
    {
        $post = PostAnimalPerdido::findOrFail($id);
        
        // Atualiza o status do animal para "Encontrado" (idStatusAnimal = 2)
        $post->animal()->update(['idStatusAnimal' => 2]);
        
        return response()->json(['message' => 'Animal marcado como encontrado']);
    }

    // Ver histórico do animal
    public function historico($id)
    {
        $post = PostAnimalPerdido::findOrFail($id);
        return response()->json($post->animal->historicos);
    }

    // Adicionar registro ao histórico
    public function adicionarHistorico(Request $request, $id)
    {
        $post = PostAnimalPerdido::findOrFail($id);
        
        $request->validate([
            'detalhesHistoricoAnimal' => 'required|string|max:300'
        ]);

        $historico = HistoricoAnimal::create([
            'idAnimal' => $post->idAnimal,
            'dataHistoricoAnimal' => now()->format('Y-m-d'),
            'horaHistoricoAnimal' => now()->format('H:i:s'),
            'detalhesHistoricoAnimal' => $request->detalhesHistoricoAnimal
        ]);

        return response()->json($historico, 201);
    }
}