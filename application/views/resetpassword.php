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
        <a href="../../index2.html"><b>LHKPN</b> <img src="<?=base_url();?>images/logo.png" style="width:130px;"></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body" style="border-radius: 5px;">
        <p class="login-box-msg">Reset Password</p>
        <!-- <form action="../../index2.html" method="post"> -->
          <div class="form-group has-feedback">
            <!-- <input type="email" class="form-control" placeholder="Email"/> -->
            <form class="formPassword" id="ajaxFormReset" action="javascript:void(0);">
            	<?php if($status === 'sukses'){ ?>
					<div class="form-group has-feedback">
		            	<input type="password" class="form-control" placeholder="Password" id="pass" name="password" required/>
		            </div>
		            <div class="form-group has-feedback">
		            	<input type="password" class="form-control" placeholder="Confirm Password" id="conpass" name="confirmpass" required/>
		            </div>
		            
				  	<div class="pull-right">
		            	<input type="hidden" name="act" value="doresetpassword">
		            	<button type="submit" class="btn btn-primary btn-block btn-flat" id="btn-reset">Update Password</button>
		        	</div>
				
				<?php }else{ ?>
					<div class="form-group has-feedback">
						Maaf status sudah Expired !!!
					</div>
				<?php } ?>
			</form>
			<form id="content-sukses" style="display:none;">
				<div class="form-group has-feedback">
	            	Update Password Baru Berhsil. Silahkan login kembali dengan menekan tombol di bawah.
	            </div>
				<div align="center">
	            	<a href="<?= base_url(); ?>index.php/auth/" class="btn btn-primary btn-block btn-flat" id="btn-reset">Login</a>
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
	       $("#btn-reset").click(function () {
	       		var pass 	= $('#pass').val();
	       		var conpass = $('#conpass').val();
	       		if(pass != conpass){
	       			alert('Password harus sama dengan Confirm Password');
	       		}else{
       				var reset    = '<?= $REQUEST_RESET; ?>';
       				var key 	 = '<?= $REQUEST_RESET_KEY; ?>';
       				var link     = '<?= base_url(); ?>index.php/auth/doupdtPasswordNew';
       				var redirect = '<?= base_url(); ?>index.php/auth';
       				var xdata    = { password : pass, reqReset : reset , reqKey : key }
       				$.post(link, xdata, function(pesan){
       					if(pesan == 'berhasil'){
       						$('.login-box-msg').text('Message');
       						$('#formPassword').hide();
       						$('#content-sukses').show();
       					}else{
       						$('#formPassword').show();
       						$('#content-sukses').hide();
       					}
       				});
	       		}
				
	        });

	        $("#btn-signin").click(function () {
	            usr = $("#username").val();
	            pwd = $("#password").val();
	            // alert(usr+' '+pwd+' '+'Login Berhasil');
	            $.post("<?php echo base_url();?>index.php/auth/dologin", {usr: usr, pwd: pwd})
	                .done(function (data) {
	                    // alert( "Data Loaded: " + data );
	                    if (data == 1) {
	                        location.href = '<?php echo base_url();?>index.php';
	                    }else{
                          $("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify.js-0.3.11/themes/alertify.bootstrap.css");
                          alertify.error('Login Gagal! <br> Periksa Kembali User Password Anda!');
                          $.get('<?php echo base_url();?>index.php/auth/loginalert', function(data) {
                            alertify.error(data);
                          });
                          errorsound.playclip();
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