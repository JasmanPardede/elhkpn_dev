<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Harta berupa kas atau setara kas (deposito, giro, tabungan, atau lainnya)"</h5>
</div>
<br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info aksi-hide" id="klarifikasiHartaKas" href="index.php/eaudit/klarifikasi/update_harta_kas/<?php echo $new_id_lhkpn;?>/<?php echo $kas->ID ? $kas->ID : 0 ; ?>/new"><span class="fa fa-plus"></span> Tambah</button><br><br>
<div class="box-body" id="wrapperKas">
<div id="tombolTetapKas"></div><br><br>
    <table class="table borderless" id="TableHartaKas">
        <thead class="table-header">
            <tr>
                <th width="15px"><input type="checkbox" onClick="chk_all_kas(this);" /></th>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="250px">URAIAN</th>
                <th>ATAS NAMA</th>
                <th>ASAL USUL HARTA</th>
                <th width="170px">SALDO SAAT PELAPORAN/ KLARIFIKASI</th>
                <th width="10px" class="aksi-hide">Aksi</th>
            </tr>                 
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalKas = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];     
            foreach ($lhkpn_harta_kas as $kas) {
                ////////////////atas nama & pasangan anak//////////////////
                $get_atas_nama = $kas->ATAS_NAMA_REKENING;
                $atas_nama = '';  
                $get_atas_nama = check_atas_nama($get_atas_nama);
                if(strstr($get_atas_nama, "5")){    
                    $atas_nama = '<b>'.substr($get_atas_nama,2).'</b>';
                }               
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $kas->PASANGAN_ANAK);
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
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$kas->ATAS_NAMA_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$kas->ATAS_NAMA_LAINNYA.')' ;
                    }
                }
                ////////////////atas nama & pasangan anak//////////////////

                if ($i%2 == 0) {
                    $bgcolor = 'bgcolor="#f9f9f9"';
                }
                else{
                    $bgcolor = '';
                }
                if ($kas->IS_PELEPASAN == '1') {
                    $style_pelepasan = 'style="background-color: rgb(128, 128, 128); color: rgb(255, 255, 255);"';
                }else{
                    $style_pelepasan = null;
                }

                // if(($kas->MATA_UANG != 1) && ($kas->IS_PELEPASAN != '1')){
                //     $totalKas += $kas->NILAI_EQUIVALEN_OLD;
                // }else{
                    $totalKas += $kas->NILAI_EQUIVALEN;
                // }

                ?>
                <tr <?php echo $bgcolor; ?> <?= $style_pelepasan ?>  >
                    <td><?php if($kas->STATUS==3 && $kas->ID_HARTA!=''){ ?>
                        <input class="chk-kas" type="checkbox" onclick="chkKas(this);" value="<?= $kas->ID ?>" name="chk-kas">  <?php }else{ ?> <input type="checkbox" disabled="true" value="" name="chk-kas"> <?php } ?></td>
                    <td><?php echo ++$i ?>.</td>
                    <td><?php 
                        $data_label = array();
                            $data_label[1] = '<label class="label label-primary">Tetap</label>';
                            $data_label[2] = '<label class="label label-success">Ubah</label>';
                            $data_label[3] = '<label class="label label-success">Baru</label>';
                            if ($kas->IS_PELEPASAN == '1') {
                                echo '<label class="label label-danger pelepasan">Lepas</label>';
                            } else {
                                if($kas->STATUS==3 && $kas->ID_HARTA!=''){
                                    echo '<label class="label label-warning">Lama</label>';
                                }else{
                                    echo $data_label[$kas->STATUS];
                                }
                                
                            }
                        ?>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                $img = null;
							if ($kas->FILE_BUKTI) {
		
							$filelist = explode(',', $kas->FILE_BUKTI);
							
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
                                $decrypt_namabank = (strlen($kas->NAMA_BANK) >= 32) ? encrypt_username($kas->NAMA_BANK,'d') : $kas->NAMA_BANK;
                                $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td width='25%'><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $kas->HKAS_NAMA . " " . $img . "</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Keterangan</b></td>
                            <td>:</td>
                              <td>-</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Nama Bank / Lembaga</b></td>
                            <td>:</td>
                           <td>" . $decrypt_namabank . "</td>
						 </tr>
					</table>
				";


                                echo $uraian;
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
								if (strlen($kas->NOMOR_REKENING) >= 32){
									$decrypt_norek = encrypt_username($kas->NOMOR_REKENING,'d');
								} else {
									$decrypt_norek = $kas->NOMOR_REKENING;
								} 
                                $info_rekening = "
					<table class='table-child table-condensed'>
						<tr>
						    <td width='25%'><b>Nomor</b></td>
                            <td>:</td>
                            <td>" . $decrypt_norek . "</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Atas Nama</b></td>
                            <td>:</td>
                            <td>" . $atas_nama . "</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $kas->KETERANGAN . "</td>
						 </tr>
					</table>
				";

                                echo $info_rekening;
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                switch ($kas->ASAL_USUL) {
                                    case '1' : echo 'Hasil Sendiri';
                                        break;
                                    case '2' : echo 'Warisan';
                                        break;
                                    case '3' : echo 'Hibah';
                                        break;
                                    case '4' : echo 'Hadiah';
                                        break;
                                };
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12">
                                <b>  Nilai Saldo</b>
                            </div>
                            <div class="col-md-12" align="right">
                                <?php // if ($kas->IS_PELEPASAN == 1) { ?>
                                <?php // echo $kas->SIMBOL . ' ' . number_format($kas->NILAI_EQUIVALEN, 0, ",", "."); ?>
                                <?php // } else { ?>
                                <?php echo 'Rp. ' . number_format($kas->NILAI_EQUIVALEN_OLD, 0, ",", "."); ?>
                                <?php // } ?>
                            </div>
                        </div>
                        <?php if ($kas->MATA_UANG != 1) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <b> Ekuivalen</b>
                                </div>
                                <div class="col-md-12" align="right">
                                    Rp. <?php echo ' '.number_format($kas->NILAI_EQUIVALEN, 0, ",", "."); ?>
                                </div>
                            </div>
                        <?php  } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <b>  Nilai Klarifikasi</b>
                            </div>
                            <div class="col-md-12" align="right">
                                <?= $kas->SIMBOL . ' ' .number_format($kas->NILAI_SALDO, 0, ",", "."); ?>
                            </div>
                        </div>
                    </td>
                    <td class="aksi-hide">
                        <?php
                        $id_ht = '<input type="hidden" class="ID_HARTA_TABLE" value="' . $kas->ID_HARTA . '" />';
                        $id_ht .= '<input type="hidden" class="STATUS_TABLE" value="' . $kas->STATUS . '" />'; 
                        $nilai = 0;
                        $key = $key ? $key : 0;
                        $nilai = $kas->$key;
                        if ($t_lhkpn->IS_COPY == '0') { // KONDISI LAPORAN PERTAMA
                            ?>
                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_kas/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID ? $kas->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $kas->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                            <?php
                        } else { // KONDISI KEDUA , KETIGA dst
                            if ($kas->IS_PELEPASAN == '0') { // JIKA BUKAN PELEPASAN
                                if ($kas->STATUS == '1') { // JIKA TETAP
                                //  dump('JIKA TETAP');
                                    ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_kas/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID ? $kas->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID ? $kas->ID : 0; ?>/t_kas" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                } else { // JIKA BUKAN TETAP & LEPAS
                                    if ($kas->ID_HARTA) { // JIKA DI AMBIL DARI HASIL COPY
                                        //  dump('JIKA DI AMBIL DARI HASIL COPY');
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_kas/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID ? $kas->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID ? $kas->ID : 0; ?>/t_kas" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                        if ($kas->STATUS == '3') {
                                            ?>
                                            <button type="button" class="btn btn-primary penetapan-action"  id_lhkpn='<?= $kas->ID_LHKPN; ?>' id_harta="<?= $kas->ID  ?>" href="#" title="Tetap"  ><i class="fa fa-link"></i></button>
                                            <?php
                                        }
                                    } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                                //  dump('JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst');
                                        ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_kas/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID ? $kas->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $kas->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                                        <?php
                                    }
                                }
                            } else { // JIKA DATA DITETAPKAN
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_kas/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID ? $kas->ID : 0; ?>/edit" title="Undo" onclick="onButton.go(this, 'large', true);"><i class="fa fa-repeat"></i></button>                                       
                                        <?php
                            }
                        }
                        echo $id_ht;
                        ?>

                        <!-- <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $kas->ID; ?>/kas" >Upload</button> -->
                        <!--<button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_harta_kas/<?php echo $kas->ID_LHKPN; ?>/<?php echo $kas->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $kas->ID; ?>/harta_kas" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>-->
                    </td>
                </tr>
                <?php if ($kas->KET_PEMERIKSAAN != ''): ?>
                    <tr <?php echo $bgcolor; ?>>
                        <td></td>
                        <td></td>
                        <td <?php echo $is_preview ? 'colspan="5"' : 'colspan="6"'; ?>><strong>Keterangan Pemeriksaan: </strong><?php echo $kas->KET_PEMERIKSAAN; ?></td>
                        <!-- <td></td> -->
                    </tr>
                <?php endif ?>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td <?php echo $is_preview ? 'colspan="6"' : 'colspan="7"'; ?>><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalKas, 0, '', '.'); ?></b></td>
                <!-- <td></td>
                <td></td> -->
            </tr>
        </tfoot>
    </table>
