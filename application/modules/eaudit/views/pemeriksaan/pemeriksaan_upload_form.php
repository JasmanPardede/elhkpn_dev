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
 * @package Views/ever/verification
 */
?>
<?php

	$hasil = [];
	foreach ($hasilVerifikasi as $ver) {
		$hasil[$ver->ID] = $ver->HASIL;
	}

?>
<div class="box">

	<div class="box-header with-border">
        <h3 class="box-title">Upload Dokumen Pendukung</h3>
    </div>

	<form class="form-horizontal" method="post" action="index.php/ever/verification/save/upload" id="ajaxFormVeritem">
                <div class="form-group">
                    <label class="col-sm-8 control-label">Bukti Dokumen/Rekening (pdf/jpg/png/jpeg/tiff)<span class="red-label">*</span></label> <?= FormHelpPopOver('bukti_dokumen_sb'); ?>
                    <br>
                    <div style="float:left; margin-right:10px;" id="show-download"></div>
                    <div class="col-sm-10 control-label">
                        <input type="file" id="file1" class="form-control" name="file1" data-allowed-file-extensions='["pdf", "jpg", "png","jpeg","tif","tiff"]'  data-show-preview="true" required />
                    </div>
                </div>
		<div class="pull-right">
			<input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>" required>
			<input type="hidden" name="ITEMVER" value="<?php echo $ITEMVER;?>" required>
			<input type="hidden" name="ID" value="<?php echo $ID;?>" required>
			<input type="hidden" name="act" value="doupload">
			<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
			<button type="button" class="btn btn-sm btn-default" onclick="CloseModalBox();">Batal</button>
		</div>
	</form>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormVeritem"), 'insert','', ng.LoadAjaxTabContent, {url:'index.php/ever/verification/verupload/<?php echo $ITEMVER;?>/<?php echo $item->ID_LHKPN;?>', block:'#block', container:$('<?=@$thisTab;?>').find('.contentTab')});
        $('.file1').show();
        $('#file_type').select2('val', '1');
        $('#file_type').change(function() {
            if ($(this).val() == '1') {
                $('.file2').hide();
                $('.file1').fadeIn('slow');
            } else {
                $('.file1').hide();
                $('.file2').fadeIn('slow');
            }
        });
        $("input[type='file']").fileinput({
            showUpload: false,
            showRemove: false,
            //overwriteInitial: false,
            initialCaption: false,
            //showCaption: false
        });
        $('[data-toggle="popover"]').popover({
        });
        $('a.over').css('cursor', 'pointer');
        $('a.over').on('click', function(e) {
            $('a.over').not(this).popover('hide');
        });
    });
</script>