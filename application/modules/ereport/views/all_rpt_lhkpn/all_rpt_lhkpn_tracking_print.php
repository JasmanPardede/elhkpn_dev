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
            <td width="1%" style="background: black; color: white;">No</td>
            <td width="20%" style="background: black; color: white;">Pengirim</td>
            <td width="20%" style="background: black; color: white;">Penerima</td>
            <td width="20%" style="background: black; color: white;">Date Insert</td>
            <td style="background: black; color: white;">Status</td>
        </tr>
        <?php $i=1; foreach ($subitem as $subitems): ?>
	        <tr>
	            <td><?= @$i++; ?></td>
				<td><?= @$subitems->PENGIRIM; ?></td>
				<td><?= @$subitems->PENERIMA; ?></td>
				<td><?= @date('d/m/Y h:m:s',strtotime(@$subitems->DATE_INSERT)); ?></td>
				<td><?= @$subitems->STATUS; ?></td>
	        </tr>
        <?php endforeach ?>
    </table>
</div>