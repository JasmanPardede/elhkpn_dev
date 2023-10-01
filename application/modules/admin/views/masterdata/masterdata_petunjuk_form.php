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
 * @package Views/bantuan
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-12">
            <div class="form-group">
                <label class="col-sm-2 control-label">Nama Petunjuk <font color='red'>*</font>:</label>
                <div class="col-sm-10">
                    <input type='text' class="form-control" name='PETUNJUK_NAME' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Judul Petunjuk <font color='red'>*</font>:</label>
                <div class="col-sm-10">
                    <input type='text' class="form-control" name='PETUNJUK_TITLE' value='' required>
                </div>
            </div><div class="form-group">
                <label class="col-sm-2 control-label">Deskripsi <font color='red'>*</font>:</label>
                <div class="col-sm-10">
                    <!-- <input type='text' class="form-control" name='PETUNJUK_DESC' value='' required> -->
                    <textarea  id="txtPetunjuk" name="txtPetunjuk" rows="10" cols="80"> 
                </textarea>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary" onClick="CKupdate()"><i class="fa fa-save"></i> Simpan</button>
            <!--<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>-->
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
        
    </form>
</div>
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
         $('#ajaxFormAdd').val("");
         CKEDITOR.replace( 'txtPetunjuk' );
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
                <label class="col-sm-2 control-label">Nama Petunjuk<font color='red'>*</font>:</label>
                <div class="col-sm-10">
                    <input type='text' class="form-control" name='PETUNJUK_NAME' id='PETUNJUK_NAME' value='<?php echo $item->PETUNJUK_NAME;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Judul Petunjuk <font color='red'>*</font>:</label>
                <div class="col-sm-10">
                    <input type='text' class="form-control" name='PETUNJUK_TITLE' id='PETUNJUK_TITLE' value='<?php echo $item->PETUNJUK_TITLE;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Deskripsi <font color='red'>*</font>:</label>
                <div class="col-sm-10">
                    <textarea  id="txtPetunjuk" name="txtPetunjuk"rows="10" cols="60" <?php echo $item->PETUNJUK_DESC;?> </textarea>
                    
                 
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary" onClick="CKupdate()"><i class="fa fa-save"></i> Simpan</button>
            <!--<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>-->
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>    
    </form>
</div>
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
          $('#ajaxFormEdit').val("");
         CKEDITOR.replace( 'txtPetunjuk' );
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
    Benarkah Akan Menghapus Petunjuk dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Nama Petunjuk :</label>
                <label class="col-sm-8">
                    <?php echo $item->PETUNJUK_NAME;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Judul Petunjuk :</label>
                <label class="col-sm-8">
                    <?php echo $item->PETUNJUK_TITLE;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Deskripsi :</label>
                <label class="col-sm-8">
                    <?php echo $item->PETUNJUK_DESC;?>
<!--                     <textarea  id="txtPetunjuk" name="txtPetunjuk"rows="10" cols="45" <?php echo $item->PETUNJUK_DESC;?>
                     </textarea>-->
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
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
if($form=='kembalikan'){
?>
<div id="wrapperFormKembalikan" class="form-horizontal">
    Benarkah Akan Mengaktifkan kembali data Petunjuk dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Petunjuk :</label>
                <label class="col-sm-8">
                    <?php echo $item->PETUNJUK_NAME;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
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
if($form=='detail'){
?>
<div id="wrapperFormDetail" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2" style="text-align:right !important;">Nama Form:</label>
            <label class="col-sm-10">
                <?php echo $item->PETUNJUK_NAME;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-2" style="text-align:right !important;">Judul Form :</label>
            <label class="col-sm-10">
                <?php echo $item->PETUNJUK_TITLE;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-2" style="text-align:right !important;">Deskripi :</label>
            <label class="col-sm-10">
                <?php echo $item->PETUNJUK_DESC;?>
            </label>
        </div>
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
    </div>
</div>
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
<?php
}
?>

<script type="text/javascript">
    function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}
</script>