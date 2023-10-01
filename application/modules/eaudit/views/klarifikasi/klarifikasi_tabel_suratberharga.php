<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Harta berupa Surat Berharga, contoh penyertaan modal saham dan investasi"</h5>
</div>
<br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info aksi-hide" id="klarifikasiDataSuratBerharga" href="index.php/eaudit/klarifikasi/update_harta_surat_berharga/<?php echo $new_id_lhkpn;?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/new"><span class="fa fa-plus"></span> Tambah</button><br><br>
<div class="box-body" id="wrapperSuratBerharga">
<div id="tombolTetapSb"></div><br><br>
    <table class="table borderless" id="TableHartaSuratBerharga">
        <thead class="table-header">
            <tr>
                <th width="15px"><input type="checkbox" onClick="chk_all_sb(this);" /></th>
                <th width="15px">NO</th>
                <th width="70px">STATUS</th>
                <th >URAIAN</th>
                <th >NO REKENING/ NO NASABAH</th>
                <th >ASAL USUL HARTA</th>
                <th >NILAI PEROLEHAN</th>
                <th >NILAI PELAPORAN/ KLARIFIKASI</th>
                <th width="10px" class="aksi-hide">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalSuratBerharga = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            if($lhkpn_harta_surat_berharga){
            foreach ($lhkpn_harta_surat_berharga as $suratberharga) {


                ////////////////atas nama & pasangan anak//////////////////
                $get_atas_nama = $suratberharga->ATAS_NAMA;
                $atas_nama = '';  
                $get_atas_nama = check_atas_nama($get_atas_nama);
                if(strstr($get_atas_nama, "5")){    
                    $atas_nama = '<b>'.substr($get_atas_nama,2).'</b>';
                }               
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $suratberharga->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$suratberharga->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$suratberharga->KET_LAINNYA.')' ;
                    }
                }
                ////////////////atas nama & pasangan anak//////////////////






                $totalSuratBerharga += str_replace('.', '', $suratberharga->NILAI_PELAPORAN);
                if ($i%2 == 0) {
                    $bgcolor = 'bgcolor="#f9f9f9"';
                }
                else{
                    $bgcolor = '';
                }
                if ($suratberharga->IS_PELEPASAN == '1') {
                    $style_pelepasan = 'style="background-color: rgb(128, 128, 128); color: rgb(255, 255, 255);"';
                }else{
                    $style_pelepasan = null;
                }
                ?>
                <tr <?php echo $bgcolor; ?> <?= $style_pelepasan ?> >
                    <td><?php if($suratberharga->STATUS==3 && $suratberharga->ID_HARTA!=''){ ?>
                        <input class="chk-sb" type="checkbox" onclick="chkSb(this);" value="<?= $suratberharga->ID ?>" name="chk-sb">  <?php }else{ ?> <input type="checkbox" disabled="true" value="" name="chk-sb"> <?php } ?></td>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                            $data_label = array();
                            $data_label[1] = '<label class="label label-primary">Tetap</label>';
                            $data_label[2] = '<label class="label label-success">Ubah</label>';
                            $data_label[3] = '<label class="label label-success">Baru</label>';
                            if ($suratberharga->IS_PELEPASAN == '1') {
                                echo '<label class="label label-danger pelepasan">Lepas</label>';
                            } else {
                                if($suratberharga->STATUS==3 && $suratberharga->ID_HARTA!=''){
                                    echo '<label class="label label-warning">Lama</label>';
                                }else{
                                    echo $data_label[$suratberharga->STATUS];
                                }
                                
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                        //$img = null;
                        //if ($suratberharga->FILE_BUKTI) {
                        //    if (file_exists($suratberharga->FILE_BUKTI)) {
                        //        $img = "  <a target='_blank' href='" . base_url() . '' . $suratberharga->FILE_BUKTI . "'><i class='fa fa-download'></i></a>";
                        //    }
                        //}

						$img = null;
						if ($suratberharga->FILE_BUKTI) {
		
							$filelist = explode(',', $suratberharga->FILE_BUKTI);
							
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
							//if (file_exists($list->FILE_BUKTI)) {
								
							//$img = "<a id='" . $list->ID . "'  href='javascript:void(0)' onclick=\"showDocument('" . base_url() . '' . $list->FILE_BUKTI . "')\" class='files' title='Document'><i class='fa fa-file-text fa-2x'></i></a>";
							
							//}
						}

                        $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->HSB_NAMA . "  " . $img . "</td>
						 </tr>
						  <tr>
						    <td><b>Atas Nama</b></td>
                            <td>:</td>
                            <td>" . $atas_nama . "</td>
						 </tr>
						  <tr>
						    <td><b>Penerbit / Perusahaan</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->NAMA_PENERBIT . "</td>
						 </tr>
						  <tr>
						    <td><b>Custodion / Sekuritas</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->CUSTODIAN . "</td>
						 </tr>
					</table>
				";
                        echo $uraian;
                        ?>


                    </td>

                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php 
								if (strlen($suratberharga->NOMOR_REKENING) >= 32){
									$decrypt_norek = encrypt_username($suratberharga->NOMOR_REKENING,'d');
								} else {
									$decrypt_norek = $suratberharga->NOMOR_REKENING;
								} 
								echo $decrypt_norek ?>
                            </div>
                        </div>
                    </td>

                    <td>

                        <?php
                        $exp = explode(',', $suratberharga->ASAL_USUL);
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
                                Rp. <?php echo number_format($suratberharga->NILAI_PEROLEHAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                NP: Rp. <?php echo number_format($suratberharga->NILAI_PELAPORAN_OLD, 0, '', '.'); ?>/<br>
                                NK: Rp. <?php echo number_format($suratberharga->NILAI_PELAPORAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td class="aksi-hide">
                        <?php
                        $id_ht = '<input type="hidden" class="ID_HARTA_TABLE" value="' . $suratberharga->ID_HARTA . '" />';
                        $id_ht .= '<input type="hidden" class="STATUS_TABLE" value="' . $suratberharga->STATUS . '" />'; 
                        $nilai = 0;
                        $key = $key ? $key : 0;
                        $nilai = $suratberharga->$key;
                        if ($t_lhkpn->IS_COPY == '0') { // KONDISI LAPORAN PERTAMA
                            ?>
                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_surat_berharga/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $suratberharga->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                            <?php
                        } else { // KONDISI KEDUA , KETIGA dst
                            if ($suratberharga->IS_PELEPASAN == '0') { // JIKA BUKAN PELEPASAN
                                if ($suratberharga->STATUS == '1') { // JIKA TETAP
                                //  dump('JIKA TETAP');
                                    ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_surat_berharga/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/t_sb" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                } else { // JIKA BUKAN TETAP & LEPAS
                                    if ($suratberharga->ID_HARTA) { // JIKA DI AMBIL DARI HASIL COPY
                                        //  dump('JIKA DI AMBIL DARI HASIL COPY');
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_surat_berharga/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/t_sb" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                        if ($suratberharga->STATUS == '3') {
                                            ?>
                                            <button type="button" class="btn btn-primary penetapan-action"  id_lhkpn='<?= $suratberharga->ID_LHKPN; ?>' id_harta="<?= $suratberharga->ID  ?>" href="#" title="Tetap"  ><i class="fa fa-link"></i></button>
                                            <?php
                                        }
                                    } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                                //  dump('JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst');
                                        ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_surat_berharga/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $suratberharga->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                                        <?php
                                    }
                                }
                            } else { // JIKA DATA DITETAPKAN
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_surat_berharga/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/edit" title="Undo" onclick="onButton.go(this, 'large', true);"><i class="fa fa-repeat"></i></button>                                       
                                        <?php
                            }
                        }
                        echo $id_ht;
                        ?>


                        <!-- <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $suratberharga->ID; ?>/suratberharga" >Upload</button> -->
                        <!--<button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_harta_surat_berharga/<?php echo $suratberharga->ID_LHKPN; ?>/<?php echo $suratberharga->ID ? $suratberharga->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $suratberharga->ID; ?>/harta_surat_berharga" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>-->
                    </td>
                </tr>
                <?php if ($suratberharga->KET_PEMERIKSAAN != ''): ?>
                    <tr <?php echo $bgcolor; ?>>
                        <td></td>
                        <td></td>
                        <td <?php echo $is_preview ? 'colspan="5"' : 'colspan="6"'; ?>><strong>Keterangan Pemeriksaan: </strong><?php echo $suratberharga->KET_PEMERIKSAAN; ?></td>
                        <td></td>
                    </tr>
                <?php endif ?>
            <?php } }?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td <?php echo $is_preview ? 'colspan="6"' : 'colspan="7"'; ?>><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalSuratBerharga, 0, '', '.'); ?></b></td>
                <td></td>
                <!-- <td></td> -->
            </tr>
        </tfoot>
    </table> 
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#klarifikasiDataSuratBerharga").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Input Klarifikasi Data Harta Surat Berharga', html, null, 'large');
            });            
            return false;
        });
        $("#wrapperSuratBerharga .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Upload Dokumen Pendukung Surat Berharga', html, '', 'standart');
            });
            return false;
        });
    });

      // PENETAPAN ACTION
    $("#TableHartaSuratBerharga tbody").on('click', '.penetapan-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function () {
            $.ajax({
                url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_sb',
                async: false,
                dataType: 'JSON',
                success: function (data) {
                    var rs = eval(data);
                    if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else if (rs.result.NILAI_PELAPORAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else{
                        do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_sb', 'Data Harta Tidak Bergerak Sudah Tetap ');
                        //   $('#TableTanah').DataTable().ajax.reload(); 
                        var url = location.href.split('#')[1];
                        url = url.split('?')[0] + '?upperli=li3&bottomli=lii3';
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
    $("#TableHartaSuratBerharga tbody").on('click', '.delete-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin menghapus data ? ", function () {
            do_delete('eaudit/klarifikasi/soft_delete/' + id + '/harta_surat_berharga', 'Data Harta Bergerak Berhasil dihapus ');
            $("#modal-success").removeClass("modal-lg"); 
            $("#modal-warning").removeClass("modal-lg");
            return false;
            });
        });

    function tetapkan_sb_all(){
        var jml_cek = $("#jml_check").val();
        confirm("Apakah anda yakin akan melakukan penetapan semua harta yang dipilih ? ", function () {
            $('.chk-sb:checkbox:checked').each(function(index){
                var id = $(this).val();
                if($(this).is(':checked')){
                    ajax_sb_penetapan(id, jml_cek, index);
                }
            })
        });
    }

    function ajax_sb_penetapan(id, jml_cek, index){
        $('#loader_area').show();
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_sb',
            async: true,
            dataType: 'JSON',
            success: function (data) {
                var rs = eval(data);
                if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else if (rs.result.NILAI_PELAPORAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else{
                    do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_sb', 'Data Harta Tidak Bergerak Sudah Tetap ');
                   
                    var url = location.href.split('#')[1];
                    url = url.split('?')[0] + '?upperli=li3&bottomli=lii3';
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

    function chk_all_sb(ele){
        if($(ele).is(':checked')){
            $('.chk-sb').prop('checked', true);
        }else{
            $('.chk-sb').prop('checked', false);
        }
        cekTombolSb("chk-sb");
    }

    function chkSb(ele){
        var val = $(ele).val();
        cekTombolSb("chk-sb");
    }

    function cekTombolSb(chkboxName){
        var checkboxes = document.getElementsByName(chkboxName);
            var checkboxesChecked = [];
            for (var i=0; i<checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                      checkboxesChecked.push(checkboxes[i]);
                    }
            }
            
            var jml_chk = checkboxesChecked.length;
            if (jml_chk === 0 ){
                $("#tombolTetapSb").empty();
            } else{
                $("#tombolTetapSb").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"tetapkan_sb_all();\"title=\"Tetap\"><input type=\"hidden\" name=\"jml_check\" id=\"jml_check\"><i class=\"fa fa-plus-square\"></i> Tetapkan Semua</button>");
                $("#jml_check").val(jml_chk);
            }
    }
</script>
