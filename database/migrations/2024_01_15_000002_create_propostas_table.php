<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propostas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_proposta')->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('valor_final', 10, 2)->default(0);
            $table->enum('estado', ['primeiro_contato', 'em_analise', 'fechado', 'perdido'])->default('primeiro_contato');
            $table->string('titulo')->nullable();
            $table->text('descricao_inicial')->nullable();
            $table->text('configuracoes_tecnicas')->nullable();
            $table->date('data_criacao');
            $table->date('data_fechamento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propostas');
    }
};
