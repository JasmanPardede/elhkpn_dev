<div id="e-report-lhkpn">
	<section class="content-header">
		<h1>
			Laporan Perubahan Harta
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
						<form method="post" class='form-horizontal' id="ajaxFormCarireportperubahanharta" action="index.php/ereport/all_rpt_lhkpn/perubahan_harta">
						    <div class="box-body">
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Tahun :</label>
						                <div class="col-sm-4">
						                    <input type="text" required class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN'];?>" id="CARI_TAHUN">
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">PN :</label>
						                <div class="col-sm-4">
						                    <input type="text" required style="border:none;width:300px;" name="CARI[PN]" placeholder="PN" value="<?php echo @$CARI['PN'];?>" id="CARI_PN">
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="form-group">
						                <label class="col-sm-4 control-label">Jenis Laporan :</label>
						                <div class="col-sm-4">
						                    <select required class="form-control" name="CARI[JENIS]">
						                        <option value="">-pilih Jenis-</option>
						                        <option value="1" <?php if(@$CARI['JENIS'] == 1){ echo 'selected';};?>>Khusus, Calon</option>
						                        <option value="2" <?php if(@$CARI['JENIS'] == 2){ echo 'selected';};?>>Khusus, Awal menjabat</option>
						                        <option value="3" <?php if(@$CARI['JENIS'] == 3){ echo 'selected';};?>>Khusus, Akhir menjabat</option>
						                        <option value="4" <?php if(@$CARI['JENIS'] == 4){ echo 'selected';};?>>Periodik tahunan</option>
						                    </select>
						                </div>
						                <div class="col-sm-3">

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

                        <?php echo @$report; ?>
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
		$("#ajaxFormCarireportperubahanharta").submit(function(e) {
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
            $('#ajaxFormCarireportperubahanharta').find('input:text').val('');
            $('#ajaxFormCarireportperubahanharta').find('select').val('');
            $('#ajaxFormCarireportperubahanharta').trigger('submit');
        });

        $('input[name="CARI[PN]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getPN')?>",
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
                    $.ajax("<?=base_url('index.php/share/reff/getPN')?>/"+id, {
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
</script>

<style type="text/css">
	#e-report-lhkpn a{
		display: block;
	}
</style>