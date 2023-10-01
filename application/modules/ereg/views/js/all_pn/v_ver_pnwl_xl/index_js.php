<?php
$init_instansi_slc = isset($in_index_js_without_init_instansi_slc) ? $in_index_js_without_init_instansi_slc : TRUE;
?>

<script type="text/javascript">

//    $(function () {
//        $('#dt_complete, #dt_complete1, #dt_complete2').dataTable({
//        });
//    });
    var gtblDaftarPenambahan = null, gtblDaftarIndividualPerubahan = null, gtblDaftarIndividualNonAktive = null;

    var editPerubahanJabatan = function (self) {
        var url = $(self).attr('href');
        $('#loader_area').show();
        $.post(url, function (html) {
            OpenModalBox('Edit Verifikasi PN/WL', html, '', 'standart');
        });
        return false;
    }

    $(document).ready(function () {

<?php if ($instansi_found) { ?>
            ExecDatasss();
<?php } ?>
        cekTombol('chk1');
        cekTombol('chk2');
        cekTombol('chk3');
        $('[data-toggle="tooltip"]').tooltip();
        $('.over').popover();
        $('.over').on('click', function (e) {
            $('.over').not(this).popover('hide');
        });
        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Edit Verifikasi PN/WL', html, '', 'standart');
            });
            return false;
        });
        $("#btn-add-exc").click(function () {
            url = $(this).attr('href');



<?php if (!$instansi_found): ?>

                var ins = $("#CARI_INSTANSI").val();

                if (ins.length <= 0) {
                    alertify.error("Pilih Instansi Terlebih dahulu !!");

                    $("#CARI_INSTANSI").focus();

                    return false;
                }

                url += "?CARI[INSTANSI]=" + ins;
<?php endif; ?>

            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Penambahan/Perubahan Data PN/WL Melalui File Excel', html, '', 'large');
            });



            return false;
        });




<?php
if ($init_instansi_slc):
    ?>
            $('input[name="CARI[INSTANSI]"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getlembaga') ?>",
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getlembaga') ?>/" + id,
                                {
                                    dataType: "json"
                                }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            }).on("change", function (e) {

                $("#spanTabNonAktif").html(" (Loading ..)");

                //LoadDtTablePenambahan();
                //LoadDtTablePerubahan();
                //LoadDtTableNonAktive();
            });

    <?php
