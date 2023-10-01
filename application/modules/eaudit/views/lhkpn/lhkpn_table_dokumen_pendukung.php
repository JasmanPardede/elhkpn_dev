<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Lampiran KTP, KK, NPWP, SK jabatan, Kartu Keluarga(KK), dan Bukti Rekening/Deposito"</h5>
</div>
<div class="box-body form-horizontal" id="wrapperDokumenPendukung">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Data Pribadi</h3>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">No</th>
                    <th>Nama Dokumen</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(count($DATA_PRIBADI) == 0) { ?>
                        <tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>
                    <?php } else {
                        $file_pribados = array(
                            '1'=>array('FILE' => $DATA_PRIBADI->FOTO, 'NAME' => 'Pas Foto'),
                            '2'=>array('FILE' => $DATA_PRIBADI->FILE_KTP, 'NAME' => 'KTP'),
                            '3'=>array('FILE' => $DATA_PRIBADI->FILE_NPWP, 'NAME' => 'NPWP'),
                            '4'=>array('FILE' => $LHKPN->FILE_KK, 'NAME' => 'Kartu Keluarga'),
                        );
                        $i = 1; foreach ($file_pribados as $key) :
                    ?>
                        <tr>
                            <td><?= @$i++; ?></td>
                            <td>
                                <?php if($key['FILE'] != '') { ?>
                                    <?php if($key['NAME'] == 'Kartu Keluarga') { ?>
                                        <a href="<?php echo 'uploads/data_keluarga/'.$LHKPN->NIK.'/'.$LHKPN->FILE_KK; ?>" target="_BLANK"><?=$key['NAME']?></a>
                                    <?php } else { ?>
                                        <a href="uploads/data_pribadi/<?= @$DATA_PRIBADI->NIK; ?>/<?php echo $key['FILE']; ?>" target="_BLANK"><?=$key['NAME']?></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?=$key['NAME']?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($key['FILE'] != ''){ ?>
                                    <?php if($key['NAME'] == 'Kartu Keluarga') { ?>
                                        <a href="<?php echo 'uploads/data_keluarga/'.$LHKPN->NIK.'/'.$LHKPN->FILE_KK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted("uploads/data_keluarga/".@$LHKPN->NIK."/".@$LHKPN->FILE_KK); ?></a>
                                    <?php } else { ?>
                                        <a href="uploads/data_pribadi/<?= @$DATA_PRIBADI->NIK; ?>/<?php echo $key['FILE']; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted("uploads/data_pribadi/".@$DATA_PRIBADI->NIK."/".@$key['FILE']); ?></a>
                                    <?php } ?>
                                <?php }else{ echo '-'; }?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Jabatan</h3>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">No</th>
                    <th>Jabatan</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $i = 1; 
                    foreach ($JABATANS as $jbt) :
                    $fileDt = explode('/', $jbt->FILE_SK);
                ?>
                    <tr>
                        <td><?= @$i++; ?></td>
                        <td>
                            <?php if($jbt->FILE_SK != '') { ?>
                                <a href="<?php echo base_url('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$jbt->FILE_SK); ?>" target="_BLANK"><?=$jbt->NAMA_JABATAN?></a>
                            <?php } else { ?>
                                <?php echo $jbt->NAMA_JABATAN ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if(@$jbt->FILE_SK != ''){ ?>
                                <a href="<?php echo base_url('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$jbt->FILE_SK); ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$jbt->FILE_SK); ?></a>
                            <?php }else{ echo '-'; } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Bukti Rekening / Deposito</h3>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">No</th>
                    <th>Nama Dokumen</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($KAS) == 0) { ?>
                    <tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>
                <?php } else { ?>
                    <?php 
                        $i = 1; foreach ($KAS as $key) { 
                            switch ($key->KODE_JENIS) {
                                case '1':
                                    $label = 'Uang Tunai';
                                    break;
                                case '2':
                                    $label = 'Deposito';
                                    break;
                                case '3':
                                    $label = 'Giro';
                                    break;
                                case '4':
                                    $label = 'Tabungan';
                                    break;
                                default:
                                    $label = 'Lainnya';
                                    break;
                            }
                    ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td>
                                <a href="<?php echo 'uploads/data_kas/'.$LHKPN->NIK.'/'.$key->FILE_BUKTI; ?>" target="_BLANK"><?=$label.' Atas Nama '.$key->ATAS_NAMA_REKENING?></a>
                            </td>
                            <td>
                                <a href="<?php echo 'uploads/data_kas/'.$LHKPN->NIK.'/'.$key->FILE_BUKTI; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_kas/'.$LHKPN->NIK.'/'.$key->FILE_BUKTI); ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if($mode == 'edit' && $status_lhkpn == '2') { ?>
    <div class="clearfix" style="margin-top: 20px;"></div>
    <div class="col-md-7" style="margin-left: -17px;">
        <fieldset class="fieldset">
            <legend class="legend_kk">Hasil Verifikasi</legend>
            <div class="form-group">
                <label class="control-label col-md-3">Terverfikasi <span style="float: right;">:</span></label>
                <div class="col-md-9">
                    <p><?php echo ($hasilVerifikasi->VAL->DOKUMENPENDUKUNG == '1') ? 'Ya' : 'Tidak'; ?></p>
                </div>
                <label class="control-label col-md-3">Alasan <span style="float: right;">:</span></label>
                <div class="col-md-9">
                    <p><?php echo $hasilVerifikasi->MSG->DOKUMENPENDUKUNG ?></p>
                </div>
            </div>
        </fieldset>
    </div>
    <?php } ?>
    
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
$(document).ready(function() {
    // #wrapperHutang
    $("#wrapperDokumenPendukung .btn-detail").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Detail Dokumen Pendukung', html, '', 'large');
        });
        return false;
    });
    $("#wrapperDokumenPendukung .btn-add").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Tambah Dokumen Pendukung', html, '', 'large');
        });
        return false;
    });
    // ctrl + a
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.which === 65 || e.which === 97) {
            e.preventDefault();
            $('#wrapperHutang .btn-add').trigger('click');
            return false;
        }
    });
    $('#wrapperDokumenPendukung .btn-edit').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Edit Dokumen Pendukung', html, '', 'large');
        });
        return false;
    });
    $('#wrapperDokumenPendukung .btn-delete').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Delete Dokumen Pendukung', html, '', 'large');
        });
        return false;
    });
});
</script>