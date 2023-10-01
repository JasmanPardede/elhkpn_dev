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
    <h5 class="">"Harta Bergerak berupa perabotan rumah Tangga, barang elektronik, perhiasan & logam/batu mulia, barang seni/antik, persediaan dan harta bergerak lainnya"</h5>
</div><!-- /.box-header -->
<div class="box-body" id="wrapperHartaBergerak2">
    <br />
    <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_bergerak_lain/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="3%">NO</th>
                <th width="10%">JENIS</th>
                <th width="20%">IDENTITAS HARTA</th>
                <th width="20%">KEPEMILIKAN</th>
                <!--<th width="12%">TAHUN PEROLEHAN</th>-->
                <th width="13%">NILAI PEROLEHAN</th>
                <th width="13%">NILAI PELAPORAN</th>
                <th width="5%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($HARTA_BERGERAK_LAINS) == 0 ? '<tr><td colspan="10" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            $totalHartaBergerak2 = 0;
            $aMata = ['1' => 'IDR', '2' => 'USD', '3' => 'YEN'];
            foreach ($HARTA_BERGERAK_LAINS as $hartabergerak2) :
                $totalHartaBergerak2 += str_replace('.', '', $hartabergerak2->NILAI_PELAPORAN);
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $hartabergerak2->NAMA; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php
                        echo $hartabergerak2->NAMA . ' Jumlah ' . $hartabergerak2->JUMLAH . ' ' . $hartabergerak2->SATUAN;
                        ?>
                    </td>
                    <td>
                        <!-- <div class="col-sm-12"><b>a.n : </b></div>
                        <div class="col-sm-12"><?php echo $hartabergerak2->ATAS_NAMA; ?></div> -->
                        <div class="col-sm-12"><b>Asal Usul Harta : </b></div>
                        <div class="col-sm-12">
                            <?php
                            $exp = explode(',', $hartabergerak2->ASAL_USUL);
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
                    </td>
                    <td>
                        <div><?php //echo $aMata[$hartabergerak2->MATA_UANG];        ?></div>
                        <div align="right"> Rp. <?php echo $hartabergerak2->SIMBOL . ' ' . _format_number($hartabergerak2->NILAI_PEROLEHAN, 0); ?></div>
                    </td>
                    <td>
                        <div align="right">
                            Rp. <?php echo _format_number($hartabergerak2->NILAI_PELAPORAN, 0); ?>        
                        </div>
                    </td>
               
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_bergerak_lain/edit/<?php echo $hartabergerak2->id_imp_xl_lhkpn_harta_bergerak_lain_secure; ?>" title="Edit" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_bergerak_lain/hapus/<?php echo $hartabergerak2->id_imp_xl_lhkpn_harta_bergerak_lain_secure; ?>" title="Hapus" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php /* <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_bergerak_lain/hapus/<?php echo $hartabergerak2->id_imp_xl_lhkpn_harta_bergerak_lain_secure; ?>" title="Delete" onclick="onButton.go(this);"><i class="fa fa-trash" ></i></button> */ ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td class="HartaBergerak2span" colspan="5/<?= $id_lhkpn ?>"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo _format_number(@$totalHartaBergerak2, 0); ?></b></td>
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
        $("#hartabergerakperabot form").submit(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/6/' + id + '/edit', $(this), $('#hartabergerakperabot').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCari2_3").click(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#hartabergerakperabot input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/6/' + id + '/edit', $(this), $('#hartabergerakperabot').find('.contentTab'));
            return false;
        });
        $("#wrapperHartaBergerak2 .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Harta Bergerak', html, '', 'large');
            });
            return false;
        });
        $("#wrapperHartaBergerak2 .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Harta Bergerak', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaBergerak2 .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Harta Bergerak', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaBergerak2 .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Harta Bergerak', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaBergerak2 .btn-pembanding').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Perbandingan Harta Bergerak Lain', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaBergerak2 .btn-pelaporan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Data Pelepasan', html, '', 'standart');
            });
            return false;
        });
    });
</script>