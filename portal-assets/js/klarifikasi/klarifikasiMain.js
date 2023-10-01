var mainFiling = function(){
    var current = readCookie('current-lhkpn');
    if (isDefined(current) && current) {
        View(current);
    } else {
        View(1);
    }
    
    $('#btn-next').click(function () {
        var current = $('#current').val();
        var next = parseInt(current) + 1;
        View(next);
    });

    $('#btn-prev').click(function () {
        var current = $('#current').val();
        var prev = parseInt(current) - 1;
        View(prev);
    });
    
    $('.money').mask("#.##0,00", {reverse: true, maxlength: false});
    $('.luas').mask("#.##0,00", {reverse: true});

    $('.tgl').datetimepicker({
        format: "DD/MM/YYYY",
        maxDate: 'now'
    });
};

var skip_to_review_harta = function(){
    var elem = $("#skip_to_review_harta");
    
    if(isDefined(elem) && $(elem).val() == 'ok_do_it'){
        $(elem).remove();
        View(9, 'REVIEW HARTA');
    }
};

$(document).ready(function () {
    
    mainFiling();
    
    skip_to_review_harta();

    /* SURAT_KUASA(1);*/

    $('#Modal_Ikthisar input[name=hasil_ikthisar]').click(function () {
        if ($(this).is(":checked")) {
            $('#Modal_Ikthisar #btn-final-next').show();
        }
    });

    $('#ModalSuratKuasa input[name=hasil_surat_kuasa3]').click(function () {
        if ($(this).is(":checked")) {
            $('#ModalSuratKuasa #btn-final-next').show();
            if ($(this).val() == '0') {
                $('#ModalSuratKuasa .peringatan').fadeIn('slow');
            } else {
                $('#ModalSuratKuasa .peringatan').fadeOut('slow');
            }
        }
    });

    $('#ModalKuasaKeluarga input[name=hasil_surat_kuasa4]').click(function () {
        if ($(this).is(":checked")) {
            $('#ModalKuasaKeluarga #btn-final-next').show();
            if ($(this).val() == '0') {
                $('#ModalSuratKuasa .peringatan').fadeIn('slow');
            } else {
                $('#ModalSuratKuasa .peringatan').fadeOut('slow');
            }
        }
    });

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

    $('.input_capital').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

    $('.action').css({
        'cursor': 'pointer'
    });

    $(".action i").hover(function () {
        $(this).css("color", "rgb(60, 141, 188)");
    }, function () {
        $(this).css("color", "rgb(51, 51, 51)");
    });

    $('#FORM_PELEPASAN').bootstrapValidator().on('success.form.bv', function (e) {
        e.preventDefault();
        $('#FORM_PELEPASAN #NILAI_PELEPASAN').maskMoney('unmasked')[0];
        var TABLE = $('#FORM_PELEPASAN #TABLE_GRID').val();
        var TEXT = $('#FORM_PELEPASAN #NOTIF').val();
        do_submit('#FORM_PELEPASAN', 'portal/filing/pelepasan', TEXT, '#ModalPelpasan');
        $(TABLE).DataTable().ajax.reload();
    });
    
    
});