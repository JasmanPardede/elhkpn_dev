<section id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <div class="alert alert-error" id="erroroldpass" style="display: none">
                    <i class="fa fa-warning"></i> 
                        Mohon Maaf , password lama tidak valid !!  
                </div>

                <?php $error_message = $this->session->flashdata('error_message'); ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fa fa-warning"></i> 
                        <?php echo $this->session->flashdata('message'); ?>
                    </div>
                <?php endif ?>
                <?php $success_message = $this->session->flashdata('success_message'); ?>
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fa fa-check"></i> 
                        <?php echo $this->session->flashdata('message'); ?>
                    </div>
                <?php endif; ?>              
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="col-lg-6 col-lg-offset-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3>Perubahan Password Pengguna Baru/Lupa Password</h3>
                    </div>
                    <div class="box-body">
                        <form role="form" id="form-password" method="POST"  action="<?php echo base_url() . 'portal/user/confirm'; ?>">
                            <div class="form-group">
                                <div class="alert alert-info">
                                    <p style="font-weight:normal; font-size:14px;">
                                        Pengguna baru/lupa password diharuskan merubah password saat login 
                                        pertama kali
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group" id="old_pass">
                                    <label>Password Lama</label>
                                    <input type="password" class="form-control"  name="old_password" id="old_password" required/>
                                    <p style="font-size: small; color: red;"><strong>Password Lama adalah password yang baru saja dikirimkan oleh Aplikasi e-LHKPN ke email Anda</strong></p>
                                </div>
                                <div class="form-group">
                                    <label>Password Baru</label>
                                    <input type="password" class="form-control" name="password" id="password" required/>
                                </div>
                                <div class="form-group">
                                    <label>Ulangi Password Baru</label>
                                    <input type="password" class="form-control" name="repassword" id="repassword" required/>
                                </div>   
                                <div class="form-group">
                                    <div id="messages"></div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="btn_update_pwd"><i class="fa fa-mail-forward"></i> Konfirmasi</button>
                                    <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>       
                </div>
                <!-- box password info -->
                <div class="box-body">
                    <div id="pswd-info-1" style="display: none;">
                        <h4>Kata sandi harus memenuhi persyaratan berikut:</h4>
                        <ul style="list-style-type:none;margin:0;padding:0;">
                            <li id="letter" class="invalid"> &nbsp;  harus ada minimal <strong>1 huruf kecil</strong></li>
                            <li id="capital" class="invalid"> &nbsp;  harus ada minimal  <strong>1 huruf besar (kapital)</strong></li>
                            <li id="number" class="invalid"> &nbsp;  harus ada minimal  <strong>1 unsur angka</strong></li>
                            <li id="length" class="invalid"> &nbsp;  harus ada minimal <strong>8 karakter</strong></li>
                            <li id="special-character" class="invalid"> &nbsp;  harus ada minimal <strong>1 unsur simbol !@#$%^&*() </strong></li>
                        </ul>
                    </div>
                </div>
                <div class="box-body">
                    <div id="pswd-info-2" style="display: none;">
                        <h4>Kata sandi harus memenuhi persyaratan berikut:</h4>
                        <ul style="list-style-type:none;margin:0;padding:0;">
                            <li id="letter-2" class="invalid-2"> &nbsp;  harus ada minimal <strong>1 huruf kecil</strong></li>
                            <li id="capital-2" class="invalid-2"> &nbsp;  harus ada minimal  <strong>1 huruf besar (kapital)</strong></li>
                            <li id="number-2" class="invalid-2"> &nbsp;  harus ada minimal  <strong>1 unsur angka</strong></li>
                            <li id="length-2" class="invalid-2"> &nbsp;  harus ada minimal <strong>8 karakter</strong></li>
                            <li id="special-character-2" class="invalid-2"> &nbsp;  harus ada minimal <strong>1 unsur simbol !@#$%^&*() </strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {

        $('#old_password').keyup(function() {
            $('#pswd-info-1').hide();
            $('#pswd-info-2').hide();
        });

        $('#password').keyup(function() {
        // keyup code here
        var pswd = $(this).val();
      
          //validate the length
          if ( pswd.length < 8 ) {
              $('#length').removeClass('valid').addClass('invalid');
          } else {
              $('#length').removeClass('invalid').addClass('valid');
          }

          //validate lower letter
          if ( pswd.match(/[a-z]/) ) {
              $('#letter').removeClass('invalid').addClass('valid');
          } else {
              $('#letter').removeClass('valid').addClass('invalid');
          }

          //validate capital letter
          if ( pswd.match(/[A-Z]/) ) {
              $('#capital').removeClass('invalid').addClass('valid');
          } else {
              $('#capital').removeClass('valid').addClass('invalid');
          }

          //validate number
          if ( pswd.match(/\d/) ) {
              $('#number').removeClass('invalid').addClass('valid');
          } else {
              $('#number').removeClass('valid').addClass('invalid');
          }

          //validate special characters
          if ( pswd.match(/[!@#$%^&*()]/) ) {
              $('#special-character').removeClass('invalid').addClass('valid');
          } else {
              $('#special-character').removeClass('valid').addClass('invalid');
          }

          var cek_invalid = $( "#pswd-info-1 ul li" ).hasClass( "invalid" );

          if(cek_invalid){ 
                $("#btn_update_pwd").attr("disabled", true);
                $("[data-bv-icon-for^='password']").removeClass('valid-3').addClass('invalid-3');
          } else{
                $("[data-bv-icon-for^='password']").removeClass('invalid-3').addClass('valid-3');
          }
          
      }).focus(function() {
          $('#pswd-info-1').show();
          $('#pswd-info-2').hide();
      }).blur(function() {
          $('#pswd-info-1').hide();
      });

        $('#repassword').keyup(function() {
        // keyup code here
        var pswd = $(this).val();
      
          //validate the length
          if ( pswd.length < 8 ) {
              $('#length-2').removeClass('valid-2').addClass('invalid-2');
          } else {
              $('#length-2').removeClass('invalid-2').addClass('valid-2');
          }

          //validate lower letter
          if ( pswd.match(/[a-z]/) ) {
              $('#letter-2').removeClass('invalid-2').addClass('valid-2');
          } else {
              $('#letter-2').removeClass('valid-2').addClass('invalid-2');
          }

          //validate capital letter
          if ( pswd.match(/[A-Z]/) ) {
              $('#capital-2').removeClass('invalid-2').addClass('valid-2');
          } else {
              $('#capital-2').removeClass('valid-2').addClass('invalid-2');
          }

          //validate number
          if ( pswd.match(/\d/) ) {
              $('#number-2').removeClass('invalid-2').addClass('valid-2');
          } else {
              $('#number-2').removeClass('valid-2').addClass('invalid-2');
          }

          //validate special characters
          if ( pswd.match(/[!@#$%^&*()]/) ) {
              $('#special-character-2').removeClass('invalid-2').addClass('valid-2');
          } else {
              $('#special-character-2').removeClass('valid-2').addClass('invalid-2');
          }

          var cek_invalid = $( "#pswd-info-2 ul li" ).hasClass( "invalid-2" );

          if(cek_invalid){ 
                $("#btn_update_pwd").attr("disabled", true);
                $("[data-bv-icon-for^='repassword']").removeClass('valid-3').addClass('invalid-3');
          }else{
                $("[data-bv-icon-for^='repassword']").removeClass('invalid-3').addClass('valid-3');
          }

      }).focus(function() {
          $('#pswd-info-2').show();
          $('#pswd-info-1').hide();
      }).blur(function() {
          $('#pswd-info-2').hide();
      });

        $('#form-password').bootstrapValidator({
            container: '#messages',
            feedbackIcons: {
                valid: 'valid-3',
                invalid: 'invalid-3',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                old_password: {
                    validators: {
                        notEmpty: {
                            message: 'Lengkapi password lama !!'
                        },
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'Lengkapi password baru !!'
                        },
                    }
                },
                repassword: {
                    validators: {
                        notEmpty: {
                            message: 'Ulangi kembali password baru  !!'
                        },
                        identical: {
                            field: 'password',
                            message: 'Password tidak sama !!'
                        }
                    }
                },
            }
        });
        $('#old_password').blur(function() {
            var val = $(this).val();
            if (val != '') {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url(); ?>portal/user/cekPassOld/',
                    data: { 
                        password : $(this).val(),
                    },
                    success: function(data){
                            if (data == 0) {
                            $('#erroroldpass').show();
                            $('#old_password').val("").focus();
                            $('#old_pass').removeClass('has-success').addClass('has-error');
                            // $('i[data-bv-icon-for="old_password"]').addClass('glyphicon glyphicon-remove');
                            $("[data-bv-icon-for^='old_password']").removeClass('valid-3').addClass('invalid-3')
                            $('#pswd-info-1').hide();
                            $('#pswd-info-2').hide();
                        } else {
                            $('#erroroldpass').hide();
                            $("[data-bv-icon-for^='old_password']").removeClass('invalid-3').addClass('valid-3');
                            
                        }
                    }
                });
            }

        });
    });



</script>