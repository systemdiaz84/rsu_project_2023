@extends('adminlte::page')

@section('title', 'Listado de usuarios')

@section('content_header')

@stop

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">

            <button type="button" class="btn btn-success float-right" id="btnRegistrar">
                <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Registrar</button>

            <h4>Listado de usuarios</h4>


        </div>
        <div class="card-body">
            <table class="table table-striped" id="user_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>EMAIL</th>
                        <th>ROLES</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td width="10px">
                                <button class="btn btn-secondary btnEditar btn-sm" data-id={{ $user->id }}><i
                                        class="fas fa-edit"></i></button>
                            <td width="10px">
                                <a href={{ route('admin.users.show', $user) }} class="btn btn-primary btn-sm"><i
                                        class="fas fa-eye"></i></a>
                            </td>
                            <td width="10px">
                                <form action={{ route('admin.users.destroy', $user->id) }} method='post' class="frmDelete">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de usuarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>

            </div>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(document).ready(function() {

            $('#btnRegistrar').click(function() {
                $.ajax({
                    url: "{{ route('admin.users.create') }}",
                    type: 'GET',
                    success: function(response) {

                        $("#Modal .modal-body").html(response);
                        $("#Modal").modal('show');
                    }
                })
            })

            $('.btnEditar').click(function() {

                var id = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('admin.users.edit', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {

                        $("#Modal .modal-body").html(response);
                        $("#Modal").modal('show');
                    }
                })
            })


            $('.frmDelete').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Seguro de eliminar?',
                    text: "Este proceso es irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                })
            })

            var table = $('#user_table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
                },
            });

        });
    </script>

    @if (null !== session('success'))
        <script>
            Swal.fire(
                'Proceso Exitoso',
                '{{ session('success') }}',
                'success'
            )
        </script>
    @endif


    @if (null !== session('error'))
        <script>
            Swal.fire(
                'Ocurrió un error',
                '{{ session('error') }}',
                'error'
            )
        </script>
    @endif
@endsection