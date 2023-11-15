@extends('adminlte::page')

@section('title', 'Listado de árboles')

@section('content_header')

@stop

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">

            <button type="button" class="btn btn-success float-right" id="btnRegistrar">
                <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Registrar</button>

            <h4>Listado de árboles</h4>


        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped" id="tree_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>FAMILIA</th>
                        <th>ESPECIE</th>
                        <th>ZONA</th>
                        <th>HOME</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($trees as $tree)
                        <tr>
                            <td>{{ $tree->id }}</td>
                            <td>{{ $tree->name }}</td>
                            <td>{{ $tree->familyname }}</td>
                            <td>{{ $tree->speciename }}</td>
                            <td>{{ $tree->zonename }}</td>
                            <td>{{ $tree->homename }}</td>
                            <td width="10px">
                                <button class="btn btn-secondary btn-sm btnEditar" data-id={{ $tree->id }}><i
                                        class="fas fa-edit"></i></button>
                            </td>
                            <td width="10px">
                                <a href={{ route('admin.trees.show', $tree->id) }} class="btn btn-primary btn-sm"><i
                                        class="fas fa-eye"></i></a>
                            </td>
                            <td width="10px">
                                <form action={{ route('admin.trees.destroy', $tree->id) }} method='post' class="frmDelete">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" disabled><i
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de árboles</h5>
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
                    url: "{{ route('admin.trees.create') }}",
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
                    url: "{{ route('admin.trees.edit', ':id') }}".replace(':id', id),
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
                    text: "Se eliminarán las imágenes, evolución y procedimientos del árbol",
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

            var table = $('#tree_table').DataTable({
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
