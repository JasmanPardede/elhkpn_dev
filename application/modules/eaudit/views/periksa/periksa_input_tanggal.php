<?php  ?>
<style media="screen">
.just-text {
  padding-top: 7px;
}
.red-cuk{color: red;}
.btn-jenis-periksa {
  text-align: center;
  margin-top: 10px;
}
.btn-jenis-periksa button {
  margin: 10px 10px;
}
.btn-batal {
  text-align: center;
  padding: 0 30px;
}
.text-jns {
  font-size: small;
  font-weight: bold;
  color: #888888;
}

.form-horizontal .form-group {
  margin-bottom: 1.5em;
}

.text-muted {
  color: #b1b1b1;
}
.has-warning .form-control-feedback {
  color: #f39c12;
}

</style>

<div class="phpvar" data-islhp="<?php echo $is_lhp ?>"></div>

<form class="form-horizontal" action="" method="post">
  <div class="form-group">
    <label class="control-label col-xs-4" for="">Nama PN :</label>
    <div class="col-xs-8 just-text">
      <b><?php echo $nama_pn; ?></b> - <?php echo $no_agenda; ?>
    </div>
  </div>

  <?php if ($is_penelaahan): ?>
    <!-- Khusus form penelaahan -->
    <div id="iNomorWarn" class="form-group has-warning has-feedback">
      <label class="control-label col-xs-4" for="iNomorLTDok">Nomor LT<span class="red-cuk">*</span>:</label>
      <div class="col-xs-7">
        <!-- TODO: get nomor lembar telaah terakhir + 1  -->
        <input id="iNomorLTDok" class="form-control" type="text" name="iNomorLTDok" readonly="true" value="<?php echo 'LT-'.$nomor_lt_terakhir.'/12/'.date('m/Y') ?>" aria-describedby="inputWarning2Status">
        <span id="iNomorIcon" class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
        <span id="inputWarning2Status" class="sr-only">(warning)</span>
        <span id="inputSuccess2Status" class="sr-only">(success)</span>
        <small id="nomorLTHelpBlock" class="form-text text-muted"> Nomor di-generate secara otomatis oleh sistem, download template LT untuk meregistrasikan nomor LT </small>
        <input type="hidden" id="iNomorLT" name="iNomorLT" value="<?php echo $nomor_lt_terakhir; ?>">
        <input type="hidden" id="iTanggalLT" name="iTanggalLT" value="<?php echo date('Y-m-d'); ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iTemplateKKP">Template LT :</label>
      <div class="col-xs-5">
        <!-- TODO: simpan nomor telaah, if pernah didownload tombol akan berubah warna, dan ada keterangan  -->
        <button id="templateLT" class="btn btn-warning form-control" href="#">Download Template</button>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iRekomendasi">Rekomendasi<span class="red-cuk">*</span>:</label>
      <div class="col-xs-4">
        <select id="iRekomendasi" class="form-control" name="iRekomendasi">
          <option value="0">Tidak Diperiksa</option>
          <option value="1">Diperiksa</option>
        </select>
      </div>
    </div>
    <div id="iKeteranganWrapper" class="form-group">
      <label class="control-label col-xs-4" for="iKeterangan">Keterangan<span class="red-cuk">*</span> :</label>
      <div class="col-xs-7">
        <textarea id="iKeterangan" class="form-control" name="iKeterangan" rows="6" cols="80" placeholder="catatan keterangan penelaahan" ><?php echo $keterangan !== '' ? $keterangan : ''; ?></textarea>
        <small id="iKeteranganHelper" class="form-text text-muted sr-only"> Field Keterangan <b>wajib</b> diisi </small>
      </div>
    </div>
    <div class="form-group" id="input-btn-submit">
      <div class="col-xs-5 col-xs-offset-4">
        <button class="btn btn-info" id="btn-submit-penelaahan" type="submit" name="button" disabled="true" title="LT belum pernah diunduh">Submit Penelaahan</button>
      </div>
    </div>

    <!-- end form penelaahan -->
    <!-- detail LHP -->
    <?php elseif ($is_detail_lhp): ?>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iNomorLHP">Nomor LHP<span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <input class="form-control" type="text" readonly="true" value="<?php echo $nomor_lhp; ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iTglLHP">Tanggal LHP<span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <input class="form-control" type="text" readonly="true" value="<?php echo date('d/m/Y', strtotime($tgl_lhp)); ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iRekomendasi">Rekomendasi <span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <?php if ($jenis_penugasan == 1): ?>
          <input class="form-control" type="text" readonly="true" value="<?php echo ($rekomendasi_lhp == 1) ? 'Dilimpahkan' : 'Diarsipkan'; ?>">
        <?php else: ?>
          <input class="form-control" type="text" readonly="true" value="<?php echo ($rekomendasi_lhp == 1) ? 'Diperiksa' : 'Tidak Diperiksa'; ?>">
        <?php endif; ?>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iNomorLHP">Periode Pemeriksaan<span class="red-cuk">*</span>:</label>
      <div class="col-xs-7" style="padding: 0;">
        <div class="col-xs-6">
          <input class="form-control" type="text" readonly="true" value="<?php echo ($tgl_periode_awal_lhp != '') ? date('d/m/Y', strtotime($tgl_periode_awal_lhp)) : '-'; ?>">
          <small><span class="help-block">Tanggal awal</span></small>
        </div>
        <div class="col-xs-6">
          <input class="form-control" type="text" readonly="true" value="<?php echo ($tgl_periode_akhir_lhp != '') ? date('d/m/Y', strtotime($tgl_periode_akhir_lhp)) : '-'; ?>">
          <small><span class="help-block">Tanggal akhir</span></small>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iNomorLHP">Keterangan :</label>
      <div class="col-xs-7">
        <textarea class="form-control" rows="6" readonly="true"><?php echo $keterangan_lhp; ?></textarea>
      </div>
    </div>
  <!-- end of detail LHP -->

  <?php elseif ($is_lhp): ?>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iNomorLHP">Nomor LHP<span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <input id="iNomorLHP" class="form-control" type="text" name="iNomorLHP">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-xs-4" for="iTglLHP">Tanggal LHP <span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <input required="" class="form-control date-picker" type="text" name="iTglLHP" id="iTglLHP" placeholder="dd/mm/yyyy" value="<?php echo date('d/m/Y') ?>">
        <!-- <input id="iTglLHP" class="form-control" type="date" name="iTglLHP" value="<?php echo date('Y-m-d'); ?>"> -->
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iRekomendasi">Rekomendasi <span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <?php if ($jenis_penugasan): ?>
          <select id="iRekomendasi" class="form-control" name="iRekomendasi">
            <option value="0">Diarsipkan</option>
            <option value="1">Dilimpahkan</option>
            <!-- <option value="1">Dilimpahkan ke Gratifikasi </option>
            <option value="2">Dilimpahkan ke Lidik</option>
            <option value="3">Dilimpahkan ke Litbang</option>
            <option value="4">Dilimpahkan ke Dumas</option>
            <option value="5">Dilimpahkan ke Korsupgah</option>
            <option value="6">Dilimpahkan ke Korsupdak</option>
            <option value="7">Dilimpahkan ke Penyidikan</option>
            <option value="8">Dilimpahkan ke PI</option>
            <option value="9">Dilimpahkan ke Labuksi / ATR</option>
            <option value="10">Dilimpahkan ke Internal Lainnya</option>
            <option value="10">Dilimpahkan ke Eksternal Lainnya</option> -->
          </select>
        <?php else: ?>
          <select id="iRekomendasi" class="form-control" name="iRekomendasi">
            <option value="0" <?php echo $rekomendasi == 0 ? 'selected' : ''; ?> >Tidak Diperiksa</option>
            <option value="1" <?php echo $rekomendasi == 1 ? 'selected' : ''; ?>>Diperiksa</option>
          </select>
        <?php endif; ?>
      </div>
    </div>
