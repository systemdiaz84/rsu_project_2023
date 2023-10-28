<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tree;
use App\Models\admin\TreePhotos;
use App\Models\Admin\Zone;
use App\Models\admin\ZoneCoord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    function index()
    {

        $trees = Tree::select('latitude', 'longitude', 'name')->get();

        $treesDescription = DB::table('trees')
                            ->join('species', 'species.id', '=', 'trees.specie_id')
                            ->join('families', 'families.id', '=', 'trees.family_id')
                            ->join('home_trees', 'home_trees.tree_id', '=', 'trees.id')
                            ->join('home', 'home.id', '=', 'home_trees.home_id')
                            ->join('zones', 'zones.id', '=', 'home.zone_id')
                            ->select('trees.id as id', 'latitude', 'longitude', 'trees.name as name', 'species.name as specie', 'families.name as family', 'zones.name as zone', 'trees.description as description')
                            ->get();

        $treePhotos = TreePhotos::select('tree_id', 'url')->get()->groupBy('tree_id');

        $zones = DB::table('zones')
            ->leftJoin('zone_coords', 'zones.id', '=', 'zone_coords.zone_id')
            ->select('zones.name as zone', 'zone_coords.latitude', 'zone_coords.longitude')
            ->get();

        // Agrupa las coordenadas por zona
        $groupedZones = $zones->groupBy('zone');

        // Formatea los datos en un formato JSON
        $perimeter = $groupedZones->map(function ($zone) {
            $coords = $zone->map(function ($item) {
                return [
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                ];
            })->toArray(); // Convertir la colección de coordenadas en una matriz
        
            return [
                'name' => $zone[0]->zone, // Cambiar 'zone' por 'name'
                'coords' => $coords,
            ];
        })->values(); // Reindexar las claves numéricas del resultado
        
        // Convertir los datos a formato JSON

        
        //return $perimeter;

        return view('admin.maps.index', compact('trees', 'perimeter', 'treesDescription', 'treePhotos'));
    }
}
