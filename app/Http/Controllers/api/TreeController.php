<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tree;
use Illuminate\Http\Request;
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
            'trees.zone_id',
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            'zones.name as zones_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
        )
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('zones', 'zones.id', '=', 'trees.zone_id')
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
        $trees = Tree::create($request->all());

        /*
        $name = $trees->name . ' ' . $trees->id;
s
        $trees->update([
            'name' => $name
        ]);
        */
        return response()->json(['status' => true ,'message' => 'Árbol registrado correctamente', 'data' => $trees, 'tree_id' => $trees->id]);
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
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
        )
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('zones', 'zones.id', '=', 'trees.zone_id')
            ->where(function ($query) use ($name) {
                $query->where('trees.name', 'like', '%' . $name . '%')
                    ->orWhere('families.name', 'like', '%' . $name . '%')
                    ->orWhere('zones.name', 'like', '%' . $name . '%');
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
            'trees.zone_id',
            'trees.user_id',
            'families.name as family_name',
            'species.name as species_name',
            'zones.name as zones_name',
            DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
        )
            ->join('species', 'species.id', '=', 'specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->join('zones', 'zones.id', '=', 'trees.zone_id')->where('trees.id', $id)->get();

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
        $tree->update($request->all());
        

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
                'trees.zone_id',
                'trees.user_id',
                'families.name as family_name',
                'species.name as species_name',
                'zones.name as zones_name',
                DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
            )
                ->join('species', 'species.id', '=', 'specie_id')
                ->join('families', 'families.id', '=', 'species.family_id')
                ->join('zones', 'zones.id', '=', 'trees.zone_id')
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
                'trees.zone_id',
                'trees.user_id',
                'families.name as family_name',
                'species.name as species_name',
                'zones.name as zones_name',
                DB::raw('(select url from tree_photos where tree_id = trees.id limit 1) as url')
            )
                ->join('species', 'species.id', '=', 'specie_id')
                ->join('families', 'families.id', '=', 'species.family_id')
                ->join('zones', 'zones.id', '=', 'trees.zone_id')
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
                ->where('trees.zone_id', $zone_id)
                ->groupBy('families.name')->get();
        }

        return $families;
    }
}
