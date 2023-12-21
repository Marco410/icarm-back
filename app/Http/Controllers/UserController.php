<?php

namespace App\Http\Controllers;

use App\Models\UserContext;
use App\Models\ViewUserRelationshipWithClient;
use App\Models\User;
use App\Models\FirebaseToken;
use App\Services\NotificationService;

use Illuminate\Http\Request;

class UserController extends ApiController
{

    public $notificationService;

    public function index(Request $request)
    {
        return $this->ok(User::with('iglesia')->get());
    }

    public function test(Request $request)
    {
       return $request->otra;
    }

    public function sendNotificationToUSer(Request $request){

        $firebase_token = FirebaseToken::where('user_id',$request->user_id)->get();

        $resp = "";
        
        if ($firebase_token) {
            foreach($firebase_token as $token){

                $title = $request->title;
                $body = $request->msg;
                $data = [  
                    'flag' => 'i',
                    'route' => 'kid',
                ];
                
                $notificationService = new NotificationService();
                $response = $notificationService->sendUserNotification($title,$body,$data,$token->token);
                $resp .= $response;
                
            }
            return $this->ok([
                'status' => 'Success', 
                'message' => 'Notificación enviada con éxito.'
            ]);

        }else{
            return $this->badRequest([
                'status' => 'Error', 
                'message' => 'No se puede enviar notificación al usuario por el momento.'
            ]);
        }
    }
}
