<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('razao_social')->nullable()->after('nome');
            $table->string('cnpj')->nullable()->after('razao_social');
            $table->string('cpf')->nullable()->after('cnpj');
            $table->text('endereco_completo')->nullable()->after('telefone');
            $table->json('emails_responsaveis')->nullable()->after('endereco_completo');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['razao_social', 'cnpj', 'cpf', 'endereco_completo', 'emails_responsaveis']);
        });
    }
};
