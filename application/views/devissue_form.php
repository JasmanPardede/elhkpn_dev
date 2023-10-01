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
 * @package Views/devissue
*/
?>
<?php
$this->load->helper('form');
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <?php
    $attributes = array('id' => 'ajaxFormAdd');
    echo form_open_multipart('devissue/savedevissue', $attributes);
    ?>
    <!-- <form method="post" id="ajaxFormAdd" action="index.php/devissue/savedevissue" enctype="multipart/form-data">        -->
        <table>
            <tr><td>Menu</td><td>:</td><td><input type='hidden' size='40' name='ID_MENU' id='ID_MENU' value=''><span id="MENUNAME"></span></td></tr>
            <tr><td>Tags</td><td>:</td><td><input type="text" name="TAGS" id="TAGS" value="">
                <select id="SEL_TAGS">
                    <option value="">-- Pilih Tag --</option>
                    <option>DB</option>
                    <option>LIST</option>
                    <option>SEARCH</option>
                    <option>PAGING</option>
                    <option>ADD</option>
                    <option>DETAIL</option>
                    <option>EDIT</option>
                    <option>DELETE</option>
                    <option>SERVER</option>
                    <option>SYSTEM</option>
                </select>
            </td></tr>
            <tr><td>Title</td><td>:</td><td><input type='text' size='40' name='TITLE' id='TITLE' value='' required></td></tr>
            <tr><td>Description</td><td>:</td><td>
                <textarea name="DESCRIPTION" style="width:350px; height: 200px;"></textarea>
            </td></tr>
            <tr><td>Photo</td><td>:</td><td><input type='hidden' size='40' name='PHOTO' id='PHOTO' value=''><input type="file" name="file" id="file"></td></tr>
            <tr><td>Resolution</td><td>:</td><td>
                <textarea name="RESOLUTION" style="width:350px; height: 200px;"></textarea>
            </td></tr>
            <tr>
                <td>Is Done</td>
                <td>:</td>
                <td>
                    <label><input type="radio" name="IS_DONE" value="1"> Done</label>
                    <label><input type="radio" name="IS_DONE" value="0" checked> Progress</label>
                </td>
            </tr> 
            <tr>
            	<td>Done Time</td><td>:</td><td>
            		<input type="text" class="form-control datetimepicker" id="datetime_example" placeholder="Date and Time" name='DONE_TIME' id='DONE_TIME' value='<?php echo date('d-m-Y H:i');?>'>
            	</td>
            </tr>                          
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
    	LoadTimePickerScript(AllTimePickers);
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);

        $('#SEL_TAGS').click(function(){
            if($(this).val()!=''){
                val = $('#TAGS').val()+' '+$(this).val();
                $('#TAGS').val(val);
            }
        });

	});
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <?php
    $attributes = array('id' => 'ajaxFormEdit');
    echo form_open_multipart('devissue/savedevissue', $attributes);
    ?>
    <!-- <form method="post" id="ajaxFormEdit" action="index.php/devissue/savedevissue"> -->
        <table>
            <tr><td>Menu</td><td>:</td><td><input type='hidden' size='40' name='ID_MENU' id='ID_MENU' value='<?php echo $item->ID_MENU;?>'><span id="MENUNAME"></span></td></tr>
            <tr><td>Tags</td><td>:</td><td><input type="text" name="TAGS" id="TAGS" value="<?php echo $item->TAGS;?>">
                <select id="SEL_TAGS">
                    <option value="">-- Pilih Tag --</option>
                    <option>DB</option>
                    <option>LIST</option>
                    <option>SEARCH</option>
                    <option>PAGING</option>
                    <option>ADD</option>
                    <option>DETAIL</option>
                    <option>EDIT</option>
                    <option>DELETE</option>
                    <option>SERVER</option>
                    <option>SYSTEM</option>
                </select>
            </td></tr>
            <tr><td>Title</td><td>:</td><td><input type='text' size='40' name='TITLE' id='TITLE' value='<?php echo $item->TITLE;?>' required></td></tr>
            <tr><td>Description</td><td>:</td><td>
                <textarea name="DESCRIPTION" style="width:350px; height: 200px;"><?php echo $item->DESCRIPTION;?></textarea>
            </td></tr>
			<tr><td>Photo</td><td>:</td><td><input type='hidden' size='40' name='PHOTO' id='PHOTO' value='<?php echo $item->PHOTO;?>'><div class="avatar">
                                    <img src="<?php echo base_url();?>upload/<?php echo $item->PHOTO; ?>" class="img-rounded" alt="avatar">
                                </div><input type="file" name="file" id="file"></td></tr>
            <tr><td>Resolution</td><td>:</td><td>
                <textarea name="RESOLUTION" style="width:350px; height: 200px;"><?php echo $item->RESOLUTION;?></textarea>
            </td></tr>                                
            <tr>
                <td>Is Done</td>
                <td>:</td>
                <td>
	                <label><input type="radio" name="IS_DONE" value="1" <?php echo $item->IS_DONE==1?'checked':'';?>> Done</label>
	                <label><input type="radio" name="IS_DONE" value="0" <?php echo $item->IS_DONE==0?'checked':'';?>> Progress</label>
                </td>
            </tr> 
			<tr>
            	<td>Done Time</td><td>:</td><td>
            		<input type="text" class="form-control datetimepicker" id="datetime_example" placeholder="Date and Time" name='DONE_TIME' id='DONE_TIME' value='<?php echo date('d-m-Y H:i', strtotime($item->DONE_TIME));?>'>
                </td>
            </tr>                        
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_ISSUE" value="<?php echo $item->ID_ISSUE;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        LoadTimePickerScript(AllTimePickers);
        ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);

        $('#SEL_TAGS').click(function(){
            if($(this).val()!=''){
                val = $('#TAGS').val()+' '+$(this).val();
                $('#TAGS').val(val);
            }
        });
                
	});
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Issue dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/devissue/savedevissue">
        <table>
            <tr><td>Id Issue</td><td>:</td><td><?php echo $item->ID_ISSUE;?></td></tr>
            <tr><td>Menu</td><td>:</td><td><?php echo $item->ID_MENU;?></td></tr>
            <tr><td>Tags</td><td>:</td><td><?php echo $item->TAGS;?></td></tr>
            <tr><td>Title</td><td>:</td><td><?php echo $item->TITLE;?></td></tr>
            <tr><td>Description</td><td>:</td><td><?php echo $item->DESCRIPTION;?></td></tr>
            <tr><td>Photo</td><td>:</td><td><div class="avatar">
                                    <img src="<?php echo base_url();?>upload/<?php echo $item->PHOTO; ?>" class="img-rounded" alt="avatar">
                                </div></td></tr>
            <tr><td>Resolution</td><td>:</td><td>
                <?php echo $item->RESOLUTION;?>
            </td></tr>                                 
            <tr>
                <td>Is Done</td>
                <td>:</td>
                <td>
                <?php echo $item->IS_DONE == '1' ? 'Done' : 'Progress'; ?>
                </td>
            </tr>
			<tr>
            	<td>Done Time</td><td>:</td><td>
            		<?php echo date('d-m-Y H:i', strtotime($item->DONE_TIME));?>
            	</td>
            </tr>
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_ISSUE" value="<?php echo $item->ID_ISSUE;?>">
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
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <div class="avatar">
            <img src="<?php echo base_url();?>upload/<?php echo $item->PHOTO; ?>" class="img-rounded" alt="avatar">
        </div>
    </div>
    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
        <table>
            <tr><td>Id Issue</td><td> : </td><td><?php echo $item->ID_ISSUE;?></td></tr>
            <tr><td>Menu</td><td> : </td><td><?php echo $item->ID_MENU;?></td></tr>
            <tr><td>Tags</td><td>:</td><td><?php echo $item->TAGS;?></td></tr>
            <tr><td>Title</td><td> : </td><td><?php echo $item->TITLE;?></td></tr>
            <tr><td>Description</td><td> : </td><td><?php echo $item->DESCRIPTION;?></td></tr>
            <tr><td>Photo</td><td>:</td><td></td></tr>
            <tr><td>Resolution</td><td>:</td><td>
                <?php echo $item->RESOLUTION;?>
            </td></tr>              
            <tr>
                <td>Is Done</td>
                <td>:</td>
                <td>
                <?php echo $item->IS_DONE == '1' ? 'Done' : 'Progress'; ?>
                </td>
            </tr> 
			<tr>
            	<td>Done Time</td><td>:</td><td>
            		<?php echo date('d-m-Y H:i', strtotime($item->DONE_TIME));?>
            	</td>
            </tr>
        </table>
        <div class="pull-right">
            <input type="reset" class="btn btn-sm btn-default" value="Keluar" onclick="CloseModalBox();">
        </div>
    </div>   
</div>
<?php
}
?>

