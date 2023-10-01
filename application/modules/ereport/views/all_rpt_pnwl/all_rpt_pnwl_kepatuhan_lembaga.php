<style type="text/css">
	tabs{
		margin-left:1em;
	}
</style>
<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			Laporan Kepatuhan Per Lembaga
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<form method="post" class='form-horizontal' id="ajaxFormCarireportjabatan" action="index.php/ereport/all_rpt_pnwl/kepatuhan_lembaga">
						    <div class="box-body">
						  		<div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Jenis Bidang :</label>
						                <div class="col-sm-4">
						                    <select class="form-control select2" name="CARI[JENIS]">
						                        <option value="">-- Jenis Bidang --</option>
						                        <option value="1" <?php if(@$CARI['JENIS'] == 1){ echo 'selected';};?>>Eksekutif</option>
						                        <option value="2" <?php if(@$CARI['JENIS'] == 2){ echo 'selected';};?>>Legislatif</option>
						                        <option value="3" <?php if(@$CARI['JENIS'] == 3){ echo 'selected';};?>>Yudikatif</option>
						                        <option value="4" <?php if(@$CARI['JENIS'] == 4){ echo 'selected';};?>>BUMN / BUMD</option>
						                    </select>
						                </div>
						                <div class="col-sm-3">
						                    
						                </div>
						            </div>
						        </div>
					        	<!-- sementara di hidden -->
					            <div class="row" style="display:none;">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Tahun :</label>
						                <div class="col-sm-4">
						                    <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN'];?>" id="CARI_TAHUN">
						                </div>
						                <div class="col-sm-3">
						                </div>
						            </div>
						        </div>
						       	<!-- end -->
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Cari :</label>
						                <div class="col-sm-4">
						                    <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA'];?>" id="CARI_NAMA">
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
								<b>Kepatuhan Per Lembaga</b>
							</div>
							<br />
							<br />
							<div class="col-md-12">
								<table class="table no-border">
									<tbody>
										<tr>
											<td width="300px;">Provinsi</td>
											<td width="10px;">:</td>
											<td>ALL</td>
										</tr>
										<tr>
											<td>Instansi</td>
											<td>:</td>
											<td>-</td>
										</tr>
										<tr>
											<td>Unit Kerja</td>
											<td>:</td>
											<td>ALL</td>
										</tr>
										<tr>
											<td>Status PN</td>
											<td>:</td>
											<td>Aktif</td>
										</tr>
										<tr>
											<td>Status UU</td>
											<td>:</td>
											<td>NonUU dan UU</td>
										</tr>
									</tbody>
								</table>
								<div class="table-responsive">
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>No.</th>
												<th>Lembaga</th>
												<th>WAJIB LAPOR LHKPN</th>
												<th>Sudah Update pada Jabatan Saat Ini(-)</th>
												<th>% Sudah Update</th>
												<th>Belum Update pada Jabatan Saat Ini(B)</th>
												<th>% Belum Update</th>
												<th>SUDAH LAPOR</th>
												<th>% SUDAH LAPOR</th>
												<th>BELUM PERNAH LAPOR (A)</th>
												<th>% BELUM PERNAH LAPOR</th>
											</tr>
										</thead>
										<tbody>
											<?php if($total_rows > 0) { ?>
												<?php
													$i = 0 + $offset;
													$start = count($items) > 0 ? $i + 1 : 0;
		                            				$end   = 0;
													foreach ($items as $key) {
												?>
													<tr>
														<td><?php echo ++$i; ?></td>
														<td><?=$key->INST_NAMA?></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												<?php
													$end++;
													}
												?>
											<?php } else { ?>
												<tr>
													<td colspan="11"><center><b>Data Tidak Ditemukan!</b></center></td>
												</tr>
											<?php } ?>
										</tbody>
										<tfoot style="background: #ABD8BD;">
											<tr>
												<td>TOTAL</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
												<td>-</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</center>
					</div>

					<?php if($total_rows > 0) { ?>
						<div class="box-footer clearfix">
							<div class="col-sm-6">
								<div class="dataTables_info" id="datatable-1_info">
									Showing
									<?php echo  $start; ?>
									to
									<?php echo  $end; ?>
									of
									<?php echo  $total_rows; ?>
									entries
								</div>
							</div>
							<div class="col-sm-6 text-right">
								<div class="dataTables_paginate paging_bootstrap">
									<?php echo $pagination; ?>
								</div>
							</div>
						<div class="clearfix"></div>
						</div>
					<?php } ?>

				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".pagination").find("a").click(function () {
	        var url = $(this).attr('href');
	        window.location.hash = url;
	        ng.LoadAjaxContentPost(url, $('#ajaxFormCarireportjabatan'));
	        return false;
	    });

	   	$("#ajaxFormCarireportjabatan").submit(function(e) {
		    var url = $(this).attr('action');
		    ng.LoadAjaxContentPost(url, $(this));
		    return false;
		});

		$('.year-picker').datepicker({
		    orientation: "left",
		    format: 'yyyy',
		    viewMode: "years",
		    minViewMode: "years",
		    autoclose: true
		});
		$("#ajaxFormCarireportjabatan").submit(function(e) {
		    var url = $(this).attr('action');
		    ng.LoadAjaxContentPost(url, $(this));
		    return false;
		});
        // $("#e-report-lhkpn").find("a").click(function () {
        //     var url = $(this).attr('href');
        //     window.location.hash = url;
        //     ng.LoadAjaxContent(url);
        //     return false;
        // });  	
        $('#btn-clear').click(function(event) {
            $('#ajaxFormCarireportjabatan').find('input:text').val('');
            $('#ajaxFormCarireportjabatan').find('select').val('');
            $('#ajaxFormCarireportjabatan').trigger('submit');
        });	
	});
</script>

<style type="text/css">
/*	#e-report-lhkpn a{
		display: block;
	}*/
</style>