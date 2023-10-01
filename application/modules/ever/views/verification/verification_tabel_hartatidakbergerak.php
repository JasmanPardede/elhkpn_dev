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
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Harta Tidak Bergerak berupa Tanah dan/atau bangunan"</h5>
</div>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataHTB" href="index.php/ever/verification_edit/update_htb/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperHartaTidakBergerak">
    <table class="table table-bordered table-hover table-striped" >
        <thead class="table-header">
            <tr >
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="30%">LOKASI/ALAMAT LENGKAP</th>
                <th width="10%">LUAS</th>
                <th width="20%">KEPEMILIKAN</th>
                <th width="20%">NILAI PEROLEHAN</th>
                <th width="20%">NILAI PELAPORAN</th>
                <?php if ($viaa == '1'): ?>
                    <th>AKSI</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $asal_usul = array('', 'PN YANG BERSANGKUTAN', 'PASANGAN / ANAK', 'LAINNYA');
            $i = 0;
            $totalHartaTidakBergerak = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_TIDAK_BERGERAKS as $hartatidakbergerak) {
                $totalHartaTidakBergerak += $hartatidakbergerak->NILAI_PELAPORAN;



                
                $get_atas_nama = $hartatidakbergerak->ATAS_NAMA;
                $atas_nama = '';  
                $get_atas_nama = check_atas_nama($get_atas_nama);
                if(strstr($get_atas_nama, "5")){    
                    $atas_nama = '<b>'.substr($get_atas_nama,2).'</b>';
                }               
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $hartatidakbergerak->PASANGAN_ANAK);
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
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$hartatidakbergerak->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$hartatidakbergerak->KET_LAINNYA.')' ;
                    }
                }
                



                ?>
                
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->               
                <?php 
                    if ($hartatidakbergerak->IS_PELEPASAN == '1'){ ?>
                        <tr style="background-color:#808080;color:#fff">
                <?php       }else{  ?>
                    <tr >
                <?php       } ?>

                    <td><?php echo ++$i; ?>.</td>
                    <td><?php
                        if ($hartatidakbergerak->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            if ($hartatidakbergerak->STATUS == '1'){
                             echo '<label class=\'label label-primary\'>'.$stat[$hartatidakbergerak->STATUS].'</label>';}
                            else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartatidakbergerak->STATUS].'</label>';}
                        }
                        ?>
                    </td>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->   
                    <td>
                        <?php
                        if ($hartatidakbergerak->ID_NEGARA == '2') {
                            echo '<b>Jalan : </b>' . $hartatidakbergerak->JALAN . '<br/> <b>Negara : </b> ' . $hartatidakbergerak->NAMA_NEGARA;
                        } else if ($hartatidakbergerak->ID_NEGARA == '1') {
                            echo '<b>Jalan/ No : </b>' . $hartatidakbergerak->JALAN . '<br/> <b>Kelurahan : </b> ' .
                            $hartatidakbergerak->KEL . '<br/> <b>Kecamatan : </b> ' .
                            $hartatidakbergerak->KEC . '<br/> <b>Kabupaten/Kota : </b> ' .
                            $hartatidakbergerak->KAB_KOT . '<br/> <b>Provinsi : </b> ' . $hartatidakbergerak->PROV;
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo '<b>Tanah </b>: ' . number_format($hartatidakbergerak->LUAS_TANAH, 0, '', '.'); ?> M<sup>2</sup>
                        <?php echo '<br/><b>Bangunan </b>: ' . number_format($hartatidakbergerak->LUAS_BANGUNAN, 0, '', '.'); ?> M<sup>2</sup>
                    </td>
                    <td>
                        <b>Jenis Bukti : </b><?php echo $list_bukti[$hartatidakbergerak->JENIS_BUKTI] ?> <br> <b>Nomor :  </b><?php echo $hartatidakbergerak->NOMOR_BUKTI ?> <br>
                        <b>A.n : </b> <?php echo $atas_nama; ?><br>
                        <b>Asal Usul Harta : </b>
                        <?php
                        $exp = explode(',', $hartatidakbergerak->ASAL_USUL);
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
                        <br>
                        <b>Pemanfaatan : </b>
                        <?php
                        $expm = explode(',', $hartatidakbergerak->PEMANFAATAN);
                        $text1 = '';
                        foreach ($expm as $key) {
                            $text1 .= $pemanfaatan1[$key] . '&nbsp;,&nbsp;&nbsp;';
                        }
                        $rinci1 = substr($text1, 0, -19);
                        echo $rinci1;
                        ?>
                        <br>
                        <b>Tahun Perolehan : </b>
                        <?php
                        echo $hartatidakbergerak->TAHUN_PEROLEHAN_AWAL;
                        ?>
                    </td>

                    <td>
                        <!--  <div>

                         </div> -->
                        <div align="right">
                            <p id="PEROLEHAN_HTB_<?php echo $i; ?>" class="NILAI_PEROLEHAN_HTB">
                                <?= $hartatidakbergerak->SIMBOL ?> <?php echo number_format($hartatidakbergerak->NILAI_PEROLEHAN, 0, '', '.'); ?>
                            </p>
                        </div>
                        <div>
                            <p name="TERBILANG_PEROLEHAN_HTB_<?php echo $i; ?>" id="TERBILANG_PEROLEHAN_HTB_<?php echo $i; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                            <!--lime = 73C6B6-->
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            <p id="PELAPORAN_HTB_<?php echo $i; ?>" class="NILAI_PELAPORAN_HTB">
                                Rp. <?php echo number_format($hartatidakbergerak->NILAI_PELAPORAN, 0, '', '.'); ?>
                            </p>
                        </div>
                        <div>
                            <p name="TERBILANG_PELAPORAN_HTB_<?php echo $i; ?>" id="TERBILANG_PELAPORAN_HTB_<?php echo $i; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <?php if ($viaa == '1'): ?>
                    <td align="center">
                        <button type="button" id="verifEditDataHTBEdit" class="btn btn-primary" href="index.php/ever/verification_edit/update_htb/<?php echo $hartatidakbergerak->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $hartatidakbergerak->ID; ?>/htb" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                    </td>
                    <?php endif ?>

                </tr>
            <?php } ?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td colspan="6"><b>Sub Total/Total</b></td>
                <td>
                    <div class="text-right">
                        <p id="TOTAL_HTB" class="NILAI_TOTAL_HTB">
                            <b>Rp. <?php echo number_format($totalHartaTidakBergerak, 0, '', '.'); ?></b>
                        </p>
                    </div>
                    <div>
                        <p name="TERBILANG_TOTAL_HTB" id="TERBILANG_TOTAL_HTB" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                    </div>
                </td>
                <?php if ($viaa == '1'): ?>
                    <td></td>
                <?php endif ?>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#verifEditDataHTB").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data HTB', html, null, 'large');
            });
            return false;
        });
    });

    showFieldEver('.NILAI_PEROLEHAN_HTB','#TERBILANG_');
    showFieldEver('.NILAI_PELAPORAN_HTB','#TERBILANG_');
    showFieldEver('.NILAI_TOTAL_HTB','#TERBILANG_');
    
    function showFieldEver(id1, id2) {
        $(id1).each(function () {
            var id = $(this).attr('id');
            var state_perolehan = $.trim($(this).text());
            var convert_perolehan = state_perolehan.replace(/[^0-9]/g, "");
            var string_nominal = terbilang(convert_perolehan);
            $(id2 + id).text(string_nominal);
        });
    }

    function terbilang(bilangan) {

        bilangan    = String(bilangan);
        var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
        var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
        var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

        var panjang_bilangan = bilangan.length;

        /* pengujian panjang bilangan */
        if (panjang_bilangan == 1 && bilangan == 0) {
            kaLimat = "Nol Rupiah";
            return kaLimat;
        }
        if (panjang_bilangan > 15) {
            kaLimat = "Diluar Batas";
            return kaLimat;
        }

        /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
        for (i = 1; i <= panjang_bilangan; i++) {
            angka[i] = bilangan.substr(-(i),1);
        }

        i = 1;
        j = 0;
        kaLimat = "";


        /* mulai proses iterasi terhadap array angka */
        while (i <= panjang_bilangan) {

            subkaLimat = "";
            kata1 = "";
            kata2 = "";
            kata3 = "";

            /* untuk Ratusan */
            if (angka[i+2] != "0") {
                if (angka[i+2] == "1") {
                    kata1 = "Seratus";
                } else {
                    kata1 = kata[angka[i+2]] + " Ratus";
                }
            }

            /* untuk Puluhan atau Belasan */
            if (angka[i+1] != "0") {
                if (angka[i+1] == "1") {
                    if (angka[i] == "0") {
                        kata2 = "Sepuluh";
                    } else if (angka[i] == "1") {
                        kata2 = "Sebelas";
                    } else {
                        kata2 = kata[angka[i]] + " Belas";
                    }
                } else {
                    kata2 = kata[angka[i+1]] + " Puluh";
                }
            }

            /* untuk Satuan */
            if (angka[i] != "0") {
                if (angka[i+1] != "1") {
                    kata3 = kata[angka[i]];
                }
            }

            /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
            if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
                subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
            }

            /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
            kaLimat = subkaLimat + kaLimat;
            i = i + 3;
            j = j + 1;

        }

        /* mengganti Satu Ribu jadi Seribu jika diperlukan */
        if ((angka[5] == "0") && (angka[6] == "0")) {
            kaLimat = kaLimat.replace("Satu Ribu","Seribu");
        }

        return kaLimat + "Rupiah";
    }
</script>
