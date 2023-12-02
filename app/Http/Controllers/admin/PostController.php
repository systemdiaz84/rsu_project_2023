<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Post;
use App\Models\admin\Familyphoto;
use App\Models\admin\Specie;
use App\Models\admin\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image = $request->file('image')->store('public/posts');
        $url = Storage::url($image);
        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $url,
            'is_active' => 1,
        ]);
        return Redirect()->route('admin.posts.index')->with('success', 'Publicación Registrada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $post = Post::find($id);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('admin.posts.edit', compact('post'));
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
        $post = Post::find($id);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public/posts');
            $url = Storage::url($image);
            $post->image = $url;
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->is_active = $request->is_active;
        $post->save();

        return Redirect()->route('admin.posts.index')->with('success', 'Publicación Actualizada');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->is_active = 0;
        $post->save();

        return Redirect()->route('admin.posts.index')->with('success', 'Publicación Desactivada');
    }

    public function listApi()
    {
        $posts = Post::where('is_active', 1)->get();
        return response()->json(['status' => true, 'message' => 'Publicaciones obtenidas correctamente', 'data' => $posts]);
    }

}