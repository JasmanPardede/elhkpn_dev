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
    <h5 class="">"LAMPIRAN 1. INFORMASI PENJUALAN/PELEPASAN HARTA KEKAYAAN"</h5>
</div>
<div class="box-body" id="wrapperLampiran1">
    <br />
    <button type="button" class="btn btn-sm btn-primary" href="index.php/efill/validasi_pelepasan/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th>KODE JENIS</th>
                <th>TANGGAL TRANSAKSI</th>
                <th>URAIAN HARTA KEKAYAAN / ATAS NAMA</th>
                <th>NILAI PENJUALAN/ PELEPASAN / PENERIMAAN</th>
                <th>INFORMASI PIHAK KEDUA</th>
                <th width='5%'>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($lampiran_pelepasan) == 0 ? '<tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php $no = 0; ?>
            <?php
            $tot = '';
            foreach ($lampiran_pelepasan as $key) {
                $tot += $key['NILAI'];
                $no++;
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $key['KODE_JENIS']; ?></td>
                    <td><?= date('d/m/Y', strtotime($key['TGL_TRANSAKSI'])); ?></td>
                    <td><?= $key['URAIAN_HARTA']; ?></td>
                    <td >
                        <div class="row">
                            <div class="col-md-2">Rp</div>
                            <div class="col-md-10"><?php echo number_format($key['NILAI'], 0, '', '.'); ?></div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div><label>Nama:</label></div>
                            <div><?= $key['PIHAK_DUA']; ?></div>
                        </div>
                        <div>
                            <div><label>Alamat:</label></div>
                            <div><?= $key['ALAMAT']; ?></div>
                        </div>
                    </td>
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-sm btn-primary" href="index.php/efill/validasi_pelepasan/edit/<?php echo $key["id_secure"]; ?>?jpl=<?php echo $key["jpl"]; ?>&okh=<?php echo make_secure_text(rand(12, 45)); ?>" title="Edit" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" href="index.php/efill/validasi_pelepasan/hapus/<?php echo $key["id_secure"]; ?>?jpl=<?php echo $key["jpl"]; ?>&okh=<?php echo make_secure_text(rand(12, 45)); ?>" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash" ></i></button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td colspan="4"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo ($tot !== '') ? number_format($tot, 0, '', '.') : 0; ?></b></td>
                <td colspan="2">&nbsp;</td>
            </tr>
        </tfoot>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function () {
        $("#wrapperLampiran1 form").submit(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/13/' + id + '/edit', $(this), $('#pelepasanharta').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCari13").click(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#wrapperLampiran1 input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/13/' + id + '/edit', $(this), $('#pelepasanharta').find('.contentTab'));
            return false;
        });
        // #wrapperHutang
        $("#wrapperLampiran1 .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
            });
            return false;
        });

        $("#wrapperLampiran1 .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'standart');
            });
            return false;
        });
        // ctrl + a
        $(document).on('keydown', function (e) {
            if (e.ctrlKey && e.which === 65 || e.which === 97) {
                e.preventDefault();
                $('#wrapperLampiran1 .btn-add').trigger('click');
                return false;
            }
        });

        $('#wrapperLampiran1 .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '');
            });
            return false;
        });

        $('#wrapperLampiran1 .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '');
            });
            return false;
        });
    });
</script>