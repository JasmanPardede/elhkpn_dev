<?php
$BIDANG = NUll;
$LEMBAGA = NULL;
$JABATAN = NULL;
$pemanfaatan = ['1' => 'TEMPAT TINGGAL', '2' => 'DISEWAKAN', '3' => 'PERTANIAN / PERKEBUNAN / PERIKANAN / PERTAMBANGAN', '4' => 'LAINNYA'];
$asalusul = ['1' => 'HASIL SENDIRI', '2' => 'WARISAN', '3' => 'HIBAH DENGAN AKTA', '4' => 'HIBAH TANPA AKTA', '5' => 'HADIAH', '6' => 'LAINNYA'];
//echo count($data['JABATAN'], COUNT_RECURSIVE).'<br/><pre>';
//print_r($data['JABATAN'][0]->BDG_NAMA);
$jb = $data['JABATAN'];
if ($data['JABATAN']) {
    if (count($data['JABATAN']) == '1') {
        $BIDANG = $jb[0]->BDG_NAMA;
        $LEMBAGA = $jb[0]->INST_NAMA;
        $JABATAN = $jb[0]->DESKRIPSI_JABATAN;
    } else {
        foreach ($data['JABATAN'] as $jb) {
            if ($jb->IS_PRIMARY == "1") {
                $BIDANG = $jb->BDG_NAMA;
                $LEMBAGA = $jb->INST_NAMA;
                $JABATAN = $jb->DESKRIPSI_JABATAN;
                //break;
            }
        }
    }
}



$HUBUNGAN_KELUARGA = array();
$HUBUNGAN_KELUARGA[1] = 'ISTRI';
$HUBUNGAN_KELUARGA[2] = 'SUAMI';
$HUBUNGAN_KELUARGA[3] = 'ANAK TANGGUNGAN';
$HUBUNGAN_KELUARGA[4] = 'ANAK BUKAN TANGGUNGAN';
$HUBUNGAN_KELUARGA[5] = 'LAINNYA';
$HUBUNGAN_KELUARGA[''] = '-';

$STATUS_HARTA = array();
$STATUS_HARTA[1] = 'TETAP';
$STATUS_HARTA[2] = 'UBAH';
$STATUS_HARTA[3] = '-';
$STATUS_HARTA[4] = 'LAPOR';
$STATUS_HARTA[''] = '-';

$ATAS_NAMA = array();
$ATAS_NAMA[1] = 'PN YANG BERSANGKUTAN';
$ATAS_NAMA[2] = 'PASANGAN / ANAK';
$ATAS_NAMA[3] = 'LAINNYA';
$ATAS_NAMA[''] = '';


$FIRST = TRUE;
if ($data['LHKPN']->IS_COPY == '1') {
    $FIRST = FALSE;
    $STATUS_HARTA[3] = 'BARU';
}
?>

