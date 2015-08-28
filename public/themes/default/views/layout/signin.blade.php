<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title>Sign in</title>
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    {{ HTML::style('css/typography.css') }}


    {{ HTML::style('bootplus/css/font-awesome.min.css') }}

    <style type="text/css">
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #E5E5E5;
    }

    .form-signin {
        border: 1px solid #D8D8D8;
        border-bottom-width: 2px;
        border-top-width: 0;
        background-color: #FFF;
        max-width: 350px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #F5F5F5;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
            border-radius: 3px;
    }
    .form-signin .form-signin-heading {
     font-size: 24px;
     font-weight: 300;
    }

    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin input[type="text"],
    .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
    }

    </style>

    {{ HTML::style('bootplus/css/bootplus-responsive.min.css') }}
    {{ HTML::style('bootplus/css/font-awesome.min.css') }}

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
        <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

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
</head>

<body>

    <div class="container">

        @yield('content')

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    {{ HTML::script('js/jquery-1.9.1.js')}}
    {{ HTML::script('js/jquery-ui-1.9.2.custom.min.js')}}
    {{ HTML::script('sm/js/bootstrap.min.js')}}


  </body>
</html>
