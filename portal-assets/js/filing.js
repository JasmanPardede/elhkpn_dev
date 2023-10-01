/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Opera 8.0+
var OPERA = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
// Firefox 1.0+
var FIREFOX = typeof InstallTrigger !== 'undefined';
// At least Safari 3+: "[object HTMLElementConstructor]"
var SAFARI = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
// Internet Explorer 6-11
var IE = /*@cc_on!@*/false || !!document.documentMode;
// Edge 20+
var EDGE = !IE && !!window.StyleMedia;
// Chrome 1+
var CHROME = !!window.chrome && !!window.chrome.webstore;
// Blink engine detection
var BLINK = (CHROME || OPERA) && !!window.CSS;

var TIMEOUT_BROWSER = 0;

if (OPERA) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (FIREFOX) {
    TIMEOUT_BROWSER = 10 / 100;
} else if (SAFARI) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (IE) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (EDGE) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (CHROME) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (BLINK) {
    TIMEOUT_BROWSER = 50 / 100;
} else {
    TIMEOUT_BROWSER = 50 / 100;
}

//fix modal force focus
$.fn.modal.Constructor.prototype.enforceFocus = function () {
    var that = this;
    $(document).on('focusin.modal', function (e) {
        if ($(e.target).hasClass('select2-input')) {
            return true;
        }
        if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
            that.$element.focus();
        }
    });
};


$(document).ready(function () {

    /* 
     SURAT_KUASA(1);*/



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

    var current = readCookie('current-lhkpn');
    if (current) {
        View(current);
    } else {
        View(1);
    }

    $('.input_capital').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

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

    $('.action').css({
        'cursor': 'pointer'
    });

    $(".action i").hover(function () {
        $(this).css("color", "rgb(60, 141, 188)");
    }, function () {
        $(this).css("color", "rgb(51, 51, 51)");
    });

    $('.money').mask("#.##0,00", {reverse: true, maxlength: false});
    $('.luas').mask("#.##0,00", {reverse: true});

    $('.tgl').datetimepicker({
        format: "DD/MM/YYYY",
        maxDate: 'now'
    });

    $('#FORM_PELEPASAN').bootstrapValidator().on('success.form.bv', function (e) {
        e.preventDefault();
        $('#FORM_PELEPASAN #NILAI_PELEPASAN').maskMoney('unmasked')[0];
        var TABLE = $('#FORM_PELEPASAN #TABLE_GRID').val();
        var TEXT = $('#FORM_PELEPASAN #NOTIF').val();
        do_submit('#FORM_PELEPASAN', 'portal/filing/pelepasan', TEXT, '#ModalPelpasan');
        $(TABLE).DataTable().ajax.reload();
    });

    /*$('.title-box').sticky({topSpacing:190});
     $('.title-box').css({
     'background-color':'blue',
     });*/
    //$('.action-panel').sticky({topSpacing:120});
});

/**
 * 
 * @requires requireDescription plugin AdminLTE-2.1.1 app.min.js
 * 
 * @param string url
 * @param string|object method if object then will be as ajax configuration
 * @param object data
 * @param function __on_complete_callback
 * @returns {do_edit.result}
 */

function do_whatever_ajax_can_do(url, method, data, __on_success_callback, __on_complete_callback, __on_error_callback) {
    var result;
    var ajaxTime = new Date().getTime();

    if (!isDefined(method)) {
        method = "POST";
    }

    var ajaxConfigDefault = {
        url: base_url + '' + url,
        async: false,
        dataType: 'JSON',
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            // Loading('hide');
        },
        success: function (data) {
            result = eval(data);
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    }

    $.ajax(ajaxConfigDefault);
    return result;
}

function do_edit(url) {
    var result;
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + '' + url,
        async: false,
        dataType: 'JSON',
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            // Loading('hide');
        },
        success: function (data) {
            result = eval(data);
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
    return result;
}

