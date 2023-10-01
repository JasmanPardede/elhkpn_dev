$(document).ready(function () {
    $('.remodal span').addClass('span-form');
    $("#panduan").click(function () {
        $('html, body').animate({
            scrollTop: $("#services").offset().top - 50
        }, 2000);
    });

    $("#video").click(function () {
        $('html, body').animate({
            scrollTop: $("#video-tutorial").offset().top - 50
        }, 2000);
    });

    var imgArr = new Array(img_url + 'img/gedung1.jpg', img_url + 'img/gedung2.jpg', img_url + 'img/gedung3.jpg');
    $("header").backgroundCycle({
        imageUrls: imgArr,
        fadeSpeed: 2500,
        duration: 9000,
        shuffle: true,
        effect: "blind",
        backgroundSize: SCALING_MODE_COVER
    });

    $('#txtCaptcha').click(function () {
        //reload_captcha();
    });

    $('#txtCaptcha-announ').click(function () {
        //reload_captcha_announ();
    });

    $('#f-message,#f-progress').hide();
    $('#f-forget').submit(function () {
        $('#f-input,#f-button').fadeOut('slow');
        $('#f-progress').fadeIn('slow');
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        //Do something with download progress
                        $('#progress_widget').attr('aria-valuenow', percentComplete);
                        $('#progress_widget').html('Sedang mengirim permintaan....');
                        if (percentComplete === 1) {
                            $('#f-progress').delay(2000).fadeOut('slow');
                        }
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: base_url + "portal/user/forget",
            data: $(this).serialize(),
            success: function (data) {
                setTimeout(function () {
                    $('#f-message').show();
                    if (data == '0') {
                        $('#berhasil,#gagal_sistem,#gagal_aktivasi').hide();
                        $('#gagal_database').fadeIn('slow');
                    } else if (data == '1' || data == '2') {
                        $('#berhasil,#gagal_database,#gagal_aktivasi').hide();
                        $('#gagal_sistem').fadeIn('slow');
                    } else if(data == '4') {
                        $('#berhasil,#gagal_sistem,#gagal_database').hide();
                        $('#gagal_aktivasi').fadeIn('slow');
                    } else {
                        $('#gagal_database,#gagal_sistem,#gagal_aktivasi').hide();
                        $('#berhasil').fadeIn('slow');
                    }
                }, 2000);
            }
        });
        return false;
    });
    $('#fa-aktivasi').submit(function () {
        $('#fa-input,#fa-button').fadeOut('slow');
        $('#fa-progress').fadeIn('slow');
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        //Do something with download progress
                        $('#progress_widget').attr('aria-valuenow', percentComplete);
                        $('#progress_widget').html('Sedang mengirim permintaan....');
                        if (percentComplete === 1) {
                            $('#fa-progress').delay(2000).fadeOut('slow');
                        }
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: base_url + "portal/user/aktivasi",
            data: $(this).serialize(),
            success: function (data) {
                setTimeout(function () {
                    $('#fa-message').show();
                    if (data == '0') {
                        $('#berhasil_a,#gagal_sistem_a,#gagal_aktivasi_a').hide();
                        $('#gagal_database_a').fadeIn('slow');
                    } else if(data == '4') {
                        $('#berhasil_a,#gagal_sistem_a,#gagal_database_a').hide();
                        $('#gagal_aktivasi_a').fadeIn('slow');
                    } else if (data == '1' || data == '2') {
                        $('#berhasil_a,#gagal_database_a,#gagal_aktivasi_a').hide();
                        $('#gagal_sistem_a').fadeIn('slow');
                    } else {
                        $('#gagal_database_a,#gagal_sistem_a,#gagal_aktivasi_a').hide();
                        $('#berhasil_a').fadeIn('slow');
                    }
                }, 2000);
            }
        });
        return false;
    });


});

$(document).on('closing', '.remodal', function (e) {
    $('#f-message,#f-progress').hide();
    $('#f-input,#f-button').show();
    $('#f-forget')[0].reset();
    $('#fa-message,#fa-progress').hide();
    $('#fa-input,#fa-button').show();
    $('#fa-aktivasi')[0].reset();

});

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 * ini yang dipanggil
 * @returns {undefined}
 */
function reload_captcha() {
    
    $.post(base_url + "index.php/auth/reload_captcha").done(function (msg) {
        var result = JSON.parse(msg);
        $('#img_captcha').html(result.image);
        $('#hdn_captcha').val(result.value);
        $('#txtCaptcha').html(result.image);
    });
}

function reload_captcha_announ() {
    
    $.post(base_url + "index.php/auth/reload_captcha_announ").done(function (msg) {
        var result = JSON.parse(msg);
        $('#img_captcha_announ').html(result.image);
        $('#hdn_captcha_announ').val(result.value);
        $('#txtCaptcha-announ').html(result.image);
    });
}


         