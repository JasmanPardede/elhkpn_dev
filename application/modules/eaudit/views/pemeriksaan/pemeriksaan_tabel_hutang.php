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
    .title-hutang
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-hutang">
    <h5 class="">"Hutang apabila ada"</h5>
</div>
<div class="box-body" id="wrapperHutang">
    <table class="table table-bordered table-hover">
        <thead class="table-header">
            <tr>
                <th>NO</th>
                <th>STATUS</th>
                <th>URAIAN</th>
                <th>NAMA KREDITUR</th>
                <th>BENTUK AGUNAN/ NO KARTU KREDIT</th>
                <th>SALDO HUTANG SAAT PELAPORAN</th>
            </tr>                 
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $tot = 0;
            $i = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HUTANGS as $hutang) {
                $tot += $hutang->SALDO_HUTANG;
                ?>
                <tr>
                    <td><?= ++$i ?>.</td>
                    <td><?php 
                        if ($hutang->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            echo '<label class=\'label label-success\'>'.$stat[$hutang->STATUS].'</label>';
                        }
                        ?>
                    </td>
                    <td><?php
                        $jenis = "
					<table class='table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                                                    <td>:</td>
                                                    <td>" . $hutang->NAMA . "</td>
						 </tr>
						 <tr>
						    <td><b>Atas Nama</b></td>
                                                    <td>:</td>
                                                    <td>" . ($hutang->ATAS_NAMA == '3' ? $hutang->ATAS_NAMA_LAINNYA . ' / ' : show_harta_atas_nama($hutang->ATAS_NAMA)) . "</td>
						 </tr>
					</table>
				";

                        echo $jenis;
                        
                        ?>
                    </td>
                    <td><?php echo $hutang->NAMA_KREDITUR; ?></td>
                    <td><?php echo $hutang->AGUNAN; ?></td>
                    <td align="right">Rp. <?php echo number_format($hutang->SALDO_HUTANG, 0, '', '.'); ?></td>

                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5" style="text-align: right;"><b>Total</b></td>
                <td style="text-align: right;"><b>Rp. <?php echo number_format($tot, 0, '', '.'); ?></b></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#wrapperHutang .btnCek").click(function () {
            var url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Cek Data Hutang', html, '', 'standart');
            });
            return false;
        });

//        if ('<?= $survey ?>' == 'no')
//        {
//            $('.hutangYes').prop("checked", false);
//            $('.hutangNo').prop("checked", true);
//            $('#hutangselectYes').attr("disabled", true);
//            f_checkboxVer($('.hutangNo'));
//        } else {
//            $('#hutangselectYes').attr("disabled", false);
//            $('.hutangYes').prop("checked", true);
//            $('.hutangNo').prop("checked", false);
//            f_checkboxVer($('.hutangYes'));
//        }
    });
</script>
