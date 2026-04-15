<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('planta_equipamento_marcadores', 'realizado_em')) {
            return;
        }

        Schema::table('planta_equipamento_marcadores', function (Blueprint $table) {
            $table->timestamp('realizado_em')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('planta_equipamento_marcadores', 'realizado_em')) {
            return;
        }

        Schema::table('planta_equipamento_marcadores', function (Blueprint $table) {
            $table->dropColumn('realizado_em');
        });
    }
};
