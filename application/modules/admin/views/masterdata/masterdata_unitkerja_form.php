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
 * @package Views/instansi
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label">Bidang <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name="UK_BIDANG_ID" id="" class="form-control" >
                        <option value="">-Pilih Bidang-</option>
                        <?php foreach ($bidang as $bdg): ?>
                            <option value="<?= @$bdg->BDG_ID; ?>"><?= @$bdg->BDG_NAMA; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div> -->
            <div class="form-group">
                <label class="col-sm-4 control-label">Instansi <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input required type='text' class="form-control form-select2" name='UK_LEMBAGA_ID' style="border:none;  padding:6px 0px; width: 100%" id='UK_LEMBAGA_ID' placeholder="Select Instansi">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Unit Kerja <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='UK_NAMA' id='UK_NAMA' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Level :</label>
                <div class="col-sm-8">
                    <select name='INST_LEVEL' id='INST_LEVEL' class='form-control'>
                        <option value='1'>Pusat</option>
                        <option value='2'>Daerah Tingkat I</option>
                        <option value='3'>Daerah Tingkat II</option>
                    </select>
                </div>
            </div>
            <div id="area" style="display:none;">
            <div class="form-group">
                <label class="col-sm-4 control-label">Provinsi :</label>
                <div class="col-sm-8">
                    <input type="text" id="ID_PROV" style="border:none;width:300px;" name="ID_PROV" placeholder="provinsi" value="">
                </div>
            </div>
            </div>
            <div id="areakab" style="display:none;">
            <div class="form-group">
                <label class="col-sm-4 control-label">Kabupaten :</label>
                <div class="col-sm-8">
                    <input type="text" id="ID_KAB" style="border:none;width:300px;" name="ID_KAB" placeholder="kabupaten" value="">
                </div>
            </div>
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
                    
              $("#INST_LEVEL").change(function(){
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
        <div class="box-body">
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label">Bidang <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name="UK_BIDANG_ID" id="" class="form-control" >
                        <option value="">-Pilih Bidang-</option>
                        <?php foreach ($bidang as $bdg): ?>
                            <option <?= ($item->UK_BIDANG_ID == $bdg->BDG_ID ? 'selected' : ''); ?> value="<?= @$bdg->BDG_ID; ?>"><?= @$bdg->BDG_NAMA; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div> -->
            <div class="form-group">
                <label class="col-sm-4 control-label">Instansi <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input value="<?= @$item->UK_LEMBAGA_ID; ?>" required type='text' class="form-control form-select2" name='UK_LEMBAGA_ID' style="border:none;  padding:6px 0px;" id='UK_LEMBAGA_ID' placeholder="Select Instans">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Unit Kerja <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='UK_NAMA' id='UK_NAMA' value='<?= @$item->UK_NAMA; ?>' required>
                </div>
            </div>
<!--            <div class="form-group">
                <label class="col-sm-4 control-label">Status<font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name='UK_STATUS' id='UK_STATUS' class='form-control' required>
                        <option value='0' <?php echo $item->UK_STATUS == 0 ? 'selected' : '';?>>Nonactive</option>
                        <option value='1' <?php echo $item->UK_STATUS == 1 ? 'selected' : '';?>>Active</option>
                    </select>
                </div>
            </div>-->
            <div class="form-group">
                <label class="col-sm-4 control-label">Level :</label>
                <div class="col-sm-8">
                    <select name='INST_LEVEL' id='INST_LEVEL' class='form-control' >
                        <option value="">-Pilih Level-</option>
                        <option value='1' <?php echo $item->LEVEL == 1 ? 'selected' : '';?>>Pusat</option>
                        <option value='2' <?php echo $item->LEVEL == 2 ? 'selected' : '';?>>Daerah Tingkat I</option>
                        <option value='3' <?php echo $item->LEVEL == 3 ? 'selected' : '';?>>Daerah Tingkat II</option>
                    </select>
                </div>
            </div> 
            <?php if($item->LEVEL == "2"){ ?>
            <div id="area" style="display:show;">
                <div class="form-group">
                <label class="col-sm-4 control-label">Provinsi :</label>
                <div class="col-sm-8">
                    <input type="text" id="ID_PROV" style="border:none;width:300px;" name="ID_PROV" placeholder="provinsi" value="<?php echo $item->ID_PROV; ?>" >
                </div>
                </div>
            </div>
            
            <?php }elseif($item->LEVEL == "3"){ ?>
            <div id="area" style="display:show;">
                <div class="form-group">
                <label class="col-sm-4 control-label">Area :</label>
                <div class="col-sm-8">
                    <input type="text" id="ID_PROV" style="border:none;width:300px;" name="ID_PROV" placeholder="provinsi" value="<?php echo $item->ID_PROV; ?>" >
                </div>
                </div>
            </div>
            <div id="areakab" style="display:show;">
                <div class="form-group">
                <label class="col-sm-4 control-label">Kabupaten :</label>
                <div class="col-sm-8">
                    <input type="text" id="ID_KAB" style="border:none;width:300px;" name="ID_KAB" placeholder="kabupaten" value="<?php echo $item->ID_KAB; ?>">
                </div>
                </div>
            </div>
            <?php }else{ ?>
            <div id="area" style="display:none;">
            <div class="form-group">
                <label class="col-sm-4 control-label">Area :</label>
                <div class="col-sm-8">
                    <input type="text" id="ID_PROV" style="border:none;width:300px;" name="ID_PROV" placeholder="provinsi" value="">
                </div>
            </div>
            </div>
            <div id="areakab" style="display:none;">
                <div class="form-group">
                <label class="col-sm-4 control-label">Kabupaten :</label>
                <div class="col-sm-8">
                    <input type="text" id="ID_KAB" style="border:none;width:300px;" name="ID_KAB" placeholder="kabupaten" value="<?php echo $item->ID_KAB; ?>">
                </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="pull-right">
            <input type="hidden" name="UK_ID" value="<?php echo $item->UK_ID;?>">
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

              $("#INST_LEVEL").change(function(){
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
    Benarkah Akan Mengaktifkan kembali data Unit Kerja dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Unit Kerja :</label>
                <label class="col-sm-8">
                    <?php echo $item->UK_NAMA;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="UK_ID" value="<?php echo $item->UK_ID;?>">
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
    Benarkah Akan Menghapus Unit Kerja dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Unit Kerja :</label>
                <label class="col-sm-8">
                    <?php echo $item->UK_NAMA;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="UK_ID" value="<?php echo $item->UK_ID;?>">
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
        <!-- <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Bidang :</label>
            <label class="col-sm-8">
                <?php echo $item->BDG_NAMA;?>
            </label>
        </div> -->
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Instansi :</label>
            <label class="col-sm-8">
                <?php echo $item->INST_NAMA;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Unit Kerja :</label>
            <label class="col-sm-8">
                <?php echo $item->UK_NAMA;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Status :</label>
            <label class="col-sm-8">
                <?= ($item->UK_STATUS == 1 ? 'Active' : 'Nonactive');?>
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
    $(document).ready(function(){
        $('input[name="UK_LEMBAGA_ID"]').select2({
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