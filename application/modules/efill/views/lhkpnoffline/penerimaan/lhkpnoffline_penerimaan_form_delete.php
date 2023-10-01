<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus Penugasan dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave; ?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">NIK :</label>
                <div class="col-sm-8">
                    <?php echo $item->NIK; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nama :</label>
                <div class="col-sm-8">
                    <?php echo $item->NAMA; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Jenis Dokumen :</label>
                <div class="col-sm-8">
                    <?php echo $item->JENIS_DOKUMEN; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Melalui :</label>
                <div class="col-sm-8">
                    <?php echo $item->MELALUI; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Tanggal Penerimaan :</label>
                <label class="col-sm-8">
                    <?php echo date('d/m/Y', strtotime($item->TANGGAL_PENERIMAAN)); ?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_PENERIMAAN" value="<?php echo $item->ID_PENERIMAAN; ?>">
            <input type="hidden" name="act" value="<?php echo $act; ?>">
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
    });
</script>