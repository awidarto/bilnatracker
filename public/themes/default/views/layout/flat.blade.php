<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
    <title>{{ Config::get('site.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <!-- Bootplus -->
    {{ HTML::style('bootstrap232/css/bootstrap.css') }}

    {{ HTML::style('bootstrap232/css/bootstrap-responsive.css') }}

    {{ HTML::style('aflat/css/aflat.css') }}

    {{ HTML::style('aflat/css/aflat-responsive.css') }}

    {{ HTML::style('bootplus/css/font-awesome.min.css') }}

    {{ HTML::style('css/dataTables.bootstrap.css') }}

    {{ HTML::style('css/bootstrap-timepicker.css') }}

    {{ HTML::style('css/bootstrap-modal.css') }}

    {{ HTML::style('css/sm-datepicker/bootstrap-datetimepicker.min.css') }}

    {{ HTML::style('css/flick/jquery-ui-1.9.2.custom.min.css') }}

    {{ HTML::style('css/pickacolor/pick-a-color-1.1.8.min.css') }}

    {{ HTML::style('css/daterangepicker-bs2.css') }}

    {{ HTML::style('css/blueimp-gallery.min.css') }}

    {{ HTML::style('css/bootstrap-select.css')}}

    {{-- HTML::style('css/style.css') --}}


    <!-- Le styles -->

    <style type="text/css">
    body {
        padding-top: 60px;
        padding-bottom: 40px;
        background-color: #fff;
    }
    .hero-unit {
        padding: 60px;
    }

    .navbar .nav>li>a {
        font-size: 12px;
        padding: 16px 0 10px 0;
    }

    .navbar .nav>li>a.active{
        border-bottom: 2px solid rgb(66, 127, 237);
    }

    @media (max-width: 980px) {
    /* Enable use of floated navbar text */
        .navbar-text.pull-right {
            float: none;
            padding-left: 5px;
            padding-right: 5px;
        }
    }
    </style>

    {{ HTML::style('css/select2.css') }}
    {{ HTML::style('css/jquery.tagsinput.css') }}

    {{ HTML::style('css/jquery-fileupload/css/jquery.fileupload-ui.css') }}

    {{ HTML::style('css/app2.css') }}

    {{ HTML::style('css/form.css') }}

    {{ HTML::style('css/gridtable.css') }}

    {{ HTML::style('css/bootstrap-wysihtml5-0.0.2.css') }}

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-fa fa-precomposed" sizes="144x144" href="../assets/ico/apple-touch-fa fa-144-precomposed.png">
    <link rel="apple-touch-fa fa-precomposed" sizes="114x114" href="../assets/ico/apple-touch-fa fa-114-precomposed.png">
      <link rel="apple-touch-fa fa-precomposed" sizes="72x72" href="../assets/ico/apple-touch-fa fa-72-precomposed.png">
                    <link rel="apple-touch-fa fa-precomposed" href="../assets/ico/apple-touch-fa fa-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">


    {{ HTML::script('js/jquery-1.9.1.js')}}
    {{ HTML::script('js/jquery-ui-1.9.2.custom.min.js')}}


    {{-- HTML::script('js/jquery.ui.addresspicker.js') --}}

    <script type="text/javascript">
        var base = '{{ URL::to('/') }}';
    </script>


   </head>

   <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
          </button>
          <a class="brand" href="{{ URL::to('/') }}">{{ Config::get('site.name') }}</a>
          <div class="nav-collapse collapse">


                @if(Auth::check())
                    <p class="navbar-text pull-right">
                        Hello {{ Auth::user()->fullname }}&nbsp;&nbsp;
                        <a href="#" ><i class="fa fa-cog"></i>&nbsp;Settings</a>
                        <a href="{{ URL::to('logout')}}" ><i class="fa fa-logout"></i>&nbsp;Logout</a>
                    </p>
                @else
                    <form method="POST" action="{{ URL::to('login')}}" class="navbar-form pull-right">
                        <input name="email" class="col-md-2" type="text" placeholder="Email">
                        <input name="password" class="col-md-2" type="password" placeholder="Password">
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </form>
                @endif
            @include('partials.topnav')

          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="col-md-12">
            @yield('content')
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; {{ Config::get('site.name')}} {{ date('Y',time()) }}</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    {{ HTML::script('bootplus/js/bootstrap.min.js')}}
    {{ HTML::script('js/bootstrap-modalmanager.js') }}
    {{ HTML::script('js/bootstrap-modal.js') }}

    {{ HTML::script('js/jquery.removeWhitespace.min.js')}}
    {{ HTML::script('js/jquery.collagePlus.min.js')}}
    {{ HTML::script('js/jquery.collageCaption.js')}}
    {{ HTML::script('js/jquery-datatables/jquery.datatables.min.js')}}
    {{ HTML::script('js/jquery-datatables/datatables.bootstrap.js')}}

    {{ HTML::script('js/jquery.tagsinput.js') }}

    {{-- HTML::script('js/bootstrap-timepicker.js') --}}
    {{ HTML::script('js/sm-datepicker/bootstrap-datetimepicker.min.js') }}

    {{ HTML::script('js/moment.min.js') }}
    {{ HTML::script('js/daterangepicker.js') }}

    {{ HTML::script('js/app.js') }}

    {{ HTML::script('js/blueimp-gallery.min.js') }}
    {{ HTML::script('js/jquery.blueimp-gallery.min.js') }}

    {{ HTML::script('js/wysihtml5-0.3.0.min.js') }}

    {{ HTML::script('js/bootstrap-wysihtml5-0.0.2.min.js') }}

    {{ HTML::script('js/bootstrap-select/bootstrap-select.js') }}

    {{ HTML::script('js/jquery-fileupload/vendor/jquery.ui.widget.js') }}

    {{ HTML::script('js/js-load-image/load-image.min.js') }}

    {{ HTML::script('js/js-canvas-to-blob/canvas-to-blob.min.js') }}

    {{ HTML::script('js/jquery-fileupload/jquery.iframe-transport.js') }}

    {{ HTML::script('js/jquery-fileupload/jquery.fileupload.js') }}

    {{ HTML::script('js/tinycolor-0.9.15.min.js')}}
    {{ HTML::script('js/pickacolor/pick-a-color-1.1.8.min.js') }}

    {{ HTML::script('js/jquery-fileupload/jquery.fileupload-process.js') }}
    {{ HTML::script('js/jquery-fileupload/jquery.fileupload-image.js') }}
    {{ HTML::script('js/jquery-fileupload/jquery.fileupload-audio.js') }}
    {{ HTML::script('js/jquery-fileupload/jquery.fileupload-video.js') }}
    {{ HTML::script('js/jquery-fileupload/jquery.fileupload-validate.js') }}


   </body>
</html>
