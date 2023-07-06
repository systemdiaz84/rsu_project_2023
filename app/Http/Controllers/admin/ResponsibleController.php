<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Procedure;
use App\Models\admin\Responsible;
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
        $responsibles = Responsible::all();
        return view('admin.responsibles.index', compact('responsibles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.responsibles.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Responsible::create($request->all());
        return redirect()->route('admin.responsibles.index')->with('success','Responsable Registrado');
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
        $responsibles = Responsible::find($id);
        return view('admin.responsibles.edit', compact('responsibles'));

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
        $responsibles = Responsible::find($id);
        $responsibles->update($request->all());
        return redirect()->route('admin.responsibles.index')->with('success','Responsable Actualizado');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $responsibles = Responsible::find($id);
        $countres = Procedure::where('responsible_id', $id)->count();

        if ($countres > 0) {
            return Redirect()->route('admin.responsibles.index')->with('error', 'No se puede eliminar ya que tiene registros asociados');
        } else {
            $responsibles->delete();
            return redirect()->route('admin.responsibles.index')->with('success', 'Responsable Eliminado');
        }

    }
}
