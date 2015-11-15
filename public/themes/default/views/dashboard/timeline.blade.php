@extends('layout.makestatic')

@section('content')


          <div class="row">
            <div class="col-md-4 col-sm-6 portlets">
              <div class="panel m-t-0">
                <div class="panel-header panel-controls">
                  <h3><i class="icon-basket"></i> <strong>Shipment</strong> Volume Stats</h3>
                </div>
                <div class="panel-content p-t-0 p-b-0">
                  <div class="bar-chart"></div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 portlets">
              <div class="panel m-t-0">
                <div class="panel-header panel-controls">
                  <h3><i class="icon-basket"></i> <strong>Shipment</strong> Volume Stats</h3>
                </div>
                <div class="panel-content p-t-0 p-b-0">
                  <div class="bar-chart"></div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 portlets">
              <div class="panel m-t-0">
                <div class="panel-header panel-controls">
                  <h3><i class="icon-basket"></i> <strong>Shipment</strong> Volume Stats</h3>
                </div>
                <div class="panel-content p-t-0 p-b-0">
                  <div class="bar-chart"></div>
                </div>
              </div>
            </div>
          </div>

@stop

@section('head')
    <link href="{{ URL::to('makeadmin') }}/assets/global/plugins/metrojs/metrojs.min.css" rel="stylesheet">
    <link href="{{ URL::to('makeadmin') }}/assets/global/plugins/maps-amcharts/ammap/ammap.min.css" rel="stylesheet">
@stop

@section('aux')

    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/noty/jquery.noty.packaged.min.js"></script>  <!-- Notifications -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script> <!-- Inline Edition X-editable -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/bootstrap-context-menu/bootstrap-contextmenu.min.js"></script> <!-- Context Menu -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/multidatepicker/multidatespicker.min.js"></script> <!-- Multi dates Picker -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/js/widgets/todo_list.js"></script>
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/metrojs/metrojs.min.js"></script> <!-- Flipping Panel -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/charts-chartjs/Chart.min.js"></script>  <!-- ChartJS Chart -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/charts-highstock/js/highstock.min.js"></script> <!-- financial Charts -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/charts-highstock/js/modules/exporting.min.js"></script> <!-- Financial Charts Export Tool -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/maps-amcharts/ammap/ammap.min.js"></script> <!-- Vector Map -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/maps-amcharts/ammap/maps/js/worldLow.min.js"></script> <!-- Vector World Map  -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/maps-amcharts/ammap/themes/black.min.js"></script> <!-- Vector Map Black Theme -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/skycons/skycons.min.js"></script> <!-- Animated Weather Icons -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/simple-weather/jquery.simpleWeather.js"></script> <!-- Weather Plugin -->
    <script src="{{ URL::to('makeadmin') }}/assets/global/js/widgets/widget_weather.js"></script>
    <script src="{{ URL::to('makeadmin') }}/assets/global/js/pages/dashboard.js"></script>


@stop