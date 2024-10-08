<?php

namespace App\Services;

use App\Models\FirebaseToken;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Factory;

class NotificationService {

    public $validationsService;
    protected $messaging;

    public function __construct()
    {
        $serviceAccount = storage_path('mtm-icarm-firebase-adminsdk-slvt4-d2ce33b31c.json');

        $factory = (new Factory)->withServiceAccount($serviceAccount);
        $this->messaging = $factory->createMessaging();
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

    public function sendUserNotification($title,$body,$data,$token){
        $payload = [
            'apns' => [
                'payload' => [
                    'aps' => [
                        'content-available' => 1,
                        'mutable-content' => 1,
                        'sound' => 'default', //Can be changed for a custom sound file
                    ],
                ],
            ],
            'data' => $data,
        ];

        $message = CloudMessage::withTarget('token', $token)->withNotification(['title' => $title, 'body' => $body])->withData($payload['data'])->withApnsConfig($payload['apns']); 

        try {
            $this->messaging->send($message);
        } catch (NotFound $e) {

            \Log::error('Token no válido o no encontrado: ' . $token);

            $this->deleteInvalidToken($token);
        } catch (\Exception $e) {
            \Log::error('Error al enviar la notificación: ' . $e->getMessage());
        }
    }    

    private function deleteInvalidToken($token)
    {
        FirebaseToken::where('token', $token)->delete();
    }
}