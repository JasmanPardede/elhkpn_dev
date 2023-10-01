<?php  ?>

<style media="screen" type="text/css">	
.css-serial {
  counter-reset: serial-number;  /* Set the serial number counter to 0 */
}

.css-serial td:first-child:before {
  counter-increment: serial-number;  /* Increment the serial number counter */
  content: counter(serial-number);  /* Display the counter */
}




</style>
<style media="screen">
  .content, .page-title {
    margin-top: 0;
    padding-top: 0;
  }

  #ajax-content {
    padding-top: 10px;
  }

  .title {
    border-bottom: 3px solid rgba(47, 47, 47, .5);
  }

  .title h3 {
    font-weight: bold;
  }

  #table-penugasan thead tr {
    /* background-color: #0F96C8; */
    font-weight: 400;
    color:rgb(241, 241, 241);
    text-transform: uppercase;
  }

  .btn-primary.disabled {
    background-color: lightgreen;
  }

  .form-penugasan-wrapper {
    padding-left: 15px;
    padding-right: 15px;
  }

  .select2-container {
    width: 100%;
  }


</style>

<div class="content">
  <div class="page-title">
    <h3>Daftar E-LHKPN</h3>
  </div>
  <!-- form untuk filter table -->
  <div class="filter-table">
    <div class="row">


      <form class='form-horizontal' id="ajaxFormCari">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tahun Pelaporan :</label>
                                            <div class="col-sm-5">

                                                <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="Pilih Tahun" value="<?php echo @$CARI['TAHUN']; ?>" id="iTahunLapor" readonly>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Jenis Laporan :</label>
                                            <div class="col-sm-5">

                                                <select class="form-control" name="CARI[JENIS]" id="selectjenislaporan">

                                                    <option value="">-- Pilih --</option>
                                                    <option value="1" <?php if (@$CARI['JENIS'] == 1) { echo 'selected'; }; ?>>Khusus, Calon</option>
                                                    <option value="2" <?php if (@$CARI['JENIS'] == 2) { echo 'selected'; }; ?>>Khusus, Awal menjabat</option>
                                                    <option value="3" <?php if (@$CARI['JENIS'] == 3) { echo 'selected'; }; ?>>Khusus, Akhir menjabat</option>
                                                    <option value="4" <?php if (@$CARI['JENIS'] == 4) { echo 'selected'; }; ?>>Periodik tahunan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Status Penugasan :</label>
                                            <div class="col-sm-5">

                                                <select class="form-control" name="CARI[STATUS_PENUGASAN]" id="selectstatuspenugasan">

                                                    <option value="0" <?php if (@$CARI['STATUS_PENUGASAN'] == 0) { echo 'selected'; }; ?>>-- Semua --</option>
                                                    <option value="1" <?php if (@$CARI['STATUS_PENUGASAN'] == 1) { echo 'selected'; }; ?>>Belum Ditugaskan</option>
                                                    <option value="2" <?php if (@$CARI['STATUS_PENUGASAN'] == 2) { echo 'selected'; }; ?>>Sudah Ditugaskan Penelaahan</option>
                                                    <option value="3" <?php if (@$CARI['STATUS_PENUGASAN'] == 3) { echo 'selected'; }; ?>>Sudah Ditugaskan Pemeriksaan</option> -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cari :</label>
                                            <div class="col-sm-3">

                                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="iNamaPN">
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-sm btn-primary" id="btn-filter"><i class="fa fa-search"></i></button>

                                                    <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
       </form>
    </div>
  </div>

  <hr>

  <!-- tables for list data pn yg sudah publikasi -->
  <div class="list-pn-publikasi">
    <table id="table-penugasan" class="table dataTable no-footer table-striped">
      <thead>
        <tr class="bg-primary">
          <td><span class="fa fa-edit"></span></td>
          <td>No.</td>
          <!-- <td>ID LHKPN</td> -->
          <td>Nama / No.Agenda</td>
          <td style="width: 50px;">Jabatan</td>
          <td>Lembaga</td>
          <td>Tanggal Pengumuman</td>
          <td>Status Penugasan</td>
          <td>Status Pemeriksaan</td>
        </tr>
      </thead>

    </table>

  </div>

  <!-- section penugasan -->
  <hr>
  <section id="penugasan-pemeriksaan">
    <form id="formPenugasan">
    <div class="penugasan-pemeriksaan-container">
      <div class="title">
        <!-- <button id="btnTambahTugas" class="btn btn-warning" type="button" name="btnTambahTugas"><span class="fa fa-plus-square"></span> Pemeriksaan / Audit</button> -->
        <h3>Daftar penugasan Audit PN</h3>
      </div>
      <div class="penugasan-content">
        <table id="list-pn-periksa" class="table table table-striped css-serial">
          <thead>
            <tr class="bg-primary">
              <th>No.</th>
              <!-- <td>ID LHKPN</td> -->
              <th>Nama / No.Agenda</th>
              <th>Jabatan</th>
              <th>Lembaga</th>
              <th>Tanggal Pengumuman</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>

    <hr>

    <div class="form-penugasan-wrapper">
      <div class="row">
        <div class="col-md-8">
          <div class="form-horizontal">
            <div class="form-group form">
              <label class="control-label col-sm-5"></label>
              <input class="form-check-input" type="radio" name="optionPenugasan" id="optionPenugasan" value="0" required="" checked="checked">

              <label class="form-check-label" for="optionPenugasan">Penelaahan</label>

              &nbsp;&nbsp;&nbsp;
              <input class="form-check-input" type="radio" name="optionPenugasan" id="optionPenugasan" value="1" required="">
              <label class="form-check-label" for="optionPenugasan">Pemeriksaan</label>
            </div>
            <div class="form-group">
                            <label class="control-label col-sm-6" for="iPemeriksa">Pemeriksa <span class="span-danger">*</span> :</label>
                            <div class="col-sm-6">
                                <select multiple id="iNamaPemeriksa" class="form-control" name="iNamaPemeriksa[]">
                                  <?php foreach ($users_admin as $ua): ?>
                                    <option value="<?php echo $ua->ID_USER; ?>"><?php echo $ua->NAMA.' ('.$ua->ROLE.')'; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div id="showHidePemeriksaan">
                          <div class="form-group">
                            <label class="control-label col-sm-6" for="iNomorSuratTugas">Nomor Surat Tugas <span class="span-danger">*</span> :</label>
                            <div class="col-sm-6">
                                <input class="form-control" type="text" id="iNomorSuratTugas" name="iNomorSuratTugas" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6" for="iTanggalPenugasanAwal">Tanggal Penugasan <span class="span-danger">*</span> :</label>
                            <div class="col-sm-6">
                                <input class="form-control date" type="text" id="iTanggalPenugasanAwal" name="iTanggalPenugasanAwal" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6" for="iTanggalPenugasanAkhir">Tanggal berakhir penugasan <span class="span-danger">*</span> :</label>
                            <div class="col-sm-6">
                                <input class="form-control date" type="text" id="iTanggalPenugasanAkhir" name="iTanggalPenugasanAkhir" required>
                            </div>
                        </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6" for="btn-action"></label>
                            <div class="col-sm-6">
                                <button class="btn btn-danger" type="button" name="btn-save-tgs" id="btn-save-tgs" title="Proses dan simpan penugasan">Simpan</button>
                                <button class="btn btn-info" type="button" name="btn-batal-tgs" id="btn-batal-tgs" title="Reset form penugasan">Batal</button>
                            </div>
                        </div>
                        <p id="cuksss"></p>
          </div>
        </div>
      </div>
    </div>
  </form>
  </section>

