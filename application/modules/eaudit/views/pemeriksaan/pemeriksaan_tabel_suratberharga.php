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
 * @update - PT.WADITRA REKA CIPTA BANDUNG
 * @package Views/ever/verification
 */
?>
<style type="text/css">
    .title-surat-berharga
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-surat-berharga">
    <h5 class="">"Harta berupa Surat Berharga, contoh penyertaan modal saham dan investasi"</h5>
</div><!-- /.box-header -->
<div class="box-body" id="wrapperSuratBerharga">
    <table class="table table-bordered table-hover table-striped table-custom">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th >URAIAN</th>
                <th >NO REKENING/ NO NASABAH</th>
                <th >ASAL USUL HARTA</th>
                <th >NILAI PEROLEHAN</th>
                <th >NILAI PELAPORAN</th>
                <!--<th>HASIL</th>-->
                <th width="10px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalSuratBerharga = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_SURAT_BERHARGAS as $suratberharga) {
                $totalSuratBerharga += str_replace('.', '', $suratberharga->NILAI_PELAPORAN);
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                        if ($suratberharga->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            echo '<label class=\'label label-success\'>'.$stat[$suratberharga->STATUS].'</label>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $img = null;
                        if ($suratberharga->FILE_BUKTI) {
                            if (file_exists($suratberharga->FILE_BUKTI)) {
                                $img = "  <a target='_blank' href='" . base_url() . '' . $suratberharga->FILE_BUKTI . "'><i class='fa fa-download'></i></a>";
                            }
                        }



                        $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->NAMA . "  " . $img . "</td>
						 </tr>
						  <tr>
						    <td><b>Atas Nama</b></td>
                            <td>:</td>
                            <td>" . map_data_atas_nama($suratberharga->ATAS_NAMA) . "</td>
						 </tr>
						  <tr>
						    <td><b>Penerbit / Perusahaan</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->NAMA_PENERBIT . "</td>
						 </tr>
						  <tr>
						    <td><b>Custodion / Sekuritas</b></td>
                            <td>:</td>
                            <td>" . $suratberharga->CUSTODIAN . "</td>
						 </tr>
					</table>
				";
                        echo $uraian;
                        ?>


                    </td>

                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $suratberharga->NOMOR_REKENING; ?>
                            </div>
                        </div>
                    </td>

                    <td>

                        <?php
                        $exp = explode(',', $suratberharga->ASAL_USUL);
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
                        <!-- <div class="row">
                            <div class="col-md-12">
                                
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <?php echo $suratberharga->SIMBOL . ' ' . number_format($suratberharga->NILAI_PEROLEHAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                Rp. <?php echo number_format($suratberharga->NILAI_PELAPORAN, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <!--<td align="center">-->
                        <?php
//                        $disable_cek = '';
//                        if (@$hasilVerifikasi['suratberharga']['hasil'][$suratberharga->ID] == 1) {
//                            echo '<i class="fa fa-check-square" style="cursor: pointer; color: blue;" title="' . (@$hasilVerifikasi['suratberharga']['catatan'][$suratberharga->ID]) . '"></i>';
//                            $disable_cek = (@$hasilVerifikasi['suratberharga']['editable'][$suratberharga->ID] == '0' ? 'disabled' : '');
//                        } else if (@$hasilVerifikasi['suratberharga']['hasil'][$suratberharga->ID] == -1) {
//                            $survey = 'no';
//                            echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;" title="' . (@$hasilVerifikasi['suratberharga']['catatan'][$suratberharga->ID]) . '"></i>';
//                        } else {
//                            
//                        }
                        ?>
                    <!--</td>-->     

<!--                    <td>
                        <?php
//                        if ($disable_cek != '') {
                            ?>
                            <button type="button" class="btn btn-sm btn-success" disabled>Cek</button>
                            <?php
//                        } else {
                            ?>
                            <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/veritem/<?php echo $suratberharga->ID; ?>/suratberharga" >Cek</button>
                            <?php
//                        }
                        ?>
                    </td>                 -->
                    <td>
                        <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $suratberharga->ID; ?>/suratberharga" >Upload</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="6"><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalSuratBerharga, 0, '', '.'); ?></b></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table> 
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#wrapperSuratBerharga .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Upload Dokumen Pendukung Surat Berharga', html, '', 'standart');
            });
            return false;
        });

//        if ('<?= $survey ?>' == 'no')
//        {
//            $('.suratberhargaYes').prop("checked", false);
//            $('.suratberhargaNo').prop("checked", true);
//            $('#suratberhargaselectYes').attr("disabled", true);
//            f_checkboxVer($('.suratberhargaNo'));
//        } else {
//            $('#suratberhargaselectYes').attr("disabled", false);
//            $('.suratberhargaYes').prop("checked", true);
//            $('.suratberhargaNo').prop("checked", false);
//            f_checkboxVer($('.suratberhargaYes'));
//        }
    });
</script>
