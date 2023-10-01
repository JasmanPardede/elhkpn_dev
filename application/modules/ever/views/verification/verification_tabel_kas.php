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
 * @package Views/ever/verification
 */
?>
<style type="text/css">
    .title-kas
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-kas">
    <h5 class="">"Harta berupa kas atau setara kas (deposito, giro, tabungan, atau lainnya)"</h5>
</div>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataHartaKas" href="index.php/ever/verification_edit/update_harta_kas/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperKas">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="30%">URAIAN</th>
                <th width="30%">ATAS NAMA</th>
                <th width="20%">ASAL USUL HARTA</th>
                <th width="20%">SALDO SAAT PELAPORAN</th>
                <!--<th>HASIL</th>-->
                <th class="aksi">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $no_column = 0;
            $totalKas = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_KASS as $kas) {

                if (($kas->IS_PELEPASAN == 1) || ($kas->NILAI_KURS != 1)) {
                    $totalKas += $kas->NILAI_EQUIVALEN;
                }else{
                    $totalKas += $kas->NILAI_SALDO;
                }

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
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$kas->KETERANGAN.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$kas->KETERANGAN.')' ;
                    }
                }




                ?>

                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->
                <?php
                    if ($kas->IS_PELEPASAN == '1'){ ?>
                        <tr style="background-color:#808080;color:#fff">
                <?php       }else{  ?>
                    <tr >
                <?php       } ?>

                    <td><?php echo ++$no_column; ?>.</td>
                    <td><?php
                        if ($kas->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            if ($kas->STATUS == '1'){
                             echo '<label class=\'label label-primary\'>'.$stat[$kas->STATUS].'</label>';}
                            else{
                            echo '<label class=\'label label-success\'>'.$stat[$kas->STATUS].'</label>';}
                        }
                ?>
                    </td>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->

                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
//                                 $image = null;
//                                 if ($kas->FILE_BUKTI) {
//                                     if (file_exists($kas->FILE_BUKTI)) {
//                                         $image = '<a target="_blank" href="' . base_url() . '' . $kas->FILE_BUKTI . '"><i class="fa fa-download"></i></a>';
//                                     }
//                                 }
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

                                        //get data from minio
                                        $ext = strtolower(end(explode('.',$tmp_name)));
                                        if($ext=='png' || $ext=='jpg' || $ext=='jpeg'){
                                            $classIcon = 'fa-file-image-o';
                                        }else{
                                            $classIcon = 'fa-file-text';
                                        }
                                        $checkFile = linkFromMinio($dir . $tmp_name,null,'t_lhkpn_harta_kas','ID',$kas->ID);
                                        if($checkFile){
                                            $link = $checkFile;
                                            $img = $img . " <a class='files btnShow' href='index.php/ever/verification/display/image/". $kas->ID . '/kas/'. $tmp_name .  "'><i class='fa ".$classIcon." fa-2x'></i></a>";
                                        }else{
                                          if (file_exists($dir . $tmp_name) && $tmp_name!="") {
                                            $tmp_name = $tmp_name;
                                            $img = $img . " <a class='files btnShow' href='index.php/ever/verification/display/image/". $kas->ID . '/kas/'. $tmp_name .  "'><i class='fa ".$classIcon." fa-2x'></i></a>";
                                          }
                                        }
                                    }
                                }

                                if (strlen($kas->NAMA_BANK) >= 32){
                                    $decrypt_namabank = encrypt_username($kas->NAMA_BANK,'d');
                                } else {
                                    $decrypt_namabank = $kas->NAMA_BANK;
                                }
                                $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td width='25%'><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $kas->NAMA . " " . $img .  "</td>
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
                                $exp = explode(',', $kas->ASAL_USUL);
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
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12">
                                <b>  Nilai Saldo</b>
                            </div>
                            <div class="col-md-12" align="right">
                                <?php if ($kas->MATA_UANG == 1) { ?>
                                <p id="PELAPORAN_KAS_SAL_<?php echo $no_column; ?>" class="NILAI_PELAPORAN_KAS_SAL">
                                <?php } ?>
                                    <?php if ($kas->IS_PELEPASAN == 1) { ?>
                                    <?php echo $kas->SIMBOL . ' ' . number_format($kas->NILAI_EQUIVALEN, 0, ",", "."); ?>
                                    <?php } else { ?>
                                    <?php echo $kas->SIMBOL . ' ' . number_format($kas->NILAI_SALDO, 0, ",", "."); ?>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <?php if ($kas->MATA_UANG == 1) { ?>
                            <p name="TERBILANG_PELAPORAN_KAS_SAL_<?php echo $no_column; ?>" id="TERBILANG_PELAPORAN_KAS_SAL_<?php echo $no_column; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                            <?php } ?>
                        </div>
                        <?php if ($kas->MATA_UANG != 1) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <b> Ekuivalen</b>
                            </div>
                            <div class="col-md-12" align="right">
                                <p id="PELAPORAN_KAS_EQ_<?php echo $no_column; ?>" class="NILAI_PELAPORAN_KAS_EQ">
                                    Rp. <?php echo number_format($kas->NILAI_EQUIVALEN, 0, ",", "."); ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <p name="TERBILANG_PELAPORAN_KAS_EQ_<?php echo $no_column; ?>" id="TERBILANG_PELAPORAN_KAS_EQ_<?php echo $no_column; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                        <?php } ?>
                    </td>
                    <td class="aksi">
                        <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $kas->ID; ?>/kas" >Upload</button>
                        <?php if ($viaa == '1'): ?>
                            <button type="button" class="btn btn-primary" href="index.php/ever/verification_edit/update_harta_kas/<?php echo $kas->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $kas->ID; ?>/harta_kas" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php endif ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td>
                    <div class="text-right">
                        <p id="TOTAL_KAS" class="NILAI_TOTAL_KAS">
                            <b>Rp. <?php echo number_format($totalKas, 0, '', '.'); ?></b>
                        </p>
                    </div>
                    <div>
                        <p name="TERBILANG_TOTAL_KAS" id="TERBILANG_TOTAL_KAS" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                    </div>
                </td>
                <td class="aksi"></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#verifEditDataHartaKas").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data Harta Kas', html, null, 'large');
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
        $("#wrapperKas .btnShow").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('File Dokumen Pendukung', html, '', 'large');
            });
            return false;
        });
    });

    showFieldEver('.NILAI_PELAPORAN_KAS_SAL','#TERBILANG_');
    showFieldEver('.NILAI_PELAPORAN_KAS_EQ','#TERBILANG_');
    showFieldEver('.NILAI_TOTAL_KAS','#TERBILANG_');

</script>