<div>
    <div>
        <?php
        /*
         * Top Page
         */
        ?>
        <div style="text-align:center; margin-bottom:10px;">
            <p style="font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
            <h2 style="font-family:arial;">IKHTISAR LHKPN <?php echo $data['PRIBADI']->NAMA_LENGKAP . ' ' . $data['PRIBADI']->NIK; ?></h2>
            </p>
        </div>
        <?php
        /*
         * data pribadi
         */
        ?>
        <div>
            <table width="100%">
                <tr>
                    <td colspan="3" style="padding-bottom:10px;">
                        <b style="font-family:arial;">1. DATA PRIBADI</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:10%; font-family:arial;">Nama</td>
                    <td style="width:1%; font-family:arial;">:</td>
                    <td style="font-family:arial;"><?php echo $data['PRIBADI']->NAMA_LENGKAP; ?></td>
                </tr>
                <tr>
                    <td style="width:10%; font-family:arial;">NHK</td>
                    <td style="width:1%; font-family:arial;">:</td>
                    <td style="font-family:arial;"><?php echo $data['PRIBADI']->NHK; ?></td>
                </tr>
                <tr>
                    <td style="width:10%; font-family:arial;">Bidang</td>
                    <td style="width:1%; font-family:arial;">:</td>
                    <td style="font-family:arial;"><?php echo $BIDANG; ?></td>
                </tr>
                <tr>
                    <td style="width:10%; font-family:arial;">Lembaga</td>
                    <td style="width:1%; font-family:arial;">:</td>
                    <td style="font-family:arial;" ><?php echo $LEMBAGA; ?></td>
                </tr>
                <tr>
                    <td style="width:10%; font-family:arial;">Jabatan</td>
                    <td style="width:1%; font-family:arial;">:</td>
                    <td style="font-family:arial;" ><?php echo $JABATAN; ?></td>
                </tr>
                <tr>
                    <td style="width:10%; font-family:arial;">Tanggal Lapor</td>
                    <td style="width:1%; font-family:arial;">:</td>
                    <td style="font-family:arial;" ><?php echo tgl_format($data['LHKPN']->tgl_lapor); ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        /*
         * End data pribadi
         */
        ?>
        <div style="margin-top:15px;">
            <table>
                <tr>
                    <td colspan="6" style="padding-bottom:10px;">
                        <b >2. DATA KELUARGA LAINNYA</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" >NO</td>
                    <td width="19%" >NAMA</td>
                    <td width="19%" >HUBUNGAN DENGAN PN</td>
                    <td width="27%" >TEMPAT DAN TANGGA LAHIR/JENIS KELAMIN</td>
                    <td width="15%" >PEKERJAAN</td>
                    <td width="16%" >ALAMAT RUMAH</td>
                </tr>
                <?php if ($data['KELUARGA']): ?>
                    <?php $i = 1;
                    foreach ($data['KELUARGA'] as $fam): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td style="font-family:arial; "><?php echo $fam->NAMA; ?></td>
                            <td style="font-family:arial; "><?php echo $HUBUNGAN_KELUARGA[$fam->HUBUNGAN]; ?></td>
                            <td style="font-family:arial; ">
                                <?php
                                $tgl_lahir = null;
                                if ($fam->TANGGAL_LAHIR) {
                                    $tgl_lahir = tgl_format($fam->TANGGAL_LAHIR);
                                }
                                echo $fam->TEMPAT_LAHIR . ' , ' . $tgl_lahir . ' / ' . $fam->JENIS_KELAMIN;
                                ?>
                            </td>
                            <td style="font-family:arial; "><?php echo $fam->PEKERJAAN; ?></td>
                            <td style="font-family:arial; "><?php echo $fam->ALAMAT_RUMAH; ?></td>
                        </tr>
                        <?php $i++;
                    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="6" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>		
            </table>
        </div>
        <!--<div style="margin-top:15px;">-->
            
            <table>
                <tr>
                    <td colspan="5" style="padding-bottom:10px;">
                        <b style="font-family:arial; ">3. JABATAN</b>
                    </td>
                </tr>
            </table>
            
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="29%" style="font-family:arial; ">JABATAN - DESKRIPSI JABATAN / ESELON</td>
                    <td width="23%" style="font-family:arial; ">LEMBAGA</td>
                    <td width="24%" style="font-family:arial; ">UNIT KERJA</td>
                    <td width="20%" style="font-family:arial; ">SUB UNIT KERJA</td>
                </tr>
                <?php if ($data['JABATAN']): ?>
    <?php $i = 1;
    foreach ($data['JABATAN'] as $jbt): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td style="font-family:arial; "><?php echo $jbt->NAMA_JABATAN; ?></td>
                            <td style="font-family:arial; "><?php echo $jbt->INST_NAMA; ?></td>
                            <td style="font-family:arial; "><?php echo $jbt->UK_NAMA; ?></td>
                            <td style="font-family:arial; "><?php echo $jbt->SUK_NAMA; ?></td>
                        </tr>
        <?php $i++;
    EndForeach; ?>
                    <?php Else: ?>
                    <tr>
                        <td colspan="5" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>
            </table>
        </div>
<!--        <div style="margin-top:15px;">-->
            <table>
                <tr>
                    <td style="padding-bottom:5px;">
                        <b style="font-family:arial; ">4. DATA HARTA</b>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td style="padding-bottom:5px;">
                        <b style="font-family:arial; ">4.1 HARTA TIDAK BERGERAK (TANAH DAN BANGUNAN)</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="3%" style="font-family:arial; ">NO</td>
                    <td width="24%" style="font-family:arial; ">LOKASI</td>
                    <td width="14%" style="font-family:arial; ">LUAS</td>
                    <td width="21%" style="font-family:arial; ">KEPEMILIKAN</td>
                    <td width="15%" style="font-family:arial; ">NILAI PELAPORAN SEBELUMNYA</td>
                    <td width="14%" style="font-family:arial; ">NILAI PELAPORAN SAAT INI</td>
                    <td width="9%" style="font-family:arial; ">KETERANGAN</td>
                </tr>
