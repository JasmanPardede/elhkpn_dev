<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Informasi Penerimaan Hibah Dalam Setahun"</h5>
</div>
<div class="box-body" id="wrapperLampiran1_1">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">NO</th>
                    <th>KODE JENIS</th>
                    <th>TANGGAL TRANSAKSI</th>
                    <th>URAIAN HARTA KEKAYAAN / ATAS NAMA</th>
                    <th>NILAI PENERIMAAN</th>
                    <th>JENIS PENERIMAAN</th>
                    <th>INFORMASI PIHAK KEDUA</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($lhkpn_hibah): ?>
                    <?php $no = 0; $totalHibah = 0; ?>
                    <?php foreach ($lhkpn_hibah as $key) {
                        $totalHibah += $key->nilai;
                        $no++;
                        ?>
                        <tr>
                            <td><?php echo $no?></td>
                            <td><?php echo $key->kode;?></td>
                            <td><?php echo tgl_format($key->tgl);?></td>
                            <td><?php echo $key->uraian;?></td>
                            <td class="text-right">
                                Rp <?php echo number_format($key->nilai,0,'','.');?>
                            </td>
                            <td><?php echo $key->jenis;?></td>
                            <td>
                                <div>
                                    <div><label>Nama:</label></div>
                                    <div><?php echo $key->nama;?></div>
                                </div>
                                <div>
                                    <div><label>Alamat:</label></div>
                                    <div><?php echo $key->almat;?></div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Data Tidak Ditemukan</td>
                    </tr>
                <?php endif ?>
            </tbody>
            <?php if ($lhkpn_hibah): ?>
            <tfoot class='table-footer'>
                <tr>
                    <td colspan="4"><b>Sub Total/Total</b></td>
                    <td align="right"><b>Rp. <?php echo number_format(@$totalHibah,0,'','.');?></b></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
            <?php endif ?>
        </table>
</div>