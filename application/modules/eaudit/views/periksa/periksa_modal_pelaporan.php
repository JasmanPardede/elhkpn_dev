<?php ?>
<div class="container-fluid">
  <div class="data-pn">
    <strong>PN</strong>: <?php echo $nama_pn ?> ( <?php echo $agenda; ?> )
  </div>
  <table class="table table-striped table-pengaduan">
    <thead>
      <tr>
        <th>Tanggal Pengaduan</th>
        <th>Isi Informasi</th>
        <th>Keterangan Pemeriksa</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pelaporan as $key => $rl): ?>
      <tr class="font-small">
        <td>
          <?php echo date('d M Y', strtotime($rl->tgl)) ?>
        </td>
        <td>
          <?php echo $rl->pengaduan; ?>
        </td>
        <td>
          <?php echo $rl->keterangan; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <hr>
  <div class="buttons">
    <button class="btn btn-danger" id="btn-batal" type="reset" name="button">Tutup</button>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {

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

