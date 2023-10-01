<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"LAMPIRAN 1. INFORMASI PENJUALAN/PELEPASAN HARTA KEKAYAAN"</h5>
</div>
<div class="box-body" id="wrapperLampiran1">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th>URAIAN</th>
                <th>URAIAN HARTA</th>
                <th>NILAI</th>
                <th>INFORMASI PIHAK KEDUA</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($lhkpn_pelepasan): ?>
            <?php $no = 0;
            $tot = ''; ?>
            <?php
            foreach ($lhkpn_pelepasan as $key) {
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
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Data Tidak Ditemukan</td>
                </tr>
            <?php endif ?>
        </tbody>
        <?php if ($lhkpn_pelepasan): ?>
        <tfoot class='table-footer'>
            <tr>
                <td colspan="4"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo ($tot !== '') ? number_format($tot, 0, '', '.') : 0; ?></b></td>
            </tr>
        </tfoot>
        <?php endif ?>
    </table>
</div><!-- /.box-body -->