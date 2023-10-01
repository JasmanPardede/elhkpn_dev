<?php  ?>
<style media="screen">
    .btn-filter-group {
      padding: 15px;
    }
    .table-striped>tbody>tr:nth-of-type(odd) {
      background-color: honeydew;
    }
    thead tr {
      background-color: #337ab7;
      height: 60px;
      color: ghostwhite;
    }
    table .btn {
      margin: 3px 1px;
    }
    .btn-same {
      width: 73px;
    }

    table.dataTable td {
        padding: .3em;
    }

    .title h3 {
      margin-top:0;
      padding-bottom: 20px;
      border-bottom: 3px solid #d2d6de;
    }
</style>

<div class="periksa-content ">
  <div class="container-fluid">
    <div class="title">
      <h3><i class="fa fa-list"></i> Pemeriksaan E-LHKPN</h3>
    </div>
    <div class="table-filter">
      <form id="form-filter" class="form-horizontal" action="" method="post">
        <div class="box-body">
          <div class="col-md-6">
            <div class="row">
              <div class="form-group">

                <div class="form-group">

                  <label class="col-sm-4 control-label">Tahun Lapor :</label>
                  <div class="col-sm-5">
                    <!-- <input id="iTahunLapor" type="text" class="form-control" name="" value=""> -->
                    <select class="form-control" name="iTahunLapor" id="iTahunLapor">
                      <option value="">-- Semua --</option>
                      <?php foreach ($tahun_lapor as $value): ?>
                        <option value="<?php echo $value->tahun_lapor ?>"><?php echo $value->tahun_lapor ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label" for="iStatusPeriksa">Status Pemeriksaan</label>
                  <div class="col-sm-5">
                    <select class="form-control" name="iStatusPeriksa" id="iStatusPeriksa">
                      <option value="">-- Semua --</option>
                      <option value="1">Baru</option>
                      <option value="2">Proses</option>
                      <option value="3">Selesai</option>
                      <!-- <option value="1">Penelaahan / Pemeriksaan Baru</option>
                      <option value="2">Proses Penelaahan / Pemeriksaan</option>
                      <option value="3">Penelaahan / Pemeriksaan Selesai</option> -->
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label" for="">Jenis Penugasan</label>
                  <div class="col-sm-8">
                    <!-- <div class="radio"> -->
                      <label class="radio-inline">
                        <input type="radio" name="iJenisPenugasan" id="iJenisPenugasan1" value="" checked>
                        Semua
                      </label>
                    <!-- </div> -->
                    <!-- <div class="radio"> -->
                      <label class="radio-inline">
                        <input type="radio" name="iJenisPenugasan" id="iJenisPenugasan2" value="0">
                        Penelaahan
                      </label>
                    <!-- </div> -->
                    <!-- <div class="radio"> -->
                      <label class="radio-inline">
                        <input type="radio" name="iJenisPenugasan" id="iJenisPenugasan3" value="1">
                        Pemeriksaan
                      </label>
                    <!-- </div> -->
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- end col-md-6 left part -->
          <div class="col-md-6">
            <div class="row">
              <div class="form-group">
                <label for="iLembaga" class="col-sm-4 control-label">Lembaga :</label>
                <?php // TODO: nanti di uncomment ?>
				<!-- <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                      <input type='text' class="input-sm form-control" name='iLembaga' style="border:none;padding:6px 0px;" id='iLembaga' value='' placeholder="-- Pilih Instasi --">
                </div> -->
                <div class="col-sm-5">
                  <input id="iLembaga" type="text" class="form-control" name="iLembaga" value="">
                </div>
              </div>

              <div class="form-group">
                <label for="iNamaPN" class="col-sm-4 control-label">Nama PN:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="iNamaPN" value="" id="iNamaPN">
                </div>
              </div>

              <div class="form-group">
              <label for="iNamaPIC" class="col-sm-4 control-label">PIC:</label>
                <div class="col-sm-5">
                  <select id="iNamaPIC" class="form-control" name="iNamaPIC">
                    <option value="">-- Semua --</option>
                      <?php foreach ($users_admin as $ua): ?>
                      <option value="<?php echo $ua->ID_USER; ?>"><?php echo $ua->NAMA.' ('.$ua->ROLE.')'; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label for="iNomorSPT" class="col-sm-4 control-label">Nomor SPT:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="iNomorSPT" value="" id="iNomorSPT">
                  <div class="form-group">
                      <div class="btn-filter-group">
                          <button type="submit" id="btn-filter" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                          <button type="button" id="btn-reset" class="btn btn-sm btn-default"> Clear</button>
                      </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <!-- end right part -->

        </div>
      </form>
    </div>
    <table id="table-periksa" class="display table-striped" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th width="30">No.</th>
          <th>Nama / No Agenda</th>
          <th>Jabatan</th>
          <th>Lembaga</th>
          <th>PIC</th>
          <th>Periode Penugasan</th>
          <th>Status Pemeriksaan</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>

</div>

