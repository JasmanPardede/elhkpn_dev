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
<table class="table table-bordered table-hover">
  <thead class="table-header">
	<tr>
	  <th>NO</th>
	  <th>ATAS NAMA / NAMA KREDITUR</th>
	  <th>TANGGAL TRANSAKSI</th>
	  <th>TANGGAL JATUH TEMPO</th>
	  <th>BENTUK AGUNAN/ NO KARTU KREDIT</th>
	  <th>SALDO HUTANG SAAT PELAPORAN</th>
	</tr>                 
  </thead>
  <tbody>
		<?php $tot =0; $i = 0; foreach ($HUTANGS as $hutang) { $tot+=$hutang->SALDO_HUTANG;?>
		<tr>
		  <td><?=++$i?>.</td>
		  <td><?php echo $hutang->NAMA_KREDITUR; ?></td>
		  <td><?php echo $hutang->TANGGAL_TRANSAKSI; ?></td>
		  <td><?php echo $hutang->TANGGAL_JATUH_TEMPO; ?></td>
		  <td><?php echo $hutang->AGUNAN; ?></td>
		  <td align="right">Rp. <?php echo number_format($hutang->SALDO_HUTANG,0,'','.'); ?></td>
		</tr>
		<?php } ?>
  </tbody>
  <tfoot>
  	<tr>
  		<th colspan="5" style="text-align: right;">Total</th>
  		<th style="text-align: right;">Rp. <?php echo number_format($tot,0,'','.'); ?></th>
  	</tr>
  </tfoot>
</table>