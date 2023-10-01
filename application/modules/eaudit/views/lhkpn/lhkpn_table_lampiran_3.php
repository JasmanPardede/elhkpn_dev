
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
    <h5 class="">"LAMPIRAN 3. SURAT KUASA MENGUMUMKAN"</h5>
</div>
<div class="box-body">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">No.</th>
                <th>Nama Keluarga</th>
                <th width="30%">Upload Surat Kuasa</th>
                <th rowspan="2" width='130px'>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        echo (count($keluargas) == 0 ? '<tr><td colspan="9" class="items-null">Data tidak ditemukan!</td></tr>' : '');
        ?>
            <?php
            $i = 0;
            foreach ($keluargas as $keluarga) {
            ?>
            <tr>
                <td><?php echo ++$i; ?></td>
                <td><?php echo $keluarga->NAMA; ?><br>
                    <?php
                    switch ($keluarga->HUBUNGAN) {
                        case 1 : echo 'Istri';
                            break;
                        case 2 : echo 'Suami';
                            break;
                        case 3 : echo 'Anak Tanggungan';
                            break;
                        case 4 : echo 'Anak Bukan Tanggungan';
                            break;
                    };
                    ?>
                </td>
                <td style="text-align:center">
                    <input id="exampleInputFile" type="file">
                </td>
                <td></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->