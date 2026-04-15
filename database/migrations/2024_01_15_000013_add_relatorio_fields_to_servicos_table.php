<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicos', function (Blueprint $table) {
            // Informações do relatório
            $table->string('numero_os')->nullable()->after('codigo_ve');
            $table->string('tipo_tarefa')->nullable()->after('numero_os'); // PMOC, Manutenção, etc.
            $table->text('orientacao')->nullable()->after('tipo_tarefa');
            
            // Horários
            $table->datetime('horario_agendamento')->nullable()->after('data_inicio');
            $table->datetime('horario_chegada')->nullable();
            $table->datetime('horario_saida')->nullable();
            $table->datetime('horario_inicio_execucao')->nullable();
            $table->datetime('horario_fim_execucao')->nullable();
            $table->time('inicio_deslocamento')->nullable();
            $table->integer('duracao_deslocamento_minutos')->nullable();
            
            // Relato e checklist
            $table->text('relato_execucao')->nullable();
            $table->json('checklist_pmoc')->nullable(); // Armazenar checklist como JSON
            
            // Assinatura
            $table->string('assinatura_usuario_id')->nullable();
            $table->text('assinatura_base64')->nullable();
            
            // Fotos (armazenar paths ou JSON com múltiplas fotos)
            $table->json('fotos')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn([
                'numero_os',
                'tipo_tarefa',
                'orientacao',
                'horario_agendamento',
                'horario_chegada',
                'horario_saida',
                'horario_inicio_execucao',
                'horario_fim_execucao',
                'inicio_deslocamento',
                'duracao_deslocamento_minutos',
                'relato_execucao',
                'checklist_pmoc',
                'assinatura_usuario_id',
                'assinatura_base64',
                'fotos',
            ]);
        });
    }
};
