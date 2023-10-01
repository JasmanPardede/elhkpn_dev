<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/efill/lhkpn_view
*/
?>
<style type="text/css">
	.div-pribadi-luar {
	    margin-top: 20px;
	    border-style: solid;
	    border-width: 1px;
	    background-color: #F7F7F7;
	    border-radius: 5px;
	    overflow: hidden;
	}
	.judul-header {
	    margin-left: -15px;
	    margin-right: -15px;
	    background-color: #C0C0C0;
	    color: #000;
	}
</style>
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
                                <?php 
                                    if($key['FILE'] != ''){
                                ?>
                                <a href="uploads/data_pribadi/<?= @$DATA_PRIBADI->NIK; ?>/<?php echo $key['FILE']; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted("uploads/data_pribadi/".@$DATA_PRIBADI->NIK."/".@$key['FILE']); ?></a>
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
                                <a href="<?= @$jbt->FILE_SK; ?>" target="_BLANK"><?=$jbt->NAMA_JABATAN?></a>
                            <?php } else { ?>
                                <?php echo $jbt->NAMA_JABATAN ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if(@$jbt->FILE_SK != ''){ ?>
                                <a href="<?= @$jbt->FILE_SK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted($jbt->FILE_SK); ?></a>
                            <?php }else{ echo '-'; } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<!--     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Keluarga</h3>
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
                <tr>
                    <td><?= (@$LHKPN->FILE_KK != '' ? '1' : '-'); ?></td>
                    <td><?= (@$LHKPN->FILE_KK != '' ? @$LHKPN->FILE_KK : '-'); ?></td>
                    <td>
                        <?php if(@$LHKPN->FILE_KK != ''){ ?>
                            <a href="<?php echo 'uploads/data_keluarga/'.$LHKPN->NIK.'/'.$LHKPN->FILE_KK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_keluarga/'.$LHKPN->NIK.'/'.$LHKPN->FILE_KK); ?></a>
                        <?php }else{ echo '-'; } ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div> -->

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
                <?php if(count($REKENINGS) == 0) { ?>
                    <tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>
                <?php } else { ?>
                    <?php $i = 1; foreach ($REKENINGS as $key) { ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td>
                                <?php if($key->FILE_BUKTI != '')  { ?>
                                    <a href="<?php echo 'uploads/data_kas/'.$LHKPN->NIK.'/'.$key->FILE_BUKTI; ?>" target="_BLANK">Rekening Atas Nama <?=$key->ATAS_NAMA_REKENING?></a>
                                <?php } else { ?>
                                    Rekening Atas Nama <?=$key->ATAS_NAMA_REKENING?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($key->FILE_BUKTI != ''){ ?>
                                    <a href="<?php echo 'uploads/data_kas/'.$LHKPN->NIK.'/'.$key->FILE_BUKTI; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_kas/'.$LHKPN->NIK.'/'.$key->FILE_BUKTI); ?></a>
                                <?php } else { echo '-'; } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->