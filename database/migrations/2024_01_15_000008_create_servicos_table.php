<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_ve')->nullable();
            $table->text('descricao')->nullable();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('equipamento_id')->nullable()->constrained('equipamentos')->onDelete('set null');
            $table->decimal('faturamento_estimado', 10, 2)->default(0);
            $table->date('data_inicio');
            $table->enum('status_operacional', ['pendente', 'em_andamento', 'pausado', 'concluido', 'cancelado'])->default('pendente');
            $table->integer('duracao_dias')->default(1);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};
