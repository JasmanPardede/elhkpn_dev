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
    .title-hartaLain
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-hartaLain">
    <h5 class="">"Harta berupa piutang, kerjasama usaha, HAKI, sewa dibayar dimuka atau hak pengelolaan"</h5>
</div>
<div class="box-body" id="wrapperHartaLain">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th>URAIAN</th>
                <th>ASAL USUL HARTA</th>
                <th>NILAI PEROLEHAN</th>
                <th>NILAI PELAPORAN</th>
                <th width="10px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalLainya = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_LAINNYAS as $hartalainnya) {
                $totalLainya += str_replace('.', '', $hartalainnya->NILAI_PELAPORAN);
                ?>
                <tr>
                    <td><?= ++$i; ?>.</td>
                    <td><?php 
                        if ($hartalainnya->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartalainnya->STATUS].'</label>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $img = null;
                        if ($hartalainnya->FILE_BUKTI) {
                            if (file_exists($hartalainnya->FILE_BUKTI)) {
                                $img = "  <a target='_blank' href='" . base_url() . '' . $hartalainnya->FILE_BUKTI . "'><i class='fa fa-download'></i></a>";
                            }
                        }
                        
                        $uraian = "
					<table class='table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $hartalainnya->NAMA . "  " . $img . "</td>
						 </tr>
						 <tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . @$hartalainnya->KETERANGAN . "</td>
						 </tr>
					</table>
				";
                        
                        echo $uraian;
                        ?>
                    </td>
                   
                    <td>
                       
                            
                                <?php
                                $exp = explode(',', $hartalainnya->ASAL_USUL);
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
                          
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <?php echo $hartalainnya->SIMBOL . ' ' . number_format($hartalainnya->NILAI_PEROLEHAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                Rp. <?php echo number_format($hartalainnya->NILAI_PELAPORAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $hartalainnya->ID; ?>/hartalainnya" >Upload</button>
                    </td>				
                    			  	
                </tr>
                    <?php } ?>
        </tbody>
        <tf oot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalLainya, 0, '', '.'); ?></b></td>
            </tr>
        </tfoot>
    </table> 
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#wrapperHartaLain .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data Harta Lainnya', html, '', 'standart');
            });
            return false;
        });

//        if ('<?= $survey ?>' == 'no')
//        {
//            $('.hartalainnyaYes').prop("checked", false);
//            $('.hartalainnyaNo').prop("checked", true);
//            $('#hartalainnyaselectYes').attr("disabled", true);
//            f_checkboxVer($('.hartalainnyaNo'));
//        } else {
//            $('#hartalainnyaselectYes').attr("disabled", false);
//            $('.hartalainnyaYes').prop("checked", true);
//            $('.hartalainnyaNo').prop("checked", false);
//            f_checkboxVer($('.hartalainnyaYes'));
//        }
    });
</script>
