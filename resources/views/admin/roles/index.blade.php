@extends('adminlte::page')

@section('title', 'Roles')

@section('content')
    
    <div class="bg-light p-4 rounded">
        <h1>Roles</h1>
        <div class="lead">
            Listado de roles
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm float-right">Añadir rol</a>
        </div>
        
        <div class="mt-2">
            @include('layouts.partials.messages')
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
            <tr>
                <th width="1%">ID</th>
                <th>Nombre</th>
                <th width="3%" colspan="3">Acciones</th>
            </tr>
                @foreach ($roles as $key => $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('admin.roles.show', $role->id) }}">Ver</a>
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.roles.edit', $role->id) }}">Editar</a>
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE','route' => ['admin.roles.destroy', $role->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Borrar', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="d-flex">
            {!! $roles->links() !!}
        </div>

    </div>
@endsection