<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>{{ Config::get('site.name') }}</title>

  <!-- CSS  -->
  {{ HTML::style('css/materialize.css', array('media' => 'screen,projection')) }}
  {{ HTML::style('css/style.css', array('media' => 'screen,projection')) }}
  {{ HTML::style('css/typography.css', array('media' => 'screen,projection')) }}

  {{ HTML::script('js/jquery-2.1.3.min.js') }}
  {{ HTML::script('js/jquery-ui-1.9.2.custom.min.js')}}

  <script type="text/javascript">
      var base = '{{ URL::to('/') }}/';
  </script>

</head>
<body>

  <ul id="dropdown1" class="dropdown-content">
    <li><a href="#!">one</a></li>
    <li><a href="#!">two</a></li>
    <li class="divider"></li>
    <li><a href="#!">three</a></li>
  </ul>
  <nav>
    <div class="nav-wrapper red darken-4">
      <div class="col s12">
        <a href="#!" class="brand-logo">Logo</a>
        <ul class="side-nav">
          <li><a href="sass.html">Sass</a></li>
          <li><a href="components.html">Components</a></li>
          <!-- Dropdown Trigger -->
          <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Dropdown<i class="mdi-navigation-arrow-drop-down right"></i></a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="section no-pad-bot">
    <div class="row">
      <div class="col s12">
        {{ Breadcrumbs::render() }}
      </div>
    </div>
  </div>


  <div class="section no-pad-bot" id="index-banner">
      <div class="row center">
        @yield('content')
      </div>
  </div>


  <footer class="orange">
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="orange-text lighten-3" href="http://materializecss.com">Materialize</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  {{ HTML::script('js/materialize.js') }}
  {{ HTML::script('js/init.js') }}

  @include('layout.js')

  </body>
</html>
