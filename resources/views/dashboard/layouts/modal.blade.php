<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from foxythemes.net/preview/products/beagle/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Sep 2018 06:24:19 GMT -->
<head>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-fav.png') }}">
    <title>Beagle</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/perfect-scrollbar/css/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/material-design-icons/css/material-design-iconic-font.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css"/>
  </head>
  <body class="be-splash-screen">
    <div class="be-wrapper be-login">
      <div class="be-content">
        <div class="main-content container-fluid">
          @yield('content')
        </div>
      </div>
    </div>
    <script src="{{ asset('assets/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/perfect-scrollbar/js/perfect-scrollbar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/bootstrap/dist/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      	//-initialize the javascript
      	App.init();
      });
      
    </script>
  </body>

<!-- Mirrored from foxythemes.net/preview/products/beagle/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Sep 2018 06:24:19 GMT -->
</html>