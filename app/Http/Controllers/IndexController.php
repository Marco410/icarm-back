<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;


class IndexController extends Controller
{
    public function index(Request $request){
        return view("icarm_app");
    }

    public function evento($slug){
        $evento = Evento::where('link', $slug)->with('iglesia')->first();
        if($evento){
            return view("evento_detail",['evento' => $evento]);
        }else{
            return view("icarm_app");
        }
    }
}
