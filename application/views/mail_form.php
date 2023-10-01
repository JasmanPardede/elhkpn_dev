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
 * @package Views/mail
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <form method="post" id="ajaxFormAdd" action="index.php/mail/savemail" enctype="multipart/form-data">
        <table>
            <tr><td>Id Mail</td><td>:</td><td><input type='text' size='40' name='ID_MAIL' id='ID_MAIL' value=''></td></tr>
			<tr><td>Nomor</td><td>:</td><td><input type='text' size='40' name='NOMOR' id='NOMOR' value=''></td></tr>
			<tr><td>Tanggal</td><td>:</td><td><input type='text' size='40' name='TANGGAL' id='TANGGAL' value=''></td></tr>
			<tr><td>Dari</td><td>:</td><td><input type='text' size='40' name='DARI' id='DARI' value=''></td></tr>
			<tr><td>Kepada</td><td>:</td><td><input type='text' size='40' name='KEPADA' id='KEPADA' value=''></td></tr>
			<tr><td>Perihal</td><td>:</td><td><input type='text' size='40' name='PERIHAL' id='PERIHAL' value=''></td></tr>
			<tr><td>Attachment</td><td>:</td><td><input type='text' size='40' name='ATTACHMENT' id='ATTACHMENT' value=''></td></tr>
			<tr><td>Need Response</td><td>:</td><td><input type='text' size='40' name='NEED_RESPONSE' id='NEED_RESPONSE' value=''></td></tr>
			<tr><td>Response Status</td><td>:</td><td><input type='text' size='40' name='RESPONSE_STATUS' id='RESPONSE_STATUS' value=''></td></tr>
			<tr><td>Mail Status</td><td>:</td><td><input type='text' size='40' name='MAIL_STATUS' id='MAIL_STATUS' value=''></td></tr>           
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
    <form method="post" id="ajaxFormEdit" action="index.php/mail/savemail">
        <table>
            <tr><td>Id Mail</td><td>:</td><td><input type='text' size='40' name='ID_MAIL' id='ID_MAIL'  value='<?php echo $item->ID_MAIL;?>'></td></tr>
			<tr><td>Nomor</td><td>:</td><td><input type='text' size='40' name='NOMOR' id='NOMOR'  value='<?php echo $item->NOMOR;?>'></td></tr>
			<tr><td>Tanggal</td><td>:</td><td><input type='text' size='40' name='TANGGAL' id='TANGGAL'  value='<?php echo $item->TANGGAL;?>'></td></tr>
			<tr><td>Dari</td><td>:</td><td><input type='text' size='40' name='DARI' id='DARI'  value='<?php echo $item->DARI;?>'></td></tr>
			<tr><td>Kepada</td><td>:</td><td><input type='text' size='40' name='KEPADA' id='KEPADA'  value='<?php echo $item->KEPADA;?>'></td></tr>
			<tr><td>Perihal</td><td>:</td><td><input type='text' size='40' name='PERIHAL' id='PERIHAL'  value='<?php echo $item->PERIHAL;?>'></td></tr>
			<tr><td>Attachment</td><td>:</td><td><input type='text' size='40' name='ATTACHMENT' id='ATTACHMENT'  value='<?php echo $item->ATTACHMENT;?>'></td></tr>
			<tr><td>Need Response</td><td>:</td><td><input type='text' size='40' name='NEED_RESPONSE' id='NEED_RESPONSE'  value='<?php echo $item->NEED_RESPONSE;?>'></td></tr>
			<tr><td>Response Status</td><td>:</td><td><input type='text' size='40' name='RESPONSE_STATUS' id='RESPONSE_STATUS'  value='<?php echo $item->RESPONSE_STATUS;?>'></td></tr>
			<tr><td>Mail Status</td><td>:</td><td><input type='text' size='40' name='MAIL_STATUS' id='MAIL_STATUS'  value='<?php echo $item->MAIL_STATUS;?>'></td></tr>             
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_MAIL" value="<?php echo $item->ID_MAIL;?>">
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
    Benarkah Akan Menghapus Mail dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/mail/savemail">
        <table>
            <tr><td>Id Mail</td><td>:</td><td><?php echo $item->ID_MAIL;?></td></tr>
			<tr><td>Nomor</td><td>:</td><td><?php echo $item->NOMOR;?></td></tr>
			<tr><td>Tanggal</td><td>:</td><td><?php echo $item->TANGGAL;?></td></tr>
			<tr><td>Dari</td><td>:</td><td><?php echo $item->DARI;?></td></tr>
			<tr><td>Kepada</td><td>:</td><td><?php echo $item->KEPADA;?></td></tr>
			<tr><td>Perihal</td><td>:</td><td><?php echo $item->PERIHAL;?></td></tr>
			<tr><td>Attachment</td><td>:</td><td><?php echo $item->ATTACHMENT;?></td></tr>
			<tr><td>Need Response</td><td>:</td><td><?php echo $item->NEED_RESPONSE;?></td></tr>
			<tr><td>Response Status</td><td>:</td><td><?php echo $item->RESPONSE_STATUS;?></td></tr>
			<tr><td>Mail Status</td><td>:</td><td><?php echo $item->MAIL_STATUS;?></td></tr>           
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_MAIL" value="<?php echo $item->ID_MAIL;?>">
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
        <tr><td>Id Mail</td><td>:</td><td><?php echo $item->ID_MAIL;?></td></tr>
		<tr><td>Nomor</td><td>:</td><td><?php echo $item->NOMOR;?></td></tr>
		<tr><td>Tanggal</td><td>:</td><td><?php echo $item->TANGGAL;?></td></tr>
		<tr><td>Dari</td><td>:</td><td><?php echo $item->DARI;?></td></tr>
		<tr><td>Kepada</td><td>:</td><td><?php echo $item->KEPADA;?></td></tr>
		<tr><td>Perihal</td><td>:</td><td><?php echo $item->PERIHAL;?></td></tr>
		<tr><td>Attachment</td><td>:</td><td><?php echo $item->ATTACHMENT;?></td></tr>
		<tr><td>Need Response</td><td>:</td><td><?php echo $item->NEED_RESPONSE;?></td></tr>
		<tr><td>Response Status</td><td>:</td><td><?php echo $item->RESPONSE_STATUS;?></td></tr>
		<tr><td>Mail Status</td><td>:</td><td><?php echo $item->MAIL_STATUS;?></td></tr>      
    </table>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>