<!--    <div class="form-group">
      <label class="control-label col-xs-4" for="iNomorLHP">Periode Pemeriksaan<span class="red-cuk">*</span>:</label>
      <div class="col-xs-7" style="padding: 0;">
        <div class="col-xs-6">
          <input required="true" class="form-control date-picker" type="text" name="iTglLHP" id="iTglAwal" placeholder="dd/mm/yyyy">
          <small><span class="help-block">Tanggal awal</span></small>
        </div>
        <div class="col-xs-6">
          <input required="true" class="form-control date-picker" type="text" name="iTglLHP" id="iTglAkhir" placeholder="dd/mm/yyyy">
          <small><span class="help-block">Tanggal akhir</span></small>
        </div>
        <div class="col-xs-12">
          <span class="help-block"><small class=" text-danger">tanggal awal lebih kecil daripada tanggal akhir</small></span>
        </div>
      </div>
    </div>-->
    <div class="form-group">
      <label class="control-label col-xs-4" for="iNomorLHP">Keterangan :</label>
      <div class="col-xs-7">
        <textarea id="iKeterangan" name="iKeterangan" class="form-control" rows="6"><?php echo $keterangan !== '' ? $keterangan : ''; ?></textarea>
      </div>
    </div>
    <!-- <div class="form-group">
      <label class="control-label col-xs-4" for="iNomorLHP">Keterangan :</label>
      <div class="col-xs-7">
        <textarea id="iKeterangan" name="iKeterangan" class="form-control" rows="6"><?php echo $keterangan !== '' ? $keterangan : ''; ?></textarea>
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-xs-4" for="iTglAwalPeriode">Awal&nbsp;Periode&nbsp;Pemeriksaan<span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <input required="" class="form-control date-picker" type="text" name="iTglAwalPeriode" id="iTglAwalPeriode" placeholder="dd/mm/yyyy">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-xs-4" for="iTglAkhirPeriode">Akhir&nbsp;Periode&nbsp;Pemeriksaan<span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <input required="" class="form-control date-picker" type="text" name="iTglAkhirPeriode" id="iTglAkhirPeriode" placeholder="dd/mm/yyyy">
      </div>
    </div> -->

    <div class="form-group" id="input-btn-submit">
      <div class="col-xs-5 col-xs-offset-4">
        <button class="btn <?php echo ($is_lhp ? "btn-danger" : "btn-primary" ); ?>" id="btn-submit" type="submit" name="button">Submit <?php echo ($is_lhp ? "Tanggal LHP" : "Tanggal BAK" ); ?></button>
        <!-- <button class="btn btn-default" id="btn-batal" type="reset" name="button">Batal</button> -->
      </div>
    </div>
  <?php else: ?>
    <!-- form untuk tanggal klarfirikasi -->
    <!-- <div class="form-group" id="input-pilih-jenis-pemeriksaan">
      <div class="col-xs-12 btn-jenis-periksa">
        <button class="btn btn-primary" id="pemeriksaan_tbk" type="btn-tbk" name="pemeriksaan_tbk">Pemeriksaan Terbuka</button>
        <button class="btn btn-danger" id="pemeriksaan_ttp" type="btn-ttp" name="pemeriksaan_ttp">Pemeriksaan Tertutup</button>
      </div>
    </div> -->

    <div class="form-group" id="input-tgl-bak">
      <label class="control-label col-xs-4" for="iTglKlarifikasi">Tanggal Klarifikasi <span class="red-cuk">*</span>:</label>
      <div class="col-xs-5">
        <input required="" class="form-control date-picker" type="text" name="iTglKlarifikasi" id="iTglKlarifikasi" placeholder="dd/mm/yyyy" value="<?php echo date('d/m/Y') ?>">
        <!-- <input id="iTglKlarifikasi" class="form-control" type="date" name="iTglKlarifikasi" value="<?php echo date('Y-m-d'); ?>"> -->
      </div>
    </div>

    <div class="form-group" id="input-btn-submit">
      <div class="col-xs-5 col-xs-offset-4">
        <button class="btn <?php echo ($is_lhp ? "btn-danger" : "btn-primary" ); ?>" id="btn-submit" type="submit" name="button">Submit <?php echo ($is_lhp ? "Tanggal LHP" : "Tanggal BAK" ); ?></button>
        <!-- <button class="btn btn-default" id="btn-batal" type="reset" name="button">Batal</button> -->
      </div>
    </div>
  <?php endif; ?>
  <div class="form-group btn-batal">
    <hr>
    <button class="btn btn-default" id="btn-batal" type="reset" name="button">Batal</button>
  </div>
