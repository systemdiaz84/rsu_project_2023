<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\admin\Treephoto;
use Illuminate\Http\Request;

class TreephotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('image')) {
            $base64Image = $request->input('image');
            
            // Decodifica la imagen en base64
            $imageData = base64_decode($base64Image);
            
            // Genera un nombre único para la imagen
            $imageName = uniqid() . '.jpg';
            
            // Ruta de almacenamiento de las imágenes
            $storagePath = public_path('images');
            
            // Guarda la imagen en el servidor
            file_put_contents($storagePath . '/' . $imageName, $imageData);
            
            // Guarda la URL de la imagen en la base de datos
            $treePhoto = new Treephoto();
            $treePhoto->url = 'images/' . $imageName;
            $treePhoto->tree_id = $request->input('tree_id');
            $treePhoto->save();
            
            return response()->json(['message' => 'Imagen almacenada correctamente']);
        }
        
        return response()->json(['message' => 'Error al almacenar la imagen'], 400);
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
        //
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
        //
    }
}
