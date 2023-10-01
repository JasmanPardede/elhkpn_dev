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
        <!-- <button type="button" class="btn btn-sm btn-default btn-add btn-primary" href="index.php/efill/lhkpn/add_lampiran1/<?php echo $id_lhkpn; ?>"><i class="fa fa-plus"></i> Tambah</button> -->
    <div class="box-tools" style="display:none;">
        <form method="post" id="ajaxFormCari13" action="">
            <div class="input-group  pull-right" style="width: 150px;">
                <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search by pihak ke 2" value="<?= @$cari ?>" id="cari"/>
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                    <button type="button" class="btn btn-sm btn-default" id="ajaxClearCari13">Clear</button>
                </div>
            </div>
        </form>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th>URAIAN</th>
                <!--<th>TANGGAL TRANSAKSI</th>-->
                <th>URAIAN HARTA</th>
                <th>NILAI</th>
                <th>INFORMASI PIHAK KEDUA</th>
                <!-- <th rowspan="2" width='130px'>Aksi</th> -->
            </tr>
        </thead>
        <tbody>
            <?php $no = 0;
            $tot = ''; ?>
            <?php
            foreach ($lampiran_pelepasan as $key) {
                $tot += $key['NILAI'];
                $no++;
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?php
                        $uraian1 = "<table class='table-child table-condensed'>
									<tr>
			                            <td><b>Jenis</b></td>
			                            <td>:</td>
			                            <td>" .$key['KODE_JENIS'] . "</td>
			                         </tr>
			                         <tr>
			                            <td><b>Keterangan</b></td>
			                            <td>:</td>
			                            <td>" . $key['KETERANGAN'] . "</td>
			                         </tr>
								</table>";


                        echo $uraian1;
//                        $key['KODE_JENIS']
                        
                        ?></td>
                    <!--<td><?= $key['TGL_TRANSAKSI']; ?></td>-->
                    <td><?= $key['URAIAN_HARTA']; ?></td>
                    <td>
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
                    <!-- <td style="text-align:center">
                        <button type="button" class="btn btn-sm btn-default btn-detail" href="index.php/efill/lhkpn/detail_lampiran1/10" title="Detail"><i class="fa fa-search-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/efill/lhkpn/edit_lampiran1/10" title="Edit"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/efill/lhkpn/delete_lampiran1/10" title="Delete"><i class="fa fa-trash"  style="color:red;"></i></button>
                    </td> -->
                </tr>
    <?php }
?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td colspan="4"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo ($tot !== '') ? number_format($tot, 0, '', '.') : 0; ?></b></td>
                <!-- <td>&nbsp;</td> -->
            </tr>
        </tfoot>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#wrapperLampiran1 form").submit(function(e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/13/' + id, $(this), $('#pelepasanharta').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCari13").click(function(e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#wrapperLampiran1 input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/13/' + id, $(this), $('#pelepasanharta').find('.contentTab'));
            return false;
        });
        // #wrapperHutang
        $("#wrapperLampiran1 .btn-detail").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Detail Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
            });
            return false;
        });
        $("#wrapperLampiran1 .btn-add").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Tambah Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
            });
            return false;
        });
        // ctrl + a
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.which === 65 || e.which === 97) {
                e.preventDefault();
                $('#wrapperLampiran1 .btn-add').trigger('click');
                return false;
            }
        });
        $('#wrapperLampiran1 .btn-edit').click(function(e) {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Edit Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
            });
            return false;
        });
        $('#wrapperLampiran1 .btn-delete').click(function(e) {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Delete Informasi Penjualan/Pelepasan Harta Kekayaan dan Penerimaan Hibah dalam Setahun', html, '', 'large');
            });
            return false;
        });
    });
</script>