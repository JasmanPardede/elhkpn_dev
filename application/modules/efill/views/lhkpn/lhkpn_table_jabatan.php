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
 * @package Views/efill/lhkpn
*/
?>
<div class="box-body" id="wrapperJabatan">

    <div class="box-header with-border portlet-header title-alat">
        <h5 class="">"Informasi Jabatan Saat Ini"</h5>
    </div>
    <?php if($mode == 'edit') { ?>
        <?php if($status_lhkpn == '2' && $hasilVerifikasi->VAL->JABATAN == '1') { ?>
        <?php } else { ?>
            <button type="button" class="btn btn-sm btn-add btn-primary" href="index.php/efill/lhkpn/addjabatan/<?php echo $id_lhkpn;?>"><font color='white'><i class="fa fa-plus"></i> Tambah</font></button>
        <?php } ?>
	<?php } ?>
    <table class="table table-bordered table-hover table-striped" >
		<thead class="table-header">
			<tr>
                <th class="<?php echo ($status_lhkpn == '0') ? 'hidden' : ''; ?>">Primary</th>
                <th width="30">N0</th>
                <th>JABATAN - DESKRIPSI JABATAN</th>
                <th>LEMBAGA</th>
                <th>UNIT KERJA</th>
                <!--<th>TMT/SD</th>-->
                <!--<th>FILE SK</th>-->
                <?php if($mode == 'edit') { ?>
<!--                    --><?php //if($status_lhkpn == '2' && $hasilVerifikasi->VAL->JABATAN == '1') { ?>
<!--                    --><?php //} else { ?>
                        <th>AKSI</th>
<!--                    --><?php //} ?>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
                echo (count($JABATANS) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            foreach ($JABATANS as $jabatan) {
            ?>
            <tr>
                <td class="<?php echo ($status_lhkpn == '0') ? 'hidden' : ''; ?>"><center><?php echo ($jabatan->IS_PRIMARY == '1') ? '<i style="cursor: help;" title="Jabatan Utama" class="primkey fa fa-key"></i>' : ''; ?></center></td>
                <td><?php echo ++$i; ?>.</td>
                <td><?php echo $jabatan->NAMA_JABATAN; ?> - <?php echo $jabatan->DESKRIPSI_JABATAN; ?>
                <?php 
                /*switch ($jabatan->ESELON) {
                    case '1':
                        echo 'I';
                        break;
                    case '2':
                        echo 'II';
                        break;
                    case '3':
                        echo 'III';
                        break;
                    case '4':
                        echo 'IV';
                        break;
                    case '5':
                        echo 'Non Eselon';
                        break;
                }*/
                ?>
                </td>
                <td><?php echo $jabatan->INST_NAMA; ?></td>
                <td><?php echo $jabatan->UK_NAMA; ?></td>
                <!--<td align="center"><?php echo date('d/m/Y',strtotime($jabatan->TMT)); ?> <?php echo $jabatan->SD=='0000/00/00' || $jabatan->SD=='' || $jabatan->SD==NULL || date('Y',strtotime($jabatan->SD))=='1970' || date('Y',strtotime($jabatan->SD))=='-0001' ? '<br />s/d<br /> sekarang':'<br />s/d<br />'.date('d/m/Y',strtotime($jabatan->SD)); ?></td>-->
                <!--<td align="center">
                    <?php
                    if($jabatan->FILE_SK){
                    ?>
                        <a href="<?php echo base_url('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$jabatan->FILE_SK); ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.$LHKPN->NIK.'/'.$jabatan->FILE_SK); ?></a>
                    <?php
                    }
                    ?>
                </td>-->
                <?php if($mode == 'edit') { ?>
<!--                    --><?php //if($status_lhkpn == '2' && $hasilVerifikasi->VAL->JABATAN == '1') { ?>
<!--                    --><?php //} else { ?>
                        <td nowrap="" style="text-align:center">
                            <!-- <button type="button" class="btn btn-sm btn-default btn-detail" href="index.php/efill/lhkpn/detailjabatan/<?php echo $jabatan->ID; ?>" title="Detail"><i class="fa fa-search-plus"></i></button> -->
                            <button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/efill/lhkpn/editjabatan/<?php echo $jabatan->ID; ?>" title="Edit"><i class="fa fa-pencil"></i></button>
                            <!-- <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/deletejabatan/<?php echo $jabatan->ID; ?>" title="Delete"><i class="fa fa-trash" ></i></button> -->
                             <?php if ($jabatan->IS_PRIMARY == '0') {?>
                            <button type="button" class='btn btn-sm btn-primjab' title='Set Jabatan Utama' href="index.php/efill/lhkpn/editprimary/<?php echo $jabatan->ID; ?>" ><i class="fa fa-check" ></i></button>
                             <?php } else if($jabatan->IS_PRIMARY == '1') { ?>
                             <button type="button"title='Jabatan Utama' ><i class="fa fa-key" ></i></button>
                          <?php } ?>

                        </td>
<!--                    --><?php //} ?>
                <?php } ?>              
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <?php if($mode == 'edit' && $status_lhkpn == '2') { ?>
    <div class="clearfix" style="margin-top: 20px;"></div>
    <div class="col-md-7" style="margin-left: -17px;">
        <fieldset class="fieldset">
            <legend class="legend_kk">Hasil Verifikasi</legend>
            <div class="form-group">
                <label class="control-label col-md-3">Terverfikasi <span style="float: right;">:</span></label>
                <div class="col-md-9">
                    <p><?php echo ($hasilVerifikasi->VAL->JABATAN == '1') ? 'Ya' : 'Tidak'; ?></p>
                </div>
                <label class="control-label col-md-3">Alasan <span style="float: right;">:</span></label>
                <div class="col-md-9">
                    <p><?php echo $hasilVerifikasi->MSG->JABATAN ?></p>
                </div>
            </div>
        </fieldset>
    </div>
    <?php } ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.primkey').tooltip();
    // #wrapperJabatan
    $("#wrapperJabatan .btn-detail").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Detail Jabatan', html, '', 'large');
        });
        return false;
    });
    $("#wrapperJabatan .btn-add").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Tambah Jabatan', html, '', 'standart');
        });
        return false;
    });
    $('#wrapperJabatan .btn-edit').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Edit Jabatan', html, '', 'standart');
        });
        return false;
    });
    $('#wrapperJabatan .btn-delete').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Delete Jabatan', html, '', 'standart');
        });
        return false;
    });
   $('#wrapperJabatan .btn-primjab').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('SET JABATAN UTAMA', html, '', 'large');
        });
        return false;
    });
});
</script>