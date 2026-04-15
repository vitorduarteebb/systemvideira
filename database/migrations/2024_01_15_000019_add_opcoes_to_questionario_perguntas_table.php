<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questionario_perguntas', function (Blueprint $table) {
            $table->json('opcoes')->nullable()->after('tipo_resposta');
        });
    }

    public function down(): void
    {
        Schema::table('questionario_perguntas', function (Blueprint $table) {
            $table->dropColumn('opcoes');
        });
    }
};
