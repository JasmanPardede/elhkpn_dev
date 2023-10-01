<?php
/*

 */
/**
 * View
 *
 * @author
 * @package
 */
?>
<style media="screen">
#btnCetakSurat
{
  background-color: #e08e0b;
  border-color: #e08e0b;
}
#btnInputSuratBalasan
{
  background-color: #008d4c;
  color: white;
}
.row
{
  margin-top: 10px;
}
tbody
{
  font-size: 13px;
}
th
{
  padding-right: 20px;
  padding-left: 20px;
}
thead tr {
  background-color: #337ab7;
  height: 60px;
  color: ghostwhite;
}
</style>
<section class="content-header">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="row">
			<form class='form-horizontal' id="ajaxFormCari">
              <div class="col-md-6">
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Nomor Surat</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="CARI[NOMOR_SURAT]" placeholder="Nomor Surat" value="<?php echo @$CARI['NOMOR_SURAT']; ?>" id="CARI_NOMOR_SURAT">
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Tanggal Surat</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control date" name="CARI[TANGGAL_SURAT]" placeholder="Tanggal Surat" value="<?php echo @$CARI['TANGGAL_SURAT']; ?>" id="CARI_TANGGAL_SURAT">
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Status Pemenuhan</label>
                        <div class="col-sm-7">
                          <select class="form-control" name='CARI[STATUS_PEMENUHAN]' id='CARI_STATUS_PEMENUHAN'>
                              <option value="" disabled selected>--- Pilih Status ---</option>
                              <option value="7">Diterima Lengkap</option>
                              <option value="8">Diterima Belum Lengkap</option>
                              <option value="-">Belum Dibalas</option>
                          </select>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Tujuan Surat</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="CARI[NAMA_INSTANSI]" placeholder="Nama Instansi" value="<?php echo @$CARI['NAMA_INSTANSI']; ?>" id="CARI_NAMA_INSTANSI">
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label labelKorespondensi">Nama PN</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                        <div class="col-md-6" style="float:right;">
                            <button type="submit" class="btn btn-sm btn-default" id="btn-filter"><i class="fa fa-search"></i></button>
                            <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                        </div>
                    </div>
                  </div>
                </div>
			</form>
            </div>
            <div class="row" style="padding-left: 15px;">
                  <button class="btn btn-sm btn-primary btn-add" id="btn-add" href="<?php echo site_url('eaudit/korespondensi/addsuratkeluar'); ?>">
                      <i class="fa fa-envelope"></i> Tambah Surat Keluar
                  </button>
            </div>
        </div>
        <div class="box-body">
          <!-- table_surat is here -->
            <table class="table table-striped no-footer dataTable" id="table-surat">
              <thead>
                <tr>
                    <th style="vertical-align: middle;">No</th>
                    <th style="width:160px;vertical-align: middle;">Nomor / Tanggal</th>
                    <th style="vertical-align: middle;">Tujuan Surat</th>
                    <th style="vertical-align: middle;width:200px">Nama PN</th>
                    <th style="vertical-align: middle;">Pemenuhan</th>
                    <th style="width: 100px;vertical-align: middle;">Aksi</th>
                </tr>
            </thead>
            </table>
        </div>
    </div>
</section>
<script type="text/javascript">
$('.date').datepicker({
  orientation: "left",
  format: 'dd/mm/yyyy',
  autoclose: true
});
var msg = {
  success: 'Data Berhasil Disimpan!',
  error: 'Data Gagal Disimpan!'
};
$(document).ready( function() {

  //console.log( "ready!" + "<?php $this->session->userdata() ?>" + "HAI");

  var table_surat = $('#table-surat').DataTable({
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
      "url": "<?php echo site_url('index.php/eaudit/korespondensi/ajax_list')?>",
      "type": "POST",
      "data": function ( data ) {
        data.nomorSurat = $('#CARI_NOMOR_SURAT').val();
        data.tanggalSurat = $('#CARI_TANGGAL_SURAT').val();
        data.statusPemenuhan = $('#CARI_STATUS_PEMENUHAN option:selected').val();
        data.namaInstansi = $('#CARI_NAMA_INSTANSI').val();
        data.namaPn = $('#CARI_NAMA').val();
      }
    },

    //Set column definition initialisation properties.
    "columnDefs": [
      {
        "targets": [ 0 ], //first column / numbering column
        "orderable": false, //set not orderable
      },
    ],
  });

  $('#table-surat tbody').on( 'click', '#btnUpdateSurat', function (e) {
    e.preventDefault();
    var id = $(this).data('idsurat');
    var url = '<?php echo site_url('eaudit/korespondensi/addsuratkeluar')?>/'+ id;
    $('#loader_area').show();

    $.post(url, function(html) {
      OpenModalBox('Tambah Surat Keluar', html, '', 'large');
    });
    return false;

  });

  $('#table-surat tbody').on( 'click', '#btnInputSuratBalasan', function (e) {
    e.preventDefault();
    var id = $(this).data('idsurat');
    var url = '<?php echo site_url('eaudit/korespondensi/addsuratbalasan')?>/'+ id;
    $('#loader_area').show();

    $.post(url, function(html) {
      OpenModalBox('Input Surat Balasan', html, '', 'large');
    });
    return false;

  });

  $('#table-surat tbody').on( 'click', '#btnCetakSurat', function (e) {
    e.preventDefault();
    var id = $(this).data('idsurat');
    var templateId = $(this).data('idtemplate');
    var url = '<?php echo  site_url("/index.php/eaudit/CetakLampiran/cetakSurat")?>/'+ id + '/' + templateId;

    window.location.href = url;
    return false;

  });

  //----------- process filter buttons
  $('#btn-filter').click(function(e) {
    e.preventDefault();
    table_surat.ajax.reload();
  });

  $('#btn-clear').click(function() {
    $('#ajaxFormCari')[0].reset();
    table_surat.ajax.reload();
  });

  $('.btn-add').click(function(e) {
    url = $(this).attr('href');
    $('#loader_area').show();
    $.post(url, function(html) {
      OpenModalBox('Tambah Surat Keluar', html, '', 'large');
    });
    return false;
  });

  $('.btn-edit').click(function(e) {
    url = $(this).attr('href');
    var suratId = $(this).data('idsurat');
    $('#loader_area').show();
    $.post(url, function(html) {
      OpenModalBox('Edit Surat Keluar', html, '', 'large');
    });
    return false;
  });

  $('.btn-reply').click(function(e) {
    var id = $(this).data('idsurat');
    var url = $(this).attr('href');
    $('#loader_area').show();
    $.post(url, function(html) {
      OpenModalBox('Input Surat Balasan', html, '', 'large');
    });
  });

  $('.btnCetakSurat').click( function(e) {
    e.preventDefault();
    var suratId = $(this).data('idsurat');
    var templateId = $(this).data('idtemplate');
    var url = '<?php echo site_url("/index.php/eaudit/CetakLampiran/cetakSurat"); ?>/'+ suratId + '/' + templateId;
    window.location.href = url;
  }) ;
});
</script>
