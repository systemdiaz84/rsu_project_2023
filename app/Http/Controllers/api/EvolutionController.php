<?php

namespace App\Http\Controllers\api;

use App\Models\admin\Evolution;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $evolution = Evolution::all();

        return $evolution;
        //return response()->json(['status' => true, 'message' => 'Evoluciones obtenidas correctamente', 'data' => $evolution]);

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
        $evolution = Evolution::create($request->all());

        return response()->json(['message' => 'Evolución registrada correctamente', 'status' => TRUE, 'data' => $evolution]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Evolution  $evolution
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $evolution = Evolution::find($id);

        return $evolution;
        //return response()->json(['message' => 'Evolución obtenida correctamente', 'status' => TRUE, 'data' => $evolution]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evolution  $evolution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $evolution = Evolution::find($id);
        $evolution->update($request->all());

        return response()->json(['message' => 'Evolución modificada correctamente', 'status' => TRUE, 'data' => $evolution]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evolution  $evolution
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $evolution = Evolution::find($id);
        $evolution->delete();

        return response()->json(['message' => 'Evolución eliminada correctamente', 'status' => TRUE, 'data' => []]);


    }

    public function showEvolutionsByTree(int $tree_id) {
        $evolution = Evolution::select('evolutions.*', 'states.name as estado')
                        ->join('states', 'states.id', '=', 'state_id')
                        ->where('tree_id', '=', $tree_id)
                        ->get();

        
        return $evolution;
        //return response()->json(['status' => true, 'message' => 'Evoluciones obtenidas por árbol correctamente', 'data' => $evolution]);
                
    }

}
