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
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
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

            <a href="#" class="fa fa-bars navbar-toggle off-left visible-xs" data-toggle="collapse" data-target="#hor-menu"></a>

            <div class="brand bg-white width-auto">
                <a href="index-2.html" class="navbar-brand text-white">
                    <i class="fa fa-microphone mg-r-xs"></i>
                    <span>{{ Config::get('site.name')}} -
                        <b>admin</b>
                    </span>
                    </span>
                </a>
            </div>

            <div class="collapse navbar-collapse pull-left no-padding" id="hor-menu">
                @include('partials.topnav')
            </div>

            @include('partials.identity')

            {{--

            <form class="navbar-form navbar-left hidden-xs" role="search">
                <div class="form-group">
                    <button class="btn btn-white no-border no-margin bg-none" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <input type="text" class="form-control no-border no-padding search" placeholder="Search Workspace">
                </div>
            </form>

            <ul class="nav navbar-nav navbar-right hidden-xs">
                <li class="notifications dropdown">
                    <a href="#" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <div class="badge badge-top bg-danger animated flash">3</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated slideInRight">
                        <div class="panel bg-white no-border no-margin">
                            <div class="panel-heading no-radius">
                                <small>
                                    <b>Notifications</b>
                                </small>
                                <small class="pull-right">
                                    <a href="#" class="mg-r-xs">mark as read</a>&#8226;
                                    <a href="#" class="fa fa-cog mg-l-xs"></a>
                                </small>
                            </div>

                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="#">
                                        <span class="pull-left mg-t-xs mg-r-md">
                                            <img src="img/face4.jpg" class="avatar avatar-sm img-circle" alt="">
                                        </span>
                                        <div class="m-body show pd-t-xs">
                                            <span>Dean Winchester</span>
                                            <span>Posted on to your wall</span>
                                            <small>2 mins ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#">
                                        <span class="pull-left mg-t-xs mg-r-md">
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x text-warning"></i>
                                                <i class="fa fa-download fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </span>
                                        <div class="m-body show pd-t-xs">
                                            <span>145 MB download in progress.</span>
                                            <div class="progress progress-xs mg-t-xs mg-b-xs">
                                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                                </div>
                                            </div>
                                            <small>Started 23 mins ago</small>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#">
                                        <span class="pull-left mg-t-xs mg-r-md">
                                            <img src="img/face3.jpg" class="avatar avatar-sm img-circle" alt="">
                                        </span>
                                        <div class="m-body show pd-t-xs">
                                            <span>Application</span>
                                            <span>Where is my workspace widget</span>
                                            <small>5 days ago</small>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <div class="panel-footer no-border">
                                <a href="#">See all notifications</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="quickmenu mg-r-md">
                    <a href="#" data-toggle="dropdown">
                        <img src="img/avatar.jpg" class="avatar pull-left img-circle" alt="user" title="user">
                        <i class="caret mg-l-xs hidden-xs no-margin"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="#">
                                <div class="pd-t-sm">
                                    gerald@morris.com
                                    <br>
                                    <small class="text-muted">4.2 MB of 51.25 GB used</small>
                                </div>
                                <div class="progress progress-xs no-radius no-margin mg-b-sm">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="profile.html">Settings</a>
                        </li>
                        <li>
                            <a href="#">Upgrade</a>
                        </li>
                        <li>
                            <a href="#">Notifications
                                <div class="badge bg-danger pull-right">3</div>
                            </a>
                        </li>
                        <li>
                            <a href="#">Help ?</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="signin.html">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>

            --}}

        </header>
        <!-- /top header -->

        <section class="layout">
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

    @include('layout.js')
    <!-- /page scripts -->

</body>
<!-- /body -->

</html>
