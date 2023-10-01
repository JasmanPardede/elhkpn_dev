<div id="wrapperFormDetail">
    <div class="row">
        <div class="col-md-3">
            <?php if (file_exists("uploads/data_pribadi/" . $item->NIK . "/" . $item->FOTO) && !empty($item->FOTO)) { ?>
                <img src='<?php echo base_url("uploads/data_pribadi/" . $item->NIK . "/" . $item->FOTO); ?>' class="img-rounded col-md-12"/>
            <?php } else { ?>
                <img src="<?php echo base_url(); ?>images/no_available_image.png" class="img-rounded col-md-12"/>
            <?php } ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">NIK </label>
                    <div class="col-sm-8"> :
                        <?= $NIK ?: '' ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama </label>
                    <div class="col-sm-8"> :
                        <?= $NAMA ?: '' ?>
                    </div>
                </div> 
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Jabatan </label>
                    <div class="col-sm-8"> :
                    <?= ($DESKRIPSI_JABATAN ?: '') . ' - ' . ($SUB_UNIT_KERJA ?: '') . ' - ' . ($UNIT_KERJA ?: '') . ' - ' . ($NAMA_LEMBAGA ?: '') ?>
                    </div>
                </div> 
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Status On/Off </label>
                    <div class="col-sm-8"> :
                        <?= $status_wl ?: '' ?>
                    </div>
                </div> 
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tahun WL </label>
                    <div class="col-sm-8"> :
                        <?= date('Y') ?>
                    </div>
                </div> 
            </div>     
        </div>
    </div>
    <br /><br />
    <div class="pull-right">
        <button type="button" class="btn btn-info btn-sm proses-copy-wl"><i class="fa fa-clone"></i> Salin Wajib Lapor</button>
        <button type="button" class="btn btn-warning btn-sm cancel" onclick="CloseModalBox2();"><i class="fa fa-remove"></i> Batal</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.proses-copy-wl').click(function() {
            id_pn_jabatan = '<?= $ID ?>';
            base_url = '<?= base_url(); ?>';
            url = base_url + 'index.php/ereg/all_pn/salin_wl/' + id_pn_jabatan;
            $('.cancel, .proses-copy-wl').prop('disabled', true);
            $.post(url, function (data) {
                if (data == 1) {
                    var dtTable = $('#dt_completeNEW').DataTable();
                    dtTable.ajax.reload( null, false );
                    alertify.success('Wajib Lapor berhasil disalin.');
                    CloseModalBox2();
                } else {
                    alertify.error('Terjadi kesalahan pada saat salin WL.');
                    $('.cancel').prop('disabled', false);
                }
            });
        })
    });
</script>