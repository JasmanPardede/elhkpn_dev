<!--<div class="box-header with-border portlet-header">
    <h3 class="box-title">IV.4 Kas dan Setara Kas</h3>
</div>-->
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
    <br />
    <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_kas/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <!--<th width="10px" class="HartaKasverif"><input type="checkbox" class="chk-all" /></th>-->
                <th width="180px">JENIS</th>
                <th width="180px">ATAS NAMA</th>
                <th width="180px">NAMA BANK / LEMBAGA LAINNYA / NOMOR REKENING</th>
                <!--<th width="80px">TAHUN BUKA REKENING</th>-->
                <th width="180px">ASAL USUL HARTA</th>
                <th width="170px">SALDO SAAT PELAPORAN</th>
                <th width="170px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($HARTA_KASS) == 0 ? '<tr><td colspan="10" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $totalSaldo = 0;
            $i = 0;
            foreach ($HARTA_KASS as $kas) {
                $totalSaldo += $kas->NILAI_EQUIVALEN;
                ?>
                <tr>
                    <td><?= ++$i ?>.</td>
                    <!--<td class="HartaKasverif"><?php echo ifaTetap($kas) ? '<input type="checkbox" value="' . $kas->ID . '" name="chk[]" class="chk-tetap hartakas" />' : '' ?></td>-->

                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $kas->NAMA; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $kas->ATAS_NAMA_REKENING != 3 ? map_data_atas_nama($kas->ATAS_NAMA_REKENING) : $kas->ATAS_NAMA_LAINNYA; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                if (strtolower(substr($kas->NAMA_BANK, 0, 4)) != strtolower('Bank')) {
                                    echo 'Bank ' . $kas->NAMA_BANK;
                                } else {
                                    echo $kas->NAMA_BANK;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                No Rekening
                                <?php echo $kas->NOMOR_REKENING; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-sm-12'>
                                <?php if (!empty($kas->FILE_BUKTI)): ?>
                                    <a href="<?php echo base_url('uploads/data_kas/' . encrypt_username($LHKPN->NIK, 'e') . '/' . $kas->FILE_BUKTI) ?>" target="_blank"><i class="fa fa-download btn btn-default btn-sm"></i></a>
                                <?php endif ?>
                            </div>
                        </div>
                    </td>
                    <!--<td>
                    <?php echo $kas->TAHUN_BUKA_REKENING; ?>
                    </td>-->
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                $exp = explode(',', $kas->ASAL_USUL);
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
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12">
                                Nilai Saldo
                            </div>
                            <div class="col-md-12" align="right">
                                <?php echo $kas->SIMBOL . ' ' . _format_number($kas->NILAI_SALDO, 0); ?>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12" align="right">
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                Ekuivalen
                            </div>
                            <div class="col-md-12" align="right">
                                Rp <?php echo _format_number($kas->NILAI_EQUIVALEN, 0); ?>
                            </div>
                        </div>
                    </td>
                    
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_kas/edit/<?php echo $kas->id_imp_xl_lhkpn_harta_kas_secure; ?>" title="Edit" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_kas/hapus/<?php echo $kas->id_imp_xl_lhkpn_harta_kas_secure; ?>" title="Hapus" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php /* <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_kas/hapus/<?php echo $kas->id_imp_xl_lhkpn_harta_kas_secure; ?>" title="Delete" onclick="onButton.go(this);"><i class="fa fa-trash" ></i></button> */ ?>
                    </td>
                </tr>
            <?php } ?>
        <tfoot class='table-footer'>
            <tr>
                <td class="HartaKasspan" colspan="5"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo _format_number(@$totalSaldo, 0); ?></b></td>
                <td></td>
            </tr>
        </tfoot>
        </tbody>
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
        $(".int").inputmask("integer", {
            groupSeparator: '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false,
            'digits': 0
        });
        $("#kas form").submit(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/8/' + id + '/edit', $(this), $('#kas').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCarikas").click(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#hartabergerak input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/8/' + id + '/edit', $(this), $('#kas').find('.contentTab'));
            return false;
        });
        // #wrapperHartaTidakBergerak
        $("#wrapperKas .btn-viewkk").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('View KK', html, '', 'normal');
            });
            return false;
        });
        $("#wrapperKas .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Kas', html, '', 'large');
            });
            return false;
        });
        $('#wrapperKas .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Kas', html, '', 'large');
            });
            return false;
        });
        $('#wrapperKas .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Kas', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperKas .btn-pembanding').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Perbandingan Harta KAS', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperKas .btn-pelaporan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Data Pelepasan', html, '', 'standart');
            });
            return false;
        });
    });
</script>