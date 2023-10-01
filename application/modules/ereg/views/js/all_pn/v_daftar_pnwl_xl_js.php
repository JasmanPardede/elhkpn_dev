<?php
$instansi_found = isset($instansi_found) ? $instansi_found : FALSE;
?>
<script type="text/javascript">

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

        $("#btn-add-exc").click(function (e) {

            e.preventDefault();

            url = $(this).attr('href');

<?php if ($isAdminInstansi || $isAdminUnitKerja): ?>
                var additional_cap = '_HDD';
<?php else: ?>
                var additional_cap = '';
<?php endif; ?>

            var ins = $("#CARI_INSTANSI" + additional_cap).val();

<?php if (!$instansi_found): ?>

                if (ins.length <= 0) {
                    alertify.error("Pilih Instansi Terlebih dahulu !!");

                    $("#CARI_INSTANSI" + additional_cap).focus();

                    return false;
                }

<?php endif; ?>

            url += "?CARI[INSTANSI]=" + ins;

            var cbuk = $("#CARI_UNIT_KERJA").val();

            if (cbuk != '') {
                url += "&CARI[UNIT_KERJA]=" + cbuk;
            } else {
                alertify.error("Pilih Unit Kerja Terlebih dahulu !!");

                $("#CARI_UNIT_KERJA").focus();

                return false;
            }

            var wl_thn = $("#CARI_TAHUN_WL").val();
            if (wl_thn != '') {
                url += "&CARI[TAHUN_WL]=" + wl_thn;
            }else{
                alertify.error("Pilih WL Tahun Terlebih dahulu !!");

                $("#CARI_TAHUN_WL").focus();

                return false;
            }


            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Penambahan/Perubahan Data PN/WL Melalui File Excel', html, '', 'large');
            });

            return false;
        });

    });





    var msg = {
        success: 'Data Berhasil Disimpan!',
        error: 'Data Gagal Disimpan!'
    };

    function DeleteTempXls(idpn) {
        confirm('Apakah Anda Yakin ?', function () {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_ver_pn/DeleteTempXls/' + idpn;
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
                        $.get(location.href.split('#')[1], function (html) {
                            $('#ajax-content').html(html);
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
            server_url = 'index.php/ereg/All_ver_pn/ajax_save_add_pnwl/' + idpn;
            url = 'index.php/ereg/All_ver_pn';
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
                        $('#refadd').replaceWith($('#refadd', $(htmldata)));
                        $('#loader_area').hide();
                    }

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    }

    function cancelVerPN(idpn, st, nm) {
//                alert(st);
        var captionConfirmation = 'Apakah Anda yakin akan membatalkan update data atas nama ' + nm + ' ?';
        confirm(captionConfirmation, function () {
            $('#loader_area').show();
            var iduk = $('#CARI_UNIT_KERJA').val();
            var server_url = 'index.php/ereg/All_ver_pn/cancelVerPN/' + idpn + '/' + st;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {
                    "idtemp": idpn,
                    "iduk": iduk
                },
                success: function (htmldata) {

                    CloseModalBox();
                    $('#loader_area').hide();
                    $('body #loader_area').hide();
                    if (htmldata == '1') {
                        $.get(location.href.split('#')[1], function (html) {
                            ExecDatasss();
                        })
                    } else {
                        alert("Pilih Unit Kerja terlebih dahulu");
                    }

                },
                cache: false,

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
                        $('#loader_area').hide();
                    } else {

                        alertify.success(msg.success);
                        reloadTableDoubleTime(gtblDaftarPenambahan);
                        reloadTableDoubleTime(gtblDaftarIndividualPerubahan);
                    }
                    $('#loader_area').hide();
                }
            });
        });
    }



</script>
