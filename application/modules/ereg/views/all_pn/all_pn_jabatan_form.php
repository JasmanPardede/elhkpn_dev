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
 * @package Views/all_pn
*/
?>
<style type="text/css">
    .form-select2 {
        padding: 6px 0px !important;
        margin: 0px !important;
    }
</style>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <!--  -->
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ereg/all_pn/savejabatan" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-sm-3 control-label">Lembaga<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" id='LEMBAGA' value='' placeholder="lembaga" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Deskripsi Jabatan<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="text" class="form-control" name="DESKRIPSI_JABATAN" value="" placeholder="Deskripsi Jabatan" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Eselon<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                        <option value='1'>I</option>
                        <option value='2'>II</option>
                        <option value='3'>III</option>
                        <option value='4'>IV</option>
                        <option value='5'>Non-Eselon</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <textarea class='form-control' name="ALAMAT_KANTOR" placeholder="Alamat Kantor"><?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR != '') { echo @json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR;}else{ echo '';}?></textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='email' class='form-control' value="<?=@json_decode(@$DATA_PRIBADI->JABATAN)->EMAIL_KANTOR?>" name="EMAIL_KANTOR" placeholder="Email Kantor">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">SK:</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="file" name="FILE_SK">
                </div>
                <span class='help-block'>Type File: xls, xlsx, doc, docx, pdf, jpg, jpeg, png .  Max File: 500KB</span>
            </div>
            
        </div> 
        <div class="form-group">
            <label class="col-sm-3 control-label">TMT/SD<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <div class="col-xs-3 col-sm-4 col-md-3 col-lg-3">
                        <input type="text" class="form-control datepicker" name="TMT" value="<?=date('d/m/Y')?>" placeholder='DD/MM/YYYY' required>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        s/d
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <input type="text" class="form-control datepicker" name="SD" value="" placeholder='DD/MM/YYYY' required>
                    </div>
                </div>
            </div>
        </div>        
        <div class="pull-right">
            <input type="hidden" name="ID_PN" id="ID_PN" value="<?php echo $id_pn;?>">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default btn-batalAddJabatan" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
    <!--  -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var ID = $('#ID_PN').val();
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/ereg/all_pn/showTable/18/'+ID, block:'#block', container:$('#jabatan').find('.contentTab')});

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('input[name="LEMBAGA"]').select2({
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
        // var lembaga = $('input[name="LEMBAGA"]').val();
        // $('input[name="UNIT_KERJA"]').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?=base_url('index.php/ereg/all_pn/getUnitKerja')?>",
        //         dataType: 'json',
        //         quietMillis: 250,
        //         data: function (term, page) {
        //             return {
        //                 q: term
        //             };
        //         },
        //         results: function (data, page) {
        //             return { results: data.item };
        //         },
        //         cache: true
        //     },
        //     initSelection: function(element, callback) {
        //         var id = $(element).val();
        //         if (id !== "") {
        //             $.ajax("<?=base_url('index.php/ereg/all_pn/getUnitKerja')?>/"+id, {
        //                 dataType: "json"
        //             }).done(function(data) { callback(data[0]); });
        //         }
        //     },
        //     formatResult: function (state) {
        //         return state.name;
        //     },
        //     formatSelection:  function (state) {
        //         return state.name;
        //     }
        // });

        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getJabatan')?>",
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
                    $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>/"+id, {
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
        
        $('#LEMBAGA').change(function(event) {
            $('input[name="UNIT_KERJA"]').prop('disabled', false);
            $('input[name="JABATAN"]').prop('disabled', false);

            $('input[name="UNIT_KERJA"]').select2('val', '');
            $('input[name="JABATAN"]').select2('val', '');
            LEMBAGA = $(this).val();
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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



    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <!--  -->
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/ereg/all_pn/savejabatan" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-sm-3 control-label">Lembaga<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" id='LEMBAGA' value='<?php echo $item->LEMBAGA;?>' placeholder="lembaga" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='<?php echo $item->UNIT_KERJA;?>' placeholder="Unit Kerja" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="text" class="form-control" name="JABATAN" style="border:none;" value="<?php echo $item->JABATAN;?>" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Deskripsi Jabatan<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="text" class="form-control" name="DESKRIPSI_JABATAN" value="<?php echo $item->DESKRIPSI_JABATAN;?>" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Eselon<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                        <option value='1' <?php if($item->ESELON == '1'){ echo 'selected'; }?>>I</option>
                        <option value='2' <?php if($item->ESELON == '2'){ echo 'selected'; }?>>II</option>
                        <option value='3' <?php if($item->ESELON == '3'){ echo 'selected'; }?>>III</option>
                        <option value='4' <?php if($item->ESELON == '4'){ echo 'selected'; }?>>IV</option>
                        <option value='5' <?php if($item->ESELON == '5'){ echo 'selected'; }?>>Non-Eselon</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <textarea class='form-control' name="ALAMAT_KANTOR" placeholder="Alamat Kantor"><?php echo $item->ALAMAT_KANTOR;?></textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type='email' class='form-control' value="<?php echo $item->EMAIL_KANTOR;?>" name="EMAIL_KANTOR" placeholder="Email Kantor">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">SK:</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <input type="file" name="FILE_SK">
                    <input type='hidden' name="FILE_SK_OLD" value="<?php echo @$item->FILE_SK;?>" readonly>
                    <?php
                    if($item->FILE_SK){
                    ?>
                        <a href="<?php echo $item->FILE_SK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted($item->FILE_SK); ?></a>
                    <?php
                    }
                    ?>
                </div>
                <span class='help-block'>Type File: xls, xlsx, doc, docx, pdf, jpg, jpeg, png .  Max File: 500KB</span>
            </div>
            
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">TMT/SD<font color='red'>*</font> :</label>
            <div class="col-sm-9">
                <div class='col-sm-12'>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <input type="text" class="form-control datepicker" name="TMT" placeholder='DD/MM/YYYY' value="<?php echo date('d/m/Y', strtotime($item->TMT));?>" required>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        s/d
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <input type="text" class="form-control datepicker" name="SD" placeholder='DD/MM/YYYY' value="<?php echo date('d/m/Y', strtotime($item->SD));?>" required>
                    </div>
                </div>
            </div>
        </div>          
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="ID_PN" id="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default btn-batalAddJabatan" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
    <!--  -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var ID = $('#ID_PN').val();
        //ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);
        // ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/ereg/all_pn/showTable/18/'+ID, block:'#block', container:$('#jabatan').find('.contentTab')});

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

       $('input[name="LEMBAGA"]').select2({
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
        
        LEMBAGA = $('#LEMBAGA').val();
        $('input[name="UNIT_KERJA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
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
                    $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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

        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getJabatan')?>",
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
                    $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>/"+id, {
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

        $('#LEMBAGA').change(function(event) {
            LEMBAGA = $(this).val();
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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

    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>

<?php
}
?>

<?php
if($form=='kllJabatan'){
    // prevent baru create pn dan add jabatan ngak boleh close
    if($COUNT_JABATAN<1){
        ?>
        <script type="text/javascript">
        $(document).ready(function() {
            $('.close').hide();
            $('.btn-batalAddJabatan').hide();
            $('#myModal').on('hide.bs.modal', function(e) {
               e.preventDefault();
           });
        });
        </script>        
        <?php
    }
?>
<div id="wrapperKllJabatan">
    <i><b>" <?= @$PN->NIK.' - '.$PN->NAMA; ?> "</b></i>
    <br><br>
    <div class="testttttt">
        <?php if($IS_KPK==1 && $stts != 2){ ?>
        <!-- <button onclick="showaddkll();" value="<?= @$id_pn; ?>" type="button" href="javascript:void(0);" class="btn btn-sm btn-addJab btn-primary"><i class="fa fa-plus"></i> Tambah</button><br><br> -->
        <?php }else if($IS_KPK!=1 && $stts == 2){} ?>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">No</th>
                    <th>Jabatan / Eselon</th>
                    <th>Lembaga</th>
                    <th>Unit Kerja</th>
                    <!-- <th>TMT / SD</th> -->
                    <th>TMT</th>
                    <th>File SK</th>
                    <th>Aksi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $jml = 0;
                    $i   = 1; foreach ($pnJabatan as $jab):
                    if($jab->id_jabat == '0'){
                        $jml++;
                    }
                    // $eselon = '';
                    if ($jab->ESELON == 0) {
                        $esel = 'Non Eselon';
                    }
                    else
                    {
                        $esel = 'Eselon '.$jab->ESELON;
                    }
                    $calon = '';
                    if (@$jab->IS_CALON == 1) {
                        $calon = '<span class="label label-warning">Calon</span>';
                    }
                ?>
                    <tr>
                        <td><?= @$i++; ?></td>
                        <td><?php
                                $ID = @$jab->ID_JABATAN;
                                $ID_STATUS_AKHIR_JABAT = @$jab->id_jabat;
                                $STATUS = @$jab->STATUS;
                                $ID_PN_JABATAN = @$jab->ID_PN_JABATAN!='NULL' ? @$jab->ID_PN_JABATAN : null;
                                $LEMBAGA = @$jab->LEMBAGA;
                                $JABATAN = ucwords(strtolower(@$jab->NAMA_JABATAN.' /  '.$esel));
                                $TMT = @$jab->TMT;
                                $SD = @$jab->SD;
                                $IS_CALON = @$jab->IS_CALON;
                                $INST_TUJUAN = @$jab->INST_TUJUAN;

                                $out = '';
                                $out .= $JABATAN;
                                if($IS_CALON==1 && (($ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN==null)){// calon pn
                                    $pnposisi = 'calon';
                                }else if(( $ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN==null){
                                    // jabatan masih aktif & tidak dimutasikan
                                    $pnposisi = 'aktif';
                                }else{
                                    if($ID_PN_JABATAN!=null){// sedang mutasi
                                        $pnposisi = 'mutasi';
                                    }else{// jabatan sudah berakhir
                                        $pnposisi = 'berakhir';
                                    }
                                }

                                if($ID_STATUS_AKHIR_JABAT == '3'){
                                    $meninggal = TRUE;
                                }

                                
                            ?>
                            <?=$out?>
                            </td>
                        </td>
                        <td><?= @$jab->INST_NAMA; ?></td>
                        <td><?= @$jab->UK_NAMA; ?></td>
                        <!-- <td><?= @(@$jab->TMT != '' ? (@$jab->TMT != '1970-01-01' ? date('d/m/Y',strtotime(@$jab->TMT)) : '-') : '-').' - '.(@$jab->SD != '' ? (@$jab->SD != '1970-01-01' ? date('d/m/Y',strtotime(@$jab->SD)) : 'Sekarang') : 'Sekarang'); ?></td> -->
                        <td align="center"><?= @(@$jab->TMT != '' ? (@$jab->TMT != '1970-01-01' ? date('d/m/Y',strtotime(@$jab->TMT)) : '-') : '-'); ?></td>
                        <td><?php if($jab->FILE_SK != ''){ ?><a href="<?php echo base_url('uploads/data_jabatan/'.$PN->NIK.'/'.$jab->FILE_SK); ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.$PN->NIK.'/'.@$jab->FILE_SK); ?></a><?php } ?></td>
                        <td>
                            <?php
                            if ($jab->proses_mutasi > 0) {
                               ?>
                               <span class="label label-warning">sedang proses mutasi</span>
                               <?php
                            }
                            else
                            {
                                ?>
                                <?php if($IS_KPK==1){ ?>
                                <button type="button" class="btn btn-sm btn-default btn-edit" onclick="showEditKllJab('<?= @$jab->ID; ?>','<?= @$id_pn; ?>');" title="Edit"><i class="fa fa-pencil"></i></button>
                                <?php if($jml > '1'){ ?>
                                <button type="button" class="btn btn-sm btn-default btn-delete" onclick="showDeleteKllJab('<?= @$jab->ID; ?>','<?= @$id_pn; ?>');" title="Delete"><i class="fa fa-trash"  style="color:red;"></i></button>
                                <?php }
                                } ?>
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                                $out1 = '';
                                switch($pnposisi){
                                    case 'calon' : 
                                        $out1 = ' <span class="label label-warning">Calon</span> - '.date('d/m/Y', strtotime(@$jab->SD));
                                    break;
                                    case 'aktif' : 
                                    break;
                                    case 'mutasi' : 
                                        $out1 .= ' - <span class="label label-warning">sedang proses mutasi ke '.$INST_TUJUAN.'</span>';
                                    break;
                                    case 'berakhir' :
                                        switch (strtolower($STATUS)) {
                                             case 'mutasi':
                                                $labelstyle = 'label-primary';
                                                break;
                                             case 'promosi':
                                                $labelstyle = 'label-success';
                                                break;
                                             case 'non wl':
                                                $labelstyle = 'label-danger';
                                                break;
                                             default:
                                                $labelstyle = 'label-danger';
                                                break;
                                         }

                                        if($IS_CALON==1){
                                            $out1  = '<span class="label label-warning">Calon</span> ';
                                            $out1 .= '<span class="label '.$labelstyle.'">'.$STATUS.'</span> ';
                                            $out1 .= $JABATAN;
                                        }else{
                                            $out1 .= '<span class="label '.$labelstyle.'">'.$STATUS.'</span> - '.date('d/m/Y', strtotime(@$jab->SD));
                                        }
                                    break;
                                }

                                echo $out1;
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="pull-right">
            <input type="reset" class="btn btn-sm btn-default btn-batalAddJabatan" value="Tutup" onclick="CloseModalBox();">
        </div>
    </div>
    <div class="pnJabatan" data-id="1" style="display: none;">
        <form class="form-horizontal" id="saveklljabatan" action="javascript:;" class="ahaha" enctype="multipart/form-data">

            <div class="box-body form-horizontal">
                <div class="col-sm-12">
                    <input type="hidden" class="" name="PNID" id="PNID" style="" value="<?= @$id_pn; ?>" placeholder="">
                    <?php 
                        $role  = $this->session->userdata('ID_ROLE');
                        $exp   = explode(',', $role);
                        $cnt   = count($exp);
                        if($cnt > 1){
                            foreach ($exp as $val) {
                                if($val != 3 || $val != 4){
                                    if($uri == 'semuapn') {
                                        $text = "
                                            <div class='form-group'>
                                                <label class='col-sm-4 control-label'>Status Jabatan <span class='red-label'>*</span> :</label>
                                                <div class='col-sm-5'>
                                                    <label><input type='radio' name='IS_CALON' value='0' class='ubahCalon' required> Menjabat</label>
                                                </div>
                                            </div>
                                        ";
                                    } else {
                                        $text = "
                                            <div class='form-group'>
                                                <label class='col-sm-4 control-label'>Status Jabatan <span class='red-label'>*</span> :</label>
                                                <div class='col-sm-5'>
                                                    <label><input ". ($iscln == '1' ? 'checked' : '') ." type='radio' name='IS_CALON' value='1' class='ubahCalon' required> Calon</label>
                                                    <label><input type='radio' name='IS_CALON' value='0' class='ubahCalon' required> Menjabat</label>
                                                </div>
                                            </div>
                                        ";
                                    }
                                }else{
                                    $text = '';
                                }   
                            }
                        }else{
                            if($role != 3 && $role != 4){
                                if($uri == 'semuapn') {
                                    $text = "
                                        <div class='form-group'>
                                            <label class='col-sm-4 control-label'>Status Jabatan <span class='red-label'>*</span> :</label>
                                            <div class='col-sm-5'>
                                                <label><input type='radio' name='IS_CALON' value='0' class='ubahCalon' required> Menjabat</label>
                                            </div>
                                        </div>
                                    ";
                                } else {
                                    $text = "
                                        <div class='form-group'>
                                            <label class='col-sm-4 control-label'>Status Jabatan <span class='red-label'>*</span> :</label>
                                            <div class='col-sm-5'>
                                                <label><input ". ($iscln == '1' ? 'checked' : '') ." type='radio' name='IS_CALON' value='1' class='ubahCalon' required> Calon</label>
                                                <label><input type='radio' name='IS_CALON' value='0' class='ubahCalon' required> Menjabat</label>
                                            </div>
                                        </div>
                                    ";
                                }
                            }else{
                                $text = '';
                            }
                        }

                        echo $text;
                    ?>
                    

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Lembaga <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <input required <?php echo ($is_instansi != false ? 'value="'.$is_instansi.'" readonly="readonly"' : '') ?> type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" id='LEMBAGA' placeholder="lembaga">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Unit Kerja <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <input required type="text" class="form-control form-select2" name="UNIT_KERJA" style="border:none;" id='UNIT_KERJA' value="" placeholder="Unit Kerja">
<!--                             <select required name='UNIT_KERJA' id='UNIT_KERJA' style="border:0px;display:none;" class="form-control form-select2" placeholder="Unit Kerja">
                                <option value=""></option>
                            </select> -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Jabatan <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <input required type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Deskripsi Jabatan :</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="DESK_JABATAN" id="" style="" value="" placeholder="Deskripsi Jabatan">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">Eselon <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <select required name="ESELON" id="" class="form-control" >
                                <option value="">-- Pilih Eselon --</option>
                                <?php foreach ($eselon as $esl): ?>
                                    <option value="<?= @$esl->ID_ESELON; ?>"><?= @$esl->ESELON; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Alamat Kantor <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <textarea required name="ALAMAT_KANTOR" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Email Kantor :</label>
                        <div class="col-sm-5">
                            <input type='email' class='form-control' value="" name="EMAIL_KANTOR" placeholder="Email Kantor">
                        </div>
                    </div>

                    <div class="form-group sksk">
                        <label class="col-sm-4 control-label">SK :</label>
                        <div class="col-sm-5">
                            <input type="file" name="FILE_SK" id='FILE_SK' class="FILE_SK">
                            <span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpg, jpeg, png .  Max File: 500KB</span>
                        </div>
                        
                    </div> 

                    <div class="form-group tmtsd">
                        <label class="col-sm-4 control-label">TMT:</label>
                        <div class="col-sm-5">
                            <div class="col-md-5" style="margin-left: -14px;">
                                <input type="text" class="form-control datepicker TMT" name="TMT" value="<?php echo date('d/m/Y') ?>" placeholder='DD/MM/YYYY'>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="pull-right">
                <button id="btnsaveKJ" type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <?php if($id_pn != 'true'){ ?>
                <input type="button" class="btn btn-sm btn-default aa" value="Kembali" onclick="f_close(this);">
                <?php } ?>
                <input type="hidden" class="" name="act" id="" style="" value="doinsert">
            </div>
        </form>
    </div>
    <div class="pnJabatan" data-id="2" style="display: none;">
        <div id="ctnEditKJ"></div>
    </div>
    <div class="pnJabatan" data-id="3" style="display: none;">
        <div id="ctnDltKJ"></div>
    </div>
<script type="text/javascript">
    function showEditKllJab(param,pn){
        $('.testttttt').slideUp('slow', function(){
            $('div[data-id="2"]').slideDown('slow');
            var target = "index.php/ereg/all_pn/showEditKllJab/"+param+"/"+pn+"<?php echo isset($url) ? '/1' : '/0'?>"+"/<?=$uri?>";
            $('div[data-id="2"]').load(target);
        });
    }

    $(document).ready(function() {

        $('.FILE_SK').change(function(){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var arr     = ['xls','xlsx','doc','docx','pdf','jpg','png','jpeg'];
            var maxsize = 500000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_SK').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_SK').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });

        $('.ubahCalon').change(function(){
            var nil = $(this).val();
            // 1 = calon, 2 = menjabat
            if(nil == 1){
                $('.TMT').attr('required', false);
                $('.req').hide();
                $('.sd').attr('style','');
                // $('.FILE_SK').attr('required', false);
                $('.sksk').hide();
                $('.tmtsd').hide();
            }else{
                $('.TMT').attr('required', true);
                $('.req').show();
                $('.FILE_SK').attr('required', true);
                $('.sksk').show();
                $('.tmtsd').show();
            }
        });

        var ID = $('#ID_PN').val();

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('.btn-addJab').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Kelola Jabatan', html, '', 'standart');
            });            
            return false;
        });

        var val = $('#LEMBAGA').val();
        // if(val != ''){
        //     unit_kerjae();
        // }
    });

    function isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    // function unit_kerjae() {
    //     var IDLEM = $('#LEMBAGA').val();
    //     $("#UNIT_KERJA").empty();
    //     $.post("index.php/efill/lhkpn/daftar_UK/" + IDLEM, function(html) {
    //         $.each(html, function(index, value) {
    //             $("#UNIT_KERJA").append("<option value='" + index + "'>" + value + "</option>");
    //         });
    //         $("#UNIT_KERJA").show();
    //         $("#UNIT_KERJA").select2();
    //     }, 'json');
    // }

    function showaddkll () {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        var tex = $('#PNID').val();

        $("form#saveklljabatan").submit(function(event) {
            var urll = 'index.php/ereg/all_pn/saveklljabatan';
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function (html) {
                    msg = {
                       success : 'Data Berhasil Disimpan!',
                       error  : 'Data Gagal Disimpan!'
                    };
                    if (html == 0) {
                       alertify.error(msg.error);
                    } else if(html == 2){
                        msgg = {
                           error  : 'Tidak boleh lebih dari 5 data'
                        };
                       alertify.error(msgg.error);
                    }else{
                        alertify.success(msg.success);
                    }
                    if(html == 1 || isJson(html)){
                        f_close();

                        if(isJson(html) && html != 1){
                            html = jQuery.parseJSON(html);
                            tex = html.id;
                        }

                        var iii = "<?php echo isset($url) ? $url : 'index.php/ereg/all_pn/'?>";
                        $.get(iii,function(data){
                            ng.LoadAjaxContent(iii);

                            var uuu = "index.php/ereg/all_pn/addjabatan/"+tex;
                            $.get(uuu,function(data){
                                $('#modal-inner').html(data);
                            });
                        });
                    }else{
                        console.log('error');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        $('.testttttt').slideUp('slow', function(){
            $('div[data-id="1"]').slideDown('slow');
//            $('#PNID').val(tex);
        });

        $('input[name="LEMBAGA"]').select2({
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

        $('#LEMBAGA').change(function(event) {
            $('input[name="UNIT_KERJA"]').prop('disabled', false);
            $('input[name="JABATAN"]').prop('disabled', false);

            $('input[name="UNIT_KERJA"]').select2('val', '');
            $('input[name="JABATAN"]').select2('val', '');
            LEMBAGA = $(this).val();
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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
        }).on("change", function(e) {
            var lembaga = $(this).val();

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga+'/'+id, {
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
    }

    function showDeleteKllJab(param,pn){
        $('.testttttt').slideUp('slow', function(){
            $('div[data-id="3"]').slideDown('slow');
            var target = "index.php/ereg/all_pn/showDeleteKllJab/"+param+"/"+pn;
            $('div[data-id="3"]').load(target);
        });
    }

    function f_close()
    {
        $('.pnJabatan').slideUp('slow', function () {
            $('.testttttt').slideDown('slow');
        })
    }

</script>
</div>
<?php
}
?>

<?php if($form=='editKJ'){ ?>
    <form class="form-horizontal" method="post" id="editklljabatan" action="javascript:;" enctype="multipart/form-data">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <input type="hidden" class="" name="ID" style="" value="<?= @$item->ID; ?>" placeholder="">
                <?php if($uri != 'calonpn'){ ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Status <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                    <?php if($uri == 'semuapn') { ?>
                        <label><input class="ubahCalon" type="radio" name="IS_CALON" value="0" <?=$item->IS_CALON==0?'checked':'';?> required> Menjabat</label>
                    <?php } else if($uri == 'calonpn') { ?>
                        <label><input class="ubahCalon" type="radio" name="IS_CALON" value="1" <?=$item->IS_CALON==1?'checked':'';?> required> Calon</label>
                    <?php } else { ?>
                        <label><input class="ubahCalon" type="radio" name="IS_CALON" value="1" <?=$item->IS_CALON==1?'checked':'';?> required> Calon</label>
                        <label><input class="ubahCalon" type="radio" name="IS_CALON" value="0" <?=$item->IS_CALON==0?'checked':'';?> required> Menjabat</label>
                    <?php } ?>
                    </div>
                </div>
                <?php }else{ ?>
                    <input type="hidden" name="iscalon" value="1" />
                <?php } ?>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Lembaga <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" id='LEMBAGA' value='<?= @$item->LEMBAGA; ?>' placeholder="lembaga">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Unit Kerja <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input required type="text" class="form-control form-select2" name="UNIT_KERJA" style="border:none;" id='UNIT_KERJA' value="<?= @$item->UNIT_KERJA; ?>" placeholder="Unit Kerja">
<!--                         <select name='UNIT_KERJA' id='UNIT_KERJA222' style="border:0px;display:none;" class="form-control form-select2" placeholder="Unit Kerja">
                            <option value=""></option>
                        </select> -->
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Jabatan <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="<?= @$item->ID_JABATAN; ?>" placeholder="Jabatan">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Deskripsi Jabatan :</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="DESK_JABATAN" id="" style="" value="<?= @$item->DESKRIPSI_JABATAN; ?>" placeholder="Deskripsi Jabatan">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Eselon <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <select name="ESELON" id="" class="form-control" >
                            <option>-- Pilih Eselon --</option>
                            <?php foreach ($Eeselon as $eEsl): ?>
                                <option <?= (@$item->ESELON == @$eEsl->ID_ESELON ? 'selected' : ''); ?> value="<?= @$eEsl->ID_ESELON; ?>"><?= @$eEsl->ESELON; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Alamat Kantor <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <textarea name="ALAMAT_KANTOR" class="form-control"><?= @$item->ALAMAT_KANTOR; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Email Kantor :</label>
                    <div class="col-sm-5">
                        <input type='email' class='form-control' value="<?= @$item->EMAIL_KANTOR; ?>" name="EMAIL_KANTOR" placeholder="Email Kantor">
                    </div>
                </div>

                <div class="form-group sksk" <?php if($item->IS_CALON==1){ echo 'style="display:none;"';} ?>>
                    <label class="col-sm-4 control-label">SK<font color='red' class="req">*</font> :</label>
                    <div class="col-sm-5">
                        <input type="hidden" name="NNIK" value="<?= @$item->NIK; ?>" >
                        <input type="file" name="FILE_SK" id="FILE_SK" class="FILE_SK">
                        <?php
                        if($item->FILE_SK){
                        ?>
                            <a href="<?php echo 'uploads/data_jabatan/'.$item->NIK.'/'.$item->FILE_SK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.$item->NIK.'/'.$item->FILE_SK); ?></a>
                        <?php
                        }
                        ?>
                        <span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpg, jpeg, png .  Max File: 500KB</span>
                    </div>
                    
                </div> 

                <div class="form-group tmtsd" <?php if($item->IS_CALON==1){ echo 'style="display:none;"';} ?>>
                    <label class="col-sm-4 control-label">TMT :</label>
                    <div class="col-sm-5">
                        <div class="col-md-5"  style="margin-left: -14px;">
                            <input type="text" class="form-control datepicker" name="TMT" value="<?= (@$item->TMT != '' ? (@$item->TMT != '1970/01/01' ? date('d/m/Y',strtotime(@$item->TMT)) : date('d/m/Y')) : date('d/m/Y')); ?>" placeholder='DD/MM/YYYY'>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="hidden" class="" name="INIIDPN" id="INIIDPN" style="" value="<?= @$IDPN; ?>" placeholder="">
            <button id="btnupdtKJ" type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="button" class="btn btn-sm btn-default aa" value="Kembali" onclick="f_close(this);">
            <input type="hidden" class="" name="act" id="" style="" value="doupdate">
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            var nill = $('.ubahCalon:checked').val();
            if(nill == 1){
                $('.req').hide();
            }else{
                $('.req').show();
            }

            $('.ubahCalon').change(function(){
                var nil = $(this).val();
                // 1 = calon, 2 = menjabat
                if(nil == 1){
                    $('.sksk').hide();
                    $('.tmtsd').hide();
                    $('.TMT').attr('required', false);
                    $('.req').hide();
                    $('.sd').attr('style','');
                }else{
                    $('.TMT').attr('required', true);
                    $('.req').show();
                    $('.sksk').show();
                    $('.tmtsd').show();
                }
            });

            $('.FILE_SK').change(function(){
                var nil     = $(this).val().split('.');
                nil         = nil[nil.length - 1].toLowerCase();
                var file    = $(this)[0].files[0].size;
                var arr     = ['xls','xlsx','doc','docx','pdf','jpg','png','jpeg'];
                var maxsize = 500000;
                if (arr.indexOf(nil) < 0)
                {
                    $('.FILE_SK').val('');
                    alertify.error('Type file tidak sesuai !');
                }
                if (file > maxsize)
                {
                    $('.FILE_SK').val('');
                    alertify.error('Ukuran File trlalu besar !');
                }
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });

            // $('#btnupdtKJ').click(function(){
            //     var tex = $('#INIIDPN').val();
            //     var url = 'index.php/ereg/all_pn/saveklljabatan';
            //     var dta = $('#editklljabatan').serialize();
            //     $.post(url,dta,function(html){
            //         if(html == 1){
            //             f_close();
            //             var uuu = "index.php/ereg/all_pn/addjabatan/"+tex;
            //             $.get(uuu,function(data){
            //                 $('#wrapperKllJabatan').html(data);
            //             });
            //             var iii = "index.php/ereg/all_pn/";
            //             $.get(iii,function(data){
            //                 $('.cek').html(data);
            //             });
            //         }else{
            //             console.log('error');
            //         }
            //     });
            // });

            $("form#editklljabatan").submit(function(event) {
                var urll     = 'index.php/ereg/all_pn/saveklljabatan';
                var formData = new FormData($(this)[0]);
                var tex      = $('#INIIDPN').val();

                $.ajax({
                    url: urll,
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (html) {
                        msg = {
                           success : 'Data Berhasil Disimpan!',
                           error : 'Data Gagal Disimpan!'
                        };
                        if (html == 0) {
                           alertify.error(msg.error);
                        } else {
                           alertify.success(msg.success);
                        }
                        if(html == 1){

                            var iii = "<?php echo $stat == '1' ? "index.php/ereg/all_pn/calonpn" : "index.php/ereg/all_pn/"?>";
                            $.get(iii, function (data) {
                                $('#ajax-content').html(data);

                                var uuu = "index.php/ereg/all_pn/addjabatan/"+tex+"<?php echo $stat == '1' ? '/2/calonpn' : ''?>";
                                $.get(uuu,function(data){
                                    $('#wrapperKllJabatan').html(data);
                                    f_close();
                                });
                            })
                        }else{
                            console.log('error');
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            });

            $('input[name="LEMBAGA"]').select2({
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
                        $.ajax("<?=base_url('index.php/share/reff/getLembaga')?>"+'/'+id, {
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

            $('input[name="LEMBAGA"]').on("change", function (e) {
                $('input[name="UNIT_KERJA"]').select2('val', '');
                $('input[name="JABATAN"]').select2('val', '');
                LEMBAGA = $(this).val();
                $('input[name="UNIT_KERJA"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
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
                            $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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
            }).on("change", function(e) {
                var lembaga = $(this).val();

                $('input[name="JABATAN"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga,
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
                            $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga+'/'+id, {
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


            var lembaga = '<?= @$item->LEMBAGA; ?>';

            // alert(lembaga);

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>"+'/'+lembaga+'/'+id, {
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
    
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getUnitKerja')?>"+'/'+lembaga,
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
                        $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>"+'/'+lembaga+'/'+id, {
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
        })

        // function unit_kerjae() {
        //     var IDLEM = $('#LEMBAGA').val();
        //     $("#UNIT_KERJA222").empty();
        //     $.post("index.php/efill/lhkpn/daftar_UK/" + IDLEM, function(html) {
        //         $.each(html, function(index, value) {
        //             $("#UNIT_KERJA222").append("<option value='" + index + "'>" + value + "</option>");
        //         });
        //         $("#UNIT_KERJA222").show();
        //         $("#UNIT_KERJA222").select2();
        //     }, 'json');
        // }

        // function unit_kerjaEdit(param) {
        //     $.post("index.php/efill/lhkpn/daftar_UK/" + param, function(html) {
        //         $.each(html, function(index, value) {
        //             select = '<?=@$item->UNIT_KERJA?>';
        //             if (index == select) {
        //                 $("#UNIT_KERJA222").append("<option value='" + index + "' selected>" + value + "</option>");
        //             } else {
        //                 $("#UNIT_KERJA222").append("<option value='" + index + "'>" + value + "</option>");
        //             };
        //         });
        //         $("#UNIT_KERJA222").show();
        //         $("#UNIT_KERJA222").select2();
        //     }, 'json');
        // }
    </script>
<?php } ?>

<?php if($form=='deleteKJ'){ ?>
    <form class="form-horizontal" method="post" id="dltklljabatan" action="javascript:;" enctype="multipart/form-data">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <input type="hidden" class="" name="ID" style="" value="<?= @$item->ID; ?>" placeholder="">

                <div class="form-group">
                    <label class="col-sm-4 control-label">Jabatan <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <?= @$item->NAMA_JABATAN; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Lembaga <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <?= @$item->INST_NAMA; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Unit Kerja <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <?= @$item->UK_NAMA; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Jabatan<span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <?= @$item->NAMA_JABATAN; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Deskripsi Jabatan <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <?= @$item->DESKRIPSI_JABATAN; ?>
                    </div>
                </div>

            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="hidden" class="" name="INIIDPN" id="INIIDPN" style="" value="<?= @$IDPN; ?>" placeholder="">
            <button id="btndltKJ" type="button" class="btn btn-sm btn-primary">Hapus</button>
            <input type="button" class="btn btn-sm btn-default aa" value="Kembali" onclick="f_close(this);">
            <input type="hidden" class="" name="act" id="" style="" value="dodelete">
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#btndltKJ').click(function(){
                var tex = $('#INIIDPN').val();
                var url = 'index.php/ereg/all_pn/saveklljabatan';
                var dta = $('#dltklljabatan').serialize();
                $.post(url,dta,function(html){
                    msg = {
                       success : 'Data Berhasil Didelete!',
                       error : 'Data Gagal Didelete!'
                    };
                    if (html == 0) {
                       alertify.error(msg.error);
                    } else {
                       alertify.success(msg.success);
                    }
                    if(html == 1){
                        f_close();

                        var iii = "index.php/ereg/all_pn/";
                        $.get(iii,function(data){
                            ng.LoadAjaxContent(iii);
                            
                            var uuu = "index.php/ereg/all_pn/addjabatan/"+tex;
                            $.get(uuu,function(data){
                                $('#modal-inner').html(data);
                            });
                        });
                    }else{
                        console.log('error');
                    }
                });
            });
        })
    </script>
<?php } ?>