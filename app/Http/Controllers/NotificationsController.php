<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pago;
use App\Models\NotificationModel;
use App\Services\NotificationService;

class NotificationsController extends ApiController
{
    public $notificationService;

    public function list(Request $request)
    {

        $notis = NotificationModel::where('user_id',$request->user_id)->orderBy('seen','asc')->orderBy('created_at','desc')->get();

        return $this->ok([
            'status' => 'Success', 
            'data' => [
                'notificaciones'=>$notis,
            ] 
        ]);
    }

    public function seen(Request $request)
    {
        $noti = NotificationModel::where('id',$request->noti_id)->update(['seen' => 1]);

            return $this->ok([
                'status' => 'Success', 
                'data' => [
                'notificacion'=>$noti,
            ] 
        ]);
    }

    public function delete(Request $request)
    {

        $noti = NotificationModel::where('id',$request->noti_id)->delete();

        return $this->ok([
                'status' => 'Success', 
                'data' => [
                'notificacion'=>$noti,
            ] 
        ]);
    }

    public function delete_all(Request $request)
    {

        $noti = NotificationModel::where('user_id',$request->user_id)->delete();

        return $this->ok([
                'status' => 'Success', 
                'data' => [
                'notificacion'=>$noti,
            ] 
        ]);
    }

    public function seen_all(Request $request)
    {
        $noti = NotificationModel::where('user_id',$request->user_id)->update(['seen' => 1]);

        return $this->ok([
            'status' => 'Success', 
            'data' => [
            'notificacion'=>$noti,
        ] 
    ]);
    }

}
