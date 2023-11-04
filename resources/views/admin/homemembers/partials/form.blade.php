{!! Form::hidden('home_id', $home->id, null) !!}
<div class="form-group col-5">
    {!! Form::label('n_doc', 'N° Documento') !!}
    {!! Form::text('n_doc', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el N° de Documento', 'readonly' => true]) !!}
</div>
<div class="form-group col-4">
    {!! Form::label('is_boss', 'Jefe de Hogar') !!}
    {!! Form::select('is_boss', ['1' => 'Si', '0' => 'No'], '0', ['class' => 'form-control', ]) !!}
</div>

<script>
    $('#frmHomeMember').submit(function(e) {
        e.preventDefault();
        var id = $('#frmHomeMember').find('input[name="home_id"]').val();
        $.ajax({
            url: "{{ route('admin.homemembers.store') }}",
            type: 'POST',
            data: $('#frmHomeMember').serialize(),
            success: function(response, status, jqXHR) {
                if (jqXHR.status == 200) {
                    $("#Modal .modal-body").html('');
                    $.ajax({
                        url: "{{ route('admin.homemembers.create', ':id') }}".replace(':id', id),
                        type: 'GET',
                        success: function(responsee) {
                            $("#Modal .modal-body").html(responsee+response);
                        }
                    })
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Miembro agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if(jqXHR.status == 409){
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'El usuario ya existe en el hogar',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }else{
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
</script>