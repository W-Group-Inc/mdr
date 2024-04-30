<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Online MDR System</title>

    <link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('css')

    <!-- Gritter -->
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    
</head>
<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
                            {{-- <span>
                                <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                            </span> --}}
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong></span>
                                {{-- <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> --}}
                                <span class="text-muted text-xs block">
                                    @switch(Auth::user()->account_role)
                                        @case(0)
                                            {{ 'Admin' }}
                                            @break
                                        @case(1)
                                            {{ 'Approver' }}
                                            @break
                                        @case(2)
                                            {{ 'Department Head' }}
                                            @break
                                        @default
                                            
                                    @endswitch
                                </span>
                            </a>
                        </div>
                    </li>
                    <li class="{{ Route::currentRouteName() == "dashboard" ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboards</span>
                        </a>
                    </li>
                    @if(Auth::user()->account_role == 0)
                        <li class="{{ Route::currentRouteName() == "manageApprover" ? 'active' : '' }}">
                            <a href="{{ route('manageApprover') }}">
                                <i class="fa fa-users"></i>
                                <span class="nav-label">Manage Approver</span>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteName() == 'departments' || Route::currentRouteName() == 'userAccounts' || Route::currentRouteName() == 'departmentKpi' || Route::currentRouteName() == 'departmentGroup' ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-cog"></i>
                                <span class="nav-label">Settings</span> 
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li class="{{ Route::currentRouteName() == "departments" ? 'active' : '' }}"><a href="{{ route('departments') }}">Departments</a></li>
                                <li class="{{ Route::currentRouteName() == "departmentKpi" ? 'active' : '' }}"><a href="{{ route('departmentKpi') }}">Department KPI</a></li>
                                <li class="{{ Route::currentRouteName() == "departmentGroup" ? 'active' : '' }}"><a href="{{ route('departmentGroup') }}">Department KPI Group</a></li>
                                <li class="{{ Route::currentRouteName() == "userAccounts" ? 'active' : '' }}"><a href="{{ route('userAccounts') }}">User Accounts</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->account_role == 2)
                        <li class="{{ Route::currentRouteName() == "mdr" ? 'active' : '' }}">
                            <a href="{{ url('mdr') }}">
                                <i class="fa fa-file"></i>
                                <span class="nav-label">MDR</span>
                            </a>
                        </li>
                        {{-- <li class="{{ Route::currentRouteName() == "manageApprover" ? 'active' : '' }}">
                            <a href="{{ route('manageApprover') }}">
                                <i class="fa fa-users"></i>
                                <span class="nav-label">Manage Approver</span>
                            </a>
                        </li> --}}
                    @endif
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>

    @stack('scripts')
    <!-- Mainly scripts -->
    {{-- <script src="js/plugins/dataTables/datatables.min.js"></script> --}}
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Flot -->
    {{-- <script src="{{ asset('js/plugins/flot/jquery.flot.js') }}"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="js/plugins/flot/jquery.flot.time.js"></script> --}}

    <!-- Peity -->
    {{-- <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script> --}}

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('js/inspinia.js') }}"></script>
    <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>

    <!-- jQuery UI -->
    {{-- <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script> --}}

    <!-- Jvectormap -->
    {{-- <script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> --}}

    <!-- EayPIE -->
    {{-- <script src="js/plugins/easypiechart/jquery.easypiechart.js"></script> --}}

    <!-- Sparkline -->
    {{-- <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script> --}}

    <!-- Sparkline demo data  -->
    {{-- <script src="js/demo/sparkline-demo.js"></script> --}}
</body>
</html>