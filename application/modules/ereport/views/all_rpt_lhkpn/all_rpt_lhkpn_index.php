<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			<i class="fa fa-list"></i> E - Report LHKPN
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
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Report LHKPN</th>
								</tr>
							</thead>
							<tbody>
								<tr><td><a href="index.php/ereport/all_rpt_lhkpn/harta_kekayaan">Laporan Posisi/Neraca Harta Kekayaan</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_lhkpn/perubahan_harta">Laporan Perubahan Harta</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_lhkpn/penerimaan_pengeluaran">Laporan Penerimaan dan Pengeluaran</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_lhkpn/tracking_lhkpn">Tracking Status Pelaporan/Notifikasi</a></td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function() {
        $("#e-report-lhkpn").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });  		
	});
</script>

<style type="text/css">
	#e-report-lhkpn a {
		display: block;
	}
</style>