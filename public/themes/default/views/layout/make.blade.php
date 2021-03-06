<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="admin-themes-lab">
    <meta name="author" content="themes-lab">
    <link rel="shortcut icon" href="../assets/global/images/favicon.png" type="image/png">
    <title>{{ Config::get('site.name') }}</title>

    @include('layout.css')

    {{ HTML::style('css/typography.css')}}

    <link href="{{ URL::to('makeadmin') }}/assets/global/css/style.css" rel="stylesheet">
    <link href="{{ URL::to('makeadmin') }}/assets/global/css/theme.css" rel="stylesheet">
    <link href="{{ URL::to('makeadmin') }}/assets/global/css/ui.css" rel="stylesheet">
    {{--
    <link href="{{ URL::to('makeadmin') }}/assets/admin/layout4/css/layout.css" rel="stylesheet">
    --}}

    <link href="{{ URL::to('makeadmin') }}/assets/admin/md-layout4/material-design/css/material.css" rel="stylesheet">
    <link href="{{ URL::to('makeadmin') }}/assets/admin/md-layout4/css/layout.css" rel="stylesheet">

    <link href="{{ URL::to('makeadmin') }}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">

    {{ HTML::style('css/syscolors.css') }}

    <script src="{{ URL::to('makeadmin') }}/assets/global/plugins/modernizr/modernizr-2.6.2-respond-1.1.0.min.js"></script>

    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/jquery/jquery-1.11.1.min.js"></script>
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/jquery/jquery-migrate-1.2.1.min.js"></script>
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/jquery-ui/jquery-ui-1.11.2.min.js"></script>

    <script type="text/javascript">
      var base = '{{ URL::to('/') }}/';
    </script>

    <style type="text/css">

      label{
        margin-top: 12px !important;

      }

      input.form-control{
        height:30px !important;
      }

      a.btn, input.btn{
        margin-top: 15px;
      }

      .select2-container .select2-choice{
        background-color: transparent !important;
      }

      .select2-selection {
          border-radius: 0px !important;
          border-top-width: 0px !important;
          border-left-width: 0px !important;
          border-right-width: 0px !important;
      }

      .select2-container--default .select2-selection:focus{
          border-bottom-width: 2px;
          outline: none !important;
          border-color: #0054a0;
      }

      .select2-selection__rendered{
          font-size: initial !important;
          color: #666666 !important;
          padding-left: 0px !important;
      }

      ul.ui-autocomplete{
        z-index: 20000 !important;
        border: thin solid #DDD;
      }

      ul.ui-autocomplete li{
        background-color: #fff;
      }

      ul.ui-autocomplete li.ui-state-focus
      {
          color:white;
          background:#058E9D;
          outline:none;
      }

      div.tagsinput span.tag , div.tagsinput span.tag a{
          color:white;
          background:#058E9D !important;
          border-color: #058E9D !important;
      }

    </style>

  </head>
  <!-- LAYOUT: Apply "submenu-hover" class to body element to have sidebar submenu show on mouse hover -->
  <!-- LAYOUT: Apply "sidebar-collapsed" class to body element to have collapsed sidebar -->
  <!-- LAYOUT: Apply "sidebar-top" class to body element to have sidebar on top of the page -->
  <!-- LAYOUT: Apply "sidebar-hover" class to body element to show sidebar only when your mouse is on left / right corner -->
  <!-- LAYOUT: Apply "submenu-hover" class to body element to show sidebar submenu on mouse hover -->
  <!-- LAYOUT: Apply "fixed-sidebar" class to body to have fixed sidebar -->
  <!-- LAYOUT: Apply "fixed-topbar" class to body to have fixed topbar -->
  <!-- LAYOUT: Apply "rtl" class to body to put the sidebar on the right side -->
  <!-- LAYOUT: Apply "boxed" class to body to have your page with 1200px max width -->

  <!-- THEME STYLE: Apply "theme-sdtl" for Sidebar Dark / Topbar Light -->
  <!-- THEME STYLE: Apply  "theme sdtd" for Sidebar Dark / Topbar Dark -->
  <!-- THEME STYLE: Apply "theme sltd" for Sidebar Light / Topbar Dark -->
  <!-- THEME STYLE: Apply "theme sltl" for Sidebar Light / Topbar Light -->

  <!-- THEME COLOR: Apply "color-default" for dark color: #2B2E33 -->
  <!-- THEME COLOR: Apply "color-primary" for primary color: #319DB5 -->
  <!-- THEME COLOR: Apply "color-red" for red color: #C9625F -->
  <!-- THEME COLOR: Apply "color-green" for green color: #18A689 -->
  <!-- THEME COLOR: Apply "color-orange" for orange color: #B66D39 -->
  <!-- THEME COLOR: Apply "color-purple" for purple color: #6E62B5 -->
  <!-- THEME COLOR: Apply "color-blue" for blue color: #4A89DC -->
  <!-- BEGIN BODY -->
  <body class="sidebar-top fixed-topbar fixed-sidebar theme-sdtl color-default">
    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <section>
      <!-- BEGIN SIDEBAR -->
      @include('partials.makesidebar')
      <!-- END SIDEBAR -->
      <div class="main-content">
        <!-- BEGIN TOPBAR -->
        @include('partials.maketopbar')
        <!-- END TOPBAR -->
        <!-- BEGIN PAGE CONTENT -->
        <div class="page-content">
          <div class="header">
            <h2>{{ boldfirst($title) }}</h2>
            <div class="breadcrumb-wrapper">
              {{ Breadcrumbs::render() }}

            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 portlets">

              <!-- HERE COMES YOUR CONTENT -->

              <div class="panel">

                <div class="panel-header panel-controls">
                </div>

                <div class="panel-content pagination2 table">

                  @yield('content')

                </div>
              </div>




            </div>
          </div>
          <div class="footer">
            <div class="copyright">
              <p class="pull-left sm-pull-reset">
                <span>Copyright <span class="copyright">©</span> 2015 </span>
                <span>THEMES LAB</span>.
                <span>All rights reserved. </span>
              </p>
              <p class="pull-right sm-pull-reset">
                <span><a href="#" class="m-r-10">Support</a> | <a href="#" class="m-l-10 m-r-10">Terms of use</a> | <a href="#" class="m-l-10">Privacy Policy</a></span>
              </p>
            </div>
          </div>
        </div>
        <!-- END PAGE CONTENT -->
      </div>
      <!-- END MAIN CONTENT -->
    </section>
    <!-- BEGIN QUICKVIEW SIDEBAR -->
    @include('partials.quickview')
    <!-- END QUICKVIEW SIDEBAR -->
    <!-- BEGIN SEARCH -->

    @include('partials.makesearch')
    <!-- END QUICKVIEW SIDEBAR -->
    <!-- BEGIN PRELOADER -->
    <!-- END PRELOADER -->
    <a href="#" class="scrollup"><i class="fa fa-angle-up"></i></a>
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/gsap/main-gsap.min.js"></script>
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/jquery-cookies/jquery.cookies.min.js"></script> <!-- Jquery Cookies, for theme -->
    {{--
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/jquery-block-ui/jquery.blockUI.min.js"></script> <!-- simulate synchronous behavior when using AJAX -->
    --}}
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/bootbox/bootbox.min.js"></script> <!-- Modal with Validation -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/mcustom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> <!-- Custom Scrollbar sidebar -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/bootstrap-dropdown/bootstrap-hover-dropdown.min.js"></script> <!-- Show Dropdown on Mouseover -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/charts-sparkline/sparkline.min.js"></script> <!-- Charts Sparkline -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/retina/retina.min.js"></script> <!-- Retina Display -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/select2/select2.min.js"></script> <!-- Select Inputs -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/icheck/icheck.min.js"></script> <!-- Checkbox & Radio Inputs -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/backstretch/backstretch.min.js"></script> <!-- Background Image -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/bootstrap-progressbar/bootstrap-progressbar.min.js"></script> <!-- Animated Progress Bar -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/charts-chartjs/Chart.min.js"></script>
    <script src="{{ URL::to('makeadmin')}}/assets/global/js/builder.js"></script> <!-- Theme Builder -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/js/sidebar_hover.js"></script> <!-- Sidebar on Hover -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/js/widgets/notes.js"></script> <!-- Notes Widget -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/js/quickview.js"></script> <!-- Chat Script -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/js/pages/search.js"></script> <!-- Search Script -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/js/plugins.js"></script> <!-- Main Plugin Initialization Script -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/js/application.js"></script> <!-- Main Application Script -->
    {{--
    <script src="{{ URL::to('makeadmin')}}/assets/admin/layout4/js/layout.js"></script> <!-- Main Application Script -->
    --}}

    <!-- BEGIN PAGE SCRIPT -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script> <!-- A mobile and touch friendly input spinner component for Bootstrap -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/timepicker/jquery-ui-timepicker-addon.min.js"></script> <!-- Time Picker -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/multidatepicker/multidatespicker.min.js"></script> <!-- Multi dates Picker -->
    <script src="{{ URL::to('makeadmin')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> <!-- >Bootstrap Date Picker -->

    <script src="{{ URL::to('makeadmin')}}/assets/admin/md-layout4/material-design/js/material.js"></script>
    <script src="{{ URL::to('makeadmin')}}/assets/admin/layout4/js/layout.js"></script>

    @include('layout.modaljs')
    @include('layout.js')


  </body>
</html>