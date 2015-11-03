<!DOCTYPE html>
<html lang="en">
   <head>
    <title>{{ Config::get('site.name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

   </head>

   <body>
      @if(@heads != '')
        {{ $heads }}
      @endif

      @yield('content')

   </body>
</html>
