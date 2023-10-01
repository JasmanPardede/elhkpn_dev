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
* @author Gunaones - PT.Mitreka Solusi Indonesia || Irfan Isma Somantri @onepiece
* @package Views/user
*/

$ROLE = array();
foreach ($roles as $role) {
    $ROLE[$role->ID_ROLE]['ROLE'] = $role->ROLE;
    $ROLE[$role->ID_ROLE]['COLOR'] = $role->COLOR;
}
$INSATNSI = array();
foreach ($instansis as $instansi) {
    $INSATNSI[$instansi->INST_SATKERKD]['INST_NAMA'] = $instansi->INST_NAMA;
}
?>
<?php
if($form=='add'){
?><style type="text/css">
  .twitter-typeahead .tt-query,
  .twitter-typeahead .tt-hint {
    margin-bottom: 0;
  }

  .twitter-typeahead .tt-hint
  {
      display: none;
  }

  .tt-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    list-style: none;
    font-size: 14px;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }
  .tt-menu {
      width: 100%;
      background-color: #FFFFFF;
      border: 1px solid #CCCCCC;
  }
  .tt-suggestion > span {
    display: block;
    padding: 3px 5px;
    clear: both;
    font-weight: normal;
    line-height: 1.1;
    color: #333333;
    white-space: nowrap;
    width: 100%;
      background-color: #FFFFFF;
      text-align: left;
  }
  .tt-suggestion > span:hover,
  .tt-suggestion > span:focus,
  .tt-suggestion.tt-cursor p {
    color: #ffffff;
    text-decoration: none;
    outline: 0;
    background-color: #428bca;
  }

</style>
<div id="wrapperFormAdd" class="form-horizontal">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/admin/user/saveuser" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' size='40' name='NAMA' id='NAMA' placeholder="Nama">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Username <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' size='40' name='USERNAME' onblur="cek_user_ldap(this.value);" id='USERNAME' placeholder="Username">
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>Username sudah terdaftar</font>
                <font id='username_tidaktersedia' style='display:none;' color='red'>Username tidak tersedia</font>
                </span>
            </div>
            <div class="col-sm-1" style="margin-top: 5px;" id="div-user">
                <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Role <span class="red-label">*</span>:</label>
            <div class="col-sm-9">
                <?php
                foreach ($roles as $role) {
                ?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <label style="color:<?php echo $role->COLOR;?>"><input class="cek_is" required type="checkbox" name="ID_ROLE[]" value="<?php echo $role->ID_ROLE;?>"> <?php echo $role->ROLE;?></label><br>
                </div>
                <?php
                }
                ?>
                <span class="help-block"><font id='cek_ada' style='display:none;' color='red'>Pilih salah satu</font></span>
            </div>
        </div>
        <!-- <div class="form-group">
            <label class="col-sm-3 control-label">Instansi <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input type='text' class="input-sm select form-control" name='INST_SATKERKD' style="border:none; padding: 6px 0px;" id='INST_SATKERKD' value='' placeholder="-- Pilih Instansi --">
            </div>
        </div> -->
        <div class="form-group">
            <label class="col-sm-3 control-label">Nomor SK <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' name='NOMOR_SK' id='NOMOR_SK' placeholder="Nomor SK">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='email' name='EMAIL' onblur="cek_email(this.value);" id='EMAIL' placeholder="johnsmith@email.com">
                <span class="help-block"><font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font></span>
            </div>
            <div class="col-sm-1" style="margin-top: 5px;" id="div-email">
                <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Handphone <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control numbersOnly" type='text' name='HANDPHONE' id='HANDPHONE' placeholder="Handphone" onkeypress="validate(event)">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Foto :</label>
            <div class="col-sm-9">
                <input style="border: none;" type='file' name='PHOTO' id='PHOTO'>
                <span class=' help-block'>Type File: png, jpg, jpeg, tif .  Max File: 2000KB</span>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary dump"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<div class="div"></div>
