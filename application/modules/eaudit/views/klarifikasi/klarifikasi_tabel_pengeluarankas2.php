<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Pengeluaran Tunai Setahun (Periode Januari s.d. Desember)"</h5>
</div>
<form role="form" id="FormPengeluaran">
    <input type="hidden" name="ID_LHKPN2" id="ID_LHKPN2" value="<?php echo $new_id_lhkpn; ?>" />
    <div class="box-body">
        <div class="tabbable block-body" id="tab-data-pengeluaran">
            <ul class="nav nav-tabs menu-filling tab-pengeluaran">
                <li class="active"><a href="#tab-axx" id="tab-aa" >A. Pengeluaran Rutin</a></li>
                <li ><a href="#tab-bxx" id="tab-bb" >B. Pengeluaran Harta</a></li>
                <li ><a href="#tab-cxx" id="tab-cc" >C. Pengeluaran Lainnya</a></li>
            </ul>
        </div>
        <div class="tab-content block-body">
            <div class="tab-pane fade in active" id="tab-axx">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th width="5%">NO</th>
                            <th width="65%">JENIS PENGELUARAN</th>
                            <th width="200px">TOTAL PENGELUARAN</th>
                            <th width="200px">NILAI KLARIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody id="block-AA">
                    </tbody>    
                </table>
            </div>
            <div class="tab-pane fade in" id="tab-bxx">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th width="5%">NO</th>
                            <th width="65%">JENIS PENGELUARAN</th>
                            <th width="200px">TOTAL PENGELUARAN</th>
                            <th width="200px">NILAI KLARIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody id="block-BB">
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade in " id="tab-cxx">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th width="5%">NO</th>
                            <th width="65%">JENIS PENGELUARAN</th>
                            <th width="200px">TOTAL PENGELUARAN</th>
                            <th width="200px">NILAI KLARIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody id="block-CC">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <table class="table table-input-total">
            <tr>
                <td width="70%" colspan="2">TOTAL A PENGELUARAN RUTIN</td>
                <td width="200px">Rp.<input type="text" id="OLD-PSUM-A" name="OLD-PSUM-A" class="table-input-text result old-val" readonly="true"/></td>
                <td width="200px">Rp.<input type="text" id="PSUM-A" name="PSUM-A" class="table-input-text result" readonly="true"/></td>
            </tr>	
            <tr>
                <td width="70%" colspan="2">TOTAL B PENGELUARAN HARTA</td>
                <td>Rp.<input type="text" id="OLD-PSUM-B" name="OLD-PSUM-B" class="table-input-text result old-val" readonly="true"/></td>
                <td>Rp.<input type="text" id="PSUM-B" name="PSUM-B" class="table-input-text result" readonly="true"/></td>
            </tr>	
            <tr>
                <td width="70%" colspan="2">TOTAL C PENGELUARAN LAINNYA</td>
                <td>Rp.<input type="text" id="OLD-PSUM-C" name="OLD-PSUM-C" class="table-input-text result old-val" readonly="true"/></td>
                <td>Rp.<input type="text" id="PSUM-C" name="PSUM-C" class="table-input-text result" readonly="true"/></td>
            </tr>	
            <tr>
                <td width="70%" colspan="2">TOTAL PENGELUARAN (A + B + C)</td>
                <td>Rp.<input type="text" id="OLD-PSUM-ALL" name="OLD-PSUM-ALL" class="table-input-text result old-val" readonly="true" /></td>
                <td>Rp.<input type="text" id="PSUM-ALL" name="PSUM-ALL" class="table-input-text result" readonly="true" /></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">
                    <button class="btn btn-sm btn-danger aksi-hide" id="btn-save" >
                        <i class="fa fa-save"></i> Simpan Pengeluaran
                    </button>
                </td>
            </tr>	
        </table>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        var base_urls = "<?php echo base_url(); ?>"
        var ID_LHKPN2 = "<?php echo $new_id_lhkpn; ?>"
        var tabx = new Array();
        tabx[0] = '#tab-axx';
        tabx[1] = '#tab-bxx';
        tabx[2] = '#tab-cxx';

        $('#btnPrevPengeluaran').click(function (e) {
            var visible;
            for (i = 0; i < tabx.length; i++) {
                if ($(tabx[i]).is(":visible")) {
                    visible = i;
                    break;
                }
            }

            if (visible == 0) {
              e.preventDefault();
              $('.nav-tabs > .active').prev('li').find('a').trigger('click');
              $('#penerimaan > .nav-tabs').find('a[href="#tab-cx"]').trigger('click');
          
            } else {
                var tab_view = tabx[visible - 1].replace("x", "");
                switch(visible){
                    case 1 : tab_view = "#tab-aa"; break;
                    case 2 : tab_view = "#tab-bb"; break;
                }
                $(tab_view).tab('show');
            }
        });

        $('#btnNextPengeluaran').click(function (e) {
            var visible;
            for (i = 0; i < tabx.length; i++) {
                if ($(tabx[i]).is(":visible")) {
                    visible = i;
                    break;
                }
            }

            if (visible == 2) {
                e.preventDefault();
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
            } else { 
                var tab_view = tabx[visible + 1].replace("xx", "");
                switch(visible){
                    case 0 : tab_view = "#tab-bb"; break;
                    case 1 : tab_view = "#tab-cc"; break;
                }
                $(tab_view).tab('show');
            }
        });

        var BLOCK_AA = load_html('eaudit/klarifikasi/load_pengeluaran/A/1');
        $('#block-AA').html(BLOCK_AA);

        var BLOCK_BB = load_html('eaudit/klarifikasi/load_pengeluaran/B/2');
        $('#block-BB').html(BLOCK_BB);

        var BLOCK_CC = load_html('eaudit/klarifikasi/load_pengeluaran/C/3');
        $('#block-CC').html(BLOCK_CC);

        // $('.tab-pengeluaran a').click(function(e) {
        //     e.preventDefault();
        //     $(this).tab('show');
        // });

        $(".input2").maskMoney({allowZero: true, precision: 0, thousands: '.'});
        $('.input2').on('propertychange change keyup paste input2', function() {


            $('.input2').maskMoney('unmasked')[0];
            var PSUM_A = 0;
            var PSUM_B = 0;
            var PSUM_C = 0;
            var PSUM_ALL = 0;
            $('#tab-axx .input2').each(function() {
                var idx = $(this).attr('id');
                var valx = $('#' + idx).val() || 0;
                var valuex = parseFloat(valx.replace(/\./g, ''));
                PSUM_A += parseInt(valuex) || 0;
            });
            $('#tab-bxx .input2').each(function() {
                var idx = $(this).attr('id');
                var valx = $('#' + idx).val() || 0;
                var valuex = parseFloat(valx.replace(/\./g, ''));
                PSUM_B += parseInt(valuex) || 0;
            });
            $('#tab-cxx .input2').each(function() {
                var idx = $(this).attr('id');
                var valx = $('#' + idx).val() || 0;
                var valuex = parseFloat(valx.replace(/\./g, ''));
                PSUM_C += parseInt(valuex) || 0;
            });
            PSUM_ALL = PSUM_A + PSUM_B + PSUM_C || 0;
            $('#PSUM-A').val(numeral(PSUM_A).format('0,0').replace(/,/g, '.'));
            $('#PSUM-B').val(numeral(PSUM_B).format('0,0').replace(/,/g, '.'));
            $('#PSUM-C').val(numeral(PSUM_C).format('0,0').replace(/,/g, '.'));
            $('#PSUM-ALL').val(numeral(PSUM_ALL).format('0,0').replace(/,/g, '.'));
        });

        $('#FormPengeluaran').submit(function() {
            $('.input2').maskMoney('unmasked')[0];
            $('.input2').val() || 0;
            var formData = new FormData($('#FormPengeluaran')[0]);
            $.ajax({
                url: base_urls + 'eaudit/klarifikasi/update_pengeluaran',
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data == 1) {
                        alertify.success('Data Berhasil Disimpan');
                    }
                    else{
                        alertify.error('Data Gagal Disimpan');
                    }
                }
            });
            return false;
        });

        $.ajax({
            url: base_urls + 'eaudit/klarifikasi/load_data_pengeluaran/' + ID_LHKPN2,
            async: false,
            dataType: 'JSON',
            success: function(data) {
                if (data) {
                    var list = eval(data.list);
                    var total = eval(data.sum);
                    var total_old = eval(data.sum_old);
                    var total_a = total.PSUM_A || 0;
                    var total_b = total.PSUM_B || 0;
                    var total_c = total.PSUM_C || 0;
                    var total_semua = total.PSUM_ALL || 0;
                    var total_a_old = total_old.OLD_PSUM_A || 0;
                    var total_b_old = total_old.OLD_PSUM_B || 0;
                    var total_c_old = total_old.OLD_PSUM_C || 0;
                    var total_semua_old = total_old.OLD_PSUM_ALL || 0;
                    for (i = 0; i < list.length; i++) {
                        var v_pn = list[i].JML || 0;
                        var v_pn_old = list[i].JML_OLD || 0;
                        var v_catatan = list[i].KET_PEMERIKSAAN == '' ? '' :  list[i].KET_PEMERIKSAAN;
                        $('#P_' + list[i].KODE_JENIS).val(parseFloat(v_pn));
                        $('#P_' + list[i].KODE_JENIS).trigger('mask.maskMoney');
                        $('.OLD_P_' + list[i].KODE_JENIS).val(numeral(v_pn_old).format('0,0').replace(/,/g, '.'));
                        $('#KET_PEMERIKSAAN2_' + list[i].KODE_JENIS).val(v_catatan);
                    }
                    $('#OLD-PSUM-A').val(numeral(total_a_old).format('0,0').replace(/,/g, '.'));
                    $('#OLD-PSUM-B').val(numeral(total_b_old).format('0,0').replace(/,/g, '.'));
                    $('#OLD-PSUM-C').val(numeral(total_c_old).format('0,0').replace(/,/g, '.'));
                    $('#OLD-PSUM-ALL').val(numeral(total_semua_old).format('0,0').replace(/,/g, '.'));
                    $('#PSUM-A').val(numeral(total_a).format('0,0').replace(/,/g, '.'));
                    $('#PSUM-B').val(numeral(total_b).format('0,0').replace(/,/g, '.'));
                    $('#PSUM-C').val(numeral(total_c).format('0,0').replace(/,/g, '.'));
                    $('#PSUM-ALL').val(numeral(total_semua).format('0,0').replace(/,/g, '.'));
                }
            }
			
        });
    });
</script>