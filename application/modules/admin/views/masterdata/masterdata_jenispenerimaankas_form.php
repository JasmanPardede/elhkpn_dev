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
                <label class="col-sm-3 control-label">Golongan Penerimaan Kas <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <select name="GOLONGAN" id="" class="form-control"  >
                        <option value="">-Pilih Golongan-</option>
                    	<?php foreach ($golongan as $glng): ?>
                    		<option value="<?= @$glng->ID_GOLONGAN_PENERIMAAN_KAS; ?>"><?= @$glng->NAMA_GOLONGAN; ?></option>
                    	<?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='NAMA' value='' required>
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
                <label class="col-sm-3 control-label">Golongan Penerimaan Kas <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <select name="GOLONGAN" id="" class="form-control" >
                    	<?php foreach ($golongan as $glng): ?>
                    		<option <?= ($glng->ID_GOLONGAN_PENERIMAAN_KAS == $item->GOLONGAN ? 'selected' : ''); ?> value="<?= @$glng->ID_GOLONGAN_PENERIMAAN_KAS; ?>"><?= @$glng->NAMA_GOLONGAN; ?></option>
                    	<?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <input class="form-control" type='text' size='40' name='NAMA' value='<?= @$item->NAMA; ?>' required>
                </div>
            </div>
        </div>       
        <div class="pull-right">
            <input type="hidden" name="ID_JENIS_PENERIMAAN_KAS" value="<?php echo $item->ID_JENIS_PENERIMAAN_KAS;?>">
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
    Benarkah Akan Mengaktifkan kembali data Jenis Penerimaan Kas dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Jenis Penerimaan Kas :</label>
                <label class="col-sm-8">
                    <?php echo $item->NAMA;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_JENIS_PENERIMAAN_KAS" value="<?php echo $item->ID_JENIS_PENERIMAAN_KAS;?>">
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
    Benarkah Akan Menghapus Jenis Penerimaan Kas dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Golongan : </label>
                <label class="col-sm-9">
                    <?php echo $item->GOLONGAN;?>
                </label>
            </div> 
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Nama : </label>
                <label class="col-sm-9">
                    <?php echo $item->NAMA;?>
                </label>
            </div> 
        </div>         
        <div class="pull-right">
            <input type="hidden" name="ID_JENIS_PENERIMAAN_KAS" value="<?php echo $item->ID_JENIS_PENERIMAAN_KAS;?>">
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
                <label class="col-sm-3" style="text-align:right !important;">Golongan Penerimaan Kas : </label>
                <label class="col-sm-9">
                    <?php echo $item->GOLONGAN;?>
                </label>
            </div> 
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Nama : </label>
                <label class="col-sm-9">
                    <?php echo $item->NAMA;?>
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

