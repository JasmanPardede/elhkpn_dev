<style type="text/css">
    .review-harta th {
        padding-top:10px;
        padding-bottom:10px;
        padding-right:10px; 
        background-color: white;  
    }

    .review-harta th:first-child {
        padding-left:16px;
    }

    .review-harta td {
        padding-top:8px;
        padding-bottom:8px;
        padding-right:10px;
        border-top: 1px solid #ddd;
    }

    .review-harta td:first-child {
        padding-left:20px;
    }

</style>

<?php
    $SUM_HARTA_OLD = $total_htb_old + $total_hb_old + $total_hbl_old + $total_sb_old + $total_kas_old + $total_lainnya_old;
    $SUM_HARTA = $total_htb + $total_hb + $total_hbl + $total_sb + $total_kas + $total_lainnya;
    $TOTAL_HARTA_OLD = $SUM_HARTA_OLD - $total_hutang_old;
    $TOTAL_HARTA = $SUM_HARTA - $total_hutang;
?>

<div class="col-lg-12" id="REVIEW_HARTA_KLARIFIKASI">
    <div class="box box-success">
        <div class="box-header with-border title-alat title-box">
            <h4><b>1.1 PERBANDINGAN KLARIFIKASI HARTA KEKAYAAN</b></h4>
        </div>
        <div class="box-body">
            <table class="" width="100%">
                <thead class="review-harta">
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Jenis Harta</th>
                        <th class="text-left" colspan="3">Menurut PN</th>
                        <th class="text-left" colspan="2">Hasil Klarifikasi</th>
                    </tr>
                </thead>
                <tbody class="review-harta">
                    <tr>
                        <td width="4%">1</td>
                        <td width="43%">HARTA TIDAK BERGERAK ( TANAH DAN / ATAU BANGUNAN )</td>
                        <td width="5%">Rp.</td>
                        <td width="20%" id="DATA0X" class="result-row2"><?= number_rupiah($total_htb_old); ?></td>
                        <td width="3%"></td>
                        <td width="5%">Rp.</td>
                        <td width="20%" id="DATA0" class="result-row2"><?= number_rupiah($total_htb); ?></td>
                    </tr>
                        <tr>
                        <td>2</td>
                        <td>HARTA BERGERAK ( ALAT TRANSPORTASI DAN MESIN )</td>
                        <td>Rp.</td>
                        <td id="DATA1X" class="result-row2"><?= number_rupiah($total_hb_old); ?></td>
                        <td></td>
                        <td>Rp.</td>
                        <td id="DATA1" class="result-row2"><?= number_rupiah($total_hb); ?></td>
                    </tr>
                        <tr>
                        <td>3</td>
                        <td>HARTA BERGERAK LAINNYA</td>
                        <td>Rp.</td>
                        <td id="DATA2X" class="result-row2"><?= number_rupiah($total_hbl_old); ?></td>
                        <td></td>
                        <td>Rp.</td>
                        <td id="DATA2" class="result-row2"><?= number_rupiah($total_hbl); ?></td>
                    </tr>
                        <tr>
                        <td>4</td>
                        <td>SURAT BERHARGA</td>
                        <td>Rp.</td>
                        <td id="DATA3X" class="result-row2"><?= number_rupiah($total_sb_old); ?></td>
                        <td></td>
                        <td>Rp.</td>
                        <td id="DATA3" class="result-row2"><?= number_rupiah($total_sb); ?></td>
                    </tr>
                        <tr>
                        <td>5</td>
                        <td>KAS DAN SETARA KAS</td>
                        <td>Rp.</td>
                        <td id="DATA4X" class="result-row2"><?= number_rupiah($total_kas_old); ?></td>
                        <td></td>
                        <td>Rp.</td>
                        <td id="DATA4" class="result-row2"><?= number_rupiah($total_kas); ?></td>
                    </tr>
                        <tr>
                        <td>6</td>
                        <td>HARTA LAINNYA</td>
                        <td>Rp.</td>
                        <td id="DATA5X" class="sum-total result-row2"><?= number_rupiah($total_lainnya_old); ?></td>
                        <td></td>
                        <td>Rp.</td>
                        <td id="DATA5" class="sum-total result-row2"><?= number_rupiah($total_lainnya); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><b>SUB TOTAL HARTA</b></td>
                        <td><b>Rp.</b></td>
                        <td id="SUBTOTALX" class="result-row2"><b><?= number_rupiah($SUM_HARTA_OLD); ?></b></td>
                        <td></td>
                        <td><b>Rp.</b></td>
                        <td id="SUBTOTAL" class="result-row2"><b><?= number_rupiah($SUM_HARTA); ?></b></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>HUTANG</td>
                        <td>Rp.</td>
                        <td id="DATA6X" class="sum-total result-row2"><?= number_rupiah($total_hutang_old); ?></td>
                        <td></td>
                        <td>Rp.</td>
                        <td id="DATA6" class="sum-total result-row2"><?= number_rupiah($total_hutang); ?></td>
                    </tr>
                        <tr>
                        <td></td>
                        <td><b>TOTAL HARTA KEKAYAAN</b></td>
                        <td><b>Rp.</b></td>
                        <td id="TOTAL_SEMUAX" class="result-row2"><b><?= number_rupiah($TOTAL_HARTA_OLD); ?></b></td>
                        <td></td>
                        <td><b>Rp.</b></td>
                        <td id="TOTAL_SEMUA" class="result-row2"><b><?= number_rupiah($TOTAL_HARTA); ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="clearfix" class="clearfix"></div>
