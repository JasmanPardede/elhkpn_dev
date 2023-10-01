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
 * @package Views/masterdata/kabupaten
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
                                 <button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div></div>
                            <div class="form-group">
                                <label class="col-sm-7 control-label">Status :</label>
                                <div class="col-sm5">
                                    <div class="col-sm-5">
                    <select name='CARI[STATUS]' id='CARI_STATUS' class='form-control' onchange="myFunction()">
                        <!--<option value='0'>All</option>-->
                        <option value='1' selected="">Aktif</option>
                        <option value='-1'>Tidak Aktif</option>
                    </select>
                </div>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
<!--        <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
        <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
        <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>-->
    </div>  
</div><!-- /.box-header -->
<div class="box-body">
<!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->

<table id="dt_masterkabupaten" class="table table-striped">
    <thead>
        <tr>
            <th align="center" width="30">No.</th>
            <th>Kabupaten / Kota</th>
            <th>Provinsi</th>
            <th style="width: 15%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /**    
        $i = 0 + $offset;
            $start = $i + 1;
            foreach ($items as $item) {
        ?>
           <?php
                            if($item->IS_ACTIVE==1){
                                ?>
        <tr>
            <td align="center"><small><?php echo ++$i; ?>.</td>
            <td align="center"> <?php echo $item->NAME_KAB; ?>
            <td align="center"><?php echo $item->NAME; ?></td> 
            <td width="120" nowrap="">
                <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                <!-- <button type="button" class="btn btn-sm btn-default btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button> -->
                <button type="button" class="btn btn-sm btn-success btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-danger btnDelete" title="Delete"><i class="fa fa-trash"></i></button>
            </td>
               <?php
                            }
                            ?>            
        </tr>
        <?php
                $end = $i;
            }
         * 
         */
        ?>
    </tbody>
