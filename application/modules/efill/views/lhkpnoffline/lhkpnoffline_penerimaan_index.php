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
        <small><?php echo $title; ?> </small>
    </h1>
    <?php echo $breadcrumb; ?>
</section>


<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <button class="btn btn-sm btn-primary" id="btn-add" href="<?php echo site_url('efill/lhkpnoffline/add'); ?>">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                    </div>

                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/efill/lhkpnoffline/index/penerimaan/">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="box-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Bulan Terima :</label>
                                        <div class="col-sm-7">
                                            <select name="CARI[BULAN]" id="CARI_BULAN" class="form-control input-sm">
                                                <option value="99">-- Pilih Bulan --</option>
                                                <?php
                                                $aBulan = [0, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];
                                                for ($i = 1; $i < count($aBulan); $i++) {
                                                    if (!empty($CARI['BULAN'])) {
                                                        $selected = $i == @$CARI['BULAN'] ? 'selected' : '';
                                                    } else {
                                                        $selected = $i == date('m') ? 'selected' : '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $aBulan[$i]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun Terima :</label>
                                        <div class="col-sm-7">
                                            <select name="CARI[TAHUN]" id="CARI_TAHUN" class="form-control input-sm">
                                                <option value="99">-- Pilih Tahun --</option>
                                                <?php
                                                for ($i = date('Y'); $i > date('Y') - 10; $i--) {
                                                    if (!empty($CARI['TAHUN'])) {
                                                        $selected = $i == @$CARI['TAHUN'] ? 'selected' : '';
                                                    } else {
                                                        $selected = $i == date('Y') ? 'selected' : '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun Lapor :</label>
                                        <div class="col-sm-7">
                                            <select name="CARI[TAHUN_LAPOR]" id="CARI_TAHUN_LAPOR" class="form-control input-sm">
                                                <option value="99">-- Pilih Tahun --</option>
                                                <?php
                                                for ($i = date('Y'); $i > date('Y') - 10; $i--) {
                                                    if (!empty($CARI['TAHUN_LAPOR'])) {
                                                        $selected = $i == @$CARI['TAHUN_LAPOR'] ? 'selected' : '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">

                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Lembaga :</label>
                                        <div class="col-sm-7">
                                            <input type='text' id='lembaga' style='width: 60%;' name='CARI[LEMBAGA]'  class='select2' value='<?php echo @$CARI['LEMBAGA']; ?>' />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Status :</label>
                                        <div class="col-sm-7">
                                            <select name="CARI[STATUS]" id="STATUS" class="form-control input-sm">
                                                <option value="0"<?php
                                                if (@$CARI['STATUS'] == '0') {
                                                    echo 'selected';
                                                }
                                                ?>>Belum Validasi</option>
                                                <option value="1"<?php
                                                if (@$CARI['STATUS'] == '1') {
                                                    echo 'selected';
                                                }
                                                ?>>Sudah Validasi</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Order By :</label>
                                        <div class="col-sm-7">
                                            <select name="CARI[ORDER]" id="CARI_ORDER" class="form-control input-sm" style="width: 195px; float: left;">
                                                <option <?php echo (@$CARI['ORDER'] == 'NAMA') ? 'selected' : ''; ?> value="NAMA">Nama</option>
                                                <option <?php echo (@$CARI['ORDER'] == 'NIK') ? 'selected' : ''; ?> value="NIK">NIK</option>
                                                <option <?php echo (@$CARI['ORDER'] == 'TGL_TERIMA') ? 'selected' : ''; ?> value="TGL_TERIMA">Tanggal Terima</option>
                                            </select>
                                            <select name="CARI[ORDER_TYPE]" id="CARI_ORDER_TYPE" class="form-control input-sm" style="width: 140px; float: right;">
                                                <option <?php echo (@$CARI['ORDER_TYPE'] == 'ASC') ? 'selected' : ''; ?> value="ASC">ASC</option>
                                                <option <?php echo (@$CARI['ORDER_TYPE'] == 'DESC') ? 'selected' : ''; ?> value="DESC">DESC</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cari :</label>
                                        <div class="col-sm-7">
                                            <select name="CARI[BY]" id="CARI_BY" class="form-control input-sm" style="width: 140px; float: left;">
                                                <option <?php echo (@$CARI['BY'] == 'NAMA') ? 'selected' : ''; ?> value="NAMA">Nama</option>
                                                <option <?php echo (@$CARI['BY'] == 'NIK') ? 'selected' : ''; ?> value="NIK">NIK</option>
                                              <!--  <option <?php echo (@$CARI['BY'] == 'PN') ? 'selected' : ''; ?> value="PN">PN</option>-->
                                                <option <?php echo (@$CARI['BY'] == 'CREATED_BY') ? 'selected' : ''; ?> value="CREATED_BY">PENG-UPLOAD</option>
                                            </select>
                                            <input type="text" style="width: 195px;" class="form-control input-sm pull-right" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT"/>
                                        </div>                                       
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-3 col-sm-offset-4">
                                            <button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari <!-- <i class="fa fa-search"></i> --></button>
                                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT').val(''); $('#CARI_BULAN').val(''); $('#STATUS').val(''); $('#CARI_TAHUN').val(''); $('#CARI_TAHUN_LAPOR').val(''); $('#CARI_BY').val(''); $('#CARI_ORDER').val(''); $('#CARI_ORDER_TYPE').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <table class="table table-striped table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th width="30">No.</th>
                                <th>Tgl Terima</th>
                                <th>PN / WL</th>
                                <th>Jenis Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_rows) { ?>
                                <?php
                                $i = 0 + $offset;
                                $start = $i + 1;
                                $aId = @explode(',', $id);
                                foreach ($items as $item) {
                                	$rowbg = '';
                                    if ($item->SCREENING_SUCCESS == 0) {
                                        $rowbg = 'style="background-color:#ffa74a;"';
                                    }
                                    else{
                                    	if ($item->IS_KEMBALI == 1) {
	                                        $rowbg = 'style="background-color:#d9534f;"';
	                                    }
                                    }
                                    ?>
                                    <tr <?php echo $rowbg; ?>>
                                        <td><?php echo ++$i; ?>.</td>
                                        <td>
                                            <b>Tanggal Terima :</b><?php echo date('d/m/Y', strtotime($item->TANGGAL_PENERIMAAN)); ?> <br>
                                            <b>Oleh :</b> <?php echo $item->CREATED_BY; ?><br>
                                            <b>Waktu Upload : </b> <?php echo string_to_date($item->CREATED_TIME, "H:i:s"); ?><br>
                                            <?php if ($item->IS_KEMBALI == '1'): ?>
                                                <b>Dikembalikan oleh:</b> <?php echo $item->UPDATED_BY; ?>
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <table style="background: transparent;">
                                                <tbody>
                                                    <tr>
                                                        <th width="80px" style="vertical-align: top;">NIK</th>
                                                        <td style="vertical-align: top;"><span style="margin-right: 10px;">:</span></td>
                                                        <td class="nik"><?php echo $item->NIK; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="vertical-align: top;">NAMA</th>
                                                        <td style="vertical-align: top;"><span style="margin-right: 10px;">:</span></td>
                                                        <td class="nama"><?php echo $item->NAMA; ?></td>
                                                        <td class="agenda" style="display: none;"><?php echo $item->TAHUN_PELAPORAN . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN ?></td>
                                                        <td class="lhkpn" style="display: none;"><?php echo substr(md5($item->ID_LHKPN), 5, 8); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="vertical-align: top;">LEMBAGA</th>
                                                        <td style="vertical-align: top;"><span style="margin-right: 10px;">:</span></td>
                                                        <td class="nama">
                                                            <?php echo ucwords(strtolower($item->NAMA_LEMBAGA)); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="vertical-align: top;">UNIT KERJA</th>
                                                        <td style="vertical-align: top;"><span style="margin-right: 10px;">:</span></td>
                                                        <td class="nama">
                                                            <?php echo ucwords(strtolower($item->NAMA_UNIT_KERJA)); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="vertical-align: top;">JABATAN</th>
                                                        <td style="vertical-align: top;"><span style="margin-right: 10px;">:</span></td>
                                                        <td class="nama">
                                                            <?php echo ucwords(strtolower($item->NAMA_JABATAN)); ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table style="background: transparent;">
                                                <tbody>
                                                    <?php if ($item->JENIS_LAPORAN <> '4') { ?>
                                                        <tr>
                                                            <th width="120px" style="vertical-align: top;">LHKPN Khusus </th>
                                                            <td>
                                                                <span style="margin-right: 10px;">:</span>
                                                                <?php
                                                                if ($item->JENIS_LAPORAN == '1') {
                                                                    echo 'Calon Penyelenggara Negara';
                                                                } else if ($item->JENIS_LAPORAN == '2') {
                                                                    echo 'Awal Menjabat';
                                                                } else if ($item->JENIS_LAPORAN == '3') {
                                                                    echo 'Akhir Menjabat';
                                                                } else {
                                                                    
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <th width="120px" style="vertical-align: top;">LHKPN Periodik </th>
                                                            <td>
                                                                <span style="margin-right: 10px;">:</span>
                                                                <?php
                                                                if ($item->TAHUN_PELAPORAN !== '0') {
                                                                    echo $item->TAHUN_PELAPORAN;
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <th style="vertical-align: top;">Jenis Dokumen</th>
                                                        <td width="110px"><span style="margin-right: 10px;">:</span> <?php echo $item->JENIS_DOKUMEN ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="vertical-align: top;">Melalui</th>
                                                        <td><span style="margin-right: 10px;">:</span> <?php echo beautify_str($lhkpn_offline_melalui[$item->MELALUI]); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="160" nowrap="" align="center">

                                            <?php if ($item->IS_REPLACED != '1' && $item->IS_SEND == '0'): ?>
                                                <?php if ($item->SCREENING_SUCCESS == '1' || $item->SCREENING_SUCCESS == '2'): ?>

                                                    <button type="reset" class="btn btn-sm btn-success btn-edit"
                                                            href="<?php echo 'index.php/efill/lhkpnoffline_verifikasi/view_uploaded/' . $item->ID_IMP_XL_LHKPN; ?>"
                                                            title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                <?php else: ?>

                                                    <button impxllhkpn="<?php echo $item->ID_IMP_XL_LHKPN; ?>" type="reset" class="btn btn-sm btn-success btn-add-with-id"
                                                            href="#"
                                                            title="Edit">
                                                        <i class="fa fa-gear"></i>
                                                    </button>

                                                <?php endif; ?>

                                                <button type="reset" class="btn btn-sm btn-danger btn-hapus"
                                                        href="<?php echo 'index.php/efill/lhkpnoffline_verifikasi/delete_penerimaan_offline/' . $item->ID_IMP_XL_LHKPN; ?>"
                                                        title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                                <?php if ($item->IS_SEND == '1' && $item->ID_LHKPN != ''): ?>
                                                    <button class="btn btn-sm btn-success" onclick="resend_lp2('<?php echo $item->ID_IMP_XL_LHKPN; ?>')" title="Kirim Ulang Lembar Penyerahan Formulir LHKPN">
                                                        <i class="fa fa-envelope"></i>
                                                    </button>
                                                    <a target="_blank" class="btn btn-sm btn-danger" href="<?php echo 'index.php/efill/lhkpnoffline_send/preview_lp/' . $item->ID_IMP_XL_LHKPN; ?>" title="Preview Lembar Penyerahan Formulir LHKPN">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                <?php endif ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $end = $i;
                                }
                                ?>
                            <?php } else { ?>
                                <tr id="not-found">
                                    <td colspan="7" align="center"><strong>Tidak ada data</strong></td>
                                </tr>
                            <?php } ?>
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
                    <div class="row">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Legenda :
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <td style="background-color: #ffa74a; width: 20px; height: 20px; "></td>
                                <td>&emsp;Tidak Lulus Screening&emsp;&emsp;</td>
                                <td style="background-color: #d9534f; width: 20px; height: 20px; "></td>
                                <td>&emsp;Dikembalikan ke Validator</td>
                            </tr>
                        </table>
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

    var callFormAdd = function (i) {

        var data = isDefined(i) ? {impxllhkpn: i} : undefined;
        var url = $("#btn-add").attr("href");

        $.post(url, data, function (html) {
            OpenModalBox('Tambah Penerimaan', html, '', 'large');
        });
        return false;
    };

    $(document).ready(function () {
        
        $('#lembaga').select2({
            data: [],
            allowClear: true,
            width: '100%',
            ajax: {
                url: '<?php echo base_url('index.php/share/reff/getLembaga') ?>',
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true,
                minimumInputLength: 3
            },
            initSelection: function (element, callback) {
                var LEMBAGA = $('#lembaga').val();
                if (LEMBAGA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getInstansi') ?>/" + LEMBAGA, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }

        });

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
        $('.petugas').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getUser/' . $role) ?>",
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
                    $.ajax("<?= base_url('index.php/share/reff/getUser/' . $role) ?>/" + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                if (state.id == '0') {
                    return '-- Pilih Koord CS dahulu --';
                } else {
                    return '<strong>' + state.role + '</strong> : ' + state.name;
                }
            },
            formatSelection: function (state) {
                if (state.id == '0') {
                    return '-- Pilih Koord CS dahulu --';
                } else {
                    return '<strong>' + state.role + '</strong> : ' + state.name;
                }
            }
        });

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
        $("#btn-add").click(function () {
            return callFormAdd();
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

    function resend_lp2(param) {
        confirm("Apakah anda akan mengirimkan Lembar Penyerahan melalui email ?", function () {
            $('#loader_area').show();
            $.ajax({
                url: '<?php echo base_url();?>efill/lhkpnoffline_send/resend_lp/'+param,
                dataType: 'json',
                success: function (data) {
                    $('#loader_area').hide();
                    alert(data.msg);
                }
            });

        }, "Konfirmasi Kirim Email", undefined, "YA", "TIDAK");
    };
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>
