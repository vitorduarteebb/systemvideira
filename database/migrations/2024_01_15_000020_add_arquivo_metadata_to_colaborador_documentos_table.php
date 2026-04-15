<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colaborador_documentos', function (Blueprint $table) {
            $table->string('arquivo_nome_original')->nullable()->after('arquivo_path');
            $table->string('arquivo_mime')->nullable()->after('arquivo_nome_original');
            $table->unsignedBigInteger('arquivo_tamanho')->nullable()->after('arquivo_mime');
            $table->string('caminho_relativo')->nullable()->after('arquivo_tamanho');
        });
    }

    public function down(): void
    {
        Schema::table('colaborador_documentos', function (Blueprint $table) {
            $table->dropColumn([
                'arquivo_nome_original',
                'arquivo_mime',
                'arquivo_tamanho',
                'caminho_relativo',
            ]);
        });
    }
};