<?php if ($data['HARTA_TDK_BEGERAK']): ?>
    <?php $i = 1;
    foreach ($data['HARTA_TDK_BEGERAK'] as $htb): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jalan / No</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->JALAN; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Kel / Desa</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->KEL; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Kecamatan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->KEC; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Kab / Kota</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->KAB_KOT; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Prov / Negara</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->PROV . ' / ' . $htb->NAMA_NEGARA; ?></span>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Tanah</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->LUAS_TANAH; ?> m <sup>2</sup></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Bangunan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->LUAS_BANGUNAN; ?> m <sup>2</sup></span>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis Bukti</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->JENIS_BUKTI_HARTA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Nomor Bukti</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $htb->NOMOR_BUKTI; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Atas Nama</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $ATAS_NAMA[$htb->ATAS_NAMA]; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Asal Usul Harta</span>
                                    <span style="font-family:arial; ">:</span>
                                    <!--<span style="font-family:arial; "><?php // echo $htb->ASAL_USUL_HARTA; ?></span>-->
                                    <span style="font-family:arial; "><?php
                                    $manfaat =  array();
                                    $manfaat = explode(',', $htb->ASAL_USUL); 
                                    if(count($manfaat)>0){
                                        $banyak = count($manfaat);
                                        $i = 1;
                                        foreach($manfaat as $row){
                                            if(array_key_exists($row, $asalusul)){
                                                echo $asalusul[$row];
                                                echo ($banyak === $i?"":", ");
                                                $i++;
                                            }
                                        }
                                    }else{
                                        echo "----";
                                    }
                                    ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Pemanfaatan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php
                                    $manfaat =  array();
                                    $manfaat = explode(',', $htb->PEMANFAATAN); 
                                    if(count($manfaat)>0){
                                        $banyak = count($manfaat);
                                        $i = 1;
                                        foreach($manfaat as $row){
                                            if(array_key_exists($row, $pemanfaatan)){
                                                echo $pemanfaatan[$row];
                                                echo ($banyak === $i?"":", ");
                                                $i++;
                                            }
                                        }
                                    }else{
                                        echo "----";
                                    }
                                    ?></span>
                                </div>
                            </td>
                            <td style="font-family:arial; ">
                                <?php
                                if ($FIRST) {
                                    echo "-";
                                } else {
                                    echo 'Rp. ' . number_rupiah($htb->NILAI_LAMA);
                                }
                                ?>	
                            </td>
                            <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($htb->NILAI_PELAPORAN); ?></td>
                            <td style="font-family:arial; "><?php if ($htb->IS_PELEPASAN == '1') {
                    echo $htb->JENIS_PELEPASAN;
                } else {
                    echo $STATUS_HARTA[$htb->STATUS_HARTA];
                } ?></td>
                        </tr>	
        <?php $i++;
    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="7" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>	
            </table>
            <table>
                <tr>
                    <td colspan="6" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">4.2 HARTA BERGERAK (MESIN / ALAT TRANSPORT)</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="30%" style="font-family:arial; ">URAIAN</td>
                    <td width="23%" style="font-family:arial; ">KEPEMILIKAN</td>
                    <td width="16%" style="font-family:arial; ">NILAI PELAPORAN SEBELUMNYA</td>
                    <td width="15%" style="font-family:arial; ">NILAI PELAPORAN SAAT INI</td>
                    <td width="12%" style="font-family:arial; ">KETERANGAN</td>
                </tr>
<?php if ($data['HARTA_BERGERAK']): ?>
    <?php $i = 1;
    foreach ($data['HARTA_BERGERAK'] as $hgr): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->JENIS_HARTA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Merek</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->MEREK; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Model</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->MODEL; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Tahun Pembuatan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->TAHUN_PEMBUATAN; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">No Pol / Registrasi</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->NOPOL_REGISTRASI; ?></span>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis Bukti</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->N_JENIS_BUKTI; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Asal Usul Harta</span>
                                    <span style="font-family:arial; ">:</span>
                                    <!--<span style="font-family:arial; "><?php // echo $hgr->ASAL_USUL_HARTA; ?></span>-->
                                    <span style="font-family:arial; "><?php
                                    $manfaat =  array();
                                    $manfaat = explode(',', $hgr->ASAL_USUL); 
                                    if(count($manfaat)>0){
                                        $banyak = count($manfaat);
                                        $i = 1;
                                        foreach($manfaat as $row){
                                            if(array_key_exists($row, $asalusul)){
                                                echo $asalusul[$row];
                                                echo ($banyak === $i?"":", ");
                                                $i++;
                                            }
                                        }
                                    }else{
                                        echo "----";
                                    }
                                    ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Atas Nama</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $ATAS_NAMA[$hgr->ATAS_NAMA]; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Pemanfaatan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->PEMANFAATAN_HARTA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Lainnya</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgr->KET_LAINNYA; ?></span>
                                </div>
                            </td>
                            <td style="font-family:arial; ">
                        <?php
                        if ($FIRST) {
                            echo "-";
                        } else {
                            echo 'Rp. ' . number_rupiah($hgr->NILAI_LAMA);
                        }
                        ?>	
                            </td>
                            <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($hgr->NILAI_PELAPORAN); ?></td>
                            <td style="font-family:arial; "><?php if ($hgr->IS_PELEPASAN == '1') {
                    echo $hgr->JENIS_PELEPASAN;
                } else {
                    echo $STATUS_HARTA[$hgr->STATUS_HARTA];
                } ?></td>
                        </tr>
        <?php $i++;
    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="6" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>
            </table>
            <table>
                <tr>
                    <td colspan="6" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">4.3 HARTA BERGERAK LAINNYA</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="27%" style="font-family:arial; ">URAIAN</td>
                    <td width="24%" style="font-family:arial; ">ASAL-USUL HARTA</td>
                    <td width="17%" style="font-family:arial; ">NILAI PELAPORAN SEBELUMNYA</td>
                    <td width="15%" style="font-family:arial; ">NILAI PELAPORAN SAAT INI</td>
                    <td width="13%" style="font-family:arial; ">KETERANGAN</td>
                </tr>
