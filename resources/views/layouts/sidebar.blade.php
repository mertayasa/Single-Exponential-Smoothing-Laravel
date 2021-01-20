<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
<div class="sidebar-brand-icon rotate-n-15">
</div>
<div class="sidebar-brand-text">Tempo Dulu Kopi</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item {{Request::is('/') ? 'active' : ''}}">
<a class="nav-link" href="{{route('dashboard')}}">
    <i class="fas fa-fw fa-tachometer-alt"></i>
    <span>Dashboard</span></a>
</li>

<hr class="sidebar-divider">

<li class="nav-item {{Request::is('menu*') ? 'active' : ''}}">
<a class="nav-link" href="{{route('menu.index')}}">
    <i class="fas fa-shopping-bag"></i>
    <span>Menu</span></a>
</li>

<hr class="sidebar-divider">

<li class="nav-item {{Request::is('actual-data*') ? 'active' : ''}}">
<a class="nav-link" href="{{route('actual_data.index')}}">
    <i class="fas fas fa-layer-group"></i>
    <span>Aktual Data</span></a>
</li>

<hr class="sidebar-divider">

<li class="nav-item {{Request::is('forecast*') ? 'active' : ''}}">
<a class="nav-link" href="{{route('forecast.index')}}">
    <i class="fas fa-chart-bar"></i>
    <span>Peramalan</span></a>
</li>

<hr class="sidebar-divider d-none d-md-block">