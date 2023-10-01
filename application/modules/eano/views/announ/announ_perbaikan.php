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
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<i class="fa <?php echo $icon;?>"></i>
		Perbaikan Naskah
<!-- 		<?php echo $title;?> -->
<!-- 		<small><?php echo $title;?> </small> -->
	</h1>
	<?php echo $breadcrumb;?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">

				<div class="box-header with-border">
					<div class="col-md-offset-4 col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
							<!-- <input type="text" name="id" value="<?php echo @$id; ?>" /> -->
							<div class="box-body">
								<div class="row">

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun Lapor :</label>
                                        <div class="col-sm-6">
                                            <input name="CARI[TAHUN]" value="<?php echo @$CARI['TAHUN'] ?>" id="TAHUN" class="form-control date-picker-tahun" placeholder="Tahun Lapor"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cari :</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA'];?>" id="CARI_NAMA">
                                            <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                            <button type="button" id="btn-clear" class="btn btn-sm btn-default" onclick="f_clear()"> Clear</button>
                                        </div>
                                    </div>
                                </div>
							</div>
						</form>
					</div>
				</div>
				
				<!-- /.box-header -->
				<div class="box-body">
					<?php if($total_rows) { ?>
						<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
							<thead>
								<tr>
									<th width="30">No.</th>
									<th width="100px">TGL LAPORAN</th>
									<th width="100px">NO Agenda</th>
									<th>NIK / NAMA</th>
                                    <th>Catatan</th>
	                                <th width="100px">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$i = 0 + $offset;
									$start = $i + 1;
									foreach ($items as $item) {
                                        $agenda = date('Y', strtotime($item->TGL_LAPOR)).'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN;
                                        ?>
								<tr>
									<td><?php echo ++$i; ?>.</td>
									<td>
                                        <?php echo date('d/m/Y',strtotime($item->TGL_LAPOR)); ?></br>
                                        <?php if($item->ENTRY_VIA == '0'){
                                            echo "<img src='".base_url()."img/online.png' title='Via Online'/>";
                                        }else if($item->ENTRY_VIA == '1'){
                                            echo "<img src='".base_url()."img/hard-copy.png' title='Via Hardcopy'/>";
                                        }else if($item->ENTRY_VIA == '2'){
                                            echo "<img src='".base_url()."img/excel-icon.png' title='Via Excel'/>";
                                        }
                                        ?>
                                        <br />
                                        dientri oleh: <?php echo ($item->ENTRY_VIA == '0' ? $item->NAMA : $item->USERNAME_ENTRI); ?>
                                    </td>
									<td>
                                        <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($item->ID_LHKPN),5,8) ?>" class="btn-tracking"><?php echo $agenda; ?></a>
                                    </td>
									<td><?php echo $item->NIK;?> /
                                        <a href="index.php/eano/announ/getInfoPn/<?php echo $item->ID_PN;?>" onClick="return getPn(this);"><?php echo $item->NAMA;?></a>
                                    </td>
                                    <td>
                                        <?= @$item->CATATAN_PERBAIKAN_NASKAH;?>
                                    </td>
									<td align="center">
										<button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/efill/lhkpn/entry/<?php echo substr(md5($item->ID_LHKPN),5,8);?>/edit/announ" title="Proses">Perbaikan Data</button>
                                        <!-- <button type="button" class="btn btn-sm btn-success btnCPN" href="index.php/eano/announ/lihat_cttnprbaikan/<?php echo substr(md5($item->ID_LHKPN),5,8);?>/edit/announ">Lihat Catatan Perbaikan</button> -->
									</td>
								</tr>
								<?php
									$end = $i;
									}
								?>
							</tbody>
						</table>
					<?php } else { ?>
						<?php echo 'Data Not Found...'; ?>
					<?php } ?>
				</div>

				<!-- /.box-body -->
				<div class="box-footer clearfix">
					<?php if(@$CARI['JENIS'] == '0') { ?>
						<?php if($total_rows) { ?>
							<div class="col-sm-6">
								<div class="dataTables_info" id="datatable-1_info">
									Showing
									<?php echo  $start; ?>
									to
									<?php echo  $end; ?>
									of
									<?php echo  $total_rows; ?>
									entries
								</div>
							</div>
						<?php } ?>
					<?php } else if($this->input->post('CARI[JENIS]') == '1') { ?>
						<?php if($total_rows) { ?>
							<div class="col-sm-6">
								<div class="dataTables_info" id="datatable-1_info">
									Showing
									<?php echo  $start; ?>
									to
									<?php echo  $end; ?>
									of
									<?php echo  $total_rows; ?>
									entries
								</div>
							</div>
						<?php } ?>
					<?php } ?>
					<div class="col-sm-6 text-right">
						<div class="dataTables_paginate paging_bootstrap">
							<?php echo $pagination; ?>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>
<!-- /.content -->

<script language="javascript">
	function f_clear(){
        var form = $('#ajaxFormCari');
        $('input', form).val('');
        $('select', form).val('');
        form.trigger('submit');
    }

    function getPn(ele){
        var url = $(ele).attr('href');
        $.get(url, function(html){
            OpenModalBox('Detail PN', html, '', 'standart');
        });

        return false;
    }

    $(document).ready(function() {
        $('.date-picker-tahun').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
        $('.date-picker-bulan').datepicker({
            orientation: "left",
            format: 'mm',
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        });

        $(".pagination").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });
        $(".breadcrumb").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });
        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });
        $("#ajaxFormAdd").submit(function(e) {
            var url = $(this).attr('action');
            var urlr = $("#ajaxFormCari").attr('action');
            $('#ajaxFormAdd input[name="id"]').val(idChk.join());

            if(idChk.length != 0) {
                $.post($(this).attr('action'), $(this).serializeArray(), function (data) {
                    alertify.success('Data berhasil di simpan!');
                    ng.LoadAjaxContentPost(urlr, $('#ajaxFormCari'));
                    // html = '<iframe src="'+data+'" width="100%" height="500px"></iframe>';
                    // OpenModalBox('Print BAST', html, '', 'large');
                });
            }else{
                alertify.error('Tidak ada data yg dipilih!');
            }
            return false;
        });
        $(".btnCPN").click(function() {
	        url = $(this).attr('href');
	        $.post(url, function(html) {
	            OpenModalBox('Catatan Perbaikan Naskah', html, '');
	        });
	        return false;
	    });

        $('.btn-tracking').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
            });
            return false;
        });

        $('.btn-edit').click(function(e) {
            var url = $(this).attr('href');
            ng.LoadAjaxContent(url, '');
            return false;
        });

        // ctrl + a
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.which === 65 || e.which === 97) {
                e.preventDefault();
                $('#btn-add').trigger('click');
                return false;
            }
        });
    });
</script>

<style>
	td .btn {
		margin: 0px;
	}
</style>