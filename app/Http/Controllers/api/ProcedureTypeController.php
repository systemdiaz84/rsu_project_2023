<?php

namespace App\Http\Controllers\api;

use App\Models\admin\ProcedureTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProcedureTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $procedureTypes = ProcedureTypes::all();
        
        return $procedureTypes;
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
        $procedureTypes = ProcedureTypes::create($request->all());

        return response()->json(['message' => 'Tipo de procedimiento registrado correctamente', 'data' => $procedureTypes, 'status' => true]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
        $procedureTypes = ProcedureTypes::find($id);

        return $procedureTypes;
        //return response()->json(['message' => 'Tipo de procedimiento obtenido correctamente', 'data' => $procedureTypes, 'status' => true]);
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admin\ProcedureTypes  $procedureTypes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $procedureTypes = ProcedureTypes::find($id);
        $procedureTypes->update($request->all());

        return response()->json(['message' => 'Tipo de procedimiento actalizado correctamente', 'data' => $procedureTypes, 'status' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admin\ProcedureTypes  $procedureTypes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $procedureTypes = ProcedureTypes::find($id);
        $procedureTypes->delete();

        return response()->json(['message' => 'Tipo de procedimiento eliminado correctamente', 'data' => [], 'status' => true]);
    }
}