<?php if ($data['HARTA_BERGERAK_LAIN']): ?>
    <?php $i = 1;
    foreach ($data['HARTA_BERGERAK_LAIN'] as $hgl): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgl->JENIS_HARTA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Jumlah</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgl->JUMLAH; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Satuan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgl->SATUAN; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Ket Lainnya</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hgl->KETERANGAN; ?></span>
                                </div>
                            </td>
                            <!--<td style="font-family:arial; "><?php // echo $hgl->ASAL_USUL_HARTA; ?></td>-->
                            <td style="font-family:arial; "><?php
                                    $manfaat =  array();
                                    $manfaat = explode(',', $hgl->ASAL_USUL); 
                                    if(count($manfaat)>0){
                                        $banyak = count($manfaat);
                                        $i = 1;
                                        foreach($manfaat as $row){
                                            if(array_key_exists($row, $asalusul)){
                                                echo $asalusul[$row];
                                                echo ($banyak === $i?"":", ");
                                                $i++;
                                            }
                                        }
                                    }else{
                                        echo "----";
                                    }
                                    ?></td>
                            <td style="font-family:arial; ">
                        <?php
                        if ($FIRST) {
                            echo "-";
                        } else {
                            echo 'Rp. ' . number_rupiah($hgl->NILAI_LAMA);
                        }
                        ?>	
                            </td>
                            <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($hgl->NILAI_PELAPORAN); ?></td>
                            <td style="font-family:arial; "><?php if ($hgl->IS_PELEPASAN == '1') {
                            echo $hgl->JENIS_PELEPASAN;
                        } else {
                            echo $STATUS_HARTA[$hgl->STATUS_HARTA];
                        } ?></td>
                        </tr>
        <?php EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="6" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
                <?php EndIf; ?>
            </table>
            <table>
                <tr>
                    <td colspan="3" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">4.4 SURAT BERHARGA</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="3%" style="font-family:arial; ">NO</td>
                    <td width="25%" style="font-family:arial; ">URAIAN</td>
                    <td width="16%" style="font-family:arial; ">NO REKENING / NO NASABAH</td>
                    <td width="20%" style="font-family:arial; ">ASAL USUL HARTA</td>
                    <td width="14%" style="font-family:arial; ">NILAI PELAPORAN SEBELUMNYA</td>
                    <td width="11%" style="font-family:arial; ">NILAI PELAPORAN SAAT INI</td>
                    <td width="11%" style="font-family:arial; ">KETERANGAN</td>
                </tr>
