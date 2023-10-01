<section class="content">
	<div class="row">
		<div class="col-md-12">
			<form id="FormFaq" role="form">
				<input type="hidden" id="ID_FAQ" name="ID_FAQ"/>
				<input type="hidden" id="METHOD" name="METHOD"/>
				<div class="form-group">
                    <label>Pertanyaan :</label>
                    <input type="text" class="form-control" name="txtPertanyaan" id="txtPertanyaan" placeholder="Pertanyaan Faq">
                 </div>
				<label>Jawaban :</label>
             	<textarea  id="txtJawaban" name="txtJawaban" rows="10" cols="80"> 
                </textarea>
                 <div style="margin-top:15px;">
                 	<input type="checkbox" id="is_published" name="is_published" value="1"> Publish
                 </div>
                 <div style="margin-top:15px;">
	            	<input type="submit" class="btn btn-sm btn-primary" value="Simpan"/>
	            	<a href="javascript:void(0)" class="btn btn-sm btn-danger" id="Btutup">Batal</a>
           		 </div>
            </form>
		</div>
	</div>
</section>
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#ID_FAQ').val('');
		CKEDITOR.replace( 'txtJawaban' );
		$('#Btutup').click(function(){
			$('#myModal').modal('hide');
		});
		$('#FormFaq').submit(function(){
			for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
			$.ajax({
				url: '<?php echo base_url();?>cms/faq/save',
				type: 'POST',
				async: false,
				data: $(this).serialize(),
				dataType:'html',
				success: function(data){
					if(data=='1'){
						alertify.success("Data Berhasil Disimpan");
						$('#myModal').modal('hide');
						$('#Tabel').DataTable().ajax.reload();
					}else{
						alertify.error("Data Gagal Disimpan !!");
						$('#myModal').modal('hide');
						$('#Tabel').DataTable().ajax.reload();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alertify.error("Data Gagal Disimpan !!");
					$('#Tabel').DataTable().ajax.reload();
				}
			});
			 return false;
		});
	});
	
</script>
