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
        notif('Mohon Maaf, Ada kesalahan pada system !!', at);
    } else if (exception === 'parsererror') {
        notif('Mohon Maaf, Ada kesalahan pada system !!', at);
    } else if (exception === 'timeout') {
        notif('Mohon Maaf, Koneksi Terputus !!', at);
    } else if (exception === 'abort') {
        notif('Mohon Maaf, Koneksi Terputus !!', at);
    } else {
        notif('Mohon Maaf, Ada kesalahan pada system !!', at);
    }
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
            notif('Mohon Maaf, Ada kesalahan pada system !!');
        },
        success: function (data) {
            $('#form-content').empty();
            $('#form-content').html(data);
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
    });
}

function dateConvert(val) {
    var res = val.split("-");
    return res[2] + '/' + res[1] + '/' + res[0];
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
    if (isDefined(t)) {
        setTimeout(function () {
            Loading('hide');
        }, parseInt(t * TIMEOUT_BROWSER));
    }
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

function setSettingHarta() {
    var IS_LOAD = $('#IS_LOAD').val();
    var INDEX = $('#INDEX').val();
    var JUMLAH_DATA = $('#JUMLAH_DATA').val();
    var C_HARTA = $('#C_HARTA').val();

    if (STATUS == '0' || STATUS == '2' || STATUS == '7' && VIA_VIA == '0') { // STATUS DRAFT
        if (IS_COPY == '0') { // LAPORAN PERTAMA
            $('#add-' + INDEX).show();
            $('#load-' + INDEX).hide();
        } else { // LAPORAN KEDUA,KETIGA dst...
            if (IS_LOAD == '0') { // BELUM DI LOAD
                $('#add-' + INDEX).hide();
                $('#load-' + INDEX).hide();
            } else {
                $('#add-' + INDEX).show();
                $('#load-' + INDEX).hide();
                $("#load-all-data-sebelumnya").hide();
            }
        }
    } else { // STATUS TERKIRIM
        $('#add-' + INDEX).hide();
        $('#load-' + INDEX).hide();
        $('#load-all-data-sebelumnya').hide();
    }

    $("#load-all-data-sebelumnya").on("click", function () {
        $('#add-' + INDEX).show();
    });

    if (JUMLAH_DATA == '0' && IS_LOAD == '0') {
        if (C_HARTA > 0) {
            $('#add-' + INDEX).show();
            $('#load-' + INDEX).hide();
            $("#load-all-data-sebelumnya").hide();
        }
    }

}

function View(i, modal, grid) {
//    setSettingHarta();
    var persen = i * 10;
    var idlhkpn = $('#idlhkpn').val();

    if (!isDefined(base_url)) {
        alert("URL Tidak dikenali");
        return false;
    }

    $.ajax({
        type: "POST",
        url: base_url + 'portal/filing/cekStatusInput/' + idlhkpn,
        async: false,
        success: function (response) {
            var data = $.parseJSON(response);
            persen = data.persen;
            $(".progress-bar")[0].style.width = persen + '%'
            $(".progress-bar").html(persen + '%');
        }
    });

    if (isDefined(modal) && modal) {
        $(modal).removeClass('in');
        $(modal).modal('hide').data('bs.modal', null);
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    } else {
        console.log("Halaman Modal tidak terdefinisi<br />filingHelper : 293.", modal, i);
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
            } else if (i == 4 || i == '4') {
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
//                setSettingHarta();
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

            if (isDefined(IS_COPY) && IS_COPY == '1') {
                $('a.update').show();
            } else {
                $('a.add-new').show();
                $('a.update').hide();
            }


            if ((STATUS == '0' || STATUS == '2' || STATUS == '7') && i != '1' && VIA_VIA == '0') {
                $('input[type=submit]').show();
                $('button[type=submit]').show();
                if (i == 9) {
                    $('#btn-finished').show();
                }
//                var showed = false, aNewButton = isDefined($('a.add-new').length);
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
            setSettingHarta();

            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);

        },
    });
}