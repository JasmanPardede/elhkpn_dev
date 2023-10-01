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
$ROLE = array();
foreach ($roles as $role) {
    $ROLE[$role->ID_ROLE]['ROLE'] = $role->ROLE;
    $ROLE[$role->ID_ROLE]['COLOR'] = $role->COLOR;
}
$INSATNSI = array();
foreach ($instansis as $instansi) {
    $INSATNSI[$instansi->INST_SATKERKD]['INST_NAMA'] = $instansi->INST_NAMA;
}
if($form=='add'){
?>
<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormAdd" action="index.php/ereg/admin_instansi/saveuser" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Username <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('username_admin_instansi'); ?>
                    <input type='text' class="form-control" name='USERNAME' id='USERNAMEadd' onblur="cek_user(this.value);" value='' required <?= FormHelpPlaceholderToolTip('username_admin_instansi') ?> required>
                    <span class="help-block"><font id='username_ada' style='display:none;' color='red'>Username sudah terdaftar</font></span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-username">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div> 


            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Nama <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('nama_admin_instansi'); ?>
                    <input type='text' class="form-control" name='NAMA' id='NAMA' value='' required <?= FormHelpPlaceholderToolTip('nama_admin_instansi') ?>>
                </div>
                <div class="col-sm-1"></div>
            </div>




            <!-- <div class="form-group">
                <label class="col-sm-4 control-label">Username <font color='red'>*</font>:</label>
                <div class="col-sm-7">
                    <input type='text' class="form-control" name='USERNAME' id='USERNAMEadd' onblur="cek_user(this.value);" value='' required>
                    <span class="help-block"><font id='username_ada' style='display:none;' color='red'>Username sudah terdaftar</font></span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-username">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nama <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='NAMA' id='NAMA' value='' required>
                </div>
            </div> -->
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Email <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('email_admin_instansi'); ?>
                    <input type='email' class="form-control" name='EMAIL' onblur="cek_email(this.value);" id='EMAIL' value='' required <?= FormHelpPlaceholderToolTip('email_admin_instansi') ?> placeholder="">
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
            </div>

             <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Instansi <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('instansi_admin_instansi'); ?>
                    <input required type='text' class="form-control form-select2" name='INST_SATKERKD' style="border:none;  padding:6px 0px;" id='INST_SATKERKD' placeholder="Pilih Instansi">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Nomor SK <span class="red-label"></span></label> 
                    <?= FormHelpPopOver('nomor_sk_admin_instansi'); ?>
                    <input type='text' class="form-control" name='NOMOR_SK' id='NOMOR_SK' value='' <?= FormHelpPlaceholderToolTip('nomor_sk_admin_instansi') ?>>
                </div>
                <div class="col-sm-1"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Handphone <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('handphone_admin_instansi'); ?>
                    <input type='text' class="form-control" onkeypress="validate(event)" name='HANDPHONE' id='HANDPHONE' value='' required <?= FormHelpPlaceholderToolTip('handphone_admin_instansi') ?>>
                </div>
                <div class="col-sm-1"></div>
            </div>
            
            
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">    
    $(function() {
        $('.over').popover();
           $('.over').on('click', function(e){
              $('.over').not(this).popover('hide'); 
           });
    })

    $(document).ready(function() {
        $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
        // $('#INST_SATKERKD').select2();
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);

        $('input[name="INST_SATKERKD"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getLembaga')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getLembaga')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
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
<div id="wrapperFormEdit" class='form-horizontal'>
    <form method="post" id="ajaxFormEdit" action="index.php/ereg/admin_instansi/saveuser">
        <div class="box-body">
                        
            <div class="form-group">
                <label class="col-sm-4 control-label">Username :</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='USERNAME' id='USERNAME' value='<?php echo $item->USERNAME;?>' required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nama :</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='NAMA' id='NAMA' value='<?php echo $item->NAMA;?>' required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Email :</label>
                <div class="col-sm-7">
                    <input type='email' class="form-control" name='EMAIL' id='EMAIL' onblur="cek_email(this.value, <?php echo $item->ID_USER ?>);" value='<?php echo $item->EMAIL;?>' required placeholder="johnsmith@email.com">
                    <span class="help-block"><font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font></span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-email">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Instansi :</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control form-select2" name='INST_SATKERKD' style="border:none; padding:6px 0px;" id='INST_SATKERKD' value='<?= @$item->INST_SATKERKD; ?>' placeholder="lembaga">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nomor SK :</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='NOMOR_SK' id='NOMOR_SK' value='<?php echo $item->NOMOR_SK;?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Handphone :</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control " onkeypress="validate(event)" name='HANDPHONE' maxlength="12" id='HANDPHONE' value='<?php echo $item->HANDPHONE;?>' required>
                </div>
            </div>
            
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_USER" value="<?php echo $item->ID_USER;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        
    </form>
</div>
<script type="text/javascript">
     $(function() {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
    })

    $(document).ready(function() {
        // $('#INST_SATKERKD').select2();
        ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);  

        $('input[name="INST_SATKERKD"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getLembaga')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getLembaga')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
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
<div id="wrapperFormDelete" class='form-horizontal'>
    Apakah Anda yakin akan menghapus username dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/ereg/admin_instansi/saveuser">
        <div class="box-body">
            
                        
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Username : </label>
                <label class="col-sm-8">
                    <?php echo $item->USERNAME;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Nama : </label>
                <label class="col-sm-8">
                    <?php echo $item->NAMA;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Email : </label>
                <label class="col-sm-8">
                    <?php echo $item->EMAIL;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Instansi : </label>
                <label class="col-sm-8">
                    <?php echo $this->minstansi->get_nama_instansi($item->INST_SATKERKD);?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Nomor SK : </label>
                <label class="col-sm-8">
                    <?php echo $item->NOMOR_SK;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Handphone : </label>
                <label class="col-sm-8">
                    <?php echo $item->HANDPHONE;?>
                </label>
            </div>
            
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_USER" value="<?php echo $item->ID_USER;?>">
            <input type="hidden" name="act" value="dodelete">
           <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Hapus</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
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
<div id="wrapperFormDetail" class='form-horizontal'>
        <div class="box-body">
            
                        
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Username : </label>
                <label class="col-sm-8">
                    <?php echo $item->USERNAME;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Nama : </label>
                <label class="col-sm-8">
                    <?php echo $item->NAMA;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Email : </label>
                <label class="col-sm-8">
                    <?php echo $item->EMAIL;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Instansi : </label>
                <label class="col-sm-8">
                    <?php echo $this->minstansi->get_nama_instansi($item->INST_SATKERKD);?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Nomor SK : </label>
                <label class="col-sm-8">
                    <?php echo $item->NOMOR_SK;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Handphone : </label>
                <label class="col-sm-8">
                    <?php echo $item->HANDPHONE;?>
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

<?php
if($form=='resetpassword'){
?>
<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/admin_instansi/doresetpassword">
        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
        <div class="col-lg-12 text-center">
            <p>Apakah anda yakin akan mereset password?</p>
            <button type="submit" class="btn btn-sm btn-primary">Ya</button>
            <input type="button" class="btn btn-danger btn-sm " value="Tidak" onclick="CloseModalBox();">
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
<script type="text/javascript">
    // $(document).ready(function() {
    //     $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
    // });
    function validate(evt) {
      var theEvent = evt || window.event;
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode( key );
      var regex = /[0-9\b]|\./;
      if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
      }
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
    });
</script>