<div class="col-lg-6" id="penerimaan">
    <div class="box box-danger">
        <div class="box-header with-border title-alat title-box">
            <h4><b>1.2 REKAPITULASI PENERIMAAN KAS</b></h4>
        </div>
        <div class="box-body">
            <table class="review-harta" width="100%">
                <tr>
                    <td width="60%">PENERIMAAN PEKERJAAN PN & PASANGAN</td>
                    <td width="10%">Rp.</td>
                    <td width="30%" id="PENERIMAAN_A" class="result-row"></td>
                </tr>
                <tr>
                    <td>PENERIMAAN USAHA / KEKAYAAN PN & PASANGAN</td>
                    <td>Rp.</td>
                    <td id="PENERIMAAN_B" class="result-row"></td>
                </tr>
                <tr>
                    <td>PENERIMAAN LAINNYA</td>
                    <td>Rp.</td>
                    <td id="PENERIMAAN_C" class="sum-total result-row"></td>
                </tr>
                <tr>
                    <td><b>TOTAL PENERIMAAN</b></td>
                    <td><b>Rp.</b></td>
                    <td id="PENERIMAAN_TOTAL" class="result-row"></td>
                </tr>
            </table>
        </div>
    </div>
</div>	
<div class="col-lg-6" id="pengeluaran">
    <div class="box box-warning">
        <div class="box-header with-border title-alat title-box">
            <h4><b>1.3 REKAPITULASI PENGELUARAN KAS</b></h4>
        </div>
        <div class="box-body">
            <table class="review-harta" width="100%">
                <tr>
                    <td width="60%">PENGELUARAN UMUM</td>
                    <td width="10%">Rp.</td>
                    <td width="30%" id="PENGELUARAN_A" class="result-row"></td>
                </tr>
                <tr>
                    <td>PENGELUARAN HARTA</td>
                    <td>Rp.</td>
                    <td id="PENGELUARAN_B" class="result-row"></td>
                </tr>
                <tr>
                    <td>PENGELUARAN LAINNYA</td>
                    <td>Rp.</td>
                    <td id="PENGELUARAN_C" class="sum-total result-row"></td>
                </tr>
                <tr>
                    <td><b>TOTAL PENGELUARAN</b></td>
                    <td><b>Rp.</b></td>
                    <td id="PENGELUARAN_TOTAL" class="sum-total result-row"></td>
                </tr>
                <tr>
                    <td><b>PENERIMAAN BERSIH</b></td>
                    <td><b>Rp.</b></td>
                    <td id="PENERIMAAN_BERSIH" class="sum-total result-row"></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var ID_LHKPN = '<?php echo $new_id_lhkpn; ?>';
        var PENDAPATAN = 0;
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/load_data_penerimaan/' + ID_LHKPN,
            async: false,
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var total = eval(data.sum);
                    var total_a = total.SUM_A || 0;
                    var total_b = total.SUM_B || 0;
                    var total_c = total.SUM_C || 0;
                    var total_semua = total.SUM_ALL || 0;
                    $('#PENERIMAAN_A').html(numeral(total_a).format('0,0').replace(/,/g, '.'));
                    $('#PENERIMAAN_B').html(numeral(total_b).format('0,0').replace(/,/g, '.'));
                    $('#PENERIMAAN_C').html(numeral(total_c).format('0,0').replace(/,/g, '.'));
                    $('#PENERIMAAN_TOTAL').html('<b>'+numeral(total_semua).format('0,0').replace(/,/g, '.')+'</b>');
                    PENDAPATAN = total_semua;
                }
            }
        })
        var PENGELUARAN = 0;
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/load_data_pengeluaran/' + ID_LHKPN,
            async: false,
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var total = eval(data.sum);
                    var total_a = total.PSUM_A || 0;
                    var total_b = total.PSUM_B || 0;
                    var total_c = total.PSUM_C || 0;
                    var total_semua = total.PSUM_ALL || 0;
                    $('#PENGELUARAN_A').html(numeral(total_a).format('0,0').replace(/,/g, '.'));
                    $('#PENGELUARAN_B').html(numeral(total_b).format('0,0').replace(/,/g, '.'));
                    $('#PENGELUARAN_C').html(numeral(total_c).format('0,0').replace(/,/g, '.'));
                    $('#PENGELUARAN_TOTAL').html('<b>'+numeral(total_semua).format('0,0').replace(/,/g, '.')+'</b>');
                    PENGELUARAN = total_semua;
                }
            }
        })
        var BERSIH = PENDAPATAN - PENGELUARAN;
        $('#PENERIMAAN_BERSIH').html('<b>' + numeral(BERSIH).format('0,0').replace(/,/g,'.') + '</b>');
    })

</script>