<?php
    switch ($form) {
        case 'bast': ?>
<link href="<?php echo base_url();?>css/style_annaoun.css" rel="stylesheet" type="text/css">
<div>
    <table align="center" style="margin-top:10px;">
        <tr>
            <td align="center">
                <strong>BERITA ACARA</strong>
            </td>
        </tr>
        <tr>
            <td align="center">
                <strong>SERAH TERIMA DOKUMEN LHKPN</strong>
            </td>
        </tr>
    </table>

    <table style="margin-top: 30px; font-size: 10px;">
        <tr>
            <td valign="top">Nomor</td>
            <td width="3%" valign="top" align="center">:</td>
            <td valign="top"></td>
        </tr>
        <!-- <tr>
            <td valign="top">Tanggal Penyerahan</td>
            <td valign="top" align="center">:</td>
            <td valign="top"><?= $tanggal; ?></td>
        </tr> -->
    </table>

    <table width="100%" class="tb-1" border="1" style="margin-top: 40px;font-size: 8px;">
        <tr>
            <th width="1%">No</th>
            <th width="10%">No Agenda</th>
            <th width="10%">Nama PN</th>
            <th width="10%">Jabatan</th>
            <th width="20%">Lembaga</th>
            <th width="15%">Tgl Penyampaian</th>
            <th>Ket</th>
        </tr>
        <?php $i = 0; foreach($data as $item): ?>
            <tr>
                <td align="center"><?php echo ++$i; ?>.</td>
                <td></td>
                <td><?php echo $item->NAMA; ?></td>
                <td>
                    <?php echo $item->DESKRIPSI_JABATAN; ?> - <?php echo $item->NAMA_JABATAN; ?>
                </td>
                <td>
                    <?php echo $item->INST_NAMA; ?>
                </td>
                <td>
                    <?php echo tgl_format($item->TANGGAL_PENERIMAAN); ?>
                </td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <table width="100%" style="margin-top: 20px; font-size: 12px;">
        <tr>
            <td width="50%" align="center">
                Yang Menerima<br><br><br><br><br>
                (<?php echo @$menerima[0]->NAMA ?>)
            </td>
            <td width="50%" align="center">
                Yang Menyerahkan<br><br><br><br><br>
                (<?php echo @$menyerahkan[0]->NAMA ?>)
            </td>
        </tr>
    </table>
</div>
        <?php break; ?>
    <?php case 'coversheet': ?>
        <link href="<?php echo base_url();?>css/style_annaoun.css" rel="stylesheet" type="text/css">
        <div style="font-family: Arial, Helvetica, sans-serif;">
            <div width="100%" align="right">
                <barcode code="<?php echo $barcode?>" type="C93" size="0.8" height="1.7" text="1" />
            </div>
            <div style="margin-top: 30px;" width="91%" align="right">
                <span><?php echo $barcode; ?></span>
            </div>
            <table style="width: 100%;margin-top: 20px; font-size: 18px; font-weight: bold;">
                <tr>
                    <td valign="top" width="200px">BIDANG</td>
                    <td width="3%" valign="top" align="center">:</td>
                    <td valign="top"><?php echo $item->BDG_NAMA;?></td>
                </tr>
                <tr>
                    <td valign="top">NAMA</td>
                    <td valign="top" align="center">:</td>
                    <td valign="top"><?php echo $item->NAMA;?></td>
                </tr>
            </table>
            <table style="width: 100%;font-size: 12px;">
                <tr>
                    <td valign="top" width="200px">TEMPAT, TGL LAHIR</td>
                    <td width="3%" valign="top" align="center">:</td>
                    <td valign="top"><?php echo $item->TEMPAT_LAHIR.', '.tgl_format($item->TGL_LAHIR);?></td>
                </tr>
                <tr>
                    <td valign="top">JABATAN</td>
                    <td valign="top" align="center">:</td>
                    <td valign="top"><?php echo $item->NAMA_JABATAN;?></td>
                </tr>
                <tr>
                    <td valign="top">LEMBAGA</td>
                    <td valign="top" align="center">:</td>
                    <td valign="top"><?php echo $item->INST_NAMA;?></td>
                </tr>
                <tr>
                    <td valign="top">UNIT KERJA</td>
                    <td valign="top" align="center">:</td>
                    <td valign="top"><?php echo $item->UK_NAMA;?></td>
                </tr>
                <tr>
                    <td valign="top">TGL LAPOR</td>
                    <td valign="top" align="center">:</td>
                    <td valign="top"><?php echo tgl_format($item->TANGGAL_PELAPORAN);?></td>
                </tr>
                <tr>
                    <td valign="top">TGL PENYAMPAIAN</td>
                    <td valign="top" align="center">:</td>
                    <td valign="top"><?php echo tgl_format($item->TANGGAL_PENERIMAAN);?></td>
                </tr>
                <tr>
                    <td valign="top"><strong>NO. AGENDA</strong></td>
                    <td valign="top" align="center">:</td>
                    <td valign="top"><strong><?php echo $barcode; ?></strong></td>
                </tr>
            </table>

            <table style="width: 100%; font-size: 12px;" class="tb-1" border="1">
                <tr>
                    <td>TGL</td>
                    <td>KONFIRMASI</td>
                    <td>PARAF</td>
                </tr>
                <tr>
                    <td height="500px"></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <table style="margin-top: 10px; width: 100%; font-size: 11px;" class="tb-1" border="1">
                <tr>
                    <td align="center"></td>
                    <td align="center"><strong>Tanggal</strong></td>
                    <td align="center"><strong>Pelaksana</strong></td>
                    <td align="center"><strong>Paraf</strong></td>
                    <td align="center"><strong>Catatan</strong></td>
                </tr>
                <tr>
                    <td style="padding: 5px;" align="center" width="150px" height="40px"><strong>Scan Document</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php break; ?>

<?php } ?>