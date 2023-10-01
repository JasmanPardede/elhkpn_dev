<?php
$primary_key_secure = $primary_key . "_secure";
$onAdd = isset($onAdd) ? $onAdd : FALSE;
?>
<form role="form" id="ajaxFormEdit" action="index.php/efill/validasi_pelepasan/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATAHUTANG</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-6">
            <?php if ($onAdd): ?>
                <div class="form-group">
                    <label>Kategori Harta <span class="red-label">*</span> </label>
                    <select name="jpl" id="jpl" class="form-control" required>
                        <?php foreach ($array_jenis_pelepasan as $code_kategori_harta => $nama_kategori_harta): ?>
                            <option value="<?php echo $code_kategori_harta; ?>"><?php echo $nama_kategori_harta; ?></option>
                        <?php endforeach; ?>
                    </select>  
                </div>
            <?php endif; ?>
            <div class="form-group">
                <?php if (!$onAdd): ?>
                    <input type="hidden" name="<?php echo $primary_key; ?>" id="<?php echo $primary_key; ?>" value="<?php echo $item->{$primary_key_secure}; ?>" />
                    <input type="hidden" name="jpl" id="jpl" value="<?php echo $jenis_pelepasan; ?>" />
                <?php endif; ?>
                <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_htg'); ?>
                <select name="JENIS_PELEPASAN_HARTA" id="JENIS_PELEPASAN_HARTA" class="form-control" required></select>  
            </div>
            <div class="form-group">
                <label>Keterangan </label> <?= FormHelpPopOver('keterangan_popup'); ?>
                <textarea class="form-control" name="URAIAN_HARTA" id="URAIAN_HARTA" rows="2" ><?php echo !$onAdd ? $item->URAIAN_HARTA : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Nilai Pelepasan <span class="red-label">*</span></label>
                <input type="text" name="NILAI_PELEPASAN" id="NILAI_PELEPASAN"  class="form-control money" value="<?php echo !$onAdd ? $item->NILAI_PELEPASAN : ''; ?>" />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Pihak Kedua</strong></label> 
            </div>
            <div class="form-group">
                <label>Nama <span class="red-label">*</span></label> <?= FormHelpPopOver('nama_pihak_kedua_popup'); ?>
                <input type="text" name="NAMA" id="NAMA"  class="form-control input_capital" required  value="<?php echo !$onAdd ? $item->NAMA : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Alamat <span class="red-label">*</span></label> <?= FormHelpPopOver('alamat_pihak_kedua_popup'); ?>
                <textarea class="form-control" name="ALAMAT" id="ALAMAT" rows="2" required><?php echo !$onAdd ? $item->ALAMAT : ''; ?></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {

        var list_jenis_harta = load_html('portal/data_harta/get_jenis_pelepasan_with_data', '<?php echo !$onAdd ? $item->JENIS_PELEPASAN_HARTA : ''; ?>');
        $('#JENIS_PELEPASAN_HARTA').html(list_jenis_harta);

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li7&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
    });
</script>