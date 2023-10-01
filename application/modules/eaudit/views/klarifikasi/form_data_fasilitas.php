<form role="form" id="ajaxFormEdit" method="POST"  action="index.php/eaudit/klarifikasi/<?php echo $action; ?>" >
    <!-- <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA LAMPIRAN FASILITAS</h4>
    </div> -->
    <div class="modal-body">
        <input type="hidden" name="ID" id="ID" value='<?php echo $ID; ?>'/>
        <div class="form-group">
            <label>Jenis <span class="red-label">*</span> </label>
            <select name="JENIS_FASILITAS" id="JENIS_FASILITAS" class="form-control" required>
                <option></option>	
                <option value="RUMAH DINAS" <?php echo $onAdd ? (strtoupper($fasilitas->JENIS_FASILITAS) == 'RUMAH DINAS' ? "selected" : "") : ''; ?>>RUMAH DINAS</option>
                <option value="BIAYA HIDUP" <?php echo $onAdd ? (strtoupper($fasilitas->JENIS_FASILITAS) == 'BIAYA HIDUP' ? "selected" : "") : ''; ?>>BIAYA HIDUP</option>
                <option value="JAMINAN KESEHATAN" <?php echo $onAdd ? (strtoupper($fasilitas->JENIS_FASILITAS) == 'JAMINAN KESEHATAN' ? "selected" : "") : ''; ?>>JAMINAN KESEHATAN</option>
                <option value="MOBIL DINAS" <?php echo $onAdd ? (strtoupper($fasilitas->JENIS_FASILITAS) == 'MOBIL DINAS' ? "selected" : "") : ''; ?>>MOBIL DINAS</option>
                <option value="OPSI PEMBELIAN SAHAM" <?php echo $onAdd ? (strtoupper($fasilitas->JENIS_FASILITAS) == 'OPSI PEMBELIAN SAHAM' ? "selected" : "") : ''; ?>>OPSI PEMBELIAN SAHAM</option>
                <option value="FASILITAS LAINNYA" <?php echo $onAdd ? (strtoupper($fasilitas->JENIS_FASILITAS) == 'FASILITAS LAINNYA' ? "selected" : "") : ''; ?>>FASILITAS LAINNYA</option>
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan Fasilitas </label>
            <textarea class="form-control" name="KETERANGAN" id="KETERANGAN" rows="3"  ><?php echo $onAdd ? $fasilitas->KETERANGAN : ''; ?></textarea>
        </div> 
        <div class="form-group">
            <label>Nama Pihak Pemberi Fasilitas <span class="red-label">*</span> </label>
            <input type="text"  id="PEMBERI_FASILITAS" name="PEMBERI_FASILITAS"  class="form-control" required value='<?php echo $onAdd ? $fasilitas->PEMBERI_FASILITAS : ""; ?>' />
        </div>
        <div class="form-group">
            <label>Keterangan Lain </label>
            <textarea class="form-control" name="KETERANGAN_LAIN" id="KETERANGAN_LAIN" rows="4"  ><?php echo $onAdd ? $fasilitas->KETERANGAN_LAIN : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label>Keterangan Pemeriksaan </label>
            <textarea class="form-control" name="KET_PEMERIKSAAN" id="KET_PEMERIKSAAN" rows="4"  ><?php echo $onAdd ? $fasilitas->KET_PEMERIKSAAN : ''; ?></textarea>
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