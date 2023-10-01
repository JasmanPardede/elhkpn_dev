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
		History BAST
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
                                        <label class="col-sm-4 control-label">Jenis BAST :</label>
                                        <div class="col-sm-6">
                                        	<select name="CARI[JENIS]" class="form-control select2" required>
                                        		<option value="">-- Pilih Jenis Bast --</option>
                                        		<option value="0" <?php if(@$CARI['JENIS'] == '0'){ echo 'selected';}?>>Dari CS ke Koord CS</option>
                                        		<option value="1" <?php if(@$CARI['JENIS'] == '1'){ echo 'selected';}?>>Dari Koord CS ke Koord Entri</option>
                                        	</select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Pengirim :</label>
                                        <!-- <div class="col-sm-6" style="margin-left: -10px;"> -->
										<div class="col-sm-6">
                                            <input name="CARI[PENGIRIM]" value="<?php echo isset($CARI['PENGIRIM']) ? $CARI['PENGIRIM'] : '0' ?>" class="form-control petugas" style="border: none;" placeholder="Pilih Pengirim"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Penerima :</label>
                                        <!-- <div class="col-sm-6" style="margin-left: -10px;"> -->
										<div class="col-sm-6">
                                            <input name="CARI[PENERIMA]" value="<?php echo isset($CARI['PENERIMA']) ? $CARI['PENERIMA'] : '0' ?>" class="form-control petugas" style="border: none;" placeholder="Pilih Penerima"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun BAST :</label>
                                        <div class="col-sm-6">
                                            <input name="CARI[TAHUN]" value="<?php echo @$CARI['TAHUN'] ?>" id="TAHUN" class="form-control date-picker-tahun" placeholder="Tahun Bast"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bulan BAST :</label>
                                        <div class="col-sm-6">
                                            <input name="CARI[BULAN]" value="<?php echo @$CARI['BULAN'] ?>" id="BULAN" class="form-control date-picker-bulan" placeholder="Bulan Bast"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                    	<div class="col-sm-3 col-sm-offset-4">
                                            <button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari</button>
			                            	<button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="f_clear();">Clear</button>
                                        </div>
                                    </div>
                                </div>
							</div>
						</form>
					</div>
				</div>
				
				<!-- /.box-header -->
				<div class="box-body">
					<?php if(@$CARI['JENIS'] == '0') { ?>
						<?php if($total_rows) { ?>
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
									<tr>
										<th width="30">No.</th>
										<th>Tanggal Bast</th>
										<th>No. Bast</th>
										<th>Pengirim</th>
		                                <th>Penerima</th>
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
										<td><?php echo date('d/m/Y',strtotime($item->TGL_PENYERAHAN)); ?></td>
										<td><a href="index.php/efill/lhkpnoffline/cetakbast/<?php echo $item->ID_BAST_CS;?>" class="btn-print"><?php echo $item->NOMOR_BAST?></a></td>
										<td><?php echo $item->USER_CS?></td>
										<td><?php echo $item->USER_KOORD_CS?></td>
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
                    <?php } else if($this->input->post('CARI[JENIS]') == '1') { ?>
						<?php if($total_rows) { ?>
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
								<thead>
									<tr>
										<th width="30">No.</th>
										<th>Tanggal Bast</th>
										<th>No. Bast</th>
										<th>Pengirim</th>
		                                <th>Penerima</th>
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
										<td><?php echo date('d/m/Y',strtotime($item->TGL_PENYERAHAN)); ?></td>
										<td><a href="index.php/efill/lhkpnoffline/cetakbast2/<?php echo $item->ID_BAST_ENTRI;?>" class="btn-print"><?php echo $item->NOMOR_BAST?></a></td>
										<td><?php echo $item->USER_KOORD_CS?></td>
										<td><?php echo $item->USER_KOORD_ENTRI?></td>
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

        $('.petugas').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUser/'.$role)?>",
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
                    $.ajax("<?=base_url('index.php/share/reff/getUser/'.$role)?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                if(state.id == '0'){
                    return '-- Pilih --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            },
            formatSelection:  function (state) {
                if(state.id == '0'){
                    return '-- Pilih --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            }
        });

        $('.btn-print').click(function(e) {
            url = $(this).attr('href');
            html = '<iframe src="'+url+'" width="100%" height="500px"></iframe>';
            OpenModalBox('Print History Bast', html, '', 'large');
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