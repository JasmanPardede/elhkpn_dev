<?php
//penerimaan kas

$PnN        = @json_decode($getPenka[0]->NILAI_PENERIMAAN_KAS_PN);
$PnA        = @json_decode($getPenka[0]->NILAI_PENERIMAAN_KAS_PASANGAN);

$totaln         = [];
$totalnALL      = 0;
if (!empty($PnN)) {
    foreach ($PnN as $key => $value) {
        $totaln[$key] = 0;
        foreach ($value as $hasil => $nilai) {
            $totaln[$key] = $totaln[$key] + str_replace(".","",$nilai);
        }
        if ($key == 'A') {
            foreach ($PnA as $hasilPA => $nilaiPA) {
                $totaln[$key] = $totaln[$key] + str_replace(".","",$nilaiPA);
            }
        }
        $totalnALL = $totalnALL + $totaln[$key];
    }
}


//pengeluaran kass

$PmN        = @json_decode($getPemka[0]->NILAI_PENGELUARAN_KAS);
// $PmA        = @json_decode($getPemka[0]->NILAI_PENERIMAAN_KAS_PASANGAN);

$totalm         = [];
$totalmALL      = 0;
if (!empty($PmN)) {
    foreach ($PmN as $key => $value) {
        $totalm[$key] = 0;
        foreach ($value as $hasil => $nilai) {
            $totalm[$key] = $totalm[$key] + str_replace(".","",$nilai);
        }
        // if ($key == 'A') {
        //     foreach ($PmA as $hasilPA => $nilaiPA) {
        //         $totalm[$key] = $totalm[$key] + str_replace(".","",$nilaiPA);
        //     }
        // }
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
                    <h3 class="box-title"><b>I.1 REKAPITULASI HARTA KEKAYAAN PERIODE</b></h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-hover">
                        <tr>
                            <td>1</td>
                            <td>HARTA TIDAK BERGERAK (Tanah/Bangunan)</td>
                            <td nowrap="" align="right">
                                Rp. <?php @$h1 = $hartirak[0]->sum_hartirak; echo number_format($h1,2,",","."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>HARTA BERGERAK</td>
                            <td nowrap="" align="right">
                                Rp. <?php  @$h2 = @$harger[0]->sum_harger; @$h2b = @$harger2[0]->sum_harger2; echo number_format($h2+$h2b,2,",","."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>SURAT BERHARGA</td>
                            <td nowrap="" align="right">
                                Rp. <?php @$h3 = @$suberga[0]->sum_suberga; echo number_format($h3,2,",","."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>KAS DAN SETARA KAS</td>
                            <td nowrap="" align="right">
                                Rp. <?php @$h4 = @$kaseka[0]->sum_kaseka; echo number_format($h4,2,",","."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>HARTA LAINNYA</td>
                            <td nowrap="" align="right">
                                Rp. <?php @$h5 = @$harlin[0]->sum_harlin; echo number_format($h5,2,",","."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><b>SUB-TOTAL HARTA</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php $subTtlHarta = $h1+$h2+$h2b+$h3+$h4+$h5; echo number_format($subTtlHarta,2,",","."); ?></b></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>HUTANG</td>
                            <td nowrap="" align="right">Rp. <?php @$h6 = @$_hutang[0]->sum_hutang; echo number_format($h6,2,",","."); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><b>TOTAL HARTA KEKAYAAN</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($subTtlHarta - $h6,2,",","."); ?></b></td>
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
                    <table class="table table-hover">
                        <?php
                        $tot1 = '0';
                        $namecoden = 'A';
                        foreach ($getGolongan1 as $key) :
                        ?>
                        <tr>
                            <td></td>
                            <td><?php echo @$key->NAMA_GOLONGAN; ?></td>
                            <td nowrap="" align="right">Rp. <?php echo @number_format($totaln[$namecoden++],2,",","."); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td></td>
                            <td><b>TOTAL PENERIMAAN</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($totalnALL,2,",","."); ?></b></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><b>I.3 REKAPITULASI PENGELUARAN KAS</b></h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-hover">
                        <?php
                        $tot2 = '0';
                        $namecodem = 'A';
                        foreach ($getGolongan2 as $yek) :
                            $nilai = 0;
                            // if (!empty($totalm[$namecodem++])) {
                            //     $nilai = $totalm[$namecodem++];
                            // }
                        ?>
                        <tr>
                            <td></td>
                            <td><?php echo @$yek->NAMA_GOLONGAN; ?></td>
                            <td nowrap="" align="right">Rp. <?php echo @number_format($totalm[$namecodem++],2,",","."); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td></td>
                            <td><b>TOTAL PENGELUARAN</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo @number_format($totalmALL,2,",","."); ?></b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><b>PENERIMAAN BERSIH</b></td>
                            <td  align="right" nowrap="" style="border-top: 1px solid #000;"><b>Rp. <?php echo @number_format($totalnALL - $totalmALL,2,",","."); ?></b></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->