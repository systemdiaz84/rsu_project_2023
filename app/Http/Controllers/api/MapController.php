<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
                //'name' => $zone[0]->zone, // Cambiar 'zone' por 'name'
                'coords' => $coords,
            ];
        })->values(); // Reindexar las claves numéricas del resultado

        // Convertir los datos a formato JSON


        return $perimeter;
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zones = DB::table('zones')
            ->leftJoin('zone_coords', 'zones.id', '=', 'zone_coords.zone_id')
            ->select('zones.name as zone', 'zone_coords.latitude', 'zone_coords.longitude')
            ->where('zone_coords.zone_id',$id)
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
                //'name' => $zone[0]->zone, // Cambiar 'zone' por 'name'
                'coords' => $coords,
            ];
        })->values(); // Reindexar las claves numéricas del resultado

        // Convertir los datos a formato JSON


        return $perimeter;
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
}