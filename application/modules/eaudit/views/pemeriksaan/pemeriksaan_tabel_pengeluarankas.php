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
    <h5 class="">"Informasi Pengeluaran Kas Dalam Setahun"</h5>
</div>
<div class="box-body" id="wrapperPengeluaran">
    <form class="form-horizontal" method="post" id="ajaxFormAddPK2" action="index.php/efill/lhkpn/savePK2">
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $idtext3 = [];
            $tot3 = 0;
            $sub3 = '0';
            $label = array('A', 'B', 'C');
            $golongan = $golongan_pengeluaran_kas_pn;
            $jenis = $jenis_pengeluaran_kas_pn;
            for ($i = 0; $i < count($golongan); $i++) :
                ?>
                <li class="<?= ($i == 0) ? 'active' : ''; ?>">
                    <a class="<?php echo $label[$i] . '_PENGELUARAN'; ?>" href="#tabs3<?= $label[$i] ?>" data-toggle="tab"><?= $label[$i] ?>. <?= $golongan[$i] ?></a>
                </li>
            <?php endfor; ?>
        </ul>
        <?php
        $PN = json_decode($getPenka ? $getPenka->NILAI_PENGELUARAN_KAS : "{}");
        $lain = FALSE;
        $jpA = 0;
        $jpB = 0;
        $jpC = 0;
        ?>
        <div style="width: 67%;">
            <div class="tab-content">
                <?php
                for ($i = 0; $i < count($jenis); $i++) :
                    ?>
                    <div class="tab-pane <?= ($i == 0) ? 'active' : ''; ?>" id="tabs3<?= $label[$i] ?>">
                        <?php
                        $getData = $this->mlhkpn->getInpekas($id_lhkpn, 'T_LHKPN_PENERIMAAN_KAS', 'M_JENIS_PENERIMAAN_KAS', 'M_GOLONGAN_PENERIMAAN_KAS', $golongan[$i], '1');
                        ?>
                        <table class="table table-bordered table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th width="40px">NO</th>
                                    <th>JENIS PENGELUARAN</th>
                                    <?php
                                    $style = 'style="width: 150px;"';
                                    ?>
                                    <th <?php echo $style; ?>>TOTAL NILAI PENGELUARAN KAS</th>
                                </tr>
                            </thead>
                            <tbody class="tabs<?= $label[$i] ?>">
                                <?php
                                for ($j = 0; $j < count($jenis[$i]); $j++) :
                                    ?>
                                    <tr>
                                        <td><center><?= ($j + 1) ?>.</center></td>
                                <td><?= $jenis[$i][$j] ?></td>
                                <td align="right">
                                    <?php $code = $label[$i] . $j; ?>
                                    Rp. 
                                    <?php
                                    if ($i == 0) {
                                        $jpA = $jpA + str_replace(".", "", @$PN->{$label[$i]}[$j]->$code);
                                    } elseif ($i == 1) {
                                        $jpB = $jpB + str_replace(".", "", @$PN->{$label[$i]}[$j]->$code);
                                    } elseif ($i == 2) {
                                        $jpC = $jpC + str_replace(".", "", @$PN->{$label[$i]}[$j]->$code);
                                    }
                                    echo number_format(@$PN->{$label[$i]}[$j]->$code, 0, ",", ".");
                                    ?>
                                </td>
                                </tr>
                                <?php
                            endfor;
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                endfor;
                ?>
                <table>
                    <tbody>
                        <tr>
                            <td>Total A <?php echo $golongan[0]; ?></td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right">
                                Rp. <span id="total2A">
                                    <?php
                                    echo number_format(@$jpA, 0, ",", ".");
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Total B <?php echo $golongan[1]; ?></td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right">
                                Rp. <span id="total2B">
                                    <?php
                                    echo number_format(@$jpB, 0, ",", ".");
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Total C <?php echo $golongan[2]; ?></td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right">
                                Rp. <span id="total2C">
                                    <?php
                                    echo number_format(@$jpC, 0, ",", ".");
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><b>TOTAL PENGELUARAN  (A + B + C)</b></td>
                            <td>&nbsp; = &nbsp;</td>
                            <td align="right"><b>
                                    Rp. <span class="total_semua_penerimaan3">
                                        <?php
                                        echo number_format(@$jpA + $jpB + $jpC, 0, ",", ".");
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
        $('.btnNextPengeluaran').click(function (e) {
            e.preventDefault();
            if ($('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').next('li').find('a').attr('href') == undefined) {
                $('#nav-tabs > .active').next('li').find('a').trigger('click');
            } else {
                $('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').next('li').find('a').trigger('click');
            }
        });
        $('.btnPreviousPengeluaran').click(function (e) {
            e.preventDefault();
            if ($('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').prev('li').find('a').attr('href') == undefined) {
                $('#nav-tabs > .active').prev('li').find('a').trigger('click');
                $('#wrapperPenerimaan > .form-horizontal > .nav-tabs').find('a[href="#tabsC"]').trigger('click');
            } else {
                $('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').prev('li').find('a').trigger('click');
            }
        });
        $("#ajaxFormAddPK2").submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serializeArray();
            $.post(url, data, function (res) {
                msg = {
                    success: 'Data Berhasil Disimpan!',
                    error: 'Data Gagal Disimpan!'
                };
                if (data == 0) {
                    alertify.error(msg.error);
                } else {
                    alertify.success(msg.success);
                }
                CloseModalBox();
                var ID = $('#idLhkpn').val();
                ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/12/' + ID + '/edit', block: '#block', container: $('#pengeluarankas').find('.contentTab')});
                ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/1/' + ID + '/edit', block: '#block', container: $('#final').find('.contentTab')});
                $('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').find('a').trigger('click');
            })
            return false;
        })
        // var ID = $('#idLhkpn').val();
        // ng.formProcess($("#ajaxFormAddPK"), '', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/11/'+ID, block:'#block', container:$('#penerimaankas').find('.contentTab')});
        var ID = $('#idLhkpn').val();
        ng.formProcess($("#ajakpengeluaran"), '', '', ng.LoadAjaxTabContent, {url: 'index.php/efill/lhkpn/showTable/12/' + ID + '/edit', block: '#block', container: $('#pengeluarankas').find('.contentTab')});
        $("#wrapperPengeluaran .add-umum").click(function () {
            var url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENGELUARAN Dari Pekerjaan Lainnya', html, '', 'large');
            });
            return false;
        });
        $("#wrapperPengeluaran .add-harta").click(function () {
            var url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENGELUARAN Dari Usaha Dan Kekayaan Lainnya', html, '', 'large');
            });
            return false;
        });
        $("#wrapperPengeluaran .add-lainnya").click(function () {
            var url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah PENGELUARAN Lainnya', html, '', 'large');
            });
            return false;
        });
        $(".int").inputmask("integer", {
            groupSeparator: '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false,
            'digits': 0
        });

        if ('<?= $mode ?>' == 'edit') {
            CalculatePengeluaran()
        }


    });

    function CalculatePengeluaran(alm) {

        var idtext3 = '<?= $idtext3 ?>';
        var hasil = 0;
        $.each(JSON.parse(idtext3), function (index, value) {

            var subtot3 = 0;
            for (var i = 1; i <= value; i++) {
                if ($('#text3' + index + i).val().replace(/\./g, '') > 0) {
                    nilai = parseInt($('#text3' + index + i).val().replace(/\./g, ''));
                } else {
                    nilai = 0;
                }
                ;
                subtot3 = parseInt(subtot3) + nilai;
            }
            ;

            hasil = hasil + subtot3;
            $('#subtext3' + index).html(formatAngka(subtot3));
            $(".total_semua_penerimaan3").html(formatAngka(hasil));
            $('#total2' + index).html(formatAngka(subtot3));
            // $('#subtext3input' + index).val(subtot3);
            $("#total_semua_penerimaan3input").val(hasil);
        });
    }

    function formatAngka(angka) {
        if (typeof (angka) != 'string')
            angka = angka.toString();
        var reg = new RegExp('([0-9]+)([0-9]{3})');
        while (reg.test(angka))
            angka = angka.replace(reg, '$1.$2');
        return angka;
    }
