{!! Form::open(['route' => 'admin.homemembers.store']) !!}

@include('admin.homemembers.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i>&nbsp;&nbsp;Agregar</button>

<!-- <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i>&nbsp;&nbsp;Cerrar</button> -->

{!! Form::close() !!}