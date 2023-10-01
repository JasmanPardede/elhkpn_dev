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
 * @package Views/lhkpnhutang
 */
?>
<?php
if ($form == 'add') {
    ?>
    <div id="wrapperFormAdd">
        <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/save_dokpendukung" enctype="multipart/form-data">
            <div class="box-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Dokumen <span class="red-label">*</span>:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="NAMA_DOKUMEN" placeholder="Nama Dokumen" type="text" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">File <span class="red-label">*</span>:</label>
                        <div class="col-sm-9">
                            <input type="file" required name="FILE_PENDUKUNG">
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input name="ID_DOKUMEN_PENDUKUNG" type="hidden">
                <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?= @$id_lhkpn; ?>">
                <input type="hidden" name="act" value="doinsert">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
            ID = $('#id_lhkpn').val();
            ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/17/' + ID, block: '#block', container: $('#dokpendukung').find('.contentTab')});
        });
    </script>
    <?php
}
?>
<?php
if ($form == 'edit') {
    ?>
    <div id="wrapperFormEdit">
        <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/efill/lhkpn/save_dokpendukung">
            <div class="box-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Dokumen <span class="red-label">*</span>:</label>
                        <div class="col-sm-9">
                            <input  required value='<?php echo $item->NAMA_DOKUMEN; ?>' class="form-control" name="NAMA_DOKUMEN" placeholder="Nama Dokumen" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">File :</label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><img src="./uploads/lhkpn/<?= $nik ?>/<?php echo $item->LOKASI_DOKUMEN; ?>" width="50%"/></p></br>
                            <input type="file" name="FILE_PENDUKUNG">
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input value='<?php echo $item->ID_LHKPN; ?>' name="ID_LHKPN" id="id_lhkpn" type="hidden">
                <input value='<?php echo $item->ID_DOKUMEN_PENDUKUNG; ?>' name="ID_DOKUMEN_PENDUKUNG" type="hidden">
                <input type="hidden" name="OLD_FILE" value="<?php echo $item->LOKASI_DOKUMEN; ?>"/>
                <input type="hidden" name="act" value="doupdate">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            // ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);
            ID = $('#id_lhkpn').val();
            ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/17/' + ID, block: '#block', container: $('#dokpendukung').find('.contentTab')});
        });
    </script>
    <?php
}
?>
<?php
if ($form == 'delete') {
    ?>
    <div id="wrapperFormDelete">
        Benarkah Akan Menghapus hutang dibawah ini ?
        <form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/save_dokpendukung">
            <div class="box-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Dokumen : </label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><?php echo $item->NAMA_DOKUMEN; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><img src="./uploads/lhkpn/<?= $nik ?>/<?php echo $item->LOKASI_DOKUMEN; ?>" width="50%"/></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input type="hidden" name="ID_DOKUMEN_PENDUKUNG" value="<?php echo $item->ID_DOKUMEN_PENDUKUNG; ?>">
                <input type="hidden" name="OLD_FILE" value="<?php echo $item->LOKASI_DOKUMEN; ?>">
                <input type="hidden" name="act" value="dodelete">
                <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
                <input value='<?php echo $item->ID_LHKPN; ?>' name="ID_LHKPN" id="id_lhkpn" type="hidden">
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            ID = $('#id_lhkpn').val();
            ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/17/' + ID, block: '#block', container: $('#dokpendukung').find('.contentTab')});
        });
    </script>
    <?php
}
?>

<?php
if ($form == 'detail') {
    ?>
    <div id="wrapperFormDetail">
        <div class="box-body form-horizontal">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama Dokumen : </label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $item->NAMA_DOKUMEN; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><img src="./uploads/lhkpn/<?= $nik ?>/<?php echo $item->LOKASI_DOKUMEN; ?>" width="50%"/></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </div>
    <?php
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('.datepicker').datepicker();
    })
</script>