<?php

namespace App\Http\Controllers\api;

use App\Models\admin\User;
use App\Http\Controllers\Controller;
use App\Models\NotifyToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
}
