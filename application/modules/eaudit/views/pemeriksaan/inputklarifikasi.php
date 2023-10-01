<?php
$form_data = isset($form_data) ? $form_data : FALSE; 
?>

<style type="text/css">
    .inputFile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .inputFile + label {
        cursor: pointer;
        /*font-size: 1.25em;*/
    }

    .inputFile:focus + label,
    .inputFile + label:hover {
        cursor: pointer;
        /*background-color: red;*/
    }

    .form-body-klarifikasi{
        overflow: auto; 
        max-height: 60px;
    }
</style>


<div id="wrapperFormAdd" class="form-horizontal" style="">
    <div id="wrapperFormKlarifikasi">
        <ol class="breadcrumb">
            <li>Input Tanggal Klarifikasi</li>
        </ol>

       <!-- <form method="post" id="ajaxFormAddKlarifikasi"  enctype="multipart/form-data"> -->
            <div class="box-body form-body-klarifikasi">
                <div class="col-sm-12">
                    <!--<div role="tabpanel" class="tab-pane active" id="a">-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Tanggal Klarifikasi <font color='red'>*</font> :</label>
                        <div class="col-sm-10">
                            <input type='text' style="width: 170px" class="form-control date-picker" placeholder='DD/MM/YYYY' name='TGL_KLARIFIKASI' id='TGL_KLARIFIKASI' value='<?php echo show_date_with_format(show_me($form_data, "TGL_KLARIFIKASI", ""), "d/MM/Y"); ?>' required>
                        </div>
                    </div>
              </div>
           </div>

           <div class="clearfix"></div>
                <br>
                <div class="pull-right">
					<input type="hidden" name="act" value="<?php echo $act ?>">
					<input type="hidden" name="id_lhkpn" id="id_lhkpn" value="<?php echo $id_lhkpn ?>">
                    <button type="button" class="btn btn-sm btn-primary btnKlarifikasi" title="Proses Pemeriksaan"><!--onclick="penerimaan.submitted = true;"--><i class="fa fa-save"></i> Simpan</button>
                    <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox2();">

                </div>
                <div class="clearfix"></div>
                <!--</div>-->
        <!-- </form> -->
    </div>
</div>

<?php
$js_page = isset($js_page) ? $js_page : '';
if (is_array($js_page)) {
    foreach ($js_page as $page_js) {
        echo $page_js;
    }
} else {
    echo $js_page;
}
?>
<script type="text/javascript">
 $(document).ready(function() {
		 $('.date-picker').datepicker({
			format: 'dd-mm-yyyy'
	    });
		
		$(".btnKlarifikasi").click(function() {
			var key = $('#id_lhkpn').val();
			var tgl_klarifikasi = $('#TGL_KLARIFIKASI').val();
            var url = '<?php echo $base_url; ?>' + 'index.php/eaudit/pemeriksaan/display/lhkpn/' + key + '/pemeriksaan/' + tgl_klarifikasi;
            ng.LoadAjaxContent(url);
			CloseModalBox2();
            return false;
			///$("#myModal2").modal('show');
        });
 });
 </script>