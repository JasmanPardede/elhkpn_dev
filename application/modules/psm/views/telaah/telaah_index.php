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
 * Hak Cipta
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
         <h1>
            <i class="fa fa-bullhorn"></i>
            Telaah Pengaduan
          </h1>
    <?php echo $breadcrumb; ?>
</section>



<!-- Main content -->
<section class="content">
    <div class="row">
		<div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-body" >
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">
                    <div class="col-md-5">
                        <div class="row">
                                <div class="form-group" style="margin-bottom:10px">
                                    <label class="col-sm-4 control-label">Instansi :</label>
                                    <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Kode :</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control input-sm" placeholder="Kode Pengaduan" name="CARI[KODE_PENGADUAN]" value="<?php echo @$CARI['KODE_PENGADUAN']; ?>" id="KODE_PENGADUAN"/>
                                    </div>
                                </div>
                            </div>
                      </div>
                        <div class="col-md-5">
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Keterangan :</label>
                                <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[STATUS]' style="border:none;padding:6px 0px;" id='CARI_STATUS' value='' placeholder="-- Pilih Keterangan --">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tanggal :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="date-picker form-control" name="CARI[TANGGAL_PENGADUAN]" onkeydown="return false;" autocomplete="off" placeholder="Tanggal Pengaduan" value="<?php echo @$CARI['TANGGAL_PENGADUAN']; ?>" id="TANGGAL_PENGADUAN">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Nama/NIK PN" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="TEXT"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>

        <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
  
              </div>
				<div class="box-body">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th align="center" width="2%">No</th>
                                <th>Kode Pengaduan</th>
                                <th>Nama</th>
                                <th class="hidden-xs hidden-sm">Instansi</th>
                                <th>Jabatan</th>
                                <th>Nama Pelapor</th>
                                <th>Tanggal Pengaduan</th>
                                <th>Status</th>
                                <th width="20px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.col -->
</div>
</div><!-- /.row -->
</section><!-- /.content -->

<script language="javascript">

	var gtblDaftarIndividual;
    var data_is_active;


    var initiateCariInstansi = function () {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();

        // $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='3122' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");

        $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instansi --\">");

        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga'); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $('#CARI_INSTANSI').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga'); ?>/" + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga'); ?>",
            dataType: "json",
            async: false,
        }).done(function (data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI').select2(cari_instansi_cfg);

                if (iins != null) {
                    // $("#CARI_INSTANSI").val(iins).trigger("change");
                }
            }
        });

	};




    var clearPencarian = function() {
        $('#TEXT').val('');
        $('#CARI_INSTANSI').val('').trigger('change');
        $('#KODE_PENGADUAN').val('');
        $('#TANGGAL_PENGADUAN').val('');
        $('#CARI_STATUS').val(9).trigger('change');  
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    var submitCari = function() {
        reloadTableDoubleTime(gtblDaftarIndividual);
    };



    var btnDetailOnClick = function (self) {
        url = $(self).attr('href');
        $('#loader_area').show();
        $.post(url, function (html) {
            $('#loader_area').hide();
            OpenModalBox('Detail Data', html, '', 'large');
        });
        return false;

    };


    var tblDaftarInstansi = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarInstansi'},
        conf: {
			"cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('psm/telaah/load_data'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
				passData.push({"name": "CARI[TANGGAL_PENGADUAN]", "value": $("#TANGGAL_PENGADUAN").val()});
				passData.push({"name": "CARI[KODE_PENGADUAN]", "value": $("#KODE_PENGADUAN").val()});
				passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
				passData.push({"name": "CARI[TEXT]", "value": $("#TEXT").val()});
				passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true},
                {"mDataProp": function (source, type, val){
                        return 'PSM-'+source.ID_PELAPORAN;    
                    }, 
                    bSearchable: true
                },
                {"mDataProp": "NAMA", bSearchable: true},
                {"mDataProp": "INST_NAMA", bSearchable: true},
                {"mDataProp": "NAMA_JABATAN", bSearchable: true},
                {"mDataProp": "NAMA_PELAPOR", bSearchable: true},
                {"mDataProp": function (source, type, val){
                        return source.CREATED_TIME_INDONESIA;    
                    }, 
                    bSearchable: true
                },
                {"mDataProp": function (source, type, val){
                    if(source.IS_VERIFICATION==0){
                        return '<b><font color="blue">Baru</font></b>';
                    }else if(source.IS_VERIFICATION==1){
                        return '<b><font color="green">Diteruskan ke Penelaahan</font></b>';
                    }else if(source.IS_VERIFICATION==2){
                        return '<b><font color="red">Tidak ditindaklanjuti</font></b>';
                    }else{
                        return '<b><font color="yellow">---</font></b>';
                    }
                    
                    }, 
                    bSearchable: true
                },
                {"mDataProp": function (source, type, val) {
                        var is_active = $('#CARI_STATUS').val();
                        var btnEdit = "";
                        var btnApprove;
                        var btnLook;
                        var btnLook = '<button class="btn btn-success btn-sm btn-detail" href="index.php/psm/telaah/detail_form/' + source.encrypt_id + '" title="Lihat" onclick="btnDetailOnClick(this);"><i class="fa fa-eye"></i></button>';

                        return (btnLook).toString();
                        
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                var stl = false;
                if (aData.STS_J == 10 || aData.STS_J == 11 || aData.STS_J == 15) {
                    stl = true;
                }
                return nRow;
            }
        }
    };


    $(document).ready(function () {

        $('.date-picker').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });


        initiateCariInstansi();

        data_is_active = [{id: 9, text: '--Semua Data--'},{id: 0, text: 'Baru'},{id: 1, text: 'Diteruskan ke Penelaahan'}, {id: 2, text: 'Tidak ditindaklanjuti'}];
        $('#CARI_STATUS').select2({data: data_is_active});
        $('#CARI_STATUS').val(9).trigger('change');  

		gtblDaftarIndividual = initDtTbl(tblDaftarInstansi);

	});


</script>
