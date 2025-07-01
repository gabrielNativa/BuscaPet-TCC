<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Raca;
use App\Models\Porte;
use App\Models\Especie;
use App\Models\Pelagem;
use App\Models\StatusAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimalOngController extends Controller
{
    public function index()
    {
        $ongId = auth()->user()->idOng;
    
        $animais = Animal::with(['raca', 'porte', 'especie', 'pelagem', 'status'])
            ->where('idOng', $ongId) 
            ->get();
    
        return view('petsOng.petsOng', compact('animais'));
}    


    public function create()
    {
        $racas = Raca::all();
        $portes = Porte::all();
        $especies = Especie::all();
        $pelagens = Pelagem::all();
        $status = StatusAnimal::where('descStatusAnimal', 'Adoção')->get();

        return view('petsOng.petsOngRegister', compact('racas', 'portes', 'especies', 'pelagens', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomeAnimal' => 'required|string|max:255',
            'idadeAnimal' => 'required|string|max:255',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $animal = new Animal();
        $animal->fill([
            'nomeAnimal' => $request->nomeAnimal,
            'idRaca' => $request->idRaca,
            'idPorte' => $request->idPorte,
            'idEspecie' => $request->idEspecie,
            'idPelagemAnimal' => $request->idPelagemAnimal,
            'idadeAnimal' => $request->idadeAnimal,
            'bioAnimal' => $request->bioAnimal,
            'idOng' => auth()->user()->idOng,
        ]);
        

        $statusAdocao = StatusAnimal::where('descStatusAnimal', 'Adoção')->first();
        $animal->idStatusAnimal = $statusAdocao ? $statusAdocao->id : 3;

        // Upload de imagens
        for ($i = 1; $i <= 4; $i++) {
            $campo = "foto{$i}";
            $imgCampo = "img{$i}Animal";
            if ($request->hasFile($campo)) {
                $file = $request->file($campo);
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('img/imgAnimal', $fileName, 'public');
                $animal->$imgCampo = $fileName;
            }
        }

        $animal->save();

        return redirect()->route('petsOng.petsOng')->with('success', 'Animal registrado com sucesso!');
    }

    public function edit($id)
    {
        $animal = Animal::where('id', $id)
            ->where('idOng', auth()->user()->id)
            ->firstOrFail();

        $racas = Raca::all();
        $portes = Porte::all();
        $especies = Especie::all();
        $pelagens = Pelagem::all();
        $status = StatusAnimal::where('descStatusAnimal', 'Adoção')->get();

        return view('petsOng.petsOngRegister', compact('animal', 'racas', 'portes', 'especies', 'pelagens', 'status'));
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::where('id', $id)
            ->where('idOng', auth()->user()->id)
            ->firstOrFail();

        $request->validate([
            'nomeAnimal' => 'required|string|max:40',
            'idadeAnimal' => 'required|string|max:30',
            'idRaca' => 'required|integer',
            'idPorte' => 'required|integer',
            'idEspecie' => 'required|integer',
            'idPelagemAnimal' => 'required|integer',
            'bioAnimal' => 'nullable|string|max:100',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $animal->update($request->except('fotos'));

        for ($i = 1; $i <= 4; $i++) {
            $campo = "foto{$i}";
            $imgCampo = "img{$i}Animal";
            if ($request->hasFile($campo)) {
                if ($animal->$imgCampo && file_exists(public_path('storage/img/imgAnimal/' . $animal->$imgCampo))) {
                    unlink(public_path('storage/img/imgAnimal/' . $animal->$imgCampo));
                }

                $file = $request->file($campo);
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('img/imgAnimal', $fileName, 'public');
                $animal->$imgCampo = $fileName;
            }
        }

        $animal->save();

        return redirect()->route('petsOng.petsOng')->with('success', 'Animal atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $animal = Animal::where('id', $id)
            ->where('idOng', auth()->user()->id)
            ->firstOrFail();

        for ($i = 1; $i <= 4; $i++) {
            $campo = "img{$i}Animal";
            if ($animal->$campo) {
                Storage::disk('public')->delete("img/imgAnimal/{$animal->$campo}");
            }
        }

        $animal->delete();

        return redirect()->route('petsOng.petsOng')->with('success', 'Animal excluído com sucesso!');
    }
}
