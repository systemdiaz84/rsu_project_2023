<div class="form-row">

    <div class="form-group col-6">
        {!! Form::label('name', 'Nombre de Zona') !!}
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el nombre',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('area', 'Área en m2') !!}
        {!! Form::number('area', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el área', 'required']) !!}
    </div>

</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la descripción']) !!}
</div>
