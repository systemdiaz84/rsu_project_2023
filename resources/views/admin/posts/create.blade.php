{!! Form::open(['route' => 'admin.posts.store', 'files' => true]) !!}

@include('admin.posts.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i>&nbsp;&nbsp;Registrar</button>

<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i>&nbsp;&nbsp;Cerrar</button>

{!! Form::close() !!}
