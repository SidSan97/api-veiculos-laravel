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
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->string('modelo');
            $table->string('cor');
            $table->integer('ano');
            $table->string('placa');
            $table->enum('estado', ['novo', 'semi-novo']);
            $table->decimal('preco', 10, 2);
            $table->float('km');
            $table->string('transmissao');
            $table->string('motor');
            $table->text('observacoes')->nullable();
            $table->string('imagem')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veiculos');
    }
};
