<?php

/**
* @author Primaningtyas Sukawati
* @version 05022018
*/

 ?>
<style>
#divButton
{
  padding-top: 20px;
}
.colAlign
{
  text-align: center;
  vertical-align: middle;
}
</style>
<div id="wrapperFormAdd">
  <form class="form-horizontal" id="formBalasan">
      <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
        <div role="tabpanel" class="tab-pane active" id="a">
          <div class="contentTab">
            <div class="box-body">
              <div class="col-md-12">
                  <div class="row">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Nomor <span class="red-label">*</span>:</label>
                      <div class="col-sm-3">
                          <input required class="form-control" type='text' maxlength="30" size='40' name='NomorSurat' id='NomorSurat' placeholder="Nomor Surat">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tanggal <span class="red-label">*</span>:</label>
                      <div class="col-sm-3">
                          <input required class="form-control date-picker" type='text' name='TGL_SURAT' id='TGL_SURAT' placeholder='DD/MM/YYYY'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Pengirim <span class="red-label">*</span>:</label>
                      <div class="col-sm-7">
                          <input required class="form-control" type='text' maxlength="50" size='40' name='NamaPengirim' id='NamaPengirim' placeholder="Nama Pengirim">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Status Pemenuhan <span class="red-label">*</span>:</label>
                      <div class="col-sm-7">
                        <select required class="form-control" name='StatusPemenuhan' id='StatusPemenuhan'>
                            <option value="" disabled selected>--- Pilih Status ---</option>
                            <!-- <option value="1">Terpenuhi</option>
                            <option value="2">Terpenuhi Sebagian</option>
                            <option value="3">Tidak Terpenuhi</option>
                            <option value="5">Tidak Lengkap</option>
                            <option value="6">Tidak Dibalas</option> -->
                            <option value="7">Diterima Lengkap</option>
                            <option value="8">Diterima Belum Lengkap</option>
                          </select>
                      </div>
                    </div>
                  </div>
              </div>
              <br>
              <div class="pull-right" id="divButton">
                <input type="hidden" name="SURAT_ID" id="SURAT_ID" value="<?php echo $this->uri->segment(4); ?>">
                <input type="hidden" name="act" value="doinsert">
                <input type="submit" id="btnSimpanBalasan" class="btn btn-sm btn-primary" value="Simpan"></input>
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
              </div>
              <div class="clearfix"></div>
              <br>
              <label>DAFTAR SURAT BALASAN</label>
              <table class="table table-striped table-hover table-heading no-border-bottom colAlign" id="table_surat">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Nomor Surat</th>
                              <th>Tanggal Surat</th>
                              <th style="width:200px">Pengirim</th>
                              <th>Status Pemenuhan</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; foreach ($surat as $balasan): ?>
                          <tr>
                            <td class="colAlign"><?php echo $i++ ?></td>
                            <td><?php echo $balasan['BALASAN_NOMOR'] ?></td>
                            <td><?php echo tgl_format(date('d-m-Y', strtotime($balasan['BALASAN_TANGGAL']))) ?></td>
                            <td style="width:200px"><?php echo $balasan['BALASAN_PENGIRIM'] ?></td>
                            <td><?php echo $balasan['PEMENUHAN_NAMA'] ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
            </div>
          </div>
        </div>
      </div>
  </form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
    $('#btnSimpanBalasan').click(function(e) {
    	e.preventDefault();
      var url = "index.php/eaudit/Korespondensi/save_korespondensi_balasan_new";
      var TglSurat = $('#TGL_SURAT').val();
      var NomorSurat = $('#NomorSurat').val();
      var NamaPengirim = $('#NamaPengirim').val();
      var SuratId = $('#SURAT_ID').val();
      var StatusPemenuhan = $('#StatusPemenuhan').val();
      // var StatusPemenuhan = $('#StatusPemenuhan :selected').text();
      var valid = true;
      if(NomorSurat == "")
        valid = false;
      if(TglSurat == "")
        valid = false;
      if(NamaPengirim == "")
        valid = false;
      if(StatusPemenuhan == null)
        valid = false;
      if(valid) {
        var a = $('#formBalasan').serialize();
        $.ajax({
           type: "POST",
           url: url,
           data: a,
           success: function(data)
           {
                $('#loader_area').hide();
                CloseModalBox2();
                alert("Data berhasil disimpan.");
                $('#table-surat').DataTable().ajax.reload();
           }
         });
      }
  	  else {
  	  }
    });

    $('.date-picker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    })
	});
</script>
