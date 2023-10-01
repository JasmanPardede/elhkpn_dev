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
    <h5 class="">"Informasi Penerimaan Hibah Dalam Setahun"</h5>
</div>
<div class="box-body" id="wrapperLampiran1_1">
        <!-- <button type="button" class="btn btn-sm btn-default btn-add btn-primary" href="index.php/efill/lhkpn/add_lampiran1/<?php echo $id_lhkpn;?>"><i class="fa fa-plus"></i> Tambah</button> -->
        <div class="box-tools" style="display:none;">
           <form method="post" id="ajaxFormCari19" action="">
               <div class="input-group  pull-right" style="width: 150px;">
                   <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search by Pihak 2" value="<?=@$cari?>" id="cari"/>
                   <div class="input-group-btn">
                       <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                       <button type="button" class="btn btn-sm btn-default" id="ajaxClearCari19">Clear</button>
                   </div>
               </div>
           </form>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">NO</th>
                    <th width="10%">KODE JENIS</th>
                    <th width="10%">TANGGAL TRANSAKSI</th>
                    <th width="50%">URAIAN HARTA KEKAYAAN / ATAS NAMA</th>
                    <th width="10%">NILAI PENERIMAAN</th>
                    <th width="10%">JENIS PENERIMAAN</th>
                    <th width="10%">INFORMASI PIHAK KEDUA</th>
                    <!-- <th rowspan="2" width='130px'>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; $totalHibah = 0; ?>
                <?php foreach ($lampiran_hibah as $key) {
                    $totalHibah += $key->nilai;
                    $no++;
                    $uraian = preg_split('(pada bank | dengan nomor rekening )',$key->uraian);
                    if (strlen($uraian[1]) >= 32 && strlen($uraian[2]) >= 32 ){
                        $decrypt_namabank = encrypt_username($uraian[1],'d');
                        $decrypt_norek = encrypt_username($uraian[2],'d');
                        $uraian_final = $uraian[0] . 'pada bank ' . $decrypt_namabank . ' dengan nomor rekening ' .$decrypt_norek;
                    } else {
                        $decrypt_namabank = $uraian[1];
                        $decrypt_norek = $uraian[2];
                        $uraian_final = $uraian[0] . $decrypt_namabank . $decrypt_norek;
                    }
                    ?>
                    <tr>
                        <td><?=$no?></td>
                        <td><?=$key->kode;?></td>
                        <td><?=tgl_format($key->tgl);?></td>
                        <td><?=$uraian_final;?></td>
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
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
$(document).ready(function() {
    $("#wrapperLampiran1_1 form").submit(function (e) {
        var id = $('input[name="ID_LHKPN"]').val();

        ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/19/'+id, $(this), $('#penerimaanhibah').find('.contentTab'));
        return false;
    });
    $("#ajaxClearCari19").click(function (e) {
        var id = $('input[name="ID_LHKPN"]').val();
        $('#wrapperLampiran1_1 input[name="cari"]').val('');

        ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/19/'+id, $(this), $('#penerimaanhibah').find('.contentTab'));
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