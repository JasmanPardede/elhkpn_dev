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
 * @package Views/jabatan
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label">Kode Bidang <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='BDG_KODE' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Bidang <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='BDG_NAMA' value='' required>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit" class="form-horizontal">
    <form method="post" id="ajaxFormEdit" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label">Kode Bidang <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='BDG_KODE' value='<?= @$item->BDG_KODE; ?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Bidang <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='BDG_NAMA' value='<?= @$item->BDG_NAMA; ?>' required>
                </div>
            </div>
        </div>       
        <div class="pull-right">
            <input type="hidden" name="BDG_ID" value="<?php echo $item->BDG_ID;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', '', sumbitAjaxFormCari);            
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus Jabatan dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Kode Bidang : </label>
                <label class="col-sm-9">
                    <?php echo $item->BDG_KODE;?>
                </label>
            </div> 
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Nama Bidang : </label>
                <label class="col-sm-9">
                    <?php echo $item->BDG_NAMA;?>
                </label>
            </div> 
        </div>         
        <div class="pull-right">
            <input type="hidden" name="BDG_ID" value="<?php echo $item->BDG_ID;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail" class="form-horizontal">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Kode Bidang : </label>
                <label class="col-sm-9">
                    <?php echo $item->BDG_KODE;?>
                </label>
            </div> 
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Nama Bidang : </label>
                <label class="col-sm-9">
                    <?php echo $item->BDG_NAMA;?>
                </label>
            </div>             
        </div> 
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>

