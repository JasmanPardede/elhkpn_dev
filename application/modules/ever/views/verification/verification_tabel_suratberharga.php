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
 * @update - PT.WADITRA REKA CIPTA BANDUNG
 * @package Views/ever/verification
 */
?>
<style type="text/css">
    .title-surat-berharga
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-surat-berharga">
    <h5 class="">"Harta berupa Surat Berharga, contoh penyertaan modal saham dan investasi"</h5>
</div>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataHartaSuratBerharga" href="index.php/ever/verification_edit/update_harta_surat_berharga/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperSuratBerharga">
    <table class="table table-bordered table-hover table-striped table-custom">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="30%">URAIAN</th>
                <th width="10%">NO REKENING/ NO NASABAH</th>
                <th width="20%">ASAL USUL HARTA</th>
                <th width="20%">NILAI PEROLEHAN</th>
                <th width="20%">NILAI PELAPORAN</th>
                <!--<th>HASIL</th>-->
                <!--<th width="10px">Aksi</th>-->
                <th width="10px" class='aksi'>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $no_column = 0;
            $totalSuratBerharga = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_SURAT_BERHARGAS as $suratberharga) {
                $totalSuratBerharga += str_replace('.', '', $suratberharga->NILAI_PELAPORAN);



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



                ?>

               <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->
                <?php
                    if ($suratberharga->IS_PELEPASAN == '1'){ ?>
                        <tr style="background-color:#808080;color:#fff">
                <?php       }else{  ?>
                    <tr >
                <?php       } ?>

                    <td><?php echo ++$no_column; ?>.</td>
                    <td><?php
                        if ($suratberharga->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            if ($suratberharga->STATUS == '1'){
                             echo '<label class=\'label label-primary\'>'.$stat[$suratberharga->STATUS].'</label>';}
                            else{
                            echo '<label class=\'label label-success\'>'.$stat[$suratberharga->STATUS].'</label>';}
                        }
                        ?>
                    </td>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->

                    <td>
                        <?php
//                         $img = null;
//                         if ($suratberharga->FILE_BUKTI) {
//                             if (file_exists($suratberharga->FILE_BUKTI)) {
//                                 $img = "  <a target='_blank' href='" . base_url() . '' . $suratberharga->FILE_BUKTI . "'><i class='fa fa-download'></i></a>";
//                             }
//                         }

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


                                  //get data from minio
                                  $ext = strtolower(end(explode('.',$tmp_name)));
                                  if($ext=='png' || $ext=='jpg' || $ext=='jpeg'){
                                      $classIcon = 'fa-file-image-o';
                                  }else{
                                      $classIcon = 'fa-file-text';
                                  }
                                  $checkFile = linkFromMinio($dir . $tmp_name,null,'t_lhkpn_harta_surat_berharga','ID',$suratberharga->ID);
                                  if($checkFile){
                                      $link = $checkFile;
                                      $img = $img . " <a class='files btnShow' href='index.php/ever/verification/display/image/". $suratberharga->ID . '/suratberharga/'. $tmp_name .  "'><i class='fa ".$classIcon." fa-2x'></i></a>";
                                  }else{
                                    if (file_exists($dir . $tmp_name) && $tmp_name!="") {
                                      $tmp_name = $tmp_name;
                                      $img = $img . " <a class='files btnShow' href='index.php/ever/verification/display/image/". $suratberharga->ID . '/suratberharga/'. $tmp_name .  "'><i class='fa ".$classIcon." fa-2x'></i></a>";
                                    }
                                  }
                            }
                        }

                        $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->NAMA . "  " . $img .  "</td>
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
                         <tr>
						    <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->TAHUN_PEROLEHAN_AWAL . "</td>
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
                                echo $decrypt_norek;
                                ?>
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
                        <!-- <div class="row">
                            <div class="col-md-12">

                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <p id="PEROLEHAN_SB_<?php echo $no_column; ?>" class="NILAI_PEROLEHAN_SB">
                                    <?php echo $suratberharga->SIMBOL . ' ' . number_format($suratberharga->NILAI_PEROLEHAN, 0, '', '.'); ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <p name="TERBILANG_PEROLEHAN_SB_<?php echo $no_column; ?>" id="TERBILANG_PEROLEHAN_SB_<?php echo $no_column; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                <p id="PELAPORAN_SB_<?php echo $no_column; ?>" class="NILAI_PELAPORAN_SB">
                                    Rp. <?php echo number_format($suratberharga->NILAI_PELAPORAN, 0, '', '.'); ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <p name="TERBILANG_PELAPORAN_SB_<?php echo $no_column; ?>" id="TERBILANG_PELAPORAN_SB_<?php echo $no_column; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <!--<td align="center">-->
                        <?php
//                        $disable_cek = '';
//                        if (@$hasilVerifikasi['suratberharga']['hasil'][$suratberharga->ID] == 1) {
//                            echo '<i class="fa fa-check-square" style="cursor: pointer; color: blue;" title="' . (@$hasilVerifikasi['suratberharga']['catatan'][$suratberharga->ID]) . '"></i>';
//                            $disable_cek = (@$hasilVerifikasi['suratberharga']['editable'][$suratberharga->ID] == '0' ? 'disabled' : '');
//                        } else if (@$hasilVerifikasi['suratberharga']['hasil'][$suratberharga->ID] == -1) {
//                            $survey = 'no';
//                            echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;" title="' . (@$hasilVerifikasi['suratberharga']['catatan'][$suratberharga->ID]) . '"></i>';
//                        } else {
//
//                        }
                        ?>
                    <!--</td>-->

<!--                    <td>
                        <?php
//                        if ($disable_cek != '') {
                            ?>
                            <button type="button" class="btn btn-sm btn-success" disabled>Cek</button>
                            <?php
//                        } else {
                            ?>
                            <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/veritem/<?php echo $suratberharga->ID; ?>/suratberharga" >Cek</button>
                            <?php
//                        }
                        ?>
                    </td>                 -->
                    <td class='aksi'>
                        <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $suratberharga->ID; ?>/suratberharga" >Upload</button>
                        <?php if ($viaa == '1'): ?>
                            <button type="button" class="btn btn-primary" href="index.php/ever/verification_edit/update_harta_surat_berharga/<?php echo $suratberharga->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $suratberharga->ID; ?>/harta_surat_berharga" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php endif ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="6"><b>Sub Total/Total</b></td>
                <td>
                    <div class="text-right">
                        <p id="TOTAL_SB" class="NILAI_TOTAL_SB">
                            <b>Rp. <?php echo number_format($totalSuratBerharga, 0, '', '.'); ?></b>
                        </p>
                    </div>
                    <div>
                        <p name="TERBILANG_TOTAL_SB" id="TERBILANG_TOTAL_SB" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                    </div>
                </td>
                <td class='aksi'></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#verifEditDataHartaSuratBerharga").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data Harta Surat Berharga', html, null, 'large');
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
        $("#wrapperSuratBerharga .btnShow").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('File Dokumen Pendukung', html, '', 'large');
            });
            return false;
        });
    });

    showFieldEver('.NILAI_PEROLEHAN_SB','#TERBILANG_');
    showFieldEver('.NILAI_PELAPORAN_SB','#TERBILANG_');
    showFieldEver('.NILAI_TOTAL_SB','#TERBILANG_');

</script>
