{!! Form::open(['route' => 'admin.homemembers.store', 'id' => 'frmHomeMember']) !!}

<div class="form-row">
    @include('admin.homemembers.partials.form')

    <div class="form-group col-3 mt-auto">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>&nbsp;&nbsp;Agregar</button>
    </div>
</div>
<!-- <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i>&nbsp;&nbsp;Cerrar</button> -->

{!! Form::close() !!}
<div class="form-group col-12">
    <label for="user_search">Buscar usuario:</label>
    <input type="text" class="form-control" id="user_search" name="user_search" minlength="3" placeholder="Ingrese el nombre o apellido o n documento">
</div>
<div class="form-group col-12" id="no_results" hidden>
    <div class="alert alert-danger" role="alert">
        No se encontraron resultados
    </div>
</div>
<table class="table table-striped" hidden id="user_table_search">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>N° Documento</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="user_list">
    </tbody>
</table>
<script>
    $(document).ready(function() {
        console.log('ready');
        $('#user_search').on('keyup', function() {
            var query = $(this).val();
            console.log(query);
            if (query.length >= 3) {
                $.ajax({
                    url: "{{ route('admin.users.search', ':query') }}".replace(':query', query),
                    method: 'GET',
                    success: function(data) {
                        if(data.length > 0) {
                            $('#user_table_search').removeAttr('hidden');
                            $('#no_results').attr('hidden', true);
                            $('#user_list').html('');
                            data.forEach(function(user) {
                                $('#user_list').append('<tr class="user-item" data-name="' + user.name + ' ' + user.lastname + '" data-n_doc="'+user.n_doc+'"><td>' + user.name + ' ' + user.lastname + '</td><td>' + user.n_doc + '</td><td><button type="button" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i></button></td></tr>');
                            });
                        } else {
                            $('#user_table_search').attr('hidden', true);
                            $('#no_results').removeAttr('hidden');
                        }
                    }
                });
            }
        });

        $(document).on('click', '.user-item', function() {
            var user_name = $(this).data('name');
            var user_n_doc = $(this).data('n_doc');

            $('#n_doc').val(user_n_doc);
            $('#user_list').html('');
            $('#user_table_search').attr('hidden', true);

        });
    });
</script>
