<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Le chemin vers le fichier de configuration des routes.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * La constante qui définit la route vers laquelle l'utilisateur sera redirigé après la connexion.
     */
    const HOME = '/dashboard'; // Redirection vers le tableau de bord après la connexion

    /**
     * Bootstrap les services de l'application.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // Vous pouvez également définir des routes API ici si nécessaire
            Route::middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
        });
    }
}
