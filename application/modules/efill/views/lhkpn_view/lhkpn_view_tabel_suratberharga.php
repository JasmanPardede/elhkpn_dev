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
<table class="table table-bordered table-hover table-striped table-custom">
    <thead class="table-header">
        <tr>
            <th width="10px">NO</th>
            <th width="150px">KODE JENIS</th>
            <th width="100px">SURAT BERHARGA</th>
            <th width="100px">NAMA PENERBIT</th>
            <th width="100px">KUANTITAS</th>
            <th width="150px">KEPEMILIKAN</th>
            <th width="50px">TAHUN INVESTASI</th>
            <th width="100px">NILAI PEROLEHAN</th>
            <th width="100px">NILAI PELAPORAN</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 0;
            $totalsuratberharga = 0;
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
            foreach ($HARTA_SURAT_BERHARGAS as $suratberharga) {
            $totalsuratberharga += $suratberharga->PELAPORAN;
            ?>
            <tr>
                <td><?php echo ++$i; ?>.</td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            switch($suratberharga->KODE_JENIS){
                                case '1' : echo 'Penyertaan Modal pada Badan Hukum'; break;
                                case '2' : echo 'Investasi'; break;
                            }; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $suratberharga->NAMA_SURAT_BERHARGA; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $suratberharga->NAMA_SURAT_BERHARGA; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>Jumlah</strong> <?php echo $suratberharga->JUMLAH; ?></br>
                            <strong>Satuan</strong> <?php echo $suratberharga->SATUAN; ?></br>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $suratberharga->ATAS_NAMA; ?>
                        </div>
                        <div class="col-sm-12">
                            <?php switch($suratberharga->ASAL_USUL){
                                    case '1' : echo 'Hasil Sendiri'; break;
                                    case '2' : echo 'Warisan'; break;
                                    case '3' : echo 'Hibah'; break;
                                    case '4' : echo 'Hadiah'; break;
                            }; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-md-12">Dari</div>
                        <div class="col-md-12">
                            <?php echo $suratberharga->TAHUN_PEROLEHAN_AWAL; ?>
                        </div>
                        <div class="col-md-12">s/d</div>
                        <div class="col-md-12">
                            <?php echo $suratberharga->TAHUN_PEROLEHAN_AKHIR; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <!-- <div class="row">
                        <div class="col-md-12">
                            
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <?php echo $suratberharga->SIMBOL.' '.number_format($suratberharga->NILAI_PEROLEHAN,0,'','.'); ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php switch($suratberharga->JENIS_NILAI_PELAPORAN){
                                case '1' : echo 'Appraisal'; break;
                                case '2' : echo 'Perkiraan Pasar'; break;
                            }; ?>
                        </div>
                        <div class="col-sm-12" align="right">
                            Rp. <?php echo number_format($suratberharga->NILAI_PELAPORAN,0,'','.'); ?>
                        </div>
                    </div>
                </td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot class="table-footer">
    <tr>
        <td colspan="8">Sub Total/Total</td>
        <td align="right"><b>Rp. <?php echo number_format($totalsuratberharga,0,'','.'); ?></b></td>
    </tr>
    </tfoot>
</table>