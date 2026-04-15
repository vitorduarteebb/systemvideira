<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('nome')->nullable();
            $table->string('tag')->nullable();
            $table->enum('tipo_unidade', ['condensadora', 'evaporadora', 'split', 'chiller', 'ar_condicionado', 'outro'])->default('condensadora');
            $table->string('capacidade_btus')->nullable();
            $table->date('ultima_manutencao')->nullable();
            $table->string('localizacao')->nullable();
            $table->text('observacoes_tecnicas')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
