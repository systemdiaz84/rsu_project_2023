@extends('adminlte::page')

@section('title', "Rol: {$role->name}")

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Rol: <b>{{ ucfirst($role->name) }}</b></h1>
        <div class="lead">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-default float-right">Regresar</a>
        </div>
        
        <div class="container mt-4">

            <h3>Permisos asignados</h3>

            <table class="table table-striped table-responsive">
                <thead>
                    <th scope="col" width="20%">Nombre</th>
                    <th scope="col" width="1%">Guard</th> 
                </thead>

                @foreach($rolePermissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->guard_name }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
    <div class="mt-4">
        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-info">Editar</a>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Regresar</a>
    </div>
@endsection