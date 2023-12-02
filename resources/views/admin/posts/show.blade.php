@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Visualización de Publicaciones</h1>
@stop

@section('content')
    <div class="card">

        <div class="card-body">

            <div class="form-group">
                <label for="">Título</label> <br>
                {{ $post->title }}
            </div>

            <div class="form-group">
                <label for="">Descripción</label> <br>
                {{ $post->description }}
            </div>

            <div class="form-group">
                <label for="">Imagen</label> <br>
                <img src="{{ asset($post->image) }}" alt="" class="img-fluid" style="max-width: 100%;">
            </div>
            <div class="form-group">
                <label for="">Estado</label> <br>
                @if ($post->is_active)
                    <span class="badge badge-success">Activo</span>
                @else
                    <span class="badge badge-danger">Inactivo</span>
                @endif
            </div>
            <a href={{ route('admin.posts.edit', $post) }} class="btn btn-success"><i
                    class="bi bi-pencil-fill"></i>&nbsp;&nbsp;Editar</a>

            <a href={{ route('admin.posts.index') }} class="btn btn-danger"><i
                    class="bi bi-backspace-fill"></i>&nbsp;&nbsp;Retornar</a>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
