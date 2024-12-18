<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title> Aqua filter</title>
   <link rel="shortcut icon" type="image/x-icon" href="{{asset('logo.png')}}">
   <link rel="icon" type="image/x-icon" href="{{asset('logo.png')}}">

  <link rel="stylesheet" type="text/css" href="{{asset('fontawesome/css/all.min.css')}}">
  <script src="{{asset('fontawesome/js/all.min.js')}}" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/vendors.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/weather-icons/climacons.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/meteocons/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/morris.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/chartist.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/charts/chartist-plugin-tooltip.css')}}">
  <!-- END VENDOR CSS-->
  <!-- BEGIN MODERN CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/app.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/custom-rtl.css')}}">
  <!-- END MODERN CSS-->
  <!-- BEGIN Page Level CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/core/menu/menu-types/vertical-menu-modern.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/core/colors/palette-gradient.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/simple-line-icons/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/core/colors/palette-gradient.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/pages/timeline.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css-rtl/pages/dashboard-ecommerce.css')}}">
  <!-- END Page Level CSS-->
  <!-- BEGIN Custom CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style-rtl.css')}}">
  <!-- <link rel="stylesheet"  href="{{asset('css/fontawesome.min.css')}}"> -->
  <!-- END Custom CSS-->
   <style>
    body.vertical-layout.vertical-menu-modern.menu-expanded .main-menu .navigation li.has-sub > a:not(.mm-next):after {
  
    font-family: 'LineAwesome';
    font-size: 1rem;
    display: none;
    position: absolute;
    left: 20px;
    top: 14px;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
    -webkit-transition: -webkit-transform 0.2s ease-in-out;
    transition: -webkit-transform 0.2s ease-in-out;
}
    </style>