function do_delete(url, text) {
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + '' + url,
        async: false,
        dataType: 'html',
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        success: function (data) {
            success(text);
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
}

function do_submit(form, url, text, modal) {
    if (modal) {
        $(modal).modal('hide');
    }
    var ajaxTime = new Date().getTime();
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url + '' + url,
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'html',
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            // Loading('hide');
        },
        success: function (data) {
            if (data == 1) {
                success(text);
            } else {
                notif('Silahkan refresh halaman ini atau login ulang.');
            }
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
}

function ajax_error_xhr(jqXHR, exception, at) {
    Loading('hide');

    if (!isDefined(at)) {
        at = ' ';
    }
    if (jqXHR.status === 0) {
        notif('Mohon Maaf, Koneksi Terputus !!', at);
    } else if (jqXHR.status == 404) {
        notif('Mohon Maaf, Halaman tidak tersedia !!', at);
    } else if (jqXHR.status == 500) {
        notif('Silahkan refresh halaman ini atau login ulang.', at);
    } else if (exception === 'parsererror') {
        notif('Silahkan refresh halaman ini atau login ulang.', at);
    } else if (exception === 'timeout') {
        notif('Mohon Maaf, Koneksi Terputus !!', at);
    } else if (exception === 'abort') {
        notif('Mohon Maaf, Koneksi Terputus !!', at);
    } else {
        notif('Silahkan refresh halaman ini atau login ulang.', at);
    }
}

function do_upload(url, name) {
    var formData = new FormData();
    if (name == 'file2[]') {
        //formData.append(name, document.getElementById('file2').files[0]);
        var ins = document.getElementById('file2').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("file2[]", document.getElementById('file2').files[x]);
        }
    } else {
        formData.append(name, $('input[type=file]')[0].files[0]);
    }

    $.ajax({
        url: base_url + '' + url,
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
}


function dateConvert(val) {
    var res = val.split("-");
    return res[2] + '/' + res[1] + '/' + res[0];
}

function CallForm(name) {
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + 'portal/filing/form/' + name,
        type: 'html',
        async: false,
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        error: function (jqXHR, exception) {
            Loading('hide');
            notif('Silahkan refresh halaman ini atau login ulang.');
        },
        success: function (data) {
            $('#form-content').empty();
            $('#form-content').html(data);
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
    });
}

function load_html(url) {
    var result;
    $.ajax({
        url: base_url + '' + url,
        type: 'html',
        async: false,
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {
            result = data;
        },
    });
    return result;
}

function pindah_tab(name, grid) {
    $(name).tab('show');
    if (grid) {
        Grid(grid);
    }

}


function to_target(target) {
    $(target).tab('show');
}


function Grid(name) {

    $.ajax({
        url: base_url + 'portal/filing/grid/' + name + '/' + ID_LHKPN,
        type: 'html',
        async: false,
        beforeSend: function () {
            //Loading('show');
            $('.grid_wrapper').html('<b>Sedang memuat data...</b>');
        },
        complete: function () {
            //Loading('hide');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
            $('.grid_wrapper').empty();
        },
        success: function (data) {

            if ($('.grid_' + name).length > 0) {
                $('.grid_' + name).empty();
            } else {
                $('.grid_wrapper').empty();
            }
            $('.grid_' + name).html(data);
            $('table .btn').addClass('btn-sm');
            // SETTING HARTA
            var IS_LOAD = $('#IS_LOAD').val();
            var INDEX = $('#INDEX').val();
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();

            if (STATUS == '0' || STATUS == '2') { // STATUS DRAFT
                if (IS_COPY == '0') { // LAPORAN PERTAMA
                    $('#add-' + INDEX).show();
                    $('#load-' + INDEX).hide();
                } else { // LAPORAN KEDUA,KETIGA dst...
                    if (IS_LOAD == '0') { // BELUM DI LOAD
                        $('#add-' + INDEX).hide();
                        $('#load-' + INDEX).show();
                    } else {
                        $('#add-' + INDEX).show();
                        $('#load-' + INDEX).hide();
                    }
                }
            } else { // STATUS TERKIRIM
                $('#add-' + INDEX).hide();
                $('#load-' + INDEX).hide();
            }


            if (JUMLAH_DATA == '0' && IS_LOAD == '0') {
                $('#add-' + INDEX).show();
                $('#load-' + INDEX).hide();
                $('#load-all-data-sebelumnya').hide();
            }



            /* if(INDEX=='7'){
             $('#add-'+INDEX).show();
             $('#load-'+INDEX).hide();
             }*/


        },
    });
}

function SELESAI_LHKPN(ID_LHKPN) {
    window.location.href = base_url + 'portal/review_harta/kirim_lhkpn/' + ID_LHKPN;
}

function RESET_TOKEN(ID_LHKPN) {
    $('#modal_token #token_reset').fadeOut('slow');
    var nomor_token = $('#nomor_token').val();
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + 'portal/review_harta/create_token/' + ID_LHKPN,
        beforeSend: function () {
            Loading('show');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {
            var totalTime = new Date().getTime() - ajaxTime;
            if (data == '1') {
                $('#modal_token #token_salah').hide();
                $('#modal_token #token_reset').show();
                $('#modal_token #nomor_token').val('');
            } else {
                notif('Silahkan refresh halaman ini atau login ulang.');
            }
            stf(totalTime);
        }
    });
}

