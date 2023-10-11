<?php

namespace App\Http\Controllers\api;

use App\Models\EvolutionPhoto;
use App\Http\Controllers\Controller;
use App\Models\admin\Evolution;
use Illuminate\Http\Request;

class EvolutionPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $evolutionPhotos = EvolutionPhoto::all();
        return $evolutionPhotos;

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
        $evolutionPhoto = EvolutionPhoto::create($request->all());

        return response()->json(['message' => 'Foto de evolución registrada correctamente', 'status' => TRUE, 'data' => $evolutionPhoto]);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EvolutionPhoto  $evolutionPhoto
     * @return \Illuminate\Http\Response
     */
    public function show(EvolutionPhoto $evolutionPhoto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EvolutionPhoto  $evolutionPhoto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $evolutionPhoto = EvolutionPhoto::find($id);
        $evolutionPhoto->update($request->all());
        

        return response()->json(['message' => 'Foto de evolución actualizada correctamente', 'status' => TRUE, 'data' => $evolutionPhoto]);        
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EvolutionPhoto  $evolutionPhoto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $evolutionPhoto = EvolutionPhoto::find($id);
        $evolutionPhoto->delete();
        
    
        return response()->json(['message' => 'Foto de evolución eliminado correctamente', 'status' => TRUE, 'data' => []]);        

    }
}
