<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Family;
use App\Models\Admin\Specie;
use App\Models\Admin\Tree;
use App\Models\Admin\Zone;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $trees = Tree::select('trees.id', 'trees.name', 'families.name as familyname', 'species.name as speciename', 'zones.name as zonename')
            ->join('families', 'trees.family_id', '=', 'families.id')
            ->join('species', 'trees.specie_id', '=', 'species.id')
            ->join('zones', 'trees.zone_id', '=', 'zones.id')->get();

        return view('admin.trees.index', compact('trees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $zones = Zone::all()->pluck('name', 'id');
        $familySQL = Family::whereRaw('id IN (Select family_id from species)')->get();
        //$familiesFirst = Family::select('id')->whereRaw('id IN (Select family_id from species)')->first();

        $families = $familySQL->pluck('name', 'id');

        $species = Specie::where('family_id', $familySQL->first()->id)->pluck('name', 'id');

        return view('admin.trees.create', compact('zones', 'families', 'species'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required'
        ]);

        Tree::create($request->all());
        return redirect()->route('admin.trees.index')->with('action', 'Arbol Registrado');
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
        $tree = Tree::find($id);
        $zones = Zone::all()->pluck('name', 'id');
        $families = Family::whereRaw('id IN (Select family_id from species)')->pluck('name', 'id');

        $species = Specie::where('family_id', $tree->family_id)->pluck('name', 'id');

        return view('admin.trees.edit', compact('tree', 'zones', 'families', 'species'));
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
        $tree = Tree::find($id);
        $tree->update($request->all());

        return redirect()->route('admin.trees.index')->with('action', 'Arbol Actualizado');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tree = Tree::find($id);
        $tree->delete();

        return redirect()->route('admin.trees.index')->with('action', 'Arbol Eliminado');

    }
}
