{!! Form::model($post, ['route' => ['admin.posts.update', $post], 'method' => 'put', 'files' => true]) !!}

@include('admin.posts.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i>&nbsp;&nbsp;Actualizar</button>

<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i>&nbsp;&nbsp;Cerrar</button>

{!! Form::close() !!}
