@extends('adminlte::page')

@section('title', 'Vista de Zona')

@section('content_header')

@stop

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h4>Vista de Zonas</h4>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-6">
                    <label for="">Zona</label>
                    <p>{{ $zone->name }}</p>

                    <label for="">Area en m2</label>
                    <p>{{ $zone->area }}</p>

                    <label for="">Descripción</label>
                    <p>{{ $zone->description }}</p>
                </div>
                <div class="col-6" style="background-color: blueviolet">
             
                </div>
            </div>
            <br>

            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-sm btn-success float-right" id="btnRegistrar" data-id={{ $zone->id }}>
                        <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Agregar Coordenada</button>

                    <h5>Coodenadas del perímetro</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="coords_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>LATITUD</th>
                                <th>LONGITUD</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coords as $coord)
                                <tr>
                                    <td>{{ $coord->id }}</td>
                                    <td>{{ $coord->latitude }}</td>
                                    <td>{{ $coord->longitude }}</td>

                                    <td width="10px">
                                        <form action={{ route('admin.zonecoords.destroy', $zone->id) }} method='post'
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


        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Coordenadas</h5>
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
           
                var id = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('admin.zonecoords.edit', ':id') }}".replace(':id', id),
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

            var table = $('#coords_table').DataTable({
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
                '{{ session('action') }}',
                'success'
            )
        </script>
    @endif
@endsection
