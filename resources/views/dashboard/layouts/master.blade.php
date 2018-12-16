<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VueBilling</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="/assets/backend/distribution/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="/assets/backend/distribution/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="/assets/backend/distribution/css/fontastic.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="/assets/backend/distribution/https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="/assets/backend/distribution/css/grasp_mobile_progress_circle-1.0.0.min.css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="/assets/backend/distribution/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="/assets/backend/distribution/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="/assets/backend/distribution/css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="/assets/backend/distribution/img/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div id="app">
    <!-- Side Navbar -->
    <nav class="side-navbar mCustomScrollbar _mCS_1 shrink">
      <div class="side-navbar-wrapper">
        <!-- Sidebar Header    -->
        <div class="sidenav-header d-flex align-items-center justify-content-center">
          <!-- User Info-->
          <div class="sidenav-header-inner text-center">
            <img src="/assets/backend/distribution/img/avatar-7.jpg" alt="person" class="img-fluid rounded-circle">
            <h2 class="h5">Nathan Andrews</h2>
            <span>Web Developer</span>
          </div>
          <!-- Small Brand information, appears on minimized sidebar-->
          <div class="sidenav-header-logo"><a href="/dashboard" class="brand-small text-center"> <strong>B</strong><strong class="text-primary">D</strong></a></div>
        </div>
        <!-- Sidebar Navigation Menus-->
        <div class="main-menu">
          <!-- <h5 class="sidenav-heading">Main</h5> -->
          <ul id="side-main-menu" class="side-menu list-unstyled">                  
            <li><router-link to="/dashboard"> <i class="icon-home"></i>Dashboard</router-link></li>
            <li><router-link to="/dashboard/customer"> <i class="icon-form"></i>Customers</router-link></li>
            <li><router-link to="/dashboard/area"> <i class="icon-grid"></i>Areas</router-link></li>
            <li><router-link to="/dashboard/package"> <i class="icon-grid"></i>Packages</router-link></li>
            <li><router-link to="/dashboard/billing"> <i class="fa fa-bar-chart"></i>Billings</router-link></li>
            <li><router-link to="/dashboard/report"> <i class="fa fa-bar-chart"></i>Reports</router-link></li>
            <li> <router-link to="/dashboard/management"><i class="icon-screen"> </i>Management</router-link></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="page active">
      <!-- navbar-->
      <header class="header">
        <nav class="navbar">
          <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
              <div class="navbar-header">
                <a href="index.html" class="navbar-brand">
                  <div class="brand-text d-none d-md-inline-block"><strong class="text-primary">Dashboard</strong></div></a></div>
              <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                <li class="nav-item"><a href="login.html" class="nav-link logout"> <span class="d-none d-sm-inline-block">Logout</span><i class="fa fa-sign-out"></i></a></li>
              </ul>
            </div>
          </div>
        </nav>
      </header>
      <!-- @End Header Section-->

      <!-- Breadcrumb-->
      <!-- <div class="breadcrumb-holder">
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Home</a></li>
            <li class="breadcrumb-item active"></li>
          </ul>
        </div>
      </div> -->
      <router-view></router-view>
      <vue-progress-bar></vue-progress-bar>


      <!-- @Footer Start-->
      <footer class="main-footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <p>Your company &copy; 2017-{{ date('Y') }}</p>
            </div>
            <div class="col-sm-6 text-right">
              <p>Developed by <a href="http://rajtika.com" class="external">Rajtika IT</a></p>
              <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions and it helps me to run Bootstrapious. Thank you for understanding :)-->
            </div>
          </div>
        </div>
      </footer>
    </div>
    </div>
    <!-- JavaScript files-->
    <script src="/assets/backend/distribution/vendor/jquery/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    <script src="/assets/backend/distribution/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="/assets/backend/distribution/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="/assets/backend/distribution/vendor/chart.js/Chart.min.js"></script>
    <script src="/assets/backend/distribution/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="/assets/backend/distribution/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/assets/backend/distribution/js/charts-home.js"></script>
    <!-- Main File-->
    <script src="/assets/backend/distribution/js/front.js"></script>
  </body>
</html>