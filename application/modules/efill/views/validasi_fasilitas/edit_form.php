<?php $onAdd = isset($onAdd) ? $onAdd : FALSE; ?>
<form role="form" id="ajaxFormEdit" method="POST"  action="index.php/efill/validasi_fasilitas/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA LAMPIRAN FASILITAS</h4>
    </div>
    <div class="modal-body">
        <?php if(!$onAdd): ?>
        <input type="hidden" name="id_imp_xl_lhkpn_fasilitas" id="ID_imp_xl_lhkpn_fasilitas" value='<?php echo $item->id_imp_xl_lhkpn_fasilitas_secure; ?>'/>
        <?php endif; ?>
        <div class="form-group">
            <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_fas'); ?>
            <select name="JENIS_FASILITAS" id="JENIS_FASILITAS" class="form-control" required>
                <option></option>	
                <option value="RUMAH DINAS" <?php echo strtoupper($item->JENIS_FASILITAS) == 'RUMAH DINAS' ? "selected" : ""; ?>>RUMAH DINAS</option>
                <option value="BIAYA HIDUP" <?php echo strtoupper($item->JENIS_FASILITAS) == 'BIAYA HIDUP' ? "selected" : ""; ?>>BIAYA HIDUP</option>
                <option value="JAMINAN KESEHATAN" <?php echo strtoupper($item->JENIS_FASILITAS) == 'JAMINAN KESEHATAN' ? "selected" : ""; ?>>JAMINAN KESEHATAN</option>
                <option value="MOBIL DINAS" <?php echo strtoupper($item->JENIS_FASILITAS) == 'MOBIL DINAS' ? "selected" : ""; ?>>MOBIL DINAS</option>
                <option value="OPSI PEMBELIAN SAHAM" <?php echo strtoupper($item->JENIS_FASILITAS) == 'OPSI PEMBELIAN SAHAM' ? "selected" : ""; ?>>OPSI PEMBELIAN SAHAM</option>
                <option value="FASILITAS LAINNYA" <?php echo strtoupper($item->JENIS_FASILITAS) == 'FASILITAS LAINNYA' ? "selected" : ""; ?>>FASILITAS LAINNYA</option>
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan </label> <?= FormHelpPopOver('keterangan_fas'); ?>
            <textarea class="form-control" name="KETERANGAN" id="KETERANGAN" rows="3"  ><?php echo $item->KETERANGAN; ?></textarea>
        </div> 
        <div class="form-group">
            <label>Nama Pihak Pemberi Fasilitas <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_pihak_pemberi_fas'); ?>
            <input type="text"  id="PEMBERI_FASILITAS" name="PEMBERI_FASILITAS"  class="form-control" required value='<?php echo $item->PEMBERI_FASILITAS; ?>' />
        </div>
        <div class="form-group">
            <label>Keterangan Lain </label> <?= FormHelpPopOver('keterangan_lain_fas'); ?>
            <textarea class="form-control" name="KETERANGAN_LAIN" id="KETERANGAN_LAIN" rows="4"  ><?php echo $item->KETERANGAN_LAIN; ?></textarea>
        </div>   


    </div>
    <div class="modal-footer">
        <button type="submit" id="btn-submit" class="btn btn-primary btn-sm">
            <i class="fa fa-save"></i> Simpan
        </button>
        <button type="button" id="btn-cancel" class="btn btn-danger btn-sm" data-dismiss="modal">
            <i class="fa fa-close"></i> Batal
        </button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li6&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

    });
</script>