<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servico_hora_registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('cascade');
            $table->foreignId('colaborador_id')->nullable()->constrained('colaboradores')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('monitoramento', ['check_in', 'check_out', 'pausa', 'retorno', 'ajuste'])->default('check_in');
            $table->dateTime('horario');
            $table->integer('tempo_corrido_minutos')->nullable();
            $table->string('motivo')->nullable();
            $table->text('justificativa')->nullable();
            $table->boolean('ajuste_manual')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servico_hora_registros');
    }
};
