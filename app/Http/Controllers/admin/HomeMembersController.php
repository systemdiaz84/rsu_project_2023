<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Home;
use App\Models\admin\HomeMembers;
use App\Models\admin\Zone;
use App\Models\admin\User;
use Illuminate\Http\Request;

class HomeMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // viene un parametro id de la ruta
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($home_id)
    {
        $home = Home::find($home_id);
        return view('admin.homemembers.create', compact('home'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // validar si el usuario ya existe en el hogar
        $home_member = HomeMembers::where('home_id', $request->input('home_id'))->where('user_id', User::select('users.id')->where('users.n_doc', $request->input('n_doc'))->pluck('id')->first())->count();
        if($home_member > 0){
            return response()->json(['error' => 'El usuario ya existe en el hogar'], 409);
        }
        $request->merge(['is_pending' => 0]);
        $request->merge(['user_id' => User::select('users.id')->where('users.n_doc', $request->input('n_doc'))->pluck('id')->first()]);
        $request->offsetUnset('n_doc');
        HomeMembers::create($request->all());

        $home = Home::find($request->input('home_id'));
        $members = User::select('users.id', 'users.n_doc', 'users.name', 'users.lastname','home_members.is_boss')->join('home_members', 'users.id', '=', 'home_members.user_id')->where('home_members.home_id', $request->input('home_id'))->get();
        
        return view('admin.homemembers.index', compact('home', 'members'));
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
        $home = Home::find($id);
        $members = User::select('users.id', 'users.n_doc', 'users.name', 'users.lastname','home_members.is_boss','home_members.is_active','home_members.is_pending')
                    ->join('home_members', 'users.id', '=', 'home_members.user_id')
                    ->where('home_members.home_id', $id)
                    ->where(function($query){
                        $query->where('home_members.is_active', 1)
                        ->orWhere('home_members.is_active', 0)->where('home_members.is_pending', 1);
                    })
                    ->get();
        return view('admin.homemembers.index', compact('home', 'members'));
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
    public function destroy($id_home, $id_member)
    {
        $home_member = HomeMembers::where('home_id', $id_home)->where('user_id', $id_member); //pueden ser varios
        $home_member->update(['is_active' => 0, 'is_pending' => 0]);
        return redirect()->route('admin.home.index')->with('success', 'Miembro eliminado');
    }

    public function accept($id_home, $id_member){
        $home_member = HomeMembers::where('home_id', $id_home)->where('user_id', $id_member)->where('is_pending', 1)->where('is_active', 0)->first();
        $home_member->is_pending = 0;
        $home_member->is_active = 1;
        $home_member->save();
        return redirect()->route('admin.home.index')->with('success', 'Miembro aceptado');
    }

    public function reject($id_home, $id_member){
        $home_member = HomeMembers::where('home_id', $id_home)->where('user_id', $id_member)->where('is_pending', 1)->where('is_active', 0)->first();
        $home_member->is_pending = 0;
        $home_member->is_active = 0;
        $home_member->save();
        return redirect()->route('admin.home.index')->with('success', 'Miembro rechazado');
    }
}
