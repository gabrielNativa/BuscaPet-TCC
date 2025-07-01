<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DenunciaController extends Controller
{
    public function denunciar(Request $request, $postId)
    {
        $request->validate([
            'comentario_id' => 'required|exists:tbcomentario,idComentario',
            'motivo' => 'required|string|max:255'
        ]);
    
        try {
            $denuncia = Denuncia::create([
                'idComentario' => $request->comentario_id,
                'idUser' => Auth::id(),
                'motivoDenuncia' => $request->motivo,
                'statusDenuncia' => 'analise'
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Denúncia registrada com sucesso e em análise'
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar denúncia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listar()
    {
        $denuncias = Denuncia::with(['comentario', 'usuario'])
                        ->where('statusDenuncia', 'pendente')
                        ->get();

        return response()->json($denuncias);
    }
}