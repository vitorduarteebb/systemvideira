<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colaborador_documentos', function (Blueprint $table) {
            if (! Schema::hasColumn('colaborador_documentos', 'cert_proximo_alerta_em')) {
                $table->timestamp('cert_proximo_alerta_em')->nullable()->after('data_vencimento');
            }
            if (! Schema::hasColumn('colaborador_documentos', 'cert_vencido_alerta_em')) {
                $table->timestamp('cert_vencido_alerta_em')->nullable()->after('cert_proximo_alerta_em');
            }
        });
    }

    public function down(): void
    {
        Schema::table('colaborador_documentos', function (Blueprint $table) {
            if (Schema::hasColumn('colaborador_documentos', 'cert_proximo_alerta_em')) {
                $table->dropColumn('cert_proximo_alerta_em');
            }
            if (Schema::hasColumn('colaborador_documentos', 'cert_vencido_alerta_em')) {
                $table->dropColumn('cert_vencido_alerta_em');
            }
        });
    }
};
