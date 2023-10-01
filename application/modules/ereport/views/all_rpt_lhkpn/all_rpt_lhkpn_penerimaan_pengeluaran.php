<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			E - Report LHKPN
		</h1>
		<?= @$breadcrumb; ?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<div class="col-md-offset-4 col-xs-8 col-sm-8 col-md-8 col-lg-8">
							<form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/ereport/all_rpt_lhkpn/penerimaan_pengeluaran">
						<div class="col-md-8 col-md-offset-2">
							<div class="form-group">
								<label class="col-sm-4 control-label">Tahun :</label>
								<div class="col-sm-5">
									<input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN'];?>" id="CARI_TAHUN" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Jenis Laporan :</label>
								<div class="col-sm-5">
									<select class="form-control" name="CARI[JENIS]">
									<option value="">-pilih Jenis-</option>
									<option value="1" <?php if(@$CARI['JENIS'] == 1){ echo 'selected';};?>>Khusus, Calon</option>
									<option value="2" <?php if(@$CARI['JENIS'] == 2){ echo 'selected';};?>>Khusus, Awal menjabat</option>
									<option value="3" <?php if(@$CARI['JENIS'] == 3){ echo 'selected';};?>>Khusus, Akhir menjabat</option>
									<option value="4" <?php if(@$CARI['JENIS'] == 4){ echo 'selected';};?>>Periodik tahunan</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Cari :</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA'];?>" id="CARI_NAMA" required>
								</div>
								<div class="col-sm-2">
									<button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
									<button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
								</div>
							</div>
						</div>
						<div class="clearfix" style="margin-bottom: 20px;"></div>
					</form>
	                    </div>
					</div>
					<div class="box-body">
						
							<div align="center">
                            	<?php if($thn!='' && $nama_pn!=''){ ?>
								<h5><strong><?php echo $nama_pn; ?></strong></h5>
								<h5><strong>LAPORAN PENERIMAAN DAN PENGELUARAN</strong></h5>
								<h5>UNTUK PERIODE YANG BERAKHIR SAMPAI DENGAN 31 DESEMBER <?php echo $thn; ?></h5>
							</div>
                            
        <?php 
            
            $label      = array('A', 'B', 'C');
            
       ?>
    <?php 
        $jenis = array(
                        array(
                                'Gaji dan tunjangan Penyelenggara Negara',
								'Gaji dan tunjangan Istri/ Suami',
                                'Penghasilan dari profesi / keahlian Penyelenggara Negara',
								'Penghasilan dari profesi / keahlian Istri / Suami',
                                'Honorarium Penyelenggara Negara',
								'Honorarium Istri / Suami',
                                'Tantiem, bonus, jasa produksi, THR Penyelenggara Negara',
								'Tantiem, bonus, jasa produksi, THR Istri / Suami',
								'Penerimaan dari pekerjaan lainnya',
                             ),
                        array(
								'Hasil investasi dalam surat berharga',
                                'Penerimaan bersih dari kegiatan usaha',
                                'Bunga tabungan, bunga deposito, dan lainnya',
                                'Hasil sewa',
                                'Penjualan atau pelepasan harta',
								'Penerimaan usaha dan kekayaan lainnya',
                             ),
                        array(
                                'Perolehan hutang',
                                'Penerimaan warisan',
                                'Penerimaan hibah / hadiah',
								'Lainnya',
                             )
                      );
@$lhkpn = $this->mglobal->get_data_all('T_LHKPN', null, ['IS_ACTIVE' => '1', 'ID_PN' => $id_pn, 'STATUS' => '3', 'YEAR(TGL_LAPOR)' => $thn]);
//echo $this->db->last_query();
@$id_lhkpn = $lhkpn[0]->ID_LHKPN;//'38';//siniiiiiiiiiiiiiiiiiiii
        $where     = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn'";
        $getPeka   = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $where);
        $PN        = @json_decode($getPeka[0]->NILAI_PENERIMAAN_KAS_PN);
        $PA        = @json_decode($getPeka[0]->NILAI_PENERIMAAN_KAS_PASANGAN);
        $lain      = @json_decode($getPeka[0]->LAINNYA);

    ?>
    		<table class="table table-bordered table-hover">
                        <thead class="table-header">
                            <tr>
                                <th width="40px">NO</th>
                                <th>URAIAN</th>
                                <th>20xxx</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; ?>
               <?php 
			   			$jml_1 = 0;
						$jml_2 = 0;
						$jml_3 = 0;
			   			for ($i=0; $i < count($jenis); $i++){
						?>
                        <tr>
                        	<td><center><?php echo $no; ?></center></td>
                            <td>
                            	<?php
                                if($i==0){
									echo "<span style='color:#0066FF;'>Penerimaan Dari Pekerjaan</span>";
								}else if($i==1){
									echo "<span style='color:#0066FF;'>Penerimaan Dari Usaha dan Kekayaan</span>";
								}else{
									echo "<span style='color:#0066FF;'>Penerimaan Lainnya</span>";
								}
								?>
                            </td>
                            <td></td>
                        </tr>
                        <?php
							$penandaPn = 0;
							$penandaPasangan = 0;
                            for ($j=0; $j < count($jenis[$i]); $j++){
								$no++;
                            ?>
                            <tr>
                                <td><center><?php echo $no; ?>.</center></td>
                                <td><?php echo $jenis[$i][$j]; ?></td>
                                <td>
									<?php 
									//penerimaan dari pekerjaan
									if($i==0){
										//penerimaan PN
										if(($j+1)%2!=0){
											$code = $label[$i].($j-$penandaPn);
											@$nominal = str_replace(".", "", @$PN->$label[$i]->$code)*1;
											echo 'Rp. '.number_format($nominal,2,",",".");
											$jml_1 += $nominal;
											$penandaPn++;
										//penerimaan pasangan
										}else{
											$PA_val = 'PA'.($j-$penandaPasangan-1);
											@$nominal = str_replace(".", "", @$PA->$PA_val)*1;
											echo 'Rp. '.number_format($nominal,2,",",".");
											$jml_1 += $nominal;
											$penandaPasangan++;
										}
									//penerimaan usaha	
									}else if($i==1){
										$code = $label[$i].$j;
										@$nominal = str_replace(".", "", @$PN->$label[$i]->$code)*1;
										echo 'Rp. '.number_format($nominal,2,",",".");
										$jml_2 += $nominal;
									//penerimaan lainnya
									}else{
										$code = $label[$i].$j;
										@$nominal = str_replace(".", "", @$PN->$label[$i]->$code)*1;
										echo 'Rp. '.number_format($nominal,2,",",".");
										$jml_3 += $nominal;
									}
									?>
                                </td>
                            </tr>
                            <?php
							if($j==count($jenis[$i])-1){
							?>
                            <tr>
                            	<td></td>
                            	<td>
									<?php
									if($i==0){
										echo "<b>Jumlah Penerimaan Dari Pekerjaan</b>";
									}else if($i==1){
										echo "<b>Jumlah Penerimaan Dari Usaha dan Kekayaan</b>";
									}else{
										echo "<b>Jumlah Penerimaan Lainnya</b>";
									}
									?>
                               	</td>
                                <td>
                                	<?php
									if($i==0){
										echo '<b>Rp. '.number_format($jml_1,2,",",".").'</b>';
									}else if($i==1){
										echo '<b>Rp. '.number_format($jml_2,2,",",".").'</b>';
									}else{
										echo '<b>Rp. '.number_format($jml_3,2,",",".").'</b>';
									}
									?>
                                </td>
                            <?php } ?>
                            <?php
							} 
						}
						?>
                        <tr>
                        	<td colspan="3">&nbsp;</td>
                        </tr>
                        <tr style="color:#FF0000;">
                        	<td></td>
                            <td><b>Jumlah Penerimaan</b></td>
                          	<td><b><?php echo 'Rp. '.number_format(($jml_1+$jml_2+$jml_3),2,",","."); ?></b></td>
                        </tr>
                        </tbody>
                    </table>
