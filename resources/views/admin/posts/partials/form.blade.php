<div class="form-group">
    {!! Form::label('title', 'Títuñp') !!}
    {!! Form::text('title', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el título de la publicación',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('is_active', 'Estado') !!}
    {!! Form::select('is_active', ['1' => 'Activo', '0' => 'Inactivo'], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la descripción']) !!}
</div>
<!-- cargar imagen -->
<div class="form-group">
    {!! Form::label('image', 'Imagen') !!}
    {!! Form::file('image', ['class' => 'form-control-file']) !!}
</div>
