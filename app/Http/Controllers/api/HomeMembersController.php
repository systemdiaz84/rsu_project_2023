<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\admin\Home;
use App\Models\admin\HomeMembers;
use App\Models\admin\User;
use App\Notifications\NotificationBasic;
use stdClass;

class HomeMembersController extends Controller
{
    public function accept($id_home, $id_member){
        $home_member = HomeMembers::where('home_id', $id_home)->where('user_id', $id_member)->where('is_pending', 1)->where('is_active', 0)->first();
        $home_member->is_pending = 0;
        $home_member->is_active = 1;
        $home_member->save();

        $home = Home::find($id_home);
        $user = User::find($id_member);

        if ($user->hasActiveNotificationTokens()) {
            $data = new stdClass();
            $data->title = 'Solicitud aceptada';
            $data->message = 'Tu solicitud para unirte al hogar "' . $home->name . '" ha sido aceptada.';
            $user->notify(new NotificationBasic($data));
        }

        return response()->json(['message' => 'Solicitud aceptada correctamente', 'data' => '', 'status' => true]); 
    }

    public function reject($id_home, $id_member){
        $home_member = HomeMembers::where('home_id', $id_home)
                              ->where('user_id', $id_member)
                              ->where('is_pending', 1)
                              ->where('is_active', 0)
                              ->first();

        if ($home_member === null) {
            return response()->json(['message' => 'No se encontró el miembro del hogar', 'data' => '', 'status' => false]); 
        }
        $home_member->is_pending = 0;
        $home_member->is_active = 0;
        $home_member->save();

        $home = Home::find($id_home);
        $user = User::find($id_member);

        if ($user->hasActiveNotificationTokens()) {
            $data = new stdClass();
            $data->title = 'Solicitud rechazada';
            $data->message = 'Tu solicitud para unirte al hogar "' . $home->name . '" ha sido rechazada. Puedes volver a intentarlo.';
            $user->notify(new NotificationBasic($data));
        }

        return response()->json(['message' => 'Solicitud rechazada correctamente', 'data' => '', 'status' => true]); 
    }

}