function CHECK_TOKEN(ID_LHKPN) {
    $('#modal_token #token_salah').fadeOut('slow');
    var nomor_token = $('#nomor_token').val();
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + 'portal/review_harta/check_token',
        type: 'POST',
        data: {'ID_LHKPN': ID_LHKPN, 'nomor_token': nomor_token},
        beforeSend: function () {
            Loading('show');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {
            var totalTime = new Date().getTime() - ajaxTime;
            if (data == '1') {
                window.location.href = base_url + 'portal/review_harta/kirim_lhkpn/' + ID_LHKPN;
            } else {
                $('#modal_token #token_salah').show();
                $('#modal_token #token_reset').hide();
                $('#modal_token #nomor_token').val('');
            }
            stf(totalTime);
        }
    });
}

function SHOW_TOKEN(ID_LHKPN) {

    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + 'portal/review_harta/create_token/' + ID_LHKPN + '/',
        type: 'html',
        async: false,
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {
            $('#modal_token #nomor_token').val('');
            if (data == '1') {
                $('#modal_token').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            } else {
                notif('Silahkan refresh halaman ini atau login ulang.');
            }
            Loading('hide');
        }
    });

}

function TOKEN(ID_LHKPN, index) {
    if (index) {
        PRINT_KELUARGA(parseInt(index));
    }
    $('#ModalSuratKuasa').modal('hide').data('bs.modal', null);
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('#ModalKuasaKeluarga,#ModalSuratKuasa').removeClass('in');
    $('#ModalKuasaKeluarga,#ModalSuratKuasa').css({'display': 'none'});
    var option = $('input[name=hasil_surat_kuasa4]:checked').val();
    if (option == '0') {
        $('#modal_lagi #btn-final-next')[0].setAttribute('onclick', 'SHOW_TOKEN(' + ID_LHKPN + ')');
        $('#modal_lagi').modal('show');
    } else {
        SHOW_TOKEN(ID_LHKPN);
    }

}


