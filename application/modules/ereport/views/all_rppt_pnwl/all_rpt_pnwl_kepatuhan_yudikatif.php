<style>
	.tengah {text-align:center;}
</style>
<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			<i class="fa fa-list"></i> Kepatuhan Bidang Yudikatif
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
					<form method="post" class='form-horizontal' id="ajaxFormCarikepatuhan" action="index.php/ereport/all_rpt_pnwl/kepatuhan_yudikatif">
							<div class="col-md-8 col-md-offset-2">
								<div class="form-group">
									<label class="col-md-4 control-label">Tahun :</label>
									<div class="col-md-5">
										<?php
										$now=date('Y');
										$start = $now - 10;
										echo "<select name='tahun'>";
										echo "<option>--Pilih--</option>";	
										for ($a=$start;$a<=$now;$a++){
										     echo "<option value=".$a.">".$a."</option>";
										}
										echo "</select>";
										?>	
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
						<h4 class="text-center"><strong>RINGKASAN PELAPORAN KEKAYAAN PENYELENGGARA NEGARA <br> NASIONAL BIDANG YUDIKATIF</strong></h4>
						<h5 class="text-center"><strong>Tahun Pelaporan : <?php echo $tahun; ?></strong></h5>

							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
									<tr>
										<th rowspan="2">NO</th>
										<th rowspan="2">INSTANSI</th>
										<th rowspan="2">WAJIB LAPOR LHKPN</th>
										<th colspan="8">SUDAH LAPOR LHKPN</th>
										<th rowspan="2" colspan="2">BELUM LAPOR LHKPN</th>
									</tr>
									<tr>	
										<th colspan="2">LAPOR LENGKAP</th>
										<th colspan="2">LAPOR TIDAK LENGKAP</th>
										<th colspan="2">BELUM TERVERIF</th>
										<th colspan="2">TOTAL SUDAH LAPOR</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$total_wajib_lapor=0;
										$total_lapor_lengkap=0;
										$total_lapor_tidak_lengkap=0;
										$total_belum_terverif=0;
										$total_sudah_lapor=0;
										$total_belum_lapor=0;

										$pre_wajib_lapor=0;
										$pre_lapor_lengkap=0;
										$pre_lapor_tidak_lengkap=0;
										$pre_belum_terverif=0;
										$pre_sudah_lapor=0;
										$pre_belum_lapor=0;

										if($list_data != false) { 
										$i=0;
										foreach($list_data as $row) {
										$i++;
										$tot_sdh_lapor = ($row->lapor_lengkap + $row->lapor_tidak_lengkap + $row->belum_terverif_a + $row->belum_terverif_b);	

										$total_wajib_lapor += $row->wajib_lapor_lhkpn;
										$total_lapor_lengkap += $row->lapor_lengkap;
										$total_lapor_tidak_lengkap += $row->lapor_tidak_lengkap;
										$total_belum_terverif += $row->belum_terverif_a + $row->belum_terverif_b;
										$total_sudah_lapor += $tot_sdh_lapor;
										$total_belum_lapor += ($row->wajib_lapor_lhkpn - $tot_sdh_lapor);


										$pre_lapor_lengkap = round(($total_lapor_lengkap/$total_wajib_lapor)*100,2);
										$pre_lapor_tidak_lengkap = round(($total_lapor_tidak_lengkap/$total_wajib_lapor)*100, 2);
										$pre_belum_terverif = round((($total_belum_terverif)/$total_wajib_lapor)*100, 2);
										$pre_sudah_lapor = round(($total_sudah_lapor/$total_wajib_lapor)*100, 2);
										$pre_belum_lapor = round((($total_belum_lapor)/$total_wajib_lapor)*100, 2);
									

									echo '<tr>
										<td class="tengah">'.$i.'</td>
										<td>'.$row->INST_NAMA.'</td>
										<td class="tengah">'.$row->wajib_lapor_lhkpn.'</td>
										<td class="tengah">'.$row->lapor_lengkap.'</td>
										<td class="tengah">'.round(($row->lapor_lengkap/$row->wajib_lapor_lhkpn * 100),2).' %</td>
										<td class="tengah">'.$row->lapor_tidak_lengkap.'</td>
										<td class="tengah">'.round(($row->lapor_tidak_lengkap/$row->wajib_lapor_lhkpn * 100), 2).' %</td>
										<td class="tengah">'.($row->belum_terverif_a + $row->belum_terverif_b).'</td>
										<td class="tengah">'.round(($row->belum_terverif_a + $row->belum_terverif_b)/$row->wajib_lapor_lhkpn, 2).' %</td>
										<td class="tengah">'.$tot_sdh_lapor.'</td>
										<td class="tengah">'.round(($tot_sdh_lapor/$row->wajib_lapor_lhkpn *100), 2).' %</td>
										<td class="tengah">'.($row->wajib_lapor_lhkpn - $tot_sdh_lapor).'</td>
										<td class="tengah">'.round((($row->wajib_lapor_lhkpn - $tot_sdh_lapor)/$row->wajib_lapor_lhkpn * 100), 2).' %</td>
									</tr>';
									}
									
									}else{
										echo '<tr>
										<td colspan="13"><center><b>Data tidak ditemukan!</b></center></td>
										</tr>';
									} ?>
								</tbody>
								<tfoot style="background: #ABD8BD;">
									<?php
									echo '<tr>
									<td class="tengah" colspan="2">Total</td>
									<td class="tengah">'.$total_wajib_lapor.'</td>
									<td class="tengah">'.$total_lapor_lengkap.'</td>
									<td class="tengah">'.$pre_lapor_lengkap.' %</td>
									<td class="tengah">'.$total_lapor_tidak_lengkap.'</td>
									<td class="tengah">'.$pre_lapor_tidak_lengkap.' %</td>
									<td class="tengah">'.$total_belum_terverif.'</td>
									<td class="tengah">'.$pre_belum_terverif.' %</td>
									<td class="tengah">'.$total_sudah_lapor.'</td>
									<td class="tengah">'.$pre_sudah_lapor.' %</td>
									<td class="tengah">'.$total_belum_lapor.'</td>
									<td class="tengah">'.$pre_belum_lapor.' %</td>';
									?>
									</tr>
								</tfoot>
							</table>
						<!-- </div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#BIDANG').select2();
		if ($('#BIDANG').val() !== '') {
    		$('#INSTANSI').prop('readonly', false);
    	} else {
    		$('#INSTANSI').prop('readonly', true);
    	}

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

        $('#BIDANG').on('change', function(){
        	var id = $(this).val();
        	$('#INSTANSI').select2('val', '');
        	if (id !== '') {
        		$('#INSTANSI').prop('readonly', false);
        	} else {
        		$('#INSTANSI').prop('readonly', true);
        	}
        });

        $('#INSTANSI').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getInstansi')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                	var bidang_id = $('#BIDANG').val();
                    return {
                        q: term,
                        qq: bidang_id
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getInstansi')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                prov = state.id;
                return state.name;
            }
        });
	});
</script>