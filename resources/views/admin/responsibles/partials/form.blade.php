<div class="form-group">
    {!! Form::label('name', 'Nombres') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre del responsable',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('lastname', 'Apellidos') !!}
    {!! Form::text('lastname', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese los apellidos del responsable',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('dni', 'DNI') !!}
    {!! Form::text('dni', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el DNI del responsable',
        'required',
    ]) !!}
</div>