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
<!--     <button type="button" class="btn btn-sm btn-default btn-add btn-primary" href="index.php/efill/lhkpn/addhutang/<?php echo $id_lhkpn; ?>"><i class="fa fa-plus"></i> Tambah</button> -->
    <div class="box-tools">
    </div>
    <br />
    <button type="button" class="btn btn-sm btn-primary" href="index.php/efill/validasi_hutang/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="300px">ATAS NAMA / NAMA KREDITUR</th>
                <th width="50px">BENTUK<br />AGUNAN</th>
                <th width="230px">NILAI AWAL HUTANG</th>
                <th width="230px">NILAI SALDO HUTANG</th>
                <th width="50px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($HUTANGS) == 0 ? '<tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            $totalHutang = 0;

            foreach ($HUTANGS as $hutang) {
                $totalHutang += $hutang->SALDO_HUTANG;
                ?>
                <tr>
                    <td><?= ++$i ?>.</td>
                    <td>
                        <?php echo ($hutang->ATAS_NAMA == '3' ? $hutang->ATAS_NAMA_LAINNYA . ' / ' : show_harta_atas_nama($hutang->ATAS_NAMA)) . $hutang->NAMA_KREDITUR; ?><br />
                        <b>Jenis Hutang</b> <?php echo $hutang->NAMA_JENIS_HUTANG; ?>
                    </td>
                    <td><?php echo $hutang->AGUNAN; ?></td>
                    <td align="right">Rp.  <?php echo _format_number($hutang->AWAL_HUTANG, 0); ?></td>
                    <td align="right">Rp.  <?php echo _format_number($hutang->SALDO_HUTANG, 0); ?></td>
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-sm btn-primary" href="index.php/efill/validasi_hutang/edit/<?php echo $hutang->id_imp_xl_lhkpn_hutang_secure; ?>" title="Edit" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" href="index.php/efill/validasi_hutang/hapus/<?php echo $hutang->id_imp_xl_lhkpn_hutang_secure; ?>" title="Hapus" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        
                        <?php /* <button type="button" class="btn btn-sm btn-danger" href="index.php/efill/validasi_hutang/hapus/<?php echo $hutang->id_imp_xl_lhkpn_hutang_secure; ?>" title="Delete" onclick="onButton.go(this);"><i class="fa fa-trash" ></i></button> */ ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td class="HartaHutangspan" colspan="4"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo _format_number(@$totalHutang, 0); ?></b></td>
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
        $(".int").inputmask("integer", {
            groupSeparator: '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false,
            'digits': 0
        });
        // #wrapperHutang
        $("#wrapperHutang .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Hutang', html, '', 'large');
            });
            return false;
        });
        $("#wrapperHutang .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Hutang', html, '', 'standart');
            });
            return false;
        });
        // ctrl + a
        $(document).on('keydown', function (e) {
            if (e.ctrlKey && e.which === 65 || e.which === 97) {
                e.preventDefault();
                $('#wrapperHutang .btn-add').trigger('click');
                return false;
            }
        });
        $('#wrapperHutang .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Hutang', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHutang .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Hutang', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHutang .btn-pembanding').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Perbandingan Hutang', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHutang .btn-pelaporan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Data Pelepasan', html, '', 'standart');
            });
            return false;
        });
    });

    function formatAngka(angka) {
        if (typeof (angka) != 'string')
            angka = angka.toString();
        var reg = new RegExp('([0-9]+)([0-9]{3})');
        while (reg.test(angka))
            angka = angka.replace(reg, '$1.$2');
        return angka;
    }
</script>