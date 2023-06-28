<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Zone;
use App\Models\admin\ZoneCoord;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zones = Zone::all();
        return view('admin.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.zones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Zone::create($request->all());
        return redirect()->route('admin.zones.index')->with('action', 'Zona Registrada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zone = Zone::find($id);

        $coords = ZoneCoord::where('zone_id', $id)->get();

        $vertice = ZoneCoord::select(
            'latitude as lat',
            'longitude as lng'
        )->where('zone_id', $id)->get();

        return view(
            'admin.zones.show',
            compact('zone', 'coords', 'vertice')
        );
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
        return view('admin.zones.edit', compact('zone'));
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
        $zone = Zone::find($id);
        $zone->update($request->all());

        return redirect()->route('admin.zones.index')->with('action', 'Zona Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone = Zone::find($id);
        $zone->delete();

        return redirect()->route('admin.zones.index')->with('action', 'Zona Eliminada');
    }
}
