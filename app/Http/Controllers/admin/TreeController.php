<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Family;
use App\Models\admin\HomeTree;
use App\Models\admin\Specie;
use App\Models\admin\Tree;
use App\Models\admin\TreePhotos;
use App\Models\admin\Zone;
use App\Models\admin\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $trees = Tree::select('trees.id', 'trees.name', 'families.name as familyname', 'species.name as speciename', 'zones.name as zonename', 'home.name as homename')

            ->join('species', 'trees.specie_id', '=', 'species.id')
            ->join('families', 'species.family_id', '=', 'families.id')
            ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
            ->join('home', 'home.id', '=', 'home_trees.home_id')
            ->join('zones', 'zones.id', '=', 'home.zone_id')->get();

        return view('admin.trees.index', compact('trees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $homes = Home::select('home.id', 'home.name')
            ->where('home.zone_id', function ($query) {
                $query->select('zones.id')
                    ->from('zones')
                    ->join('district', 'district.id', '=', 'zones.district_id')
                    ->where('district.departament_id', '14')
                    ->limit(1);
            })->pluck('name', 'id');

        $zones = Zone::select('zones.id', 'zones.name')
            ->join('district', 'district.id', '=', 'zones.district_id')
            ->where('district.departament_id', '14')->pluck('name', 'id');
        $familySQL = Family::whereRaw('id IN (Select family_id from species)')->get();
        $families = $familySQL->pluck('name', 'id');
        $species = Specie::where('family_id', $familySQL->first()->id)->pluck('name', 'id');
        return view('admin.trees.create', compact('zones', 'families', 'species', 'homes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);

        $home_id = $request->home_id;
        $request->offsetUnset('home_id');
        $request->offsetUnset('zone_id');


        $trees = Tree::create($request->all());

        $name = $trees->name . ' ' . $trees->id;

        $trees->update([
            'name' => $name
        ]);
        $trees->is_pending = 0;
        // $trees->is_active = 1;
        $trees->save();

        $home_tree = new HomeTree();
        $home_tree->home_id = $home_id;
        $home_tree->tree_id = $trees->id;
        $home_tree->save();

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
        $tree = Tree::findOrFail($id);
        $photos = TreePhotos::where('tree_id', $id)->get();

        return view('admin.trees.show', compact('tree', 'photos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tree = Tree::select('trees.*', 'home.id as home_id', 'home.zone_id as zone_id')
            ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
            ->join('home', 'home.id', '=', 'home_trees.home_id')
            ->where('trees.id', $id)->first();
        $families = Family::whereRaw('id IN (Select family_id from species)')->pluck('name', 'id');
        $homes = Home::select('home.id', 'home.name')
            ->where('home.zone_id', $tree->zone_id)->pluck('name', 'id');
        $zones = Zone::select('zones.id', 'zones.name')
            ->join('district', 'district.id', '=', 'zones.district_id')
            ->where('district.departament_id', '14')->pluck('name', 'id');
        $species = Specie::where('family_id', $tree->family_id)->pluck('name', 'id');

        return view('admin.trees.edit', compact('tree', 'families', 'species', 'homes', 'zones'));
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
        $request->offsetUnset('zone_id');
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
