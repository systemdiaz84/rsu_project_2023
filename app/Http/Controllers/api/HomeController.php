<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\admin\Home;
use App\Models\admin\HomeMembers;
use App\Models\admin\Zone;
use App\Models\admin\User;
use Illuminate\Http\Request;

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

        return response()->json(['message' => 'Hogar creado correctamente', 'data' => $home, 'status' => true]); 
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
        $home = Home::where('code',$codeHome)->where('is_active',1)->first();
        if($home){
            $homeMember = HomeMembers::where('home_id',$home->id)->where('user_id',auth()->user()->id)->where('is_active',1)->first();
            if($homeMember){
                return response()->json(['message' => 'Ya perteneces a este hogar', 'data' => $home, 'status' => false]);
            }else{
                $homeMember = HomeMembers::where('home_id',$home->id)->where('user_id',auth()->user()->id)->where('is_pending',1)->first();
                if($homeMember){
                    return response()->json(['message' => 'Ya tienes una solicitud pendiente', 'data' => $home, 'status' => false]);
                }else{
                    $homeMember = new HomeMembers();
                    $homeMember->home_id = $home->id;
                    $homeMember->is_active = 0;
                    $homeMember->is_pending = 1;
                    $homeMember->is_boss = 0;
                    $homeMember->user_id = auth()->user()->id;
                    $homeMember->save();
                    return response()->json(['message' => 'Solicitud enviada correctamente', 'data' => $home, 'status' => true]);
                }
            }
        }else{
            return response()->json(['message' => 'El hogar no existe', 'data' => $home, 'status' => false]);
        }
    }
}
