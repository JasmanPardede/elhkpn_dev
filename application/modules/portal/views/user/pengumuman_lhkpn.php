
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
                           <br><br>
                        </li>
                    </ul>
                   
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <br>
        <br><br>

        <section id="announ">
            <div class="container" id="announ-page">
                <div class="row">
                    <div class="col-lg-12">
                        <!--<h2 class="section-heading">e-Announcement</h2><br>-->
                        <table style="width:100%">
                            <tr height="200">
                                <td style="width:20%">
                                    <img src="<?php echo $LOGO_LEMBAGA ?>" width="200"/>
                                </td>
                                <td>
                                    <h3 class="section-heading" style="text-align:center;line-height:1.5em">e-Announcement LHKPN <br><?php echo $NAMA_LEMBAGA  ?></h3>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo base_url(); ?>portal/user/check_search_pengumuman_lhkpn#announ">
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
                                                <input type="text" autocomplete="off" class="year-picker form-control" name="CARI[TAHUN]"  onkeydown="return false"  placeholder="TAHUN" value="<?php echo @$CARI['TAHUN']; ?>" id="CARI_TAHUN">
                                            </div>
                                            <div class="col-sm-3">

                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Lembaga :</label>
                                            <div class="col-sm-6">-->
                                            <input type="hidden" class="form-control" name="CARI[JUMLAH_WL]"  value="<?php echo $CHART_RESULT['Jumlah_WL']; ?>" id="jumlah_wL">
                                            <input type="hidden" class="form-control" name="CARI[JUMLAH_BELUM_LAPOR]"  value="<?php echo $CHART_RESULT['Jumlah_Belum_Lapor']; ?>" id="jumlah_belum_lapor">
                                            <input type="hidden" class="form-control" name="CARI[JUMLAH_SUDAH_LAPOR]"  value="<?php echo $CHART_RESULT['Jumlah_Sudah_Lapor']; ?>" id="jumlah_sudah_lapor">
                                                <input type="hidden" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA']; ?>" id="CARI_LEMBAGA">
                                            <!--</div>
                                        </div>
                                    </div>-->
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Kode Keamanan :</label>
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
                                            <span style="overflow:hidden;">
                                                <div id="captcha-announ" name="captcha-announ" class="col-sm-3" style="transform:scale(0.9);-webkit-transform:scale(0.9);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
                                                <!--<div id="captcha-announ" class="col-sm-3 g-recaptcha"
                                                style="transform:scale(1);-webkit-transform:scale(1);transform-origin:0 0;-webkit-transform-origin:0px 0px">
                                                </div>-->
                                            </span>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"></label>
                                            <div class="col-sm-3">
                                                <!--<input type="text" id="captcha-announ" name="captcha-announ" class="form-control " placeholder="Kode Captcha" required/>-->
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                                                    <button type="button" id="btn-clear" class="btn btn-info"> Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6" style="padding:8em">
                                    
                                    <?php echo $IFRAME_CHART; ?>