</div>


<script type="text/javascript">
  $(document).ready( function() {
    /* Date */
    $('.date').datepicker({
        orientation: "left",
        format: 'dd/mm/yyyy',
        autoclose: true
    });
    $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
    $('#showHidePemeriksaan').hide();
    $(':radio').change(function () {
      var valOp = $(this).val();
      if (valOp == '0') {
        $('#showHidePemeriksaan').hide();
      }
      else{
        $('#showHidePemeriksaan').show();
      }
    });
    var table_surat = $('#table-surat').DataTable({
      // using serverside processing
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.

      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "<?php echo site_url('index.php/eaudit/korespondensi/ajax_list')?>",
          "type": "POST",
          "data": function (data) {
            data.tahun = $('#iTahunLapor').val();
            data.jenisLaporan = $('#selectjenislaporan option:selected').val();
            data.statusPenugasan = $('#selectstatuspenugasan option:selected').val();
            //data.lembaga = $('#iLembaga').val();
            //data.jabatan = $('#iJabatan').val();
            data.namaPn = $('#iNamaPN').val();
          }
      },

      //Set column definition initialisation properties.
      "columnDefs": [
        {
            "targets": [ 0, 1, 7 ], //first column / numbering column
            "orderable": false, //set not orderable
        }
      ],
    });

    // inistialize table penugasan
    // $('#list-pn-periksa').DataTable();

    //----------- process filter buttons
    $('#btn-filter').click(function(e) {
      e.preventDefault();
      table_surat.ajax.reload();
    });

    $('#btn-clear').click(function() {
      $('#ajaxFormCari')[0].reset();
      table_surat.ajax.reload();
    });

    //---------- add proses pemeriksaan actions
	var number = 1;
    $('#table-penugasan tbody').on( 'click', '#btn-penugasan', function () {

      var a = $(this).text(); 
      if (a == 'Batal') {
        var rownumber = '#row'+$(this).data('idlhkpn');
        $(this).html('Pilih');
        $(this).removeClass('btn-danger');
        $(this).addClass('btn-success');
        $(rownumber).remove();
		number-1;
      }
      else{

        //number += number;


        $(this).html('Batal');
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        $("#list-pn-periksa tbody").append('<tr id="row'+$(this).data('idlhkpn')+'">'+

            '<td></td>'+

            '<td><b>'+ $(this).data('nama')+'</b><br>'+ $(this).data('agenda')+'</td>'+
            '<td>'+ $(this).data('jabatan')+'</td>'+
            '<td>'+ $(this).data('lembaga')+'</td>'+
            '<td>'+ $(this).data('tglpengumuman')+'</td>'+

            '<td><input type="hidden" name="idnya[]" value="'+$(this).data('idlhkpn')+'"> <button id="btn-hapus-penugasan" data-idlhkpn="'+$(this).data('idlhkpn')+'" class="btn btn-default" title="keluarkan dari daftar penugasan"><i class="fa fa-trash"></i>Batal</button> </td>'+
          '</tr>'
        ); number++;

      }

    } );

    // initialize select2 for list nama pemeriksa
    $('#iNamaPemeriksa').select2();

    $('#list-pn-periksa tbody').on('click', '#btn-hapus-penugasan', function(){
      // re enabled the assign button
      var enabled_btn_penugasan_id = $(this).data('idlhkpn'); 
      var the_btn = $('#table-penugasan tbody').find('[data-idlhkpn="'+enabled_btn_penugasan_id+'"]');
      the_btn.html('Pilih');
      the_btn.removeClass('btn-danger');
      the_btn.addClass('btn-success');
      // remove row
      $(this).parent().parent().remove();
    });

    $('#btn-save-tgs').click(function () {
      var a = $("#formPenugasan").serialize();
      $.ajax( {
      type: "POST",
      url: '<?php echo base_url().'eaudit/penugasan/savePenugasan'; ?>',
      data: a,
      success: function( response ) {
        if (response == true) {
          document.getElementById("formPenugasan").reset();
          alertify.success('Berhasil Ditugaskan');
		  //var table1 = $('#list-pn-periksa').DataTable();
		  //table1.clear().draw();
		  location.reload();

        }
        else{
          alertify.error('Gagal Ditugaskan');
        }
        console.log( response );
      }
    } );
    });

  });
</script>
