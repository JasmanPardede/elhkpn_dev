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
 * @package Views/Pendidikan
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">Kode Eselon <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='KODE_ESELON' id='KODE_ESELON' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Eselon <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='ESELON' id='ESELON' value='' required>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
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
                <label class="col-sm-4 control-label">Kode Eselon <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='KODE_ESELON' id='KODE_ESELON' value='<?php echo $item->KODE_ESELON;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Eselon <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='ESELON' id='ESELON' value='<?php echo $item->ESELON;?>' required>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_ESELON" value="<?php echo $item->ID_ESELON;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
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
if($form=='kembalikan'){
?>
<div id="wrapperFormKembalikan" class="form-horizontal">
    Benarkah Akan Mengaktifkan kembali data Eselon dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Eselon :</label>
                <label class="col-sm-8">
                    <?php echo $item->ESELON;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_ESELON" value="<?php echo $item->ID_ESELON;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Kembalikan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormKembalikan"), 'kembalikan', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus Eselon dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Kode Eselon :</label>
                <label class="col-sm-8">
                    <?php echo $item->KODE_ESELON;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Eselon :</label>
                <label class="col-sm-8">
                    <?php echo $item->ESELON;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_ESELON" value="<?php echo $item->ID_ESELON;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Hapus</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
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
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Kode Eselon :</label>
            <label class="col-sm-8">
                <?php echo $item->KODE_ESELON;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Eselon :</label>
            <label class="col-sm-8">
                <?php echo $item->ESELON;?>
            </label>
        </div>
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
    </div>
</div>
<?php
}
?>

