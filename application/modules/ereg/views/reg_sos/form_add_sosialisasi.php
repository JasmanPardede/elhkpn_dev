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
$INSATNSI = array();
foreach ($instansis as $instansi) {
    $INSATNSI[$instansi->INST_SATKERKD]['INST_NAMA'] = $instansi->INST_NAMA;
}
if($form=='add'){
?>
<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormAdd" action="index.php/ereg/reg_sos/save_sosialisasi" enctype="multipart/form-data">
        <div class="box-body">
            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Instansi <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('instansi_admin_instansi'); ?>
                    <input required type='text' class="form-control form-select2" name='INST_SATKERKD' style="border:none;  padding:0px 0px;" id='INST_SATKERKD' placeholder="Pilih Instansi">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <?php endif; ?>
            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3 || $this->session->userdata('ID_ROLE') == 31): ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Unit Kerja <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('unit_kerja_pnwl'); ?>
                    <input type='text' class="form-control form-select2" name='UK_ID' style="border:none;  padding:0px 0px;" id='UK_ID' placeholder="Pilih Unit Kerja">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Bimtek <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('bimtek'); ?>
                    <select class="form-control" name='bimtek' id='bimtek' placeholder="Pilih Bimtek" required <?= FormHelpPlaceholderToolTip('bimtek') ?>>
                        <option value=""></option>
                        <option value="1">e-Filling</option>
                        <option value="2">e-Registration</option>
                        <option value="3">Regulasi</option>
                        <option value="4">Rakor Monev</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Tempat <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('tempat'); ?>
                    <textarea class="form-control" name="tempat" id='tempat' value='' required <?= FormHelpPlaceholderToolTip('tempat') ?>></textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Tanggal <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('tanggal'); ?>
                    <input type='text' class="form-control date-picker" name='tanggal' id='tanggal' value=''  onkeydown="return false" autocomplete="off" placeholder='DD/MM/YYYY' required <?= FormHelpPlaceholderToolTip('tanggal') ?>>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Waktu Pelaksanaan <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('waktu_pelaksanaan'); ?>
                    <textarea class="form-control" name="waktu_pelaksanaan" id='waktu_pelaksanaan' value='' required <?= FormHelpPlaceholderToolTip('waktu_pelaksanaan') ?>></textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Pelaksana <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('pelaksana'); ?>
                    <textarea class="form-control" name="pelaksana" id='pelaksana' value='' required <?= FormHelpPlaceholderToolTip('pelaksana') ?>></textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Jumlah Peserta <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('jumlah_peserta'); ?>
                    <textarea class="form-control" name="jumlah_peserta" id='jumlah_peserta' value='' required <?= FormHelpPlaceholderToolTip('jumlah_peserta') ?>></textarea>
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
        $('#tanggal').datepicker({
            maxViewMode: "-17 years",
            format: "dd/mm/yyyy",
            autoclose: true
        });
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormAdd"), 'insert', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormAdd').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });



        $('#bimtek').select2();

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
if($form=='edit'){
?>
<div id="wrapperFormEdit" class='form-horizontal'>
    <form method="post" id="ajaxFormEdit" action="index.php/ereg/reg_sos/save_sosialisasi">
        <div class="box-body">
            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
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
            <?php endif; ?>
            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3 || $this->session->userdata('ID_ROLE') == 31): ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Unit Kerja <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('UK_ID'); ?>
                    <input type='text' class="form-control form-select2" name='UK_ID' style="border:none;  padding:6px 0px;" id='UK_ID' placeholder="Pilih Unit Kerja">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Bimtek <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('bimtek'); ?>
                    <select class="form-control" name='bimtek' id='bimtek' placeholder="Pilih Bimtek" required <?= FormHelpPlaceholderToolTip('bimtek') ?>>
                        <option value=""></option>
                        <option value="1">e-Filling</option>
                        <option value="2">e-Registration</option>
                        <option value="3">Regulasi</option>
                        <option value="4">Rakor Monev</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Tempat <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('tempat'); ?>
                    <textarea class="form-control" name="tempat" id='tempat' required <?= FormHelpPlaceholderToolTip('tempat') ?>><?php echo html_to_textarea($item->TEMPAT); ?></textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Tanggal <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('tanggal'); ?>
                    <input type='text' class="form-control date-picker" name='tanggal' id='tanggal'  value='<?php echo show_date_with_format($item->TANGGAL,'d/m/Y','Y-m-d'); ?>'  onkeydown="return false" autocomplete="off" placeholder='DD/MM/YYYY' required <?= FormHelpPlaceholderToolTip('tanggal') ?>>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Waktu Pelaksanaan <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('waktu_pelaksanaan'); ?>
                    <textarea class="form-control" name="waktu_pelaksanaan" id='waktu_pelaksanaan' required <?= FormHelpPlaceholderToolTip('waktu_pelaksanaan') ?>><?php echo html_to_textarea($item->WAKTU_PELAKSANAAN); ?></textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Pelaksana <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('pelaksana'); ?>
                    <textarea class="form-control" name="pelaksana" id='pelaksana' required <?= FormHelpPlaceholderToolTip('pelaksana') ?>><?php echo html_to_textarea($item->PELAKSANA); ?></textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Jumlah Peserta <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('jumlah_peserta'); ?>
                    <textarea class="form-control" name="jumlah_peserta" id='jumlah_peserta'  required <?= FormHelpPlaceholderToolTip('jumlah_peserta') ?>><?php echo html_to_textarea($item->JUMLAH_PESERTA); ?></textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
            
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_SOSIALISASI" value="<?php echo $item->ID_SOSIALISASI;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        
    </form>