<script src="<?php echo base_url();?>plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="<?php echo base_url();?>plugins/typeahead/handlebars.js"></script>
<script src="<?php echo base_url();?>plugins/typeahead/typeahead.bundle.min.js"></script>
<script type="text/javascript">

    var countChecked = function() {
        var n = $( ".cek_is:checked" ).length;
        if(n >= 1){
            $('.cek_is').attr('required',false);
        }else{
            $('.cek_is').attr('required',true);
        }
    };

    countChecked();

    $( ".cek_is[type=checkbox]" ).on( "click", countChecked );

    $(document).ready(function() {
        var list_user_ldap = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '<?=base_url("index.php/admin/user/search_user_ldap/?username=")?>%QUERY',
                wildcard: '%QUERY'
            }
        });
        $('#USERNAME').typeahead(null, {
            name: 'Username',
            display: 'value',
            source: list_user_ldap,
            templates: {
                suggestion: Handlebars.compile('<div class="autocomplete"><span>{{value}}</span></div>')
            },
            hint: true,
            highlight: true,
            minLength: 1
        });
        $('#USERNAME').bind('typeahead:selected', function(obj, datum, name) {
            $(this).val(name);
			$('#NAMA').val(datum.displayName);
			cek_user_ldap(datum.value);
            return false;
        });
        $('#INST_SATKERKD').change(function(){
            v = $(this).val();
            $("#CARI_INST").select2("val", v);
        });
        $('#INST_SATKERKD').select2({
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
?><style type="text/css">
    .twitter-typeahead .tt-query,
    .twitter-typeahead .tt-hint {
        margin-bottom: 0;
    }

    .twitter-typeahead .tt-hint
    {
        display: none;
    }

    .tt-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        list-style: none;
        font-size: 14px;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        background-clip: padding-box;
    }
    .tt-menu {
        width: 100%;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
    }
    .tt-suggestion > span {
        display: block;
        padding: 3px 5px;
        clear: both;
        font-weight: normal;
        line-height: 1.1;
        color: #333333;
        white-space: nowrap;
        width: 100%;
        background-color: #FFFFFF;
        text-align: left;
    }
    .tt-suggestion > span:hover,
    .tt-suggestion > span:focus,
    .tt-suggestion.tt-cursor p {
        color: #ffffff;
        text-decoration: none;
        outline: 0;
        background-color: #428bca;
    }

</style>
<div id="wrapperFormEdit" class="form-horizontal">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/admin/user/saveuser">
        <div class="row">    
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <?php if ($item->PHOTO == 'no_image_available.gif'): ?>
                    <img src="<?php echo base_url().'uploads/users/'.$item->PHOTO; ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
                <?php else : ?>  
                    <img src="<?php echo base_url().'uploads/users/'.$item->USERNAME.'/'.$item->PHOTO; ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
                <?php endif ?>
            </div>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
                    <div class="col-sm-9">
                        <input required class="form-control" type='text' size='40' name='NAMA' id='NAMA' placeholder="Nama" value='<?php echo $item->NAMA;?>'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Username <span class="red-label">*</span>:</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="CURRENT_USERNAME" id="CURRENT_USERNAME" value="<?php echo $item->USERNAME;?>" />
                        <input required class="form-control" type='text' size='40' name='USERNAME' id='USERNAME' onblur="cek_user_ldap(this.value, '<?php echo $item->USERNAME;?>');" placeholder="Username" value='<?php echo $item->USERNAME;?>'>
                    </div>
                    <div class="col-sm-1" style="margin-top: 5px;" id="div-user">
                        <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                        <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                        <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Role <span class="red-label">*</span>:</label>
                    <div class="col-sm-9">
                        <?php
                        $ARR_USERROLE = explode(',',$item->ID_ROLE);
                        foreach ($roles as $role) {
                        ?>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <label style="color:<?php echo $role->COLOR;?>"><input type="checkbox" name="ID_ROLE[]" value="<?php echo $role->ID_ROLE;?>" <?php echo in_array($role->ID_ROLE, $ARR_USERROLE)?'checked':'';?>> <?php echo $role->ROLE;?></label><br>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label class="col-sm-3 control-label">Instansi <span class="red-label">*</span>:</label>
                    <div class="col-sm-9">
                        <input type='text' class="form-control input-sm select" name='INST_SATKERKD' style="border:none;padding: 6px 0px;" id='INST_SATKERKD' value='<?php echo $item->INST_SATKERKD;?>' placeholder="-- Pilih Instansi --">
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nomor SK <span class="red-label">*</span>:</label>
                    <div class="col-sm-9">
                        <input required class="form-control" type='text' size='40' name='NOMOR_SK' id='NOMOR_SK' placeholder="Nomor SK" value='<?php echo $item->NOMOR_SK;?>'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Email <span class="red-label">*</span>:</label>
                    <div class="col-sm-9">
                        <input required class="form-control" type='email' size='40' name='EMAIL' onblur="cek_email(this.value);" id='EMAIL' placeholder="johnsmith@email.com" value='<?php echo $item->EMAIL;?>'>
                        <span class="help-block"><font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font></span>
                    </div>
                    <div class="col-sm-1" style="margin-top: 5px;" id="div-email">
                        <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                        <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                        <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Handphone <span class="red-label">*</span>:</label>
                    <div class="col-sm-9">
                        <input required class="form-control numbersOnly" type='text' size='40' name='HANDPHONE' id='HANDPHONE' placeholder="Handphone" value='<?php echo $item->HANDPHONE;?>' onkeypress="validate(event)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Foto :</label>
                    <div class="col-sm-9">
                        <input style="border: none;" type='file' name='PHOTO' id='PHOTO'>
                        <span class=' help-block'>Type File: png, jpg, jpeg, tif .  Max File: 2000KB</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doupdate">
            <input type="hidden" name="ID_USER" value="<?= @$item->ID_USER; ?>">
            <input type="hidden" name="tmp_PHOTO" value="<?= @$item->PHOTO; ?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<script src="<?php echo base_url();?>plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="<?php echo base_url();?>plugins/typeahead/handlebars.js"></script>
<script src="<?php echo base_url();?>plugins/typeahead/typeahead.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var list_user_ldap = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '<?=base_url("index.php/admin/user/search_user_ldap/?username=")?>%QUERY',
                wildcard: '%QUERY'
            }
        });

        $('#USERNAME').typeahead(null, {
            name: 'Username',
            display: 'value',
            source: list_user_ldap,
            templates: {
                suggestion: Handlebars.compile('<div class="autocomplete"><span>{{value}}</span></div>')
            },
            hint: true,
            highlight: true,
            minLength: 1
        });
        $('#USERNAME').bind('typeahead:selected', function(obj, datum, name) {
            $(this).val(name);
            $('#NAMA').val(datum.displayName);
            cek_user_ldap(datum.value);
            return false;
        });

        $('#INST_SATKERKD').change(function(){
            v = $(this).val();
            $("#CARI_INST").select2("val", v);
        });        
        $('#INST_SATKERKD').select2({
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
<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus User dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/admin/user/saveuser">
        <div class="row">
            <div class="clearfix" style="margin-bottom: 10px;"></div>
            <div class="col-md-4">
                <?php if ($item->PHOTO == 'no_image_available.gif'): ?>
                    <img src="<?php echo base_url().'uploads/users/'.$item->PHOTO; ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
                <?php else : ?>  
                    <img src="<?php echo base_url().'uploads/users/'.$item->USERNAME.'/'.$item->PHOTO; ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
                <?php endif ?>
            </div>
            <div class="col-md-8">
                <table>
                    <!-- <tr><td>Id User</td><td>:</td><td><?php echo $item->ID_USER;?></td></tr> -->
                    <tr><td width="100px"><strong>Nama</strong></td><td width="10px">:</td><td><?php echo $item->NAMA;?></td></tr>
                    <tr><td><strong>Username</strong></td><td>:</td><td><?php echo $item->USERNAME;?></td></tr>
                    <!-- <tr><td>Nip</td><td>:</td><td><?php echo $item->NIP;?></td></tr> -->
                    <tr><td><strong>Role</strong></td><td>:</td><td><?php echo printRole($item->ID_ROLE, $ROLE);?></td></tr>
                    <!-- <tr><td><strong>Instansi</strong></td><td>:</td><td><?php echo $INSATNSI[$item->INST_SATKERKD]['INST_NAMA']; ?></td></tr> -->
                    <tr><td><strong>Nomor SK</strong></td><td>:</td><td><?php echo $item->NOMOR_SK;?></td></tr>
                    <tr><td><strong>Email</strong></td><td>:</td><td><?php echo $item->EMAIL;?></td></tr>
                    <tr><td><strong>Handphone</strong></td><td>:</td><td><?php echo $item->HANDPHONE;?></td></tr>
                </table>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_USER" value="<?php echo $item->ID_USER;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Hapus</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail" class="form-horizontal">
    <div class="row">
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <?php if ($item->PHOTO == 'no_image_available.gif'): ?>
            <img src="<?php echo base_url().'uploads/users/'.$item->PHOTO; ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
        <?php else : ?>  
            <img src="<?php echo base_url().'uploads/users/'.$item->USERNAME.'/'.$item->PHOTO; ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
        <?php endif ?>
    </div>
    
    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
        <h3>Detail</h3>
        <table>
            <!-- <tr><td>Id User</td><td>:</td><td><?php echo $item->ID_USER;?></td></tr> -->
                <tr><td width="100px"><strong>Nama</strong></td><td width="10px">:</td><td><?php echo $item->NAMA;?></td></tr>
                <tr><td><strong>Username</strong></td><td>:</td><td><?php echo $item->USERNAME;?></td></tr>
                <!-- <tr><td>Nip</td><td>:</td><td><?php echo $item->NIP;?></td></tr> -->
                <tr><td><strong>Role</strong></td><td>:</td><td><?php echo printRole($item->ID_ROLE, $ROLE);?></td></tr>
                <tr><td><strong>Nomor SK</strong></td><td>:</td><td><?php echo $item->NOMOR_SK;?></td></tr>
                <!--<tr><td><strong>Instansi</strong></td><td>:</td><td><?= ($item->INST_SATKERKD != '' ? $INSATNSI[$item->INST_SATKERKD]['INST_NAMA'] : '-' ); ?></td></tr>-->
                <!--<tr><td><strong>Password Expired</strong></td><td>:</td><td><?php echo date('d/m/Y',strtotime($item->EXPIRED));?></td></tr>--> 
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
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
    </div>
</div>
<?php
}
?>

<?php
if($form=='resetpassword'){
?>
<div id="ajaxFormResetPassword" class="form-horizontal">
    Benarkah Akan Mereset Password dibawah ini, Password akan dikirim melalui email?
    <form method="post" id="ajaxFormReset" action="index.php/admin/user/doresetpassword">
        <table>
            <input type="hidden" name="USERNAME" id="" style="" value="<?php echo $item->USERNAME;?>">
            <!-- <tr><td>Id User</td><td>:</td><td><?php echo $item->ID_USER;?></td></tr> -->
            <tr><td width="100px"><strong>Nama</strong></td><td width="10px">:</td><td><?php echo $item->NAMA;?></td></tr>
            <tr><td><strong>Username</strong></td><td>:</td><td><?php echo $item->USERNAME;?></td></tr>
            <!-- <tr><td>Nip</td><td>:</td><td><?php echo $item->NIP;?></td></tr> -->
            <tr><td><strong>Role</strong></td><td>:</td><td><?php echo printRole($item->ID_ROLE, $ROLE);?></td></tr>
            <tr><td><strong>Instansi</strong></td><td>:</td><td><?php echo $INSATNSI[$item->INST_SATKERKD]['INST_NAMA']; ?></td></tr>
            <tr><td><strong>Jabatan</strong></td><td>:</td><td><?php echo $item->JABATAN; ?></td></tr>
            <tr><td><strong>Nomor SK</strong></td><td>:</td><td><?php echo $item->NOMOR_SK;?></td></tr>
            <tr><td><strong>Email</strong></td><td>:</td><td><?php echo $item->EMAIL;?></td></tr>
            <tr><td><strong>Handphone</strong></td><td>:</td><td><?php echo $item->HANDPHONE;?></td></tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>:</td>
                <td>
                <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
                </td>
            </tr>
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_USER" value="<?php echo $item->ID_USER;?>">
            <input type="hidden" name="act" value="dorepas1">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Kirim</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormReset"), 'delete', location.href.split('#')[1]);
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