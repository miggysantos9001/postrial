<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>System</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo asset('public/template/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo asset('public/template/bower_components/font-awesome/css/font-awesome.min.css') ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo asset('public/template/bower_components/Ionicons/css/ionicons.min.css') ?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo asset('public/template/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') ?>">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo asset('public/template/bower_components/bootstrap-daterangepicker/daterangepicker.css') ?>">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo asset('public/template/plugins/timepicker/bootstrap-timepicker.min.css') ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo asset('public/template/bower_components/select2/dist/css/select2.min.css') ?>">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo asset('public/template/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') ?>">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo asset('public/template/dist/css/AdminLTE.min.css') ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo asset('public/template/dist/css/skins/_all-skins.min.css') ?>">
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">
  <style>
      .btn{
        border-radius: 0px;
      }
  </style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  @notify_css
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <a href="#" class="logo">
                <!-- <span class="logo-mini"><b>A</b>LT</span> -->
                <span class="logo-lg">
                  <img src="<?php echo asset('public/template/dist/img/header.png') ?>">
                </span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo asset('public/template/dist/img/thelogo.png') ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="<?php echo asset('public/template/dist/img/thelogo.png') ?>" class="img-circle" alt="User Image">
                                    <p>
                                        {{ Auth::user()->name }}
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="text-center">
                                        <a href="{{ action('UserController@changepassword',Auth::user()->id) }}" class="btn btn-warning btn-flat">
                                            Change Password
                                        </a>
                                        <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                                
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo asset('public/template/dist/img/thelogo.png') ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dot-circle-o"></i> <span>Main Forms</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ action('ClientController@index') }}"><i class="fa fa-circle-o"></i> Client List</a></li>
                            <li><a href="{{ action('ClientgroupController@index') }}"><i class="fa fa-circle-o"></i> Client Group List</a></li>
                            <li><a href="{{ action('CustomerController@index') }}"><i class="fa fa-circle-o"></i> Customer List</a></li>
                            <li><a href="{{ action('DonationController@index') }}"><i class="fa fa-circle-o"></i> Donation List</a></li>
                            <li><a href="{{ action('SVIBookingController@index') }}"><i class="fa fa-circle-o"></i> Order From Supplier</a></li>
                            <li><a href="{{ action('BookingController@index') }}"><i class="fa fa-circle-o"></i> Booking List</a></li>
                            <!-- <li><a href="{{ action('PenongsController@index') }}"><i class="fa fa-circle-o"></i> Penong Booking List</a></li> -->
                            <li><a href="{{ action('PaymentController@index') }}"><i class="fa fa-circle-o"></i> Payment List</a></li>
                            <li><a href="{{ action('ExpenseController@index') }}"><i class="fa fa-circle-o"></i> Expense List</a></li>
                            <li><a href="{{ action('ReportController@view_lost_kilos') }}"><i class="fa fa-circle-o"></i> Lost Kilos</a></li>
                        </ul>
                    </li>
                    <!-- <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dot-circle-o"></i> <span>Inventories</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            
                        </ul>
                    </li> -->
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dot-circle-o"></i>
                            <span>Reports</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ action('ReportController@view_customer_order_report') }}"><i class="fa fa-circle-o"></i> Customer Order Report</a></li>
                            <li><a href="{{ action('ReportController@view_expenses') }}"><i class="fa fa-circle-o"></i> Expenses Report</a></li>
                            <li><a href="{{ action('ReportController@view_donation_report') }}"><i class="fa fa-circle-o"></i> Donation Summary Report</a></li>
                            <li><a href="{{ action('ReportController@view_order_report') }}"><i class="fa fa-circle-o"></i> Order Summary Report</a></li>
                            <li><a href="{{ action('ReportController@view_summary') }}"><i class="fa fa-circle-o"></i> Summary Report</a></li>
                            <li><a href="{{ action('ReportController@view_sales_summary') }}"><i class="fa fa-circle-o"></i> Sales Report</a></li>
                            <li><a href="{{ action('ReportController@view_daily_sales_summary') }}"><i class="fa fa-circle-o"></i> Daily Sales Report</a></li>
                            <li><a href="{{ action('ReportController@view_discrepancy') }}"><i class="fa fa-circle-o"></i> Discrepancy Report</a></li>
                            <li><a href="{{ action('ReportController@view_customer_soa') }}"><i class="fa fa-circle-o"></i> Customer SOA Report</a></li>
                            <li><a href="{{ action('ReportController@view_unpaids') }}"><i class="fa fa-circle-o"></i> Unpaid Per Customer</a></li>
                            <li><a href="{{ action('ReportController@view_lost_kilo_report') }}"><i class="fa fa-circle-o"></i> Lost Kilo Report</a></li>
                            <li><a href="{{ action('ReportController@view_bank_balance') }}"><i class="fa fa-circle-o"></i> Bank Balance</a></li>
                            <li><a href="{{ action('ReportController@yearly_report_per_client') }}"><i class="fa fa-circle-o"></i> Client Yearly Summary Report</a></li>
                            <li><a href="{{ action('ReportController@yearly_report_all_client') }}"><i class="fa fa-circle-o"></i> Yearly Summary Report</a></li>
                            <li><a href="{{ action('ReportController@yearly_sales_report') }}"><i class="fa fa-circle-o"></i> Yearly Sales Report</a></li>
                            <li><a href="{{ action('ReportController@monthly_expense_report') }}"><i class="fa fa-circle-o"></i> Monthly Expense Report</a></li>
                            <li><a href="{{ action('ReportController@yearly_expense_report') }}"><i class="fa fa-circle-o"></i> Yearly Expense Report</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dot-circle-o"></i>
                            <span>Utilities</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ action('BankBalanceController@index') }}"><i class="fa fa-circle-o"></i> Bank Balance</a></li>
                            <li><a href="{{ action('CustomergroupController@index') }}"><i class="fa fa-circle-o"></i> Customer Group</a></li>
                            <li><a href="{{ action('ExpensetypeController@index') }}"><i class="fa fa-circle-o"></i> Expense Types</a></li>
                            <li><a href="{{ action('ProductController@index') }}"><i class="fa fa-circle-o"></i> Products</a></li>
                            <li><a href="{{ action('TermController@index') }}"><i class="fa fa-circle-o"></i> Terms</a></li>
                            <li><a href="{{ action('UserController@index') }}"><i class="fa fa-circle-o"></i> Users</a></li>
                        </ul>
                    </li>
                </ul>
            </section>
        </aside>
        @yield('content')
        @yield('modal')
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0
            </div>
            <strong>Copyright &copy; 2020 <a href="#">MDS</a>.</strong> All rights
            reserved.
        </footer>  
    </div>
    @notify_js
    @notify_render
    <!-- jQuery 3 -->
    <script src="<?php echo asset('public/template/bower_components/jquery/dist/jquery.min.js') ?>"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo asset('public/template/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- DataTables -->
    <script src="<?php echo asset('public/template/bower_components/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo asset('public/template/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') ?>"></script>
    <!-- Select2 -->
    <script src="<?php echo asset('public/template/bower_components/select2/dist/js/select2.full.min.js') ?>"></script>
    <!-- bootstrap datepicker -->
    <script src="<?php echo asset('public/template/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') ?>"></script>
    <!-- SlimScroll -->
    <script src="<?php echo asset('public/template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo asset('public/template/bower_components/fastclick/lib/fastclick.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo asset('public/template/dist/js/adminlte.min.js') ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo asset('public/template/dist/js/demo.js') ?>"></script>
    <script>
      $(document).ready(function () {
        $('.sidebar-menu').tree();
      })

      $(document).ready( function () {
        $('#myTable').DataTable({
            'ordering'  : false
        });
        $('.select2').select2();
        $('.dp').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd',
        })
      });


    </script>
    @yield('js')
</body>
</html>
