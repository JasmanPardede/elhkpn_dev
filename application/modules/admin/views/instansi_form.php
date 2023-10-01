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
 * @package Views/instansi
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="index.php/admin/instansi/saveinstansi" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">Inst Nama <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='INST_NAMA' id='INST_NAMA' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Inst Akronim <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='INST_AKRONIM' id='INST_AKRONIM' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Inst Level <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name='INST_LEVEL' id='INST_LEVEL' class='form-control' required>
                        <option value='1'>Pusat</option>
                        <option value='2'>Daerah</option>
                    </select>
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
    <form method="post" id="ajaxFormEdit" action="index.php/admin/instansi/saveinstansi">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">Inst Nama <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='INST_NAMA' id='INST_NAMA' value='<?php echo $item->INST_NAMA;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Inst Akronim <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='INST_AKRONIM' id='INST_AKRONIM' value='<?php echo $item->INST_AKRONIM;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Inst Level <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name='INST_LEVEL' id='INST_LEVEL' class='form-control' required>
                        <option value='1' <?php echo $item->INST_LEVEL == 1 ? 'selected' : '';?>>Pusat</option>
                        <option value='2' <?php echo $item->INST_LEVEL == 2 ? 'selected' : '';?>>Daerah</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="INST_SATKERKD" value="<?php echo $item->INST_SATKERKD;?>">
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
    Benarkah Akan Menghapus Instansi dibawah ini ?
    <form method="post" id="ajaxFormEdit" action="index.php/admin/instansi/saveinstansi">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Inst Nama :</label>
                <label class="col-sm-8">
                    <?php echo $item->INST_NAMA;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Inst Akronim :</label>
                <label class="col-sm-8">
                    <?php echo $item->INST_AKRONIM;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Inst Level :</label>
                <label class="col-sm-8">
                    <?php echo  $item->INST_LEVEL == '1' ? 'Pusat' : $item->INST_LEVEL == '2' ? 'Daerah' : ''; ?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="INST_SATKERKD" value="<?php echo $item->INST_SATKERKD;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
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
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Inst Nama :</label>
            <label class="col-sm-8">
                <?php echo $item->INST_NAMA;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Inst Akronim :</label>
            <label class="col-sm-8">
                <?php echo $item->INST_AKRONIM;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Inst Level :</label>
            <label class="col-sm-8">
                <?php echo  $item->INST_LEVEL == '1' ? 'Pusat' : $item->INST_LEVEL == '2' ? 'Daerah' : ''; ?>
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

