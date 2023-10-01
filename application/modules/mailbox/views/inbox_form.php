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
 * @package Views/lhkpn
*/
?>
<style type="text/css">
	.pesan {
		padding-top: 7px;
		border: 1px solid rgb(189, 189, 189);
		padding: 5px;
		border-radius: 5px;
	}
</style>
<?php if($form=='reply'){ ?>
<div id="wrapperFormAdd" class="form-horizontal">
    <div id="wrapperFormCreate">
	    <form method="post" id="ajaxFormAdd" action="index.php/mailbox/inbox/savemail" enctype="multipart/form-data">
	        <div class="row">
	        	<div class="col-sm-12">
		            <div class="form-group">
		                <label class="col-sm-3 control-label">Penerima <span class="red-label">*</span> :</label>
		                <div class="col-sm-8">
		                	<input style="border: none;" type='text' class="form-control" id='ID_PENGIRIM' readonly>
		                	<input type="hidden" value="<?=$item->ID_PENGIRIM?>" name='ID_PENERIMA'>
		                </div>
		            </div>
		            <div class="form-group">
		            	<label class="col-sm-3 control-label">Subjek <span class="red-label">*</span> :</label>
		            	<div class="col-sm-8">
		            		<input type="text" class="form-control" name="SUBJEK" required>
		            	</div>
		            </div>
		            <div class="form-group">
		                <label class="col-sm-3 control-label">Pesan <span class="red-label">*</span> :</label>
		                <div class="col-sm-8">
		                	<textarea rows="5" class="form-control" name="PESAN" id="PESAN" required></textarea>
		                </div>
		            </div>
		            <!--<div class="form-group">-->
		                <!--<label class="col-sm-3 control-label">Attachment :</label>-->
		                <!--<div class="col-sm-8">-->
		                	<!--<input type="file" name="filename" id="filename" class="attachment">-->
		                	<!--<span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpeg, jpg, png .  Max File: 500KB</span>-->
		                <!--</div>-->
		            <!--</div>-->
		        </div>
	        </div>
	        <div class="clearfix" style="margin-bottom: 20px;"></div>
	        <div class="pull-right">
	        	<input type="hidden" name="act" value="doinsert">
	            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Kirim</button>
	        </div>
	    </form>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.attachment').change(function(){
		    var nil     = $(this).val().split('.');
		    nil 		= nil[nil.length - 1].toLowerCase();
		    var file    = $(this)[0].files[0].size;
		    var arr     = ['xls','xlsx','doc','docx','pdf','jpg','png','jpeg'];
		    var maxsize = 500000;
		    if (arr.indexOf(nil) < 0)
		    {
		        $('.attachment').val('');
		        alertify.error('Type file tidak sesuai !');
		    }
		    if (file > maxsize)
		    {
		        $('.attachment').val('');
		        alertify.error('Ukuran File trlalu besar !');
		    }
		});
		$("#ajaxFormAdd").submit(function(){
			var urll = $(this).attr('action');
			var formData = new FormData($(this)[0]);
			
			$('#loader_area').show();
			$.ajax({
			    url: urll,
			    type: 'POST',
			    data: formData,
			    async: false,
			    success: function (html) {
			        msg = {
			           success : 'Data Berhasil Disimpan!',
			           error : 'Data Gagal Disimpan!'
			        };
			        if (html == 0) {
			           alertify.error(msg.error);
			        } else {
			           alertify.success(msg.success);
			        }
			        if(html == 1){
			           ng.LoadAjaxContent('index.php/mailbox/inbox/');
			           CloseModalBox();
			        }else{
			            console.log('error');
			        }
			    },
			    cache: false,
			    contentType: false,
			    processData: false
			});

			return false;

        });
		// $("#ajaxFormAdd").submit(function(){
  //           var url = $(this).attr('action');
  //           var data = $(this).serializeArray();
  //           $.post(url, data, function(res){
  //                msg = {
  //                   success : 'Pesan Berhasil Dikirim !',
  //                   error : 'Pesan Gagal Dikirim !'
  //                };
  //                if (data == 0) {
  //                   alertify.error(msg.error);
  //                } else {
  //                   alertify.success(msg.success);
  //                }
  //                ng.LoadAjaxContent('index.php/mailbox/inbox/');
  //                CloseModalBox();
  //           })
  //           return false;
  //       })
		$.ajax({
			url: "<?=base_url('index.php/mailbox/inbox/getContact_name')?>/<?=$item->ID_PENGIRIM?>",
			dataType: "json",
			success : function(result){
				var name = result[0].nama;
				var inst = (result[0].inst_satkerkd !== null) ? ' - ('+result[0].inst_satkerkd+')' : '';
				var data = name + inst

				$('#ID_PENGIRIM').val(data);
			}
		});	
	});

	function formatResult(state){
	    var data = state.name;

	    return data;
	}
