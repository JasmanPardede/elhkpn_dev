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
 * @author Rizky Awlia Fajrin (Evan Sumangkut) - PT.Waditra Reka Cipta
 * @package Views/user
*/
?>
<?php
$ROLE = array();
foreach ($roles as $role) {
    $ROLE[$role->ID_ROLE]['ROLE'] = $role->ROLE;
    $ROLE[$role->ID_ROLE]['COLOR'] = $role->COLOR;
}
if($form=='add'){
?>
<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormAdd" enctype="multipart/form-data">
        <div class="box-body">
            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Instansi <span class="red-label">*</span></label>
                    <?= FormHelpPopOver('api_pengumuman_instansi'); ?>
                    <input required type='text' class="form-control form-select2" name='inst_satkerkd' style="border:none;  padding:0px 0px;" id='inst_satkerkd' placeholder="Pilih Instansi">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <!-- <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Nama </label>
                    <input type='text' class="form-control" name='name'  id='name'>
                </div>
                <div class="col-sm-1">
                </div>
            </div> -->
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Email </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <input type='text' class="form-control" name='email'  id='email'>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Password </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <input type='text' class="form-control" name='password'  id='password'>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>IP Permission </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <textarea  name='ip_permission'  id='ip_permission' class="form-control" rows="3"></textarea>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Akses </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <br>
                    <input type='checkbox' name="akses_api" value='wajib_lapor'  id='akses_api'> Wajib Lapor
                    <br>
                    <input type='checkbox' name="akses_api" value='harta'  id='akses_api'> Harta
                    <br>
                    <input type='checkbox' name="akses_api" value='keluarga'  id='akses_api'> Keluarga
                    <br>
                    <input type='checkbox' name="akses_api" value='pengumuman_batch'  id='akses_api'> Pengumuman batch
                    <br>
                    <input type='checkbox' name="akses_api" value='report'  id='akses_api'> Report
                    <br>
                    <input type='checkbox' name="akses_api" value='wajib_lapor_kemenkeu'  id='akses_api'> Wajib Lapor Kemenkeu
                    <br>
                    <input type='checkbox' name="akses_api" value='harta_kemenkeu'  id='akses_api'> Harta Kemenkeu
                    <br>
                    <input type='checkbox' name="akses_api" value='detail_harta'  id='akses_api'> Detail Harta
                    <br>
                    <input type='checkbox' name="akses_api" value='pelaporan_bkn'  id='akses_api'> Pelaporan BKN
                    <br>
                    <input type='checkbox' name="akses_api" value='satu_data_indonesia'  id='akses_api'> Satu Data Indonesia
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <?php endif; ?>

        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Generate</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>

    </form>
</div>
<script type="text/javascript">
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


    $(document).ready(function() {
        $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
        // $('#INST_SATKERKD').select2();
        var dtTable = $('#dt_completeNEW').DataTable();
        // ng.formProcess($("#ajaxFormAdd"), 'insert','',null,null,null,'API sudah dibuat!');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormAdd').submit(function (e) {
            event.preventDefault();
            const set_inst_satkerkd = $('#inst_satkerkd').val();
            const set_name = $('#name').val();
            const set_email = $('#email').val();
            const set_password = $('#password').val();
            const set_ip_permission = $('#ip_permission').val();
            const set_akses_api = $("#akses_api:checked").map(function(){
                return $(this).val();
            }).get();
            const key = '<?= $this->config->item('apiInstansiKey') ?>';
            const url = '<?= $this->config->item('apiInstansiURL') ?>';
            values = { inst_satkerkd: set_inst_satkerkd, name: set_name, email:set_email, password:set_password, key:key, akses_api:set_akses_api, ip_permission:set_ip_permission };
            $.ajax({
                url: url+'register',
                type: "post",
                data: values,
                success: function (response) {
                    dtTable.ajax.reload( null, false );
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
            CloseModalBox2();
            return;
        });

        <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31){ ?>
                $('input[name="inst_satkerkd"]').select2({
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
                }).on("change", function (e) {
                    var ID_INST = $('#inst_satkerkd').val();
                    GetUK(ID_INST);
                });

                    $('input[name="UK_ID"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?=base_url('index.php/share/reff/getUnitKerja')?>",
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
                            $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>", {
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
        <?php }else{ ?>
              var ID_INST = <?php echo $this->session->userdata('INST_SATKERKD'); ?>;
              GetUK(ID_INST);
        <?php } ?>
    });
    function GetUK(ID_INST){
            $('input[name="UK_ID"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+ID_INST,
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
                        $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+ID_INST, {
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
</script>
<?php
}
?>
<?php
if($form=='approve'){
?>
<div id="wrapperFormApprove" class='form-horizontal'>
    <?php if($item->IS_ACTIVE!=1){ ?>
    Apakah anda ingin menonaktifkan API ?
    <?php }else{ ?>
    Apakah anda ingin mengaktifkan API ?
    <?php } ?>
    <form method="post" id="ajaxFormActive" action="index.php/admin/api_instansi/save_api">
        <div class="box-body">
            <div class="form-group">
            <br>
            </div>

        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->id;?>">
            <input type="hidden" name="act" value="doapprove">
           <button type="submit" class="btn btn-primary btn-sm">Ya</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>

    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormActive"), 'active', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormActive').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });
    });
</script>

<?php
}
?>
<?php
if($form=='email'){
?>
<div id="wrapperFormApprove" class='form-horizontal'>
    <form method="post" id="ajaxFormActive" action="index.php/admin/api_instansi/email_send">
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Email <span class="red-label">*</span></label>
                    <?= FormHelpPopOver('api_pengumuman_email'); ?>
                    <input required type='email' class="form-control" name='email'  id='email' placeholder="Masukan email penerima">
                </div>
                <div class="col-sm-1">
                </div>
            </div>

        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="INST_SATKERKD" value="<?php echo $item->INST_SATKERKD;?>">
            <input type="hidden" name="act" value="doemail">
           <button type="submit" class="btn btn-primary btn-sm">Ya</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>

    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormActive"), 'active', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormActive').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });
    });
</script>

<?php
}
?>
<?php
if($form=='password'){
?>
<div id="wrapperFormApprove" class='form-horizontal'>
    <form method="post" id="ajaxFormActive">
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Password baru </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <input type='text' class="form-control" name='password' required  id='password' value="">
                </div>
                <div class="col-sm-1">
                </div>
            </div>

        </div>
        <div class="pull-right">
            <input type="hidden" name="email" id="email" value="<?php echo $item->email;?>">
            <input type="hidden" name="act" value="dopassword">
           <button type="submit" class="btn btn-primary btn-sm">Ya</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>

    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var dtTable = $('#dt_completeNEW').DataTable();
        // ng.formProcess($("#ajaxFormActive"), 'active', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormActive').submit(function (e) {
            // dtTable.ajax.reload( null, false );
            event.preventDefault();
            const key = '<?= $this->config->item('apiInstansiKey') ?>';
            const set_email = $('#email').val();
            const set_password = $('#password').val();
            const url = '<?= $this->config->item('apiInstansiURL') ?>';
            values = { password: set_password, email: set_email, key:key };
            $.ajax({
                url: url+'update_password',
                type: "post",
                data: values,
                success: function (response) {
                    dtTable.ajax.reload( null, false );
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
            CloseModalBox2();
            return;
        });
    });
