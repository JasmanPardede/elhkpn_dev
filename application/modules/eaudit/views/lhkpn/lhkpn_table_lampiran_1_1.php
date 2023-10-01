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
    <h5 class="">"Informasi Penerimaan Hibah Dalam Setahun</h5>
</div>
<div class="box-body" id="wrapperLampiran1_1">
        <!-- <button type="button" class="btn btn-sm btn-default btn-add btn-primary" href="index.php/efill/lhkpn/add_lampiran1/<?php echo $id_lhkpn;?>"><i class="fa fa-plus"></i> Tambah</button> -->
        <div class="box-tools">
        <?php if($mode == 'edit') { ?>
           <form method="post" id="ajaxFormCari19" action="">
               <div class="input-group  pull-right" style="width: 150px;">
                   <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search by Pihak 2" value="<?=@$cari?>" id="cari"/>
                   <div class="input-group-btn">
                       <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                       <button type="button" class="btn btn-sm btn-default" id="ajaxClearCari19">Clear</button>
                   </div>
               </div>
           </form>
        <?php } ?>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">NO</th>
                    <th>KODE JENIS</th>
                    <th>TAHUN TRANSAKSI</th>
                    <th>URAIAN HARTA KEKAYAAN / ATAS NAMA</th>
                    <th width="150px" style="white-space: nowrap;">NILAI PENERIMAAN</th>
                    <th>JENIS PENERIMAAN</th>
                    <th>INFORMASI PIHAK KEDUA</th>
                    <!-- <th rowspan="2" width='130px'>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
            <?php
            echo (count($lampiran_hibah) == 0 ? '<tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
                <?php $no = 0; $totalHibah = 0; ?>
                <?php foreach ($lampiran_hibah as $key) {
                    $totalHibah += $key->nilai;
                    $no++;
                    ?>
                    <tr>
                        <td><?=$no?></td>
                        <td><?=$key->kode;?></td>
                        <td><?=tgl_format($key->tgl);?></td>
                        <td><?=replaceRegex($key->uraian);?></td>
                        <td class="text-right">
                            Rp <?php echo number_format($key->nilai,0,'','.');?>
                        </td>
                        <td><?=$key->jenis;?></td>
                        <td>
                            <div>
                                <div><label>Nama:</label></div>
                                <div><?=$key->nama;?></div>
                            </div>
                            <div>
                                <div><label>Alamat:</label></div>
                                <div><?=$key->almat;?></div>
                            </div>
                        </td>
                        <!-- <td style="text-align:center">
                            <button type="button" class="btn btn-sm btn-default btn-detail" href="index.php/efill/lhkpn/detail_lampiran1/10" title="Detail"><i class="fa fa-search-plus"></i></button>
                            <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/efill/lhkpn/edit_lampiran1/10" title="Edit"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/efill/lhkpn/delete_lampiran1/10" title="Delete"><i class="fa fa-trash"  style="color:red;"></i></button>
                        </td> -->
                    </tr>
                    <?php
                } ?>
            </tbody>
            <tfoot class='table-footer'>
                <tr>
                    <td colspan="4"><b>Sub Total/Total</b></td>
                    <td align="right"><b>Rp. <?php echo number_format(@$totalHibah,0,'','.');?></b></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <?php if($mode == 'edit' && $status_lhkpn == '2') { ?>
        <div class="clearfix" style="margin-top: 20px;"></div>
        <div class="col-md-7" style="margin-left: -17px;">
            <fieldset class="fieldset">
                <legend class="legend_kk">Hasil Verifikasi</legend>
                <div class="form-group">
                    <label class="control-label col-md-3">Terverfikasi <span style="float: right;">:</span></label>
                    <div class="col-md-9">
                        <p><?php echo ($hasilVerifikasi->VAL->PENERIMAANHIBAH == '1') ? 'Ya' : 'Tidak'; ?></p>
                    </div>
                    <label class="control-label col-md-3">Alasan <span style="float: right;">:</span></label>
                    <div class="col-md-9">
                        <p><?php echo $hasilVerifikasi->MSG->PENERIMAANHIBAH ?></p>
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
    $("#wrapperLampiran1_1 form").submit(function (e) {
        var id = $('input[name="ID_LHKPN"]').val();

        ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/19/'+id + '/edit', $(this), $('#penerimaanhibah').find('.contentTab'));
        return false;
    });
    $("#ajaxClearCari19").click(function (e) {
        var id = $('input[name="ID_LHKPN"]').val();
        $('#wrapperLampiran1_1 input[name="cari"]').val('');

        ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/19/'+id + '/edit', $(this), $('#penerimaanhibah').find('.contentTab'));
        return false;
    });
    // #wrapperHutang
    $("#wrapperLampiran1_1 .btn-detail").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Detail Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
        });
        return false;
    });
    $("#wrapperLampiran1_1 .btn-add").click(function() {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Tambah Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
        });
        return false;
    });
    // ctrl + a
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.which === 65 || e.which === 97) {
            e.preventDefault();
            $('#wrapperLampiran1_1 .btn-add').trigger('click');
            return false;
        }
    });
    $('#wrapperLampiran1_1 .btn-edit').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Edit Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
        });
        return false;
    });
    $('#wrapperLampiran1_1 .btn-delete').click(function(e) {
        url = $(this).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Delete Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
        });
        return false;
    });
});
</script>