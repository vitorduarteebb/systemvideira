<?php

namespace App\Providers;

use App\Models\Colaborador;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar binding para 'files' que o ViewServiceProvider precisa
        $this->app->singleton('files', function () {
            return new \Illuminate\Filesystem\Filesystem();
        });
        
        // Registrar binding para 'cookie' que o EncryptCookies middleware precisa
        if (!$this->app->bound('cookie')) {
            $this->app->singleton('cookie', function ($app) {
                return new \Illuminate\Cookie\CookieJar();
            });
        }
    }

    public function boot(): void
    {
        // Projeto sem FoundationServiceProvider no config: Request::validate não existe por padrão
        if (! Request::hasMacro('validate')) {
            Request::macro('validate', function (array $rules, array $messages = [], array $customAttributes = []) {
                /** @var Request $this */
                $validator = Validator::make($this->all(), $rules, $messages, $customAttributes);
                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                return $validator->validated();
            });
        }

        View::composer('components.sidebar', function ($view) {
            $servicosExecucaoMenu = collect();
            $u = auth()->user();
            if ($u && $u->isTecnico()) {
                $c = Colaborador::resolveFromUser($u);
                if ($c) {
                    $servicosExecucaoMenu = Servico::query()
                        ->with('cliente')
                        ->whereHas('tecnicos', fn ($q) => $q->where('colaboradores.id', $c->id))
                        ->whereIn('status_operacional', ['em_andamento', 'pausado'])
                        ->orderByDesc('updated_at')
                        ->limit(15)
                        ->get();
                }
            }
            $view->with('servicosExecucaoMenu', $servicosExecucaoMenu);
        });
    }
}
