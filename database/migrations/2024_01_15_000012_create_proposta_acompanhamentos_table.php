<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposta_acompanhamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained('propostas')->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('descricao');
            $table->date('data_retorno')->nullable();
            $table->date('data_evento')->nullable();
            $table->string('tipo')->default('acompanhamento'); // acompanhamento, retorno, fechamento, etc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposta_acompanhamentos');
    }
};
