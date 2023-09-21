<?php

namespace App\Http\Middleware;

use Closure;

class SwaggerAutoGenerate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // MDB Ejecuta el comando para actualizar la documentacion con cada peticion
        \Illuminate\Support\Facades\Artisan::call('l5-swagger:generate', []);

        return $next($request);
    }
}
