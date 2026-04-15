<?php

namespace App\Console\Commands;

use App\Models\PlantaEquipamentoMarcador;
use Illuminate\Console\Command;

class PlantaExpirarManutencaoMensalCommand extends Command
{
    protected $signature = 'planta:expirar-manutencao-mensal';

    protected $description = 'Volta marcadores da planta (verde) para pendente após 1 mês da data de realização';

    public function handle(): int
    {
        $n = PlantaEquipamentoMarcador::aplicarVencimentoManutencaoGlobal();
        $this->info("Marcadores atualizados para manutenção pendente: {$n}");

        return self::SUCCESS;
    }
}
