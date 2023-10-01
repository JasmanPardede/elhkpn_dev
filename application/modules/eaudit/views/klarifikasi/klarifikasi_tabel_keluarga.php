<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Data Keluarga Inti"</h5>
</div>
<div class="box-body pull-right">
    <button class="btn btn-sm btn-warning aksi-hide" id="klarifAddDataKeluarga" href="index.php/eaudit/klarifikasi/update_data_keluarga/<?php echo $new_id_lhkpn;?>/new"><span class="fa fa-plus"></span> Tambah Data Keluarga</button>
    <br>
    <br>
</div>
<div class="box-body" id="wrapperKeluarga">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th>NAMA</th>
                <th>HUBUNGAN DENGAN PN</th>
                <th>TEMPAT &amp; TANGGAL LAHIR / JENIS KELAMIN</th>
                <th>PEKERJAAN</th>
                <th>ALAMAT RUMAH</th>
                <th width="100px" class="aksi-hide">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            if ($lhkpn_keluarga) {
            foreach ($lhkpn_keluarga as $keluarga) { ?>
            <tr>
                <td><?php echo $i++; ?>.</td>
                <td><b>NIK: </b><?php echo $keluarga->NIK; ?><br><b>Nama: </b><?php echo $keluarga->NAMA; ?></td>
                <td><?php
                        switch ($keluarga->HUBUNGAN) {
                            case 1 : echo 'ISTRI';echo '<br>';
                                break;
                            case 2 : echo 'SUAMI';echo '<br>';
                                break;
                            case 3 : echo 'ANAK TANGGUNGAN';echo '<br>';
                                break;
                            case 4 : echo 'ANAK BUKAN TANGGUNGAN';echo '<br>';
                                break;
                            default : echo 'LAINNYA';echo '<br>';
                        };
                    // if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11') {
                    //     switch ($keluarga->HUBUNGAN) {
                    //         case 3 : echo 'ISTRI';echo '<br>';
                    //             break;
                    //         case 2 : echo 'SUAMI';echo '<br>';
                    //             break;
                    //         case 4 : echo 'ANAK TANGGUNGAN';echo '<br>';
                    //             break;
                    //         case 5 : echo 'ANAK BUKAN TANGGUNGAN';echo '<br>';
                    //             break;
                    //         default : echo 'LAINNYA';echo '<br>';
                    //     };
                    // }
                    // else{
                    //     switch ($keluarga->HUBUNGAN) {
                    //         case 1 : echo 'ISTRI';echo '<br>';
                    //             break;
                    //         case 2 : echo 'SUAMI';echo '<br>';
                    //             break;
                    //         case 3 : echo 'ANAK TANGGUNGAN';echo '<br>';
                    //             break;
                    //         case 4 : echo 'ANAK BUKAN TANGGUNGAN';echo '<br>';
                    //             break;
                    //         default : echo 'LAINNYA';echo '<br>';
                    //     };
                    // }

                    ?>
                </td>
                <td><?php echo $keluarga->TEMPAT_LAHIR.', '. tgl_format($keluarga->TANGGAL_LAHIR); ?><br>
                    <i><?php echo $keluarga->JENIS_KELAMIN; ?></i>
                </td>
                <td><?php echo $keluarga->PEKERJAAN; ?></td>
                <td><?php echo $keluarga->ALAMAT_RUMAH; ?></td>
                <td align="center" class="aksi-hide">
                    <button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_data_keluarga/<?php echo $keluarga->ID_KELUARGA; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/delete/<?php echo $keluarga->ID_KELUARGA; ?>/keluarga" title="Hapus Data" onclick="deleteKeluarga(this);"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <br />
    
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $("#klarifAddDataKeluarga").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Form Data Keluarga', html, null, 'large');
            });            
            return false;
        });
    });

    function deleteKeluarga (obj) {
        confirm("Apakah anda yakin menghapus data ? ", function () {
            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                var url = location.href.split('#')[1];
                url = url.split('?')[0] + '?upperli=li2&bottomli=0';
                window.location.hash = url;
                ng.LoadAjaxContent(url);
                return false;
            });
        });
        return false;
    }


</script>