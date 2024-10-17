<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class CronjobsController extends ApiController
{
    public $notificationService;

    public function reminderEvent(Request $request)
    {

        $evento = Evento::where('is_public',1)->where('fecha_inicio', date('Y-m-d', strtotime('+1 day')))->get();

        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'evento'=> date('Y-m-d', strtotime('+1 day')),
            ] 
        ]);
    }

}