</script>
<?php } ?>
<?php if($form=='detail'){ ?>
<div id="wrapperFormAdd" class="form-horizontal">
    <div id="wrapperFormCreate">
        <div class="row">
        	<div class="col-sm-12">
	            <div class="form-group">
	                <label class="col-sm-3 control-label">Pengirim :</label>
	                <div class="col-sm-8">
	                	<input style="border: none;" type='text' class="form-control" id='ID_PENGIRIM' readonly>
	                	<input type="hidden" value="<?=$item->ID_PENGIRIM?>" name='ID_PENGIRIM'>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-sm-3 control-label">Subjek :</label>
	                <div class="col-sm-8">
	                	<p style="padding-top: 7px;">
	                		<?=$item->SUBJEK?>
	                	</p>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-sm-3 control-label">Pesan :</label>
	                <div class="col-sm-8">
	                	<div class="pesan">
	                		<?=htmlspecialchars_decode($item->PESAN)?>
	                	</div>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-sm-3 control-label">Tanggal Masuk :</label>
	                <div class="col-sm-8">
	                	<p style="padding-top: 7px;">
	                		<?=indonesian_date(strtotime($item->TANGGAL_MASUK)); ?>
	                	</p>
	                </div>
	            </div>
	            <div class="form-group">
	                <label class="col-sm-3 control-label">File :</label>
	            	<div class="col-sm-8">
	                	<?php if(!empty($item->FILE)) : ?>
	                		<a href="<?php echo 'uploads/mail_out/'.$item->ID_PENGIRIM.'/'.$item->FILE; ?>" target="_BLANK">
		                		<i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/mail_out/'.$item->ID_PENGIRIM.'/'.$item->FILE); ?>
		                	</a>
		                <?php else : ?>
		                	-
		                <?php endif; ?>
	                </div>
	            </div>
            </div>
	    </div>
    </div>
	<form method="post" id="ajaxFormAdd" action="index.php/mailbox/inbox/savemail/<?php echo substr(md5($item->ID),5,8);?>"  enctype="multipart/form-data">
	    <div id="balas" style="display: none;">
	    	<hr>
	    	<div id="wrapperFormCreate">
	    		<div class="row">
	        		<div class="col-sm-12">
	        			<div class="form-group">
	        				<label class="col-sm-3 control-label">Subjek <span class="red-label">*</span> :</label>
	        				<div class="col-sm-8">
	        					<?php
	        						$text = str_replace('re:[', '', $item->SUBJEK);
	        						if (substr($text, -1) == ']') {
	        							$text = substr($text,0,-1); 
	        						}
	        					?>
	        					<input type="text" class="form-control" onclick="$(this).val('');" name="SUBJEKB" value="re:[<?=$text?>]" placeholder="re:[<?=$text?>]">
	        				</div>
	        			</div>
	        			<div class="form-group">
			                <label class="col-sm-3 control-label">Pesan <span class="red-label">*</span> :</label>
			                <div class="col-sm-8">
			                	<textarea rows="5" class="form-control" name="PESAN" id="PESAN" required></textarea>
			                </div>
			            </div>
			            <!--<div class="form-group">-->
			                <!--<label class="col-sm-3 control-label">Attachment :</label>-->
			                <!--<div class="col-sm-8">-->
			                	<!--<input type="file" name="filename" id="filename" class="attachment">-->
			                	<!--<span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpeg, jpg, png .  Max File: 500KB</span>-->
			                <!--</div>-->
			            <!--</div>-->
			            <input type="hidden" name="ID_PENERIMA" value="<?=$item->ID_PENGIRIM?>">
	        		</div>
	        	</div>
	    	</div>
	    </div>
	    <div class="clearfix" style="margin-bottom: 20px;"></div>
	    <div class="pull-right">
	    	<input type="hidden" name="SUBJEKA" value="<?php echo $item->SUBJEK ?>">
	    	<input type="hidden" name="act" value="doinsert">
	        <button type="button" id="button_balas" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Balas</button>
	        <button type="submit" id="button_kirim" style="display: none; float: left; margin-left: 3px;" class="btn btn-sm btn-primary">Kirim</button>
	    	<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
	    </div>
	</form>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.attachment').change(function(){
		    var nil     = $(this).val().split('.');
		    nil 		= nil[nil.length - 1].toLowerCase();
		    var file    = $(this)[0].files[0].size;
		    var arr     = ['xls','xlsx','doc','docx','pdf','jpg','png','jpeg'];
		    var maxsize = 500000;
		    if (arr.indexOf(nil) < 0)
		    {
		        $('.attachment').val('');
		        alertify.error('Type file tidak sesuai !');
		    }
		    if (file > maxsize)
		    {
		        $('.attachment').val('');
		        alertify.error('Ukuran File trlalu besar !');
		    }
		});
		$("#ajaxFormAdd").submit(function(){
			var urll = $(this).attr('action');
			var formData = new FormData($(this)[0]);

			$('#loader_area').show();
			$.ajax({
			    url: urll,
			    type: 'POST',
			    data: formData,
			    async: false,
			    success: function (html) {
			        msg = {
			           success : 'Data Berhasil Disimpan!',
			           error : 'Data Gagal Disimpan!'
			        };
			        if (html == 0) {
			           alertify.error(msg.error);
			        } else {
			           alertify.success(msg.success);
			        }
			        if(html == 1){
			           ng.LoadAjaxContent('index.php/mailbox/sent/');
			           CloseModalBox();
			        }else{
			            console.log('error');
			        }
			    },
			    cache: false,
			    contentType: false,
			    processData: false
			});

			return false;

        });
		// $("#ajaxFormAdd").submit(function(){
  //           var url = $(this).attr('action');
  //           var data = $(this).serializeArray();
  //           $.post(url, data, function(res){
  //                msg = {
  //                   success : 'Pesan Berhasil Dikirim !',
  //                   error : 'Pesan Gagal Dikirim !'
  //                };
  //                if (data == 0) {
  //                   alertify.error(msg.error);
  //                } else {
  //                   alertify.success(msg.success);
  //                }
  //                ng.LoadAjaxContent('index.php/mailbox/inbox/');
  //                CloseModalBox();
  //           })
  //           return false;
  //       });
		$.ajax({
			url: "<?=base_url('index.php/mailbox/inbox/getContact_name')?>/<?=$item->ID_PENGIRIM?>",
			dataType: "json",
			success : function(result){
				var name = result[0].nama;
				var inst = (result[0].inst_satkerkd !== null) ? ' - ('+result[0].inst_satkerkd+')' : '';
				var data = name + inst

				$('#ID_PENGIRIM').val(data);
			}
		});

		$('#button_balas').click(function(){
			$('#balas').slideDown(500);
			$(this).css({display: 'none'});
			$('#button_kirim').css({display: 'block'});
		});

	});

	function formatResult(state){
	    var data = state.name;

	    return data;
	}
