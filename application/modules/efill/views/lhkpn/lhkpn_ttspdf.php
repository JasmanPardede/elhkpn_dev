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
            <td align="center"><h5>TANDA TERIMA SEMENTARA <br> LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</h5></td>
        </tr>
    </table>

    <table width="100%" class="cont22">
       <tr>
           <td>Yth. <?php echo $NAMA; ?><br><br></td>
       </tr>
       <tr>
           <td>
               &nbsp;&nbsp;&nbsp;&nbsp;Terima kasih telah mengisi LHKPN secara Online, kami akan melakukan verifikasi LHKPN Bapak/Ibu. Tanda Terima LHKPN akan kami kirimkan melalui email setelah proses verifikasi selesai. Berikut kami lampirkan Tanda Terima Sementara LHKPN Bapak/Ibu.<br><br>
               <span style="height: 200px;"></span>
           </td>
       </tr>
       <tr>
           <td>Terima kasih</td>
       </tr>
    </table>
</div>