function KUASA_KELUARGA(index, modal) {
    var option = $('input[name=hasil_surat_kuasa3]:checked').val();
    var LINK = base_url + 'portal/review_harta/surat_kuasa_pdf/' + ID_LHKPN + '/' + option;
    window.open(LINK, '_blank');
    $('#ModalSuratKuasa').modal('hide').data('bs.modal', null);
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('#ModalSuratKuasa').removeClass('in');
    $('#ModalSuratKuasa').css({'display': 'none'});
    /*if (option == '0') {
     $('#modal_lagi #btn-final-next')[0].setAttribute('onclick', 'SHOW_KELUARGA(' + index + ',"' + modal + '",' + option + ')');
     $('#modal_lagi').modal('show');
     } else {
     SHOW_KELUARGA(index, modal, option);
     }*/
    SHOW_KELUARGA(index, modal, option);
}

function KUASA_KELUARGA2(index, modal) {
    PRINT_KELUARGA(parseInt(index - 1));
    var option = $('input[name=hasil_surat_kuasa4]:checked').val();
    $('#ModalKuasaKeluarga').modal('hide').data('bs.modal', null);
    if (modal) {
        $('#ModalKuasaKeluarga').modal('hide').data('bs.modal', null);
    }
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('#ModalKuasaKeluarga').removeClass('in');
    $('#ModalKuasaKeluarga').css({'display': 'none'});
    /*if (option == '0') {
     $('#modal_lagi #btn-final-next')[0].setAttribute('onclick', 'SHOW_KELUARGA(' + index + ',"' + modal + '",' + option + ')');
     $('#modal_lagi').modal('show');
     } else {
     SHOW_KELUARGA(index, modal, option);
     }*/
    SHOW_KELUARGA(index, modal, option);
}


function SHOW_KELUARGA(index, modal, option) {
    $("#ModalKuasaKeluarga input[name=hasil_surat_kuasa4]").prop('checked', false);
    $("#ModalKuasaKeluarga #btn-final-next").hide();
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + 'portal/review_harta/data_keluarga/' + ID_LHKPN + '/' + index + '/' + option,
        type: 'json',
        async: false,
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {

            if (data) {
                var rs = eval(data);

                var UMUR = parseInt(rs.UMUR);
                var ID = rs.ID;
                var STATUS_CETAK = rs.STATUS_CETAK_SURAT_KUASA || 0;
                var LIMA_TAHUN = rs.LIMA_TAHUN;
                $('#ModalKuasaKeluarga .modal-footer').css({
                    'padding': '10px'
                });
                $('#ModalKuasaKeluarga .modal-dialog').css({
                    'width': '95%',
                });
                $('#ModalKuasaKeluarga .modal-dialog p').css({
                    'text-align': 'justify',
                    'font-size': '13px',
                    'line-height': '1.5',
                    'margin': '0 0 10px'
                });
                $('#ModalKuasaKeluarga .modal-dialog ol').css({
                    'text-align': 'justify',
                    'font-size': '13px',
                    'line-height': '1.5',
                    'color': '#000',
                    'margin': '0',
                });
                if (rs.LAST == '1') {
                    $('#ModalKuasaKeluarga #btn-final-next')[0].setAttribute('onclick', 'TOKEN(' + ID_LHKPN + ',' + index + ')');
                    //$('#ModalKuasaKeluarga #btn-final-isi')[0].setAttribute('onclick', 'SELESAI_LHKPN(' + ID_LHKPN + ')');
                } else {
                    $('#ModalKuasaKeluarga #btn-final-next')[0].setAttribute('onclick', rs.NEXT);
                    //$('#ModalKuasaKeluarga #btn-final-isi')[0].setAttribute('onclick', rs.NEXT);
                }


                if (IS_COPY == '0') {
                    /* alert(UMUR+' '+STATUS_CETAK);*/
                    if (UMUR >= 17 && STATUS_CETAK == '0') {
                        $('#ModalKuasaKeluarga #btn-print').show();
                        $('#ModalKuasaKeluarga #btn-print')[0].setAttribute('onclick', 'PRINT_KELUARGA(' + index + ')');
                    } else {
                        $('#ModalKuasaKeluarga #btn-print').hide();
                    }
                } else {
                    if (UMUR >= 17 && STATUS_CETAK == '0' && LIMA_TAHUN == '1') {
                        $('#ModalKuasaKeluarga #btn-print').show();
                        $('#ModalKuasaKeluarga #btn-print')[0].setAttribute('onclick', 'PRINT_KELUARGA(' + index + ')');
                    } else {
                        $('#ModalKuasaKeluarga #btn-print').hide();
                    }

                }

                $('#KELUARGA_NAMA,#KELUARGA_TTD').text(rs.NAMA || "");
                $('#KELUARGA_TTL').text(rs.TTL || "");
                $('#KELUARGA_KTP').text(rs.NOMOR_KTP || "");
                $('#KELUARGA_ALAMAT').text(rs.ALAMAT || "");
                $('#ModalKuasaKeluarga').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            } else {
                TOKEN(ID_LHKPN);
            }

            Loading('hide');

        }
    });


}



