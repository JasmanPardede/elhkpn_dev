<!-- QIQIE -->
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
      <h3><i class="fa fa-list"></i> Pemeriksaan Terbuka E-LHKPN</h3>
    </div>
    <div class="table-filter">
      <form id="form-filter" class="form-horizontal" action="" method="post">
        <div class="box-body">
          <div class="col-md-6">
          </div>
          <!-- end col-md-6 left part -->
          <div class="col-md-6">
            <div class="row">

            <div class="form-group">
                  <label class="col-sm-4 control-label" for="iStatusKlarif">Status Klarifikasi</label>
                  <div class="col-sm-5">
                    <select class="form-control" name="iStatusKlarif" id="iStatusKlarif">
                      <option value="">-- Semua --</option>
                      <option value="1">Sudah Klarifikasi</option>
                      <option value="2">Belum Klarifikasi</option>
                      <!-- <option value="1">Penelaahan / Pemeriksaan Baru</option>
                      <option value="2">Proses Penelaahan / Pemeriksaan</option>
                      <option value="3">Penelaahan / Pemeriksaan Selesai</option> -->
                    </select>
                  </div>
                    <button type="submit" id="btn-filter" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                    <button type="button" id="btn-reset" class="btn btn-sm btn-default"> Clear</button>
                </div>

            </div>
          </div>
          <!-- end right part -->

        </div>
      </form>
    </div>
    <table id="table-hasil-klarif" class="display table-striped" cellspacing="0" width="100%">
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

$(document).ready(function() {

  <?php $xxx = 1; if ($xxx) : //$this->session->userdata()['ID_ROLE'] == 24 || $this->session->userdata()['ID_ROLE'] == 25): ?>
  // preparing data tables
  var table = $('#table-hasil-klarif').DataTable({
    "language": {
        searchPlaceholder: "NAMA"
    },
    "destroy": true,
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    "ajax": {
      "url": '<?php echo site_url("index.php/eaudit/periksa/ajax_list_periksa_hasil_klarif/"); ?>',
      // "dataSrc": "",
      "type" : "POST",
      "data" : function (data) {
        data.status_klarif = $('#iStatusKlarif').val();
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
      { "width": "15%" },
      null,
      null,
      null,
      { "width": "5%" },
      { "width": "5%" },
      { "width": "5%" }
    ],
  });
  <?php endif; ?>

  // proses filter
  $('#btn-filter').click(function(e){
    e.preventDefault();
    $('#table-hasil-klarif').DataTable().ajax.reload();
    table.ajax.reload();
  });

  $('#btn-reset').click(function(e) {
    e.preventDefault();
    $('#form-filter')[0].reset();
    // console.log('-- reset form filter');
    $('#table-hasil-klarif').DataTable().ajax.reload();
    table.ajax.reload();
  })

    $('#table-hasil-klarif tbody').on( 'click', '.btn-modal-ikhtisar-klarif', function (e) {
      var id_lhkpn = $(this).data('idlhkpn');
      var id_nama_pn = $(this).data('id_nama_pn');
      var url = '<?php echo site_url("/index.php/ever/ikthisar/cetak_klarifikasi"); ?>' + '/' + id_lhkpn;
      window.location.href = url;
      table.ajax.reload();
      $('#table-hasil-klarif').DataTable().ajax.reload();
      alertify.warning('Memproses Cetak Ikhtisar Klarifikasi: ' + '\n' + id_nama_pn);
    })
    
    $('#table-hasil-klarif tbody').on( 'click', '.btn-modal-draft', function (e) {
      var id_lhkpn = $(this).data('idlhkpn');
      var id_nama_pn = $(this).data('id_nama_pn');

      var url = '<?php echo site_url("/index.php/ever/ikthisar/cetak_draft"); ?>' + '/' + id_lhkpn;
      window.location.href = url;
      table.ajax.reload();
      $('#table-hasil-klarif').DataTable().ajax.reload();
      alertify.warning('Memproses Cetak Draft: ' + id_nama_pn);
    })

  $('#table-hasil-klarif tbody').on( 'click', '.btn-periksa', function (e) {
    e.preventDefault();
    id_lhkpn = $(this).data('idlhkpn');
    no_st = $(this).data('nost') + '';
    id_audit = $(this).data('idaudit');
    url = 'index.php/eaudit/klarifikasi/index/' + id_lhkpn + '/' + no_st.split('/').join('-') + '/' + id_audit;
    $('#loader_area').show();
    ng.LoadAjaxContent(url);

    return false;
  });

  $('#table-hasil-klarif tbody').on( 'click', '.btn-modal-resend', function (e) {
    e.preventDefault();
    id_lhkpn = $(this).data('idlhkpn');
    id_audit = $(this).data('idaudit');
    nama_pn = '<?php echo $nama_pn; ?>';
    url = '<?php echo site_url('index.php/eaudit/klarifikasi/ajax_modal_resend/'); ?>'+'/'+ id_audit + '/' + id_lhkpn;
    $('#loader_area').show();
    data = {
      'id_lhkpn': id_lhkpn,
      'id_audit': id_audit
    }
    $.post(url, data, function(html) {
        OpenModalBox('Kirim Ulang Email Klarifikasi', html, '', 'standart');
    });
    return false;
  });

} );

</script>
