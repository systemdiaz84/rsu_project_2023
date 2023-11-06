@extends('adminlte::page')

@section('title', 'Listado de hogares')

@section('content_header')

@stop

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">


            <button type="button" class="btn btn-success float-right" id="btnRegistrar">
                <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Registrar</button>
            <h4>Listado de hogares</h4>


        </div>
        <div class="card-body">
            <table class="table table-striped" id="home_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Usuario Creador</th>
                        <th>Zona</th>
                        <th>Pendiente</th>
                        <th>Público</th>

                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($homes as $home)
                        <tr>
                            <td>{{ $home->id }}</td>
                            <td>{{ $home->code }}</td>
                            <td>{{ $home->name }}</td>
                            <td>{{ $home->direction }}</td>
                            <td>{{ $home->username }} {{ $home->userlastname }}</td>
                            <td>{{ $home->zonename }}</td>
                            <td>
                                <span class="d-flex justify-content-center">
                                    <span class="badge badge-pill badge-{{ $home->is_pending ? 'warning' : 'success' }}">
                                        {{ $home->is_pending ? 'Pendiente' : 'Activo' }}
                                    </span>
                                </span>
                                @if ($home->is_pending)
                                    <div class="btn-group mt-1 d-flex justify-content-center">
                                        <a href="{{ route('admin.home.accept', $home->id) }}" class="btn btn-success btn-sm">
                                            Aceptar
                                        </a>
                                        <a href="{{ route('admin.home.reject', $home->id) }}" class="btn btn-danger btn-sm">
                                            Rechazar
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td><span class="badge badge-pill badge-{{ $home->is_public ? 'success' : 'warning' }}">
                                    {{ $home->is_public ? 'Público' : 'Privado' }}</span></td>
                            <td width="35px">
                                <button class="btn btn-info btn-sm btnMiembros" data-id={{ $home->id }}><i
                                        class="fas fa-users"></i>Miembros</button>
                            </td>
                            <td width="10px">
                                <button class="btn btn-secondary btn-sm btnEditar" data-id={{ $home->id }}><i
                                        class="fas fa-edit"></i></button>
                            </td>

                            <td width="10px">
                                <form action={{ route('admin.home.destroy', $home->id) }} method='post'
                                    class="frmDelete">
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
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de hogares</h5>
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
                    url: "{{ route('admin.home.create') }}",
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
                    url: "{{ route('admin.home.edit', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {

                        $("#Modal .modal-body").html(response);
                        $("#Modal").modal('show');
                    }
                })
            })
            
            $('.btnMiembros').click(function() {

                var id = $(this).attr('data-id');
                $("#Modal .modal-body").html('');
                $.ajax({
                    url: "{{ route('admin.homemembers.create', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        $("#Modal .modal-body").html(response);
                    }
                })
                $.ajax({
                    url: "{{ route('admin.homemembers.show', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        $("#Modal .modal-body").html($("#Modal .modal-body").html() + response);
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

            var table = $('#home_table').DataTable({
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
