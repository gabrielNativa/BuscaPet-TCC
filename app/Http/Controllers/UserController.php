<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);
        return view('user.user', compact('users'));
    }

    public function create()
    {
        return view('user.userRegister');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|max:80',
            'cpf' => 'required|string|max:14',
            'cep' => 'required|string|max:9',
            'uf' => 'required|string|max:40',
            'endereco' => 'required|string|max:100',
            'num' => 'required|string|max:40',
            'nasc' => 'required|date',
            'celular' => 'required|string|max:15',
            'password' => 'required|string|max:255',
        ]);

        $user = new User();
        $user->nomeUser = $validatedData['nome'];
        $user->emailUser = $validatedData['email'];
        $user->cpfUser = $validatedData['cpf'];
        $user->cepUser = $validatedData['cep'];
        $user->ufUser = $validatedData['uf'];
        $user->lograUser = $validatedData['endereco'];
        $user->numLograUser = $validatedData['num'];
        $user->dataNascUser = $validatedData['nasc'];
        $user->telUser = $validatedData['celular'];
        $user->senhaUser = Hash::make($validatedData['password']);
        $user->cidadeUser = '';
        $user->compUser = '';
        $user->paisUser = 'Brasil';
        $user->ativo = 1; // Usuário ativo por padrão

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/imgUser'), $fileName);
            $user->imgUser = $fileName;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Administrador cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.userRegister', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|max:80',
            'cpf' => 'required|string|max:14',
            'cep' => 'required|string|max:9',
            'uf' => 'required|string|max:40',
            'endereco' => 'required|string|max:100',
            'num' => 'required|string|max:40',
            'nasc' => 'required|date',
            'celular' => 'required|string|max:15',
            'password' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        if ($request->filled('password')) {
            $user->senhaUser = Hash::make($validatedData['password']);
        }

        $user->update([
            'nomeUser' => $validatedData['nome'],
            'emailUser' => $validatedData['email'],
            'cpfUser' => $validatedData['cpf'],
            'telUser' => $validatedData['celular'],
            'cepUser' => $validatedData['cep'],
            'ufUser' => $validatedData['uf'],
            'lograUser' => $validatedData['endereco'],
            'numLograUser' => $validatedData['num'],
            'dataNascUser' => $validatedData['nasc'],
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/imgUser'), $fileName);

            if ($user->imgUser && file_exists(public_path('img/imgUser/' . $user->imgUser))) {
                unlink(public_path('img/imgUser/' . $user->imgUser));
            }

            $user->imgUser = $fileName;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Bloquear um usuário (em vez de excluir)
     */
    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->ativo = 0; // Define como bloqueado
        $user->save();

        return redirect()->route('user.index')->with('success', 'Usuário bloqueado com sucesso!');
    }

    /**
     * Desbloquear um usuário
     */
    public function unblock($id)
    {
        $user = User::findOrFail($id);
        $user->ativo = 1; // Define como ativo
        $user->save();

        return redirect()->route('user.index')->with('success', 'Usuário desbloqueado com sucesso!');
    }

    /**
     * Método destroy mantido para compatibilidade, mas não será mais usado
     * Recomendamos usar o método block em vez deste
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->imgUser && file_exists(public_path('img/imgUser/' . $user->imgUser))) {
            unlink(public_path('img/imgUser/' . $user->imgUser));
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Usuário excluído com sucesso!');
    }

    public function getAtividades($id)
    {
        $comentarios = DB::table('tbcomentario')
            ->where('idUser', $id)
            ->select('comment', 'created_at')
            ->get();

        $reels = DB::table('reels')
            ->where('idUser', $id)
            ->select('tituloReels', 'created_at')
            ->get();

        // Curtidas em posts
        $likesPosts = DB::table('tblike')
            ->where('idUser', $id)
            ->get();

        $curtidas = collect();

        foreach ($likesPosts as $like) {
            $post = DB::table('posts')->where('id', $like->id)->first();
            if ($post) {
                $curtidas->push([
                    'tipo' => 'Post',
                    'titulo' => $post->title,
                    'created_at' => $like->created_at,
                ]);
            }
        }

        // Curtidas em reels
        $likesReels = DB::table('tbreel_likes')
            ->where('idUser', $id)
            ->get();

        foreach ($likesReels as $like) {
            $reel = DB::table('reels')->where('idReels', $like->idReels)->first();
            if ($reel) {
                $curtidas->push([
                    'tipo' => 'PetToks',
                    'titulo' => $reel->tituloReels,
                    'created_at' => $like->created_at,
                ]);
            }
        }

        $denuncias = DB::table('tbdenunciacomentario')
            ->where('idUser', $id)
            ->select('motivoDenuncia', 'created_at')
            ->get();

        return response()->json([
            'comentarios' => $comentarios,
            'reels' => $reels,
            'curtidas' => $curtidas->sortByDesc('created_at')->values(),
            'denuncias' => $denuncias,
        ]);
    }
}
