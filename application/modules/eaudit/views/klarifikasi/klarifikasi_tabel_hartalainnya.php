<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Harta berupa piutang, kerjasama usaha, HAKI, sewa dibayar dimuka atau hak pengelolaan"</h5>
</div>
<br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info aksi-hide" id="klarifikasiHartaLainnya" href="index.php/eaudit/klarifikasi/update_harta_lainnya/<?php echo $new_id_lhkpn;?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/new"><span class="fa fa-plus"></span> Tambah</button><br><br>
<div class="box-body" id="wrapperHartaLain">
<div id="tombolTetapHl"></div><br><br>
    <table  class="table table-bordered table-hover table-striped" id="TableHartaLainnya">
        <thead class="table-header">
            <tr>
                <th width="15px"><input type="checkbox" onClick="chk_all_hl(this);" /></th>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th>URAIAN</th>
                <th>ASAL USUL HARTA</th>
                <th>NILAI PEROLEHAN</th>
                <th>NILAI PELAPORAN/ KLARIFIKASI</th>
                <th width="10px" class="aksi-hide">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($lhkpn_harta_lainnya): ?>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalLainya = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($lhkpn_harta_lainnya as $hartalainnya) {


                            $img = null;
							if ($hartalainnya->FILE_BUKTI) {
		
                                $filelist = explode(',', $hartalainnya->FILE_BUKTI);
                                
                                $dir = null;
							
							    foreach ($filelist as $key => $tmp_name) {
								//if (empty($tmp_name)) {
									
                                    if ($key==0) {
                                        $dt = explode("/", $tmp_name);
                                        $c = count($dt);
                                        for($i=0; $i<$c-1; $i++) {
                                            $dir = $dir . $dt[$i] . "/";
                                        }
                                        $tmp_name = $dt[$i++];
                                    }
		
                                    if (file_exists($dir . $tmp_name) && $tmp_name!="") {
                                            $tmp_name = $dir . $tmp_name;
                                            $img = $img . " <a class='files' target='_blank' href='" . base_url() . '' . $tmp_name . "'><i class='fa fa-file-text fa-2x'></i></a>";
                                        }
                                        
                                    //}
                                    }
                                }



                $totalLainya += str_replace('.', '', $hartalainnya->NILAI_PELAPORAN);
                if ($i%2 == 0) {
                    $bgcolor = 'bgcolor="#f9f9f9"';
                }
                else{
                    $bgcolor = '';
                }
                if ($hartalainnya->IS_PELEPASAN == '1') {
                    $style_pelepasan = 'style="background-color: rgb(128, 128, 128); color: rgb(255, 255, 255);"';
                }else{
                    $style_pelepasan = null;
                }
                ?>
                <tr <?php echo $bgcolor; ?> <?= $style_pelepasan ?> >
                    <td><?php if($hartalainnya->STATUS==3 && $hartalainnya->ID_HARTA!=''){ ?>
                        <input class="chk-hl" type="checkbox" onclick="chkHl(this);" value="<?= $hartalainnya->ID ?>" name="chk-hl">  <?php }else{ ?> <input type="checkbox" disabled="true" value="" name="chk-hl"> <?php } ?></td>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                        $data_label = array();
                            $data_label[1] = '<label class="label label-primary">Tetap</label>';
                            $data_label[2] = '<label class="label label-success">Ubah</label>';
                            $data_label[3] = '<label class="label label-success">Baru</label>';
                            if ($hartalainnya->IS_PELEPASAN == '1') {
                                echo '<label class="label label-danger pelepasan">Lepas</label>';
                            } else {
                                if($hartalainnya->STATUS==3 && $hartalainnya->ID_HARTA!=''){
                                    echo '<label class="label label-warning">Lama</label>';
                                }else{
                                    echo $data_label[$hartalainnya->STATUS];
                                }
                                
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                        // $img = null;
                        // if ($hartalainnya->FILE_BUKTI) {
                        //     if (file_exists($hartalainnya->FILE_BUKTI)) {
                        //         $img = "  <a target='_blank' href='" . base_url() . '' . $hartalainnya->FILE_BUKTI . "'><i class='fa fa-download'></i></a>";
                        //     }
                        // }
                        
                        $uraian = "
					<table class='table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $hartalainnya->HLAIN_NAMA . "  " . $img . "</td>
						 </tr>
						 <tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . @$hartalainnya->KETERANGAN . "</td>
						 </tr>
					</table>
				";
                        
                        echo $uraian;
                        ?>
                    </td>
                   
                    <td>
                       
                            
                                <?php
                                $exp = explode(',', $hartalainnya->ASAL_USUL);
                                $text = '';
                                foreach ($exp as $key) {
                                    foreach ($asalusul as $au) {
                                        if ($au->ID_ASAL_USUL == $key) {
                                            $text .= ($au->IS_OTHER === '1' ? '<font>' . $au->ASAL_USUL . '</font>' : $au->ASAL_USUL) . '&nbsp;,&nbsp;&nbsp;';
                                        }
                                    }
                                }
                                $rinci = substr($text, 0, -19);
                                echo $rinci;
                                ?>
                          
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <?php echo $hartalainnya->SIMBOL . ' ' . number_format($hartalainnya->NILAI_PEROLEHAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                NP: Rp. <?php echo number_format($hartalainnya->NILAI_PELAPORAN_OLD, 0, '', '.'); ?>/<br>
                                NK: Rp. <?php echo number_format($hartalainnya->NILAI_PELAPORAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td class="aksi-hide">
                        <?php
                            $id_ht = '<input type="hidden" class="ID_HARTA_TABLE" value="' . $hartalainnya->ID_HARTA . '" />';
                            $id_ht .= '<input type="hidden" class="STATUS_TABLE" value="' . $hartalainnya->STATUS . '" />'; 
                            $nilai = 0;
                            $key = $key ? $key : 0;
                            $nilai = $hartalainnya->$key;
                            if ($t_lhkpn->IS_COPY == '0') { // KONDISI LAPORAN PERTAMA
                                ?>
                                <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_lainnya/<?php echo $hartalainnya->ID_LHKPN; ?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartalainnya->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                                <?php
                            } else { // KONDISI KEDUA , KETIGA dst
                                if ($hartalainnya->IS_PELEPASAN == '0') { // JIKA BUKAN PELEPASAN
                                    if ($hartalainnya->STATUS == '1') { // JIKA TETAP
                                    //  dump('JIKA TETAP');
                                        ?>
                                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_lainnya/<?php echo $hartalainnya->ID_LHKPN; ?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartalainnya->ID_LHKPN; ?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/t_hl" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                            <?php
                                    } else { // JIKA BUKAN TETAP & LEPAS
                                        if ($hartalainnya->ID_HARTA) { // JIKA DI AMBIL DARI HASIL COPY
                                            //  dump('JIKA DI AMBIL DARI HASIL COPY');
                                            ?>
                                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_lainnya/<?php echo $hartalainnya->ID_LHKPN; ?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartalainnya->ID_LHKPN; ?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/t_hl" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                            <?php
                                            if ($hartalainnya->STATUS == '3') {
                                                ?>
                                                <button type="button" class="btn btn-primary penetapan-action"  id_lhkpn='<?= $hartalainnya->ID_LHKPN; ?>' id_harta="<?= $hartalainnya->ID  ?>" href="#" title="Tetap"  ><i class="fa fa-link"></i></button>
                                                <?php
                                            }
                                        } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                                    //  dump('JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst');
                                            ?>
                                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_lainnya/<?php echo $hartalainnya->ID_LHKPN; ?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartalainnya->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                                            <?php
                                        }
                                    }
                                } else { // JIKA DATA DITETAPKAN
                                            ?>
                                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_lainnya/<?php echo $hartalainnya->ID_LHKPN; ?>/<?php echo $hartalainnya->ID ? $hartalainnya->ID : 0; ?>/edit" title="Undo" onclick="onButton.go(this, 'large', true);"><i class="fa fa-repeat"></i></button>                                       
                                            <?php
                                }
                            }
                            echo $id_ht;
                            ?>

                        <!-- <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $hartalainnya->ID; ?>/hartalainnya" >Upload</button> -->
                            <!--<button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_harta_lainnya/<?php echo $hartalainnya->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $hartalainnya->ID; ?>/harta_lainnya" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>-->
                    </td>				
                    			  	
                </tr>
                <?php if ($hartalainnya->KET_PEMERIKSAAN != ''): ?>
                    <tr <?php echo $bgcolor; ?>>
                        <td></td>
                        <td></td>
                        <td <?php echo $is_preview ? 'colspan="4"' : 'colspan="5"'; ?>><strong>Keterangan Pemeriksaan: </strong><?php echo $hartalainnya->KET_PEMERIKSAAN; ?></td>
                        <!-- <td></td> -->
                    </tr>
                <?php endif ?>

                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Data Tidak Ditemukan</td>
                    </tr>
                <?php endif ?>
        </tbody>
        <tf oot class="table-footer">
            <?php if ($lhkpn_harta_lainnya): ?>
            <tr>
                <td <?php echo $is_preview ? 'colspan="5"' : 'colspan="6"'; ?>><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalLainya, 0, '', '.'); ?></b></td>
            </tr>
            <?php endif ?>
        </tfoot>
    </table> 
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#klarifikasiHartaLainnya").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Input Klarifikasi Data Harta Lainnya', html, null, 'large');
            });            
            return false;
        });
        $("#wrapperHartaLain .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data Harta Lainnya', html, '', 'standart');
            });
            return false;
        });
    });
    
    // PENETAPAN ACTION
    $("#TableHartaLainnya tbody").on('click', '.penetapan-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function () {
            $.ajax({
                url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_hl',
                async: false,
                dataType: 'JSON',
                success: function (data) {
                    var rs = eval(data);
                    if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else if (rs.result.NILAI_PELAPORAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else{
                        do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_hl', 'Data Harta Tidak Bergerak Sudah Tetap ');
                        //   $('#TableTanah').DataTable().ajax.reload(); 
                        var url = location.href.split('#')[1];
                        url = url.split('?')[0] + '?upperli=li3&bottomli=lii5';
                        window.location.hash = url;
                        ng.LoadAjaxContent(url);
                        $('.modal-open').css("overflow", "scroll");
                        $('.modal-backdrop').remove();
                        return false;
                    }
                }
                });
            });
        });

    // DELETE ACTION
    $("#TableHartaLainnya tbody").on('click', '.delete-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin menghapus data ? ", function () {
            do_delete('eaudit/klarifikasi/soft_delete/' + id + '/harta_lainnya', 'Data Harta Bergerak Berhasil dihapus ');
            $("#modal-success").removeClass("modal-lg"); 
            $("#modal-warning").removeClass("modal-lg"); 
            return false;
                
            });
        });
    
    function tetapkan_hl_all(){
        var jml_cek = $("#jml_check").val();
        confirm("Apakah anda yakin akan melakukan penetapan semua harta yang dipilih ? ", function () {
            $('.chk-hl:checkbox:checked').each(function(index){
                var id = $(this).val();
                if($(this).is(':checked')){
                    ajax_hl_penetapan(id, jml_cek, index);
                }
            })
        });
    }

    function ajax_hl_penetapan(id, jml_cek, index){
        $('#loader_area').show();
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_hl',
            async: true,
            dataType: 'JSON',
            success: function (data) {
                var rs = eval(data);
                if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else if (rs.result.NILAI_PELAPORAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else{
                    do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_hl', 'Data Harta Tidak Bergerak Sudah Tetap ');

                    var url = location.href.split('#')[1];
                    url = url.split('?')[0] + '?upperli=li3&bottomli=lii5';
                    window.location.hash = url;

                    if(jml_cek == 1){
                        ng.LoadAjaxContent(url);
                    }else if(jml_cek > 1 && jml_cek == index+1){
                        var delayInMilliseconds = 10;
                        setTimeout(function() {
                            ng.LoadAjaxContent(url);
                        }, delayInMilliseconds);
                    }

                    $('.modal-open').css("overflow", "scroll");
                    $('.modal-backdrop').remove();
                    return false;
                }
            }
        });
    }
    
    function chk_all_hl(ele){
        if($(ele).is(':checked')){
            $('.chk-hl').prop('checked', true);
        }else{
            $('.chk-hl').prop('checked', false);
        }
        cekTombolHl("chk-hl");
    }

    function chkHl(ele){
        var val = $(ele).val();
        cekTombolHl("chk-hl");
    }

    function cekTombolHl(chkboxName){
        var checkboxes = document.getElementsByName(chkboxName);
            var checkboxesChecked = [];
            for (var i=0; i<checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                      checkboxesChecked.push(checkboxes[i]);
                    }
            }
            
            var jml_chk = checkboxesChecked.length;
            if (jml_chk === 0 ){
                $("#tombolTetapHl").empty();
            } else{
                $("#tombolTetapHl").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"tetapkan_hl_all();\"title=\"Tetap\"><input type=\"hidden\" name=\"jml_check\" id=\"jml_check\"><i class=\"fa fa-plus-square\"></i> Tetapkan Semua</button>");
                $("#jml_check").val(jml_chk);
            }
    }

</script>
