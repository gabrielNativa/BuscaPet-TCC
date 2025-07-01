<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampanhaController;
use App\Http\Controllers\PostAnimalPerdidoController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AnimalApiController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\HistoricoAnimalController;
use App\Http\Controllers\ImagensAnimalController;
use App\Http\Controllers\AnimalAdocaoController;
use App\Http\Controllers\PreferenciaController;
use App\Http\Controllers\OpcoesController;
use App\Http\Controllers\ReelsController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\InteresseController;
use App\Http\Controllers\NotificacaoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [AuthUserController::class, 'login']);
Route::get('/usuario/{id}', [UserApiController::class, 'show']);
Route::middleware('auth:sanctum')->get('/usuario', [UserApiController::class, 'index']);
Route::post('/usuario', [UserApiController::class, 'store']);
Route::put('/usuario/{id}', [UserApiController::class, 'update']);
Route::delete('/usuario/{id}', [UserApiController::class, 'destroy']);
Route::get('/historico', [HistoricoAnimalController::class, 'index']);

Route::post('/historico', [HistoricoAnimalController::class, 'store']);

Route::put('/usuario/{id}/password', [UserApiController::class, 'updatePassword'])->middleware('auth:sanctum');
Route::post('/check-existence', [UserApiController::class, 'checkExistence']);
Route::put('/usuario/{id}/foto', [UserApiController::class, 'updatePhoto']);
Route::get('/reels/user/{id}', [ReelsController::class, 'getReelsByUser']);
Route::get('/posts/user/{id}', [PostAnimalPerdidoController::class, 'getPostsByUser']);
// routes/api.php
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/animais/pesquisar', [AnimalAdocaoController::class, 'pesquisarAnimais']);
Route::get('/posts/pesquisar', [PostController::class, 'pesquisarPost']);

Route::get('/posts', [PostController::class, 'indexApi']);
Route::get('/posts/categorias', [PostController::class, 'getCategorias']);
Route::get('/posts/categorias/{categoriaId}', [PostController::class, 'getPostsByCategoria']);

