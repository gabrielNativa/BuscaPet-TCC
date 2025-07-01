<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('tbcomentario', function (Blueprint $table) {
        // Renomear coluna se necessário
        $table->renameColumn('comment', 'texto');
        
        // Ou adicionar se não existir
        if (!Schema::hasColumn('tbcomentario', 'texto')) {
            $table->text('texto')->after('id');
        }
        
        // Garantir que as relações estão corretas
        $table->foreign('idUser')->references('idUser')->on('tbuser');
        $table->foreign('id')->references('id')->on('posts');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