<?php
			$jenis = array(
                        array(
                                'Biaya Rumah Tangga',
                                'Biaya Transportasi',
                                'Biaya Pendidikan',
                                'Biaya Kesehatan',
                                'Biaya Keagamaan/Adat',
                                'Biaya Rekreasi',
                                'Pembayaran Pajak',
								'Lainnya'
                             ),
                        array(
                                'Pembelian atau Perolehan Harta Baru',
                                'Rehabilitasi / Renovasi / Modifikasi Harta',
								'Lainnya'
                             ),
                        array(
                                'Biaya Pengurusan Waris/hibah/hadiah',
                                'Pelunasan/Angsuran Hutang',
								'Lainnya'
                             )
                      ); 
        $where     = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$id_lhkpn'";
        $getPeka   = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $where);
        $PN        = @json_decode($getPeka[0]->NILAI_PENGELUARAN_KAS);
        $lain      = @json_decode($getPeka[0]->LAINNYA);
?>					<hr />
					<table class="table table-bordered table-hover">
                        <thead class="table-header">
                            <tr>
                                <th width="40px">NO</th>
                                <th>URAIAN</th>
                                <th>20xxx</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; ?>
               <?php 
			   			$jml_1 = 0;
						$jml_2 = 0;
						$jml_3 = 0;
			   			for ($i=0; $i < count($jenis); $i++){
						?>
                        <tr>
                        	<td><center><?php echo $no; ?></center></td>
                            <td>
                            	<?php
                                if($i==0){
									echo "<span style='color:#0066FF;'>Pengeluaran Umum</span>";
								}else if($i==1){
									echo "<span style='color:#0066FF;'>Pengeluaran Harta</span>";
								}else{
									echo "<span style='color:#0066FF;'>Pengeluaran Lainnya</span>";
								}
								?>
                            </td>
                            <td></td>
                        </tr>
                        <?php
                            for ($j=0; $j < count($jenis[$i]); $j++){
								$no++;
                            ?>
                            <tr>
                                <td><center><?php echo $no; ?>.</center></td>
                                <td><?php echo $jenis[$i][$j]; ?></td>
                                <td>
									<?php 
									if($i==0){
										$code = $label[$i].$j;
										@$nominal = str_replace(".", "", @$PN->$label[$i]->$code)*1;
										echo 'Rp. '.number_format($nominal,2,",",".");
										$jml_1 += $nominal;
									}else if($i==1){
										$code = $label[$i].$j;
										@$nominal = str_replace(".", "", @$PN->$label[$i]->$code)*1;
										echo 'Rp. '.number_format($nominal,2,",",".");
										$jml_2 += $nominal;
									//penerimaan lainnya
									}else{
										$code = $label[$i].$j;
										@$nominal = str_replace(".", "", @$PN->$label[$i]->$code)*1;
										echo 'Rp. '.number_format($nominal,2,",",".");
										$jml_3 += $nominal;
									}
									?>
                                </td>
                            </tr>
                            <?php
							if($j==count($jenis[$i])-1){
							?>
                            <tr>
                            	<td></td>
                            	<td>
									<?php
									if($i==0){
										echo "<b>Jumlah Pengeluaran Umum</b>";
									}else if($i==1){
										echo "<b>Jumlah Pengeluaran Harta</b>";
									}else{
										echo "<b>Jumlah Pengeluaran Lainnya</b>";
									}
									?>
                               	</td>
                                <td>
                                	<?php
									if($i==0){
										echo '<b>Rp. '.number_format($jml_1,2,",",".").'</b>';
									}else if($i==1){
										echo '<b>Rp. '.number_format($jml_2,2,",",".").'</b>';
									}else{
										echo '<b>Rp. '.number_format($jml_3,2,",",".").'</b>';
									}
									?>
                                </td>
                            <?php } ?>
                            <?php
							} 
						}
						?>
                        <tr>
                        	<td colspan="3">&nbsp;</td>
                        </tr>
                        <tr style="color:#FF0000;">
                        	<td></td>
                            <td><b>Jumlah Pengeluaran</b></td>
                          	<td><b><?php echo 'Rp. '.number_format(($jml_1+$jml_2+$jml_3),2,",","."); ?></b></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		$('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });

        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });
	});
</script>