<?php if ($data['HARTA_SURAT_BERHARGA']): ?>
    <?php $i = 1;
    foreach ($data['HARTA_SURAT_BERHARGA'] as $hs): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hs->NAMA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Atas Nama</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hs->ATAS_NAMA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Penerbit / Perusahaan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hs->NAMA_PENERBIT; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Cutodian / Sekuritas</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hs->CUSTODIAN; ?></span>
                                </div>
                            </td>
                            <td style="font-family:arial; ">
                                <?php 
                            	if (strlen($hs->NOMOR_REKENING) >= 32){
                            	    $decrypt_norek = encrypt_username($hs->NOMOR_REKENING,'d');
                            	} else {
                            	    $decrypt_norek = $hs->NOMOR_REKENING;
                            	}
                                echo $decrypt_norek
                            	?>
                            </td>
                            <!--<td style="font-family:arial; "><?php // echo $hs->ASAL_USUL_HARTA; ?></td>-->
                            <td style="font-family:arial; "><?php
                                    $manfaat =  array();
                                    $manfaat = explode(',', $hs->ASAL_USUL); 
                                    if(count($manfaat)>0){
                                        $banyak = count($manfaat);
                                        $i = 1;
                                        foreach($manfaat as $row){
                                            if(array_key_exists($row, $asalusul)){
                                                echo $asalusul[$row];
                                                echo ($banyak === $i?"":", ");
                                                $i++;
                                            }
                                        }
                                    }else{
                                        echo "----";
                                    }
                                    ?></td>
                            <td style="font-family:arial; ">
        <?php
        if ($FIRST) {
            echo "-";
        } else {
            echo 'Rp. ' . number_rupiah($hs->NILAI_LAMA);
        }
        ?>	
                            </td>
                            <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($hs->NILAI_PELAPORAN); ?></td>
                            <td style="font-family:arial; "><?php if ($hs->IS_PELEPASAN == '1') {
            echo $hs->JENIS_PELEPASAN;
        } else {
            echo $STATUS_HARTA[$hs->STATUS_HARTA];
        } ?></td>
                        </tr>
        <?php $i++;
    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="7" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>
            </table>
            <table>
                <tr>
                    <td colspan="6" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">4.5 KAS DAN SETARA KAS</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="3%" style="font-family:arial; ">NO</td>
                    <td width="30%" style="font-family:arial; ">URAIAN</td>
                    <td width="18%" style="font-family:arial; ">INFO REKENING</td>
                    <td width="20%" style="font-family:arial; ">ASAL USUL HARTA</td>
                    <td width="14%" style="font-family:arial; ">NILAI SALDO</td>
                    <td width="15%" style="font-family:arial; ">KETERANGAN</td>
                </tr>
<?php if ($data['HARTA_KAS']): ?>
    <?php $i = 1;
    foreach ($data['HARTA_KAS'] as $hks): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hks->NAMA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Keterangan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo trim($hks->KETERANGAN); ?></span>
                                </div>
                                <?php 
                                if (strlen($hks->NAMA_BANK) >= 32){
                                    $decrypt_namabank = encrypt_username($hks->NAMA_BANK,'d');
                                } else {
                                    $decrypt_namabank = $hks->NAMA_BANK;
                                }
                                ?>
                                <div>
                                    <span style="font-family:arial; ">Nama Bank / Lembaga</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $decrypt_namabank; ?></span>
                                </div>
                            </td>
                            <td>
                            	<?php 
                            	if (strlen($hks->NOMOR_REKENING) >= 32){
                            	    $decrypt_norek = encrypt_username($hks->NOMOR_REKENING,'d');
                            	} else {
                            	    $decrypt_norek = $hks->NOMOR_REKENING;
                            	}
                            	?>
                                <div>
                                    <span style="font-family:arial; ">Nomor</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $decrypt_norek; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Atas Nama</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hks->ATAS_NAMA_REKENING; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Keterangan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hks->KETERANGAN; ?></span>
                                </div>
                            </td>
                            <!--<td style="font-family:arial; "><?php // echo $hks->ASAL_USUL_HARTA; ?></td>-->
                            <td style="font-family:arial; "><?php
                                    $manfaat =  array();
                                    $manfaat = explode(',', $hks->ASAL_USUL); 
                                    if(count($manfaat)>0){
                                        $banyak = count($manfaat);
                                        $i = 1;
                                        foreach($manfaat as $row){
                                            if(array_key_exists($row, $asalusul)){
                                                echo $asalusul[$row];
                                                echo ($banyak === $i?"":", ");
                                                $i++;
                                            }
                                        }
                                    }else{
                                        echo "----";
                                    }
                                    ?></td>
                            <td style="font-family:arial; ">
        <?php
        if ($FIRST) {
            echo 'Rp. ' . number_rupiah($hks->NILAI_EQUIVALEN);
        } else {
            echo 'Rp. ' . number_rupiah($hks->NILAI_EQUIVALEN);
        }
        ?>	
                            </td>
                            <td style="font-family:arial; "><?php if ($hks->IS_PELEPASAN == '1') {
                    echo $hks->JENIS_PELEPASAN;
                } else {
                    echo $STATUS_HARTA[$hks->STATUS_HARTA];
                } ?></td>
                        </tr>
        <?php $i++;
    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="6" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>
            </table>
            <table>
                <tr>
                    <td colspan="6" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">4.6 HARTA LAINNYA</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="3%" style="font-family:arial; ">NO</td>
                    <td width="22%" style="font-family:arial; ">URAIAN</td>
                    <td width="26%" style="font-family:arial; ">ASAL USUL HARTA</td>
                    <td width="17%" style="font-family:arial; ">NILAI PELAPORAN SEBELUMNYA</td>
                    <td width="18%" style="font-family:arial; ">NILAI PELAPORAN SAAT INI</td>
                    <td width="14%" style="font-family:arial; ">KETERANGAN</td>
                </tr>
