<?php
/**
 * Gerar APP_KEY diretamente no .env
 * Execute: php gerar_chave_direto.php
 */

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    die("✗ .env não existe!\n");
}

// Gerar chave
$key = 'base64:' . base64_encode(random_bytes(32));

// Ler .env
$content = file_get_contents($envFile);

// Substituir ou adicionar APP_KEY
if (preg_match('/^APP_KEY=.*$/m', $content)) {
    $content = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=$key", $content);
} else {
    $content = "APP_KEY=$key\n" . $content;
}

// Salvar
file_put_contents($envFile, $content);

echo "✓ APP_KEY gerado: $key\n";
echo "✓ .env atualizado\n";
