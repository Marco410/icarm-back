<?php

namespace App\Http\Controllers;

use App\Models\FirebaseToken;
use Illuminate\Http\Request;
use App\Models\Evento;

class CronjobsController extends ApiController
{
    public $notificationService;

    public function reminderEvent(Request $request)
    {

        //$evento = Evento::where('is_public',1)->where('fecha_inicio', date('Y-m-d', strtotime('+1 day')))->get();

        $fechaInicio = date('Y-m-d 00:00:00', strtotime('+1 day'));
        $fechaFin = date('Y-m-d 23:59:59', strtotime('+1 day'));

        $eventos = Evento::where('is_public', 1)
            ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where('reminder',0)
            ->get();

            $tokens = FirebaseToken::get();

        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'evento'=> $eventos,
                'tokens'=> $tokens,
            ] 
        ]);
    }

}
