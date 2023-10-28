<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Home;
use App\Models\admin\Zone;
use App\Models\Admin\User;
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
        $homes = Home::select('home.id', 'home.code', 'home.name', 'home.user_id', 'home.is_pending', 'home.is_public', 'home.direction', 'zones.name as zonename','users.name as username','users.lastname as userlastname')->join('zones', 'home.zone_id', '=', 'zones.id')->join('users', 'home.user_id', '=', 'users.id')->where('home.is_active', 1)->get();

        return view('admin.home.index', compact('homes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = Zone::pluck('name', 'id');
        
        do {
            $code = $this->generateCode();
        } while ($this->checkCode($code) > 0);

        return view('admin.home.create', compact('zones', 'code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Home::create($request->all());
        if($this->checkCode($request->input('code')) > 0){
            return redirect()->route('admin.home.index')->with('error', 'El código ya existe');
        }
        $home = new Home();
        $home->code = $request->input('code');
        $home->name = $request->input('name');
        $home->is_public = $request->input('is_public');
        $home->direction = $request->input('direction');
        $home->zone_id = $request->input('zone_id');
        $home->user_id = User::select('users.id')->where('users.n_doc', $request->input('n_doc'))->pluck('id')->first();
        $home->is_active = 1;
        $home->is_pending = 0;
        $home->save();
        return redirect()->route('admin.home.index')->with('success', 'Hogar creado');
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
        $home = Home::find($id);
        $home->is_active = 0;
        $home->save();

        return Redirect()->route('admin.home.index')->with('success', 'Hogar dado de baja');

    }
}
