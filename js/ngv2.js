/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
 (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
 (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/** 
 * js
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package js
 *
 
 ngv2.js    
 Copyright (c) Gunaones, 2015
 
 Licensed opensource
 http://www.opensource.org/licenses/mit-license.php
 
 */
function OpenModalBox(title, content, footer, $size) { 
    /*(document.getElementById('modal-inner').innerHTML = '<html>';
     document.getElementById('modal-inner').innerHTML = content;
     document.getElementById('modal-header').innerHTML = title;
     document.getElementById('modal-bottom').innerHTML = footer;
     document.getElementById('modal-bottom').innerHTML = '</html>'*/

    $('#loader_area').hide();
    $('#modal-inner').html(content);
    $('#modal-header').html(title);
    $('#modal-bottom').html(footer);
    if ($size == 'large') {
        $('#myModal').attr('class', 'modal fade bs-example-modal-lg').attr(
                'aria-labelledby', 'myLargeModalLabel');
        $('.modal-dialog').attr('class', 'modal-dialog modal-lg');
    }
    if ($size == 'standart') {
        $('#myModal').attr('class', 'modal fade').attr('aria-labelledby',
                'myModalLabel');
        $('.modal-dialog').attr('class', 'modal-dialog');
    }
    if ($size == 'small') {
        $('#myModal').attr('class', 'modal fade bs-example-modal-sm').attr(
                'aria-labelledby', 'mySmallModalLabel');
        $('.modal-dialog').attr('class', 'modal-dialog modal-sm');
    }
    $('#myModal').modal({backdrop: 'static'});
    $('#myModal').modal('show');
}

function emptyTheModalBox() {
    if ($('#modal-inner').length > 0) {
        $('#modal-inner').empty();
    }

    if ($('#modal-header').length > 0) {
        $('#modal-header').empty();
    }

    if ($('#modal-bottom').length > 0) {
        $('#modal-bottom').empty();
    }
}

function CloseModalBox() {
    emptyTheModalBox();
    if ($('#myModal').length > 0) {
        $('#myModal').modal('hide');
    }
}

function CloseModalBox2() {
    emptyTheModalBox();
    if ($('#myModal').length > 0) {
        $('#myModal').modal('hide');
    }
}
function CloseModalBox2_old() {
    var a = confirm('Apa anda yakin akan keluar ?');
    if (a == true) {
        emptyTheModalBox();
        $('#myModal').modal('hide');
    }
}

var ng = {
    reference: {
        id: ''
    },
    formProcess: function (Obj, act, redirect, callback, callbackoption, isCloseModal, text = "Data Telah digunakan!") {
        this.stopSubmitOnPressEnter(Obj);
        //callback handler for form submit
        switch (act) { 
            case 'delete':
                msg = {
                    success: 'Data Berhasil Dihapus!',
                    error: 'Data Gagal Dihapus!',
                    duplicate: text
                    // untu narasi apabila nilai balikan 9
                };
                break;
            case 'reset_password':
                msg = {
                    success: 'Password baru berhasil dikirim !',
                    error: 'Password baru Gagal diproses/ dikirim!'
                };
                break;
            default:
                msg = {
                    success: 'Data Berhasil Disimpan!',
                    error: 'Data Gagal Disimpan!',
                    duplicate: text
                };
        }

        $(Obj).submit(function (e) {
            var formObj = $(this);
            $(this).children(":submit").prop('disabled', true);
            var formURL = formObj.attr("action");
            var formData = new FormData(this);
            if (isCloseModal == false) {
            } else {
                CloseModalBox();
            }
            $('#loader_area').show();
            //$('button').attr('disabled','disabled');
            $.ajax({
                url: formURL,
                type: 'POST',
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data, textStatus, jqXHR) {
                    //$('button').removeAttr('disabled');
                    $('#loader_area').hide();

                    if (data == 0) {
                        alertify.error(msg.error);
                    }else if(data == 9){
                        alertify.error(msg.duplicate);
                    } else {
                        if (act == 'reset_password' && data == '2') {
                            alert("Akun tidak dapat di Reset dikarenakan belum diaktivasi, silakan koordinasikan dengan pemilik akun untuk mengaktivasi akunnya", "Perhatian");
                        } else if (act == 'kirim_aktivasi' && data == '2') {
                            alert("Email aktivasi tidak dapat dikirim dikarenakan Akun sudah aktif, silahkan menggunakan fitur Reset Password", "Perhatian");
                        } else {
                            alertify.success(msg.success);
                        }
                    }
                    if (typeof callback === "function") {
                        callback(callbackoption, data, textStatus);
                    }
                    if (redirect) {
                        var url = redirect;
                        window.location.hash = url;
                        ng.LoadAjaxContent(url);
                        return false;
                    }
                    // alert(data);
                    ng.reference.id = data;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //$('button').removeAttr('disabled');
                    $('#loader_area').hide();
                    alertify.error(msg.error + "\n" + jqXHR.statusText);
                }
            });
            e.preventDefault(); //Prevent Default action.
            // e.unbind();
        });
        if (act == 'insert') { // Ctrl + s
            $(document).on('keydown', function (e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $(Obj).submit();
                    return false;
                }
            });
        } else if (act == 'update') {
            $(document).on('keydown', function (e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $(Obj).submit();
                    return false;
                }
            });
        }
    },
    LoadAjaxContentPost: function (url, form, target, callback, callbackoption) {
        $('#loader_area').show();
        $.post(url, form.serialize(), function (html) {
            $('#loader_area').hide();
            if (target) {
                $(target).html(html);
            } else {
                $('#ajax-content').html(html);
            }
            // $('.preloader').hide();
            if (typeof callback === "function") {
                callback(callbackoption);
            }
            $('.breadcrumb a').click(function (e) {
                e.preventDefault(); //Prevent Default action.
                // e.unbind();            
                url = $(this).attr('href');
                window.location.hash = url;
                ng.LoadAjaxContent(url);
                return false;
            });
            return false;
        });
    },
    LoadAjaxContent: function (url, target, callback) {
        // $('.preloader').show();
        // url = $(this).attr('href');
        if (!isDefined(url)) {
            return false;
        }

        var n = url.indexOf("undefined");
        // alertify.alert('Ajax URL',ajax_url);
        if (n > -1 && url == undefined) {
            return false;
        }
        window.location.hash = url;
        if (url.substring(0, 9) != 'index.php') {
//			alertify.alert('Warning!', 'Not Allow');
            return;
        }
        $('#loader_area').show();
        $.ajax({
            mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
            url: url,
            type: 'GET',
            success: function (html) {
                $('#loader_area').hide();
                if (typeof callback === 'function') {
                    var res = callback(html);

                    if (!res) {
                        return;
                    }
                }
                if (target) {
                    $(target).html(html);
                } else {
                    $('#ajax-content').html(html);
                }
                // $('.preloader').hide();
                $('.breadcrumb a').click(function (e) {
                    e.preventDefault(); //Prevent Default action.
                    url = $(this).attr('href');
                    window.location.hash = url;
                    ng.LoadAjaxContent(url);
                    // e.unbind();             
                    return false;
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alertify.alert(textStatus, errorThrown);
                $('#loader_area').hide();
            },
            dataType: "html",
            async: false
        });
    },
    stopSubmitOnPressEnter: function (Obj) {
        $(Obj).find(":input").keypress(function (e) {
            var code = e.keyCode || e.which;
            var src = e.srcElement || e.target;
            if (src.tagName.toLowerCase() != "textarea") {
                if (code == 13) {
                    if (e.preventDefault) {
                        e.preventDefault();
                    } else {
                        e.returnValue = false;
                    }
                    e.preventDefault();
                    return false;
                }
            }
        });
    },
    LoadAjaxTabContent: function (opt) {
        $(opt.block).block({
            message: '<img src="images/loading.gif" width="100px" />'
        });
        $.get(opt.url, function (html) {
            $(opt.container).html(html);
            $(opt.block).unblock();
        });
    },
    exportTo: function (mode, action, title) { // author gunaones
        $('#ajaxFormCari').after(
                '<form method="post" action="' + action
                + '" id="ajaxFormPrint"></form>');
        $("#ajaxFormCari").children().clone().appendTo('#ajaxFormPrint');
        if (mode == 'pdf') {
            html = '<iframe src="" width="100%" height="'
                    + ($(window).height() - 140 + 'px')
                    + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
            f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
//            OpenModalBox(title, html, '', 'large');
            OpenModalBox(title, html, f_close, 'large');
            $('#ajaxFormPrint').attr('target', 'iframeCetak');
        } else if (mode == 'excel') {
            $('#ajaxFormPrint').removeAttr('target');
        } else if (mode == 'word') {
            $('#ajaxFormPrint').removeAttr('target');
        }
        $('#ajaxFormPrint').submit();
        $('#ajaxFormPrint').remove();
    },
    postOpenModalBox: function (url, title, footer, size) {
        $.post(url, function (html) {
            OpenModalBox(title, html, footer, size);
        });
    },
    setRequired: function (objRequired) {
        $.each(objRequired, function (index, val) {
            if (val[1]) {
                $(val[0]).prop('required', true);
            } else {
                $(val[0]).prop('required', false);
            }
        });
    }
}

$(document).ajaxStart(function () {
    if ($('#globalOnlineStatus').val() == 'offline') {
        alertify.alert('Offline!', 'Koneksi Terputus!');
        return false;
    }
});

$(document).ready(function () {
    var ajax_url = location.hash.replace(/^#/, '');
    if (ajax_url.length < 1) {
        //ajax_url = 'index.php/welcome/dashboard';
        window.location.href = base_url + 'portal/home';
    }
    // alertify.alert('Ajax URL',ajax_url);
    if (isDefined(ajax_url) && ajax_url != undefined) {
        ng.LoadAjaxContent(ajax_url);
    }

    function resetActive() {
        $('.sidebar-menu a').each(function (index, el) {
            $(this).removeClass('linkActive');
        });
    }
    $('.sidebar-menu').on('click', 'a', function (e) {
        if ($(this).hasClass('ajax-link')) {
            e.preventDefault();
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            resetActive();
            $(this).addClass('linkActive');
        }
        if ($(this).attr('href') == '#') {
            e.preventDefault();
        }
    });
    $('.navbar-custom-menu').on('click', 'a', function (e) {
        if ($(this).hasClass('ajax-link')) {
            e.preventDefault();
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
        }
        if ($(this).attr('href') == '#') {
            e.preventDefault();
        }
    });

    $("body").append('<input type="hidden" id="globalOnlineStatus">');
    var condition = navigator.onLine ? "online" : "offline";
    $('#globalOnlineStatus').val(condition);
    function updateOnlineStatus(event) {
        var condition = navigator.onLine ? "online" : "offline";
        $('#globalOnlineStatus').val(condition);
        if (condition == 'online') {
            alertify.closeAll();
            alertify.alert('Online!', 'Koneksi Tersambung Kembali').set('closable', true);
        } else if (condition == 'offline') {
            alertify.alert('Offline!', 'Koneksi Terputus!').set('closable', false);
        }
    }
    if (window.addEventListener) {
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
    } else {
        document.body.attachEvent('onoffline', updateOnlineStatus);
        document.body.attachEvent('ononline', updateOnlineStatus);
    }
});

var onButton = {
    ajaxLah: function (url, size, attemp) { 
		

        var _attemp = 0;
        if (isDefined(attemp)) {
            _attemp = attemp;
        }

        var xhr = $.ajax({
            type: "POST",
            url: url,
            success: function (html) { 
//                    alert( + ' bytes');
//                if (xhr.getResponseHeader('Content-Length') <= 0) {
                if (html.length <= 0) {
                    if (_attemp <= 10) {
                        _attemp += 1;
                        onButton.ajaxLah(url, size, _attemp);
                    }
                } else { 
                    OpenModalBox('Validasi', html, '', size);
                }
            }
        });
    },
    go: function (obj, size, recallwhenfailed) {

        if (!isDefined(size)) {

            size = 'large'; 
        } 
        if (!isDefined(recallwhenfailed)) {
            recallwhenfailed = false;
        }
		
        var url = $(obj).attr('href');
        $('#loader_area').show();

        if (!recallwhenfailed) {
            $.post(url, function (html) { 
                OpenModalBox('Validasi', html, '', size);
            });
        } else {
            onButton.ajaxLah(url, size); 
        }

        return false;
    },
    delete: function (obj, size) {
        confirm("Apakah anda yakin menghapus data ? ", function () {
            if (!isDefined(size)) {
                size = 'large';
            }

            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                location.reload();
            });
        });
        return false;
    },
    changePrimaryKey: function (obj, size,jabatan) {
        confirm("Apakah Anda Yakin akan Memilih Jabatan "+ jabatan +" Sebagai Jabatan Utama ? ", function () {
            if (!isDefined(size)) {
                size = 'large';
            }
            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                location.reload();
            });
        },'SET JABATAN UTAMA');
        return false;
    }
};

