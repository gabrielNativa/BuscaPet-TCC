<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use App\Models\Like;
use App\Models\Comment;
use App\Models\PostAnimalPerdido;
use App\Models\ReelComentario;
use App\Models\ReelLike;
use App\Models\Reel;
class UserApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado',
            ], 401);
        }
    
        $users = User::all();
        return response()->json($users);
    }

public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'nomeUser' => 'required|string|max:60',
            'emailUser' => 'required|email|max:80|unique:tbuser,emailUser',
            'cpfUser' => 'required|string|max:14|unique:tbuser,cpfUser',
            'telUser' => 'nullable|string|max:15|unique:tbuser,telUser', // Adicionado unique para telefone
            'senhaUser' => 'required|string|min:6|max:255', // Senha agora é obrigatória e com min 6
            'cepUser' => 'nullable|string|max:9',
            'ufUser' => 'nullable|string|max:40',
            'lograUser' => 'nullable|string|max:100',
            'numLograUser' => 'nullable|string|max:20',
            'dataNascUser' => 'nullable|date',
            'cidadeUser' => 'nullable|string|max:40',
            'compUser' => 'nullable|string|max:40',
            'paisUser' => 'nullable|string|max:40',
            'imgUser' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nomeUser.required' => 'O nome é obrigatório.',
            'emailUser.required' => 'O e-mail é obrigatório.',
            'emailUser.email' => 'O e-mail deve ser um endereço válido.',
            'emailUser.unique' => 'Este e-mail já está em uso.',
            'cpfUser.required' => 'O CPF é obrigatório.',
            'cpfUser.unique' => 'Este CPF já está em uso.',
            'telUser.unique' => 'Este telefone já está em uso.',
            'senhaUser.required' => 'A senha é obrigatória.',
            'senhaUser.min' => 'A senha deve ter pelo menos :min caracteres.',
        ]);

        $userData = $validated;

        if (isset($validated['senhaUser'])) {
            $userData['senhaUser'] = Hash::make($validated['senhaUser']);
        }

        if ($request->hasFile('imgUser')) {
            $path = $request->file('imgUser')->store('public/usuarios');
            $userData['imgUser'] = str_replace('public/', 'storage/', $path);
        }

        try {
            $user = User::create($userData);

            return response()->json([
                'success' => true,
                'message' => 'Usuário criado com sucesso',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro de validação',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao criar usuário',
            'error' => $e->getMessage()
        ], 500);
    }
}
  public function checkExistence(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (!$field || !$value) {
            return response()->json(['error' => 'Campo e valor são obrigatórios'], 400);
        }

        // Mapeia os nomes dos campos do frontend para as colunas do banco de dados
        $columnMap = [
            'nome' => 'nomeUser',
            'email' => 'emailUser',
            'cpf' => 'cpfUser',
            'telefone' => 'telUser',
        ];

        if (!isset($columnMap[$field])) {
            return response()->json(['error' => 'Campo inválido'], 400);
        }

        $columnName = $columnMap[$field];

        $exists = User::where($columnName, $value)->exists();

        return response()->json(['exists' => $exists]);
    }



    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID inválido',
                    'received_id' => $id
                ], 400);
            }
    
            $user = User::where('idUser', $id)->first();
    
            if (!$user) {
                Log::warning('Usuário não encontrado', [
                    'id' => $id,
                    'existing_users' => User::all()->pluck('idUser')
                ]);
    
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não encontrado',
                    'debug_info' => [
                        'requested_id' => $id,
                        'existing_ids' => User::all()->pluck('idUser'),
                        'table_structure' => Schema::getColumnListing('tbuser')
                    ]
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->idUser,
                    'nome' => $user->nomeUser,
                    'email' => $user->emailUser,
                    'tel' => $user->telUser,
                    'imgUser' => $user->imgUser ? url($user->imgUser) : null
                ]
            ]);
    
        } catch (\Exception $e) {
            Log::error('Erro no UserController@show', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor',
                'technical_details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Log detalhado para debug
            Log::info('=== INÍCIO DO UPDATE ===');
            Log::info('User ID:', ['id' => $id]);
            Log::info('Dados recebidos:', $request->all());
            Log::info('Arquivos recebidos:', $request->allFiles());
            Log::info('Headers:', $request->headers->all());

            // Validação dos dados com mensagens customizadas
            $validatedData = $request->validate([
                'nomeUser'     => 'nullable|string|max:60',
                'emailUser'    => 'nullable|email|max:80|unique:tbuser,emailUser,' . $id . ',idUser',
                'cpfUser'      => 'nullable|string|max:14|unique:tbuser,cpfUser,' . $id . ',idUser',
                'cepUser'      => 'nullable|string|max:9',
                'ufUser'       => 'nullable|string|max:40',
                'lograUser'    => 'nullable|string|max:100',
                'numLograUser' => 'nullable|string|max:20',
                'dataNascUser' => 'nullable|date',
                'telUser'      => 'nullable|string|max:15',
                'senhaUser'    => 'nullable|string|min:6|max:255',
                'cidadeUser'   => 'nullable|string|max:40',
                'compUser'     => 'nullable|string|max:40',
                'paisUser'     => 'nullable|string|max:40',
                'imgUser'      => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ], [
                'emailUser.unique' => 'Este email já está em uso por outro usuário.',
                'cpfUser.unique' => 'Este CPF já está em uso por outro usuário.',
                'imgUser.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif ou webp.',
                'imgUser.max' => 'A imagem não pode ser maior que 5MB.',
                'telUser.max' => 'O telefone não pode ter mais que 15 caracteres.',
            ]);

            Log::info('Dados validados:', $validatedData);

            // Preenche os campos (exceto senha e imagem)
            foreach (Arr::except($validatedData, ['senhaUser', 'imgUser']) as $key => $value) {
                if (!is_null($value)) {
                    $user->{$key} = $value;
                }
            }

            // Atualiza senha se fornecida
            if (!empty($validatedData['senhaUser'])) {
                $user->senhaUser = Hash::make($validatedData['senhaUser']);
            }

            // Processa a imagem se enviada
            if ($request->hasFile('imgUser') && $request->file('imgUser')->isValid()) {
                Log::info('=== PROCESSANDO IMAGEM ===');
                $file = $request->file('imgUser');
                
                Log::info('Detalhes do arquivo:', [
                    'originalName' => $file->getClientOriginalName(),
                    'mimeType' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension(),
                    'isValid' => $file->isValid(),
                    'error' => $file->getError()
                ]);
                
                // Obter extensão corretamente
                $extension = $file->getClientOriginalExtension();
                if (empty($extension)) {
                    // Tentar obter extensão do MIME type
                    $mimeType = $file->getMimeType();
                    switch ($mimeType) {
                        case 'image/jpeg':
                            $extension = 'jpg';
                            break;
                        case 'image/png':
                            $extension = 'png';
                            break;
                        case 'image/gif':
                            $extension = 'gif';
                            break;
                        case 'image/webp':
                            $extension = 'webp';
                            break;
                        default:
                            $extension = 'jpg';
                    }
                }
                
                // Nome do arquivo com extensão
                $filename = 'user_' . $user->idUser . '_' . time() . '.' . $extension;
                Log::info('Nome do arquivo gerado:', ['filename' => $filename]);
                
                // Usar separadores consistentes (sempre /)
                $destination = public_path('img/imgUser');
                Log::info('Destino:', ['destination' => $destination]);
                
                // Criar diretório se não existir
                if (!file_exists($destination)) {
                    $created = mkdir($destination, 0755, true);
                    Log::info('Diretório criado:', ['created' => $created]);
                }

                // Remove imagem antiga, se existir
                if ($user->imgUser) {
                    $oldPath = public_path($user->imgUser);
                    if (file_exists($oldPath)) {
                        $deleted = unlink($oldPath);
                        Log::info('Imagem antiga removida:', ['deleted' => $deleted, 'path' => $oldPath]);
                    }
                }

                // Move o arquivo
                try {
                    $moved = $file->move($destination, $filename);
                    if ($moved) {
                        // Atualiza o caminho no banco com separadores corretos
                        $user->imgUser = 'img/imgUser/' . $filename;
                        Log::info('Imagem salva com sucesso:', [
                            'path' => $user->imgUser,
                            'fullPath' => $destination . '/' . $filename
                        ]);
                    } else {
                        throw new \Exception('Falha ao mover o arquivo');
                    }
                } catch (\Exception $moveError) {
                    Log::error('Erro ao mover arquivo:', [
                        'error' => $moveError->getMessage(),
                        'destination' => $destination,
                        'filename' => $filename
                    ]);
                    throw new \Exception('Falha ao salvar a imagem: ' . $moveError->getMessage());
                }
            } else if ($request->hasFile('imgUser')) {
                Log::warning('Arquivo de imagem inválido:', [
                    'hasFile' => $request->hasFile('imgUser'),
                    'file' => $request->file('imgUser'),
                    'isValid' => $request->file('imgUser') ? $request->file('imgUser')->isValid() : 'null'
                ]);
            }

            // Salva as alterações
            $user->save();
            Log::info('Usuário salvo com sucesso');

            // Gera URL pública da imagem
            $imgUrl = $user->imgUser ? url($user->imgUser) : null;
            Log::info('URL da imagem gerada:', ['img_url' => $imgUrl]);

            Log::info('=== FIM DO UPDATE - SUCESSO ===');

            return response()->json([
                'success' => true,
                'message' => 'Usuário atualizado com sucesso',
                'data' => [
                    'user' => $user,
                    'img_url' => $imgUrl,
                ],
                'changes' => $user->getChanges(),
            ]);

        } catch (ValidationException $e) {
            Log::error('=== ERRO DE VALIDAÇÃO ===');
            Log::error('Erros de validação:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERRO GERAL ===');
            Log::error('Erro no update:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar usuário',
                'error' => $e->getMessage(),
                'debug' => env('APP_DEBUG') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

 public function destroy($id)
{
    try {
        DB::beginTransaction();

        $user = User::findOrFail($id);

        // 1. Deletar comentários
        DB::table('tbcomentario')->where('idUser', $id)->delete();

        // 2. Deletar reels
        DB::table('reels')->where('idUser', $id)->delete();

        // 3. Deletar preferências
        DB::table('tbpreferencias_usuario')->where('idUser', $id)->delete();

        // 4. Deletar tokens de acesso
        DB::table('personal_access_tokens')->where('tokenable_id', $id)->delete();

        // 5. Deletar imagem do usuário (se houver)
        if ($user->imgUser) {
            Storage::delete(str_replace('storage/', 'public/', $user->imgUser));
        }

        // 6. Finalmente, deletar o próprio usuário
        $user->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Usuário removido com sucesso'
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Erro ao deletar usuário: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erro ao excluir usuário',
            'error' => $e->getMessage()
        ], 500);
    }
}


    
    public function updatePassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $request->validate([
                'currentPassword' => 'required|string',
                'newPassword' => 'required|string|min:6'
            ]);

            if (!Hash::check($request->currentPassword, $user->senhaUser)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Senha atual incorreta'
                ], 401);
            }

            $user->senhaUser = Hash::make($request->newPassword);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Senha alterada com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar senha',
                'error' => $e->getMessage()
            ], 500);
        }
    }  

    public function dash(Request $request)
    {
        try {
            $user = Auth::user();
    
            if (!$user) {
                return response()->json(['error' => 'Usuário não autenticado'], 401);
            }
    
            $userId = $user->idUser;


            // postONG
            // Total de curtidas dadas pelo usuário
            $postLikes = Like::where('idUser', $userId)->count();
    
            // Total de comentários feitos pelo usuário
            $postComent = Comment::where('idUser', $userId)->count();

            //animais
            $lostPets = PostAnimalPerdido::where('idUser', $userId)->count();
            
            $reelsPost = Reel::where('idUser', $userId)->count();
            //Reels
            $reelsLikes = ReelLike::where('idUser', $userId)->count();
            $reelsComents = ReelComentario::where('idUser', $userId)->count();

            //total 
            $totalComments = $postComent + $reelsComents;
            
            $totalLikes = $postLikes + $reelsLikes;

            return response()->json([
                'totalLikes' => $totalLikes,
                'totalComents' => $totalComments,
                'lostPets'=> $lostPets,
                'reelsPost' =>  $reelsPost,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno no servidor',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    
}