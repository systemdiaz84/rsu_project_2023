<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\admin\HomeMembers;
use App\Models\admin\HomeTree;
use App\Models\admin\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOption\None;

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
            'zones.id as zone_id',
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
        )
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
            ->join('home', 'home.id', '=', 'home_trees.home_id')
            ->join('zones', 'zones.id', '=', 'home.zone_id')
            ->orderBy('trees.id', 'desc')
            ->get();

            return $trees;
            //return response()->json(['status' => true ,'message' => 'Árboles obtenidos correctamente', 'data' => $trees]);
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
        /* Validar los datos 
        $request->validate([
            'atributo' =>  'validadores'
        ]);
        */

        //Creamos el nuevo árbol con todos los parametros ingresados menos home_id que se usa para la asociación
        $tree = new Tree();
        $tree->name = $request->input("name");
        $tree->birth_date = $request->input("birth_date");
        $tree->planting_date = $request->input("planting_date");
        $tree->latitude = $request->input("latitude");
        $tree->longitude = $request->input("longitude");
        $tree->family_id = $request->input("family_id");
        $tree->description = $request->input("description");
        $tree->specie_id = $request->input("specie_id");

        $user = Auth::user();
        
        $tree->user_id = $user->id;
        $tree->is_pending = 0;
        $tree->is_active = 1;
        $tree->save();

        //$trees = Tree::create($request->except('home_id'));

        //Creamos la asociación home_trees
        $homeTree = new HomeTree();
        $homeTree->home_id = $request->input('home_id');
        $homeTree->tree_id = $tree->id;
        $homeTree->save();

        /*
        $name = $trees->name . ' ' . $trees->id;
s
        $trees->update([
            'name' => $name
        ]);
        */
        return response()->json(['status' => true ,'message' => 'Árbol registrado correctamente', 'data' => $tree, 'tree_id' => $tree->id]);
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
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
        )
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->where(function ($query) use ($name) {
                $query->where('trees.name', 'like', '%' . $name . '%')
                    ->orWhere('families.name', 'like', '%' . $name . '%');
                    //->orWhere('trees.id', '==', $name);
            })
            ->get();
        
            return $trees;
            //return response()->json(['status' => true ,'message' => 'Árbol obtenido correctamente', 'data' => $trees]);
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
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            'zones.name as zones_name', 
            'home.name as homes_name',
            'home.id as home_id',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
        )
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
            ->join('home', 'home.id', '=', 'home_trees.home_id')
            ->join('zones', 'zones.id', '=', 'home.zone_id')
            ->where('trees.id', $id)->get();

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
        /*
        $request->validate([
            'name' => 'required'
        ]);
        */
        //
        if ($request->all() == []){
            return response()->json(['status' => false, 'data' => $request, 'message' => 'Ingrese parámetros']); 
        }

        $tree = Tree::find($id);
        $tree->update($request->except('home_id'));

        //Verificamos si se actualizará el hogar asignado
        if ($request->has('home_id')) {
            //modificamos el estado de los hogares anteriores
            HomeTree::where( "tree_id", $tree->id)->update(['is_active' => 0]);


            //Creamos la nueva relación 
            $homeTree = new HomeTree();
            $homeTree->tree_id = $tree->id;
            $homeTree->home_id = $request->input("home_id");
            $homeTree->is_active = 1;
            $homeTree->save();
        }

        return response()->json(['status' => true ,'message' => 'Árbol actualizado correctamente', 'data' => $tree]);

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
        $tree = Tree::find($id);
        $tree->delete();

        return response()->json(['status' => true ,'message' => 'Árbol eliminado correctamente', 'data' => []]);

    }

    //TODO: search trees use coordinates
    public function trees_zone($zone_id)
    {
        if ($zone_id == 0) {
            $trees = Tree::select(
                'trees.id',
                'trees.name',
                'trees.birth_date',
                'trees.planting_date',
                'trees.description',
                'trees.latitude',
                'trees.longitude',
                'trees.specie_id',
                'home.zone_id',
                'trees.user_id',
                'families.name as family_name',
                'species.name as species_name',
                'zones.name as zones_name',
                DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
            )
                ->join('species', 'species.id', '=', 'specie_id')
                ->join('families', 'families.id', '=', 'species.family_id')
                ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
                ->join('home', 'home.id', '=', 'home_trees.home_id')
                ->join('zones', 'zones.id', '=', 'home.zone_id')
                ->orderBy('trees.id', 'desc')
                ->get();
        } else {
            $trees = Tree::select(
                'trees.id',
                'trees.name',
                'trees.birth_date',
                'trees.planting_date',
                'trees.description',
                'trees.latitude',
                'trees.longitude',
                'trees.specie_id',
                'home.zone_id',
                'trees.user_id',
                'families.name as family_name',
                'species.name as species_name',
                'zones.name as zones_name',
                DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
            )
                ->join('species', 'species.id', '=', 'specie_id')
                ->join('families', 'families.id', '=', 'species.family_id')
                ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
                ->join('home', 'home.id', '=', 'home_trees.home_id')
                ->join('zones', 'zones.id', '=', 'home.zone_id')
                ->where('zones.id', $zone_id)
                ->orderBy('trees.id', 'desc')
                ->get();
        }

        return $trees;
    }

    function trees_families($zone_id)
    {
        if ($zone_id == 0) {
            $families = Tree::select(
                'families.name as name',
                DB::raw('count(*) as count')
            )
                ->join('species', 'species.id', '=', 'trees.specie_id')
                ->join('families', 'families.id', '=', 'species.family_id')
                //->where('trees.zone_id', $zone_id)
                ->groupBy('families.name')->get();
        } else {
            $families = Tree::select(
                'families.name as name',
                DB::raw('count(*) as count')
            )
                ->join('species', 'species.id', '=', 'trees.specie_id')
                ->join('families', 'families.id', '=', 'species.family_id')
                ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
                ->join('home', 'home.id', '=', 'home_trees.home_id')
                ->where('home.zone_id', $zone_id)
                ->groupBy('families.name')->get();
        }

        return $families;
    }
}
