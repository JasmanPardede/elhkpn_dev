<?php
/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/ever/verification
 */

if($LHKPN->entry_via == '1'):
?>



<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Informasi Penerimaan Kas Dalam Setahun"</h5>
</div>
<div class="box-body" id="wrapperPenerimaan">
    <form class="form-horizontal" method="post" id="ajaxFormAddPK" action="index.php/efill/lhkpn/savePK">
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $idtext2 = [];
            $tot2 = 0;
            $sub2 = '0';
            $label = array('A', 'B', 'C');
            $golongan = $golongan_penerimaan_kas_pn;
            for ($i = 0; $i < count($golongan); $i++) :
                ?>
                <li class="<?= ($i == 0) ? 'active' : ''; ?>">
                    <a class="<?php echo $label[$i] . '_PENERIMAAN'; ?>" href="#tabs<?= $label[$i] ?>" data-toggle="tab" onClick="change('<?= $label[$i] ?>')"><?= $label[$i] ?>. <?= $golongan[$i] ?></a>
                </li>
            <?php endfor; ?>
        </ul>
        <?php
        $jenis = $jenis_penerimaan_kas_pn;
        $req = array(
            array('0',
                '0',
                '0',
                '0',
            ),
            array('0',
                '0',
                '0',
                '0',
                '0',
            ),
            array('0',
                '0',
                '0'
            )
        );

        $PN = json_decode($getPemka ? $getPemka->NILAI_PENERIMAAN_KAS_PN : "{}");
        $PA = json_decode($getPemka ? $getPemka->NILAI_PENERIMAAN_KAS_PASANGAN : "{}");
        $lain = FALSE;

        $jAPN = 0;
        $jAP = 0;
        $JB = 0;
        $jC = 0;
        ?>
        <div style="width: 67%;">
            <div class="tab-content">
                <?php
                for ($i = 0; $i < count($jenis); $i++) :
                    ?>
                    <div class="tab-pane <?= ($i == 0) ? 'active' : ''; ?>" id="tabs<?= $label[$i] ?>">
                        <table class="table table-bordered table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th width="40px">NO</th>
                                    <th>JENIS PENERIMAAN</th>
                                    <?php
                                    $style = 'style="width: 150px;"';
                                    ?>
                                    <?php echo ($i == 0) ? '<th ' . $style . '>PENYELENGGARA NEGARA</th>' : '<th>TOTAL NILAI PENERIMAAN KAS</th>'; ?>
                                    <?php echo ($i == 0) ? '<th ' . $style . '>PASANGAN</th>' : ''; ?>
                                </tr>
                            </thead>
                            <tbody class="tabs<?= $label[$i] ?>">
                                <?php
                                for ($j = 0; $j < count($jenis[$i]); $j++) :
                                    $PA_val = 'PA' . $j;
                                    ?>
                                    <tr>
                                        <td><center><?= ($j + 1) ?>.</center></td>
                                <td><?= $jenis[$i][$j]; ?></td>
                                <td align="right">
                                    <?php
                                    /**
                                     * lws
                                     * Penerimaan PN
                                     */
                                    ?>
                                    <?php $code = $label[$i] . $j; ?>
                                    Rp. 
                                    <?php
                                    if ($i == 0) {
                                        $jAP = $jAP + str_replace(".", "", @$PN->{$label[$i]}[$j]->$code);
                                    } elseif ($i == 1) {
                                        $JB = $JB + str_replace(".", "", @$PN->{$label[$i]}[$j]->$code);
                                    } elseif ($i == 2) {
                                        $jC = $jC + str_replace(".", "", @$PN->{$label[$i]}[$j]->$code);
                                    }

                                    echo number_format(str_replace(".", "", @$PN->{$label[$i]}[$j]->$code), 0, ",", ".");
                                    ?>
                                </td>
                                <?php if ($i == 0) : ?>
                                    <?php
                                    /**
                                     * lws
                                     * Penerimaan Pasangan
                                     */
                                    ?>
                                    <td align="right">
                                        Rp.
                                        <?php
                                        $formatted = NULL;
                                        if (is_array($PA)) {
                                            $jAPN = $jAPN + str_replace(".", "", $PA[$j]->$PA_val);
                                            $formatted = number_format(str_replace(".", "", $PA[$j]->$PA_val), 0, ",", ".");
                                        }
                                        echo!is_null($formatted) ? $formatted : '0';
                                        ?>

                                    </td>
                                <?php endif; ?>
                                </tr>
                                <?php
                            endfor;
                            $label_text = $label[$i];
                            $idtext2[$label_text] = ($j + 1);
                            $PA_val = 'PA' . $j;
                            $code = $label[$i] . $j;
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                endfor;
                $idtext2 = json_encode($idtext2);
                ?>
                <table>
                    <tbody>
                        <tr>
                            <td>Total A <?php echo $golongan[0]; ?> </td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right">
                                Rp. <span id="totalA">
                                    <?php
                                    $jAP = $jAP + $jAPN;
                                    echo number_format(@$jAP, 0, ",", ".");
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Total B <?php echo $golongan[1]; ?></td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right">
                                Rp. <span id="totalB">
                                    <?php
                                    echo number_format(@$JB, 0, ",", ".");
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Total C <?php echo $golongan[2]; ?></td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right">
                                Rp. <span id="totalC">
                                    <?php
                                    echo number_format(@$jC, 0, ",", ".");
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><b>TOTAL PENERIMAAN  (A + B + C)</b></td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right"><b>
                                    Rp. <span class="total_semua_penerimaan2">
                                        <?php
                                        echo number_format(@$jAP + $JB + $jC, 0, ",", ".");
                                        ?>
                                    </span>
                                </b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div>
    </form>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<br>
