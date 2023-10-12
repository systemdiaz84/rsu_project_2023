<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Family;
use App\Models\Admin\Specie;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $families = Family::all();
        return $families;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $family = Family::create($request->all());

        return response()->json(['message' => 'Familia registrada correctamente', 'data' => $family, 'status' => true]);

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
        $family = Family::find($id);
        
        return response()->json(['message' => 'Familia obtenida correctamente', 'data' => $family, 'status' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
        $family = Family::find($id);
        $family->update($request->all());
        
        return response()->json(['message' => 'Familia actualizada correctamente', 'data' => $family, 'status' => true]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $family = Family::find($id);
        $family->delete();
        
        return response()->json(['message' => 'Familia eliminada correctamente', 'data' => [], 'status' => true]);


    }

    public function species_family($id)
    {
        $species = Specie::where('family_id',$id)->get();

        return $species;
        //return response()->json($species);
    }
}
