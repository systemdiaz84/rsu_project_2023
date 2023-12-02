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

    public function pendingRequests() {
        //obtenemos el usuario
        $user = auth()->user();

        //Obtenemos los hogares donde es jefe este usuario
        $home_members = HomeMembers::where('user_id', $user->id)->where('is_boss', 1);

        //Obtenemos los homeMembers que estan pendientes de los hogares donde el es jefe
        if ($home_members === null) {
            return response()->json(['message'=> 'No es jefe de ningun hogar', 'data'=> '', 'status'=> false]);
        } 

        $pending_requests = array();

        foreach ($home_members as $member) {
            $pending_request = HomeMembers::where('home_id', $member->home_id)->where('is_pending', 1);
            if ($pending_request != null) {
                $pending_requests[] = $pending_request;
            }
        }

        return $pending_requests;
    }

}
