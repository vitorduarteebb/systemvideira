<?php

/**
 * Entrada HTTP na raiz do projeto (pasta FTP "web" na Locaweb).
 *
 * vendor/, .env, app/, bootstrap/ ficam neste mesmo nível.
 * Arquivos públicos (build/, storage/) ficam em public/ — o .htaccess
 * redireciona /build/* e /storage/* para public/ antes de chegar aqui.
 */
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
