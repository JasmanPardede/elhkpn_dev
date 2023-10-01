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
    .title-kas
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-kas">
    <h5 class="">"Harta berupa kas atau setara kas (deposito, giro, tabungan, atau lainnya)"</h5>
</div><!-- /.box-header -->
<div class="box-body" id="wrapperKas">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="250px">URAIAN</th>
                <th>ATAS NAMA</th>
                <th>ASAL USUL HARTA</th>
                <th width="170px">SALDO SAAT PELAPORAN</th>
                <!--<th>HASIL</th>-->
                <th width="10px">Aksi</th>
            </tr>                 
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalKas = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_KASS as $kas) {
                $totalKas += $kas->NILAI_EQUIVALEN;
                ?>
                <tr>
                    <td><?= ++$i ?>.</td>
                    <td><?php 
                        if ($kas->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            echo '<label class=\'label label-success\'>'.$stat[$kas->STATUS].'</label>';
                        }
                        ?>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                $image = null;
                                if ($kas->FILE_BUKTI) {
                                    if (file_exists($kas->FILE_BUKTI)) {
                                        $image = '<a target="_blank" href="' . base_url() . '' . $kas->FILE_BUKTI . '"><i class="fa fa-download"></i></a>';
                                    }
                                }


                                $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td width='25%'><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $kas->NAMA . " " . $image . "</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Keterangan</b></td>
                            <td>:</td>
                              <td>-</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Nama Bank / Lembaga</b></td>
                            <td>:</td>
                           <td>" . $kas->NAMA_BANK . "</td>
						 </tr>
					</table>
				";


                                echo $uraian;
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                $info_rekening = "
					<table class='table-child table-condensed'>
						<tr>
						    <td width='25%'><b>Nomor</b></td>
                            <td>:</td>
                            <td>" . $kas->NOMOR_REKENING . "</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Atas Nama</b></td>
                            <td>:</td>
                            <td>" . map_data_atas_nama($kas->ATAS_NAMA_REKENING) . "</td>
						 </tr>
						 <tr>
						    <td width='25%'><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $kas->KETERANGAN . "</td>
						 </tr>
					</table>
				";

                                echo $info_rekening;
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                switch ($kas->ASAL_USUL) {
                                    case '1' : echo 'Hasil Sendiri';
                                        break;
                                    case '2' : echo 'Warisan';
                                        break;
                                    case '3' : echo 'Hibah';
                                        break;
                                    case '4' : echo 'Hadiah';
                                        break;
                                };
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12">
                                <b>  Nilai Saldo</b>
                            </div>
                            <div class="col-md-12" align="right">
                                <?php if ($kas->IS_PELEPASAN == 1) { ?>
                                <?php echo $kas->SIMBOL . ' ' . number_format($kas->NILAI_EQUIVALEN, 0, ",", "."); ?>
                                <?php } else { ?>
                                <?php echo $kas->SIMBOL . ' ' . number_format($kas->NILAI_SALDO, 0, ",", "."); ?>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($kas->MATA_UANG != 1) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <b> Ekuivalen</b>
                                </div>
                                <div class="col-md-12" align="right">
                                    Rp <?php echo number_format($kas->NILAI_EQUIVALEN, 0, ",", "."); ?>
                                </div>
                            </div><?php } ?>
                    </td>
<!--                    <td align="center">
                        <?php
//                        $disable_cek = '';
//                        if (@$hasilVerifikasi['kas']['hasil'][$kas->ID] == 1) {
//                            echo '<i class="fa fa-check-square" style="cursor: pointer; color: blue;" title="' . (@$hasilVerifikasi['kas']['catatan'][$kas->ID]) . '"></i>';
//                            $disable_cek = (@$hasilVerifikasi['kas']['editable'][$kas->ID] == '0' ? 'disabled' : '');
//                        } else if (@$hasilVerifikasi['kas']['hasil'][$kas->ID] == -1) {
//                            $survey = 'no';
//                            echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;" title="' . (@$hasilVerifikasi['kas']['catatan'][$kas->ID]) . '"></i>';
//                        } else {
//                            
//                        }
                        ?>
                    </td>	

                    <td>
                        <?php
//                        if ($disable_cek != '') {
                            ?>
                            <button type="button" class="btn btn-sm btn-success" disabled>Cek</button>
                            <?php
//                        } else {
                            ?>
                            <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/veritem/<?php echo $kas->ID; ?>/kas" >Cek</button>
                            <?php
//                        }
                        ?>
                    </td> -->
                    <td>
                        <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $kas->ID; ?>/kas" >Upload</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalKas, 0, '', '.'); ?></b></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>		    
<script type="text/javascript">
    $(document).ready(function() {
        $("#wrapperKas .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data KAS', html, '', 'standart');
            });
            return false;
        });

//        if ('<?= $survey ?>' == 'no')
//        {
//            $('.kasYes').prop("checked", false);
//            $('.kasNo').prop("checked", true);
//            $('#KasselectYes').attr("disabled", true);
//            f_checkboxVer($('.kasNo'));
//        } else {
//            $('#KasselectYes').attr("disabled", false);
//            $('.kasYes').prop("checked", true);
//            $('.kasNo').prop("checked", false);
//            f_checkboxVer($('.kasYes'));
//        }
    });
</script>