</head>
<body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar"
data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

  <!-- Loading Spinner -->
  <!-- <div id="loading-spinner" style="display:none;">
    <div class="spinner"></div>
  </div> -->

  <!-- fixed-top-->
  <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
          <li class="nav-item mr-auto">
            <a class="navbar-brand" href="{{route('dashboard')}}">
              <img class="brand-logo"  src="{{asset('logo.png')}}">
              <h3 class="brand-text"> اكوا فلتر </h3>
            </a>
          </li>
          <li class="nav-item d-none d-md-block float-right"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
          <li class="nav-item d-md-none">
            <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
          </li>
        </ul>
      </div>
      <div class="navbar-container content">
        <div class="collapse navbar-collapse" id="navbar-mobile">
          <ul class="nav navbar-nav float-right">
            <li class="dropdown dropdown-user nav-item d-flex align-items-center mr-3 me-3">
              <div class="d-flex align-items-center">
                <a href="{{route('cashier.viewCart')}}" class="btn btn-primary"> الكاشير </a>
              </div>
            </li>
            <li class="dropdown dropdown-user nav-item">
              <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                <span class="mr-1">مرحبا,
                  <span class="user-name text-bold-700"> {{Auth::user()->name}} </span>
                </span>
                <span class="avatar avatar-online">
                  <img src="{{asset('app-assets/images/portrait/small/avatar-s-19.png')}}" alt="avatar"><i></i></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="{{route('profile.edit')}}"><i class="ft-user"></i> تعديل بيانات الحساب </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ft-power"></i> تسجيل الخروج
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        @php
            $user = auth()->user();
        @endphp
    
        <!-- Dashboard -->
        @if($user->hasRole('admin') || $user->hasPermission('عرض لوحة التحكم'))
            <li class="{{ Route::currentRouteName() == 'dashboard' ? 'active':'' }} nav-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home"></i><span class="menu-title">لوحة التحكم</span></a>
            </li>
        @endif
    
        <!-- Products -->
        @if($user->hasRole('admin') || $user->hasPermission('عرض المنتجات') || $user->hasPermission('إنشاء المنتجات') || $user->hasPermission('عرض تقارير المنتجات'))
            <li class="nav-item">
                <a href="#"><i class="la la-cube"></i><span class="menu-title">المنتجات</span></a>
                <ul class="menu-content">
                    @if($user->hasPermission('عرض المنتجات'))
                        <li class="{{ Route::currentRouteName() == 'products.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('products.index') }}">عرض المنتجات</a>
                        </li>
                    @endif
                    @if($user->hasPermission('إنشاء المنتجات'))
                        <li class="{{ Route::currentRouteName() == 'products.create' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('products.create') }}">اضافة منتج</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض تقارير المنتجات'))
                        <li class="{{ Route::currentRouteName() == 'quantity.updates' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('quantity.updates') }}">تقارير المنتجات</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض الفئات'))
                        <li class="{{ Route::currentRouteName() == 'categories.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('categories.index') }}">عرض فئات المنتجات</a>
                        </li>
                    @endif
                    @if($user->hasPermission('إنشاء الفئات'))
                        <li class="{{ Route::currentRouteName() == 'categories.create' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('categories.create') }}">اضافة فئة منتج</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    
        <!-- Cashier -->
        @if($user->hasRole('admin') || $user->hasPermission('عرض عربة التسوق') || $user->hasPermission('إدارة الفواتير') || $user->hasPermission('إنشاء الفواتير'))
            <li class="nav-item">
                <a href="#"><i class="la la-shopping-cart"></i><span class="menu-title">الكاشير</span></a>
                <ul class="menu-content">
                    @if($user->hasPermission('عرض عربة التسوق'))
                        <li class="{{ Route::currentRouteName() == 'cashier.viewCart' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('cashier.viewCart') }}">اضافة فاتورة</a>
                        </li>
                    @endif
                    @if($user->hasPermission('إدارة الفواتير'))
                        <li class="{{ Route::currentRouteName() == 'invoices.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('invoices.index') }}">عرض فواتير البيع</a>
                        </li>
                    @endif
                    @if($user->hasPermission('إنشاء الفواتير'))
                        <li class="{{ Route::currentRouteName() == 'clients.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('clients.index') }}">ادارة العملاء</a>
                        </li>
                        <li class="{{ Route::currentRouteName() == 'invoices.scan' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('invoices.scan') }}">دفع اقساط بالكود</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    
        <!-- Purchase Invoices -->
        @if($user->hasRole('admin') || $user->hasPermission('عرض فواتير الشراء') || $user->hasPermission('إدارة الموردين') || $user->hasPermission('إضافة فاتورة شراء'))
            <li class="nav-item">
                <a href="#"><i class="la la-file-text"></i><span class="menu-title">فواتير الشراء</span></a>
                <ul class="menu-content">
                    @if($user->hasPermission('إضافة فاتورة شراء'))
                        <li class="{{ Route::currentRouteName() == 'purchases.create' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('purchases.create') }}">اضافة فاتورة</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض فواتير الشراء'))
                        <li class="{{ Route::currentRouteName() == 'purchases.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('purchases.index') }}">عرض فواتير الشراء</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض تقرير نقل المنتجات'))
                        <li class="{{ Route::currentRouteName() == 'reports.productTransfers' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('reports.productTransfers') }}">تقرير نقل المنتجات</a>
                        </li>
                    @endif
                    @if($user->hasPermission('إدارة الموردين'))
                        <li class="{{ Route::currentRouteName() == 'suppliers.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('suppliers.index') }}">ادارة الموردين</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    
        <!-- Reports -->
        @if($user->hasRole('admin') || $user->hasPermission('عرض تقارير المبيعات') || $user->hasPermission('عرض تقارير المنتجات') || $user->hasPermission('عرض تقارير الأقساط اليومية'))
            <li class="nav-item">
                <a href="#"><i class="la la-bar-chart"></i><span class="menu-title">التقارير</span></a>
                <ul class="menu-content">
                    @if($user->hasPermission('عرض التقارير اليومية'))
                        <li class="{{ Route::currentRouteName() == 'reports.daily' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('reports.daily') }}">التقارير اليومية للمبيعات</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض التقارير الشهرية'))
                        <li class="{{ Route::currentRouteName() == 'reports.monthly' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('reports.monthly') }}">التقارير الشهرية للمبيعات</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض تقارير المبيعات'))
                        <li class="{{ Route::currentRouteName() == 'reports.dateRange' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('reports.dateRange') }}">تقارير المبيعات بالتاريخ</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض تقارير المنتجات'))
                        <li class="{{ Route::currentRouteName() == 'reports.soldProductsReport' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('reports.soldProductsReport') }}">تقارير المنتجات المباعة</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض تقرير الأقساط اليومية'))
                        <li class="{{ Route::currentRouteName() == 'sales.installments.dailySummary' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('sales.installments.dailySummary') }}">تقرير الأقساط اليومية</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    
        <!-- Permissions for Admin Only -->
        @if($user->hasRole('admin'))
            <li class="nav-item">
                <a href="#"><i class="la la-lock"></i><span class="menu-title">المستخدمين والصلاحيات</span></a>
                <ul class="menu-content">
                    <li class="{{ Route::currentRouteName() == 'users.index' ? 'active':'' }}">
                        <a class="menu-item" href="{{ route('users.index') }}">  ارفاق الصلاحيات </a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'roles.create' ? 'active':'' }}">
                        <a class="menu-item" href="{{ route('roles.create') }}">اضافة أدوار</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'roles.index' ? 'active':'' }}">
                        <a class="menu-item" href="{{ route('roles.index') }}">عرض الأدوار</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'permissions.create' ? 'active':'' }}">
                        <a class="menu-item" href="{{ route('permissions.create') }}">اضافة صلاحية</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'permissions.index' ? 'active':'' }}">
                        <a class="menu-item" href="{{ route('permissions.index') }}">عرض الصلاحيات</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'role_user.index' ? 'active':'' }}">
                        <a class="menu-item" href="{{ route('role_user.index') }}">أدوار المستخدمين</a>
                    </li>
                    <li class="{{ Route::currentRouteName() == 'users.create' ? 'active':'' }}">
                        <a class="menu-item" href="{{ route('users.create') }}">اضافة مستخدم</a>
                    </li>
                </ul>
            </li>
        @endif
    
        <!-- Treasury -->
        @if($user->hasRole('admin') || $user->hasPermission('عرض الخزينة'))
            <li class="{{ Route::currentRouteName() == 'treasury' ? 'active':'' }}">
                <a class="menu-item" href="{{ route('treasury') }}">الخزينة</a>
            </li>
        @endif
    
        <!-- Villages -->
        @if($user->hasRole('admin') || $user->hasPermission('إدارة القري'))
            <li class="{{ Route::currentRouteName() == 'villages.index' ? 'active':'' }}">
                <a class="menu-item" href="{{ route('villages.index') }}">ادارة القرى</a>
            </li>
        @endif
    
        <!-- Maintenance Management -->
        @if($user->hasRole('admin') || $user->hasPermission('عرض فواتير الصيانة') || $user->hasPermission('عرض عملاء الصيانة'))
            <li class="nav-item">
                <a href="#"><i class="la la-wrench"></i><span class="menu-title">ادارة الصيانة</span></a>
                <ul class="menu-content">
                    @if($user->hasPermission('عرض فواتير الصيانة') || $user->hasRole('admin'))
                        <li class="{{ Route::currentRouteName() == 'maintenances.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('maintenances.index') }}">عرض الفواتير</a>
                        </li>
                    @endif
                    @if($user->hasPermission('عرض عملاء الصيانة') ||$user->hasRole('admin'))
                        <li class="{{ Route::currentRouteName() == 'maintenance.clients.index' ? 'active':'' }}">
                            <a class="menu-item" href="{{ route('maintenance.clients.index') }}">عرض عملاء الصيانة</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
      </ul>      
    </div>
