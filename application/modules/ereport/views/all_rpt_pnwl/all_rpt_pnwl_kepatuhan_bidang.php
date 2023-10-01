<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			<i class="fa fa-list"></i> Kepatuhan Per Bidang
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<form method="post" class='form-horizontal' id="ajaxFormCarikepatuhan" action="index.php/ereport/all_rpt_pnwl/kepatuhan_bidang">
							<div class="col-md-8 col-md-offset-2">
								<div class="form-group">
									<label class="col-md-4 control-label">Bidang :</label>
									<div class="col-md-5">
										<select required id="BIDANG" style="border:none; padding: 6px 0px;" class="form-control input-sm" name="CARI[BIDANG]">
											<option value="">-Pilih Bidang-</option>
											<option <?php echo (@$cari['BIDANG'] == '1') ? 'selected' : '' ; ?> value="1">Eksekutif</option>
											<option <?php echo (@$cari['BIDANG'] == '2') ? 'selected' : '' ; ?> value="2">Legislatif</option>
											<option <?php echo (@$cari['BIDANG'] == '3') ? 'selected' : '' ; ?> value="3">Yudikatif</option>
											<option <?php echo (@$cari['BIDANG'] == '4') ? 'selected' : '' ; ?> value="4">BUMN/BUMD</option>
										</select>
									</div>
								</div>
								<!-- sementara di hidden -->
							    <div class="row" style="display:none;">
									<label class="col-sm-4 control-label">Tahun :</label>
									<div class="col-sm-5">
										<input type="text" class="year-picker form-control input-sm" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$cari['TAHUN'] ?>" id="CARI_TAHUN">
									</div>
								</div>
								<!-- end -->
								<div class="form-group">
									<div class="col-md-5 col-md-offset-4">
										<button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
										<button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix" style="margin-bottom: 20px;"></div>
						</form>
						<?php
							// display($query);
							// display($items);
						?>
						<hr>
						<h4 class="text-center"><strong>KEPATUHAN PER BIDANG</strong></h4>
						<table class="table no-border">
							<tbody>
								<tr>
									<td width="300px;">Bidang</td>
									<td width="10px;">:</td>
                                    <td>
                                        <?php echo (!empty($items)) ? $items[0]->BDG_NAMA : '-' ; ?>
                                    </td>
                                </tr>
								<tr>
									<td>Instansi</td>
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
						<!-- <div class="table-responsive"> -->
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
									<tr>
										<th>Instansi</th>
										<th>WAJIB LAPOR LHKPN</th>
										<th>SUDAH LAPOR</th>
										<th>% SUDAH LAPOR</th>
										<th>BELUM PERNAH LAPOR (A)</th>
										<th>% BELUM PERNAH LAPOR</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$TOTAL_WAJIBLAPORT = 0;
										$TOTAL_SUDAHLAPORT = 0;
										$TOTAL_PROSEN_SUDAH_LAPORT = 0;
										$TOTAL_BLM_LAPORT = 0;
										$TOTAL_PROSEN_BLM_LAPORT = 0;
										if (!empty($items)) :
											foreach ($items as $key) :

											$TOTAL_WAJIBLAPORT += $key->WAJIB_LAPOR;
											$TOTAL_SUDAHLAPORT += $key->SUDAH_LAPOR;
									?>
									<tr>
										<td><?php echo $key->INST_NAMA ?></td>
										<td class="text-right"><?php echo $key->WAJIB_LAPOR; ?></td>
										<td class="text-right"><?php echo $key->SUDAH_LAPOR; ?></td>
										<td class="text-right">
                                        <?php
										if($key->SUDAH_LAPOR==0){
											echo '0%';
										}else{
											$PROSEN_SDH_LAPOR = ($key->SUDAH_LAPOR/$key->WAJIB_LAPOR)*100;
											echo round($PROSEN_SDH_LAPOR, 2).'%';
											$TOTAL_PROSEN_SUDAH_LAPORT += round($PROSEN_SDH_LAPOR, 2);
										}
										?>
                                        </td>
										<td class="text-right">
                                        <?php
										if($key->WAJIB_LAPOR==0){
											echo '0';
											$PROSEN_BLM_LAPOR = '0';
										}else{
											$BLM_LAPOR = $key->WAJIB_LAPOR - $key->SUDAH_LAPOR;
											$PROSEN_BLM_LAPOR = ($BLM_LAPOR/$key->WAJIB_LAPOR)*100;
											echo $BLM_LAPOR;
											$TOTAL_BLM_LAPORT += $BLM_LAPOR;
											$TOTAL_PROSEN_BLM_LAPORT += round($PROSEN_BLM_LAPOR, 2);
										}
										?>
                                        </td>
										<td class="text-right">
                                        <?php echo round($PROSEN_BLM_LAPOR, 2).'%'; ?>
                                        </td>
									</tr>
									<?php
											endforeach;
										else :
									?>
									<tr>
										<td colspan="10"><center><b>Data tidak ditemukan!</b></center></td>
									</tr>
									<?php endif; ?>
								</tbody>
								<tfoot style="background: #ABD8BD;">
									<tr>
										<td>TOTAL</td>
										<td class="text-right"><?php echo $TOTAL_WAJIBLAPORT; ?></td>
										<td class="text-right"><?php echo $TOTAL_SUDAHLAPORT; ?></td>
										<td class="text-right">
										<?php
										if($TOTAL_WAJIBLAPORT==0){
											echo '0%';
										}else{
											$TOTAL_PROSEN_SUDAH_LAPORT = ($TOTAL_SUDAHLAPORT/$TOTAL_WAJIBLAPORT)*100;
											echo round($TOTAL_PROSEN_SUDAH_LAPORT, 2).'%';
										}
										?>
                                        </td>
										<td class="text-right"><?php echo $TOTAL_BLM_LAPORT; ?></td>
										<td class="text-right">
                                        <?php
										if($TOTAL_WAJIBLAPORT==0){
											echo '0%';
										}else{
											$TOTAL_PROSEN_BLM_LAPORT = ($TOTAL_BLM_LAPORT/$TOTAL_WAJIBLAPORT)*100;
											echo round($TOTAL_PROSEN_BLM_LAPORT, 2).'%';
										}
										?>
										</td>
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