<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\Pelagem;
use App\Models\Porte;
use App\Models\StatusAnimal;

class OpcoesController extends Controller
{
    public function listarEspecies()
    {
        try {
            $especies = Especie::all();
            return response()->json([
                'success' => true,
                'data' => $especies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar espécies'
            ], 500);
        }
    }

    public function listarRacasPorEspecie($idEspecie)
    {
        try {
            $racas = Raca::where('idEspecie', $idEspecie)->get();
            return response()->json([
                'success' => true,
                'data' => $racas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar raças'
            ], 500);
        }
    }

    public function listarPelagens()
    {
        try {
            $pelagens = Pelagem::all();
            return response()->json([
                'success' => true,
                'data' => $pelagens
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar pelagens'
            ], 500);
        }
    }

    public function listarPortes()
    {
        try {
            $portes = Porte::all();
            return response()->json([
                'success' => true,
                'data' => $portes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar portes'
            ], 500);
        }
    }

    public function listarStatus()
    {
        try {
            $statuses = StatusAnimal::all();
            return response()->json([
                'success' => true,
                'data' => $statuses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar status'
            ], 500);
        }
    }
}