function PRINT_KELUARGA(index) {
    var option = $('input[name=hasil_surat_kuasa4]:checked').val();
    var LINK = base_url + 'portal/review_harta/surat_kuasa_pdf2/' + ID_LHKPN + '/' + index + '/' + option;
    window.open(LINK, '_blank');
    $('#ModalKuasaKeluarga #btn-print').hide();
}

function SURAT_KUASA() {
    var ajaxTime = new Date().getTime();
    var option = $('input[name=hasil_ikthisar]:checked').val();
    $("#ModalSuratKuasa input[name=hasil_surat_kuasa3]").prop('checked', false);
    $.ajax({
        url: base_url + 'portal/review_harta/data_pribadi/' + ID_LHKPN + '/' + option,
        type: 'json',
        async: false,
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {
            if (data) {
                var rs = eval(data);
                $('#KUASA_NAMA,#KUASA_TTD').text(rs.NAMA_LENGKAP || "");
                $('#KUASA_TTL').text(rs.TEMPAT_LAHIR + ' - ' + dateConvert(rs.TANGGAL_LAHIR) || "");
                $('#KUASA_KTP').text(rs.NIK || "");



                if (rs.NEGARA == '2') {
                    $('#KUASA_ALAMAT').text(rs.ALAMAT_NEGARA || "");
                } else {
                    $('#KUASA_ALAMAT').text(rs.ALAMAT_RUMAH + ' , ' + rs.KELURAHAN + ' , ' + rs.KECAMATAN + ' , ' + rs.KABKOT + ' , ' + rs.PROVINSI);
                }


                $('#ModalSuratKuasa .modal-footer').css({
                    'padding': '10px'
                });
                $('#ModalSuratKuasa .modal-dialog').css({
                    'margin-top': '5px',
                    'width': '95%',
                });
                $('#ModalSuratKuasa .modal-dialog p').css({
                    'text-align': 'justify',
                    'font-size': '13px',
                    'line-height': '1.5',
                    'margin': '0 0 10px'
                });
                $('#ModalSuratKuasa').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
                //window.open(base_url + 'portal/ikthisar/priview_cetak/' + ID_LHKPN + '/1/' + option, '_blank');
                //$('#IS_PRINT').val('1'); // status lampiran 3 cetak
                var totalTime = new Date().getTime() - ajaxTime;
                stf(totalTime);
            } else {
                Loading('hide');
            }
        }
    });
}

function closeModalAlert() {
    $('.modal-backdrop').remove();
    $('#ModalAlert').modal('hide');
    $('body').removeClass('modal-open');
}

/**
 * 
 * kemungkinan tidak dipanggil
 * kemungkinan yg dipanggil adalah yg didalam folder
 * 
 * @param {type} id
 * @returns {undefined}
 */
