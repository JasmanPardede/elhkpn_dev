<style type="text/css">
	.text_center {
		text-align: center;
	}
	.tab_line {
		text-indent: 1em;
	}
	.tab_line2 {
		text-indent: 2em;
	}
</style>
<section class="content-header">
	<h1>
		<i class="fa fa-list"></i> Laporan Posisi/Neraca Harta Kekayaan
	</h1>
	<ol class="breadcrumb">
		<!-- <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li> -->
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/ereport/all_rpt_lhkpn/harta_kekayaan">
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
					<hr>
					<?php if($thn!='' && $nama_pn!=''){ ?>
					<div class="text-center">
						<p><strong><?php echo $nama_pn; ?></strong></p>
						<p><strong>LAPORAN POSISI/NERACA HARTA KEKAYAAN</strong></p>
						<p>PER 31 DESEMBER <?php echo $thn-1; ?> dan <?php echo $thn; ?></p>
					</div>
					<table class="table table-bordered table-hover">
						<thead class="text-center">
							<tr>
								<td width="40px;">No.</td>
								<td>Uraian</td>
								<td width="300px"><?php echo $thn-1; ?></td>
								<td width="300px"><?php echo $thn; ?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text_center">1</td>
								<td>HARTA</td>
								<td class="text_center"></td>
								<td class="text_center"></td>
							</tr>
							<tr>
								<td class="text_center">2</td>
								<td class="tab_line">Harta Tidak Bergerak</td>
								<td class="text_center">Rp. <?php @$h1_1 = $hartirak_1[0]->sum_hartirak; echo number_format($h1_1,2,",","."); ?></td>
								<td class="text_center">Rp. <?php @$h1_2 = $hartirak_2[0]->sum_hartirak; echo number_format($h1_2,2,",","."); ?></td>
							</tr>
							<tr>
								<td class="text_center">3</td>
								<td class="tab_line">Harta Bergerak</td>
								<td class="text_center">Rp. <?php  @$h2_1 = @$harger_1[0]->sum_harger; @$h2b_1 = @$harger2_1[0]->sum_harger2; echo number_format($h2_1+$h2b_1,2,",","."); ?></td>
								<td class="text_center">Rp. <?php  @$h2_2 = @$harger_2[0]->sum_harger; @$h2b_2 = @$harger2_2[0]->sum_harger2; echo number_format($h2_2+$h2b_2,2,",","."); ?></td>
							</tr>
							<tr>
								<td class="text_center">4</td>
								<td class="tab_line">Surat Berharga</td>
								<td class="text_center">Rp. <?php @$h3_1 = @$suberga_1[0]->sum_suberga; echo number_format($h3_1,2,",","."); ?></td>
								<td class="text_center">Rp. <?php @$h3_2 = @$suberga_2[0]->sum_suberga; echo number_format($h3_2,2,",","."); ?></td>
							</tr>
							<tr>
								<td class="text_center">5</td>
								<td class="tab_line">Kas dan Setara Kas</td>
								<td class="text_center">Rp. <?php @$h4_1 = @$kaseka_1[0]->sum_kaseka; echo number_format($h4_1,2,",","."); ?></td>
								<td class="text_center">Rp. <?php @$h4_2 = @$kaseka_2[0]->sum_kaseka; echo number_format($h4_2,2,",","."); ?></td>
							</tr>
							<tr>
								<td class="text_center">6</td>
								<td class="tab_line">Harta Lainnya</td>
								<td class="text_center">Rp. <?php @$h5_1 = @$harlin_1[0]->sum_harlin; echo number_format($h5_1,2,",","."); ?></td>
								<td class="text_center">Rp. <?php @$h5_2 = @$harlin_2[0]->sum_harlin; echo number_format($h5_2,2,",","."); ?></td>
							</tr>
							<tr>
								<td class="text_center">7</td>
								<td class="tab_line2">Jumlah Harta</td>
								<td class="text_center"><strong>Rp. <?php $subTtlHarta_1 = $h1_1+$h2_1+$h2b_1+$h3_1+$h4_1+$h5_1; echo number_format($subTtlHarta_1,2,",","."); ?></strong></td>
								<td class="text_center"><strong>Rp. <?php $subTtlHarta_2 = $h1_2+$h2_2+$h2b_2+$h3_2+$h4_2+$h5_2; echo number_format($subTtlHarta_2,2,",","."); ?></strong></td>
							</tr>
							<tr>
								<td class="text_center">8</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="text_center">9</td>
								<td>HUTANG</td>
								<td class="text_center"></td>
								<td class="text_center"></td>
							</tr>
							<tr>
								<td class="text_center">10</td>
								<td class="tab_line">Jumlah Hutang</td>
								<td class="text_center"><strong>Rp. <?php @$h6_1 = @$_hutang_1[0]->sum_hutang; echo number_format($h6_1,2,",","."); ?></strong></td>
								<td class="text_center"><strong>Rp. <?php @$h6_2 = @$_hutang_2[0]->sum_hutang; echo number_format($h6_2,2,",","."); ?></strong></td>
							</tr>
							<tr>
								<td class="text_center">11</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="text_center">12</td>
								<td>Jumlah Harta Kekayaan</td>
								<td class="text_center"><strong>Rp. <?php echo number_format($subTtlHarta_1 - $h6_1,2,",","."); ?></strong></td>
								<td class="text_center"><strong>Rp. <?php echo number_format($subTtlHarta_2 - $h6_2,2,",","."); ?></strong></td>
							</tr>
						</tbody>
					</table>
                    <?php }else{ echo 'Data Tidak Ditemukan'; } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(function(){
		$('.year-picker').datepicker({
			orientation: "left",
			format: 'yyyy',
			viewMode: "years",
			minViewMode: "years",
			autoclose: true
		});
		$('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });
    	$("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;            
        });
		
	});
</script>