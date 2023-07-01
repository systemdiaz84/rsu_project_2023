<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\admin\Treephoto;
use App\Models\admin\TreePhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $storagePath = public_path('images');

            // Verifica si el directorio de almacenamiento existe, si no, créalo
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Guarda la imagen en el servidor utilizando el almacenamiento de Laravel
            Storage::disk('public')->put('images/' . $imageName, $imageData);

            // Guarda la URL de la imagen en la base de datos
            $treePhoto = new TreePhotos();
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