function FINISHED(id) {

    var ajaxTime = new Date().getTime();
    var URL = base_url + 'portal/review_harta/checklhkpn/' + ID_LHKPN;



    if (id) {
//        closeModalAlert();
        URL = base_url + 'portal/review_harta/checklhkpn/' + ID_LHKPN + '/' + id;
    }

    $.ajax({
        url: URL,
        type: 'json',
        async: false,
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {
            if (data) {
                var result = eval(data);
                var notif = result.notif;
                var view = result.view;
                var grid = result.grid;
                var next = parseInt(view) + 1;
                var index = result.index;


                var btn_final_next = isDefined($('#ModalAlert #btn-final-next')[0]) ? $('#ModalAlert #btn-final-next')[0] : $('#ModalAlert #btn-final-next');
                $(btn_final_next).show();
                if (view == '7') {
                    $(btn_final_next).attr('onclick', 'PreviewIkthisar()');
                } else {
                    $(btn_final_next).attr('onclick', 'FINISHED(' + index + ')');
                }

                if (view == 4) {
                    $(btn_final_next).hide();
                }

                var btn_final_isi = isDefined($('#ModalAlert #btn-final-isi')[0]) ? $('#ModalAlert #btn-final-isi')[0] : $('#ModalAlert #btn-final-isi');

                if (!isEmpty(grid) && grid) {
                    $(btn_final_isi).attr('onclick', 'View("' + view + '","#ModalAlert","' + grid + '")');
                } else {
                    $(btn_final_isi).attr('onclick', 'View("' + view + '","#ModalAlert")');
                }

                var judul_text = notif.substring(0, 12);

                if (judul_text == 'Data Jabatan')
                    $('#ModalAlert #btn-final-next').hide();

                $('#ModalAlert #judultext').text(judul_text);
                $('#ModalAlert #notif-text').text(notif);
                $('#ModalAlert').modal('show');
                var totalTime = new Date().getTime() - ajaxTime;
            } else {
                var IS_PRINT = $('#IS_PRINT').val();
                if (IS_PRINT == '0') {
                    //window.open( base_url+'portal/review_harta/surat_kuasa_pdf/'+ID_LHKPN , '_blank');
                    $('#IS_PRINT').val('1');
                }
                $('#ModalAlert').removeClass('in');
                $('#ModalAlert').css({'display': 'none'});
                $('#ModalAlert').modal('hide').data('bs.modal', null);
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                PreviewIkthisar();
            }
            stf(totalTime);
        }
    });
}

function SEND_LHKPN() {
    $('#ModalAlert').modal('hide').data('bs.modal', null);
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    setTimeout(function () {
        window.open(base_url + 'portal/review_harta/surat_kuasa_pdf/' + ID_LHKPN, '_blank');
        //KUASA_KELUARGA(1);
        SURAT_KUASA();
    }, 10);
}

function Loading(t) {
    var m = document.getElementById('ModalLoading');
    if (t == 'hide') {
        m.style.display = "none";
    } else {
        m.style.display = "block";
    }
}

/**
 * function setTimeOut
 * @returns {undefined}
 */
function stf(t) {
    setTimeout(function () {
        Loading('hide');
    }, parseInt(t * TIMEOUT_BROWSER));
}
function notif(t, at) {
    if (isDefined(at)) {
        t = t + at;
    }
    $('#ModalWarning #notif-text').text(t);
    $('#ModalWarning').modal('show');
}
function success(t) {
    $('#ModalSuccess #notif-text').text(t);
    $('#ModalSuccess').modal('show');
}
function pindah(x) {
    for (var i = 1; i <= 9; i++) {
        if (i == x) {
            View(i);
            break;
        }
    }
}



function UpdateData(index, name, table, dtTable, callback_on_success) {
    var ajaxTime = new Date().getTime();
    $('.inp-load-all-data-' + index).val('0');
    $.ajax({
        url: base_url + 'portal/data_harta_update/' + name + '/' + ID_LHKPN,
        type: 'html',
        async: false,
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {
            if (data == 1) {
                success('Data Berhasil Diperbaharui !!');
                $(table).DataTable().ajax.reload();
                $('.block-body #add-' + index).show();
                $('.block-body #load-' + index).hide();
                $('.block-body #load-all-data-sebelumnya').hide();

                $('.inp-load-all-data-' + index).val('1');
            } else {
                notif('Silahkan refresh halaman ini atau login ulang.');
            }
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);

            if (isDefined(callback_on_success) && isDefined(dtTable)) {
                callback_on_success(dtTable);
            }
        }
    });
}



