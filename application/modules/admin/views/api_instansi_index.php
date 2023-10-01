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
            <i class="fa fa-code"></i>
            API Instansi
          </h1>
    <?php echo $breadcrumb; ?>
</section>



<!-- Main content -->
<section class="content">



<div class="panel box box-info">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                        Dokumentasi API Instansi
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET TOKEN</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>login</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  { <br>
                                   &nbsp;&nbsp; "email" : email yang telah di registrasi,<br>
                                   &nbsp;&nbsp; "password" : password dari KPK<br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET WAJIB LAPOR</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>wajib_lapor</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  { <br>
                                   &nbsp;&nbsp; "no_identitas": NIK atau ID_PN<br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET HARTA</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>harta</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  { <br>
                                   &nbsp;&nbsp; "no_identitas": NIK atau ID_PN<br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET KELUARGA</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>keluarga</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  { <br>
                                   &nbsp;&nbsp; "no_identitas": ID_LHKPN<br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET PENGUMUMAN BATCH</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>pengumuman_batch</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  { <br>
                                   &nbsp;&nbsp; "no_identitas": NIK atau ID_PN<br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET WAJIB LAPOR KEMENKEU (List)</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>kemenkeu/wajib-lapor-kemenkeu</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  {
                                  <br>
                                   &nbsp;&nbsp; "limit": Nilai limit untuk mendapatkan data dengan nilai default yaitu 0 jika tidak disertakan
                                  <br>
                                   &nbsp;&nbsp; "offset": Nilai offset untuk mendapatkan data dengan nilai default yaitu 0 jika tidak disertakan
                                  <br>
                                   &nbsp;&nbsp; "order_by": Untuk mengurutkan data. Nilai parameter ini harus dalam bentuk string dan bernilai tahun_wl, nik, nip_nrp atau nama
                                  <br>
                                   &nbsp;&nbsp; "order_direction": Untuk mengurutkan arah data yaitu secara Ascending (asc) atau Descending (desc). Nilai parameter ini harus berbentuk string dan bernilai asc atau desc dan parameter ini menjadi mandatory jika order_by disertakan
                                  <br>
                                   &nbsp;&nbsp; "tahun_wl": Paramater filter berdasarkan Tahun WL PN. Date Format: Y
                                  <br>
                                   &nbsp;&nbsp; "nik": Parameter filter berdasarkan NIK PN
                                  <br>
                                   &nbsp;&nbsp; "nip_nrp": Paramater filter berdasarkan NIP NRP PN
                                  <br>
                                   &nbsp;&nbsp; "nama": Paramater filter berdasarkan nama lengkap PN
                                  <br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET WAJIB LAPOR KEMENKEU (Detail)</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>kemenkeu/wajib-lapor-kemenkeu/detail</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  {
                                  <br>
                                   &nbsp;&nbsp; "nik": nik ini seperti yang tersedia pada list Wajib Lapor Kemenkeu
                                  <br>
                                   &nbsp;&nbsp; "tahun_wl": tahun_wl ini seperti yang tersedia pada list Wajib Lapor Kemenkeu. Date Format: Y
                                  <br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET HARTA KEMENKEU (List)</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>kemenkeu/harta-kemenkeu</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  {
                                  <br>
                                   &nbsp;&nbsp; "limit": Nilai limit untuk mendapatkan data dengan nilai default yaitu 0 jika tidak disertakan
                                  <br>
                                   &nbsp;&nbsp; "offset": Nilai offset untuk mendapatkan data dengan nilai default yaitu 0 jika tidak disertakan
                                  <br>
                                   &nbsp;&nbsp; "order_by": Untuk mengurutkan data. Nilai parameter ini harus dalam bentuk string dan bernilai nik, nama, thn_lapor, jenis_pelaporan_rinci, status_laporan
                                  <br>
                                   &nbsp;&nbsp; "order_direction": Untuk mengurutkan arah data yaitu secara Ascending (asc) atau Descending (desc). Nilai parameter ini harus berbentuk string dan bernilai asc atau desc dan parameter ini menjadi mandatory jika order_by disertakan
                                  <br>
                                  &nbsp;&nbsp; "nik": Parameter filter berdasarkan NIK PN
                                  <br>
                                   &nbsp;&nbsp; "nama": Parameter filter berdasarkan nama PN
                                  <br>
                                   &nbsp;&nbsp; "thn_lapor": Paramater filter berdasarkan Tahun Lapor PN. Date Format: Y
                                  <br>
                                  }
                              </td>
                          </tr>
                      </table>
                      <hr>
                      <table>
                          <tr>
                              <td colspan="3" style="font-weight:bold">GET HARTA KEMENKEU (Detail)</td>
                          </tr>
                          <tr>
                              <td>URL</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td><?= $this->config->item('apiInstansiURL') ?>kemenkeu/harta-kemenkeu/detail</td>
                          </tr>
                          <tr>
                              <td>Method</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>POST</td>
                          </tr>
                          <tr>
                              <td>Authorization</td>
                              <td>&nbsp;:&nbsp;</td>
                              <td>Bearer</td>
                          </tr>
                          <tr>
                              <td style="vertical-align:top">Params</td>
                              <td style="vertical-align:top">&nbsp;:&nbsp;</td>
                              <td>
                                  {
                                  <br>
                                   &nbsp;&nbsp; "nik": nik ini seperti yang tersedia pada list Harta Kemenkeu
                                  <br>
                                   &nbsp;&nbsp; "thn_lapor": thn_lapor ini seperti yang tersedia pada list Harta Kemenkeu
                                  <br>
                                  }
                              </td>
                          </tr>
                      </table>
                    </div>
                  </div>
                </div>


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
                            </div>
                      </div>
                        <div class="col-md-5">
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Status :</label>
                                <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[STATUS]' style="border:none;padding:6px 0px;" id='CARI_STATUS' value='' placeholder="-- Pilih Status --">
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
                 <button class="btn btn-sm btn-primary" id="btn-add" href="index.php/admin/api_instansi/add_form"><i class="fa fa-plus"></i> Generate API</button>
              </div>
				<div  class="box-body table-responsive">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                    <table  id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th align="center" width="2%">No</th>
                                <th width="200px">Lembaga</th>
                                <th>Email</th>
                                <th>Akses API</th>
                                <th>IP Permission</th>
                                <th>Diakses</th>
                                <th width="70px">Dibuat</th>
                                <th>Status</th>
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

<script src="<?php echo base_url();?>portal-assets/js/notify.js"></script>
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


    var btnDeleteOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Ubah Status', html, '');
        });
        return false;
    };
    var btnEmailOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Kirim API', html, '');
        });
        return false;
    };
    var btnPasswordOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Set Password', html, '');
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
            "sAjaxSource": "<?php echo base_url('admin/api_instansi/load_data'); ?>",
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
                {"mDataProp": "INST_NAMA", bSearchable: true},
                {"mDataProp": "EMAIL", bSearchable: true},
                {"mDataProp": "AKSES_API", bSearchable: true},
                {"mDataProp": "IP_PERMISSION", bSearchable: true},
                {"mDataProp": "DIAKSES", bSearchable: true},
                {"mDataProp": "CREATED_TIME_INDONESIA", bSearchable: true},
                {"mDataProp": function (source, type, val){
                    if(source.is_active==1){
                        return '<label class="label label-success">Aktif</label>';
                    }else{
                        return '<label class="label label-danger">Non Aktif</label>';
                    }

                    },
                    bSearchable: true
                },
                {"mDataProp": function (source, type, val) {
                        var btnApprove;
                        // var btnEmail = '<button class="btn btn-info btn-sm btn-warning" title="Kirim email" href="index.php/admin/api_instansi/email_form/' + source.id + '" onclick="btnEmailOnClick(this);"><i class="fa fa-envelope"></i></button>';
                        var btnPassword = '<button class="btn btn-info btn-sm btn-warning" title="Set Password" href="index.php/admin/api_instansi/password_form/' + source.id + '" onclick="btnPasswordOnClick(this);"><i class="fa fa-key"></i></button>';
                        var btnLogo = '<button class="btn btn-info btn-sm btn-primary" title="Edit API" href="index.php/admin/api_instansi/edit_form/' + source.id + '" onclick="btnEditOnClick(this);"><i class="fa fa-pencil"></i></button>';

                        if(source.IS_ACTIVE!=1){
                            btnApprove = '<button type="button" class="btn btn-success btn-sm" href="index.php/admin/api_instansi/approve_form/' + source.id + '" title="Aktifkan API" onclick="btnDeleteOnClick(this);"><i class="fa fa-check" style="color:white;"></i></button>';
                        }else{
                            btnApprove = '<button type="button" class="btn btn-danger btn-sm" href="index.php/admin/api_instansi/approve_form/' + source.id + '" title="Non Aktifkan API" onclick="btnDeleteOnClick(this);"><i class="fa fa-times" style="color:white;"></i></button>';
                        }
                        return (btnLogo+' '+btnPassword+' '+btnApprove).toString();

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

    var btnEditOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Edit API Instansi', html, '');
        });
        return false;
    };


    $(document).ready(function () {

        $('.date-picker').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });


        initiateCariInstansi();

        data_is_active = [{id: 9, text: '--Semua Data--'},{id: 1, text: 'Aktif'},{id: 0, text: 'Non Aktif'}];
        $('#CARI_STATUS').select2({data: data_is_active});
        $('#CARI_STATUS').val(9).trigger('change');

		gtblDaftarIndividual = initDtTbl(tblDaftarInstansi);



        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                $('#loader_area').hide();
                OpenModalBox('Generate API', html, '');
            });
            return false;
        });

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                $('#loader_area').hide();
                OpenModalBox('Generate API', html, '');
            });
            return false;
        });





	});


</script>
