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
		                <th width="180px">ATAS NAMA</th>
		                <th width="180px">NAMA BANK / LEMBAGA LAINNYA / NOMOR REKENING</th>
		                <th width="80px">TAHUN BUKA REKENING</th>
		                <th width="180px">ASAL USUL HARTA</th>
		                <th width="170px">SALDO SAAT PELAPORAN</th>
		            </tr>                 
		        </thead>
		        <tbody>
		            <?php
		            $i = 0;
		            foreach ($HARTA_KASS as $kas) {
            ?>
            <tr>
                <td><?=++$i?>.</td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php switch($kas->KODE_JENIS){
                            case '1' : echo 'Uang Tunai'; break;
                            case '2' : echo 'Deposite'; break;
                            case '3' : echo 'Giro'; break;
                            case '4' : echo 'Tabungan'; break;
                            case '5' : echo 'Lainnya'; break;
                            }; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $kas->ATAS_NAMA_REKENING; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            Bank
                            <?php echo $kas->NAMA_BANK; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            No Rekening
                            <?php echo $kas->NOMOR_REKENING; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-12">
                            Tahun
                        </div>
                        <div class="col-md-12">
                            <?php echo $kas->TAHUN_BUKA_REKENING; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                                $kasz = '';
                                $kass = explode(',', $kas->ASAL_USUL);
                                foreach ($kass as $key) {
                                    if($key == '1') {
                                        $kasz .= 'Hasil Sendiri <br>';
                                    } elseif ($key == '2') {
                                        $kasz .= 'Warisan <br>';
                                    } elseif ($key == '3') {
                                        $kasz .= 'Hibah <br>';
                                    } elseif ($key == '4') {
                                        $kasz .= 'Hadiah <br>';
                                    }
                                }

                                echo $kasz;
                            ?>
<!--                             <?php switch($kas->ASAL_USUL){
                            case '1' : echo 'Hasil Sendiri'; break;
                            case '2' : echo 'Warisan'; break;
                            case '3' : echo 'Hibah'; break;
                            case '4' : echo 'Hadiah'; break;
                            }; ?> -->
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <?php echo $kas->SIMBOL.' '.number_format($kas->NILAI_SALDO,0,'','.'); ?>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12" align="right">
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12">
                            Ekuivalen
                        </div>
                        <div class="col-md-12" align="right">
                            Rp <?php echo number_format($kas->NILAI_EQUIVALEN,0,'','.'); ?>
                        </div>
                    </div>
                </td>
		                </tr>
		                <?php } ?>
		        </tbody>
		    </table>