<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\District;
use App\Models\admin\Home;
use App\Models\admin\Province;
use App\Models\admin\Tree;
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
        $provinces = Province::where('departament_id','14')->get();
        $districts = District::where('departament_id','14')->get();
        return view('admin.zones.create',compact('provinces','districts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Zone::create($request->except('province_id'));
        return redirect()->route('admin.zones.index')->with('success', 'Zona Registrada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zone = Zone::select('z.id','z.name','z.description','z.area','d.province_id','d.name as district_name','p.name as province_name','z.district_id')->from('zones as z')
            ->join('district as d','z.district_id','=','d.id')
            ->join('province as p','d.province_id','=','p.id')
            ->where('z.id',$id)->first();

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
        $zone = Zone::select('z.id','z.name','z.description','z.area','d.province_id','z.district_id')->from('zones as z')->join('district as d','z.district_id','=','d.id')->where('z.id',$id)->first();
        $provinces = Province::where('departament_id','14')->get();
        $districts = District::where('departament_id','14')->where('province_id',$zone->province_id)->get();
        return view('admin.zones.edit', compact('zone','provinces','districts'));
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
        $zone->update($request->except('province_id'));

        return redirect()->route('admin.zones.index')->with('success', 'Zona Actualizada');
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

        $counttrees = Home::where('zone_id', $id)->count();
        $countcoords = ZoneCoord::where('zone_id', $id)->count();


        if ($counttrees > 0 ||  $countcoords > 0) {
            return Redirect()->route('admin.zones.index')->with('error', 'No se puede eliminar ya que tiene registros asociados');
        } else {
            $zone->delete();
            return redirect()->route('admin.zones.index')->with('success', 'Zona Eliminada');
        }
    }

    public function homes_zone($id)
    {
        $homes = Home::where('zone_id', $id)->where('is_active', 1)->get();
        return $homes;
    }
}
