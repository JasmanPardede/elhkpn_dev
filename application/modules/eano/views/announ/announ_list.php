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
 * @package Views/eano/announ
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Daftar Pengumuman PNWL
        <small></small>
    </h1>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <!-- <button type="button" id="btnAdd" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Tambah Data</button> -->
                            <!-- <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
                            <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
                            <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button> -->

                        </div>         
                        <!--    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/eano/announ/index/list">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="box-body">
                                <div class="col-md-6">    
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tahun Pengumuman :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN']; ?>" id="CARI_TAHUN">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                            <div class="col-sm-3">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Jenis Laporan :</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="CARI[JENIS]">
                                                    <option value="">-pilih Jenis-</option>
                                                    <option value="1" <?php if (@$CARI['JENIS'] == 1) {
        echo 'selected';
    }; ?>>Khusus, Calon</option>
                                                    <option value="2" <?php if (@$CARI['JENIS'] == 2) {
        echo 'selected';
    }; ?>>Khusus, Awal menjabat</option>
                                                    <option value="3" <?php if (@$CARI['JENIS'] == 3) {
        echo 'selected';
    }; ?>>Khusus, Akhir menjabat</option>
                                                    <option value="4" <?php if (@$CARI['JENIS'] == 4) {
        echo 'selected';
    }; ?>>Periodik tahunan</option>
                                                    <option value="5" <?php if (@$CARI['JENIS'] == 5) {
        echo 'selected';
    }; ?>>Klarifikasi</option>
                                                </select>
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                            <div class="col-sm-3">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- 			                    <div class="row">
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-4 control-label">Status Laporan :</label>
                                                                                    <div class="col-sm-5">
                                                                                        <select class="form-control" name="CARI[STATUS]">
                                                                                            <option value="">-pilih Status-</option>
                                                                                            <option value="1" <?php if (@$CARI['STATUS'] == 1) {
        echo 'selected';
    }; ?>>Masuk</option>
                                                                                            <option value="2" <?php if (@$CARI['STATUS'] == 2) {
        echo 'selected';
    }; ?>>Perlu Perbaikan</option>
                                                                                            <option value="3" <?php if (@$CARI['STATUS'] == 3) {
        echo 'selected';
    }; ?>>Terverifikasi</option>
                                                                                        </select> -->
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                <!-- 			                            </div>
                                                                                    <div class="col-sm-3">
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div> -->
                                <!-- 			                    <div class="row">
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-4 control-label">Entri Via :</label>
                                                                                    <div class="col-sm-5">
                                                                                        <select class="form-control" name="CARI[VIA]">
                                                                                            <option value="">-pilih Via-</option>
                                                                                            <option value="0" <?php if (@$CARI['VIA'] == '0') {
        echo 'selected';
    }; ?>>WL, Online</option>
                                                                                            <option value="1" <?php if (@$CARI['VIA'] == 1) {
        echo 'selected';
    }; ?>>Entry Hard Copy</option>
                                                                                            <option value="2" <?php if (@$CARI['VIA'] == 2) {
        echo 'selected';
    }; ?>>Import Excel</option>
                                                                                        </select> -->
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                <!-- 			                            </div>
                                                                                    <div class="col-sm-3">
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div> -->
                                <div class="col-md-6"> 
                                    <!-- <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Lembaga :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[LEMBAGA_NAMA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA_NAMA']; ?>" id="CARI_LEMBAGA_NAMA">
                                                <button type="button" class="btn btn-sm btn-default">...</button> ->ini dikomen
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Unit Kerja :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[UNITKERJA]" placeholder="UNIT KERJA" value="<?php echo @$CARI['UNITKERJA']; ?>" id="CARI_UNITKERJA">
                                                <button type="button" class="btn btn-sm btn-default">...</button>->ini dikomen
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Lembaga :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA']; ?>" id="CARI_LEMBAGA">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Unit Kerja :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[UNIT_KERJA]" placeholder="UNIT KERJA" value="<?php echo @$CARI['UNIT_KERJA']; ?>" id="CARI_UNIT_KERJA">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cari :</label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                                    <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>


                <!-- <div class="box-tools"> -->
                <!-- </div> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <strong style="display:none;"><span id="jml">0</span> Dokumen yang dipilih!</strong>
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <!--<th width="30"><input type="checkbox" onClick="chk_all(this);" /></th>-->
                            <th width="30">No.</th>
                            <th>No. Agenda</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Lembaga</th>
                            <th>Unit Kerja</th>
                            <th>Tanggal Pengumuman</th>
                            <!--<th class="hidden-xs hidden-sm">Jenis Laporan</th>-->
                            <!--<th>Hasil Verifikasi</th>-->
                            <th width="120">Aksi</th>
                           <!-- <th>Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody>
<?php if ($total_rows) { ?>
    <?php
    $i = 0 + $offset;
    $start = $i + 1;
    $aId = @explode(',', $id);
    $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
    $aStatus = ['0' => 'Draft', '1' => 'Masuk', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi Lengkap', '4' => 'Diumumkan Lengkap', '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Ditolak'];
    $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
    foreach ($items as $item) {
        $agenda = date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : ($item->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $item->NIK . '/' . $item->ID_LHKPN;
        ?>
                                <tr>
                                    <!-- <td class="agenda" style="display: none;"><?php date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : ($item->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $item->NIK . '/' . $item->ID_LHKPN ?></td> -->
                                    <td class="lhkpn" style="display: none;"><?php echo substr(md5($item->ID_LHKPN), 5, 8); ?></td>
                                    <td class="lhkpnori" style="display: none;"><?php echo $item->ID_LHKPN; ?></td>
                                    <td class="nik" style="display: none;"><?php echo $item->NIK; ?></td>
                                    <td class="tgl_lapor" style="display: none;"><?php echo date('Y', strtotime($item->tgl_lapor)); ?></td>
                                    <td class="jenis_laporan" style="display: none;"><?php echo $item->JENIS_LAPORAN == '4' ? 'R' : ($item->JENIS_LAPORAN == '5' ? 'P' : 'K'); ?></td>
                                    <!--<td> <?php echo (in_array($item->ID_LHKPN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="' . $item->ID_LHKPN . '" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="' . $item->ID_LHKPN . '" onclick="chk(this);" />') ?></td>-->
                                    <td><?php echo ++$i; ?>.</td>
                                    <td class="agenda">
                                        <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo $item->ID_LHKPN; ?>" class="btn-tracking"><?php echo $agenda; ?></a>
                                    </td>
                                    <td><a href="index.php/eano/announ/getInfoPn/<?php echo $item->ID_PN; ?>" onClick="return getPn(this);" class="nama"><?php echo $item->NAMA; ?></a></td>
                                    <td><?php echo $item->NAMA_JABATAN; ?></td>
                                    <td><?php echo $item->INST_NAMA; ?></td>
                                    <td><?php echo $item->UK_NAMA; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($item->TGL_BA_PENGUMUMAN)); ?>
                                    <?php
                                    if ($item->entry_via == '0') {
                                        echo "&nbsp; <img src='" . base_url() . "img/online.png' title='Via Online'/>";
                                        $entry = 'dientri oleh: ';
                                    } else if ($item->entry_via == '1') {
                                        echo "&nbsp; <img src='" . base_url() . "img/excel-icon.png' title='Via Excel'/>";
                                        $entry = 'divalidasi oleh: ';
                                    } else if ($item->entry_via == '2') {
                                        echo "&nbsp; <img src='" . base_url() . "img/hard-copy.png' title='Via Hardcopy'/>";
                                    }
                                    ?>			
                                    </td>
        
                                    <!--<td class="hidden-xs hidden-sm"><?php // echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus'; ?></td>-->
<!--                                    <td >
        <?php
//        if (@$item->STATUS == '4') {
//            echo 'Diumumkan Lengkap';
//        } else if (@$item->STATUS == '6') {
//            echo 'Diumumkan Tidak Lengkap';
//        }
        ?>
                                    </td>-->
                                    <td><a id="DownloadPDFII" target="_blank" title="Preview cetak pengumuman" class="btn btn-sm btn-success yesdownl" href="index.php/eano/announ/BeforeAnnoun/<?php echo $item->ID_LHKPN; ?>"><i class="fa fa-download"></i></a>
                                    <!--<a id="gen<?php echo $item->ID_LHKPN; ?>" title="Kirim Ulang pengumuman" class="btn btn-sm btn-success nodownl btnGenPDFII" href="index.php/eano/announ/CreatePdfFinalWithEmail/<?php echo $item->ID_LHKPN; ?>"><i class="fa fa-send"></i></a>-->
                                    <button id="gen<?php echo $item->ID_LHKPN; ?>" data-mailpn="<?=$item->EMAIL?>" type="button" class="btn btn-sm btn-success nodownl btnGenPDFII" href="index.php/eano/announ/CreatePdfFinalWithEmail/<?php echo $item->ID_LHKPN; ?>" value="<?php echo $item->STATUS_CETAK_PENGUMUMAN_PDF; ?>"><i class="fa fa-send"></i></button>
                                    <?php
                                        if($item->JENIS_LAPORAN == 5){
                                    ?>
                                    <a id="cetak_final" class="btn btn-sm btn-success cetakikhtisar" title="Cetak ikhtisar" target="_blank" href="<?php echo base_url(); ?>ever/ikthisar/cetak_klarifikasi/<?php echo $item->ID_LHKPN; ?>"><i class="fa fa-print"></i></a></td>
                                    <?php
                                        }else{
                                    ?>
                                    <a id="cetak_final" class="btn btn-sm btn-success cetakikhtisar" title="Cetak ikhtisar" target="_blank" href="<?php echo base_url(); ?>ever/ikthisar/cetak/<?php echo $item->ID_LHKPN; ?>"><i class="fa fa-print"></i></a></td>
                                    <?php
                                        }
                                    ?>
                                </tr>
        <?php
        $end = $i;
    }
    ?>
<?php } elseif ($no_filter == 1){ ?>
                            <tr>
                                <td colspan="8" align="center"><strong>Silahkan masukkan pencarian di filter yang disediakan</strong></td>
                            </tr>
<?php }else{ ?> <tr id="not-found">
                                <td colspan="8" align="center"><strong>Belum ada data</strong></td>
                            </tr>
 <?php } ?>
                    </tbody>
                </table>
                <!--<tr id="paging">-->
                <!-- Main content -->
                    <!--<section class="content">-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                        <?php // echo $content_list; ?>
                        <?php echo $content_paging; ?>
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <!--</section> /.content -->

<?php echo $content_js; ?>


            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->



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

    $('#CARI_LEMBAGA').select2({
        minimumInputLength: 0,
        ajax: {
            url: "<?=base_url('index.php/share/reff/getLembaga')?>",
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return { results: data.item };
            },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?=base_url('index.php/share/reff/getLembaga')?>/"+id, {
                    dataType: "json"
                }).done(function(data) { callback(data[0]); });
            }
        },
        formatResult: function (state) {
            return state.name;
        },
        formatSelection:  function (state) {
            return state.name;
        }
    });
    LEMBAGA = $('#CARI_LEMBAGA').val();
    $('#CARI_UNIT_KERJA').select2({
        minimumInputLength: 0,
        ajax: {
            url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
            dataType: 'json',
            quietMillis: 250,
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return { results: data.item };
            },
            cache: true
        },
        initSelection: function(element, callback) {
            var id = $(element).val();
            if (id !== "") {
                $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
                    dataType: "json"
                }).done(function(data) { callback(data[0]); });
            }
        },
        formatResult: function (state) {
            return state.name;
        },
        formatSelection:  function (state) {
            return state.name;
        }
    });
    $('#CARI_LEMBAGA').change(function(event) {
        LEMBAGA = $(this).val();
        $('#CARI_UNIT_KERJA').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
            }
        });
    }); 

    $(document).ready(function () {
        
        $('.btnGenPDFII').click(function(event) {
            var email_pn = $(this).data('mailpn');
            var url =  $(this).attr('href');
            confirm(`Apakah anda yakin ingin kirim ulang ke ${email_pn} ?`, function(){
                $('#loader_area').show();
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    success: function(htmldata) {
                        htmldata = JSON.parse(htmldata);
                        msg = {
                            success: 'Data Berhasil di Kirim !',
                            error: 'Data Tidak Berhasil di Kirim !'
                        };
                        if (htmldata == 0) {
                            alertify.error(msg.error);
                            $('#loader_area').hide();
                        } else {
                            alertify.success(msg.success);
                            $('#loader_area').hide();
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        });
        
        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
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