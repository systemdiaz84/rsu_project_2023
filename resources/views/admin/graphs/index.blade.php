<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gráficos</title>
</head>
<body>

    <div class="row">
        <div class="form-group col-12" id="families_species"></div>
    </div>
    <div class="row">
        @if ($families->count())
            {{-- <div class="col-lg-4 mb-lg-0 mb-4 col-12" id="families_graph"></div> --}}
            <div class="col-6 form-group" id="species"></div>
            <div class="col-6 form-group" id="zones"></div>
        @else
            <div class="col">
                <p>-Aún no hay árboles registrados-</p>
            </div>
        @endif
    </div>
    @section('js')
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    
        <script>
            Highcharts.chart('families_species', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Arboles sembrados por familias y especie'
                },
                subtitle: {
                    text: 'Click en las familias para ver especies'
                },
    
                accessibility: {
                    announceNewData: {
                        enabled: true
                    },
                    point: {
                        valueSuffix: '%'
                    }
                },
    
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                        }
                    }
                },
    
                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                },
    
                series: [{
                    name: "Familias",
                    colorByPoint: true,
                    data: @json($families)
                }],
                drilldown: {
                    series: @json($species_families)
                }
            });
    
    
            function create_pie_chart(element, data, title) {
                Highcharts.chart(element, {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: title
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Árboles',
                        colorByPoint: true,
                        data: data
                    }]
                });
            }
    
            create_pie_chart(document.getElementById('species'), @json($species), 'Árboles por Especie')
            create_pie_chart(document.getElementById('zones'), @json($zones), 'Árboles por Zona')
        </script>
    @stop
    
    @section('css')
        <style>
            .highcharts-credits {
                display: none;
            }
        </style>
    @endsection
    
</body>
</html>

