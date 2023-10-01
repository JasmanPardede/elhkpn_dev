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
        <div class="row">
            <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Instansi <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('instansi'); ?>
                    <input type="text" id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;" required/>  
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('unit_kerja'); ?>
                <input type="text" id="UNIT_KERJA" name="UNIT_KERJA" class="select" style="width: 100%;" required/>
            </div>
            <div class="col-sm-1"></div>
        </div>
         <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Sub Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('sub_unit_kerja'); ?>
                <input type="text" id="SUB_UNIT_KERJA" name="SUB_UNIT_KERJA" class="select" style="width: 100%;" required/>
            </div>
            <div class="col-sm-1"></div>
        </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Kode Eselon <font color='red'>*</font>:</label>
                    <select name="KODE_ESELON" id="" class="form-control" required>
                        <option value="">-- Pilih Eselon --</option>
                        <?php foreach ($eselon as $item): ?>
                            <option value="<?= @$item->ID_ESELON; ?>"><?= @$item->ESELON; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                 <div class="col-sm-1"></div>
            </div>
             <!--   <div class="form-group">
                <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label >Kode Eselon <font color='red'>*</font>:</label>
                    <input required type='text' class="form-control form-select2" name='ID_ESELON' style="border:none;  padding:6px 0px;" id='ID_ESELON' placeholder="Select Eselon">
                </div>
                <div class="col-sm-1"></div>
            </div> -->
            <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Nama Jabatan <span class="red-label">*</span>:</label>
                <textarea rows="2" style="width: 100%;" name='NAMA_JABATAN' id='NAMA_JABATAN' value='' required></textarea>
                    <!--<input type='text' style="width: 100%;" name='NAMA_JABATAN' id='NAMA_JABATAN' value='' required>-->
                </div>
            <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
            <div class="col-sm-1"></div>
                <div class="col-sm-10">
                <label>UU<span class="red-label">*</span>:</label>
                <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input name="UU" value="0" type="radio">
                                NonUU
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input name="UU" value="1" checked="" type="radio">
                                UU
                            </label>
                        </div>
                    </div>
                </div>
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
    // $(document).ready(function() {
    //     ng.formProcess($("#ajaxFormAdd"), 'insert', '', sumbitAjaxFormCari);
    // });
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
                <input required readonly="" type='text' class="form-control form-select2" name='INST_TUJUAN' style="border:none;  padding:6px 0px;" id='INST_TUJUAN' value='<?php echo $item->UK_LEMBAGA_ID; ?>' placeholder="Instansi">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('unit_kerja'); ?>
                <input  type='text' readonly="" class="form-control form-select2" name='UNIT_KERJA' style="border:none;  padding:6px 0px;" id='UNIT_KERJA' value='<?php echo $item->UK_NAMA; ?>' placeholder="Unit Kerja">
                <!--<input  type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;  padding:6px 0px;" id='UNIT_KERJA' value="<?= @$item->UK_ID; ?>" placeholder="Unit Kerja">-->
                
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Sub Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('sub_unit_kerja'); ?>
                <input readonly="" type="text" id="SUB_UNIT_KERJA" name="SUB_UNIT_KERJA" class="form-control form-select2" style="width: 100%;" value='<?php echo $item->SUK_NAMA; ?>' />
                <!--<input type="text" id="SUB_UNIT_KERJA" name="SUB_UNIT_KERJA" class="select" style="width: 100%;" value="<?= @$item->SUK_ID; ?>" />-->
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
                <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Kode Eselon <font color='red'>*</font>:</label>
                    <select name="KODE_ESELON" id="" class="form-control form-select2" >
                             <?php foreach ($eselon as $row): ?>
                            <option <?= ($item->KODE_ESELON == $row->ID_ESELON)  ?  'SELECTED' : ''?> value="<?= @$row->ID_ESELON; ?>"><?= @$row->ESELON; ?></option>
                        <?php endforeach ?>
                             
                    </select>
                </div>
                 <div class="col-sm-1"></div>
            </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Nama Jabatan <span class="red-label">*</span>:</label>
                <textarea rows="2" style="width: 100%;" name='NAMA_JABATAN' id='NAMA_JABATAN' value='<?php echo $item->NAMA_JABATAN?>' required><?php echo $item->NAMA_JABATAN;?></textarea>
                    <!--<input type='text' style="width: 100%;" name='NAMA_JABATAN' id='NAMA_JABATAN' value='<?php echo $item->NAMA_JABATAN?>' required>-->
                </div>
            <div class="col-sm-1"></div>
            </div>
       
        <div class="form-group">
            <div class="col-sm-1"></div>
                <div class="col-sm-10">
                <label>UU<span class="red-label">*</span>:</label>
                <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    
                        <div class="radio">
                            <label>
                                <input type="radio" name="UU" value="0" <?php echo ($item->IS_UU == '0' ? 'checked' : ''); ?>>
                                NonUU
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="UU" value="1" <?php echo ($item->IS_UU == '1' ? 'checked' : ''); ?>>
                                UU
                            </label>
                        </div>
                    </div>
                </div>
                </div>
            <div class="col-sm-1"></div>
            </div>
            <div class="pull-right">
            <input type="hidden" name="ID_JABATAN" value="<?php echo $item->ID_JABATAN;?>">
            <input type="hidden" name="UK_ID" value="<?php echo $item->UK_ID;?>">
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
    });
</script>

<?php
}
?>

<?php
if($form=='kembalikan'){
?>
<div id="wrapperFormKembalikan" class="form-horizontal">
    Benarkah Akan Mengaktifkan kembali data Jabatan dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Jabatan :</label>
                <label class="col-sm-8">
                    <?php echo $item->NAMA_JABATAN;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_JABATAN" value="<?php echo $item->ID_JABATAN;?>">
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
    Benarkah Akan Menghapus Jabatan dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-3" style="text-align:right !important;">Nama Jabatan : </label>
                <label class="col-sm-9">
                    <?php echo $item->NAMA_JABATAN;?>
                </label>
            </div> 
        </div>         
        <div class="pull-right">
            <input type="hidden" name="ID_JABATAN" value="<?php echo $item->ID_JABATAN;?>">
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
                <label class="col-sm-3" style="text-align:right !important;">Nama Jabatan : </label>
                <label class="col-sm-9">
                    <?php echo $item->NAMA_JABATAN;?>
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
                url: "<?=base_url('index.php/efill/lhkpn/getLembaga')?>",
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
                    $.ajax("<?=base_url('index.php/efill/lhkpn/getLembaga')?>/"+id, {
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

                $('#UNIT_KERJA').change(function(event) {
            UNIT_KERJA = $(this).val();

            $('input[name="SUB_UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
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
                        $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
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