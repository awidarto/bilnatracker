<!DOCTYPE html>
<html class="no-js">

<head>
    <!-- Some assets concatenated and minified to improve load speed. Download version includes source css, javascript and less assets -->
    <!-- meta -->
    <meta charset="utf-8">
    <meta name="description" content="Flat, Clean, Responsive, admin template built with bootstrap 3">
    <meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1, maximum-scale=1">

    <title>{{ Config::get('site.name') }}</title>

    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ URL::to('cameo') }}/bootstrap/css/bootstrap.min.css">
    <!-- /bootstrap -->

    <!-- core styles -->
    <link rel="stylesheet" href="{{ URL::to('cameo') }}/min/main.min.css">
    <!-- /core styles -->

    <!-- page styles -->
    <link rel="stylesheet" href="{{ URL::to('cameo') }}/vendor/bootstrap-select/bootstrap-select.css">
    {{ HTML::style('datatables/css/dataTables.bootstrap.css')}}
    {{ HTML::style('css/bootstrap-modal-bs3patch.css') }}
    {{ HTML::style('css/bootstrap-modal.css') }}
    {{ HTML::style('css/flick/jquery-ui-1.9.2.custom.min.css') }}
    @include('layout.css')
    <!-- /page styles -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="{{ URL::to('cameo') }}/https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="{{ URL::to('cameo') }}/https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- load modernizer -->
    <script src="{{ URL::to('cameo') }}/vendor/modernizr.js"></script>

    {{ HTML::script('js/jquery-1.9.1.js')}}
    {{ HTML::script('js/jquery-ui-1.9.2.custom.min.js')}}

    {{ HTML::script('js/blueimp-gallery.min.js') }}
    {{ HTML::script('js/jquery.blueimp-gallery.min.js') }}

    <script type="text/javascript">
        var base = '{{ URL::to('/') }}/';
    </script>

</head>

<!-- body -->

<body>
    <div class="app">
        <!-- top header -->
        <header class="header header-fixed navbar bg-white">

            <div class="brand bg-success">
                <a href="{{ URL::to('cameo') }}/#" class="fa fa-bars off-left visible-xs" data-toggle="off-canvas" data-move="ltr"></a>

                <a href="{{ URL::to('/') }}" class="navbar-brand text-white">
                    <i class="fa fa-microphone mg-r-xs"></i>
                    <span>{{ Config::get('site.name')}} -
                        <b>admin</b>
                    </span>
                </a>
            </div>

            <form class="navbar-form navbar-left hidden-xs" role="search">
                <div class="form-group">
                    {{--
                    <button class="btn no-border no-margin bg-none no-pd-l" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <input type="text" class="form-control no-border no-padding search" placeholder="Search Workspace">
                    --}}
                </div>
            </form>

            @include('partials.identity')
        </header>
        <!-- /top header -->

        <section class="layout">
            <!-- sidebar menu -->
            <aside class="sidebar collapsible canvas-left bg-dark">
                <div class="scroll-menu">
                    <!-- main navigation -->
                    <nav class="main-navigation slimscroll" data-height="auto" data-size="4px" data-color="#ddd" data-distance="0">
					    @include('partials.topnav')
                    </nav>

                </div>

                <!-- footer -->
                <footer>
                    <div class="about-app pd-md animated pulse">
                        <a href="#">
                            <img src="{{ URL::to('cameo')}}/img/about.png" alt="">
                        </a>
                        <span>
                            <b>Cameo</b>&#32;is a responsive admin template powered by bootstrap 3.
                            <a href="#">
                                <b>Find out more</b>
                            </a>
                        </span>
                    </div>

                    <div class="footer-toolbar pull-left">
                        <a href="#" class="pull-left help">
                            <i class="fa fa-question-circle"></i>
                        </a>

                        <a href="#" class="toggle-sidebar pull-right hidden-xs">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </div>
                </footer>
                <!-- /footer -->
            </aside>
            <!-- /sidebar menu -->

            <!-- main content -->
            <section class="main-content">

                <!-- content wrapper -->
                <div class="content-wrap">
                    @yield('content')
                </div>
                <!-- /content wrapper -->

            </section>
            <!-- /main content -->

        </section>

    </div>

    <script src="{{ URL::to('cameo') }}/js/main.js"></script>

    <!-- page scripts -->
    <script src="{{ URL::to('cameo') }}/vendor/jquery.slimscroll.js"></script>

    @include('layout.js')
    <!-- /page scripts -->




</body>
<!-- /body -->

</html>
