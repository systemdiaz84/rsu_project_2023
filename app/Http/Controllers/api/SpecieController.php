<?php

namespace App\Http\Controllers\api;

use App\Models\admin\Specie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpecieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $specie = Specie::all();

        return response()->json(['status' => true ,'message' => 'Especies obtenidas correctamente', 'data' => $specie]);

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
        $specie = Specie::create($request->all());

        return response()->json(['status' => true ,'message' => 'Especie creada correctamente', 'data' => $specie]);

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
        $specie = Specie::find($id);

        return response()->json(['status' => true ,'message' => 'Especie obtenida correctamente', 'data' => $specie]);

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
        $specie = Specie::find($id);
        $specie->update($request->all());


        return response()->json(['status' => true ,'message' => 'Especie actualizada correctamente', 'data' => $specie]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        $specie = Specie::find($id);
        $specie->delete();

        return response()->json(['status' => true ,'message' => 'Especie eliminada correctamente', 'data' => []]);


    }
}
