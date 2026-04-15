<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametros_precificacao', function (Blueprint $table) {
            $table->id();
            $table->decimal('custo_mo_hora', 10, 2)->default(55);
            $table->decimal('aliquota_impostos', 5, 2)->default(12);
            $table->decimal('taxa_adm_fixa', 5, 2)->default(2);
            $table->decimal('refeicao_diaria_pessoa', 10, 2)->default(50);
            $table->decimal('pernoite_diaria_pessoa', 10, 2)->default(175);
            $table->decimal('locacao_veiculo_diaria', 10, 2)->default(100);
            $table->timestamps();
        });

        // Inserir registro padrão
        DB::table('parametros_precificacao')->insert([
            'custo_mo_hora' => 55,
            'aliquota_impostos' => 12,
            'taxa_adm_fixa' => 2,
            'refeicao_diaria_pessoa' => 50,
            'pernoite_diaria_pessoa' => 175,
            'locacao_veiculo_diaria' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('parametros_precificacao');
    }
};
