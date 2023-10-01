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
 * @package Views/efill/lhkpn_view
*/
?>
<table class="table table-bordered table-hover table-striped" >
	<thead class="table-header">
		<tr >
            <th width="10px">NO</th>
            <th width="30px">Status</th>
			<th width="150px">LOKASI/ALAMAT LENGKAP</th>
			<th width="150px">LUAS TANAH/ BANGUNAN</th>
			<th width="180px">KEPEMILIKAN</th>
			<th width="80px">TAHUN PEROLEHAN</th>
			<th width="200px">NILAI PEROLEHAN</th>
			<th width="170px">NILAI PELAPORAN</th>
		</tr>
	</thead>
	<tbody>
		<?php
        $aStatus    = [
                1 =>  [
                    'label' => 'info',
                    'name' => 'Tetap'
                ],
                2 => [
                    'label' => 'warning',
                    'name' => 'Ubah'
                ],
                3 => [
                    'label' => 'success',
                    'name' => 'Baru'
                ]
            ];
		$i = 0;
		$totalHartaTidakBergerak = 0;
		foreach ($HARTA_TIDAK_BERGERAKS as $hartatidakbergerak) {
            $totalHartaTidakBergerak += $hartatidakbergerak->NILAI_PELAPORAN;
            ?>
            <tr>
                <td><?php echo ++$i;?>.</td>
                <td><span class="label label-<?php echo $aStatus[$hartatidakbergerak->STATUS]['label']?>"><?php echo $aStatus[$hartatidakbergerak->STATUS]['name']?></span></td>
                <td>
                    <?php if($hartatidakbergerak->ID_NEGARA == '1'){ ?>
                    <b>Negara</b><br> <?php echo $hartatidakbergerak->NAMA_NEGARA; ?><br>
                    <?php } ?>
                    <b>Jalan/ No</b><br> <?php echo $hartatidakbergerak->JALAN; ?><br>
                    <?php if($hartatidakbergerak->ID_NEGARA == '2'){ ?>
                    <b>Kel/Desa</b><br> <?php echo $hartatidakbergerak->KEL; ?><br>
                    <b>Kecamatan</b><br> <?php echo $hartatidakbergerak->KEC; ?><br>
                    <b>Kab/Kota</b><br> <?php echo @$hartatidakbergerak->KAB_KOT; ?><br>
                    <b>Prov/Negara</b><br> <?php echo @$hartatidakbergerak->PROV; ?><br>
                    <?php } ?>
                </td>
                <td><b>Tanah</b> <?php echo $hartatidakbergerak->LUAS_TANAH; ?> M<sup>2</sup><br>/ <b>Bangunan</b> <?php echo $hartatidakbergerak->LUAS_BANGUNAN; ?> M<sup>2</sup></td>
                <td>
                    <b>Jenis Bukti : </b><br/>
                    <?php
                    switch($hartatidakbergerak->JENIS_BUKTI){
                        case '1' : echo 'Sertifikat'; break;
                        case '2' : echo 'Akta Jual Beli'; break;
                        case '3' : echo 'Girik'; break;
                        case '4' : echo 'Letter C'; break;
                        case '5' : echo 'Pipil'; break;
                        case '6' : echo 'Lainnya'; break;
                    }; ?>
                    <br>
                    <b>Nomor Bukti : </b> <br/><?php echo $hartatidakbergerak->NOMOR_BUKTI; ?><br>
                    <b>Atas Nama : </b> <br/><?php echo $hartatidakbergerak->ATAS_NAMA; ?><br>
                    <b>Asal Usul Harta : </b> <br/>
                    <?php
                        $exp = explode(',', $hartatidakbergerak->ASAL_USUL);
                        foreach ($exp as $key) {
                            foreach ($asalusul as $au) {
                                if($au->ID_ASAL_USUL == $key){
                                    echo ($au->IS_OTHER === '1' ? '<badge class="label label-danger">'.$au->ASAL_USUL.'</badge>' : $au->ASAL_USUL).'<br>';
                                }
                            }
                        }
                    ?>
                    <br>
                    <b>Pemanfaatan : </b> <br/><?php
                            $expm = explode(',', $hartatidakbergerak->PEMANFAATAN);
                            foreach ($expm as $key) {
                                echo $pemanfaatan1[$key].'<br />';   
                            }
                    ?><br>
                    <b>Ket. Lainnya : </b><br>
                    <?php echo $hartatidakbergerak->KET_LAINNYA; ?>
                </td>
                <td>Dari <?php echo $hartatidakbergerak->TAHUN_PEROLEHAN_AWAL; ?> s/d <?php echo $hartatidakbergerak->TAHUN_PEROLEHAN_AKHIR; ?></td>
                <td>
                   <!--  <div>
                        
                    </div> -->
                    <div align="right">
                        <?=$hartatidakbergerak->SIMBOL?> <?php echo number_format($hartatidakbergerak->NILAI_PEROLEHAN,0,'','.'); ?>
                    </div>
                </td>
                <td>
                    <div>
                    <?php
                    switch($hartatidakbergerak->JENIS_NILAI_PELAPORAN){
                        case '1' : echo 'Appraisal'; break;
                        case '2' : echo 'Perkiraan Pasar'; break;
                    }
                    ?>
                    </div>
                    <div align="right">
                        Rp. <?php echo number_format($hartatidakbergerak->NILAI_PELAPORAN,0,'','.'); ?>
                    </div>
                </td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot class='table-footer'>
	<tr>
		<td colspan="7"><b>Sub Total/Total</b></td>
		<td><b>Rp. <?php echo number_format($totalHartaTidakBergerak,0,'','.');?></b></td>
	</tr>
	</tfoot>
</table>