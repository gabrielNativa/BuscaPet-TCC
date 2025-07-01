<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Ong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AnimalAdocaoController extends Controller
{
    public function index()
    {
        try {
            $statusAdocao = 3;
            
            $animais = Animal::with([
                            'raca', 
                            'porte', 
                            'especie', 
                            'pelagem',
                            'status',
                            'imagensAnimal',
                            'ong' => function($query) {
                                $query->where('status', 'aprovado');
                            }
                        ])
                        ->where('idStatusAnimal', $statusAdocao)
                        ->whereHas('ong', function($query) {
                            $query->where('status', 'aprovado');
                        })
                        ->get()
                        ->map(function ($animal) {
                            // Formata as imagens adicionais
                            $imagensAdicionais = [];
                            
                            if ($animal->imagensAnimal) {
                                for ($i = 1; $i <= 4; $i++) {
                                    $campo = "img{$i}Animal";
                                    if (!empty($animal->imagensAnimal->$campo)) {
                                        $imagensAdicionais[] = asset('img/imgAnimal/' . $animal->imagensAnimal->$campo);
                                    }
                                }
                            }
                            
                            return [
                                'id' => $animal->idAnimal,
                                'nome' => $animal->nomeAnimal,
                                'idade' => $animal->idadeAnimal,
                                'bio' => $animal->bioAnimal,
                                'imagem_principal' => $animal->imgPrincipal 
                                    ? asset('img/imgAnimal/' . $animal->imgPrincipal)
                                    : null,
                                'imagens_adicionais' => $imagensAdicionais,
                                
                                // Dados dos relacionamentos
                                'raca' => $animal->raca ? [
                                    'idRaca' => $animal->raca->idRaca,
                                    'nomeRaca' => $animal->raca->nomeRaca
                                ] : null,
                                
                                'porte' => $animal->porte ? [
                                    'idPorte' => $animal->porte->idPorte,
                                    'nomePorte' => $animal->porte->nomePorte
                                ] : null,
                                
                                'especie' => $animal->especie ? [
                                    'idEspecie' => $animal->especie->idEspecie,
                                    'nomeEspecie' => $animal->especie->nomeEspecie
                                ] : null,
                                
                                'pelagem' => $animal->pelagem ? [
                                    'idPelagemAnimal' => $animal->pelagem->idPelagemAnimal,
                                    'pelagemAnimal' => $animal->pelagem->pelagemAnimal
                                ] : null,
                                
                                'ong' => $animal->ong ? [
                                    'idOng' => $animal->ong->idOng,
                                    'nomeOng' => $animal->ong->nomeOng,
                                    'telOng' => $animal->ong->telOng,
                                    'lograOng' => $animal->ong->lograOng,
                                    'ufOng' => $animal->ong->ufOng
                                ] : null,
                                
                                'status' => $animal->status ? [
                                    'idStatusAnimal' => $animal->status->idStatusAnimal,
                                    'statusAnimal' => $animal->status->statusAnimal
                                ] : null
                            ];
                        });
    
            return response()->json([
                'success' => true,
                'data' => $animais,
                'message' => 'Animais para adoção recuperados com sucesso'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar animais para adoção',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function pesquisarAnimais(Request $request)
    {
        try {
            DB::enableQueryLog();
            
            $query = Animal::with([
                'especie:idEspecie,nomeEspecie',
                'raca:idRaca,nomeRaca',
                'status:idStatusAnimal,descStatusAnimal',
                'ong:idOng,nomeOng,lograOng,telOng',
                'porte', 
                'pelagem', 
                'imagensAnimal' // Garantir que imagensAnimal está sendo carregado
            ])->where('idStatusAnimal', 3);
    
            if ($request->search) {
                $search = strtolower($request->search);
                
                $searches = [$search];

                if(str_ends_with($search, 's')){
                    $searches[] =rtrim($search, 's');
                }

                if(str_ends_with($search, 'ões')){
                    $searches[] =rtrim($search, 'ão');
                }elseif(str_ends_with($search, 'ais')){
                    $searches[] =substr($search, 0, -3) . 'al';
                }

                $query->where(function($q) use ($searches) {
                    foreach($searches as $term) {
                    $q->where(DB::raw('LOWER(nomeAnimal)'), 'LIKE', "%$term%")
                    ->orWhere(DB::raw('LOWER(bioAnimal)'), 'LIKE', "%$term%")
                    ->orWhereHas('especie', function($q) use ($term) {
                        $q->where(DB::raw('LOWER(nomeEspecie)'), 'LIKE', "%$term%");
                    })
                    ->orWhereHas('raca', function($q) use ($term) {
                        $q->where(DB::raw('LOWER(nomeRaca)'), 'LIKE', "%$term%");
                 
                      });
                    }
                });
            }
    
            $resultados = $query->get()->map(function ($animal) {
                $imagensAdicionais = [];
            
                // Verifica se a relação imagensAnimal foi carregada e não é null
                if ($animal->relationLoaded('imagensAnimal') && $animal->imagensAnimal) {
                    for ($i = 1; $i <= 4; $i++) {
                        $campo = "img{$i}Animal";
                        if (!empty($animal->imagensAnimal->$campo)) {
                            $imagensAdicionais[] = asset('img/imgAnimal/' . $animal->imagensAnimal->$campo);
                        }
                    }
                }
            
                return [
                    'id' => $animal->idAnimal,
                    'nome' => $animal->nomeAnimal,
                    'idade' => $animal->idadeAnimal,
                    'bio' => $animal->bioAnimal,
                    'porte' => $animal->porteAnimal, 
                    'pelagem' => $animal->pelagemAnimal, 
                    'imagem_principal' => $animal->imgPrincipal 
                        ? asset('img/imgAnimal/' . $animal->imgPrincipal)
                        : null,
                    'imagens_adicionais' => $imagensAdicionais,
            
                    'raca' => $animal->raca ? [
                        'idRaca' => $animal->raca->idRaca,
                        'nomeRaca' => $animal->raca->nomeRaca
                    ] : null,
            
                    'especie' => $animal->especie ? [
                        'idEspecie' => $animal->especie->idEspecie,
                        'nomeEspecie' => $animal->especie->nomeEspecie
                    ] : null,
            
                    'status' => $animal->status ? [
                        'idStatusAnimal' => $animal->status->idStatusAnimal,
                        'descStatusAnimal' => $animal->status->descStatusAnimal
                    ] : null,
                    'pelagem' => $animal->pelagem ? [
                        'idPelagemAnimal' => $animal->pelagem->idPelagemAnimal,
                        'pelagemAnimal' => $animal->pelagem->pelagemAnimal
                    ] : null,
                                
                    'porte' => $animal->porte ? [
                        'idPorte' => $animal->porte->idPorte,
                        'nomePorte' => $animal->porte->nomePorte
                    ] : null,
            
                    
                    'ong' => $animal->ong ? [
                        'idOng' => $animal->ong->idOng,
                        'nomeOng' => $animal->ong->nomeOng,
                        'telOng' => $animal->ong->telOng,
                        'lograOng' => $animal->ong->lograOng,
                        'ufOng' => $animal->ong->ufOng
                    ] : null,
                ];
            });
    
            Log::info('Query Executada:', DB::getQueryLog());
    
            return response()->json([
                'success' => true,
                'data' => $resultados,
                'count' => $resultados->count()
            ]);
    
        } catch (\Exception $e) {
            Log::error('ERRO: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erro na pesquisa',
                'exception' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    
       private function filtrarAnimais(Request $request)
{
    $query = Animal::with(['especie', 'raca', 'porte', 'pelagem', 'status', 'ong'])
        ->orderBy('idAnimal', 'desc');

    // Filtro por status
    if ($request->has('status')) {
        $query->where('idStatusAnimal', $request->status);
    }

    // Filtro por espécie
    if ($request->has('idEspecie')) {
        $query->where('idEspecie', $request->idEspecie);
    }

    // Filtro por texto
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('nomeAnimal', 'like', "%{$search}%")
              ->orWhere('bioAnimal', 'like', "%{$search}%")
              ->orWhereHas('especie', function($q) use ($search) {
                  $q->where('nomeEspecie', 'like', "%{$search}%");
              })
              ->orWhereHas('raca', function($q) use ($search) {
                  $q->where('nomeRaca', 'like', "%{$search}%");
              })
              ->orWhereHas('ong', function($q) use ($search) {
                  $q->where('nomeOng', 'like', "%{$search}%")
                    ->orWhere('lograOng', 'like', "%{$search}%");
              });
        });
    }

    return $query;
}
}