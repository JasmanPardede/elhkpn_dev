<!DOCTYPE html>
<html>
  <head>
    <title><?php if(isset($title)): echo $title; EndIf; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Waditra Reka Cipta">
    <link href="<?php echo base_url();?>portal-assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon">
	<!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/bootstrap.min.css" type="text/css">
    <!-- Custom Fonts -->
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/font-awesome/css/font-awesome.min.css" type="text/css">
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/animate.min.css" type="text/css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>portal-assets/css/remodal.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>portal-assets/css/remodal-default-theme.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/bootstrap-datetimepicker.min.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/fileinput.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/bootstrapValidator.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/select2-bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>plugins/datatables/dataTables.bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/style.css" type="text/css">
   
	 <!-- jQuery -->
    <script src="<?php echo base_url();?>portal-assets/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>portal-assets/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="<?php echo base_url();?>portal-assets/js/jquery.easing.min.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/jquery.fittext.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/wow.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url();?>portal-assets/js/creative.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/scroll.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/background.cycle.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/remodal.min.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/moment.min.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo base_url();?>portal-assets/js/prettify.js"></script>
	<script src="<?php echo base_url();?>portal-assets/js/jquery.bootstrap.wizard.js"></script>
	<script src="<?php echo base_url();?>portal-assets/js/select2.js"></script>
	<script src="<?php echo base_url();?>portal-assets/js/fileinput.min.js"></script>
	<script src="<?php echo base_url();?>portal-assets/js/jquery.sticky.js"></script>
    <script src="<?php echo base_url();?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>plugins/datatables/dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/bootstrapValidator.min.js"></script>
	<script src="<?php echo base_url();?>portal-assets/js/app.js"></script>
  </head>
  <body id="page-top">
     <nav id="mainNav" class="navbar navbar-default navbar-fixed-top ">
        <div class="container-fluid" id="navi">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                 <a class="navbar-brand page-scroll" title="LHKPN"  href="#">
                    <img src="<?php echo base_url();?>portal-assets/img/logo.png"/>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                
                <ul class="nav navbar-nav navbar-right last">
                    <li>
                        <ul>
                           <li id="tanggal"></li>
                           <li id="output"></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                             <?php
                                $session_foto = $this->session->userdata('PHOTO');
                                $real_foto = null;
                                if($session_foto && file_exists('images/'.$session_foto)){
                                   $real_foto = base_url().'images/'.$session_foto;
                                }else{
                                   $real_foto = base_url().'images/no_available_image.png';
                                }
                             ?>
                             <img src="<?php echo  $real_foto;?>" class="img-circle" alt="Profil Saya">
                        </a>
                    </li>
                    <li>
                         <ul>
                           <li><?php echo $this->session->userdata('NAMA');?></li>
                           <li><?php echo role_user($this->session->userdata('ID_USER'));?></li>
						   
                           <li><a href="<?php echo base_url(); ?>index.php/auth/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    