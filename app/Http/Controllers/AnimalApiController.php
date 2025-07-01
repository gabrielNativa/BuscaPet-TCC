<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\Porte;
use App\Models\Pelagem;
use App\Models\StatusAnimal;

class AnimalApiController extends Controller
{

    public function getEspecies() {
        return response()->json(Especie::all());
    }
    
    public function getRacas() {
        return response()->json(Raca::all());
    }
    
    public function getPortes() {
        return response()->json(Porte::all());
    }
    
    public function getPelagens() {
        return response()->json(Pelagem::all());
    }
    
    public function getStatusAnimais() {
        return response()->json(StatusAnimal::all());
    }
    /**
     * Cadastra um novo animal com imagens
     */
    /**
     * Cadastra um novo animal com imagens
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {

            $validator = Validator::make($request->all(), [
                'idRaca' => 'required|integer|exists:tbraca,idRaca',
                'nomeAnimal' => 'required|string|max:40',
                'idPorte' => 'required|integer|exists:tbporte,idPorte',
                'idEspecie' => 'required|integer|exists:tbespecie,idEspecie',
                'idPelagemAnimal' => 'required|integer|exists:tbpelagemanimal,idPelagemAnimal',
                'idadeAnimal' => 'required|string|max:30',
                'bioAnimal' => 'required|string|max:100',
                'imgPrincipal' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $animalData = [
                'idRaca' => $request->idRaca,
                'nomeAnimal' => $request->nomeAnimal,
                'idPorte' => $request->idPorte,
                'idEspecie' => $request->idEspecie,
                'idPelagemAnimal' => $request->idPelagemAnimal,
                'idadeAnimal' => $request->idadeAnimal,
                'bioAnimal' => $request->bioAnimal,
                'idStatusAnimal' => 1,
            ];
    

            if ($request->hasFile('imgPrincipal')) {
                $image = $request->file('imgPrincipal');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('img'), $imageName);
                $animalData['imgPrincipal'] = 'img/' . $imageName; 
            }
    
            $animal = Animal::create($animalData);
    
            DB::commit();
    
            $animal->load(['raca', 'porte', 'especie', 'pelagem', 'status']);
    
            return response()->json([
                'success' => true,
                'message' => 'Animal cadastrado com sucesso',
                'data' => $animal
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar animal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar animal',
                'error' => config('app.debug') ? $e->getMessage() : 'Ocorreu um erro interno'
            ], 500);
        }
    }
    
    

    /**
     * Armazena uma imagem no storage e retorna o caminho
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $folder
     * @return string
     */


    /**
     * Processa o upload e armazenamento das imagens
     */
    

    /**
     * Lista animais com paginação
     */
    public function index(Request $request)
    {
        
        try {
            $query = Animal::with(['raca', 'porte', 'especie', 'pelagem', 'status'])
                ->orderBy('idAnimal', 'desc');

            // Filtro por status
            if ($request->has('status')) {
                $query->where('idStatusAnimal', $request->status);
            }

            // Filtro por espécie
            if ($request->has('especie')) {
                $query->where('idEspecie', $request->especie);
            }

            $animais = $query->paginate($request->per_page ?? 10);

            return response()->json([
                'success' => true,
                'data' => $animais,
                'message' => 'Animais listados com sucesso'
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Erro ao listar animais', $e);
        }
    }
    // AnimalApiController.php




    /**
     * Mostra um animal específico
     */
    public function show($id)
    {
        try {
            $animal = Animal::with(['raca', 'porte', 'especie', 'pelagem', 'status', 'historicos', 'postsPerdidos', 'postsAdocao'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $animal,
                'message' => 'Animal encontrado com sucesso'
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Animal não encontrado', $e, 404);
        }
    }

    /**
     * Resposta padronizada para erros
     */
    private function errorResponse($message, \Exception $e, $code = 500)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'error' => $e->getMessage()
        ];

        if (config('app.debug')) {
            $response['trace'] = $e->getTrace();
        }

        return response()->json($response, $code);
    }
    public function getRacasByEspecie(Request $request)
{
    $idEspecie = $request->query('idEspecie');

    if (!$idEspecie) {
        return response()->json([
            'success' => false,
            'message' => 'idEspecie é obrigatório.'
        ], 400);
    }

    $racas = \App\Models\Raca::where('idEspecie', $idEspecie)->get();

    return response()->json($racas);
}

}
