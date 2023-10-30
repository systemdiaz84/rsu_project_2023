{!! Form::open(['route' => 'admin.homemembers.store', 'id' => 'frmHomeMember']) !!}

<div class="form-row">
    @include('admin.homemembers.partials.form')

    <div class="form-group col-3 mt-auto">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>&nbsp;&nbsp;Agregar</button>
    </div>
</div>
<!-- <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i>&nbsp;&nbsp;Cerrar</button> -->

{!! Form::close() !!}

<!-- generar script para enviar la solicitud al servidor, pero si obtiene success, la tabla #home_members_table 
    debe actualizarse automáticamente -->
<!-- @section('js') -->
    
<!-- @stop -->