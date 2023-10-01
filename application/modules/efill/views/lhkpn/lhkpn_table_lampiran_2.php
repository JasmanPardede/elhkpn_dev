
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
    <h5 class="">"LAMPIRAN 2. INFORMASI PENERIMAAN FASILITAS DALAM SETAHUN"</h5>
</div>
<div class="box-body" id="wrapperLampiran2">
    <?php if ($mode == 'edit') { ?>
        <?php if ($status_lhkpn == '2' && $hasilVerifikasi->VAL->PENERIMAANFASILITAS == '1') { ?>
        <?php } else { ?>
            <button type="button" class="btn btn-sm btn-add btn-primary" href="index.php/efill/lhkpn/add_lampiran2/<?php echo $id_lhkpn; ?>"><i class="fa fa-plus"></i> Tambah</button>
            <div class="box-tools">
                <form method="post" id="ajaxFormCarifasilitass" action="">
                    <div class="input-group  pull-right" style="width: 500px;">
                        <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Cari berdasar Nama Fasilitas" value="<?php echo @$cari; ?>" id="carifasilitas"/>
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#carifasilitas').val(''); $('#ajaxFormCarifasilitass').trigger('submit');">Clear</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
        <?php
    }
//  var_dump($lamp2s);exit;
    ?>
            <br />
            <button type="button" class="btn btn-primary" href="index.php/efill/validasi_fasilitas/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this);"><i class="fa fa-plus"></i> Tambah</button>
            <br />
            <br />
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="10px">STATUS</th>
                <th>KODE JENIS</th>
                <th>URAIAN</th>
                <th>NAMA PIHAK PEMBERI FASILITAS</th>
                <th>KETERANGAN</th>
                <th rowspan="2" width='130px'>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($lamp2s) == 0 ? '<tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            foreach ($lamp2s as $lamp2) {
                if ($lamp2->JENIS_FASILITAS == '1') {
                    $text = 'Rumah Dinas';
                } else if ($lamp2->JENIS_FASILITAS == '2') {
                    $text = 'Biaya Hidup';
                } else if ($lamp2->JENIS_FASILITAS == '3') {
                    $text = 'Jaminan Kesehatan';
                } else if ($lamp2->JENIS_FASILITAS == '4') {
                    $text = 'Mobil Dinas';
                } else if ($lamp2->JENIS_FASILITAS == '5') {
                    $text = 'Opsi Pembelian Saham';
                } else {
                    $text = 'Fasilitas Lainnya';
                }
                ?>
                <tr>
                    <td><?php echo ++$i . '.'; ?></td>
                    <td>
            <center>
                <?php
                if (!empty($verifItem['penerimaanfasilitas'][$lamp2->ID]['hasil']) && $status_lhkpn == '2' && $mode == 'edit') {
                    if ($verifItem['penerimaanfasilitas'][$lamp2->ID]['hasil'] == '-1') {
                        ?>
                        <i class="fa fa-minus-square hasilVerif" style="color: red; cursor: help" title="<?php echo $verifItem['penerimaanfasilitas'][$lamp2->ID]['catatan'] ?>"></i>
                    <?php } else { ?>
                        <i class="fa fa-check-square hasilVerif" style="color: blue; cursor: help" title="<?php echo $verifItem['penerimaanfasilitas'][$lamp2->ID]['catatan'] ?>"></i>
                        <?php
                    }
                }
                ?>
            </center>
            </td>
            <td><?php echo $lamp2->JENIS_FASILITAS; ?></td>
            <td><?php echo $lamp2->NAMA_FASILITAS . " " . $lamp2->KETERANGAN; ?></td>
            <td><?php echo $lamp2->PEMBERI_FASILITAS; ?></td>
            <td><?php echo $lamp2->KETERANGAN_LAIN; ?></td>
            <td nowrap="" style="text-align:center">
                <button type="button" class="btn btn-primary" href="index.php/efill/validasi_fasilitas/edit/<?php echo $lamp2->ID_imp_xl_lhkpn_fasilitas_secure; ?>" title="Edit" onclick="onButton.go(this);"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-danger" href="index.php/efill/validasi_fasilitas/hapus/<?php echo $lamp2->ID_imp_xl_lhkpn_fasilitas_secure; ?>" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash" ></i></button>
            </td>
            <?php /* if ($mode == 'edit') { ?>
              <?php if ($status_lhkpn == '2' && $hasilVerifikasi->VAL->PENERIMAANFASILITAS == '1') { ?>
              <?php } else { ?>
              <td style="text-align:center">
              <!-- <button type="button" class="btn btn-sm btn-default btn-detail" href="index.php/efill/lhkpn/detail_lampiran2/<?php echo $lamp2->ID; ?>" title="Detail"><i class="fa fa-search-plus"></i></button> -->
              <button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/efill/lhkpn/edit_lampiran2/<?php echo $lamp2->ID; ?>" title="Edit"><i class="fa fa-pencil"></i></button>
              <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/delete_lampiran2/<?php echo $lamp2->ID; ?>" title="Delete"><i class="fa fa-trash" ></i></button>
              </td>
              <?php } ?>
              <?php } */ ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if ($mode == 'edit' && $status_lhkpn == '2') { ?>
        <div class="clearfix" style="margin-top: 20px;"></div>
        <div class="col-md-7" style="margin-left: -17px;">
            <fieldset class="fieldset">
                <legend class="legend_kk">Hasil Verifikasi</legend>
                <div class="form-group">
                    <label class="control-label col-md-3">Terverfikasi <span style="float: right;">:</span></label>
                    <div class="col-md-9">
                        <p><?php echo ($hasilVerifikasi->VAL->PENERIMAANFASILITAS == '1') ? 'Ya' : 'Tidak'; ?></p>
                    </div>
                    <label class="control-label col-md-3">Alasan <span style="float: right;">:</span></label>
                    <div class="col-md-9">
                        <p><?php echo $hasilVerifikasi->MSG->PENERIMAANFASILITAS ?></p>
                    </div>
                </div>
            </fieldset>
        </div>
    <?php } ?>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
$(document).ready(function() {
    $('.hasilVerif').tooltip();

    // #wrapperHutang
    $("#wrapperLampiran2 .btn-detail").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Detail Informasi Penerimaan Fasilitas dalam Setahun', html, '', 'standart');
        });
        return false;
    });
    $("#wrapperLampiran2 .btn-add").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Tambah Informasi Penerimaan Fasilitas dalam Setahun', html, '', 'standart');
        });
        return false;
    });
    $("#wrapperLampiran2 form").submit(function (e) {
        var id = $('input[name="ID_LHKPN"]').val();

        ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/14/'+id + '/edit', $(this), $('#fasilitas').find('.contentTab'));
        return false;
    });
    // ctrl + a
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.which === 65 || e.which === 97) {
            e.preventDefault();
            $('#wrapperLampiran2 .btn-add').trigger('click');
            return false;
        }
    });
    $('#wrapperLampiran2 .btn-edit').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Edit Informasi Penerimaan Fasilitas dalam Setahun', html, '', 'standart');
        });
        return false;
    });
    $('#wrapperLampiran2 .btn-delete').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Delete Informasi Penerimaan Fasilitas dalam Setahun', html, '', 'standart');
        });
        return false;
    });
});
</script>