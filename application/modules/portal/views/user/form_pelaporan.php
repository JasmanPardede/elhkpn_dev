
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
            .red-label{
                color: red;
            }
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }
            .inputfile-1 + label {
                color: #004d1a;
            }

            .inputfile-1 + label {
                border: 1px solid #009933;
                background-color: #f1e5e6;
                padding: 0;
            }

            .inputfile-1:focus + label,
            .inputfile-1.has-focus + label,
            .inputfile-1 + label:hover {
                border-color: #006622;
            }

            .inputfile-1 + label span,
            .inputfile-1 + label strong {
                padding: 0.625rem 1.25rem;
            /* 10px 20px */
            }

            .inputfile-1 + label span {
                width: 200px;
                min-height: 2em;
                display: inline-block;
                text-overflow: ellipsis;
                white-space: nowrap;
                overflow: hidden;
                vertical-align: top;
            }

            .inputfile-1 + label strong {
                height: 100%;
                color: #fff;
                background-color: #009933;
                display: inline-block;
            }

            .inputfile-1:focus + label strong,
            .inputfile-1.has-focus + label strong,
            .inputfile-1 + label:hover strong {
                background-color: #006622;
            }

            @media screen and (max-width: 50em) {
            .inputfile-1 + label strong {
                display: block;
            }
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
                    <div class="col-lg-12">
                    <form id="f-pelaporan">
                        <input type="hidden" name="stateSession" value="<?php echo encrypt_username($id_lhkpn) ?>" />
                        <!--<h2 class="section-heading">Pelaporan Harta Kekayaan</h2>-->
                        <br>
                        <div class="box-body">
                            
                                <div class="form-group">
                                    <label>
                                        <u>IDENTITAS PENYELENGGARA NEGARA</u>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Nama</label>
                                        <input type="text" readonly  value="<?php echo $nama ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Instansi</label> 
                                        <input type="text"  readonly  value="<?php echo $instansi ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Jabatan</label>
                                        <input type="text"  readonly  value="<?php echo $jabatan ?>" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <u>IDENTITAS ANDA</u>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Nama <span class="red-label">*</span></label> <?= FormHelpPopOver('nama_psm'); ?>
                                        <input type="text" placeholder="Nama Anda" name="NAMA_PELAPOR" id="NAMA_PELAPOR" value="" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Nomor HP/Telp <span class="red-label">*</span></label> <?= FormHelpPopOver('telpon_psm'); ?>
                                        <input type="text" placeholder="Nomor HP/Telp" name="NOMOR_PELAPOR" id="NOMOR_PELAPOR"   value="" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Email <span class="red-label">*</span></label> <?= FormHelpPopOver('email_psm'); ?>
                                        <input type="email" placeholder="Alamat Email" name="EMAIL_PELAPOR" id="EMAIL_PELAPOR"  value="" class="form-control"/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <u>ISI INFORMASI</u>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                    <label>Informasi <span class="red-label">*</span></label> <?= FormHelpPopOver('informasi_psm'); ?>
                                    <textarea  name="ISI_PENGADUAN" id="ISI_PENGADUAN" class="form-control">
                                        <b>Informasi mengenai Tanah/Bangunan milik PN yang ingin dilaporkan</b>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <b>Informasi Mesin/Alat Transportasi yang ingin dilaporkan</b>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <b>Informasi Harta Bergerak yang ingin dilaporkan</b>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <b>Informasi Surat Berharga yang ingin dilaporkan</b>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <b>Informasi Kas / Setara Kas yang ingin dilaporkan</b>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <b>Informasi Harta Lainnya yang ingin dilaporkan</b>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <b>Informasi Hutang yang ingin dilaporkan</b>
                                        <ul>
                                            <li></li>
                                            <li></li>
                                        </ul>

                                    </textarea>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                 <div class="form-group">
                                    <label>
                                        <u>LAMPIRAN</u>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Lampiran <span class="red-label">*</span></label> <?= FormHelpPopOver('lampiran_psm'); ?>
                                        <br>
                                        <input type="file" name="file_pendukung_lhkpn[]" id="file_pendukung_lhkpn" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple  multiple data-allowed-file-extensions='["pdf", "jpg", "png","jpeg","tif","tiff","doc","docx","xls","xlsx","ppt","pptx"]' value="" />
                                        <label for="file_pendukung_lhkpn"><span></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Choose a file&hellip;</strong></label>
                                        <br>
                                        <span>(Tekan 'CTRL' dan click beberapa file yang akan di upload)</span>   
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                        <label>Keterangan <span class="red-label">*</span></label></label><br>
                                        <input type="text" class="form-control" name="file_pendukung_keterangan" id="file_pendukung_keterangan" value=""/>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                    <span>Total ukuran keseluruhan file: </span><b><span id="space_used">0</span><span> Kb</span></b>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10">
                                    <span>Batas maksimum ukuran keseluruhan file: <b>6.000 Kb</b></span>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                
                            </div>
                            <div class="pull-right" style="margin-right:8em">
                                <input type="hidden" name="act" value="doinsert">
                                <input type="hidden" name="via_pengumuman" value="<?php echo $via_pengumuman; ?>">
                                <button type="reset" class="btn btn-danger btn-sm "><i class="fa fa-remove"></i> Batal</button>
                                <button type="button" class="btn btn-success btn-sm kirim-pelaporan-lhkpn"><i class="fa fa-share"></i> Kirim</button>
                                
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
        <!-- CK EDITOR CUSTOM -->
        <script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
        <script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>

<script type="text/javascript">    
    $(function() {
        $('.over').popover();
           $('.over')
           .mouseenter(function(e){
              $(this).popover('show'); 
           })
           .mouseleave(function(e){
              $(this).popover('hide'); 
           });  
    })

CKEDITOR.config.customConfig = "<?php echo base_url();?>plugins/ckeditor/config-announ-pelaporan.js?v=<?=$this->config->item('cke_version');?>";
CKEDITOR.replace( 'ISI_PENGADUAN', {
    entities : false
});


$(document).ready(function() {
    $(function() {
        $('[data-toggle="popover"]').popover()
    })


    $("#NOMOR_PELAPOR").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


    $("#file_pendukung_lhkpn").change(function() {
        var inputFile = document.getElementById('file_pendukung_lhkpn');

        var pathFile = inputFile.value; 
        var ekstensiOk = /(\.pdf|\.jpg|\.png|\.jpeg|\.tif|\.tiff|\.doc|\.docx|\.xls|\.xlsx|\.ppt|\.pptx)$/i;
        if(!ekstensiOk.exec(pathFile)){
            alert('Silakan upload file yang memiliki ekstensi .pdf, .jpg, .png, .jpeg, .tif, .tiff, .doc, .docx, .xls, .xlsx, .ppt, .pptx');
            inputFile.value = '';
            return false;
        }

        var all_file = inputFile.files;
        var size_total = 0; 

        $.each(all_file, function(index, value ) {
            size_total += value.size;  
        });

        if(size_total > 6000000){ 
            alert('Silakan upload file size maksimal 6 Mb');
            inputFile.value = '';
        }

        var total_space = number_format(size_total,0,",",".");
        total_space = total_space.substr(0, 5);

        $('#space_used').text(total_space);
    });

    $( '.inputfile' ).each( function(){
		var $input	 = $( this ),
			$label	 = $input.next( 'label' ),
			labelVal = $label.html();

		$input.on( 'change', function( e )
		{
			var fileName = '';

			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else if( e.target.value )
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				$label.find( 'span' ).html( fileName );
			else
				$label.html( labelVal );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
	});

    function number_format (number, decimals, decPoint, thousandsSep) { 
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        var n = !isFinite(+number) ? 0 : +number
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        var s = ''

        var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec)
        return '' + (Math.round(n * k) / k)
            .toFixed(prec)
        }

        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
        }

        return s.join(dec)
    }


    $(".kirim-pelaporan-lhkpn").on("click", (function() {
        var NAMA_PELAPOR =  $('#NAMA_PELAPOR').val();
        var NOMOR_PELAPOR =  $('#NOMOR_PELAPOR').val();
        var EMAIL_PELAPOR =  $('#EMAIL_PELAPOR').val();
        var ISI_PENGADUAN =   CKEDITOR.instances.ISI_PENGADUAN.getData();
        var FILE_LAMPIRAN = $('#file_pendukung_lhkpn').val();
        var KETERANGAN = $('#file_pendukung_keterangan').val();

        if(NAMA_PELAPOR.trim()=="" || NAMA_PELAPOR.trim()==null){
            swal('Isian tidak lengkap','','warning')
            return;
        }
        if(NOMOR_PELAPOR.trim()=="" || NOMOR_PELAPOR.trim()==null){
            swal('Isian tidak lengkap','','warning')
            return;
        }
        if(EMAIL_PELAPOR.trim()=="" || EMAIL_PELAPOR.trim()==null){
            swal('Isian tidak lengkap','','warning')
            return;
        }
        if( !isValidEmailAddress(EMAIL_PELAPOR) ) {
            swal('Format email tidak sesuai','','warning')
            return;
        }
        if(ISI_PENGADUAN.trim()=="" || ISI_PENGADUAN.trim()==null){
            swal('Isian tidak lengkap','','warning')
            return;
        }
        if(FILE_LAMPIRAN.trim()=="" || FILE_LAMPIRAN.trim()==null){
            swal('Isian tidak lengkap','','warning')
            return;
        }
        if(KETERANGAN.trim()=="" || KETERANGAN.trim()==null){
            swal('Isian tidak lengkap','','warning')
            return;
        }
        
        if(ISI_PENGADUAN =="<p><strong>Informasi mengenai Tanah/Bangunan milik PN yang ingin dilaporkan</strong></p><ul><li>&nbsp;</li><li>&nbsp;</li></ul><p><strong>Informasi Mesin/Alat Transportasi yang ingin dilaporkan</strong></p><ul><li>&nbsp;</li><li>&nbsp;</li></ul><p><strong>Informasi Harta Bergerak yang ingin dilaporkan</strong></p><ul><li>&nbsp;</li><li>&nbsp;</li></ul><p><strong>Informasi Surat Berharga yang ingin dilaporkan</strong></p><ul><li>&nbsp;</li><li>&nbsp;</li></ul><p><strong>Informasi Kas / Setara Kas yang ingin dilaporkan</strong></p><ul><li>&nbsp;</li><li>&nbsp;</li></ul><p><strong>Informasi Harta Lainnya yang ingin dilaporkan</strong></p><ul><li>&nbsp;</li><li>&nbsp;</li></ul><p><strong>Informasi Hutang yang ingin dilaporkan</strong></p><ul><li>&nbsp;</li><li>&nbsp;</li></ul>"){
            swal('Informasi Belum Diisi','','warning')
            return;
        }
        //open loading//
        swal({
            title: 'Mohon Tunggu',
            html: 'Informasi anda sedang diproses',
            allowOutsideClick:false,
            allowEscapeKey:false,
            onOpen: () => {
                swal.showLoading()
            }
        })
        //open loading//
        var urll = '<?php echo base_url(); ?>portal/user/post_pelaporan_lhkpn';
        var form = $('#f-pelaporan')[0];
        var data = new FormData(form);
        data.append('ISI_PENGADUAN', ISI_PENGADUAN);

        $.ajax({
            url: urll,
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false, 
            contentType: false,
            cache: false,
            data: data,
            success: function(htmldata) {
                var result = JSON.parse(htmldata);
                if(result.status==1){
                    window.location.href = "<?php echo base_url(); ?>portal/user/pelaporan_konfirmasi/"+result.setState+"/<?php echo protectUrl(); ?>";
                }else if(result.status==9){
                    window.location.href = "<?php echo base_url(); ?>Errcontroller";
                }else{
                    swal('Terjadi kesalahan pada server','','warning')
                    return;
                }
            }
        });
        // CloseModalBox2();
        // $('#FillingEditLaporan').modal('hide');
        return;
    }));

     $(".upload-file-pelaporan").on("click", (function() {
        var get_file = $('#file_pendukung_lhkpn').val();
        var get_keterangan = $('#file_pendukung_keterangan').val();
        if(get_file==null || get_file==''){
            swal('File belum dipilih','','warning')
            return;
        }
        if(get_keterangan==null || get_keterangan==''){
            swal('Keterangan belum diisi','','warning')
            return;
        }
        var urll = '<?php echo base_url(); ?>portal/user/post_pelaporan_lhkpn_file';
        var form = $('#f-file-pelaporan')[0];
        var data = new FormData(form);
        data.append("space_used", $('#space_used').text());
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
                            var data = result.data;
                            var keteranganParsing = "<input type='hidden' name='keteranganFile[]' value='"+data.state_keterangan+"'/>";
                            var dataParsing = "<input type='hidden' name='dataFile[]' value='"+data.state_username+"'/>";
                            var removeButton = "<a class=\"remove-button\"  onclick=\"deleteFilePelaporan('"+data.state_username+"','"+data.state_id+"','"+data.state_file_size+"')\"><span class=\"glyphicon glyphicon-trash text-danger\"></span></a>";
                            var td1 = $("<td class=\"td-nama-file\">"+keteranganParsing+dataParsing+data.state_name+"</td>");
                            var td2 = $("<td class=\"td-keterangan-file\">"+data.state_keterangan+"</td>");
                            var td3 = $("<td class=\"td-size-file\">"+data.state_size+"</td>");
                            var td4 = $("<td class=\"td-aksi-file\">"+removeButton+"</td>");
                            var tr = $("<tr id='"+data.state_id+"'></tr>");
                            $(tr).append(td1);
                            $(tr).append(td2);
                            $(tr).append(td3);
                            $(tr).append(td4);
                            $("#tableListFileUpload tbody").prepend(tr);
                            $('#space_used').text(data.space_used);
                        }else if(result.status==9){
                            swal('Data tidak diijinkan!','','error')
                            return;
                        }else if(result.status==8){
                             swal('Ukuran file melebihi batas maksimum!','','error')
                            return;
                        }else{
                            swal('Terjadi kesalahan pada server','','warning')
                            return;
                        }
                }catch(err) {
                    swal('Terjadi kesalahan pada server','','warning')
                    return;
                }
                
            }
        });
        $('#file_pendukung_keterangan').val('');
        $('#file_pendukung_lhkpn').val('');
        $('#modalUploadFile').modal('hide');
        return;
    }));


});

function deleteFilePelaporan(state_username,state_id,file_size) {
        $.ajax({
            url: '<?php echo base_url(); ?>portal/user/delete_pelaporan_lhkpn_file',
            async: true,
            type: 'POST',
            data: {state_username: state_username,space_used:$('#space_used').text(),file_size:file_size},
            success: function(htmldata) {
                try{
                    var result = JSON.parse(htmldata);
                    if(result.status==1){
                         var data = result.data;
                         $('#'+state_id).remove();
                         $('#space_used').text(data.space_used);
                    }else{
                        swal('Terjadi kesalahan pada server','','warning')
                        return;
                    }
                }catch(err) {
                    swal('Terjadi kesalahan pada server','','warning')
                    return;
                }
            }
        });
       return;
      }

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}
</script>