<?php if ($data['HARTA_LAINNYA']): ?>
                    <?php $i = 1;
                    foreach ($data['HARTA_LAINNYA'] as $hl): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hl->NAMA_JENIS; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Keterangan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hl->KETERANGAN; ?></span>
                                </div>
                            </td>
                            <!--<td style="font-family:arial; "><?php // echo $hl->ASAL_USUL_HARTA; ?></td>-->
                            <td style="font-family:arial; "><?php
                                    $manfaat =  array();
                                    $manfaat = explode(',', $hl->ASAL_USUL); 
                                    if(count($manfaat)>0){
                                        $banyak = count($manfaat);
                                        $i = 1;
                                        foreach($manfaat as $row){
                                            if(array_key_exists($row, $asalusul)){
                                                echo $asalusul[$row];
                                                echo ($banyak === $i?"":", ");
                                                $i++;
                                            }
                                        }
                                    }else{
                                        echo "----";
                                    }
                                    ?></td>
                            <td style="font-family:arial; ">
                        <?php
                        if ($FIRST) {
                            echo "-";
                        } else {
                            echo 'Rp. ' . number_rupiah($hl->NILAI_LAMA);
                        }
                        ?>	
                            </td>
                            <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($hl->NILAI_PELAPORAN); ?></td>
                            <td style="font-family:arial; "><?php if ($hl->IS_PELEPASAN == '1') {
                            echo $hl->JENIS_PELEPASAN;
                        } else {
                            echo $STATUS_HARTA[$hl->STATUS_HARTA];
                        } ?></td>
                        </tr>
        <?php $i++;
    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="6" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
                <?php EndIf; ?>
            </table>
            <table>
                <tr>
                    <td colspan="6" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">4.7 HUTANG</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="3%" style="font-family:arial; ">NO</td>
                    <td width="23%" style="font-family:arial; ">URAIAN</td>
                    <td width="18%" style="font-family:arial; ">NAMA KREDITUR</td>
                    <td width="17%" style="font-family:arial; ">BENTUK AGUNAN</td>
                    <td width="19%" style="font-family:arial; ">NILAI AWAL HUTANG</td>
                    <td width="20%" style="font-family:arial; ">NILAI SALDO HUTANG</td>
                </tr>
<?php if ($data['HUTANG']): ?>
    <?php $i = 1;
    foreach ($data['HUTANG'] as $hutang): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hutang->NAMA; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Atas Nama</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $hutang->ATAS_NAMA; ?></span>
                                </div>
                            </td>
                            <td style="font-family:arial; "><?php echo $hutang->NAMA_KREDITUR; ?></td>
                            <td style="font-family:arial; "><?php echo $hutang->AGUNAN; ?></td>
                            <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($hutang->AWAL_HUTANG); ?></td>
                            <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($hutang->SALDO_HUTANG); ?></td>
                        </tr>
                        <?php $i++;
                    EndForeach; ?>
                    <?php Else: ?>
                    <tr>
                        <td colspan="6" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
                <?php EndIf; ?>
            </table>
        </div>
<!--        <div style="margin-top:15px">-->
            <table style="width:100%;">
                <tr>
                    <td style="padding-bottom:5px;">
                        <b style="font-family:arial; ">5. PENERIMAAN</b>
                    </td>
                </tr>
            </table>
<!--        </div>-->
        <div>
            <table>
                <tr>
                    <td style="padding-bottom:5px;">
                        <b style="font-family:arial; ">5.1 PENERIMAAN DARI PEKERJAAN</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="40%" style="font-family:arial; ">JENIS PENERIMAAN</td>
                    <td width="30%" style="font-family:arial; ">PENYELENGGARA NEGARA</td>
                    <td width="26%" style="font-family:arial; ">PASANGAN</td>
                </tr>
