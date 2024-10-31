<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends ApiController
{
    public $notificationService;

    public function getAppVersion(Request $request){


        $version = Settings::where('name','version')->first();
        
        if($version){
            return $this->ok([
                'status' => 'Success', 
                'data' => [
                    'version' => $version
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