function View(i, modal, grid) {
    var persen = i * 10;
    var idlhkpn = $('#idlhkpn').val();

    $.ajax({
        type: "POST",
        url: base_url + 'portal/filing/cekStatusInput/' + idlhkpn,
        async: false,
        success: function (response)
        {
            var data = $.parseJSON(response);
            persen = data.persen;
            $(".progress-bar")[0].style.width = persen + '%'
            $(".progress-bar").html(persen + '%');
        }
    });

    if (!isEmpty(modal) && modal && $(modal).length > 0) {
        $(modal).removeClass('in');
        $(modal).modal('hide').data('bs.modal', null);
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }


    var modul = new Array();
    modul[1] = 'data_pribadi';
    modul[2] = 'data_jabatan';
    modul[3] = 'data_keluarga';
    modul[4] = 'data_harta';
    modul[5] = 'data_penerimaan';
    modul[6] = 'data_pengeluaran';
    modul[7] = 'data_fasilitas';
    modul[8] = 'review_lampiran';
    modul[9] = 'review_harta';
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + 'portal/' + modul[i],
        type: 'html',
        async: false,
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            //Loading('hide');
        },
        timeout: 1000,
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
        success: function (data) {

            var title = $('li.tab' + i).text();
            $('#container').empty();
            $('#container').html(data);

            $('#sidebar li').removeClass("active");
            $('li.tab' + i).addClass('active');
            $('.box-title').text(title.toUpperCase());
            $('#current').val(i);

            if (i == 1) {
                $('#btn-prev,#btn-finished,#btn-kembali,#cetak_final').hide();
                $('#btn-next').show();
            } else if (i == 2) {
                // Hide Completed
                $('#btn-prev,#btn-next').show();
                $('#btn-finished,#btn-kembali,#cetak_final').hide();
            } else if (i == 3) {
                // Hide Completed
                $('#btn-prev,#btn-next').show();
                $('#btn-finished,#btn-kembali,#cetak_final').hide();
            } else if (i == 4) {
                if (grid) {
                    var href = $(grid).attr('href').toString();
                    var grid_view = grid.replace("#", "");
                    $('a[href="' + href + '"]').tab('show');
                    Grid(grid_view);
                } else {
                    $('a[href="#data_tanah"]').tab('show');
                    Grid('harta_tidak_bergerak');
                }
                // Hide Completed
                $('#btn-prev,#btn-next').show();
                $('#btn-finished,#btn-kembali,#cetak_final').hide();
            } else if (i == 5) {
                // Hide Completed
                $('#btn-prev,#btn-next').show();
                $('#btn-finished,#btn-kembali,#cetak_final').hide();
            } else if (i == 6) {
                // Hide Completed
                $('#btn-prev,#btn-next').show();
                $('#btn-finished,#btn-kembali,#cetak_final').hide();
            } else if (i == 7) {
                // Hide Completed
                $('#btn-prev,#btn-next').show();
                $('#btn-finished,#btn-kembali,#cetak_final').hide();
            } else if (i == 8) {
                // Hide Completed
                $('#btn-prev,#btn-next').show();
                $('#btn-finished,#btn-kembali,#cetak_final').hide();
            } else {
                $('#btn-prev,#btn-finished,#btn-kembali,#cetak_final').show();
                $('#btn-next').hide();
                $('.box-title').text('Ringkasan Laporan Harta Kekayaan Penyelenggaraan Negara');
            }


            createCookie('current-lhkpn', i, 7);
            $('table .btn').addClass('btn-sm');


            if (IS_COPY == '1') {
                $('a.update').show();
            } else {
                $('a.add-new').show();
                $('a.update').hide();
            }



            if ((STATUS == '0' || STATUS == '2') && i != '1') {
                $('input[type=submit]').show();
                $('button[type=submit]').show();
                if (i == 9) {
                    $('#btn-finished').show();
                }
                $('a.add-new').show();
            } else {
                $('input[type=submit]').hide();
                $('button[type=submit]').hide();
                if (i == 9) {
                    $('#btn-finished').hide();
                }
                $('a.add-new').hide();
            }
            $('.bv-hidden-submit').hide();

            // SETTING HARTA
            var IS_LOAD = $('#IS_LOAD').val();
            var INDEX = $('#INDEX').val();
            var JUMLAH_DATA = $('#JUMLAH_DATA').val();
            if (STATUS == '0' || STATUS == '2') { // STATUS DRAFT
                if (IS_COPY == '0') { // LAPORAN PERTAMA
                    $('#add-' + INDEX).show();
                    $('#load-' + INDEX).hide();
                } else { // LAPORAN KEDUA,KETIGA dst...
                    if (IS_LOAD == '0') { // BELUM DI LOAD
                        $('#add-' + INDEX).hide();
                        $('#load-' + INDEX).show();
                    } else {
                        $('#add-' + INDEX).show();
                        $('#load-' + INDEX).hide();
                    }
                }
            } else { // STATUS TERKIRIM
                $('#add-' + INDEX).hide();
                $('#load-' + INDEX).hide();
            }

            if (JUMLAH_DATA == '0' && IS_LOAD == '0') {
                $('#add-' + INDEX).show();
                $('#load-' + INDEX).hide();
            }


            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);


        },
    });
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

