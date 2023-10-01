<?php
function printRekap($item, $pre){
	$CI =& get_instance();
	$aJNS = [
		'htb' => 'Harta Tidak Bergerak',
		'hb' => 'Harta Bergerak',
		'hbl' => 'Harta Bergerak Lainnya',
		'hs' => 'Surat Berharga',
		'hk' => 'Kas & Setara Kas',
		'hl' => 'Harta Lainnya',
		'ht' => 'Hutang'
	];
	?>
	<?php
}
?>


<!-- <p style="text-align: center; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;"> -->
<h2 style="font-family: Arial, Helvetica, sans-serif; text-align: center; font-size: 16px; line-height: 1.5; margin: 0px 0px 10px;">LEMBAR PERSETUJUAN</h2>
<!-- </p> -->
<div style="font-family: Arial, Helvetica, sans-serif; text-align: center; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
	 NOMOR  : <?php echo $head->NOMOR_BAP;?>
    <!-- NOMOR  : <?php echo tgl_format(date('Y-m-d')); ?> -->
</div>

 <br><br><br>   
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif; margin-top: 20px; font-size: 16px;">
 Bersama ini diajukan Laporan Harta Kekayaan Penyelenggara Negara (LHKPN) sesuai daftar terlampir untuk diumumkan pada Berita Negara RI Nomor <?php echo $head->NOMOR_PNRI;?> Tanggal <?php echo date('d/m/Y',strtotime($head->TGL_PNRI)); ?> sebanyak <?php echo $bidangpn->jum;?> PN, dengan rincian sebagai berikut :
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif;margin-top: 20px;">
I.	Bidang Eksekutif 	= 	<?= $bidangpn->BDG_KODE == 'E'  ? $bidangpn->jum :  0 ; ?> PN
</div>
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif;margin-top: 20px;">
II.	Bidang Legislatif 	= 	<?= $bidangpn->BDG_KODE == 'L'  ? $bidangpn->jum :  0 ; ?> PN
</div>
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif;margin-top: 20px;">
III.	Bidang Yudikatif	= 	<?= $bidangpn->BDG_KODE == 'Y'  ? $bidangpn->jum :  0 ; ?> PN
</div>
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif;margin-top: 20px;">
IV.	Bidang BUMN/D	=	<?= $bidangpn->BDG_KODE == 'B'  ? $bidangpn->jum :  0 ; ?> PN   
<div>
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif;margin-top: 20px;">
=============================== +
</div>
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif;margin-top: 20px;">
Total     	=	<?= $bidangpn->BDG_KODE   ? $bidangpn->jum :  0 ; ?> PN   
</div>
<br><br><br><br><br><br><br>   
 <div style="display: block;" style="font-family: Arial, Helvetica, sans-serif;margin-top: 20px;">
    <div style="font-family: Arial, Helvetica, sans-serif;width: 30%;text-align: center;float: left;">
        Mengajukan,
        <br>
         Group Head<br><br><br>
        <div>
            <span>(</span>
            <span style="ont-family: Arial, Helvetica, sans-serif;margin-top: 20px;display: inline-block; width: 200px;text-align: center;"><?php echo $direkturpp->NAMA ?></span>
            <!-- <span style="display: inline-block; width: 150px;text-align: center;;"><?php echo $direkturpp->NAMA ?></span> -->
            <span>)</span>
        </div>
    </div>
    <div style="font-family: Arial, Helvetica, sans-serif;width: 30%; text-align: center;float: right;">
        Menyetujui,
        <br>
        Direktur PP LHKPN<br><br><br>
        <div>
            <span>(</span>
            <span style="ont-family: Arial, Helvetica, sans-serif;margin-top: 20px;display: inline-block; width: 150px;text-align: center;;"><?php echo $direkturpencegahan->NAMA ?></span>
            <span>)</span>
        </div>
    </div>
</div>


<style type="text/css">
	td{
		vertical-align: top;
	}

	table {
	      border-collapse: collapse;
	}
	.tblRekap td{
		font-size: 12px;
	}
	.tblRekap th{
		font-size: 12px;
        padding: 5px;
        font-size: 12px;
	}

</style>