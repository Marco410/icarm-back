<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends ApiController
{

    public function getAll(Request $request){
        $module = Module::get();
        if($module){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'modules' => $module
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
