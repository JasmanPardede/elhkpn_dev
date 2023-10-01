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
        <link href="<?php echo base_url('img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon">
        <link href="<?php echo base_url(); ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url(); ?>plugins/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>plugins/AdminLTE-2.1.1/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- iCheck -->
        <link href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
        <!-- Sweet Alert -->
        <link href="<?php echo base_url(); ?>plugins/sweet-alert/dist/sweetalert.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <b>LHKPN </b> <img src="<?php echo base_url(); ?>images/logo.png" style="width:130px;">
            </div><!-- /.login-logo -->
            <div class="login-box-body" style="border-radius: 5px;">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="<?php echo base_url(); ?>index.php/auth/dologin" method="post" id="frm_login">
                    <div class="form-group has-feedback">
                      <!-- <input type="email" class="form-control" placeholder="Email"/> -->
                        <label>Username / NIK </label>
                        <input type="text" class="form-control" placeholder="Username / NIK" id="username" name="username" required/>
                        <!-- <span class="glyphicon glyphicon-envelope form-control-feedback"></span> -->
                    </div>
                    <div class="form-group has-feedback">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12" style="text-align: center;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="text-align: center;" id="img_captcha"><?php echo $image_captcha; ?></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="text" name="usr_captcha"  placeholder="Isikan kode diatas" id="usr_captcha" class="usr_captcha"
                                               required style="width: 110px;" value=""/>
                                        <button type="button" class="btn" title="Reload Captcha" onClick="">
                                            <i alt="Reload Captcha" class="glyphicon glyphicon-refresh"></i></button>
                                    </td>
                                </tr>
                            </table>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">    
                            <div class="checkbox icheck">
                                <label>
                                  <!-- <input type="checkbox"> Remember Me -->
                                    <a href="<?php echo base_url(); ?>index.php/auth/lupapassword">Lupa Password</a>
                                </label>
                            </div>                        
                        </div><!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" id="btn-signin">Sign In</button>
                        </div><!-- /.col -->
                    </div>
                    <input type="hidden" name="hdn_captcha" id="hdn_captcha">
                </form>

            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="<?php echo base_url(); ?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <!-- Sweet Alert -->
        <script src="<?php echo base_url(); ?>plugins/sweet-alert/dist/sweetalert.min.js" type="text/javascript"></script>

        <!-- include the script -->
        <script src="<?php echo base_url(); ?>plugins/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/alertifyjs/css/alertify.min.css" />
        <!-- include a theme -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/alertifyjs/css/themes/default.min.css" />

        <script>
                                    $(function() {
                                        $('input').iCheck({
                                            checkboxClass: 'icheckbox_square-blue',
                                            radioClass: 'iradio_square-blue',
                                            increaseArea: '20%' // optional
                                        });
                                    });
        </script>
        <script language="javascript">
            $(document).ready(function() {
                $("#username").focus();


                $("#frm_login").submit(function() {
                    usr = $("#username").val();
                    pwd = $("#password").val();
                    //captcha = $('#usr_captcha').val();
                    a = $('#hdn_captcha').val();
                    if (a == '') {
                        sec = '<?php echo $random_word; ?>';
                    } else {
                        sec = $('#hdn_captcha').val();
                    }
                    //alert(usr+pwd+captcha+sec);
                    // alert(usr+' '+pwd+' '+'Login Berhasil');
                    $.post("<?php echo base_url(); ?>index.php/auth/dologin", {usr: usr, pwd: pwd})
                            .done(function(data) {
                                //alert( "Data Loaded: " + data );
                                if (data == 1) {
                                    location.href = '<?php echo base_url(); ?>index.php';
                                } else if (data == 2) {
                                    $('#frm_login input').val('');
                                    //reload_captcha();
                                    $("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify.js-0.3.11/themes/alertify.bootstrap.css");
                                    alertify.error('Login Gagal! <br> Kode Capcha yg anda masukkan salah!');
                                    $.get('<?php echo base_url(); ?>index.php/auth/loginalert', function(data) {
                                        alertify.error(data);
                                    });
                                    errorsound.playclip();
                                } else if (data == 3) {
                                    swal({
                                        title: "Informasi",
                                        text: "User anda masih aktif. \n Apakah anda ingin memulai sessi baru (login) di komputer ini?",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Yes",
                                        cancelButtonText: "No",
                                        closeOnConfirm: false
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            $.post("<?php echo base_url(); ?>index.php/auth/is_login/" + usr).done(function(msg) {
                                                $.post("<?php echo base_url(); ?>index.php/auth/dologin", {usr: usr, pwd: pwd}).done(function(data) {
                                                    //alert( "Data Loaded: " + data );

                                                    if (data == 1) {
                                                        location.href = '<?php echo base_url(); ?>index.php/welcome';
                                                    } else if (data == 2) {
                                                        $('#frm_login input').val('');
                                                        //reload_captcha();
                                                        $("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify.js-0.3.11/themes/alertify.bootstrap.css");
                                                        alertify.error('Login Gagal! <br> Kode Capcha yg anda masukkan salah!');
                                                        $.get('<?php echo base_url(); ?>index.php/auth/loginalert', function(data) {
                                                            alertify.error(data);
                                                        });
                                                        errorsound.playclip();
                                                    } else {
                                                        $('#frm_login input').val('');
                                                        //reload_captcha();
                                                        $("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify.js-0.3.11/themes/alertify.bootstrap.css");
                                                        alertify.error('Login Gagal! <br> Periksa Kembali User Password Anda!');
                                                        $.get('<?php echo base_url(); ?>index.php/auth/loginalert', function(data) {
                                                            alertify.error(data);
                                                        });
                                                        errorsound.playclip();
                                                    }
                                                });
                                            });
                                            // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                                        }
                                    });
                                } else {
                                    $('#frm_login input').val('');
                                    //reload_captcha();
                                    $("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify.js-0.3.11/themes/alertify.bootstrap.css");
                                    alertify.error('Login Gagal! <br> Periksa Kembali User Password Anda!');
                                    $.get('<?php echo base_url(); ?>index.php/auth/loginalert', function(data) {
                                        alertify.error(data);
                                    });
                                    errorsound.playclip();
                                }
                            });
                    return false;
                });

                var msg = "";
                var elements = document.getElementsByTagName("INPUT");

                for (var i = 0; i < elements.length; i++) {
                    elements[i].oninvalid = function(e) {
                        if (!e.target.validity.valid) {
                            switch (e.target.id) {
                                case 'password' :
                                    e.target.setCustomValidity("Silahkan Isi Password");
                                    break;
                                case 'username' :
                                    e.target.setCustomValidity("Username harus diisi");
                                    break;
                                default :
                                    e.target.setCustomValidity("Kode Capcha harus diisi!");
                                    break;
                            }
                        }
                    };
                    elements[i].oninput = function(e) {
                        e.target.setCustomValidity(msg);
                    };
                }



                $('.form-control').keypress(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        $("#btn-signin").trigger("click");
                    }
                });
            });
            function reload_captcha() {
                $.post("<?php echo base_url(); ?>index.php/auth/reload_captcha").done(function(msg) {
                    var result = JSON.parse(msg);
                    $('#img_captcha').html(result.image);
                    $('#hdn_captcha').val(result.value);
                    $('.usr_captcha').val(result.value);
                });
            }
        </script>    
    </body>
</html>