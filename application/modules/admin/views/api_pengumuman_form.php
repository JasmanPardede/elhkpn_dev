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
    <form method="post" id="ajaxFormAdd" action="index.php/admin/api_pengumuman/save_api" enctype="multipart/form-data">
        <div class="box-body">
            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Instansi <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('api_pengumuman_instansi'); ?>
                    <input required type='text' class="form-control form-select2" name='INST_SATKERKD' style="border:none;  padding:0px 0px;" id='INST_SATKERKD' placeholder="Pilih Instansi">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Set Logo </label> 
                    <?= FormHelpPopOver('api_pengumuman_logo'); ?>
                    <input type='file' class="form-control" name='logo'  id='logo'>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Link Website </label> 
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <input type='text' class="form-control" name='LINK_WEB'  id='LINK_WEB'>
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
        ng.formProcess($("#ajaxFormAdd"), 'insert','',null,null,null,'API sudah dibuat!');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormAdd').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });

        <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31){ ?>
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
                }).on("change", function (e) {
                    var ID_INST = $('#INST_SATKERKD').val();
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
    <form method="post" id="ajaxFormActive" action="index.php/admin/api_pengumuman/save_api">
        <div class="box-body">
            <div class="form-group">
            <br>
            </div>
           
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
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
    <form method="post" id="ajaxFormActive" action="index.php/admin/api_pengumuman/email_send">
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
if($form=='logo'){
?>
<div id="wrapperFormApprove" class='form-horizontal'>
    <form method="post" id="ajaxFormActive" action="index.php/admin/api_pengumuman/save_api">
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Set Logo </label> 
                    <?= FormHelpPopOver('api_pengumuman_logo'); ?>
                    <input type='file' class="form-control" name='logo'  id='logo'>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Hapus Logo </label> 
                    <input type='checkbox' name='hapus_logo'  id='hapus_logo'>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Link Website </label> 
                    <?= FormHelpPopOver('api_pengumuman_link_web'); ?>
                    <input type='text' class="form-control" name='LINK_WEB'  id='LINK_WEB' value="<?php echo $item->LINK_WEB ?>">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
           
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="INST_SATKERKD" value="<?php echo $item->INST_SATKERKD;?>">
            <input type="hidden" name="act" value="dologo">
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