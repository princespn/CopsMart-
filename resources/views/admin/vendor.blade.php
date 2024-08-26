<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Copsmart Admin</title>


    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/new.css">
    
    <link rel="stylesheet" href='https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css'>
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/duotone.css" integrity="sha384-R3QzTxyukP03CMqKFe0ssp5wUvBPEyy9ZspCB+Y01fEjhMwcXixTyeot+S40+AjZ" crossorigin="anonymous"/>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css" integrity="sha384-eHoocPgXsiuZh+Yy6+7DsKAerLXyJmu2Hadh4QYyt+8v86geixVYwFqUvMU8X90l" crossorigin="anonymous"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" />

</head>

<div class="loading-overlay">
 <img src='/images/loader.svg'>
</div>


<div class="loading-overlay2">
    {{-- <object type="image/svg+xml" data="/images/new.svg"> --}}

        <img src="/images/new.svg" />
        
        {{-- </object> --}}
    {{-- <img src='' /> --}}
   </div>
   
<body class="hold-transition ">
    
    <div class="wrapper" id="app">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand  navbar-light border-bottom sidebar-dark-primary">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link clicknew" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>
            <div class="w-100 row">
            <div style="float: left; margin-left: 63px;">
                <a id="buttonc"  data-toggle="modal" data-target="#notificas"  class="btn btn-small btn-warning fl-right" style="display:none">Enable Notification</a>
                <a href="\dashboard" class="btn btn-small  text-white btn-primary  btn-outline-secondary fl-right mr-2">Dashboard</a>
            </div>
            <div class="col-md-3">
            </div>
            <div class="col-md-3">
                <h6 class="mb-0 text-white">Contact Support : + 91 7020979072</h6>
                <h6 class="mb-0 text-white">Support Hours : Mon-Fri(10.00 AM to 7:00PM)</h6>
            </div>
           
            <div class="col-md-4">
                
                <a href="\MakeBill" class="btn btn-small  text-white btn-primary  btn-outline-secondary fl-right mr-2">New Make</a>
                <a href="\makeabill" class="btn btn-small text-white btn-primary btn-outline-secondary fl-right mr-2">Make a bill</a>
                <a href="\vAddCustomer" class="btn btn-small text-white   btn-primary btn-outline-secondary fl-right mr-2">Add Customer </a>
            </div>
            
               
        </div>

            <input type='hidden' value="{{Auth::user()->id}}" id="authid">
            <input type='hidden' value="" id="checknotification">
            <input type='hidden' value="" id="modald">
            <!-- SEARCH FORM -->
            <!-- <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form> -->


        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/" class="brand-link">

                <img src="/images/logo.png" alt="Logo" class="img img-responsive" style="max-width:150px;">
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
               

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <router-link to="/dashboard" class="nav-link">
                                <i class="nav-icon fa fa-home"></i>
                                <p>
                                    Dashboard
                                </p>
                            </router-link>
                        </li>
                        <!-- businesss section -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                 Business Section
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/OrderManagement" class="nav-link">
                                    <i class="fas fa-shopping-cart nav-icon green"></i>
                                        <p>Order Mangement</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/OnlineOrderManagement" class="nav-link">
                                    <i class="fas fa-shopping-cart nav-icon green"></i>
                                        <p>Online Order Mangement</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/customer" class="nav-link">
                                        <i class="fa fa-user nav-icon"></i>
                                        <p>Customers Management</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/delivery_person" class="nav-link">
                                        <i class="fa fa-user nav-icon green"></i>
                                        <p>Delivery Boy Management</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/attribute" class="nav-link">
                                    <i class="fas fa-sitemap nav-icon green"></i>
                                        <p>Custom Request Management</p>
                                    </router-link>
                                </li>

                                
                              

                            </ul>
                        </li>
                        <!-- end -->
                        <!-- inventory section -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-dolly-flatbed"></i>
                            <p>Inventory Management <i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                            <li class="nav-item">
                                    <router-link to="/addproduct" class="nav-link">
                                    <i class="fa fa-cube nav-icon green"></i>
                                        <p>Product / Item Addition</p>
                                    </router-link>
                            </li>
                            <li class="nav-item">
                                    <router-link to="/product" class="nav-link">
                                    <i class="fa fa-cube nav-icon green"></i>
                                        <p>Product List</p>
                                    </router-link>
                            </li>
                            <li class="nav-item">
                                    <router-link to="/ProductRecomend" class="nav-link">
                                    <i class="fa fa-cube nav-icon green"></i>
                                        <p>Product Recomend</p>
                                    </router-link>
                            </li>
                            <li class="nav-item">
                                    <router-link to="/stockmanagement" class="nav-link">
                                     <i class="fad fa-dolly-flatbed-alt nav-icon green"></i> 
                                        <p>Stock Management</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/ViewPurchase" class="nav-link">
                                    <i class="fa fa-store nav-icon"></i>
                                        <p>Purchase Management</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/VendorManagement" class="nav-link">
                                        <i class="fa fa-user nav-icon green"></i>
                                        <p>Vendor Management</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/menuitemmanagement" class="nav-link">
                                    <i class="fas fa-ellipsis-v nav-icon green"></i>
                                        <p>Menu Item Management</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <!-- end -->
                        <!-- banner setting -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class=" nav-icon fas fa-ad"></i>
                                <p>Banner Settings<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/TopBannerList" class="nav-link">
                                    <i class="fas fa-ad nav-icon green"></i>
                                        <p>Top Banners</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/BottomBannerList" class="nav-link">
                                        <i class="fas fa-ad nav-icon"></i>
                                        <p>Bottom Banner</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <!-- end -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-cog"></i>
                                <p>Inventory Settings<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/category" class="nav-link">
                                        <i class="fa fa-cube nav-icon red"></i>
                                        <p>Product Categories</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/sub_category" class="nav-link">
                                    <i class="fab fa-adn nav-icon green"></i>
                                        <p>Sub Sub Category</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/brand" class="nav-link">
                                        <i class="fas fa-exclamation-circle nav-icon green"></i>
                                        <p>Product Brands</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/attribute" class="nav-link">
                                    <i class="fas fa-tags nav-icon green"></i>
                                        <p>Product Tags</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/size" class="nav-link">
                                    <i class="fas fa-tags nav-icon green"></i>
                                        <p>Product Size</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/color" class="nav-link">
                                    <i class="fas fa-tags nav-icon green"></i>
                                        <p>Product Color</p>
                                    </router-link>
                                </li>

                              

                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-bell"></i>
                                <p>Notification<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/vSendFirePushNotiSetting" class="nav-link">
                                        <i class="fa fa-bell nav-icon red"></i>
                                        <p>Send Notification</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <!-- settlement module -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                                <p>Settlement Section<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/vSettleGateApiSetting" class="nav-link">
                                        <i class="fa fa-file nav-icon red"></i>
                                        <p>Settlement Reports</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                          <!-- feedback module -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-comments"></i>
                                <p>Feedback Module<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/ProductReview" class="nav-link">
                                    <i class="fas fa-star nav-icon"></i>
                                        <p>Product Reviews</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/ProductReview" class="nav-link">
                                    <i class="fas fa-star nav-icon"></i>
                                        <p>Delivery Boy Reviews</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                         
                       <!-- Reports -->
                       <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                                <p>Reports<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/Showstock" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Show Stock</p>
                                    </router-link>
                                </li>
                                
                                <li class="nav-item">
                                    <router-link to="/GstReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>GST Sales Report</p>
                                    </router-link>
                                </li>

                             
                                <li class="nav-item">
                                    <router-link to="/gstRebateReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>GST Rebate Report</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/getsalenewreport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Stock Sales Report</p>
                                    </router-link>
                                </li>
                                  <li class="nav-item">
                                    <router-link to="/HsnReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>HSN Sales Report</p>
                                    </router-link>
                                </li>
                                
                                <li class="nav-item">
                                    <router-link to="/PurchaseGstReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>GST Purchase Report</p>
                                    </router-link>
                                </li>
                                
                                 <li class="nav-item">
                                    <router-link to="/DayBookReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Day-Book Report</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/OverallDayReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Overall Report</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/SaleReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Sales Report</p>
                                    </router-link>
                                </li>
                                
                                <li class="nav-item">
                                    <router-link to="/detailsaleReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Detail Sales Report</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/PaySaleReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Sales Report(Payment Mode)</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/UserOrderReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Sales Report(User Wise)</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/SaleProductReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Sales Report(Product Wise)</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/ProfitReport" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                        <p>Profit Report</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <!-- setting -->
                          
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-cog"></i>
                                <p>Settings<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/ProductReview" class="nav-link">
                                    <i class="fas fa-users-cog nav-icon"></i>
                                        <p>About Vendor</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/VendorSetting" class="nav-link">
                                    <i class="fas fa-cog nav-icon"></i>
                                        <p>Vendor Settings</p>
                                    </router-link>
                                </li>

                                <li class="nav-item">
                                    <router-link to="/delivery_charges" class="nav-link">
                                    <i class="fas fa-cog nav-icon"></i>
                                        <p>Delivery Charges Settings</p>
                                    </router-link>
                                </li>

                            </ul>
                        </li>

                        
                       
                      

                   

                      
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                <i class="nav-icon fa fa-power-off"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
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
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Vue Router -->
                    <router-view></router-view>
                    <vue-progress-bar></vue-progress-bar>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer" id="fott">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2020 <a href="http://mechatrontechgear.com/">Mechatron Techgear Pvt Ltd</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- AdminLTE App -->
    <script src="/js/app.js"></script>
    <script src="/js/table2excel.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</body>

</html>
