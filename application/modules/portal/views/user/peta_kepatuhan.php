
<!DOCTYPE html>
<!-- no cache headers -->
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="no-cache">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<!-- end no cache headers -->
<html lang="en">
    <head>
        <title>e-lhkpn</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Waditra Reka Cipta">
        <link href="<?php echo base_url(); ?>portal-assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon">



        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>portal-assets/css/bootstrap.min.css" type="text/css">


        <!-- Custom Fonts -->
        <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'> -->

        <link rel="stylesheet" href="<?php echo base_url(); ?>portal-assets/font-awesome/css/font-awesome.min.css" type="text/css">

        <!-- Plugin CSS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>portal-assets/css/animate.min.css" type="text/css">

        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>portal-assets/css/remodal.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>portal-assets/css/remodal-default-theme.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>plugins/datepicker/datepicker3.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>portal-assets/css/login.css" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/select2.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/select2-bootstrap.css" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="<?php echo base_url(); ?>portal-assets/js/modernizr.custom.js"></script>
        <!--<script src='https://www.google.com/recaptcha/api.js'></script>-->

        <style>
            #txtCaptcha-announ{
                cursor: pointer;
                height: 50px;
                position: relative;
                -webkit-transition: all .35s;
                -moz-transition: all .35s;
                transition: all .35s;
            }
            #txtCaptcha-announ:after{
                content: "\f021";
                position: absolute;
                top: 3;
                right: 2;
                font-family: 'FontAwesome','Century Gothic', Helvetica, Dosis;
            }
            #captcha-announ{
                font-weight: normal;
                font-family: 'FontAwesome','Century Gothic', Helvetica, Dosis;
            }
        </style>
    </head>

    <body id="page-top">
    <br><br>
        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid" id="nav-top">
                <div class="row">
                    <div class="col-lg-12" id="image-wrapper">
                        <a class="navbar-brand page-scroll" title="lhkpn"  href="#page-top">
                            <img src="<?php echo base_url(); ?>portal-assets/img/image2.jpeg"/>
                        </a>
                        <img  src="<?php echo base_url(); ?>portal-assets/img/logo.png" />
                    </div>
                </div>
            </div>
            <div class="container-fluid" id="nav-bottom">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <!--<a class="page-scroll" href="<?php echo base_url(); ?>portal/user/login">Beranda</a>--><br/><br/>
                        </li>
                    </ul>
                   
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>

        <section id="about">
            <div class="container">
                <div class="form-horizontal">
                    <div class="col-lg-12" style="text-align:center">
                    <?php echo $ifram_kepatuhan; ?>

                   
                    </div>
                </div>
            </div>
        </section>


        <section id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <p>
                            Gedung Merah Putih KPK - Jl. Kuningan Persada Kav. 4, Setiabudi,
                            Jakarta 12950,
                            Call Center:198,
                            Fax:(021) 2557 8413,
                            Email:elhkpn@kpk.go.id
                        </p>
                    </div>
                    <div class="col-lg-12">
                        <ul>
                            <li><a href="#"><img src="<?php echo base_url(); ?>portal-assets/img/integrito.png"/></a></li>
                            <li><a href="#"><img src="<?php echo base_url(); ?>portal-assets/img/acch.png"/></a></li>
                            <li><a href="#"><img src="<?php echo base_url(); ?>portal-assets/img/integritas.png"/></a></li>
                            <li><a href="#"><img src="<?php echo base_url(); ?>portal-assets/img/aclc.png"/></a></li>
                            <li><a href="#"><img src="<?php echo base_url(); ?>portal-assets/img/kanal_tv.png"/></a></li>
                            <li><a href="#"><img src="<?php echo base_url(); ?>portal-assets/img/wistlebower.png"/></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row copyright">
                    <div class="col-lg-12">
                        <strong>Copyright 2017 <br>  <?php echo $this->config->item('version') ?></strong>
                    </div>
                </div>
            </div>
        </section>
<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->

        <!-- jQuery -->
        <script src="<?php echo base_url(); ?>portal-assets/js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="<?php echo base_url(); ?>portal-assets/js/bootstrap.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="<?php echo base_url(); ?>portal-assets/js/jquery.easing.min.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/jquery.fittext.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/wow.min.js"></script>
        <script src="<?php echo base_url();?>portal-assets/js/select2.js"></script>
        <script src="<?php echo base_url();?>plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">    


// $(window).bind({
//   beforeunload: function(ev) {
//     window.location.replace("<?php echo base_url(); ?>Errcontroller");
//   },
//   unload: function(ev) {
//     window.location.replace("<?php echo base_url(); ?>Errcontroller");
//   }
// });
</script>
