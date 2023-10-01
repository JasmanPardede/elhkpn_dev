<style type="text/css">
    .list-value{
  padding: 0;
  margin: 0;
  font-size: 12px;
}

.list-value ul{
   margin: 0;
   padding: 0;
}

.list-value ul li{
  list-style-type: none;
    margin: 0;
   padding: 0;
}


.list-value ul li span:last-child{
  text-align: justify;
}

.list-value .btn{
  margin: 2px;
}

.table-child{
 border: none;
  background: none !important;

}

.table-child tbody tr td{
  border: none;
  padding: 1px;
}

.table-child tbody tr th{

}

.table-input td{
  margin: 0;
  padding: 0;
}

.table-child tbody tr td:nth-child(1){
   border: none;
   width: 100px !important;
}

.table-child tbody tr td:nth-child(2){
   border: none;
   width: 1px !important;
}

.table-child tbody tr td:nth-child(3){
  border: none;
  width: 170px !important;
  text-align: left;
}

.table-input > thead,
.table-filing > thead {
  background: -webkit-linear-gradient(#3c8dbc, #09C);
  background: -moz-linear-gradient(#3c8dbc, #09C);
  background: linear-gradient(#3c8dbc, #09C);
  color: #fff;
 /* background-color: #f0f0f0;*/
  font-weight: normal;
}

.table-input  > thead > tr > th{
  padding: 8px;
}

.table-input  > tbody > tr > td{
  margin: 0;
  padding: 3px 0px 3px 8px;
}

.table-input input{
  width: 80%;
  height: 100%;
  border: 1px solid #e7e7e7;
  padding: 4px;
  font-weight: normal;
  text-align: right;
}

.table-input-total input{
  width: 80%;
  height: 100%;
  border: none;
  padding: 4px;
  font-weight: normal;
  text-align: right;
}

.box-body .box{
  margin-top: 15px;
  box-shadow: none;
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
}

.sum-total{
  border-bottom: 1.5px solid #000;
}

.result-lhkpn table{
   font-weight: normal;
   font-size: 12px;
}

.table-regular > thead > tr > th{
   padding: 8px !important;
}

.dataTables_wrapper .btn,
.table-regular .btn{
   margin: 2px;
}

#sidebar:after{
    content:"";
    position: absolute;
    top: 0;
    right: 0;
    z-index: 999;
    border: 0.01px solid #e7e7e7;
    width: 0px;
    height: 400px;
    margin-right: -16px;
}

.modal-header{
  background-color: #818285;
  color: #fff;
}

.modal-header button{
  color: #fff;
  opacity: 1;
}

.dataTable thead tr th,
.dataTable thead tr td{
  text-align: center;
}

table.dataTable td, table.dataTable th {
  font-size: 12.5px !important;
}


.result-row{
/*  border: 1px solid red;*/
  width: 40%;
  text-align: right;
  padding-left: 10px;
}

.result-row2{
/*  border: 1px solid red;*/
  width: 10%;
  text-align: right;
  padding-left: 10px;
}

.old-val{
   background: #e7e7e7;
}

textarea {
    resize: vertical;
    
}

</style>
<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Penerimaan Tunai Setahun (Periode Januari s.d. Desember)"</h5>
</div>
<form role="form" id="FormPenerimaan">
    <div class="box-body">
        <div class="tabbable block-body" id="tab-data-penerimaan">
            <ul class="nav nav-tabs menu-filling tab-penerimaan">
                <li class="active"><a href="#tab-ax" id="tab-a" >A. Penerimaan Dari Pekerjaan</a></li>
                <li ><a href="#tab-bx" id="tab-b">B. Penerimaan dari Usaha dan Kekayaan</a></li>
                <li ><a href="#tab-cx" id="tab-c">C. Penerimaan Lainnya</a></li>
            </ul>
        </div>
        <div class="tab-content block-body">
            <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="<?php echo $new_id_lhkpn; ?>" />
            <div class="tab-pane fade in active" id="tab-ax">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th width="5%" rowspan="2">NO</th>
                            <th width="55%" rowspan="2">JENIS PENERIMAAN</th>
                            <th width="20%" colspan="2">MENURUT PN</th>
                            <th width="20%" colspan="2">HASIL KLARIFIKASI</th>
                        </tr>
                        <tr>
                            <th width="180px">PENYELENGGARA NEGARA</th>
                            <th width="180px">PASANGAN</th>
                            <th width="180px">PENYELENGGARA NEGARA</th>
                            <th width="180px">PASANGAN</th>
                        </tr>
                    </thead>
                    <tbody id="block-A">
                    </tbody>
                </table>
                <!---->
            </div>
            <div class="tab-pane fade in " id="tab-bx">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th width="5%" rowspan="2">NO</th>
                            <th width="55%" rowspan="2">JENIS PENERIMAAN</th>
                            <th width="40%" colspan="2">TOTAL NILAI PENERIMAAN KAS</th>
                        </tr>
                        <tr>
                          <th width="180px">MENURUT PN</th>
                          <th width="180px">HASIL KLARIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody id="block-B">
                    </tbody>
                </table>
                <!---->
            </div>
            <div class="tab-pane fade in " id="tab-cx">
                <table class="table table-bordered  table-input">
                    <thead>
                        <tr>
                            <th width="5%" rowspan="2">NO</th>
                            <th width="55%" rowspan="2">JENIS PENERIMAAN</th>
                            <th width="40%" colspan="2">TOTAL NILAI PENERIMAAN KAS</th>
                        </tr>
                        <tr>
                          <th width="180px">MENURUT PN</th>
                          <th width="180px">HASIL KLARIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody id="block-C">
                    </tbody>
                </table>
                <!---->
            </div>
        </div>

    </div>
    <div class="box-footer">
        <table class="table table-input-total">
            <tr>
                <td>TOTAL A PENERIMAAN DARI PEKERJAAN</td>
                <td width="360px">Rp.<input type="text" id="OLD-SUM-A" name="OLD-SUM-A" class="table-input-text result old-val" readonly="true"/></td>
                <td width="360px">Rp.<input type="text" id="SUM-A" name="SUM-A" class="table-input-text result" readonly="true"/></td>
            </tr>
            <tr>
                <td>TOTAL B PENERIMAAN DARI USAHA DAN KEKAYAAN</td>
                <td>Rp.<input type="text" id="OLD-SUM-B" name="OLD-SUM-B" class="table-input-text result old-val" readonly="true"/></td>
                <td>Rp.<input type="text" id="SUM-B" name="SUM-B" class="table-input-text result" readonly="true"/></td>
            </tr>
            <tr>
                <td>TOTAL C PENERIMAAN LAINNYA</td>
                <td>Rp.<input type="text" id="OLD-SUM-C" name="OLD-SUM-C" class="table-input-text result old-val" readonly="true"/></td>
                <td>Rp.<input type="text" id="SUM-C" name="SUM-C" class="table-input-text result" readonly="true"/></td>
            </tr>
            <tr>
                <td>TOTAL PENERIMAAN (A + B + C)</td>
                <td>Rp.<input type="text" id="OLD-SUM-ALL" name="OLD-SUM-ALL" class="table-input-text result old-val" readonly="true" /></td>
                <td>Rp.<input type="text" id="SUM-ALL" name="SUM-ALL" class="table-input-text result" readonly="true" /></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td align="right">
                    <button class="btn btn-sm btn-danger aksi-hide">
                        <i class="fa fa-save"></i> Simpan Penerimaan
                    </button>
                </td>
            </tr>

        </table>
    </div>
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>portal-assets/js/jquery.mask.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>portal-assets/js/jquery.maskMoney.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>portal-assets/js/numeral.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var ID_LHKPN = '<?php echo $new_id_lhkpn; ?>';
        var base_url = '<?php echo base_url(); ?>';
        var tab = new Array();
        tab[0] = '#tab-ax';
        tab[1] = '#tab-bx';
        tab[2] = '#tab-cx';

        $('#btnPrevPenerimaan').click(function (e) {
            var visible;
            for (i = 0; i < tab.length; i++) {
                if ($(tab[i]).is(":visible")) {
                    visible = i;
                    break;
                }
            }

            if (visible == 0) {
              e.preventDefault();
              $('.nav-tabs > .active').prev('li').find('a').trigger('click');
              $('#harta > .nav-tabs').find('a[href="#hutang"]').trigger('click');
          
            } else {
                var tab_view = tab[visible - 1].replace("x", "");
                $(tab_view).tab('show');
            }
        });

        $('#btnNextPenerimaan').click(function (e) {
            var visible;
            for (i = 0; i < tab.length; i++) {
                if ($(tab[i]).is(":visible")) {
                    visible = i;
                    break;
                }
            }

            if (visible == 2) {
                e.preventDefault();
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                $('#pengeluaran > .nav-tabs').find('a[href="#tab-axx"]').trigger('click');
            } else {
                var tab_view = tab[visible + 1].replace("x", "");
                $(tab_view).tab('show');
            }
        });

        var BLOCK_A = load_html('eaudit/klarifikasi/load_penerimaan/A/1/');
        $('#block-A').html(BLOCK_A);

        var BLOCK_B = load_html('eaudit/klarifikasi/load_penerimaan/B/2/');
        $('#block-B').html(BLOCK_B);

        var BLOCK_C = load_html('eaudit/klarifikasi/load_penerimaan/C/3/');
        $('#block-C').html(BLOCK_C);

        // $('.tab-penerimaan a').click(function (e) {
        //     e.preventDefault();
        //     $(this).tab('show');
        // });

        $(".input").maskMoney({allowZero: true, precision: 0, thousands: '.'});
        $('.input').on('propertychange change keyup paste input', function () {

            $('.input').maskMoney('unmasked')[0];
            var SUM_A = 0;
            var SUM_B = 0;
            var SUM_C = 0;
            var SUM_ALL = 0;
            $('#tab-ax .input').each(function () {
                var id = $(this).attr('id');
                var val = $('#' + id).val() || 0;
                var value = parseFloat(val.toString().replace(/\./g, ''));
                SUM_A += parseInt(value) || 0;
            });
            $('#tab-bx .input').each(function () {
                var id = $(this).attr('id');
                var val = $('#' + id).val() || 0;
                var value = parseFloat(val.toString().replace(/\./g, ''));
                SUM_B += parseInt(value) || 0;
            });
            $('#tab-cx .input').each(function () {
                var id = $(this).attr('id');
                var val = $('#' + id).val() || 0;
                var value = parseFloat(val.toString().replace(/\./g, ''));
                SUM_C += parseInt(value) || 0;
            });
            SUM_ALL = SUM_A + SUM_B + SUM_C || 0;
            $('#SUM-A').val(numeral(SUM_A).format('0,0').replace(/,/g, '.'));
            $('#SUM-B').val(numeral(SUM_B).format('0,0').replace(/,/g, '.'));
            $('#SUM-C').val(numeral(SUM_C).format('0,0').replace(/,/g, '.'));
            $('#SUM-ALL').val(numeral(SUM_ALL).format('0,0').replace(/,/g, '.'));
        });

        $('#FormPenerimaan').submit(function () {
            $('.input').maskMoney('unmasked')[0];
            $('.input').val() || 0;
            var formData = new FormData($('#FormPenerimaan')[0]);
            $.ajax({
                url: base_url + 'eaudit/klarifikasi/update_penerimaan',
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
            url: base_url + 'eaudit/klarifikasi/load_data_penerimaan/' + ID_LHKPN,
            async: false,
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var list_p = eval(data.list);
                    var total = eval(data.sum);
                    var total_old = eval(data.sum_old);
                    var total_a = total.SUM_A || 0;
                    var total_b = total.SUM_B || 0;
                    var total_c = total.SUM_C || 0;
                    var total_semua = total.SUM_ALL || 0;
                    var total_a_old = total_old.OLD_SUM_A || 0;
                    var total_b_old = total_old.OLD_SUM_B || 0;
                    var total_c_old = total_old.OLD_SUM_C || 0;
                    var total_semua_old = total_old.OLD_SUM_ALL || 0;
                    $('#SUM-A').val(numeral(total_a).format('0,0').replace(/,/g, '.'));
                    $('#SUM-B').val(numeral(total_b).format('0,0').replace(/,/g, '.'));
                    $('#SUM-C').val(numeral(total_c).format('0,0').replace(/,/g, '.'));
                    $('#SUM-ALL').val(numeral(total_semua).format('0,0').replace(/,/g, '.'));
                    $('#OLD-SUM-A').val(numeral(total_a_old).format('0,0').replace(/,/g, '.'));
                    $('#OLD-SUM-B').val(numeral(total_b_old).format('0,0').replace(/,/g, '.'));
                    $('#OLD-SUM-C').val(numeral(total_c_old).format('0,0').replace(/,/g, '.'));
                    $('#OLD-SUM-ALL').val(numeral(total_semua_old).format('0,0').replace(/,/g, '.'));
                    for (i = 0; i < list_p.length; i++) {
                        // console.log('#' + list_p[i].KODE_JENIS, '.' + list_p[i].KODE_JENIS);

                        var v_pn = list_p[i].PN == '' ? 0 :  list_p[i].PN;
                        var v_pasangan = list_p[i].PASANGAN == '' ? 0 : list_p[i].PASANGAN ;
                        var v_pn_old = list_p[i].PN_OLD == '' ? 0 :  list_p[i].PN_OLD;
                        var v_pasangan_old = list_p[i].PASANGAN_OLD == '' ? 0 :  list_p[i].PASANGAN_OLD;
                        var v_catatan = list_p[i].KET_PEMERIKSAAN == '' ? '' :  list_p[i].KET_PEMERIKSAAN;
                        if (list_p[i].GROUP_JENIS == 'A') {
                          $('#P' + list_p[i].KODE_JENIS).val(parseFloat(v_pasangan));
                          $('#P' + list_p[i].KODE_JENIS).trigger('mask.maskMoney');
                          $('.P' + list_p[i].KODE_JENIS).val(numeral(v_pasangan_old).format('0,0').replace(/,/g, '.'));
                          $('#KET_PEMERIKSAAN_' + list_p[i].KODE_JENIS).val(v_catatan);
                        }
                        $('#' + list_p[i].KODE_JENIS).val(parseFloat(v_pn));
                        $('#' + list_p[i].KODE_JENIS).trigger('mask.maskMoney');
                        $('.' + list_p[i].KODE_JENIS).val(numeral(v_pn_old).format('0,0').replace(/,/g, '.'));
                        $('#KET_PEMERIKSAAN_' + list_p[i].KODE_JENIS).val(v_catatan);

                        // console.log('#' + list_p[i].KODE_JENIS + ' - ' + parseFloat(v_pn), '.' + list_p[i].KODE_JENIS +' - '+v_pn_old);
                    }
                }
            }
        });


    });



</script>
