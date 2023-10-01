<!---HARTA TIDAK BERGERAK -->
<style type="text/css">
    .help-block-red{
        color: #c93840;
    }
</style>
<form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>" >
    <div class="modal-body row">
        <div class="col-sm-12">
            <input type="hidden" name="ID" id="ID" value='<?php echo $id; ?>' />
            <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_fas'); ?>
                        <select name="JENIS_FASILITAS" id="JENIS_FASILITAS" class="form-control" required>
                        <option></option>
                    	<option value="RUMAH DINAS" <?php echo !$onAdd && strtoupper($fasilitas->JENIS_FASILITAS) == 'RUMAH DINAS' ? 'selected' : ''; ?>>RUMAH DINAS</option>
                        <option value="BIAYA HIDUP" <?php echo !$onAdd && strtoupper($fasilitas->JENIS_FASILITAS) == 'BIAYA HIDUP' ? 'selected' : ''; ?>>BIAYA HIDUP</option>
                        <option value="JAMINAN KESEHATAN" <?php echo !$onAdd && strtoupper($fasilitas->JENIS_FASILITAS) == 'JAMINAN KESEHATAN' ? 'selected' : ''; ?>>JAMINAN KESEHATAN</option>
                        <option value="MOBIL DINAS" <?php echo !$onAdd && strtoupper($fasilitas->JENIS_FASILITAS) == 'MOBIL DINAS' ? 'selected' : ''; ?>>MOBIL DINAS</option>
                        <option value="OPSI PEMBELIAN SAHAM" <?php echo !$onAdd && strtoupper($fasilitas->JENIS_FASILITAS) == 'OPSI PEMBELIAN SAHAM' ? 'selected' : ''; ?>>OPSI PEMBELIAN SAHAM</option>
                        <option value="FASILITAS LAINNYA" <?php echo !$onAdd && strtoupper($fasilitas->JENIS_FASILITAS) == 'FASILITAS LAINNYA' ? 'selected' : ''; ?>>FASILITAS LAINNYA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan </label> <?= FormHelpPopOver('keterangan_fas'); ?>
                        <textarea class="form-control input_capital" name="KETERANGAN" id="KETERANGAN" rows="3" ><?php echo !$onAdd ? $fasilitas->KETERANGAN : ''; ?></textarea>
                    </div> 
                    <div class="form-group">
                        <label>Nama Pihak Pemberi Fasilitas <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_pihak_pemberi_fas'); ?>
                        <input type="text"  id="PEMBERI_FASILITAS" name="PEMBERI_FASILITAS"  class="form-control input_capital" value="<?php echo !$onAdd ? $fasilitas->PEMBERI_FASILITAS : ''; ?>" required/>
                    </div>
                    <div class="form-group">
                        <label>Keterangan Lain </label> <?= FormHelpPopOver('keterangan_lain_fas'); ?>
                        <textarea class="form-control input_capital" name="KETERANGAN_LAIN" id="KETERANGAN_LAIN" rows="4"><?php echo !$onAdd ? $fasilitas->KETERANGAN_LAIN : ''; ?></textarea>
                    </div>   
          
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit"  id="button-saved"  class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> <i class="fa fa-remove"></i> Batal</button>
    </div>
    </div>
</form>

<!---END HARTA TIDAK BERGERAK -->
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js"></script>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('.over').popover();
            $('.over')
                    .mouseenter(function (e) {
                        $(this).popover('show');
                    })
                    .mouseleave(function (e) {
                        $(this).popover('hide');
                    });
        });

    
        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li7&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
</script>