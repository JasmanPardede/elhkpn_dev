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
<table class="table table-bordered table-hover table-striped">
		<thead class="table-header">
			<tr>
				<th width="10px">NOA</th>
				<th width="180px">KODE JENIS</th>
				<th width="180px">NAMA HARTA & KUANTITAS HARTA</th>
				<th width="180px">KEPEMILIKAN</th>
				<th width="80px">TAHUN PEROLEHAN</th>
				<th width="170px">NILAI PEROLEHAN</th>
				<th width="170px">NILAI PELAPORAN</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 0;
			foreach ($HARTA_LAINNYAS as $hartalainnya) {
				?>
			<tr>
				<td><?=++$i;?>.</td>
				<td>
					<div class="row">
						<div class="col-sm-12">
							<?php switch($hartalainnya->KODE_JENIS){
								case '1' : echo 'Piutang'; break;
								case '2' : echo 'Kerjasama Usaha yang Tidak Berbadan Hukum'; break;
								case '3' : echo 'Hak Kekayaan Intelektual'; break;
								case '4' : echo 'Sewa Jangaka Panjang Dibayar Dimuka'; break;
								case '5' : echo 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan'; break;
							}; ?>
						</div>
					</div>
				</td>
				<td>
					<div class="row">
						<div class="col-sm-12">
							<?php echo $hartalainnya->NAMA; ?>
						</div>
						<div class="col-sm-12">
							<?php echo $hartalainnya->KUANTITAS; ?>
						</div>
					</div>									
				</td>
				<td>
					<div class="row">
						<div class="col-md-12">
							<?php echo $hartalainnya->ATAS_NAMA; ?>
						</div>
						<div class="col-sm-12">
							<?php switch($hartalainnya->ASAL_USUL){
								case '1' : echo 'Hasil Sendiri'; break;
								case '2' : echo 'Warisan'; break;
								case '3' : echo 'Hibah'; break;
								case '4' : echo 'Hadiah'; break;
							}; ?>
						</div>
					</div>
				</td>
		  		<td>
					<div class="row">
						<div class="col-md-12">
							Tahun
						</div>
						<div class="col-md-12">
							<?php echo $hartalainnya->TAHUN_PEROLEHAN_AWAL; ?>
						</div>
					</div>
				</td>
			  	<td>
					<div class="row">
						<div class="col-md-12">Mata Uang</div>
						<div class="col-md-12">
							<?php switch($hartalainnya->MATA_UANG){
								case '1' : echo 'IDR'; break;
								case '2' : echo 'USD'; break;
								case '3' : echo 'Yen'; break;
							}; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							Nilai
							<?php echo number_format($hartalainnya->NILAI_PEROLEHAN, 0, '.', '.'); ?>
						</div>
					</div>
				</td>
				<td>
					<div class="row">
						<div class="col-sm-2">Rp</div>
						<div class="col-sm-12">
							<?php echo number_format($hartalainnya->NILAI_PELAPORAN, 0, '.', '.'); ?>
						</div>
						<div class="col-sm-12">
							<?php switch($hartalainnya->JENIS_NILAI_PELAPORAN){
								case '1' : echo 'Appraisal'; break;
								case '2' : echo 'Perkiraan Pasar'; break;
							}; ?>
						</div>
						
					</div>
				</td>
			  	
			</tr>
		<?php } ?>
  </tbody>
</table> 