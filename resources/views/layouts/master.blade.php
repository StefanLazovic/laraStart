<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

  <title>LaraStart</title>

  <style media="screen">
    /* width */
  ::-webkit-scrollbar {
  width: 10px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
  background: #f1f1f1;
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
  background: rgba(68, 63, 181, 0.9);
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
  background: #443fb5;
  }

  body {
    min-width: 375px;
  }

  #user-image {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    /* the default image will be changed, if the profile picture is updated */
    background-image: url("{{ Auth::user()->photo == 'profile.jpeg' ? './img/profile.jpeg' : './img/profile/' . Auth::user()->photo }}");
    background-size: cover;
    background-position: center;
  }
  </style>
</head>
<body class="hold-transition">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-grey navbar-white border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars color-white"></i></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    {{-- search form (app.js, master.blade.php, Users.vue, UserController@search, api.php) --}}
    <div v-show="searchBox" class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input @keyup="searchit" v-model="search" class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button @click="searchit" class="btn btn-navbar">
            <i class="fa fa-search color-white"></i>
          </button>
        </div>
      {{-- </div> --}}
    </div>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 overflow-hidden">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link brand-link-hover">
      <img src="{{ url('img/logo.png') }}" alt="LaraStart Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">LaraStart</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-2 mb-3">
        {{-- user image --}}
        <div class="d-inline-block" id="user-image" alt="User Image" style="margin-left: 11px;"></div>
        <div class="info">
          <a href="/profile">
            {{-- uppercase the first Auth::user()->type letter --}}
            <span>{{ Auth::user()->name }} ({{ strtoupper((Auth::user()->type)[0]) . substr((Auth::user()->type), 1) }})</span>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li @click="searchBox = false" class="nav-item">
            <router-link to="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt color-indigo"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>
          @can ('isAdminOrAuthor')
            <li class="nav-item has-treeview menu-open">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog color-green"></i>
                <p>
                  Management
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li @click="searchBox = true" class="nav-item">
                  <router-link to="/users" class="nav-link">
                    <i class="fas fa-users nav-icon color-yellow"></i>
                    <p>Users</p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li @click="searchBox = false" class="nav-item">
              <router-link to="/developer" class="nav-link">
                <i class="nav-icon fas fa-cogs color-pink"></i>
                <p>
                  Developer
                </p>
              </router-link>
            </li>
          @endcan
          <li @click="searchBox = false" class="nav-item">
            <router-link to="/profile" class="nav-link">
              <i class="nav-icon fas fa-user-alt color-orange"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-power-off color-red"></i>
              <p>
                {{ __('Logout') }}
              </p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="text-center">
      <strong>Copyright &copy; {{ date('Y') }} LaraStart</strong>
    </div>
  </footer>
</div>
<!-- ./wrapper -->

{{-- if the current user is authenticated, I'm going to get that information, and store it in the window object --}}
{{-- Access Control List (Gate.js, AuthServiceProvider.php, master.blade.php, Users.vue) --}}
@auth
  <script>
    window.user = @json(auth()->user())
  </script>
@endauth

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
