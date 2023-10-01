<?php ?>
<div class="data-pn">
  <strong>PN</strong>: <?php echo $nama_pn ?> ( <?php echo $id_lhkpn; ?> )
</div>
<table class="table table-striped table-condensed table-kkp">
  <thead>
    <tr>
      <th>Tahun<br>Lapor</th>
      <th>Nomor Agenda</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($riwayat_lapor as $key => $rl): ?>
    <tr class="font-small">
      <td>
        <?php echo date('Y', strtotime($rl->tgl_lapor)) ?>
      </td>
      <td>
        <?php echo ($rl->ID_LHKPN == $id_lhkpn) ? '<b>' : ''; ?>

        <?php echo date('Y', strtotime($rl->tgl_lapor)) ?>/<?php
          if ($rl->JENIS_LAPORAN == 4)
            echo 'R';
          else if ($rl->JENIS_LAPORAN == 5)
            echo 'P';
          else
            echo 'K';
        ?>/<?php
          // NIK
          echo $rl->NIK;
        ?>/<?php
          echo $rl->ID_LHKPN;
         ?>
       <?php echo ($rl->ID_LHKPN == $id_lhkpn) ? '</b>' : ''; ?>
      </td>
      <td>
        <?php
          switch ($rl->STATUS) {
            case '0':
              echo "Draft";
              break;
            case '1':
              echo "Masuk";
              break;
            case '2':
              echo "Perlu Perbaikan";
              break;
            case '3':
              echo "Terverifikasi Lengkap";
              break;
            case '4':
              echo "Diumumkan Lengkap";
              break;
            case '5':
              echo "Terverifikasi tidak lengkap";
              break;
            case '6':
              echo "Diumumkan tidak lengkap";
              break;
            case '7':
              echo "ditolak";
              break;
          }
        ?>
     </td>
      <td><a href="#" class="btn btn-xs btn-warning btn-cetak-kkp"
        data-idlhkpn="<?php echo $rl->ID_LHKPN; ?>"
        data-nost="<?php echo $no_st;?>"
        data-idaudit="<?php echo $id_audit;?>"
        data-namapn="<?php echo $nama_pn; ?>"
        data-status="<?php echo $status_periksa;?>">Cetak KKP</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<!--
<pre>
<?php var_dump($riwayat_lapor); ?>
</pre> -->

<p>
<small><strong>Catatan: </strong><br>
Nomor agenda yg dicetak tebal adalah yg ditugaskan</small>
</p>
<hr>
<div class="buttons">
  <button class="btn btn-danger" id="btn-batal" type="reset" name="button">Tutup</button>
</div>


<script type="text/javascript">
  $(document).ready(function() {

    $('#btn-batal').click(function(e){
      e.preventDefault();
      CloseModalBox2();
      $('#table-periksa').DataTable().ajax.reload();
    });

    // TODO: ------- update button agar dapat digunakan
    // proses cetak kkp
    $('.table-kkp tbody').on('click', '.btn-cetak-kkp', function (e) {
      e.preventDefault();

      // update status jika status pemeriksaan = 1 ( pemeriksaan baru ), menjadi 2 (dalam proses pemeriksaan)
      var status_periksa = $(this).data('status');
      var id_lhkpn = $(this).data('idlhkpn');
      if (status_periksa == 1) {
        // var url = '<?php echo site_url("/index.php/eaudit/periksa/ajax_set_status_periksa"); ?>/'+ id_audit + '/2';
        var url = '<?php echo site_url("/index.php/eaudit/periksa/ajax_set_status_periksa"); ?>' + '/';
        var data = {
          'no_st' : $(this).data('nost'),
          'id_audit' : $(this).data('idaudit'),
          'status' : '2',
          'id_lhkpn' : id_lhkpn
        }
        $.post(url, data);
      }

      // proses cetak kkp
      var id_nama_pn = $(this).data('namapn');
      var url = '<?php echo site_url("/index.php/eaudit/CetakKKP/export_kkp"); ?>/'+ id_lhkpn;
      window.location.href = url;

      table.ajax.reload();
      $('#table-periksa').DataTable().ajax.reload();
      alertify.warning('Memproses Cetak KKP: ' + id_lhkpn + '\n' + id_nama_pn);
    } );

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
