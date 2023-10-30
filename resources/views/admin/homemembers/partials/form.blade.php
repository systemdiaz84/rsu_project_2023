{!! Form::hidden('home_id', $home->id, null) !!}
<div class="form-group col-6">
    {!! Form::label('n_doc', 'N° Documento') !!}
    {!! Form::text('n_doc', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el N° de Documento']) !!}
</div>
<div class="form-group col-3">
    {!! Form::label('is_boss', 'Jefe de Hogar') !!}
    {!! Form::select('is_boss', ['1' => 'Si', '0' => 'No'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione']) !!}
</div>

<script>
        // $(document).ready(function() {
            $('#frmHomeMember').submit(function(e) {
                e.preventDefault();
                var id = $('#frmHomeMember').find('input[name="home_id"]').val();
                $.ajax({
                    url: "{{ route('admin.homemembers.store') }}",
                    type: 'POST',
                    data: $('#frmHomeMember').serialize(),
                    success: function(response) {
                        if (response.status == 200) {

                            $("#Modal .modal-body").html('');
                            $.ajax({
                                url: "{{ route('admin.homemembers.create', ':id') }}".replace(':id', id),
                                type: 'GET',
                                success: function(responsee) {
                                    $("#Modal .modal-body").html(responsee+response);
                                }
                            })
                            console.log(response);
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Miembro agregado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        else if (response.status == 302) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'El usuario ya existe en el hogar',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Error al agregar miembro',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                })
            })
        // })

    </script>