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
<?php if($form=='add'){ ?>
<div id="wrapperFormAdd" class="form-horizontal">
    <div id="wrapperFormCreate">
    	<div id="loading"></div>
	    <form method="POST" id="ajaxFormAdd" action="index.php/mailbox/sent/savemail" enctype="multipart/form-data">
	        <div class="row">
	        	<div class="col-sm-12" >
		            <div class="form-group" id='form_penerima_select2' style="min-height: 42px;">
		                <label class="col-sm-3 control-label">Penerima <span class="red-label">*</span> :</label>
		                <div class="col-sm-8">
		                	<input style="border: none; padding: 6px 0px;" type='text' class="form-control" name='ID_PENERIMA' id='ID_PENERIMA' required>
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
		                	<!--<input type="file" name="filename" id="filename" class='attachment'>-->
		                	<!--<span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpeg, jpg, png .  Max File: 500KB</span>-->
		                <!--</div>-->
		            <!--</div>-->
		        </div>
	        </div>
	        <div class="clearfix" style="margin-bottom: 20px;"></div>
	        <div class="pull-right">
	        	<input type="hidden" name="act" value="doinsert">
	            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Kirim</button>
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

		$('#ID_PENERIMA').change(function(){
			width =$('.select2-choices').height();
			$('#form_penerima_select2').css('height', (width+10)+'px');
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
			    	$('#loader_area').show();
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

        })
		$('#ID_PENERIMA').select2({
			multiple: true,
			minimumInputLength: 0,
			ajax: {
				url: "<?=base_url('index.php/mailbox/sent/getContact')?>",
				dataType: 'json',
				quietMillis: 250,
				data: function (term, page) {
					return {
						q: term
					};
				},
				results: function (data, page) {
					return { results: data.item };
				},
				cache: true
			},
			initSelection: function(element, callback) {
				var id = $(element).val();
				if (id !== "") {
					$.ajax("<?=base_url('index.php/mailbox/sent/getContact')?>/"+id, {
						dataType: "json"
					}).done(function(data) { callback(data[0]); });
				}
			},
			allowClear: true,
		    formatResult: formatResult,
    		formatSelection: formatResult,
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
	                <label class="col-sm-3 control-label">Penerima :</label>
	                <div class="col-sm-8">
	                	<input style="border: none;" type='text' class="form-control" id='ID_PENERIMA' readonly>
	                	<input type="hidden" value="<?=$item->ID_PENERIMA?>" name='ID_PENERIMA'>
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
	                <label class="col-sm-3 control-label">Tanggal Kirim :</label>
	                <div class="col-sm-8">
	                	<p style="padding-top: 7px;">
	                		<?=indonesian_date(strtotime($item->TANGGAL_KIRIM)); ?>
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
            <!-- <button type="button" id="balas" class="btn btn-sm btn-success">Balas</button> -->
            <!-- <button type="submit" class="btn btn-sm btn-primary">Kirim</button> -->
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
			url: "<?=base_url('index.php/mailbox/sent/getContact_name')?>/<?=$item->ID_PENERIMA?>",
			dataType: "json",
			success : function(result){
				var name = result[0].nama;
				var inst = (result[0].inst_satkerkd !== null) ? ' - ('+result[0].inst_satkerkd+')' : '';
				var data = name + inst

				$('#ID_PENERIMA').val(data);
			}
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
	<form method="post" id="ajaxFormDelete" action="index.php/mailbox/sent/savemail/<?php echo substr(md5($item->ID),5,8);?>">
		Benarkah akan menghapus pesan dibawah ini ?
		<div class="clearfix" style="margin-bottom: 20px;"></div>
	    <div id="wrapperFormCreate">
	        <div class="row">
	        	<div class="col-sm-12">
		            <div class="form-group">
		                <label class="col-sm-3 control-label">Penerima :</label>
		                <div class="col-sm-8">
		                	<input style="border: none;" type='text' class="form-control" id='ID_PENERIMA' readonly>
		                	<input type="hidden" value="<?=$item->ID_PENERIMA?>" name='ID_PENERIMA'>
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
		                <label class="col-sm-3 control-label">Tanggal Kirim :</label>
		                <div class="col-sm-8">
		                	<p style="padding-top: 7px;">
		                		<?=indonesian_date(strtotime($item->TANGGAL_KIRIM)); ?>
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
                 ng.LoadAjaxContent('index.php/mailbox/sent/');
                 CloseModalBox();
            })
            return false;
        })
		$.ajax({
			url: "<?=base_url('index.php/mailbox/sent/getContact_name')?>/<?=$item->ID_PENERIMA?>",
			dataType: "json",
			success : function(result){
				var name = result[0].nama;
				var inst = (result[0].inst_satkerkd !== null) ? ' - ('+result[0].inst_satkerkd+')' : '';
				var data = name + inst

				$('#ID_PENERIMA').val(data);
			}
		});
	});

	function formatResult(state){
	    var data = state.name;

	    return data;
	}
</script>
<?php } ?>