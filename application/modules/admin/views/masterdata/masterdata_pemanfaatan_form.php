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
                <label class="col-sm-3 control-label" style="width: 160px">Golongan Harta <font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <select name="GOLONGAN_HARTA" class="form-control" >
                        <option value="">-Pilih Golongan Harta-</option>
                    	<?php foreach ($golta as $goltas): ?>
                    		<option value="<?= @$goltas->ID_GOLONGAN_HARTA; ?>"><?= @$goltas->NAMA_GOLONGAN; ?></option>
                    	<?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" style="width: 160px">Pemanfaatan <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='PEMANFAATAN' value='' required style="width: 100%">
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-close"></i> Simpan</button>
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
                <label class="col-sm-3 control-label" style="width: 160px">Golongan Harta <font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <select name="GOLONGAN_HARTA" class="form-control" >
                    	<?php foreach ($golta as $goltas): ?>
                    		<option <?= ($item->GOLONGAN_HARTA == $goltas->ID_GOLONGAN_HARTA ? 'selected' : ''); ?> value="<?= @$goltas->ID_GOLONGAN_HARTA; ?>"><?= @$goltas->NAMA_GOLONGAN; ?></option>
                    	<?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" style="width: 160px">Nama <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='PEMANFAATAN' value='<?= @$item->PEMANFAATAN; ?>' required>
                </div>
            </div>
        </div>       
        <div class="pull-right">
            <input type="hidden" name="ID_PEMANFAATAN" value="<?php echo $item->ID_PEMANFAATAN;?>">
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
    Benarkah Akan Mengaktifkan kembali data Pemanfaatan dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Pemanfaatan :</label>
                <label class="col-sm-8">
                    <?php echo $item->PEMANFAATAN;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_PEMANFAATAN" value="<?php echo $item->ID_PEMANFAATAN;?>">
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
    Benarkah Akan Menghapus Jabatan dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Pemanfaatan : </label>
                <label class="col-sm-9">
                    <?php echo $item->PEMANFAATAN;?>
                </label>
            </div> 
        </div>         
        <div class="pull-right">
            <input type="hidden" name="ID_PEMANFAATAN" value="<?php echo $item->ID_PEMANFAATAN;?>">
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
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Pemanfaatan : </label>
                <label class="col-sm-9">
                    <?php echo $item->PEMANFAATAN;?>
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

