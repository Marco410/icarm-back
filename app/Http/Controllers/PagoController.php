<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pago;
use App\Services\NotificationService;

class PagoController extends ApiController
{
    public $notificationService;

    public function getAll(Request $request){
        $pagos = Pago::get();
        if($pagos){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'pagos' => $pagos
                ] 
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }


    public function create(Request $request){
        $datetime = date("Y-m-d H:i:s");

        $pago = Pago::create([
            'id_persona' => $request->user_id,
            'evento_id' => $request->evento_id,
            'cantidad' => $request->cantidad,
            'concepto' => $request->concepto,
            'fecha_agrego' => $datetime
        ]);


        if($pago){
            return $this->ok([
                'status' => 'Success', 
               
            ]);
        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No pudimos completar la operación, intente más tarde'
            ]);
        }
    }

}
