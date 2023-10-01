<!---HARTA MESIN -->
<?php $onAdd = isset($onAdd) ? $onAdd : FALSE; ?>
<form role="form" id="ajaxFormEdit" action="index.php/efill/validasi_harta_bergerak/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HARTA BERGERAK (ALAT TRANSPORTASI DAN MESIN)</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-4">
            <?php if(!$onAdd): ?>
            <input type="hidden" name="id_imp_xl_lhkpn_harta_bergerak" value='<?php echo $item->id_imp_xl_lhkpn_harta_bergerak_secure; ?>' id="ID"/>
            <?php endif; ?>
            <div class="form-group">
                <label>Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_hb'); ?>
                <select name="KODE_JENIS" id="KODE_JENIS" class="form-control" required></select>  
            </div>
            <div class="form-group">
                <label>Merek <span class="red-label">*</span></label> <?= FormHelpPopOver('merek_hb'); ?>
                <input type="text" name="MEREK" id="MEREK"  class="form-control input_capital" required  value='<?php echo !$onAdd ? $item->MEREK : ''; ?>' />   
            </div>
            <div class="form-group">
                <label>Tipe / Model <span class="red-label">*</span></label> <?= FormHelpPopOver('tipe_hb'); ?>
                <input type="text" name="MODEL" id="MODEL"  class="form-control input_capital" required value='<?php echo !$onAdd ? $item->MODEL : ''; ?>' />   
            </div>
            <div class="form-group form-tahun">
                <label>Tahun Pembuatan <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_pembuatan_hb'); ?>
                <input type="text" name="TAHUN_PEMBUATAN" id="TAHUN_PEMBUATAN"  class="form-control year" value="<?php echo !$onAdd ? $item->TAHUN_PEMBUATAN : ''; ?>" />
                <small class="help-block notif-tahun" style="color:#a94442; display:none;">Data ini wajib di isi</small>   
            </div>
            <div class="form-group">
                <label>No Pol./Registrasi <span class="red-label">*</span></label> <?= FormHelpPopOver('no_registrasi_hb'); ?>
                <input type="text" name="NOPOL_REGISTRASI" id="NOPOL_REGISTRASI" class="form-control input_capital" required value="<?php echo !$onAdd ? $item->NOPOL_REGISTRASI : ''; ?>">
            </div>
            <div class="form-group">
                <label>Jenis Bukti <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_bukti_hb'); ?>
                <select name="JENIS_BUKTI" id="JENIS_BUKTI" class="form-control" required></select>  
            </div>
            <div class="form-group">
                <label>Atas Nama <span class="red-label">*</span></label> <?= FormHelpPopOver('atas_nama_hb'); ?>
                <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $onAdd ? '' : ($item->ATAS_NAMA == 1 ? "selected" : ""); ?> >PN YANG BERSANGKUTAN</option>  
                    <option value="2" <?php echo $onAdd ? '' : ($item->ATAS_NAMA == 2 ? "selected" : ""); ?> >PASANGAN / ANAK</option>  
                    <option value="3" <?php echo $onAdd ? '' : ($item->ATAS_NAMA == 3 ? "selected" : ""); ?> >LAINNYA</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_hb'); ?>
                <table class="table" id="table-asal-usul" required>                    
                </table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo !$onAdd ? $item->ATAS_NAMA_LAINNYA : ''; ?>' />
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pemanfaatan<span class="red-label">*</span></label> <?= FormHelpPopOver('pemanfaatan_hb'); ?>
                <table class="table" id="table-pemanfaatan" required>
                </table>
            </div>
            <div class="form-group">
                <label>Nilai Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_perolehan_hb'); ?>
                <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" class="form-control money" required value="<?php echo !$onAdd ? $item->NILAI_PEROLEHAN : ''; ?>" />   
            </div>
            <div class="form-group">
                <label>Nilai Estimasi Saat Pelaporan <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_hb'); ?>
                <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" class="form-control money" required value="<?php echo !$onAdd ? $item->NILAI_PELAPORAN : ''; ?>" />   
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

    $(document).ready(function () {
        
        var list_jenis_harta = load_html('portal/data_harta/get_jenis_harta_with_data/2', '<?php echo !$onAdd ? $item->KODE_JENIS : ''; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);
        
        var list_jenis_bukti = load_html('portal/data_harta/get_jenis_bukti_with_data/2', '<?php echo !$onAdd ? $item->JENIS_BUKTI : ''; ?>');
        $('#JENIS_BUKTI').html(list_jenis_bukti);

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo !$onAdd ? $item->ASAL_USUL : ''; ?>');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        var list_pemanfaatan = load_html('portal/data_harta/get_pemanfaatan_with_data/2', '<?php echo !$onAdd ? $item->PEMANFAATAN : ''; ?>');
        $('#table-pemanfaatan').html('<tbody>' + list_pemanfaatan + '</tbody>');
        
        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li3&bottomli=li12';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
    });
</script>