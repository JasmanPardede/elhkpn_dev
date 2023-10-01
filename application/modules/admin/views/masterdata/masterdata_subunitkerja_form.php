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
 * @package Views/subunitkerja
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal"> <?php //echo $urlSave; ?>
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
       <!-- <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('unit_kerja'); ?>
                <input required type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;  padding:6px 0px;" id='UNIT_KERJA' placeholder="Unit Kerja">
            </div>
            <div class="col-sm-1"></div>
        </div> -->
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Instansi <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('instansi'); ?>
                    <input type="text" id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;" />
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="form-group">
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
                <label>Nama Sub Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('sub_unit_kerja'); ?>
                <input type='text' class="form-control" name='SUK_NAMA' size='40' id='SUK_NAMA' value='' required <?= FormHelpPlaceholderToolTip('sub_unit_kerja') ?> placeholder="Sub Unit Kerja">
            </div>
            <div class="col-sm-1"></div>
        </div>
       <div class="form-group">
                <div class="col-sm-1"></div>                
                <div class="col-sm-10">
                    <label> Level </label>
                    <select name='LEVEL' id='LEVEL' class='form-control'>
                        <option value='1'>Pusat</option>
                        <option value='2'>Daerah Tingkat I</option>
                        <option value='3'>Daerah Tingkat II</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div id="area" style="display:none;">
            <div class="form-group">
                <div class="col-sm-1"></div>      
                <div class="col-sm-10">
                    <label>Provinsi :</label>
                    <input type="text" id="ID_PROV" style="border:none;width:100%;" name="ID_PROV" placeholder="provinsi" value="">
                </div>
                <div class="col-sm-1"></div>
            </div>
            </div>
            <div id="areakab" style="display:none;">
            <div class="form-group">
                <div class="col-sm-1"></div>       
                
                <div class="col-sm-10">
                    <label>Kabupaten :</label>
                    <input type="text" id="ID_KAB" style="border:none;width:100%;" name="ID_KAB" placeholder="kabupaten" value="">
                </div>
                <div class="col-sm-1"></div>       
            </div>
            </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', '', sumbitAjaxFormCari);
        $('#ID_PROV').select2({
                        minimumInputLength: 0,
                        ajax: {                             
                            url: "<?= base_url('index.php/share/reff/get_propinsi') ?>",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function(term, page) {
                                return {
                                    q: term
                                };
                            },
                            results: function(data, page) {
                                return {results: data.item};
                            },
                            cache: true
                        },
                        initSelection: function(element, callback) {
                            var id = $(element).val();
                            if (id !== "") {
                                $.ajax("<?= base_url('index.php/share/reff/get_propinsi') ?>/" + id, {
                                    dataType: "json"
                                }).done(function(data) {
                                    callback(data[0]);
                                });
                            }
                        },
                        formatResult: function(state) {
                            return state.name;
                        },
                        formatSelection: function(state) {
                            return state.name;
                        }
                    });
                    
                    
                    var valProv = $('#ID_PROV').val();
                    var valKab = $('#ID_KAB').val();
                    PROP = valProv;
                    $('#ID_KAB').select2({
                        minimumInputLength: 0,
                        ajax: {
                            url: "<?= base_url('index.php/share/reff/getKab') ?>/" + PROP,
                            //url: "<?= base_url('index.php/share/reff/getKab') ?>" ,
                            dataType: 'json',
                            quietMillis: 250,
                            data: function(term, page) {
                                var propid = $('#ID_PROV').val();
                                return {
                                    q: term,
                                    prop: propid
                                };
                            },
                            results: function(data, page) {
                                return {results: data.item};
                            },
                            cache: true
                        },
                        initSelection: function(element, callback) {
                            var id = $(element).val();
                            if (id !== "") {
                                $.ajax("<?= base_url('index.php/share/reff/getKab') ?>/" + PROP + '/' + id, {
                                //$.ajax("<?= base_url('index.php/share/reff/getKab') ?>/"  + id, {
                                    dataType: "json"
                                }).done(function(data) {
                                    callback(data[0]);
                                });
                            }
                        },
                        formatResult: function(state) {
                            return state.name;
                        },
                        formatSelection: function(state) {
                            return state.name;
                        }
                    });
                    
              $("#LEVEL").change(function(){
                if($(this).val()=="1"){    
                    $("#area").hide();
                    $("#areakab").hide();
                }if($(this).val()=="2"){    
                    $("#area").show();
                    $("#areakab").hide();
                }if($(this).val()=="3"){
                    $("#area").show();
                    $("#areakab").show();
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
<div id="wrapperFormEdit" class="form-horizontal">
    <form method="post" id="ajaxFormEdit" action="<?php echo $urlSave;?>">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Instansi <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('instansi'); ?>
                <input required type='text' class="form-control form-select2" name='INST_TUJUAN' style="border:none;  padding:6px 0px;" id='INST_TUJUAN' value='<?php echo $item->UK_LEMBAGA_ID; ?>' placeholder="Instansi">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('unit_kerja'); ?>
                <input required type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;  padding:6px 0px;" id='UNIT_KERJA' value='<?php echo $item->UK_ID; ?>' placeholder="Unit Kerja">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Nama Sub Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('sub_unit_kerja'); ?>
                <input type='text' class="form-control" name='SUK_NAMA' size='40' id='SUK_NAMA' value='<?php echo $item->SUK_NAMA;?>' required <?= FormHelpPlaceholderToolTip('sub_unit_kerja') ?> placeholder="Sub Unit Kerja">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Level :</label>
                <select name='LEVEL' id='LEVEL' class='form-control' >
                    <option value="">-Pilih Level-</option>
                    <option value='1' <?php echo $item->LEVEL == 1 ? 'selected' : '';?>>Pusat</option>
                    <option value='2' <?php echo $item->LEVEL == 2 ? 'selected' : '';?>>Daerah Tingkat I</option>
                    <option value='3' <?php echo $item->LEVEL == 3 ? 'selected' : '';?>>Daerah Tingkat II</option>
                </select>
            </div>
            <div class="col-sm-1"></div>
        </div> 
        <?php if($item->LEVEL == "2"){ ?>
            <div id="area" style="display:show;">
                <div class="form-group">
                    <div class="col-sm-1"></div>                    
                    <div class="col-sm-10">
                        <label>Provinsi :</label>
                        <input type="text" id="ID_PROV" style="border:none;width:100%;" name="ID_PROV" placeholder="provinsi" value="<?php echo $item->ID_PROV; ?>" >
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>
            
            <?php }elseif($item->LEVEL == "3"){ ?>
            <div id="area" style="display:show;">
                <div class="form-group">
                    <div class="col-sm-1"></div>                
                    <div class="col-sm-10">
                        <label>Area :</label>
                        <input type="text" id="ID_PROV" style="border:none;width:100%;" name="ID_PROV" placeholder="provinsi" value="<?php echo $item->ID_PROV; ?>" >
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>
            <div id="areakab" style="display:show;">
                <div class="form-group">
                    <div class="col-sm-1"></div>                
                <div class="col-sm-10">
                    <label>Kabupaten :</label>
                    <input type="text" id="ID_KAB" style="border:none;width:100%;" name="ID_KAB" placeholder="kabupaten" value="<?php echo $item->ID_KAB; ?>">
                </div>
                <div class="col-sm-1"></div>
                </div>
            </div>
            <?php }else{ ?>
            <div id="area" style="display:none;">
            <div class="form-group">
                <div class="col-sm-1"></div>                
                <div class="col-sm-10">
                    <label>Area :</label>
                    <input type="text" id="ID_PROV" style="border:none;width:100%;" name="ID_PROV" placeholder="provinsi" value="">
                </div>
                <div class="col-sm-1"></div>
            </div>
            </div>
            <div id="areakab" style="display:none;">
                <div class="form-group">
                    <div class="col-sm-1"></div>                
                    <div class="col-sm-10">
                        <label>Kabupaten :</label>
                        <input type="text" id="ID_KAB" style="border:none;width:100%;" name="ID_KAB" placeholder="kabupaten" value="<?php echo $item->ID_KAB; ?>">
                    </div>
                    <div class="col-sm-1"></div> 
                </div>
            </div>
            <?php } ?>
        <div class="pull-right">
            <input type="hidden" name="SUK_ID" value="<?php echo $item->SUK_ID;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', '', sumbitAjaxFormCari);     
        
        $('#ID_PROV').select2({
                        minimumInputLength: 0,
                        ajax: {
                            url: "<?= base_url('index.php/share/reff/get_propinsi') ?>",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function(term, page) {
                                return {
                                    q: term
                                };
                            },
                            results: function(data, page) {
                                return {results: data.item};
                            },
                            cache: true
                        },
                        initSelection: function(element, callback) {
                            var id = $(element).val();
                            if (id !== "") {
                                $.ajax("<?= base_url('index.php/share/reff/get_propinsi') ?>/" + id, {
                                    dataType: "json"
                                }).done(function(data) {
                                    callback(data[0]);
                                });
                            }
                        },
                        formatResult: function(state) {
                            return state.name;
                        },
                        formatSelection: function(state) {
                            return state.name;
                        }
                    });
              
                var valProv = $('#ID_PROV').val();
                var valKab = $('#ID_KAB').val();
                PROP = valProv;
              $('#ID_KAB').select2({
                        minimumInputLength: 0,
                        ajax: {
                            url: "<?= base_url('index.php/share/reff/getKab') ?>/" + PROP,
                            //url: "<?= base_url('index.php/share/reff/getKab') ?>",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function(term, page) {
                                var propid = $('#ID_PROV').val();
                                return {
                                    q: term,
                                    prop: propid
                                };
                            },
                            results: function(data, page) {
                                return {results: data.item};
                            },
                            cache: true
                        },
                        initSelection: function(element, callback) {
                            var id = $(element).val();
                            if (id !== "") {
                                $.ajax("<?= base_url('index.php/share/reff/getKab') ?>/" + PROP + '/'  + id, {
                                //$.ajax("<?= base_url('index.php/share/reff/getKab') ?>/"  + id, {
                                    dataType: "json"
                                }).done(function(data) {
                                    callback(data[0]);
                                });
                            }
                        },
                        formatResult: function(state) {
                            return state.name;
                        },
                        formatSelection: function(state) {
                            return state.name;
                        }
                    });

              $("#LEVEL").change(function(){
                if($(this).val()=="1"){    
                    $("#area").hide();
                    $("#areakab").hide();
                }if($(this).val()=="2"){    
                    $("#area").show();
                    $("#areakab").hide();
                }if($(this).val()=="3"){
                    $("#area").show();
                    $("#areakab").show();
                }
            });
        
    });
