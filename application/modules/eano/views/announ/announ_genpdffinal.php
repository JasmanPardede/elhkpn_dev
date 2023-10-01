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
 * @author Gunaones - PT.Mitreka Solusi Indonesia || Irfan Kiddo - Pirate.net
 * @package Views/eano/announ
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link href="<?php echo base_url(); ?>css/style_annaoun.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <span class="logo-mini"><img style="width:100px;" src="<?php echo base_url(); ?>images/logo_kpk.png" ></span>
        <hr>
        <table cellspacing="0" cellpadding="0">
            <col width="31" />
            <col width="26" />
            <col width="40" />
            <col width="23" />
            <col width="64" />
            <col width="34" />
            <col width="134" />
            <col width="27" />
            <col width="78" />
            <col width="21" />
            <col width="81" />
            <col width="26" />
            <col width="170" />
            <tr height="31">
                <td style="font-family:Arial" height="31" colspan="15" align="center"><b><h2>PENGUMUMAN HARTA KEKAYAAN PENYELENGGARA    NEGARA&nbsp;</h2></b></td>
            </tr>
            <tr height="7">
                <td style="font-family:Arial" width="25" height="7"></td>
                <td style="font-family:Arial" width="27"></td>
                <td style="font-family:Arial" width="41"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial" width="177"></td>
                <td style="font-family:Arial" width="14"></td>
                <td style="font-family:Arial" width="171"></td>
                <td style="font-family:Arial" width="14"></td>
                <td style="font-family:Arial" width="19"></td>
                <td style="font-family:Arial" width="21"></td>
                <td style="font-family:Arial" width="24"></td>
                <td style="font-family:Arial" width="135"></td>
                <td style="font-family:Arial" width="223"></td>
                <td style="font-family:Arial" width="32"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial" colspan="2"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20" colspan="11"><b>BIDANG&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?= $datapn->BDG_NAMA ?></b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial" width="131"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20" colspan="11"><b>LEMBAGA&nbsp; : 
                    <?= $datapn->INST_NAMA ?></b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"><b>I.</b></td>
                <td style="font-family:Arial" colspan="5"><b>DATA PRIBADI</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial">1.</td>
                <td style="font-family:Arial" colspan="3">Nama</td>
                <td style="font-family:Arial">:</td>
                <td style="font-family:Arial" colspan="7"><?= $datapn->NAMA_LENGKAP ?></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial">2.</td>
                <td style="font-family:Arial" colspan="3">Jabatan</td>
                <td style="font-family:Arial">:</td>
                <td style="font-family:Arial" colspan="7"><?= $datapn->DESKRIPSI_JABATAN ?></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial">3.</td>
                <td style="font-family:Arial" colspan="3">NHK</td>
                <td style="font-family:Arial">:</td>
                <td style="font-family:Arial" colspan="7"><?= $datapn->NHK != NULL ? $datapn->NHK  : $nhk; ?></td>
                <td style="font-family:Arial"></td>
            </tr>
            
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial">4.</td>
                <td style="font-family:Arial" colspan="3">Tanggal/Tahun Pelaporan</td>
                <td style="font-family:Arial">:</td>
                <td style="font-family:Arial" colspan="7"><?= date('d/m/Y', strtotime($datapn->TGL_LAPOR)) ?></td>
                <td style="font-family:Arial"></td>
            </tr>
            
            <tr height="20">
              <td style="font-family:Arial" height="20">&nbsp;</td>
              <td style="font-family:Arial" colspan="5">&nbsp;</td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"><b>II.</b></td>
                <td style="font-family:Arial" colspan="5"><b>DATA HARTA</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"><b>A.</b></td>
                <td style="font-family:Arial" colspan="7"><b>HARTA TIDAK BERGERAK (TANAH DAN BANGUNAN)</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->T1) ?></b></td>
            </tr>
            <?php
            $i = 0;
            foreach ($dt_harta_tidak_bergerak as $data) {
                $tmp = $data->NEGARA == 2 ? $data->JALAN . ', ' . $data->NAMA_NEGARA : ' Kota ' . $data->KAB_KOT;
                $i++;
                ?>
                <tr height="20">
                    <td style="font-family:Arial" height="20"></td>
                    <td style="font-family:Arial"></td>
                    <td style="font-family:Arial"><?= $i ?></td>
                    <td style="font-family:Arial" colspan="10"><?= 'Tanah dan Bangunan Seluas ' . $data->LUAS_BANGUNAN . ' m<sup>2</sup>/' . $data->LUAS_TANAH . ' m<sup>2</sup> di ' . ucfirst($tmp) . ', ' . ' senilai Rp'.number_rupiah($data->NILAI_PELAPORAN).', berasal dari '. ucfirst($data->ASAL_USUL); ?></td>
                    <td style="font-family:Arial"></td>
                </tr>
            <?php } ?>

            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"><b>B.</b></td>
                <td style="font-family:Arial" colspan="8"><b>HARTA BERGERAK (ALAT TRANSPORTASI DAN MESIN)</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->T6) ?></b></td>
            </tr>
            <?php
            $i = 0;
            foreach ($dt_harta_bergerak as $data) {
                $i++;
                ?>
                <tr height="20">
                    <td style="font-family:Arial" height="20"></td>
                    <td style="font-family:Arial"></td>
                    <td style="font-family:Arial"><?= $i; ?></td>
                    <td style="font-family:Arial" colspan="10"><?= $data->NAMA . ', ' . $data->MEREK . ' ' . $data->MODEL . ', senilai Rp' . number_rupiah($data->NILAI_PELAPORAN).', berasal dari '. $data->ASAL_USUL ?></td>
                    <td style="font-family:Arial"></td>
                    <td style="font-family:Arial"></td>
                </tr>
            <?php } ?>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"><b>C.</b></td>
                <td style="font-family:Arial" colspan="6"><b>HARTA BERGERAK LAINYA</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->T5) ?></b></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"><b>D.</b></td>
                <td style="font-family:Arial" colspan="4"><b>SURAT BERHARGA</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->T2) ?></b></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"><b>E.</b></td>
                <td style="font-family:Arial" colspan="5"><b>KAS DAN SETARA KAS</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->T4) ?></b></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"><b>F.</b></td>
                <td style="font-family:Arial" colspan="4"><b>HARTA LAINNYA</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->T3) ?></b></td>
            </tr>
            <tr height="20">
              <td style="font-family:Arial" height="20"></td>
              <td style="font-family:Arial" colspan="9">&nbsp;</td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial">&nbsp;</td>
              <td style="font-family:Arial" colspan="2"><hr></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial" colspan="9"><b>TOTAL HARTA (II)&nbsp;&nbsp;&nbsp;</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>

                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6) ?></b></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
				<td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"><b>III</b></td>
                <td style="font-family:Arial" colspan="5"><b>HUTANG</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah($datapn->jumhut) ?></b></td>
            </tr>

            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial" colspan="2"><hr></td>
			</tr>
            <tr height="20">
                <td style="font-family:Arial" height="19"><b>IV.</b></td>
                <td style="font-family:Arial" colspan="7"><b>TOTAL HARTA KEKAYAAN (II-III)</b></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"><b>Rp.</b></td>
                <td style="font-family:Arial" align="right"><b><?= number_rupiah(($datapn->T1 + $datapn->T2 + $datapn->T3 + $datapn->T4 + $datapn->T5 + $datapn->T6) - $datapn->jumhut) ?></b></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial" colspan="2"><hr></td>
			</tr>
            <tr height="21">
              <td style="font-family:Arial" height="21"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
            </tr>
            <tr height="21">
              <td style="font-family:Arial" height="21"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
            </tr>
            <tr height="21">
              <td style="font-family:Arial" height="21"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
            </tr>
            <tr height="21">
              <td style="font-family:Arial" height="21"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
            </tr>
            <tr height="21">
              <td style="font-family:Arial" height="21"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
              <td style="font-family:Arial"></td>
            </tr>
            <tr height="21">
                <td style="font-family:Arial" height="21"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="20">
              <td style="font-family:Arial" height="20" colspan="15" valign="bottom"><hr></td>
            </tr>
            <tr height="20">
                <td style="font-family:Arial" height="20">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial" colspan="3"><b>Catatan :</b></td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial">&nbsp;</td>
                <td style="font-family:Arial"></td>
            </tr>
            <tr height="61">
                <td style="font-family:Arial" colspan="3" rowspan="5" align="left" valign="top">
                    <img src="<?php echo base_url(); ?>uploads/FINAL_LHKPN/<?= $datapn->NIK ?>/qrcode.png" alt="a" width="94" height="94" />
                    
                    <table cellpadding="0" cellspacing="0">
                    </table></td>
                <td style="font-family:Arial" width="36" height="38">1.</td>
                <td style="font-family:Arial" colspan="11">Rincian harta kekayaan    dalam pengumuman ini sesuai dengan yang dilaporkan oleh Penyelenggara Negara    dan tidak dapat dijadikan dasar oleh Penyelenggara Negara atau siapapun juga    untuk menyatakan bahwa harta yang bersangkutan tidak terkait tindak pidana.</td>
            </tr>
            <tr height="84">
                <td style="font-family:Arial" height="57">2.</td>
                <td style="font-family:Arial" colspan="11">Pengumuman ini telah    ditempatkan dalam media pengumuman resmi KPK dalam rangka memfasilitasi    pemenuhan kewajiban Penyelenggara Negara untuk mengumuman harta kekayaan    sesuai dengan Undang-Undang Nomor 28 Tahun 1999 tentang Penyelenggaraan    Negara yang Bersih dan Bebas dari Korupsi, Kolusi dan Nepotisme</td>
            </tr>
            <?php
            if ($datapn->STATUS == 3) {
                ?>

                <tr height="20">
                    <td style="font-family:Arial" height="19">3.</td>
                    <td style="font-family:Arial" colspan="11">Pengumuman ini tidak    memerlukan tanda tangan karena dicetak secara komputerisasi</td>
                </tr>
                <?php
            } else {
                ?>
                <tr height="20">
                    <td style="font-family:Arial" height="20">3.</td>
                    <td style="font-family:Arial" colspan="11">Laporan Harta Kekayaan dalam pengumuman ini dinyatakan tidak lengkap sesuai dengan Pasal 8,  Peraturan Komisi Pemberantasan Korupsi Republik Indonesia Nomor 07 Tahun 2016 Tentang Tata Cara Pendaftaran, Pengumuman, dan Pemeriksaan LHKPN.</td>
                </tr>
                <tr height="20">
                    <td style="font-family:Arial" height="20">4.</td>
                    <td style="font-family:Arial" colspan="11">Pengumuman ini tidak memerlukan tanda tangan karena dicetak secara komputerisasi</td>
                </tr>

                <?php
            }
            ?>

            <tr height="20">
                <td style="font-family:Arial" height="20"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
                <td style="font-family:Arial"></td>
				<td></td>
            </tr>
        </table>
    </body>
</html>



