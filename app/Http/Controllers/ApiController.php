<?php

namespace App\Http\Controllers;

use App\Traits\Http\Controllers\ApiResponses;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
* @OA\Info(title="API Lumy", version="1.0")
*
* @OA\Server(url="http://localhost")
*/

class ApiController extends Controller
{
    use ApiResponses;

    public $user;

    function __construct()
    {
        if (config('app.showQueryLog', false)) {
            DB::enableQueryLog();
        }

        /* $this->user = JWTAuth::user(); */
    }


/*     public function getUserLoggedIn()
    {
        return JWTAuth::user();
    } */
}
