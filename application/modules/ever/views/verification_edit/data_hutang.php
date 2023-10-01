<form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HUTANG</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-6">
            <div class="form-group">
                <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />
                <label>Jenis <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('jenis_htg'); ?>
                <select name="KODE_JENIS" id="KODE_JENIS" class="form-control" required></select>  
            </div>
            <div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('atas_nama_ksk'); ?>
                <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $onAdd ? '' : ($hutang->ATAS_NAMA == '1' ? "selected" : ""); ?>>PN YANG BERSANGKUTAN</option>
                    <option value="2" <?php echo $onAdd ? '' : ($hutang->ATAS_NAMA == '2' ? "selected" : ""); ?>>PASANGAN / ANAK</option>
                    <option value="3" <?php echo $onAdd ? '' : ($hutang->ATAS_NAMA == '3' ? "selected" : ""); ?>>LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span> <?php echo FormHelpPopOver('keterangan_hb'); ?>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo !$onAdd ? $hutang->ATAS_NAMA_LAINNYA : ''; ?>' />
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nama Kreditur <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('nama_kreditur_htg'); ?>
                <input type="text" placeholder="" id="NAMA_KREDITUR" name="NAMA_KREDITUR"  class="form-control input_capital" required value="<?php echo !$onAdd ? $hutang->NAMA_KREDITUR : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Bentuk Agunan</label> <?php echo FormHelpPopOver('bentuk_agunan_htg'); ?>
                <input type="text" placeholder="" id="AGUNAN" name="AGUNAN"  class="form-control input_capital" value="<?php echo !$onAdd ? $hutang->AGUNAN : ''; ?>" />
            </div> 
            <div class="form-group">
                <label>Nilai Awal Hutang (Rp) <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('nilai_awal_hutang_htg'); ?>
                <input type="text" placeholder="" id="AWAL_HUTANG" name="AWAL_HUTANG"  class="form-control money" required value="<?php echo !$onAdd ? $hutang->AWAL_HUTANG : ''; ?>" />
            </div> 
            <div class="form-group">
                <label>Nilai Saldo Hutang (Rp) <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('nilai_saldo_hutang_htg'); ?>
                <input type="text" placeholder="" id="SALDO_HUTANG" name="SALDO_HUTANG"  class="form-control money" required value="<?php echo !$onAdd ? $hutang->SALDO_HUTANG : ''; ?>" />
            </div> 
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        if ($("#ATAS_NAMA").val() == '3') {
            $('#ket_lainnya_an_div').show();
            $('#ATAS_NAMA_LAINNYA').prop('required', true);
        } else {
            $('#ATAS_NAMA_LAINNYA').removeAttr('required');
            $('#ket_lainnya_an_div').hide();
        }

        $("#ATAS_NAMA").change(function() {
            $("#ATAS_NAMA_LAINNYA").val('');

            if ($("#ATAS_NAMA").val() == '3') {
                $('#ket_lainnya_an_div').show();
                $('#ATAS_NAMA_LAINNYA').prop('required', true);
            } else {
                $('#ATAS_NAMA_LAINNYA').removeAttr('required');
                $('#ket_lainnya_an_div').hide();
            }
        });

        var list_jenis_harta = load_html('portal/data_harta/get_jenis_hutang_with_data', '<?php echo !$onAdd ? $hutang->KODE_JENIS : ''; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li4&bottomli=6';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
</script>