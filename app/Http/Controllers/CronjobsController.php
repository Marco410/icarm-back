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

        $eventoSemana = $this->searchEvent(7, 0);    // 7 días antes 
        $eventoDosDias = $this->searchEvent(2, 1);   // 2 días antes
        $eventoMañana = $this->searchEvent(1, 2);    // 1 día antes

        $tokens = FirebaseToken::orderBy('created_at')
            ->get()
            ->unique('user_id')->values();

        

        return $tokens;

        $title = "📆 Recordatorio de evento ";
        $body = "";
        $data = [
            'type' => "event",
            'fg_status' => 1,
        ];

        if ($eventoSemana) {
            $body = "¡Faltan 7 días para $eventoSemana->nombre! 📅 No olvides reservar la fecha. Será a las " 
                . date('H:i', strtotime($eventoSemana->fecha_inicio)) . " hrs ⏰";
            $eventoSemana->update(['reminder' => 1]); 
            $this->sendReminder($tokens, $title, $body, $data);
        }

        if ($eventoDosDias) {
            $body = "¡Faltan solo 2 días para $eventoDosDias->nombre! ⏳ No te olvides de confirmar tu asistencia. Será a las " 
                . date('H:i', strtotime($eventoDosDias->fecha_inicio)) . " hrs ⏰";
            $eventoDosDias->update(['reminder' => 2]);
            $this->sendReminder($tokens, $title, $body, $data);
        }

        if ($eventoMañana) {
            $body = "¡Mañana es $eventoMañana->nombre! 🎉 No te lo pierdas. Te esperamos puntualmente a las " 
                . date('H:i', strtotime($eventoMañana->fecha_inicio)) . " hrs ⏰";
            $eventoMañana->update(['reminder' => 3]);
            $this->sendReminder($tokens, $title, $body, $data);
        }
        return null;
    }

    private function sendReminder($tokens, $title, $body, $data) {
        foreach ($tokens as $token) {
            $this->firebaseService->sendNotificationToUserInAPI(
                $token->user_id, 
                0, 
                $title, 
                $body, 
                $data
            );
        }
    }

    public function searchEvent($days, $reminder) {
        $inicio = date('Y-m-d 00:00:00', strtotime("+$days days"));
        $fin = date('Y-m-d 23:59:59', strtotime("+$days days"));
    
        return Evento::where('is_public', 1)
            ->whereBetween('fecha_inicio', [$inicio, $fin])
            ->where('reminder', $reminder)
            ->first();
    }

}
