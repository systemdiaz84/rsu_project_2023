<?php

namespace App\Http\Controllers\api;

use App\Models\Responsible;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $responsible = Responsible::all();

        return $responsible;
        //return response()->json(['status' => true ,'message' => 'Responsables obtenidos correctamente', 'data' => $responsible]);


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
        $responsible = Responsible::create($request->all());

        return response()->json(['status' => true ,'message' => 'Responsable registrado correctamente', 'data' => $responsible]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $responsible = Responsible::find($id);

        return response()->json(['status' => true ,'message' => 'Responsable obtenido correctamente', 'data' => $responsible]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $responsible = Responsible::find($id);
        $responsible->update($request->all());

        return response()->json(['status' => true ,'message' => 'Responsable actualizado correctamente', 'data' => $responsible]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        $responsible = Responsible::find($id);
        $responsible->delete();

        return response()->json(['status' => true ,'message' => 'Responsable eliminado correctamente', 'data' => []]);


    }
}
