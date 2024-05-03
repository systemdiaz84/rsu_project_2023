<div class="form-row">
    {!! Form::hidden('zone_id', $zone->id, null) !!}
    <div class="form-group col-12">
        {!! Form::label('area', 'Área m2') !!}
        {!! Form::text('area', null, [
            'class' => 'form-control',
            'placeholder' => 'Área',
            'readonly',
            'required',
        ]) !!}
    </div>
    <input type="hidden" id="coords" name="coordenadas" value="">
    <button id="button" type="button" class="btn btn-success mr-2 my-2" ><i class="fas fa-plus"></i>&nbsp;&nbsp;Añadir</button>
    <button id="delete" type="button" class="btn my-2 text-white" style="background-color: green;"><i class="fas fa-trash"></i>&nbsp;&nbsp;Eliminar</button>
</div>
<div id="map2" style="height: 400px;width: 100%;"></div>
<br>
<script>
    var eliminar = false;
    var markers = [];
    var ids = 0;

    function initMap() {

        var lat = -12.049710110586066;
        var lng = -77.03763001490877;

        if (true) {
            // Obtener ubicación actual si los campos están vacíos o no contienen valores numéricos válidos
            navigator.geolocation.getCurrentPosition(function(position) {
                lat = position.coords.latitude;
                lng = position.coords.longitude;
                displayMap(lat, lng);
            });
        } else {
            // Utilizar las coordenadas de los campos de entrada
            displayMap(lat, lng);
        }
    }
    function viewArea(polygon){
        var path = polygon.getPath();
        if (path && path.getLength() > 0) {
            $('input[name="area"]').val(google.maps.geometry.spherical.computeArea(path));
        } else {
            $('input[name="area"]').val(0);
        }
    }
    function eventClick(marker,perimeterPolygon,infowindow,map){
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
                    viewArea(perimeterPolygon);
                }else{
                    infowindow.close();
                    infowindow.setContent("Latitud: " + marker.getPosition().lat() + 
                        "<br>Longitud: " + marker.getPosition().lng());
                    infowindow.open(map, marker);
                }
            })
    }

    function eventDrag(marker,perimeterPolygon,infowindow,map){
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
            // console.log(markers.map(mk => ({ lat: mk.lat, lng: mk.lng })))
            viewArea(perimeterPolygon);
        });
    }

    function newMarker(lat,lng,perimeterPolygon,map){
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
        marker.setMap(map);

        eventClick(marker,perimeterPolygon,infowindow,map);
        eventDrag(marker,perimeterPolygon,infowindow,map);

        // return marker
    }

    function displayMap(lat, lng) {
        var mapOptions = {
            center: {
                lat: lat,
                lng: lng
            },
            zoom: 17
        };

        var map = new google.maps.Map(document.getElementById('map2'), mapOptions);


        // SI SE REQUIERE UN MARCADOR AL INICIAR EL MAPA
        // LLAMAR FUNCIÓN addMarker()

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

        perimeterCoords.map((data)=>{
            newMarker(data.lat,data.lng,perimeterPolygon,map);
        })

        perimeterPolygon.setMap(map);
        viewArea(perimeterPolygon);


        $("#button").click(function () {
            newMarker(lat,lng,perimeterPolygon,map);
        })

    }
    // console.log(@zone_id)
    $('form').submit(function(event) {
        event.preventDefault();
        const zoneId = $('input[name="zone_id"]').val();
        const token = $('input[name="_token"]').val();
        const area = $('input[name="area"]').val();

        $.ajax({
            url: "{{ route('admin.zonecoords.store') }}",
            type: 'POST',
            data: {
                "zone_id": zoneId,
                "_token": token,
                "area": area,
                "coords": JSON.stringify(markers.map(mk => ({ lat: mk.lat, lng: mk.lng })))
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },success: function(response) {
                $("#Modal .modal-body").html(response);
                $("#Modal").modal('show');
                window.location.replace(`{{ route('admin.zones.show', ':id') }}`.replace(':id', zoneId));
            }
        });
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&libraries=geometry" async defer>
</script>
