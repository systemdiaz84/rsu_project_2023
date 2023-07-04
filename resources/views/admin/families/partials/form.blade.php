<div class="form-group">
    {!! Form::label('name', 'Nombre Común') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre común de la familia',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('scientific_name', 'Nombre Científico') !!}
    {!! Form::text('scientific_name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre científico de la familia',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la descripción']) !!}
</div>