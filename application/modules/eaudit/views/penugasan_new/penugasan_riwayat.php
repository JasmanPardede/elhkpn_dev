<div class="container-fluid">

  <div class="profil-pn">
    <p><?php echo $title; ?></p>
  </div>

  <table class="table table-riwayat">
    <thead>
      <tr>
        <th>No</th>
        <th>No Agenda</th>
        <th>Tanggal Penugasan</th>
        <th>Jenis Penugasan</th>
        <th>Status</th>
        <th>Ditugaskan oleh</th>
        <th>Tim Pemeriksa</th>
        <th>PIC</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($daftar_riwayat) <= 0): ?>
      <?php else: ?>
        <?php $no = 0; ?>
        <?php foreach ($daftar_riwayat as $riwayat): ?>
          <tr>
            <td><?php echo ++$no; ?></td>
            <td><?php echo $riwayat->agenda; ?></td>
            <td><?php echo date('d-m-Y',strtotime($riwayat->tgl_mulai_periksa)) ; ?> sd <?php echo date('d-m-Y',strtotime($riwayat->tgl_selesai_periksa)) ; ?></td>
            <td>
              <?php
                $jenis_penugasan = $riwayat->jenis_penugasan;
                if ($jenis_penugasan == '0') {
                  echo "Penelaahan";
                }
                else {
                  if ($riwayat->jenis_pemeriksaan == '1') {
                    echo "Pemeriksaan Tertutup (".$riwayat->nomor_surat_tugas.')';
                  }
                  else{
                    echo "Pemeriksaan Terbuka (".$riwayat->nomor_surat_tugas.')';
                  }
                }
              ?>
            </td>
            <td>
              <?php
                $status_periksa =  $riwayat->status_periksa;
                switch ($status_periksa) {
                  case '1':
                    echo 'Baru';
                    break;
                  case '2':
                    echo 'Proses';
                    break;
                  case '3':
                    echo 'Selesai';
                    break;
                  default:
                    echo '-';
                    break;
                }
              ?>
            </td>
            <td><?php echo $riwayat->creator; ?></td>
            <td><?php echo $riwayat->pemeriksa; ?></td>
            <td><?php echo ($riwayat->pic ? $riwayat->pic : '---' ); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>

    </tbody>
  </table>

  <div class="text-center">
    <button class="btn btn-default" id="btn-batal" type="button" name="button">Tutup</button>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

  // initialize
  // $('.table-riwayat').DataTable();

  // events
  $('#btn-batal').click(function(e){
    e.preventDefault();
    CloseModalBox2();
  });

  // --end--
});
</script>
