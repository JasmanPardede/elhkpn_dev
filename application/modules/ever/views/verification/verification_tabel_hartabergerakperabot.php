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
    <h5 class="">"Harta Bergerak berupa perabotan rumah Tangga, barang elektronik, perhiasan &amp; logam/batu mulia, barang seni/antik, persediaan dan harta bergerak lainnya"</h5>
</div>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataHartaBergerakLain" href="index.php/ever/verification_edit/update_harta_bergerak_lain/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperHartaBergerak2">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="30%">URAIAN</th>
                <th width="30%">KEPEMILIKAN</th>
                <th width="20%">NILAI PEROLEHAN</th>
                <th width="20%">NILAI PELAPORAN</th>
                <?php if ($viaa == '1'): ?>
                    <th>AKSI</th>
                <?php endif ?>
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

                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->               
                <?php 
                    if ($hartabergerak2->IS_PELEPASAN == '1'){ ?>
                        <tr style="background-color:#808080;color:#fff">
                <?php       }else{  ?>
                    <tr >
                <?php       } ?>

                    <td><?php echo ++$i; ?>.</td>
                    <td><?php
                        if ($hartabergerak2->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            if ($hartabergerak2->STATUS == '1'){
                             echo '<label class=\'label label-primary\'>'.$stat[$hartabergerak2->STATUS].'</label>';}
                            else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartabergerak2->STATUS].'</label>';}
                        }
                        ?>
                    </td>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->   
                
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
                         <tr>
						    <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->TAHUN_PEROLEHAN_AWAL . "</td>
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
                        <div align="right">
                            <p id="PEROLEHAN_HBP_<?php echo $i; ?>" class="NILAI_PEROLEHAN_HBP">
                                <?php echo $hartabergerak2->SIMBOL . ' ' . number_format($hartabergerak2->NILAI_PEROLEHAN, 0, '', '.'); ?>
                            </p>
                        </div>
                        <div>
                            <p name="TERBILANG_PEROLEHAN_HBP_<?php echo $i; ?>" id="TERBILANG_PEROLEHAN_HBP_<?php echo $i; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            <p id="PELAPORAN_HBP_<?php echo $i; ?>" class="NILAI_PELAPORAN_HBP">
                                Rp. <?php echo number_format($hartabergerak2->NILAI_PELAPORAN, 0, '', '.'); ?>        
                            </p>
                        </div>
                        <div>
                            <p name="TERBILANG_PELAPORAN_HBP_<?php echo $i; ?>" id="TERBILANG_PELAPORAN_HBP_<?php echo $i; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <?php if ($viaa == '1'): ?>
                    <td align="center">
                        <button type="button" class="btn btn-primary" href="index.php/ever/verification_edit/update_harta_bergerak_lain/<?php echo $hartabergerak2->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $hartabergerak2->ID; ?>/harta_bergerak_lain" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                    </td>
                    <?php endif ?>
                   
                    	                                    
                </tr>
<?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td>
                    <div class="text-right">
                        <p id="TOTAL_HBP" class="NILAI_TOTAL_HBP">
                            <b>Rp. <?php echo number_format($totalHartaBergerak2, 0, '', '.'); ?></b>
                        </p>
                    </div>
                    <div>
                        <p name="TERBILANG_TOTAL_HBP" id="TERBILANG_TOTAL_HBP" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                    </div>
                </td>
                <?php if ($viaa == '1'): ?>
                    <td></td>
                <?php endif ?>
            </tr>
        </tfoot>
    </table> 
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#verifEditDataHartaBergerakLain").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data Harta Bergerak Lainnya', html, null, 'large');
            });            
            return false;
        });
    });

    showFieldEver('.NILAI_PEROLEHAN_HBP','#TERBILANG_');
    showFieldEver('.NILAI_PELAPORAN_HBP','#TERBILANG_');
    showFieldEver('.NILAI_TOTAL_HBP','#TERBILANG_');

</script>
