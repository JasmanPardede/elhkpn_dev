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
 * @package Views/eano/announ
*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Daftar Announcement LHKPN
    <small></small>
  </h1>
  <?php echo $breadcrumb;?>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
			<div class="box-header with-border">
				<div class="row">
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					    <!-- <button type="button" id="btnAdd" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Tambah Data</button> -->
					    <!-- <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
					    <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
					    <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button> -->

					</div>
			        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
			            <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/eano/announ">
			                <div class="box-body">
			                    <div class="row">
			                        <div class="form-group">
			                            <label class="col-sm-4 control-label">Tahun Lapor :</label>
			                            <div class="col-sm-5">
			                                <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN'];?>" id="CARI_TAHUN">
			                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
			                            </div>
			                            <div class="col-sm-3">

			                            </div>
			                        </div>
			                    </div>
			                    <div class="row">
			                        <div class="form-group">
			                            <label class="col-sm-4 control-label">Jenis Laporan :</label>
			                            <div class="col-sm-5">
			                                <select class="form-control" name="CARI[JENIS]">
			                                    <option value="">-pilih Jenis-</option>
			                                    <option value="1" <?php if(@$CARI['JENIS'] == 1){ echo 'selected';};?>>Khusus, Calon</option>
			                                    <option value="2" <?php if(@$CARI['JENIS'] == 2){ echo 'selected';};?>>Khusus, Awal menjabat</option>
			                                    <option value="3" <?php if(@$CARI['JENIS'] == 3){ echo 'selected';};?>>Khusus, Akhir menjabat</option>
			                                    <option value="4" <?php if(@$CARI['JENIS'] == 4){ echo 'selected';};?>>Periodik tahunan</option>
			                                </select>
			                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
			                            </div>
			                            <div class="col-sm-3">

			                            </div>
			                        </div>
			                    </div>
<!-- 			                    <div class="row">
			                        <div class="form-group">
			                            <label class="col-sm-4 control-label">Status Laporan :</label>
			                            <div class="col-sm-5">
			                                <select class="form-control" name="CARI[STATUS]">
			                                    <option value="">-pilih Status-</option>
			                                    <option value="1" <?php if(@$CARI['STATUS'] == 1){ echo 'selected';};?>>Masuk</option>
			                                    <option value="2" <?php if(@$CARI['STATUS'] == 2){ echo 'selected';};?>>Perlu Perbaikan</option>
			                                    <option value="3" <?php if(@$CARI['STATUS'] == 3){ echo 'selected';};?>>Terverifikasi</option>
			                                </select> -->
			                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
