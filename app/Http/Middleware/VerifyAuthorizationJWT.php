<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use App\Traits\Http\Controllers\ApiResponses;
use Closure;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyAuthorizationJWT
{
    use ApiResponses;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* $user = JWTAuth::user();

        if (empty($user)) {
            $user = JWTAuth::parseToken()->authenticate();
        }

        if (!$user->active) {
            return $this->unauthorized(null, 401.3);
        }

        if ($user->token != trim(str_replace(['Bearer', 'bearer'], '', $request->header('Authorization')))) {
            return $this->unauthorized(null, 401.3);
        }

        if ($user->last_request == null) {
            return $this->unauthorized(null, 401.3);
        } else {
            /*  if (Carbon::parse($user->last_request)->addMinutes(env('TOKEN_LIFETIME')) < Carbon::now()) {
                return $this->unauthorized(null, 401.3);
            }  
        }

        
        return $next($request); */
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
            UserSession::updateLastRequest($user->id);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired']);
            }else{
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}
