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
        {!! Form::number('area', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el área', ]) !!}
    </div>
 
    <div class="form-group col-6">
        <label for="provincia">Provincia:</label>
        <select id="provincia" class="form-control" name="province_id">
            <option value="">Seleccione...</option>
            @foreach ($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-6">
        <label for="distrito">Distrito:</label>
        <select id="distrito" class="form-control" name="district_id">
            <option value="">Seleccione una provincia primero</option>
        </select>
    </div>

</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la descripción']) !!}
</div>

<script>
    try {
        const provinciaSelect = document.getElementById('provincia');
        const distritoSelect = document.getElementById('distrito');
        const districts = <?php echo json_encode($districts); ?>;
        provinciaSelect.addEventListener('change', () => {
            const provinciaId = provinciaSelect.value;
            let options = '';
            districts.filter(district => district.province_id == provinciaId).map(
                (district)=>{
                    options += `<option value="${district.id}">${district.name}</option>`
                }
            )
            distritoSelect.innerHTML = options;
        });
    } catch (error) {
    }
</script>
