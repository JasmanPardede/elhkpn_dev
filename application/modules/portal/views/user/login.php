
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
        <!-- <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script> -->
        <!-- <script src="https://www.google.com/recaptcha/api.js?render=6LeR4icbAAAAALNeSDWB1qz_A11ahqEuNPyrm8Jv"></script>
        <script type="text/javascript">
            var captcha1;
            var captcha_announ1;
            var myCallBack = function() {
                // alert("grecaptcha is ready!");
                //Render the recaptcha1 on the element with ID "captcha"
                captcha = grecaptcha.render('captcha', {
                'sitekey' : '6Ler104UAAAAAIy94JTYV-yLDuoklciSupbbD4-C', //Replace this with your Site key
                'theme' : 'light',
                'callback' : correctCaptcha
                });

                //Render the recaptcha2 on the element with ID "captcha_announ"
                captcha_announ = grecaptcha.render('captcha-announ', {
                'sitekey' : '6Ler104UAAAAAIy94JTYV-yLDuoklciSupbbD4-C', //Replace this with your Site key
                'theme' : 'light',
                'callback' : correctCaptchaAnnoun
                });
            };
            var correctCaptcha = function(response) {
                if(response !== '') {
                    $('#hdn_captcha').val('success');
                }
            };
            var correctCaptchaAnnoun = function(response) {
                if(response !== '') {
                    $('#hdn_captcha_announ').val('success');
                }
            };

        </script>
        -->

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
            #btn-Aktivasi {
                color: #fff;
                background-color:#337ab7;border-color:#2e6da4;
            }
            #btn-Aktivasi:hover {
                color: #fff;
                background-color:#204d74;border-color:#122b40;
            }
            .inner-addon i {
                position: absolute;
                padding: 19px;
            }
            .right-addon i {
                right: 15px; color: #777 !important;
            }
            .right-addon input {
                padding-right: 50px;
            }
            .toggle-password {
                cursor: pointer;
            }
            .btn-unduh > p > .btn {
                white-space: normal;
                margin-bottom: 5px;
                text-align: left;
	    .grecaptcha-badge {
                bottom: 15% !important;
                z-index: 1000 !important;
            }

        </style>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    </head>

    <body id="page-top">

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
                            <a class="page-scroll" href="#page-top">Login</a>
                        </li>
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="page-scroll" href="#about">Tentang e-lhkpn</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#services">Panduan Aplikasi</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#download">Unduh</a>
                        </li>
                        <li>
                            <!--<a target="_blank" href="https://acch.kpk.go.id/pengumuman-lhkpn">e-Announcement</a>-->
                            <a class="page-scroll" href="#announ">e-Announcement</a>
                        </li>
                        <li class="dropdown">
                            <a class="page-scroll dropdown-toggle" data-toggle="dropdown" href="#">Dashboard</a>
                            <ul class="dropdown-menu">
                                <li><a style="color: black;" target="_blank" href="<?php echo base_url(); ?>portal/user/monitoring_implementasi" >Monitoring Implementasi</a></li>
                                <li><a style="color: black;" target="_blank" href="<?php echo base_url(); ?>portal/user/petakepatuhan" >Peta Kepatuhan</a></li>
                                <li><a style="color: black;" target="_blank" href="<?php echo base_url(); ?>portal/user/monitoring_kepatuhan" >Monitoring Kepatuhan Pimpinan Tinggi</a></li>
                                <li><a style="color: black;" target="_blank" href="#" >WL Belum Lapor</a></li>
                                <li><a style="color: black;" target="_blank" href="#" >WL Belum Lengkap</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="page-scroll" href="#kontak">Kontak Kami</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#faq">FAQ</a>
                        </li>
                        
                    </ul>
                </div>
                
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>

        <header>
            <div class="header-content">
                <div class="header-content-inner">
                    <div class="container-fluid login">
                        <div class="row">
                            <div class="col-lg-5 col-lg-offset-3 text-center">
                                <?php if ($agent_info->msg != ""): ?>
                                    <div class="alert alert-info" style="height: inherit; font-size: 18px; justify-content: space-between; ">
                                        <?php echo $agent_info->msg; ?>
                                    </div>
                                <?php else: ?>
                                    <form id="flogin" method="POST" action="<?php echo base_url(); ?>portal/user/auth" onsubmit="updateLoginHash()" role="form">
                                        <span>
                                            <h2></h2>
                                            <h5></h5>
                                        </span>
                                        <span>
                                            <input type="text" id="usr" name="usr" class="form-control"  placeholder="Username (NIK)" required/>
                                        </span>

                                        <span>
                                            <div class="inner-addon right-addon">
                                                <i toggle="#pwd" class="fas fa-eye-slash toggle-password"></i>
                                                <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Password" required/>
                                            </div>
                                        </span>
                                        <!--<span style="overflow:hidden;">-->
                                            <!--<div id="txtCaptcha" class="form-control" style="width:49%; float:left;">-->
                                                 <!--<label><?php //echo $random_word;  ?></label> -->
                                                <!--<?php //echo $image_captcha; ?>-->
                                            <!--</div>-->
                                            <!--<input type="text" id="captcha" name="captcha" class="form-control " style="width:49%; float:right;" onkeypress="return isNotSpecialKey(event)" placeholder="Kode Captcha" required/>-->
                                        <!--</span>-->
                                        <!-- <span style="overflow:hidden;height:5.9em"> -->
                                            <!-- <div id="captcha" name="captcha" style="transform:scale(1.28, 1);-webkit-transform:scale(1.28, 1);transform-origin:0 0;-webkit-transform-origin:0px 0px"></div> -->
                                           <!--<div class="g-recaptcha" data-sitekey="6Ler104UAAAAAIy94JTYV-yLDuoklciSupbbD4-C"
                                           style="transform:scale(1.28, 1);-webkit-transform:scale(1.28, 1);transform-origin:0 0;-webkit-transform-origin:0px 0px"
                                           ></div>-->
                                        <!-- </span> -->
                                        <span>
                                            <button type="submit" class="btn btn-large btn-block btn-primary" type="button">Login</button>
                                            <input id="password_hash" name="password_hash" type="hidden" />
                                            <input id="g-recaptcha-response" name="g-recaptcha-response" type="hidden"/>
                                        </span>
                                        <span style="padding-top:5px;">
                                                <button type="button" class="btn btn-large btn-block btn-warning" data-remodal-target="modal">Lupa password</button>
<!--                                             <a id="link-login"  href="#" data-remodal-target="modal"> -->
<!--                                                 <h5>Lupa password</h5> -->
<!--                                             </a> -->
                                        </span>
                                        <span style="padding-top:5px;">
                                            <button type="button" class="btn btn-large btn-block" id="btn-Aktivasi" data-remodal-target="modalAktivasi">Kirim Ulang Aktivasi</button>
                                        </span>
                                        <span style="text-shadow: rgb(1, 1, 1) 2px 2px 1px; padding-top:12px;">
                                            <div>
                                                <a style="color:#fff;" href="mailto:elhkpn@kpk.go.id "><i class="fa fa-envelope"></i> elhkpn@kpk.go.id </a>
                                            </div>
                                            <div>
                                                <i class="fa fa-phone"></i> Call Center:198
                                            </div>
                                            <div>

                                            </div>
                                            <div>
                                                <?php echo $agent_info->msg; ?>
                                            </div>
                                        </span>
                                        <span style="text-shadow: rgb(1, 1, 1) 2px 2px 1px; padding-top:12px;">
                                            <div>
                                                Aplikasi Optimal dijalankan dengan Browser Chrome  <i class="fa fa-chrome"></i>
                                            </div>
                                        </span>
                                        <input type="hidden" name="hdn_captcha" id="hdn_captcha">
                                        <input type="hidden" name="hdn_captcha_announ" id="hdn_captcha_announ">
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section  id="about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-heading">Tentang e-lhkpn</h2>
                        <div class="row">
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <!-- <video controls controlsList="nodownload" class='index_vid'>
                                    <source src="<?= base_url(); ?>portal-assets/video/1_about_lhkpn.mp4" type="video/mp4">
                                </video> -->
                                <iframe class='index_vid' width="100%" height="315" src="https://www.youtube.com/embed/IOAUD20uB9M" frameborder="0" allowfullscreen></iframe>
                                <!-- <div class="overlayText">
                                    <div class="topText">APA ITU LAPORAN KEKAYAAN HASIL PENYELENGGARA NEGARA</div>
                                </div> -->
                            </div>
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <!-- <video controls controlsList="nodownload" class='index_vid'>
                                    <source src="<?= base_url(); ?>portal-assets/video/2_perubahan_regulasi_lhkpn.mp4" type="video/mp4">
                                </video> -->
                                <iframe class='index_vid' width="100%" height="315" src="https://www.youtube.com/embed/UhhWsQ7r1bw" frameborder="0" allowfullscreen></iframe>
                                <!-- <div class="overlayText">
                                    <div class="topText">PERUBAHAN REGULASI LHKPN</div>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <!-- <video controls controlsList="nodownload" class='index_vid'>
                                    <source src="<?= base_url(); ?>portal-assets/video/3_cari_tahu_announ.mp4" type="video/mp4">
                                </video> -->
                                <iframe class='index_vid' width="100%" height="315" src="https://www.youtube.com/embed/sN5sJ2G6z0w" frameborder="0" allowfullscreen></iframe>
                                <!-- <div class="overlayText">
                                    <div class="topText">CARI TAHU DENGAN E-ANNOUNCEMENT</div>
                                </div> -->
                            </div>
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <!-- <video controls controlsList="nodownload" class='index_vid'>
                                    <source src="<?= base_url(); ?>portal-assets/video/4_peran_serta.mp4" type="video/mp4">
                                </video> -->
                                <iframe class='index_vid' width="100%" height="315" src="https://www.youtube.com/embed/ertaHp5p91s" frameborder="0" allowfullscreen></iframe>
                                <!-- <div class="overlayText">
                                    <div class="topText">PERAN SERTA MASYARAKAT</div>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <!-- <video controls controlsList="nodownload">
                                    <source src="<?= base_url(); ?>portal-assets/video/5_peraturan_kpk_no_2_2020.mp4" type="video/mp4">
                                </video> -->
                                <iframe class='index_vid' width="100%" height="309" src="https://www.youtube.com/embed/uaTV1kf1xa0" frameborder="0" allowfullscreen></iframe>
                                <!-- <div class="overlayText" style="margin-top:0">
                                    <div class="topText">PERAN SERTA MASYARAKAT</div>
                                </div> -->
                            </div>
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <!-- <video controls controlsList="nodownload">
                                    <source src="<?= base_url(); ?>portal-assets/video/6_tata_cara_lapor.mp4" type="video/mp4">
                                </video> -->
                                <iframe class='index_vid' width="100%" height="309" src="https://www.youtube.com/embed/VvjwZqWWg0c" frameborder="0" allowfullscreen></iframe>
                                <div class="overlayText" style="margin-top:0">
                                    <div class="topText">PERAN SERTA MASYARAKAT</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-heading">Panduan Aplikasi <a href="<?php echo base_url(); ?>download/TUTORIAL_PN/file/index.html" class="btn btn-success btn-xl" id="video" target="_blank">Video Tutorial</a></h2>
                        <div class="row">
                            <div class="col-lg-6 col-md-offset-3" style="margin-bottom: 12px;">
                                <!-- <strong>Tahapan E-filling LHKPN Calon Kepala Daerah</strong> -->
                                <!--<a href="https://www.kpk.go.id/images/LHKPN/Brosur_elhkpn_2017-1.jpg" target ="_blank">-->
                                    <!--<img src="https://www.kpk.go.id/images/LHKPN/Brosur_elhkpn_2017-1.jpg" width="423" height="424" />-->
                                <!-- <a download href="<?php echo base_url(); ?>images/brosur/PILKADA_2020.jpg" target ="_blank">
                                    <img src="<?php echo base_url(); ?>images/brosur/PILKADA_2020.jpg" width="100%" height="auto" /> </a> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <iframe class='index_vid' width="100%" height="309" src="https://www.youtube.com/embed/C2aPB8Q0mDM" frameborder="0" allowfullscreen></iframe>
                                <!-- <video controls controlsList="nodownload" class='index_vid'>
                                    <source src="<?= base_url(); ?>portal-assets/video/1_panduan_alur_pendaftaran.mp4" type="video/mp4">
                                </video> -->
                                <!-- <div class="overlayText">
                                    <div class="topText">ALUR PENDAFTARAN</div>
                                </div> -->
                            </div>
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <iframe class='index_vid' width="100%" height="309" src="https://www.youtube.com/embed/GqSFE7N6S_k" frameborder="0" allowfullscreen></iframe>
                                <!-- <video controls controlsList="nodownload" class='index_vid'>
                                    <source src="<?= base_url(); ?>portal-assets/video/2_panduan_efilling.mp4" type="video/mp4">
                                </video>
                                <div class="overlayText">
                                    <div class="topText">E-FILLING</div>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <iframe class='index_vid' width="100%" height="309" src="https://www.youtube.com/embed/OOUmBv4FXIY" frameborder="0" allowfullscreen></iframe>
                                <!-- <video controls controlsList="nodownload" class='index_vid'>
                                    <source src="<?= base_url(); ?>portal-assets/video/3_panduan_ereg.mp4" type="video/mp4">
                                </video>
                                <div class="overlayText">
                                    <div class="topText">E-REGISTRATION I (KELOLA ADMIN)</div>
                                </div> -->
                            </div>
                            <div class="col-lg-6">
                                <div style="visibility: hidden;">
                                    .
                                </div>
                                <iframe class='index_vid' width="100%" height="309" src="https://www.youtube.com/embed/H08x7yabRlk" frameborder="0" allowfullscreen></iframe>
                                <!-- <video controls controlsList="nodownload" class='index_vid'> https://www.youtube.com/watch?v=OOUmBv4FXIY
                                    <source src="<?= base_url(); ?>portal-assets/video/4_panduan_ereg_2.mp4" type="video/mp4">
                                </video> -->
                                <div class="overlayText">
                                    <div class="topText">E-REGISTRATION II (KELOLA WL)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="download">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-heading">Unduh</h2>
                        <div class="row">
                            <div class="col-lg-6">
                                <p>
                                    Berdasarkan Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara sebagaimana diubah dengan Peraturan Komisi Pemberantasan Korupsi Nomor 02 Tahun 2020 tentang Perubahan atas Peraturan Komisi Pemberantasan Korupsi Nomor 07 Tahun 2016 tentang Tata Cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara bahwa terhitung tanggal 1 Januari 2017, penyampaian LHKPN mulai berlaku secara elektronik melalui aplikasi e-LHKPN. Terlampir beberapa file pendukung yang dapat diunduh.
                                </p>
<!--                                <p>
                                    <a href="<?php echo base_url(); ?>/download/Formulir_Permohonan_Aktivasi_Penggunaan_ereg.pdf" target="_blank"><u>Unduh Formulir Permohonan Aktivasi e-Registration LHKPN</u></a>
                                    <br><a href="<?php echo base_url(); ?>/download/Formulir_Permohonan_Aktivasi_Penggunaan_efiling.pdf" target="_blank"><u>Unduh Formulir Permohonan Aktivasi e-Filing LHKPN</u></a>
                                    <br><a href="<?php echo base_url(); ?>/download/panduan_pendafataran_akun_effilling.pdf" target="_blank"><u>Unduh Panduan Pendaftaran Akun e-Filing LHKPN</u></a>
                                    <br>
                                    <a href="<?php echo base_url(); ?>/download/GuidanceAkunelhkpn.pdf" target="_blank">
                                        <u>Unduh Petunjuk Teknis Pembuatan Akun Administrator</u>
                                    </a>
                                    <br />
                                    <a href="<?php echo base_url(); ?>/download/UserManuale-lhkpneksternal.pdf" target="_blank">
                                        <u>Unduh Petunjuk Teknis Aplikasi e-LHKPN</u>
                                    </a>
                                </p>-->


                            </div>
                            <div class="col-lg-6 btn-unduh">
                                <p>
                                <br>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/Formulir_Permohonan_Aktivasi_Penggunaan_efiling.pdf" target="_blank"><i class="fa fa-cloud-download"></i> 1. Unduh Formulir Permohonan Aktivasi e-Filing LHKPN</a>
                                <a class="btn btn-info" href="<?php // echo base_url(); ?>/download/Formulir_Permohonan_Aktivasi_Penggunaan_ereg.pdf" target="_blank"><i class="fa fa-cloud-download"></i> 2. Unduh Formulir Permohonan Aktivasi e-Registration LHKPN</a>
                                <br><a class="btn btn-info"  href="<?php echo base_url(); ?>/download/panduan_pendafataran_akun_effilling.pdf" target="_blank"><i class="fa fa-cloud-download"></i> 3. Unduh Panduan Pendaftaran Akun e-Filing LHKPN</a>
                                <br>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/GuidanceAkunelhkpn.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 4. Unduh Petunjuk Teknis Pembuatan Akun Administrator
                                </a>
                                <br />
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/User Manual e-lhkpn eksternal - v1.4.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 5. Unduh Petunjuk Teknis (User Manual) Aplikasi e-LHKPN
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/pileg_alur.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 6. Unduh Alur Pelaporan LHKPN untuk Pileg/Pilpres
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/Contoh Format Pengisian Form Aktivasi E-Filing CALEG DPD 2019 (1.2).pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 7. Unduh Format Pengisian Form Aktivasi e-Filing Caleg DPD 2019
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/Tata Cara Pelaporan dan Aktivasi Akun Caleg DPR,DPRD.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 8. Unduh Tata Cara Pelaporan dan Aktivasi Akun Caleg DPR/DPRD 2019
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/SE KPK No. 22 Tahun 2018.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 9. Unduh SE KPK No.22 Tahun 2018 tentang Juknis Penyampaian LHKPN Pileg<br> Tahun 2019 - 2024
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/FAQ e-LHKPN eksternal.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 10. FAQ e-LHKPN Eksternal
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>/download/Form_Permohonan_Link_API_e-Announcement.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 11. Form Permohonan Link API e-Announcement
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>download/SE KPK No. 07.1 Tahun 2020 tentang Juknis Penyampaian LHKPN Proses Pemilihan Gubernur, Bupati dan Walikota.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 12. Unduh SE KPK No. 07.1 Tahun 2020 tentang Juknis Penyampaian LHKPN<br> Proses Pemilihan Gubernur, Bupati dan Walikota
                                </a>
                                <a class="btn btn-info"  href="<?php echo base_url(); ?>download/Perkom No. 2 Tahun 2020 LHKPN.pdf" target="_blank">
                                    <i class="fa fa-cloud-download"></i> 13. Unduh Peraturan KPK RI No. 2 Tahun 2020 tentang Tata Cara Pendaftaran, Pengumuman,<br> dan Pemeriksaan Harta Kekayaan Penyelenggara Negara
                                </a>
                                </p>
                            </div>
<!--                            <div class="col-lg-6">
                                <p>
                                    Atau mengisi Formulir LHKPN format excel yang setelah diisi kemudian dikirimkan ke alamat email: <a href= "mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>

                                    <br><br>
                                    <a href="<?php echo base_url(); ?>/download/TATA CARA MELAPORKAN LHKPN FORMAT EXCEL (Jan 2018).pdf" target ="_blank"><u>Unduh Tata Cara Pelaporan Format Excel</u></a>
                                    <br><a href="<?php echo base_url(); ?>/download/FORMULIR LHKPN KPK Ver 2.1 (Only for Microsoft Excel for Windows).xlsm" target="_blank">
                                        <u>Unduh Formulir LHKPN Format Excel</u></a>
                                    <br><a href="https://www.kpk.go.id/images/LHKPN/PETUNJUK_TEKNIS_PENGISIAN_FORMULIR_18_Jan_2017.pdf" target="_blank"><u>Unduh Petunjuk Pengisian Formulir LHKPN</u></a>
                                    <br><br>
                                    Dokumen Pendukung yang harus dikirimkan setelah di tandatangani:
                                <br><ul>
                                    <li>Ringkasan LHKPN (Halaman Pertama dari Formulir)</li>
                                    <li>Surat Kuasa Mengumumkan (PN saja)</li>
                                    <li>Surat Kuasa (PN/Pasangan/Anak Dalam Tanggungan)</li>
                                </ul>
                                </p>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="kontak">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-heading">Kontak Layanan e-lhkpn</h2>
                        <div class="row">
                            <div class="col-lg-6">
                                <img src="<?php echo base_url(); ?>portal-assets/img/gedungdwiwarna.jpg" width="100%" height="auto">
                            </div>
                            <div class="col-lg-6">
                                <h4>
                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN
                                </h4>
                                <h4>
                                    Komisi Pemberantasan Korupsi
                                </h4>
                                <ul style="margin:0;padding:0;">
                                    <li style="list-style-type:none;">
                                        <i class="fa fa-map-marker"></i> Gedung Merah Putih KPK - Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta 12950
                                    </li>
                                    <li style="list-style-type:none;">
                                        <i class="fa fa-archive"></i> Call Center : 198
                                    </li>
                                    <li style="list-style-type:none;">
                                        <i class="fa fa-fax"></i> Fax : (021) 2557 8413
                                    </li>
                                    <li style="list-style-type:none;">
                                        <a style="color:#333;" href="mailto:elhkpn@kpk.go.id "><i class="fa fa-envelope"></i>  elhkpn@kpk.go.id </a>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="faq">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-heading">FAQ (Frequently Asked Questions)</h2>
                        <div class="accordion" id="accordion2">
                            <?php if (isset($faq)): ?>
                                <?php foreach ($faq as $f): ?>
                                    <div class="accordion-group">
                                        <div class="accordion-heading">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $f->ID_FAQ; ?>">
                                                <strong><?php echo $f->PERTANYAAN; ?></strong>
                                            </a>
                                        </div>
                                        <div id="collapse<?php echo $f->ID_FAQ; ?>" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <?php echo $f->JAWABAN; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php EndForeach; ?>
                                <?php EndIf; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="announ">
            <div class="container" id="announ-page">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-heading">e-Announcement</h2><br>
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo base_url(); ?>portal/user/check_search_announ#announ">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="box-body">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cari :</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama/NIK" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA" required>
                                            </div>
                                            <!--                                            <label class="col-sm-4 control-label">NHK :</label>
                                                                                        <div class="col-sm-5">
                                                                                            <input type="text" class="form-control" name="CARI[NHK]" placeholder="NHK" value="<?php echo @$CARI['NHK']; ?>" id="CARI_NHK">
                                                                                        </div>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tahun Lapor :</label>
                                            <div class="col-sm-6">
                                                <input type="text" autocomplete="off" class="year-picker form-control" name="CARI[TAHUN]"  onkeydown="return false"  placeholder="Semua Tahun" value="<?php echo @$CARI['TAHUN']; ?>" id="CARI_TAHUN">
                                            </div>
                                            <div class="col-sm-3">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Lembaga :</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA']; ?>" id="CARI_LEMBAGA">
                                            </div>
                                        </div>
                                    </div>
                                    <input id="g-recaptcha-response-announ" name="g-recaptcha-response-announ" type="hidden"/>
                                    <!-- <div class="row"> -->
                                        <!-- <div class="form-group"> -->
                                            <!-- <label class="col-sm-4 control-label">Kode Keamanan :</label> -->
                                            <!--<span style="overflow:hidden;">-->
                                                <!--<div id="txtCaptcha-announ" class="col-sm-3" style="width:49%; float:left; color: gray">-->
                                                    <!-- <label><?php //echo $random_word;  ?></label> -->
                                                    <!--<?php //echo $image_captcha_announ; ?>-->

                                                <!--</div>-->
                                            <!--</span>-->
                                            <!--<span style="overflow:hidden;">-->
                                                <!--<div id="txtCaptcha"  class="form-control" style="width:100%; height:100px; float:left;">-->
                                                    <!--<label><?php //echo $random_word;  ?></label> -->
                                                    <!--<?php// echo $image_captcha; ?>-->
                                                <!--</div>-->
                                            <!--<input type="text" id="captcha" name="captcha" class="form-control " style="width:49%; float:right;" onkeypress="return isNotSpecialKey(event)" placeholder="Kode Captcha" required/>-->
                                            <!--</span>-->
                                            <!-- <span style="overflow:hidden;"> -->
                                                <!-- <div id="captcha-announ" name="captcha-announ" class="col-sm-3" style="transform:scale(0.9);-webkit-transform:scale(0.9);transform-origin:0 0;-webkit-transform-origin:0 0;"></div> -->
                                                <!--<div id="captcha-announ" class="col-sm-3 g-recaptcha"
                                                style="transform:scale(1);-webkit-transform:scale(1);transform-origin:0 0;-webkit-transform-origin:0px 0px">
                                                </div>-->
                                            <!-- </span> -->
                                        <!-- </div> -->
                                    <!-- </div> -->



                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"></label>
                                            <div class="col-sm-3">
                                                <!--<input type="text" id="captcha-announ" name="captcha-announ" class="form-control " placeholder="Kode Captcha" required/>-->
                                            </div>
                                            <div class="col-sm-5" style="padding-left: 38px;">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                                                    <button type="button" id="btn-clear" class="btn btn-info"> Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /*
                                        <p style="font-size:12px;">
                                        <br>
                                        Daftar Wajib LHKPN (WL) Belum Lapor dan Belum Lengkap dapat diakses dengan<br>cara klik pada menu di bawah ini : <br>
                                        <a href="" target="_blank"><strong>1. WL Belum Lapor</strong></a><br>
                                        <a href="" target="_blank"><strong>2. WL Belum Lengkap</strong></a>
                                        </p>
                                        */ ?>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <p style="font-size:12px;">
                                        *Informasi Pengumuman Harta Kekayaan Penyelenggara Negara yang tercantum dalam situs e-Announcement LHKPN ini adalah sesuai dengan yang telah dilaporkan oleh Penyelenggara Negara dalam LHKPN dan hanya untuk tujuan informasi umum. KPK tidak bertanggung jawab atas informasi Harta Kekayaan Penyelenggara Negara yang bersumber dari situs dan/atau media lainnya. Apabila terdapat perbedaan informasi antara pengumuman yang tercantum dalam situs e-Announcement dengan informasi yang berasal dari situs dan/atau media lainnya, maka informasi yang dianggap valid adalah informasi yang tercantum dalam situs e-Announcement ini.
                                        <!-- <br><br><strong>Situs ini hanya menampilkan Pengumuman Harta Kekayaan Penyelenggara Negara atas LHKPN yang disampaikan kepada KPK dengan menggunakan Aplikasi e-LHKPN (dimulai dari LHKPN Tahun 2017 dan seterusnya).</strong> Informasi Pengumuman Harta Kekayaan Penyelenggara Negara atas LHKPN yang menggunakan Formulir LHKPN Model KPK-A dan Model KPK-B tetap dapat diakses melalui <a style="color:#333;background:#87CEFA;border-radius:5px;padding:3px 10px;text-decoration:none;" target="_blank" href="https://acch.kpk.go.id/pengumuman-lhkpn/"><strong>acch.kpk.go.id/pengumuman-lhkpn</strong></a> -->
                                        <br><br>Situs ini menampilkan <strong>Pengumuman Harta Kekayaan Penyelenggara Negara</strong> atas LHKPN yang disampaikan kepada KPK menggunakan <strong>Aplikasi e-LHKPN</strong> (dimulai dari LHKPN Tahun 2017 dan seterusnya) dan menggunakan <strong>Formulir LHKPN Model KPK-A dan Model KPK-B.</strong>
                                        <br><br>Situs ini hanya menampilkan Perbandingan Pengumuman Harta Kekayaan Penyelenggara Negara atas LHKPN yang disampaikan kepada KPK menggunakan Aplikasi e-LHKPN (dimulai dari LHKPN Tahun 2018 dan seterusnya).
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                <thead>
                                    <tr>
                                        <!--<th width="30"><input type="checkbox" onClick="chk_all(this);" /></th>-->
                                        <th width="30">No.</th>
                                        <!-- <th>No. Agenda</th> -->
                                        <th>Nama</th>
                                        <th>Lembaga</th>
                                        <th>Unit Kerja</th>
                                        <th>Jabatan</th>
                                        <th width="140">Tanggal Lapor</th>
                                        <!--<th class="hidden-xs hidden-sm">Jenis Laporan</th>-->
                                        <th>Jenis Laporan</th>
                                        <th>Total Harta Kekayaan</th>
                                        <th width="125">Aksi</th>
                                    <!-- <th>Aksi</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($total_rows) { ?>
                                        <?php
                                        $i = 0 + $offset;
                                        $start = $i + 1;
                                        $aId = @explode(',', $id);
                                        $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
                                        $aStatus = ['0' => 'Draft', '1' => 'Masuk', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi Lengkap', '4' => 'Diumumkan Lengkap', '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Ditolak'];
                                        $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
                                        $jenisLaporan = ['1' => 'Khusus, Calon PN', '2' => 'Khusus, Awal Menjabat', '3' => 'Khusus, Akhir Menjabat', '4' => 'Periodik', '5' => 'Klarifikasi'];
                                        foreach ($items as $item) {
                                            $agenda = date('Y', strtotime($item->tgl_kirim_final)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : ($item->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                                            ?>
                                            <tr>
                                                <!-- <td class="agenda" style="display: none;"><?php date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN ?></td> -->
                                                <td class="lhkpn" style="display: none;"><?php echo substr(md5($item->ID_LHKPN), 5, 8); ?></td>
                                                <td class="lhkpnori" style="display: none;"><?php echo $item->ID_LHKPN; ?></td>
                                                <td class="nik" style="display: none;"><?php echo $item->NIK; ?></td>
                                                <td class="tgl_lapor" style="display: none;"><?php echo date('Y', strtotime($item->tgl_lapor)); ?></td>
                                                <td class="jenis_laporan" style="display: none;"><?php echo $item->JENIS_LAPORAN == '4' ? 'R' : ($item->JENIS_LAPORAN == '5' ? 'P' : 'K'); ?></td>
                                                <!--<td> <?php echo (in_array($item->ID_LHKPN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="' . $item->ID_LHKPN . '" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="' . $item->ID_LHKPN . '" onclick="chk(this);" />') ?></td>-->
                                                <td><?php echo ++$i; ?>.</td>
            <!--                                    <td class="agenda">
                                                <?php // echo $agenda; ?></a>
                                                </td>-->
                                                <td><?php echo $item->NAMA; ?></a></td>
                                                <td><?php echo $item->INST_NAMA; ?></td>
                                                <td><?php echo $item->UK_NAMA; ?></td>
                                                <td><?php echo $item->NAMA_JABATAN; ?></td>
                                                <td><?php echo tgl_format($item->tgl_lapor); ?></td>
                                                <td><?php echo $jenisLaporan[$item->JENIS_LAPORAN]; ?></td>
                                                <td><?php echo ' Rp.' . number_rupiah($item->T1+$item->T2+$item->T3+$item->T4+$item->T5+$item->T6-$item->T7);?></td>
                                        <!--<td class="hidden-xs hidden-sm"><?php // echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus';  ?></td>-->
            <!--                                    <td >
                                                <?php
                    //        if (@$item->STATUS == '4') {
                    //            echo 'Diumumkan Lengkap';
                    //        } else if (@$item->STATUS == '6') {
                    //            echo 'Diumumkan Tidak Lengkap';
                    //        }
                                                ?>
                                        </td>-->
                                                    <td>
                                                    <?php if ($this->session->userdata('ceksesi')) { ?>
                                                    <a id="DownloadPDFII" title="Preview cetak pengumuman" class="btn btn-sm btn-success nodownl" data-id="<?php echo encrypt_username($item->ID_LHKPN); ?>" ><i class="fa fa-download"></i></a>
                                                    <a class="btn btn-danger btn-sm" title="Kirim Informasi Harta" href="<?php echo base_url(); ?>portal/user/pelaporan/<?php echo encrypt_username(json_encode(array($item->ID_LHKPN,$item->NAMA,$item->INST_NAMA,$item->NAMA_JABATAN))); ?>/<?php echo protectUrl(); ?>" ><i class="fa fa-bullhorn" style="color:white;"></i></a>
                                                    <?php } else { ?>
                                                    <a id="DownloadPDFII" title="Preview cetak pengumuman" class="btn btn-sm btn-success yesdownl" data-id="<?php echo encrypt_username($item->ID_LHKPN); ?>" ><i class="fa fa-download"></i></a>
                                                    <a class="btn btn-danger btn-sm" title="Kirim Informasi Harta" href="<?php echo base_url(); ?>portal/user/pelaporan/<?php echo encrypt_username(json_encode(array($item->ID_LHKPN,$item->NAMA,$item->INST_NAMA,$item->NAMA_JABATAN))); ?>/<?php echo protectUrl(); ?>" ><i class="fa fa-bullhorn" style="color:white;"></i></a>
                                                    <?php } ?>
                                                    <?php if ($item->entry_via == 0 && (date('Y', strtotime($item->tgl_lapor)) >= 2018)) { ?>
                                                    <a  title="Perbandingan e-Announcement LHKPN" class="btn btn-sm btn-info perbandingan-announcement" data-id="<?php echo encrypt_username($item->ID_LHKPN); ?>" data-harta="<?php echo base_url() . 'portal/user/compare_harta/' . encrypt_username($item->ID_LHKPN); ?>" data-toggle="modal" data-target="#modal-perbandingan-announcement-lhkpn"><i class="far fa-copy"></i></a>
                                                    <?php } ?>
                                                    </td>
                                            </tr>
                                            <?php
                                            $end = $i;
                                        }
                                        ?>
                                    <?php } else { ?>
                                        <tr id="not-found">
                                            <td colspan="9" align="center"><strong>Belum ada data</strong></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!--<tr id="paging">-->
                        <!-- Main content -->
                            <!--<section class="content">-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <?php // echo $content_list; ?>
                                    <?php echo $content_paging; ?>
                                </div><!-- /.box -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        <!--</section> /.content -->

                        <?php echo $content_js; ?>


                    </div><!-- /.box-body -->
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

        <div class="remodal" data-remodal-id="modal-announ">
            <button data-remodal-action="close" class="remodal-close"></button>
            <h3>Siapakah Anda?</h3>
            <form id="f-announ">
                <div class="box-body">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nama :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="email-announ" placeholder="Masukan nama anda" id="email-announ" required>
                                    <input type="hidden" id="id_lhkpn_announ" name="id_lhkpn_announ" value="<?php echo encrypt_username($id_lhkpn); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Umur :</label>
                                <div class="col-sm-7" align="left">
                                    <input type="radio" name="umur-announ" id="umur-announ" value="18-24"> 18-24 <br>
                                    <input type="radio" name="umur-announ" id="umur-announ" value="25-34"> 25-34 <br>
                                    <input type="radio" name="umur-announ" id="umur-announ" value="35-44"> 35-44 <br>
                                    <input type="radio" name="umur-announ" id="umur-announ" value="45-54"> 45-54 <br>
                                    <input type="radio" name="umur-announ" id="umur-announ" value="55+"> 55+
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Profesi :</label>
                                <div class="col-sm-7" align="left">
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="pn"> Pegawai Negeri <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="media"> Media / Pers <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="akademisi"> Akademisi <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="lsm"> LSM <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="mu"> Masyarakat Umum
                                </div>
                            </div>
                        </div>
                        <span id="f-button-announ">
                            <a class="btn btn-success announ-download">Download</a>
                            <!--<button class="btn btn-success announ-download">Download</button>-->
                            <button data-remodal-action="cancel" class="btn btn-danger">Batal</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <div class="remodal" data-remodal-id="modal">
            <button data-remodal-action="close" class="remodal-close"></button>
            <h3>Lupa Password</h3>
            <form id="f-forget">
                <span id="f-input">
                    <input type="text" name="nik" id="input-email" placeholder="Silahkan masukan Username/NIK anda dengan benar" required/>
                </span>
                <span id="f-input">
                    <input type="email" name="email" id="input-email" placeholder="Silahkan masukan email anda dengan benar" required/>
                </span>
                <span id="f-progress">
                    <div class="progress">
                        <div id="progress_widget" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                            Sedang mengirim data....
                        </div>
                    </div>
                </span>
                <span id="f-message">
                    <div class="alert alert-success" id="berhasil">
                        Permintaan untuk mereset password berhasil dikirim , silahkan cek kembali email Anda.
                    </div>
                    <div class="alert alert-danger" id="gagal_sistem">
                        Mohon maaf, ada kesalahan jaringan/sistem. silahkan dicoba lagi sesaat kemudian.
                    </div>
                    <div class="alert alert-danger" id="gagal_database">
                        Data NIK atau Email yang anda masukkan tidak sesuai, silahkan cek kembali data yang Anda masukkan.
                    </div>
                    <div class="alert alert-danger" id="gagal_aktivasi">
                        Akun ini belum diaktivasi, silahkan melakukan aktivasi melalui URL yang telah dikirimkan ke email yang telah didaftarkan.
                    </div>
                </span>
                <span id="f-button">
                    <button class="remodal-confirm">Kirim</button>
                    <button data-remodal-action="cancel" class="remodal-cancel">Batal</button>
                </span>
            </form>
        </div>

        <div class="remodal" data-remodal-id="modalAktivasi">
            <button data-remodal-action="close" class="remodal-close"></button>
            <h3>Kirim Ulang Aktivasi</h3>
            <form id="fa-aktivasi">
                <span id="fa-input">
                    <input type="text" name="nik" id="input-email" placeholder="Silahkan masukan Username/NIK anda dengan benar" required/>
                </span>
                <span id="fa-input">
                    <input type="email" name="email" id="input-email" placeholder="Silahkan masukan email anda dengan benar" required/>
                </span>
                <span id="fa-progress">
                    <div class="progress">
                        <div id="progress_widget" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                            Sedang mengirim data....
                        </div>
                    </div>
                </span>
                <span id="fa-message">
                    <div class="alert alert-success" id="berhasil_a">
                        Aktivasi berhasil dikirim ulang, silahkan cek kembali email Anda.
                    </div>
                    <div class="alert alert-danger" id="gagal_sistem_a">
                        Mohon maaf, ada kesalahan jaringan/sistem. silahkan dicoba lagi sesaat kemudian.
                    </div>
                    <div class="alert alert-danger" id="gagal_database_a">
                        Data NIK atau Email yang anda masukkan tidak sesuai, silahkan cek kembali data yang Anda masukkan.
                    </div>
                    <div class="alert alert-danger" id="gagal_aktivasi_a">
                        Email aktivasi tidak dapat dikirim dikarenakan Akun sudah aktif, silahkan menggunakan fitur Reset Password.
                    </div>
                </span>
                <span id="fa-button">
                    <button class="remodal-confirm">Kirim</button>
                    <button data-remodal-action="cancel" class="remodal-cancel">Batal</button>
                </span>
            </form>
        </div>

        <?php $error_message = $this->session->flashdata('error_message'); ?>
        <?php if ($error_message): ?>
            <div class="remodal" style="background:none;" data-remodal-id="modal-message">
                <div class="alert alert-error">
                    <button type="button" data-remodal-action="close" class="close" >&times;</button>
                    <div id="pesan-warning">
                        <i class="fa fa-warning"></i> <?php echo $this->session->flashdata('message'); ?>
                    </div>
                </div>
            </div>
            <?php EndIf; ?>

        <div class="remodal"  data-remodal-id="modal-confirm">
            <button data-remodal-action="close" class="remodal-close"></button>
            <p style="text-align:center">
                Email anda telah dikonfirmasi, silahkan login untuk melanjutkan.
            </p>
        </div>

        <div class="remodal"  data-remodal-id="modal-waiting">
            <button data-remodal-action="close" class="remodal-close"></button>
            <p style="text-align:center; font-size: 20px; color: green;">
                Terima kasih telah mengunduh berkas LHKPN ini, <br>berkas akan terunduh dalam <span id="timer" style="display:inline; padding-top:0px;"></span> detik.
            </p>
        </div>

        <div class="remodal remodal-one" data-remodal-id="modal-notice">
            <button data-remodal-action="close" class="remodal-close"></button>
            <div id="notice">
                <h2>Pengumuman Terbaru</h2>
                <?php if (isset($pengumuman)): echo $pengumuman;
                EndIf; ?>
            </div>
        </div>

        <div class="remodal remodal-two" data-remodal-id="modal-notice-two" style="max-width:100%; background: none">
            <button data-remodal-action="close" class="remodal-close"></button>
            <div id="notice">
                        <div class="row">
                            <div class="col-md-6 remodal-image">
                                <a href="#page-top" class="anchor-login" role="button">
                                    <img src="<?php echo base_url(); ?>portal-assets/img/lhkpn_merah.jpg">
                                    <!-- <div class="overlay-image">LAPOR LHKPN</div> -->
                                </a>
                            </div>
                            <div class="col-md-6 remodal-image">
                                <a href="#announ" class="anchor-eannoun" role="button">
                                    <img src="<?php echo base_url(); ?>portal-assets/img/announ_putih2.jpg">
                                    <!-- <div class="overlay-image">PENGUMUMAN LHKPN</div> -->
                                </a>
                            </div>
                        </div>
            </div>
        </div>

        <div class="modal fade" id="modal-perbandingan-announcement-lhkpn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document" style="width: 80%; margin-left: auto; margin-right: auto;">
                <div class="modal-content" style="-webkit-border-radius: 0px !important; -moz-border-radius: 0px !important; border-radius: 0px !important;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">PERBANDINGAN E-ANNOUNCEMENT LHKPN</h4>
                    </div>
                    <div class="modal-body">
                        <div class="rows text-center" style="margin-bottom: 30px;">
                            <div><strong>PENGUMUMAN</strong></div>
                            <div><strong>LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</strong></div>
                            <div class='tahun_lhkpn'><strong>(Tanggal Penyampaian/Jenis Laporan - Tahun: )</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-2"><strong>NAMA</strong></div>
                            <div class="col-xs-8 col-sm-8 col-md-10 nama_lengkap">: </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-2"><strong>LEMBAGA</strong></div>
                            <div class="col-xs-8 col-sm-8 col-md-10 lembaga">: </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-2"><strong>UNIT KERJA</strong></div>
                            <div class="col-xs-8 col-sm-8 col-md-10 unit_kerja">: </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-2"><strong>SUB UNIT KERJA</strong></div>
                            <div class="col-xs-8 col-sm-8 col-md-10 sub_unit_kerja">: </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-2"><strong>JABATAN</strong></div>
                            <div class="col-xs-8 col-sm-8 col-md-10 jabatan">: </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-2"><strong>NHK</strong></div>
                            <div class="col-xs-8 col-sm-8 col-md-10 nhk">: </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-2"><strong>PERBANDINGAN LHKPN</strong></div>
                            <input type="hidden" id="id_lhkpn_1" value="">
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <select class="form-control lhkpn_options">
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info bandingkan"><b>Bandingkan</b></button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <small><i>* Situs ini hanya menampilkan Perbandingan Pengumuman Harta Kekayaan Penyelenggara Negara atas LHKPN yang disampaikan kepada KPK menggunakan Aplikasi e-LHKPN (dimulai dari LHKPN Tahun 2018 dan seterusnya).</i></small>
                            </div>
                        </div>
                        <div class="table-responsive" style="margin-top: 10px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="vertical-align: middle;">I.</th>
                                        <th colspan="4" rowspan="2" style="vertical-align: middle;">DATA HARTA</th>
                                        <th class="text-center" rowspan="2" style="vertical-align: middle;">Pelaporan LHKPN<br>
                                        <div class="lhkpn_1"></div></th>
                                        <th class="text-center" rowspan="2" style="vertical-align: middle;">Pelaporan LHKPN<br><div class="lhkpn_2"></div></th>
                                        <th class="text-center" colspan="2">Kenaikan / (penurunan)</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">%</th>
                                    </tr>
                                </thead>
                                <tbody class="data_perbandingan_lhkpn">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info cetak-perbandingan" style="margin-right:10px;" disabled="true" data-cetaklhkpn1="" data-cetaklhkpn2=""><i class="fa fa-file-pdf"></i><b> Cetak</b></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modal-announcement-notif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        Perbandingan laporan harta minimal tahun 2018
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- MD5 JavaScript -->
        <script type="text/javascript" src="<?php echo base_url(); ?>portal-assets/js/jquery.md5.js"></script>

        <!-- Custom Theme JavaScript -->
        <script type="text/javascript">

            function updateLoginHash() {
                    $.ajax({
                        url: "<?php echo base_url(); ?>portal/user/get_salt",
                        type: 'GET',
                        data: {
                            user : $('#usr').val(),
                            pswd : $.md5($('#pwd').val())
                            },
                        async: false,
                        cache: false,
                        success: function(password_salt) {
                            if(password_salt == 1){
                                var pwd = $('#pwd').val();
                                $('#pwd').val(pwd);
                                $('#password_hash').val('');
                            }else{
                                $('#pwd').val('');
                                $('#password_hash').val(password_salt);
                            }
                        }
                    });
                }

            var img_url = '<?php echo base_url(); ?>portal-assets/';
            var base_url = '<?php echo base_url(); ?>';
            function reload_captcha() {
                $.post(base_url + "index.php/auth/reload_captcha").done(function(msg) {
                    var result = JSON.parse(msg);
                    $('#img_captcha').html(result.image);
                    $('#hdn_captcha').val(result.value);
                    $('#hdn_captcha_announ').val(result.value);
                    $('#txtCaptcha').html(result.image);
                    $('#txtCaptcha-announ').html(result.image);
                });
            }

            function isNotSpecialKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (!( (charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) ) && charCode != 8 && charCode != 13)
                    return false;
                return true;
            }
        </script>
        <script src="<?php echo base_url(); ?>portal-assets/js/creative.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/background.cycle.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/scroll.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/remodal.min.js"></script>
        <!--<script src="<?php echo base_url(); ?>js/ngv2.js"></script>-->
        <script src="<?php echo base_url(); ?>plugins/AdminLTE-2.1.1/dist/js/demo.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datepicker/bootstrap-datepicker.js"></script>
        <script type="text/javascript">

            function timer_download(id_lhkpn, urll) {
//                alert(id_lhkpn+'=id lhkpn');return false;
                $.post('<?php echo base_url(); ?>portal/user/setid/', {id: id_lhkpn}, function(data) {
                    var win = window.open('<?php echo base_url(); ?>portal/user/timer/', '_blank');
                    if (win){
                        win.focus();
                        location.href = "check_search_announ#announ";
                        location.reload(true);
                    }else{
                        alert('Please allow popups for this website');
                    }
                });
            }

            function yes_download(id_lhkpn_announ) {
                $.post('<?php echo base_url(); ?>portal/user/setid/', {id: id_lhkpn_announ}, function(data) {
                    var win = window.open('<?php echo base_url(); ?>portal/user/timer/', '_blank');
                    // var win = window.open('<?php echo base_url(); ?>portal/user/PreviewAnnoun/', '_blank');
                    if (win){
                        win.focus();
                        location.href = "check_search_announ#announ";
                        location.reload(true);
                    }else{
                        alert('Please allow popups for this website');
                    }
                });
            }

            $(document).ready(function() {
                function playFile() {
                    $(".index_vid").not(this).each(function () {
                        $(this).get(0).pause();
                    });
                    this[this.get(0).paused ? "play" : "pause"]();
                }

                $('.index_vid').on("click play", function() {
                    playFile.call(this);
                });
            
            //// show or hide password ////
            $(".toggle-password").click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $($(this).attr("toggle"));

                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

            var options = { hashTracking: false };
            var inst = $('[data-remodal-id=modal-notice]').remodal(options);
            var OpUrl = window.location.href.indexOf("login");
            if (OpUrl > -1){
                $('.remodal-one').on('closed', function () {
                    var notice2 = $('[data-remodal-id=modal-notice-two]').remodal();
                    notice2.open();
                    $('.anchor-eannoun, .anchor-login').on('click', function () {
                        notice2.close();
                    });
                });
                inst.open();
            }
                $('.year-picker').datepicker({
                    orientation: "left",
                    format: 'yyyy',
                    viewMode: "years",
                    minViewMode: "years",
                    autoclose: true
                });

                $("#ajaxFormCari").submit(function(e) {
                  e.preventDefault();

                  var stateNama = $('#CARI_NAMA').val();
                  var stateLembaga = $('#CARI_LEMBAGA').val();
                  var minLength = 4;
                  if(stateNama==" " || stateNama=="  " || stateNama=="   "  || stateNama==null){
                    alert('Field Nama/NIK masih kosong!');
                    return true;
                  }
                  if (stateNama.length < minLength) {
                    alert('Field Nama/NIK minimal 4 karakter!');
                    return false;
                  }
                //   if(stateLembaga=="" || stateLembaga==" "){
                //     e.preventDefault();
                //     alert('Lembaga belum dipilih!');
                //     return true;
                //   }

                    /*
                    grecaptcha.ready(function() {
                        grecaptcha.execute('6LeR4icbAAAAALNeSDWB1qz_A11ahqEuNPyrm8Jv', {action: 'announcement'}).then(function(token) {
                            document.getElementById("g-recaptcha-response-announ").value = token;
                            document.getElementById("ajaxFormCari").submit();
                        });
                    });
                    */

                    var url = $(this).attr('action');
                    ng.LoadAjaxContentPost(url, $(this));
                    return false;
                });


                $('#btn-clear').click(function(event) {
                    $('#ajaxFormCari').find('input:text').val('');
                    $('#ajaxFormCari').find('select').val('');
                    $('#ajaxFormCari').trigger('submit');
                });

                $(".yesdownl").on("click", (function() {
                    var id_lhkpn = $(this).data('id');
                    var options = {hashTracking: false};
                    var inst = $('[data-remodal-id=modal-announ]').remodal(options);
                    inst.open();
                    $("#id_lhkpn_announ").val(id_lhkpn);
                }));

                $(".announ-download").on("click", (function() {
                    var email = $('#email-announ').val();
                    var umur = $('#umur-announ:checked').val();
                    var profesi = $('#profesi-announ:checked').val();
                    if (email == '') {
                        alert('Terdapat inputan yang belum terisi atau salah!!');
                        return false;
                    }else if(umur == null){
                      alert('Inputan umur belum terisi!!');
                      return false;
                    }else if(profesi == null){
                      alert('Inputan profesi belum terisi!!');
                      return false;
                    }else {
                        var options = {hashTracking: false};
                        var inst = $('[data-remodal-id=modal-announ]').remodal(options);
                        var urll = 'announ_user';
                        var id_lhkpn_announ = $('#id_lhkpn_announ').val();
                        yes_download(id_lhkpn_announ);
//                        window.open('<?php echo base_url(); ?>portal/user/PreviewAnnoun/'+id_lhkpn_announ,'_blank');
                        inst.close();
                        $.ajax({
                            url: urll,
                            async: true,
                            type: 'POST',
                            data: $('#f-announ').serialize(),
                            success: function(htmldata) {
//                                if (htmldata){
//                                    location.href="check_search_announ#announ";
//                                    location.reload(true);
//                                }
                            }
                        });
                    }
                }));

                $(".nodownl").on("click", (function() {
                    var id_lhkpn = $(this).data('id');
                    var urll = "<?php echo base_url(); ?>portal/user/timer/";
                    timer_download(id_lhkpn, urll)
                }));


                    $('#CARI_LEMBAGA').select2({
                        minimumInputLength: 0,
                        ajax: {
                        url: "<?=base_url('portal/user/getLembaga')?>",
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (term, page) {
                            return {
                                q: term
                            };
                        },
                        results: function (data, page) {
                            return { results: data.item };
                        },
                        cache: true
                        },
                        initSelection: function(element, callback) {
                        var id = $(element).val();
                        if (id !== "") {
                            $.ajax("<?=base_url('portal/user/getLembaga')?>/"+id, {
                                dataType: "json"
                            }).done(function(data) { callback(data[0]); });
                        }
                        },
                        formatResult: function (state) {
                        return state.name;
                        },
                        formatSelection:  function (state) {
                        return state.name;
                        }
                    });
                  //   LEMBAGA = $('#CARI_LEMBAGA').val();
                  //   $('#CARI_LEMBAGA').change(function(event) {
                  //     // var data = $('your-original-element').select2('data');
                  //   var stateLembaga = $(this).select2('data');
                  //   LEMBAGA = stateLembaga.name;
                  //   console.log(LEMBAGA);
                  // });

                $('.perbandingan-announcement').click(function() {
                    var lhkpn_1 = $(this).data('id');
                    $.ajax({
                        url: "<?php echo base_url(); ?>portal/user/compare_harta/" + lhkpn_1,
                        dataType: 'json',
                        beforeSend: function(){
                            setCookie('iwanibe_token', '<?= hash('sha256', 'iwankeren'.strtotime(date('Y-m-d H:i'))); ?>', 10);
                        },
                        complete: function(){
                            // Loading('hide');
                        },
                        success: function(res){
                            if (res.pn.entry_via != 0) {
                                $('#modal-announcement-notif').modal('show');
                                $('#modal-perbandingan-announcement-lhkpn').modal('hide');
                            }
                            if (res.pn != null) {

                                $(".tahun_lhkpn").html(
                                    "<strong>"
                                    + "(Tanggal Penyampaian/Jenis Laporan - Tahun: "
                                    + formattingDate(res.pn['tgl_kirim_final'])
                                    +  "/" + res.pn['DESKRIPSI_JENIS_LAPORAN']
                                    + ")</strong>"
                                );
                                $(".nama_lengkap").html(res.pn['NAMA'] ? ": " + res.pn['NAMA'].toUpperCase().bold() : "<b>: - </b>");
                                $(".lembaga").html(res.pn['INST_NAMA'] ? ": " + res.pn['INST_NAMA'].toUpperCase().bold() : "<b>: - </b>");
                                $(".unit_kerja").html(res.pn['UK_NAMA'] ? ": " + res.pn['UK_NAMA'].toUpperCase().bold() : "<b>: - </b>");
                                $(".sub_unit_kerja").html(res.pn['SUK_NAMA'] ? ": " + res.pn['SUK_NAMA'].toUpperCase().bold() : "<b>: - </b>");
                                $(".jabatan").html(res.pn['NAMA_JABATAN'] ? ": " + res.pn['NAMA_JABATAN'].toUpperCase().bold() : "<b>: - </b>");
                                $(".nhk").html(res.pn['NHK'] ? ": " + res.pn['NHK'].toUpperCase().bold() : "<b>: - </b>");

                                html_options = '<option></option>';
                                    $.each(res.options, function(i, option_val){
                                        html_options += '<option value=' + option_val.ID_LHKPN + '>';
                                        html_options += 'LHKPN ' + new Date(option_val.TGL_LAPOR).getFullYear() + ' (' + formattingDate(option_val.TGL_LAPOR) +')';
                                        html_options += '</option>';
                                    });

                                $('.lhkpn_options').html(html_options);
                                $('.bandingkan').prop('disabled', true);
                                $('.lhkpn_options').change(function() {
                                    if($(this).val() != '' && res.pn.entry_via == 0) {
                                        $('.bandingkan').prop('disabled', false);
                                    } else {
                                        $('.bandingkan').prop('disabled', true);
                                    }
                                });
                                $(".lhkpn_1").html('' + formattingDate(res.pn['tgl_lapor']));
                                $("#id_lhkpn_1").val(lhkpn_1);

                                //data lhkpn_1
                                total_htb_lhkpn1 = res.data_harta.dhtb.summary[lhkpn_1] ? res.data_harta.dhtb.summary[lhkpn_1] : 0;
                                html_total_htb_lhkpn = '<tr><th></th><th>A.</th><th colspan="2">TANAH DAN BANGUNAN</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_htb_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                total_hb_lhkpn1 = res.data_harta.dhb.summary[lhkpn_1] ? res.data_harta.dhb.summary[lhkpn_1] : 0;
                                html_total_hb_lhkpn = '<tr><th></th><th>B.</th><th colspan="2">ALAT TRANSPORTASI DAN MESIN</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hb_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                total_hbl_lhkpn1 = res.data_harta.dhbl.summary[lhkpn_1] ? res.data_harta.dhbl.summary[lhkpn_1] : 0;
                                html_total_hbl_lhkpn = '<tr><th></th><th>C.</th><th colspan="2">HARTA BERGERAK LAINNYA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hbl_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                total_hsb_lhkpn1 = res.data_harta.dhsb.summary[lhkpn_1] ? res.data_harta.dhsb.summary[lhkpn_1] : 0;
                                html_total_hsb_lhkpn = '<tr><th></th><th>D.</th><th colspan="2">SURAT BERHARGA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hsb_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                total_hk_lhkpn1 = res.data_harta.dhk.summary[lhkpn_1] ? res.data_harta.dhk.summary[lhkpn_1] : 0;
                                html_total_hk_lhkpn = '<tr><th></th><th>E.</th><th colspan="2">KAS DAN SETARA KAS</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hk_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                total_hl_lhkpn1 = res.data_harta.dhl.summary[lhkpn_1] ? res.data_harta.dhl.summary[lhkpn_1] : 0;
                                html_total_hl_lhkpn = '<tr><th></th><th>F.</th><th colspan="2">HARTA LAINNYA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hl_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                sub_total_harta_kekayaan_lhkpn1 = total_htb_lhkpn1 + total_hb_lhkpn1 + total_hbl_lhkpn1 + total_hsb_lhkpn1 + total_hk_lhkpn1 + total_hl_lhkpn1;
                                html_sub_total_harta_kekayaan_lhkpn = '<tr><th></th><th></th><th colspan="2">Sub Total</th><th>Rp</th><th class="text-right">'+formatMoney(sub_total_harta_kekayaan_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                total_h_lhkpn1 = res.data_harta.dh.summary[lhkpn_1] ? res.data_harta.dh.summary[lhkpn_1] : 0;
                                html_total_h_lhkpn = '<tr><th style="vertical-align: middle;">II.</th><th colspan="3">HUTANG</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_h_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                total_harta_kekayaan_lhkpn1 = sub_total_harta_kekayaan_lhkpn1 - total_h_lhkpn1;
                                html_total_harta_kekayaan_lhkpn = '<tr><th style="vertical-align: middle;">III.</th><th colspan="3">TOTAL HARTA KEKAYAAN (I-II)</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_harta_kekayaan_lhkpn1, 0, '', '.')+'</th><th class="text-right"></th><th class="text-right"></th><th class="text-right"></th></tr>';

                                html_table_space = '<tr style="height: 37px;"><th colspan="9"></th></tr>';
                                html_detail_dhtb = "";
                                no_dhtb = 1;
                                $.each(res.data_harta.dhtb, function(key, hartas){
                                    $.each(hartas, function(k, harta){
                                        if (k != "summary" && harta != null && harta.summary != undefined) {
                                            deskripsi = harta.summary.DESKRIPSI;
                                            htb_detail = harta.summary.LHKPN_1 ? formatMoney(harta.summary.LHKPN_1, 0, '', '.') : '';
                                            html_detail_dhtb += '<tr><td></td><td></td><td>'+no_dhtb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+htb_detail+'</td><td class="text-right"></td><td class="text-right"></td><td class="text-right"></td></tr>';

                                            no_dhtb++;
                                        }

                                    });
                                });
                                // $.each(res.data_harta.dhtb, function(key, harta){
                                //     if (key != "summary") {
                                //         deskripsi = harta.summary.DESKRIPSI;
                                //         htb_detail = harta.summary.LHKPN_1 ? formatMoney(harta.summary.LHKPN_1, 0, '', '.') : '';
                                //         html_detail_dhtb += '<tr><td></td><td></td><td>'+no_dhtb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+htb_detail+'</td><td class="text-right"></td><td class="text-right"></td><td class="text-right"></td></tr>';

                                //         no_dhtb++;
                                //     }
                                // });
                                html_detail_dhb = "";
                                no_dhb = 1;
                                $.each(res.data_harta.dhb, function(key, hartas){
                                    $.each(hartas, function(k, harta){
                                        if (k != "summary" && harta != null && harta.summary != undefined) {
                                            deskripsi = harta.summary.DESKRIPSI;
                                            hb_detail = harta.summary.LHKPN_1 ? formatMoney(harta.summary.LHKPN_1, 0, '', '.') : '';
                                            html_detail_dhb += '<tr><td></td><td></td><td>'+no_dhb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+hb_detail+'</td><td class="text-right"></td><td class="text-right"></td><td class="text-right"></td></tr>';

                                            no_dhb++;
                                        }

                                    });
                                });
                                // $.each(res.data_harta.dhb, function(key, harta){
                                //     if (key != "summary") {
                                //         deskripsi = harta.summary.DESKRIPSI;
                                //         hb_detail = harta.summary.LHKPN_1 ? formatMoney(harta.summary.LHKPN_1, 0, '', '.') : '';
                                //         html_detail_dhb += '<tr><td></td><td></td><td>'+no_dhb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+hb_detail+'</td><td class="text-right"></td><td class="text-right"></td><td class="text-right"></td></tr>';

                                //         no_dhb++;
                                //     }
                                // });
                                html_lhkpn = html_total_htb_lhkpn
                                    +''+ html_detail_dhtb
                                    +''+ html_total_hb_lhkpn
                                    +''+ html_detail_dhb
                                    +''+ html_total_hbl_lhkpn
                                    +''+ html_total_hsb_lhkpn
                                    +''+ html_total_hk_lhkpn
                                    +''+ html_total_hl_lhkpn
                                    +''+ html_sub_total_harta_kekayaan_lhkpn
                                    +''+ html_table_space
                                    +''+ html_total_h_lhkpn
                                    +''+ html_table_space
                                    +''+ html_total_harta_kekayaan_lhkpn;

                                $('.data_perbandingan_lhkpn').html(html_lhkpn);
                            }
                        },
                        error: function(){
                            console.log('error');
                        }
                    });
                });

                // start event bandingkan
                $('.bandingkan').click(function() {
                    //action perbandingan
                    var lhkpn_1 = $('#id_lhkpn_1').val();
                    var lhkpn_2 = $('.lhkpn_options').val();

                    $.ajax({
                        url: "<?php echo base_url(); ?>portal/user/compare_harta/" + lhkpn_1 + "/" + lhkpn_2,
                        dataType: 'json',
                        beforeSend: function(){
                            setCookie('iwanibe_token', '<?= hash('sha256', 'iwankeren'.strtotime(date('Y-m-d H:i'))); ?>', 10);
                        },
                        complete: function(){
                            // Loading('hide');
                        },
                        success: function(res){
                            $.each(res.options, function(key, val){
                                if (val.ID_LHKPN == lhkpn_2) {
                                    tahun_2 = val.TGL_LAPOR;
                                    $(".cetak-perbandingan").prop('disabled', false);
                                    $('.cetak-perbandingan').attr('data-cetaklhkpn1', $('#id_lhkpn_1').val());
                                    $('.cetak-perbandingan').attr('data-cetaklhkpn2', $('.lhkpn_options').val());
                                }
                            });
                            var lhkpn_terbaru = res.data_harta.summary.lhkpn_terbaru;
                            var lhkpn_terlama = res.data_harta.summary.lhkpn_terlama;
                            $(".lhkpn_2").html('' + formattingDate(tahun_2));
                            //data lhkpn_12
                            total_htb = [];
                            total_htb[lhkpn_1] = res.data_harta.dhtb.summary[lhkpn_1] || 0;
                            total_htb[lhkpn_2] = res.data_harta.dhtb.summary[lhkpn_2] || 0;
                            selisih_htb = res.data_harta.dhtb.summary.selisih != null
                                ? res.data_harta.dhtb.summary.selisih
                                : 0;
                            persentase_htb = res.data_harta.dhtb.summary.persentase != null
                                ? res.data_harta.dhtb.summary.persentase.toFixed(2) + '%'
                                : '';

                            html_total_htb_lhkpn12 = '<tr><th></th><th>A.</th><th colspan="2">TANAH DAN BANGUNAN</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_htb[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_htb[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_htb, 0, '', '.')+'</th><th class="text-right">'+persentase_htb+'</th></tr>';

                            html_detail_dhtb12 = "";
                            no_dhtb = 1;
                            $.each(res.data_harta.dhtb, function(key, hartas){
                                $.each(hartas, function(k, harta){
                                    if (k != "summary" && harta != null && harta.summary != undefined) {
                                        deskripsi = harta.summary.DESKRIPSI;
                                        htb_detail = [];
                                        htb_detail[lhkpn_1] = harta.summary.LHKPN_1 ? harta.summary.LHKPN_1 : 0;
                                        htb_detail[lhkpn_2] = harta.summary.LHKPN_2 ? harta.summary.LHKPN_2 : 0;
                                        selisih_htb_detail = harta.summary.selisih != null 
                                            ? harta.summary.selisih 
                                            : 0;
                                        persentase_htb_detail = harta.summary.persentase != null 
                                            ? harta.summary.persentase.toFixed(2)+'%' 
                                            : '';
                                        
                                        html_detail_dhtb12 += '<tr><td></td><td></td><td>'+no_dhtb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+formatMoney(htb_detail[lhkpn_1], 0, '', '.')+'</td><td class="text-right">'+formatMoney(htb_detail[lhkpn_2], 0, '', '.')+'</td><td class="text-right">'+formatMoney(selisih_htb_detail, 0, '', '.')+'</td><td class="text-right">'+persentase_htb_detail+'</td></tr>';

                                        no_dhtb++;
                                    }

                                }); 
                            });
                            // $.each(res.data_harta.dhtb, function(key, harta){
                            //     if (key != "summary") {
                            //         deskripsi = harta.summary.DESKRIPSI;
                            //         htb_detail = [];
                            //         htb_detail[lhkpn_1] = harta.summary.LHKPN_1 ? harta.summary.LHKPN_1 : 0;
                            //         htb_detail[lhkpn_2] = harta.summary.LHKPN_2 ? harta.summary.LHKPN_2 : 0;
                            //         selisih_htb_detail = harta.summary.selisih != null 
                            //             ? harta.summary.selisih 
                            //             : 0;
                            //         persentase_htb_detail = harta.summary.persentase != null 
                            //             ? harta.summary.persentase.toFixed(2)+'%' 
                            //             : '';
                                    
                            //         html_detail_dhtb12 += '<tr><td></td><td></td><td>'+no_dhtb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+formatMoney(htb_detail[lhkpn_1], 0, '', '.')+'</td><td class="text-right">'+formatMoney(htb_detail[lhkpn_2], 0, '', '.')+'</td><td class="text-right">'+formatMoney(selisih_htb_detail, 0, '', '.')+'</td><td class="text-right">'+persentase_htb_detail+'</td></tr>';

                            //         no_dhtb++;
                            //     }
                            // });

                            total_hb = [];
                            total_hb[lhkpn_1] = res.data_harta.dhb.summary[lhkpn_1] || 0;
                            total_hb[lhkpn_2] = res.data_harta.dhb.summary[lhkpn_2] || 0;
                            selisih_hb = res.data_harta.dhb.summary.selisih != null
                                ? res.data_harta.dhb.summary.selisih
                                : 0;
                            persentase_hb = res.data_harta.dhb.summary.persentase != null
                                ? res.data_harta.dhb.summary.persentase.toFixed(2)+'%'
                                : '';

                            html_total_hb_lhkpn12 = '<tr><th></th><th>B.</th><th colspan="2">ALAT TRANSPORTASI DAN MESIN</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hb[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_hb[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_hb, 0, '', '.')+'</th><th class="text-right">'+persentase_hb+'</th></tr>';

                            html_detail_dhb12 = "";
                            no_dhb = 1;

                            html_detail_dhb = "";
                            no_dhb = 1;
                            $.each(res.data_harta.dhb, function(key, hartas){
                                $.each(hartas, function(k, harta){
                                    if (k != "summary" && harta != null && harta.summary != undefined) {
                                        deskripsi = harta.summary.DESKRIPSI;
                                        hb_detail = [];
                                        hb_detail[lhkpn_1] = harta.summary.LHKPN_1 ? harta.summary.LHKPN_1 : 0;
                                        hb_detail[lhkpn_2] = harta.summary.LHKPN_2 ? harta.summary.LHKPN_2 : 0;
                                        selisih_hb_detail = harta.summary.selisih != null 
                                            ? harta.summary.selisih 
                                            : 0;
                                        persentase_hb_detail = harta.summary.persentase != null 
                                            ? harta.summary.persentase.toFixed(2)+'%' 
                                            : '';
                                        
                                        html_detail_dhb12 += '<tr><td></td><td></td><td>'+no_dhb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+formatMoney(hb_detail[lhkpn_1], 0, '', '.')+'</td><td class="text-right">'+formatMoney(hb_detail[lhkpn_2], 0, '', '.')+'</td><td class="text-right">'+formatMoney(selisih_hb_detail, 0, '', '.')+'</td><td class="text-right">'+persentase_hb_detail+'</td></tr>';

                                        no_dhb++;
                                    }

                                }); 
                            });

                            // $.each(res.data_harta.dhb, function(key, harta){
                            //     if (key != "summary") {
                            //         deskripsi = harta.summary.DESKRIPSI;
                            //         hb_detail = [];
                            //         hb_detail[lhkpn_1] = harta.summary.LHKPN_1 ? harta.summary.LHKPN_1 : 0;
                            //         hb_detail[lhkpn_2] = harta.summary.LHKPN_2 ? harta.summary.LHKPN_2 : 0;
                            //         selisih_hb_detail = harta.summary.selisih != null 
                            //             ? harta.summary.selisih 
                            //             : 0;
                            //         persentase_hb_detail = harta.summary.persentase != null 
                            //             ? harta.summary.persentase.toFixed(2)+'%' 
                            //             : '';
                                    
                            //         html_detail_dhb12 += '<tr><td></td><td></td><td>'+no_dhb+'.</td><td>'+deskripsi+'</td><td></td><td class="text-right">'+formatMoney(hb_detail[lhkpn_1], 0, '', '.')+'</td><td class="text-right">'+formatMoney(hb_detail[lhkpn_2], 0, '', '.')+'</td><td class="text-right">'+formatMoney(selisih_hb_detail, 0, '', '.')+'</td><td class="text-right">'+persentase_hb_detail+'</td></tr>';

                            //         no_dhb++;
                            //     }
                            // });

                            total_hbl = [];
                            total_hbl[lhkpn_1] = res.data_harta.dhbl.summary[lhkpn_1] || 0;
                            total_hbl[lhkpn_2] = res.data_harta.dhbl.summary[lhkpn_2] || 0;
                            selisih_hbl = res.data_harta.dhbl.summary.selisih != null
                                ? res.data_harta.dhbl.summary.selisih
                                : 0;
                            persentase_hbl = res.data_harta.dhbl.summary.persentase != null
                                ? res.data_harta.dhbl.summary.persentase.toFixed(2)+'%'
                                : '';

                            html_total_hbl_lhkpn12 = '<tr><th></th><th>C.</th><th colspan="2">HARTA BERGERAK LAINNYA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hbl[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_hbl[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_hbl, 0, '', '.')+'</th><th class="text-right">'+persentase_hbl+'</th></tr>';

                            total_hsb = [];
                            total_hsb[lhkpn_1] = res.data_harta.dhsb.summary[lhkpn_1] || 0;
                            total_hsb[lhkpn_2] = res.data_harta.dhsb.summary[lhkpn_2] || 0;
                            selisih_hsb = res.data_harta.dhsb.summary.selisih != null
                                ? res.data_harta.dhsb.summary.selisih
                                : 0;
                            persentase_hsb = res.data_harta.dhsb.summary.persentase != null
                                ? res.data_harta.dhsb.summary.persentase.toFixed(2)+'%'
                                : '';

                            html_total_hsb_lhkpn12 = '<tr><th></th><th>D.</th><th colspan="2">SURAT BERHARGA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hsb[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_hsb[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_hsb, 0, '', '.')+'</th><th class="text-right">'+persentase_hsb+'</th></tr>';
                            html_total_hbl_lhkpn12 = '<tr><th></th><th>C.</th><th colspan="2">HARTA BERGERAK LAINNYA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hbl[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_hbl[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_hbl, 0, '', '.')+'</th><th class="text-right">'+persentase_hbl+'</th></tr>';

                            total_hk = [];
                            total_hk[lhkpn_1] = res.data_harta.dhk.summary[lhkpn_1] || 0;
                            total_hk[lhkpn_2] = res.data_harta.dhk.summary[lhkpn_2] || 0;
                            selisih_hk = res.data_harta.dhk.summary.selisih != null
                                ? res.data_harta.dhk.summary.selisih
                                : 0;
                            persentase_hk = res.data_harta.dhk.summary.persentase != null
                                ? res.data_harta.dhk.summary.persentase.toFixed(2)+'%'
                                : '';

                            html_total_hk_lhkpn12 = '<tr><th></th><th>E.</th><th colspan="2">KAS DAN SETARA KAS</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hk[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_hk[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_hk, 0, '', '.')+'</th><th class="text-right">'+persentase_hk+'</th></tr>';
                            html_total_hbl_lhkpn12 = '<tr><th></th><th>C.</th><th colspan="2">HARTA BERGERAK LAINNYA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hbl[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_hbl[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_hbl, 0, '', '.')+'</th><th class="text-right">'+persentase_hbl+'</th></tr>';

                            total_hl = [];
                            total_hl[lhkpn_1] = res.data_harta.dhl.summary[lhkpn_1] || 0;
                            total_hl[lhkpn_2] = res.data_harta.dhl.summary[lhkpn_2] || 0;
                            selisih_hl = res.data_harta.dhl.summary.selisih != null
                                ? res.data_harta.dhl.summary.selisih
                                : 0;
                            persentase_hl = res.data_harta.dhl.summary.persentase != null
                                ? res.data_harta.dhl.summary.persentase.toFixed(2)+'%'
                                : '';

                            html_total_hl_lhkpn12 = '<tr><th></th><th>F.</th><th colspan="2">HARTA LAINNYA</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_hl[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_hl[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_hl, 0, '', '.')+'</th><th class="text-right">'+persentase_hl+'</th></tr>';

                            sub_total_harta_kekayaan = [];
                            sub_total_harta_kekayaan[lhkpn_1] = res.data_harta.summary.subtotal[lhkpn_1];
                            sub_total_harta_kekayaan[lhkpn_2] = res.data_harta.summary.subtotal[lhkpn_2];
                            sub_total_selisih = res.data_harta.summary.subtotal.selisih != null
                                ? res.data_harta.summary.subtotal.selisih
                                : 0 ;
                            sub_total_persentase = res.data_harta.summary.subtotal.persentase != null
                                ? res.data_harta.summary.subtotal.persentase.toFixed(2)+'%'
                                : '' ;

                            html_sub_total_harta_kekayaan_lhkpn12 = '<tr><th></th><th></th><th colspan="2">Sub Total</th><th>Rp</th><th class="text-right">'+formatMoney(sub_total_harta_kekayaan[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(sub_total_harta_kekayaan[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(sub_total_selisih, 0, '', '.')+'</th><th class="text-right">'+sub_total_persentase+'</th></tr>';

                            total_h = [];
                            total_h[lhkpn_1] = res.data_harta.dh.summary[lhkpn_1] || 0;
                            total_h[lhkpn_2] = res.data_harta.dh.summary[lhkpn_2] || 0;
                            selisih_h = res.data_harta.dh.summary.selisih != null
                                ? res.data_harta.dh.summary.selisih
                                : 0;
                            persentase_h = res.data_harta.dh.summary.persentase != null
                                ? res.data_harta.dh.summary.persentase.toFixed(2)+'%'
                                : '';

                            html_total_h_lhkpn12 = '<tr><th style="vertical-align: middle;">II.</th><th colspan="3">HUTANG</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_h[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_h[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_h, 0, '', '.')+'</th><th class="text-right">'+persentase_h+'</th></tr>';

                            total_all = [];
                            total_all[lhkpn_1] =
                                sub_total_harta_kekayaan[lhkpn_1] - total_h[lhkpn_1];
                            total_all[lhkpn_2] =
                                sub_total_harta_kekayaan[lhkpn_2] - total_h[lhkpn_2];
                            selisih_all = res.data_harta.summary.total.selisih != null
                                ? res.data_harta.summary.total.selisih
                                : 0 ;
                            persentase_all = res.data_harta.summary.total.persentase != null
                                ? res.data_harta.summary.total.persentase.toFixed(2)+'%'
                                : '' ;
                            html_total_all_lhkpn12 = '<tr><th style="vertical-align: middle;">III.</th><th colspan="3">TOTAL HARTA KEKAYAAN (I-II)</th><th>Rp</th><th class="text-right nilai_harta">'+formatMoney(total_all[lhkpn_1], 0, '', '.')+'</th><th class="text-right">'+formatMoney(total_all[lhkpn_2], 0, '', '.')+'</th><th class="text-right">'+formatMoney(selisih_all, 0, '', '.')+'</th><th class="text-right">'+persentase_all+'</th></tr>';

                            html_lhkpn12 = html_total_htb_lhkpn12
                                +''+ html_detail_dhtb12
                                +''+ html_total_hb_lhkpn12
                                +''+ html_detail_dhb12
                                +''+ html_total_hbl_lhkpn12
                                +''+ html_total_hsb_lhkpn12
                                +''+ html_total_hk_lhkpn12
                                +''+ html_total_hl_lhkpn12
                                +''+ html_sub_total_harta_kekayaan_lhkpn12
                                +''+ html_table_space
                                +''+ html_total_h_lhkpn12
                                +''+ html_table_space
                                +''+ html_total_all_lhkpn12
                                ;

                            $('.data_perbandingan_lhkpn').html(html_lhkpn12);


                        },
                        error: function(){

                        },
                    });
                });
                // end event bandingkan

                // cetak perbandingan
                $('.cetak-perbandingan').click(function() {
                    setCookie('iwanibe_token', '<?= hash('sha256', 'iwankeren'.strtotime(date('Y-m-d H:i'))); ?>', 15);
                    base_url = "<?php echo base_url(); ?>portal/user/preview_compare_harta/";
                    lhkpn1 = $('.cetak-perbandingan').attr('data-cetaklhkpn1');
                    lhkpn2 = $('.cetak-perbandingan').attr('data-cetaklhkpn2');
                    url = base_url + lhkpn1 + "/" + lhkpn2;

                    window.open(url, '_blank');
                });

                $('#modal-perbandingan-announcement-lhkpn').on('hidden.bs.modal', function () {
                    $('.data_perbandingan_lhkpn').html('');
                    $(".tahun_lhkpn").html(
                        "<strong>"
                        + "(Tanggal Penyampaian/Jenis Laporan - Tahun: "
                        + ")</strong>"
                    );
                    $(".nama_lengkap").html(": ");
                    $(".lembaga").html(": ");
                    $(".unit_kerja").html(": ");
                    $(".sub_unit_kerja").html(": ");
                    $(".jabatan").html(": ");
                    $(".nhk").html(": ");
                    $('.lhkpn_options').html('<option value=""></option>');
                    $('.data_perbandingan_lhkpn').html('');
                    $(".lhkpn_1").html('');
                    $(".lhkpn_2").html('');
                    $('.bandingkan').prop('disabled', true);
                    $('.cetak-perbandingan').prop('disabled', true);
                    $('.cetak-perbandingan').attr('data-cetaklhkpn2', null);
                });

                function formattingDate(tgl) {
                    var d = new Date(tgl),
                        day = ('0' + d.getDate()).slice(-2),
                        month = d.getMonth()+1,
                        year = '' +  d.getFullYear();
                        months = [];
                        months['1'] = 'Januari';
                        months['2'] = 'Februari';
                        months['3'] = 'Maret';
                        months['4'] = 'April';
                        months['5'] = 'Mei';
                        months['6'] = 'Juni';
                        months['7'] = 'Juli';
                        months['8'] = 'Agustus';
                        months['9'] = 'September';
                        months['10'] = 'Oktober';
                        months['11'] = 'November';
                        months['12'] = 'Desember';
                    return day + ' ' + months[month] + ' ' + year;
                }

                function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
                    try {
                        decimalCount = Math.abs(decimalCount);
                        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                        const negativeSign = amount < 0 ? "-" : "";

                        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                        let j = (i.length > 3) ? i.length % 3 : 0;

                        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
                    } catch (e) {
                        console.log(e)
                    }
                }

                /*
                $('form#flogin').submit(function(e) {
                    e.preventDefault();

                    grecaptcha.ready(function() {
                        grecaptcha.execute('6LeR4icbAAAAALNeSDWB1qz_A11ahqEuNPyrm8Jv', {action: 'login'}).then(function(token) {
                            document.getElementById("g-recaptcha-response").value = token;
                            document.getElementById("flogin").submit();
                        });
                    });
                });
                */

                // Cookies
                function setCookie(cname,cvalue,exseconds) {
                    const d = new Date();
                    d.setTime(d.getTime() + (exseconds*1000));
                    let expires = "expires=" + d.toGMTString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                }

                function getCookie(cname) {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(';');
                    for(let i = 0; i < ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                }

                function checkCookie() {
                    let user = getCookie("username");
                    if (user != "") {
                        alert("Welcome again " + user);
                    } else {
                        user = prompt("Please enter your name:","");
                        if (user != "" && user != null) {
                        setCookie("username", user, 30);
                        }
                    }
                }
            });
        </script>
        <?php $error_message = $this->session->flashdata('error_message'); ?>
<?php if ($error_message): ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    var options = {hashTracking: false};
                    var inst = $('[data-remodal-id=modal-message]').remodal(options);
                    inst.open();
                });
            </script>
            <?php EndIf; ?>
        <?php if (isset($user_key)): ?>
    <?php if ($user_key): ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        var options = {hashTracking: false};
                        var inst = $('[data-remodal-id=modal-confirm]').remodal(options);
                        inst.open();
                    });
                </script>
                <?php EndIf; ?>
    <?php EndIf; ?>
        <script type="text/javascript" src="<?php echo base_url(); ?>portal-assets/js/login.js"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-29889353-15"></script>
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
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-29889353-15');
        </script>

    </body>

</html>
