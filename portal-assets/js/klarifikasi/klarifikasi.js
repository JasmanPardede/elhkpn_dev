/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

function notif(t, at) {
    if (isDefined(at)) {
        t = t + at;
    }
    $('#ModalWarning #notif-text').text(t);
    $('#ModalWarning').modal('show');
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
                notif('Mohon Maaf, Ada kesalahan pada system !!');
            }
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
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

/**
 * function CallForm(); filingHelper.js
 * function dateConvert(); filingHelper.js
 * function load_html(); filingHelper.js
 * pindah_tab(); filingHelper.js
 * to_target(); filingHelper.js
 * Loading(); filingHelper.js
 * stf(); filingHelper.js
 * notif(); filingHelper.js
 * success(); filingHelper.js
 * pindah(); filingHelper.js
 */






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
//            $('.grid_wrapper').empty();

            if ($('.grid_' + name).length > 0) {
                $('.grid_' + name).empty();
            } else {
                $('.grid_wrapper').empty();
            }
            $('.grid_' + name).html(data);
            $('table .btn').addClass('btn-sm');
            // SETTING HARTA
            setSettingHarta();


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
            $('#modal_token #server_code').text(data);
            if (data != '0') {
                $('#modal_token #token_salah').hide();
                $('#modal_token #token_reset').show();
                $('#modal_token #nomor_token').val('');
            } else {
                notif('Mohon Maaf, Ada kesalahan pada system !!');
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
                $('#modal_token #btn-final-next')[0].setAttribute('disabled', 'disabled');
                $('#modal_token #btn-final-next')[1].setAttribute('disabled', 'disabled');
                $('#modal_token #btn-cancel')[0].setAttribute('disabled', 'disabled');
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
            $('#modal_token #server_code').text(data);
            if (data != '0') {
//                $('#modal_token').modal({
//                    backdrop: 'static',
//                    keyboard: false,
//                    show: true
//                });

                showLhkpnModal('#modal_token', {
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                }, function () {
                    $('#modal_token .group-0').attr('style', '');
                });
            } else {
                notif('Mohon Maaf, Ada kesalahan pada system !!');
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
	
    $.ajax({
        url: LINK,
        type: 'json',
        async: false,
        success: function (data) {
            $('#ModalSuratKuasa').modal('hide').data('bs.modal', null);
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $('#ModalSuratKuasa').removeClass('in');
            $('#ModalSuratKuasa').css({'display': 'none'});

            SHOW_KELUARGA(index, modal, option);
        }
    });
        // window.open(LINK, '_blank');

        // $('#ModalSuratKuasa').modal('hide').data('bs.modal', null);
        // $('body').removeClass('modal-open');
        // $('.modal-backdrop').remove();
        // $('#ModalSuratKuasa').removeClass('in');
        // $('#ModalSuratKuasa').css({'display': 'none'});
        // /*if (option == '0') {
        // $('#modal_lagi #btn-final-next')[0].setAttribute('onclick', 'SHOW_KELUARGA(' + index + ',"' + modal + '",' + option + ')');
        // $('#modal_lagi').modal('show');
        // } else {
        // SHOW_KELUARGA(index, modal, option);
        // }*/
        
        // SHOW_KELUARGA(index, modal, option);
}

function KUASA_KELUARGA2_old(index, modal) {
    PRINT_KELUARGA(parseInt(index - 1));
    var option = $('input[name=hasil_surat_kuasa4]:checked').val();
    $('#ModalKuasaKeluarga').modal('hide').data('bs.modal', null);

//    console.log(modal, "check modal");
    if (modal) {
        console.log("masuk sini, index : " + index);
//        while ($('#ModalKuasaKeluarga').hasClass('in')) {
        $('#ModalKuasaKeluarga').modal('hide').data('bs.modal', null);
//        }
    }
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('#ModalKuasaKeluarga').removeClass('in');
    $('#ModalKuasaKeluarga').css({'display': 'none'});
    $('#ModalKuasaKeluarga').hide();
//    if($('.modal-backdrop').is(':visible')){
//        $('.modal-backdrop').hide();
//    }

    /*if (option == '0') {
     $('#modal_lagi #btn-final-next')[0].setAttribute('onclick', 'SHOW_KELUARGA(' + index + ',"' + modal + '",' + option + ')');
     $('#modal_lagi').modal('show');
     } else {
     SHOW_KELUARGA(index, modal, option);
     }*/

//    return false;
    SHOW_KELUARGA(index, modal, option);
}

function KUASA_KELUARGA2(index, modal) {
//    PRINT_KELUARGA(parseInt(index - 1));
    var option = $('input[name=hasil_surat_kuasa4]:checked').val();
    var LINK = base_url + 'portal/review_harta/surat_kuasa_pdf2/' + ID_LHKPN + '/' + parseInt(index - 1) + '/' + option;
//    console.log(LINK);
    window.open(LINK, '_blank');
    if ($('#ModalSuratKuasa #btn-print').length > 0) {
        $('#ModalSuratKuasa #btn-print').hide();
    }

    $('#ModalSuratKuasa').modal('hide').data('bs.modal', null);

    $('body').removeClass('modal-open');
//    $('.modal-backdrop').remove();
    $('#ModalSuratKuasa').removeClass('in');
    $('#ModalSuratKuasa').css({'display': 'none'});
//    $('#ModalSuratKuasa').hide();
//    if($('.modal-backdrop').is(':visible')){
//        $('.modal-backdrop').hide();
//    }

    /*if (option == '0') {
     $('#modal_lagi #btn-final-next')[0].setAttribute('onclick', 'SHOW_KELUARGA(' + index + ',"' + modal + '",' + option + ')');
     $('#modal_lagi').modal('show');
     } else {
     SHOW_KELUARGA(index, modal, option);
     }*/

//    return false;
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
                var STATUS_CETAK = rs.STATUS_CETAK_SURAT_KUASA; // || 0;
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

//                if (rs.LAST == '1') {
//                    $('#ModalKuasaKeluarga #btn-final-next')[0].setAttribute('onclick', 'TOKEN(' + ID_LHKPN + ',' + index + ')');
//                    //$('#ModalKuasaKeluarga #btn-final-isi')[0].setAttribute('onclick', 'SELESAI_LHKPN(' + ID_LHKPN + ')');
//                } else {
                    $('#ModalKuasaKeluarga #btn-final-next')[0].setAttribute('onclick', rs.NEXT);
                    //$('#ModalKuasaKeluarga #btn-final-isi')[0].setAttribute('onclick', rs.NEXT);
//                }


                if (IS_COPY == '0') {
                    /* alert(UMUR+' '+STATUS_CETAK);*/
                    if (UMUR >= 17 && STATUS_CETAK == '0') {
//                        $('#ModalKuasaKeluarga #btn-print').show();
                        $('#ModalKuasaKeluarga #btn-print')[0].setAttribute('onclick', 'PRINT_KELUARGA(' + index + ')');
                    } else {
                        $('#ModalKuasaKeluarga #btn-print').hide();
                    }
                } else {
                    if (UMUR >= 17 && STATUS_CETAK == '0' && LIMA_TAHUN == '1') {
//                        $('#ModalKuasaKeluarga #btn-print').show();
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



//                var modalshown = false;
//                $('#ModalKuasaKeluarga').modal('hide');
//                while(!modalshown){
//                    $('#ModalKuasaKeluarga').modal('show');
//                    if($('#ModalKuasaKeluarga').hasClass('in')){
//                        modalshown = true;
//                    }
//                }

//                console.log("modal is shown : ", $('#ModalKuasaKeluarga').hasClass('in'));

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
//    console.log(LINK);
    window.open(LINK, '_blank');
    if ($('#ModalKuasaKeluarga #btn-print').length > 0) {
        $('#ModalKuasaKeluarga #btn-print').hide();
    }
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
            $('input[type="radio"]').on('click', function (e) {
                var val = $(this).val();
                if (val == '1') {
                    $("#ModalSuratKuasa #btn-final-next").show();
                } else {
                    $("#ModalSuratKuasa #btn-final-next").hide();
                }
            });
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
                
                //Saughi
                if (rs.STATUS_CETAK_SURAT_KUASA == '1') {
                    $('#ModalSuratKuasa #btn-final-next')[0].setAttribute("onclick","KUASA_KELUARGA(2, '#ModalKuasaKeluarga')");
                }
                //Saughi

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
 * kemungkinan dipanggil
 *
 * @param {type} id
 * @returns {undefined}
 */
function FINISHED(id) {
    Loading('hide');
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
            Loading('hide');
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
                var ctr = result.ctr;
                var t_harta = result.total_harta;
                var btn_final_next = isDefined($('#ModalAlert #btn-final-next')[0]) ? $('#ModalAlert #btn-final-next')[0] : $('#ModalAlert #btn-final-next');

                $(btn_final_next).show();
                if (view == '7') {
                    $(btn_final_next).attr('onclick', 'PreviewIkthisar()');
                } else {
                    $(btn_final_next).attr('onclick', 'FINISHED(' + index + ')');
                }

                if (view == 4) {
//                    $(btn_final_next).hide();
                }
                
                var newCond = (STATUS == '0' || STATUS == '2') && IS_COPY == '0';
//                if ((index == 10 && (ctr == '0' || ctr == 0)) || !newCond) {
                if ((ctr == '0' || ctr == 0) || !newCond) {
                    if (view == 1 || view == 3) {
                    	$(btn_final_next).hide();
                    } else {
	                	if (notif == result.title + ' belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.') {
	                        $(btn_final_next).show();
	                    } else {
	                        console.log(notif);
	                        console.log(result.title + ' belum diisi, apakah Anda yakin? Bila Ya klik tombol Lanjutkan.');
	                        $(btn_final_next).hide();
	                    }
                    }
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



function UpdateData(index, name, table, dtTable, callback_on_success) {
    var ajaxTime = new Date().getTime();
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
            } //else {
            //notif('Mohon Maaf, Ada kesalahan pada system !!');
            //}
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);

            if (isDefined(callback_on_success) && isDefined(dtTable)) {
                callback_on_success(dtTable);
            }
        }
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
        'width': '100%',
        'height': '100%'
    });
    var height = $('body').height();
    $('#Modal_Ikthisar .modal-dialog').css({
        'margin-top': '5px',
        'width': '100%',
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