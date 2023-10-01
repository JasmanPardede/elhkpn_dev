<?php
//penerimaan kas


$PnN = @json_decode($getPemka->NILAI_PENERIMAAN_KAS_PN);
$PnA = @json_decode($getPemka->NILAI_PENERIMAAN_KAS_PASANGAN);


$totaln = [];
$totalnALL = 0;

//A penerimaan Pasangan
if (!empty($PnA)) {
    foreach ($PnA as $key => $fieldPnA) {
        $totaln['A'] = isset($totaln['A'])?$totaln['A']:0 + current((array) $fieldPnA);
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

$where = "WHERE ID_LHKPN = '$item->ID_LHKPN'";
$getPeka_in = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS2', $where);

$pn = new stdClass();
$pn->A = 0; $pn->B = 0; $pn->C = 0; 
$ps = new stdClass();
$ps->A = 0; $ps->B = 0; $ps->C = 0;
foreach($getPeka_in as $key => $val) {
    $jenis = $val->GROUP_JENIS;
    $pn->$jenis += $val->PN;
    $ps->$jenis += $val->PASANGAN;
}
$totalPenerimaanAll = $pn->A + $ps->A + $pn->B + $pn->C;


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

$where = "WHERE ID_LHKPN = '$item->ID_LHKPN'";
$getPeka_out = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS2', $where);

$pn2 = new stdClass();
$pn2->A = 0; $pn2->B = 0; $pn2->C = 0; 
foreach($getPeka_out as $key => $val) {
    $jenis = $val->GROUP_JENIS;
    $pn2->$jenis += $val->JML;
}
$totalPengeluaranAll = $pn2->A + $pn2->B + $pn2->C;

function nominal_in_milyar($nominal){
    $nominal = abs($nominal);
    if($nominal >= 1000000000 && $nominal <= 100000000000){        // 1M-100M highlight warna hijau
        return 1;                 
    }else if($nominal > 100000000000 && $nominal <= 500000000000){ // 100M-500M highlight warna kuning
        return 2;                
    }else if($nominal > 500000000000){                             // > 500M highlight warna merah
        return 3;                
    }else{
        return 0;
    }
}

?>

<div class="box-header with-border portlet-header">
    <h3 class="box-title">I. Ringkasan Laporan Harta Kekayaan Penyelenggara Negara</h3>
</div><!-- /.box-header -->
<div class="box-body">
    <div class="row justify-content-md-center">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div>
                <b>Jabatan PN : <?php echo $JABATANS[0]->NAMA_JABATAN; ?></b>
            </div>
        </div>
    </div>
</div>
<div class="box-body">
    <div class="row justify-content-md-center">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><b>I.1 REKAPITULASI HARTA KEKAYAAN</b></h3>
                </div><!-- /.box-header -->

                <div class="box-body no-padding">
                    <table class="table table-hover custom-hover">
                        <tr>
                            <td width="2%"></td>
                            <td></td>
                            <td width="8%" align="center"><b>Jumlah Asset</b></td>
                            <td width="2%"></td>
                            <td width="10%"><b>Total Nilai Asset</b></td>
                            <td width="2%"></td>
                            <td width="45%"><b>Terbilang</b></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaTidakBergerak');">
                            <td>1</td>
                            <td>HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)</td>
                            <td nowrap="" align="center"><?php echo (@$hartirak[0]->jumlah == 0) ? '-' : @$hartirak[0]->jumlah; ?></td>
                            <td></td>
                            <td nowrap="" align="right" id="HARTA1">
                                Rp. <?php
                                @$h1 = $hartirak[0]->sum_hartirak;
                                echo number_format($h1, 0, ",", ".");
                                ?>
                            </td>
                            <td></td>
                            <td id="TERBILANG_HARTA1" class="pembilang" data-highlight="<?=nominal_in_milyar(@$h1);?>"></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaBergerak');">
                            <td>2</td>
                            <td>HARTA BERGERAK(ALAT TRANSPORTASI DAN MESIN)</td>
                            <td nowrap="" align="center"><?php echo (@$harger[0]->jumlah == 0) ? '-' : @$harger[0]->jumlah; ?></td>
                            <td></td>
                            <td nowrap="" align="right" id="HARTA2">
                                Rp. <?php
                                @$h2 = @$harger[0]->sum_harger;
                                echo number_format($h2, 0, ",", ".");
                                ?>
                            </td>
                            <td></td>
                            <td id="TERBILANG_HARTA2" class="pembilang" data-highlight="<?=nominal_in_milyar(@$h2);?>"></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaBergerak2');">
                            <td>3</td>
                            <td>HARTA BERGERAK LAINNYA</td>
                            <td nowrap="" align="center"><?php echo (@$harger2[0]->jumlah == 0) ? '-' : @$harger2[0]->jumlah; ?></td>
                            <td></td>
                            <td nowrap="" align="right" id="HARTA3">
                                Rp. <?php
                                @$h3 = @$harger2[0]->sum_harger2;
                                echo number_format($h3, 0, ",", ".");
                                ?>
                            </td>
                            <td></td>
                            <td id="TERBILANG_HARTA3" class="pembilang" data-highlight="<?=nominal_in_milyar(@$h3);?>"></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaSurat');">
                            <td>4</td>
                            <td>SURAT BERHARGA</td>
                            <td nowrap="" align="center"><?php echo (@$suberga[0]->jumlah == 0) ? '-' : @$suberga[0]->jumlah; ?></td>
                            <td></td>
                            <td nowrap="" align="right" id="HARTA4">
                                Rp. <?php
                                @$h4 = @$suberga[0]->sum_suberga;
                                echo number_format($h4, 0, ",", ".");
                                ?>
                            </td>
                            <td></td>
                            <td id="TERBILANG_HARTA4" class="pembilang" data-highlight="<?=nominal_in_milyar(@$h4);?>"></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaKas');">
                            <td>5</td>
                            <td>KAS DAN SETARA KAS</td>
                            <td nowrap="" align="center"><?php echo (@$kaseka[0]->jumlah == 0) ? '-' : @$kaseka[0]->jumlah; ?></td>
                            <td></td>
                            <td nowrap="" align="right" id="HARTA5">
                                Rp. <?php
                                @$h5 = @$kaseka[0]->sum_kaseka;
                                echo number_format($h5, 0, ",", ".");
                                ?>
                            </td>
                            <td></td>
                            <td id="TERBILANG_HARTA5" class="pembilang" data-highlight="<?=nominal_in_milyar(@$h5);?>"></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaLainnya');">
                            <td>6</td>
                            <td>HARTA LAINNYA</td>
                            <td nowrap="" align="center"><?php echo (@$harlin[0]->jumlah == 0) ? '-' : @$harlin[0]->jumlah; ?></td>
                            <td></td>
                            <td nowrap="" align="right" id="HARTA6">
                                Rp. <?php
                                @$h6 = @$harlin[0]->sum_harlin;
                                echo number_format($h6, 0, ",", ".");
                                ?>
                            </td>
                            <td></td>
                            <td id="TERBILANG_HARTA6" class="pembilang" data-highlight="<?=nominal_in_milyar(@$h6);?>"></td>
                        </tr>
                        <tr class="none">
                            <td></td>
                            <td><b>SUB-TOTAL HARTA</b></td>
                            <td nowrap="" style="//border-top: 1px solid #000;" align="center"><b></b></td>
                            <td></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right" id="SUBTOTAL"><b>Rp. <?php
                                    $subTtlHarta = $h1 + $h2 + $h3 + $h4 + $h5 + $h6;
                                    echo number_format($subTtlHarta, 0, ",", ".");
                                    ?></b></td>
                            <td></td>
                            <td id="TERBILANG_SUBTOTAL" class="pembilang" data-highlight="<?=nominal_in_milyar(@$subTtlHarta);?>"></td>
                        </tr>
                        <tr onclick="ITEM('link_harta', 'navTabHartaHutang');">
                            <td>7</td>
                            <td>HUTANG</td>
                            <td nowrap="" align="center"><?php echo (@$_hutang[0]->jumlah == 0) ? '-' : @$_hutang[0]->jumlah; ?></td>
                            <td></td>
                            <td nowrap="" align="right" id="HUTANG">Rp. <?php
                                @$h7 = @$_hutang[0]->sum_hutang;
                                echo number_format($h7, 0, ",", ".");
                                ?></td>
                            <td></td>
                            <td id="TERBILANG_HUTANG" class="pembilang" data-highlight="<?=nominal_in_milyar(@$h7);?>"></td>
                        </tr>
                        <tr class="none">
                            <td></td>
                            <td><b>TOTAL HARTA KEKAYAAN</b></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="center"><b></b></td>
                            <td></td>
                            <td nowrap="" style="border-top: 1px solid #000;" align="right" id="TOTAL_HARTA"><b>Rp. <?php echo number_format($subTtlHarta - $h7, 0, ",", "."); ?></b></td>
                            <td></td>
                            <td id="TERBILANG_TOTAL_HARTA" class="pembilang" data-highlight="<?=nominal_in_milyar(@$subTtlHarta-$h7);?>"></td>
                        </tr>
                    </table>
                </div><!-- /.box-body -->
            </div>
            
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>I.2 REKAPITULASI PENERIMAAN KAS</b></h3>
                </div>
                <table class="table table-hover custom-hover">
                    <tr>
                        <td width="2%"></td>
                        <td></td>
                        <td width="10%"><b>Total Per Penerimaan</b></td>
                        <td width="2%"></td>
                        <td width="45%"><b>Terbilang</b></td>
                    </tr>
                    <?php
                    // $totalPenerimaanPemasukanBeneran = 0;
                    // $tot1 = '0';
                    // $namecoden = 'A';
                    // $nc = 'A';
                    // foreach ($getGolongan1 as $key) :
                    //     $nameClass = $nc++ . '_PENERIMAAN';
                    // $keycode = $namecoden++;
                    // $totalPenerimaanPemasukanBeneran += $totaln[$keycode];
                        ?>
                        <!-- <tr onclick="ITEM('link_penerimaan', '<?php echo $nameClass; ?>');">
                            <td></td>
                            <td><?php echo @$key->NAMA_GOLONGAN; ?></td>
                            <td nowrap="" align="right">Rp. <?php echo @number_format($totaln[$keycode], 0, ",", "."); ?></td>
                        </tr> -->
                    <?php //endforeach; 
                    if($LHKPN->entry_via == '1'){
                    ?>
                    <tr>
                        <td>A</td>
                        <td>PENERIMAAN PEKERJAAN</td>
                        <td nowrap="" align="right" id="A">Rp. <?php echo number_format(@$jAP, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_A" class="pembilang" data-highlight="<?=nominal_in_milyar(@$jAP);?>"></td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td>PENERIMAAN USAHA ATAU KEKAYAAN</td>
                        <td nowrap="" align="right" id="B">Rp. <?php echo number_format(@$JB, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_B" class="pembilang" data-highlight="<?=nominal_in_milyar(@$JB);?>"></td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td>PENERIMAAN LAINNYA</td>
                        <td nowrap="" align="right" id="C">Rp. <?php echo number_format(@$jC, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_C" class="pembilang" data-highlight="<?=nominal_in_milyar(@$JC);?>"></td>
                    </tr>
                    <tr class="none">
                        <td></td>
                        <td><b>TOTAL PENERIMAAN</b></td>
                        <!--<td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($totalnALL, 0, ",", "."); ?></b></td>-->
                        <?php $totalPenerimaan = @$jAP + @$JB + @$jC; ?>
                        <td nowrap="" style="border-top: 1px solid #000;" align="right" id="TOTAL"><b>Rp. <?php echo number_format($totalPenerimaan, 0, ",", "."); ?></b></td>
                        <td></td>
                        <td id="TERBILANG_TOTAL" class="pembilang" data-highlight="<?=nominal_in_milyar(@$totalPenerimaan);?>"></td>
                    </tr>
                    <?php }else{ ?>
                    <tr>
                        <td>A</td>
                        <td>PENERIMAAN PEKERJAAN</td>
                        <?php $penerimaanPekerjaan= $pn->A + $ps->A; ?>
                        <td nowrap="" align="right" id="A">Rp. <?php echo number_format($penerimaanPekerjaan, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_A" class="pembilang" data-highlight="<?=nominal_in_milyar(@$penerimaanPekerjaan);?>"></td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td>PENERIMAAN USAHA ATAU KEKAYAAN</td>
                        <td nowrap="" align="right" id="B">Rp. <?php echo number_format($pn->B, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_B" class="pembilang" data-highlight="<?=nominal_in_milyar($pn->B);?>">></td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td>PENERIMAAN LAINNYA</td>
                        <td nowrap="" align="right" id="C">Rp. <?php echo number_format($pn->C, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_C" class="pembilang" data-highlight="<?=nominal_in_milyar($pn->C);?>"></td>
                    </tr>
                    <tr class="none">
                        <td></td>
                        <td><b>TOTAL PENERIMAAN</b></td>
                        <!--<td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($totalnALL, 0, ",", "."); ?></b></td>-->
                        <td nowrap="" style="border-top: 1px solid #000;" align="right" id="TOTAL"><b>Rp. <?php echo number_format($totalPenerimaanAll, 0, ",", "."); ?></b></td>
                        <td></td>
                        <td id="TERBILANG_TOTAL" class="pembilang" data-highlight="<?=nominal_in_milyar($totalPenerimaanAll);?>"></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><b>I.3 REKAPITULASI PENGELUARAN KAS</b></h3>
                </div>
                <table class="table table-hover custom-hover">
                    <tr>
                        <td width="2%"></td>
                        <td></td>
                        <td width="10%"><b>Total Per Pengeluaran</b></td>
                        <td width="2%"></td>
                        <td width="45%"><b>Terbilang</b></td>
                    </tr>
                    <?php
                    if($LHKPN->entry_via == '1'){
                    ?>
                    <tr>
                        <td>A</td>
                        <td>PENGELUARAN RUTIN</td>
                        <td nowrap="" align="right" id="A2">Rp. <?php echo number_format(@$jpA, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_A2" class="pembilang" data-highlight="<?=nominal_in_milyar(@$jpA);?>"></td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>PENGELUARAN HARTA</td>
                        <td nowrap="" align="right" id="B2">Rp. <?php echo number_format(@$jpB, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_B2" class="pembilang" data-highlight="<?=nominal_in_milyar(@$jpB);?>"></td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>PENGELUARAN LAINNYA</td>
                        <td nowrap="" align="right" id="C2">Rp. <?php echo number_format(@$jpC, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_C2" class="pembilang" data-highlight="<?=nominal_in_milyar(@$jpC);?>"></td>
                    </tr>
                    <tr class="none">
                        <td></td>
                        <td><b>TOTAL PENGELUARAN</b></td>
                        <?php $totalPengeluaran = @$jpA + $jpB + $jpC; ?>
                        <td nowrap="" style="border-top: 1px solid #000;" align="right" id="TOTAL2"><b>Rp. <?php echo number_format($totalPengeluaran, 0, ",", "."); ?></b></td>
                        <td></td>
                        <td id="TERBILANG_TOTAL2" class="pembilang" data-highlight="<?=nominal_in_milyar($totalPengeluaran);?>"></td>
                    </tr>
                    <tr class="none">
                        <td></td>
                        <td><b>PENERIMAAN BERSIH</b></td>
                        <?php $penerimaanBersih = (@$jAP + $JB + $jC) - (@$jpA + $jpB + $jpC); ?>
                        <td  align="right" nowrap="" style="border-top: 1px solid #000;" id="BERSIH"><b>Rp. <?php echo @number_format($penerimaanBersih, 0, ",", "."); ?></b></td>
                        <td></td>
                        <td id="TERBILANG_BERSIH" class="pembilang" data-highlight="<?=nominal_in_milyar($penerimaanBersih);?>"></td>
                    </tr>
                    <?php }else{ ?>
                        <tr>
                        <td>A</td>
                        <td>PENGELUARAN RUTIN</td>
                        <td nowrap="" align="right" id="A2">Rp. <?php echo number_format($pn2->A, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_A2" class="pembilang" data-highlight="<?=nominal_in_milyar($pn2->A);?>"></td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>PENGELUARAN HARTA</td>
                        <td nowrap="" align="right" id="B2">Rp. <?php echo number_format($pn2->B, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_B2" class="pembilang" data-highlight="<?=nominal_in_milyar($pn2->B);?>"></td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>PENGELUARAN LAINNYA</td>
                        <td nowrap="" align="right" id="C2">Rp. <?php echo number_format($pn2->C, 0, ",", "."); ?></td>
                        <td></td>
                        <td id="TERBILANG_C2" class="pembilang" data-highlight="<?=nominal_in_milyar($pn2->C);?>"></td>
                    </tr>
                    <tr class="none">
                        <td></td>
                        <td><b>TOTAL PENGELUARAN</b></td>
                        <td nowrap="" style="border-top: 1px solid #000;" align="right" id="TOTAL2"><b>Rp. <?php echo number_format($totalPengeluaranAll, 0, ",", "."); ?></b></td>
                        <td></td>
                        <td id="TERBILANG_TOTAL2" class="pembilang" data-highlight="<?=nominal_in_milyar($totalPengeluaranAll);?>"></td>
                    </tr>
                    <tr class="none">
                        <td></td>
                        <td><b>PENERIMAAN BERSIH</b></td>
                        <?php $penerimaanBersih = $totalPenerimaanAll - $totalPengeluaranAll ?>
                        <td nowrap="" style="border-top: 1px solid #000;" align="right" id="BERSIH"><b>Rp. <?php echo number_format($penerimaanBersih, 0, ",", "."); ?></b></td>
                        <td></td>
                        <td id="TERBILANG_BERSIH" class="pembilang" data-highlight="<?=nominal_in_milyar($penerimaanBersih);?>"></td>
                    </tr>
                    <?php } ?>
                </table>
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

    showFieldEver('#HARTA1','#TERBILANG_');
    showFieldEver('#HARTA2','#TERBILANG_');
    showFieldEver('#HARTA3','#TERBILANG_');
    showFieldEver('#HARTA4','#TERBILANG_');
    showFieldEver('#HARTA5','#TERBILANG_');
    showFieldEver('#HARTA6','#TERBILANG_');
    showFieldEver('#SUBTOTAL','#TERBILANG_');
    showFieldEver('#HUTANG','#TERBILANG_');
    showFieldEver('#TOTAL_HARTA','#TERBILANG_');

    showFieldEver('#A','#TERBILANG_');
    showFieldEver('#B','#TERBILANG_');
    showFieldEver('#C','#TERBILANG_');
    showFieldEver('#TOTAL','#TERBILANG_');

    showFieldEver('#A2','#TERBILANG_');
    showFieldEver('#B2','#TERBILANG_');
    showFieldEver('#C2','#TERBILANG_');
    showFieldEver('#TOTAL2','#TERBILANG_');
    showFieldEver('#BERSIH','#TERBILANG_');

    $('.pembilang[data-highlight="1"]').css('background-color', 'green').css('color', 'white');
    $('.pembilang[data-highlight="2"]').css('background-color', 'yellow').css('color', 'black');
    $('.pembilang[data-highlight="3"]').css('background-color', 'red').css('color', 'white');

        
    // $('.pembilang:contains("Milyar")').css('background-color', 'red').css('color', 'white');
    // $('.pembilang:contains("Triliun")').css('background-color', 'red').css('color', 'white');
    // $('.pembilang:contains("Diluar Batas")').css('background-color', 'red').css('color', 'white');

</script>