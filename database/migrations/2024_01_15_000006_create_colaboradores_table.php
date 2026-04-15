<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->id();
            $table->string('nome_profissional');
            $table->enum('departamento', ['operacional', 'comercial', 'administrativo', 'tecnico', 'outro'])->default('operacional');
            $table->decimal('valor_hora', 10, 2)->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('cpf')->nullable()->unique();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaboradores');
    }
};
