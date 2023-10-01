<?php

/**
* @author Ahmad Saughi
* @version 04122018
*/

 ?>
<style>
.form-horizontal .form-group {
    margin-bottom: 0.5em;
}
.btn-batal {
    text-align: center;
}
.red-cuk {
    color: red;
}
.column {
  float: left;
  width: 33.33%;
  padding: 10px;
}
table tbody {
    font-size: 13px;
}
th {
    padding-right: 20px;
    padding-left: 20px;
}
thead tr {
    background-color: #337ab7;
    height: 60px;
    color: ghostwhite;
}
td {
    text-align: center;
    vertical-align: middle;
}
/* Clear floats after the columns */
/* .row:after {
  content: "";
  display: table;
  clear: both;
} */
.notsu {
    color: white;
}
.btn_s {
    color: white;
}

</style>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="formNodin" action="index.php/eaudit/nodin/<?php echo $action; ?>" enctype="multipart/form-data" >
        <div class="row" id="wrapperFormAddND">
            <div class="column" style="//background-color:#ccc;">
                <div class="form-group">
                    <label class="control-label col-xs-4" for="iNomorND">Nomor ND<span class="red-cuk">*</span>:</label>
                    <div class="col-xs-6" style="padding-right: 1px;">
                        <input type="text"  class="form-control iNomorND"  name="iNomorND" autocomplete="off" <?= $onAdd ? 'value='.$NOMOR_ND : null?> required/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-4" for="iJenis">Jenis ND<span class="red-cuk">*</span>:</label>
                    <div class="col-xs-6" style="padding-right: 1px;">
                        <select class="form-control" id="iJenis" name="iJenis">
                            <option <?php echo $onAdd ? (($JENIS_ND == 1) ? 'selected' : '') : ''; ?> value="1">Data Keuangan</option>
                            <option <?php echo $onAdd ? (($JENIS_ND == 2) ? 'selected' : '') : ''; ?> value="2">LHP (Rekomendasi)</option>
                            <option <?php echo $onAdd ? (($JENIS_ND == 3) ? 'selected' : '') : ''; ?> value="3">LHP</option>
                            <option <?php echo $onAdd ? (($JENIS_ND == 4) ? 'selected' : '') : ''; ?> value="4">Data Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-4" for="iTujuan">Tujuan ND<span class="red-cuk">*</span>:</label>
                    <div class="col-xs-6" style="padding-right: 1px;">
                    <input type="text" class="form-control form-select2 iTujuan" name="iTujuan" style="border:none;" id="iTujuan" value="<?php echo isset($TUJUAN_ND) ? $TUJUAN_ND : ""; ?>" placeholder="-- Pilih Tujuan ND --" data-title="Tujuan ND" required>
                    </div>
                </div>

            </div>
            <div class="column" style="//background-color:#ccc;">
                <div class="form-group">
                    <label class="control-label col-xs-4" for="iTanggalND">Tanggal ND<span class="red-cuk">*</span>:</label>
                    <div class="col-xs-6">
                    <input class="form-control date-picker" type="text" id="iTanggalND" name="iTanggalND" placeholder="dd/mm/yyyy" autocomplete="off" required value="<?php echo $onAdd ? date('d/m/Y') : ''; ?>"  />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-4" for="iKeterangan">Keterangan :</label>
                    <div class="col-xs-7">
                        <textarea id="iKeterangan" name="iKeterangan" class="form-control" rows="6"><?php echo $onAdd ? $KETERANGAN : ''; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="column" style="//background-color:#ccc;">
                <div class="form-group form-file">
                    <label class="control-label col-xs-4">File/Dokumen:</label>
                    <div class="col-xs-7">
                        <div style="float:left; margin-right:10px;" id="show-download"></div>
                        <input type="file" id="file1" name="file1[]" class="form-control" multiple data-allowed-file-extensions='["pdf", "jpg", "png", "jpeg", "tif", "tiff"]'  data-show-preview="true"/>
                        <small><span class="help-block">Format file</span></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-batal">
            <hr>
            <div class="col-md-4" style="//padding-top: 10px;//background-color:yellow">
            </div>
            <div class="col-md-4" style="//padding-top: 10px;//background-color:red">
            </div>
            <div class="col-md-4" style="//padding-top: 10px;//background-color:blue">
                <form class='form-inline' id="ajaxFormCari">
                    <div class="form-group">
                        <label class="control-label col-xs-4" for="iNamaPN">Cari PN</label>
                        <div class="col-xs-8">
                            <input class="form-control" type="text" id="iNamaPN" name="iNamaPN" placeholder="NAMA / NIK" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-col-sm-3 col-sm-offset-4-2 text-right" style="padding-right: 1em">
                            <button type="submit" class="btn btn-sm btn-default" id="btn-cari" >Cari PN</button>
                            <button type="button" class="btn btn-sm btn-default" id="btn-clear">Clear</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="divDataPN">
                    <div class="box-body">
                        <table id="table_PN" class="display table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:25%">Nama PN / NIK</th>
                                    <th style="width:20%">No. Agenda</th>
                                    <th style="width:25%">Jabatan</th>
                                    <th style="width:25%">Lembaga</th>
                                    <th style="width:25%">Nomor ST</th>
                                    <th style="width:5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="body_table_PN">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="divPilihPN">
                    <div class="box-body">
                        <table id="table_pilih_PN" class="display table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:25%">Nama PN / NIK</th>
                                    <th style="width:20%">No. Agenda</th>
                                    <th style="width:25%">Jabatan</th>
                                    <th style="width:25%">Lembaga</th>
                                    <th style="width:25%">Nomor ST</th>
                                    <th style="width:5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            <div class="pull-right">
                <input type="hidden" name="listNamaPN" id="listNamaPN">
                <input type="hidden" name="listPilihId" id="listPilihId" value="<?php echo $onAdd ? $ID_LHKPN : ''; ?>">
                <input type="hidden" name="listPilihIdAudit" id="listPilihIdAudit" value="<?php echo $onAdd ? $ID_AUDIT : ''; ?>">
                <!-- <input type="hidden" name="listPilihNamaPN" id="listPilihNamaPN" value="<?php //echo $onAdd ? $NAMA : ''; ?>"> -->
            </div>
            <div class="pull-left">
                <label>Info jumlah PN yang dipilih: <span id="jmlPN"></span></label>
            </div>
            <div class="clearfix"></div>
            <div class="btn-batal">
                <hr>
                <!-- <button type="button" class="btn btn-primary btn-sm" id='simpan'><i class="fa fa-share"></i>Simpan</button> -->
                <button type="submit" class="btn btn-primary btn-sm" id='simpan'><i class="fa fa-share"></i>Simpan</button>
                <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
            </div>
        </div>
    </form>
