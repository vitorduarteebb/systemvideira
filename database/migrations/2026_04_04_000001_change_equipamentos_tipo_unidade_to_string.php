<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE equipamentos MODIFY tipo_unidade VARCHAR(64) NOT NULL DEFAULT "condensadora"');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE equipamentos MODIFY tipo_unidade ENUM('condensadora','evaporadora','split','chiller','ar_condicionado','outro') NOT NULL DEFAULT 'condensadora'");
        }
    }
};
