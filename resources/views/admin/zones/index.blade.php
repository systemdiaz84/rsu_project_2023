@extends('adminlte::page')

@section('title', 'Zonas')

@section('content_header')

@stop

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">

            <button type="button" class="btn btn-success float-right" id="btnRegistrar">
                <i class="fas fa-plus-circle"></i> </button>

            <h4>Listado de Zonas</h4>


        </div>
        <div class="card-body">
            <table class="table table-striped" id="zone_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>ÁREA</th>
                        <th>DESCRIPCIÓN</th>
                        <th>EDITAR</th>
                        <th>ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($zones as $zone)
                        <tr>
                            <td>{{ $zone->id }}</td>
                            <td>{{ $zone->name }}</td>
                            <td>{{ $zone->area }}</td>
                            <td>{{ $zone->description }}</td>
                            <td width="10px" align="center">
                                <button class="btn btn-secondary btnEditar" data-id={{ $zone->id }}><i
                                        class="fas fa-edit"></i></button>
                            <td width="10px" align="center">
                                <form action={{ route('admin.zones.destroy', $zone->id) }} method='post' class="frmDelete">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger "><i class="fas fa-trash"></i></button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Zonas</h5>
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
                    url: "{{ route('admin.zones.create') }}",
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
                    url: "{{ route('admin.zones.edit', ':id') }}".replace(':id', id),
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

            var table = $('#zone_table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
                },
            });

        });
    </script>

@if (null !== session('action'))
<script>
    Swal.fire(
        'Proceso Exitoso',
        '{{ session("action") }}',
        'success'
    )
</script>
@endif
@endsection
