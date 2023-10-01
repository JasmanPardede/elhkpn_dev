<style>
	.tengah {text-align:center;}
</style>
<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			<i class="fa fa-list"></i> History Laporan
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<form method="post" class='form-horizontal' id="ajaxFormCarikepatuhan" action="index.php/ereport/all_rpt_pnwl/history_laporan">
						<!-- <form method="post" class='form-horizontal' id="ajaxFormCarikepatuhan2" action="index.php/ereport/all_rpt_pnwl/history"> -->
							<div class="col-md-8 col-md-offset-2">
								<div class="form-group">
						            <label class="col-sm-3 control-label">NIK :</label>
						            <div class="col-sm-5">
						                <input required="" class="form-control" type="text" maxlength="16" required size="40" name="NIK" id="NIK" placeholder="Masukkan NIK..." value="<?php echo $nik; ?>" onblur="cek_user(this.value)">
						                <span class="help-block"><font id="username_ada" style="display:none;" color="red">User PN dengan NIK <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
						            </div>
						        </div>
								<div class="form-group">
									<div class="col-md-5 col-md-offset-4">
										<button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
										<button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix" style="margin-bottom: 20px;"></div>
						</form>

						<hr>

						<!-- <div class="table-responsive"> -->
							<table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
									<tr>	
										<th class="tengah">NO</th>
										<th class="tengah">NIK</th>
										<th class="tengah">NAMA</th>
										<th class="tengah">LEMBAGA</th>
										<th class="tengah">UNIT KERJA I</th>
										<th class="tengah">UNIT KERJA II</th>
										<th class="tengah">JABATAN</th>
										<th class="tengah">NHK</th>
										<th class="tengah">TANGGAL TERIMA</th>
										<th class="tengah">STATUS PELAPORAN</th>
									</tr>
								</thead>
								<tbody>
								<?php if($list_data != FALSE){
										$i=0;
										foreach($list_data as $row){
										$i++;

										switch ($row->STATUS) {
											case '0':
												$status = "Draft"; 
												break;
											case '1':
												$status = "Masuk"; 
												break;

											case '2':
												$status = "Perlu Perbaikan"; 
												break;

											case '3':
												$status = "Terverifikasi Lengkap"; 
												break;
											
											case '4':
												$status = "Diumumkan"; 
												break;

											case '5':
												$status = "Terverifikasi tidak lengkap"; 
												break;

											case '6':
												$status = "Diumumkan tidak lengkap"; 
												break;

											default:
												$status = "";
												break;
										}
										
										echo '<tr>
										<td>'.$i.'</td>
										<td>'.$row->NIK.'</td>
										<td>'.$row->NAMA.'</td>
										<td>'.$row->INST_NAMA.'</td>
										<td>'.$row->UK_NAMA.'</td>
										<td>'.$row->SUK_NAMA.'</td>
										<td>'.$row->DESKRIPSI_JABATAN.'</td>
										<td class="tengah">'.$row->NHK.'</td>
										<td>'.$row->TGL_LAPOR.'</td>
										<td>'.$status.'</td>
										</tr>';

											}
										}
								?>
								</tbody>
							</table>


							<!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
									<th>No</th>
									<th>Tanggal Lapor</th>
									<th>Tanggal Kirim</th>
									<th>Status</th>
								</thead>
								<tbody>
									<?php if($list_data != FALSE){
										$i=0;
										foreach($list_data as $row){
										$i++;

										switch ($row->STATUS) {
											case '0':
												$status = "Draft"; 
												break;
											case '1':
												$status = "Masuk"; 
												break;

											case '2':
												$status = "Perlu Perbaikan"; 
												break;

											case '3':
												$status = "Terverifikasi Lengkap"; 
												break;
											
											case '4':
												$status = "Diumumkan"; 
												break;

											case '5':
												$status = "Terverifikasi tidak lengkap"; 
												break;

											case '6':
												$status = "Diumumkan tidak lengkap"; 
												break;

											default:
												$status = "";
												break;
										}
											echo '<tr>
											<td class="tengah">'.$i.'</td>
											<td class="tengah">'.$row->TGL_LAPOR.'</td>
											<td class="tengah">'.$row->TGL_KIRIM.'</td>
											<td class="tengah">'.$status.'</td>
											</tr>';
										}
									} ?>
								</tbody>
							</table> -->
						<!-- </div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	
		$('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });

        $("#ajaxFormCarikepatuhan").submit(function(e) {
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
            $('#ajaxFormCarikepatuhan').find('input:text').val('');
            $('#ajaxFormCarikepatuhan').find('select').val('');
            $('#ajaxFormCarikepatuhan').find('select').select2('val', '');
            $('#ajaxFormCarikepatuhan').trigger('submit');
        });

	});
</script>