</div>		    
<script type="text/javascript">
    $(document).ready(function() {
        $("#klarifikasiHartaKas").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Input Klarifikasi Data Harta Kas', html, null, 'large');
            });            
            return false;
        });
        $("#wrapperKas .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data KAS', html, '', 'standart');
            });
            return false;
        });
    });

    // PENETAPAN ACTION
    $("#TableHartaKas tbody").on('click', '.penetapan-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function () {
            $.ajax({
                url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_kas',
                async: false,
                dataType: 'JSON',
                success: function (data) {
                    var rs = eval(data);

                    ///// KODE_JENIS = 18  ==>  UANG TUNAI /////
                    if(rs.result.KODE_JENIS != 18 && (rs.result.TAHUN_BUKA_REKENING == null || rs.result.TAHUN_BUKA_REKENING == '-')){
                        $("#modal-warning").removeClass("modal-lg"); 
                        notif('Data Harta KAS / Setara KAS gagal dimutakhirkan dengan status TETAP, dikarenakan tahun buka rekening masih kosong, silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else{
                        do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_kas', 'Data Harta Tidak Bergerak Sudah Tetap ');
                        //   $('#TableTanah').DataTable().ajax.reload(); 
                        var url = location.href.split('#')[1];
                        url = url.split('?')[0] + '?upperli=li3&bottomli=lii4';
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
    $("#TableHartaKas tbody").on('click', '.delete-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin menghapus data ? ", function () {
            do_delete('eaudit/klarifikasi/soft_delete/' + id + '/harta_kas', 'Data Harta Kas Berhasil dihapus ');
            $("#modal-success").removeClass("modal-lg"); 
            $("#modal-warning").removeClass("modal-lg"); 
            return false;
                
            });
        });
    
    function tetapkan_kas_all(){
        var jml_cek = $("#jml_check").val();
        confirm("Apakah anda yakin akan melakukan penetapan semua harta yang dipilih ? ", function () {
            $('.chk-kas:checkbox:checked').each(function(index){
                var id = $(this).val();
                if($(this).is(':checked')){
                    ajax_kas_penetapan(id, jml_cek, index);
                }
            })
        });
    }

    function ajax_kas_penetapan(id, jml_cek, index){
        $('#loader_area').show();
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_kas',
            async: true,
            dataType: 'JSON',
            success: function (data) {
                var rs = eval(data);

                var url = location.href.split('#')[1];
                url = url.split('?')[0] + '?upperli=li3&bottomli=lii4';
                window.location.hash = url;

                ///// KODE_JENIS = 18  ==>  UANG TUNAI /////
                if(rs.result.KODE_JENIS != 18 && (rs.result.TAHUN_BUKA_REKENING == null || rs.result.TAHUN_BUKA_REKENING == '-')){
                    $('#loader_area').hide();
                    $("#modal-warning").removeClass("modal-lg"); 
                    notif('Data Harta KAS / Setara KAS gagal dimutakhirkan dengan status TETAP, dikarenakan tahun buka rekening masih kosong, silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA'); 

                    $(".btn-danger").click(function(){
                        $('#loader_area').show();
                        check_reload_tetap_all(jml_cek, url, index);
                    });
                }else{
                    do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_kas', 'Data Harta Tidak Bergerak Sudah Tetap ');

                    check_reload_tetap_all(jml_cek, url, index)
                }
            }
        });
    }

    function chk_all_kas(ele){
        if($(ele).is(':checked')){
            $('.chk-kas').prop('checked', true);
        }else{
            $('.chk-kas').prop('checked', false);
        }
        cekTombolKas("chk-kas");
    }

    function chkKas(ele){
        var val = $(ele).val();
        cekTombolKas("chk-kas");
    }

    function cekTombolKas(chkboxName){
        var checkboxes = document.getElementsByName(chkboxName);
            var checkboxesChecked = [];
            for (var i=0; i<checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                      checkboxesChecked.push(checkboxes[i]);
                    }
            }
            
            var jml_chk = checkboxesChecked.length;
            if (jml_chk === 0 ){
                $("#tombolTetapKas").empty();
            } else{
                $("#tombolTetapKas").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"tetapkan_kas_all();\"title=\"Tetap\"><input type=\"hidden\" name=\"jml_check\" id=\"jml_check\"><i class=\"fa fa-plus-square\"></i> Tetapkan Semua</button>");
                $("#jml_check").val(jml_chk);
            }
    }

    function check_reload_tetap_all(jml_cek, url, index){
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
    }

</script>
