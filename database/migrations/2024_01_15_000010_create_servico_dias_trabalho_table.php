<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servico_dias_trabalho', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('cascade');
            $table->integer('dia_numero'); // D1, D2, D3...
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->integer('intervalo_minutos')->default(60);
            $table->boolean('escalavel')->default(true);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servico_dias_trabalho');
    }
};
