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
    <h5 class="">"LAMPIRAN 2. INFORMASI PENERIMAAN FASILITAS DALAM SETAHUN"</h5>
</div>


<div class="box-body" id="wrapperLampiran2">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th>URAIAN</th>
                <!--<th>NAMA FASILITAS</th>-->
                <th>NAMA PIHAK PEMBERI FASILITAS</th>
                <th>KETERANGAN</th>
                <!--th>Hasil</th>
                <th>Aksi</th-->
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($lamp2s as $lamp2) {
                if ($lamp2->JENIS_FASILITAS == '1') {
                    $text = 'Rumah Dinas';
                } else if ($lamp2->JENIS_FASILITAS == '2') {
                    $text = 'Biaya Hidup';
                } else if ($lamp2->JENIS_FASILITAS == '3') {
                    $text = 'Jaminan Kesehatan';
                } else if ($lamp2->JENIS_FASILITAS == '4') {
                    $text = 'Mobil Dinas';
                } else if ($lamp2->JENIS_FASILITAS == '5') {
                    $text = 'Opsi Pembelian Saham';
                } else {
                    $text = 'Fasilitas Lainnya';
                }

                $html = "
					<table class='table-child table-condensed'>
                        <tr>
                            <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $lamp2->JENIS_FASILITAS . "</td>
                         </tr>
                         <tr>
                            <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $lamp2->KETERANGAN . "</td>
                        </tr>
                    </table>
				";
                ?>
                <tr>
                    <td><?php echo ++$i; ?></td>
                    <td><?php echo $html; ?></td>
                    <!--<td><?php echo $lamp2->NAMA_FASILITAS; ?></td>-->
                    <td><?php echo $lamp2->PEMBERI_FASILITAS; ?></td>
                    <td><?php echo $lamp2->KETERANGAN; ?></td>
                    <!--td align="center">
                    <?php
                    $disable_cek = '';
                    if (@$hasilVerifikasi['penerimaanfasilitas']['hasil'][$lamp2->ID] == 1) {
                        echo '<i class="fa fa-check-square" style="cursor: pointer; color: blue;" title="' . (@$hasilVerifikasi['penerimaanfasilitas']['catatan'][$lamp2->ID]) . '"></i>';
                        $disable_cek = (!empty($tmpData) ? 'disabled' : '');
                    } else if (@$hasilVerifikasi['penerimaanfasilitas']['hasil'][$lamp2->ID] == -1) {
                        echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;" title="' . (@$hasilVerifikasi['penerimaanfasilitas']['catatan'][$lamp2->ID]) . '"></i>';
                    } else {
                        
                    }
                    ?>
                    </td>       
                    <td style="text-align:center">
                    <?php
                    if ($disable_cek != '') {
                        ?>
                                   <button type="button" class="btn btn-sm btn-success" disabled>Cek</button>
                        <?php
                    } else {
                        ?>
                                    <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/veritem/<?php echo $lamp2->ID; ?>/penerimaanfasilitas" >Cek</button>
                        <?php
                    }
                    ?>
                    </td-->
                </tr>
                <?php } ?>
        </tbody>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function() {
        // #wrapperHutang
        $("#wrapperLampiran2 .btnCek").click(function() {
            url = $(this).attr('href');
            // alert(url);
            $.post(url, function(html) {
                OpenModalBox('Cek Data Fasilitas', html, '', 'large');
            });
            return false;
        });
    });
</script>