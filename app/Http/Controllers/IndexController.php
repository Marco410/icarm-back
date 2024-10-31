<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class IndexController extends Controller
{
    public function index(Request $request){


        return view("icarm_app");
        

        $playStoreUrl = "https://play.google.com/store/apps/details?id=com.mtm.icarm";
        $appStoreUrl = "https://apps.apple.com/mx/app/ayr-morelia/id6449270971?l=en-GB"; 
        
        $userAgent = strtolower($request->header('User-Agent'));

        if (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad') || str_contains($userAgent,'macintosh')) {
            return redirect($appStoreUrl);
        } elseif (str_contains($userAgent, 'android') || str_contains($userAgent, 'windows')) {
            return redirect($playStoreUrl);
        }
    }
}