</div>
<script type="text/javascript">
     $(function() {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
    });    
    $(document).ready(function() {
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormEdit"), 'update', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormEdit').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });
        $('#tanggal').datepicker({
            maxViewMode: "-17 years",
            format: "dd/mm/yyyy",
            autoclose: true
        });

        <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31){ ?>
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
                }).on("change", function (e) {

                    var ID_INST = $('#INST_SATKERKD').val();
                    GetUK(ID_INST);
                });    
                var inst_satkerkd_id = '<?php echo $item->INST_SATKERKD; ?>';
                $('#INST_SATKERKD').val(inst_satkerkd_id).trigger('change');  
        <?php }else{ ?>
            var ID_INST = <?php echo $this->session->userdata('INST_SATKERKD'); ?>;
            GetUK(ID_INST);
        <?php } ?>

        var uk_id = '<?php echo $item->UK_ID; ?>';
        var bimtek_val = '<?php echo $item->BIMTEK; ?>';
        $('#UK_ID').select2("data", {id: uk_id, name: '<?php echo $uk_nama ?>'});   
        $('#bimtek').val(bimtek_val).trigger('change');  
    });


function GetUK(ID_INST){
    $('#UK_ID').select2({
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
if($form=='delete'){
?>
<div id="wrapperFormDelete" class='form-horizontal'>
    Apakah anda yakin akan menghapus data ?
    <form method="post" id="ajaxFormDelete" action="index.php/ereg/reg_sos/save_sosialisasi">
        <div class="box-body">
            
                        
            <div class="form-group">
            <br>
            </div>
           
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_SOSIALISASI" value="<?php echo $item->ID_SOSIALISASI;?>">
            <input type="hidden" name="act" value="dodelete">
           <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Hapus</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        //ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormDelete"), 'delete', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormDelete').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });
        
    });
</script>

<?php
}
?>
<?php
if($form=='approve'){
?>
<div id="wrapperFormApprove" class='form-horizontal'>
    <?php if($item->STATUS==0){ ?>
    Apakah anda yakin melakukan validasi Sosialisasi?
    <?php }else{ ?>
    Apakah anda yakin membatalkan validasi Sosialisasi?
    <?php } ?>
    <form method="post" id="ajaxFormActive" action="index.php/ereg/reg_sos/save_sosialisasi">
        <div class="box-body">
            
                        
            <div class="form-group">
            <br>
            </div>
           
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_SOSIALISASI" value="<?php echo $item->ID_SOSIALISASI;?>">
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