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
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>SOSIALISASI</strong></div>
    </div>
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
                            <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Instansi:</label>
                                    <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Bimtek:</label>
                                <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[BIMTEK]' style="border:none;padding:6px 0px;" id='CARI_BIMTEK' value='' placeholder="-- Pilih Bimtek --">
                                </div>
                            </div>
                        </div>
                      </div>
                        <div class="col-md-5">
                             
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Keterangan:</label>
                                <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[STATUS]' style="border:none;padding:6px 0px;" id='CARI_STATUS' value='' placeholder="-- Pilih Keterangan --">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Tanggal:</label>
                                <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                    <input type='text' class="form-control date-picker input-sm" name='CARI[TANGGAL]' id='CARI_TANGGAL' value=''  onkeydown="return false" autocomplete="off" placeholder='DD/MM/YYYY'>
                                </div>
                            </div>
                            <!--<div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Nomor Regulasi" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="TEXT"/>
                                </div>
                            </div>-->

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
              <div class="col-md-2">
              <?php
              echo '<button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/reg_sos/add_form_sosialisasi"><i class="fa fa-plus"></i> Tambah Data</button>';
              ?>
              </div>
              </div>
				<div class="box-body">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th align="center" width="2%">No</th>
                                <th>Instansi</th>
                                <th>Unit Kerja</th>
                                <th>Bimtek</th>
                                <th>Tempat</th>
                                <th>Tanggal</th>
                                <th>Waktu Pelaksanaan</th>
                                <th>Pelaksana</th>
                                <th>Jumlah Peserta</th>
                                <th>Keterangan</th>
                                <th width="120px">Aksi</th>
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
	<?php // if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2"): ?>
        <?php if ($this->session->userdata('ID_ROLE') !== "3" || $this->session->userdata('ID_ROLE') !== "4"): ?>
		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='3122' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
	<?php else: ?>
		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instansi --\">");
	<?php endif; ?>

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
				$("#CARI_INSTANSI").val(iins).trigger("change");

			}
		}
	});

	};

    var clearPencarian = function() {
        $('#TEXT').val('');
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    var submitCari = function() {
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    var btnEditOnClick = function (self) {
        url = $(self).attr('href');
        console.log(url);
        $('#loader_area').show();
        $.post(url, function (html) {
            $('#loader_area').hide();
            OpenModalBox('Edit Sosialisasi', html, '', 'standart');
        });
        return false;

    };

    var btnDeleteOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('KONFIRMASI ULANG', html, '');
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
            "sAjaxSource": "<?php echo base_url('ereg/reg_sos/load_data_sosialisasi'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
				passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
				passData.push({"name": "CARI[TEXT]", "value": $("#TEXT").val()});
				passData.push({"name": "CARI[BIMTEK]", "value": $("#CARI_BIMTEK").val()});
				passData.push({"name": "CARI[TANGGAL]", "value": $("#CARI_TANGGAL").val()});
				passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true},
                {"mDataProp": "INST_NAMA", bSearchable: true},
                {"mDataProp": "UK_NAMA", bSearchable: true},
                {"mDataProp": "BIMTEK", bSearchable: true},
                {"mDataProp": "TEMPAT", bSearchable: true},
                {"mDataProp": "TANGGAL", bSearchable: true},
                {"mDataProp": "WAKTU_PELAKSANAAN", bSearchable: true},
                {"mDataProp": "PELAKSANA", bSearchable: true},
                {"mDataProp": "JUMLAH_PESERTA", bSearchable: true},
                {"mDataProp": function (source, type, val){
                    if(source.STATUS==1){
                        return '<b><font color="green">Sudah Divalidasi KPK</font></b>';
                    }else{
                        return '<b><font color="red">Belum Divalidasi KPK</font></b>';
                    }
                    
                    }, 
                    bSearchable: true
                },
                {"mDataProp": function (source, type, val) {
                        var is_active = $('#CARI_STATUS').val();
                        var btnEdit = "";
                        var btnApprove;

                        var btnEdit = '<button type="button" class="btn btn-warning btn-sm btn-edit" href="index.php/ereg/reg_sos/edit_form_sosialisasi/' + source.ID_SOSIALISASI + '" title="Edit" onclick="btnEditOnClick(this);"><i class="fa fa-pencil"></i></button>';

                        var btnDelete = '<button type="button" class="btn btn-danger btn-sm btn-delete" href="index.php/ereg/reg_sos/delete_form_sosialisasi/' + source.ID_SOSIALISASI + '" title="Delete" onclick="btnDeleteOnClick(this);"><i class="fa fa-trash" style="color:white;"></i></button>';
                        <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31){ ?>
                        if(source.STATUS==0){
                            btnApprove = '<button type="button" class="btn btn-success btn-sm" href="index.php/ereg/reg_sos/approve_form_sosialisasi/' + source.ID_SOSIALISASI + '" title="Sudah Divalidasi KPK" onclick="btnDeleteOnClick(this);"><i class="fa fa-check" style="color:white;"></i></button>';
                        }else{
                            btnApprove = '<button type="button" class="btn btn-danger btn-sm" href="index.php/ereg/reg_sos/approve_form_sosialisasi/' + source.ID_SOSIALISASI + '" title="Belum Divalidasi KPK" onclick="btnDeleteOnClick(this);"><i class="fa fa-times" style="color:white;"></i></button>';
                        }
                        
                        <?php }else{ ?>
                        var btnApprove = "";
                        <?php } ?>
                        return (btnEdit + " " + btnDelete + " " +btnApprove).toString();
                        
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
        data_is_active = [{id: 9, text: '--Semua Data--'},{id: 1, text: 'Sudah Divalidasi KPK'}, {id: 0, text: 'Belum Divalidasi KPK'}];
        $('#CARI_STATUS').select2({data: data_is_active});
        $('#CARI_STATUS').val(9).trigger('change');  

        data_is_bimtek = [{id: 9, text: '--Semua Data--'},{id: 1, text: 'e-Filling'}, {id: 2, text: 'e-Registration'}, {id: 3, text: 'Regulasi'}, {id: 4, text: 'Rakor Monev'}];
        $('#CARI_BIMTEK').select2({data: data_is_bimtek});
        $('#CARI_BIMTEK').val(9).trigger('change'); 

        $('#CARI_TANGGAL').datepicker({
            maxViewMode: "-17 years",
            format: "dd/mm/yyyy",
            autoclose: true
        });

		initiateCariInstansi();
		gtblDaftarIndividual = initDtTbl(tblDaftarInstansi);



        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                $('#loader_area').hide();
                OpenModalBox('Tambah Sosialisasi', html, '');
            });
            return false;
        });
	});


</script>
