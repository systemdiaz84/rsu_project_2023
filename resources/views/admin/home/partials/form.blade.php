<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('code', 'Código') !!}
        {!! Form::text('code', isset($code) ? $code : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el código',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('is_public', 'Público') !!}
        {!! Form::select('is_public', ['1' => 'Sí', '0' => 'No'], null, ['class' => 'form-control', 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('direction', 'Dirección') !!}
    {!! Form::text('direction', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese la dirección',
        'required',
    ]) !!}
</div>
<div class="form-row">
    <div class="form-group col-5">
    {!! Form::label('zone_id', 'Zona') !!}
    {!! Form::select('zone_id', $zones, null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-7">
        {!! Form::label('n_doc', 'Número de documento') !!}
        {!! Form::text('n_doc', isset($n_doc) ? $n_doc : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el número de documento',
            'required',
        ]) !!}

    </div>
</div>