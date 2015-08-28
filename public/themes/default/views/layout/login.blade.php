<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ Config::get('site.name') }}</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="favicon_16.ico"/>
    <link rel="bookmark" href="favicon_16.ico"/>
    <!-- site css -->

    {{ HTML::style('bootflat-admin/css/site.min.css')}}

    {{ HTML::style('css/typography.css')}}

    @include('layout.css')

    {{ HTML::style('bootflat-admin/css/style.css')}}

    <!-- <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'> -->
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    {{ HTML::script('bootflat-admin/js/site.min.js') }}
    <style>
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #388E3C;
        color: #C1C3C6
      }

      .h2, h2{
        font-size: 24px;
        text-align: center;
      }

      .form-signin{
        max-width: 330px;
      }

      .form-signin input {
        background-color: #FFF;
        color: #000;
        border-color: #FF9800;
      }

      .btn-primary, .btn-primary.active, .btn-primary.disabled, .btn-primary:active, .btn-primary[disabled] {
        border: thin solid #fff;
      }

      .help-block{
        color: #fff;
      }


    </style>
  </head>
  <body>
    <div class="container">
      @yield('content')
    </div>
    <div class="clearfix"></div>
    <!--footer-->
  </body>
</html>
