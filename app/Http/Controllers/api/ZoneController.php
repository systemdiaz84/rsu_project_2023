<?php

namespace App\Http\Controllers\api;

use App\Models\admin\Zone;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $zones = Zone::all();

        return response()->json(['status' => true ,'message' => 'Zonas obtenidas correctamente', 'data' => $zones]);

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
        $zones = Zone::create($request->all());

        return response()->json(['status' => true ,'message' => 'Zona registrada correctamente', 'data' => $zones]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
        $zones = Zone::find($id);

        return response()->json(['status' => true ,'message' => 'Zona obtenida correctamente', 'data' => $zones]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
        $zones = Zone::find($id);
        $zones->update($request->all());

        return response()->json(['status' => true ,'message' => 'Zona actualizada correctamente', 'data' => $zones]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        $zones = Zone::find($id);
        $zones->delete();

        return response()->json(['status' => true ,'message' => 'Zona actualizada correctamente', 'data' => []]);
    }
}
