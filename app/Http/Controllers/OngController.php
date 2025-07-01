<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Ong;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\OngApproved;
use App\Mail\OngRejected;
use App\Models\Animal;
use App\Models\Post;

class OngController extends Controller
{

    public function profile()
    {
        $ong = auth('ong')->user();
        return view('ong.profile', compact('ong'));
    }

    public function dashboard()
    {
        return view('layouts.dashboard', [
            'ong' => auth('ong')->user()
        ]);
    }

    public function index(Request $request)
    {
        $query = Ong::query();

        // Filtro por status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filtro por data de cadastro
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filtro por busca (nome/CNPJ)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomeOng', 'like', '%' . $search . '%')
                    ->orWhere('cnpjOng', 'like', '%' . $search . '%');
            });
        }

        // Filtro por animais para adoção
        if ($request->filled('has_animals')) {
            if ($request->has_animals == '1') {
                $query->whereHas('animais', function ($q) {
                    $q->where('idStatusAnimal', 3);
                });
            } elseif ($request->has_animals == '0') {
                $query->whereDoesntHave('animais', function ($q) {
                    $q->where('idStatusAnimal', 3);
                });
            }
        }

        // Filtro por campanhas ativas
        if ($request->filled('has_campaigns')) {
            if ($request->has_campaigns == '1') {
                $query->whereHas('posts', function ($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                });
            } elseif ($request->has_campaigns == '0') {
                $query->whereDoesntHave('posts', function ($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                });
            }
        }


        $totalOngs = $query->count();
        $ongs = $query->get();

        foreach ($ongs as $ong) {
            $ong->animaisCount = Animal::where('idOng', $ong->idOng)->count();

            $posts = Post::where('idOng', $ong->idOng)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            foreach ($posts as $post) {
                $post->comentariosCount = DB::table('tbcomentario')
                    ->where('id', $post->id)
                    ->count();

                $post->likesCount = DB::table('tblike')
                    ->where('id', $post->id)
                    ->count();
            }

            $ong->posts = $posts;
            $ong->postsCount = Post::where('idOng', $ong->idOng)->count();
        }

        return view('ong.index', [
            'ongs' => $ongs,
            'totalOngs' => $totalOngs,
            'filters' => $request->all()
        ]);
    }


    public function create()
    {
        return view('auth.cadastrarOng');
    }

    public function store(Request $request)
    {
        try {

            Log::debug('Dados recebidos no store:', $request->all());


            $dados = $request->all();


            $validatedData = $request->validate([
                'nome' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:tbong,emailOng',
                'cnpj' => 'required|string|min:14|unique:tbong,cnpjOng',
                'cep' => 'required|string|min:8',
                'endereco' => 'required|string|max:255',
                'celular' => 'required|string|min:11',
                'password' => 'required|string|min:6|confirmed',
            ]);


            Log::debug('Dados validados:', $validatedData);


            $ong = Ong::create([
                'nomeOng' => $validatedData['nome'],
                'emailOng' => $validatedData['email'],
                'cnpjOng' => $validatedData['cnpj'],
                'cepOng' => $validatedData['cep'],
                'lograOng' => $validatedData['endereco'],
                'telOng' => $validatedData['celular'],
                'senhaOng' => Hash::make($validatedData['password']),
                'status' => 'pendente'
            ]);


            Log::debug('ONG criada:', $ong->toArray());

            return redirect()->route('ong.login')
                ->with('modal_message', 'Cadastro enviado para análise! Aguarde a aprovação do administrador.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação:', $e->errors());
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar ONG:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ong = Ong::findOrFail($id);
        return view('ong.ongRegister', compact('ong'));
    }


    public function update(Request $request, $id)
    {

        $ong = Ong::findOrFail($id);


        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cnpj' => 'required|string|max:18',
            'celular' => 'required|string|max:15',
            'cep' => 'required|string|max:10',
            'endereco' => 'required|string|max:255',
            'num' => 'nullable|string|max:10',
            'uf' => 'nullable|string|max:2',
            'password' => 'nullable|string|min:6|confirmed',
            'fotoOng' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($request->hasFile('fotoOng')) {

            $imageName = time() . '.' . $request->fotoOng->extension();


            $request->fotoOng->move(public_path('images/ong'), $imageName);


            $ong->fotoOng = 'images/ong/' . $imageName;
        }


        $ong->update([
            'nomeOng' => $request->nome,
            'emailOng' => $request->email,
            'cnpjOng' => $request->cnpj,
            'telOng' => $request->celular,
            'cepOng' => $request->cep,
            'lograOng' => $request->endereco,
            'numLograOng' => $request->num,
            'ufOng' => strtoupper($request->uf),
            'senhaOng' => $request->password ? Hash::make($request->password) : $ong->senhaOng,
        ]);


        return redirect()->route('ong.profile')->with('success', 'ONG atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $ong = Ong::findOrFail($id);
        $ong->delete();

        return redirect()->route('ong.index')->with('success', 'ONG excluída com sucesso!');
    }
    public function aprovar($id)
    {
        try {
            $ong = Ong::findOrFail($id);


            DB::table('tbong')
                ->where('idOng', $id)
                ->update(['status' => 'aprovado']);
            $ong->status = 'aprovado';
            $ong->save();

            Log::info("Status atualizado para: {$ong->status}");




            Mail::to($ong->emailOng)->send(new OngApproved($ong));

            return back()->with('success', 'ONG aprovada com sucesso!');
        } catch (\Exception $e) {
            Log::error("ERRO: " . $e->getMessage());
            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function rejeitar(Request $request, $id)
    {
        $request->validate(['motivo' => 'required|string|min:10|max:500']);

        $ong = Ong::findOrFail($id);
        $ong->status = 'rejeitado';
        $ong->save();

        try {
            Mail::to($ong->emailOng)->send(new OngRejected($ong, $request->motivo));
            return redirect()->back()->with('success', 'ONG rejeitada e e-mail enviado!');
        } catch (\Exception $e) {
            Log::error("Erro ao rejeitar ONG: " . $e->getMessage());
            return redirect()->back()->with('error', 'ONG rejeitada, mas o e-mail falhou: ' . $e->getMessage());
        }
    }
}
