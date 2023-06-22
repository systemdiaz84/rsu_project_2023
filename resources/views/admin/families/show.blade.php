@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Visualización de Familia</h1>
@stop

@section('content')
    <div class="card">

        <div class="card-body">

            <div class="form-group">
                <label for="">Nombre</label> <br>
                {{ $family->name }}
            </div>

            <div class="form-group">
                <label for="">Descripción</label> <br>
                {{ $family->description }}
            </div>

            <a href={{ route('admin.families.edit', $family) }} class="btn btn-success"><i
                    class="bi bi-pencil-fill"></i>&nbsp;&nbsp;Editar</a>

            <a href={{ route('admin.families.index') }} class="btn btn-danger"><i
                    class="bi bi-backspace-fill"></i>&nbsp;&nbsp;Retornar</a>



            <div class="card mt-3">

                <div class="card-header">
                    <h5>Carga de imágenes</h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        @foreach ($photos as $photo)
                            <div class="col-2">

                                <div class="card img-thumbnail" style="width: 150px;height:150px">
                                    <img src={{ asset($photo->url) }} alt="" style="width: 100%;height:100%">
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                <div class="card-footer">
                    <form action={{ route('admin.familyphotos.store') }} method="post" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="family_id" value={{ $family->id }}>

                        <input type="file" name="url" accept="image/*"> <button type="submit"
                            class="btn btn-sm btn-success">Cargar</button>

                    </form>

                </div>

            </div>

        </div>





    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
