<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReelsController extends Controller
{
    public function __construct()
    {
        \DB::enableQueryLog();
    }

    /**
     * Versão final do controller para upload de reels
     * Compatível com React Native/Expo
     */
     public function store(Request $request)
    {
        try {
            Log::info('Iniciando upload de reel');
            // Logar apenas campos que não sejam o arquivo binário para evitar logs muito grandes
            Log::info('Dados de texto recebidos: ' . json_encode($request->except(['video', 'thumbnail'])));
            
            // Para testes, definir um ID de usuário fixo (ou use Auth::id() se estiver autenticado)
            // Lembre-se de remover ou ajustar isso para produção
            $userId = Auth::id();  
            
            
            if (!$userId) {
                Log::error('Usuário não autenticado ao tentar publicar reel.');
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado.'
                ], 401);
            }
            // 1. Validação do vídeo (MUITO IMPORTANTE!)
            // Isso garante que o arquivo existe, é um vídeo e está dentro do tamanho permitido.
            // O 51200 é em KB (50MB)
            $request->validate([
                'video' => 'required|file|mimes:mp4,mov,avi,mkv|max:51200', // max: 50MB
                'title' => 'nullable|string|max:50',
                'description' => 'nullable|string|max:200',
                'pet_name' => 'nullable|string|max:30',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // max: 10MB
            ]);

            // 2. Obter o arquivo de vídeo
            $videoFile = $request->file('video');

            // Verificar se o arquivo de vídeo foi realmente enviado e é válido
            if (!$videoFile || !$videoFile->isValid()) {
                Log::error('Arquivo de vídeo não encontrado ou inválido após validação. Provavelmente, o upload falhou na configuração do PHP.');
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo de vídeo inválido ou falha no upload. Verifique as configurações de upload do PHP (php.ini).'
                ], 400);
            }

            // Criar diretórios se não existirem
            $videosDir = public_path('reels/videos');
            if (!File::exists($videosDir)) {
                File::makeDirectory($videosDir, 0755, true, true); 
            }

            // Gerar nome único para o vídeo e mover
            $videoExtension = $videoFile->getClientOriginalExtension();
            $videoName = time() . '_' . Str::random(10) . '.' . $videoExtension;
            $videoPath = 'reels/videos/' . $videoName;
            $videoFullPath = public_path($videoPath);

            // Tentar mover o arquivo. Se falhar, é problema de permissão ou espaço.
            if (!$videoFile->move($videosDir, $videoName)) {
                Log::error('Falha ao mover arquivo de vídeo para: ' . $videoFullPath . '. Verifique as permissões do diretório.');
                return response()->json([
                    'success' => false,
                    'message' => 'Falha ao salvar o vídeo no servidor. Verifique as permissões de pasta.'
                ], 500);
            }
            
            Log::info('Vídeo salvo com sucesso: ' . $videoPath . ' (' . File::size($videoFullPath) . ' bytes)');

            // Lógica para a thumbnail (opcional)
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailFile = $request->file('thumbnail');
                $thumbnailsDir = public_path('reels/thumbnails');
                if (!File::exists($thumbnailsDir)) {
                    File::makeDirectory($thumbnailsDir, 0755, true, true);
                }
                $thumbnailExtension = $thumbnailFile->getClientOriginalExtension();
                $thumbnailName = time() . '_thumb_' . Str::random(10) . '.' . $thumbnailExtension;
                $thumbnailPath = 'reels/thumbnails/' . $thumbnailName;
                $thumbnailFullPath = public_path($thumbnailPath);

                if (!$thumbnailFile->move($thumbnailsDir, $thumbnailName)) {
                    Log::warning('Falha ao mover arquivo de thumbnail para: ' . $thumbnailFullPath);
                    $thumbnailPath = null; // Resetar se falhar
                } else {
                    Log::info('Thumbnail salva com sucesso: ' . $thumbnailPath);
                }
            }


            // Criar reel no banco
            $reel = new Reel();
            $reel->idUser = $userId; 
            $reel->tituloReels = $request->input('title', 'Sem título');
            $reel->descricaoReels = $request->input('description', 'Sem descrição');
            $reel->video_urlReels = $videoPath;
            $reel->thumbnail_urlReels = $thumbnailPath; 
            $reel->pet_nomeReels = $request->input('pet_name', '');
            $reel->duracaoReels = '0:30'; 
            $reel->visualizacoesReels = 0;
            $reel->likesReels = 0;
            $reel->comentarios_countReels = 0;
            $reel->ativo = true;
            $reel->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Reel publicado com sucesso',
                'data' => [
                    'id' => $reel->idReels,
                    'video_url' => asset($reel->video_urlReels),
                    'thumbnail_url' => asset($reel->thumbnail_urlReels)
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar erros de validação específicos para uma resposta mais amigável
            Log::error('Erro de validação ao publicar reel: ' . $e->getMessage());
            Log::error('Detalhes da validação: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); 
        } catch (\Exception $e) {
            Log::error('Erro ao publicar reel: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao publicar reel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar todos os reels
     */
   public function index()
{
    try {
        $reels = Reel::with('user:idUser,nomeUser,imgUser')
            ->withCount(['likes', 'comments'])
            ->where('ativo', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedReels = [];
        $user = auth('sanctum')->user();
        $userId = $user?->idUser;

        foreach ($reels as $reel) {
            $user = $reel->user;
            
            $formattedReels[] = [
                'id' => $reel->idReels,
                'idUser' => $user ? $user->idUser : null, // ← ADICIONE ESTA LINHA
                'userName' => $user ? $user->nomeUser : 'Usuário',
                'userAvatar' => $user && $user->imgUser ? asset($user->imgUser) : null,
                'petName' => $reel->pet_nomeReels,
                'title' => $reel->tituloReels,
                'description' => $reel->descricaoReels,
                'videoUrl' => asset($reel->video_urlReels),
                'videoThumbnail' => $reel->thumbnail_urlReels ? asset($reel->thumbnail_urlReels) : null,
                'likes' => $reel->likesReels,
                'comments' => $reel->comments_count,
                'views' => $reel->visualizacoesReels,
                'duration' => $reel->duracaoReels,
                'created_at' => $reel->created_at->format('Y-m-d H:i:s'),
                'is_liked' => $userId ? $this->isLikedByUser($reel->idReels, $userId) : false,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $formattedReels
        ]);

    } catch (\Exception $e) {
        Log::error('Erro ao listar reels: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erro ao listar reels: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Exibir um reel específico
     */
    public function show($id)
    {
        try {
            $reel = Reel::with('user')->find($id);
            
            if (!$reel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reel não encontrado'
                ], 404);
            }
            
            // Incrementar visualizações
            $reel->visualizacoesReels += 1;
            $reel->save();
            
            $user = $reel->user;
            
            $data = [
                'id' => $reel->idReels,
                'userName' => $user ? $user->nomeUser : 'Usuário',
                'userAvatar' => $user && $user->fotoUser ? asset($user->fotoUser) : null,
                'petName' => $reel->pet_nomeReels,
                'title' => $reel->tituloReels,
                'description' => $reel->descricaoReels,
                'videoUrl' => asset($reel->video_urlReels),
                'videoThumbnail' => $reel->thumbnail_urlReels ? asset($reel->thumbnail_urlReels) : null,
                'likes' => $reel->likesReels,
                'comments' => $reel->comentarios_countReels,
                'views' => $reel->visualizacoesReels,
                'duration' => $reel->duracaoReels,
                'created_at' => $reel->created_at->format('Y-m-d H:i:s'),
                'is_liked' => Auth::check() ? $this->isLikedByUser($reel->idReels, Auth::id()) : false
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar reel: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar reel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Curtir/descurtir um reel
     */
    public function like($id)
    {
        try {
            // Verificar autenticação
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $user = Auth::user();
            $reel = Reel::findOrFail($id);
            
            // Verificar se já curtiu
            $isLiked = $this->isLikedByUser($id, $user->idUser);
            
            if ($isLiked) {
                // Remover curtida
                \DB::table('tbreel_likes')
                    ->where('idReels', $id)
                    ->where('idUser', $user->idUser)
                    ->delete();
                
                // Atualizar contador
                $reel->likesReels = max(0, $reel->likesReels - 1);
                $reel->save();
                
                $liked = false;
            } else {
                // Adicionar curtida
                \DB::table('tbreel_likes')->insert([
                    'idReels' => $id,
                    'idUser' => $user->idUser,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Atualizar contador
                $reel->likesReels += 1;
                $reel->save();
                
                $liked = true;
            }
            
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes' => $reel->likesReels
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao processar curtida: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar curtida: ' . $e->getMessage()
            ], 500);
        }
    }
public function deleteComment($reelId, $commentId)
{
    try {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        $userId = Auth::id();

        // Verifica se o comentário existe e pertence ao usuário
        $comment = \DB::table('tbreel_comentarios')
            ->where('idreel_comentarios', $commentId)
            ->where('idReels', $reelId)
            ->where('idUser', $userId)
            ->first();

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comentário não encontrado ou não pertence ao usuário.'
            ], 404);
        }

        // Exclui o comentário
        \DB::table('tbreel_comentarios')->where('idreel_comentarios', $commentId)->delete();

        // Decrementa o contador
        \DB::table('tbreels')->where('idReels', $reelId)->decrement('comentarios_countReels');

        return response()->json([
            'success' => true,
            'message' => 'Comentário excluído com sucesso.'
        ]);

    } catch (\Exception $e) {
        \Log::error('Erro ao excluir comentário: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erro interno ao excluir comentário.'
        ], 500);
    }
}

    /**
     * Comentar em um reel
     */
    public function comment(Request $request, $id)
    {
        try {
            // Validar dados
            if (!$request->has('comment') || trim($request->input('comment')) === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Comentário não pode ser vazio'
                ], 422);
            }

            // Verificar autenticação
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $user = Auth::user();
            $reel = Reel::findOrFail($id);
            
            // Criar comentário
            $commentId = \DB::table('tbreel_comentarios')->insertGetId([
                'idReels' => $id,
                'idUser' => $user->idUser,
                'comentario' => $request->input('comment'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Atualizar contador
            $reel->comentarios_countReels += 1;
            $reel->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Comentário adicionado com sucesso',
                'data' => [
                    'idComentario' => $commentId,
                    'texto' => $request->input('comment'),
                    'nomeUser' => $user->nomeUser,
                    'imgUser' => $user->imgUser ? asset($user->imgUser) : null,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar comentário: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar comentário: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar comentários de um reel
     */
    public function getComments($id)
    {
        try {
            Log::info('Buscando comentários para o reel: ' . $id);
            
            $reel = Reel::findOrFail($id);
            
            $comments = \DB::table('tbreel_comentarios')
                ->join('tbuser', 'tbreel_comentarios.idUser', '=', 'tbuser.idUser')
                ->where('tbreel_comentarios.idReels', $id)
                ->select(
                    'tbreel_comentarios.idreel_comentarios',
                    'tbreel_comentarios.comentario as texto',
                    'tbreel_comentarios.created_at',
                    'tbuser.idUser',
                    'tbuser.nomeUser',
                    'tbuser.imgUser'
                )
                ->orderBy('tbreel_comentarios.created_at', 'desc')
                ->get();
                $userId = Auth::id();


            
            Log::info('Comentários encontrados: ' . $comments->count());
            Log::info('Dados dos comentários: ' . json_encode($comments));
          $formattedComments = $comments->map(function ($comment) use ($userId) {
                return [
                    'idComentario' => $comment->idreel_comentarios,
                    'texto' => $comment->texto,
                    'nomeUser' => $comment->nomeUser,
                    'imgUser' => $comment->imgUser ? asset($comment->imgUser) : null,
                    'created_at' => date('Y-m-d H:i:s', strtotime($comment->created_at)),
                    'isMine' => $comment->idUser == $userId // ✅ AQUI!
                ];
            });

            return response()->json([
                'success' => true,
                'comments' => $formattedComments
            ]);
        } catch (\Exception $e) {
    Log::error('Erro ao buscar comentários: ' . $e->getMessage());
    Log::error('Stack trace: ' . $e->getTraceAsString());
    // Correção aqui: Use json_encode para o log da query
    Log::error('Query SQL: ' . json_encode(\DB::getQueryLog())); 
    return response()->json([
        'success' => false,
        'message' => 'Erro ao buscar comentários: ' . $e->getMessage()
    ], 500);
}
    }
public function getReelsByUser($id)
{
    try {
        $reels = Reel::with('user:idUser,nomeUser,imgUser')
            ->withCount(['likes', 'comments'])
            ->where('idUser', $id)
            ->where('ativo', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $user = auth('sanctum')->user();
        $userId = $user?->idUser;

        return response()->json([
            'success' => true,
            'data' => $reels->map(function ($reel) use ($userId) {
                $user = $reel->user;
                
                return [
                    'idReels' => $reel->idReels, // Mantive o nome do campo igual na versão que funciona
                    'video_urlReels' => asset($reel->video_urlReels), // Mantive o nome do campo
                    'thumbnail_urlReels' => $reel->thumbnail_urlReels ? asset($reel->thumbnail_urlReels) : null,
                    'tituloReels' => $reel->tituloReels,
                    'created_at' => $reel->created_at->format('d/m/Y H:i'), // Mantive o formato da versão que funciona
                    
                    // Informações adicionais
                    'idUser' => $user ? $user->idUser : null,
                    'userName' => $user ? $user->nomeUser : 'Usuário',
                    'userAvatar' => $user && $user->imgUser ? asset($user->imgUser) : null,
                    'petName' => $reel->pet_nomeReels,
                    'description' => $reel->descricaoReels,
                    'likes' => $reel->likes_count, // Assumindo que withCount cria likes_count
                    'comments' => $reel->comments_count, // Assumindo que withCount cria comments_count
                    'views' => $reel->visualizacoesReels,
                    'duration' => $reel->duracaoReels,
                    'is_liked' => $userId ? $this->isLikedByUser($reel->idReels, $userId) : false,
                ];
            })
        ]);

    } catch (\Exception $e) {
        Log::error('Erro ao buscar reels do usuário: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar reels'
        ], 500);
    }
}
public function buscarReels(Request $request)
{
    try {
        $termo = $request->query('termo');

        if (empty($termo)) {
            return response()->json([
                'success' => false,
                'message' => 'Termo de busca não fornecido'
            ], 400);
        }

        // Prepara o termo: minúsculas, sem acentos, separa por palavras
        $termo = strtolower($this->removerAcentos($termo));
        $palavras = explode(' ', $termo);

        $reels = Reel::with('user')
            ->where('ativo', true)
            ->where(function ($query) use ($palavras) {
                $query->where(function ($subquery) use ($palavras) {
                    foreach ($palavras as $palavra) {
                        $subquery->orWhere(function ($sub) use ($palavra) {
                            $sub->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(tituloReels, "á", "a"), "à", "a"), "ã", "a"), "â", "a"), "é", "e"), "ê", "e"), "í", "i"), "ó", "o"), "ô", "o"), "õ", "o"), "ú", "u"), "ç", "c")) LIKE ?', ["%{$palavra}%"])
                                ->orWhereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(descricaoReels, "á", "a"), "à", "a"), "ã", "a"), "â", "a"), "é", "e"), "ê", "e"), "í", "i"), "ó", "o"), "ô", "o"), "õ", "o"), "ú", "u"), "ç", "c")) LIKE ?', ["%{$palavra}%"])
                                ->orWhereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(pet_nomeReels, "á", "a"), "à", "a"), "ã", "a"), "â", "a"), "é", "e"), "ê", "e"), "í", "i"), "ó", "o"), "ô", "o"), "õ", "o"), "ú", "u"), "ç", "c")) LIKE ?', ["%{$palavra}%"]);
                        });
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $user = auth('sanctum')->user();
        $userId = $user?->idUser;

        $formattedReels = $reels->map(function ($reel) use ($userId) {
            $user = $reel->user;

            return [
                'id' => $reel->idReels,
                'userName' => $user ? $user->nomeUser : 'Usuário',
                'userAvatar' => $user && $user->imgUser ? asset($user->imgUser) : null,
                'petName' => $reel->pet_nomeReels,
                'title' => $reel->tituloReels,
                'description' => $reel->descricaoReels,
                'videoUrl' => asset($reel->video_urlReels),
                'videoThumbnail' => $reel->thumbnail_urlReels ? asset($reel->thumbnail_urlReels) : null,
                'likes' => $reel->likesReels,
                'comments' => $reel->comentarios_countReels,
                'views' => $reel->visualizacoesReels,
                'duration' => $reel->duracaoReels,
                'created_at' => $reel->created_at->format('Y-m-d H:i:s'),
                'is_liked' => $userId ? $this->isLikedByUser($reel->idReels, $userId) : false,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedReels
        ]);

    } catch (\Exception $e) {
        Log::error('Erro ao buscar reels: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar reels: ' . $e->getMessage()
        ], 500);
    }
}


private function removerAcentos($string)
{
    $acentos = ['á','à','ã','â','ä','é','è','ê','ë','í','ì','î','ï','ó','ò','õ','ô','ö','ú','ù','û','ü','ç'];
    $sem_acentos = ['a','a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','c'];
    return str_replace($acentos, $sem_acentos, $string);
}


    /**
     * Verificar status de curtida
     */
    public function checkLikeStatus($id)
    {
        try {
            $reel = Reel::findOrFail($id);
            $isLiked = false;
            
            if (Auth::check()) {
                $isLiked = $this->isLikedByUser($id, Auth::id());
            }
            
            return response()->json([
                'success' => true,
                'is_liked' => $isLiked,
                'likes_count' => $reel->likesReels
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status de curtida: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar status de curtida: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Verificar se um reel foi curtido por um usuário
     */
    private function isLikedByUser($reelId, $userId)
    {
        return \DB::table('tbreel_likes')
            ->where('idReels', $reelId)
            ->where('idUser', $userId)
            ->exists();
    }
}
