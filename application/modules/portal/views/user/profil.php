<section id="wrapper" class="container">
    <div class="row">
        <div class="col-md-5 col-md-offset-3">
           <div class="tabbable"> <!-- Only required for left/right tabs -->
              <ul class="nav nav-tabs" id="tab-user">
                <li class="active"><a href="#tab1" data-toggle="tab">Pengaturan User</a></li>
                <li><a href="#tab2" data-toggle="tab">Ganti Password</a></li>
                <li><a href="#tab3" data-toggle="tab">Ganti Email</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active box box-default" id="tab1" style="border:1px solid #ddd;">
                   <div class="box-body">
                     <?php $error_message = $this->session->flashdata('error_message');?>
                     <?php if ($error_message):?>
                     <div class="alert alert-error">
                         <i class="fa fa-warning"></i> 
                         <?php echo $this->session->flashdata('message'); ?>
                      </div>
                      <?php endif?>
                      <?php $success_message = $this->session->flashdata('success_message');?>
                      <?php if ($success_message):?>
                      <div class="alert alert-success">
                         <i class="fa fa-check"></i> 
                        <?php echo $this->session->flashdata('message'); ?>
                      </div>
                      <?php endif; ?>       
                   </div>
                   <div class="box-body">
                      <form role="form" enctype="multipart/form-data" method="POST" action="<?php echo base_url().'portal/home/update_pribadi';?>">
                         <div class="form-group">
                          <label>Foto</label>
                          <input type="hidden" name="ID_USER" id="ID_USER" value="<?php //echo $user[0]['ID_USER'];?>"/>
                          <input type="file" id="foto" name="file"  data-allowed-file-extensions='["jpg", "jpeg","png"]'>
                         </div>
                         <div class="form-group">
                          <label>Username</label>
                          <input type="text" class="form-control " readonly name="username" id="username" value="<?php echo $user[0]['USERNAME'];?>" required/>
                         </div>
                         <div class="form-group">
                          <label>Nomor Handphone</label>
                          <input type="text" class="form-control" name="handphone" id="handphone" value="<?php echo $user[0]['HANDPHONE'];?>" required onkeypress="return isNumber(event)"/>
                         </div>
                         <div class="form-group">
                           <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                           <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                      </form>
                   </div>
                </div>
                <div class="tab-pane box box-default " id="tab2" style="border:1px solid #ddd;">
                   <div class="box-body">
                     <?php $error_message = $this->session->flashdata('error_message');?>
                     <?php if ($error_message && $this->session->flashdata('password_alert')):?>
                     <div class="alert alert-error">
                         <i class="fa fa-warning"></i> 
                         <?php echo $this->session->flashdata('message'); ?>
                      </div>
                      <?php endif?>
                      <?php $success_message = $this->session->flashdata('success_message');?>
                      <?php if ($success_message && $this->session->flashdata('password_alert')):?>
                      <div class="alert alert-success">
                         <i class="fa fa-check"></i> 
                        <?php echo $this->session->flashdata('message'); ?>
                      </div>
                      <?php endif; ?>       
                   </div>
                   <div class="box-body">
                      <form id="form-password" role="form" method="POST"  action="<?php echo base_url().'portal/home/update_password';?>">
                        <div class="form-group">
                          <label>Password Lama</label>
                          <input type="hidden" name="ID_USER" id="ID_USER" value="<?php //echo $user[0]['ID_USER'];?>"/>
                          <input name="old_password" id="old_password"  type="password" class="form-control " placeholder="Masukan password lama.." required/>
                        </div>
                         <div class="form-group">
                          <label>Password Baru</label>
                          <input name="new_password" id="new_password" type="password" class="form-control " placeholder="Masukan password baru.." required/>
                        </div>
                         <div class="form-group">
                          <label>Konfirmasi Password Baru</label>
                          <input name="confirm_password" id="confirm_password"  type="password" class="form-control " placeholder="Konfirmasi password baru.." required/>
                        </div>
                        <div class="form-group">
                            <div id="messages-password"></div>
                        </div>
                        <div class="form-group">
                           <button type="submit" class="btn btn-primary" id="btn_update_pwd"><i class="fa fa-save"></i> Simpan</button>
                           <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                     </form>
                   </div>
                </div>
                 
                 <!-- box password info -->
                 <div class="box-body">
                    <div id="pswd-info-profile-1" style="display: none;">
                        <h4>Kata sandi harus memenuhi persyaratan berikut:</h4>
                        <ul style="list-style-type:none;margin:0;padding:0;">
                            <li id="letter" class="invalid"> &nbsp; harus ada minimal <strong>1 huruf kecil</strong></li>
                            <li id="capital" class="invalid"> &nbsp; harus ada minimal <strong>1 huruf besar (kapital)</strong></li>
                            <li id="number" class="invalid"> &nbsp; harus ada minimal <strong>1 unsur angka</strong></li>
                            <li id="length" class="invalid"> &nbsp; harus ada minimal <strong>8 karakter</strong></li>
                            <li id="special-character" class="invalid"> &nbsp; harus ada minimal <strong>1 unsur simbol !@#$%^&*() </strong></li>
                        </ul>
                    </div>
                </div>
                <div class="box-body">
                    <div id="pswd-info-profile-2" style="display: none;">
                        <h4>Kata sandi harus memenuhi persyaratan berikut:</h4>
                        <ul style="list-style-type:none;margin:0;padding:0;">
                            <li id="letter-2" class="invalid-2"> &nbsp; harus ada minimal <strong>1 huruf kecil</strong></li>
                            <li id="capital-2" class="invalid-2"> &nbsp; harus ada minimal  <strong>1 huruf besar (kapital)</strong></li>
                            <li id="number-2" class="invalid-2"> &nbsp; harus ada minimal  <strong>1 unsur angka</strong></li>
                            <li id="length-2" class="invalid-2"> &nbsp; harus ada minimal <strong>8 karakter</strong></li>
                            <li id="special-character-2" class="invalid-2"> &nbsp; harus ada minimal <strong>1 unsur simbol !@#$%^&*() </strong></li>
                        </ul>
                    </div>
                </div>

                <div class="tab-pane  box box-default " id="tab3" style="border:1px solid #ddd;">
                   <div class="box-body">
                     <?php $error_message = $this->session->flashdata('error_message');?>
                     <?php if ($error_message && $this->session->flashdata('email_alert')):?>
                     <div class="alert alert-error">
                         <i class="fa fa-warning"></i> 
                         <?php echo $this->session->flashdata('message'); ?>
                      </div>
                      <?php endif?>
                      <?php $success_message = $this->session->flashdata('success_message');?>
                      <?php if ($success_message && $this->session->flashdata('email_alert')):?>
                      <div class="alert alert-success">
                         <i class="fa fa-check"></i> 
                        <?php echo $this->session->flashdata('message'); ?>
                      </div>
                      <?php endif; ?>       
                   </div>
                   <div class="box-body">
                     <form id="form-email" role="form" method="POST" action="<?php echo base_url().'portal/home/update_email';?>">
                        <div class="form-group">
                          <label>Email Lama</label>
                          <input type="hidden" name="ID_USER" id="ID_USER" value="<?php //echo $user[0]['ID_USER'];?>"/>
                          <p class="help-block"><?php echo $user[0]['EMAIL'];?></p>
                        </div>
                         <div class="form-group">
                          <label>Email Baru</label>
                          <input  type="email" name="new_email" id="new_email" class="form-control " placeholder="Masukan email baru.." required/>
                        </div>
                         <div class="form-group">
                          <label>Konfirmasi Email Baru</label>
                          <input  type="email" name="confirm_email" id="confirm_email" class="form-control " placeholder="Konfirmasi email baru.." required/>
                        </div>
                         <div class="form-group">
                            <div id="messages-email"></div>
                        </div>
                        <div class="form-group">
                           <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                           <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                     </form>
                   </div>
                </div>
              </div>
          </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>