<?php
$PENERIMAAN = $data['PENERIMAAN'];
$C_PENERIMAAN = array();
$C_PENERIMAAN[0] = 0;
$C_PENERIMAAN[1] = 0;
$C_PENERIMAAN[2] = 0;
foreach ($PENERIMAAN as $PM) {
    if ($PM->GROUP_JENIS == 'A') {
        $C_PENERIMAAN[0] += 1;
    } else if ($PM->GROUP_JENIS == 'B') {
        $C_PENERIMAAN[1] += 1;
    } else {
        $C_PENERIMAAN[2] += 1;
    }
}
?>
<?php if ($C_PENERIMAAN[0] > 1): ?>
                    <?php $i = 1;
                    foreach ($data['PENERIMAAN'] as $PA): if ($PA->GROUP_JENIS == 'A'): ?>
                            <tr>
                                <td style="font-family:arial; "><?php echo $i; ?></td>
                                <td style="font-family:arial; "><?php echo $PA->JENIS_PENERIMAAN; ?></td>
                                <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($PA->PN); ?></td>
                                <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($PA->PASANGAN); ?></td>
                            </tr>		
            <?php $i++;
        EndIf;
    EndForeach; ?>	
    <?php Else: ?>
                    <tr>
                        <td colspan="4" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>
            </table>
            <br>
            <table>
                <tr>
                    <td colspan="3" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">5.2 PENERIMAAN DARI USAHA DAN KEKAYAAN</b>
                    </td>
                </tr>
            </table>

            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="46%" style="font-family:arial; ">JENIS PENERIMAAN</td>
                    <td width="50%" style="font-family:arial; ">TOTAL PENERIMAAN KAS</td>
                </tr>
<?php if ($C_PENERIMAAN[1] > 1): ?>
                <?php $i = 1;
                foreach ($data['PENERIMAAN'] as $PA): if ($PA->GROUP_JENIS == 'B'): ?>
                            <tr>
                                <td style="font-family:arial; "><?php echo $i; ?></td>
                                <td style="font-family:arial; "><?php echo $PA->JENIS_PENERIMAAN; ?></td>
                                <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($PA->PN); ?></td>
                            </tr>		
                        <?php $i++;
                    EndIf;
                EndForeach; ?>	
                <?php Else: ?>
                    <tr>
                        <td colspan="3" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
            <?php EndIf; ?>
            </table>

            <table>
                <tr>
                    <td colspan="3" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">5.3 PENERIMAAN LAINNYA</b>
                    </td>
                </tr>
            </table>

            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="46%" style="font-family:arial; ">JENIS PENERIMAAN</td>
                    <td width="50%" style="font-family:arial; ">TOTAL PENERIMAAN KAS</td>
                </tr>
<?php if ($C_PENERIMAAN[2] > 1): ?>
    <?php $i = 1;
    foreach ($data['PENERIMAAN'] as $PA): if ($PA->GROUP_JENIS == 'C'): ?>
                            <tr>
                                <td style="font-family:arial; "><?php echo $i; ?></td>
                                <td style="font-family:arial; "><?php echo $PA->JENIS_PENERIMAAN; ?></td>
                                <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($PA->PN); ?></td>
                            </tr>		
            <?php $i++;
        EndIf;
    EndForeach; ?>	
    <?php Else: ?>
                    <tr>
                        <td colspan="3" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>
            </table>
        </div>
<!--        <div style="margin-top:15px">-->
<?php
$PENGELUARAN = $data['PENGELUARAN'];
$C_PENGELUARAN = array();
$C_PENGELUARAN[0] = 0;
$C_PENGELUARAN[1] = 0;
$C_PENGELUARAN[2] = 0;
foreach ($PENGELUARAN as $PNG) {
    if ($PNG->GROUP_JENIS == 'A') {
        $C_PENGELUARAN[0] += 1;
    } else if ($PNG->GROUP_JENIS == 'B') {
        $C_PENGELUARAN[1] += 1;
    } else {
        $C_PENGELUARAN[2] += 1;
    }
}
?>
<!--            <table style="width:100%;">-->
            <table>
                <tr>
                    <td style="padding-bottom:5px;">
                        <b style="font-family:arial; ">6. PENGELUARAN</b>
                    </td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <td colspan="3" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">6.1 PENGELUARAN RUTIN</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="45%" style="font-family:arial; ">JENIS PENGELUARAN</td>
                    <td width="51%" style="font-family:arial; ">TOTAL NILAI PENGELUARAN</td>
                </tr>
<?php if ($C_PENGELUARAN[0] > 1): ?>
    <?php $i = 1;
    foreach ($data['PENGELUARAN'] as $PL): if ($PL->GROUP_JENIS == 'A'): ?>
                            <tr>
                                <td style="font-family:arial; "><?php echo $i; ?></td>
                                <td style="font-family:arial; "><?php echo $PL->JENIS_PENGELUARAN; ?></td>
                                <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($PL->JML); ?></td>
                            </tr>		
                            <?php $i++;
                        EndIf;
                    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="3" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
                <?php EndIf; ?>	
            </table>
            <br>
            <table>
                <tr>
                    <td colspan="3" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">6.2 PENGELUARAN HARTA</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="45%" style="font-family:arial; ">JENIS PENGELUARAN</td>
                    <td width="51%" style="font-family:arial; ">TOTAL NILAI PENGELUARAN</td>
                </tr>