</script>
<?php } ?>
<?php if($form=='delete'){ ?>
<div id="wrapperFormAdd" class="form-horizontal">
	<form method="post" id="ajaxFormDelete" action="index.php/mailbox/inbox/savemail/<?php echo substr(md5($item->ID),5,8);?>">
		Benarkah akan menghapus pesan dibawah ini ?
		<div class="clearfix" style="margin-bottom: 20px;"></div>
	    <div id="wrapperFormCreate">
	        <div class="row">
	        	<div class="col-sm-12">
		            <div class="form-group">
		                <label class="col-sm-3 control-label">Pengirim <span class="red-label">*</span> :</label>
		                <div class="col-sm-8">
		                	<input style="border: none;" type='text' class="form-control" id='ID_PENGIRIM' readonly>
		                	<input type="hidden" value="<?=$item->ID_PENGIRIM?>" name='ID_PENGIRIM'>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="col-sm-3 control-label">Subjek :</label>
		                <div class="col-sm-8">
		                	<p style="padding-top: 7px;">
		                		<?=$item->SUBJEK?>
		                	</p>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="col-sm-3 control-label">Pesan <span class="red-label">*</span> :</label>
		                <div class="col-sm-8">
		                	<div class="pesan">
	                		    <?=htmlspecialchars_decode($item->PESAN)?>
		                	</div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="col-sm-3 control-label">Tanggal Masuk <span class="red-label">*</span> :</label>
		                <div class="col-sm-8">
		                	<p style="padding-top: 7px;">
		                		<?=indonesian_date(strtotime($item->TANGGAL_MASUK)); ?>
		                	</p>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="col-sm-3 control-label">File :</label>
		            	<div class="col-sm-8">
		                	<?php if(!empty($item->FILE)) : ?>
		                		<a href="<?php echo 'uploads/mail_out/'.$item->ID_PENGIRIM.'/'.$item->FILE; ?>" target="_BLANK">
			                		<i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/mail_out/'.$item->ID_PENGIRIM.'/'.$item->FILE); ?>
			                	</a>
			                <?php else : ?>
			                	-
			                <?php endif; ?>
		                </div>
		            </div>
		        </div>
	        </div>
	        <div class="clearfix" style="margin-bottom: 20px;"></div>
	        <div class="pull-right">
	        	<input type="hidden" name="act" value="dodelete">
	            <!-- <button type="button" id="balas" class="btn btn-sm btn-success">Balas</button> -->
	            <!-- <button type="submit" class="btn btn-sm btn-primary">Kirim</button> -->
	            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Hapus</button>
	            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
	        </div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#ajaxFormDelete").submit(function(){
            var url = $(this).attr('action');
            var data = $(this).serializeArray();
            $.post(url, data, function(res){
                 msg = {
                    success : 'Pesan Berhasil Dihapus !',
                    error : 'Pesan Gagal Dihapus !'
                 };
                 if (data == 0) {
                    alertify.error(msg.error);
                 } else {
                    alertify.success(msg.success);
                 }
                 ng.LoadAjaxContent('index.php/mailbox/inbox/');
                 CloseModalBox();
            })
            return false;
        });
		$.ajax({
			url: "<?=base_url('index.php/mailbox/inbox/getContact_name')?>/<?=$item->ID_PENGIRIM?>",
			dataType: "json",
			success : function(result){
				var name = result[0].nama;
				var inst = (result[0].inst_satkerkd !== null) ? ' - ('+result[0].inst_satkerkd+')' : '';
				var data = name + inst

				$('#ID_PENGIRIM').val(data);
			}
		});
	});

	function formatResult(state){
	    var data = state.name;

	    return data;
	}
</script>
<?php } ?>