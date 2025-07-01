<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Raca;
use App\Models\Porte;
use App\Models\Especie;
use App\Models\Pelagem;
use App\Models\StatusAnimal;
use App\Models\ImagensAnimal;
use App\Models\Interesse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller
{
    public function index()
    {
        $ong = auth("ong")->user();

        $animais = Animal::with(["raca", "porte", "especie", "pelagem", "status"])
            ->withCount('interesses')
            ->where("idOng", $ong->idOng)
            ->get();

        return view("pets.pets", compact("animais"));
    }

    public function create()
    {
        $racas = Raca::all();
        $portes = Porte::all();
        $especies = Especie::all();
        $pelagens = Pelagem::all();

        return view("pets.petsRegister", compact("racas", "portes", "especies", "pelagens"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "nomeAnimal" => "required|string|max:40",
            "idadeAnimal" => "required|string|max:30",
            "idRaca" => "required|exists:tbRaca,idRaca",
            "idPorte" => "required|exists:tbPorte,idPorte",
            "idEspecie" => "required|exists:tbEspecie,idEspecie",
            "idPelagemAnimal" => "required|exists:tbPelagemAnimal,idPelagemAnimal",
            "bioAnimal" => "nullable|string|max:500",
            "imgPrincipal" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        try {
          
            $uploadPath = public_path("img/imgAnimal");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

           
            $imageName = time() . "_" . uniqid() . "." . $request->imgPrincipal->extension();

           
            $request->imgPrincipal->move($uploadPath, $imageName);

            
            $ong = auth("ong")->user();

            if (!$ong) {
                throw new \Exception("Nenhuma ONG autenticada encontrada");
            }

            
            $animal = Animal::create([
                "nomeAnimal" => $validated["nomeAnimal"],
                "idadeAnimal" => $validated["idadeAnimal"],
                "idRaca" => $validated["idRaca"],
                "idPorte" => $validated["idPorte"],
                "idEspecie" => $validated["idEspecie"],
                "idPelagemAnimal" => $validated["idPelagemAnimal"],
                "bioAnimal" => $validated["bioAnimal"] ?? "",
                "idStatusAnimal" => 3, 
                "imgPrincipal" => $imageName,
                "idOng" => $ong->idOng 
            ]);

            return redirect()->route("pets.uploadImages", $animal->idAnimal)
                ->with("success", "Dados básicos salvos com sucesso!");
        } catch (\Exception $e) {

            if (isset($imageName) && file_exists($uploadPath . "/" . $imageName)) {
                unlink($uploadPath . "/" . $imageName);
            }

            return back()->with("error", "Erro ao cadastrar animal: " . $e->getMessage())->withInput();
        }
    }

   
    public function showUploadImages($idAnimal)
    {
        $animal = Animal::with("imagensAnimal")->findOrFail($idAnimal);
        return view("pets.uploadImages", compact("animal"));
    }

    public function saveImages(Request $request, $idAnimal)
    {
        $request->validate([
            "img1Animal" => $request->isMethod("post") ? "required|image|mimes:jpeg,png,jpg,gif|max:2048" : "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "img2Animal" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "img3Animal" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "img4Animal" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        try {
            $imageData = [];

            for ($i = 1; $i <= 4; $i++) {
                $field = "img{$i}Animal";
                $currentField = "current_img{$i}Animal";

               
                if (!$request->hasFile($field)) {
                    if ($request->filled($currentField)) {
                        $imageData[$field] = $request->$currentField;
                    }
                    continue;
                }

                
                $image = $request->file($field);
                $imageName = time() . "_" . $i . "." . $image->extension();
                $image->move(public_path("img/imgAnimal"), $imageName);

               
                if ($request->filled($currentField)) {
                    $oldImage = public_path("img/imgAnimal/" . $request->$currentField);
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                $imageData[$field] = $imageName;
            }

            
            ImagensAnimal::updateOrCreate(
                ["idAnimal" => $idAnimal],
                $imageData
            );

            return redirect()->route("pets.index")->with("success", "Imagens salvas com sucesso!");
        } catch (\Exception $e) {
            return back()->with("error", "Erro ao salvar imagens: " . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);

        $request->validate([
            "nomeAnimal" => "required|string|max:40",
            "idadeAnimal" => "required|string|max:30",
            "idRaca" => "required|exists:tbRaca,idRaca",
            "idPorte" => "required|exists:tbPorte,idPorte",
            "idEspecie" => "required|exists:tbEspecie,idEspecie",
            "idPelagemAnimal" => "required|exists:tbPelagemAnimal,idPelagemAnimal",
            "bioAnimal" => "nullable|string",
            "imgPrincipal" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        try {
            if ($request->hasFile("imgPrincipal")) {
                if ($animal->imgPrincipal && file_exists(public_path("img/imgAnimal/" . $animal->imgPrincipal))) {
                    unlink(public_path("img/imgAnimal/" . $animal->imgPrincipal));
                }

                $imageName = time() . "." . $request->imgPrincipal->extension();
                $request->imgPrincipal->move(public_path("img/imgAnimal"), $imageName);
                $animal->imgPrincipal = $imageName;
            }

            
            $animal->update([
                "nomeAnimal" => $request->nomeAnimal,
                "idadeAnimal" => $request->idadeAnimal,
                "idRaca" => $request->idRaca,
                "idPorte" => $request->idPorte,
                "idEspecie" => $request->idEspecie,
                "idPelagemAnimal" => $request->idPelagemAnimal,
                "bioAnimal" => $request->bioAnimal ?? "",
                "imgPrincipal" => $animal->imgPrincipal
            ]);

            return redirect()->route("pets.uploadImages", $animal->idAnimal)
                ->with("success", "Dados básicos atualizados com sucesso!");
        } catch (\Exception $e) {
            return back()->with("error", "Erro ao atualizar animal: " . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $animal = Animal::findOrFail($id);

       
        if (auth("ong")->user()->idOng !== $animal->idOng) {
            abort(403, "Acesso não autorizado");
        }

        $racas = Raca::all();
        $portes = Porte::all();
        $especies = Especie::all();
        $pelagens = Pelagem::all();
        $status = StatusAnimal::all();

        return view("pets.petsRegister", compact("animal", "racas", "portes", "especies", "pelagens", "status"));
    }

    public function getAnimaisDaOng()
    {
        try {
            $ong = auth("ong")->user();

            if (!$ong) {
                return response()->json([
                    "success" => false,
                    "message" => "Nenhuma ONG autenticada encontrada."
                ], 401);
            }

            $animais = Animal::where("idOng", $ong->idOng)->get();

            return response()->json([
                "success" => true,
                "animais" => $animais
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Erro ao buscar animais: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna os detalhes completos do animal para o modal
     */
    public function getDetalhes($id)
    {
        try {
            $animal = Animal::with(["raca", "porte", "especie", "pelagem", "status", "imagensAnimal"])
                ->withCount('interesses')
                ->where("idAnimal", $id)
                ->where("idOng", auth("ong")->user()->idOng)
                ->firstOrFail();

            // Buscar usuários interessados com seus dados completos
            $usuariosInteressados = DB::table('tbinteresses')
                ->join('tbuser', 'tbinteresses.idUser', '=', 'tbuser.idUser')
                ->where('tbinteresses.idAnimal', $id)
                ->select(
                    'tbuser.idUser',
                    'tbuser.nomeUser',
                    'tbuser.telUser',
                    'tbuser.emailUser',
                    'tbuser.imgUser',
                    'tbinteresses.observacoes',
                    'tbinteresses.statusInteresse',
                    'tbinteresses.created_at as dataInteresse'
                )
                ->orderBy('tbinteresses.created_at', 'desc')
                ->get();

            return response()->json([
                "success" => true,
                "animal" => [
                    "idAnimal" => $animal->idAnimal,
                    "nomeAnimal" => $animal->nomeAnimal,
                    "idadeAnimal" => $animal->idadeAnimal,
                    "bioAnimal" => $animal->bioAnimal,
                    "imgPrincipal" => $animal->imgPrincipal,
                    "raca" => $animal->raca->nomeRaca ?? "N/A",
                    "porte" => $animal->porte->nomePorte ?? "N/A",
                    "especie" => $animal->especie->nomeEspecie ?? "N/A",
                    "pelagem" => $animal->pelagem->pelagemAnimal ?? "N/A",
                    "status" => [
                        "id" => $animal->status->idStatusAnimal,
                        "nome" => $animal->status->descStatusAnimal
                    ],
                    "interesses_count" => $animal->interesses_count,
                    "usuarios_interessados" => $usuariosInteressados,
                    "imagens" => $animal->imagensAnimal ? [
                        "img1" => $animal->imagensAnimal->img1Animal,
                        "img2" => $animal->imagensAnimal->img2Animal,
                        "img3" => $animal->imagensAnimal->img3Animal,
                        "img4" => $animal->imagensAnimal->img4Animal
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Erro ao buscar detalhes do animal: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método simplificado para alterar status do animal
     * Permite apenas as transições: Adoção -> Análise -> Adotado
     * E permite voltar de Análise para Adoção
     */
    public function alterarStatus(Request $request, $id)
    {
        $request->validate([
            "acao" => "required|in:colocar_analise,marcar_adotado,voltar_adocao"
        ]);

        $animal = Animal::where("idAnimal", $id)
            ->where("idOng", auth("ong")->user()->idOng)
            ->firstOrFail();

        $statusAtual = $animal->idStatusAnimal;
        $novoStatus = null;
        $mensagem = "";

        switch ($request->acao) {
            case "colocar_analise":
                if ($statusAtual == 3) { // Se está em Adoção
                    $novoStatus = 5; // Colocar em Análise
                    $mensagem = "Animal colocado em análise com sucesso!";
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Só é possível colocar em análise animais que estão para adoção."
                    ], 400);
                }
                break;

            case "marcar_adotado":
                if ($statusAtual == 5) { // Se está em Análise
                    $novoStatus = 4; // Marcar como Adotado
                    $mensagem = "Animal marcado como adotado com sucesso!";
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Só é possível marcar como adotado animais que estão em análise."
                    ], 400);
                }
                break;

            case "voltar_adocao":
                if ($statusAtual == 5) { // Se está em Análise
                    $novoStatus = 3; // Voltar para Adoção
                    $mensagem = "Animal retornado para adoção com sucesso!";
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Só é possível retornar para adoção animais que estão em análise."
                    ], 400);
                }
                break;
        }

        if ($novoStatus) {
            $animal->idStatusAnimal = $novoStatus;
            $animal->save();

            $statusObj = StatusAnimal::find($novoStatus);

            return response()->json([
                "success" => true,
                "message" => $mensagem,
                "novoStatus" => $statusObj->descStatusAnimal,
                "novoStatusId" => $novoStatus
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Ação não permitida."
        ], 400);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $animal = Animal::findOrFail($id);

            // Verifica se o animal pertence à ONG autenticada
            if (auth("ong")->user()->idOng !== $animal->idOng) {
                abort(403, "Acesso não autorizado");
            }

            // 1. Primeiro exclua as imagens associadas
            if ($animal->imagensAnimal) {
                // Remove os arquivos de imagem do storage
                for ($i = 1; $i <= 4; $i++) {
                    $field = "img{$i}Animal";
                    if ($animal->imagensAnimal->$field) {
                        $imagePath = public_path("img/imgAnimal/" . $animal->imagensAnimal->$field);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                }

                // Remove o registro das imagens
                $animal->imagensAnimal->delete();
            }

            // Remove a imagem principal
            if ($animal->imgPrincipal) {
                $imagePath = public_path("img/imgAnimal/" . $animal->imgPrincipal);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // 2. Depois exclua o histórico se existir
            if ($animal->historicoAnimal) {
                $animal->historicoAnimal()->delete();
            }

            // 3. Finalmente exclua o animal
            $animal->delete();

            DB::commit();

            return redirect()->route("pets.index")
                ->with("success", "Animal excluído com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", "Erro ao excluir animal: " . $e->getMessage());
        }
    }
}

