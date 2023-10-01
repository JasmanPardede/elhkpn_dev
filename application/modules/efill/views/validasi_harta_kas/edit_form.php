<!---HARTA KAS -->
<?php $onAdd = isset($onAdd) ? $onAdd : FALSE; ?>
<form role="form" id="ajaxFormEdit" action="index.php/efill/validasi_harta_kas/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA KAS DAN SETARA KAS</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-4">
            <?php if (!$onAdd): ?>
                <input type="hidden" name="id_imp_xl_lhkpn_harta_kas" id="id_imp_xl_lhkpn_harta_kas" value="<?php echo $item->id_imp_xl_lhkpn_harta_kas_secure; ?>" />
            <?php endif; ?>
            <div class="form-group">
                <label>Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_ksk'); ?>
                <select class="form-control" id="KODE_JENIS" name="KODE_JENIS" required></select>  
            </div>
            <div class="form-group">
                <label>Nama Bank/Lembaga Keuangan </label> <?= FormHelpPopOver('nama_bank_ksk'); ?>
                <input type="text" name="NAMA_BANK" id="NAMA_BANK" class="form-control input_capital" value="<?php echo!$onAdd ? $item->NAMA_BANK : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Nomor Rekening </label> <?= FormHelpPopOver('no_rek_ksk'); ?>
                <input type="text" name="NOMOR_REKENING" id="NOMOR_REKENING" class="form-control input_capital" value="<?php echo!$onAdd ? $item->NOMOR_REKENING : ''; ?>" />
            </div>
            <div class="form-group">
                <label>File Bukti </label> <?= FormHelpPopOver('file_bukti'); ?>
                <?php echo form_dropdown('FILE_BUKTI', $file_list, !$onAdd ? $item->FILE_BUKTI : '', "class=\"form-control\""); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_ksk'); ?>
                <select name="ATAS_NAMA_REKENING" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $onAdd ? '' : ($item->ATAS_NAMA_REKENING == '1' ? "selected" : ""); ?>>PN YANG BERSANGKUTAN</option>
                    <option value="2" <?php echo $onAdd ? '' : ($item->ATAS_NAMA_REKENING == '2' ? "selected" : ""); ?>>PASANGAN / ANAK</option>
                    <option value="3" <?php echo $onAdd ? '' : ($item->ATAS_NAMA_REKENING == '3' ? "selected" : ""); ?>>LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo!$onAdd ? $item->ATAS_NAMA_LAINNYA : ''; ?>' />
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_ksk'); ?>
                <table class="table" id="table-asal-usul" required></table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Jenis Mata Uang <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_mata_uang_ksk'); ?>
                <select class="form-control" id="MATA_UANG" name="MATA_UANG" required></select>
            </div>
            <div class="form-group">
                <label>Nilai Kurs <span class="red-label">*</span></label> <?= FormHelpPopOver('kurs_ksk'); ?>
                <input type="text" id="NILAI_KURS" name="NIlAI_KURS" class="form-control money" required value="<?php echo!$onAdd ? $item->NILAI_KURS : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Nilai Saldo<span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_saldo_ksk'); ?>
                <input type="text" id="NILAI_SALDO" name="NILAI_SALDO" class="form-control money" required value="<?php echo!$onAdd ? $item->NILAI_SALDO : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Ekuivalen ke kurs Rp.</label> <?= FormHelpPopOver('ekuivalen_ksk'); ?>
                <input type="text" id="NILAI_EQUIVALEN" name="NILAI_EQUIVALEN" class="form-control" readonly="true" value="<?php echo!$onAdd ? $item->NILAI_EQUIVALEN : ''; ?>" >
            </div>
        </div>
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit" id="button-saved" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
    </div>
</form>
<!---END HARTA TIDAK BERGERAK -->


<script type="text/javascript">

    var remove_mask = function (i) {
        var s = i.split('.').join('');
        return s.split(',').join('.');
    };
    var add_mask = function(i){
        var s = i.split(',').join('--');
        s = s.split('.').join(',');
        return s.split('--').join('.');
    };
    var hitung_equivalent = function (s, k) {
        if (isDefined(s) && isDefined(k)) {
            s = remove_mask(s);
            k = remove_mask(k);
            s = parseInt(s) > 0 ? parseInt(s) : 0;
            k = parseInt(k) > 0 ? parseInt(k) : 0;
            var r = add_mask((s * k).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            
            $("#NILAI_EQUIVALEN").val(r);
        }
    };

    $(document).ready(function () {

        var list_jenis_harta = load_html('portal/data_harta/get_jenis_harta_with_data/5', '<?php echo!$onAdd ? $item->KODE_JENIS : ''; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);

        var list_mata_uang = load_html('portal/data_harta/get_mata_uang_with_data/', '<?php echo!$onAdd ? $item->MATA_UAN : ''; ?>');
        $('#MATA_UANG').html(list_mata_uang);

        var list_jenis_bukti = load_html('portal/data_harta/get_jenis_bukti_with_data/1', '<?php echo!$onAdd ? $item->JENIS_BUKTI : ''; ?>');
        $('#JENIS_BUKTI').html(list_jenis_bukti);

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo!$onAdd ? $item->ASAL_USUL : ''; ?>');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        $("#NILAI_SALDO, #NILAI_KURS").change(function (e) {
            e.preventDefault();
            hitung_equivalent($("#NILAI_SALDO").val(), $("#NILAI_KURS").val());
        });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li3&bottomli=li15';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        hitung_equivalent($("#NILAI_SALDO").val(), $("#NILAI_KURS").val());
    });

</script>