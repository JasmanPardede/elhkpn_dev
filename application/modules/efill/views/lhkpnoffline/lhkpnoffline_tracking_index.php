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
<link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>plugins/barcode/jquery-barcode.js"></script>
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
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

                        </div>         
                        <!--    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/efill/lhkpnoffline/index/tracking/">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars(stripcslashes(@$id), ENT_QUOTES); ?>" />
                            <div class="box-body">
                                <div class="col-md-6"> 
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Lembaga :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['LEMBAGA']), ENT_QUOTES); ?>" id="CARI_LEMBAGA">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Unit Kerja :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[UNIT_KERJA]" placeholder="UNIT KERJA" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['UNIT_KERJA']), ENT_QUOTES); ?>" id="CARI_UNIT_KERJA">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tanggal Lahir :</label>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" placeholder="Tanggal Lahir" name="CARI[TGL_LAHIR]" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['TGL_LAHIR']), ENT_QUOTES); ?>" id="CARI_TGL_LAHIR"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label class="col-sm-4 control-label">NHK :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[NHK]" placeholder="NHK" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['NHK']), ENT_QUOTES); ?>" id="CARI_NHK" onkeypress="return isNumber(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cari :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama/Email" value="<?php echo htmlspecialchars(stripcslashes(@$CARI["NAMA"]), ENT_QUOTES); ?>" id="CARI_NAMA">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                            <div class="form-group">
                                                <div class="col-col-sm-3 col-sm-offset-4-2">
                                                    <button type="submit" class="btn btn-sm btn-primary" id="btn-cari">Cari </button>
                                                    <!--<button type="button" class="btn btn-sm btn-info" id="btnCariPN" href="<?php echo site_url('efill/lhkpnoffline/cari_tracking/'); ?>"><i class="fa fa-search"></i></button>-->
                                                    <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                                                </div>
                                                <a class="btn-cetak btn btn-default btn-sm btn-same" style="background-color: #34ac75; margin-top: 10px;">
                                                    <span class="logo-mini">
                                                        <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                                                    </span> Print to Excel 
                                                </a>
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
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <!--<th width="30"><input type="checkbox" onClick="chk_all(this);" /></th>-->
                            <th class="col-md-0" style="text-align:'center'">No.</th>
                            <th class="col-md-2">Nama / No. Agenda / Tanggal Lahir</th>
                            <th class="col-md-2">Tanggal Lapor / Tanggal Kirim Final / Status</th>
                            <th class="col-md-2">Jabatan</th>
                            <th class="col-md-2">Unit Kerja</th>
                            <th class="col-md-2">Lembaga</th>
                            <th class="col-md-1">Last Login / Entry Via</th>
                            <th class="col-md-1">Aksi</th>
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
    $setStatus = ['0' => 'Draft', '1' => 'Proses Verifikasi', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi Lengkap', '4' => 'Diumumkan Lengkap', '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Dikembalikan'];
    
    foreach ($items as $index => $item) {
            $item->tahun_wl = $max_tahun_wls[$index];
            
            if($item->JENIS_LAPORAN==5){
                $kode_agenda = 'P';
            }elseif($item->JENIS_LAPORAN==4){
                $kode_agenda = 'R';
            }else{
                $kode_agenda = 'K';
            }
        $agenda = date('Y', strtotime($item->tgl_lapor)) . '/' . $kode_agenda . '/' . $item->NIK . '/' . $item->ID_LHKPN;
        ?>

            <tr>
                <td><?php echo ++$i ?>.</td>
                <td class="agenda">
                    <a href="index.php/eano/announ/getInfoPn/<?php echo $item->ID_PN; ?>" onClick="return getPn(this);" class="nama"><?php echo $item->NAMA; ?></a><br>
                    <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo $item->ID_LHKPN; ?>" class="btn-tracking"><?php echo $agenda; ?></a><br>
                    <?php echo tgl_format($item->TGL_LAHIR); ?>
                </td>
                <td>Tgl Lapor : <br><strong><?php echo $item->tgl_lapor == '' || $item->tgl_lapor == NULL ? '-' : tgl_format($item->tgl_lapor); ?></strong>
                <br>Tgl Kirim Final : <br><strong><?php echo $item->tgl_kirim_final == '' || $item->tgl_kirim_final == NULL ? '-' : tgl_format($item->tgl_kirim_final); ?></strong>
                <br>Status : <br><strong><?php echo $item->STATUS == '1' && $item->ALASAN != NULL && $item->DIKEMBALIKAN == '0' ? 'Sudah Diperbaiki' : ($item->STATUS == '1' && $item->DIKEMBALIKAN > '0' ? 'Proses Verifikasi (Dikembalikan)' : $setStatus[$item->STATUS]); ?></strong>
                </td>
                <td><?php echo $item->NAMA_JABATAN; ?></td>
                <td><?php echo $item->UK_NAMA; ?></td>
                <td><?php echo $item->INST_NAMA; ?></td>
                <td><?php echo date('d-m-Y H:i:s',$item->LAST_LOGIN) != '01-01-1970 07:00:00' ? date('d-m-Y H:i:s',$item->LAST_LOGIN) : '-'; ?>
                    <?php
                    if ($item->entry_via == '0') {
                        echo "&nbsp; <img src='" . base_url() . "img/online.png' title='Via Online'/>";
                        $entry = "<img src='" . base_url() . "img/entry.png' title='di entry oleh'/> &nbsp;";
                    } else if ($item->entry_via == '1') {
                        echo "&nbsp; <img src='" . base_url() . "img/excel-icon.png' title='Via Excel'/>";
                        $entry = "<img src='" . base_url() . "img/validate.png' title='di validasi oleh'/> &nbsp;";
                    } else if ($item->entry_via == '2') {
                        echo "&nbsp; <img src='" . base_url() . "img/hard-copy.png' title='Via Hardcopy'/>";
                    }
                    ?>
                </td>
                <td>
                <!-- <button type="button" id="btnEditJenisFilling' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-warning btn-sm edit-action" title="Edit Jenis Laporan" onclick="btnEditJenisFillingOnClick(this);"><i class="fa fa-bars" style="color:white;"></i></button> -->
                <?php 
                if($item->JENIS_LAPORAN==5){
                ?>
                <a id="cetak_final" class="btn btn-sm btn-primary cetakikhtisar" title="Cetak ikhtisar" target="_blank" href="<?php echo base_url(); ?>ever/ikthisar/cetak_klarifikasi/<?php echo $item->ID_LHKPN; ?>"><i class="fa fa-print"></i></a>
                <?php
                    if($item->STATUS==4 || $item->STATUS==6){
                ?>                    
                    <a id="DownloadPengumuman" class="btn btn-sm" style="background-color: #cccc00;color:black" dkey="<?php echo $item->ID_LHKPN;?>" title="Download Pengumuman" onclick="btnCetakPengumumanOnClick(this);"><i class="fa fa-bullhorn"></i></a>
                <?php
                    }
                }else{
                    if($item->entry_via==0){ 
                        if($item->STATUS != 0){
                    ?>
                            <a id="VerifikasiInfo" class="btn btn-sm btn-info" dkey="<?php echo $item->ID_LHKPN;?>" title="Lihat LHKPN" onclick="btnInfoVerifOnClick(this);"><i class="fa fa-search-plus"></i></a>
                    <?php
                            if($item->STATUS==1 || $item->STATUS==2){
                    ?>

                                <!--<button type="button" id="btnEditJenisFilling'.<?php // echo $item->ID_LHKPN; ?>.'" dkey="<?php // echo $item->ID_LHKPN; ?>" th_wl_mx="<?php // echo $item->tahun_wl; ?>" class="btn btn-warning btn-sm edit-action" title="Edit Jenis Laporan" onclick="btnEditJenisFillingOnClick(this);"><i class="fa fa-bars" style="color:white;"></i></button>-->

                    <?php 
                            }
                        } 
                    }
                    ?>
                    <?php if($item->STATUS!=0){ ?>
                        <a id="cetak_final" class="btn btn-sm btn-primary cetakikhtisar" title="Cetak ikhtisar" target="_blank" href="<?php echo base_url(); ?>ever/ikthisar/cetak/<?php echo $item->ID_LHKPN; ?>"><i class="fa fa-print"></i></a>
                    <?php } ?>
                    <?php 
                        if (($item->entry_via == 0 || $item->entry_via == 1) && ($item->STATUS == '3' || $item->STATUS == '4' || $item->STATUS == '5' || $item->STATUS == '6')) { 
                            if ($item->ALASAN != null){
                    ?>
                                <a id="DownloadKekurangan" class="btn btn-sm btn-danger" target="_blank" dkey="<?php echo $item->ID_LHKPN;?>" title="Download Lampiran Kekurangan" onclick="btnCetakKekuranganOnClick(this);"><i class="glyphicon glyphicon-exclamation-sign"></i></a>
                    <?php
                            }
                    ?>
                            <a id="DownloadPDFII" class="btn btn-sm yesdownl" style="background-color: #c0c0c0;" target="_blank" title="Download Tanda Terima" href="index.php/ever/verification/preview_tandaterima/<?php echo $item->ID_LHKPN;?>/<?php echo $item->entry_via;?>"><i class="fa fa-download"></i></a>
                    <?php          
                        }
                        if (($item->entry_via == 0 || $item->entry_via == 1) && ($item->STATUS == '4'|| $item->STATUS == '6')) { 
                    ?>
                            <a id="DownloadPengumuman" class="btn btn-sm" style="background-color: #cccc00;color:black" dkey="<?php echo $item->ID_LHKPN;?>" title="Download Pengumuman" onclick="btnCetakPengumumanOnClick(this);"><i class="fa fa-bullhorn"></i></a>
                    <?php
                        }
                        if (($item->STATUS == 2 && $item->entry_via != 2) || ($item->STATUS == 1 && $item->ALASAN != NULL) || ($item->STATUS == 7 && $item->DIKEMBALIKAN != 0)) {
                    ?>
                            <a id="DownloadKekurangan" class="btn btn-sm btn-danger" target="_blank" dkey="<?php echo $item->ID_LHKPN;?>" title="Download Lampiran Kekurangan" onclick="btnCetakKekuranganOnClick(this);"><i class="glyphicon glyphicon-exclamation-sign"></i></a>
                <?php 
                    }
                }
                ?>
                </td>
            </tr>
        <?php
        $end = $i;
    }
    ?>
<?php } else { ?>
                            <tr id="not-found">
                                <td colspan="9" align="center"><strong>Belum ada data</strong></td>
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
                        <?php echo isset($content_paging)?$content_paging:''; ?>
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <!--</section> /.content -->

<?php echo isset($content_js)?$content_js:''; ?>


            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->
<!-- /.content -->
<div id="FillingEditLaporan" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form"  id="ajaxFormSave" method="POST" action="index.php/efill/lhkpnoffline/save_tracking_lhkpn">
<!-- 																	action="index.php/efill/lhkpnoffline/index/tracking/" -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!--<h4 class="modal-title">BUAT LHKPN BARU</h4>-->
                    <h4 class="modal-title"><label id="title-label" style="font-size: 20px;"></label></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="is_update" id="is_update" class="form-control"/>
                        <input type="hidden" name="id_lhkpn" id="id_lhkpn" class="form-control"/>
                        <input type="hidden" name="id_pn" id="id_pn" class="form-control"/>
                        <label>Jenis Laporan <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_laporan_create'); ?>
                        <select name="jenis_laporan" id="jenis_laporan" class="form-control" required>
                            <option></option>
                            <option value="4">Periodik</option>
                            <option value="2">Khusus</option>
                        </select>
                    </div>
                    <div class="form-group group-0">
                        <label>Tahun Pelaporan <span class="red-label">*</span> </label> <?= FormHelpPopOver('tahun_laporan_create'); ?>
                        <input type="text" name="tahun_pelaporan" id="tahun_pelaporan" class="form-control" autocomplete="off"  onkeydown="return false" />
                    </div>
                    <div class="form-group group-1">
                        <label>Status <span class="red-label">*</span> </label> <?= FormHelpPopOver('status_laporan_create'); ?>
                        <select name="status" id="status" class="form-control" required>
                            <option></option>
                            <option value="1">Calon Penyelenggara Negara</option>
                            <option value="2">Awal Menjabat</option>
                            <option value="3">Akhir Menjabat</option>
                        </select>
                    </div>
                    <div class="form-group group-1">
                        <label>Tanggal Pelaporan <span class="red-label">*</span> </label> <?= FormHelpPopOver('tanggal_laporan_create'); ?>
                        <input type="text" name="tgl_pelaporan" id="tgl_pelaporan" class="form-control date" autocomplete="off" onkeydown="return false" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" >
                        <i class="fa fa-share"></i> Lanjut
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-remove"></i> Batal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script language="javascript">
    $(document).ready(function() {
        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            // console.log(url);
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

		$("#ajaxFormSave").submit(function () {
			$('#loader_area').show();
            var url = $(this).attr('action');
            var data = $(this).serializeArray();
            $.post(url, data, function (res) {
                var data_get = JSON.parse(res);
                if (data_get.status == 0) { 
                    alertify.error(data_get.msg);
                } else {
                    alertify.success(data_get.msg);
                }
                $('#FillingEditLaporan').modal('hide');
            })
            $('#loader_area').hide();
            $("#ajaxFormCari").submit()
            return false;
        });

        
        $('.btn-print').click(function(e) {
            url = $(this).attr('href');
            html = '<iframe src="' + url + '" width="100%" height="500px"></iframe>';
            OpenModalBox('Print Tracking LHKPN', html, '', 'large');
            return false;
        });

        $("#btnCariPN").click(function() {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cari PN/WL', html, '', 'large');
            });
            return false;
        });

        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });
        
        $('.btn-tracking').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
            });
            return false;
        });

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

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!

        var yyyy = today.getFullYear();
        var yearminsatu = today.getFullYear()-1;
        if(dd<10){
            dd='0'+dd;
        }
        if(mm<10){
            mm='0'+mm;
        }
        var today = dd+'/'+mm+'/'+yyyy;

        $('#jenis_laporan').change(function () {
            var val = $(this).val();
            if (val == 4) {
                $('.group-1').hide();
                $('.group-0').fadeIn('slow');
                $('#tahun_pelaporan').val(yearminsatu);
            } else {
                $('.group-0').hide();
                $('.group-1').fadeIn('slow');
                $('#tgl_pelaporan').val(today);
            }
        });
        var d = new Date();
        d.setFullYear(d.getFullYear() - 1);
        var limit = new Date(d.getFullYear(), 11, 31);
        var limitq = new Date(d.setFullYear(d.getFullYear(), 0, 0));
        
        // $('#tahun_pelaporan').datetimepicker({
        //     viewMode: 'years',
        //     allowInputToggle: true,
        //     locale: 'id',
        //     format: "YYYY",
        //     maxDate: yearminsatulimit,
        // });

        btnCetakKekuranganOnClick = function (self) {
            var id = $(self).attr('dkey');
            var url = '<?php echo base_url(); ?>ever/verification/previewmsg';
            $("<form target='_blank' action='" + url + "' method='post' ></form>")
                .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                .append($("<input type='hidden' name='verif' />").attr('value', 'kekurangan'))
                .appendTo('body')
                .submit()
                .remove();
            return false;
        };

        btnCetakPengumumanOnClick = function (self) {
            var id = $(self).attr('dkey');
            window.open('<?php echo base_url(); ?>portal/filing/BeforeAnnoun/' + id,'_blank')
        };

        btnEditJenisFillingOnClick = function (self) {
            var id = $(self).attr('dkey');
            var max_tahun_wl = $(self).attr('th_wl_mx');

                $.ajax({
                 url: '<?php echo base_url();?>' +'portal/filing/edit_jenis_pelaporan/'+id,
                 async: false,
                 dataType: 'JSON',
                 success:function(data){
                    var rs = eval(data);
//                     $('#jenis_laporan').select2('val',rs.result.JENIS_LAPORAN == '1' || rs.result.JENIS_LAPORAN == '2' || rs.result.JENIS_LAPORAN == '3' ? '2' : '4');

                        var val = rs.result.JENIS_LAPORAN == '1' || rs.result.JENIS_LAPORAN == '2' || rs.result.JENIS_LAPORAN == '3' ? '2' : '4';
						$("#jenis_laporan").val(val).change();
                        if(rs.count_all_lhkpn==1){
                            if(rs.result.JENIS_LAPORAN=='4'){
                                var optionKhusus = $('<option></option>').attr("value", "2").text("Khusus");
                                var optionDefault = $('<option></option>').attr("value", "").text("");
                                $("#jenis_laporan").empty().append(optionDefault);
                                $("#jenis_laporan").append(optionKhusus);
                            }else{
                                $('#jenis_laporan').attr("disabled", true);
                            }
                        }else{
                            $('#jenis_laporan').attr("disabled", false);
                        }
						var date    = rs.result.tgl_lapor.split("-"),
                        yr      = date[0],
                        month   = date[1],
                        day     = date[2],
                        newDate = day + '/' + month + '/' + yr;
                        var d = new Date();
                        d.setFullYear(d.getFullYear() - 1);

                        var today = new Date();
                        var dd = today.getDate();
                        var mm = today.getMonth()+1; //January is 0!

                        var yyyy = today.getFullYear();

                        var limit = new Date(d.getFullYear(), 11, 31);
                        var limitqq =  new Date(max_tahun_wl,1-1,1); //1 januari tahun max wl
                        var limitq = new Date(yyyy,1-1,1);  //1 januari tahun sekarang
                        var tgl_kirim_final = new Date(rs.result.tgl_kirim_final);    
                    if (val == 4) {
                        
                        $('.group-1').hide();
                        $('.group-0').fadeIn('slow');
                        $('#status').removeAttr('required');
                        console.log(yearminsatu);
                        console.log(yr);
                        if(yearminsatu > yr){
                            console.log('masuk atas');
                            $('#tahun_pelaporan').datetimepicker({
                            viewMode: 'years',
                            allowInputToggle: true,
                            locale: 'id',
                            format: "YYYY",
                            maxDate: String(yearminsatu),
                            minDate: yr
                        });
                        }else{
                            console.log('masuk bawah');
                                $('#tahun_pelaporan').datetimepicker({
                                viewMode: 'years',
                                allowInputToggle: true,
                                locale: 'id',
                                format: "YYYY",
                                minDate: String(yearminsatu),
                                maxDate: yr
                            });
                        }
                        $('#tahun_pelaporan').val(yr);
                        

                    } else {
                        $("#status").val(rs.result.JENIS_LAPORAN).change();
                        $('#tgl_pelaporan').val(newDate);
                        $('.group-0').hide();
                        $('.group-1').fadeIn('slow');
                    }
                    $('#is_update').val('update');
                    $('#id_lhkpn').val(rs.result.ID_LHKPN);
                    $('#id_pn').val(rs.result.ID_PN);

                    var maxLapor = new Date(max_tahun_wl, 11, 31); //31 desember tahun max 

                    if(yyyy == max_tahun_wl){
                        maxLapor = "now";
                    }else{
                        maxLapor;
                    }

                    if(rs.is_update == 'update'){
                        $('#title-label').text('EDIT JENIS LAPORAN');
                        if(rs.result.tgl_kirim_final){
                            $('#tgl_pelaporan').datetimepicker({ 
                                format: "DD/MM/YYYY",
                                allowInputToggle: true,
                                locale: 'id',
                                maxDate: maxLapor,
                                minDate: new Date(limitqq) 
                            });
                            if (val != 4) {
                                $('#tgl_pelaporan').val(newDate);
                            }
                        }else{ 
                            $('#tgl_pelaporan').datetimepicker({
                                format: "DD/MM/YYYY",
                                allowInputToggle: true,
                                locale: 'id',
                                maxDate: 'now',
                                minDate: new Date(limitq)
                            });
                        }
                    }

                }
            });
//            $('.group-0,.group-1, #img_pop_content').hide();
            $('#FillingEditLaporan').modal('show');
        };

        btnInfoVerifOnClick = function (self) {
            var id = $(self).attr('dkey');
            var url1 = '#index.php/ever/verification/display/infolhkpn/';
            var url2 = location.href.split('#')[0];
            window.open(url2 + url1 + id + '/infoLHKPN','_blank');
            //////////////////// Not New Tab
            // var url = 'index.php/ever/verification/display/infolhkpn/' + id + '/infoLHKPN';
            // ng.LoadAjaxContent(url);
            // return false;
        };

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        $('.btn-cetak').click(function(e){
            e.preventDefault();

            var lembaga = ($('#CARI_LEMBAGA').val() == '') ? 'ALL' : $('#CARI_LEMBAGA').val();
            var uk = ($('#CARI_UNIT_KERJA').val() == '') ? 'ALL' : $('#CARI_UNIT_KERJA').val();
            var tgl_lahir = ($('#CARI_TGL_LAHIR').val() == '') ? 'ALL' : $('#CARI_TGL_LAHIR').val();
            var nhk = ($('#CARI_NHK').val() == '') ? 'ALL' : $('#CARI_NHK').val();
            var nama = ($('#CARI_NAMA').val() == '') ? 'ALL' : $('#CARI_NAMA').val();

            if (lembaga === "ALL" && uk === 'ALL' && tgl_lahir === 'ALL' && nhk === 'ALL' && nama === 'ALL') {
                alertify.warning('Tidak ada data yang dicetak');
                return;
            }

            var url = '<?php echo site_url("index.php/efill/lhkpnoffline/export_tracking_lhkpn") ?>/'+ lembaga + '/' + uk + '/' + tgl_lahir + '/' + nhk + '/' + escape(nama);
            window.location.href = url;
            return;

        });

        
    });
    
    function getPn(ele) {
        var url = $(ele).attr('href');
        $.get(url, function (html) {
            OpenModalBox('Detail PN', html, '', 'standart');
        });

        return false;
    }

    function generateBarcode() {
        var value = '<?php echo @$CARI['KODE']; ?>';
        var btype = 'code93';
        var renderer = $("input[name=renderer]:checked").val();

        var quietZone = false;
        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')) {
            quietZone = true;
        }

        var settings = {
            output: renderer,
            bgColor: $("#bgColor").val(),
            color: $("#color").val(),
            barWidth: $("#barWidth").val(),
            barHeight: $("#barHeight").val(),
            moduleSize: $("#moduleSize").val(),
            posX: $("#posX").val(),
            posY: $("#posY").val(),
            addQuietZone: $("#quietZoneSize").val()
        };

        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')) {
            value = {code: value, rect: true};
        }

        if (renderer == 'canvas') {
            clearCanvas();
            $("#barcodeTarget").hide();
            $("#canvasTarget").show().barcode(value, btype, settings);
        } else {
            $("#canvasTarget").hide();
            $("#barcodeTarget").html("").show().barcode(value, btype, settings);
        }
    }

    $(function() {
        generateBarcode();
    });

    
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>
