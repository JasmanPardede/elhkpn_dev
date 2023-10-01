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
	<table class="table table-bordered table-hover table-striped">
	        <thead class="table-header">
	            <tr>
	                <th width="10px">NO</th>
	                <th width="180px">KODE JENIS</th>
	                <th width="180px">IDENTITAS HARTA</th>
	                <th width="180px">KEPEMILIKAN</th>
	                <th width="80px">TAHUN PEROLEHAN</th>
	                <th width="170px">NILAI PEROLEHAN</th>
	                <th width="170px">NILAI PELAPORAN</th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php
	                            $i = 0;
	                            $totalHartaBergerak2 = 0;
	                            foreach ($HARTA_BERGERAK_LAINS as $hartabergerak2) {
                $totalHartaBergerak2 += str_replace('.', '', $hartabergerak2->NILAI_PELAPORAN);
            ?>
            <tr>
                <td><?php echo ++$i;?>.</td>
                <td>
                    <?php
                    switch($hartabergerak2->KODE_JENIS){
                            case '1' : echo 'Perabotan Rumah Tangga'; break;
                            case '2' : echo 'Barang Elektronik'; break;
                            case '3' : echo 'Perhiasan & Logam / Batu Mulia'; break;
                            case '4' : echo 'Barang Seni / Antik'; break;
                            case '5' : echo 'Persediaan'; break;
                            case '6' : echo 'Harta Bergerak Lainnya'; break;
                    } ?>
                </td>
                <td>
                    <b>Nama : </b> <?php echo $hartabergerak2->NAMA;?><br>
                    <b>Jumlah : </b> <?php echo $hartabergerak2->JUMLAH;?><br>
                    <b>Satuan : </b> <?php echo $hartabergerak2->SATUAN;?><br>
                    <b>Keterangan : </b> <?php echo $hartabergerak2->KETERANGAN;?><br>
                </td>
                <td>
                    <b>Atas Nama : </b> <?php echo $hartabergerak2->ATAS_NAMA; ?><br>
                    <b>Asal Usul Harta : </b> <?php
                    switch($hartabergerak2->ASAL_USUL){
                        case '1' : echo 'Hasil Sendiri'; break;
                        case '2' : echo 'Warisan'; break;
                        case '3' : echo 'Hibah'; break;
                        case '4' : echo 'Hadiah'; break;
                    } ?><br>
                    <b>Pemanfaatan : </b> <?php
                    switch($hartabergerak2->PEMANFAATAN){
                        case '7' : echo 'Digunakan Sendiri'; break;
                        case '8' : echo 'Tidak digunakan sendiri & menghasilkan'; break;
                        case '9' : echo 'Tidak digunakan sendiri & tidak menghasilkan'; break;
                        case '0' : echo 'Lainnya'; break;
                    }; ?><br>
                    Ket. Lainnya <br>
                </td>
                <td>Dari <?php echo @$hartabergerak2->TAHUN_PEROLEHAN_AWAL; ?> s/d <?php echo @$hartabergerak2->TAHUN_PEROLEHAN_AKHIR; ?></td>
                <td>
                    <div><?php //echo $aMata[$hartabergerak2->MATA_UANG]; ?></div>
                    <div align="right"><?php echo $hartabergerak2->SIMBOL.' '.number_format($hartabergerak2->NILAI_PEROLEHAN,0,'','.'); ?></div>
                </td>
                <td>
                    <div>
                        <?php
                        switch($hartabergerak2->JENIS_NILAI_PELAPORAN){
                            case '1' : echo 'Appraisal'; break;
                            case '2' : echo 'Perkiraan Pasar'; break;
                        }
                        ?>
                    </div>
                    <div align="right">
                        Rp. <?php echo number_format($hartabergerak2->NILAI_PELAPORAN,0,'','.'); ?>        
                    </div>
                </td>
	                                </tr>
	                <?php } ?>
	        </tbody>
	        <tfoot class="table-footer">
	            <tr>
	                <td colspan="6"><b>Sub Total/Total</b></td>
	                <td><b>Rp. <?php echo @number_format($totalHartaBergerak2,0,'','.');?></b></td>
	            </tr>
	        </tfoot>
	    </table> 