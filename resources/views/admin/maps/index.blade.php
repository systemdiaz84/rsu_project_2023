@extends('adminlte::page')

@section('title', 'Visualización en mapa')

@section('content_header')

@stop

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h4>Visualización en mapa</h4>
        </div>
        <div class="card-body">
            <div id=map style="height:550px;width: 100%;"></div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer>
    </script>

    <script>
        var coordenadas = @json($trees); // Obtener el array de coordenadas desde PHP
        var perimeters = @json($perimeter);

        var currentInfoWindow = null;

        function initMap() {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                var mapOptions = {
                    center: {
                        lat: lat,
                        lng: lng
                    },
                    zoom: 15
                };

                var map = new google.maps.Map(document.getElementById('map'), mapOptions);



                coordenadas.forEach(function(coordenada) {
                    var marker = new google.maps.Marker({
                        position: {
                            lat: coordenada.latitude,
                            lng: coordenada.longitude
                        },
                        map: map,
                        title: 'Mi ubicación'
                    });

                    marker.addListener('click', function() {

                        if (currentInfoWindow) {
                            currentInfoWindow.close(); // Cerrar el InfoWindow anterior si existe
                        }

                        var infowindow = new google.maps.InfoWindow({
                            content: coordenada.name
                        });
                        infowindow.open(map, marker);

                        currentInfoWindow = infowindow;
                    });

                });

                var colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'];


                perimeters.forEach(function(perimeter,index) {
                    var perimeterCoords = perimeter.coords;
                    var color = colors[index % colors.length]; // Obtiene un color de la matriz de colores

                    // Crea un objeto de polígono con los puntos del perímetro
                    var perimeterPolygon = new google.maps.Polygon({
                        paths: perimeterCoords,
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color,
                        fillOpacity: 0.35,
                        map: map // Asigna el mapa al polígono para mostrarlo
                    });
                });
            });
        }
    </script>
@endsection
