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
                                $jj = $j + count($jenis[1]);
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