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

	$filelist = explode(',', $item->FILE_BUKTI);

	$dir = null;
	$img = '';

	foreach ($filelist as $key => $tmp_name) {
	    if ($key==0) {
	        $dt = explode("/", $tmp_name);
	        $c = count($dt);
	        for($i=0; $i<$c; $i++) {
	            $dir = $dir . $dt[$i] . "/";
	        }
	        $tmp_name = $dt[$i++];
	    }

	}

//	$img = $dir . $currImage;
	$img = $dir;
        $check_slash = substr($img, -1);
        if($check_slash=="/"){
            $img = substr($img, 0,-1);
        }

	$ext = end(explode(".", $img));


    if($idImage){
        $getRawPath = explode('/', current($filelist));
        $getExplodePath =array_slice($getRawPath, 0,-1);
        $getRealPath =implode("/",$getExplodePath).'/';
        $img = $getRealPath.$idImage;
    }

    $checkFile = null;
    if($thisTab=="#suratberharga"){
        $checkFile = linkFromMinio($img,null,'t_lhkpn_harta_surat_berharga','ID',$item->ID);
    }elseif($thisTab=="#kas"){
        $checkFile = linkFromMinio($img,null,'t_lhkpn_harta_kas','ID',$item->ID);
    }elseif($thisTab=="#hartalainnya"){
        $checkFile = linkFromMinio($img,null,'t_lhkpn_harta_lainnya','ID',$item->ID);
    }elseif($thisTab=="#suratkuasamengumumkan"){
        $checkFile = linkFromMinio($img,null,'t_lhkpn_harta_surat_berharga','ID',$item->ID);
    }elseif($thisTab=="#suratkuasamengumumkan"){
        $checkFile = linkFromMinio($img,null,'t_lhkpn_harta_surat_berharga','ID',$item->ID);
    }

    if($checkFile){
        $full_url = $checkFile;
    }else{
        $full_url =  base_url().$img;
    }

?>
<div class="box">

	<div class="box-header with-border">
        <h3 class="box-title">File  Dokumen Pendukung</h3>
    </div>
	<form class="form-horizontal" method="post" action="index.php/ever/verification/save/image" id="ajaxFormVeritem" name="ajaxFormVeritem">

                <div class="form-group">

                <?php if ($ext=='pdf' || $ext=='PDF' || $ext=='tif'|| $ext=='TIF') {?>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;<object data="<?php echo $img;?>" type="application/x-pdf" width="800" height="720">
    			     <a target="_blank" href="<?php echo $full_url?>"><i class="fa fa-file-text fa-2x"></i></a>
				</object>

                <?php } else  { ?>
                	<center><img src='<?php echo $full_url?>' width="50%" height="50%"></center>
                <?php } ?>
                </div>
		<div class="pull-right">
			<input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>" required>
			<input type="hidden" name="ITEMVER" value="<?php echo $ITEMVER;?>" required>
			<input type="hidden" name="ID" value="<?php echo $ID;?>" required>
			<input type="hidden" name="CURR_IMAGE" value="<?php echo $currImage;?>" required>
			<button type="submit" id="button-saved" class="btn btn-sm btn-danger">Hapus</button>
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

    function CustomValidation() {

        var fileUpload = $("#file1")[0].files.length;
        if (fileUpload>3) {
            $('.notif-file').show();
            $('.form-file').removeClass('has-success').addClass('has-error');
            $('#button-saved').prop('disabled', true);
            return false;
        } else {
         $('.notif-file').hide();
         $('.form-file').removeClass('has-error').addClass('has-success');
         $('#button-saved').prop('disabled', false);
         return true;
        }
        return false;
    }

</script>
