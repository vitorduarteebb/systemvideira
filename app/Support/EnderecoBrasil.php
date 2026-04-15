<?php

namespace App\Support;

/**
 * Heurística para agrupar endereços textuais por UF (logística / mapa administrativo).
 */
class EnderecoBrasil
{
    /** @var array<string, true> */
    private static array $ufs;

    public static function ufDeTexto(?string $texto): ?string
    {
        if ($texto === null || trim($texto) === '') {
            return null;
        }

        $s = mb_strtoupper($texto, 'UTF-8');

        self::$ufs ??= array_fill_keys([
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG',
            'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO',
        ], true);

        foreach (array_keys(self::$ufs) as $uf) {
            if (preg_match('/\b'.preg_quote($uf, '/').'\b/u', $s)) {
                return $uf;
            }
        }

        // Padrão comum: "Cidade/UF" ou " - UF"
        if (preg_match('#[/\-]\s*([A-Z]{2})\s*$#u', trim($s), $m)) {
            $c = $m[1];
            if (isset(self::$ufs[$c])) {
                return $c;
            }
        }

        return null;
    }
}
