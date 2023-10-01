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
        <p class="login-box-msg">Lupa Password</p>
        <!-- <form action="../../index2.html" method="post"> -->
          <div class="form-group has-feedback">
            <!-- <input type="email" class="form-control" placeholder="Email"/> -->
			<form id="ajaxFormReset">
                <div class="body-box">
                    <!-- <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input type="text" class="form-control" placeholder="Username" id="username" name="username" autocomplete="off"/></div>
                    </div> -->
                    <div class="form-group">
                        <label for="exampleInputEmail1">Token Kadaluarsa / Token tidak terdaftar</label>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="act" value="doresetpassword">
                            <!-- <button type="submit" class="btn btn-primary btn-block btn-flat" id="btn-reset">Reset Password</button> -->
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-block btn-flat" onClick="reload();">Back</button>
                        </div>
                    </div>
                </div>
          </div>
			</form>
			<!-- </form> -->
			<br>
			<span class="loading"></span>

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
    
  </body>
</html>
<script type="text/javascript">
  function reload(){
    location.href = '<?php echo base_url();?>index.php';
  }
</script>