<!--<div class="box-header with-border portlet-header">
    <h3 class="box-title">IV.5 Harta Lainnya</h3>
</div>-->

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
    <br />
    <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_lainnya/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="180px">JENIS</th>
                <th width="180px">KEPEMILIKAN</th>
                <th width="170px">NILAI PEROLEHAN</th>
                <th width="170px">NILAI PELAPORAN</th>
                <th width="170px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($HARTA_LAINNYAS) == 0 ? '<tr><td colspan="10" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            $totalPelaporan = 0;
            foreach ($HARTA_LAINNYAS as $hartalainnya) {
                $totalPelaporan += $hartalainnya->NILAI_PELAPORAN;
                ?>
                <tr>
                    <td><?= ++$i ?>.</td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $hartalainnya->NAMA; ?>
                            </div>
                        </div>
                    </td>
                  <!--   <td>
                    <?php echo $hartalainnya->NAMA . ' (' . $hartalainnya->KUANTITAS . ')'; ?>
                    </td> -->
                    <td>
                        <div class="row">
                            <!-- <div class="col-md-12">
                            <?php echo '<strong>A.n : </strong> ' . $hartalainnya->ATAS_NAMA; ?>
                            </div> -->
                            <div class="col-sm-12">
                                <b>Asal Usul :</b>
                            </div>
                            <div class="col-sm-12">
                                <?php
                                $exp = explode(',', $hartalainnya->ASAL_USUL);
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
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-sm-12'>
                                <?php if (!empty($hartalainnya->FILE_BUKTI)): ?>
                                    <a href="<?php echo base_url('uploads/data_hartalainnya/' . encrypt_username($LHKPN->NIK, 'e') . '/' . $hartalainnya->FILE_BUKTI) ?>" target="_blank"><i class="fa fa-download btn btn-default btn-sm"></i></a>
                                <?php endif ?>
                            </div>
                        </div>
                    </td>
                    <!--<td>
                        <div class="row">
                            <div class="col-md-12">
                    <?php
                    ng::printDariSd($hartalainnya->TAHUN_PEROLEHAN_AWAL, $hartalainnya->TAHUN_PEROLEHAN_AKHIR);
                    ?>
                            </div>
                        </div>
                    </td>-->
                    <td> 
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <?php echo $hartalainnya->SIMBOL . ' Rp. ' . number_format($hartalainnya->NILAI_PEROLEHAN, 0); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                Rp. <?php echo number_format($hartalainnya->NILAI_PELAPORAN, 0); ?>
                            </div>
                        </div>
                    </td>
                    
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_lainnya/edit/<?php echo $hartalainnya->id_imp_xl_lhkpn_harta_lainnya_secure; ?>" title="Edit" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_lainnya/hapus/<?php echo $hartalainnya->id_imp_xl_lhkpn_harta_lainnya_secure; ?>" title="Hapus" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php /* <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_lainnya/hapus/<?php echo $hartalainnya->id_imp_xl_lhkpn_harta_lainnya_secure; ?>" title="Delete" onclick="onButton.go(this);"><i class="fa fa-trash" ></i></button> */ ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td class="HartaLainnyaspan" colspan="6"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo number_format(@$totalPelaporan, 0, '', '.'); ?></b></td>
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
        // #wrapperHartaLain
        $("#hartalainnya form").submit(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/9/' + id + '/edit', $(this), $('#hartalainnya').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCari2_9").click(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#hartalainnya input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/9/' + id + '/edit', $(this), $('#hartalainnya').find('.contentTab'));
            return false;
        });
        $("#wrapperHartaLain .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Harta Lain', html, '', 'large');
            });
            return false;
        });
        $("#wrapperHartaLain .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Harta Lain', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaLain .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Harta Lain', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaLain .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Harta Lain', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaLain .btn-pelaporan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Data Pelepasan', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaLain .btn-pembanding').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Perbandingan Harta Lainnya', html, '', 'standart');
            });
            return false;
        });
    });
</script>