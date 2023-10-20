<?php

namespace App\Http\Controllers\api;

use App\Models\Procedure;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $procedure = Procedure::all();

        return $procedure;
        //return response()->json(['status' => true ,'message' => 'Procedimientos obtenidos correctamente', 'data' => $procedure]);

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
        $procedure = Procedure::create($request->all());

        return response()->json(['status' => true ,'message' => 'Procedimiento registrado correctamente', 'data' => $procedure]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $procedure = Procedure::find($id);

        return $procedure;
        //return response()->json(['status' => true ,'message' => 'Procedimiento obtenido correctamente', 'data' => $procedure]);
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $procedure = Procedure::find($id);
        $procedure->update($request->all());

        return response()->json(['status' => true ,'message' => 'Procedimiento actualizado correctamente', 'data' => $procedure]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $procedure = Procedure::find($id);
        $procedure->delete();

        return response()->json(['status' => true ,'message' => 'Procedimiento eliminado correctamente', 'data' => []]);

    }

    public function showProceduresByTreeId(int $tree_id) {
        $procedures = Procedure::select("procedures.*", "procedure_types.name as procedure_type_name")
                                ->join('procedure_types', 'procedure_types.id', '=', 'procedures.procedure_type_id')
                                ->where("tree_id", "=", $tree_id)

                                ->get();

        return $procedures;
    }

}
