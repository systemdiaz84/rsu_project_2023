<?php

namespace App\Http\Controllers\api;

use App\Models\admin\Home;
use App\Models\admin\HomeMembers;
use App\Models\admin\User;
use App\Http\Controllers\Controller;
use App\Models\NotifyToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = new User();
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->n_doc = $request->input('n_doc');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password')); // Se recomienda encriptar la contraseña

        // Guarda el usuario en la base de datos
        $user->save();

        //Obtenemos token para el nuevo usuario
        $token = $user->createToken('api-token')->plainTextToken;

        $user->token = $token;

        return response()->json(['status' => true ,'message' => 'Usuario registrado correctamente', 'data' => $user]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admin\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }


    public function data_email($email) {

        $users = User::select('*')
                        ->where('email', '=', $email)
                        ->get();

        return $users;
    }

    public function save_token_device(Request $request) {

        $user = auth()->user();

        $token = NotifyToken::where('user_id', $user->id)
                    ->where('token', $request->token)
                    ->first();

        if ($token) {
            $token->is_active = 1;
            $token->save();
            return response()->json([
                'status' => True,
                'data' => '',
                'message' => 'Token device already exists'
            ], 200);
        }
        NotifyToken::create([
            'user_id' => $user->id,
            'token' => $request->token,
            'is_active' => 1
        ]);

        return response()->json([
            'status' => True,
            'data' => '',
            'message' => 'Token device saved successfully'
        ], 200);
    }
    public function disable_token_device(Request $request) {
        $user = auth()->user();
        NotifyToken::where('user_id', $user->id)
                ->where('token', $request->token)
                    ->update(['is_active' => 0]);

        return response()->json([
            'status' => True,
            'data' => '',
            'message' => 'Token device disabled successfully'
        ], 200);
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8',
        ]);    
        $user = auth()->user();
    
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'La contraseña actual es incorrecta'], 200);
        }
    
        $user->password = Hash::make($request->password);
        $user->save();
    
        return response()->json(['status' => true, 'message' => 'Contraseña actualizada correctamente', 'data' => $request->all()]);
    }


    
    public function pendingRequests() {
        //obtenemos el usuario
        $user = auth()->user();

        //Obtenemos los hogares donde es jefe este usuario
        $home_members = HomeMembers::where('user_id', $user->id)->where('is_boss', 1)->where('is_active', 1)->get();

        //Obtenemos los homeMembers que estan pendientes de los hogares donde el es jefe
        if (count($home_members) == 0) {
            return response()->json(['message'=> 'No es jefe de ningun hogar', 'data'=> '', 'status'=> true]);
        } 

        $pending_requests_home_acces = array();

        foreach ($home_members as $member) {
            $pending_request = HomeMembers::where('home_id', $member->home_id)->where('is_pending', 1)->get();

            if (count($pending_request) != 0) {
                foreach ($pending_request as $request) {
                    $home = Home::where('id', $request->home_id)->where('is_active',1)->first();
                    $user_request = User::find( $request->user_id );

                    $data = new stdClass();
                    $data->username = strtoupper($user_request->name . ' ' . $user_request->lastname);
                    $data->homename = $home->name;
                    $data->codehome = $home->code;
                    $data->data = [
                        'home' => [
                            'id' => $home->id,
                            'name' => $home->name,
                            'code' => $home->code,
                            'direction' => $home->direction,
                        ],
                        'user' => [
                            'id' => $user_request->id,
                            'name' => $user_request->name . ' ' . $user_request->lastname,
                            'lastname' => $user_request->lastname,
                            'email' => $user_request->email,
                            'n_doc' => $user_request->n_doc,
                            'profile_photo_path' => $user_request->profile_photo_path,
                        ]
                    ];

                    array_push($pending_requests_home_acces, $data);
                }

            }
        }

        return response()->json(['status' => true, 'message' => 'Solicitudes obtenidas correctamente', 'home_access_requests' =>  $pending_requests_home_acces, 'home_create_requests' => '',  'tree_create_requests' => '']);

    }

}
