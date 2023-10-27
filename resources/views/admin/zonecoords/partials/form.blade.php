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
    <button id="button" type="button" class="btn btn-success mr-2 my-2" ><i class="fas fa-plus"></i>&nbsp;&nbsp;Añadir</button>
    <button id="delete" type="button" class="btn my-2" style="background-color: green;"><i class="fas fa-trash"></i>&nbsp;&nbsp;Eliminar</button>
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

        var markers = [];
        var eliminar = false;
        var ids = 0;

        var map = new google.maps.Map(document.getElementById('map2'), mapOptions);


        // SI SE REQUIERE UN MARCADOR AL INICIAR EL MAPA, ENTONCES SE DEBE ABSTRAER
        // LA FUNCIÓN QUE OYE EL EVENTO
        // var marker = new google.maps.Marker({
        //     position: {
        //         lat: lat,
        //         lng: lng
        //     },
        //     map: map,
        //     title: 'Ubicación',
        //     draggable: true // Permite arrastrar el marcador
        // });
        // marker.metadata = { type: "point", id: ids + 1 };
        // markers.push({ id: ids + 1, lat: lat, lng: lng });
        // ids += 1;

        // Eliminar un marker:
        // 1. dar click en el botón
        // 2. dar click sobre marker a eliminar
        // OJO: si se quiere eliminar otro marker se deben repetir los pasos
        $("#delete").click(function () {
            eliminar = !eliminar;
            $(this).css('background-color', eliminar ? 'red' : 'green');
        })

        var perimeterCoords = @json($vertice);
        console.log(perimeterCoords) // inicializar 

        // Crea un objeto de polígono con los puntos del perímetro
        var perimeterPolygon = new google.maps.Polygon({
            paths: perimeterCoords,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });


        $("#button").click(function () {
            var marker =  new google.maps.Marker({
                position: { lat: lat, lng: lng },
                draggable: true
            });
            marker.metadata = { type: "point", id: ids + 1 };
            markers.push({ id: ids + 1, lat: lat, lng: lng });
            ids += 1;

            const infowindow = new google.maps.InfoWindow({
                content: "Latitud: " + marker.getPosition().lat() + 
                "<br>Longitud: " + marker.getPosition().lng()
            });
            infowindow.open(map, marker)

            google.maps.event.addListener(marker, 'click', function (event) {
                if (eliminar) {

                    // eliminar marcador
                    markers = markers.filter(i => i.id != marker.metadata.id);
                    marker.setMap(null);

                    // eliminar marcador del poligono
                    perimeterPolygon.setMap(null);
                    perimeterPolygon.setPath(markers.map(mk => ({ lat: mk.lat, lng: mk.lng })));
                    perimeterPolygon.setMap(map);

                    eliminar = false;//SI SE REQUIERE SEGUIR ELIMINANDO, ENTONCES REMOVER
                    $("#delete").css('background-color', 'green');
                }else{
                    infowindow.close();
                    infowindow.setContent("Latitud: " + marker.getPosition().lat() + 
                        "<br>Longitud: " + marker.getPosition().lng());
                    infowindow.open(map, marker);
                }
            })

            google.maps.event.addListener(marker, 'drag', function (event) {
                var latLng = event.latLng;

                markers = markers.map( mk => ({ 
                    id: mk.id, 
                    lat: marker.metadata.id == mk.id ? latLng.lat() : mk.lat, 
                    lng: marker.metadata.id == mk.id ? latLng.lng() : mk.lng 
                }))

                perimeterPolygon.setMap(null);
                perimeterPolygon.setPath(markers.map(mk => ({ lat: mk.lat, lng: mk.lng })));
                perimeterPolygon.setMap(map);

                // console.log(markers)
                infowindow.setContent("Latitud: " + marker.getPosition().lat() + 
                    "<br>Longitud: " + marker.getPosition().lng());
                console.log(markers.map(mk => ({ lat: mk.lat, lng: mk.lng })))
                
            });
            marker.setMap(map);
            console.log(coordenadas)



        })
        // perimeterPolygon.setMap(map);

        // // Actualizar las coordenadas al mover el marcador
        // google.maps.event.addListener(marker, 'dragend', function(event) {
        //     var latLng = event.latLng;
        //     latInput.value = latLng.lat();
        //     lonInput.value = latLng.lng();
        // });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer>
</script>
