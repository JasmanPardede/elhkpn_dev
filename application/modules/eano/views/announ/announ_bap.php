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
  <div class="panel panel-default">
    <div class="panel-heading"><strong>BA PENGUMUMAN</strong></div>
    </div>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-body" >
										<div class="row">
												<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

				 								<div class="row">
				 								</div>
    									</div>
        <!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
        <div class="col-md-12">
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
                <!-- <input type="hidden" name="id" value="<?php echo @$id; ?>" /> -->
		<div class="box-body">
		    <div class="col-md-6">
		<div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun BAST :</label>
                                        <div class="col-sm-6">
                                            <input name="CARI[TAHUN]" value="<?php echo @$CARI['TAHUN'] ?>" id="TAHUN" class="form-control date-picker-tahun" placeholder="Tahun Bast"/>
                                        </div>
                                        <div class="col-sm-3">

                            	</div>
                        	      </div>
                    	</div>
                    </div>
                              <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bulan BAST :</label>
                                        <div class="col-sm-6">
										<div class="input-group">
                                            <input name="CARI[BULAN]" value="<?php echo @$CARI['BULAN'] ?>" id="BULAN" class="form-control date-picker-bulan" style="width: 120px;" placeholder="Bulan Bast"/>
                                            <div class="input-group-btn">
												<button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari</button>
												<button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="f_clear();">Clear</button>
											</div>
										</div>
										</div>
                                    </div>

                                    <!-- <div class="form-group">
                                    	<div class="col-sm-6">

                                        </div>
                                    </div> -->
                                </div>
                            </div>
							</div>
						</form>
			</div>
		</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="box box-primary">
						<div class="box-header with-border">
						<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
							<thead>
								<tr>
									<th width="30">No.</th>
									<th>No. Pengumuman</th>
									<th>Tanggal Pengumuman</th>
                                                                        <th>No. BA Pengumuman</th>
									<th>Tanggal BA Pengumuman</th>
									<th>Keterangan</th>
	                                <th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php if($total_rows) { ?>
								<?php
									$i = 0 + $offset;
									$start = $i + 1;
									foreach ($items as $item) {
								?>
								<tr>
									<td><?php echo ++$i; ?>.</td>
                                                                        <td><?php echo $item->NOMOR_PNRI?></td>
                                                                        <td><?php echo date('d/m/Y',strtotime($item->TGL_PNRI)); ?></td>
                                                                        <td><?php echo $item->NOMOR_BAP?></td>
									<td><?php echo date('d/m/Y',strtotime($item->TGL_BA_PENGUMUMAN)); ?></td>
									<td><?php echo $item->KETERANGAN?></td>
									<td align="center">
										<button type="button" class="btn btn-sm btn-info btnDetailBap" href="index.php/eano/announ/bap_detail/<?php echo substr(md5($item->ID_BAP),5,8);?>"title="Preview Detil BA"><i class="fa fa-search-plus"></i></button>
										<!--<button type="button" class="btn btn-sm btn-success btnCetakRekap" href="index.php/eano/announ/cetakrekap/<?php echo substr(md5($item->ID_BAP),5,8);?>"title="Cetak Lembar Persetujuan"><i class="fa fa-file-text-o"></i></button>-->
										<button type="button" class="btn btn-sm btn-success btnCetakDaftar" href="index.php/eano/announCetak/index/<?php echo $item->ID_BAP;?>"title="Cetak Lembar Persetujuan"><i class="fa fa-file-o"></i></button>
										<button type="button" class="btn btn-sm btn-danger btnPopupPDF" href="index.php/eano/announ/pdf_detail/<?php echo substr(md5($item->ID_BAP),5,8);?>"title="Cetak dan Kirim Pengumuman"><i class="fa fa-file-pdf-o"></i></button>
										<button type="button" class="btn btn-sm btn-warning btnCetakExcel" href="index.php/eano/announCetak/cetak_ba/<?php echo $item->ID_BAP;?>"title="Cetak Excel BA Pengumuman"><i class="fa fa-file-excel-o"></i></button>
									</td>
								</tr>
								<?php
									$end = $i;
									}
								?>
								<?php } else { ?>
							                <tr id="not-found">
							                    <td colspan="8" align="center"><strong>Belum ada data</strong></td>
							                </tr>
							            <?php }?>
							</tbody>
						</table>

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
		</div>
		</div>
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
        $('.btn-print').click(function(e) {
            url = $(this).attr('href');
            html = '<iframe src="'+url+'" width="100%" height="500px"></iframe>';
            OpenModalBox('Print History Bast', html, '', 'large');
            return false;
        });

        $(".btnDetailBap").click(function () {
           url = $(this).attr('href');
            $('#loader_area').show();
            f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
            $.post(url, function (html) {
                OpenModalBox('Detail BA Pengumuman', html, f_close, 'large');
            });
            return false;
        });
        $(".btnPopupPDF").click(function () {
           url = $(this).attr('href');
            $('#loader_area').show();
            f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
            $.post(url, function (html) {
                OpenModalBox('Generate PDF Pengumuman', html, f_close, 'large');
            });
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

        $('.btnCetakRekap').click(function(event) {
            var url = $(this).attr('href');
            // window.location.hash = url;
            // ng.LoadAjaxContent(url);
            // $.post(url, function(html) {
            //     OpenModalBox('Cetak Rekap', html, '', 'large');
            // });
                f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
			html = '<iframe src="" width="100%" height="'
			        + ($(window).height() - 140 + 'px')
			        + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
			OpenModalBox('Cetak Lembar Persetujuan LHKPN', html, f_close, 'large');
			$("<form action='"+url+"' method='post' target='iframeCetak'></form>")
			    // .append($("<input type='hidden' name='msg' />").attr('value',msg))
			    .appendTo('body')
			    .submit()
			    .remove();
			return false;

        });
          $('.btnCetakDaftar').click(function(event) {
            var url = $(this).attr('href');
            // window.location.hash = url;
            // ng.LoadAjaxContent(url);
            // $.post(url, function(html) {
            //     OpenModalBox('Cetak Rekap', html, '', 'large');
            // });
//                f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
//			html = '<iframe src="" width="100%" height="'
//			        + ($(window).height() - 140 + 'px')
//			        + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
//			OpenModalBox('Cetak Daftar Pengumuman LHKPN', html, f_close, 'large');
			$("<form action='"+url+"' method='post'></form>")
			    // .append($("<input type='hidden' name='msg' />").attr('value',msg))
			    .appendTo('body')
			    .submit()
			    .remove();
//			return false;

        });

		$('.btnCetakExcel').click(function(event) {
            var url = $(this).attr('href');
			$("<form action='"+url+"' method='post'></form>")
			    .appendTo('body')
			    .submit()
			    .remove();
        });

        $('.btnGenPDF').click(function(event) {
            var url = $(this).attr('href');
	        $.ajax({
	            url: url,
	            method: 'POST',
	            dataType: 'json',
	            success: function(res){
	                msg = {
	                    success: 'Pesan dan email berhasil dikirim !',
	                    error: 'Pesan dan email gagal dikirim !'
	                };
	                if (res.hasil == 1) {
	                	swal({
            				title: 'Data berhasil disimpan',
            				text:
				              'File bisa didownload di '+
				              '<a href="<?=base_url("uploads/FINAL_LHKPN")?>'+'/'+res.name+'">disini</a>',
				            html: true
				        });
	                } else {
	                    // alertify.success(msg.success);
	                }
	            }
	        });

        });

    });
</script>

<style>
	td .btn {
		margin: 0px;
	}
</style>