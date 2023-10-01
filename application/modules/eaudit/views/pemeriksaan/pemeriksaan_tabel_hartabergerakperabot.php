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
    .title-harta-perabotan
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-harta-perabotan">
    <h5 class="">"Harta Bergerak berupa perabotan rumah Tangga, barang elektronik, perhiasan & logam/batu mulia, barang seni/antik, persediaan dan harta bergerak lainnya"</h5>
</div><!-- /.box-header -->
<div class="box-body" id="wrapperHartaBergerak2">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="35%">URAIAN</th>
                <th width="35%x">KEPEMILIKAN</th>
                <th width="15%">NILAI PEROLEHAN</th>
                <th width="15%">NILAI PELAPORAN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalHartaBergerak2 = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_BERGERAK_LAINS as $hartabergerak2) {
                $totalHartaBergerak2 += str_replace('.', '', $hartabergerak2->NILAI_PELAPORAN);
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                        if ($hartabergerak2->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartabergerak2->STATUS].'</label>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->NAMA . "</td>
						 </tr>
						 <tr>
						    <td><b>Jumlah</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->JUMLAH . "</td>
						 </tr>
						 <tr>
						    <td><b>Satuan</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->SATUAN . "</td>
						 </tr>
						 <tr>
						    <td><b>Ket Lainnya</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->KETERANGAN . "</td>
						 </tr>
					</table>
				";
                       echo $uraian;
                        ?>
                    </td>
                    
                    <td>
                        <div class="col-sm-12"><b>Asal Usul Harta : </b>
                            <?php
                            $exp = explode(',', $hartabergerak2->ASAL_USUL);
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
                        </div>
                        
                    </td>
                   
                    <td>
                        <div align="right"><?php echo $hartabergerak2->SIMBOL . ' ' . number_format($hartabergerak2->NILAI_PEROLEHAN, 0, '', '.'); ?></div>
                    </td>
                    <td>
                        <div align="right">
                            Rp. <?php echo number_format($hartabergerak2->NILAI_PELAPORAN, 0, '', '.'); ?>        
                        </div>
                   
                    	                                    
                </tr>
<?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalHartaBergerak2, 0, '', '.'); ?></b></td>
            </tr>
        </tfoot>
    </table> 
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#wrapperHartaBergerak2 .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data Harta Bergerak Perabot', html, '', 'standart');
            });
            return false;
        });

//        if ('<?= $survey ?>' == 'no')
//        {
//            $('.hartabergerakperabotYes').prop("checked", false);
//            $('.hartabergerakperabotNo').prop("checked", true);
//            $('#hartabergerakperabotselectYes').attr("disabled", true);
//            f_checkboxVer($('.hartabergerakperabotNo'));
//        } else {
//            $('#hartabergerakperabotselectYes').attr("disabled", false);
//            $('.hartabergerakperabotYes').prop("checked", true);
//            $('.hartabergerakperabotNo').prop("checked", false);
//            f_checkboxVer($('.hartabergerakperabotYes'));
//        }
    });
</script>
