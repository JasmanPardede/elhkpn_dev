<style type="text/css">
    .title-alat
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
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Upload Surat Kuasa Mengumumkan dan Surat Kuasa"</h5>
</div>
<form class="form-horizontal" method="post" action="index.php/ever/verification/save/upload" id="ajaxFormVeritem">
<div class="box-body" id="wrapperSuratKuasa">
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
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $ID_LHKPN; ?>/skm" >Upload</button>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <table class="table table-bordered table-hover table-striped" id="tableSKM">
            <thead class="table-header">
                <tr>
                    <th>Upload Dokumen (pdf/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php
                            $ex_file = explode('/', $LHKPN->FILE_BUKTI_SKM);
                            echo $ex_file[3]; 
                        ?>
                    </td>
                    <td>
                        <?php
                            $img = null;
                            if ($LHKPN->FILE_BUKTI_SKM) {
                                if (file_exists($LHKPN->FILE_BUKTI_SKM)) {
                                    $img = "  <a target='_blank' href='" . base_url() . '' . $LHKPN->FILE_BUKTI_SKM . "'><i class='fa fa-file'></i>". ' ' . ng::filesize_formatted($LHKPN->FILE_BUKTI_SKM)."</a>";
                                }
                            }
                            echo $img;
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Kuasa</h3>
                </div>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $ID_LHKPN; ?>/sk" >Upload</button>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th>Upload Dokumen (pdf/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php
                            $ex_file = explode('/', $LHKPN->FILE_BUKTI_SK);
                            echo $ex_file[3]; 
                        ?>
                    </td>
                    <td>
                        <?php
                            $img = null;
                            if ($LHKPN->FILE_BUKTI_SK) {
                                if (file_exists($LHKPN->FILE_BUKTI_SK)) {
                                    $img = "  <a target='_blank' href='" . base_url() . '' . $LHKPN->FILE_BUKTI_SK . "'><i class='fa fa-file'></i>". ' ' . ng::filesize_formatted($LHKPN->FILE_BUKTI_SK)."</a>";
                                }
                            }
                            echo $img;
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div><!-- /.box-body -->
</form>
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
$("#wrapperSuratKuasa .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Upload Dokumen Pendukung Surat Berharga', html, '', 'standart');
            });
            return false;
        });
</script>