<script type="text/javascript">
    $(document).ready(function () {
        $('.btnNextPenerimaan').click(function (e) {
            e.preventDefault();
            if ($('#wrapperPenerimaan > .form-horizontal > .nav-tabs > .active').next('li').find('a').attr('href') == undefined) {
                $('#nav-tabs > .active').next('li').find('a').trigger('click');
                $('#wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3A"]').trigger('click');
            } else {
                $('#wrapperPenerimaan > .form-horizontal > .nav-tabs > .active').next('li').find('a').trigger('click');
            }
        });
        $('.btnPreviousPenerimaan').click(function (e) {
            e.preventDefault();
            if ($('#wrapperPenerimaan > .form-horizontal > .nav-tabs > .active').prev('li').find('a').attr('href') == undefined) {
                $('#nav-tabs > .active').prev('li').find('a').trigger('click');
                $('#harta > .nav-tabs').find('a[href="#hutang"]').trigger('click');
            } else {
                $('#wrapperPenerimaan > .form-horizontal > .nav-tabs > .active').prev('li').find('a').trigger('click');
            }
        });

        $(".reqA").prop("required", true);
        // var ID = $('#idLhkpn').val();
        // ng.formProcess($("#ajaxFormAddPK"), '', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/11/'+ID, block:'#block', container:$('#penerimaankas').find('.contentTab')});        
        $("#wrapperPenerimaan .add-umum").click(function () {
            var url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENERIMAAN Dari Pekerjaan Lainnya', html, '', 'large');
            });
            return false;
        });
        $("#wrapperPenerimaan .add-harta").click(function () {
            var url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENERIMAAN Dari Usaha Dan Kekayaan Lainnya', html, '', 'large');
            });
            return false;
        });
        $("#wrapperPenerimaan .add-lainnya").click(function () {
            var url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENERIMAAN Lainnya', html, '', 'large');
            });
            return false;
        });
        $(".int").inputmask("integer", {
            groupSeparator: '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false,
            'digits': 0
        });

    });

    function formatAngka(angka) {
        if (typeof (angka) != 'string')
            angka = angka.toString();
        var reg = new RegExp('([0-9]+)([0-9]{3})');
        while (reg.test(angka))
            angka = angka.replace(reg, '$1.$2');
        return angka;
    }

    function change(label) {
        if (label == 'A')
        {
            $(".reqA").prop("required", true);
            $(".reqB").prop("required", false);
        } else if (label == 'B') {
            $(".reqA").prop("required", false);
            $(".reqB").prop("required", true);
        }
    }
</script>





<?php
else:
?>
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
    <h5 class="">"Informasi Penerimaan Kas"</h5>
</div>
<div class="box-body" id="wrapperPenerimaan">
    <?php
    $idtext2 = [];
    $tot2 = 0;
    $label = array('A', 'B', 'C');
    $golongan = array(
        'PENERIMAAN PEKERJAAN',
        'PENERIMAAN USAHA / KEKAYAAN',
        'PENERIMAAN LAINNYA',
    );
