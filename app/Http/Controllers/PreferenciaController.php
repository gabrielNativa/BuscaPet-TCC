<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Especie;
use App\Models\Porte;
use App\Models\TbpreferenciasUsuario;

class PreferenciaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:tbuser,idUser',
            'especie_id' => 'nullable|exists:tbespecie,idEspecie',
            'portes' => 'nullable|array',
        ]);

        $preferencia = TbpreferenciasUsuario::updateOrCreate(
            ['idUser' => $request->user_id],
            [
                'especie_preferida' => $request->especie_id,
                'porte_preferido' => $request->portes
            ]
        );

        $preferencia->load('especie');

        return response()->json([
            'success' => true,
            'message' => 'Preferências salvas com sucesso',
            'data' => $preferencia
        ]);
    }

    public function show($userId)
    {
        $preferencia = TbpreferenciasUsuario::with('especie')
            ->where('idUser', $userId)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $preferencia ?: null
        ]);
    }
}
