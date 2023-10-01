<?php
    $sql = "SELECT
        CORE_SETTING.`OWNER`,
        CORE_SETTING.SETTING,
        CORE_SETTING.`VALUE`
        FROM
        CORE_SETTING
        WHERE
        CORE_SETTING.`OWNER` = 'app.lhkpn'
        AND CORE_SETTING.SETTING = 'tts'";

    $row = $this->db->query($sql)->row();
    $s = json_decode(@$row->VALUE);
    
?>
<link href="<?php echo base_url();?>css/style_annaoun.css" rel="stylesheet" type="text/css">
<div class="brdr">
    <table align="center" style="margin-top:10px;">
        <tr>
            <td><img src="images/<?php echo $s->LOGO;?>" class="logo-header" width="<?php echo $s->WIDTH;?>" height="<?php echo $s->HEIGHT;?>"></td>
            <td style="padding-left: 10px;" align="center">
                <?php echo @$s->ALAMAT;?>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top:20px;">
        <tr>
            <td align="center"><h5>TANDA TERIMA PENYERAHAN FORMULIR <br> LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA
            <?php //($STATUS == '2')?"<br/> (TERVERIFIKASI TIDAK LENGKAP)":"" ?>
            <?php if ($STATUS == '2' || $STATUS == '5') { 
                echo "<br/> (TERVERIFIKASI TIDAK LENGKAP)"; 
            } else if ($STATUS == '7') { 
                echo "<br/> (DITOLAK)"; 
            } else { 
                echo "";
            } ?></h5>
            </td>
        </tr>
    </table>

    <table width="100%" class="cont22">
        <tr>
            <td width="20%" valign="top">Atas Nama</td>
            <td width="3%" valign="top" align="center">:</td>
            <td valign="top"><?= strtoupper(@$NAMA); ?></td>
        </tr>
        <tr>
            <td valign="top">Jabatan</td>
            <td valign="top" align="center">:</td>
            <td valign="top"><?= strtoupper(@$JABATAN); ?></td>
        </tr>
        <tr>
            <td valign="top">Bidang</td>
            <td valign="top" align="center">:</td>
            <td valign="top"><?= strtoupper(@$BDG_NAMA); ?></td>
        </tr>
        <tr>
            <td valign="top">Lembaga</td>
            <td valign="top" align="center">:</td>
            <td valign="top"><?= strtoupper(@$LEMBAGA); ?></td>
        </tr>
        <tr>
          <td valign="top">Tahun Pelaporan</td>
          <td valign="top" align="center">:</td>
          <td valign="top"><?= @$LAPOR; ?></td>
        </tr>
        <tr>
            <td valign="top">Tanggal Kirim </td>
            <td valign="top" align="center">:</td>
            <td valign="top"><?php  echo date('d-m-Y') ?></td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 20px; font-size: 12px;">
        <tr>
            <td align="center" width="50%">&nbsp;</td>
            <td align="center" width="50%">Jakarta,  <?php  echo date('d F Y') ?></td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 20px; font-size: 12px;">
        <tr>
          <td colspan="3" align="center">&nbsp;</td>
          <td colspan="2" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td colspan="2" align="center"><img src="img/stempel.jpg"  width="<?php echo $s->WIDTH;?>" height="<?php echo $s->HEIGHT;?>" class="logo-header" /></td>
          <td colspan="2" align="center">Yang Menerima<br />
            <br />
            <br />
            <br />
            <br />
           (<?php echo $PETUGAS ?>) 
          </td>
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">Jabatan</td>
          <td align="left">: <?php echo $TUGAS_PETUGAS?></td>
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td colspan="2" align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%" align="left">&nbsp;</td>
            <td width="11%" align="left">&nbsp;</td>
            <td width="29%" align="left">&nbsp;</td>
            <td width="12%" align="left">&nbsp;</td>
            <td width="38%" align="left">&nbsp;</td>
        </tr>
    </table>
<!--    <table width="100%" class="tbl-akhir">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td width="10%" valign="top">Jabatan</td>
            <td width="1%" valign="top">:</td>
            <td>
                Koordinator Verifikasi
            </td>
        </tr>
    </table>-->
</div>