function PreviewIkthisar() {
    $("#Modal_Ikthisar input[name=hasil_ikthisar]").prop('checked', false);
    $('#ModalAlert').removeClass('in');
    $('#ModalAlert').css({'display': 'none'});
    $('#ModalAlert').modal('hide').data('bs.modal', null);
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    var frameSrc = base_url + "portal/ikthisar/preview/" + ID_LHKPN + "/1/1";
    $('#Modal_Ikthisar .modal-dialog').css({
        'margin-top': '5px',
        'width': '70%',
        'height': '100%'
    });
    var height = $('body').height();
    $('#Modal_Ikthisar .modal-dialog').css({
        'margin-top': '5px',
        'width': '80%',
        'height': '100%'
    });
    $('#Modal_Ikthisar iframe').attr("src", frameSrc);
    //$('#Modal_Ikthisar iframe').css({'width':'100%','height':100});
    $('#Modal_Ikthisar').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

function createCookie(name, value, days) {
    /* if (days) {
     var date = new Date();
     date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
     var expires = "; expires=" + date.toGMTString();
     }
     else
     var expires = "";
     document.cookie = name + "=" + value + expires + "; path=/";*/
}

// COOKIES

function readCookie(name) {
    /*  var nameEQ = name + "=";
     var ca = document.cookie.split(';');
     for (var i = 0; i < ca.length; i++) {
     var c = ca[i];
     while (c.charAt(0) == ' ')
     c = c.substring(1, c.length);
     if (c.indexOf(nameEQ) == 0)
     return c.substring(nameEQ.length, c.length);
     }
     return null;*/
}



function deleteAllCookies() {
    /*var cookies = document.cookie.split(";");
     for (var i = 0; i < cookies.length; i++) {
     var cookie = cookies[i];
     var eqPos = cookie.indexOf("=");
     var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
     document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
     }*/
}

function fileExists(url) {
    if (url) {
        var req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.send();
        return req.status == 200;
    } else {
        return false;
    }
}


function CapitalFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function intToFloat(num, decPlaces) {
    return parseFloat(num).toFixed(decPlaces);
}


