<?php $sql = "SELECT
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
                <td align="center">
                    <?php if($tt == 'tts') { ?>
                        <h5>BERITA ACARA PENYAMPAIAN<br> LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</h5>
                    <?php } else if($tt = 'tt') { ?>
                        <h5>TANDA TERIMA PENYERAHAN FORMULIR <br> LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</h5>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <table width="100%" class="cont22">
            <tr>
                <td width="20%" valign="top">Atas Nama</td>
                <td width="3%" valign="top" align="center">:</td>
                <td valign="top"><?= strtoupper(@$item->NAMA); ?></td>
            </tr>
            <tr>
                <td valign="top">Jabatan</td>
                <td valign="top" align="center">:</td>
                <td valign="top"><?= strtoupper(@$item->NAMA_JABATAN); ?></td>
            </tr>
            <tr>
                <td valign="top">Bidang</td>
                <td valign="top" align="center">:</td>
                <td valign="top"><?= strtoupper(@$item->BDG_NAMA); ?></td>
            </tr>
            <tr>
                <td valign="top">Lembaga</td>
                <td valign="top" align="center">:</td>
                <td valign="top"><?= strtoupper(@$item->INST_NAMA); ?></td>
            </tr>
            <tr>
                <td valign="top">Tahun Pelaporan</td>
                <td valign="top" align="center">:</td>
                <td valign="top"><?= (@$item->TGL_LAPOR != '0' ? date('Y', strtotime($item->TGL_LAPOR)) : '-'); ?></td>
            </tr>
        </table>

        <table width="100%" style="margin-top: 20px; font-size: 12px;">
            <tr>
                <td align="center" width="50%">&nbsp;</td>
                <td align="center" width="50%">Jakarta, <?= tgl_format(date('d-M-Y')); ?></td>
            </tr>
        </table>

        <table width="100%" style="margin-top: 20px; font-size: 12px;">
            <tr>
                <td width="50%" align="center">&nbsp;</td>
                <td width="50%" align="center">
                    Yang Menerima<br><br><br><br><br>
                    ( <?php echo $this->session->userdata('NAMA')?> )
                </td>
            </tr>
        </table>

        <table width="100%" class="tbl-akhir">
            <tr>
                <td></td>
                <td></td>
                <td width="10%" valign="top">Jabatan</td>
                <td width="1%" valign="top">:</td>
                <td>
                    User Entri
                </td>
            </tr>
        </table>
    </div>