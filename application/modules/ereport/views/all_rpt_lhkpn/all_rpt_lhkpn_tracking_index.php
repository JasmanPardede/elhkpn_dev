<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
		___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
				(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/efill/penerimaan
 */
?>
<link href="<?php echo base_url();?>css/custom.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url();?>plugins/barcode/jquery-barcode.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<i class="fa <?php echo $icon;?>"></i>
		<?php echo $title;?>
		<small><?php echo $title;?> </small>
	</h1>
	<?php echo $breadcrumb;?>
</section>


<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<!-- <h3 class="box-title">Bordered Table</h3> -->

					<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<form method="post" class='form-horizontal' id="ajaxFormCari" action="<?=@ $thisPageUrl;?>">
                            <div class="box-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">No Agenda :</label>
                                        <div class="col-sm-2">
											<input required type="text" style="width: 190px;" class="form-control input-sm" placeholder="xxxx/x/xxxxxxxxx/xxx" name="CARI[KODE]" value="<?php echo @$CARI['KODE'];?>" id="CARI_KODE"/>
										</div>
										<button type="submit" style="margin-left:90px;" class="btn btn-sm btn-default" id="btn-cari">Cari </button>
										<button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_KODE').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
										<?php if(@$total_rows){ ?>
											<button type="button" class="btn btn-sm btn-default btn-print" href="<?php echo base_url().'/index.php/efill/lhkpnoffline/tracking/printitem/'.substr(md5(@$items[0]->ID_LHKPN),5,8); ?>" title="Cetak Cover Sheet" style='width:67px;'>
	                                        	Print
	                                    	</button>
	                                    <?php }else{ echo '';} ?>	
										<script type="text/javascript">
											$(document).ready(function() {
										        $('.barkode').mask("9999/a/99999999999999999/999", {autoclear : false,selectOnFocus: true});
										    });
										</script>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<?php if(@$total_rows){ ?>
						<div class="bx-bd">
							<div class="bkd-cntn">
								<div id="barcodeTarget" class="barcodeTarget"></div>
								<canvas id="canvasTarget" width="150" height="150"></canvas>
							</div>
							<table width="85%" class="tbl-bd" align="center">
								<tr>
									<td width="5%"><strong>Bidang</strong></td>
									<td width="1%" align="center">:</td>
									<td width="30%"><?= @$items[0]->BDG_NAMA; ?></td>
								</tr>
								<tr>
									<td><strong>Nama</strong></td>
									<td align="center">:</td>
									<td><?= strtoupper(@$items[0]->NAMA); ?></td>
								</tr><tr>
									<td><strong>Tempat, Tanggal Lahir</strong></td>
									<td align="center">:</td>
									<td><?= strtoupper(@$items[0]->TEMPAT_LAHIR).', '.strtoupper(tgl_format(@$items[0]->TGL_LAHIR));?></td>
								</tr><tr>
									<td><strong>Jabatan</strong></td>
									<td align="center">:</td>
									<td><?= @$items[0]->NAMA_JABATAN;?></td>
								</tr><tr>
									<td><strong>Lembaga</strong></td>
									<td align="center">:</td>
									<td><?= @$items[0]->INST_NAMA;?></td>
								</tr><tr>
									<td><strong>Unit Kerja</strong></td>
									<td align="center">:</td>
									<td><?= @$items[0]->UK_NAMA;?></td>
								</tr><tr>
									<td><strong>Tanggal Lapor</strong></td>
									<td align="center">:</td>
                                    <td><?= (!empty($items[0]->TGL_LAPOR)) ? strtoupper(tgl_format(@$items[0]->TGL_LAPOR)) : strtoupper(tgl_format($items[0]->TANGGAL_PELAPORAN));?></td>
								</tr><tr>
									<td><strong>Tanggal Penyampaian</strong></td>
									<td align="center">:</td>
                                    <td><?= (!empty($items[0]->TANGGAL_PENERIMAAN)) ? strtoupper(tgl_format(@$items[0]->TANGGAL_PENERIMAAN)) : '-';?></td>
								</tr><tr>
									<td><strong>No. Agenda</strong></td>
									<td align="center">:</td>
									<td><?php echo @$CARI['KODE']; ?></td>
								</tr>
							</table>
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
								<thead>
									<tr>
										<th width="5%">No.</th>
										<th width="18%">Pengirim</th>
										<th width="18%">Penerima</th>
										<th width="18%">Date Insert</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php  
										$arr = array();
										if($getHistory == $arr){
									?>
										<tr>
											<td colspan="5">Data Not Found...</td>
										</tr>
									<?php
										}else{
											$i = 1;
											foreach ($getHistory as $hist) {
									?>
									<tr>
										<td><?= @$i++; ?></td>
										<td><?= @$hist->PENGIRIM; ?></td>
										<td><?= @$hist->PENERIMA; ?></td>
										<td><?= @date('d/m/Y h:m:s',strtotime(@$hist->DATE_INSERT)); ?></td>
										<td><?= @$hist->STATUS; ?></td>
									</tr>
									<?php }} ?>
								</tbody>
							</table>
						</div>
					<?php  }else{ echo 'Data Not Found...'; }?>
				</div>
				<!-- /.box-body -->
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>
<!-- /.content -->

<script language="javascript">
    $(document).ready(function() {
    	$("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('.btn-print').click(function(e) {
            url = $(this).attr('href');
            html = '<iframe src="'+url+'" width="100%" height="500px"></iframe>';
            OpenModalBox('Print Tracking LHKPN', html, '', 'large');
            return false;
        });
    });

    function generateBarcode(){
        var value    = '<?php echo @$CARI['KODE']; ?>';
        var btype    = 'code93';
        var renderer = $("input[name=renderer]:checked").val();
        
	    var quietZone = false;
        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
          	quietZone = true;
        }
		
        var settings = {
          	output:renderer,
          	bgColor: $("#bgColor").val(),
          	color: $("#color").val(),
          	barWidth: $("#barWidth").val(),
          	barHeight: $("#barHeight").val(),
          	moduleSize: $("#moduleSize").val(),
          	posX: $("#posX").val(),
          	posY: $("#posY").val(),
          	addQuietZone: $("#quietZoneSize").val()
        };

        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
          value = {code:value, rect: true};
        }

        if (renderer == 'canvas'){
          	clearCanvas();
          	$("#barcodeTarget").hide();
          	$("#canvasTarget").show().barcode(value, btype, settings);
        } else {
          	$("#canvasTarget").hide();
          	$("#barcodeTarget").html("").show().barcode(value, btype, settings);
        }
  	}

  	$(function(){
    	generateBarcode();
  	});
</script>

<style>
	td .btn {
		margin: 0px;
	}
</style>
