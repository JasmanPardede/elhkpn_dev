<section class="content">
	<div class="row">
		<div class="col-md-12">
			<form id="FormPengumuman">
				<input type="hidden" id="ID_PENGUMUMAN" name="ID_PENGUMUMAN"/>
				<input type="hidden" id="METHOD" name="METHOD"/>
             	<textarea  id="txtPengumuman" name="txtPengumuman" rows="10" cols="80"> 
                </textarea>
				<div style="margin-top:15px;">
					<!--<input type="checkbox" id="ID_ROLE_5" name="ID_ROLE" value="5" required> PN | <input type="checkbox" id="ID_ROLE_1" name="ID_ROLE" value="1"> Non PN -->
					<input type="checkbox" id="ID_ROLE_5" name="ID_ROLE[1]" value="5" > Umum & PN | <input type="checkbox" id="ID_ROLE_1" name="ID_ROLE[2]" value="1"> Admin 
				</div>
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
		$('#ID_PENGUMUMAN').val('');
		CKEDITOR.replace( 'txtPengumuman' );
		$('#Btutup').click(function(){
			$('#myModal').modal('hide');
		});
		$('#FormPengumuman').submit(function(){
			if(!$('#FormPengumuman input[type="checkbox"]').is(':checked')){
				alert("Please check at least one.");
				return false;
			}
			for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
//             var ID_PENGUMUMAN = $('#ID_PENGUMUMAN').val();
//             var METHOD = $('#METHOD').val();
//             var txtPengumuman = $('#txtPengumuman').val();
//             var get_new = CKEDITOR.instances.txtPengumuman.getData();
			$.ajax({
				url: '<?php echo base_url();?>cms/pengumuman/save',
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
