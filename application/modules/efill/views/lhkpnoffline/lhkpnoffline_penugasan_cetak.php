<?php
	if($total_rows){
?>
<link href="<?php echo base_url();?>css/style_annaoun.css" rel="stylesheet" type="text/css">
<div align="center" class="head-top">
    PENGUMUMAN HARTA KEKAYAAN PENYELENGGARA NEGARA <br> TAHUN 2015
</div>
<div class="head-content-second">
    <table width="100%" class="tbl-ctn3">
        <thead>
            <tr class="lhkpn_head">
                <th width="5%">No.</th>
                <th width="15%">Tanggal Terima</th>
                <th>PN</th>
                <th>Jenis DOKUMEN</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 0 + $offset;
                $start = $i + 1;
                foreach ($items as $item) {
            ?>
            <tr>
                <td align="center" valign="top"><?php echo ++$i; ?></td>
                <td valign="top">
                    <?php 
                        echo date('d-m-Y',strtotime($item->TANGGAL_PENERIMAAN)); ?> <br>
                        Oleh : <?php echo $item->USERNAME; 
                    ?>
                </td>
                <td>
                    NIK : <?php echo $item->NIK; ?> <br>
                    Nama : <?= @$item->NAMA; ?> <br>
                    Jabatan : - <?php echo $item->NAMA_JABATAN; ?><br>
                            - <?php echo $item->DESKRIPSI_JABATAN; ?><br>
                            - <?php echo $item->NAMA_UNIT_KERJA; ?><br>
                            - <?php echo $item->NAMA_LEMBAGA; ?>
                </td>
                <td valign="top">
                    <div class="jnsdok">
                        <?php 
                            if ($item->TAHUN_PELAPORAN == '0') { 
                                if ($item->JENIS_LAPORAN == '1') {
                                    $ec = 'Calon Penyelenggara Negara';
                                } else if ($item->JENIS_LAPORAN == '2') {
                                    $ec = 'Awal Menjabat';
                                } else if ($item->JENIS_LAPORAN == '3') {
                                    $ec = 'Akhir Menjabat';
                                } else {}
                        ?>
                            <div>LHKPN Khusus : <?= @$ec; ?></div>
                        <?php 
                            }else{
                                if ($item->TAHUN_PELAPORAN !== '0') {
                                    $ec2 = $item->TAHUN_PELAPORAN;
                                 } else {
                                    $ec2 = '-';
                                 }
                         ?>
                            <div>LHKPN Periodik : <?= @$ec2; ?></div>
                        <?php } ?>
                        <div>Jenis Dokumen : <?= @$item->JENIS_DOKUMEN; ?></div>
                        <div>Melalui : <?= @$item->MELALUI; ?></div>
                    </div>
                </td>
            </tr>
            <?php
                    $end = $i;
                }
            ?>
        </tbody>
    </table>
</div>
<?php
	}else{
		echo 'Data Not Found...';
	}
?>	