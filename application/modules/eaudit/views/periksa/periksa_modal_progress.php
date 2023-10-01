<?php ?>
<div class="container-fluid">
  <form>
    
  </form>
  <div class="form-group row">
    <label class="control-label col-xs-6">Nama PN</label>
    <div class="col-xs-5">
      <?php echo $nama_pn ? $nama_pn : '-'; ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6">Terakhir Diperbarui</label>
    <div class="col-xs-5">
      <?php echo $UPDATED_AT ? $UPDATED_AT : '-'; ?>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6">Diperbarui Oleh</label>
    <div class="col-xs-5">
      <?php echo $nama ? $nama : '-'; ?>
    </div>
  </div>
  <br>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsUnduhKkp">Unduh KKP</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsUnduhKkp" disabled <?php echo $status_periksa == 2 ? 'checked' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsCekTelusur">Pengecekan Telusur</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsCekTelusur" <?php echo $IS_CEK_TELUSUR == 1 ? 'checked' : ''; //echo $status_periksa == 3 ? 'disabled' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsSipesat">Pengecekan Sipesat</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsSipesat" <?php echo $IS_CEK_SIPESAT == 1 ? 'checked' : ''; //echo $status_periksa == 3 ? 'disabled' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsPedal">Permintaan Ke PEDAL</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsPedal" disabled <?php echo $pedal_no_permintaan && $pedal_tgl_approval3 ? 'checked' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsVoucher">Permintaan Voucher (Bukti Transaksi)</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsVoucher" <?php echo $IS_VOUCHER_PEDAL == 1 ? 'checked' : ''; //echo $status_periksa == 3 ? 'disabled' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsLapangan">Pengecekan Lapangan</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsLapangan" <?php echo $IS_CEK_LAPORAN  == 1 ? 'checked' : ''; //echo $status_periksa == 3 ? 'disabled' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsKlarifikasi">Melakukan Klarifikasi</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsKlarifikasi" disabled <?php echo $jenis_pemeriksaan == 0 ? 'checked' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsDraftLhkpn">Pembuatan Draft LHP</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsDraftLhkpn" <?php echo $IS_DRAFT_LHKPN == 1 ? 'checked' : ''; //echo $status_periksa == 3 ? 'disabled' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="iIsPendalaman">Pendalaman</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="iIsPendalaman" <?php echo $IS_PENDALAMAN == 1 ? 'checked' : ''; //echo $status_periksa == 3 ? 'disabled' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="is_paparan_pimpinan">Paparan Pimpinan</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="is_paparan_pimpinan" disabled <?= $is_paparan_pimpinan ? 'checked' : ''; ?>>
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-xs-6" for="is_arahan_pimpinan">Arahan Pimpinan</label>
    <div class="col-xs-5">
      <input class="form-check-input progress-checkbox" type="checkbox" value="1" id="is_arahan_pimpinan" disabled <?= $is_arahan_pimpinan ? 'checked' : ''; ?>>
    </div>
  </div>
  <br>
  <div class="form-group row" id="input-btn-submit">
    <div class="col-xs-5 col-xs-offset-4 <?php //echo $status_periksa == 3 ? 'col-xs-offset-5' :  'col-xs-offset-4'?>">
      <?php
     // if ($status_periksa != 3) {
        echo '
          <button class="btn btn-primary progress-submit" id="btn-submit" type="submit" name="button">Submit Progress</button>
          <button class="btn btn-default" id="btn-batal" type="reset" name="button">Batal</button>
        ';
      //} else {
      //   echo '<button class="btn btn-default" id="btn-batal" type="reset" name="button">Tutup</button>';
      // }
      ?>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    console.log('<?php echo $pedal_no_permintaan . " lala " .$pedal_tgl_approval3; ?>')
    var created_at = '<?php echo $CREATED_AT ? $CREATED_AT : null; ?>';
    var telusur = $('#iIsCekTelusur').is(":checked") ? true : false;
    var siPesat = $('#iIsSipesat').is(":checked") ? true : false;
    var voucher = $('#iIsVoucher').is(":checked") ? true : false;
    var lapangan = $('#iIsLapangan').is(":checked") ? true : false;
    var draftLhkpn = $('#iIsDraftLhkpn').is(":checked") ? true : false;
    var pendalaman = $('#iIsPendalaman').is(":checked") ? true : false;
  
    $('#btn-submit').click(function(e){
      e.preventDefault();
      
      var data = {
        'id_audit': '<?php echo $id_audit; ?>',
        'lhkpn' : '<?php echo $id_lhkpn; ?>',
        'telusur': $('#iIsCekTelusur').is(":checked") ? 1 : 0,
        'sipesat': $('#iIsSipesat').is(":checked") ? 1 : 0,
        'voucher_pedal': $('#iIsVoucher').is(":checked") ? 1 : 0,
        'laporan': $('#iIsLapangan').is(":checked") ? 1 : 0,
        'draft_lhkpn': $('#iIsDraftLhkpn').is(":checked") ? 1 : 0,
        'pendalaman': $('#iIsPendalaman').is(":checked") ? 1 : 0,
        'created_at': '<?php echo $CREATED_AT; ?>'
      }
      var url = '<?php echo site_url("index.php/eaudit/periksa/set_progress/") ?>';
      $.post( url, data)
        .done(function(results) {
          CloseModalBox2();
          alertify.success(results);
          $('#table-periksa').DataTable().ajax.reload();
        });
    });

    $('#btn-batal').click(function(e){
      e.preventDefault();
      CloseModalBox2();
      $('#table-periksa').DataTable().ajax.reload();
    });

  })
</script>

<style media="screen">
  .data-pn {
    margin-bottom: 10px;
  }
  .buttons {
    text-align: center;
  }
  .font-small td{
    font-size: 12px;
  }
  .font-bold: {
    font-weight: bold;
  }
</style>

