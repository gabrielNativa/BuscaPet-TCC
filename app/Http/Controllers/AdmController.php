<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Adm;

class AdmController extends Controller
{
    /**
     * Exibe a lista de administradores (SELECT ALL).
     */
    public function index()
    {
        $adms = Adm::all();
        return view('admin.adm', compact('adms'));
    }


    /**
     * Exibe o formulário de cadastro.
     */
    public function create()
    {
        return view('admin.admRegister');
    }

    /**
     * Salva um novo administrador no banco de dados.
     */
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
            'password' => 'required|string|max:11',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validação da imagem
        ]);

        $adm = new Adm();
        $adm->nomeAdm = $validatedData['nome'];
        $adm->emailAdm = $validatedData['email'];
        $adm->cpfAdm = $validatedData['cpf'];
        $adm->cepAdm = $validatedData['cep'];
        $adm->ufAdm = $validatedData['uf'];
        $adm->lograAdm = $validatedData['endereco'];
        $adm->numLograAdm = $validatedData['num'];
        $adm->dataNascAdm = $validatedData['nasc'];
        $adm->telAdm = $validatedData['celular'];
        $adm->senhaAdm = Hash::make($request->password);
        $adm->cidadeAdm = '';
        $adm->bairroAdm = '';
        $adm->paisAdm = 'Brasil';

        // Processamento da imagem
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/imgAdm'), $fileName);
            $adm->imgAdm = $fileName; // Salva o nome da imagem no banco
        }

        $adm->save();

        return redirect()->route('admin.index')->with('success', 'Administrador cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit($id)
    {
        $adm = Adm::findOrFail($id);
        return view('admin.admRegister', compact('adm'));
    }

    /**
     * Atualiza os dados do administrador.
     */
    public function update(Request $request, $id)
    {
        $adm = Adm::findOrFail($id);

        // Validação dos dados
        $validatedData = $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|max:80',
            'cpf' => 'required|string|max:14',
            'cep' => 'required|string|max:9',
            'uf' => 'required|string|max:40', // Corrigido para estar em sintonia com o seu campo 'ufAdm'
            'endereco' => 'required|string|max:100',
            'num' => 'required|string|max:40',
            'nasc' => 'required|date',
            'celular' => 'required|string|max:15',
            'password' => 'nullable|string|max:11',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Atualiza os dados do administrador
        $adm->update([
            'nomeAdm' => $validatedData['nome'],
            'emailAdm' => $validatedData['email'],
            'cpfAdm' => $validatedData['cpf'],
            'telAdm' => $validatedData['celular'],
            'cepAdm' => $validatedData['cep'],
            'ufAdm' => $validatedData['uf'], // Estado atualizado
            'lograAdm' => $validatedData['endereco'], // Endereço atualizado
            'numLograAdm' => $validatedData['num'],
            'dataNascAdm' => $validatedData['nasc'],
        ]);

        // Atualiza a senha se foi informada
        if ($request->filled('password')) {
            $adm->senhaAdm = Hash::make($request->password);
        }

        // Se uma nova imagem foi enviada, exclui a antiga e salva a nova
        if ($request->hasFile('foto')) {
            // Remove a imagem antiga
            if ($adm->imgAdm && file_exists(public_path('img/imgAdm/' . $adm->imgAdm))) {
                unlink(public_path('img/imgAdm/' . $adm->imgAdm));
            }

            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/imgAdm'), $fileName);
            $adm->imgAdm = $fileName;
        }

        // Salva as alterações
        $adm->save();

        return redirect()->route('admin.index')->with('success', 'Administrador atualizado com sucesso!');
    }

    /**
     * Remove um administrador do banco de dados.
     */
    public function destroy($id)
    {
        $adm = Adm::findOrFail($id);

        // Remove a imagem associada antes de excluir o registro
        if ($adm->imgAdm && file_exists(public_path('img/imgAdm/' . $adm->imgAdm))) {
            unlink(public_path('img/imgAdm/' . $adm->imgAdm));
        }

        $adm->delete();

        return redirect()->route('admin.index')->with('success', 'Administrador excluído com sucesso!');
    }
}
