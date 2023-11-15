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
        const districts = <?php echo json_encode($districts); ?>;
        const provinciaSelect = document.getElementById('provincia');
        const distritoSelect = document.getElementById('distrito');
        <?php if(isset($zone)): ?>
            let district_id = <?php echo json_encode($zone->district_id); ?>;
            let province_id = <?php echo json_encode($zone->province_id); ?>;
            provinciaSelect.value = province_id;
            listDiscrits(province_id,districts,distritoSelect)
            distritoSelect.value = district_id;
        <?php endif; ?>
        
        provinciaSelect.addEventListener('change', () => {
            const provinciaId = provinciaSelect.value;
            listDiscrits(provinciaId,districts,distritoSelect)
        });
    } catch (error) {
    }
    function listDiscrits(province_id, d, ds){
        let options = '';
        d.filter(district => district.province_id == province_id).map(
            (district)=>{
                options += `<option value="${district.id}">${district.name}</option>`
            }
        )
        ds.innerHTML = options;
    }
</script>
