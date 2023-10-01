<script type="text/javascript">
    $(document).ready(function () {

//        $("#ajaxFormAdd").submit(function (e) {
        $("#btnSubmit").click(function (e) {
            var isianNikBermasalah = !HitungText(true);
            var isianNikBaruBermasalah = !HitungText(true, true);
            var isianHpBermalasah = cek_nohp($("#NO_HP"), true);

            var tglLhr = $('#TGL_LAHIR').val();
            if (dobIsNotValid17($('#TGL_LAHIR'), true)) {
                showDob17Alert($('#TGL_LAHIR'));
                return false;
            }
            if (!isianHpBermalasah) {
                alertify.closeAll();
                alertify.alert('PERHATIAN!!', 'Periksa Kembali Isian No HP, tidak boleh kosong.').set('closable', true);
                return false;
            }
            if (isianNikBermasalah) {
                alertify.closeAll();
                alertify.alert('PERHATIAN!!', 'Periksa Kembali Isian NIK, isian anda kurang dari 16 karakter').set('closable', true);
                return false;
            }
            if (isianNikBaruBermasalah) {
                alertify.closeAll();
                alertify.alert('PERHATIAN!!', 'Periksa Kembali Isian NIK Baru, isian anda kurang dari 16 karakter').set('closable', true);
                return false;
            }
            if (dobIsNotValid17($('#TGL_LAHIR').true, true)) {
                alertify.closeAll();
                alertify.alert('PERHATIAN!!', 'Periksa Kembali Isian NIK, isian anda kurang dari 16 karakter').set('closable', true);
                return false;
            }

            var urll = form.attr('actrel');
            var formData = new FormData($("#ajaxFormAdd")[0]), Lengkap = true;

            alertify.closeAll();
            if ($('#NAMA').val() == '' || $('#TEMPAT_LAHIR').val() == '' || $('#TGL_LAHIR').val() == '' || $('#JNS_KEL').val() == '') {
                Lengkap = false;
                alertify.alert('PERHATIAN!!', 'Mohon Melengkapi Isian Yang Bertanda Bintang(*).').set('closable', true);
            }
            if (Lengkap && $('#LEMBAGA').val() == '' || $('#UNIT_KERJA').val() == '' || $('#JABATAN').val() == '' || $('#TGL_LAHIR').val() == '') {
                Lengkap = false;
                alertify.alert('PERHATIAN!!', 'Mohon Melengkapi Isian Yang Bertanda Bintang(*).').set('closable', true);
            }
            //is_aplikasi
            //cekapliaksi(document.getElementById('is_aplikasi'));
            if ($('input[name=is_aplikasi]:checked').length > 0) {
                if (Lengkap && $('#EMAIL').val() == '' || $('#NO_HP').val() == '') {
                    Lengkap = false;
                    alertify.alert('PERHATIAN!!', 'Periksa Kembali Email dan No HP').set('closable', true);
                }
            }


            if (Lengkap) {
                if (!isianNikBermasalah) {
                    $('#loader_area').show();
                    e.preventDefault();
                    $.ajax({
                        url: urll + '/<?php echo md5($daftar_pn->KNIK) . "n1ko" . $daftar_pn->KNIK . "ok1o" . md5($daftar_pn->KNIK); ?>',
                        type: 'POST',
                        data: formData,
                        async: false,
                        success: function (htmldata) {
                            htmldata = JSON.parse(htmldata);
                            alertify.closeAll();
                            if (htmldata.status == 0) {
                                if (isDefined(msg)) {
                                    alertify.error(msg.error);
                                } else {
                                    alertify.error("Terjadi kesalahan, Periksa kembali isian anda.");
                                }
                                $('#loader_area').hide();
                            } else if (htmldata.status == 2) {
                                alertify.error('NIK sudah terdaftar sebagai user, periksa nama PN di daftar WL atau Non WL !!!');
                                $('#loader_area').hide();
                            }else if (htmldata == 8) {
                                alertify.error('Format Email Salah!!!');
                                $('#loader_area').hide();
                            } else if (htmldata == 9) {
                                alertify.error('Email sudah terdaftar!!!');
                                $('#loader_area').hide();
                            } else {
                                if (isDefined(msg)) {
                                    alertify.success(msg.success);
                                } else {
                                    alertify.success("Sukses menyimpan data.");
                                }
//                                 $.get(location.href.split('#')[1], function (html) {
//                                     $('#ajax-content').html(html);
//                                     CloseModalBox();
//                                     $('#loader_area').hide();
//                                 });

                                var dtTable = $('#dt_completeNEW').DataTable();
                                dtTable.ajax.reload( null, false );
        						$('#loader_area').hide();
        						CloseModalBox();




                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                }
            }
            return false;
        });
    });
</script>