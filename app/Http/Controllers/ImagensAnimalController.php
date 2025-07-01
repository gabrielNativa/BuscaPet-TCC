<?php

namespace App\Http\Controllers;

use App\Models\ImagensAnimal;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImagensAnimalController extends Controller
{
    /**
     * Salva a imagem principal e cria registro para imagens adicionais
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
           // No ImagensAnimalController
$validator = Validator::make($request->all(), [
    'image' => 'required|mimetypes:image/jpeg,image/png,image/jpg|max:2048',
    'idAnimal' => 'required|exists:tbanimal,idAnimal'
], [
    'image.mimetypes' => 'O arquivo deve ser uma imagem (JPEG, PNG ou JPG)',
    'idAnimal.exists' => 'Animal não encontrado'
]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Processar upload da imagem
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = 'img/animais/' . $imageName;
            
            // Criar diretório se não existir
            if (!file_exists(public_path('img/animais'))) {
                mkdir(public_path('img/animais'), 0755, true);
            }
            
            $image->move(public_path('img/animais'), $imageName);

            // Criar registro das imagens
            $imagensAnimal = new ImagensAnimal();
            $imagensAnimal->idAnimal = $request->idAnimal;
            $imagensAnimal->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'imagePath' => $path,
                'message' => 'Imagem principal salva com sucesso'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar imagem: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar imagem',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno'
            ], 500);
        }
    }

    /**
     * Salva as imagens adicionais do animal (img1Animal a img4Animal)
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
            
            try {
                if ($request->hasFile($campo)) {
                    // Remove imagem antiga se existir
                    if ($imagensAnimal->$campo && file_exists(public_path($imagensAnimal->$campo))) {
                        @unlink(public_path($imagensAnimal->$campo));
                    }

                    $image = $request->file($campo);
                    $imageName = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $image->getClientOriginalName());
                    
                    $destinationPath = public_path('img/imgAnimais');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    
                    $image->move($destinationPath, $imageName);
                    $imagensAnimal->$campo = 'img/imgAnimais/' . $imageName;
                }
            } catch (\Exception $e) {
                Log::error("Erro ao salvar imagem {$campo}: " . $e->getMessage());
                continue;
            }
        }

        $imagensAnimal->save();
        return $imagensAnimal;
    }

    /**
     * Atualizar a imagem principal e as imagens adicionais
     */
    public function atualizarImagensAnimal(Request $request, $idAnimal)
    {
        DB::beginTransaction();
        
        try {
            $animal = Animal::findOrFail($idAnimal);
            $animalData = [];

            // Processar imagem principal
            if ($request->hasFile('imgPrincipal')) {
                // Remove imagem antiga se existir
                if ($animal->imgPrincipal && file_exists(public_path($animal->imgPrincipal))) {
                    unlink(public_path($animal->imgPrincipal));
                }

                $image = $request->file('imgPrincipal');
                $imageName = time() . '_principal_' . $image->getClientOriginalName();
                $image->move(public_path('img'), $imageName);
                $animalData['imgPrincipal'] = 'img/' . $imageName;
            }

            // Atualizar animal
            if (!empty($animalData)) {
                $animal->update($animalData);
            }

            // Processar imagens adicionais
            $imagensAnimal = $this->salvarImagensAdicionais($idAnimal, $request);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Imagens atualizadas com sucesso',
                'data' => [
                    'animal' => $animal,
                    'imagens' => $imagensAnimal
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar imagens: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar imagens',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno'
            ], 500);
        }
    }

    /**
     * Recupera as imagens adicionais de um animal
     */
    public function show($idAnimal)
    {
        try {
            $imagens = ImagensAnimal::where('idAnimal', $idAnimal)->first();

            if (!$imagens) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Nenhuma imagem adicional encontrada'
                ]);
            }

            // Transforma os caminhos em URLs completas
            $imagens->img1Animal = $imagens->img1Animal ? asset($imagens->img1Animal) : null;
            $imagens->img2Animal = $imagens->img2Animal ? asset($imagens->img2Animal) : null;
            $imagens->img3Animal = $imagens->img3Animal ? asset($imagens->img3Animal) : null;
            $imagens->img4Animal = $imagens->img4Animal ? asset($imagens->img4Animal) : null;

            return response()->json([
                'success' => true,
                'data' => $imagens
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recuperar imagens',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove uma imagem específica
     */
    public function destroyImage(Request $request, $idAnimal)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'campo_imagem' => 'required|in:img1Animal,img2Animal,img3Animal,img4Animal'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campo de imagem inválido'
                ], 400);
            }

            $campo = $request->campo_imagem;
            $imagens = ImagensAnimal::where('idAnimal', $idAnimal)->firstOrFail();

            if ($imagens->$campo) {
                // Remove do storage
                $filePath = public_path($imagens->$campo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Atualiza no banco
                $imagens->$campo = null;
                $imagens->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Imagem removida com sucesso'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao remover imagem: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover imagem',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}