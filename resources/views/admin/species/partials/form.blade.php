<div class="form-group">
    {!! Form::label('family_id', 'Familia') !!}
    {!! Form::select('family_id', $families, null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('name', 'Nombre común') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre común',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('scientific_name', 'Nombre científico') !!}
    {!! Form::text('scientific_name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre científico',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la descripción']) !!}
</div>