<!--                                    <table>
                                     <tr>
                                        <td style="width:29em">
                                        <canvas id="myChart"></canvas>
                                        </td>
                                        <td style="vertical-align:top;padding-top:5px;font-weight:bold;line-height:1.8em">
                                        <strong style="font-size:1.2em">Informasi:</strong><br>
                                        Wajib Lapor: <?php // echo number_format($CHART_RESULT['Jumlah_WL'],0,",","."); ?>
                                            <li style="list-style:none"><i class="glyphicon glyphicon-stop" style="color:rgba(44, 175, 55, 0.8)"></i> Sudah lapor: <?php // echo number_format($CHART_RESULT['Jumlah_Sudah_Lapor'],0,",","."); ?> (<?php // echo substr($CHART_RESULT['Jumlah_Sudah_Lapor'] / $CHART_RESULT['Jumlah_WL'] * 100,0,4) ?>%)</li>
                                            <li style="list-style:none"><i class="glyphicon glyphicon-stop" style="color:rgba(209, 87, 87, 0.8)"></i> Belum lapor: <?php // echo number_format($CHART_RESULT['Jumlah_Belum_Lapor'],0,",","."); ?> (<?php // echo substr($CHART_RESULT['Jumlah_Belum_Lapor'] / $CHART_RESULT['Jumlah_WL'] * 100,0,4) ?>%)</li>
                                        </td>
                                     </tr>
                                    </table>-->
                                </div>
                            </div>
                        </form>
                        <table style="width:100%">
                            <tr>
                                <td style="width:50%;padding:1em;vertical-align:top">
                                    <p style="font-size:12px;">
                                        *Informasi Pengumuman Harta Kekayaan Penyelenggara Negara yang tercantum dalam situs e-Announcement LHKPN ini adalah sesuai dengan yang telah dilaporkan oleh Penyelenggara Negara dalam LHKPN dan hanya untuk tujuan informasi umum. KPK tidak bertanggung jawab atas informasi Harta Kekayaan Penyelenggara Negara yang bersumber dari situs dan/atau media lainnya. Apabila terdapat perbedaan informasi antara pengumuman yang tercantum dalam situs e-Announcement dengan informasi yang berasal dari situs dan/atau media lainnya, maka informasi yang dianggap valid adalah informasi yang tercantum dalam situs e-Announcement ini.
                                    </p>
                                </td>
                                <td style="padding:1em;vertical-align:top">
                                    <p style="font-size:12px;">
                                        Situs ini hanya menampilkan <strong>Pengumuman Harta Kekayaan Penyelenggara Negara</strong> atas LHKPN yang disampaikan kepada KPK menggunakan <strong>Aplikasi e-LHKPN</strong> (dimulai dari LHKPN Tahun 2017 dan seterusnya) dan menggunakan <strong>LHKPN Model KPK-A dan Model KPK-B.</strong>
                                    </p>
                                </td>
                            </tr>
                        </table>

                         <!--<div class="box-body">
                                <div class="col-lg-6">
                                </div>
                                <div class="col-lg-6">
                                </div>
                            </div>-->
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                            <thead>
                                <tr>
                                    <!--<th width="30"><input type="checkbox" onClick="chk_all(this);" /></th>-->
                                    <th width="30">No.</th>
                                    <!--<th>No. Agenda</th>-->
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th width="140">Tanggal Lapor</th>
                                    <th width="140">Total Harta Kekayaan</th>
                                    <!--<th class="hidden-xs hidden-sm">Jenis Laporan</th>-->
                                    <!--<th>Hasil Verifikasi</th>-->
                                    <th width="100">Aksi</th>
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
                                    foreach ($items as $item) {
                                        $agenda = date('Y', strtotime($item->tgl_kirim_final)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                                        ?>
                                        <tr>
                                            <!-- <td class="agenda" style="display: none;"><?php date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN ?></td> -->
                                            <td class="lhkpn" style="display: none;"><?php echo substr(md5($item->ID_LHKPN), 5, 8); ?></td>
                                            <td class="lhkpnori" style="display: none;"><?php echo $item->ID_LHKPN; ?></td>
                                            <td class="nik" style="display: none;"><?php echo $item->NIK; ?></td>
                                            <td class="tgl_lapor" style="display: none;"><?php echo date('Y', strtotime($item->tgl_lapor)); ?></td>
                                            <td class="jenis_laporan" style="display: none;"><?php echo $item->JENIS_LAPORAN == '4' ? 'R' : 'K'; ?></td>
                                            <!--<td> <?php echo (in_array($item->ID_LHKPN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="' . $item->ID_LHKPN . '" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="' . $item->ID_LHKPN . '" onclick="chk(this);" />') ?></td>-->
                                            <td><?php echo ++$i; ?>.</td>
        <!--                                    <td class="agenda">
                                            <?php // echo $agenda; ?></a>
                                            </td>-->
                                            <td><?php echo $item->NAMA; ?></a></td>
                                            <td><?php echo $item->NAMA_JABATAN; ?></td>
                                            <td><?php echo $item->UK_NAMA; ?></td>
                                            <td><?php echo tgl_format($item->tgl_lapor); ?></td>
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
                                            <?php if ($this->session->userdata('ceksesi')) { ?>
                                                <td><a id="DownloadPDFII" title="Preview cetak pengumuman" class="btn btn-sm btn-success nodownl" data-id="<?php echo encrypt_username($item->ID_LHKPN); ?>" ><i class="fa fa-download"></i></a>
                                                <a class="btn btn-danger btn-sm" title="Kirim Informasi Harta" href="<?php echo base_url(); ?>portal/user/pelaporan/<?php echo encrypt_username(json_encode(array($item->ID_LHKPN,$item->NAMA,$item->INST_NAMA,$item->NAMA_JABATAN,'via_pengumuman'))); ?>/<?php echo protectUrl(); ?>" ><i class="fa fa-bullhorn" style="color:white;"></i></a>
                                                </td>
                                                <?php } else { ?>
                                                <td><a id="DownloadPDFII" title="Preview cetak pengumuman" class="btn btn-sm btn-success yesdownl" data-id="<?php echo encrypt_username($item->ID_LHKPN); ?>" ><i class="fa fa-download"></i></a>
                                                <a class="btn btn-danger btn-sm" title="Kirim Informasi Harta" href="<?php echo base_url(); ?>portal/user/pelaporan/<?php echo encrypt_username(json_encode(array($item->ID_LHKPN,$item->NAMA,$item->INST_NAMA,$item->NAMA_JABATAN,'via_pengumuman'))); ?>/<?php echo protectUrl(); ?>" ><i class="fa fa-bullhorn" style="color:white;"></i></a>
                                                </td>
                                                <?php } ?>
                                        </tr>
                                        <?php
                                        $end = $i;
                                    }
                                    ?>
                                <?php } else { ?>
                                    <tr id="not-found">
                                        <td colspan="8" align="center"><strong>Belum ada data</strong></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
        <script src="<?php echo base_url(); ?>portal-assets/js/remodal.min.js"></script>
        <script src="<?php echo base_url();?>portal-assets/js/select2.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url();?>plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

            <script>
var ctx = document.getElementById("myChart");

var myDoughnutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
            display:false,
            labels: [
                'Sudah lapor',
                'Belum Lapor',
            ],
            datasets: [{
                data: [<?php echo $CHART_RESULT['Jumlah_Sudah_Lapor']; ?>, <?php echo $CHART_RESULT['Jumlah_Belum_Lapor']; ?>, ],
                backgroundColor: [
                    'rgba(44, 175, 55, 0.8)',
                    'rgba(209, 87, 87, 0.8)'
                ],
                borderColor: [
                    'white',
                    'white'
                ],
                borderWidth:[
                    1,1
                ],
            }],
            
        },
        options: {
            scales: {
            },
            cutoutPercentage:60,
            rotation:0,
            legend: {
            display: false,
            },
            title: {
                display: true,
                fontSize:20,
                text: 'Peta Kepatuhan'
            }
        }
});