<?php if ($C_PENGELUARAN[1] > 1): ?>
    <?php $i = 1;
    foreach ($data['PENGELUARAN'] as $PL): if ($PL->GROUP_JENIS == 'B'): ?>
                            <tr>
                                <td style="font-family:arial; "><?php echo $i; ?></td>
                                <td style="font-family:arial; "><?php echo $PL->JENIS_PENGELUARAN; ?></td>
                                <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($PL->JML); ?></td>
                            </tr>		
            <?php $i++;
        EndIf;
    EndForeach; ?>
    <?php Else: ?>
                    <tr>
                        <td colspan="3" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>	
            </table>
            <br>
            <table>
                <tr>
                    <td colspan="3" style="padding-left:10px; padding-bottom:10px;">
                        <b style="font-family:arial; ">6.3 PENGELUARAN LAINNYA</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <td width="4%" style="font-family:arial; ">NO</td>
                    <td width="45%" style="font-family:arial; ">JENIS PENGELUARAN</td>
                    <td width="51%" style="font-family:arial; ">TOTAL NILAI PENGELUARAN</td>
                </tr>
            <?php if ($C_PENGELUARAN[2] > 1): ?>
                <?php $i = 1;
                foreach ($data['PENGELUARAN'] as $PL): if ($PL->GROUP_JENIS == 'C'): ?>
                            <tr>
                                <td style="font-family:arial; "><?php echo $i; ?></td>
                                <td style="font-family:arial; "><?php echo $PL->JENIS_PENGELUARAN; ?></td>
                                <td style="font-family:arial; "><?php echo 'Rp. ' . number_rupiah($PL->JML); ?></td>
                            </tr>		
                        <?php $i++;
                    EndIf;
                EndForeach; ?>
                <?php Else: ?>
                    <tr>
                        <td colspan="3" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
        <?php EndIf; ?>	
            </table>
        </div>
<!--        <div style="margin-top:15px">-->
            <table>
                <tr>
                    <td style="padding-bottom:5px;">
                        <b style="font-family:arial; ">7. LAMPIRAN FASILITAS</b>
                    </td>
                </tr>
            </table>
            <table border="1" align="left" cellpadding="5" style="font-size: 11px;  border-collapse: collapse; table-layout:fixed; width:100%;">
                <tr>
                    <th width="4%" style="font-family:arial; ">NO</th>
                    <th width="28%" style="font-family:arial; ">URAIAN</th>
                    <th width="47%" style="font-family:arial; ">NAMA PIHAK PEMBERI FASILITAS</th>
                    <th width="21%" style="font-family:arial; ">KETERANGAN</th>
                </tr>
<?php if ($data['FASILITAS']): ?>
    <?php $i = 1;
    foreach ($data['FASILITAS'] as $fs): ?>
                        <tr>
                            <td style="font-family:arial; "><?php echo $i; ?></td>
                            <td>
                                <div>
                                    <span style="font-family:arial; ">Jenis</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $fs->JENIS_FASILITAS; ?></span>
                                </div>
                                <div>
                                    <span style="font-family:arial; ">Keterangan</span>
                                    <span style="font-family:arial; ">:</span>
                                    <span style="font-family:arial; "><?php echo $fs->KETERANGAN; ?></span>
                                </div>
                            </td>
                            <td style="font-family:arial; "><?php echo $fs->PEMBERI_FASILITAS; ?></td>
                            <td style="font-family:arial; "><?php echo $fs->KETERANGAN; ?></td>
                        </tr>	
        <?php $i++;
    EndForeach; ?>	
    <?php Else: ?>
                    <tr>
                        <td colspan="4" style="font-family:arial; ">Tidak ada data</td>
                    </tr>
<?php EndIf; ?>
            </table>
<?php if ($data['KETENTUAN'] == '1'): ?>
    <?php $this->load->view('portal/filing/ketentuan'); ?>
    <?php EndIf; ?>
<?php
//if($data['OPTION']!='-1'){
//echo "<label>Status Persetujuan : </label>";
//if($data['OPTION']=='1'){
echo "<br><br><br><br><b>Setuju (v)</b>";
//}else{
//echo "<b>Tidak Setuju (v)</b>";
//}
//}
?>	
        </div>
<?php
/*
 * Bottom Page
 */
?>
    </div>
</div>