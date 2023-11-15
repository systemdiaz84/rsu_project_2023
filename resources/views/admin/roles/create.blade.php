@extends('adminlte::page')

@section('title', 'Añadir Rol')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Nuevo rol</h1>
        <div class="lead">
            Añadir rol y asignar permisos.
        </div>

        <div class="container mt-4">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Ups!</strong> Hay algunos problemas en la entrada de datos.<br><br>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ old('name') }}" 
                        type="text" 
                        class="form-control" 
                        name="name" 
                        placeholder="Nombre" required>
                </div>
                
                <label for="permissions" class="form-label">Asignar Permisos</label>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
                            <th scope="col" width="20%">Nombre</th>
                            <th scope="col" width="1%">Guard</th> 
                        </thead>

                        @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <input type="checkbox" 
                                    name="permission[{{ $permission->name }}]"
                                    value="{{ $permission->name }}"
                                    class='permission'>
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-default">Regresar</a>
            </form>
        </div>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('[name="all_permission"]').on('click', function() {

                if($(this).is(':checked')) {
                    $.each($('.permission'), function() {
                        $(this).prop('checked',true);
                    });
                } else {
                    $.each($('.permission'), function() {
                        $(this).prop('checked',false);
                    });
                }
                
            });
        });
    </script>
@endsection