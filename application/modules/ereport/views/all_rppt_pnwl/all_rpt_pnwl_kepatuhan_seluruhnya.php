<style>
	.tengah {text-align:center;}
</style>
<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			<i class="fa fa-list"></i> Kepatuhan Seluruhnya
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
					<form method="post" class='form-horizontal' id="ajaxFormCarikepatuhan" action="index.php/ereport/all_rpt_pnwl/kepatuhan_seluruhnya">
							<div class="col-md-8 col-md-offset-2">
								<div class="form-group">
									<label class="col-md-4 control-label">Lembaga :</label>
									<div class="col-md-5">
						                <input type="text"  style="border:none;width:300px;" name="lembaga" placeholder="Lembaga" value="" id="lembaga">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Unit Kerja :</label>
									<div class="col-md-5">
						                <input type="text" style="border:none;width:300px;" name="unitkerja" placeholder="Unit Kerja" value="" id="unitkerja">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Tahun :</label>
									<div class="col-md-5">
										<?php
										$now=date('Y');
										$start = $now - 10;
										echo "<select name='tahun'>";
										echo "<option value=''>--Pilih--</option>";	
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
						<h4 class="text-center"><strong>REKAPITULASI PENYAMPAIAN LHKPN <br> KOMISI PEMBERANTASAN KORUPSI (KPK)</strong></h4>
						<h5 class="text-center"><strong>Tahun Pelaporan : <?php echo $tahun; ?></strong></h5>

							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
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
									<?php 
									$i=0;
									if($list_data != false){
										$i=0;
										foreach($list_data as $row){
										$i++;
											echo '<tr>
												<td class="tengah">'.$i.'</td>
												<td>'.$row->NIK.'</td>
												<td>'.$row->NAMA.'</td>
												<td>'.$row->INST_NAMA.'</td>
												<td>'.$row->UK_NAMA.'</td>
												<td>'.$row->SUK_NAMA.'</td>
												<td>'.$row->DESKRIPSI_JABATAN.'</td>
												<td class="tengah">'.$row->NHK.'</td>
												<td>'.$row->TGL_LAPOR.'</td>
												<td>'.status($row->STATUS).'</td>
											</tr>';
										}
									}
									$j = $i;
									if($list_data2 != null){
										foreach($list_data2 as $val){
											$j++;
											echo '<tr>
												<td class="tengah">'.$j.'</td>
												<td>'.$val->NIK.'</td>
												<td>'.$val->NAMA.'</td>
												<td>'.$val->INST_NAMA.'</td>
												<td>'.$val->UK_NAMA.'</td>
												<td>'.$val->SUK_NAMA.'</td>
												<td>'.$val->DESKRIPSI_JABATAN.'</td>
												<td class="tengah">'.$val->NHK.'</td>
												<td>'.$val->TGL_LAPOR.'</td>
												<td>'.status($val->STATUS).'</td>
											</tr>';
										}
									}

									$k = $j;
									if($list_data3 != null){
										foreach($list_data3 as $value){
											$k++;
											echo '<tr>
												<td class="tengah">'.$k.'</td>
												<td>'.$value->NIK.'</td>
												<td>'.$value->NAMA.'</td>
												<td>'.$value->INST_NAMA.'</td>
												<td>'.$value->UK_NAMA.'</td>
												<td>'.$value->SUK_NAMA.'</td>
												<td>'.$value->DESKRIPSI_JABATAN.'</td>
												<td class="tengah">'.$value->NHK.'</td>
												<td>'.$value->TGL_LAPOR.'</td>
												<td>'.status($value->STATUS).'</td>
											</tr>';
										}
									}

									$l = $k;
									if($list_data4 != null){
										foreach($list_data4 as $rows){
											$l++;
											echo '<tr>
												<td class="tengah">'.$l.'</td>
												<td>'.$rows->NIK.'</td>
												<td>'.$rows->NAMA.'</td>
												<td>'.$rows->LEMBAGA.'</td>
												<td>'.$rows->UNIT_KERJA.'</td>
												<td>'.$rows->SUB_UNIT_KERJA.'</td>
												<td>'.$rows->DESKRIPSI_JABATAN.'</td>
												<td class="tengah">'.$rows->NHK.'</td>
												<td class="tengah">-</td>
												<td class="tengah">-</td>
											</tr>';
										}
									}
									?>
									

								</tbody>
							</table>
						<!-- </div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php 
function status($stat_id){
	switch($stat_id) {
	case '0':
	$status = "Draft";
	break;

	case 1:
	$status = "Masuk";
	break;

	case 2:
	$status = "Perlu Perbaikan";
	break;

							            case 3:
							                $status = "Terverifikasi Lengkap";
							                break;

							            case 4:
							                $status = "Diumumkan";
							                break;

							            case 5:
							                $status = "Terverifikasi tidak lengkap";
							                break;

							            case 6:
							                $status = "Diumumkan tidak lengkap";
							                break;

							            default:
								            $status = "";
								            break;
								        }
}
?>
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

       $('#lembaga').select2({
			            minimumInputLength: 0,
			            ajax: {
			                url: "<?= base_url('index.php/share/reff/getLembaga') ?>",
			                dataType: 'json',
			                quietMillis: 250,
			                data: function(term, page) {
			                    return {
			                        q: term
			                    };
			                },
			                results: function(data, page) {
			                    return {results: data.item};
			                },
			                cache: true
			            },
			            initSelection: function(element, callback) {
			                var id = $(element).val();
			                if (id !== "") {
			                    $.ajax("<?= base_url('index.php/share/reff/getLembaga') ?>/" + id, {
			                        dataType: "json"
			                    }).done(function(data) {
			                        callback(data[0]);
			                    });
			                }
			            },
			            formatResult: function(state) {
			                return state.name;
			            },
			            formatSelection: function(state) {
			                return state.name;
			            }
			        }).on("change", function(e) {
			            var lembid = $('#lembaga').val();


			            $('input[name="unitkerja"]').select2({
			                minimumInputLength: 0,
			                ajax: {
			                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + lembid,
			                    dataType: 'json',
			                    quietMillis: 250,
			                    data: function(term, page) {
			                        return {
			                            q: term
			                        };
			                    },
			                    results: function(data, page) {
			                        return {results: data.item};
			                    },
			                    cache: true
			                },
			                initSelection: function(element, callback) {
			                    var id = $(element).val();
			                    if (id !== "") {
			                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + lembid, {
			                            // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/"+lembid+'/'+id, {
			                            dataType: "json"
			                        }).done(function(data) {
			                            callback(data[0]);
			                        });
			                    }
			                },
			                formatResult: function(state) {
			                    return state.name;
			                },
			                formatSelection: function(state) {
			                    return state.name;
			                }
			            });
			        });
	});
</script>