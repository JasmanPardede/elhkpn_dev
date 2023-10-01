<!--<div class="box-header with-border portlet-header">
    <h3 class="box-title">III. Data Keluarga Inti</h3>
</div>-->
<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
    .kartu_keluarga {
        padding: 0.35em 0.625em 0.75em;
        margin: 0px 2px;
        border: 1px solid #C0C0C0;
    }
    .legend_kk {
        border: 0px none;
        margin-bottom: 0;
        font-size: 16px;
        border-style: none;
        width: auto;
        font-weight: bold;
        padding: 0 5px;
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Data Keluarga Inti"</h5>
</div>
<div class="box-body" id="wrapperKeluarga">
    <!--    <?php
    $i = 0;
    $text = '';
    foreach ($rinci_keluargas as $rinci_keluarga) {
        switch ($rinci_keluarga->HUBUNGAN) {
            case 1 : $hubungan = '<b>Istri</b>';
                break;
            case 2 : $hubungan = '<b>Suami</b>';
                break;
            case 3 : $hubungan = '<b>Anak Tanggungan</b>';
                break;
            case 4 : $hubungan = '<b>Anak Bukan Tanggungan</b>';
                break;
            case 5 : $hubungan = '<b>Lainnya</b>';
                break;
        };
        $text .= $hubungan . "&nbsp; <b> = " . $rinci_keluarga->JUMLAH . "</b>&nbsp;,&nbsp;&nbsp;";
        // echo $text;
    }
    $rinci = substr($text, 0, -19);
    echo $rinci;
    ?> -->
    <br/>
    <button type="button" class="btn btn-primary" href="index.php/efill/validasi_keluarga/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"> Tambah</i></button>
    <br/>
    <br/>
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="200px">NAMA</th>
                <th width="120px">HUBUNGAN DENGAN PN</th>
                <th width="200px">TEMPAT &amp; TANGGAL LAHIR / JENIS KELAMIN</th>
                <th width="200px">PEKERJAAN</th>
                <th width="200px">ALAMAT RUMAH</th>
                <th width="80px">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($KELUARGAS) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            foreach ($KELUARGAS as $keluarga) {
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.<br>
                    </td>
                    <td>
                    
                        <b>NIK : </b><?php echo $keluarga->NIK ? $keluarga->NIK : "-"?><br>
                        <b>Nama : </b><?php echo $keluarga->NAMA; ?><br>
                    </td>
                    <td><?php echo show_hubungan_keluarga($keluarga->HUBUNGAN, "-", $info_penerimaan_offline->VERSI_EXCEL); ?></td>
                    <td><b>TTL : </b><?php echo $keluarga->TEMPAT_LAHIR; ?>, <?php echo tgl_format($keluarga->TANGGAL_LAHIR); ?><br>
                        <b>Jenis Kelamin : </b><?php echo show_jenis_kelamin($keluarga->JENIS_KELAMIN); ?>
                    <td><?php echo $keluarga->PEKERJAAN; ?></td>
                    <td>
                        <b>Alamat Rumah : </b><br>
                        <?php echo $keluarga->ALAMAT_RUMAH; ?>
                        <br>
                        <b>Nomor Telepon : </b>
                        <?php echo $keluarga->NOMOR_TELPON; ?>
                    </td>
                    <td style="text-align:center">
                        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_keluarga/edit/<?php echo $keluarga->id_imp_xl_lhkpn_keluarga_secure; ?>?asd=<?php echo $keluarga->id_imp_xl_lhkpn_keluarga; ?>" title="Edit Data" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/efill/validasi_keluarga/hapus/<?php echo $keluarga->id_imp_xl_lhkpn_keluarga_secure; ?>" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php /* <button type="button" class="btn btn-danger" href="index.php/efill/validasi_keluarga/edit/<?php echo $keluarga->id_imp_xl_lhkpn_keluarga_secure; ?>" title="Edit Data" onclick="onButton.go(this);"><i class="fa fa-trash"></i></button> */ ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function () {

        var ID = $('#id_lhkpn').val();
        ng.formProcess($("#ajaxFormKK"), 'insert', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/3/' + ID + '/edit', block: '#block', container: $('#keluarga').find('.contentTab')});
        $("#wrapperKeluarga .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Keluarga', html, '', 'large');
            });
            return false;
        });
        $("#wrapperKeluarga .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Keluarga', html, '', 'large');
            });
            return false;
        });
        $('#wrapperKeluarga .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Keluarga', html, '', 'large');
            });
            return false;
        });
        $('#wrapperKeluarga .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Keluarga', html, '', 'large');
            });
            return false;
        });
        $("#wrapperKeluarga .btn-show").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Lihat Dokumen', html, '', 'medium');
            });
            return false;
        });
    });
</script>