<?php

namespace App\Http\Middleware;

use Closure;

class TestMiddleware
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
        // print_r($request->method());
        // echo '<br>';
        // print_r($request->url());
        // echo '<br>';
        // print_r($request->fullUrl());
        // echo '<br>';
        // print_r($request->path());
        // echo '<br>';
        // print_r($request->segments());
        // echo '<br>';
        // print_r($request->secure());
        // echo '<br>';
        // print_r($request->ip());
        // echo '<br>';
        // print_r($request->userAgent());
        // echo '<br>';
        // print_r($request->header('Authorization'));
        // echo '<br>';
        // $user = JWTAuth::parseToken()->authenticate();
        // print_r(JWTAuth::getToken()->get());
        
        $response = $next($request);
        
        return $response;
    }
}
