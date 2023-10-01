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
		PENGUMUMAN HARTA KEKAYAAN PENYELENGGARA NEGARA <br> TAHUN <?php echo date('Y', strtotime($LHKPN->TGL_LAPOR)) ?>
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
								<td><?= strtoupper(@$jbtn->NAMA_JABATAN); ?></td>
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
					<td><?= strtoupper(@$LHKPN->INST_NAMA); ?></td>
				</tr>
				<tr>
					<td>Alamat kantor</td>
					<td>:</td>
					<td><?= strtoupper(@$LHKPN->ALAMAT_TINGGAL); ?></td>
				</tr>
			</table>

			<table width="100%" class="tbl-ctn2 tbl-ctn3">
				<tr class="tbl-header">
					<td align="center" rowspan="2" width="30px"><b>No</b></td>
					<td align="center" rowspan="2"><b>Uraian</b></td>
					<?= (!empty(@$cekLHKPN) ? '<td align="center"><b>Nilai Pelaporan Sebelumnya</b></td>' : ''); ?>
					<td align="center"><b>Nilai Pelaporan Ini</b></td>
				</tr>
				<tr class="tbl-header">
					<?= (!empty(@$cekLHKPN) ? '<td align="center"><b>Tahun ('.date('Y', strtotime($cekLHKPN->TGL_LAPOR)).')</b></td>' : ''); ?>
					<td align="center" width="40%"><b>Tahun (<?php echo date('Y', strtotime($LHKPN->TGL_LAPOR)) ?>)</b></td>
				</tr>
				<tr>
					<td align="center">1</td>
					<td>Harta Tidak Bergerak</td>
					<?php 
						$AA = @$hartirakSeb->sum_hartirakSeb;
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '. number_format(@$hartirakSeb->sum_hartirakSeb,0,'','.').'</td>' : ''); 
					?>
					<td align="right">
						Rp. 
			    		<?php 
			    			$A = $hartirak->sum_hartirak;
			    			echo number_format($hartirak->sum_hartirak,0,'','.'); 
		    			?>
					</td>
				</tr>
				<tr>
					<td align="center">2</td>
					<td>Harta Bergerak</td>
					<?php
						$BB = @$hargerSeb->sum_hargerSeb + @$harger3Seb->sum_harger3Seb;
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '.number_format((@$hargerSeb->sum_hargerSeb + @$harger3Seb->sum_harger3Seb),0,'','.').'</td>' : ''); 
					?>
					<td align="right">
						Rp. 
			    		<?php 
			    			$B = $harger->sum_harger + $harger3->sum_harger3;
			    			echo number_format(($harger->sum_harger+$harger3->sum_harger3),0,'','.');
		    			?>
					</td>
				</tr>
				<tr>
					<td align="center">3</td>
					<td>Surat Berharga</td>
					<?php 
						$CC = @$subergaSeb->sum_subergaSeb;
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '.number_format(@$subergaSeb->sum_subergaSeb,0,'','.').'</td>' : ''); 
					?>
					<td align="right">
						Rp. 
			    		<?php 
			    			@$D = $suberga->sum_suberga;
			    			echo number_format(@$suberga->sum_suberga,0,'','.'); 
		    			?>
					</td>
				</tr>
				<tr>
					<td align="center">4</td>
					<td>Kas dan Setara Kas</td>
					<?php 
						$DD = @$kasekaSeb->sum_kasekaSeb;
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '.number_format(@$kasekaSeb->sum_kasekaSeb,0,'','.').'</td>' : ''); 
					?>
					<td align="right">
						Rp. 
			    		<?php 
			    			@$E = $kaseka->sum_kaseka;
			    			echo number_format(@$kaseka->sum_kaseka,0,'','.'); 
		    			?>
					</td>
				</tr>
				<tr>
					<td align="center">5</td>
					<td>Harta Lainnya</td>
					<?php 
						$EE = @$harlinSeb->sum_harlinSeb;
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '.number_format(@$harlinSeb->sum_harlinSeb,0,'','.').'</td>' : ''); 
					?>
					<td align="right">
						Rp. 
			    		<?php 
			    			$C = @$harger2->sum_harger2;
			    			echo number_format(@$harger2->sum_harger2,0,'','.'); 
		    			?>
					</td>
				</tr>
				<tr>
					<td></td>
					<td align="center"><b>Total Harta</b></td>
					<?php
						$totSeb = $AA+$BB+$CC+$DD+$EE;
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '.number_format($totSeb,0,'','.').'</td>' : ''); 
					?>
					<td align="right">
						Rp. 
		    			<?php 
		    				$totII = $A+$B+$C+$D+$E;
		    				echo number_format($totII,0,'','.'); 
	    				?>
					</td>
				</tr>
				<tr>
					<td align="center">6</td>
					<td>Hutang</td>
					<?php 
						$FF = @$hutangSeb->sum_hutangSeb;
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '.number_format(@$hutangSeb->sum_hutangSeb,0,'','.').'</td>' : ''); 
					?>
					<td align="right">
						Rp. 
						<?php 
							@$F = $_hutang->sum_hutang;
							echo number_format(@$_hutang->sum_hutang,0,'','.'); 
						?>
					</td>
				</tr>
				<tr>
					<td></td>
					<td align="center"><b>Total Harta Kekayaan</b></td>
					<?php 
						echo (!empty(@$cekLHKPN) ? '<td align="right">Rp. '.number_format($totSeb - $FF,0,'','.').';</td>' : ''); 
					?>
					<td align="right">
						Rp. 
		    			<?php echo number_format($totII-$F,0,'','.'); ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>



