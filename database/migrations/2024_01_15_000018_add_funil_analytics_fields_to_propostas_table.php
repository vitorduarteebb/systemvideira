<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propostas', function (Blueprint $table) {
            $table->text('motivo_ganho')->nullable()->after('data_fechamento');
            $table->text('motivo_perda')->nullable()->after('motivo_ganho');
            $table->text('motivo_negociacao')->nullable()->after('motivo_perda');
        });
    }

    public function down(): void
    {
        Schema::table('propostas', function (Blueprint $table) {
            $table->dropColumn(['motivo_ganho', 'motivo_perda', 'motivo_negociacao']);
        });
    }
};
