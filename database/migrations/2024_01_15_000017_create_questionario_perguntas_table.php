<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionario_perguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionario_id')->constrained('questionarios')->onDelete('cascade');
            $table->unsignedInteger('ordem')->default(1);
            $table->text('texto');
            $table->string('tipo_resposta')->default('texto');
            $table->boolean('resposta_obrigatoria')->default(false);
            $table->boolean('descricao_pergunta')->default(false);
            $table->timestamps();
        });

        Schema::create('servico_questionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('cascade');
            $table->foreignId('questionario_id')->constrained('questionarios')->onDelete('cascade');
            $table->json('respostas')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['servico_id', 'questionario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servico_questionarios');
        Schema::dropIfExists('questionario_perguntas');
    }
};
