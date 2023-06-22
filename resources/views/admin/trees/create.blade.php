{!! Form::open(['route' => 'admin.trees.store']) !!}

@include('admin.trees.partials.form')

<button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp;&nbsp;Registrar</button>

<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="bi bi-x-circle-fill"></i>&nbsp;&nbsp;Cerrar</button>

{!! Form::close() !!}
