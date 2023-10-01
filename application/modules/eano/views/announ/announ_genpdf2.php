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
 * @author Gunaones - PT.Mitreka Solusi Indonesia || Irfan Kiddo - Pirate.net
 * @package Views/eano/announ
*/
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="<?php echo base_url();?>css/style_annaoun.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div align="center" class="head-top">
		RINCIAN PENGUMUMAN HARTA KEKAYAAN PENYELENGGARA NEGARA <br> TAHUN <?php echo date('Y', strtotime($LHKPN->TGL_LAPOR)) ?>
	</div>
	<div class="head-content-second">
		<div class="hcs-first">
			<table class="tbl-hcsf">
				<tr>
					<td>Nama</td>
					<td>:</td>
					<td><?= strtoupper(@$LHKPN->NAMA); ?></td>
				</tr>
				<tr>
					<td valign="top">Jabatan</td>
					<td valign="top">:</td>
					<td valign="top">
						<table style="margin-bottom:-30px;">
							<?php //foreach ($lhkpn_jbtn as $jbtn) : ?>
							<!-- <tr>
								<td><?= @$jbtn->NAMA_JABATAN; ?></td>
							</tr> -->
							<tr>
                                <td><?= (!empty($lhkpn_jbtn[0]->TEXT_JABATAN_PUBLISH) ? strtoupper(@$lhkpn_jbtn[0]->TEXT_JABATAN_PUBLISH) : strtoupper(@$lhkpn_jbtn[0]->NAMA_JABATAN.' - '.@$lhkpn_jbtn[0]->DESKRIPSI_JABATAN))?></td>
							</tr>
							<?php //endforeach; ?>
						</table>
					</td>
				</tr>
				<tr>
					<td>Lembaga</td>
					<td>:</td>
					<td><?= @$LHKPN->INST_NAMA; ?></td>
				</tr>
				<tr>
					<td>Alamat kantor</td>
					<td>:</td>
					<td><?= strtoupper(@$LHKPN->ALAMAT_TINGGAL); ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5>1. Harta Tidak Bergerak (Tanah dan/atau Bangungan)</h5>
			<table class="tbl-ctn2" width="100%">
				<tr class="tbl-ctn2-header">
					<td align="center"><font color="white"><b>No</b></font></td>
					<td align="center"><font color="white"><b>Jenis dan Lokasi</b></font></td>
					<td align="center"><font color="white"><b>Luas Tanah / Bangunan</b></font></td>
					<td align="center"><font color="white"><b>Asal Usul Kekayaan</b></font></td>
					<td align="center"><font color="white"><b>Tahun Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<?php $no=1; foreach ($HARTA_TIDAK_BERGERAKS as $HTB): ?>
					<tr>
						<td align="center"><?= @$no++; ?></td>
						<td>
							<?php
                                if($HTB->ID_NEGARA == '0'){
                                    $ket = 'kota ';
                                    $area = getArea($HTB->PROV, $HTB->KAB_KOT)[0]->NAME;
                                }else{
                                    $ket = 'negara ';
                                    $area = getNegara($HTB->ID_NEGARA)[0]->NAMA_NEGARA;
                                }

								if($HTB->LUAS_TANAH != '' && $HTB->LUAS_BANGUNAN != ''){
									echo 'Tanah dan Bangunan di '.$ket.$area;
								}else if($HTB->LUAS_TANAH != '' && $HTB->LUAS_BANGUNAN == ''){
									echo 'Tanah di '. $ket . $area;
								}else if($HTB->LUAS_TANAH == '' && $HTB->LUAS_BANGUNAN != ''){
									echo 'Bangunan di '. $ket . $area;
								}else{
									echo $area;
								}
							?>
						</td>
						<td align="center">
							<?php 
								if($HTB->LUAS_TANAH != '' && $HTB->LUAS_BANGUNAN != ''){
									echo number_format($HTB->LUAS_TANAH, 0, ',', '.').' / '.number_format($HTB->LUAS_BANGUNAN, 0, ',', '.').' m2';
								}else if($HTB->LUAS_TANAH != '' && $HTB->LUAS_BANGUNAN == ''){
									echo number_format($HTB->LUAS_TANAH, 0, ',', '.').' m2';
								}else if($HTB->LUAS_TANAH == '' && $HTB->LUAS_BANGUNAN != ''){
									echo number_format($HTB->LUAS_BANGUNAN, 0, ',', '.').' m2';
								}else{
									'';
								}
							?>
						</td>
						<td align="center">
							<?php
								$exp = explode(',', $HTB->ASAL_USUL);
                                $tmp = [];
                                foreach ($exp as $key) {
                                    foreach ($asalusul as $au) {
                                        if($au->ID_ASAL_USUL == $key){
                                            $tmp[] = $au->ASAL_USUL;
                                        }
                                    }
                                }
                                echo implode(',', $tmp);
							?>
						</td>
						<td align="center"><?php echo $HTB->TAHUN_PEROLEHAN_AWAL." - ".$HTB->TAHUN_PEROLEHAN_AKHIR; ?></td>
						<td align="right">Rp. <?= number_format($HTB->NILAI_PEROLEHAN,0,'','.');  ?></td>
						<td align="right">Rp. <?=  number_format($HTB->NILAI_PELAPORAN,0,'','.'); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="6" align="center"><b>Total</b></td>
					<td align="right">
						Rp. <?php $A = $hartirak->sum_hartirak; echo number_format($hartirak->sum_hartirak,0,'','.'); ?>
					</td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5>2.1 Harta Bergerak (Alat Transportasi dan Mesin)</h5>
			<table class="tbl-ctn2" width="100%">
				<tr class="tbl-ctn2-header">
					<td align="center"><font color="white"><b>No</b></font></td>
					<td align="center"><font color="white"><b>Jenis dan Tipe</b></font></td>
					<td align="center"><font color="white"><b>Tahun Pembuatan</b></font></td>
					<td align="center"><font color="white"><b>Asal Usul Kekayaan</b></font></td>
					<td align="center"><font color="white"><b>Tahun Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<?php $noHB=1; foreach ($HARTA_BERGERAKS as $HB): ?>
					<tr>
						<td><?= @$noHB; ?></td>
						<td><?= @$HB->NAMA.' merek '.@$HB->MEREK.', model '.$HB->MODEL; ?></td>
						<td align="center"><?= @$HB->TAHUN_PEMBUATAN; ?></td>
						<td align="center">
							<?php  
								$exp  = explode(',', $HB->ASAL_USUL);
								$tmp1 = [];
                                foreach ($exp as $key) {
                                    foreach ($asalusul as $au) {
                                        if($au->ID_ASAL_USUL == $key){
                                            $tmp1[] = $au->ASAL_USUL;
                                        }
                                    }
                                }
                                echo implode(',', $tmp1);
							?>
						</td>
						<td align="center"><?= @$HB->TAHUN_PEROLEHAN_AWAL; ?></td>
						<td align="right">Rp. <?= number_format(@$HB->NILAI_PEROLEHAN,0,'','.'); ?></td>
						<td align="right">Rp. <?= number_format(@$HB->NILAI_PELAPORAN,0,'','.'); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="6" align="center"><b>Total</b></td>
					<td align="right">Rp. <?php $B = $harger->sum_harger; echo number_format($harger->sum_harger,0,'','.'); ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5>2.2. Harta Bergerak (Perabotan Rumah Tangga, Barang Eektronik, Perhiasan & Logam/Batu Mulia, Barang Seni/Antik, Persediaan Dan Harta Bergerak Lainnya)</h5>
			<table class="tbl-ctn2" width="100%">
				<tr class="tbl-ctn2-header">
					<td align="center"><font color="white"><b>No</b></font></td>
					<td align="center"><font color="white"><b>Jenis dan Tipe</b></font></td>
					<td align="center"><font color="white"><b>Kuantitas</b></font></td>
					<td align="center"><font color="white"><b>Asal Usul Kekayaan</b></font></td>
					<td align="center"><font color="white"><b>Tahun Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<?php 
					$noHB   = 1; 
					$jumHBL = 0; 
					foreach ($HARTA_BERGERAK_LAINS as $harbela): 
						$jumHBL += $harbela->NILAI_PELAPORAN;
				?>
					<tr>
						<td><?= @$noHB; ?></td>
						<td>
							<?php 
								if($harbela->KODE_JENIS == '1'){
									echo 'Perabotan Rumah Tangga';
								}else if($harbela->KODE_JENIS == '2'){
									echo 'Barang Elektronik';
								}else if($harbela->KODE_JENIS == '3'){
									echo 'Perhiasan Logam / Batu Mulia';
								}else if($harbela->KODE_JENIS == '4'){
									echo 'Barang Seni / Antik';
								}else if($harbela->KODE_JENIS == '5'){
									echo 'Persediaan';
								}else{
									echo 'Harta Bergerak Lainnya';
								}
						 	?>
					 	</td>
						<td align="center"><?= @$harbela->KETERANGAN; ?></td>
						<td align="center">
							<?php  
								$exp  = explode(',', $harbela->ASAL_USUL);
								$tmp2 = [];
                                foreach ($exp as $key) {
                                    foreach ($asalusul as $au) {
                                        if($au->ID_ASAL_USUL == $key){
                                            $tmp2[] = $au->ASAL_USUL;
                                        }
                                    }
                                }
                                echo implode(',', $tmp2);
							?>
						</td>
						<td align="center"><?= @$harbela->TAHUN_PEROLEHAN_AWAL.' - '. @$harbela->TAHUN_PEROLEHAN_AKHIR; ?></td>
						<td align="right">Rp. <?= @number_format($harbela->NILAI_PEROLEHAN, 0, ',', '.'); ?></td>
						<td align="right">Rp. <?= @number_format($harbela->NILAI_PELAPORAN, 0, ',', '.'); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="6" align="center"><b>Total</b></td>
					<td align="right">Rp. <?php $C = $jumHBL; echo number_format($jumHBL,0,'','.');  ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5>3. Surat Berharga</h5>
			<table class="tbl-ctn2" width="100%">
				<tr class="tbl-ctn2-header">
					<td align="center"><font color="white"><b>No</b></font></td>
					<td align="center"><font color="white"><b>Tahun Investasi</b></font></td>
					<td align="center"><font color="white"><b>Asal Usul Kekayaan</b></font></td>
					<td align="center"><font color="white"><b>Tahun Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<?php $noHB=1; foreach ($HARTA_SURAT_BERHARGAS as $harsuber): ?>
					<tr>
						<td><?= @$noHB; ?></td>
						<td><?= "Tahun Investasi ".@$harsuber->TAHUN_PEROLEHAN_AWAL; ?></td>
						<td align="center">
							<?php  
								$exp  = explode(',', $harsuber->ASAL_USUL);
								$tmp3 = [];
                                foreach ($exp as $key) {
                                    foreach ($asalusul as $au) {
                                        if($au->ID_ASAL_USUL == $key){
                                            $tmp3[] = $au->ASAL_USUL;
                                        }
                                    }
                                }
                                echo implode(',', $tmp3);
							?>
						</td>
						<td align="center"><?= @$harsuber->TAHUN_PEROLEHAN_AWAL.' - '. @$harsuber->TAHUN_PEROLEHAN_AKHIR; ?></td>
						<td align="right">Rp. <?= number_format(@$harsuber->NILAI_PEROLEHAN, 0, ',', '.'); ?></td>
						<td align="right">Rp. <?= number_format(@$harsuber->NILAI_PELAPORAN, 0, ',', '.'); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="5" align="center"><b>Total</b></td>
					<td align="right">Rp. <?php @$D = $suberga->sum_suberga; echo number_format(@$suberga->sum_suberga,0,'','.');  ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5>4. Kas dan Setara Kas</h5>
			<table class="tbl-ctn2" width="100%">
				<tr class="tbl-ctn2-header">
					<td align="center"><font color="white"><b>No</b></font></td>
					<td align="center"><font color="white"><b>Uraian</b></font></td>
					<!-- <td align="center"><font color="white"><b>Asal Usul Kekayaan</b></font></td> -->
					<td align="center"><font color="white"><b>Nilai Perolehan</b></font></td>
					<!-- <td align="center"><font color="white"><b>Nilai Pelaporan</b></font></td> -->
				</tr>
				<?php $noHB=1; foreach ($HARTA_KASS as $harsuber): ?>
					<tr>
						<td><?= @$noHB; ?></td>
						<td>Kas dan Setara Kas dalam  mata Uang Rupiah</td>
						<!-- <td align="center">
							<?php  
								// $exp  = explode(',', $harsuber->ASAL_USUL);
								// $tmp4 = [];
        //                         foreach ($exp as $key) {
        //                             foreach ($asalusul as $au) {
        //                                 if($au->ID_ASAL_USUL == $key){
        //                                     $tmp4[] = $au->ASAL_USUL;
        //                                 }
        //                             }
        //                         }
        //                         echo implode(',', $tmp4);
							?>
						</td> -->
						<td align="right">Rp. <?= number_format(@$harsuber->NILAI_EQUIVALEN, 0, ',', '.'); ?></td>
						<!-- <td align="right">Rp. <?= number_format(@$harsuber->NILAI_SALDO, 0, ',', '.'); ?></td> -->
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="2" align="center"><b>Total</b></td>
					<td align="right">Rp. <?php @$E = $kaseka->sum_kaseka; echo number_format(@$kaseka->sum_kaseka,0,'','.');  ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5>5. Harta Lainnya</h5>
			<table class="tbl-ctn2" width="100%">
				<tr class="tbl-ctn2-header">
					<td align="center"><font color="white"><b>No</b></font></td>
					<td align="center"><font color="white"><b>Uraian</b></font></td>
					<td align="center"><font color="white"><b>Tahun Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<?php $noHB=1; foreach ($HARTA_LAINNYAS as $harlins): ?>
					<tr>
						<td><?= @$noHB; ?></td>
						<td>
							<?php 
								if($harlins->KODE_JENIS == '1'){
									echo 'Piutang';
								}else if($harlins->KODE_JENIS == '2'){
									echo 'Kerjasama Usaha yang Tidak Berbadan Hukum';
								}else if($harlins->KODE_JENIS == '3'){
									echo 'Hak Kekayaan Intelektual';
								}else if($harlins->KODE_JENIS == '4'){
									echo 'Sewa Jangka Panjang Dibayar Dimuka';
								}else{
									echo 'Hak Pengelolaan / Pengusaha yang dimiliki perorangan';
								}
						 	?>
						</td>
						<td align="center"><?= @$harlins->TAHUN_PEROLEHAN_AWAL; ?></td>
						<td align="right">Rp. <?= number_format(@$harlins->NILAI_PEROLEHAN, 0, ',', '.'); ?></td>
						<td align="right">Rp. <?= number_format(@$harlins->NILAI_PELAPORAN, 0, ',', '.'); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="4" align="center"><b>Total</b></td>
					<td align="right">Rp. <?php @$F = $harlin->sum_harlin; echo number_format(@$F,0,'','.');  ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5></h5>
			<table class="tbl-ctn2" width="100%">
				<tr>
					<td colspan="3" rowspan="2" align="center"><h2>Sub-total Harta</h2></td>
					<td width="20%" class="tbl-ctn2-header" align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<tr>
					<td align="right">Rp. <?php $subtotalhrt = $A+$B+$C+$D+$E+$F; echo number_format($subtotalhrt,0,'','.');  ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5>6. Hutang</h5>
			<table class="tbl-ctn2" width="100%">
				<tr class="tbl-ctn2-header">
					<td align="center"><font color="white"><b>No</b></font></td>
					<td align="center"><font color="white"><b>Tahun Transaksi</b></font></td>
					<td align="center"><font color="white"><b>Nilai Perolehan</b></font></td>
					<td align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<?php $noHB=1; foreach ($HUTANGS as $htng): ?>
					<tr>
						<td><?= @$noHB; ?></td>
						<td><?= 'Tahun Transaksi '. date('Y',strtotime($htng->TANGGAL_TRANSAKSI)); ?></td>
						<td align="right">Rp. <?= number_format(@$htng->SALDO_HUTANG, 0, ',', '.'); ?></td>
						<td align="right">Rp. <?= number_format(@$htng->SALDO_HUTANG, 0, ',', '.'); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="3" align="center"><b>Total</b></td>
					<td align="right">Rp. <?php @$G = @$_hutang->sum_hutang; echo number_format($G,0,'','.');  ?></td>
				</tr>
			</table>
		</div>
		<div class="ctn-2">
			<h5></h5>
			<table class="tbl-ctn2" width="100%">
				<tr>
					<td colspan="3" rowspan="2" align="center"><h2>Total Harta Kekayaan</h2></td>
					<td width="20%" class="tbl-ctn2-header" align="center"><font color="white"><b>Nilai Pelaporan</b></font></td>
				</tr>
				<tr>
					<td align="right">Rp. <?php $total = $subtotalhrt - $G; echo number_format($total,0,'','.');  ?></td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>