//comentarios
Route::prefix('posts')->group(function () {
    // Rota pública
    Route::get('/pesquisar', [PostController::class, 'pesquisarPost']);
    Route::get('/{post}/comments', [PostController::class, 'getComments']);

    // Rotas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/{post}/like', [PostController::class, 'likePost']);
        Route::post('/{post}/comment', [PostController::class, 'commentOnPost']);
        Route::get('/{post}/check-like-status', [PostController::class, 'checkLikeStatus']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/{post}/denunciar', [DenunciaController::class, 'denunciar']);
});

Route::prefix('imagens-animal')->group(function () {
    Route::post('/', [ImagensAnimalController::class, 'store']);
    Route::get('/{idAnimal}', [ImagensAnimalController::class, 'show']);
    Route::delete('/{idAnimal}/remover', [ImagensAnimalController::class, 'destroyImage']);
});

Route::prefix('animais')->group(function () {
    Route::get('/', [AnimalApiController::class, 'index']);
    Route::post('/', [AnimalApiController::class, 'store']);
    Route::get('/{id}', [AnimalApiController::class, 'show']);
});

Route::get('/status/{status}', [AnimalApiController::class, 'index']);
Route::get('/especie/{especie}', [AnimalApiController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Rotas para Posts de Animais Perdidos
|--------------------------------------------------------------------------
*/

Route::get('/especies', [AnimalApiController::class, 'getEspecies']);
Route::get('/racas', [AnimalApiController::class, 'getRacas']);
Route::get('/portes', [AnimalApiController::class, 'getPortes']);
Route::get('/pelagens', [AnimalApiController::class, 'getPelagens']);
Route::get('/status-animais', [AnimalApiController::class, 'getStatusAnimais']);

Route::middleware('auth:sanctum')->delete('/comments/{commentId}', [PostController::class, 'deleteComment']);


// Rotas de Interesse
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/interesse/verificar', [InteresseController::class, 'verificarInteresse']);
    Route::post('/interesse/registrar', [InteresseController::class, 'registrarInteresse']);
    Route::post('/interesse/cancelar', [InteresseController::class, 'cancelarInteresse']);
    Route::get('/interesse/usuario', [InteresseController::class, 'listarInteressesUsuario']);
});

// Rotas de Notificação (para ONGs)
Route::middleware('auth:ong')->group(function () {
    Route::get('/notificacoes', [NotificacaoController::class, 'index']);
    Route::get('/notificacoes/count', [NotificacaoController::class, 'contarNaoLidas']);
    Route::get('/notificacoes/recentes', [NotificacaoController::class, 'recentes']);
    Route::post('/notificacoes/{id}/marcar-lida', [NotificacaoController::class, 'marcarComoLida']);
    Route::post('/notificacoes/marcar-todas-lidas', [NotificacaoController::class, 'marcarTodasComoLidas']);
    Route::delete('/notificacoes/{id}', [NotificacaoController::class, 'destroy']);
    Route::get('/interesse/ong', [InteresseController::class, 'listarInteressesOng']);
});
Route::get('/posts-animais-perdidos', [PostAnimalPerdidoController::class, 'index']);
Route::get('/posts-animais-perdidos/{id}', [PostAnimalPerdidoController::class, 'show']);


Route::post('/posts-animais-perdidos', [PostAnimalPerdidoController::class, 'store']);


Route::put('/posts-animais-perdidos/{id}', [PostAnimalPerdidoController::class, 'update']);
Route::delete('/posts-animais-perdidos/{id}', [PostAnimalPerdidoController::class, 'destroy']);


Route::get('/usuario/{idUser}/posts-animais-perdidos', [PostAnimalPerdidoController::class, 'index']);
Route::get('/animal/{idAnimal}/posts-animais-perdidos', [PostAnimalPerdidoController::class, 'index']);


    Route::get('animais-para-adocao', [AnimalAdocaoController::class, 'index']);

    Route::get('/racas-por-especie', [AnimalApiController::class, 'getRacasByEspecie']);

    
// Rotas públicas de reels
Route::get('/reels', [ReelsController::class, 'index']);
Route::get('/reels/buscar-reels', [ReelsController::class, 'buscarReels']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reels/{id}', [ReelsController::class, 'show']);
    Route::get('/reels/{id}/comments', [ReelsController::class, 'getComments']);
    Route::get('/reels/{id}/check-like-status', [ReelsController::class, 'checkLikeStatus']);

    Route::post('/reels', [ReelsController::class, 'store']);
    Route::post('/reels/{id}/like', [ReelsController::class, 'like']);
    Route::post('/reels/{id}/comment', [ReelsController::class, 'comment']);
    Route::delete('/reels/{reelId}/comments/{commentId}', [ReelsController::class, 'deleteComment']);
});



// routes/api.php

Route::prefix('opcoes')->group(function () {
    // Listar todas as espécies
    Route::get('/especies', [OpcoesController::class, 'listarEspecies']);
    
    // Listar raças por espécie
    Route::get('/racas/{idEspecie}', [OpcoesController::class, 'listarRacasPorEspecie']);
    
    // Listar todas as pelagens
    Route::get('/pelagens', [OpcoesController::class, 'listarPelagens']);
    
    // Listar todos os portes
    Route::get('/portes', [OpcoesController::class, 'listarPortes']);
    
    // Listar todos os status
    Route::get('/status', [OpcoesController::class, 'listarStatus']);
});

// Rotas de verificação de email
Route::post('/send-verification', [VerificationController::class, 'sendVerificationCode']);
Route::post('/verificar', [VerificationController::class, 'verifyCode']);
Route::post('/enviar-codigo', [PasswordResetController::class, 'enviarCodigo']);
Route::post('/redefinir-senha', [PasswordResetController::class, 'redefinirSenha']);




//ignore
Route::get('/ongs/pending/count', function() {
    return response()->json([
        'count' => \App\Models\Ong::where('status', 'pending')->count()
    ]);
})->middleware('auth');

Route::middleware('auth:sanctum')->get('/dash', [UserApiController::class, 'dash']);
