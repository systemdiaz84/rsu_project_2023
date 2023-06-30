<div class="form-group">
    {!! Form::label('family_id', 'Familia') !!}
    {!! Form::select('family_id', $families, null, ['class'=>'form-control','required']) !!}
</div>

<div class="form-group">
    {!! Form::label('name', 'Nombre de especie') !!}
    {!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Ingrese el nombre de la especie','required']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, ['class'=>'form-control','placeholder'=>'Ingrese la descripción']) !!}
</div>