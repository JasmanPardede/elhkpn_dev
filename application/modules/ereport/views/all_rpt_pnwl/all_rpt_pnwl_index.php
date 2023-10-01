<!--<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			<i class="fa fa-list"></i> E - Report PN/WL
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
            
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Report PN/WL</th>
								</tr>
							</thead>
							<tbody>
							 <tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_bidang">Kepatuhan Per Bidang</a></td></tr> 
								 <tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_bidang">Kepatuhan Per Bidang</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_unit_kerja">Kepatuhan Per Unit Kerja</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_jabatan">Kepatuhan Per Jabatan</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_provinsi">Kepatuhan Per Provinsi</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_lembaga">Kepatuhan Per Lembaga</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_detail">Kepatuhan Detail</a></td></tr> 
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_per_bidang">Kepatuhan Per Bidang</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_eksekutif_pusat">Kepatuhan Eksekutif Pusat</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_eksekutif_daerah">Kepatuhan Eksekutif Daerah</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_legislatif">Kepatuhan Legislatif</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_yudikatif">Kepatuhan Yudikatif</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_bumn">Kepatuhan BUMN</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_bumd">Kepatuhan BUMD</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_unit_kerja_1">Kepatuhan Unit Kerja I</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_unit_kerja_2">Kepatuhan Unit Kerja II</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_per_eselon">Kepatuhan Per Eselon</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_belum_lapor">Kepatuhan Belum Lapor</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_sudah_lapor">Kepatuhan Sudah Lapor</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/kepatuhan_seluruhnya">Kepatuhan Seluruhnya</a></td></tr>
								<tr><td><a href="index.php/ereport/all_rpt_pnwl/history_laporan">History Laporan</a></td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>-->
<input type="hidden" id="base_url_tableau" name="base_url_tableau" value="<?php echo $base_url_tableau;?>"/>
<script type="text/javascript">
	$(document).ready(function() {   
//         var src = window.parent.document.getElementById('idx_frame').src;
         var src = $('iframe').attr('src');
         var base_url_tableau = $('#base_url_tableau').val();
         console.log(src+' --- '+base_url_tableau);
         if (src == '0?'+base_url_tableau){
             location.reload();  
         }
//        document.getElementById('some_frame_id').contentWindow.location.reload();  
//        $("#e-report-lhkpn").find("a").click(function () {
//            var url = $(this).attr('href');
//            window.location.hash = url;
//            ng.LoadAjaxContent(url);
//            return false;
//        });  		
	});
</script>

<style type="text/css">
	#e-report-lhkpn a{
		display: block;
	}
</style>