<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaborador_pastas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colaborador_id')->constrained('colaboradores')->onDelete('cascade');
            $table->string('nome');
            $table->string('caminho_relativo');
            $table->timestamps();

            $table->unique(['colaborador_id', 'caminho_relativo'], 'uniq_colaborador_pasta_caminho');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaborador_pastas');
    }
};

