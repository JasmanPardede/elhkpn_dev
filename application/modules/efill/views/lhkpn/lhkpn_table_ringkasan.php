<?php
//penerimaan kas


$PnN = @json_decode($getPemka->NILAI_PENERIMAAN_KAS_PN);
$PnA = @json_decode($getPemka->NILAI_PENERIMAAN_KAS_PASANGAN);


$totaln = [];
$totalnALL = 0;

//A penerimaan Pasangan
if (!empty($PnA)) {
    foreach ($PnA as $key => $fieldPnA) {
        $totaln['A'] = $totaln['A'] + current((array) $fieldPnA);
        $totalnALL = $totalnALL + $totaln['A'];
    }
}

if (!empty($PnN)) {
    foreach ($PnN as $key => $value) {
        if ($key != 'A') {
            $totaln[$key] = 0;
        }
        foreach ($value as $key_val => $nilai) {
            $totaln[$key] = $totaln[$key] + current((array) $nilai);
        }
        $totalnALL = $totalnALL + $totaln[$key];
    }
}



//pengeluaran kass

$PmN = @json_decode($getPenka->NILAI_PENGELUARAN_KAS);
$totalm = [];
$totalmALL = 0;
if (!empty($PmN)) {
    foreach ($PmN as $key => $value) {
        $totalm[$key] = 0;
        foreach ($value as $hasil => $nilai) {
//            $totalm[$key] = $totalm[$key] + intval(str_replace(".", "", current((array) $nilai)));
            $totalm[$key] = $totalm[$key] + current((array) $nilai);
        }
        $totalmALL = $totalmALL + $totalm[$key];
    }
}
?>

<div class="box-header with-border portlet-header">
    <h3 class="box-title">I. Ringkasan Laporan Harta Kekayaan Penyelenggara Negara</h3>
</div><!-- /.box-header -->
<div class="box-body">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><b>I.1 REKAPITULASI HARTA KEKAYAAN</b></h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-hover custom-hover">
                        <tr onclick="ITEM('link_harta', 'navTabHartaTidakBergerak');">
                            <td>1</td>
                            <td>HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)</td>
                            <td nowrap="" align="right">
                                Rp. <?php
                                @$h1 = $hartirak[0]->sum_hartirak;
                                echo number_format($h1, 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaBergerak');">
                            <td>2</td>
                            <td>HARTA BERGERAK(ALAT TRANSPORTASI DAN MESIN)</td>
                            <td nowrap="" align="right">
                                Rp. <?php
                                @$h2 = @$harger[0]->sum_harger;
                                echo number_format($h2, 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaBergerak2');">
                            <td>3</td>
                            <td>HARTA BERGERAK LAINNYA</td>
                            <td nowrap="" align="right">
                                Rp. <?php
                                @$h3 = @$harger2[0]->sum_harger2;
                                echo number_format($h3, 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaSurat');">
                            <td>4</td>
                            <td>SURAT BERHARGA</td>
                            <td nowrap="" align="right">
                                Rp. <?php
                                @$h4 = @$suberga[0]->sum_suberga;
                                echo number_format($h4, 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaKas');">
                            <td>5</td>
                            <td>KAS DAN SETARA KAS</td>
                            <td nowrap="" align="right">
                                Rp. <?php
                                @$h5 = @$kaseka[0]->sum_kaseka;
                                echo number_format($h5, 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaLainnya');">
                            <td>6</td>
                            <td>HARTA LAINNYA</td>
                            <td nowrap="" align="right">
                                Rp. <?php
                                @$h6 = @$harlin[0]->sum_harlin;
                                echo number_format($h6, 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                        <tr class="none">
                            <td></td>
                            <td><b>SUB-TOTAL HARTA</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php
                                    $subTtlHarta = $h1 + $h2 + $h3 + $h4 + $h5 + $h6;
                                    echo number_format($subTtlHarta, 0, ",", ".");
                                    ?></b></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaHutang');">
                            <td>7</td>
                            <td>HUTANG</td>
                            <td nowrap="" align="right">Rp. <?php
                                @$h7 = @$_hutang[0]->sum_hutang;
                                echo number_format($h7, 0, ",", ".");
                                ?></td>
                        </tr>
                        <tr class="none">
                            <td></td>
                            <td><b>TOTAL HARTA KEKAYAAN</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($subTtlHarta - $h7, 0, ",", "."); ?></b></td>
                        </tr>
                    </table>
                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>I.2 REKAPITULASI PENERIMAAN KAS</b></h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-hover custom-hover">
                        <?php
                        $totalPenerimaanPemasukanBeneran = 0;
                        $tot1 = '0';
                        $namecoden = 'A';
                        $nc = 'A';
                        foreach ($getGolongan1 as $key) :
                            $nameClass = $nc++ . '_PENERIMAAN';
                        $keycode = $namecoden++;
                        $totalPenerimaanPemasukanBeneran += $totaln[$keycode];
                            ?>
                            <tr onclick="ITEM('link_penerimaan', '<?php echo $nameClass; ?>');">
                                <td></td>
                                <td><?php echo @$key->NAMA_GOLONGAN; ?></td>
                                <td nowrap="" align="right">Rp. <?php echo @number_format($totaln[$keycode], 0, ",", "."); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="none">
                            <td></td>
                            <td><b>TOTAL PENERIMAAN</b></td>
                            <!--<td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($totalnALL, 0, ",", "."); ?></b></td>-->
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($totalPenerimaanPemasukanBeneran, 0, ",", "."); ?></b></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><b>I.3 REKAPITULASI PENGELUARAN KAS</b></h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-hover custom-hover">
                        <?php
                        $tot2 = '0';
                        $namecodem = 'A';
                        $nc2 = 'A';
                        foreach ($getGolongan2 as $yek) :
                            $nameClass2 = $nc2++ . '_PENGELUARAN';
                            $nilai = 0;
                            // if (!empty($totalm[$namecodem++])) {
                            //     $nilai = $totalm[$namecodem++];
                            // }
                            ?>
                            <tr onclick="ITEM('link_pengeluaran', '<?php echo $nameClass2; ?>');">
                                <td></td>
                                <td><?php echo @$yek->NAMA_GOLONGAN; ?></td>
                                <td nowrap="" align="right">Rp. <?php echo @number_format($totalm[$namecodem++], 0, ",", "."); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="none">
                            <td></td>
                            <td><b>TOTAL PENGELUARAN</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo @number_format($totalmALL, 0, ",", "."); ?></b></td>
                        </tr>
                        <tr class="none">
                            <td></td>
                            <td><b>PENERIMAAN BERSIH</b></td>
                            <td  align="right" nowrap="" style="border-top: 1px solid #000;"><b>Rp. <?php echo @number_format($totalPenerimaanPemasukanBeneran - $totalmALL, 0, ",", "."); ?></b></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.custom-hover').css({cursor: 'pointer'});
        $('tr.none').css({cursor: 'default'});
    });

    function ITEM(param, param2) {
        $('#' + param).click();
        $('.' + param2).click();
    }
</script>