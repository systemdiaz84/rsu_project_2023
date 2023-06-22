<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('name', 'Nombre de árbol') !!}
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => 'Nombre de árbol',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('zone_id', 'Zona') !!}
        {!! Form::select('zone_id', $zones, null, ['class' => 'form-control', 'required']) !!}

    </div>

</div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('family_id', 'Familia') !!}
        {!! Form::select('family_id', $families, null, ['class' => 'form-control', 'required', 'id' => 'family_id']) !!}

    </div>

    <div class="form-group col-6">
        {!! Form::label('specie_id', 'Especie') !!}
        {!! Form::select('specie_id', $species, null, ['class' => 'form-control', 'required', 'id' => 'specie_id']) !!}


        </select>
    </div>

</div>
<div class="form-row">

    <div class="form-group col-6">
        {!! Form::label('birth_date', 'Nacimiento') !!}
        {!! Form::date('birth_date', null, [
            'class' => 'form-control',
        
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('planting_date', 'Fecha de Plantado') !!}
        {!! Form::date('planting_date', null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
</div>
<div class="form-row">

    <div class="form-group col-6">
        {!! Form::label('latitude', 'Latitud') !!}
        {!! Form::text('latitude', null, [
            'class' => 'form-control',
            'placeholder' => 'Latitud',
            'readonly',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('longitude', 'Longitud') !!}
        {!! Form::text('longitude', null, [
            'class' => 'form-control',
            'placeholder' => 'Longitud',
            'readonly',
            'required',
        ]) !!}
    </div>
</div>
<div id="map" style="height: 200px;width: 100%;"></div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'rows' => '4',
        'placeholder' => 'Ingrese la descripción',
    ]) !!}
</div>

<script>
    $('#family_id').change(function() {
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.species_family', ':id') }}".replace(':id', id),
            type: 'GET',
            dataType: 'json',
            contentType: 'application/json;chartset=utf-8',
            success: function(response) {

                $('#specie_id').empty();
                $.each(response, function(key, value) {
                    $('#specie_id').append('<option value="' + value.id + '">' + value
                        .name + '</option>');
                });


            }
        })
    })

    var latInput = document.getElementById('latitude');
    var lonInput = document.getElementById('longitude');

    function initMap() {

        var lat = parseFloat(latInput.value);
        var lng = parseFloat(lonInput.value);

        if (isNaN(lat) || isNaN(lng)) {
            // Obtener ubicación actual si los campos están vacíos o no contienen valores numéricos válidos
            navigator.geolocation.getCurrentPosition(function(position) {
                lat = position.coords.latitude;
                lng = position.coords.longitude;
                latInput.value = lat;
                lonInput.value = lng;
                displayMap(lat, lng);
            });
        } else {
            // Utilizar las coordenadas de los campos de entrada
            displayMap(lat, lng);
        }
    }

    function displayMap(lat, lng) {
        var mapOptions = {
            center: {
                lat: lat,
                lng: lng
            },
            zoom: 12
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: lng
            },
            map: map,
            title: 'Ubicación',
            draggable: true // Permite arrastrar el marcador
        });

        // Actualizar las coordenadas al mover el marcador
        google.maps.event.addListener(marker, 'dragend', function(event) {
            var latLng = event.latLng;
            latInput.value = latLng.lat();
            lonInput.value = latLng.lng();
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer>
</script>
