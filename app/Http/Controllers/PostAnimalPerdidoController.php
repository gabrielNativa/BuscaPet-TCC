<?php

namespace App\Http\Controllers;

use App\Models\PostAnimalPerdido;
use App\Models\HistoricoAnimal;
use App\Models\Animal;
use App\Models\ImagensAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostAnimalPerdidoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = PostAnimalPerdido::with([
                'animal.raca',
                'animal.especie',
                'animal.pelagem',
                'animal.porte',
                'animal.status',
                'animal.imagensAnimal',
                'usuario' => function($query) {
                    $query->select([
                        'idUser', 'nomeUser', 'emailUser', 
                        'telUser', 'imgUser', 'cidadeUser', 'ufUser'
                    ]);
                },
                'animal.historicoAnimal' => function($query) {
                    $query->orderBy('dataHistoricoAnimal', 'desc')
                          ->orderBy('horaHistoricoAnimal', 'desc')
                          ->select(['idHistoricoAnimal', 'dataHistoricoAnimal', 
                                  'horaHistoricoAnimal', 'detalhesHistoricoAnimal', 
                                  'idAnimal']);
                }
            ]);
    
            // Filtros
            if ($request->filled('idAnimal')) {
                $query->where('idAnimal', $request->idAnimal);
            }
    
            if ($request->filled('idUser')) {
                $query->where('idUser', $request->idUser);
            }
    
            if ($request->filled('status')) {
                $query->whereHas('animal', function($q) use ($request) {
                    $q->where('idStatusAnimal', $request->status);
                });
            }
    
            if ($request->filled('data_inicio')) {
                $query->whereHas('animal.historicoAnimal', function($q) use ($request) {
                    $q->where('dataHistoricoAnimal', '>=', $request->data_inicio);
                });
            }
    
            if ($request->filled('data_fim')) {
                $query->whereHas('animal.historicoAnimal', function($q) use ($request) {
                    $q->where('dataHistoricoAnimal', '<=', $request->data_fim);
                });
            }
    
            if ($request->filled('cidade')) {
                $query->whereHas('usuario', function($q) use ($request) {
                    $q->where('cidadeUser', 'like', '%'.$request->cidade.'%');
                });
            }
    
            // Paginação e ordenação
            $posts = $query->orderBy('idPostAnimalPerdido', 'desc')
                         ->paginate($request->per_page ?? 10);
    
            // Formatação dos dados
            $posts->getCollection()->transform(function($post) {
                return $this->formatarPost($post);
            });
    
            return response()->json([
                'success' => true,
                'data' => $posts,
                'message' => 'Posts recuperados com sucesso'
            ]);
    
        } catch (\Exception $e) {
            Log::error('Erro ao listar posts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar posts',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno'
            ], 500);
        }
    }
    
    private function formatarPost($post)
    {
        // Remover dados sensíveis do usuário
        unset($post->usuario->senhaUser);
        unset($post->usuario->cpfUser);
        unset($post->usuario->dataNascUser);
    
        // Formatar URLs das imagens
        if ($post->animal) {
            if ($post->animal->imgPrincipal) {
                $post->animal->imgPrincipal_url = asset($post->animal->imgPrincipal);
            }
    
            if ($post->animal->imagensAnimal) {
                $imagens = $post->animal->imagensAnimal;
                foreach (['img1Animal', 'img2Animal', 'img3Animal', 'img4Animal'] as $campo) {
                    if (!empty($imagens->$campo)) {
                        $imagens->{$campo . '_url'} = asset($imagens->$campo);
                    }
                }
            }
        }
    
        // Adicionar campo de localização combinada
        if ($post->usuario) {
            $post->localizacao = $post->usuario->cidadeUser . ', ' . $post->usuario->ufUser;
        }
    
        return $post;
    }
    /**
     * Criar um novo post de animal perdido
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'idAnimal' => 'required|exists:tbanimal,idAnimal',
                'idUser' => 'required|exists:tbuser,idUser',
                'img1Animal' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'img2Animal' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'img3Animal' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'img4Animal' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Busca o animal com a imgPrincipal já existente
            $animal = Animal::findOrFail($request->idAnimal);

            // Atualiza status para "Perdido"
            $animal->update(['idStatusAnimal' => 1]);

            // Cria/atualiza as imagens adicionais
            $imagensAnimal = $this->salvarImagensAdicionais($animal->idAnimal, $request);

            // Registro no histórico


            // Cria o post com a estrutura correta da tabela
            $post = PostAnimalPerdido::create([
                'idAnimal' => $animal->idAnimal,
                'idUser' => $request->idUser
                // Não inclui outros campos que não existem na tabela
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Post criado com sucesso',
                'data' => [
                    'post' => $post,
                    'animal' => $animal,
                    'imagens' => $imagensAnimal,
                   
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Salva as imagens adicionais
     */
    private function salvarImagensAdicionais($idAnimal, Request $request)
    {
        $imagensAnimal = ImagensAnimal::where('idAnimal', $idAnimal)->first();

        if (!$imagensAnimal) {
            $imagensAnimal = new ImagensAnimal();
            $imagensAnimal->idAnimal = $idAnimal;
        }

        for ($i = 1; $i <= 4; $i++) {
            $campo = 'img' . $i . 'Animal';

            if ($request->hasFile($campo)) {
                // Remove imagem antiga se existir
                if ($imagensAnimal->$campo && file_exists(public_path($imagensAnimal->$campo))) {
                    unlink(public_path($imagensAnimal->$campo));
                }

                // Salva a nova imagem
                $image = $request->file($campo);
                $imageName = time() . '_' . $i . '_' . $image->getClientOriginalName();
                $image->move(public_path('img'), $imageName);
                $imagensAnimal->$campo = 'img/' . $imageName;
            }
        }

        $imagensAnimal->save();
        return $imagensAnimal;
    }
}
