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
    <form method="post" id="ajaxFormAdd" action="index.php/admin/jabatan/savejabatan" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Jabatan <span class="red-label">*</span>:</label>
                <div class="col-sm-9">
                    <input type='text' size='40' name='NAMA_JABATAN' id='NAMA_JABATAN' value='' required>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit" class="form-horizontal">
    <form method="post" id="ajaxFormEdit" action="index.php/admin/jabatan/savejabatan">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Jabatan <span class="red-label">*</span>:</label>
                <div class="col-sm-9">
                    <input type='text' size='40' name='NAMA_JABATAN' id='NAMA_JABATAN'  value='<?php echo $item->NAMA_JABATAN;?>' required>
                </div>
            </div>
        </div>        
        <div class="pull-right">
            <input type="hidden" name="ID_JABATAN" value="<?php echo $item->ID_JABATAN;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);            
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
    <form method="post" id="ajaxFormDelete" action="index.php/admin/jabatan/savejabatan">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Nama Jabatan : </label>
                <label class="col-sm-9">
                    <?php echo $item->NAMA_JABATAN;?>
                </label>
            </div> 
        </div>         
        <div class="pull-right">
            <input type="hidden" name="ID_JABATAN" value="<?php echo $item->ID_JABATAN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
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
                <label class="col-sm-3" style="text-align:right !important;">Nama Jabatan : </label>
                <label class="col-sm-9">
                    <?php echo $item->NAMA_JABATAN;?>
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

