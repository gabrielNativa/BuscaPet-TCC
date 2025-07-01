<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Interesse;
use App\Models\Animal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ongId = auth()->guard('ong')->user()->idOng;
    
        $totalLikes = Post::where('idOng', $ongId)->sum('likes');
    
        $animaisAdocao = Animal::where('idOng', $ongId)
            ->where('idStatusAnimal', 3)
            ->count();
    
        $animaisAdotados = Animal::where('idOng', $ongId)
            ->where('idStatusAnimal', 4)
            ->count();
    
        $animaisAnalise = Animal::where('idOng', $ongId)
            ->where('idStatusAnimal', 5)
            ->count();
    
        $categoriesWithCount = Post::where('idOng', $ongId)
            ->select('idCategoriaPosts', \DB::raw('count(*) as count'))
            ->groupBy('idCategoriaPosts')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $this->getCategoryName($item->idCategoriaPosts),
                    'count' => $item->count,
                    'color' => $this->getCategoryColor($item->idCategoriaPosts)
                ];
            });

        // Limitar a 5 categorias principais e agrupar o resto como "Outros"
        if ($categoriesWithCount->count() > 5) {
            $topCategories = $categoriesWithCount->take(5);
            $othersCount = $categoriesWithCount->skip(5)->sum('count');
            
            if ($othersCount > 0) {
                $topCategories = $topCategories->push([
                    'name' => 'Outros',
                    'count' => $othersCount,
                    'color' => '#64748b'
                ]);
            }
            
            $categoriesWithCount = $topCategories;
        }
    
        // Obter todos os interesses
        $interesses = Interesse::with(['animal.raca', 'usuario'])
            ->whereHas('animal', function($query) use ($ongId) {
                $query->where('idOng', $ongId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.dashboard', [
            'totalLikes' => $totalLikes,
            'animaisAdocao' => $animaisAdocao,
            'animaisAdotados' => $animaisAdotados,
            'animaisAnalise' => $animaisAnalise,
            'categoriesWithCount' => $categoriesWithCount,
            'interesses' => $interesses,
        ]);
    }

    private function getCategoryName($categoryId)
    {
        $categories = [
            1 => 'Castração',
            2 => 'Adoção Responsável',
            3 => 'Arrecadação',
            4 => 'Combate a Maus-Tratos',
            5 => 'Saúde e Bem-Estar',
            6 => 'Políticas Públicas',
            7 => 'Animais em Situação de Rua'
        ];

        return $categories[$categoryId] ?? 'Outros';
    }

    private function getCategoryColor($categoryId)
    {
        $colors = [
            1 => '#3b82f6', // Azul
            2 => '#10b981', // Verde
            3 => '#f59e0b', // Amarelo
            4 => '#ef4444', // Vermelho
            5 => '#8b5cf6', // Roxo
            6 => '#ec4899', // Rosa
            7 => '#14b8a6'  // Ciano
        ];

        return $colors[$categoryId] ?? '#64748b'; // Cinza padrão
    }
}