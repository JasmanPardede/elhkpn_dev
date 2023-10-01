  <?php
$ROLE = array();
foreach ($roles as $item) {
    $ROLE[$item->ID_ROLE]['ROLE'] = $item->ROLE;
    $ROLE[$item->ID_ROLE]['COLOR'] = $item->COLOR;
}
$photo = $this->session->userdata('PHOTO');

$role_kd = $this->session->userdata('INST_SATKERKD');
$NAMA = $this->session->userdata('UK_nama');
$this->load->model('mglobal');
if($role_kd != NULL || $role_kd != ''){
$dt_satker = $this->mglobal->get_by_id('m_inst_satker', 'INST_SATKERKD', $role_kd);
$dt_satkers = $this->mglobal->get_by_id_2($role_kd,$this->session->userdata('USR'));
}else{
$dt_satker = '';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LHKPN</title>

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="<?php echo base_url('img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon">
        <link href="<?php echo base_url(); ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url(); ?>plugins/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo base_url(); ?>plugins/ionicons-2.0.1/css/ionicons.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>plugins/AdminLTE-2.1.1/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?php echo base_url(); ?>plugins/AdminLTE-2.1.1/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>css/main.css" rel="stylesheet" type="text/css">

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Sweet Alert -->
        <!--<link href="<?php echo base_url(); ?>plugins/sweet-alert/dist/sweetalert.css" rel="stylesheet" type="text/css" />-->
        <script src="<?php echo base_url();?>plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <!--input file-->
        <script src="<?php echo base_url();?>portal-assets/js/fileinput.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/fileinput.min.css" />

        <!-- Bootstrap 3.3.2 JS -->
        <script src="<?php echo base_url(); ?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DataTables -->
        <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <!-- include the script -->
        <script src="<?php echo base_url(); ?>plugins/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/alertifyjs/css/alertify.min.css" />
        <!-- include a theme -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/alertifyjs/css/themes/default.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datepicker/datepicker3.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/daterangepicker/daterangepicker-bs3.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>portal-assets/css/generates.css" />
        <script type="text/javascript">var base_url = '<?php echo base_url();?>';</script>
        <script src="<?php echo base_url(); ?>js/ngv2.js"></script>

        <script src="<?php echo base_url(); ?>js/jquery-maskinput.js"></script>

        <link href="<?php echo base_url(); ?>plugins/select2/4.0.0/css/select2.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>portal-assets/css/select2-bootstrap.css" rel="stylesheet" />
        <script src="<?php echo base_url(); ?>plugins/select2/4.0.0/js/select2.js"></script>

        <!-- add by Wahyu -->
        <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/bootstrapValidator.min.css" />
        <script src="<?php echo base_url();?>portal-assets/js/moment-with-locales.js"></script>
        <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/bootstrap-datetimepicker.min.css" />
        <script src="<?php echo base_url();?>portal-assets/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?php echo base_url();?>portal-assets/js/bootstrapValidator.min.js"></script>
        <!-- End of Wahyu  -->


        <!----update by sukma----->
        <link href="<?php echo base_url(); ?>css/jquery.autocomplete.css" rel="stylesheet" />
        <script src="<?php echo base_url(); ?>js/jquery.autocomplete.js" type="text/javascript"></script>
        <!----end update------>

		<!-- addin by iqbal -->
		<script src="<?php echo base_url(); ?>js/adding.js" type="text/javascript"> </script>
		<!--end -->

        <script type="text/javascript" src="<?= base_url("portal-assets/js/jquery.maskMoney.min.js"); ?>"></script>
        <script type="text/javascript" src="<?= base_url("portal-assets/js/handlebars-v4.7.7.js"); ?>"></script>
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            body {
                font-family: 'Century Gothic', Helvetica, Dosis !important;
            }

            thead tr{
                /*background-color: #cfcfcf;*/
                background-color: #EAEAEA;
            }
            .modal-content {
                border-radius: 5px;
            }
            .skin-black-light .main-header .logo {
                background-color: #FFFFFF;
            }
            .skin-black-light .main-header .logo:hover {
                background-color: #EAEAEA;
            }
            .skin-black-light .main-header .navbar {
                background-color: #F6F6F6;
            }
            .linkActive{
                color: rgb(255, 255, 255) !important;
                background-color: #337AB7;
            }

        .table > thead > tr > th {
                background: -webkit-linear-gradient(#3c8dbc, #09C);
                background: -moz-linear-gradient(#3c8dbc, #09C);
                background: linear-gradient(#3c8dbc, #09C);
                color: #fff;
                 /* background-color: #f0f0f0;*/
                font-weight: normal;
                 padding: 8px;
            }
           /* .table > thead > tr > th {
                padding: 30px !important;
            }*/

        </style>

    </head>
    <body class="skin-black-light fixed sidebar-mini">
        <div id="loader_area">
            <div id="loader_page">
                <div id="loaderCircle"><img src="<?php echo base_url() ?>images/loader_big.gif" /></div>
            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header form-header">
                        <!-- <button type="button" class="close" onclick="window.location.reload(true)" data-dismiss=""><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> -->
						<h4 class="modal-title" id="modal-header"></h4>
                    </div>
                    <div class="modal-body" id="modal-inner">
                    </div>
                    <br>
                    <div class="modal-footer" id="modal-bottom">
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo base_url(); ?>portal/home" class="logo" style="background-color:#f6f6f6; border:none;">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <!-- <span class="logo-mini"><b>L</b>HKPN</span> -->
                   <span class="logo-mini"><img style="width:75%;" src="<?php echo base_url(); ?>images/logolhkpn kecil.png" ></span>
                    <!-- logo for regular state and mobile devices -->
                    <!-- <span class="logo-lg"><b>Admin</b>LHKPN</span> -->
                   <span class="logo-lg"><img style="width:75%;" src="<?php echo base_url(); ?>images/logolhkpn medium.png" ></span>

                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" >
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <!-- <a href="#" class="sidebar-toggle" data-toggle="dropdown" role="button" style="padding-top:15px;">
                        <i class="fa fa-envelope" style="font-size:40px; color:rgb(13, 189, 228);"></i>
                    </a> -->

                    <!-- <span style="margin-top: 15px;" class="badge bg-blue"><?php echo $this->session->userdata('INST_NAMA'); ?></span> -->

                    <div class="navbar-custom-menu navbar-left" id="menu-utama">
                        <ul class="nav navbar-nav">
                            <li><a href="<?php echo base_url();?>portal/home">BERANDA</a></li>
                            <li><a href="<?php echo base_url();?>portal/home/panduan">PANDUAN</a></li>
                            <li><a href="<?php echo base_url();?>portal/home/faq">FAQ</a></li>
                        </ul>
                    </div>
                    <div class="navbar-custom-menu navbar navbar-right" id="navbar-last">
                        <ul class="nav navbar-nav">
                             <li>
                                <?php
                                    $session_foto = $this->session->userdata('PHOTO');
                                    $real_foto = null;
                                    if($session_foto && file_exists('images/'.$session_foto)){
                                       $real_foto = base_url().'images/'.$session_foto;
                                    }else{
                                       $real_foto = base_url().'images/no_available_image.png';
                                    }
                                 ?>
                                <!--<a class="ajax-link" href="index.php/profile">-->
								<a href="<?php echo base_url();?>portal/user">
                                    <img title="Profil Saya" src="<?php echo $real_foto;?>" class="img-circle" alt="Profil  Saya">
                                </a>
                             </li>
                             <li id="info-user">
                                <ul>
								<?php
								$Jenis = role_user($this->session->userdata('ID_USER'));
                                if ($this->session->userdata('IS_KPK') == 1) {
                                    $NAMA = '';
                                }
                                else{
                                    $NAMA = $dt_satkers->UK_nama;
                                }
								if ($NAMA!=''){
								?>
								   <li><?php echo '<b>'.$this->session->userdata('NAMA').'</b> <small> ('.$Jenis.')</small>';?></li>
								   <li><?php echo '<b><small> '.$NAMA .'</small></b>';?></li>
								<?php } else { ?>
									<li><?php echo '<b>'.$this->session->userdata('NAMA').'</b> <small> ('.$Jenis.')</small>';?></li>
								<?php }?>
                                   <li><?php echo isset($dt_satker->INST_AKRONIM) ? $dt_satker->INST_AKRONIM : ""; ?></li>
                                   <li><a href="<?php echo base_url();?>index.php/auth/logout">LOGOUT</a></li>
                                </ul>
                            </li>
                        </ul>

                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar" style="padding-top:80px;">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <?php
                        echo $menu;
                    ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <div id="ModalLoading" class="modal fade" role="dialog">
                <div class='modal-dialog loader'>
                </div>
            </div>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" id="" style="background:#FCFCFC;">
                <div style="" class="lay_base header_shadow">&nbsp;</div>
                <div id="ajax-content" style="padding-top:40px;"></div>
            </div><!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b><?php echo $this->config->item('version') ?></b>
                </div>
                <strong>Copyright &copy; 2017.</strong> Komisi Pemberantasan Korupsi, All rights reserved.
                <!-- <strong>Copyright &copy; 2015.</strong> PT. MITREKA SOLUSI INDONESIA, All rights reserved. -->
            </footer>
        </div><!-- ./wrapper -->



        <!-- SlimScroll -->
        <script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <!-- FastClick -->
        <script src='<?php echo base_url(); ?>plugins/fastclick/fastclick.min.js'></script>
        <!-- AdminLTE App -->
        <script src="<?php echo base_url(); ?>plugins/AdminLTE-2.1.1/dist/js/app.min.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo base_url(); ?>plugins/AdminLTE-2.1.1/dist/js/demo.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/daterangepicker/moment.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/jquery.blockUI.js" type="text/javascript"></script>
        <!--<script src="<?php echo base_url(); ?>plugins/sweet-alert/dist/sweetalert.min.js" type="text/javascript"></script>-->
        <script src="<?php echo base_url(); ?>plugins/moment.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/jquery.form.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/klarifikasi.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/klarifikasiHelper.js" type="text/javascript"></script>
        <script type="text/javascript">
                            $(document).ready(function() {
                                $('body').find('.ajax-link').click(function() {
                                    $("html, body").animate({scrollTop: 0}, 1000);
                                    return true;
                                });

                                $(document).ajaxError(function(event, jqxhr, settings, exception) {
                                    var status = jqxhr.status;
                                    if (status == '501') { // Jika Tidak Login / Session Expired
                                        window.location.replace("<?php echo base_url(); ?>index.php");
                                    }
                                });
                            })
        </script>
        <style type="text/css">
            .slimScrollDiv{
                background-color: white;
            }
            .form-group {
                margin-bottom: 6px;
            }
            .table-bordered thead tr th {
                background-color: #F0F0F0;
                /*background-image: linear-gradient(to bottom, #F0F0F0, #DFDFDF);*/
                color: #696868;
                background-image: linear-gradient(#3c8dbc, #09C)
            }
            .table-bordered thead tr th{
                text-align: center;
                vertical-align: middle !important;
                text-transform: uppercase;
                font-weight: bold;
                color: #FFFFFF;
            }
            .table-bordered {
                border: 1px solid #DDD;
            }
            .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
                /*border: 1px solid #F8F8FF;*/
                /*border-width: inherit;*/
            }
            .modal-lg {
                width: 95%;
            }
            .navTab{
                padding: 8px 10px !important;
            }
           /*
            .sidebar-menu .treeview-menu {
                           display: block !important;
                       }*/

            .lay_base { width: 100%; height: 100%; top: 0px; left: 0px; }
            .header_shadow { background-repeat: no-repeat; bottom: 0px; background-position: center top; z-index: 10000; padding-bottom: 6px;}
            /*.header_shadow { background-image: url("<?php echo base_url(); ?>images/shadow_005.png"); }*/
            .header_shadow { display: block; }
            .content-header { margin-top: -20px; }
        </style>
        <script type="text/javascript">
            $(function() {
                $('[data-toggle="popover"]').popover()
            })
            $(function() {
                $('table.display').DataTable();
                $("#example1").dataTable();
                $('#example2').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
            });
        </script>
        <!-- The core Firebase JS SDK is always required and must be listed first -->
        <script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-app.js"></script>

        <!-- TODO: Add SDKs for Firebase products that you want to use
             https://firebase.google.com/docs/web/setup#available-libraries -->
        <script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-analytics.js"></script>

        <script>
          // Your web app's Firebase configuration
          var firebaseConfig = {
            apiKey: "AIzaSyBzcCD4M10YfuUUNv1zvh_Lpwm9lgJWgms",
            authDomain: "ultra-surfer-231709.firebaseapp.com",
            databaseURL: "https://ultra-surfer-231709.firebaseio.com",
            projectId: "ultra-surfer-231709",
            storageBucket: "ultra-surfer-231709.appspot.com",
            messagingSenderId: "635804150277",
            appId: "1:635804150277:web:71ff61f343251399deca6c",
            measurementId: "G-XCYX5T88WZ"
          };
          // Initialize Firebase
          firebase.initializeApp(firebaseConfig);
          firebase.analytics();
        </script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-29889353-15"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-29889353-15');
        </script>
<!--        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5bd8049365224c2640515235/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>-->
    </body>
</html>
