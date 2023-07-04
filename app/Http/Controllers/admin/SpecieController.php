<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Family;
use App\Models\admin\Specie;
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

        $species = Specie::select('species.id','species.name','species.scientific_name','families.name as familyname','species.description')->join('families','species.family_id','=','families.id')->get();

        //return $species;

        return view('admin.species.index', compact('species'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $families = Family::pluck('name','id');
        return view('admin.species.create',compact('families'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Specie::create($request->all());
        return redirect()->route('admin.species.index')->with('action','Especie Actualizada');
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
        $specie = Specie::find($id);
        $families = Family::pluck('name','id');
        return view('admin.species.edit', compact('specie','families'));
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
        $specie = Specie::find($id);
        $specie->update($request->all());

        return redirect()->route('admin.species.index')->with('action','Especie Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $specie=Specie::find($id);
        $specie->delete();

        return redirect()->route('admin.species.index')->with('action','Especie Eliminada');

    }
}