</script>

<?php
}
?>

<?php
if($form=='edit'){
?>
<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormActive" action="index.php/admin/api_instansi/save_api">
        <div class="box-body">
            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Email </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <input type='text' class="form-control" name='email'  id='email' value="<?= $item->email ?>">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>IP Permission </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <textarea  name='ip_permission'  id='ip_permission' class="form-control" rows="3"><?= $item->ip_permission ?></textarea>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Akses </label>
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <br>
                    <input type='checkbox' name="akses_api[]" value='wajib_lapor' <?= strpos($item->akses_api,'wajib_lapor') ? 'checked' : false ?>  id='akses_api'> Wajib Lapor
                    <br>
                    <input type='checkbox' name="akses_api[]" value='harta' <?= strpos($item->akses_api,'harta') ? 'checked' : false ?>  id='akses_api'> Harta
                    <br>
                    <input type='checkbox' name="akses_api[]" value='keluarga' <?= strpos($item->akses_api,'keluarga') ? 'checked' : false ?>  id='akses_api'> Keluarga
                    <br>
                    <input type='checkbox' name="akses_api[]" value='pengumuman_batch' <?= strpos($item->akses_api,'pengumuman_batch') ? 'checked' : false ?>  id='akses_api'> Pengumuman batch
                    <br>
                    <input type='checkbox' name="akses_api[]" value='report' <?= strpos($item->akses_api,'report') ? 'checked' : false ?>  id='akses_api'> Report
                    <br>
                    <input type='checkbox' name="akses_api[]" value='wajib_lapor_kemenkeu' <?= strpos($item->akses_api,'wajib_lapor_kemenkeu') ? 'checked' : false ?>  id='akses_api'> Wajib Lapor Kemenkeu
                    <br>
                    <input type='checkbox' name="akses_api[]" value='harta_kemenkeu' <?= strpos($item->akses_api,'harta_kemenkeu') ? 'checked' : false ?>  id='akses_api'> Harta Kemenkeu
                    <br>
                    <input type='checkbox' name="akses_api[]" value='detail_harta' <?= strpos($item->akses_api,'detail_harta') ? 'checked' : false ?>  id='akses_api'> Detail Harta
                    <br>
                    <input type='checkbox' name="akses_api[]" value='pelaporan_bkn' <?= strpos($item->akses_api,'pelaporan_bkn') ? 'checked' : false ?>  id='akses_api'> Pelaporan BKN
                    <br>
                    <input type='checkbox' name="akses_api[]" value='satu_data_indonesia' <?= strpos($item->akses_api,'satu_data_indonesia') ? 'checked' : false ?>  id='akses_api'> Satu Data Indonesia
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <?php endif; ?>

        </div>
        <div class="pull-right">
            <input type="hidden" name="id" value="<?php echo $item->id;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Generate</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>

    </form>
</div>
<script type="text/javascript">
   $(document).ready(function() {
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormActive"), 'active', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormActive').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });
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
