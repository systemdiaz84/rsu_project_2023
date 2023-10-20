@extends('adminlte::page')

@section('title', 'Añadir Permiso')

@section('content')
    <div class="bg-light p-4 rounded">
        <h2>Añadir nuevo permiso</h2>

        <div class="container mt-4">

            <form method="POST" action="{{ route('admin.permissions.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ old('name') }}" 
                        type="text" 
                        class="form-control" 
                        name="name" 
                        placeholder="Nombre" required>

                    @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-default">Regresar</a>
            </form>
        </div>

    </div>
@endsection