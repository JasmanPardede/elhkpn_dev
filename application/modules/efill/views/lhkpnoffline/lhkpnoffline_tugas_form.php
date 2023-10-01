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
 * @package Views/Agama
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">PN <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                	list pn jika belum terdaftar maka ditambahkan
                    <input type='text' class="form-control" name='ID_PN' id='ID_PN' value='' required>
                </div>
            </div>            
            <div class="form-group">
                <label class="col-sm-4 control-label">LHKPN <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                	list Lhkpn yang belum diverifikasi
                    <input type='text' class="form-control" name='ID_LHKPN' id='ID_LHKPN' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Assign to <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='USERNAME' id='USERNAME' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tanggal Penugasan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='TANGGAL_PENUGASAN' id='TANGGAL_PENUGASAN' value='<?php echo date('d-m-Y');?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Due Date <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='DUE_DATE' id='DUE_DATE'  value='<?php echo date('d-m-Y', strtotime('+7 days'));?>'  required>
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
    <form method="post" id="ajaxFormEdit" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">PN <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                	list pn jika belum terdaftar maka ditambahkan
                    <input type='text' class="form-control" name='ID_PN' id='ID_PN' value='' required>
                </div>
            </div>         
            <div class="form-group">
                <label class="col-sm-4 control-label">LHKPN <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='ID_LHKPN' id='ID_LHKPN' value='<?php echo $item->ID_LHKPN;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Assign to <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='USERNAME' id='USERNAME' value='<?php echo $item->USERNAME;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tanggal Penugasan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='TANGGAL_PENUGASAN' id='TANGGAL_PENUGASAN' value='<?php echo $item->TANGGAL_PENUGASAN;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Due Date <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='DUE_DATE' id='DUE_DATE'  value='<?php echo $item->DUE_DATE;?>'  required>
                </div>
            </div>
        </div>      
        <div class="pull-right">
            <input type="hidden" name="ID_TUGAS" value="<?php echo $item->ID_TUGAS;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
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
    Benarkah Akan Menghapus Penugasan dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">PN :</label>
                <label class="col-sm-8">
                    <?php echo $item->ID_PN;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">LHKPN :</label>
                <label class="col-sm-8">
                    <?php echo $item->LHKPN;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Assign to :</label>
                <label class="col-sm-8">
                    <?php echo $item->USERNAME;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Tanggal Penugasan :</label>
                <label class="col-sm-8">
                    <?php echo $item->TANGGAL_PENUGASAN;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Due Date :</label>
                <label class="col-sm-8">
                    <?php echo $item->DUE_DATE;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_TUGAS" value="<?php echo $item->ID_TUGAS;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
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
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">PN :</label>
                <label class="col-sm-8">
                    <?php echo $item->ID_PN;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">LHKPN :</label>
                <label class="col-sm-8">
                    <?php echo $item->LHKPN;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Assign to :</label>
                <label class="col-sm-8">
                    <?php echo $item->USERNAME;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Tanggal Penugasan :</label>
                <label class="col-sm-8">
                    <?php echo $item->TANGGAL_PENUGASAN;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Due Date :</label>
                <label class="col-sm-8">
                    <?php echo $item->DUE_DATE;?>
                </label>
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

<?php
if($form=='proses'){
?>
Proses Entry LHKPN<br>
<div id="wrapperFormProses" class="form-horizontal">
    <form method="post" id="ajaxFormProses" action="<?php echo $urlSave;?>">
        <div class="box-body">
            Item Entry dan methodenya :<br>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_TUGAS" value="<?php echo $item->ID_TUGAS;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormProses"), 'proses', location.href.split('#')[1]);
    });
</script>
<?php
}
?>