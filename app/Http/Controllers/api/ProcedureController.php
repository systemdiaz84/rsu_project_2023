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
        return Procedure::all();
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

        return response()->json(['message' => 'Procedimiento registrado correctamente', 'procedure_id' => $procedure->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Procedure  $procedure
     * @return \Illuminate\Http\Response
     */
    public function show(Procedure $procedure)
    {
        //
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

        return response()->json(['message' => 'Procedimiento actualizado correctamente', 'procedure_id' => $procedure->id]);
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

        return response()->json(['message' => 'Procedimiento eliminado correctamente', 'procedure_id' => $procedure->id]);

    }
}
