<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tree;
use App\Models\Admin\Zone;
use App\Models\admin\ZoneCoord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    function index()
    {

        $trees = Tree::select('latitude', 'longitude', 'name')->get();

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

        return view('admin.maps.index', compact('trees', 'perimeter'));
    }
}
