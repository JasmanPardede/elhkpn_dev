<?php
$file_list = isset($file_list) ? $file_list : array();
$file_list_ikhtisar = isset($file_list_ikhtisar) ? $file_list_ikhtisar : array();

$file_extension_accept = '["pdf", "jpg", "png","jpeg","doc","docx","tif","tiff"]';
$file_extension_accept2 = '.pdf,.jpg,.png,.jpeg,.doc,.docx,.tif,.tiff';

$e_IDLHKPN = encrypt_username($ID_LHKPN, 'e');
?>

<style type="text/css">
    .title-hutang
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }

    .inputFile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .inputFile + label {
        cursor: pointer;
        margin-left: 10px;
        /*font-size: 1.25em;*/
    }

    .inputFile:focus + label,
    .inputFile + label:hover {
        cursor: pointer;
        /*background-color: red;*/
    }

    .td-lhkpn-excel, .td-aksi{
        text-align: center;
    }

    .td-lhkpn-excel, .td-aksi, .td-nama-file{
        font-size: 12px;
        margin: 5px;
    }
</style>
<div class="box-header with-border portlet-header title-hutang">
    <h5 class="">"Daftar Dokumen yang diupload" <?php echo $secure_id_imp_xl_lhkpn; ?></h5>
</div>
<div class="box-body" id="wrapperHutang">
<!--     <button type="button" class="btn btn-sm btn-default btn-add btn-primary" href="index.php/efill/lhkpn/addhutang/<?php echo $id_lhkpn; ?>"><i class="fa fa-plus"></i> Tambah</button> -->
    <div class="box-tools">

    </div>



    <div class="box-body box-primary">
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dokumen SKM</h3>
                        <span class="label pull-right">
                            <input type="file" id="file1" name="file1" class="inputFile" data-allowed-file-extensions='<?php echo $file_extension_accept; ?>' accept='<?php echo $file_extension_accept2; ?>' data-show-preview="true" required multiple />
                            <label for="file1" class="btn btn-sm btn-primary">Pilih File</label>
                        </span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover" id="tableSKM">
                            <thead class="table-header">
                                <tr>
                                    <th width="230px">Nama File</th>
                                    <th width="180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($file_list_skm as $filename) {
                                    ?>
                                    <tr fnm="<?php echo $filename; ?>" dtp="skm" inpkhl="<?php echo encrypt_username($ID_LHKPN, 'e'); ?>" >
                                        <td><?php echo $filename; ?></td>
                                        <td width="10%">
                                            <a href="#" class="btn btn-sm" onclick="uploadInput.removeFile(this, 'tableSKM');"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                                            <a href="<?php echo base_url() . $path_skm_upload . encrypt_username($LHKPN->NIK, 'e') . "/" . $filename; ?>" target="_blank" class="btn btn-sm">
                                                <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dokumen SKB</h3>
                        <span class="label pull-right">
                            <input type="file" id="file2" name="file2" class="inputFile" data-allowed-file-extensions='<?php echo $file_extension_accept; ?>' accept='<?php echo $file_extension_accept2; ?>' data-show-preview="true" required multiple />
                            <label for="file2" class="btn btn-sm btn-primary">Pilih File</label>
                        </span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover" id="tableSKB">
                            <thead class="table-header">
                                <tr>
                                    <th width="230px">Nama File</th>
                                    <th width="180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($file_list_sk as $filename) {
                                    ?>
                                    <tr fnm="<?php echo $filename; ?>" dtp="skb" inpkhl="<?php echo encrypt_username($ID_LHKPN, 'e'); ?>">
                                        <td><?php echo $filename; ?></td>
                                        <td width="10%">
                                            <a href="#" class="btn btn-sm" onclick="uploadInput.removeFile(this, 'tableSKB');"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                                            <a href="<?php echo base_url() . $path_skb_upload . encrypt_username($LHKPN->NIK, 'e') . "/" . $filename; ?>" target="_blank" class="btn btn-sm">
                                                <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ikhtisar Harta</h3>
                        <span class="label pull-right">
                            <input type="file" id="file3" name="file3" class="inputFile" data-allowed-file-extensions='<?php echo $file_extension_accept; ?>' accept='<?php echo $file_extension_accept2; ?>' data-show-preview="true" required multiple />
                            <label for="file3" class="btn btn-sm btn-primary">Pilih File</label>
                        </span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover" id="tableIkhtisarHarta">
                            <thead class="table-header">
                                <tr>
                                    <th width="230px">Nama File</th>
                                    <th width="180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($file_list_ikhtisar as $filename) {
                                    ?>
                                    <tr fnm="<?php echo $filename; ?>" dtp="ikhtisar" inpkhl="<?php echo encrypt_username($ID_LHKPN, 'e'); ?>">
                                        <td><?php echo $filename; ?></td>
                                        <td width="10%">
                                            <a href="#" class="btn btn-sm" onclick="uploadInput.removeFile(this, 'tableIkhtisarHarta');"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                                            <a href="<?php echo base_url() . $path_ikhtisar_upload . encrypt_username($LHKPN->NIK, 'e') . "/" . $filename; ?>" target="_blank" class="btn btn-sm">
                                                <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dokumen Terupload</h3>
                        <span class="label pull-right">
                            <input type="hidden" id="random_id" value="<?php echo $rand_id; ?>" />
                            <input type="file" id="file4" name="file4" class="inputFile" data-allowed-file-extensions='<?php echo $file_extension_accept; ?>' accept='<?php echo $file_extension_accept2; ?>' data-show-preview="true" required multiple />
                            <label for="file4" class="btn btn-sm btn-primary">Pilih File</label>
                        </span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover" id="tableDokLainnya">
                            <thead class="table-header">
                                <tr>
                                    <th width="230px">Nama File</th>
                                    <th width="180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($file_list as $filename) {
                                    ?>
                                    <tr fnm="<?php echo $filename; ?>">
                                        <td><?php echo $filename; ?></td>
                                        <td width="10%">
                                            <a href="#" class="btn btn-sm" onclick="uploadInput.removeFile(this, 'tableDokLainnya');"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                                            <a href="<?php echo $download_location . $filename; ?>" target="_blank" class="btn btn-sm">
                                                <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div><!-- /.box-body -->

<div class="box-footer">
</div><!-- /.box-footer -->



