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

        $evento = Evento::where('is_public', 1)
            ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where('reminder',0)
            ->first();

            $tokens = FirebaseToken::whereIn('user_id', [2154, 358])
            ->orderBy('created_at')  
            ->get()
            ->unique('user_id');  

        $title = "Recordatorio de evento 📆";
        $body = "Mañana es: $evento->nombre. No te quedes fuera y confirma tu asistencia. ";

        $data = [
            'type' => "event",
            'fg_status' => 1,
        ];

        foreach ($tokens as $token){
             $this->firebaseService->sendNotificationToUserInAPI($token->user_id,$title,$body,$data); 
        }

        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'evento'=> $evento,
                'tokens'=> $tokens,
            ] 
        ]);
    }

}
