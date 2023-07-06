<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Procedure;
use App\Models\admin\ProcedureTypes;
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
        $proceduretypes = ProcedureTypes::all();
        return view('admin.proceduretypes.index', compact('proceduretypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proceduretypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ProcedureTypes::create($request->all());
        return redirect()->route('admin.proceduretypes.index')->with('success','Tipo de procedimiento Registrado');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proceduretypes = ProcedureTypes::find($id);
        return view('admin.proceduretypes.edit', compact('proceduretypes'));

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
        $proceduretypes = ProcedureTypes::find($id);
        $proceduretypes->update($request->all());
        return redirect()->route('admin.proceduretypes.index')->with('success','Tipo de procedimiento Actualizado');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proceduretype = ProcedureTypes::find($id);

        $countpro = Procedure::where('procedure_type_id', $id)->count();

        if ($countpro > 0) {
            return Redirect()->route('admin.proceduretypes.index')->with('error', 'No se puede eliminar ya que tiene registros asociados');
        } else {
            $proceduretype->delete();
            return redirect()->route('admin.proceduretypes.index')->with('success', 'Tipo de procedimiento Eliminado');
        }
    }
}
