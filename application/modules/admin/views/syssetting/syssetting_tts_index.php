<?php
$item = json_decode($item->VALUE);;
//foreach ($items as $item) {
//        $item = json_decode($item->VALUE);
//        
//}
?>

<div id="wrapperFormUpdateSetting" class="form-horizontal">
	<form method="post" id="ajaxFormUpdateSetting" action="index.php/admin/syssetting/save/tts">
		<div class="form-group">
			<label class="col-sm-3 control-label">Logo :</label>
			<div class="col-sm-9">
				<img src="images/<?php echo @$item->LOGO;?>" class="logo-header" width="<?php echo @$item->WIDTH;?>" height="<?php echo @$item->HEIGHT;?>">
				<input type="file" name="LOGO" data-allowed-file-extensions='["jpg", "png","jpeg"]'>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Ukuran Vertikal :</label>
			<div class="col-sm-9">
				<input type="text" name="HEIGHT" value="<?php echo @$item->HEIGHT;?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Ukuran Horizontal :</label>
			<div class="col-sm-9">
				<input type="text" name="WIDTH" value="<?php echo @$item->WIDTH;?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Alamat :</label>
			<div class="col-sm-9">
				<textarea name="ALAMAT" cols="50" rows="10" class="ckeditor"><?php echo @$item->ALAMAT;?></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="pull-right col-sm-9">
				<input type="hidden" name="act" value="doupdatesetting">
				<input type="hidden" name="OWNER" value="app.lhkpn">
				<input type="hidden" name="SETTING" value="tts">
				<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
				<!--<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>-->
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		ng.formProcess($("#ajaxFormUpdateSetting"), 'insert', 'index.php/admin/syssetting/index/tts/', '');
	});
</script>