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
<div class="pull-right">
    <button type="button" class="btn btn-sm btn-warning btnPreviousPenerimaan"><i class="fa fa-backward"></i> Sebelumnya</button>
    <button type="button" class="btn btn-sm btn-warning btnNextPenerimaan">Selanjutnya <i class="fa fa-forward"></i></button>
</div>

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
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENERIMAAN Dari Pekerjaan Lainnya', html, '', 'large');
            });
            return false;
        });
        $("#wrapperPenerimaan .add-harta").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENERIMAAN Dari Usaha Dan Kekayaan Lainnya', html, '', 'large');
            });
            return false;
        });
        $("#wrapperPenerimaan .add-lainnya").click(function () {
            url = $(this).attr('href');
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
