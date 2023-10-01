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
    <h5 class="">"Harta Bergerak berupa alat transportasi dan mesin"</h5>
</div>
<div class="box-body" id="wrapperHartaBergerak">
	<br/>
	<button type="button" class="btn btn-sm btn-add btn-primary" href="index.php/eaudit/lhkpn_klarifikasi/addhartabergerak/<?php echo $id_lhkpn; ?>" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"></i> Tambah</button>
	<br/>
	<br/>
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="30%">URAIAN</th>
                <th width="30%">KEPEMILIKAN</th>
                <th width="20%">NILAI PEROLEHAN</th>
                <th width="20%">NILAI PELAPORAN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalHartaBergerak = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_BERGERAKS as $hartabergerak) {
                $totalHartaBergerak += $hartabergerak->NILAI_PELAPORAN;
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                        if ($hartabergerak->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartabergerak->STATUS].'</label>';
                        }
                        ?>
                    </td>
                    <td><b>Jenis : </b><?php echo $list_harta[$hartabergerak->KODE_JENIS] ?><br>
                        <b>Merek : </b> <?php echo $hartabergerak->MEREK; ?><br>
                        <b>Model : </b> <?php echo $hartabergerak->MODEL; ?><br>
                        <b>Tahun Pembuatan : </b> <?php echo $hartabergerak->TAHUN_PEMBUATAN; ?><br>
                        <b>No Pol / Registrasi : </b> <?php echo $hartabergerak->NOPOL_REGISTRASI; ?><br>
                    </td>

                    <td>
                        <?php
                        $an = array('', 'PN YANG BERSANGKUTAN', 'PASANGAN / ANAK', 'LAINNYA');
                        if ($hartabergerak->JENIS_BUKTI == '8') {
                            echo 'Bukti Lain';
                        } else {
                            echo '<b>Jenis bukti : </b>'.$list_bukti_alat_transportasi[$hartabergerak->JENIS_BUKTI];
                        }
                        ?><br/><b>a.n : </b><?php echo $an[$hartabergerak->ATAS_NAMA]; ?><br>
                        <b>Asal Usul Harta : </b><br>
                        <?php
                        $exp = explode(',', $hartabergerak->ASAL_USUL);
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
                        <br />
                        <?php
//                        echo $pemanfaatan2[$hartabergerak->PEMANFAATAN];
                        echo map_data_pemanfaatan($hartabergerak->PEMANFAATAN, 2);
                        ?>
                        <br>
                    </td>

                    <td>
                        <div align="right">
                            <?php echo $hartabergerak->SIMBOL; ?>
                            <!-- </div> -->
                            <!-- <div align="right"> -->
                            <?php echo number_format($hartabergerak->NILAI_PEROLEHAN, 0, '', '.'); ?>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            Rp. <?php echo number_format($hartabergerak->NILAI_PELAPORAN, 0, '', '.'); ?>
                        </div>
                    </td>
                    
                                     
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalHartaBergerak, 0, '', '.'); ?></b></td>
            </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#wrapperHartaBergerak .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data Harta Bergerak', html, '', 'standart');
            });
            return false;
        });

//        if ('<?= $survey ?>' == 'no')
//        {
//            $('.hartabergerakYes').prop("checked", false);
//            $('.hartabergerakNo').prop("checked", true);
//            $('#hartabergerakselectYes').attr("disabled", true);
//            f_checkboxVer($('.hartabergerakNo'));
//        } else {
//            $('#hartabergerakselectYes').attr("disabled", false);
//            $('.hartabergerakYes').prop("checked", true);
//            $('.hartabergerakNo').prop("checked", false);
//            f_checkboxVer($('.hartabergerakYes'));
//        }
    });
</script>
