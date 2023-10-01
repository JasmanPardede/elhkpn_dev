
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
                            <?php if($via_pengumuman!=1){ ?>
                            <a class="page-scroll" href="<?php echo base_url(); ?>portal/user/login">Beranda</a>
                            <?php }else{
                                echo '<br><br>';
                            } ?>
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
                    <div class="col-lg-12" style="margin-top:12em;margin-bottom:20em">
                    <form id="f-pelaporan">
                            <input type="hidden" name="stateSession" value="<?php echo $get_state ?>" />
                            <!--<h2 class="section-heading">Pelaporan Harta Kekayaan</h2>-->
                            <br>
                             <div class="box-body">
                                <div class="form-group" style="text-align:center">
                                    <h3>KONFIRMASI TOKEN</h3>
                                </div><br>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Token <span class="red-label">*</span></label>
                                        <input type="text" placeholder="Masukan token yang diterima via sms/email" name="TOKEN_PELAPOR" id="TOKEN_PELAPOR" value="" class="form-control"/>
                                        <span>Server Code : </span><span id="server_code_text"><?php echo $server_code ?></span><br><br>
                                        <span>(pastikan server code sesuai dengan yang ada di email/sms token).</span>
                                       
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                              
                            </div>
                            <div class="pull-right" style="margin-right:8em" >
                                 <button type="button" class="btn btn-info btn-sm kirim-ulang-token"><span id="timeout_txt"></span> <i class="fa fa-refresh"></i> Belum menerima token?</button>
                                <button type="button" class="btn btn-success btn-sm kirim-pelaporan-lhkpn"><i class="fa fa-save"></i> Kirim</button>
                            </div>
                        </form>
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


$(document).ready(function() {
    var detik = 30;

    function hitung() {
        var timeout = setInterval(function() {
                        $('.kirim-ulang-token').prop('disabled', true);
                        $('#timeout_txt').text(detik);
        
                        /** Melakukan Hitung Mundur dengan Mengurangi variabel detik - 1 */
                        detik --;

                if(detik < 0) { 
                    
                        $('.kirim-ulang-token').prop('disabled', false);
                    $('#timeout_txt').text('');                              
                    clearInterval(timeout); 
                    // alert('habis');
                }

        }, 1000);
    }
    hitung();

    $(".kirim-pelaporan-lhkpn").on("click", (function() {
        var TOKEN_PELAPOR =  $('#TOKEN_PELAPOR').val();
        if(TOKEN_PELAPOR.trim()=="" || TOKEN_PELAPOR.trim()==null){
            swal('Token belum dimasukan','','warning')
            return;
        }
        //open loading//
        swal({
            title: 'Verifikasi Token',
            allowOutsideClick:false,
            allowEscapeKey:false,
            onOpen: () => {
                swal.showLoading()
            }
        })
        //open loading//
        var urll = '<?php echo base_url(); ?>portal/user/post_pelaporan_token';
        var form = $('#f-pelaporan')[0];
        var data = new FormData(form);
        $.ajax({
            url: urll,
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false, 
            contentType: false,
            cache: false,
            data: data,
            success: function(htmldata) {
                try{
                    var result = JSON.parse(htmldata);
                    if(result.status==1){
                        $('#TOKEN_PELAPOR').val('');
                        swal({
                            type: 'success',
                            title: 'Terima kasih atas partisipasi Saudara',
                            timer: 10000,
                            allowOutsideClick:false,
                            allowEscapeKey:false,
                            html:'<b>Halaman otomatis diarahkan ke beranda</b>',
                            showConfirmButton: false,
                            onClose: () => {                 
                                window.location.href = "<?php echo base_url(); ?>portal/user/login";
                            }
                        })

                    }else if(result.status==9){
                        $('#TOKEN_PELAPOR').val('');
                        swal('Maaf kode token yang Anda masukan salah, silahkan cek dan masukkan kembali (pastikan server code sesuai dengan yang ada di email token).','','warning')
                        return;
                    }else{
                        $('#TOKEN_PELAPOR').val('');
                        swal('Terjadi kesalahan sistem','','warning')
                        return;
                    }
                }catch(err) {
                    $('#TOKEN_PELAPOR').val('');
                    swal('Terjadi kesalahan sistem','','warning')
                    return;
                }
            }
        });
        // CloseModalBox2();
        // $('#FillingEditLaporan').modal('hide');
        return;
    }));


    $(".kirim-ulang-token").on("click", (function() {

        swal({
            title: 'Mohon Tunggu',
            html: 'Mengirim ulang token',
            allowOutsideClick:false,
            allowEscapeKey:false,
            onOpen: () => {
                swal.showLoading()
            }
        })

        var urll = '<?php echo base_url(); ?>portal/user/kirim_ulang_token';
        var form = $('#f-pelaporan')[0];
        var data = new FormData(form);
        $.ajax({
            url: urll,
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false, 
            contentType: false,
            cache: false,
            data: data,
            success: function(htmldata) {
                try{
                    var result = JSON.parse(htmldata);
                    if(result.status==1){
                        detik = 30;
                        hitung();
                        $('#TOKEN_PELAPOR').val('');
                        swal('Kode token telah berhasil dikirimkan kembali.','','success')
                        $('#server_code_text').text(result.server_code);
                    }else{
                        $('#TOKEN_PELAPOR').val('');
                        swal('Terjadi kesalahan sistem, coba lagi','','warning')
                        return;
                    }
                }catch(err) {
                    $('#TOKEN_PELAPOR').val('');
                    swal('Terjadi kesalahan sistem','','warning')
                    return;
                }
                    
            }
        });
        return;
    }));
});
    
</script>