//    $jenis = array(
//        array(
//            'Gaji dan tunjangan',
//            'Penghasilan dari profesi / keahlian',
//            'Honorarium',
//            'Tantiem, bonus, jasa produksi, THR',
//            'pemberian dari perusahaan',
//        ),
//        array(
//            'Hasil investasi dalam surat berharga',
//            'Hasil usaha/sewa',
//            'Bunga tabungan, bunga deposito, dan lainnya',
//            'Penjualan atau pelepasan harta',
//            'Penerimaan Lainnya',
//        ),
//        array(
//            'Perolehan hutang',
//            'Penerimaan warisan',
//            'Penerimaan hibah / hadiah',
//            'Lainnya',
//        )
//    );

    $jenis1 = $this->mlhkpn->getFieldWhere('m_jenis_penerimaan_kas', 'NAMA', "WHERE IS_ACTIVE = '1' AND GOLONGAN = '1'");
    $jenis2 = $this->mlhkpn->getFieldWhere('m_jenis_penerimaan_kas', 'NAMA', "WHERE IS_ACTIVE = '1' AND GOLONGAN = '2'");
    $jenis3 = $this->mlhkpn->getFieldWhere('m_jenis_penerimaan_kas', 'NAMA', "WHERE IS_ACTIVE = '1' AND GOLONGAN = '3'");
    $jenis = array($jenis1,$jenis2,$jenis3);
    
    $where = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$item->ID_LHKPN'";
    $wherekas2 = "WHERE ID_LHKPN = '$item->ID_LHKPN'";
    $getPeka = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS', $where);
    $getPekas2 = $this->mlhkpn->getValue('T_LHKPN_PENERIMAAN_KAS2', $wherekas2);

    $PN = @json_decode($getPeka[0]->NILAI_PENERIMAAN_KAS_PN);
    $PA = @json_decode($getPeka[0]->NILAI_PENERIMAAN_KAS_PASANGAN);
    $lain = @json_decode($getPeka[0]->LAINNYA);
    ?>
    <div class="tab-content">
        <?php
        for ($i = 0; $i < count($jenis); $i++) :
            ?>
            <?= $label[$i] ?>. <?= $golongan[$i] ?>
            <div class="" id="tabs<?= $label[$i] ?>">
                <?php
                $getData = $this->mlhkpn->getInpekas($item->ID_LHKPN, 'T_LHKPN_PENERIMAAN_KAS', 'M_JENIS_PENERIMAAN_KAS', 'M_GOLONGAN_PENERIMAAN_KAS', $golongan[$i], '1');
                ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-header">
                        <tr>
                            <th width="40px">NO</th>
                            <th>JENIS PENERIMAAN</th>
                            <?php echo ($i == 0) ? '<th>PENYELENGGARA NEGARA</th>' : '<th>TOTAL NILAI PENERIMAAN KAS</th>'; ?>
                            <?php echo ($i == 0) ? '<th>PASANGAN</th>' : ''; ?>
                        </tr>
                    </thead>
                    <tbody class="tabs<?= $label[$i] ?>">
                        <?php
                        $arr_pn = array();
                        $arr_pas = array();
                        for ($j = 0; $j < count($jenis[$i]); $j++) :
                            $PA_val = 'PA' . $j;
                            if ($i == 1)
                                $jj = $j + count($jenis[0]);
                            elseif ($i == 2)
