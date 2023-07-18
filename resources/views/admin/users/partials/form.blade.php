
<div class="form-group">
    {!! Form::label('name', 'Nombres') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese los nombres',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('name', 'Apellidos') !!}
    {!! Form::text('lastname', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese los apellidos',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('name', 'email') !!}
    {!! Form::text('email', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el email',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('name', 'password') !!}
    {!! Form::password('password',  [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el password',
        'required',
    ]) !!}
</div>