<?php

namespace App\Services;

use HelperUtilities;
use App\Models\Settings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\FirebaseToken;

class NotificationService {

    public $validationsService;
    public function __construct()
    {
        
    }


    public function sendNotificationToUserInAPI($user_id,$title,$body,$data){

        $firebase_token = FirebaseToken::where('user_id',$user_id)->get();

        $resp = "";
        
        if ($firebase_token) {
            foreach($firebase_token as $token){
                $notificationService = new NotificationService();
                $response = $this->sendUserNotification($title,$body,$data,$token->token);
                $resp .= $response;
                
            }

        }
    }

    public function sendUserNotification($title,$body,$data,$user_toke_firebase){
        $settings = Settings::where('name','token_service_firebase')->first(); 
        $response = Http::withToken($settings->value)->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $user_toke_firebase,
            "priority" => "high",
            'notification' => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => $data
        ]);
        return $response;
    }    
}