<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>{{ Config::get('site.name') }}</title>

    <!-- Bootstrap core CSS -->
    {{ HTML::style('bootflat-admin/css/site.min.css')}}

    {{ HTML::style('css/typography.css')}}

    @include('layout.css')

    {{ HTML::style('bootflat-admin/css/style.css')}}

    {{-- HTML::style('datatables/css/jquery.dataTables.min.css') --}}

    <!-- Custom styles for this template -->
    {{ HTML::style('css/navbar-fixed-top.css')}}

    {{ HTML::style('js/jquery-ui-1.11.2.custom/jquery-ui.min.css')}}
    {{ HTML::style('js/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css')}}

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{ HTML::script('bootflat/js/site.min.js')}}
    {{-- HTML::script('js/jquery-ui-1.9.2.custom.min.js') --}}
    {{ HTML::script('js/jquery-ui-1.11.2.custom/jquery-ui.min.js')}}


    <script type="text/javascript">
      var base = '{{ URL::to('/') }}/';
    </script>


  </head>

  <body>

    @include('partials.fixedtopnav')

    <div class="container">
      <div class="container">
          {{ Breadcrumbs::render() }}
      </div>
      <div class="">
        <div class="container">
          <h4>{{ $title }}</h4>
        </div>
        <div class="row">
            <div class="col-md-6">
              @yield('left')
            </div>
            <div class="col-md-6">
              @yield('right')
            </div>
        </div>
      </div>
      <!-- Main component for a primary marketing message or call to action -->
    </div> <!-- /container -->

    @yield('modals')

    @yield('aux')

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    @include('layout.js')
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {{--
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    --}}
  </body>
</html>
