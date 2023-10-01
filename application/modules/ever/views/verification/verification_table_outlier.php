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
    .highlights
    {
      background-color: #FFFF00; 
    }
    .highlightno
    {
      background-color: #FFFFFF;
      
    }
</style>
<div class="box-header with-border portlet-header title-alat">
<h5 class="">"Informasi Terkait Outlier dari Hasil Analisa Menggunakan <strong><i>Artificial Intelligence (AI)</i></strong>"</h5>
</div>
<div class="box-body" id="wrapperHartaTidakBergerak">
<?php  foreach ($tmpAPI as $key => $ai) { $json = json_decode($ai->response); ?>
    <table class="table table-bordered table-hover table-striped" >
        <thead class="table-header">
            <tr >
                <th width="20px">KATEGORI HARTA</th>
                <th width="10%">NILAI SUBTOTAL HARTA <br> Tahun <?php echo $json->_detailed_report->tahun_wl - 1 ;?> (Rp)</th>
                <th width="10%">NILAI SUBTOTAL HARTA <br> Tahun <?php echo $json->_detailed_report->tahun_wl ;?> (Rp)</th>
                <th width="30%">CATATAN OUTLIER HARTA <br> (Pengecekan nilai subtotal per kategori harta di tahun pelaporan) </th>
                <th width="30%">CATATAN OUTLIER RATIO <br> (Perbandingan dengan laporan sebelumnya)</th>
            </tr>
        </thead>
        <tbody>
                        <tr>  
                            <td style="font-weight: bolder;">Harta Tidak Bergerak</td>
                            <td style=" text-align: right;">Rp. <?php echo number_format($json->_request->previous_year->total_harta_tidak_bergerak, 0, ",", "."); ?></td>
                            <td style=" text-align: right"><?php if($json->anomaly->total_harta_tidak_bergerak == true || $json->diff_anomaly->total_harta_tidak_bergerak == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_harta_tidak_bergerak, 0, ",", "."); ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_harta_tidak_bergerak == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_harta_tidak_bergerak == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
                        <tr>  
                            <td style="font-weight: bolder;">Alat Transportasi dan Mesin</td>
                            <td style=" text-align: right">Rp. <?php echo number_format($json->_request->previous_year->total_alat_transportasi, 0, ",", "."); ?></td>
                            <td style=" text-align: right"><?php if($json->anomaly->total_alat_transportasi == true || $json->diff_anomaly->total_alat_transportasi == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_alat_transportasi, 0, ",", "."); ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_alat_transportasi == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_alat_transportasi == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
                          <tr>  
                            <td style="font-weight: bolder;">Harta Bergerak Lainnya</td>
                            <td style=" text-align: right">Rp. <?php echo number_format($json->_request->previous_year->total_harta_bergerak_lain, 0, ",", "."); ?></td>
                            <td style=" text-align: right"><?php if($json->anomaly->total_harta_bergerak_lain == true || $json->diff_anomaly->total_harta_bergerak_lain == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_harta_bergerak_lain, 0, ",", "."); ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_harta_bergerak_lain == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_harta_bergerak_lain == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
                        <tr>  
                            <td style="font-weight: bolder;">Surat Berharga</td>
                            <td style=" text-align: right">Rp. <?php echo number_format($json->_request->previous_year->total_surat_berharga, 0, ",", "."); ?></td>
                            <td style=" text-align: right"><?php if($json->anomaly->total_surat_berharga == true || $json->diff_anomaly->total_surat_berharga == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_surat_berharga, 0, ",", "."); ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_surat_berharga == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_surat_berharga == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
                        <tr>  
                            <td style="font-weight: bolder;">Kas dan Setara Kas</td>
                            <td style=" text-align: right">Rp. <?php echo number_format($json->_request->previous_year->total_kas, 0, ",", "."); ?></td>
                            <td style=" text-align: right"><?php if($json->anomaly->total_kas == true || $json->diff_anomaly->total_kas == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_kas, 0, ",", "."); ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_kas == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_kas == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
                        <tr>  
                            <td style="font-weight: bolder;">Harta Lainnya</td>
                            <td style=" text-align: right">Rp. <?php echo number_format($json->_request->previous_year->total_harta_lainnya, 0, ",", "."); ?></td>
                            <td style=" text-align: right"><?php if($json->anomaly->total_harta_lainnya == true || $json->diff_anomaly->total_harta_lainnya == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_harta_lainnya, 0, ",", "."); ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_harta_lainnya == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_harta_lainnya == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
                        <tr>  
                            <td style="font-weight: bolder;">Hutang</td>
                            <td style=" text-align: right">Rp. <?php echo number_format($json->_request->previous_year->total_hutang, 0, ",", "."); ?></td >
                            <td style=" text-align: right"><?php if($json->anomaly->total_hutang == true || $json->diff_anomaly->total_hutang == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_hutang, 0, ",", "."); ?></p></td >
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_hutang == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_hutang == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
                        <tr>  
                            <td style="font-weight: bolder;">Total Harta Kekayaan</td>
                            <td style=" text-align: right">Rp. <?php echo number_format($json->_request->previous_year->total_harta_kekayaan, 0, ",", "."); ?></td >
                            <td style=" text-align: right"><?php if($json->anomaly->total_harta_kekayaan == true || $json->diff_anomaly->total_harta_kekayaan == true){  ?><p class="highlights"> <?php }else{?> <p class="highlightno"> <?php } ?>Rp. <?php echo number_format($json->_request->current_year->total_harta_kekayaan, 0, ",", "."); ?></p></td >
                            <td style="font-weight: bolder;"><?php if($json->anomaly->total_harta_kekayaan == true){  ?><p class="highlights"> Terdapat data yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                            <td style="font-weight: bolder;"><?php if($json->diff_anomaly->total_harta_kekayaan == true){  ?><p class="highlights"> Terdapat selisih signifikan yang perlu dilakukan pengecekan ulang <?php }else{?> <p class="highlightno"> <?php } ?></p></td>
                        </tr>
        </tbody>
    </table>
    <?php } ?>
</div>
