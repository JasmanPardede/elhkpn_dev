<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<!-- <div class="box-header with-border">
                    <div class="box-tools">

                    </div>
                </div> -->
                <!-- /.box-header -->
                <div class="box-body">
                	<?php if ($check[0]->IS_KPK == '1'): ?>
	                	<h4>10 User Terbanyak</h4>
	                	<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
	                		<thead>
	                			<tr>
	                				<th>Instansi</th>
	                				<th>Admin Instansi</th>
	                				<th>User Instansi</th>
	                				<th>Jumlah</th>
	                			</tr>
	                		</thead>
	                		<tbody>
	                			<?php foreach ($data_iskpk as $key): ?>
	                			<tr>
	                				<td><?php echo $key->INST_NAMA?></td>
	                				<td align="right"><?php echo $key->admin?></td>
	                				<td align="right"><?php echo $key->user?></td>
	                				<td align="right"><?php echo $key->jumlah?></td>
	                			</tr>
	                			<?php endforeach ?>
	                		</tbody>
	                	</table>
                	<?php endif ?>
                	<?php if ($check[0]->IS_INSTANSI == '1' || $check[0]->IS_USER_INSTANSI == '1'): ?>
	                	<table class="table no-border">
	                		<tbody>
	                			<tr>
	                				<th width="200px">Instansi</th>
	                				<td width="10px">:</td>
	                				<td><?php echo ($this->session->USERdata('INST_NAMA')) ?></td>
	                			</tr>
	                			<tr>
	                				<th>Admin</th>
	                				<td>:</td>
	                				<td><?php echo $data_instansi[0]->admin ?> Orang</td>
	                			</tr>
	                			<tr>
	                				<th>User</th>
	                				<td>:</td>
	                				<td><?php echo $data_instansi[0]->user ?> Orang</td>
	                			</tr>
	                		</tbody>
	                	</table>
	                	<hr>
	                	<h4>Keaktifan</h4>
	                	<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
	                		<thead>
	                			<tr>
	                				<th width="10px">No.</th>
	                				<th width="50%">Admin</th>
	                				<th>User</th>
	                			</tr>
	                		</thead>
	                		<tbody>
	                			<?php if ($data_instansi[0]->jumlah == 0) { ?>
	                				<tr>
		                				<td colspan="3"><center>Data not found !</center></td>
		                			</tr>
		                		<?php	} else {
		                			$row = 0;
		                			if ($data_instansi[0]->admin > $data_instansi[0]->user) {
		                				$row = $data_instansi[0]->admin;
		                			} elseif ($data_instansi[0]->admin < $data_instansi[0]->user) {
		                				$row = $data_instansi[0]->user;
		                			} else {
		                				$row = $data_instansi[0]->admin;
		                			}
		                			for ($i=1; $i <= $row; $i++) :
	                			?>
	                			<tr>
	                				<td><center><?php echo $i ?></center></td>
	                				<td><?php echo (!empty($admin_inst[($i-1)]->USERNAME)) ? $admin_inst[($i-1)]->USERNAME.' ('.$admin_inst[($i-1)]->jumlah.' x login)' : '-' ?></td>
	                				<td><?php echo (!empty($user_inst[($i-1)]->USERNAME)) ? $user_inst[($i-1)]->USERNAME.' ('.$user_inst[($i-1)]->jumlah.' x login)' : '-' ?></td>
	                			</tr>
	                			<?php endfor; } ?>
	                		</tbody>
	                	</table>
                	<?php endif ?>
                </div>
			</div>
		</div>
	</div>
</section>
<?php exit(); ?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="box-tools">

                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <?php
                    
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_ADMAPP){
                        ?>
                        <h4>AS Administrator</h4><br>
                        Jml PN Yang Sudah mengsubmit LHKPN, sedang memproses<br>
                    <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_AK){
                        ?>
                        <div class="box-body">
					<div class="row">
												<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="info-box">
								<span class="info-box-icon bg-aqua">
									<i class="fa fa-user"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">Admin Instansi Seluruh Indonesia</span>
									<span class="info-box-number">Total 14</span>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="info-box">
								<span class="info-box-icon bg-aqua">
									<i class="fa fa-user"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">User Instansi di KPK</span>
									<span class="info-box-number">Total 4</span>
								</div>
							</div>
						</div>
												<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="info-box">
								<span class="info-box-icon bg-aqua">
									<i class="fa fa-user"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">PN/WL KPK</span>
									<span class="info-box-number">Total 3</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<b>Admin Instansi</b>
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
								<tr>
									<th>Instansi</th>
									<th>Jumlah</th>
								</tr>
								</thead>
										
																<tbody>
								<tr>
									<td>Komisi Pemberantasan Korupsi</td>
									<td>2</td>
								</tr>
								</tbody>
																<tbody>
								<tr>
									<td>Badan Pemeriksa Keuangan</td>
									<td>4</td>
								</tr>
								</tbody>
																<tbody>
								<tr>
									<td>Kementrian Pekerjaan</td>
									<td>1</td>
								</tr>
								</tbody>
																<tbody>
								<tr>
									<td>DIRJEN PAJAK</td>
									<td>1</td>
								</tr>
								</tbody>
																<tbody>
								<tr>
									<td>Kementrian Kelautan</td>
									<td>1</td>
								</tr>
								</tbody>
																<tbody>
								<tr>
									<td>Kementrian Perhubungan</td>
									<td>1</td>
								</tr>
								</tbody>
															</table>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<b>User Instansi</b>
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
								<tr>
									<th>Instansi</th>
									<th>Jumlah</th>
								</tr>
								</thead>
																<tbody>
								<tr>
									<td>Komisi Pemberantasan Korupsi</td>
									<td>1</td>
								</tr>
								</tbody>
																<tbody>
								<tr>
									<td>Kementrian Kelautan</td>
									<td>2</td>
								</tr>
								</tbody>
															</table>
						</div>
					</div>
                </div>
                        
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_AI){
                        ?>
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_UI){
                        ?>
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_PN){
                        ?>
                        <h4>AS PN</h4><br>
                        LHKPN, sedang diproses<br>
                        <?php
                    }
                    if($this->session->userdata('ID_ROLE')==ID_ROLE_VER){
                        ?>
                        <h4>AS Verifikator</h4><br>
                        <?php
                    }
                    ?>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->