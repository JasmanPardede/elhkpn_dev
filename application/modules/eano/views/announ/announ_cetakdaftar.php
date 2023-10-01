<?php

function printRekap($item, $pre) {
    $CI = & get_instance();
    $aJNS = [
        'htb' => 'Harta Tidak Bergerak',
        'hb' => 'Harta Bergerak',
        'hbl' => 'Harta Bergerak Lainnya',
        'hs' => 'Surat Berharga',
        'hk' => 'Kas & Setara Kas',
        'hl' => 'Harta Lainnya',
        'ht' => 'Hutang'
    ];
    ?>
    <?php
}
?>


<!-- <p style="text-align: center; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;"> -->
<h2 style="font-family: Arial, Helvetica, sans-serif; text-align: center; font-size: 16px; line-height: 1.5; margin: 0px 0px 10px;">Daftar Pengumuman LHKPN</h2>
<h2 style="font-family: Arial, Helvetica, sans-serif; text-align: center; font-size: 16px; line-height: 1.5; margin: 0px 0px 10px;">Berita Negara RI Nomor <?php echo $head->NOMOR_PNRI; ?> Tanggal <?php echo date('d/m/Y', strtotime($head->TGL_PNRI)); ?>

</h2>
<br>
<br>
<?php
$t_bid = array();
$t_ins = array();
foreach ($bidangpn as $rows) {
    $t_bid[] = $rows->BDG_NAMA;
    $t_ins[] = $rows->INST_NAMA;
}
$r_bid = array_unique($t_bid);
$r_ins = array_unique($t_ins);


foreach ($r_bid as $bid) {
    ?>
    <div style="font-family:arial;display: block;margin-top: 20px;">
        <div style="font-family:arial;text-align: left;float: left;">
            Bidang    :   <?php echo $bid ?>
        </div>
    </div>
    <table cellpadding="1" cellspacing="1" border="1" width="100%">
        <thead>
            <tr>
                <th style="font-family:arial;" width="30">No.</th>
                <th style="font-family:arial;" width="30">NHK</th>
                <th style="font-family:arial;" width="100">Nama/Jabatan</th>
                <th style="font-family:arial;" width="100">Tahun/Tanggal Pelaporan</th>
                <th style="font-family:arial;" width="100">Total Nilai harta</th>
                <th style="font-family:arial;" width="100">Hutang</th>
                <th style="font-family:arial;" width="100">Total Nilai Harta Kekayaan</th>
                <th style="font-family:arial;" width="100">Keterangan</th>
            </tr>
        </thead>
         <tr>
                <th style="font-family:arial;" width="30"><small>1</small></th>
                <th style="font-family:arial;" width="30"><small>2</small></th>
                <th style="font-family:arial;" width="100"><small>3</th>
                <th style="font-family:arial;" width="100"><small>4</small></th>
                <th style="font-family:arial;" width="100"><small>5</small></th>
                <th style="font-family:arial;" width="100"><small>6</small></th>
                <th style="font-family:arial;" width="100"><small>7(5-6)</small></th>    
                <th style="font-family:arial;" width="100"><small>8</small></th>
                </tr>
        <?php
        foreach ($r_ins as $ins) {
            ?>

            <tr>
                <th style="font-family:arial; text-align:left;" colspan="8"> <small>LEMBAGA :  <?= $ins ?> </small>  </th>>
            </tr>
           

            <tbody>
                <?php
                $i = 0;
                foreach ($bidangpn as $data) {
                    $tot = $data->T1 + $data->T2 + $data->T3 + $data->T4 + $data->T5 + $data->T6;
                    if ($bid == $data->BDG_NAMA) {
                        if ($ins == $data->INST_NAMA) {
                            $i++;
                            ?>
                            <tr>
                                <td style="font-family:arial; text-align:center;"><small><?= $i ?></small></td>
                                <td style="font-family:arial; text-align:left;"><small><?= $data->NHK ?></small></td>
                                <td style="font-family:arial; text-align:left;"><small><?= $data->NAMA . ' / '. $data->DESKRIPSI_JABATAN ?></small></td>
                                <td style="font-family:arial; text-align:center;"><small><?= date('d/m/Y', strtotime($data->TGL_LAPOR))  ?></small></td>
                                <td style="font-family:arial; text-align:right;"><small><?= number_rupiah($tot) ?></small></td>
                                <td style="font-family:arial; text-align:right;"><small><?= number_rupiah($data->jumhut) ?></small></td>
                                <td style="font-family:arial; text-align:right;"><small><?= number_rupiah($tot - $data->jumhut) ?></small></td>
                                <td style="font-family:arial; text-align:left;"><small><?= $data->STATUS == 3 ? 'Terverifikasi Lengkap' : 'Terverifikasi Tidak Lengkap'; ?></small></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tbody>
            <?php
        }
        ?>
    </table>

<?php } ?>
<style type="text/css">
    td{
        vertical-align: top;
    }

    table {
        border-collapse: collapse;
    }
    .tblRekap td{
        font-size: 12px;
    }
    .tblRekap th{
        font-size: 12px;
        padding: 5px;
        font-size: 12px;
    }

</style>