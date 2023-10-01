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
 * @package Views/activity
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <form method="post" id="ajaxFormAdd" action="index.php/admin/activity/saveactivity" enctype="multipart/form-data">
        <table>
            <tr><td>Id Activity</td><td>:</td><td><input type='text' size='40' name='ID_ACTIVITY' id='ID_ACTIVITY' value=''></td></tr>
			<tr><td>Username</td><td>:</td><td><input type='text' size='40' name='USERNAME' id='USERNAME' value=''></td></tr>
			<tr><td>Activity</td><td>:</td><td><input type='text' size='40' name='ACTIVITY' id='ACTIVITY' value=''></td></tr>
<!--             <tr>
                <td>Is Active</td>
                <td>:</td>
                <td>
                    <label><input type="radio" name="IS_ACTIVE" value="1"> Active</label>
                    <label><input type="radio" name="IS_ACTIVE" value="0" checked> inActive</label>
                </td>
            </tr> -->             
        </table>
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
<div id="wrapperFormEdit">
    <form method="post" id="ajaxFormEdit" action="index.php/admin/activity/saveactivity">
        <table>
            <tr><td>Id Activity</td><td>:</td><td><input type='text' size='40' name='ID_ACTIVITY' id='ID_ACTIVITY'  value='<?php echo $item->ID_ACTIVITY;?>'></td></tr>
			<tr><td>Username</td><td>:</td><td><input type='text' size='40' name='USERNAME' id='USERNAME'  value='<?php echo $item->USERNAME;?>'></td></tr>
			<tr><td>Activity</td><td>:</td><td><input type='text' size='40' name='ACTIVITY' id='ACTIVITY'  value='<?php echo $item->ACTIVITY;?>'></td></tr>
<!--             <tr>
                <td>Is Active</td>
                <td>:</td>
                <td>
                    <label><input type="radio" name="IS_ACTIVE" value="1" <?php echo $item->IS_ACTIVE==1?'checked':'';?>> Active</label>
                    <label><input type="radio" name="IS_ACTIVE" value="0" <?php echo $item->IS_ACTIVE==0?'checked':'';?>> inActive</label>
                </td>
            </tr>  -->             
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_ACTIVITY" value="<?php echo $item->ID_ACTIVITY;?>">
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
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Activity dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/admin/activity/saveactivity">
        <table>
            <tr><td>Id Activity</td><td>:</td><td><?php echo $item->ID_ACTIVITY;?></td></tr>
			<tr><td>Username</td><td>:</td><td><?php echo $item->USERNAME;?></td></tr>
			<tr><td>Activity</td><td>:</td><td><?php echo $item->ACTIVITY;?></td></tr>
<!--             <tr>
                <td>Is Active</td>
                <td>:</td>
                <td>
                <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
                </td>
            </tr>   -->          
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_ACTIVITY" value="<?php echo $item->ID_ACTIVITY;?>">
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
<div id="wrapperFormDetail">
    <table>
        <tr><td>Id Activity</td><td>:</td><td><?php echo $item->ID_ACTIVITY;?></td></tr>
			<tr><td>Username</td><td>:</td><td><?php echo $item->USERNAME;?></td></tr>
			<tr><td>Activity</td><td>:</td><td><?php echo $item->ACTIVITY;?></td></tr>
<!--         <tr>
            <td>Is Active</td>
            <td>:</td>
            <td>
            <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
            </td>
        </tr> -->        
    </table>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>