</table>
</div><!-- /.box-body -->
<script language="javascript">
    var stat='none';
    function myFunction() {            
            var x = document.getElementById("CARI_STATUS").value;
            
            if (x = 1) { stat =''}
            else if (x = -1) { stat = '' }
        }
    var fnEdit = function (self, k) {
//            var key = $(self).parents('td').children('.key').val();
            var url = '<?php echo $urlEdit; ?>' + k;
            ng.postOpenModalBox(url, 'Edit <?php echo $title; ?>', '', 'standart');
            return false;
        };

        var fnDel = function (self, k) {
//            var key = $(self).parents('td').children('.key').val();
            var url = '<?php echo $urlDelete; ?>' + k;
            ng.postOpenModalBox(url, 'Delete <?php echo $title; ?>', '', 'standart');
            return false;
        };
        var fnKembalikan = function (self, k) {
//            var key = $(self).parents('td').children('.key').val();
            var url = '<?php echo $urlKembalikan; ?>' + k;
            ng.postOpenModalBox(url, 'Kembalikan <?php echo $title; ?>', '', 'standart');
            return false;
        };

        var tblMasterKabupaten = {
            tableId: 'dt_masterkabupaten',
            reloadFn: {tableReload: true, tableCollectionName: 'tblMasterKabupaten'},
            conf: {
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "bServerSide": true,
                "sAjaxSource": "<?php echo base_url('admin/masterdata/load_data_daftar_master_kabupaten/'); ?>",
                "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                    var passData = getRecordDtTbl(sSource, aoData, oSettings);

                    passData.push({"name": "CARI[TEXT]", "value": $("#CARI_TEXT").val()});
                    passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                    
                    $.getJSON(sSource, passData, function (json) {
                        fnCallback(json);
                    });
                },
                "aoColumns": [
                    {"mDataProp": "NO_URUT", bSearchable: false},
                    {"mDataProp": "NAME_KAB", bSearchable: true},
                    {"mDataProp": "NAME", bSearchable: true},
                    {
                        "mDataProp": function (source, type, val) {

                            var BtnHapus = '<button type="button" class="btn btn-sm btn-danger btnDelete" onclick="fnDel(this, ' + source.ID_KAB + ');" title="Delete"><i class="fa fa-trash" ></i></button>';
                            var BtnEdit = '<button type="button" class="btn btn-sm btn-success btnEdit" onclick="fnEdit(this, ' + source.ID_KAB + ');" title="Edit"><i class="fa fa-pencil"></i></button>';
                            var BtnKembalikan = '<button style="display:'+stat+'" type="button" class="btn btn-sm btn-success btnEdit" onclick="fnKembalikan(this, ' + source.ID_KAB + ');" title="Kembalikan"><i class="fa fa-repeat"></i></button>';
                            
                            return (BtnEdit + " " + BtnHapus +" " + BtnKembalikan).toString();
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
        var gtblMasterKabupten;
        var clearPencarian = function () {
            $('#CARI_TEXT').val('');
            reloadTableDoubleTime(gtblMasterKabupten);
        };

        var submitCari = function () {
            reloadTableDoubleTime(gtblMasterKabupten);
        };

        $(document).ready(function () {
            gtblMasterKabupten = initDtTbl(tblMasterKabupaten);
        });
        
//  --  $(document).ready(function () {
//
//        if($('#CARI_KOTA').val() != ''){
//            $('#CARI_KOTA').removeAttr("disabled");
//        }
//
//        if($('#CARI_KEC').val() != ''){
//            $('#CARI_KEC').removeAttr("disabled");
//        }
//            
//        $('input[name="CARI[PROV]"]').select2({
//            minimumInputLength: 0,
//            ajax: {
//                url: "<?=base_url('index.php/efill/lhkpn/getProvinsiNew')?>",
//                dataType: 'json',
//                quietMillis: 250,
//                data: function (term, page) {
//                    return {
//                        q: term
//                    };
//                },
//                results: function (data, page) {
//                    return { results: data.item };
//                },
//                cache: true
//            },
//            initSelection: function(element, callback) {
//                var id = $(element).val();
//                if (id !== "") {
//                    $.ajax("<?=base_url('index.php/efill/lhkpn/getProvinsiNew')?>/"+id, {
//                        dataType: "json"
//                    }).done(function(data) { callback(data[0]); });
//                }
//            },
//            formatResult: function (state) {
//                return state.name;
//            },
//            formatSelection:  function (state) {
//                return state.name;
//            }
//        }).on("change", function(e) {
//            $('#CARI_KOTA').removeAttr("disabled");
//            idprov = e.added.id;
//
//            $('input[name="CARI[KOTA]"]').select2({
//                minimumInputLength: 0,
//                ajax: {
//                    url: "<?=base_url('index.php/share/reff/getKota')?>/"+idprov,
//                    dataType: 'json',
//                    quietMillis: 250,
//                    data: function (term, page) {
//                        return {
//                            q: term
//                        };
//                    },
//                    results: function (data, page) {
//                        return { results: data.item };
//                    },
//                    cache: true
//                },
//                initSelection: function(element, callback) {
//                    var id = $(element).val();
//                    if (id !== "") {
//                        $.ajax("<?=base_url('index.php/share/reff/getKota')?>/"+idprov+"/"+id, {
//                            dataType: "json"
//                        }).done(function(data) { callback(data[0]); });
//                    }
//                },
//                formatResult: function (state) {
//                    return state.name;
//                },
//                formatSelection:  function (state) {
//                    return state.name;
//                }
//            }).on("change", function(e) {
//                $('#CARI_KEC').removeAttr("disabled");
//                idkabkot = e.added.id;
//
//                $('input[name="CARI[KEC]"]').select2({
//                    minimumInputLength: 0,
//                    ajax: {
//                        url: "<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot,
//                        dataType: 'json',
//                        quietMillis: 250,
//                        data: function (term, page) {
//                            return {
//                                q: term
//                            };
//                        },
//                        results: function (data, page) {
//                            return { results: data.item };
//                        },
//                        cache: true
//                    },
//                    initSelection: function(element, callback) {
//                        var id = $(element).val();
//                        if (id !== "") {
//                            $.ajax("<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot+"/"+id, {
//                                dataType: "json"
//                            }).done(function(data) { callback(data[0]); });
//                        }
//                    },
//                    formatResult: function (state) {
//                        return state.name;
//                    },
//                    formatSelection:  function (state) {
//                        return state.name;
//                    }
//                });
//            });
//        });
//  --  
// --        idprov = $('#CARI_PROV').val();

        // $('input[name="CARI[KOTA]"]').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?=base_url('index.php/share/reff/getKota')?>/"+idprov,
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
        //             $.ajax("<?=base_url('index.php/share/reff/getKota')?>/"+idprov+"/"+id, {
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

        // idkabkot = $('#CARI_KOTA').val();

        // $('input[name="CARI[KEC]"]').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot,
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
        //             $.ajax("<?=base_url('index.php/share/reff/getKec')?>/"+idprov+"/"+idkabkot+"/"+id, {
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
//--    });
</script>
