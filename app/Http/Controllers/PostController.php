<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['ong', 'categoria'])
            ->withCount(['likes', 'comments'])
            ->where('idOng', Auth::guard('ong')->id())
            ->latest()
            ->get();

        return view('layouts.posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6048',
            'idCategoriaPosts' => 'required|exists:tbcategoriaposts,idCategoriaPosts'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            if (!File::exists(public_path('img/posts'))) {
                File::makeDirectory(public_path('img/posts'), 0755, true);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img/posts'), $imageName);
            $imagePath = 'img/posts/' . $imageName;
        }

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'idOng' => Auth::guard('ong')->id(),
            'idCategoriaPosts' => $request->idCategoriaPosts
        ]);

        return redirect()->route('posts.index')->with('success', 'Post criado com sucesso!');
    }

    public function create()
    {
        $categorias = DB::table('tbcategoriaposts')->get();
        return view('layouts.posts.create', compact('categorias'));
    }

    public function show(Post $post)
    {
        return view('layouts.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $categorias = DB::table('tbcategoriaposts')->get();
        return view('layouts.posts.edit', compact('post', 'categorias'));
    }
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6048',
            'idCategoriaPosts' => 'required|exists:tbcategoriaposts,idCategoriaPosts'
        ]);

        if ($request->hasFile('image')) {
            if ($post->image && File::exists(public_path($post->image))) {
                File::delete(public_path($post->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img/posts'), $imageName);
            $post->image = 'img/posts/' . $imageName;
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->idCategoriaPosts = $request->idCategoriaPosts;
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy(Post $post)
    {
        if ($post->image && File::exists(public_path($post->image))) {
            File::delete(public_path($post->image));
        }
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post excluído com sucesso!');
    }

    public function destroyFromAdmin(Post $post)
    {
        if ($post->image && File::exists(public_path($post->image))) {
            File::delete(public_path($post->image));
        }

        $post->delete();
        return redirect()->route('campanha')->with('success', 'Post excluído com sucesso!');
    }

    public function indexApi()
    {
        try {
            $posts = Post::withCount('comments')
                ->with([
                    'ong' => function ($query) {
                        $query->select('idOng', 'nomeOng', 'fotoOng');
                    }
                ])
                ->select([
                    'id',
                    'title',
                    'description',
                    'image',
                    'views',
                    'likes',
                    'saves',
                    'created_at',
                    'updated_at',

                    'idOng'
                ])
                ->latest()
                ->get();

            // Transformação dos dados
            $posts->transform(function ($post) {
                if ($post->image) {
                    $post->image = asset($post->image);
                }
                // Garante que a foto da ONG também tenha URL completa
                if ($post->ong && $post->ong->fotoOng) {
                    $post->ong->fotoOng = asset($post->ong->fotoOng);
                }
                return $post;
            });

            $posts->transform(function ($post) {
                $commentCount = DB::table('tbcomentario')->where('id', $post->id)->count();
                $post->comments_count = $commentCount;

                // Garante URL completa da imagem
                if ($post->image) {
                    $post->image = asset($post->image);
                }

                // Garante URL completa da foto da ONG
                if ($post->ong && $post->ong->fotoOng) {
                    $post->ong->fotoOng = asset($post->ong->fotoOng);
                }

                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts,
                'message' => 'Posts carregados com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function campanhasAdm()
    {
        // Remova o ->get() e use apenas ->paginate()
        $posts = Post::with('ong')->latest()->paginate(5);
        return view('campanhas.campanha', compact('posts'));
    }

    public function likePost(Post $post)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $userId = Auth::guard('sanctum')->id();
        DB::beginTransaction();

        try {
            $existingLike = DB::table('tblike')
                ->where('idUser', $userId)
                ->where('id', $post->id)
                ->first();

            if ($existingLike) {
                DB::table('tblike')
                    ->where('id', $existingLike->id)
                    ->delete();
                $post->decrement('likes');
                $liked = false;
            } else {
                DB::table('tblike')->insert([
                    'idUser' => $userId,
                    'id' => $post->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $post->increment('likes');
                $liked = true;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes' => $post->fresh()->likes, // Garante que estamos pegando o valor atualizado
                'message' => $liked ? 'Post curtido' : 'Like removido'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar o like',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkLikeStatus(Post $post)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'is_liked' => false,
                'message' => 'Usuário não autenticado'
            ]);
        }

        $userId = Auth::guard('sanctum')->id();
        $existingLike = DB::table('tblike')
            ->where('idUser', $userId)
            ->where('id', $post->id)
            ->exists();

        return response()->json([
            'is_liked' => $existingLike,
            'likes_count' => $post->likes
        ]);
    }


    
    public function commentOnPost(Request $request, Post $post)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate(['comment' => 'required|string|max:1000']);

        try {
            $user = Auth::guard('sanctum')->user();

            // Use APENAS a coluna que você manteve (comment OU texto)
            DB::table('tbcomentario')->insert([
                'idUser' => $user->idUser,
                'id' => $post->id,
                'comment' => $request->comment,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comentário adicionado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar comentário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getComments(Post $post)
    {
        try {
            $comments = DB::table('tbcomentario')
                ->join('tbuser', 'tbcomentario.idUser', '=', 'tbuser.idUser')
                ->where('tbcomentario.id', $post->id)
                ->where('tbcomentario.visivel', 1)
                ->select([
                    'tbcomentario.idComentario',
                    'tbcomentario.comment',
                    'tbcomentario.created_at',
                    'tbuser.nomeUser',
                    'tbuser.imgUser',
                    'tbuser.idUser'
                    
                ])
                ->orderBy('tbcomentario.created_at', 'desc')
                ->get();

            // Adiciona a URL completa da imagem do usuário
            $comments->transform(function ($comment) {
                if ($comment->imgUser) {
                    $comment->imgUser = asset($comment->imgUser);
                }
                return $comment;
            });

            $commentCount = DB::table('tbcomentario')
                ->where('id', $post->id)
                ->where('visivel', 1)
                ->count();

            return response()->json([
                'success' => true,
                'comments' => $comments,
                'comment_count' => $commentCount,
                'message' => 'Comentários carregados com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar comentários',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function incrementViews(Post $post)
    {
        $post->increment('views');
        return response()->json(['success' => true, 'message' => 'Visualização incrementada']);
    }



    public function pesquisarPost(Request $request)
    {
        try {
            DB::enableQueryLog();

            $query = Post::with(['ong:idOng,nomeOng,fotoOng'])
                ->select([
                    'id',
                    'title',
                    'description',
                    'image',
                    'views',
                    'likes',
                    'saves',
                    'created_at',
                    'idOng'
                ]);

            if ($request->search) {
                $search = strtolower($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where(DB::raw('LOWER(title)'), 'like', "%$search%")
                        ->orWhere(DB::raw('LOWER(description)'), 'like', "%$search%");
                });
            }

            $posts = $query->orderByDesc('created_at')->get();

            $posts->transform(function ($post) {
                if ($post->image) {
                    $post->image = asset($post->image);
                }
                if ($post->ong && $post->ong->fotoOng) {
                    $post->ong->fotoOng = asset($post->ong->fotoOng);
                }
                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts,
                'count' => $posts->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro na pesquisa de posts: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao pesquisar posts',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function getLikes(Post $post)
    {
        try {
          
            $likes = DB::table('tblike')
                ->join('tbuser', 'tblike.idUser', '=', 'tbuser.idUser')
                ->where('tblike.id', $post->id)
                ->select('tblike.*', 'tbuser.nomeUser')
                ->get();

            return response()->json([
                'success' => true,
                'likes' => $likes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar curtidas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCategorias()
    {
        try {
          
            if (!Schema::hasTable('tbcategoriaposts')) {
                throw new \Exception('Tabela de categorias não encontrada');
            }

            $categorias = DB::table('tbcategoriaposts')
                 ->select('idCategoriaPosts as id', 'categoriaPosts as nome')
                ->get();

            if ($categorias->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Nenhuma categoria encontrada'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $categorias,
                'message' => 'Categorias carregadas com sucesso'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar categorias: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao carregar categorias',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function getPostsByCategoria($categoriaId)
    {
        try {
            $posts = Post::where('idCategoriaPosts', $categoriaId)
                ->with(['ong', 'categoria'])
                ->withCount(['likes', 'comments'])
                ->get();

            $posts->transform(function ($post) {
                if ($post->image) {
                    $post->image = asset($post->image);
                }
                if ($post->ong && $post->ong->fotoOng) {
                    $post->ong->fotoOng = asset($post->ong->fotoOng);
                }
                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts,
                'message' => 'Posts filtrados com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao filtrar posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }


public function deleteComment(Request $request, $commentId)
{
    if (!Auth::guard('sanctum')->check()) {
        return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
    }

    try {
        $userId = Auth::guard('sanctum')->id();
        
        // Verifica se o comentário pertence ao usuário
        $comment = DB::table('tbcomentario')
            ->where('idComentario', $commentId)
            ->first();

        if (!$comment) {
            return response()->json(['success' => false, 'message' => 'Comentário não encontrado'], 404);
        }

        if ($comment->idUser != $userId) {
            return response()->json(['success' => false, 'message' => 'Você não tem permissão para excluir este comentário'], 403);
        }

        // Soft delete (marca como invisível) ou delete permanente
        DB::table('tbcomentario')
            ->where('idComentario', $commentId)
            ->delete();

        return response()->json([
            'success' => true,  
            'message' => 'Comentário excluído com sucesso'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao excluir comentário',
            'error' => $e->getMessage()
        ], 500);
    }
}
}