</script>





<script type="text/javascript">    
// $(window).bind({
//   beforeunload: function(ev) {
//     window.location.replace("<?php echo base_url(); ?>Errcontroller");
//   },
//   unload: function(ev) {
//     window.location.replace("<?php echo base_url(); ?>Errcontroller");
//   }
// });

            function timer_download(id_lhkpn, urll) {
//                alert(id_lhkpn+'=id lhkpn');return false;
                $.post('<?php echo base_url(); ?>portal/user/setid/', {id: id_lhkpn}, function(data) {
                    var win = window.open('<?php echo base_url(); ?>portal/user/pengumuman_timer/', '_blank');
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
                    var win = window.open('<?php echo base_url(); ?>portal/user/PreviewAnnoun/', '_blank');
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
    
            var options = { hashTracking: false };
            var inst = $('[data-remodal-id=modal-notice]').remodal(options);

            $('.year-picker').datepicker({
                orientation: "left",
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });

            $("#ajaxFormCari").submit(function(e) {
                  var stateNama = $('#CARI_NAMA').val();
                  var stateLembaga = $('#CARI_LEMBAGA').val();
                  //var recaptcha = document.forms["ajaxFormCari"]["g-recaptcha-response"].value;
                  if(stateNama==" " || stateNama=="  " || stateNama=="   "  || stateNama==null){
                    e.preventDefault();
                    alert('Field nama masih kosong!');
                    return true;
                  }
                  /*
                  if (recaptcha == ""){
                      alert("Please fill reCAPTCHA");
                      return false;
                    }
                    */
                //   if(stateLembaga=="" || stateLembaga==" "){
                //     e.preventDefault();
                //     alert('Lembaga belum dipilih!');
                //     return true;
                //   }

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
                    var urll = "<?php echo base_url(); ?>portal/user/pengumuman_timer/";
                    timer_download(id_lhkpn, urll)
                }));
});
</script>
<!--
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
<script type="text/javascript">      
    var captcha_announ1;
    var myCallBack = function() {

        //Render the recaptcha2 on the element with ID "captcha_announ"
        captcha_announ = grecaptcha.render('captcha-announ', {
        'sitekey' : '6Ler104UAAAAAIy94JTYV-yLDuoklciSupbbD4-C', //Replace this with your Site key
        'theme' : 'light',
        'callback' : correctCaptchaAnnoun
        });
    };

    var correctCaptchaAnnoun = function(response) {
        if(response !== '') {
            $('#hdn_captcha_announ').val('success');
        }
    };
    
</script>
-->