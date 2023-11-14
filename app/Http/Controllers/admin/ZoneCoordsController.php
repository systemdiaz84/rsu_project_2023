<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Zone;
use App\Models\admin\ZoneCoord;
use Illuminate\Http\Request;

class ZoneCoordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Zone::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $coordsJSON = $request->input('coords');
        $coords = json_decode($coordsJSON, true);

        $zone = Zone::where('id', $request->input('zone_id'))->first();
        $zone->area = $request->input('area');
        $zone->save();

        ZoneCoord::where('zone_id', $request->input('zone_id'))->delete();
        foreach ($coords as $coord) {
            ZoneCoord::create([
                'zone_id' => $request->input('zone_id'),
                'latitude' => $coord['lat'],
                'longitude' => $coord['lng'],
            ]);
    }
        return redirect()->route('admin.zones.show', $request->zone_id)->with('action', 'Coordena Agregada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vertice = ZoneCoord::select(
            'latitude as lat',
            'longitude as lng'
        )->where('zone_id', $id)->get();

        return $vertice;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $zone = Zone::find($id);

        $vertice = ZoneCoord::select(
            'latitude as lat',
            'longitude as lng'
        )->where('zone_id', $id)->get();

        $lastcoords = ZoneCoord::select(
            'latitude as lat',
            'longitude as lng'
        )
            ->where('zone_id', $id)
            ->latest()
            ->first();

        return view('admin.zonecoords.create', compact('zone', 'vertice','lastcoords'));
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
        $zonecoord = ZoneCoord::find($id);
        $zone_id = $zonecoord->zone_id;
        $zonecoord->delete();
        return redirect()->route('admin.zones.show', $zone_id)->with('action', 'Coordena Eliminada');
    }
}
