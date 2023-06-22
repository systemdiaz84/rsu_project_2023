{!! Form::model($tree, ['route' => ['admin.trees.update', $tree], 'method' => 'put']) !!}

@include('admin.trees.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i>&nbsp;&nbsp;Actualizar</button>

<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i>&nbsp;&nbsp;Cerrar</button>

{!! Form::close() !!}