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
 * @package Views/masterdata/jabatan
 */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        
                <!--<button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>-->
                <div class="col-md-12">    
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group">
                                 <!-- <button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button> -->
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div></div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
<!--                <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
                <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
                <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>-->
            

        <!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
        <div class="col-md-12">    
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Instansi :</label>
                                <div class="col-sm-6">
                                    <input type='text' class="input-sm select2 form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='<?php echo @$CARI['INSTANSI']; ?>'  placeholder="-- Pilih Instansi --">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Unit Kerja:</label>
                                <div class="col-sm-6">
                                    <input type='text' class="input-sm select2 form-control" name='CARI[UNITKERJA]' style="border:none;padding:6px 0px;" id='CARI_UNITKERJA' value='<?php echo @$CARI['UNITKERJA']; ?>' placeholder="-- Pilih Unit Kerja --">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Sub Unit Kerja :</label>
                                <div class="col-sm-6">
                                    <input type='text' class="input-sm select2 form-control" name='CARI[SUBUKER]' style="border:none;padding:6px 0px;" id='CARI_SUBUKER' value='<?php echo @$CARI['SUBUKER']; ?>' placeholder="-- Pilih Sub Unit Kerja --" >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Status :</label>
                               
                                <div class="col-sm-6">
                                    <select name='CARI[STATUS]' id='CARI_STATUS' class='form-control' onchange="myFunction()">
                                        <!--<option value='0'>All</option>-->
                                        <option value='1' selected="">Aktif</option>
                                        <option value='-1'>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 col-sm-offset-4">
                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn-cetak btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                        <span class="logo-mini">
                            <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                        </span> Print to Excel 
                    </a>
                </div>
            </form>
        </div>
    </div><!-- /.box-header -->

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="col-md-2">
                    <button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>
                </div>
            </div>
            <div class="box-body">

                <table id="dt_masterjabatan" class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="width=100% !important;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Unit Kerja</th>
                            <th>Sub Unit Kerja</th>
                            <th>Nama Jabatan</th>
                            <th>Eselon</th>
                            <th>IS UU</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /*
                        ?>
                        <tr>
                        <td><small><?php echo ++$i; ?>.</small></td>
                        <td><small><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->NAMA_JABATAN);?></small></td>
                        <td align="center"> <?php echo $item->KODE_ESELON; ?></td>
                        <td align="center"><small><?= ($item->IS_UU == '0' ? 'No UU' : 'UU' ); ?></small></td>
                        <td align ="center"width="120" nowrap="">
                        <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                        <!-- <button type="button" class="btn btn-sm btn-default btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button> -->
                        <button type="button" class="btn btn-sm btn-success btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btnDelete" title="Delete"><i class="fa fa-trash" ></i></button>
                        </td>
                        </tr>
                        <?php
                        */
                        ?>
                    </tbody>
                </table>
            </div>
        </div><!-- /.box -->
    </div><!-- /.col -->

        <script language="javascript">
            var stat='none';
            function myFunction() {            
            var x = document.getElementById("CARI_STATUS").value;
            
            if (x = 1) { stat =''}
            else if (x = -1) { stat = '' }
        }
            $(document).ready(function() {

                if ($('#CARI_UNITKERJA').val() != '') {
                    $('#CARI_UNITKERJA').removeAttr("disabled");
                }

                if ($('#CARI_SUBUKER').val() != '') {
                    $('#CARI_SUBUKER').removeAttr("disabled");
                }

                $('input[name="CARI[INSTANSI]"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?= base_url('index.php/share/reff/getlembaga') ?>",
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
                            $.ajax("<?= base_url('index.php/share/reff/getlembaga') ?>/" + id, {
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
                }).on("change", function(e) {
                    $('#CARI_UNITKERJA').removeAttr("disabled");
                    idlemb = e.added.id;

                    $('input[name="CARI[UNITKERJA]"]').select2({
                        minimumInputLength: 0,
                        ajax: {
                            url: "<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + idlemb,
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
                                $.ajax("<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + idlemb + "/" + id, {
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
                    }).on("change", function(e) {
                        $('#CARI_SUBUKER').removeAttr("disabled");
                        idker = e.added.id;

                        $('input[name="CARI[SUBUKER]"]').select2({
                            minimumInputLength: 0,
                            ajax: {
                                url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + idker,
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
                                    $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + idker + "/" + id, {
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
                });

            });
            
            $('.btn-cetak').click(function(e) {
                e.preventDefault();
                <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
                    if ($('#CARI_INSTANSI').val()){
                        submitCetak();
                    }
                    else{
                        alert('Mohon pilih salah satu instansi terlebih dahulu sebelum Export to Excel.');
                        return false;
                    }
                <?php else: ?>
                    submitCetak();
                <?php endif; ?>
            });
            function submitCetak() {
                var text = ($('#CARI_TEXT').val() == '') ? 'ALL' : $('#CARI_TEXT').val();
                var instansi = ($('#CARI_INSTANSI').val() == '') ? 'ALL' : $('#CARI_INSTANSI').val();
                var uk = ($('#CARI_UNITKERJA').val() == '') ? 'ALL' : $('#CARI_UNITKERJA').val();
                var subuker = ($('#CARI_SUBUKER').val() == '') ? 'ALL' : $('#CARI_SUBUKER').val();
                var status = ($('#CARI_STATUS').val() == '') ? 'ALL' : $('#CARI_STATUS').val();
                       
                var url = '<?php echo site_url("/index.php/admin/Cetak/export"); ?>/' + text +'/' + instansi +'/' + uk +'/'+ subuker +'/' + status;
                window.location.href = url;
                return;
            }

            var fnEdit = function(self) {
                var key = $(self).parents('td').children('.key').val();
                var url = '<?php echo $urlEdit; ?>' + key;
                ng.postOpenModalBox(url, 'Edit <?php echo $title; ?>', '', 'standart');
                return false;
            };

            var fnDel = function(self) {
                var key = $(self).parents('td').children('.key').val();
                var url = '<?php echo $urlDelete; ?>' + key;
                ng.postOpenModalBox(url, 'Delete <?php echo $title; ?>', '', 'standart');
                return false;
            };
            
            var fnKembalikan = function (self, k) {
//            var key = $(self).parents('td').children('.key').val();
            var url = '<?php echo $urlKembalikan; ?>' + k;
            ng.postOpenModalBox(url, 'Kembalikan <?php echo $title; ?>', '', 'standart');
            return false;
        };

            var tblMasterJabatan = {
                tableId: 'dt_masterjabatan',
                reloadFn: {tableReload: true, tableCollectionName: 'tblMasterJabatan'},
                conf: {
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers",
                    "bServerSide": true,
                    "bAutoWidth": false,
                    "sAjaxSource": "<?php echo base_url('admin/masterdata/load_data_daftar_master_jabatan/'); ?>",
                    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                        var passData = getRecordDtTbl(sSource, aoData, oSettings);

                        passData.push({"name": "CARI[TEXT]", "value": $("#CARI_TEXT").val()});
                        passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                        passData.push({"name": "CARI[UNITKERJA]", "value": $("#CARI_UNITKERJA").val()});
                        passData.push({"name": "CARI[SUBUKER]", "value": $("#CARI_SUBUKER").val()});
                        passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});

                        $.getJSON(sSource, passData, function(json) {
                            fnCallback(json);
                        });
                    },
                    "aoColumns": [
                        {"mDataProp": "NO_URUT", bSearchable: false},
                        {"mDataProp": "UK_NAMA", bSearchable: true},
                        {"mDataProp": "SUK_NAMA", bSearchable: true},
                        {"mDataProp": "NAMA_JABATAN", bSearchable: true},
                        {"mDataProp": "ESELON", bSearchable: true},
                        {
                            "mDataProp": function(source, type, val) {

                                if (isObjectAttributeExists(source, 'IS_UU') && !isEmpty(source.IS_UU)) {
                                    if (source.IS_UU == '0') {
                                        return 'Non UU';
                                    }
                                    return 'UU';
                                }

                                return '-';
                            },
                            bSearchable: true
                        },
                        {
                            "mDataProp": function(source, type, val) {


                                //
                                //
                                var BtnHapus = '<button type="button" class="btn btn-sm btn-danger btnDelete" onclick="fnDel(this);" title="Delete"><i class="fa fa-trash" ></i></button>';
                                var BtnEdit = '<button type="button" class="btn btn-sm btn-success btnEdit" onclick="fnEdit(this);" title="Edit"><i class="fa fa-pencil"></i></button>';
                                var inp = '<input type="hidden" class="key" value="' + source.ID_JABATAN + '">';
                                var BtnKembalikan = '<button style="display:'+stat+'" type="button" class="btn btn-sm btn-success btnEdit" onclick="fnKembalikan(this, ' + source.ID_JABATAN + ');" title="Kembalikan"><i class="fa fa-repeat"></i></button>';
                                if($("#CARI_STATUS").val()!=1){
                                    return (inp + " " + BtnEdit + " " + BtnHapus+" " + BtnKembalikan).toString();
                                }else{
                                    return (inp + " " + BtnEdit + " " + BtnHapus).toString();
                                }   
                                
                            },
                            bSortable: false,
                            bSearchable: false
                        }
                    ],
//                    "fnRowCallback": function(nRow, aData) {
//
//                        return nRow;
//                    }
                }
            };
            var gtblMasterJabatan;
            var clearPencarian = function() {
                var temp="a"; 
                $("#CARI_INSTANSI").val(temp);
                //                $('#CARI_INSTANSI').val('');
                $('#CARI_INSTANSI').val('') ;
                $('#CARI_UNITKERJA').val('');
                $('#CARI_SUBUKER').val('');
                //document.getElementById('s2id_CARI_UNITKERJA').selectedIndex = -1;
                $('#CARI_TEXT').val('');
                reloadTableDoubleTime(gtblMasterJabatan);
            };

            var submitCari = function() {
                reloadTableDoubleTime(gtblMasterJabatan);
            };

            $(document).ready(function() {

                gtblMasterJabatan = initDtTbl(tblMasterJabatan);

                $("#dt_masterjabatan_filter").hide();

            });

        </script>