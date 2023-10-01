<?php
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
 * @package Views/profile
*/
?>


        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Profile
            <small>Data Saya</small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box-body">
				<div class="row">
					<div class="col-xs-3">
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-8">
								 <?php
                                    $session_foto = $this->session->userdata('PHOTO');
                                    $real_foto = null;
                                    if($session_foto && file_exists('images/'.$session_foto)){
                                       $real_foto = base_url().'images/'.$session_foto;
                                    }else{
                                       $real_foto = base_url().'images/no_available_image.png';
                                    }
                                 ?>
								<p>
									<img src="<?php echo $real_foto;?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
								</p>
							</div>
							<form action="index.php/profile/updateFoto" class="form-horizontal" method="post" id="ajaxFormAdd9" enctype="multipart/form-data">
								<input type="hidden" name="tmp_PHOTO" value="<?= @$user->PHOTO; ?>">
								<input type="hidden" name="ID_USER" value="<?= @$user->ID_USER; ?>">
								<input type="hidden" name="USERNAME" value="<?= @$user->USERNAME; ?>">
								<div class="col-sm-9">
				                    <div class='col-sm-12'>
				                        <input type="file" class="upload" name="FILE_FOTO" class="ini">
				                        <span class='help-block'>Max File: 500KB</span>
				                    </div>
				                    <button type="submit" style="display:none;margin-left:15px;" class="btn btn-primary btn-sm btn-ubah">Update</button>
				                </div>
			                </form>
			                <script type="text/javascript">
			                	jQuery(document).ready(function() {

			                		$('.upload').change(function(){
			                			var pram = $(this).val();
			                			if(pram != ''){
			                				$('.btn-ubah').show();
			                			}else{
			                				$('.btn-ubah').hide();
			                			}

			                			ng.formProcess($("#ajaxFormAdd9"), 'insert', location.href.split('#')[1]);

							            // var nil     = $(this).val().split('.');
							            // nil         = nil[nil.length - 1].toLowerCase();
							            // var file    = $(this)[0].files[0].size;
							            // var arr     = ['xls','xlsx','doc','docx','pdf','jpg','png','jpeg'];
							            // var maxsize = 500000;
							            // // if (arr.indexOf(nil) < 0)
							            // // {
							            // //     $(this).val('');
							            // //     alertify.error('Type file tidak sesuai !');
							            // // }
							            // if (file > maxsize)
							            // {
							            //     $(this).val('');
							            //     alertify.error('Ukuran File trlalu besar !');
							            // }
							        });
		                		});
			                </script>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-horizontal">
						
							<!--<div class="form-group">
								<label class="col-sm-3 control-label">NIK :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo $user->NIP; ?></p>
								</div>
							</div>-->
							<div class="form-group">
								<label class="col-sm-3 control-label">Username :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo $user->USERNAME; ?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><a href="mailto:<?php echo $user->EMAIL; ?>"><?php echo $user->EMAIL; ?></a></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Handphone :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><a href="tel:<?php echo $user->HANDPHONE; ?>"><?php echo $user->HANDPHONE; ?></a></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Nama :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo $user->NAMA; ?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last Login :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo indonesian_date(strtotime($user->LAST_LOGIN)); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Role :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><span class="badge bg-<?php echo $user->COLOR; ?>"><?php echo $user->ROLE; ?></span></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Instansi :</label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo $user->INST_NAMA; ?></p>
								</div>
							</div>
						
						</div>
					</div>
					<div class="col-xs-3">
						<button class="btn pull-left btn-warning" type="submit" id="changePassword" href="index.php/profile/changePassword/<?= substr(md5(@$user->ID_USER),5,8) ?>">Ganti Password</button>
						<button class="btn pull-left btn-primary" type="submit" id="changeEmail" href="index.php/profile/changeEmail/<?= substr(md5(@$user->ID_USER),5,8) ?>">Ganti Email</button>
					</div>
				</div>
			  </div><!-- /.box-body -->
            </div><!-- /.col-md-12 -->
			<div class="box-footer clearfix">
            </div>
          </div><!-- /.row -->
       </section><!-- /.content -->

<script language="javascript">
    $(document).ready(function () {
        $("#changePassword").click(function () {
            var url = $(this).attr('href');
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Ganti Password', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/profile');
            });            
            return false;
        });
        $("#changeEmail").click(function () {
            var url = $(this).attr('href');
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Ganti Email', html, '');
//                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/profile');
            });
            return false;
        });
    });
</script>

