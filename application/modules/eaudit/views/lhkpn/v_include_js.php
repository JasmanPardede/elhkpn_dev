<!--<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/filing/filingMain.js?v=1.0"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/filing/filingHelper.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/filing/filing.js"></script>-->

<script type="text/javascript">
    function load_html(url, data) {
        var result;
        $.ajax({
            url: base_url + '' + url,
            type: 'html',
            async: false,
            method: 'POST',
            data: {
                "data": data
            },
            error: function (jqXHR, exception) {
                ajax_error_xhr(jqXHR, exception);
            },
            success: function (data) {
                result = data;
            },
        });
        return result;
    }

    function AsalUsulValidation() {
        var pilih_asal_usul = $("[name='ASAL_USUL[]']:checked").length;
        if (pilih_asal_usul >= 1) {
            $('.notif-asal').hide();
            $('#button-saved').prop('disabled', false);
        } else {
            $('.notif-asal').show();
            $('#button-saved').prop('disabled', true);
        }
    }

    function CustomValidation() {
        var NEGARA = $('#NEGARA').val();
        if (NEGARA == '1') {
            var PROV = $('#PROV').val();
            var KAB_KOT = $('#KAB_KOT').val();
            if (PROV == '') {
                $('.notif-prov').show();
                $('.form-prov').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-prov').hide();
                $('.form-prov').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
            if (KAB_KOT == '') {
                $('.notif-kota').show();
                $('.form-kota').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-kota').hide();
                $('.form-kota').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        } else {
            var ID_NEGARA = $('#ID_NEGARA').val();
            if (ID_NEGARA == '') {
                $('.notif-negara').show();
                $('.form-negara').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-negara').hide();
                $('.form-negara').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        }

        if ($("#ATAS_NAMA").val() != '3') {
            $('.notif-ket-lainnya').hide();
            $('.ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
        } else {
            $('.notif-ket-lainnya').show();
            $('.ket_lainnya_an_div').removeClass('has-success').addClass('has-error');
        }

        AsalUsulValidation();
    }

    var show_atas_nama_lainnya = function () {
        $('#ket_lainnya_an_div').show();
        $("#ATAS_NAMA_LAINNYA").attr("required");
    }
    
    var hide_atas_nama_lainnya = function () {
        $('#ket_lainnya_an_div').hide();
        $('.notif-ket-lainnya').hide();
        $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
    }

    $(document).ready(function () {

        if ($('#ket_lainnya_an_div').length > 0) {
            $('#ket_lainnya_an_div').hide();
        }
        if ($('#ATAS_NAMA').length > 0) {
            $("#ATAS_NAMA").change(function () {
                $("#ATAS_NAMA_LAINNYA").val('');
//            var isKeteranganLainnyaExists = document.getElementById("ATAS_NAMA_LAINNYA");
                $("#ATAS_NAMA_LAINNYA").removeAttr("required");
                if ($("#ATAS_NAMA").val() == '3') {
                    show_atas_nama_lainnya();
//                $('#FormHarta').bootstrapValidator('addField', isKeteranganLainnyaExists);
                } else {
                    hide_atas_nama_lainnya();
//                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
                }
            });

            if ($("#ATAS_NAMA").val() == '3') {
                show_atas_nama_lainnya();
            } else {
                hide_atas_nama_lainnya();
            }
        }
        
        $('.money').mask('000.000.000.000.000.000', {reverse: true});
//
//        $('* .frmt_duit').each(function () {
//            var item = $(this).val();
//            var num = Number(item).toLocaleString('id');
//
//            $(this).val(num);
//        });
    });
</script>