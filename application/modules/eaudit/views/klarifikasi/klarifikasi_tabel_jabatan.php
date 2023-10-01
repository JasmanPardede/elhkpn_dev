<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Informasi Jabatan"</h5>
</div>
<div class="box-body pull-right">
    <button class="btn btn-sm btn-warning aksi-hide" id="klarifAddDataJabatan" href="index.php/eaudit/klarifikasi/update_data_jabatan/<?php echo $new_id_lhkpn;?>/new"><span class="fa fa-plus"></span> Rangkap Jabatan</button>
    <br>
    <br>
</div>
<div class="box-body" id="wrapperJabatan">
    <?php
    $i = 0;
    $jumJab = count($lhkpn_jabatan);
    ?>
    <table class="table table-bordered table-hover table-striped" >
        <thead class="table-header">
            <tr>
                <th width="30">No.</th>
                <th>Jabatan </th>
                <th>Lembaga</th>
                <th>Unit Kerja</th>
                <th>Sub Unit Kerja</th>
                <th width="100" class="aksi-hide">Aksi</th>

            </tr>
        </thead>
        <tbody>
            <?php //display($lhkpn_jabatan);
            if($lhkpn_jabatan){
                foreach ($lhkpn_jabatan as $jabatan) {
                    if ($jabatan->IS_PRIMARY == '1')
                        $key = '<i class="fa fa-key"></i>';
                    else
                        $key = '';
                    ?>
                    <tr>
                        <td><?php echo ++$i; ?>.</td>
                        <td><?php echo $key . ' ' . $jabatan->NAMA_JABATAN; ?>
                            <?php
                            switch ($jabatan->ESELON) {
                                case '1':
                                    echo 'I';
                                    break;
                                case '2':
                                    echo 'II';
                                    break;
                                case '3':
                                    echo 'III';
                                    break;
                                case '4':
                                    echo 'IV';
                                    break;
                                case '5':
                                    echo 'Non Eselon';
                                    break;
                            }
                            ?>
                        </td>
                        <td><?php echo $jabatan->INST_NAMA;  ?></td>
                        <td><?php echo $jabatan->UK_NAMA; ?></td>
                        <td><?php echo $jabatan->SUK_NAMA; ?></td>
                        <td align="center" class="aksi-hide">
                            <?php if($jabatan->IS_PRIMARY==1){?>
                            <button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_data_jabatan/<?php echo $jabatan->ID;?>/edit" title="Edit Data" onclick="onButton.go(this, 'standart', true);"><i class="fa fa-pencil"></i></button>
                            <?php }else{ ?>
                            <button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_data_jabatan/<?php echo $jabatan->ID;?>/edit" title="Edit Data" onclick="onButton.go(this, 'standart', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/changePrimaryKey/<?php echo $jabatan->ID; ?>/jabatan/<?php echo $jabatan->ID_LHKPN; ?>" title="Set Sebagai Jabatan Utama" onclick="changePrimaryKey(this, '<?php echo $jabatan->NAMA_JABATAN ?>');"><i class="fa fa-key"></i></button>
                            <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/delete/<?php echo $jabatan->ID; ?>/jabatan" title="Hapus Data" onclick="deleteJabatan(this);"><i class="fa fa-trash"></i></button>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<div class="box-footer"></div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function() {

        $("#klarifAddDataJabatan").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Form Data Jabatan', html, null, 'standart');
            });            
            return false;
        });
    });

    function changePrimaryKey (obj, jabatan) {
        confirm("Apakah Anda Yakin akan Memilih Jabatan "+ jabatan +" Sebagai Jabatan Utama ? ", function () {
            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                var url = location.href.split('#')[1];
                url = url.split('?')[0] + '?upperli=li1&bottomli=0';
                window.location.hash = url;
                ng.LoadAjaxContent(url);
                return false;
            });
        },'SET JABATAN UTAMA');
        return false;
    }

    function deleteJabatan (obj) {
        confirm("Apakah anda yakin menghapus data ? ", function () {
            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                var url = location.href.split('#')[1];
                url = url.split('?')[0] + '?upperli=li1&bottomli=0';
                window.location.hash = url;
                ng.LoadAjaxContent(url);
                return false;
            });
        });
        return false;
    }

</script>
