
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
    <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_tidak_bergerak/add/<?php echo $id_imp_xl_lhkpn; ?>/<?php echo rand(23452, 43456); ?>" title="Edit" onclick="onButton.go(this, 'large', true);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover table-striped" >
        <thead class="table-header">
            <tr >
                <th width="10px">NO</th>
                <!--<th width="10px" class="HartaTidakBergerakverif"><input type="checkbox" class="chk-all" /></th>-->

                <th width="190px">LOKASI/ALAMAT LENGKAP</th>
                <th width="120px">LUAS TANAH/ BANGUNAN</th>
                <th width="190px">KEPEMILIKAN</th>
                <!--<th width="80px">TAHUN PEROLEHAN</th>-->
                <th width="200px">NILAI PEROLEHAN</th>
                <th width="170px">NILAI PELAPORAN</th>
                <th width="80px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($HARTA_TIDAK_BERGERAKS) == 0 ? '<tr><td colspan="10" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            $totalHartaTidakBergerak = 0;

            // display($HARTA_TIDAK_BERGERAKS);
            foreach ($HARTA_TIDAK_BERGERAKS as $hartatidakbergerak) {
                $totalHartaTidakBergerak += $hartatidakbergerak->NILAI_PELAPORAN;
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <!--<td class="HartaTidakBergerakverif"><?php echo ifaTetap($hartatidakbergerak) ? '<input type="checkbox" value="' . $hartatidakbergerak->ID . '" name="chk[]" class="chk-tetap hartatidak" />' : '' ?></td>-->
                    <td>
                        <?php
                        if ($hartatidakbergerak->NAMA_NEGARA != null && $hartatidakbergerak->NAMA_NEGARA != 'INDONESIA' && $hartatidakbergerak->ID_NEGARA == '2') {
                            echo $hartatidakbergerak->JALAN.', '.$hartatidakbergerak->NAMA_NEGARA;
                        }
                        else{
                            echo $hartatidakbergerak->JALAN . '<br />'
                        . '<b>Kelurahan</b> ' . $hartatidakbergerak->KEL . '<br />'
                        . '<b>Kecamatan</b> ' . $hartatidakbergerak->KEC . '<br />'
                        . '<b>Kabupaten/Kota</b> ' . $hartatidakbergerak->KAB_KOT . '<br />'
                        . '<b>Provinsi</b> ' . $hartatidakbergerak->PROV;
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo number_format($hartatidakbergerak->LUAS_TANAH, 2, '.', ''); ?> M<sup>2</sup> /
                        <?php echo number_format($hartatidakbergerak->LUAS_BANGUNAN, 2, '.', ''); ?> M<sup>2</sup>
                    </td>
                    <td>
                        <b>Jenis Bukti : </b> <?php echo $list_bukti[$hartatidakbergerak->JENIS_BUKTI]; ?><br>
                        <b>No. Bukti : </b> <?php echo $hartatidakbergerak->NOMOR_BUKTI; ?><br>
                        <b>a.n : </b> <?php echo $hartatidakbergerak->ATAS_NAMA != 3 ? map_data_atas_nama($hartatidakbergerak->ATAS_NAMA) : $hartatidakbergerak->ATAS_NAMA_LAINNYA; ?><br>
                        <b>Asal Usul Harta : </b>
                        <?php
                        $exp = explode(',', $hartatidakbergerak->ASAL_USUL);
                        $text = '';
                        foreach ($exp as $key) {
                            foreach ($asalusul as $au) {
                                if ($au->ID_ASAL_USUL == $key) {
                                    $text .= ($au->IS_OTHER === '1' ? '<font color="blue">' . $au->ASAL_USUL . '</font>' : $au->ASAL_USUL) . '&nbsp;,&nbsp;&nbsp;';
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
                            $exp1 = explode('. ', $pemanfaatan1[$key]);
                            $text1 .= $exp1[1] . '&nbsp;,&nbsp;&nbsp;';
                        }
                        $rinci1 = substr($text1, 0, -19);
                        echo $rinci1;
                        ?>
                        <br>
                    </td>
                    <!--<td>
                    <?php
                    //ng::printDariSd($hartatidakbergerak->TAHUN_PEROLEHAN_AWAL, $hartatidakbergerak->TAHUN_PEROLEHAN_AKHIR);
                    if ($hartatidakbergerak->TAHUN_PEROLEHAN_AKHIR != '') {
                        echo $hartatidakbergerak->TAHUN_PEROLEHAN_AWAL . ' s/d ' . $hartatidakbergerak->TAHUN_PEROLEHAN_AKHIR;
                    } else {
                        echo $hartatidakbergerak->TAHUN_PEROLEHAN_AWAL;
                    }
                    ?>
                                    </td>-->	
                    <td>
                        <!-- <div>
        
                         </div> -->
                        <div align="right">
                            Rp. <?php echo _format_number($hartatidakbergerak->NILAI_PEROLEHAN, 0); ?>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            Rp. <?php echo _format_number($hartatidakbergerak->NILAI_PELAPORAN, 0); ?>
                        </div>
                    </td>
                    
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_tidak_bergerak/edit/<?php echo $hartatidakbergerak->id_imp_xl_lhkpn_harta_tidak_bergerak_secure; ?>/<?php echo rand(23452, 43456); ?>" title="Edit" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_tidak_bergerak/hapus/<?php echo $hartatidakbergerak->id_imp_xl_lhkpn_harta_tidak_bergerak_secure; ?>" title="Hapus" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php /* <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_tidak_bergerak/hapus/<?php echo $hartatidakbergerak->ID; ?>" title="Delete" onclick="onButton.go(this);"><i class="fa fa-trash" ></i></button> */ ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td colspan="5" class="HartaTidakBergerakspan"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo number_format($totalHartaTidakBergerak, 0, '', '.'); ?></b></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.hasilVerif').tooltip();

        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
        $(".int").inputmask("integer", {
            groupSeparator: '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false,
            'digits': 0
        });
        $("#hartatidakbergerak form").submit(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/4/' + id + '/edit', $(this), $('#hartatidakbergerak').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCari1_1").click(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#hartabergerak input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/4/' + id + '/edit', $(this), $('#hartatidakbergerak').find('.contentTab'));
            return false;
        });
        // #wrapperHartaTidakBergerak
        $("#wrapperHartaTidakBergerak .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Harta Tidak Bergerak', html, '', 'large');
            });
            return false;
        });
        $("#wrapperHartaTidakBergerak .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Harta Tidak Bergerak', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaTidakBergerak .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Harta Tidak Bergerak', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaTidakBergerak .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Harta Tidak Bergerak', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaTidakBergerak .btn-pembanding').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Perbandingan Harta Tidak Bergerak', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaTidakBergerak .btn-pelepasan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Data Pelepasan', html, '', 'standart');
            });
            return false;
        });
    });
</script>