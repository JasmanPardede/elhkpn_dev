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
 * @package Views/ever/verification
 */
?>
<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Harta Tidak Bergerak berupa Tanah dan/atau bangunan"</h5>
</div>
<div class="box-body" id="wrapperHartaTidakBergerak">
	<br />
    <button type="button" class="btn btn-primary" href="index.php/eaudit/lhkpn_klarifikasi/addhartatidakbergerak/<?php echo $id_lhkpn; ?>" title="Edit" onclick="onButton.go(this, '95%', true);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover table-striped" >
        <thead class="table-header">
            <tr >
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="150px">LOKASI/ALAMAT LENGKAP</th>
                <th width="150px">LUAS</th>
                <th width="180px">KEPEMILIKAN</th>
                <th width="200px">NILAI PEROLEHAN</th>
                <th width="170px">NILAI PELAPORAN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $asal_usul = array('', 'PN YANG BERSANGKUTAN', 'PASANGAN / ANAK', 'LAINNYA');
            $i = 0;
            $totalHartaTidakBergerak = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_TIDAK_BERGERAKS as $hartatidakbergerak) {
                $totalHartaTidakBergerak += $hartatidakbergerak->NILAI_PELAPORAN;
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                        if ($hartatidakbergerak->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartatidakbergerak->STATUS].'</label>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($hartatidakbergerak->ID_NEGARA == '2') {
                            echo $hartatidakbergerak->JALAN . ', ' . $hartatidakbergerak->NAMA_NEGARA;
                        } else if ($hartatidakbergerak->ID_NEGARA == '1') {
                            echo '<b>Jalan/ No : </b>' . $hartatidakbergerak->JALAN . '<br/> <b>Kelurahan : </b> ' .
                            $hartatidakbergerak->KEL . '<br/> <b>Kecamatan : </b> ' .
                            $hartatidakbergerak->KEC . '<br/> <b>Kabupaten/Kota : </b> ' .
                            $hartatidakbergerak->KAB_KOT . '<br/> <b>Provinsi : </b> ' . $hartatidakbergerak->PROV;
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo '<b>Tanah </b>: ' . number_format($hartatidakbergerak->LUAS_TANAH, 0, '', '.'); ?> M<sup>2</sup> 
                        <?php echo '<br/><b>Bangunan </b>: ' . number_format($hartatidakbergerak->LUAS_BANGUNAN, 0, '', '.'); ?> M<sup>2</sup>
                    </td>
                    <td>
                        <b>Jenis Bukti : </b><?php echo $list_bukti[$hartatidakbergerak->JENIS_BUKTI] ?> <br> <b>Nomor :  </b><?php echo $hartatidakbergerak->NOMOR_BUKTI ?> <br>
                        <b>A.n : </b> <?php echo $asal_usul[$hartatidakbergerak->ATAS_NAMA]; ?><br>
                        <b>Asal Usul Harta : </b>
                        <?php
                        $exp = explode(',', $hartatidakbergerak->ASAL_USUL);
                        $text = '';
                        foreach ($exp as $key) {
                            foreach ($asalusul as $au) {
                                if ($au->ID_ASAL_USUL == $key) {
                                    $text .= ($au->IS_OTHER === '1' ? '<font>' . $au->ASAL_USUL . '</font>' : $au->ASAL_USUL) . '&nbsp;,&nbsp;&nbsp;';
                                }
                            }
                        }
                        $rinci = substr($text, 0, -19);
                        echo $rinci;
                        ?>
                        <br>
                        <b>Pemanfaatan : </b>
                        <?php
                        $expm = explode(',', $hartatidakbergerak->PEMANFAATAN);
                        $text1 = '';
                        foreach ($expm as $key) {
                            $text1 .= $pemanfaatan1[$key] . '&nbsp;,&nbsp;&nbsp;';
                        }
                        $rinci1 = substr($text1, 0, -19);
                        echo $rinci1;
                        ?>
                        <br>
                    </td>

                    <td>
                        <!--  <div>
                             
                         </div> -->
                        <div align="right">
                            <?= $hartatidakbergerak->SIMBOL ?> <?php echo number_format($hartatidakbergerak->NILAI_PEROLEHAN, 0, '', '.'); ?>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            Rp. <?php echo number_format($hartatidakbergerak->NILAI_PELAPORAN, 0, '', '.'); ?>
                        </div>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td colspan="6"><b>Sub Total/Total</b></td>
                <td>
                    <div align="right">
                        <b>Rp. <?php echo number_format($totalHartaTidakBergerak, 0, '', '.'); ?></b>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#wrapperHartaTidakBergerak .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data Harta Tidak Bergerak', html, '', 'standart');
            });
            return false;
        });

//        if ('<?= $survey ?>' == 'no')
//        {
//            $('.hartatidakbergerakYes').prop("checked", false);
//            $('.hartatidakbergerakNo').prop("checked", true);
//            $('#hartatidakbergerakselectYes').attr("disabled", true);
//            f_checkboxVer($('.hartatidakbergerakNo'));
//        } else {
//            $('#hartatidakbergerakselectYes').attr("disabled", false);
//            $('.hartatidakbergerakYes').prop("checked", true);
//            $('.hartatidakbergerakNo').prop("checked", false);
//            f_checkboxVer($('.hartatidakbergerakYes'));
//        }
    });
</script>
