<?php

namespace App\Http\Controllers;

use App\Models\FirebaseToken;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Services\NotificationService;

class CronjobsController extends ApiController
{
    protected $firebaseService;


    public function __construct()
    {
        $this->firebaseService = new NotificationService();
    }


    public function reminderEvent(Request $request)
    {

        //$evento = Evento::where('is_public',1)->where('fecha_inicio', date('Y-m-d', strtotime('+1 day')))->get();

        $fechaInicio = date('Y-m-d 00:00:00', strtotime('+1 day'));
        $fechaFin = date('Y-m-d 23:59:59', strtotime('+1 day'));

        $eventos = Evento::where('is_public', 1)
            ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where('reminder',0)
            ->get();

        $tokens = FirebaseToken::where('user_id',2154)->where('user_id',358)->get();

        $title = "Recordatorio de cita";
        $body = "";

        $data = [
            'type' => "date",
            'fg_status' => 2,
        ];


        $this->firebaseService->sendNotificationToUserInAPI(358,"Ministerio de niños",$body,$data);

        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'evento'=> $eventos,
                'tokens'=> $tokens,
            ] 
        ]);
    }

}
