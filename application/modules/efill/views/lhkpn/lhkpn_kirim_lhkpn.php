

<div class="box-header with-border portlet-header">
    <h3 class="box-title">Kirim LHKPN</h3>
</div><!-- /.box-header -->
<div class="box-body">
    <div class="row" style="padding: 5px;">
        <p>Mohon pastikan kembali semua data telah sesuai dengan laporan yang terdapat pada file Excel sebelum melakukan kirim LHKPN.</p>
    </div>
    <div class="row">
        <br>
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>&nbsp;

            <a target="_blank" href="<?php echo base_url(); ?>portal/ikthisar/cetak/<?php echo $id_imp_xl_lhkpn; ?>/cetakvalidasi" class="btn btn-primary btn-sm" style="margin-right: 5px;">
                <i class="fa fa-print"></i>  Cetak Ikhtisar Harta
            </a>&nbsp;

            <?php if ($UNIT_KERJA_ADA && $ID_JABATAN_ADA): ?>
                <button id="btn-preview-lp" type="button" rel="<?php echo $id_imp_xl_lhkpn; ?>" class="btn btn-success btn-sm"> <i class="fa fa-tv"></i> Preview LP</button>&nbsp;
                <button id="btn-terima-lhkpn" type="button" rel="<?php echo $id_imp_xl_lhkpn; ?>" class="btn btn-success btn-sm"> <i class="fa fa-send"></i> Valid dan Terima LHKPN</button>&nbsp;
            <?php else: ?>
                <br />
                <br />
                <strong>JABATAN Tidak terdeteksi. Mohon cek kembali.</strong>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <div class="clearfix">
            <!-- </form> -->
        </div>
    </div>
</div><!-- /.box-body -->

<script type="text/javascript">
    $(document).ready(function () {
        $('.custom-hover').css({cursor: 'pointer'});
        $('tr.none').css({cursor: 'default'});
    });
</script>

