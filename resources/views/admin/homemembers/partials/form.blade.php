<div class="form-row">
    {!! Form::hidden('home_id', $home->id, null) !!}
    <div class="form-group col-7">
        {!! Form::label('n_doc', 'N° Documento') !!}
        {!! Form::text('n_doc', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el N° de Documento']) !!}
    </div>
    <div class="form-group col-5">
        {!! Form::label('is_boss', 'Jefe de Hogar') !!}
        {!! Form::select('is_boss', ['1' => 'Si', '0' => 'No'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione']) !!}
    </div>
</div>