</script>


<?php else: ?>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Informasi Pengeluaran Kas"</h5>
</div>
<div class="box-body" id="wrapperPengeluaran">
    <form class="form-horizontal" method="post" id="ajaxFormAddPK2" action="index.php/efill/lhkpn/savePK2">
        <?php
        $idtext3 = [];
        $tot3 = 0;
        $sub3 = '0';
        $label = array('A', 'B', 'C');
        $golongan = array(
            'PENGELUARAN RUTIN',
            'PENGELUARAN HARTA',
            'PENGELUARAN LAINNYA',
        );
//        $jenis = array(
//            array(
//                'Biaya Rumah Tangga (termasuk Transportasi, Pendidikan, Kesehatan, Rekreasi, Pembayaran Kartu Kredit)',
//                'Biaya Sosial (keagamaan, zakat, infaq, sumbangan lain)',
//                'Pembayaran Pajak (PBB, Kendaraan, Pajak Daerah, Pajak Lain)',
//                'Pengeluaran Rutin Lainnya'
//            ),
//            array(
//                'Pembelian/Perolehan Harta Baru',
//                'Pemeliharaan/Modifikasi/Rehabilitasi Harta',
//                'Pengeluaran Non Rutin Lainnya'
//            ),
//            array(
//                'Biaya Pengurusan Waris/hibah/hadiah',
//                'Pelunasan/Angsuran Hutang',
//                'Pengeluaran Lainnya'
//            )
//        );
        
        $jenis1 = $this->mlhkpn->getFieldWhere('m_jenis_pengeluaran_kas', 'NAMA', "WHERE IS_ACTIVE = '1' AND GOLONGAN = '1'");
        $jenis2 = $this->mlhkpn->getFieldWhere('m_jenis_pengeluaran_kas', 'NAMA', "WHERE IS_ACTIVE = '1' AND GOLONGAN = '2'");
        $jenis3 = $this->mlhkpn->getFieldWhere('m_jenis_pengeluaran_kas', 'NAMA', "WHERE IS_ACTIVE = '1' AND GOLONGAN = '3'");
        $jenis = array($jenis1,$jenis2,$jenis3);
    
        $where = "WHERE IS_ACTIVE = '1' AND ID_LHKPN = '$LHKPN->ID_LHKPN'";
        $where2 = "WHERE ID_LHKPN = '$LHKPN->ID_LHKPN'";
        $getPeka = $this->mlhkpn->getValue('T_LHKPN_PENGELUARAN_KAS', $where);
        $getPekas2 = $this->mlhkpn->getValue('t_lhkpn_pengeluaran_kas2', $where2);
        $PN = @json_decode($getPeka[0]->NILAI_PENGELUARAN_KAS);
        $lain = @json_decode($getPeka[0]->LAINNYA);
        ?>
        <div class="tab-content">
            <?php
            for ($i = 0; $i < count($jenis); $i++) :
                ?>
                <?= $label[$i] ?>. <?= $golongan[$i] ?>
                <div class="" id="tabs3<?= $label[$i] ?>">
                    <?php
                    $getData = $this->mlhkpn->getInpekas($LHKPN->ID_LHKPN, 'T_LHKPN_PENERIMAAN_KAS', 'M_JENIS_PENERIMAAN_KAS', 'M_GOLONGAN_PENERIMAAN_KAS', $golongan[$i], '1');
                    ?>
                    <table class="table table-bordered table-hover">
                        <thead class="table-header">
                            <tr>
                                <th width="40px">NO</th>
                                <th>JENIS PENGELUARAN</th>
                                <th>TOTAL NILAI PENGELUARAN KAS</th>
                            </tr>
                        </thead>
                        <tbody class="tabs<?= $label[$i] ?>">
                            <?php
                            $arr_pn = array();
                            for ($j = 0; $j < count($jenis[$i]); $j++) :
                                if ($i == 1)
                                    $jj = $j + count($jenis[0]);
                                elseif ($i == 2)
