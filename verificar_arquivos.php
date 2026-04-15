<?php
/**
 * Verificar quais arquivos estão faltando
 * Acesse: https://yellow-spoonbill-121332.hostingersite.com/verificar_arquivos.php
 */

echo "<h1>Verificação de Arquivos</h1>";
echo "<pre>";

$arquivos_essenciais = [
    'index.php',
    '.htaccess',
    'artisan',
    'composer.json',
    'bootstrap/app.php',
    'app/Http/Kernel.php',
    'routes/web.php',
    'config/app.php',
    'config/database.php',
    'resources/views/auth/login.blade.php',
    'resources/views/dashboard.blade.php',
];

echo "Arquivos Essenciais:\n";
echo str_repeat("=", 60) . "\n";

$faltando = [];
foreach ($arquivos_essenciais as $arquivo) {
    if (file_exists($arquivo)) {
        echo "✓ $arquivo\n";
    } else {
        echo "✗ $arquivo (FALTANDO)\n";
        $faltando[] = $arquivo;
    }
}

echo "\n" . str_repeat("=", 60) . "\n";

if (empty($faltando)) {
    echo "\n✓ Todos os arquivos essenciais estão presentes!\n";
} else {
    echo "\n✗ Faltam " . count($faltando) . " arquivo(s) essencial(is):\n";
    foreach ($faltando as $arquivo) {
        echo "  - $arquivo\n";
    }
    echo "\n⚠ Faça upload de TODOS os arquivos do projeto!\n";
}

// Verificar pastas
echo "\nPastas:\n";
echo str_repeat("=", 60) . "\n";

$pastas = ['app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'storage', 'vendor'];
foreach ($pastas as $pasta) {
    if (is_dir($pasta)) {
        $arquivos = count(glob("$pasta/**/*", GLOB_BRACE));
        echo "✓ $pasta/ ($arquivos itens)\n";
    } else {
        echo "✗ $pasta/ (FALTANDO)\n";
    }
}

// Verificar .env
echo "\n.env:\n";
if (file_exists('.env')) {
    echo "✓ .env existe\n";
    $env = parse_ini_file('.env');
    echo "  APP_KEY: " . (empty($env['APP_KEY']) ? "✗ VAZIO" : "✓ Configurado") . "\n";
    echo "  DB_DATABASE: " . ($env['DB_DATABASE'] ?? 'não definido') . "\n";
} else {
    echo "✗ .env não existe\n";
}

echo "</pre>";
