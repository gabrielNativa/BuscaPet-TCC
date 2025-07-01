<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthOngController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OngController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalOngController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminOngController;
use App\Http\Controllers\RacaController;

Route::get("/", function () {
    return redirect()->route("login");
});
Route::get("/racas/{idEspecie}", [AnimalController::class, "getRacasPorEspecie"]);
Route::get("/ong/{id}/edit", [OngController::class, "edit"])->name("ong.edit");
Route::get("/ong/profile", [OngController::class, "profile"])->name("ong.profile")->middleware("auth:ong");
Route::get("/racas", [RacaController::class, "index"])->name("races.index");
Route::post("/racas", [RacaController::class, "store"])->name("races.store");
Route::get("/racas/{id}", [RacaController::class, "show"])->name("races.show");
Route::put("/racas/{id}", [RacaController::class, "update"])->name("races.update");
Route::delete("/racas/{id}", [RacaController::class, "destroy"])->name("races.destroy");
Route::get("/pets/{id}/edit-images", [AnimalController::class, "editImages"]);


Route::get("/ong/cadastro", [OngController::class, "create"])->name("ong.register");
Route::get("/ong/login", [AuthOngController::class, "showLoginForm"])->name("ong.login");
Route::middleware("guest")->group(function () {
    Route::get("/login", [AuthController::class, "showLoginForm"])->name("login");
    Route::post("/login", [AuthController::class, "login"])->name("login.submit");
});




Route::put("/ong/{id}", [OngController::class, "update"])->name("ong.update");
Route::prefix("ong")->group(function () {
 
        Route::get("/cadastrar", [OngController::class, "create"])->name("ong.create");
        Route::post("/cadastrar", [OngController::class, "store"])->name("ong.store");
        Route::get("/ong/register/success", [OngController::class, "success"])->name("ong.register.success");
        Route::get("/login", [AuthOngController::class, "showLoginForm"])->name("ong.login");
        Route::post("/login", [AuthOngController::class, "login"])->name("ong.login.submit");


    Route::middleware("auth:ong")->group(function () {
        Route::post("/logout", [AuthOngController::class, "logout"])->name("ong.logout");
        Route::get("/dashboard", [DashboardController::class, "index"])->name("ong.dashboard");
        Route::resource("posts", PostController::class);
        Route::get("/pets/{id}/upload-images", [AnimalController::class, "showUploadImages"])->name("pets.uploadImages");
        Route::post("/pets/{id}/save-images", [AnimalController::class, "saveImages"])->name("pets.saveImages");
        Route::put("/pets/{id}/save-images", [AnimalController::class, "saveImages"])->name("pets.saveImages");
    });
});



Route::delete("/posts/{post}/admin-destroy", [PostController::class, "destroyFromAdmin"])->name("posts.destroy.admin");
Route::delete("/posts/{post}/", [PostController::class, "destroy"])->name("posts.destroy");


Route::prefix("admin")->middleware("auth")->group(function () {

    Route::get("/", [AdminController::class, "index"])->name("admin.index");
    Route::get("/ongs/pendentes", [AdminOngController::class, "pendente"])
        ->name("admin.ongs.pendente");
    Route::post("/ongs/{id}/aprovar", [AdminOngController::class, "aprovar"])
        ->name("admin.ongs.aprovar");
        Route::post("ongs/rejeitar/{id}", [AdminOngController::class, "rejeitar"])
        ->name("admin.ongs.rejeitar");
        Route::get("/ongs", [OngController::class, "index"])->name("admin.ongs.index");
        Route::post("/ongs/{id}/rejeitar", [OngController::class, "rejeitar"])->name("admin.ongs.rejeitar");
        Route::post("/ongs/{id}/aprovar", [OngController::class, "aprovar"])->name("admin.ongs.aprovar");
        Route::get("/usuario/{id}/atividades-json", [UserController::class, "getAtividades"]);
        Route::get("/create", [AdminController::class, "create"])->name("admin.create");
        Route::post("/store", [AdminController::class, "store"])->name("admin.store");
        Route::get("/{id}/edit", [AdminController::class, "edit"])->name("admin.edit");
        Route::put("/{admin}", [AdminController::class, "update"])->name("admin.update");
        Route::delete("/{admin}", [AdminController::class, "destroy"])->name("admin.destroy");

});


Route::middleware("auth")->group(function () {

    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
    Route::get("/home", [HomeController::class, "index"])->name("home");
    Route::resource("user", UserController::class);
    Route::patch("/user/{id}/block", [UserController::class, "block"])->name("user.block");
    Route::patch("/user/{id}/unblock", [UserController::class, "unblock"])->name("user.unblock");
    Route::get("/campanha", [PostController::class, "campanhasAdm"])->name("campanha");
    Route::get("/ongs", [OngController::class, "index"])->name("ong.index");

});

Route::middleware(["auth:ong"])->group(function () {

        Route::prefix("pets")->group(function () {
            Route::get("/", [AnimalController::class, "index"])->name("pets.index"); // Lista todos pets
            Route::get("/cadastrar", [AnimalController::class, "create"])->name("pets.create"); // Formulário de cadastro
            Route::post("/", [AnimalController::class, "store"])->name("pets.store"); // Salvar novo pet
            Route::post("/{id}/images", [AnimalController::class, "storeImages"])->name("pets.storeImages");
            Route::get("/{id}/editar", [AnimalController::class, "edit"])->name("pets.edit"); // Formulário de edição
            Route::put("/{id}", [AnimalController::class, "update"])->name("pets.update"); // Atualizar pet
            Route::post("/{id}/update-images", [AnimalController::class, "updateImages"])->name("pets.updateImages");
            Route::delete("/{id}", [AnimalController::class, "destroy"])->name("pets.destroy"); // Excluir pet
            
            // Rotas para o modal de detalhes e alteração de status
            Route::get("/{id}/detalhes", [AnimalController::class, "getDetalhes"])->name("pets.detalhes");
            Route::post("/{id}/alterar-status", [AnimalController::class, "alterarStatus"])->name("pets.alterarStatus");
        });
    });

  

// Rota Pública
Route::get("racas/{idEspecie}", [EspecieController::class, "getRacasByEspecie"])->name("racas.byEspecie");

// Rota de Sucesso de Cadastro
Route::get("/ong/register/success", function () {
    return view("ong.register_success");
})->name("ong.register.success");

Route::get("/posts/{post}/comments", [PostController::class, "getComments"])->name("posts.comments");
Route::get("/posts/{post}/likes", [PostController::class, "getLikes"])->name("posts.likes");