<script type="text/javascript">
    
    function btnInfoJabatanOnClick (ele){
        var id_pn = $(ele).data('idpn');
        var url = 'index.php/eaudit/periksa/getJabatanPNPeriksa/'+id_pn;
        $.post(url, function(html) {
            OpenModalBox('Info Jabatan', html, '', 'large');
        });
        return false;
    }
    
$(document).ready(function() {
	// initiateCariInstansi();
  $('#iNamaPIC').select2();

  <?php $xxx = 1; if ($xxx) : //$this->session->userdata()['ID_ROLE'] == 24 || $this->session->userdata()['ID_ROLE'] == 25): ?>
  // preparing data tables
  var table = $('#table-periksa').DataTable({
    "destroy": true,
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    "ajax": {
      "url": '<?php echo site_url("index.php/eaudit/periksa/ajax_list_periksa/"); ?>',
      // "dataSrc": "",
      "type" : "POST",
      "data" : function (data) {
        data.tgl_lapor = $('#iTahunLapor').val();
        data.nama_lengkap = $('#iNamaPN').val();
        data.status_periksa = $('#iStatusPeriksa').val();
        data.lembaga = $('#iLembaga').val();
        data.jenis_penugasan = $('input[name="iJenisPenugasan"]:checked').val();
        data.id_pic = $('#iNamaPIC').val();
        data.nomor_surat_tugas = $('#iNomorSPT').val();
      }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
      {
        "targets": [ 0,5,6 ], //first column / numbering column
        "orderable": false, //set not orderable
      }
    ],
    "columns": [
      null,
      { "width": "25%" },
      null,
      null,
      null,
      { "width": "5%" },
      { "width": "5%" },
      { "width": "5%" }
    ]
  });
  <?php endif; ?>

  // proses filter
  $('#btn-filter').click(function(e){
    e.preventDefault();
    // console.log('-- filter table');
    // console.log($('#iTahunLapor').val());
    // console.log($('#iNamaPN').val());
    // console.log($('#iStatusPeriksa').val());
    // console.log($('#iLembaga').val());
    // console.log($('input[name="iJenisPenugasan"]:checked').val());
    $('#table-periksa').DataTable().ajax.reload();
    table.ajax.reload();
  });

  $('#btn-reset').click(function(e) {
    e.preventDefault();
    $('#form-filter')[0].reset();
    // console.log('-- reset form filter');
    $('#table-periksa').DataTable().ajax.reload();
    table.ajax.reload();
  })

    $('#table-periksa tbody').on( 'click', '.btn-modal-ikhtisar', function (e) {
      var id_lhkpn = $(this).data('idlhkpn');
      var url = '<?php echo site_url("/index.php/ever/ikthisar/cetak"); ?>' + '/' + id_lhkpn;
      window.location.href = url;
      table.ajax.reload();
      $('#table-periksa').DataTable().ajax.reload();
      alertify.warning('Memproses Cetak Ikhtisar: ' + id_lhkpn + '\n' + id_nama_pn);
    })
    
    
  // proses cetak kkp
  $('#table-periksa tbody').on( 'click', '.btn-kkp', function (e) {
    e.preventDefault();

    // update status jika status pemeriksaan = 1 ( pemeriksaan baru ), menjadi 2 (dalam proses pemeriksaan)
    var status_periksa = $(this).data('status');
    var id_lhkpn = $(this).data('idlhkpn');
    var id_audit = $(this).data('idaudit');

    // proses cetak kkp
    var id_nama_pn = $(this).data('namapn');
    var url = '<?php echo site_url("/index.php/eaudit/CetakKKP/export_kkp"); ?>/'+ id_lhkpn +'/'+ id_audit;
    window.location.href = url;
    
    if (status_periksa == 1) {
      // var url = '<?php echo site_url("/index.php/eaudit/periksa/ajax_set_status_periksa"); ?>/'+ id_audit + '/2';
      var url = '<?php echo site_url("/index.php/eaudit/periksa/ajax_set_status_periksa"); ?>' + '/';
      var state_nost = $(this).data('nost');
      var state_idaudit = $(this).data('idaudit');
      var data = {
        'no_st' : state_nost,
        'id_audit' : state_idaudit,
        'status' : '2',
        'id_lhkpn' : id_lhkpn
      }
      $.post(url, data);
    }

    table.ajax.reload();
    $('#table-periksa').DataTable().ajax.reload();
    alertify.warning('Memproses Cetak KKP: ' + id_lhkpn + '\n' + id_nama_pn);
    return;
  } );

  // proses modal input tanggal BAK
  $('#table-periksa tbody').on( 'click', '.btn-input-tgl', function (e) {
    e.preventDefault();
    url = $(this).attr('href');
    $('#loader_area').show();
    data = {
      'id_lhkpn': $(this).data('idlhkpn'),
      'id_audit': $(this).data('idaudit')
    }
    $.post(url, data, function(html) {
        OpenModalBox('Input Tanggal BAK', html, '', 'standart');
    });
    return false;
  });
  
  // preview klarif
    $('#table-periksa tbody').on( 'click', '.btn-preview-klarif', function (e) {
        var id_lhkpn = $(this).data('idlhkpn');
        var no_st = $(this).data('nost') + '';
        var id_audit = $(this).data('idaudit');
        var url1 = '#index.php/eaudit/klarifikasi/index/' + id_lhkpn + '/' + no_st.split('/').join('-') + '/' + id_audit + '/' + 1;
        var url2 = location.href.split('#')[0];
        window.open(url2 + url1,'_blank');
    });
    
  $('#table-periksa tbody').on( 'click', '.btn-periksa', function (e) {
    e.preventDefault();
    id_lhkpn = $(this).data('idlhkpn');
    no_st = $(this).data('nost') + '';
    id_audit = $(this).data('idaudit');
    url = 'index.php/eaudit/klarifikasi/index/' + id_lhkpn + '/' + no_st.split('/').join('-') + '/' + id_audit;
    $('#loader_area').show();
    ng.LoadAjaxContent(url);

    return false;
  });

  $('#table-periksa tbody').on( 'click', '.btn-bak', function (e) {
    e.preventDefault();
    id_lhkpn = $(this).data('idlhkpn');
    url = 'index.php/eaudit/klarifikasi/bak/' + id_lhkpn;
    $('#loader_area').show();
    ng.LoadAjaxContent(url);

    return false;
  });

  // proses modal input tanggal LHP
  $('#table-periksa tbody').on( 'click', '.btn-lhp', function (e) {
    e.preventDefault();
    url = '<?php echo site_url('index.php/eaudit/periksa/ajax_input_tanggal/'); ?>'+'/'+$(this).data('idaudit');
    $('#loader_area').show();
    // console.log('>> ', $(this).data('is_penelaahan'));
    data = {
      'id_lhkpn': $(this).data('idlhkpn'),
      'id_audit': $(this).data('idaudit'),
      'is_lhp' : 'true',
      'is_penelaahan' : $(this).data('ispenelaahan'),
      'is_detail_lhp' : $(this).data('islhpdetail')
    }
    $.post(url, data, function(html) {
      var modal_title = ( data.is_penelaahan == 1 ) ? 'Input Lembar Telaah' : (( data.is_detail_lhp == 1 ) ? 'Data LHP' : 'Input Tanggal LHP');
      //console.log('>> ' + modal_title );
      OpenModalBox(modal_title, html, '', 'standart');
    });
    return false;
  });

  // proses modal list semua data KKP
  $('#table-periksa tbody').on( 'click', '.btn-modal-kkp', function (e) {
    e.preventDefault();
    url = '<?php echo site_url('index.php/eaudit/periksa/ajax_modal_kkp/'); ?>'+'/'+ $(this).data('idaudit') + '/' + $(this).data('idlhkpn');
    // console.log(url);
    $('#loader_area').show();
    data = {
      'id_lhkpn': $(this).data('idlhkpn'),
      'id_audit': $(this).data('idaudit'),
      'is_lhp' : 'true',
      'is_penelaahan' : $(this).data('idaudit')
    }
    $.post(url, data, function(html) {
        OpenModalBox('', html, '', 'standart');
    });
    return false;
  });

  // proses modal list semua data Pengaduan
  $('#table-periksa tbody').on( 'click', '.btn-modal-pelaporan', function (e) {
    e.preventDefault();
    url = '<?php echo site_url('index.php/eaudit/periksa/ajax_modal_pelaporan/'); ?>'+'/'+ $(this).data('idaudit') + '/' + $(this).data('idlhkpn');
    // console.log(url);
    $('#loader_area').show();
    data = {
      'id_lhkpn': $(this).data('idlhkpn'),
      'id_audit': $(this).data('idaudit')
    }
    $.post(url, data, function(html) {
        OpenModalBox('', html, '', 'standart');
    });
    return false;
  });

  // proses modal list semua data Pengaduan
  $('#table-periksa tbody').on( 'click', '.btn-progress', function (e) {
    e.preventDefault();
    url = '<?php echo site_url('index.php/eaudit/periksa/ajax_modal_progress/'); ?>'+'/'+ $(this).data('idaudit') + '/' + $(this).data('idlhkpn');
    // console.log(url);
    $('#loader_area').show();
    data = {
      'id_lhkpn': $(this).data('idlhkpn'),
      'id_audit': $(this).data('idaudit')
    }
    $.post(url, data, function(html) {
        OpenModalBox('Progress Pemeriksaan', html, '', 'standart');
    });
    return false;
  });

  $('#table-periksa tbody').on( 'click', '.btn-hasil-analisis', function (e) {
    e.preventDefault();
    id_audit = $(this).data('idaudit');
    url = 'index.php/eaudit/periksa/list_data_hasil_analisis/' + id_audit;
    $('#loader_area').show();
    ng.LoadAjaxContent(url);

    return false;
  });

} );

</script>