<!-- 			                            </div>
			                            <div class="col-sm-3">

			                            </div>
			                        </div>
			                    </div> -->
			                    <div class="row">
			                        <div class="form-group">
			                            <label class="col-sm-4 control-label">Entri Via :</label>
			                            <div class="col-sm-5">
			                                <select class="form-control" name="CARI[VIA]">
			                                    <option value="">-pilih Via-</option>
			                                    <option value="0" <?php if(@$CARI['VIA'] == '0'){ echo 'selected';};?>>WL, Online</option>
			                                    <option value="1" <?php if(@$CARI['VIA'] == 1){ echo 'selected';};?>>Entry Hard Copy</option>
			                                    <option value="2" <?php if(@$CARI['VIA'] == 2){ echo 'selected';};?>>Import Excel</option>
			                                </select>
			                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
			                            </div>
			                            <div class="col-sm-3">

			                            </div>
			                        </div>
			                    </div>
			                    <div class="row">
			                        <div class="form-group">
			                            <label class="col-sm-4 control-label">Lembaga :</label>
			                            <div class="col-sm-5">
			                                <input type="text" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA'];?>" id="CARI_LEMBAGA">
			                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
			                            </div>
			                        </div>
			                    </div>
			                    <div class="row">
			                        <div class="form-group">
			                            <label class="col-sm-4 control-label">Cari :</label>
			                            <div class="col-sm-3">
			                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA'];?>" id="CARI_NAMA">
			                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
			                            </div>
			                            <div class="col-sm-2">
			                                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
			                                <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			            </form>
			        </div>
					<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
				</div>


			  <!-- <div class="box-tools"> -->
			  <!-- </div> -->
			</div><!-- /.box-header -->
			<div class="box-body">
			<?php
			    if($total_rows){
			?>
			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
			    <thead>
			        <tr>
			            <th width="30">No.</th>
			            <th>Agenda</th>
			            <th>PN</th>
			            <th>Tgl Lapor</th>
			            <th>Jabatan</th>
			            <th class="hidden-xs hidden-sm">Jenis</th>
			            <th>Aksi</th>
			        </tr>
			    </thead>
			    <tbody>
			        <?php
			            $i = 0 + $offset;
			            $start = $i + 1;
			            $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
			            $aStatus = ['0' => 'Draft', '1' => 'Masuk', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi', '4' => 'Diumumkan', '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Ditolak'];
			            $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
			            foreach ($items as $item) {
			            	$agenda = date('Y', strtotime($item->TGL_LAPOR)).'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN;
			        ?>
			        <tr>
			            <td><?php echo ++$i; ?>.</td>
			            <td>
                            <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($item->ID_LHKPN),5,8) ?>" class="btn-tracking"><?php echo $agenda; ?></a>
                        </td>
			            <td><a href="index.php/eano/announ/getInfoPn/<?php echo $item->ID_PN;?>" onClick="return getPn(this);"><?php echo $item->NAMA; ?></a></td>
			            <td><?php echo date('d/m/Y',strtotime($item->tgl_kirim_final)); ?>
							<?php if($item->ENTRY_VIA == '0'){
									echo "&nbsp; <img src='".base_url()."img/online.png' title='Via Online'/>";
								}else if($item->ENTRY_VIA == '1'){
									echo "&nbsp; <img src='".base_url()."img/hard-copy.png' title='Via Hardcopy'/>";
								}else if($item->ENTRY_VIA == '2'){
									echo "&nbsp; <img src='".base_url()."img/excel-icon.png' title='Via Excel'/>";
								}
							?>
						</td>
			            <td>
			                <?php
			                    if(@$item->NAMA_JABATAN){
			                        $j = explode(',',@$item->NAMA_JABATAN);
			                        echo '<ul>';
			                        foreach ($j as $ja) {
			                            $jb = explode(':58:', @$ja);
			                            $idjb = @$jb[0];
			                            @$statakhirjb = @$jb[1];
			                            @$statakhirjbtext = @$jb[2];
			                            @$statmutasijb = @$jb[3];
			                            echo '<li>'.@$jb[4].'</li>';
			                        }
			                        echo '</ul>';
			                    }
			                ?>
			            </td>
			            <td class="hidden-xs hidden-sm"><?php echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus'; ?></td>
			            <td width="120" nowrap="" align="center">
			            	<input type="hidden" class="key" value="<?php echo substr(md5($item->ID_LHKPN),5,8);?>">
							<button type="button" class="btn btn-sm btn-success btnGenPDF" href="index.php/efill/lhkpn_view/genpdf">Periksa Naskah</button><br/>
			            	<?php
			                    if ($item->STATUS == '2') {
			                        echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;" title="Naskah ini perlu perbaikan !"></i>';
			                    }
			                ?>

			            </td>
			        </tr>
			        <?php
			                $end = $i;
			            }
			        ?>
			    </tbody>
			</table>
			<?php
			    }else{
			        echo 'Tidak ada data.';
			    }
			?>
			</div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->



<script type="text/javascript">
function getPn(ele){
    var url = $(ele).attr('href');
    $.get(url, function(html){
        OpenModalBox('Detail PN', html, '', 'standart');
    });

    return false;
}

$(document).ready(function() {
    $('.year-picker').datepicker({
        orientation: "left",
        format: 'yyyy',
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    });

    $("#ajaxFormCari").submit(function(e) {
        var url = $(this).attr('action');
        ng.LoadAjaxContentPost(url, $(this));
        return false;
    });

    $('#btn-clear').click(function(event) {
        $('#ajaxFormCari').find('input:text').val('');
        $('#ajaxFormCari').find('select').val('');
        $('#ajaxFormCari').trigger('submit');
    });

    $('.btn-tracking').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
        });
        return false;
    });

    $('.btnGenPDF').click(function(){
    	key = $(this).parents('td').children('.key').val();
    	url = $(this).attr('href')+'/'+key;
		// ng.exportTo('pdf', url, 'Generate PDF LHKPN');
        $.get('<?php echo base_url() ?>/index.php/efill/lhkpn/entry/'+key+'/view', function(home2){
            $('#ajaxFormCari').after(
                '<form method="post" action="' + url
                + '" id="ajaxFormPrint"></form>');
            $("#ajaxFormCari").children().clone().appendTo('#ajaxFormPrint');
            html = '<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">'
                + '<iframe src="" width="100%" height="'
                + ($(window).height() - 140 + 'px')
                + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe></div>';
            OpenModalBox('', html, '', 'large');
            $('#ajaxFormPrint').attr('target', 'iframeCetak');
            $('#ajaxFormPrint').submit();
            $('#ajaxFormPrint').remove();
        });
    });
});
</script>