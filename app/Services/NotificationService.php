<?php

namespace App\Services;

use HelperUtilities;
use App\Models\Settings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class NotificationService {

    public $validationsService;
    public function __construct()
    {
        
    }

    public function sendUserNotification($title,$body,$data,$user_toke_firebase){
        $settings = Settings::where('name','token_service_firebase')->first(); 
        $response = Http::withToken($settings->value)->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $user_toke_firebase,
            'notification' => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => $data
        ]);
        return $response;
    }    
}