//                                $jj = $j + count($jenis[1]);
                                $jj = $j + 10;
                            else
                                $jj = $j;
                            $arr_pn[] = $getPekas2[$jj]->PN;
                            $arr_pas[] = $getPekas2[$jj]->PASANGAN;

                            ?>

                            <tr>
                                <td><center><?= ($j + 1) ?></center></td>
                        <td><?= $jenis[$i][$j]->NAMA ?></td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">Rp.</div>
                                <div class="col-md-6 text-right"><?= number_format($getPekas2[$jj]->PN, 0, ",", "."); ?></div>
                                                        <!--      <?php $code = $label[$i] . $j; ?> Rp. <?= (@$PN->$label[$i]->$code != '') ? @$PN->$label[$i]->$code : ' -' ?> -->
                            </div>
                        </td>
                        <?php
                        if ($i == 0) :
                            ?>
                            <td align="right">
                                <div class="row">
                                    <div class="col-md-6">Rp.</div>
                                    <div class="col-md-6 text-right"> <?= number_format($getPekas2[$jj]->PASANGAN, 0, ",", "."); ?></div>
                                </div>
                                <!--   Rp. <?= (@$PA->$PA_val != '') ? @$PA->$PA_val : ' -' ?> -->
                            </td>
                            <?php
                        endif;
                        ?>
                        </tr>
                        <?php
                    endfor;
                    $data['jum' . $i] = array_sum($arr_pn);
                    $data['jump' . $i] = array_sum($arr_pas);
                    $label_text = $label[$i];
                    $idtext2[$label_text] = ($j + 1);
                    $PA_val = 'PA' . $j;
                    $code = $label[$i] . $j;
                    ?>

                    </tbody>
                </table>
            </div>
            <?php
        endfor;
        $idtext2 = json_encode($idtext2);
        $jum_pas = $data['jump0'];
        $total = [];
        $totalALL = 0;
        if (!empty($PN)) {
            foreach ($PN as $key => $value) {
                $total[$key] = 0;
                foreach ($value as $hasil => $nilai) {
                    $total[$key] = $total[$key] + str_replace(".", "", $nilai);
                }
                if ($key == 'A') {
                    foreach ($PA as $hasilPA => $nilaiPA) {
                        $total[$key] = $total[$key] + str_replace(".", "", $nilaiPA);
                    }
                }
                $totalALL = $totalALL + $total[$key];
            }
        }
        ?>
        <div style="float: bottom;">
            <?php
            $totALL = 0;
            foreach ($total as $key => $value) {
                if ($key == 'A')
                    $n = 0;elseif ($key == 'B')
                    $n = 1;
                
                    else$n = 2;
                $subjum = $data['jum' . $n];
                $subjum = ($key == 'A') ? $data['jum' . $n] + $jum_pas : $data['jum' . $n];
                $totALL += $subjum;
                ?>
               <div class="row">
                        <div class="col-md-4"><b>Total <?= $key ?> : </b></div>
                        <div class="col-md-1">Rp.</div>
                        <div class="col-md-3 text-right"><?php echo number_format($subjum, 0, ",", "."); ?></div>
                        <div class="col-md-4"></div>
                    </div>
                
                <?php
            }
            ?>
        </div>
        <!--<b>TOTAL PENERIMAAN  (A + B + C)</b> <b>Rp. <span class="total_semua_penerimaan2"><?php echo number_format($totALL, 0, ",", "."); ?></span></b>-->
        <div class="row">
                        <div class="col-md-4"><b>TOTAL PENERIMAAN  (A + B + C)</b> </b></div>
                        <div class="col-md-1">Rp.</div>
                        <div class="col-md-3 text-right"><span class="total_semua_penerimaan2"><?php echo number_format($totALL, 0, ",", "."); ?></span></div>
                        <div class="col-md-4"></div>
                    </div>
        
        <hr>
    </div>
</div>

<?php endif; ?>


Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANKAS == '1') ? 'checked' : ''; ?> name="penerimaankas" class="checkboxVer" value="1"> Ya</label>
<label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANKAS == '-1') ? 'checked' : ''; ?> name="penerimaankas" class="checkboxVer" value="0"> Tidak</label>
<br>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->PENERIMAANKAS; ?></textarea>
</div>
<br />
<div class="pull-right">
    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousPenerimaan"><i class="fa fa-backward"></i> Sebelumnya</button>
    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextPenerimaan">Simpan & Lanjut <i class="fa fa-forward"></i></button>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $('.btnNextPenerimaan').click(function(e) {
            e.preventDefault();
            var val = $('input[name="penerimaankas"]:checked').val();
            modifCKEDITOR(val, 'ck_penerimaankas');
            if ($('#wrapperPenerimaan > .nav-tabs > .active').next('li').find('a').attr('href') == undefined) {
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                $('#wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3A"]').trigger('click');
            } else {
                $('#wrapperPenerimaan > .nav-tabs > .active').next('li').find('a').trigger('click');
            }
        });
        $('.btnPreviousPenerimaan').click(function(e) {
            e.preventDefault();
            var val = $('input[name="penerimaankas"]:checked').val();
            modifCKEDITOR(val, 'ck_penerimaankas');
            if ($('#wrapperPenerimaan > .nav-tabs > .active').prev('li').find('a').attr('href') == undefined) {
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                $('#harta > .nav-tabs').find('a[href="#hutang"]').trigger('click');
            } else {
                $('#wrapperPenerimaan > .nav-tabs > .active').prev('li').find('a').trigger('click');
            }
        });
    });
</script>
