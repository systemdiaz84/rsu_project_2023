<?php

namespace App\Http\Controllers\api;

use App\Models\EvolutionPhoto;
use App\Http\Controllers\Controller;
use App\Models\admin\Evolution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvolutionPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $evolutionPhotos = EvolutionPhoto::all();
        
        return $evolutionPhotos;

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

        try {
            if ($request->has('image')) {
                
                //Metodo para guardar la imagen en formato de imagen
                //$url = $request->file('image')->storeAs('images/trees/' . $request->input('tree_id'), $imageName,'public');
                
                $base64Image = $request->input("image");
                
                list($type, $imageData) = explode(';', $base64Image);
                list(,$extension) = explode('/',$type);
                list(,$imageData) = explode(',', $imageData);

                // Genera un nombre único para la imagen
                $imageName = uniqid() . '.jpg';
                //$imageName = uniqid().'.'.$extension; //codigo si se quiere mantener la extención del archivo
                
                $imageData = base64_decode($imageData);
                
                $url = 'images/evolution/' . $request->input('evolution_id') . '/' . $imageName;
                
                // Guarda la imagen en el servidor utilizando el almacenamiento de Laravel
                Storage::disk('public')->put($url, $imageData);

                // Guarda la URL de la imagen en la base de datos
                $evolutionPhoto = new EvolutionPhoto();
                $evolutionPhoto->url ='storage/'.$url;
                $evolutionPhoto->evolution_id = $request->input('evolution_id');
                $evolutionPhoto->save();
                

                return response()->json(['status' => true ,'message' => 'Foto de evolución registrada correctamente', 'data' => $evolutionPhoto]);
            }
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Error al almacenar la imagen', 'error' => $ex], 400);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $evolutionPhoto = EvolutionPhoto::find($id);
        
        return $evolutionPhoto;
        //return response()->json(['message' => 'Foto de evolución obtenida correctamente', 'status' => TRUE, 'data' => $evolutionPhoto]);        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EvolutionPhoto  $evolutionPhoto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*
        $evolutionPhoto = EvolutionPhoto::find($id);
        $evolutionPhoto->update($request->all());
        

        return response()->json(['message' => 'Foto de evolución actualizada correctamente', 'status' => TRUE, 'data' => $evolutionPhoto]);        
        */
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EvolutionPhoto  $evolutionPhoto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $evolutionPhoto = EvolutionPhoto::find($id);
        $evolutionPhoto->delete();
        
    
        return response()->json(['status' => true, 'message' => 'Foto de evolución eliminado correctamente', 'data' => []]);        

    }
}
