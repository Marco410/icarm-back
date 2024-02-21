<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Iglesia;
use App\Models\Evento;
use App\Services\NotificationService;

class IglesiaController extends ApiController
{
    public $notificationService;

    public function getAll(Request $request){


        $iglesias = Iglesia::get();
        
        if($iglesias){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'iglesias' => $iglesias
                ] 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

}
