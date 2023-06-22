{!! Form::model($tree, ['route' => ['admin.trees.update', $tree], 'method' => 'put']) !!}

@include('admin.trees.partials.form')
<button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i>&nbsp;&nbsp;Actualizar</button>

<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="bi bi-x-circle-fill"></i>&nbsp;&nbsp;Cerrar</button>

{!! Form::close() !!}
