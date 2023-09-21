<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapApiAuthRoutes();
        
        /* $this->mapApiResourcesRoutes(); */
        
        $this->mapApiAppRoutes();

       /*  $this->mapApiTestsRoutes(); */

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiAppRoutes()
    {
        Route::prefix('api/app')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.app.php'));
    }

    /**
     * Define the "api/auth" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiAuthRoutes()
    {
        Route::prefix('api/auth')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.auth.php'));
    }

    /**
     * Define the "api/resources" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiResourcesRoutes()
    {
        Route::prefix('api/resources')
            ->middleware([
                'api',
                'verify.authorization.jwt'
            ])
            ->namespace($this->namespace . '\Resources')
            ->group(base_path('routes/api.resources.php'));
    }

    /**
     * Define the "api/tests" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiTestsRoutes()
    {
        Route::prefix('api/tests')
            ->middleware('api')
            ->namespace($this->namespace . '\Tests')
            ->group(base_path('routes/api.tests.php'));
    }
}
