<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/efill/lhkpn_view
*/
?>
<div class="box-body" id="wrapperLampiran2">
  <table class="table table-bordered table-hover table-striped">
      <thead class="table-header">
          <tr>
              <th width="10px">NO</th>
              <th>KODE JENIS</th>
              <th>NAMA FASILITAS</th>
              <th>NAMA PIHAK PEMBERI FASILITAS</th>
              <th>KETERANGAN</th>
          </tr>
      </thead>
      <tbody>
          <?php 
            $i = 0; 
            foreach ($lamp2s as $lamp2) { 
            if($lamp2->JENIS_FASILITAS == '1'){
              $text = 'Rumah Dinas';
            }else if($lamp2->JENIS_FASILITAS == '2'){
              $text = 'Biaya Hidup';
            }else if($lamp2->JENIS_FASILITAS == '3'){
              $text = 'Jaminan Kesehatan';
            }else if($lamp2->JENIS_FASILITAS == '4'){
              $text = 'Mobil Dinas';
            }else if($lamp2->JENIS_FASILITAS == '5'){
              $text = 'Opsi Pembelian Saham';
            }else{
              $text = 'Fasilitas Lainnya';
            }
          ?>
          <tr>
              <td><?php echo ++$i; ?></td>
              <td><?php echo $text; ?></td>
              <td><?php echo $lamp2->NAMA_FASILITAS; ?></td>
              <td><?php echo $lamp2->PEMBERI_FASILITAS; ?></td>
              <td><?php echo $lamp2->KETERANGAN; ?></td>
          </tr>
          <?php } ?>
      </tbody>
  </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->