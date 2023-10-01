<style type="text/css">
	.form-select2{
		border: none;
		background-color: rgba(255, 255, 255, 0);
		margin: 0 !important;
		padding: 6px 0 !important;
	}
</style>
<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			E - Report LHKPN
		</h1>
		<?=$breadcrumb?>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<form method="post" class='form-horizontal' id="ajaxFormCariformdetail" action="index.php/ereport/all_rpt_pnwl/kepatuhan_detail">
						    <div class="box-body">
						    	<!-- sementara di hidden -->
						        <div class="row" style="display:none;">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Tahun Lapor :</label>
						                <div class="col-sm-4">
						                    <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN'];?>" id="CARI_TAHUN">
						                </div>
						                <div class="col-sm-3">
						                    
						                </div>
						            </div>
						        </div>
						        <!-- end -->
						        <div class="row">
						            <label class="col-sm-4 control-label">Instansi :</label>
						            <div class="col-sm-4">
						                <input type="text" class="form-control form-select2" id='lembaga' name="CARI[INST]" placeholder="Nama" value="<?php echo @$CARI['INST'];?>" id="CARI_NAMA">
						                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
						            </div>
						            <div class="col-sm-2">
						            </div>      
						        </div>
						        <div class="row">
						            <label class="col-sm-4 control-label">Unit Kerja :</label>
						            <div class="col-sm-4">
						                <input type="text" class="form-control form-select2" id='uker' name="CARI[UKER]" placeholder="Nama" value="<?php echo @$CARI['UKER'];?>" id="CARI_NAMA">
						                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
						            </div>
						            <div class="col-sm-2">
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
						<div align="center" style="padding-top:30px;padding-bottom:30px;">
							<h3><strong>Kepatuhan Detail</strong></h3>
							<div class='col-md-12'>
								<div class='col-md-2' align="left">
									Provinsi
								</div>	
								<div class='col-md-9' align="left">
									: All
								</div>
							</div>
							<div class='col-md-12'>
								<div class='col-md-2' align="left">
									Instansi
								</div>	
								<div class='col-md-9' align="left">
									: <?php if(@$CARI['INST'] != ''){ echo urldecode(explode('-', str_replace("_"," ",$CARI['INST']))[1]);}else{ echo 'All';} ?>
								</div>
							</div>
							<div class='col-md-12'>
								<div class='col-md-2' align="left">
									Unit Kerja
								</div>	
								<div class='col-md-9' align="left">
									: <?php if(@$CARI['UKER'] != ''){ echo urldecode(explode('-', str_replace("_"," ",$CARI['UKER']))[1]);}else{ echo 'All';} ?>
								</div>
							</div>
							<div class='col-md-12'>
								<div class='col-md-2' align="left">
									Tahun
								</div>	
								<div class='col-md-9' align="left">
									: <?php if(@$CARI['TAHUN'] != ''){ echo $CARI['TAHUN'];}else{ echo date("Y");} ?>
								</div>
							</div>
						</div>
						<div class='col-md-12' style="padding-top:30px;padding-bottom:30px;">
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
			                    <thead>
			                        <tr>
			                            <th width="30">No.</th>
			                            <th>NIK</th>
			                            <th>NAMA</th>
			                            <th>Jabatan</th>
			                            <th>Tanggal Lahir</th>
			                            <th>Tanggal Lapor</th>
			                            <th>Tanggal Terima</th>
			                        </tr>
			                    </thead>
			                    <tbody>
			                        <?php
			                            $i = 0 + $offset;
			                            $start = $i + 1;
			                            foreach ($items as $item) {
			                        ?>
			                        <tr>
			                            <td><?php echo ++$i; ?>.</td>
			                            <td>
			                                <?php echo $item->NIK;?>
			                            </td>
			                            <td>
			                            	<?php echo $item->NAMA;?>
			                            </td>                            
			                            <!-- <td><?php echo $item->JABATAN; ?> - <?php echo $item->INST_NAMA; ?></td> -->
			                            <td>
			                                <?php
			                                if($item->NAMA_JABATAN){
			                                    $j = explode(',',$item->NAMA_JABATAN);
			                                    echo '<ul>';
			                                    foreach ($j as $ja) {
			                                        $jb = explode(':58:', $ja);
			                                        $idjb = $jb[0];
			                                        $statakhirjb = @$jb[1];
			                                        $statakhirjbtext = @$jb[2];
			                                        $statmutasijb = @$jb[3];
			                                        if (@$jb[4] != '') {
			                                            echo '<li>'.@$jb[4].'</li>';
			                                        }
			                                    }
			                                    echo '</ul>';
			                                }
			                                ?>
			                            </td>
			                            <td class="text-center">
			                            	<?php echo date('d-m-Y',strtotime($item->TGL_LAHIR)); ?>
										</td>
			                            <td class="text-center">
			                                <?php echo date('d-m-Y',strtotime($item->TGL_LAPOR)); ?>
			                            </td>
			                            <td class="text-center">
			                            </td>
			                        </tr>
			                        <?php
			                                $end = $i;
			                            }
			                        ?>
			                        <?php
			                        echo (count($items) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
			                        ?>
			                    </tbody>
			                </table>
			            </div>
					</div>
					<div class="box-footer clearfix">
					    <?php
					        if($total_rows){
					    ?>
					    <div class="col-sm-6">
					        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo $start; ?> to <?php echo $end; ?>
					            of <?php echo $total_rows; ?> entries
					        </div>
					    </div>
					    <?php
					        }
					    ?>
					    <div class="col-sm-6 text-right">
					        <div class="dataTables_paginate paging_bootstrap">
					            <?php echo $pagination; ?>
					        </div>
					    </div>
					    <div class="clearfix"></div>
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

		$('#lembaga').select2({
		    minimumInputLength: 0,
		    ajax: {
		        url: "<?=base_url('index.php/share/reff/getLembaganama')?>",
		        dataType: 'json',
		        quietMillis: 250,
		        data: function (term, page) {
		            return {
		                q: term
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
		            $.ajax("<?=base_url('index.php/share/reff/getLembaganama')?>"+'/'+id, {
		                dataType: "json"
		            }).done(function(data) { callback(data[0]); });
		        }
		    },
		    formatResult: function (state) {
		        return state.name;
		    },
		    formatSelection:  function (state) {
		        return state.name;
		    }
		});

		$('#lembaga').on("change", function (e) {
		    $('#uker').select2('val', '');
		    LEMBAGA = $(this).val();
		    $('#uker').select2({
		        minimumInputLength: 0,
		        ajax: {
		            url: "<?=base_url('index.php/share/reff/getUnitKerjanama')?>/"+LEMBAGA,
		            dataType: 'json',
		            quietMillis: 250,
		            data: function (term, page) {
		                return {
		                    q: term
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
		                $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
		                    dataType: "json"
		                }).done(function(data) { callback(data[0]); });
		            }
		        },
		        formatResult: function (state) {
		            return state.name;
		        },
		        formatSelection:  function (state) {
		            return state.name;
		        }
		    });
		});

		if($('#lembaga').val() != ''){
			$('#uker').select2({
			    minimumInputLength: 0,
			    ajax: {
			        url: "<?=base_url('index.php/share/reff/getUnitKerjanama')?>/"+LEMBAGA,
			        dataType: 'json',
			        quietMillis: 250,
			        data: function (term, page) {
			            return {
			                q: term
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
			            $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
			                dataType: "json"
			            }).done(function(data) { callback(data[0]); });
			        }
			    },
			    formatResult: function (state) {
			        return state.name;
			    },
			    formatSelection:  function (state) {
			        return state.name;
			    }
			});
		}

		$("#ajaxFormCariformdetail").submit(function(e) {
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
        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCariformdetail'));
            return false;
        });  
        $('#btn-clear').click(function(event) {
            $('#ajaxFormCariformdetail').find('input:text').val('');
            $('#ajaxFormCariformdetail').find('select').val('');
            $('#ajaxFormCariformdetail').trigger('submit');
        });	
	});
</script>