<script type="text/javascript">
  $(document).ready(function(){

    $('#old_password').keyup(function(){
        $('#pswd-info-profile-1').hide();
        $('#pswd-info-profile-2').hide();
        $("[data-bv-icon-for^='old_password']").removeClass('invalid-4').addClass('valid-4');
    });

      $('#new_password').keyup(function() {
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

          var cek_invalid = $( "#pswd-info-profile-1 ul li" ).hasClass( "invalid" );

          if(cek_invalid){ 
              $("#btn_update_pwd").attr("disabled", true);
              $("[data-bv-icon-for^='new_password']").removeClass('valid-4').addClass('invalid-4');
          }else{
              $("[data-bv-icon-for^='new_password']").removeClass('invalid-4').addClass('valid-4');
          }
          
      }).focus(function() {
          $('#pswd-info-profile-1').show();
          $('#pswd-info-profile-2').hide();
      }).blur(function() {
          $('#pswd-info-profile-1').hide();
      });

      $('#confirm_password').keyup(function() {
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

          var cek_invalid = $( "#pswd-info-profile-2 ul li" ).hasClass( "invalid-2" );

          if(cek_invalid){  
              $("#btn_update_pwd").attr("disabled", true);
              $("[data-bv-icon-for^='confirm_password']").removeClass('valid-4').addClass('invalid-4');
          }else{
              $("[data-bv-icon-for^='confirm_password']").removeClass('invalid-4').addClass('valid-4');
          }
          
      }).focus(function() {
          $('#pswd-info-profile-2').show();
          $('#pswd-info-profile-1').hide();
      }).blur(function() {
          $('#pswd-info-profile-2').hide();
      });

    
       //$("#tab-user li").removeClass("active");

       $('#form-email').bootstrapValidator({
        container: '#messages-email',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                new_email: {
                    validators: {
                        notEmpty: {
                            message: 'Lengkapi Alamat Email !!'
                        },
                    }
                },
                confirm_email: {
                    validators: {
                        notEmpty: {
                            message: 'Ulangi kembali Alamat Email  !!'
                        },
                        identical: {
                          field: 'new_email',
                          message: 'Email tidak sama !!'
                        }
                    }
                },
            }
        });

       $('#form-password').bootstrapValidator({
        container: '#messages-password',
        feedbackIcons: {
            valid: 'valid-4',
            invalid: 'invalid-4',
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
            new_password: {
                validators: {
                    notEmpty: {
                        message: 'Lengkapi password baru !!'
                    },
                }
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'Ulangi kembali password baru  !!'
                    },
                    identical: {
                      field: 'new_password',
                      message: 'Password tidak sama !!'
                    }
                }
            },
        }
    });

       var foto = "<?php echo $this->session->userdata('PHOTO');?>";
       var checkFile = "<?php echo linkFromMinio('images/'.$this->session->userdata('PHOTO'),null,'t_user','ID_USER',$this->session->userdata('ID_USER')); ?>";
       var base_url = "<?php echo base_url();?>";
       var real_foto;
       if(foto==''){
          real_foto = base_url+'images/no_available_image.png';
       }else{
          if(checkFile!=0){
             real_foto = checkFile;
          }else{
            <?php if(file_exists('images/'.$this->session->userdata('PHOTO'))): ?>
              real_foto = base_url+'images/'+foto;
            <?php Else: ?>
              real_foto = base_url+'images/no_available_image.png';
            <?php EndIf; ?>
          }
       }


       $("#foto").fileinput({
            initialPreview: [
              "<img src='"+real_foto+"' class='file-preview-image' alt='Upload Foto' title='Upload Foto'>",
          ],
          overwriteInitial: true,
          initialCaption: false,
          showCaption: false
      });
       $('.fileinput-remove-button,.fileinput-remove').hide();

      <?php if($this->session->flashdata('password_alert')): ?>
         $(".tab-pane,#tab-user li").removeClass("active");
         $("#tab-user li:nth-child(2),#tab2").addClass("active");
      <?php ElseIf($this->session->flashdata('email_alert')): ?>
         $(".tab-pane,#tab-user li").removeClass("active");
         $("#tab-user li:nth-child(3),#tab3").addClass("active");
      <?php Endif; ?> 
       
  });
</script>