/**
 * 
 * @param {type} file
 * @param {type} progress progress bar
 * @returns {undefined}
 */
function UploadFile(file, actionUrl, progressBar, allowedFileType, callbackIfDone, replaceIsExist) {

    if (!isDefined(replaceIsExist)) {
        replaceIsExist = false;
    }

    if (!isDefined(allowedFileType) || (isDefined(allowedFileType) && allowedFileType != false)) {
        allowedFileType = [
            "application/vnd.ms-excel",
            "application/vnd.ms-excel.sheet.macroEnabled.12",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "text/xml",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/pdf",
            "application/x-zip-compressed",
            "text/plain",
            "image/jpeg",
            "image/png",
            "image/bmp",
            "image/gif",
        ];
    }

    var fileTypeOk = jQuery.inArray(file.type, allowedFileType) !== -1;

    // following line is not necessary: prevents running on SitePoint servers
    if (location.host.indexOf("sitepointstatic") >= 0) {
        return;
    }

    var xhr = new XMLHttpRequest();
//        if (xhr.upload && fileTypeOk && file.size <= $id("MAX_FILE_SIZE").value) {
    if (xhr.upload && fileTypeOk) {

        // create progress bar
//            var o = $id("progress");
//            var progress = o.appendChild(document.createElement("p"));
//            progress.appendChild(document.createTextNode("upload " + file.name));


        // progress bar
        if (isDefined(progressBar)) {
            xhr.upload.addEventListener("progress", function (e) {
                var pc = parseInt(100 - (e.loaded / e.total * 100));
//                    progress.style.backgroundPosition = pc + "% 0";
                $(progressBar).attr("aria-valuenow", pc);
            }, false);
        }

        // file received/failed

        xhr.onreadystatechange = function (e) {
            if (xhr.readyState == 4) {

                var isSuccess = (xhr.status == 200 ? "success" : "failure");
                if (isDefined(progressBar)) {
                    var progressBarParent = $(progressBar).parent();
                    $(progressBarParent).html('');
                    $(progressBarParent).text(isSuccess);
                }

                if (isDefined(callbackIfDone)) {
                    callbackIfDone(xhr.status, xhr, progressBarParent);
                }
            }
        };


        // start upload
//            xhr.open("POST", $id("upload").action, true);
        xhr.open("POST", actionUrl, true);

        var formData = new FormData();
        formData.append("file_import_excel_temp", file);
        formData.append("replace_is_exist", (replaceIsExist ? 1 : 0));
        formData.append("file_id", $("#random_id").val());

        xhr.send(formData);

    }

}


//LHKPNOffline
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