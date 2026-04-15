<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('colaboradores', 'user_id')) {
            return;
        }

        $exists = collect(DB::select("SHOW INDEX FROM `colaboradores` WHERE Key_name = 'colaboradores_user_id_unique'"))->isNotEmpty();
        if ($exists) {
            return;
        }

        Schema::table('colaboradores', function (Blueprint $table) {
            $table->unique('user_id', 'colaboradores_user_id_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('colaboradores', 'user_id')) {
            return;
        }

        $exists = collect(DB::select("SHOW INDEX FROM `colaboradores` WHERE Key_name = 'colaboradores_user_id_unique'"))->isNotEmpty();
        if (! $exists) {
            return;
        }

        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropUnique('colaboradores_user_id_unique');
        });
    }
};