</div>

<div id="wrapperFormCekNDExist" class="divCekND" style="display: none;">
    <h3>Info Nota Dinas</h3>
        <table id="table_cek_ND" class="display table-striped" width="100%">
            <thead>
                <tr>
                    <th style="width:25%">Tujuan</th>
                    <th style="width:20%">Jenis</th>
                    <th style="width:25%">Nomor ND</th>
                    <th style="width:25%">Tanggal ND</th>
                </tr>
            </thead>
            <tbody id="body_table_cek_ND">
            </tbody>
        </table>
    <br>
    <button type="button" class="btn btn-sm btn-default" id="btnBackToForm">Kembali Ke Form</button>
</div>

<script type="text/javascript">
    var pilih_id = [];
    var pilih_id_audit = [];
    var onAdd = <?php echo $onAdd == '' ? '0' : $onAdd; ?>;
    var mode = onAdd == 0 ? 'create' : 'update';
    var getND = '<?php echo $NOMOR_ND ?: null; ?>';
    
    function CekND(needResponse) {

        var no_nd = $('#iNomorND').val();
        var nd_tr = no_nd.trim();
        var nd = btoa(nd_tr);
        var url = "index.php/eaudit/Nodin/cek_nd/" + nd;
        $.post(url, function (data) {
            if (data == '0') {
                var url_cekND = 'index.php/eaudit/Nodin/cekND/'+nd;
                $.post(url_cekND,data,function( data ) {
                    $('.modal-dialog').animate({})
                    $("#wrapperFormAdd").hide('fast');
                    $("#wrapperFormCekNDExist").show('fast');
                     $('#body_table_cek_ND tr').remove();
                    drawTablecekND(data);
                },'json',);
//                $.post('index.php/eaudit/Nodin/cekND/' + nd, {redirect: window.location.href.split('#')[1]}, function (data, textStatus, xhr) {
//                    $('.modal-dialog').animate({})
//
//                    $("#wrapperFormAdd").hide('fast');
//                    $("#wrapperFormCekNDExist").show('fast');
//                });
            }
        });

    }
    
    function CekND_Baru(needResponse) {

        var no_nd = $('#iNomorND_BARU').val();
        var nd_tr = no_nd.trim();
        var nd = btoa(nd_tr);
        var url = "index.php/eaudit/Nodin/cek_nd/" + nd;
        $.post(url, function (data) {
            if (data == '0') {
                var url_cekND = 'index.php/eaudit/Nodin/cekND/'+nd;
                $.post(url_cekND,data,function( data ) {
                    $('.modal-dialog').animate({})
                    $("#wrapperFormAdd").hide('fast');
                    $("#wrapperFormCekNDExist").show('fast');
                     $('#body_table_cek_ND tr').remove();
                    drawTablecekND(data);
                },'json',);
//                $.post('index.php/eaudit/Nodin/cekND/' + nd, {redirect: window.location.href.split('#')[1]}, function (data, textStatus, xhr) {
//                    $('.modal-dialog').animate({})
//
//                    $("#wrapperFormAdd").hide('fast');
//                    $("#wrapperFormCekNDExist").show('fast');
//                });
            }
        });

    }
    
    function drawTablecekND(data) {
            var ii = 1;
            var tujuan = "";
            var jenis = "";
            var nomor_nd = "";
            var tanggal_nd = "";

            $('#loader_area').hide();
            for (var i = 0; i < data.length; i++) {
                var id = data[i]["ID"];
                var tujuan = data[i]["NAMA_UK_EAUDIT"];
                var jenis = data[i]["JENIS_NOTA_DINAS"];
                var nomor_nd = data[i]["NOMOR_NOTA_DINAS"];
                var tanggal_nd = data[i]['TANGGAL_NOTA_DINAS'];
                if (jenis == 1){
                    jenis = 'Data Keuangan'
                } else if (jenis == 2){
                    jenis = 'LHP (Rekomendasi)';
                } else if (jenis == 3){
                    jenis = 'LHP';
                } else if (jenis == 4){
                    jenis = 'Data Lainnya';
                }
                rowCount = ' ';
                $('#table_cek_ND tbody').append(
                    '<tr id="rowcekND' + id + '">' +
                    '<td><label>' + tujuan + '</label> </td>' +
                    '<td><label>' + jenis + '</label> </td>' +
                    '<td><label>' + nomor_nd + '</label> </td>' +
                    '<td><label>' + tanggal_nd + '</label> </td>' +
                    "</tr>"
                )

                ii++;
                rowCount++;
            };
        }
    
    $("#btnBackToForm").click(function(e) {
            $('.modal-dialog').animate({
            })
            $("#wrapperFormAdd").show('fast');
            $("#wrapperFormCekNDExist").hide('fast');
            if (onAdd == '0' || onAdd == 0){                
                $("#iNomorND").select2("val", "");
                $('#iTanggalND').val('');
            }else{
                var nomorND = '<?= $NOMOR_ND ?>';
                var tanggalND = '<?= date('d/m/Y', strtotime($TANGGAL_ND))?>';
                $("#iNomorND").select2("val", nomorND);
                $('#iTanggalND').val(tanggalND);
            }
            
        });
        
    function showHideFieldNDBaru(hide) {
        if (isDefined(hide) && hide) {
            $("div#divFieldInputNDBaru").hide();
            if (onAdd == '0' || onAdd == 0){
                $("div#divButtonShowFieldInputNDBaru").hide();
            }else{
                $("div#divButtonShowFieldInputNDBaru").show();
            }

//            $("#NIKBaru2").empty();
            $("#iNomorND_BARU").val('');

        }
        else {
            $("div#divFieldInputNDBaru").show();
            $("div#divButtonShowFieldInputNDBaru").hide();
        }
    }
    
    $(document).ready(function() {
        var dtTable = $('#table-nodin').DataTable();
        var id_lhkpn = $('#listPilihId').val();
        var id_audit = $('#listPilihIdAudit').val();

        if (id_lhkpn == '') {
            // console.log("KOSONG");
        } else {
            var data1 = $.parseJSON('<?php echo $JSON_LIST; ?>');
            var nomor = 1;
            // console.log(data1);
            $.each(data1, function(i, item) {
                $.each(this, function(i, val) {
                    pilih_id.push(val.id_lhkpn);
                    pilih_id_audit.push(val.id_audit);
                    $('#table_pilih_PN tbody').append(
                        '<tr id="rowpilih' + val.id_lhkpn + '">' +
                            // '<td><label>' + pilih_id.length + '</label> </td>' +
                            '<td><label>' + val.nama_pn + '</label> (' + val.nik_pn + ')</td>' +
                            '<td><label>' + val.agenda_pn + '</label> </td>' +
                            '<td><label>' + val.jabatan_pn + '</label> </td>' +
                            '<td><label>' + val.lembaga_pn + '</label> </td>' +
                            '<td><label>' + val.no_st + '</label> </td>' +
                            '<td><button type="button" id="pilih" class="btn btn-xs btn-danger btn_s" onClick="f_hapus(this)" data-id="' + val.id_lhkpn + '" data-id-audit="' + val.id_audit + '" data-nama="' + val.nama_pn + '"><span class="glyphicon glyphicon-remove"></span></button></td>' +
                        '</tr>'
                    )
                })
            });
        }

        if(pilih_id.length == 0) {
            $('.pull-left').hide();
        } else {
            $('#jmlPN').text(pilih_id.length)
        }

        $('#btn-clear').click(function() {
            $('#body_table_PN tr').remove();
            $('#iNamaPN').val('');
        });
        
        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        
        $("input#iNomorND,input#iNomorND_BARU").on({
            keydown: function(e) {
              if (e.which === 32)
                return false;
            },
            change: function() {
              this.value = this.value.replace(/\s/g, "");
            }
          });
          
        var removeSpace = {
            pasteND:  function( e ) {
                var ele = $(this);
                setTimeout(function () {
                    ele.val(ele.val().replace(/\s/g, ''));
                }, 5);
            }
        }  
          
        $('#iNomorND').on('paste', removeSpace.pasteND);  
        $('#iNomorND_BARU').on('paste', removeSpace.pasteND);
            
        if (onAdd == '1' || onAdd == 1){
            $("#iNomorND").prop('disabled', true);
        }
        showHideFieldNDBaru(true);   
        $("button.btn-cancel-nd-baru").click(function(e) {
            e.preventDefault();

            showHideFieldNDBaru(true);
        });
        $("button.btn-edit-nd-baru").click(function(e) {
            e.preventDefault();
            showHideFieldNDBaru();
        });

        $('#simpan').click(function() {
            var test = 1;
            nomorND = $('#iNomorND').val();
            jenisND = $('#iJenis').val();
            tujuanND = $('#iTujuan').val();
            tanggalND = $('#iTanggalND').val();
            id_lhkpn = $('#listPilihId').val();
            validate = (nomorND == '' || null || false)
                || (jenisND == '' || false)
                || (tujuanND == '' || null || false)
                || (tanggalND == '' || null || false)
                || (id_lhkpn == '' || null || false)
                ? false
                : true;
            if (!validate) {
                alertify.error('Data Gagal Disimpan! Harap lengkapi form.');
            } else {
                $("#iNomorND").prop('disabled', false);
                var id_lhkpn = $('#listPilihId').val();
                var id_audit = $('#listPilihIdAudit').val();
                var url = $('#formNodin').attr('action');
                var formData = new FormData($('#formNodin')[0]);
                var msg = {
                    success: 'Data Berhasil Disimpan!',
                    error: 'Data Gagal Disimpan!',
                };            
                if (id_lhkpn == '') {
                    alertify.error('Pilih PN Terlebih Dahulu!');
                } else {
                    $('#loader_area').show();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        async: false,
                        success: function (data) {
                            $('#loader_area').hide();
                            CloseModalBox2();
                            dtTable.ajax.reload( null, false );
                            if (data == 1) {
                                alertify.success(msg.success);
                            } else if (data == 2){
                                alertify.error('Data Gagal Disimpan! <br> Nomor Nota Dinas sudah pernah di input!');
                            }
                            else{
                                alertify.error(msg.error);
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false,
                    });
                }
                return false;
            }
            return false;
        });
        
        $('.iTujuan').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getUnitKerjaNodin') ?>/",
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
                    $.ajax("<?= base_url('index.php/share/reff/getUnitKerjaNodin') ?>/" + id, {
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

        $('#iNomorND').select2({
            minimumInputLength: 0,
            placeholder: '-- Pilih Nomor ND --',
            // allowClear: true,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getNodin') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term) {
                    return {
                        d: term
                    };
                },
                results: function(data) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection : function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getNodin') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
                // if (getND) {
                //     var data = {id: getND, text: getND};
                //     callback(data);
                // }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        }).on("select2-selecting", function(state) {
            //cek is exists ND
            CekND();
            var tanggal = moment(state.object.tanggal).format('DD/MM/YYYY');
            $('#iTanggalND').val(tanggal);
        }).on("select2-clearing", function(state) {
            $('#iTanggalND').val('');
        }).on('change', function (e) {
            e.preventDefault();
            CekND();
        });

        $('#simpan').click(function(e) {
            // e.preventDefault();
            var id_lhkpn = $('#listPilihId').val();
            var url = $('#formNodin').attr('action');
            var data = $('#formNodin').serializeArray();
            var formData = new FormData($('#formNodin')[0]);
            // console.log(url,data,formData);
            var msg = {
                success: 'Data Berhasil Disimpan!',
                error: 'Data Gagal Disimpan!',
            };
            if (id_lhkpn == '') {
                alertify.error('Pilih PN Terlebih Dahulu!');
            } else {
                // $('#loader_area').show();
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    async: false,
                    success: function (data) {
                        // $('#loader_area').hide();
                        // CloseModalBox2();
                        // dtTable.ajax.reload( null, false );
                        // if (data == '1') {
                        //     alertify.success(msg.success);
                        // } else {
                        //     alertify.error(msg.error);
                        // }
                    },
                    cache: false,
                    contentType: false,
                    processData: false,                
                });
            }
            return false;
        });

        $('#formNodin').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
                e.preventDefault();
                return false;
            }
        });
        
        $("input[type='file']").fileinput({
            showUpload: false,
            showRemove: false,
            //overwriteInitial: false,
            initialCaption: false,
            //showCaption: false
        });

        $('.date-picker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
	    });
      
        $('#btn-cari').click(function(e) {
            $('#body_table_PN tr').remove();
            $('#loader_area').show();
            e.preventDefault();

            var nama = $('#iNamaPN').val();
            var url = "";
            if ($('#iNamaPN').val().length > 0) {
                url = "index.php/eaudit/Nodin/get_pn_by_name/"
                var rowCount = $('#table_PN tr').length;
                var data = {
                    nama : nama
                };
                $.post(url,data,function( data ) {
                    drawTable(data);
                },'json',);
            } else {
                $('#loader_area').hide();
                return false;
            }

        });


        function drawTable(data) {
            var ii = 1;
            var status = "";
            var className = "";
            var agenda = "";
            var agendaTemp = "";
            var tglLapor = "";
            var tahunLapor = "";
            var nik = "";
            var idlhkpn = "";
            //get all list PN
            var allPN = "";
            var hasilSearch = "";
            var noST = "";
            var idaudit = "";
            for (var i = 0; i < data.length; i++) {
                if(!hasilSearch.includes(data[i]["NAMA"])) {
                    hasilSearch = data[i]["NAMA"] + ',' + hasilSearch;
                }
            }
            allPN = $('#listNamaPN').val();

            if(checkPNValid(hasilSearch)) {
                $('#loader_area').hide();
                for (var i = 0; i < data.length; i++) {
                    var lhkpn = data[i]["ID_LHKPN"];
                    var nikPN = data[i]["NIK"];
                    var namaPN = data[i]["NAMA"];
                    var lembaga = data[i]['INST_NAMA'];
                    var jabatan = data[i]['NAMA_JABATAN'];
                    var tglLapor = data[i]["tgl_lapor"];
                    var noST = data[i]["no_st"];
                    var idaudit = data[i]["id_audit"];
                    
                    if (tglLapor != null) {
                        tahunLapor = tglLapor.split('-')[0];
                    } else {
                        tahunLapor = '-';
                    }
                    agenda = tahunLapor + '/' + (data[i]["JENIS_LAPORAN"] == '4' ? 'R' : (data[i]["JENIS_LAPORAN"] == '5' ? 'P' : 'K')) + '/' + nikPN + '/' + lhkpn;
                    if (jabatan == null) {
                        jabatan = '-';
                    }
                    if (lembaga == null) {
                        lembaga = '-';
                    }
                    var btn_pilih = '<button type="button" id="pilih' + lhkpn + '" class="btn btn-sm btn-success btn_s" onClick="f_pilih(this)" data-id="' + lhkpn +'" data-id-audit="' + idaudit +'" data-no-st="' + noST +'" data-nik="' + nikPN + '" data-nama="' + namaPN + '" data-jabatan="' + jabatan + '" data-lembaga="' + lembaga + '" data-agenda="' + agenda + '">Pilih</button>';
                    var btn_batal = '<button type="button" id="pilih' + lhkpn + '" class="btn btn-sm btn-danger notsu" onClick="f_pilih(this)" data-id="' + lhkpn +'" data-id-audit="' + idaudit +'" data-no-st="' + noST +'" data-nik="' + nikPN + '" data-nama="' + namaPN + '" data-jabatan="' + jabatan + '" data-lembaga="' + lembaga + '" data-agenda="' + agenda + '">Batal</button>';
                    rowCount = ' ';
                    $('#table_PN tbody').append(
                        '<tr id="row' + lhkpn + '">' +
                        '<td><label>' + namaPN + '</label> (' + nikPN + ')</td>' +
                        '<td><label>' + agenda + '</label> </td>' +
                        '<td><label>' + jabatan + '</label> </td>' +
                        '<td><label>' + lembaga + '</label> </td>' +
                        '<td><label>' + noST + '</label> </td>' +
                        '<td>' + ((pilih_id.includes(lhkpn)) ? btn_batal : btn_pilih ) + '</td>' +
                        "</tr>"
                    )

                    ii++;
                    rowCount++;
                    $('#listNamaPN').val($('#listNamaPN').val() + ',' + data[i]["NAMA"]);
                };
            } else {
                $('#loader_area').hide();
                var namaPN = $('#listNamaPN').val().split(',');
            }
        }
        
        function checkPNValid($hasilSearch) {
            var tempHasilSearch = $hasilSearch.replace(',', '');
            var tempPN = $('#listNamaPN').val().replace(',', '');
            if($('#table_PN tr').length > 1) {
                if(tempPN.includes(tempHasilSearch)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
        
    });

    function f_pilih(ele) {
        // ele.preventDefault();
        var rownumber = '';
        var idlhkpn = $(ele).data('id');
        var nik = $(ele).data('nik');
        var nama = $(ele).data('nama');
        var jabatan = $(ele).data('jabatan');
        var lembaga = $(ele).data('lembaga');
        var agenda = $(ele).data('agenda');
        var no_st = $(ele).data('no-st');
        var id_audit = $(ele).data('id-audit');

        if ($(ele).hasClass('btn_s')) {
            pilih_id.push(String(idlhkpn));
            pilih_id_audit.push(String(id_audit));
            $('#listPilihId').val(pilih_id);
            $('#listPilihIdAudit').val(pilih_id_audit);
            if(pilih_id.length == 0) {
                $('.pull-left').hide();
            } else {
                $('.pull-left').show();
                $('#jmlPN').text(pilih_id.length)
            }            
            $(ele).html('Batal').toggleClass('btn_s notsu');
            $(ele).html('Batal').toggleClass('btn-success btn-danger');

            $('#table_pilih_PN tbody').append(
                '<tr id="rowpilih' + idlhkpn + '">' +
                    // '<td><label>' + pilih_id.length + '</label> </td>' +
                    '<td><label>' + nama + '</label> (' + nik + ')</td>' +
                    '<td><label>' + agenda + '</label> </td>' +
                    '<td><label>' + jabatan + '</label> </td>' +
                    '<td><label>' + lembaga + '</label> </td>' +
                    '<td><label>' + no_st + '</label> </td>' +
                    '<td><button type="button" id="pilih" class="btn btn-xs btn-danger btn_s" onClick="f_hapus(this)" data-id="' + idlhkpn + '" data-id-audit="' + id_audit + '" data-nama="' + nama + '"><span class="glyphicon glyphicon-remove"></span></button></td>' +
                '</tr>'
            )
        } else {
            rownumber = '#rowpilih'+idlhkpn;
            $(rownumber).remove();
            $(ele).html('Pilih').toggleClass('notsu btn_s');
            $(ele).html('Pilih').toggleClass('btn-danger btn-success');
            f_batal(ele);
        }
    };

    function f_hapus(ele) {
        var buttonPN = '#pilih'+$(ele).data('id');
        var rownumber = '#rowpilih'+$(ele).data('id');
        $(rownumber).remove();
        $(buttonPN).html('Pilih').toggleClass('notsu btn_s');
        $(buttonPN).html('Pilih').toggleClass('btn-danger btn-success');
        f_batal(ele);
    }

    function f_batal(ele) {
        var idlhkpn = $(ele).data('id');
        var idaudit = $(ele).data('id-audit');
        var nama = $(ele).data('nama');
        pilih_id = $.grep(pilih_id, function(value) {
            return value != idlhkpn;
        });
        pilih_id_audit = $.grep(pilih_id_audit, function(value) {
            return value != idaudit;
        });
        $('#listPilihId').val(pilih_id);
        $('#listPilihIdAudit').val(pilih_id_audit);
        if(pilih_id.length == 0) {
            $('.pull-left').hide();
        } else {
            $('#jmlPN').text(pilih_id.length)
        }    
    }


    

</script>