</script>
<?php
}
?>

<?php
if($form=='kembalikan'){
?>
<div id="wrapperFormKembalikan" class="form-horizontal">
    Benarkah Akan Mengaktifkan kembali data Sub Unit Kerja dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Sub Unit Kerja :</label>
                <label class="col-sm-8">
                    <?php echo $item->SUK_NAMA;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="SUK_ID" value="<?php echo $item->SUK_ID;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Kembalikan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormKembalikan"), 'kembalikan', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus Sub Unit Kerja dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <!-- <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Unit Kerja<span class="red-label">*</span> :</label>
                <div class="col-sm-9">
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none; padding:6px 0px;" id='UNIT_KERJA' value='<?= @$item->UNIT_KERJA; ?>' placeholder="Unit Kerja">
                </div>
            </div>
        </div> -->
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Nama Sub Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('sub_unit_kerja'); ?>
                <input type='text' class="form-control" name='SUK_NAMA' size='40' id='SUK_NAMA' value='<?php echo $item->SUK_NAMA;?>' required <?= FormHelpPlaceholderToolTip('sub_unit_kerja') ?> placeholder="Sub Unit Kerja" readonly="readonly">
            </div>
            <div class="col-sm-1"></div>
        </div>        
        <div class="pull-right">
            <input type="hidden" name="SUK_ID" value="<?php echo $item->SUK_ID;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Hapus</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail" class="form-horizontal">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Unit Kerja<span class="red-label">*</span> :</label>
                <div class="col-sm-9">
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none; padding:6px 0px;" id='UNIT_KERJA' value='<?= $item->UNIT_KERJA; ?>'>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Nama Sub Unit Kerja : </label>
                <label class="col-sm-9">
                    <?php echo $item->SUK_NAMA;?>
                </label>
            </div>            
        </div> 
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
    </div>
</div>
<?php
}
?>
<script type="text/javascript">
     $(function() {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
    })

</script>

<script type="text/javascript">
    $(document).ready(function() {
//        ng.formProcess($("#ajaxFormAdd"), 'insert', window.location.href.split('#')[1]);
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

<?php
if($form=='edit'){
?>
         
$('input[name="UNIT_KERJA"]').select2({
            // var lembid = $('#INST_TUJUAN').val();
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+ <?php echo $item->UK_LEMBAGA_ID; ?>,
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+<?php echo $item->UK_LEMBAGA_ID; ?>+'/'+id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        });
<?php
    }
?>
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