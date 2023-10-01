<?php
/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/efill/penerimaan
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa <?php echo $icon; ?>"></i>
        <?php echo $title; ?>
    </h1>
    <?php echo $breadcrumb; ?>
</section>


<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <button class="btn btn-sm btn-primary btn-add" id="btn-add-suratkeluar" href="<?php echo site_url('eaudit/korespondensi/addsuratkeluar'); ?>">
                            <i class="fa fa-envelope"></i> Tambah Surat Keluar
                        </button>
                    </div> -->
                    <!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <button class="btn btn-sm btn-primary" id="btn-add-suratmasuk" href="<?php echo site_url('eaudit/korespondensi/addsuratmasuk'); ?>">
                            <i class="fa fa-envelope"></i> Tambah Surat Masuk
                        </button>
                    </div> -->
                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label">Nomor Surat<span class="red-label">*</span>:</label>
                        <div id="inpCariNomorSuratPlaceHolder" class="col-sm-7">
                              <input type='text' class="input-sm form-control" name='CARI[NOMOR_SURAT]' style="border:none;padding:6px 0px;" id='CARI_NOMOR_SURAT' value='' placeholder="-- Pilih Jenis Instasi --">
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cari Nama PN:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">

                        </div>
                        <div class="form-group">
                            <div class="col-col-sm-3 col-sm-offset-4-2">
                                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-striped table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th width="30">Nomor</th>
                                <th>Tanggal</th>
                                <th>Instansi</th>
                                <th>Hal</th>
                                <th>Nama PN</th>
                                <th>Status</th>
                                <th>Pemenuhan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        <?php if ($total_rows) { ?>
                            <div class="col-sm-6">
                                <div class="dataTables_info" id="datatable-1_info">
                                    Showing
                                    <?php echo $start; ?>
                                    to
                                    <?php echo $end; ?>
                                    of
                                    <?php echo $total_rows; ?>
                                    entries
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-sm-6 text-right">
                            <div class="dataTables_paginate paging_bootstrap">
                                <?php echo $pagination; ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<script language="javascript">
    var idChk = [];
    var jml = 0;

    function chk_all(ele)
    {
        if ($(ele).is(':checked')) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }
    }

    // var callFormAdd = function (i) {
    //
    //     var data = isDefined(i) ? {impxllhkpn: i} : undefined;
    //     var url = $("#btn-add").attr("href");
    //
    //     $.post(url, data, function (html) {
    //         OpenModalBox('Tambah Surat Keluar', html, '', 'large');
    //     });
    //     return false;
    // };

    var callFormAddSuratMasuk = function (i) {

        var data = isDefined(i) ? {impxllhkpn: i} : undefined;
        var url = $("#btn-add-suratmasuk").attr("href");

        $.post(url, data, function (html) {
            OpenModalBox('Tambah Surat Masuk', html, '', 'large');
        });
        return false;
    };

    var callFormAddSuratKeluar = function (i) {

        var data = isDefined(i) ? {impxllhkpn: i} : undefined;
        var url = $("#btn-add-suratkeluar").attr("href");

        $.post(url, data, function (html) {
            OpenModalBox('Tambah Surat Keluar', html, '', 'large');
        });

        return false;
    };

    $(document).ready(function () {
        $('.btn-edit').click(function (e) {
            $('#loader_area').show();
            var self = this;
            setTimeout(function () {
                var url = $(self).attr('href');
                ng.LoadAjaxContent(url);
            }, 500);

            return false;
        });

        $('.btn-hapus').click(function () {
            onButton.delete(this);
        });


        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            url = url.replace('<?= base_url(); ?>', '');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });

        var tmo = $('#ajaxFormCari input[name="id"]').val();
        if (tmo != '') {
            idChk = tmo.split(',');
        }

        $("#ajaxFormAdd").submit(function (e) {
            var url = $(this).attr('action');
            $('#ajaxFormAdd input[name="id"]').val(idChk.join());

            if (idChk.length != 0) {
                $.post($(this).attr('action'), $(this).serializeArray(), function (data) {
                    alertify.success('Data berhasil di simpan!');
                    html = '<iframe src="' + data + '" width="100%" height="500px"></iframe>';
                    f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
                    OpenModalBox('Print BAST', html, f_close, 'large');
                    url = 'index.php/efill/lhkpnoffline/index/penerimaan/';
                    ng.LoadAjaxContent(url);
                });
            } else {
                alertify.error('Tidak ada data yg dipilih!');
            }
            return false;
        });

        $('.date-picker').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        // $('.petugas').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?= base_url('index.php/share/reff/getUser/' . $role) ?>",
        //         dataType: 'json',
        //         quietMillis: 250,
        //         data: function (term, page) {
        //             return {
        //                 q: term
        //             };
        //         },
        //         results: function (data, page) {
        //             return {results: data.item};
        //         },
        //         cache: true
        //     },
        //     initSelection: function (element, callback) {
        //         var id = $(element).val();
        //         if (id !== "") {
        //             $.ajax("<?= base_url('index.php/share/reff/getUser/' . $role) ?>/" + id, {
        //                 dataType: "json"
        //             }).done(function (data) {
        //                 callback(data[0]);
        //             });
        //         }
        //     },
        //     formatResult: function (state) {
        //         if (state.id == '0') {
        //             return '-- Pilih Koord CS dahulu --';
        //         } else {
        //             return '<strong>' + state.role + '</strong> : ' + state.name;
        //         }
        //     },
        //     formatSelection: function (state) {
        //         if (state.id == '0') {
        //             return '-- Pilih Koord CS dahulu --';
        //         } else {
        //             return '<strong>' + state.role + '</strong> : ' + state.name;
        //         }
        //     }
        // });

        $(".breadcrumb").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });
        $("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });
        $(".btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Penerimaan', html, '', 'standart');
            });
            return false;
        })
        $("#btn-add-suratkeluar").click(function () {
            return callFormAddSuratKeluar();
        });
        $("#btn-add-suratmasuk").click(function () {
            return callFormAddSuratMasuk();
        });

        $(".btn-add-with-id").click(function () {
            return callFormAdd($(this).attr("impxllhkpn"));
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Penerimaan', html, '', 'standart');
            });
            return false;
        });
        $('.btn-print').click(function (e) {
            url = $(this).attr('href');
            html = '<iframe src="' + url + '" width="100%" height="500px"></iframe>';
            f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
            OpenModalBox('Print Penerimaan', html, f_close, 'large');
            return false;
        });
        $('#btnPrintPDF').click(function () {
            var url = '<?php echo $linkCetak; ?>/pdf';
            ng.exportTo('pdf', url, 'Cetak <?php echo $titleCetak; ?>');
        });
        $('#btnPrintEXCEL').click(function () {
            var url = '<?php echo $linkCetak; ?>/excel';
            ng.exportTo('excel', url);
        });
        $('#btnPrintWORD').click(function () {
            var url = '<?php echo $linkCetak; ?>/word';
            ng.exportTo('word', url);
        });
    });

    var redirectToViewUpload = function (i) {
        CloseModalBox2();
        CloseModalBox();

        var url = base_url.replace("http:", "").replace("https:", "") + 'index.php#index.php/efill/lhkpnoffline_verifikasi/view_uploaded/' + i;

        window.location.href = url;
    }

    // function resend_lp2(param) {
    //     confirm("Apakah anda akan mengirimkan Lembar Penyerahan melalui email ?", function () {
    //         $('#loader_area').show();
    //         $.ajax({
    //             url: '<?php echo base_url();?>efill/lhkpnoffline_send/resend_lp/'+param,
    //             dataType: 'json',
    //             success: function (data) {
    //                 $('#loader_area').hide();
    //                 alert(data.msg);
    //             }
    //         });
    //
    //     }, "Konfirmasi Kirim Email", undefined, "YA", "TIDAK");
    // };
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>
