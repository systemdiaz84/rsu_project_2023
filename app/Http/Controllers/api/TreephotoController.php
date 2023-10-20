<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tree;
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
        $treePhotos = TreePhotos::select(
            'tree_photos.id',
            'tree_photos.url',
            'tree_photos.tree_id',
            'trees.name',
        )
            ->join('trees', 'trees.id', '=', 'tree_id')
            ->orderBy('trees.id', 'desc')
            ->get();

        return $treePhotos;
        //return response()->json(['status' => true ,'message' => 'Fotos de arboles  obtenidas correctamente', 'data' => $treePhotos]);

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
        try {
            if ($request->has('image')) {
                
                //Metodo para guardar la imagen en formato de imagen
                //$url = $request->file('image')->storeAs('images/trees/' . $request->input('tree_id'), $imageName,'public');
                
                $base64Image = $request->input("image");
                
                
                if (str_contains($base64Image, 'data:image')) {
                    list($type, $imageData) = explode(';', $base64Image);
                    list(,$extension) = explode('/',$type);
                    list(,$imageData) = explode(',', $imageData);
                } else {
                    $imageData = $base64Image;
                }
                

                // Genera un nombre único para la imagen
                $imageName = uniqid() . '.jpg';
                //$imageName = uniqid().'.'.$extension; //codigo si se quiere mantener la extención del archivo
                
                $imageData = base64_decode($imageData);
                
                $url = 'images/trees/' . $request->input('tree_id') . '/' . $imageName;
                
                // Guarda la imagen en el servidor utilizando el almacenamiento de Laravel
                Storage::disk('public')->put($url, $imageData);

                // Guarda la URL de la imagen en la base de datos
                $treePhoto = new TreePhotos();
                $treePhoto->url ='storage/'.$url;
                $treePhoto->tree_id = $request->input('tree_id');
                $treePhoto->save();
                
                return response()->json(['status' => true ,'message' => 'Árbol foto registrada correctamente', 'data' => $treePhoto]);
            }
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Error al almacenar la imagen', 'error' => $ex], 400);

        }
    }

    public function old_store(Request $request) {
        if ($request->has('image')) {
            $base64Image = $request->input('image');

            // Decodifica la imagen en base64
            $imageData = base64_decode($base64Image);

            // Genera un nombre único para la imagen
            $imageName = uniqid() . '.jpg';

            // Ruta de almacenamiento de las imágenes
            $storagePath = public_path('images/trees/' . $request->input('tree_id'));


            // Verifica si el directorio de almacenamiento existe, si no, créalo
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $url = 'images/trees/' . $request->input('tree_id') . '/' . $imageName;

            // Guarda la imagen en el servidor utilizando el almacenamiento de Laravel
            Storage::disk('public')->put($url, $imageData);

            // Guarda la URL de la imagen en la base de datos
            $treePhoto = new TreePhotos();
            $treePhoto->url ='storage/'.$url;
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
        $treePhoto = TreePhotos::where('tree_id',$id)->get();

        return $treePhoto;
        //return response()->json(['status' => true ,'message' => 'Árbol foto obtenido correctamente', 'data' => $treePhoto]);
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
        /*
        if ($request->all() == []){
            return response()->json(['status' => false, 'data' => $request, 'message' => 'Ingrese parámetros']); 
        }

        $treePhoto = TreePhotos::find($id);

        if ($request->has('image')) {
            $base64Image = $request->input('image');

            // Decodifica la imagen en base64
            $imageData = base64_decode($base64Image);

            //Obtenemos la ruta de la imagen
            $url = $treePhoto->url;
            

            // Verifica si la imagen existe, si no, mensaje
            if (!file_exists($url)) {
                return response()->json(['message' => 'URL de la imagen no existente', 'url' => $url]);
            }


            // Guarda la nueva imagen en el servidor utilizando el almacenamiento de Laravel
            Storage::disk('public')->put($url, $imageData);
        }

        if ($request->has('tree_id')) {
            $treePhoto->update($request->input('tree_id'));
        }

        return response()->json(['status' => true ,'message' => 'Árbol foto actualizado correctamente', 'data' => $treePhoto]);
        */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $treephoto = TreePhotos::find($id);
        $treephoto->delete();

        return response()->json(['status' => true ,'message' => 'Árbol foto eliminado correctamente', 'data' => []]);

    }
}