//                                    $jj = $j + count($jenis[1]);
                                    $jj = $j + 7;
                                else
                                    $jj = $j;

                                $arr_pn[] = $getPekas2[$jj]->JML;
                                ?>
                                <tr>
                                    <td><center><?= ($j + 1) ?></center></td>
                            <td><?= $jenis[$i][$j]->NAMA ?></td>
                            <td align="right">
                                <div class="row">
                                    <div class="col-md-6">Rp.</div>
                                    <div class="col-md-6 text-right"> <?= number_format($getPekas2[$jj]->JML, 0, ",", "."); ?></td></div>

                                    </tr>
                                    <?php
                                endfor;
                                $data['jum' . $i] = array_sum($arr_pn);
                                $label_text = $label[$i];
                                $idtext3[$label_text] = ($j + 1);
                                $code = $label[$i] . $j;
                                ?>

                                </tbody>
                    </table>
                </div>
                <?php
            endfor;
            $idtext3 = json_encode($idtext3);
            $total = [];
            $totalALL = 0;
            if (!empty($PN)) {
                foreach ($PN as $key => $value) {
                    $total[$key] = 0;
                    foreach ($value as $hasil => $nilai) {
                        $total[$key] = $total[$key] + str_replace(".", "", $nilai);
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
                        $n = 0;
                    elseif ($key == 'B')
                        $n = 1;
                    else
                        $n = 2;
                    $subjum = $data['jum' . $n];
                    $totALL += $subjum;
                    ?>
                    <!--Total <?= $key ?> : Rp. <?php echo number_format($subjum, 0, ",", "."); ?><br>-->
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
                        <div class="col-md-4"><b>TOTAL PENGELUARAN  (A + B + C)</b> </b></div>
                        <div class="col-md-1">Rp.</div>
                        <div class="col-md-3 text-right"><span class="total_semua_penerimaan2"><?php echo number_format($totALL, 0, ",", "."); ?></span></div>
                        <div class="col-md-4"></div>
                    </div>
            <hr>
        </div>
    </form>
</div><!-- /.box-body -->

<?php endif; ?>
Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PENGELUARANKAS == '1') ? 'checked' : ''; ?> name="pengeluarankas" class="checkboxVer" value="1"> Ya</label>
<label><input type="radio" <?php echo (@$tmpData->VAL->PENGELUARANKAS == '-1') ? 'checked' : ''; ?> name="pengeluarankas" class="checkboxVer" value="0"> Tidak</label>
<br>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->PENGELUARANKAS; ?></textarea>
</div>
<div class="pull-right">
    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousPengeluaran"><i class="fa fa-backward"></i> Sebelumnya</button>
    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextPengeluaran">Simpan & Lanjut <i class="fa fa-forward"></i></button>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.btnNextPengeluaran').click(function(e) {
            e.preventDefault();
            var val = $('input[name="pengeluarankas"]:checked').val();
            modifCKEDITOR(val, 'ck_pengeluarankas');
            if ($('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').next('li').find('a').attr('href') == undefined) {
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
            } else {
                $('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').next('li').find('a').trigger('click');
            }
        });
        $('.btnPreviousPengeluaran').click(function(e) {
            e.preventDefault();
            var val = $('input[name="pengeluarankas"]:checked').val();
            modifCKEDITOR(val, 'ck_pengeluarankas');
            if ($('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').prev('li').find('a').attr('href') == undefined) {
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                $('#wrapperPenerimaan > .nav-tabs').find('a[href="#tabsC"]').trigger('click');
            } else {
                $('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').prev('li').find('a').trigger('click');
            }
        });
    });
</script>
