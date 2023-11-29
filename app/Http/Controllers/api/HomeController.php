<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\admin\Home;
use App\Models\admin\HomeMembers;
use App\Models\admin\Zone;
use App\Models\admin\User;
use App\Models\admin\ZoneResponsible;
use App\Notifications\NotificationBasic;
use App\Notifications\NotificationRequestAccessHome;
use App\Notifications\NotificationRequestCreateHome;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use stdClass;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $code = $this->generateCode();
        while($this->checkCode($code)){
            $code = $this->generateCode();
        }
        $home = new Home();
        $home->code = $code;
        $home->name = $request->input('name');
        $home->direction = $request->input('direction');
        $home->is_public = 0;
        // 1
        $home->zone_id = $request->input('zone_id');
        $home->user_id = auth()->user()->id;
        $home->is_active = 0;
        $home->is_pending = 1;
        $home->save();

        $homeMember = new HomeMembers();
        $homeMember->home_id = $home->id;
        $homeMember->is_active = 0;
        $homeMember->is_pending = 1;
        $homeMember->is_boss = 1;
        $homeMember->user_id = $home->user_id;
        $homeMember->save();

        $user = User::find($home->user_id);

        $data = new stdClass();
        $data->username = strtoupper($user->name . ' ' . $user->lastname);
        $data->homename = $home->name;
        $data->codehome = $home->code;
        $data->direction = $home->direction;
        $data->data = [
            'home' => [
                'id' => $home->id,
                'name' => $home->name,
                'code' => $home->code,
                'direction' => $home->direction,
                'zone' => Zone::find($home->zone_id)->name,
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'n_doc' => $user->n_doc,
                'profile_photo_path' => $user->profile_photo_path,
            ]
        ];
        $zoneBoss = ZoneResponsible::where('zone_id',$home->zone_id)->where('is_active',1)->get();
        foreach($zoneBoss as $boss){
            $user_noti = User::find($boss->user_id);
            if ($user_noti->hasActiveNotificationTokens()) {
                $user_noti->notify(new NotificationRequestCreateHome($data));
            }
        }

        return response()->json(['message' => 'Se solicitó la creación del hogar correctamente', 'data' => $home, 'status' => true]); 
    }
    
    function checkCode($code){
        return Home::where('code',$code)->where('is_active',1)->count() > 0;
    }
    // generar codigo aleatorio de 6 digitos alfanumericos
    function generateCode(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }
        return $code;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $home = Home::find($id);
        $zones = Zone::pluck('name', 'id');
        $n_doc = User::select('users.n_doc')
                    ->join('home', 'users.id', '=', 'home.user_id')
                    ->where('home.id', $id)->pluck('n_doc')->first();

        return view('admin.home.edit', compact('home', 'zones', 'n_doc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $home = Home::find($id);
        if($home->code != $request->input('code')){
            if($this->checkCode($request->input('code')) > 0){
                return redirect()->route('admin.home.index')->with('error', 'El código ya existe');
            }
        }
        $home->code = $request->input('code');
        $home->name = $request->input('name');
        $home->is_public = $request->input('is_public');
        $home->direction = $request->input('direction');
        $home->zone_id = $request->input('zone_id');
        $home->user_id = User::select('users.id')->where('users.n_doc', $request->input('n_doc'))->pluck('id')->first();
        $home->save();

        return redirect()->route('admin.home.index')->with('success', 'Hogar Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        

    }

    public function requestAccessHome($codeHome)
    {
        $user = auth()->user();

        $home = Home::where('code',$codeHome)->where('is_active',1)->first();
        if($home){
            $homeMember = HomeMembers::where('home_id',$home->id)->where('user_id',$user->id)->where('is_active',1)->first();
            if($homeMember){
                return response()->json(['message' => 'Ya perteneces a este hogar', 'data' => $home, 'status' => false]);
            }else{
                $homeMember = HomeMembers::where('home_id',$home->id)->where('user_id',$user->id)->where('is_pending',1)->first();
                if($homeMember){
                    return response()->json(['message' => 'Ya tienes una solicitud pendiente', 'data' => $home, 'status' => false]);
                }else{
                    $homeMember = new HomeMembers();
                    $homeMember->home_id = $home->id;
                    $homeMember->is_active = 0;
                    $homeMember->is_pending = 1;
                    $homeMember->is_boss = 0;
                    $homeMember->user_id = $user->id;
                    $homeMember->save();
                    
                    
                    $data = new stdClass();
                    $data->username = strtoupper($user->name . ' ' . $user->lastname);
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
                            'id' => $user->id,
                            'name' => $user->name . ' ' . $user->lastname,
                            'lastname' => $user->lastname,
                            'email' => $user->email,
                            'n_doc' => $user->n_doc,
                            'profile_photo_path' => $user->profile_photo_path,
                        ]
                    ];

                    $homeBoss = HomeMembers::where('home_id',$home->id)->where('is_active',1)->where('is_boss',1)->where('is_pending',0)->get();
                    foreach($homeBoss as $boss){
                        $user_noti = User::find($boss->user_id);
                        if ($user_noti->hasActiveNotificationTokens()) {
                            $user_noti->notify(new NotificationRequestAccessHome($data));
                        }
                    }
                    
                    return response()->json(['message' => 'Solicitud enviada correctamente', 'data' => $home, 'status' => true]);
                }
            }
        }else{
            return response()->json(['message' => 'El hogar no existe', 'data' => $home, 'status' => false]);
        }
    }


    //Obtener los hogares de un usuario
    public function homeByUser($user_id) {

        $homes = Home::select(
                            'home.id', 
                            'home.code', 
                            'home.name', 
                            'home.user_id', 
                            'home.is_pending', 
                            'home.is_public', 
                            'home.direction', 
                            'zones.id as zone_id',
                            'zones.name as zonename',
                            )
                        ->join('zones', 'home.zone_id', '=', 'zones.id')
                        ->join('home_members', 'home.id', '=', 'home_members.home_id')
                        ->where([['home.is_active', 1], ['home_members.user_id', $user_id]])
                        ->Where('home_members.is_active', 1)
                        ->Where('home_members.user_id', $user_id)
                        ->get();
        
        //Obtener algunos datos del jefe del hogar  
        foreach ($homes as $home_id => $home){
            $bosses = User::select(
                'users.id',
                'name',
                'lastname',
                'email',
                )
                ->join('home_members', 'home_members.user_id', '=', 'users.id')
                ->where('home_members.home_id', $home->id)
                ->where('home_members.is_boss', '1')
                ->get();

            //echo ($bosses);
            $home->bosses = $bosses;
        }
    
                        
        return $homes;

    }

    public function accept($id)
    {
        $home = Home::find($id);
        $home->is_active = 1;
        $home->is_pending = 0;
        $home->save();

        $user = User::find($home->user_id);
        if($user->hasActiveNotificationTokens()){
            $data = new stdClass();
            $data->title = 'Solicitud aceptada';
            $data->message = 'Tu solicitud para crear el hogar "' . $home->name . '" ha sido aceptada.';
            $user->notify(new NotificationBasic($data));
        }

        return response()->json(['message' => 'Solicitud aceptada correctamente', 'data' => '', 'status' => true]); 
    }

    public function reject($id)
    {
        $home = Home::find($id);
        $home->is_active = 0;
        $home->is_pending = 0;
        $home->save();

        $user = User::find($home->user_id);
        if($user->hasActiveNotificationTokens()){
            $data = new stdClass();
            $data->title = 'Solicitud rechazada';
            $data->message = 'Tu solicitud para crear el hogar "' . $home->name . '" ha sido rechazada. Puedes volver a intentarlo.';
            $user->notify(new NotificationBasic($data));
        }

        return response()->json(['message' => 'Solicitud rechazada correctamente', 'data' => '', 'status' => true]);
    }

}
