<style type="text/css">
    .span-danger {
        color: red;
    }
</style>
<section class="content-header">
    <h1>Daftar Penugasan Pemeriksaan LHKPN</h1><?php echo $breadcrumb; ?>
    <?php echo $user_roles; ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/eaudit/penugasan/index">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="box-body">
                               <!-- <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tahun Lapor :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="Pilih Tahun" value="<?php echo @$CARI['TAHUN']; ?>" id="CARI_TAHUN" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Jenis Laporan :</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="CARI[JENIS]">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="1" <?php if (@$CARI['JENIS'] == 1) { echo 'selected'; }; ?>>Khusus, Calon</option>
                                                    <option value="2" <?php if (@$CARI['JENIS'] == 2) { echo 'selected'; }; ?>>Khusus, Awal menjabat</option>
                                                    <option value="3" <?php if (@$CARI['JENIS'] == 3) { echo 'selected'; }; ?>>Khusus, Akhir menjabat</option>
                                                    <option value="4" <?php if (@$CARI['JENIS'] == 4) { echo 'selected'; }; ?>>Periodik tahunan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Status Penugasan :</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="CARI[STATUS_PENUGASAN]">
                                                    <option value="0" <?php if (@$CARI['STATUS_PENUGASAN'] == 0) { echo 'selected'; }; ?>>-- Semua --</option>
                                                    <option value="1" <?php if (@$CARI['STATUS_PENUGASAN'] == 1) { echo 'selected'; }; ?>>Belum Ditugaskan</option>
                                                    <option value="2" <?php if (@$CARI['STATUS_PENUGASAN'] == 2) { echo 'selected'; }; ?>>Sudah Ditugaskan</option>
                                                    <option value="3" <?php if (@$CARI['STATUS_PENUGASAN'] == 3) { echo 'selected'; }; ?>>Selesai Pemeriksaan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cari :</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                                                    <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <!-- <strong style="display:none;"><span id="jml">0</span> Dokumen yang dipilih!</strong> -->
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50"><input type="checkbox" onClick="chk_all(this);" /></th>
                                <th width="30">No.</th>
                                <th>Nama / No.Agenda</th>
    							<th>Jabatan</th>
    							<th>Lembaga</th>
    							<th>Tanggal Pengumuman*</th>
    							<th>Status Penugasan</th>
    							<th>Status Pemeriksaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_rows) { ?>
                            <?php
                            // echo $offset;
                                $i = 0 + $offset;
                                $start = $i + 1;
                                $aId = @explode(',', $id);
                                echo $id;
                                foreach ($items as $item) {
                                    $agenda = date('Y', strtotime($item->tgl_kirim_final)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                            ?>
                            <tr>
                                <td align="center"> <?php echo (in_array($item->ID_LHKPN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="' . $item->ID_LHKPN . '" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="' . $item->ID_LHKPN . '" onclick="chk(this);" />') ?></td>
                                <td><?php echo ++$i; ?>.</td>
                                <td class="agenda"><?php echo '<b>'.$item->NAMA.'</b><br>'.$agenda; ?>
                                </td>
                                <td><?php echo $item->NAMA_JABATAN; ?></td>
                                <td><?php echo $item->INST_NAMA; ?></td>
                                <td>-<?php //echo date('d/m/Y', strtotime($item->tgl_kirim_final)); ?>
                                </td>
                                <td class="hidden-xs hidden-sm">-</td>
                                <td >-</td>
                            </tr>
                            <?php
                            $end = $i;
                            }
                            ?>
                            <?php } else { ?>
                            <tr id="not-found">
                                <td colspan="8" align="center"><strong>Belum ada data</strong></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <?php echo $content_list; ?>
                                <?php echo $content_paging; ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $content_js; ?>
                    <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="f_bast();" title="Tambahkan ke Daftar Penugasan"><i class="fa fa-plus-square"></i> Daftar Penugasan</button>

                    <table id="con-bast" class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th width="30">No.</th>
                                <th width="100px">NIK</th>
                                <th>Nama</th>
                                <th width="200px" align="center">No Agenda</th>
                                <th width="100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            if (isset($item_selected)) {
                                foreach (@$item_selected as $row):?>
                            <tr>
                                <td align="center"><?php echo $i; ?></td>
                                <td><?php var_dump($row); ?></td>
                                <td><?php echo $row->NAMA ?></td>
                                <td><a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($row->ID_ID_LHKPN), 5, 8); ?>" onclick="return tracking(this)" class="btn-tracking"><?php echo date('Y', strtotime($row->tgl_kirim_final)) . '/' . ($row->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $row->NIK . '/' . $row->ID_ID_LHKPN; ?></a></td>
                                <td align="center"><button type="button" class="btn btn-default" onClick="f_batal(this);" data-id="<?php echo $row->ID_ID_LHKPN ?>" >Hapus</button> </td>
                            </tr>
                            <?php $i++;
                            endforeach;
                            } ?>
                            <?php if (@$id == '') { ?>
                            <tr id="not-found">
                                <td colspan="5" align="center"><strong>Belum ada data</strong></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <br />
                    <br />

                    <form class="form-horizontal" method="post" name="ajaxFormBAP" id="ajaxFormBAP" action="index.php/eano/announ/save">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="iPemeriksa">Pemeriksa <span class="span-danger">*</span> :</label>
                            <div class="col-sm-4">
                                <select multiple id="iNamaPemeriksa" class="form-control" name="iNamaPemeriksa">
                                  <?php foreach ($users_admin as $ua): ?>
                                    <option value="<?php echo $ua->ID_USER; ?>"><?php echo $ua->NAMA.' ('.$ua->ROLE.')'; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="iNomorSuratTugas">Nomor Surat Tugas <span class="span-danger">*</span> :</label>
                            <div class="col-sm-4">
                                <input class="form-control" type="text" id="iNomorSuratTugas" name="iNomorSuratTugas" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="iTanggalPenugasanAwal">Tanggal Penugasan <span class="span-danger">*</span> :</label>
                            <div class="col-sm-4">
                                <input class="form-control date" type="text" id="iTanggalPenugasanAwal" name="iTanggalPenugasanAwal" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="iTanggalPenugasanAkhir">Tanggal berakhir penugasan <span class="span-danger">*</span> :</label>
                            <div class="col-sm-4">
                                <input class="form-control date" type="text" id="iTanggalPenugasanAkhir" name="iTanggalPenugasanAkhir" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="btn-action"></label>
                            <div class="col-sm-4">
                                <button class="btn btn-danger" type="button" name="btn-save-tgs" id="btn-save-tgs" title="Proses dan simpan penugasan">Simpan</button>
                                <button class="btn btn-info" type="button" name="btn-batal-tgs" id="btn-batal-tgs" title="Reset form penugasan">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $('[data-toggle="popover"]').popover({
    });
    $('a.over').css('cursor', 'pointer');
    $('a.over').on('click', function (e) {
        $('a.over').not(this).popover('hide');
    });

    var idChk = [];
    var jml = 0;
    function getPn(ele) {
        var url = $(ele).attr('href');
        $.get(url, function (html) {
            OpenModalBox('Detail PN', html, '', 'standart');
        });

        return false;
    }

    function chk_all(ele)
    {
        if ($(ele).is(':checked')) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }

        $('.chk:visible').each(function () {
            chk(this);
        });
    }

    function chk(ele) {
        var val = $(ele).val();
        var index = idChk.indexOf(val);    // <-- Not supported in <IE9
        if (index !== -1) {
            idChk.splice(index, 1);
        }else{
            idChk.push(val);
        }

    }

    function count() {
        var jml = parseInt($('.chk:checked').length);
        $('#jml').text(jml);
        if (jml > 0) {
            $('#con-bast #not-found').hide();
        } else {
            $('#con-bast #not-found').show();
        }

        var tmo = $('input[name="id"]').val(idChk.join());
    }

    function f_bast() {
        $('.chk').each(function () {
            var val = $(this).val();
            if ($(this).is(':checked') && $(this).is(':visible')) {
                jml++;
                $(this).hide();
                var table = $(this).closest('tr');
                var nik = $('.nik', table).text();
                var nama = $('.nama', table).text();
                var agenda = $('.agenda', table).text();
                var lhkpn = $('.lhkpn', table).text();
                var lhkpnori = $('.lhkpn', table).text();
                var tgl_lapor = $('.tgl_lapor', table).text();
                var jenis_laporan = $('.jenis_laporan', table).text();

                $('#con-bast tbody').append('<tr><td align="center">' + jml + '</td><td>' + nik + '</td><td>' + nama + '</td><td><a href="index.php/efill/lhkpnoffline/tracking/show/' + lhkpn + '" class="btn-tracking" onclick="return tracking(this);">' + agenda + '</a></td><td align="center"><button class="btn btn-default" data-id="' + val + '" type="button" onClick="f_batal(this);">Hapus</button></td></tr>');
//                $('#con-bast tbody').append('<tr><td align="center">' + jml + '</td><td>' + nik + '</td><td>' + nama + '</td><td><a href="index.php/efill/lhkpnoffline/tracking/show/' + lhkpn + '" class="btn-tracking" onclick="return tracking(this);">' + tgl_lapor + '/' + jenis_laporan + '/' + nik + '/' + lhkpn + '</a></td><td align="center"><button class="btn btn-default" data-id="' + val + '" type="button" onClick="f_batal(this);">Hapus</button></td></tr>');
            }
        })

        count();
    }

    function f_batal(ele) {
        var id = $(ele).attr('data-id');
        var index = idChk.indexOf(id);    // <-- Not supported in <IE9
        if (index !== -1) {
            idChk.splice(index, 1);
        }

        $(ele).closest('tr').remove();
        $('.chk[value="' + id + '"]').show();
        $('.chk[value="' + id + '"]').prop('checked', false);

        count();
    }

    function tracking(ele)
    {
        url = $(ele).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
        });
        return false;
    }

    $(document).ready(function () {
        $('#btn-batal-tgs').click(function () {
            $('#iNamaPemeriksa').select2("val","");
            $('#iNomorSuratTugas').val("");
            $('#iTanggalPenugasanAwal').val("");
            $('#iTanggalPenugasanAkhir').val("");
        });

        $('#iNamaPemeriksa').select2();
        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
        $('.date').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });

        var tmo = $('#ajaxFormCari input[name="id"]').val();
        if (tmo != '') {
            idChk = tmo.split(',');
            jml = idChk.length;
        }

        count();

        $("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('#btn-clear').click(function (event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });

        $('.date-picker').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });

        $('.btn-tracking').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
            });
            return false;
        });


        $('.btnGenPDF').click(function () {
            key = $(this).parents('td').children('.key').val();
            url = $(this).attr('href') + '/' + key;

            $.get('index.php/eano/announ/periksaNaskah/' + key, function (html) {
                $.get('<?php echo base_url() ?>/index.php/efill/lhkpn/entry/' + key + '/view/true', function (home2) {
                    $('#ajaxFormCari').after(
                            '<form method="post" action="' + url
                            + '" id="ajaxFormPrint"></form>');
                    $("#ajaxFormCari").children().clone().appendTo('#ajaxFormPrint');
                    html += '<div class="row"><div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">'
                            + '<iframe src="" width="100%" height="'
                            + ($(window).height() - 140 + 'px')
                            + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe></div>';
                    html += '<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">' + home2 + '</div></div>';
                    OpenModalBox('', html, '', 'large');
                    $('#ajaxFormPrint').attr('target', 'iframeCetak');
                    $('#ajaxFormPrint').submit();
                    $('#ajaxFormPrint').remove();
                });
            });

        });

        $('#ajaxFormBAP').submit(function () {
            $('#ajaxFormBAP input[name="id"]').val(idChk.join());
            if (idChk.length != 0) {
                var formObj = $(this);
                var formURL = formObj.attr("action");
                var formData = new FormData(this);
                $.ajax({
                    url: formURL,
                    type: 'POST',
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data, textStatus, jqXHR) {
                        msg = {
                            success: 'Data Berhasil Disimpan!',
                            error: 'Data Gagal Disimpan!'
                        };
                        if (data == 0) {
                            alertify.error(msg.error);
                        } else {
                            alertify.success(msg.success);
                        }
                        url = 'index.php/eano/announ/index/publikasi';
                        ng.LoadAjaxContent(url);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alertify.error(msg.error + "\n" + jqXHR.statusText);
                    }
                });
                return false;
            } else {
                alertify.error('Silahkan Pilih Penerimaan');
                return false;
            }
            e.preventDefault(); //Prevent Default action.
        });
    });
</script>
