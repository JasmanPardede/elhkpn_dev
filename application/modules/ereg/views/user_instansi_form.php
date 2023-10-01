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
 * @package Views/user
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ereg/user_instansi/saveuser" enctype="multipart/form-data">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Username <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('username'); ?>
                <input type='text' class="form-control" size='40' name='USERNAME' onblur="cek_user(this.value);" id='USERNAME' value='' placeholder="Username" required <?= FormHelpPlaceholderToolTip('username') ?>>
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>Username <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Nama <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('nama'); ?>
                <input type='text' class="form-control" name='NAMA' id='NAMA' value='' required <?= FormHelpPlaceholderToolTip('nama') ?> placeholder="Nama">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Email <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('email'); ?>
                <input class="form-control" type='email' size='40' name='EMAIL' onblur="cek_email(this.value)" id='EMAIL' placeholder="johnsmith@email.com" value='' required <?= FormHelpPlaceholderToolTip('email') ?> >
                <span class="help-block">
                    <font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font>
                    <font id='email_salah' style='display:none;' color='red'>Format Email Salah</font>
                </span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-email">
                <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            <!-- <div class="col-sm-1"></div> -->
        </div>
        <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Handphone <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('handphone'); ?>
                    <input type='text' class="form-control " onkeypress="validate(event)" name='HANDPHONE' id='HANDPHONE' value='' required <?= FormHelpPlaceholderToolTip('handphone') ?>>
                </div>
                <div class="col-sm-1"></div>
            </div>
<!--         <div class="form-group">
            <label class="col-sm-3 control-label">Password <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='password' size='40' name='Password' id='Password' placeholder="Password">
            </div>
        </div> -->
        <!-- <div class="form-group">
            <label class="col-sm-3 control-label">Status :</label>
            <div class="col-sm-5" style="padding-left: 30px;">
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input name="IS_ACTIVE" value="1" checked="checked" type="radio"> Active
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input name="IS_ACTIVE" value="0" type="radio"> InActive
                        </label>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', window.location.href.split('#')[1]);
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/ereg/user_instansi/saveuser">
        <input type="hidden" name="HIDDEN_USERNAME" id="HIDDEN_USERNAME" value="<?php echo $item->USERNAME; ?>">
        <div class="form-group">
            <label class="col-sm-3 control-label">Username <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' size='40' name='USERNAME' onblur="cek_user_edit(this.value, $('#HIDDEN_USERNAME').val());" id='USERNAME' placeholder="Username" value="<?php echo $item->USERNAME; ?>">
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>Username <span id="check_uname_edit" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' size='40' name='NAMA' id='NAMA' placeholder="Nama" value="<?php echo $item->NAMA; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='email' size='40' name='EMAIL' onblur="cek_email(this.value, <?=$data_id?>)" id='EMAIL' placeholder="johnsmith@email.com" VALUE="<?php echo $item->EMAIL; ?>">
                <span class="help-block"><font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Handphone <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' size='40' name='HANDPHONE' id='HANDPHONE' placeholder="Handphone" value="<?php echo $item->HANDPHONE; ?>">
            </div>
        </div>
        <!-- <div class="form-group">
            <label class="col-sm-3 control-label">IS ACTIVE <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <label><input type="radio" name="IS_ACTIVE" value="1" <?php echo $item->IS_ACTIVE==1?'checked':'';?>> Active</label>
                <label><input type="radio" name="IS_ACTIVE" value="0" <?php echo $item->IS_ACTIVE==0?'checked':'';?>> inActive</label>
            </div>
        </div> -->

        <div class="pull-right">
            <input type="hidden" name="ID_USER" value="<?php echo $item->ID_USER;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
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
    Benarkah Akan Menghapus User dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/ereg/user_instansi/saveuser">
        <table>
            <!-- <tr><td>Id User</td><td>:</td><td><?php echo $item->ID_USER;?></td></tr> -->
			<tr><td>Username</td><td>:</td><td><?php echo $item->USERNAME;?></td></tr>
            <tr><td>Nama</td><td>:</td><td><?php echo $item->NAMA;?></td></tr>
            <tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL;?></td></tr>
			<tr><td>Handphone</td><td>:</td><td><?php echo $item->HANDPHONE;?></td></tr>
			<!-- <tr><td>Nip</td><td>:</td><td><?php echo $item->NIP;?></td></tr> -->
            <!-- <tr>
                <td>Is Active</td>
                <td>:</td>
                <td>
                <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
                </td>
            </tr> -->
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_USER" value="<?php echo $item->ID_USER;?>">
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
if($form=='reset_password'){
?>
    <form method="post" id="ajaxFormResetPassword" action="index.php/ereg/user_instansi/do_reset_password">
        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
        <div class="col-lg-12 text-center">
            <p>Apakah anda yakin untuk mereset password?</p>
            <button type="submit" class="btn btn-sm btn-primary">Ya</button>
            <input type="button" class="btn btn-sm btn-default" value="Tidak" onclick="CloseModalBox();">
        </div>
    </form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormResetPassword"), 'reset_password', location.href.split('#')[1]);
    });
</script>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail">
    <div class="row">
        
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <img src="<?php echo base_url().'uploads/users/'.$item->PHOTO; ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
    </div>

    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
        <h3>Detail User</h3>

        <table>
			<tr><td width="100px"><strong>Username</strong></td><td width="10px">:</td><td><?php echo $item->USERNAME;?></td></tr>
			<tr><td><strong>Nama</strong></td><td>:</td><td><?php echo $item->NAMA;?></td></tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>:</td>
                <td>
                <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
        <h3>Contact</h3>
        <table>
                <tr><td><a href="mailto:<?php echo $item->EMAIL;?>"><i class="fa fa-envelope"></i> <?php echo $item->EMAIL;?></a></td></tr>
                <tr><td><a href="tel:<?php echo $item->HANDPHONE;?>"><i class="fa fa-phone"></i> <?php echo $item->HANDPHONE;?></a></td></tr>
        </table>
    </div>    

    </div>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
    });

    $(function() {
        $('.over').popover();
               $('.over')
           .mouseenter(function(e){
              $(this).popover('show'); 
           })
           .mouseleave(function(e){
              $(this).popover('hide'); 
           }); 
    })
</script>
