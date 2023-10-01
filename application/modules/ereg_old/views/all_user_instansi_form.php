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
if($form=='add'){ /// KETIKA TAMBAH DATA
?>

<div id="wrapperFormAdd" xmlns="http://www.w3.org/1999/html">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" data-toggle="modal" data-bakcdrop="static" action="index.php/ereg/all_user_instansi/saveuser" enctype="multipart/form-data">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                 <label>Admin Unit Kerja<span class="red-label">*</span></label>
				<?= FormHelpPopOver('jenis_user_instansi'); ?>				 
                 <span style="display:block;">
                     <label><input type="radio" name="jenis_user" class="jenis_user" value="1"/> Lingkup Instansi</label>&nbsp
                     <label><input type="radio" name="jenis_user" class="jenis_user" value="2"/> Lingkup Unit Kerja</label>
                 </span>
            </div>
            <div class="col-sm-1"></div>
        </div>
       <div class="form-group instansi">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Instansi <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('instansi_user_instansi'); ?>
                <?php if($this->session->userdata('ID_ROLE')=='4'||$this->session->userdata('ID_ROLE')=='3'): ?>
                    <input disabled  type="text" id="INST_TUJUAN" placeholder="Pilih Instansi" class="select" style="width: 100%;" />
                    <input type="hidden"  name="INST_TUJUAN" value="<?php echo $this->session->userdata('INST_SATKERKD');?>"/>
                <?php Else: ?>
                    <input type="text" id="INST_TUJUAN" placeholder="Pilih Instansi" name="INST_TUJUAN" class="select" style="width: 100%;" />
                <?php EndIf; ?>    
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="form-group unit_kerja">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('unit_kerja_user_instansi'); ?>
                <input type="text" id="UNIT_KERJA" placeholder="Pilih Unit Kerja" name="UNIT_KERJA" class="select" style="width: 100%;" />
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Username <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('username_user_instansi'); ?>
                <input type='text' class="form-control" name='USERNAME' id='USERNAME' onblur="cek_user(this.value);" value='' required <?= FormHelpPlaceholderToolTip('username_user_instansi') ?> placeholder="">
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>Username <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
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
                <?= FormHelpPopOver('nama_user_instansi'); ?>
                <input type='text' class="form-control" name='NAMA' size='40' id='NAMA' value='' required <?= FormHelpPlaceholderToolTip('nama_user_instansi') ?> placeholder="">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Email <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('email_user_instansi'); ?>
                <input type='email' class="form-control" name='EMAIL' size='40' id='EMAIL' onblur="cek_email(this.value);" value='' required <?= FormHelpPlaceholderToolTip('email_user_instansi') ?> placeholder="">
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
                <label>Handphone <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('handphone_user_instansi'); ?>
                <input type='text' class="form-control " onkeypress="validate(event)" onkeypress="validate(event)" size='40' name='HANDPHONE' id='HANDPHONE' value='' required <?= FormHelpPlaceholderToolTip('handphone_user_instansi') ?> placeholder="">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
           <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', window.location.href.split('#')[1]);
        // $('#ajaxFormAdd').modal({
        //   backdrop: false
        // });


       

        $('.unit_kerja,.instansi').hide();
        $('.jenis_user').click (function(){
            if (this.checked) {
                var value = $(this).val();
                if(value=='1'){
                  $('.instansi').fadeIn('slow');
                  $('.unit_kerja').fadeOut('slow');
                }else{
                  $('.instansi').fadeIn('slow');
                  $('.unit_kerja').fadeIn('slow');
                }
                var x = $('#INST_TUJUAN').val();
                $('input[name="UNIT_KERJA"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+x,
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
                            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+x, {
                            // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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
            }
        });

        $('#INST_TUJUAN').select2({
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
        }).on("change", function(e) {
            var lembid = $('#INST_TUJUAN').val();

            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+lembid,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+lembid, {
                        // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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

        <?php if($this->session->userdata('ID_ROLE')=='4'||$this->session->userdata('ID_ROLE')=='3'): ?>
            $('.instansi').fadeIn('slow');
            $('#INST_TUJUAN').select2("data", { id: <?php echo $this->session->userdata('INST_SATKERKD');?>, name: "<?php echo strtoupper($this->session->userdata('INST_NAMA'));?>" });
        <?php EndIf; ?>


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
if($form=='edit'){ // KETIKA EDIT DATA
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/ereg/all_user_instansi/saveuser">
        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                 <label>Jenis User <span class="red-label">*</span></label> 
                 <span style="display:block;">
                    <?php if($item->UK_ID): ?>
                        <label><input type="radio" id="radio1" name="jenis_user" class="jenis_user" value="1"/> User Instansi</label>&nbsp
                        <label><input type="radio" id="radio2" name="jenis_user" class="jenis_user" value="2" checked/> User Unit Kerja</label>
                    <?php Else: ?>
                         <label><input type="radio" id="radio1" name="jenis_user" class="jenis_user" value="1" checked/> User Instansi</label>&nbsp
                        <label><input type="radio" id="radio2" name="jenis_user" class="jenis_user" value="2"/> User Unit Kerja</label>
                    <?php EndIf; ?>    
                 </span>
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="form-group instansi">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Instansi <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('instansi'); ?>
                 <?php if($this->session->userdata('ID_ROLE')=='4'||$this->session->userdata('ID_ROLE')=='3'): ?>
                    <input disabled  type="text" id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;" />
					<input type="hidden"  name="INST_TUJUAN" value="<?php echo $this->session->userdata('INST_SATKERKD');?>"/>
                <?php Else: ?>
                    <input type="text" id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;" />
                <?php EndIf; ?>    
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="form-group unit_kerja">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('unit_kerja'); ?>
                <input type="text" id="UNIT_KERJA" name="UNIT_KERJA" class="select" style="width: 100%;" />
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Username <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('username'); ?>
                <input type='text' class="form-control" name='USERNAME' id='USERNAME' onblur="cek_user(this.value,<?php echo $item->ID_USER;?>);" value='<?php echo $item->USERNAME;?>' required <?= FormHelpPlaceholderToolTip('username') ?> placeholder="Username">
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>Username <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
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
                <?= FormHelpPopOver('nama'); ?>
                <input type='text' class="form-control" name='NAMA' size='40' id='NAMA' value='<?php echo $item->NAMA;?>' required <?= FormHelpPlaceholderToolTip('nama') ?> placeholder="Nama">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Email <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('email'); ?>
                <input type='email' class="form-control" name='EMAIL' size='40' id='EMAIL' onblur="cek_email(this.value,<?php echo $item->ID_USER;?>);" value='<?php echo $item->EMAIL;?>' required <?= FormHelpPlaceholderToolTip('email') ?> placeholder="Email">
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
                <label>Handphone <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('handphone'); ?>
                <input type='text' class="form-control " onkeypress="validate(event)" onkeypress="validate(event)" size='40' name='HANDPHONE' id='HANDPHONE' value='<?php echo $item->HANDPHONE;?>' required <?= FormHelpPlaceholderToolTip('handphone') ?> placeholder="Handphone">
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="pull-right">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);
        $('.unit_kerja,.instansi').hide();

        var uk_id;
        <?php
            if($item->UK_ID){
                echo "uk_id = ".$item->UK_ID.";";
                echo " $('.instansi').fadeIn('slow'); ";
                echo " $('.unit_kerja').fadeIn('slow'); ";          
            }else{
                echo " $('.instansi').fadeIn('slow'); ";   
            }
        ?> 

        <?php if($item->UK_ID): ?>
         $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                    ajax: {
                        url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/<?php echo $instansi->INST_SATKERKD;?>",
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
                            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/<?php echo $instansi->INST_SATKERKD;?>", {
                            // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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
        $('input[name="UNIT_KERJA"]').select2("data", { id: <?php echo $unit_kerja->UK_ID;?>, name: "<?php echo strtoupper($unit_kerja->UK_NAMA);?>" });
        <?php EndIf; ?>

        $('.jenis_user').click (function(){
            if (this.checked) {
                var value = $(this).val();
                if(value=='1'){
                  $('.instansi').fadeIn('slow');
                  $('.unit_kerja').fadeOut('slow');
                }else{
                  $('.instansi').fadeIn('slow');
                  $('.unit_kerja').fadeIn('slow');
                }
                var x = $('#INST_TUJUAN').val();
                $('input[name="UNIT_KERJA"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+x,
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
                            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+x, {
                            // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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
            }
        });


         $('#INST_TUJUAN').select2({
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
        }).on("change", function(e) {
            var lembid = $('#INST_TUJUAN').val();

            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+lembid,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+lembid, {
                        // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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
        $("#INST_TUJUAN").select2("data", { id: <?php echo $instansi->INST_SATKERKD;?>, name: "<?php echo strtoupper($instansi->INST_NAMA);?>" });


       


    });
</script>
<?php
}
?>
<?php
if($form=='delete'){ // KETIKA HAPUS DATA
?>
<div id="wrapperFormDelete">
    Apakah anda yakin akan menghapus username dibawah ini?
    <form method="post" id="ajaxFormDelete" action="index.php/ereg/all_user_instansi/saveuser">
        <table width="30%">
            <!-- <tr><td>Id User</td><td>:</td><td><?php echo $item->ID_USER;?></td></tr> -->
			<tr><td>Username</td><td>:</td><td><?php echo $item->USERNAME;?></td></tr>
            <tr><td>Nama</td><td>:</td><td><?php echo $item->NAMA;?></td></tr>
            <tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL;?></td></tr>
			<tr><td>Handphone</td><td>:</td><td><?php echo $item->HANDPHONE;?></td></tr>
			<!-- <tr><td>Nip</td><td>:</td><td><?php echo $item->NIP;?></td></tr> -->
            <tr style="display:none;">
                <td>Is Active</td>
                <td>:</td>
                <td>
                <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
                </td>
            </tr>
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_USER" value="<?php echo $item->ID_USER;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Simpan</button>
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
if($form=='reset_password'){ // KETIKA GANTI PASSWORD
?>
    <form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_user_instansi/do_reset_password">
        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
        <div class="col-lg-12 text-center">
            <p>Apakah Anda yakin akan mereset password?</p>
            <button type="submit" class="btn btn-sm btn-primary">Ya</button>
            <input type="button" class="btn btn-sm btn-danger" value="Tidak" onclick="CloseModalBox();">
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
if($form=='detail'){ // KETIKA LIHAT DATA
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

     $(function() {
        $('.over').popover();
               $('.over').on('click', function(e){
                  $('.over').not(this).popover('hide'); 
               });
    });

function showUnit() {
    if ($('#L_Unit_kerja').is(':checked')) {
        $('#L_Unit :input').attr('disabled', true);
    } else {
        $('#L_Unit :input').removeAttr('disabled');
    }   
}
</script>