endif;
?>

    });

    var msg = {
        success: 'Data Berhasil Disimpan!',
        error: 'NIK telah terdaftar pada basis data. Lakukan pembatalan.',
        error1: 'Data Gagal Disimpan!'
    };

    function saveUbahJabPNWL(idpn, idinst) {
        confirm('Apakah Anda Yakin ?', function () {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_ver_pn/ajax_update_jabpnwl/' + idpn + '?idinst=' + idinst;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function (htmldata) {

                    if (htmldata.status == 0) {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {
                        alertify.success(msg.success);
//                                $('#refup').replaceWith($('#refup', $(htmldata)));
//                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
//                                $('#loader_area').hide();
                        $.get(location.href.split('#')[1], function (html) {
                            ExecDatasss();
                            CloseModalBox();

                            $('#loader_area').hide();
                        })
                    }

                },
                cache: false,
                contentType: false,
                processData: false

            });
        });
    }

    function savePenambahanPNWL(idpn) {
        confirm('Apakah Anda Yakin ?', function () {
            $('#loader_area').show();
            var server_url = '<?php echo base_url(); ?>index.php/ereg/All_ver_pn/ajax_save_add_pnwl/' + idpn, url = 'index.php/ereg/All_ver_pn';
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function (htmldata) {
                    if (htmldata == 0) {
                        alert(msg.error);
                        $('#loader_area').hide();
                    } else {
                        alertify.success(msg.success);
//                                $('#refadd').replaceWith($('#refadd', $(htmldata)));
//                                $('#tabtwo').replaceWith($('#tabtwo', $(htmldata)));
//                                $('#loader_area').hide();
                        $.get(location.href.split('#')[1], function (html) {
                            ExecDatasss();
                            CloseModalBox();

                            $('#loader_area').hide();
                        });
                    }

                },
                cache: false,
                contentType: false,
                processData: false

            });
        });
    }

    function cancelPenambahan(occ) {
        confirm('Apakah Anda Yakin ?', function () {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_ver_pn/cancelUpld/';
            $.ajax({
                url: server_url,
                method: "POST",
                dataType: 'text',
                data: {"coo": occ},
                success: function (htmldata) {

                    if (htmldata == '0') {
                        alertify.error(msg.error);
                    } else {

                        alertify.success(msg.success);
                        ExecDatasss();
                    }
                    $('#loader_area').hide();
                }
            });
        });
    }

    function cancelVerPN(occ, st) {
        confirm('Apakah Anda Yakin ?', function () {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_ver_pn/cancelVerPN/' + idpn;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function (htmldata) {

                    if (htmldata.status == 0) {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {
//                              $('#allpage').replaceWith($('#allpage', $(htmldata)));
//                            if (st === 1) {
//                                $('#refup').replaceWith($('#refup', $(htmldata)));
//                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
//                            } else if (st === 2) {
//                                $('#refadd').replaceWith($('#refadd', $(htmldata)));
//                                $('#tabtwo').replaceWith($('#tabtwo', $(htmldata)));
//                            }
                        alertify.success(msg.success);

                        $.get(location.href.split('#')[1], function (html) {
                            ExecDatasss();
                            CloseModalBox();

                            $('#loader_area').hide();
                        })
                    }

                },
                cache: false,
                contentType: false,
                processData: false

            });
        });
    }

    function ApproveNonActPN(idpn) {
        confirm('Apakah Anda Yakin ?', function () {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_ver_pn/ApproveNonActPN/' + idpn;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function (htmldata) {

                    if (htmldata.status == 0) {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {
                        $.get(location.href.split('#')[1], function (html) {
                            ExecDatasss();
                            CloseModalBox();

                            $('#loader_area').hide();
                        })
                    }

                },
                cache: false,
                contentType: false,
                processData: false

            });
        });
    }

    function cancel_nonactxls(idpn, idjb, nm) {
        var captionConfirmation = 'Apakah Anda yakin akan membatalkan update data atas nama ' + nm + ' ?';
        confirm(captionConfirmation, function () {
            $('#loader_area').show();
            var iduk = $('#CARI_UNIT_KERJA').val();
            var server_url = 'index.php/ereg/All_ver_pn/cancel_nonactxls/' + idpn + '/' + idjb;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {
                    "idtemp": idpn,
                    "iduk": iduk
                },
                success: function (htmldata) {
//                    if (htmldata.status == 0) {
//                        alertify.error(msg.error);
//                        $('#loader_area').hide();
//                    } else {
//                }
                    CloseModalBox();
                    $('#loader_area').hide();
                    if (htmldata == '1') {
                        $.get(location.href.split('#')[1], function (html) {
                            ExecDatasss();                            
                        })
                    } else {
                        alert("Pilih Unit Kerja terlebih dahulu");
                    }

                },
                cache: false

            });
        });
    }

    var msgmail = {
        success: 'Email Berhasil Dikirim!',
        error: 'Email Gagal Dikirim!'
    };

    function KirimEmail(idpn) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_ver_pn/kirim_email/';
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function (htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msgmail.error);
                    $('#loader_area').hide();
                } else {
                    alertify.success(msgmail.success);
                    $('#loader_area').hide();

                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    }

    function chk_all(ele) {
        if ($(ele).is(':checked')) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }
        cekTombol('chk1');
    }

    function chk(ele) {
        var val = $(ele).val();
        //idChk.push(val);
        cekTombol('chk1');
    }
    /*       
     function cekTombol(chkboxName) {
     var checkboxes = document.getElementsByName(chkboxName);
     var checkboxesChecked = [];
     for (var i=0; i<checkboxes.length; i++) {
     if (checkboxes[i].checked) {
     checkboxesChecked.push(checkboxes[i]);
     }
     }
     // Return the array if it is non-empty, or null
     if (checkboxesChecked.length === 0 )
     {
     if(chkboxName ==='chk1'){
     $("#tombolverifperubahan").empty();}
     }
     else{
     if(chkboxName ==='chk1'){
     $("#tombolverifperubahan").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"verif_all();\"title=\"Verifikasi\"><i class=\"fa fa-plus-square\"></i> Verifikasi Semua</button>");
     }
     }
     }	
     */
    function cekTombol(chkboxName) {
        var checkboxes = document.getElementsByName(chkboxName);
        var checkboxesChecked = [];
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                checkboxesChecked.push(checkboxes[i]);
            }
        }
        // Return the array if it is non-empty, or null
        if (checkboxesChecked.length === 0)
        {
            if (chkboxName === 'chk1') {
                // $("#veriftommbolnwl").empty();
                // $("#canceltommbolnwl").empty();
                $("#tombolverifperubahan").empty();
                $("#tombolcancelperubahan").empty();
                // $("#veriftombolpenambahan").empty();
                // $("#canceltombolpenambahan").empty();
            } else if (chkboxName === 'chk2') {
                $("#veriftommbolnwl").empty();
                $("#canceltommbolnwl").empty();
                // $("#tombolverifperubahan").empty();
                // $("#tombolcancelperubahan").empty();
                // $("#veriftombolpenambahan").empty();
                // $("#canceltombolpenambahan").empty();
            } else {
                // $("#veriftommbolnwl").empty();
                // $("#canceltommbolnwl").empty();
                // $("#tombolverifperubahan").empty();
                // $("#tombolcancelperubahan").empty();
                $("#veriftombolpenambahan").empty();
                $("#canceltombolpenambahan").empty();
            }
        } else {
            if (chkboxName === 'chk1') {
                $("#tombolverifperubahan").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"verif_all();\"title=\"Verifikasi\"><i class=\"fa fa-plus-square\"></i> Verifikasi Semua</button>");
                $("#tombolcancelperubahan").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"cancel_all();\"title=\"Cancel Verifikasi\"><i class=\"fa fa-minus-square\"></i> Cancel Verifikasi Semua</button>");
            } else if (chkboxName === 'chk2') {
                $("#veriftommbolnwl").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"verifNonWajibLapor_all();\"title=\"Verifikasi\"><i class=\"fa fa-plus-square\"></i> Verifikasi Semua</button>");
                $("#canceltommbolnwl").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"cancelNonWajibLapor_all();\"title=\"Cancel Verifikasi\"><i class=\"fa fa-minus-square\"></i> Cancel Verifikasi Semua</button>");
            } else if (chkboxName === 'chk3') {
                $("#veriftombolpenambahan").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"verifPenambahan_all();\"title=\"Verifikasi\"><i class=\"fa fa-plus-square\"></i> Verifikasi Semua</button>");
                $("#canceltombolpenambahan").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"cancelPenambahan_all();\"title=\"Cancel Verifikasi\"><i class=\"fa fa-minus-square\"></i> Cancel Verifikasi Semua</button>");
            }
        }
    }

    function verif_all() {
        confirm('Apakah Anda Yakin Memverifikasi Semua Data Yang Dipilih?', function () {
            $('.chk').each(function () {
                var val = $(this).val();
                var res = val.split(",");
                if ($(this).is(':checked')) {
                    saveUbahJabPNWL_veriifAll(res[0], res[1]);
                }
            })
            count();
        });
    }
    
    function cancel_all() {
        confirm('Apakah Anda Yakin Membatalkan Semua Data Yang Dipilih?', function () {
            $('.chk').each(function () {
                var val = $(this).val();
                var res = val.split(",");
                if ($(this).is(':checked')) {
                    cancelPenambahan_veriifAll(res[0]);
                }
            })
            count();
        });
    }
    
    function verifNonWajibLapor_all() {
        confirm('Apakah Anda Yakin Memverifikasi Semua Data Yang Dipilih?', function () {
            $('.chk2').each(function () {
                var val = $(this).val();
                var res = val.split(",");
                if ($(this).is(':checked')) {

                    ApproveNonActPN_All(res[0]);
                }
            })
            count();
        });
    }
    function cancelNonWajibLapor_all() {
        confirm('Apakah Anda Yakin Membatalkan Semua Data Yang Dipilih?', function () {
            $('.chk2').each(function () {
                var val = $(this).val();
                var res = val.split(",");
                if ($(this).is(':checked')) {

                    CancelNonActPN_All(res[1],res[0]);
                }
            })
            count();
        });
    }

    function verifPenambahan_all() {
        confirm('Apakah Anda Yakin Memverifikasi Semua Data Yang Dipilih?', function () {
            $('.chk3').each(function () {
                var val = $(this).val();
                var res = val.split(",");
                if ($(this).is(':checked')) {

                    savePenambahanPNWL_All(res[0]);
                }
            })
            count();
        });
    }

    function cancelPenambahan_all() {
        confirm('Apakah Anda Yakin Membatalkan Semua Data Yang Dipilih?', function () {
            $('.chk3').each(function () {
                var val = $(this).val();
                var res = val.split(",");
                if ($(this).is(':checked')) {

                    cancelPenambahanPNWL_All(res[0]);
                }
            })
            count();
        });
    }
    
    function chkPenambahan_all(ele) {
        if ($(ele).is(':checked')) {
            $('.chk3').prop('checked', true);
        } else {
            $('.chk3').prop('checked', false);
        }
        cekTombol('chk3');
    }

    function chkPenambahan(ele) {
        var val = $(ele).val();
        cekTombol('chk3');
    }

    function chkNonWajibLapor_all(ele) {
        if ($(ele).is(':checked')) {
            $('.chk2').prop('checked', true);
        } else {
            $('.chk2').prop('checked', false);
        }
        cekTombol('chk2');
    }

    function chkNonWajibLapor(ele) {
        var val = $(ele).val();
        cekTombol('chk2');
    }

    function saveUbahJabPNWL_veriifAll(idpn, idinst) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_ver_pn/ajax_update_jabpnwl/' + idpn + '?idinst=' + idinst;
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function (htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    alertify.success(msg.success);
                    //                                $('#refup').replaceWith($('#refup', $(htmldata)));
                    //                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
                    //                                $('#loader_area').hide();
                    $.get(location.href.split('#')[1], function (html) {
                        ExecDatasss();
                        CloseModalBox();

                        $('#loader_area').hide();
                        $("#veriftommbolnwl").empty();
                        $("#canceltommbolnwl").empty();
                        $("#tombolcancelperubahan").empty();
                        $("#tombolverifperubahan").empty();
                        $("#veriftombolpenambahan").empty();
                        $("#canceltombolpenambahan").empty();
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    }
    
    function savePenambahanPNWL_All(occ) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_ver_pn/ajax_save_add_pnwl/' + occ,
        $.ajax({
            url: server_url,
            method: "POST",
            dataType: 'text',
            data: {"idtemp": occ},
            success: function (htmldata) {
                if (htmldata == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    alertify.success(msg.success);
                    //                                $('#refup').replaceWith($('#refup', $(htmldata)));
                    //                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
                    //                                $('#loader_area').hide();
                    $.get(location.href.split('#')[1], function (html) {
                        ExecDatasss();
                        CloseModalBox();

                        $('#loader_area').hide();
                        $("#veriftommbolnwl").empty();
                        $("#canceltommbolnwl").empty();
                        $("#tombolcancelperubahan").empty();
                        $("#tombolverifperubahan").empty();
                        $("#veriftombolpenambahan").empty();
                        $("#canceltombolpenambahan").empty();
                    })
                }

            },
            cache: false,

        });
    }

    // function cancelPenambahan_veriifAll(occ) {
    function cancelPenambahanPNWL_All(occ) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_ver_pn/cancelUpld/';
        $.ajax({
            url: server_url,
            method: "POST",
            dataType: 'text',
            data: {"coo": occ},
            success: function (htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    alertify.success(msg.success);
                    //                                $('#refup').replaceWith($('#refup', $(htmldata)));
                    //                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
                    //                                $('#loader_area').hide();
                    $.get(location.href.split('#')[1], function (html) {
                        ExecDatasss();
                        CloseModalBox();

                        $('#loader_area').hide();
                        $("#veriftommbolnwl").empty();
                        $("#canceltommbolnwl").empty();
                        $("#tombolcancelperubahan").empty();
                        $("#tombolverifperubahan").empty();
                        $("#veriftombolpenambahan").empty();
                        $("#canceltombolpenambahan").empty();
                    })
                }

            },
            cache: false,

        });
    }

    function ApproveNonActPN_All(idpn) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_ver_pn/ApproveNonActPN/' + idpn;
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function (htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    $.get(location.href.split('#')[1], function (html) {
                        ExecDatasss();
                        CloseModalBox();

                        $('#loader_area').hide();
                        $("#veriftommbolnwl").empty();
                        $("#canceltommbolnwl").empty();
                        $("#tombolcancelperubahan").empty();
                        $("#tombolverifperubahan").empty();
                        $("#veriftombolpenambahan").empty();
                        $("#canceltombolpenambahan").empty();
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    }
    
    function CancelNonActPN_All(idpn, idjb) {
        $('#loader_area').show();
        var server_url = 'index.php/ereg/All_ver_pn/cancel_nonactxls/' + idpn + '/' + idjb;
        var iduk = $('#CARI_UNIT_KERJA').val();
        $.ajax({
            url: server_url,
            type: "POST",
            data: {
                "idtemp": idpn,
                "iduk": iduk
            },
            success: function (htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    $.get(location.href.split('#')[1], function (html) {
                        ExecDatasss();
                        CloseModalBox();

                        $('#loader_area').hide();
                        $("#veriftommbolnwl").empty();
                        $("#canceltommbolnwl").empty();
                        $("#tombolcancelperubahan").empty();
                        $("#tombolverifperubahan").empty();
                        $("#veriftombolpenambahan").empty();
                        $("#canceltombolpenambahan").empty();
                    })
                }

            },
            cache: false,

        });
    }

</script>