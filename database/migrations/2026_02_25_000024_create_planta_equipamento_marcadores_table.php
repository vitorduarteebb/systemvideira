<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planta_equipamento_marcadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planta_baixa_id')->constrained('plantas_baixas')->onDelete('cascade');
            $table->foreignId('equipamento_id')->constrained('equipamentos')->onDelete('cascade');
            $table->decimal('pos_x', 5, 2)->default(0); // percent 0-100
            $table->decimal('pos_y', 5, 2)->default(0);
            $table->enum('status', ['realizado', 'pendente', 'duplicado'])->default('pendente');
            $table->date('mes_ref')->nullable(); // mês de referência ex: 2026-02-01
            $table->timestamps();

            $table->unique(['planta_baixa_id', 'equipamento_id', 'mes_ref'], 'planta_equip_mes_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planta_equipamento_marcadores');
    }
};
