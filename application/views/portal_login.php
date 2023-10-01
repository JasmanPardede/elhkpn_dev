<!DOCTYPE html>
<html lang="en">
<head>
   <title>e-Filing</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Waditra Reka Cipta">
    <link href="<?php echo base_url();?>portal-assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon">

    

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/bootstrap.min.css" type="text/css">
   

    <!-- Custom Fonts -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'> -->

    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/font-awesome/css/font-awesome.min.css" type="text/css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/animate.min.css" type="text/css">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>portal-assets/css/remodal.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>portal-assets/css/remodal-default-theme.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>portal-assets/css/login.css" type="text/css">
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url();?>portal-assets/js/modernizr.custom.js"></script>
</head>

<body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid" id="nav-top">
              <div class="row">
                 <div class="col-lg-12" id="image-wrapper">
                    <a class="navbar-brand page-scroll" title="LHKPN"  href="#page-top">
                        <img src="<?php echo base_url();?>portal-assets/img/kpk.png"/>
                    </a>
                    <img src="<?php echo base_url();?>portal-assets/img/logo.png" />
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
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#about">Apa itu e-LHKPN?</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Panduan Aplikasi</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#e-annouchment">E-Announcement</a>
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
                            <form id="flogin">
                                 <span>
                                    <h2>eLHKPN Online Login</h2>
                                    <h5>Silahkan Login Untuk memulai aplikasi eLHKPN</h5>
                                 </span>
                                 <span id="pesan"></span>
                                 <span>
                                     <input type="text" id="username" name="usr" placeholder="Username" required/>
                                 </span>
                                <span>
                                     <input type="password" id="password" name="pwd" placeholder="Password" required/>
                                </span>
                                <span id="captcha-wrapper">
                                     <div id="img_captcha" style="display:inline;"><?php echo $img; ?></div>
                                     <a id="re-captcha" id="captcha-img"  class="btn btn-primary" href="javascript:void(0)">
                                        <i class="fa fa-refresh"></i>
                                     </a>
                                </span>
                                <span>
                                    <input type="text" name="usr_captcha" id="usr_captcha"  placeholder="Isikan Kode Diatas" required/>
                                </span>
                                <span>
                                     <input type="submit" value="Log Me In"/>
                                 </span>
                                 <span>
                                    <a id="link-login"  href="#" data-remodal-target="modal">
                                        <h5>Klik disini bila Anda Lupa Password</h5>
                                    </a>
                                 </span>
                                  <span style="text-shadow: rgb(1, 1, 1) 2px 2px 1px; padding-top:12px;">
                                    Untuk informasi lebih lanjut Anda dapat menghubungi ke 198 atau ke elhkpn@kpk.go.id 
                                 </span>
                                 <input type="hidden" name="hdn_captcha" id="hdn_captcha">
                                 <input type="hidden" name="sec" id="sec">
                            </form>
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
                    <h2 class="section-heading">E-LHKPN?</h2>
                    <div class="row">
                        <div class="col-lg-6">
                             <video controls="" loop="" >
                                  <source src="<?php echo base_url();?>portal-assets/video/KPKeLHKPN.mp4" type="video/mp4">
                             </video>
                        </div>   
                        <div class="col-lg-6">
                             <p>
                               <strong>e-LHKPN</strong> merupakan kumpulan dokumen pelaporan harta kekayaan penyelenggara negara yang telah diverifikasi oleh KPK dalam bentuk Tambahan Berita Negara (TBN). Aplikasi ini dapat diakses oleh masyarakat sebagai bentuk transparansi informasi publik mengenai jumlah kekayaan penyelenggara negara. Aplikasi ini berfungsi sebagai alat kontrol dan salah satu mekanisme untuk menilai kejujuran dan integritas penyelenggara negara. 
                            </p>
                            <p>
                                Apabila masyarakat menemukan ketidaksesuaian antara data pelaporan tersebut di atas dengan realitas di lapangan, dapat melaporkan melalui Pengaduan Masyarakat KPK, atau menghubungi: 
                            </p>
                            <p>
                                 <a href="#" class="btn btn-success btn-xl">Contact US</a>
                            </p>
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
                    <h2 class="section-heading">Panduan Aplikasi</h2>
                    <p>
                        Penyelenggara Negara dapat menyampaikan LHKPN kepada KPK baik secara online melalui aplikasi eLHKPN, datang langsung ke KPK maupun melalui pos. Cuctomer Service LHKPN akan memberikan bukti tanda terima terkait penyerahan LHKPN kepada Penyelenggara yang datang secara langsung atau mengirimkan tanda terima tersebut melalui POS.
                        KPK akan melakukan pengecekan terhadap seluruh LHKPN yang diterima terkait ketepatan pengisian dan kelengkapan dokumen pendukung.
                        Apabila dokumen yang diterima tidak tepat pengisiannya ataupun terdapat dokumen pendukung yang belum lengkap, maka KPK akan menyurati Penyelenggara Negara untuk mengoreksi isian formulir dan melengkapi dokumen pendukung.
                        Perlu diperhatikan bahwa dokumen yang belum lengkap dan tidak tepat tidak akan di proses. Untuk melengkapi dokumen dan memberikan koreksi pengisian, Penyelenggara Negara dapat menyampaikannya secara lansung ke Customer Service maupun melalui POS.
                    </p>
                    <p>
                        Dokumen yang sudah lengkap akan diproses dan akan diumumkan pada Tambahan Berita Negara (TBN) dan diberi Nomor Harta Kekayaan (NHK).
						Penyelenggara Negara wajib mengingat NHK untuk kebutuhan pelaporan berikutnya. TBN dan poster pengumuman akan disampaikan kepada Penyelenggara Negara melalui instansi masing-masing Penyelenggara Negara.
						Penyelenggara Negara wajib menempelkan poster dan pengumuman tersebut pada media pengumuman di kantor/instansi Penyelenggara Negara dan menyampaikan lembar pemberitahuan pengumuman LHKPN di instansi ke KPK.
                    </p>
                    <a href="#" class="btn btn-success btn-xl">Klik disini untuk memulai login ke aplikasi eLHKPN</a>
                </div>
            </div>
        </div>
    </section>

   

    <section id="e-annouchment">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-heading">E-Announcement</h2>
                    <p>
                        Laporan Harta Kekayaan Penyelenggara Negara (TBN LHKPN)
                    </p>
                    <p>
                        Berisi dokumen pelaporan harta kekayaan Penyelenggara Negara yang telah diverifikasi oleh KPK dalam bentuk Tambahan Berita Negara (TBN).
                    </p>
					<p>
                        Apabila masyarakat menemukan ketidaksesuaian antara data pelaporan tersebut dengan realitas di lapangan dapat melaporkan melalui Pengaduan Masyarakat KPK atau menghubungi ke kontak Layanan LHKPN.
                    </p>
						<p>
                        
                    </p>
						<p>
                        
                    </p>
                    <a href="#" class="btn btn-success btn-xl">Kontak Layanan LHKPN</a>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-heading">Kontak</h2>
                    <p>
                        KONTAK LAYANAN LHKPN
                    </p>
					<p>
						Komisi Pemberantasan Korupsi
                    </p>
					<p>  
						Gedung Merah Putih KPK - Jl. Kuningan Persada Kav. 4, Setiabudi
                    </p>
					<p>
						Jakarta 12950
                    </p>
					<p>
						Call Center: 198
                    </p>
					<p>
						Email : elhkpn@kpk.go.id
                    </p>
                    <p>
                        https://www.twitter.com/KPK_RI
                    </p>
					<p>
                        https://www.facebook.com/KomisiPemberantasanKorupsi
                    </p>
					<p>
                        https://www.youtube.com/user/HUMASKPK
                    </p>
                    <a href="#" class="btn btn-success btn-xl">Memulai aplikasi eLHKPN</a>
                </div>
            </div>
        </div>
    </section>

    <section id="faq">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-heading">FAQ</h2>
                    <div class="accordion" id="accordion2">

                      <div class="accordion-group">
                        <div class="accordion-heading">
                          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                            Peraturan apa saja yang mengatur tentang LHKPN?
                          </a>
                        </div>
                        <div id="collapseOne" class="accordion-body collapse in">
                          <div class="accordion-inner">
                           <p>
                                Peraturan yang mengatur LHKPN adalah sebagai berikut:
                                
                                Undang undang Nomor 28 Tahun 1999 tentang Penyelenggara Negara yang bersih dan bebas dari korupsi, kolusi dan nepotisme;
                                Undang undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi; 
								Keputusan KPK Nomor: KEP/07/KPK/02/2005 tentang tata cara, pendaftaran, pengumuman, dan pemeriksaan Laporan Harta Kekayaan Penyelenggara Negara
                            </p>
                            <p>
                                
                            </p>
                          </div>
                        </div>
                      </div>
                    
                      <div class="accordion-group">
                        <div class="accordion-heading">
                          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                            Siapa sajakah Penyelenggara Negara yang harus menyampaikan LHKPN?
                          </a>
                        </div>
                        <div id="collapseOne2" class="accordion-body collapse in">
                          <div class="accordion-inner">
                           <p>
                                Adapaun Penyelenggara Negara sebagaimana dimaksud dalam pasal pasal 2 Undang undang Nomor 28 Tahun 1999 adalah sebagai berikut:
								
                                Pejabat Negara pada Lembaga Tertinggi Negara;
								Pejabat Negara pada Lembaga Tinggi Negara;
								Menteri;
								Gubernur;
								Hakim;
								Pejabat Negara yang lain sesuai dengan ketentuan perundangan undangan yang berlaku;
								Pejabat lain yang memiliki fungsi strategis dalam kaitannya dengan penyelenggaraan negara sesuai dengan ketentuan perundangan yang berlaku, meliputi:
								Direksi, Komisaris dan pejabat struktural lainnya sesuai pada Badan Usaha Milik Negara dan Badan Usaha Milik Daerah;
								Pimpinan Bank Indonesia;
								Pimpinan Perguruan Tinggi Negeri;
								Pejabat eselon I dan Pejabat lain yang disamakan di lingkungan sipil, militer dan Kepolisian Negara Republik Indonesia;
								Jaksa;
								Penyidik;
                            </p>
                            <p>
                                
                            </p>
                          </div>
                        </div>
                      </div>

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
                        JL.HR Rasuna Said Kav.C1 Kuningan,
                        Jakarta Selatan 12920,
                        Call Center: 198,
                        Fax:(021) 5289 2456,
                        Email: informasi@kpk.go.ig
                    </p>
                </div>
                <div class="col-lg-12">
                    <ul>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/icon1.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/icon2.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/icon3.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/icon4.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/icon5.png"/></a></li>
                        <li><a href="#"><img src="<?php echo base_url();?>portal-assets/img/icon6.png"/></a></li>
                    </ul>
                </div>
            </div>
            <div class="row copyright">
                <div class="col-lg-12">
                     <strong>Copyright <?php echo date('Y');?></strong>
                </div>
            </div>
        </div>
    </section>
   
   
    
    <div class="remodal" data-remodal-id="modal">
      <button data-remodal-action="close" class="remodal-close"></button>
      <h3>Lupa Password</h3>
        <form id="f-forget">
         <span id="f-input">
            <input type="email" id="input-email" placeholder="Silahkan masukan email anda dengan benar." required/>
         </span>
         <span id="f-progress">
            <div class="progress">
              <div id="progress_widget" class="progress-bar progress-bar-striped active" role="progressbar"
              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                0%
              </div>
            </div>
         </span>
         <span id="f-message">
             <div class="alert alert-success">
                 Permintaan lupa password berhasil dikirim , silahkan menuggu konfirmasi.
             </div>
        </span>
         <span id="f-button">
            <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>
            <button class="remodal-confirm">OK</button>
         </span>
        </form>
    </div>

    <div class="remodal" style="background:none;" data-remodal-id="modal-progress">
        <span>
            <div class="progress">
              <div id="progress_load" class="progress-bar progress-bar-striped active" role="progressbar"
              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                0%
              </div>
            </div>
        </span>
    </div>

    <div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->
        
    <!-- jQuery -->
    <script src="<?php echo base_url();?>portal-assets/js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>portal-assets/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="<?php echo base_url();?>portal-assets/js/jquery.easing.min.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/jquery.fittext.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/wow.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script type="text/javascript">
        var img_url = '<?php echo base_url();?>portal-assets/';
        var site_url = '<?php echo base_url();?>';
        var base_url = '<?php echo base_url();?>';
    </script>
    <script src="<?php echo base_url();?>portal-assets/js/creative.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/background.cycle.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/scroll.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/remodal.min.js"></script>
    <script src="<?php echo base_url();?>portal-assets/js/login.js"></script>
   

</body>

</html>
