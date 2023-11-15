@extends('adminlte::page')

@section('title', "Editando Rol: {$role->name}")

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Editar rol: <b>{{ ucfirst($role->name) }}</b></h1>
        <div class="lead">
            Editar nombre y permisos. <a href="{{ route('admin.roles.index') }}" class="btn btn-default float-right">Regresar</a>
        </div>

        <div class="container mt-4">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Error!</strong> Hay algunos errores en los datos ingresados, verifique.<br><br>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
                @method('patch')
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ $role->name }}" 
                        type="text" 
                        class="form-control" 
                        name="name" 
                        placeholder="Name" required>
                </div>
                
                <label for="permissions" class="form-label">Permisos asignados</label>
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
                                    class='permission'
                                    {{ in_array($permission->name, $rolePermissions) 
                                        ? 'checked'
                                        : '' }}>
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Regresar</a>
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