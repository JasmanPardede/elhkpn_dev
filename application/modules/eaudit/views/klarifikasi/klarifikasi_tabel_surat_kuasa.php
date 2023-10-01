<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Surat Kuasa Mengumumkan dan Surat Kuasa"</h5>
</div>
<div class="box-body" id="wrapperDokumenPendukung">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="row">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Kuasa Mengumumkan</h3>
                </div>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <table class="table table-bordered table-hover table-striped" id="tableSKM">
            <thead class="table-header">
                <tr>
                    <th>Dokumen (pdf/doc(x)/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php $ex_file = json_decode($t_lhkpn_pn->FILE_BUKTI_SKM); ?>
                <?php if ($ex_file != ''): ?>
                <?php foreach ($ex_file as $file): ?>
                    <tr fnm="<?php echo $file; ?>">
                        <td class="td-nama-file"><?php echo $file; ?></td>
                        <td class="td-lhkpn-excel">

                            <a class="btn btn-sm remFile" href="#"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                            <a class="btn btn-sm" target="_blank" href="<?php echo base_url('uploads'); ?>/data_skm/<?php echo encrypt_username($t_lhkpn_pn->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/<?php echo $file; ?>"><span class="glyphicon glyphicon-new-window text-danger"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Data Tidak Ditemukan</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="row">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Kuasa</h3>
                </div>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <table class="table table-bordered table-hover table-striped" id="tableSK">
            <thead class="table-header">
                <tr>
                    <th>Surat Kuasa (pdf/doc(x)/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php $ex_file = json_decode($t_lhkpn_pn->FILE_BUKTI_SK); ?>
                <?php if ($ex_file != ''): ?>
                <?php foreach ($ex_file as $file): ?>
                    <tr fnm="<?php echo $file; ?>">
                        <td class="td-nama-file"><?php echo $file; ?></td>
                        <td class="td-lhkpn-excel">
                            <a class="btn btn-sm remFile" href="#"><span class="glyphicon glyphicon-remove text-danger aksi-hide"></span></a>
                            <a class="btn btn-sm" target="_blank" href="<?php echo base_url('uploads'); ?>/data_sk/<?php echo encrypt_username($t_lhkpn_pn->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/<?php echo $file; ?>"><span class="glyphicon glyphicon-new-window text-danger"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Data Tidak Ditemukan</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>

</div>