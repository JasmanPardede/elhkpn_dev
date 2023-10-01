<!---HARTA LAINNYA -->
<form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HARTA LAINNYA</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-6">
            <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />
            <div class="form-group">
                <label>Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_hl'); ?>
                <select name="KODE_JENIS" id="KODE_JENIS" class="form-control" required></select>  
            </div>
            <div class="form-group">
                <label>Keterangan </label> <?= FormHelpPopOver('keterangan_hl'); ?>
                <textarea class="form-control input_capital" id="KETERANGAN" name="KETERANGAN" rows="2" ><?php echo !$onAdd ? $harta->KETERANGAN : ''; ?></textarea>
            </div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker10'>
                    <label>Tahun Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                    <input type="text" name="TAHUN_PEROLEHAN_AWAL" id="TAHUN_PEROLEHAN_AWAL"  class="form-control year" required value='<?php echo!$onAdd ? $harta->TAHUN_PEROLEHAN_AWAL : ''; ?>'/> 
                </div> 
            </div>              
            
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_hl'); ?>
                <table class="table" id="table-asal-usul" required></table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
        </div>
        <div class="col-sm-6">
<!--             <div class="form-group"> -->
    <!--             <label>File Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('file_bukti'); ?> -->
     <!--            <?php echo form_dropdown('FILE_BUKTI', $file_list, (!$onAdd ? $harta->FILE_BUKTI : ''), "class=\"form-control\""); ?> -->
<!--             </div> -->
            <div class="form-group">
                <label>Nilai Perolehan <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_hl'); ?>
                <input type="text" id="NILAI_PEROLEHAN" name="NILAI_PEROLEHAN" class="form-control money" required value="<?php echo !$onAdd ? $harta->NILAI_PEROLEHAN : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Nilai Estimasi Saat Pelaporan <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_estimasi_pelaporan'); ?>
                <input type="text" id="NILAI_PELAPORAN" name="NILAI_PELAPORAN" class="form-control money" required value="<?php echo !$onAdd ? $harta->NILAI_PELAPORAN : ''; ?>" />
            </div>
        </div>
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit" id="button-saved" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
    </div>
</form>
<!---END HARTA TIDAK BERGERAK -->
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript">

    $(document).ready(function () {

        var list_jenis_harta = load_html('portal/data_harta/get_jenis_harta_with_data/6', '<?php echo $harta->KODE_JENIS; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo $harta->ASAL_USUL; ?>');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li4&bottomli=5';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});


        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker({
            useCurrent: false, /*ab membuat nilai false pada default value di text box*/  
            viewMode: 'years',
            format: "YYYY",
            maxDate: 'now'
        }).on('dp.change dp.show',function(){ 
        });
 
        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker('option', {maxDate: 'now'});            
    });

</script>

