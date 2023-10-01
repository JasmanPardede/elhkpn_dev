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
	                            $totalHartaBergerak = 0;
	                            foreach ($HARTA_BERGERAKS as $hartabergerak) {
                $totalHartaBergerak += $hartabergerak->NILAI_PELAPORAN;

            ?>
            <tr>
                <td><?php echo ++$i;?>.</td>
                <td>
                    <?php
                    echo $list_harta[$hartabergerak->KODE_JENIS]
                     ?>
                </td>
                <td>
                    <b>Merek</b> <?php echo $hartabergerak->MEREK;?><br>
                    <b>Model</b> <?php echo $hartabergerak->MODEL;?><br>
                    <b>Tahun Pembuatan</b> <?php echo $hartabergerak->TAHUN_PEMBUATAN;?><br>
                    <b>No. Pol. / Registrasi</b> <?php echo $hartabergerak->NOPOL_REGISTRASI;?><br>
                </td>
                <td>
                    <b>Jenis Bukti : </b><br/>
                    <?php
                    echo $list_bukti[$hartabergerak->JENIS_BUKTI];
                     ?><br>
                    <b>Nomor Bukti : </b> <br/><?php echo $hartabergerak->NOMOR_BUKTI; ?><br>
                    <b>Atas Nama : </b><br/><?php echo $hartabergerak->NAMA; ?><br>
                    <b>Asal Usul Harta : </b> <br/><?php
                    $exp = explode(',', $hartabergerak->ASAL_USUL);
                    foreach ($exp as $key) {
                        foreach ($asalusul as $au) {
                            if($au->ID_ASAL_USUL == $key){
                                echo ($au->IS_OTHER === '1' ? '<badge class="label label-danger">'.$au->ASAL_USUL.'</badge>' : $au->ASAL_USUL).'<br>';
                            }
                        }
                    }
                     ?><br>
                    <b>Pemanfaatan : </b> <br/><?php
                    echo $pemanfaatan2[$hartabergerak->PEMANFAATAN];
                     ?><br>
                    <b>Ket. Lainnya</b><br>
                    <?php echo $hartabergerak->KET_LAINNYA; ?>
                </td>
                <td>Dari <?php echo $hartabergerak->TAHUN_PEROLEHAN_AWAL; ?> s/d <?php echo $hartabergerak->TAHUN_PEROLEHAN_AKHIR; ?></td>
                <td>
                    <div align="rright">
                        <?php echo $hartabergerak->SIMBOL; ?>
                    <!-- </div> -->
                    <!-- <div align="right"> -->
                        <?php echo number_format($hartabergerak->NILAI_PEROLEHAN,0,'','.') ; ?>
                    </div>
                </td>
                <td>
                    <div>
                        <?php
                        switch($hartabergerak->JENIS_NILAI_PELAPORAN){
                            case '1' : echo 'Appraisal'; break;
                            case '2' : echo 'Perkiraan Pasar'; break;
                        }
                        ?> 
                    </div>
                    <div align="right">
                        Rp. <?php echo number_format($hartabergerak->NILAI_PELAPORAN,0,'','.'); ?></td>
                    </div>
	                                </tr>
	                <?php } ?>
	        </tbody>
	        <tfoot class="table-footer">
	            <tr>
	                <td colspan="6"><b>Sub Total/Total</b></td>
	                <td><b>Rp. <?php echo number_format($totalHartaBergerak,0,'','.');?></b></td>
	            </tr>
	        </tfoot>
	    </table>