<div class="form-row">
    {!! Form::hidden('zone_id', $zone->id, null) !!}
    <div class="form-group col-6">
        {!! Form::label('latitude', 'Latitud') !!}
        {!! Form::text('latitude', optional($lastcoords)->lat, [
            'class' => 'form-control',
            'placeholder' => 'Latitud',
            'readonly',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('longitude', 'Longitud') !!}
        {!! Form::text('longitude', optional($lastcoords)->lng, [
            'class' => 'form-control',
            'placeholder' => 'Longitud',
            'readonly',
            'required',
        ]) !!}
    </div>
</div>
<div id="map2" style="height: 400px;width: 100%;"></div>
<br>
<script>
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
            zoom: 18
        };

        var map = new google.maps.Map(document.getElementById('map2'), mapOptions);

        var marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: lng
            },
            map: map,
            title: 'Ubicación',
            draggable: true // Permite arrastrar el marcador
        });

        var perimeterCoords = @json($vertice);
            // Crea un objeto de polígono con los puntos del perímetro
            var perimeterPolygon = new google.maps.Polygon({
                paths: perimeterCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });

            perimeterPolygon.setMap(map);

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
