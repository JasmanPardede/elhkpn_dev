<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"LAMPIRAN 2. INFORMASI PENERIMAAN FASILITAS DALAM SETAHUN"</h5>
</div>
<!-- <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="klarifikasiDataFasilitas" href="index.php/eaudit/klarifikasi/update_fasilitas/<?php //echo $new_id_lhkpn;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br> -->
<div class="box-body pull-right">
    <button class="btn btn-sm btn-warning aksi-hide" id="klarifAddDataFasilitas" href="index.php/eaudit/klarifikasi/update_fasilitas/<?php echo $new_id_lhkpn;?>/new"><span class="fa fa-plus"></span> Tambah Data Fasilitas</button>
    <br>
    <br>
</div>
<div class="box-body" id="wrapperLampiran2">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th>URAIAN</th>
                <th>NAMA PIHAK PEMBERI FASILITAS</th>
                <th>KETERANGAN PEMERIKSAAN</th>
                <th width="100px" class="aksi-hide">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($lhkpn_fasilitas): ?>
            <?php
            $i = 0;
            foreach ($lhkpn_fasilitas as $lamp2) {

                $html = "
					<table class='table-child table-condensed'>
                        <tr>
                            <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $lamp2->JENIS_FASILITAS . "</td>
                         </tr>
                         <tr>
                            <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . $lamp2->KETERANGAN . "</td>
                        </tr>
                    </table>
				";
                ?>
                <tr>
                    <td><?php echo ++$i; ?></td>
                    <td><?php echo $html; ?></td>
                    <td><?php echo $lamp2->PEMBERI_FASILITAS; ?></td>
                    <td><?php echo $lamp2->KET_PEMERIKSAAN; ?></td>
                    <td align="center" class="aksi-hide">
                        <button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_fasilitas/<?php echo $lamp2->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'standart', true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/delete/<?php echo $lamp2->ID; ?>/fasilitas" title="Hapus Data" onclick="deleteFasilitas(this);"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Data Tidak Ditemukan</td>
                    </tr>
                <?php endif ?>

        </tbody>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#klarifAddDataFasilitas").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Form Data Fasilitas', html, null, 'standart');
            });            
            return false;
        });
        $("#wrapperLampiran2 .btnCek").click(function() {
            url = $(this).attr('href');
            // alert(url);
            $.post(url, function(html) {
                OpenModalBox('Cek Data Fasilitas', html, '', 'large');
            });
            return false;
        });
    });

    function deleteFasilitas (obj) {
        confirm("Apakah anda yakin menghapus data ? ", function () {
            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                var url = location.href.split('#')[1];
                url = url.split('?')[0] + '?upperli=li6&bottomli=0';
                window.location.hash = url;
                ng.LoadAjaxContent(url);
                return false;
            });
        });
        return false;
    }

</script>