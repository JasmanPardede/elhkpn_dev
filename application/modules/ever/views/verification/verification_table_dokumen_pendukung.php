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
    <h5 class="">"Dokumen Pendukung, contoh Surat Berharga, Kas / Setara Kas dan lainnya"</h5>
</div>
<div class="box-body form-horizontal" id="wrapperDokumenPendukung">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Berharga</h3>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">No</th>
                    <th>Nomor Rekening</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php
//                    $file_pribados = array(
//                        '1'=>array('FILE' => @$SURAT_BERHARGAS->FILE_BUKTI)
//                    );
                    $i = 1; foreach (@$SURAT_BERHARGAS as $key => $row) :    
                ?>
                    <tr>
                        <td><?= @$i++; ?></td>
                        <td><?= ($row->FILE_BUKTI != '' ? $row->NOMOR_REKENING : '-'); ?></td>
                        <td>
                            <?php 
                                if($row->FILE_BUKTI != ''){
                            ?>
                            <!--<a href="uploads/data_suratberharga/<?= @$SURAT_BERHARGAS->NIK; ?>/<?php echo $key['FILE']; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted("uploads/data_suratberharga/".@$SURAT_BERHARGAS->NIK."/".@$key['FILE']); ?></a>-->
                            <a href="<?= $row->FILE_BUKTI; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted($row->FILE_BUKTI); ?></a>
                            <?php }else{ echo '-'; }?>
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
                    <h3 class="box-title">Kas / Setara Kas</h3>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">No</th>
                    <th>Nomor Rekening</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php  
//                    foreach ($JABATANS as $jbt) :
//                    $fileDt = explode('/', $jbt->FILE_SK);
                      $i = 1; foreach (@$HARTA_SETARA_KASS as $key => $row) :
                ?>
                    <tr>
                        <td><?= @$i++; ?></td>
                        <td><?= ($row->FILE_BUKTI != '' ? $row->NOMOR_REKENING : '-'); ?></td>
                        <!--<td><a href="<?= @$jbt->FILE_SK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted($jbt->FILE_SK); ?></a></td>-->
                        <td>
                            <?php 
                                if($row->FILE_BUKTI != ''){
                            ?>
                            <a href="<?= $row->FILE_BUKTI; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted($row->FILE_BUKTI); ?></a>
                            <?php }else{ echo '-'; }?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<!--    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
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
    </div>-->
    
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
$(document).ready(function() {
    // #wrapperHutang
    $("#wrapperDokumenPendukung .btn-detail").click(function() {
        var url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Detail Dokumen Pendukung', html, '', 'large');
        });
        return false;
    });
    $("#wrapperDokumenPendukung .btn-add").click(function() {
        var url = $(this).attr('href');
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
        var url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Edit Dokumen Pendukung', html, '', 'large');
        });
        return false;
    });
    $('#wrapperDokumenPendukung .btn-delete').click(function(e) {
        var url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Delete Dokumen Pendukung', html, '', 'large');
        });
        return false;
    });
});
</script>