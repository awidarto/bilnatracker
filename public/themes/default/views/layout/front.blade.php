<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7 footer-sticky navbar-sticky"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8 footer-sticky navbar-sticky"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9 footer-sticky navbar-sticky"> <![endif]-->
<!--[if gt IE 8]> <html class="ie footer-sticky navbar-sticky"> <![endif]-->
<!--[if !IE]><!--><html class="footer-sticky navbar-sticky"><!-- <![endif]-->
<head>
    <title>{{ Config::get('site.name') }}</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

    <!--
    **********************************************************
    In development, use the LESS files and the less.js compiler
    instead of the minified CSS loaded by default.
    **********************************************************
    {{--
    <link rel="stylesheet" href="{{ URL::to('coral') }}/assets/css/admin/module.admin.stylesheet-complete.layout_fixed.true.min.css" />
    --}}
    -->
    <link rel="stylesheet/less" href="{{ URL::to('coral') }}/assets/less/admin/module.admin.stylesheet-complete.layout_fixed.true.less?{{ time() }}" />

    <link rel="stylesheet" href="{{  URL::to('/') }}/css/typography.css">

    {{ HTML::style('css/jquery.tagsinput.css') }}

        <!--[if lt IE 9]><link rel="stylesheet" href="{{ URL::to('coral') }}/assets/components/library/bootstrap/css/bootstrap.min.css" /><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="{{ URL::to('coral')}}/assets/components/library/jquery/jquery.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/library/jquery/jquery-migrate.min.js?v=v1.9.6&sv=v0.0.1"></script>

        {{ HTML::script('js/jquery-ui-1.9.2.custom.min.js')}}

    <script src="{{ URL::to('coral')}}/assets/components/library/modernizr/modernizr.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/plugins/less-js/less.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/modules/admin/charts/flot/assets/lib/excanvas.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/plugins/browser/ie/ie.prototype.polyfill.js?v=v1.9.6&sv=v0.0.1"></script>
    <script>if (/*@cc_on!@*/false && document.documentMode === 10) { document.documentElement.className+=' ie ie10'; }</script>


    <script type="text/javascript">
        var base = '{{ URL::to('/') }}/';
    </script>

    <style type="text/css">
        /* centered columns styles */
        .row-centered {
            text-align:center;
        }
        .col-centered {
            display:inline-block;
            float:none;
            /* reset the text-align */
            text-align:left;
            /* inline-block space fix */
            margin-right:-4px;
        }
        .music-link{
            cursor: pointer;
        }
    </style>

</head>
<body class=" menu-right-hidden">

    <!-- Main Container Fluid -->
    <div class="container-fluid menu-hidden">
        <!-- Content -->
        <div id="content">
            <div class="navbar hidden-print main navbar-default" role="navigation">
                {{--<div class="container">--}}
                    <div class="navbar-header" style="margin-left:16px;">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <a id="logo" href="{{ URL::to('/')}}" class="animated fadeInDown pull-left"><b class="fa fa-3x fa-headphones"></b></a>
                    </div>

                    <div class="navbar-collapse collapse">

                        @include('partials.topnav')

                        <div class="user-action pull-right ">
                            <a class="btn btn-navbar-right btn-primary btn-stroke" href="{{ URL::to('logout')}}" class="dropdown-toggle"><i class="fa fa-sign-out"></i></a>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                {{--</div>--}}
            </div>
            <!-- // END navbar -->
            <div class="container">
                {{ Breadcrumbs::render() }}
            </div>

            <div class="layout-app">
                <div class="container-fluid">
                    <div class="row margin-none">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <!-- // Sidebar menu & content wrapper END -->
        <!-- Footer -->
        <div id="footer" class="hidden-print">
            <div class="copy">&copy; 2012 - 2014 - {{ Config::get('site.name') }} - All Rights Reserved.</div>
        </div>
        <!-- // Footer END -->
    </div>
    <!-- // Main Container Fluid END -->

    <!-- Global -->
    <script data-id="App.Config">
    var basePath = '',
        commonPath = '../assets/',
        rootPath = '../',
        DEV = false,
        componentsPath = '../assets/components/',
        layoutApp = false,
        module = 'admin';

    var primaryColor = '#eb6a5a',
        dangerColor = '#b55151',
        successColor = '#609450',
        infoColor = '#4a8bc2',
        warningColor = '#ab7a4b',
        inverseColor = '#45484d';

    var themerPrimaryColor = primaryColor;
    </script>

    <script src="{{ URL::to('coral')}}/assets/components/library/bootstrap/js/bootstrap.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.9.6&sv=v0.0.1"></script>

    <script src="{{ URL::to('coral')}}/assets/components/plugins/breakpoints/breakpoints.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/plugins/preload/pace/pace.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/core/js/preload.pace.init.js?v=v1.9.6"></script>

    <script src="{{ URL::to('coral')}}/assets/components/common/gallery/gridalicious/assets/lib/jquery.gridalicious.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/common/gallery/gridalicious/assets/custom/gridalicious.init.js?v=v1.9.6"></script>

    <script src="{{ URL::to('coral')}}/assets/components/common/gallery/blueimp-gallery/assets/lib/js/blueimp-gallery.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/common/gallery/blueimp-gallery/assets/lib/js/jquery.blueimp-gallery.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/plugins/image-preview/image-preview.js?v=v1.9.6&sv=v0.0.1"></script>

    <script src="{{ URL::to('coral')}}/assets/components/common/forms/elements/bootstrap-select/assets/lib/js/bootstrap-select.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/common/forms/elements/bootstrap-select/assets/custom/js/bootstrap-select.init.js?v=v1.9.6&sv=v0.0.1"></script>

    <script src="{{ URL::to('coral')}}/assets/components/plugins/mixitup/jquery.mixitup.min.js?v=v1.9.6&sv=v0.0.1"></script>
    <script src="{{ URL::to('coral')}}/assets/components/plugins/mixitup/mixitup.init.js?v=v1.9.6&sv=v0.0.1"></script>


    <script src="{{ URL::to('coral')}}/assets/components/core/js/core.init.js?v=v1.9.6"></script>
    {{--
    <script src="{{ URL::to('coral')}}/assets/components/core/js/animations.init.js?v=v1.9.6"></script>
    --}}


@include('layout.js')

</body>
</html>