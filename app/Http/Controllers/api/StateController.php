<?php

namespace App\Http\Controllers\api;

use App\Models\admin\State;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $states = State::all();

        return $states;
        //return response()->json(['status' => true, 'message' => 'Estados obtenidos correctamente', 'data' => $states]);

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
        $states = State::create($request->all());

        return response()->json(['status' => true, 'message' => 'Estado registrado correctamente', 'data' => $states]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\admin\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $states = State::find($id);

        return $states;
        //return response()->json(['status' => true, 'message' => 'Estado obtenido correctamente', 'data' => $states]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admin\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $states = State::find($id);
        $states->update($request->all());

        return response()->json(['status' => true, 'message' => 'Estado actualizado correctamente', 'data' => $states]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admin\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $states = State::find($id);
        $states->delete();

        return response()->json(['status' => true, 'message' => 'Estado eliminado correctamente', 'data' => []]);
    }


}
