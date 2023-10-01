<?php
	$array = [
        [
            'posisi' => '1',
            'Uraian' => 'Jumlah Harta Awal',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '',
            'Uraian' => '',
            '20x1'   => '',
            '20x0'   => ''
        ],
        [
            'posisi' => '1',
            'Uraian' => 'Penambahan Harta',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Pembelian/perolehan harta',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Penerimaan warisan',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Penerimaan hibah',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Penerimaan hadiah',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Peningkatan nilai harta',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '3',
            'Uraian' => 'Jumlah Penambahan Harta',
            '20x1'   => 'xxxx',
            '20x0'   => 'xxxx'
        ],
        [
            'posisi' => '',
            'Uraian' => '',
            '20x1'   => '',
            '20x0'   => ''
        ],
        [
            'posisi' => '1',
            'Uraian' => 'Pengurangan Harta',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Penjualan/Pelepasan Harta',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Pengeluaran hibah',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Pengeluaran hadiah',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '2',
            'Uraian' => 'Penurunan nilai harta',
            '20x1'   => 'xxx',
            '20x0'   => 'xxx'
        ],
        [
            'posisi' => '3',
            'Uraian' => 'Jumlah Pengurangan Harta',
            '20x1'   => 'xxxx',
            '20x0'   => 'xxxx'
        ],
        [
            'posisi' => '',
            'Uraian' => '',
            '20x1'   => '',
            '20x0'   => ''
        ],
        [
            'posisi' => '1',
            'Uraian' => 'Jumlah Harta Akhir',
            '20x1'   => 'xxxxx',
            '20x0'   => 'xxxxx'
        ],
     ];
?>
<style type="text/css">
	tabs{
		margin-left:1em;
	}
</style>

<center>
    <div class="col-md-12">
        <b>ABCD</b>
    </div>
    <div class="col-md-12">
        <b>LAPORAN PERUBAHAN HARTA</b>
    </div>
    <div class="col-md-12">
        PER 31 DESEMBER 20X1 dan 20X0
    </div>
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="40px" class="text-center">No.</th>
                    <th class="text-center">Uraian</th>
                    <th width="200px" class="text-center">20X1</th>
                    <th width="200px" class="text-center">20X0</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 0;
                    foreach ($array as $key) {
                    $no++;
                ?>
                    <tr>
                        <td class="text-center"><?=$no;?></td>
                        <td>
                            <?php
                                if ($key['posisi'] == '1') {
                                    echo $key['Uraian'];
                                }
                                elseif ($key['posisi'] == '2') {
                                    echo '<tabs/>'.$key['Uraian'];
                                }
                                elseif ($key['posisi'] == '3') {
                                    echo '<tabs/><tabs/>'.$key['Uraian'];
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                                if ($key['posisi'] == '3') {
                                    echo '<b>'.$key['20x1'].'</b>';
                                }
                                elseif ($key['Uraian'] == 'Jumlah Harta Akhir') {
                                    echo '<b>'.$key['20x1'].'</b>';
                                }
                                else
                                {
                                    echo $key['20x1'];
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                                if ($key['posisi'] == '3') {
                                    echo '<b>'.$key['20x0'].'</b>';
                                }
                                elseif ($key['Uraian'] == 'Jumlah Harta Akhir') {
                                    echo '<b>'.$key['20x0'].'</b>';
                                }
                                else
                                {
                                    echo $key['20x0'];
                                }
                            ?>
                        </td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</center>