<form role="form" id="FormPengeluaran">
    <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
    <?= FormHelpAccordionEfiling('pengeluaran_harta'); ?>
    <div class="box-body">
        <div class="tabbable block-body" id="tab-data-pengeluaran">
            <ul class="nav nav-tabs menu-filling">
                <li class="active"><a href="#tab-ax" id="tab-a" >A. Pengeluaran Rutin</a></li>
                <li ><a href="#tab-bx" id="tab-b" >B. Pengeluaran Harta</a></li>
                <li ><a href="#tab-cx" id="tab-c" >C. Pengeluaran Lainnya</a></li>
            </ul>
        </div>
        <div class="tab-content block-body">
            <div class="tab-pane fade in active" id="tab-ax">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>JENIS PENGELUARAN</th>
                            <th>TOTAL PENGELUARAN</th>
                        </tr>
                    </thead>
                    <tbody id="block-A">
                    </tbody>    
                </table>
            </div>
            <div class="tab-pane fade in" id="tab-bx">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>JENIS PENGELUARAN</th>
                            <th>TOTAL PENGELUARAN</th>
                        </tr>
                    </thead>
                    <tbody id="block-B">
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade in " id="tab-cx">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>JENIS PENGELUARAN</th>
                            <th>TOTAL PENGELUARAN</th>
                        </tr>
                    </thead>
                    <tbody id="block-C">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <table class="table table-input">
            <tr>
                <td>TOTAL A PENGELUARAN RUTIN</td>
                <td width="2%">=</td>
                <td width="2%">Rp.</td>
                <td width="40%" class="text-right">
                    <input type="text" id="SUM-A" name="SUM-A" class="table-input-text result" readonly="true"/>
                    <div name="TOTAL_TERBILANG_SUM-A" id="TOTAL_TERBILANG_SUM-A" style="border-radius:5px;padding:5px;font-style:italic;font-weight:bold;" class="text-right"></div>
                </td>
            </tr>	
            <tr>
                <td>TOTAL B PENGELUARAN HARTA</td>
                <td>=</td>
                <td>Rp.</td>
                <td class="text-right">
                    <input type="text" id="SUM-B" name="SUM-B" class="table-input-text result" readonly="true"/>
                    <div name="TOTAL_TERBILANG_SUM-B" id="TOTAL_TERBILANG_SUM-B" style="border-radius:5px;padding:5px;font-style:italic;font-weight:bold;" class="text-right"></div>
                </td>
            </tr>	
            <tr>
                <td>TOTAL C PENGELUARAN LAINNYA</td>
                <td>=</td>
                <td>Rp.</td>
                <td class="text-right">
                    <input type="text" id="SUM-C" name="SUM-C" class="table-input-text result" readonly="true"/>
                    <div name="TOTAL_TERBILANG_SUM-C" id="TOTAL_TERBILANG_SUM-C" style="border-radius:5px;padding:5px;font-style:italic;font-weight:bold;" class="text-right"></div>
                </td>
            </tr>	
            <tr>
                <td>TOTAL PENGELUARAN (A + B + C)</td>
                <td>=</td>
                <td>Rp.</td>
                <td class="text-right">
                    <input type="text" id="SUM-ALL" name="SUM-ALL" class="table-input-text result" readonly="true" />
                    <div name="TOTAL_TERBILANG_SUM-ALL" id="TOTAL_TERBILANG_SUM-ALL" style="border-radius:5px;padding:5px;font-style:italic;font-weight:bold;" class="text-right"></divp>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <button class="btn btn-sm btn-primary" id="btn-save" >
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <div class="clearfix"></div>
                    <br>
                    <div class="pull-right">
                        <a href="javascript:void(0)" id="tombol-A" class="btn btn-warning btn-sm" style="margin-left:5px;">
                            <i class="fa fa-backward"></i>  Sebelumnya
                        </a>
                        <a href="javascript:void(0)" id="tombol-B" class="btn btn-warning btn-sm" style="margin-left:5px;">
                            Selanjutnya <i class="fa fa-forward"></i>  
                        </a>
                    </div>
                </td>
            </tr>	
        </table>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
            $('#btn-save').hide();
        }

        var tab = new Array();
        tab[0] = '#tab-ax';
        tab[1] = '#tab-bx';
        tab[2] = '#tab-cx';

        $('#tab-a').click(function() {
            if (STATUS == '0' || STATUS == '2' || STATUS == '7' && VIA_VIA == '0') {
                $('#btn-save').show();
            } else {
                $('#btn-save').hide();
            }
        });

        $('#tab-b').click(function() {
            if (STATUS == '0' || STATUS == '2' || STATUS == '7' && VIA_VIA == '0') {
                $('#btn-save').show();
            } else {
                $('#btn-save').hide();
            }
        });

        $('#tab-c').click(function() {
            if (STATUS == '0' || STATUS == '2' || STATUS == '7' && VIA_VIA == '0') {
                $('#btn-save').show();
            } else {
                $('#btn-save').hide();
            }
        });

        $('#tombol-A').click(function() {
            var visible;
            for (i = 0; i < tab.length; i++) {
                if ($(tab[i]).is(":visible")) {
                    visible = i;
                    break;
                }
            }
            if (visible == 0) {
                pindah(5);
            } else {
                var tab_view = tab[visible - 1].replace("x", "");
                $(tab_view).tab('show');
            }
        });

        $('#tombol-B').click(function() {
            var visible;
            for (i = 0; i < tab.length; i++) {
                if ($(tab[i]).is(":visible")) {
                    visible = i;
                    break;
                }
            }
            if (visible == 2) {
                pindah(8);
            } else {
                var tab_view = tab[visible + 1].replace("x", "");
                $(tab_view).tab('show');
            }
        });

        var BLOCK_A = load_html('portal/data_pengeluaran/load_pengeluaran/A/1');
        $('#block-A').html(BLOCK_A);

        $('#tab-ax .input').each(function () {
            var id = $(this).attr('id');
            $('#TERBILANG_' + id).hide();
        });
        showfield('#tab-ax .input','#TERBILANG_');

        var BLOCK_B = load_html('portal/data_pengeluaran/load_pengeluaran/B/2');
        $('#block-B').html(BLOCK_B);

        $('#tab-bx .input').each(function () {
            var id = $(this).attr('id');
            $('#TERBILANG_' + id).hide();
            /*  rollback
            $('#B0').attr('disabled', 'disabled'); 
            rollback */
        });
        showfield('#tab-bx .input','#TERBILANG_');

        var BLOCK_C = load_html('portal/data_pengeluaran/load_pengeluaran/C/3');
        $('#block-C').html(BLOCK_C);

        $('#tab-cx .input').each(function () {
            var id = $(this).attr('id');
            $('#TERBILANG_' + id).hide();
        });
        showfield('#tab-cx .input','#TERBILANG_');

        $('#ID_LHKPN').val(ID_LHKPN);

        $('.nav-tabs a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });



        $(".input").maskMoney({allowZero: true, precision: 0, thousands: '.'});
        $('.input,.box-footer input').val(0.00);
        $('.input').on('propertychange change keyup paste input', function() {

            if ($(tab[2]).is(":visible")) {
                if (STATUS != '1') {
                    $('#btn-save').show();
                }
            }


            $('.input').maskMoney('unmasked')[0];
            var SUM_A = 0;
            var SUM_B = 0;
            var SUM_C = 0;
            var SUM_ALL = 0;
            $('#tab-ax .input').each(function() {
                var id = $(this).attr('id');
                var val = $('#' + id).val() || 0;
                var value = parseFloat(val.replace(/\./g, ''));
                SUM_A += parseInt(value) || 0;
            });
            $('#tab-bx .input').each(function() {
                var id = $(this).attr('id');
                var val = $('#' + id).val() || 0;
                var value = parseFloat(val.replace(/\./g, ''));
                SUM_B += parseInt(value) || 0;
            });
            $('#tab-cx .input').each(function() {
                var id = $(this).attr('id');
                var val = $('#' + id).val() || 0;
                var value = parseFloat(val.replace(/\./g, ''));
                SUM_C += parseInt(value) || 0;
            });
            SUM_ALL = SUM_A + SUM_B + SUM_C || 0;
            $('#SUM-A').val(numeral(SUM_A).format('0,0').replace(/,/g, '.'));
            $('#SUM-B').val(numeral(SUM_B).format('0,0').replace(/,/g, '.'));
            $('#SUM-C').val(numeral(SUM_C).format('0,0').replace(/,/g, '.'));
            $('#SUM-ALL').val(numeral(SUM_ALL).format('0,0').replace(/,/g, '.'));

            var string_nominal_A = terbilang(SUM_A);
            var string_nominal_B = terbilang(SUM_B);
            var string_nominal_C = terbilang(SUM_C);
            var string_nominal_ALL = terbilang(SUM_ALL);
            $('#TOTAL_TERBILANG_SUM-A').text('( '+string_nominal_A+' )');
            $('#TOTAL_TERBILANG_SUM-B').text('( '+string_nominal_B+' )');
            $('#TOTAL_TERBILANG_SUM-C').text('( '+string_nominal_C+' )');
            $('#TOTAL_TERBILANG_SUM-ALL').text('( '+string_nominal_ALL+' )');
        });

        $('#FormPengeluaran').submit(function() {
            $('.input').maskMoney('unmasked')[0];
            $('.input').val() || 0;
            do_submit('#FormPengeluaran', 'portal/data_pengeluaran/update', 'Data pengeluaran berhasil ditambahkan atau diperbaharui.');
            $('#btn-save').hide();
            return false;
        });

        $.ajax({
            url: base_url + 'portal/data_pengeluaran/load_data/' + ID_LHKPN,
            async: false,
            dataType: 'JSON',
            success: function(data) {
                if (data) {
                    var list = eval(data.list);
                    var total = eval(data.sum);
                    var total_a = total.SUM_A || 0;
                    var total_b = total.SUM_B || 0;
                    /* rollback var total_b0 = total.SUM_B0 || 0; rolback */
                    var total_c = total.SUM_C || 0;
                    var total_semua = total.SUM_ALL || 0;
                    for (i = 0; i < list.length; i++) {
                        var v_pn = list[i].JML || 0;
                        $('#' + list[i].KODE_JENIS).val(parseFloat(v_pn));
                        $('#' + list[i].KODE_JENIS).trigger('mask.maskMoney');
                        $('#TERBILANG_' + list[i].KODE_JENIS).hide();
                    }
                    /*
                    rollback
                    //tembak ke b0 (Pembelian/Perolehan Harta Baru)
                    $('#B0').val(total_b0).trigger('mask.maskMoney');
                    rollback
                    */
                    
                    $('#SUM-A').val(numeral(total_a).format('0,0').replace(/,/g, '.'));
                    $('#SUM-B').val(numeral(total_b).format('0,0').replace(/,/g, '.'));
                    $('#SUM-C').val(numeral(total_c).format('0,0').replace(/,/g, '.'));
                    $('#SUM-ALL').val(numeral(total_semua).format('0,0').replace(/,/g, '.'));
                    
                    showFieldTwo('#SUM-A','#TOTAL_TERBILANG_');
                    showFieldTwo('#SUM-B','#TOTAL_TERBILANG_');
                    showFieldTwo('#SUM-C','#TOTAL_TERBILANG_');
                    showFieldTwo('#SUM-ALL','#TOTAL_TERBILANG_');
                }

				if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
                    $("input").attr("disabled", "disabled");
//                        $(id).attr("disabled", "disabled");
//                        $(id).attr("readonly", "readonly");
                }
            }
			
        });

        if (STATUS == '1') {
            $('#btn-save').hide();
        }

    });

    function showfield(id1, id2) {
        $(id1).each(function () {
            var id = $(this).attr('id');
            $('#' + id).focusin(function() {
                $(id2 + id).show();
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $(id2 + id).val(string_nominal);
                $('#' + id).keyup(function(){
                    var state_perolehan = $(this).val();
                    var convert_perolehan = state_perolehan.replace(/\./g, "");
                    var string_nominal = terbilang(convert_perolehan);
                    $(id2 + id).val(string_nominal);
                });
            });
            $('#' + id).focusout(function() {
                $(id2 + id).hide();
            });
        });
    }

    function showFieldTwo(id1, id2) {
        $(id1).each(function () {
            var id = $(this).attr('id');
            var state_perolehan = $.trim($(this).val());
            var convert_perolehan = state_perolehan.replace(/[^0-9]/g, "");
            var string_nominal = terbilang(convert_perolehan);
            $(id2 + id).text('( '+string_nominal+' )');
        });
    }

    function terbilang(bilangan) {

        bilangan    = String(bilangan);
        var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
        var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
        var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

        var panjang_bilangan = bilangan.length;

        /* pengujian panjang bilangan */
        if (panjang_bilangan == 1 && bilangan == 0) {
            kaLimat = "Nol Rupiah";
            return kaLimat;
        }
        if (panjang_bilangan > 15) {
            kaLimat = "Diluar Batas";
            return kaLimat;
        }

        /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
        for (i = 1; i <= panjang_bilangan; i++) {
            angka[i] = bilangan.substr(-(i),1);
        }

        i = 1;
        j = 0;
        kaLimat = "";


        /* mulai proses iterasi terhadap array angka */
        while (i <= panjang_bilangan) {

            subkaLimat = "";
            kata1 = "";
            kata2 = "";
            kata3 = "";

            /* untuk Ratusan */
            if (angka[i+2] != "0") {
                if (angka[i+2] == "1") {
                    kata1 = "Seratus";
                } else {
                    kata1 = kata[angka[i+2]] + " Ratus";
                }
            }

            /* untuk Puluhan atau Belasan */
            if (angka[i+1] != "0") {
                if (angka[i+1] == "1") {
                    if (angka[i] == "0") {
                        kata2 = "Sepuluh";
                    } else if (angka[i] == "1") {
                        kata2 = "Sebelas";
                    } else {
                        kata2 = kata[angka[i]] + " Belas";
                    }
                } else {
                    kata2 = kata[angka[i+1]] + " Puluh";
                }
            }

            /* untuk Satuan */
            if (angka[i] != "0") {
                if (angka[i+1] != "1") {
                    kata3 = kata[angka[i]];
                }
            }

            /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
            if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
                subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
            }

            /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
            kaLimat = subkaLimat + kaLimat;
            i = i + 3;
            j = j + 1;

        }

        /* mengganti Satu Ribu jadi Seribu jika diperlukan */
        if ((angka[5] == "0") && (angka[6] == "0")) {
            kaLimat = kaLimat.replace("Satu Ribu","Seribu");
        }

        return kaLimat + "Rupiah";
    }
    
</script>
<style>
    /*
    rollback
    input:disabled {
        background-color: #e8e8e8;
    }
    rollback
    */
</style>