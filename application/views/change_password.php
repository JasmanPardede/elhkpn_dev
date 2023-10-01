<?php
// echo sha1(md5(123));
// echo time();
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/login
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>LHKPN</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?=base_url();?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?=base_url();?>plugins/font-awesome-4.3.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?=base_url();?>plugins/AdminLTE-2.1.1/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?=base_url();?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>css/main.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-page">
<div id="loader_area">
    <div id="loader_page">
        <div id="loaderCircle"><img src="<?php echo base_url() ?>images/loader_big.gif" /></div>
    </div>
</div>
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>LHKPN</b> <img src="<?=base_url();?>images/logo.png" style="width:130px;"></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body" style="border-radius: 5px;">
        Anda diharuskan merubah password saat login pertama kali!
        <div id="wrapperFormEdit">
            <form class="form-horizontal" method="post" id="ajaxFormPassword" action="<?php echo base_url().'index.php/auth/dochangepassword'; ?>">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Password Lama <span class="red-label">*</span>:</label>
                    <div class="col-sm-12">
                        <input required class="form-control" type='password' name='password_lama' id='password_lama' placeholder="Password Lama" />
                        <span class="help-block"><font id='valid_password' style='display:none;' color='red'>Password tidak valid</font></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Password Baru <span class="red-label">*</span>:</label>
                    <div class="col-sm-12">
                        <input required class="form-control" type='password' name='password_baru' id='password_baru' placeholder="Password Baru" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Ulangi Password <span class="red-label">*</span>:</label>
                    <div class="col-sm-12">
                        <input required class="form-control" type='password' name='ulangi_password' id='ulangi_password' placeholder="Ulangi Password" />
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Ubah Password">
                </div>
            </form>
        </div>

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="<?=base_url();?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?=base_url();?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- iCheck -->
<script src="<?=base_url();?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>

<!-- include the script -->
<script src="<?=base_url();?>plugins/alertifyjs/alertify.min.js"></script>
<!-- include the style -->
<link rel="stylesheet" href="<?=base_url();?>plugins/alertifyjs/css/alertify.min.css" />
<!-- include a theme -->
<link rel="stylesheet" href="<?=base_url();?>plugins/alertifyjs/css/themes/default.min.css" />

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
<script language="javascript">
    $(document).ready(function () {
        //ketika submit button d click
        $("#ajaxFormPassword").submit(function () {
            $('#loader_area').show();
            password_lama   = $("#password_lama").val();
            password_baru   = $('#password_baru').val();
            ulangi_password = $('#ulangi_password').val();
            $.post("<?php echo base_url();?>index.php/auth/dochangepassword", {
                password_lama: password_lama,
                password_baru: password_baru,
                ulangi_password: ulangi_password
            })

                    .done(function (data) {
                        $('#loader_area').hide();
                        $('#ajaxFormPassword input[type="password"]').val('');


                        if (data == 1) {
                            $("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify.js-0.3.11/themes/alertify.bootstrap.css");
                            alertify.success('Password berhasil diubah!');
                            location.href = '<?php echo base_url();?>index.php';
                        }else{
                            $("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify.js-0.3.11/themes/alertify.bootstrap.css");
                            alertify.error(data);
                        }
                    });
            return false;
        });

        $('.form-control').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                $("#btn-signin").trigger("click");
            }
        });
    });
</script>
</body>
</html>