<?php
	$array = [
				[
					'posisi' => '1',
					'Uraian' => 'Jumlah Harta Awal',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '',
					'Uraian' => '',
					'20x1'   => '',
					'20x0'   => ''
				],
				[
					'posisi' => '1',
					'Uraian' => 'Penambahan Harta',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Pembelian/perolehan harta',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Penerimaan warisan',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Penerimaan hibah',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Penerimaan hadiah',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Peningkatan nilai harta',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '3',
					'Uraian' => 'Jumlah Penambahan Harta',
					'20x1'   => 'xxxx',
					'20x0'   => 'xxxx'
				],
				[
					'posisi' => '',
					'Uraian' => '',
					'20x1'   => '',
					'20x0'   => ''
				],
				[
					'posisi' => '1',
					'Uraian' => 'Pengurangan Harta',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Penjualan/Pelepasan Harta',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Pengeluaran hibah',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Pengeluaran hadiah',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '2',
					'Uraian' => 'Penurunan nilai harta',
					'20x1'   => 'xxx',
					'20x0'   => 'xxx'
				],
				[
					'posisi' => '3',
					'Uraian' => 'Jumlah Pengurangan Harta',
					'20x1'   => 'xxxx',
					'20x0'   => 'xxxx'
				],
				[
					'posisi' => '',
					'Uraian' => '',
					'20x1'   => '',
					'20x0'   => ''
				],
				[
					'posisi' => '1',
					'Uraian' => 'Jumlah Harta Akhir',
					'20x1'   => 'xxxxx',
					'20x0'   => 'xxxxx'
				],
			 ];
?>
<style type="text/css">
	tabs{
		margin-left:1em;
	}
</style>
<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			Laporan Perubahan Harta
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<form method="post" class='form-horizontal' id="ajaxFormCarireportperubahanharta" action="index.php/ereport/all_rpt_lhkpn/perubahan_harta">
						    <div class="box-body">
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Tahun :</label>
						                <div class="col-sm-4">
						                    <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN'];?>" id="CARI_TAHUN">
						                </div>
						                <div class="col-sm-3">
						                    
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Jenis Laporan :</label>
						                <div class="col-sm-4">
						                    <select class="form-control" name="CARI[JENIS]">
						                        <option value="">-pilih Jenis-</option>
						                        <option value="1" <?php if(@$CARI['JENIS'] == 1){ echo 'selected';};?>>Khusus, Calon</option>
						                        <option value="2" <?php if(@$CARI['JENIS'] == 2){ echo 'selected';};?>>Khusus, Awal menjabat</option>
						                        <option value="3" <?php if(@$CARI['JENIS'] == 3){ echo 'selected';};?>>Khusus, Akhir menjabat</option>
						                        <option value="4" <?php if(@$CARI['JENIS'] == 4){ echo 'selected';};?>>Periodik tahunan</option>
						                    </select>
						                    <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
						                </div>
						                <div class="col-sm-3">
						                    
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Cari :</label>
						                <div class="col-sm-4">
						                    <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA'];?>" id="CARI_NAMA">
						                    <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
						                </div>
						                <div class="col-sm-2">
						                </div>                                        
						            </div>
						        </div>
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label"></label>
						                <div class="col-sm-4" align="right">
						                	<button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
						                	<button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
						                </div>
						                <div class="col-sm-2">
						                </div>                                        
						            </div>
						        </div>
						    </div>
						       
						</form>
						<center>
							<div class="col-md-12">
								<b>ABCD</b>
							</div>
							<div class="col-md-12">
								<b>LAPORAN PERUBAHAN HARTA</b>
							</div>
							<div class="col-md-12">
								PER 31 DESEMBER 20X1 dan 20X0
							</div>
							<div class="col-md-12">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th width="40px" class="text-center">No.</th>
											<th class="text-center">Uraian</th>
											<th width="200px" class="text-center">20X1</th>
											<th width="200px" class="text-center">20X0</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$no = 0;
											foreach ($array as $key) {
											$no++;
										?>
											<tr>
												<td class="text-center"><?=$no;?></td>
												<td>
													<?php
														if ($key['posisi'] == '1') {
															echo $key['Uraian'];
														}
														elseif ($key['posisi'] == '2') {
															echo '<tabs/>'.$key['Uraian'];
														}
														elseif ($key['posisi'] == '3') {
															echo '<tabs/><tabs/>'.$key['Uraian'];
														}
													?>
												</td>
												<td class="text-center">
													<?php
														if ($key['posisi'] == '3') {
															echo '<b>'.$key['20x1'].'</b>';
														}
														elseif ($key['Uraian'] == 'Jumlah Harta Akhir') {
															echo '<b>'.$key['20x1'].'</b>';
														}
														else
														{
															echo $key['20x1'];
														}
													?>
												</td>
												<td class="text-center">
													<?php
														if ($key['posisi'] == '3') {
															echo '<b>'.$key['20x0'].'</b>';
														}
														elseif ($key['Uraian'] == 'Jumlah Harta Akhir') {
															echo '<b>'.$key['20x0'].'</b>';
														}
														else
														{
															echo $key['20x0'];
														}
													?>
												</td>
											</tr>
										<?php
											}
										?>
									</tbody>
								</table>
							</div>
						</center>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.year-picker').datepicker({
		    orientation: "left",
		    format: 'yyyy',
		    viewMode: "years",
		    minViewMode: "years",
		    autoclose: true
		});
		$("#ajaxFormCarireportperubahanharta").submit(function(e) {
		    var url = $(this).attr('action');
		    ng.LoadAjaxContentPost(url, $(this));
		    return false;
		});
        $("#e-report-lhkpn").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });  	
        $('#btn-clear').click(function(event) {
            $('#ajaxFormCarireportperubahanharta').find('input:text').val('');
            $('#ajaxFormCarireportperubahanharta').find('select').val('');
            $('#ajaxFormCarireportperubahanharta').trigger('submit');
        });	
	});
</script>

<style type="text/css">
	/*#e-report-lhkpn a{
		display: block;
	}*/
</style>