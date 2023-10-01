
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


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="<?php echo base_url(); ?>portal-assets/js/modernizr.custom.js"></script>
    </head>

    <body id="page-top">

        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid" id="nav-top">
                <div class="row">
                    <div class="col-lg-12" id="image-wrapper">
                        <a class="navbar-brand page-scroll" title="lhkpn"  href="#page-top">
                            <img src="<?php echo base_url(); ?>portal-assets/img/kpk.png"/>
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
<!--                        <li>
                            <a class="page-scroll" href="#page-top">Login</a>
                        </li>-->
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
<!--                        <li>
                            <a target="_blank" href="https://acch.kpk.go.id/pengumuman-lhkpn">e-Announcement</a>
                            <a class="page-scroll" href="#announ">e-Announcement</a>
                        </li>-->
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
                                <?php if($agent_info->msg != ""): ?>
                                <div class="alert alert-info" style="height: inherit; font-size: 18px; justify-content: space-between; ">
                                    <?php echo $agent_info->msg; ?>
                                </div>
                                <?php else: ?>
                                <!--<form id="flogin" method="POST" action="<?php echo base_url(); ?>portal/user/auth" role="form">-->
                                    <span>
                                        <h2></h2>
                                        <h5></h5>
                                    </span>
                                    <div class="alert alert-info" style="height: inherit; font-size: 18px; justify-content: space-between; ">
                                        <input type="hidden" id="id_lhkpnku" name="id_lhkpnku" value="<?php echo $this->session->userdata('id_lhkpn'); ?>">
                                        <!--<p>-->
                                            Terima kasih telah mengunduh berkas LHKPN ini, berkas akan terunduh dalam <span id="timer" style="display:inline; padding-top:0px;"></span> detik.
                                        <!--</p>--> 
                                    </div>
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
                                <!--</form>-->
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
                                <!--video controls="" loop="" id="kpkvideoelhkpn2">
                                </video-->
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/jBOLbB9Wc4w" frameborder="0" allowfullscreen></iframe>
                            </div>    
                            <div class="col-lg-6">
                                <!--video controls="" loop="" id="PerKPK07Tahun2016">
                                </video-->
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/K9uBUBu7nYU" frameborder="0" allowfullscreen></iframe>
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
                            <div class="col-lg-6">
                                <!--<a href="https://www.kpk.go.id/images/LHKPN/Brosur_elhkpn_2017-1.jpg" target ="_blank">-->
                                    <!--<img src="https://www.kpk.go.id/images/LHKPN/Brosur_elhkpn_2017-1.jpg" width="423" height="424" />-->
                                <a download href="<?php echo base_url(); ?>images/brosur/Brosur e-LHKPN Update (13-03-17) - 01.jpg" target ="_blank">
                                    <img src="<?php echo base_url(); ?>images/brosur/Brosur e-LHKPN Update (13-03-17) - 01.jpg" width="423" height="424" /> </a>
                            </div>   
                            <div class="col-lg-6">
                                <!--<a href="https://www.kpk.go.id/images/LHKPN/Prosedur_e_LHKPN_2.jpg" target ="_blank">-->
                                    <!--<img src="https://www.kpk.go.id/images/LHKPN/Prosedur_e_LHKPN_2.jpg" width="423" height="424" />-->
                                <a download href="<?php echo base_url(); ?>images/brosur/Brosur e-LHKPN Update (13-03-17) - 02.jpg" target ="_blank">
                                    <img src="<?php echo base_url(); ?>images/brosur/Brosur e-LHKPN Update (13-03-17) - 02.jpg" width="423" height="424" />
                                </a>
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
                                    Sesuai Surat Edaran Pimpinan KPK No.08 Tahun 2016 tentang Petunjuk Teknis Penyampaian dan Pengelolaan Laporan Harta Kekayaan Penyelenggara Negara setelah diberlakukannya Peraturan KPK No.07 Tahun 2016 tentang Tata cara Pendaftaran, Pengumuman dan Pemeriksaan Harta Kekayaan Penyelenggara Negara bahwa terhitung tanggal 1 Januari 2017, penyampaian LHKPN mulai berlaku secara elektronik melalui aplikasi e-LHKPN, terlampir file pendukung yang dapat diunduh.
                                </p>
                                <p>
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
                                </p>


                            </div>   
                            <div class="col-lg-6">
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
                            </div> 
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
                                <img src="<?php echo base_url(); ?>portal-assets/img/gedungdwiwarna.jpg" width="550" height="320">
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
                        <strong>Copyright 2017</strong>
                    </div>
                </div>
            </div>
        </section>

        <div class="remodal" data-remodal-id="modal-announ">
            <button data-remodal-action="close" class="remodal-close"></button>
            <h3>Siapakan Anda?</h3>
            <form id="f-announ" class="form-horizontal" role="form" method="post" action="announ_user" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Email :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="email-announ" placeholder="Masukan email anda" id="email-announ" required>
                                    <input type="hidden" id="id_lhkpn_announ" name="id_lhkpn_announ" value="<?php echo $item->ID_LHKPN; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Profesi :</label>
                                <div class="col-sm-7" align="left">
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="pn" required> Pegawai Negeri <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="media"> Media / Pers <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="akademisi"> Akademisi <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="lsm"> LSM <br>
                                    <input type="radio" name="profesi-announ" id="profesi-announ" value="mu"> Masyarakat Umum
                                </div>
                            </div>
                        </div>
                        <span id="f-button">
                            <a class="btn btn-success announ-download" href="<?php base_url(); ?>announ_user" target="_blank">Download</a>
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
                        Alamat email yang dimasukan tidak terdaftar di e-LHKPN, silahkan menghubungi administrator instansi Anda.
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




        <div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->

        <!-- jQuery -->
        <script src="<?php echo base_url(); ?>portal-assets/js/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="<?php echo base_url(); ?>portal-assets/js/bootstrap.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="<?php echo base_url(); ?>portal-assets/js/jquery.easing.min.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/jquery.fittext.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/wow.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script type="text/javascript">
            var img_url = '<?php echo base_url(); ?>portal-assets/';
            var base_url = '<?php echo base_url(); ?>';
            function reload_captcha() {
                $.post(base_url + "index.php/auth/reload_captcha").done(function (msg) {
                    var result = JSON.parse(msg);
                    $('#img_captcha').html(result.image);
                    $('#hdn_captcha').val(result.value);
                    $('#txtCaptcha').html(result.image);
                    $('#txtCaptcha-announ').html(result.image);
                });
            }
        </script>
        <script src="<?php echo base_url(); ?>portal-assets/js/creative.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/background.cycle.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/scroll.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/remodal.min.js"></script>
        <script src="<?php echo base_url(); ?>portal-assets/js/jwplayer.js"></script>
        <!--<script src="<?php echo base_url(); ?>js/ngv2.js"></script>-->
        <script src="<?php echo base_url(); ?>plugins/AdminLTE-2.1.1/dist/js/demo.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datepicker/bootstrap-datepicker.js"></script>
        <script>jwplayer.key = "i37uQE33ShjgKi4xSchebu6drFlR1lEPVu0wXg==";</script>
        <script type="text/javascript">
            function timer (){
                var counter = 6;
                var id_lhkpn = $("#id_lhkpnku").val()
                setInterval(function() {
                  counter--;
                  if (counter >= 0) {
                    span = document.getElementById("timer");
                    span.innerHTML = counter;
                  }
                  // Display 'counter' wherever you want to display it.
                  if (counter === 0) {
                      if (id_lhkpn == ''){
                          alert('Session anda telah habis, silahkan download ulang !!')
                      }else{
                          location.href="<?php echo base_url();?>portal/user/PreviewAnnoun/"+id_lhkpn;
                          clearInterval(counter);
                      }
                  }

                }, 1000);

              }
            timer();
            $(document).ready(function (){
               $('.year-picker').datepicker({
                    orientation: "left",
                    format: 'yyyy',
                    viewMode: "years",
                    minViewMode: "years",
                    autoclose: true
                });
                
                $("#ajaxFormCari").submit(function (e) {
                    var url = $(this).attr('action');
                    ng.LoadAjaxContentPost(url, $(this));
                    return false;
                });
                
                $('#btn-clear').click(function (event) {
                    $('#ajaxFormCari').find('input:text').val('');
                    $('#ajaxFormCari').find('select').val('');
                    $('#ajaxFormCari').trigger('submit');
                });
                
                $(".announ-download").on("click",(function(){
                    var options = {hashTracking: false};
                    var inst = $('[data-remodal-id=modal-announ]').remodal(options);
                    var urll = $("#f-announ").attr('action');
                    inst.close();
                    $.ajax({
                        url: urll,
                        async: true,
                        type: 'POST',
                        data: $('#f-announ').serialize(),
                        success:function(htmldata){
                            if (htmldata){
                                location.href="check_search_announ#announ";
                                location.reload(true);
                            }
                       },
                    cache: false,
                    contentType: false,
                    processData: true
                   });
                })); 
            });
        </script>
        <script type="text/javascript">
            jwplayer("kpkvideoelhkpn").setup({
                file: "<?php echo base_url(); ?>portal-assets/video/KPKeLHKPN.mp4",
                height: "500px",
                width: "100%"
            });
        </script>
        <script type="text/javascript">
            jwplayer("kpkvideoelhkpn2").setup({
                file: "<?php echo base_url(); ?>portal-assets/video/KPKeLHKPN.mp4",
                height: "270",
                width: "500",
                left: 0
            });
        </script>
        <script type="text/javascript">
            jwplayer("PerKPK07Tahun2016").setup({
                file: "<?php echo base_url(); ?>portal-assets/video/PerKPK07Tahun2016.mp4",
                height: "270",
                width: "500",
                left: 0
            });
        </script>
        <?php $error_message = $this->session->flashdata('error_message'); ?>
        <?php if ($error_message): ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    var options = {hashTracking: false};
                    var inst = $('[data-remodal-id=modal-message]').remodal(options);
                    inst.open();
                });
            </script>    
            <?php EndIf; ?> 
        <?php if (isset($user_key)): ?>
            <?php if ($user_key): ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var options = {hashTracking: false};
                        var inst = $('[data-remodal-id=modal-confirm]').remodal(options);
                        inst.open();
                    });
                </script>    
                <?php EndIf; ?>  
            <?php EndIf; ?>  
        <script type="text/javascript" src="<?php echo base_url(); ?>portal-assets/js/login.js"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-29889353-15"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        </script>  
        
    </body>

</html>
