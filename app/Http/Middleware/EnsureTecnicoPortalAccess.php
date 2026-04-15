<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTecnicoPortalAccess
{
    /** @var list<string> */
    private const ALLOWED_ROUTE_NAMES = [
        'crm.agenda',
        'crm.relatorios.relato',
        'crm.relatorios.anexos',
        'crm.relatorios.anexos.destroy',
        'crm.relatorios.horas',
        'crm.relatorios.questionarios.vincular',
        'crm.relatorios.questionarios.respostas',
        'crm.colaborador.servicos.iniciar',
        'crm.colaborador.execucao',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->isTecnico()) {
            return $next($request);
        }

        $name = $request->route()?->getName();
        if ($name && in_array($name, self::ALLOWED_ROUTE_NAMES, true)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('warning', 'Esta área não está disponível para o seu perfil.');
    }
}
