<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->id();
            $table->string('fornecedor');
            $table->string('descricao');
            $table->string('categoria')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('grupo_duplicata')->nullable()->index();
            $table->unsignedInteger('parcela')->default(1);
            $table->unsignedInteger('total_parcelas')->default(1);
            $table->decimal('valor_total', 15, 2);
            $table->decimal('valor_pago', 15, 2)->default(0);
            $table->date('data_emissao')->nullable();
            $table->date('data_vencimento')->index();
            $table->date('data_pagamento')->nullable();
            $table->enum('status', ['aberto', 'parcial', 'pago', 'cancelado'])->default('aberto')->index();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_pagar');
    }
};
