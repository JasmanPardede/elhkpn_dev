<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Data Keluarga Inti"</h5>
</div>
<?php
$viaa = $item->entry_via;
?>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataKeluarga" href="index.php/ever/verification_edit/keluarga/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data Keluarga</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperKeluarga">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="200px">NAMA</th>
                <th width="120px">HUBUNGAN DENGAN PN</th>
                <th width="200px">TEMPAT &amp; TANGGAL LAHIR / JENIS KELAMIN</th>
                <th width="200px">PEKERJAAN</th>
                <th width="200px">ALAMAT RUMAH</th>
                <?php if ($viaa == '1'): ?>
                <th width="80px">AKSI</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            if(isset($KELUARGAS) && (is_array($KELUARGAS) || is_object($KELUARGAS))) :
                foreach ($KELUARGAS as $keluarga) { ?>
                <tr>
                    <td><?php echo $i++; ?>.</td>
                    <td><b>NIK: </b><?php echo $keluarga->NIK; ?><br><b>Nama: </b><?php echo $keluarga->NAMA; ?></td>
                    <td><?php
                        if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11'|| $lhkpn_ver == '2.1') {
                            switch ($keluarga->HUBUNGAN) {
                                case 3 : echo 'ISTRI';echo '<br>';
                                    break;
                                case 2 : echo 'SUAMI';echo '<br>';
                                    break;
                                case 4 : echo 'ANAK TANGGUNGAN';echo '<br>';
                                    break;
                                case 5 : echo 'ANAK BUKAN TANGGUNGAN';echo '<br>';
                                    break;
                                default : echo 'LAINNYA';echo '<br>';
                            };
                        }
                        else{
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
                        }

                        ?>
                    </td>
                    <td><?php echo $keluarga->TEMPAT_LAHIR.', '. tgl_format($keluarga->TANGGAL_LAHIR); ?><br>
                        <i><?php echo show_jenis_kelamin($keluarga->JENIS_KELAMIN); ?></i><br>
                        <?php
                            if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11'|| $lhkpn_ver == '2.1') {
                                if ($keluarga->HUBUNGAN == 2 || $keluarga->HUBUNGAN == 3 || $keluarga->HUBUNGAN == 4) {
                        ?>
                                    <b>Umur Saat lapor LHKPN: <?php echo getHitungUmur($keluarga->TANGGAL_LAHIR, $keluarga->tgl_lapor); ?></b><br>
                        <?php
                                    if ($keluarga->umur_lapor >= 17) {
                        ?>
                                    <b>SK Sudah Diterima: <?php echo ($keluarga->FLAG_SK == '1') ? 'Sudah' : 'Belum'; ?></b>
                        <?php
                                    } else {
                        ?>
                                    <b>Belum Wajib SK</b>
                        <?php
                                    }
                                } else {
                        ?>
                                    <b>Tidak Wajib SK</b>
                        <?php
                                }
                            } else {
                                if ($keluarga->HUBUNGAN == 1 || $keluarga->HUBUNGAN == 2 || $keluarga->HUBUNGAN == 3) {
                        ?>
                                    <b>Umur Saat lapor LHKPN: <?php echo getHitungUmur($keluarga->TANGGAL_LAHIR, $keluarga->tgl_lapor); ?></b><br>
                        <?php 
                                    if ($keluarga->umur_lapor >= 17) {
                        ?>
                                    <b>SK Sudah Diterima: <?php echo ($keluarga->FLAG_SK == '1') ? 'Sudah' : 'Belum'; ?></b>
                        <?php
                                    } else {
                        ?>
                                    <b>Belum Wajib SK</b>
                        <?php
                                    }
                                } else {
                        ?>
                                    <b>Tidak Wajib SK</b>
                        <?php
                                }
                            }
                        ?>
                    </td>
                    <td><?php echo $keluarga->PEKERJAAN; ?></td>
                    <td><?php echo $keluarga->ALAMAT_RUMAH; ?></td>
                    <?php if ($viaa == '1'): ?>
                    <td align="center">
                        <button type="button" class="btn btn-primary" href="index.php/ever/verification_edit/keluarga/<?php echo $keluarga->ID_KELUARGA; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $keluarga->ID_KELUARGA; ?>/keluarga" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                    </td>
                    <?php endif ?>
                </tr>
                <?php
                }
            endif;
            ?>
        </tbody>
    </table>
    <br />
    
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#verifEditDataKeluarga").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data Keluarga', html, null, 'large');
            });            
            return false;
        });
    });
</script>