</form>


<script type="text/javascript">
  $(document).ready(function() {
    var is_lhp = $('.phpvar').data('islhp');

    // --- functions penelaahan ----------

    // instantiate if
    var nomor_lt_dok = '<?php echo $nomor_lt_dok; ?>';
    var submited_at = '<?php echo $submited_at; ?>';

    if (nomor_lt_dok != '') {
      form_text_to_success(
        $('#iNomorLTDok'),
        $('#iNomorWarn'),
        $('#iNomorIcon'),
        $('#templateLT')
      );
      $('#iNomorLTDok').val(nomor_lt_dok);
      $('#btn-submit-penelaahan').attr('disabled', false);
      $('#btn-submit-penelaahan').attr('title', 'Simpan informasi lembar telaah');
      if (submited_at != '') {
        var rekomendasi_lt = '<?php echo $rekomendasi_lt; ?>';
        // $('#iRekomendasi option[value!="'+rekomendasi_lt+'"]').attr('disabled', true);
        $('#iRekomendasi option[value="'+rekomendasi_lt+'"]').prop('selected', true);
        $('#iRekomendasi').prop('disabled', true);
        // $('#iKeterangan').attr('readonly', true);
        $('#iKeterangan').prop('disabled', true);
        $('#btn-submit-penelaahan').prop('disabled', true);
      }
    }

    $('#templateLT').click( function(e) {
      e.preventDefault();

      // console.log('[+] registering number');
      var url_no_lt = '<?php echo base_url()."index.php/eaudit/periksa/ajax_set_nolt" ?>';
      var data = {
        'id_lhkpn': '<?php echo $id_lhkpn; ?>',
        'id_audit': '<?php echo $id_audit; ?>',
        'nomor_lt': $('#iNomorLT').val(),
        'tanggal_lt': $('#iTanggalLT').val(),
        'nomor_lt_dok': $('#iNomorLTDok').val(),
      };
      var url_template = '<?php echo base_url()."index.php/eaudit/periksa/ajax_lt" ?>/' + '<?php echo $id_lhkpn; ?>/' + '<?php echo $id_audit; ?>';

      if (nomor_lt_dok == '') {
        // generate number
        $.post(url_no_lt, data)
        .done( function(e) {
          // console.log('[+] change warning to success on number registration');
          form_text_to_success(
            $('#iNomorLTDok'),
            $('#iNomorWarn'),
            $('#iNomorIcon'),
            $('#templateLT')
          );
          $('#btn-submit-penelaahan').attr('disabled', false);
          window.location.href = url_template;
          alertify.success('Mendownload Template Lembar Telaah...');
        });
      } else {
        window.location.href = url_template;
        alertify.success('Mendownload Template Lembar Telaah...');
      }
    })

   $('#btn-submit-penelaahan').click( function(e) {
     e.preventDefault()
    //  console.log('[+] check all input value not null & nomor_tl is already generated');
     var keterangan = $('#iKeterangan').val();
     if (keterangan == '') {
       // TODO: do something here ----------------------------
      //  console.warn('[!] field keterangan wajib diisi');
       $('#iKeteranganWrapper').addClass('has-error');
       $('#iKeteranganHelper').removeClass('sr-only');
     }
     else {
      //  console.log('[+] submit to database');
       var url = '<?php echo base_url()."index.php/eaudit/periksa/update_lt" ?>';
       var data = {
         'nomor_lt_dok': $('#iNomorLTDok').val(),
         'id_audit' : '<?php echo $id_audit; ?>',
         'id_lhkpn' : '<?php echo $id_lhkpn; ?>',
         'rekomendasi': $('#iRekomendasi').val(),
         'keterangan' : $('#iKeterangan').val()
       }
       $.post(url, data)
       .done( function(res) {
        //  console.log('[v] data penelaahan saved');
        //  console.log('data:' + res);
         CloseModalBox2();
         alertify.success('Informasi Lembar Telaah berhasil disimpan');
         $('#table-periksa').DataTable().ajax.reload();
       })
       .error( function(err) {
        //  console.error('[x] Error! failed to save data');
        //  console.log(err);
       });
     }
   })

    function form_text_to_success(form_input, form_group_warn, form_icon, download_btn){
      form_input.attr('aria-describedby', 'inputSuccess2Status');
      form_group_warn.removeClass('has-warning');
      form_group_warn.addClass('has-success');
      form_icon.removeClass('glyphicon-warning-sign');
      form_icon.addClass('glyphicon-ok');
      download_btn.removeClass('btn-warning');
      download_btn.addClass('btn-success');
    }

    // --- functions pemeriksaan
    $('.date-picker').datepicker({
      orientation: "left",
      // maxViewMode: "-17 years",
      format: "dd/mm/yyyy",
      autoclose: 'true'
    });

        $('#btn-batal').click(function(e){
      e.preventDefault();
      CloseModalBox2();
      $('#table-periksa').DataTable().ajax.reload();
    });

    $('#btn-submit').click(function(e){
      $('#btn-submit').attr('disabled', true);  
      e.preventDefault();
      <?php if ($is_lhp): ?>
        // url untuk tanggal LHP
        var nomor_lhp       = $('#iNomorLHP').val();
        var tgl_lhp         = $('#iTglLHP').val();
        var rekomendasi     = $('#iRekomendasi').val();
        var keterangan      = $('#iKeterangan').val();
        var awal_periode    = $('#iTglAwal').val();
        var akhir_periode   = $('#iTglAkhir').val();
        var data = {
          'id_lhkpn': '<?php echo $id_lhkpn; ?>',
          'id_audit': '<?php echo $id_audit; ?>',
          'nomor_lhp': nomor_lhp,
          'tgl_lhp': tgl_lhp,
          'rekomendasi': rekomendasi,
          'keterangan': keterangan,
//          'awal_periode': awal_periode,
//          'akhir_periode': akhir_periode,
          'no_st' : '<?php echo $no_st; ?>'
        }
        var url = '<?php echo base_url("index.php/eaudit/periksa/ajax_update_tgl_lhp/") ?>';
        var diff = new Date($('#iTglAkhir').datepicker('getDate') - $('#iTglAwal').datepicker('getDate'))/1000/60/60/24;
//        if (nomor_lhp == '' || tgl_lhp == '' || rekomendasi == '' || awal_periode == '' || akhir_periode == '') {
        if (nomor_lhp == '' || tgl_lhp == '' || rekomendasi == '') {
          alertify.error('Isian dengan tanda bintang wajib diisi');
        } else if (diff < 0) {
          alertify.error('Periode tanggal akhir harus lebih besar daripada tanggal awal');
        } else{
          $.post(url, data);
          $('#table-periksa').DataTable().ajax.reload();
          CloseModalBox2();
          alertify.success('Tanggal LHP berhasil disimpan');
        }
      <?php else: ?>
        // url untuk tanggal BAK
        var tgl_klarifikasi = $('#iTglKlarifikasi').val();
        var data = {
          'id_lhkpn': '<?php echo $id_lhkpn; ?>',
          'id_audit': '<?php echo $id_audit; ?>',
          'no_st' : '<?php echo $no_st; ?>',
          'tgl_klarifikasi': tgl_klarifikasi,
          'id_lhkpn_pemeriksaan': '<?php echo $id_lhkpn_pemeriksaan; ?>',
          'id_pn': '<?= $id_pn ?>'
        }

        var url;
        if (data.id_lhkpn_pemeriksaan == '---'){
          url = '<?php echo site_url("index.php/eaudit/periksa/ajax_create_new_lhkpn_pemeriksaan/") ?>';
        } else {
          url = '<?php echo site_url("index.php/eaudit/periksa/update_tgl_klarifikasi/") ?>' + '/' + data.id_lhkpn_pemeriksaan;
        }
        if (tgl_klarifikasi == '') {
          alertify.error('Isian dengan tanda bintang wajib diisi');
        }
        else{
         // create new data list
         $.post(url, data, function(res) {
            if (res == -1) {
              alertify.error('Data gagal disimpan');
            } else {
              if (res == 1){
                $('.btn-input-tgl').hide();
                $('#table-periksa').DataTable().ajax.reload();
                alertify.success('Tanggal BAK berhasil disimpan');
              } else {
                location.reload();
              }
            }
          });

          CloseModalBox2();
          location.reload();
        }
      <?php endif; ?>
    });
  })
</script>
