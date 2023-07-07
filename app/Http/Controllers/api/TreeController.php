<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trees = Tree::select(
            'trees.id',
            'trees.name',
            'trees.birth_date',
            'trees.planting_date',
            'trees.description',
            'trees.latitude',
            'trees.longitude',
            'trees.specie_id',
            'trees.zone_id',
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            'zones.name as zones_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url'))
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('zones', 'zones.id','=','trees.zone_id')
            ->orderBy('trees.id', 'desc')
            ->get();

        return $trees;
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
        $trees = Tree::create($request->all());

        $name = $trees->name . ' ' . $trees->id;

        $trees->update([
            'name' => $name
        ]);

        return response()->json(['message' => 'Árbol registrado correctamente', 'tree_id' => $trees->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $trees = Tree::select(
            'trees.id',
            'trees.name',
            'trees.birth_date',
            'trees.planting_date',
            'trees.description',
            'trees.latitude',
            'trees.longitude',
            'trees.specie_id',
            'trees.zone_id',
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            'zones.name as zones_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url'))
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('zones', 'zones.id','=','trees.zone_id')
            ->where('trees.name','like','%'.$name.'%')
            ->get();

        return $trees;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trees = Tree::select(
            'trees.id',
            'trees.name',
            'trees.birth_date',
            'trees.planting_date',
            'trees.description',
            'trees.latitude',
            'trees.longitude',
            'families.id as family_id',
            'trees.specie_id',
            'trees.zone_id',
            'trees.user_id',    
            'families.name as family_name',
            'species.name as species_name',
            'zones.name as zones_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url'))
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('zones', 'zones.id','=','trees.zone_id')->where('trees.id',$id)->get();

        return $trees;
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
    }

    public function trees_zone($zone_id)
    {
        $trees = Tree::select(
            'trees.id',
            'trees.name',
            'trees.birth_date',
            'trees.planting_date',
            'trees.description',
            'trees.latitude',
            'trees.longitude',
            'trees.specie_id',
            'trees.zone_id',
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            'zones.name as zones_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url'))
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('zones', 'zones.id','=','trees.zone_id')
            ->where('zones.id',$zone_id)
            ->orderBy('trees.id', 'desc')
            ->get();

        return $trees;

    }
}