</div>



  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">
            @yield('content')
      </div>
    </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <footer class="footer footer-static footer-light navbar-border navbar-shadow">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
      <span class="float-md-left d-block d-md-inline-block">Copyright &copy; ENG / GEORGE SAMY IBRAHIEM . </span>
      <span class="float-md-right d-block d-md-inline-blockd-none d-lg-block">01554923541
      <i class="fas fa-phone pink"></i>
      </p>
  </footer>
  <!-- BEGIN VENDOR JS-->
  <script src="{{asset('app-assets/vendors/js/vendors.min.js')}}" type="text/javascript"></script>
  <!-- BEGIN VENDOR JS-->
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{asset('app-assets/vendors/js/charts/chartist.min.js')}}" type="text/javascript"></script>
  {{-- <script src="{{asset('app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js')}}" type="text/javascript"></script>--}}
  <script src="{{asset('app-assets/vendors/js/charts/raphael-min.js')}}" type="text/javascript"></script>
  <script src="{{asset('app-assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('app-assets/vendors/js/timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN MODERN JS-->
  <script src="{{asset('app-assets/js/core/app-menu.js')}}" type="text/javascript"></script>
  <script src="{{asset('app-assets/js/core/app.js')}}" type="text/javascript"></script>
  <script src="{{asset('app-assets/js/scripts/customizer.js')}}" type="text/javascript"></script>
  <!-- END MODERN JS-->
  <script>
  $(document).ready(function() {
    // Show spinner on page load
    $('#loading-spinner').fadeIn();

    // Hide spinner when the document is ready
    if (document.readyState === 'complete') {
        $('#loading-spinner').fadeOut();
    } else {
        $(window).on('load', function() {
            $('#loading-spinner').fadeOut();
        });
    }
  });

  </script>
  <!-- BEGIN PAGE LEVEL JS-->
 {{-- <script src="{{asset('app-assets/js/scripts/pages/dashboard-ecommerce.js')}}" type="text/javascript"></script>  --}}
   
  <!-- END PAGE LEVEL JS-->
  @stack('scripts')
</body>
</html>