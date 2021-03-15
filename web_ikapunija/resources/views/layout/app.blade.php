<?php

$menu['user_mgr']['select'] = 
$menu['user_alumni']['select'] = 
$menu['user_alumni_conf']['select'] = 
$menu['jurusan']['select'] = 
$menu['prodi']['select'] =
$menu['category']['select'] =
$menu['loker']['select'] =
$menu['banner']['select'] =

$menu['album']['val'] = 
$menu['album']['select'] =
$menu['album_als']['select'] =
$menu['album_gal']['select'] =

$menu['stk']['val'] = 
$menu['stk']['select'] =
$menu['stk_list']['select'] =
$menu['stk_level']['select'] =

$menu['pengumuman']['select'] = 
$menu['berita']['select'] =
$menu['agenda']['select'] =
'';

$menu[$head]['select'] = 'active';
if(isset($multi))
{ 
    $menu[$head]['val'] = 'menu-open'; 
    $menu[$sec]['select'] = 'active'; 
}

?>
<!DOCTYPE html>
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CMS Admin Ikapunija</title>
        <link rel="icon" type="image/png" href="https://ikapunija.com/favicon.ico">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
        <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
          <!-- Select2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- summernote -->
        <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/custom.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    </head>
    <!-- END HEAD -->

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link"  href="sign_out" role="button">
                <i class="nav-icon fas fa-sign-out-alt"></i>&nbsp Logout
                </a>
            </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
            <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">CMS Ikapunija</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                    <img src="asset_image/users/pp/default.webp" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                    <a href="#" class="d-block"><?php echo $username ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                    <li class="nav-header">CMS Manager</li>
                    <li class="nav-item">
                        <a href="user_manager" class="nav-link <?php echo $menu['user_mgr']['select'] ; ?>">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            User Manager
                        </p>
                        </a>
                    </li>

                    <!-- Kampus & ALumni -->
                    <li class="nav-header">Kampus & Alumni</li>
                    <li class="nav-item">
                        <a href="user_alumni" class="nav-link <?php echo $menu['user_alumni']['select'] ; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User Alumni
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="user_alumni_conf" class="nav-link <?php echo $menu['user_alumni_conf']['select'] ; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Konfirmasi User Alumni
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="cdc" class="nav-link <?php echo $menu['loker']['select'] ; ?>">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            CDC
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="jurusan" class="nav-link <?php echo $menu['jurusan']['select'] ; ?>">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            Jurusan
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="prodi" class="nav-link <?php echo $menu['prodi']['select'] ; ?>">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Prodi
                        </p>
                        </a>
                    </li>

                    <!-- Blog -->
                    <li class="nav-header">Blog</li>
                    <li class="nav-item">
                        <a href="category" class="nav-link <?php echo $menu['category']['select'] ; ?>">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p>
                            Category
                        </p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $menu['album']['val'] ; ?>">
                        <a href="#" class="nav-link <?php echo $menu['album']['select'] ; ?>">
                        <i class="nav-icon fas fa-camera"></i>
                        <p>
                            Album & Gallery
                            <i class="right fas fa-angle-left"></i>
                        </p>
                        </a>
                        <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="album" class="nav-link <?php echo $menu['album_als']['select'] ; ?>">
                            <i class="fas fa-angle-right nav-icon"></i>
                            <p>Album</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="gallery" class="nav-link <?php echo $menu['album_gal']['select'] ; ?>">
                            <i class="fas fa-angle-right nav-icon"></i>
                            <p>Gallery</p>
                            </a>
                        </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="pengumuman" class="nav-link <?php echo $menu['pengumuman']['select'] ; ?>">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Pengumuman
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="berita_alumni" class="nav-link <?php echo $menu['berita']['select'] ; ?>">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            Berita Alumni
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="agenda" class="nav-link <?php echo $menu['agenda']['select'] ; ?>">
                        <i class="nav-icon fas fa-calendar-week"></i>
                        <p>
                            Agenda
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="banner" class="nav-link <?php echo $menu['banner']['select'] ; ?>">
                        <i class="nav-icon fas fa-images"></i>
                        <p>
                            Banner
                        </p>
                        </a>
                    </li>

                    <!-- Lain Lain -->
                    <li class="nav-header">Lain Lain</li>
                    <li class="nav-item <?php echo $menu['stk']['val'] ; ?>">
                        <a href="#" class="nav-link <?php echo $menu['stk']['select'] ; ?>">
                        <i class="nav-icon fas fa-camera"></i>
                        <p>
                            Struktur
                            <i class="right fas fa-angle-left"></i>
                        </p>
                        </a>
                        <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="struktur" class="nav-link <?php echo $menu['stk_list']['select'] ; ?>">
                            <i class="fas fa-angle-right nav-icon"></i>
                            <p>List Struktur</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="strukturLevel" class="nav-link <?php echo $menu['stk_level']['select'] ; ?>">
                            <i class="fas fa-angle-right nav-icon"></i>
                            <p>List Level</p>
                            </a>
                        </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="sign_out" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                        </a>
                    </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
                @yield('content')
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0
            </div>
            <strong>Copyright &copy; 2021 <a href="https://ikapunija.com">Ikapunija</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="plugins/jszip/jszip.min.js"></script>
        <script src="plugins/pdfmake/pdfmake.min.js"></script>
        <script src="plugins/pdfmake/vfs_fonts.js"></script>
        <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <script src="plugins/toastr/toastr.min.js"></script>
        <!-- Select2 -->
        <script src="plugins/select2/js/select2.full.min.js"></script>
        <!-- Summernote -->
        <script src="plugins/summernote/summernote-bs4.min.js"></script>  
        
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="dist/js/demo.js"></script>

        <!-- overlayScrollbars -->
        <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

        <!-- date-range-picker -->
        <script src="plugins/daterangepicker/daterangepicker.js"></script>   

        <!-- InputMask -->
        <script src="plugins/moment/moment.min.js"></script>
        <script src="plugins/inputmask/jquery.inputmask.min.js"></script> 
        
        <script>
            var base64Img = '';
            var email = '<?php echo session('email'); ?>';
            var token = '<?php echo session('token'); ?>';
            
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        </script>
        <script src="dist/js/custom_plugin.js"></script> 
        @yield('page_script')
        </body>

</html>
