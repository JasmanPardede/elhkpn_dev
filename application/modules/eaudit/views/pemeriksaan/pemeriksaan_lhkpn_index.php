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
 * @author Ferry Ricardo Siagian - Komisi Pemberantasan Korupsi
 * @package Views/eaudit/pemeriksaan
 */


?>
<div class="box-header with-border">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <!-- <button type="button" id="btnAdd" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Tambah Data</button> -->
<!--            <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
            <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
            <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>-->

        </div>
    </div>
    <!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
    <div class="col-md-12">
        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/eaudit/pemeriksaan/index/lhkpn/">
            <div class="box-body">
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                           <div class="form-group">
                            <label class="col-sm-4 control-label">Tahun Pemeriksaan :</label>
                            <div class="col-sm-6">
                                <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN']; ?>" id="CARI_TAHUN">
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Status Pemeriksaan :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[STATUS]">
                                    <!--<option value="">-pilih Status-</option>-->
                                    <option value="1" <?php
                                    if (@$CARI['STATUS'] == 1) {
                                        echo 'selected';
                                    };
                                    ?>>Permintaan Baru</option>
                                    <option value="2" <?php
                                    if (@$CARI['STATUS'] == 2) {
                                        echo 'selected';
                                    };
                                    ?>>Proses Pemeriksaan</option>
                                    <option value="3" <?php
                                    if (@$CARI['STATUS'] == 3) {
                                        echo 'selected';
                                    };
                                    ?>>Pemeriksaan Selesai</option>
                                    <option value="4" <?php
                                    if (@$CARI['STATUS'] == 4) {
                                        echo 'selected';
                                    };
                                    ?></option>
                                </select>
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Lembaga :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA']; ?>" id="CARI_LEMBAGA">
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Cari :</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                            <div class="form-group">
                                <div class="col-col-sm-3 col-sm-offset-4-2">
                                    <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <br>
                Jenis Entry <select name="CARI[JENIS_ENTRY]" id="CARI_JENIS_ENTRY">
                          <option>-- Jenis Entry --</option>
                          <option value="1">Form</option>
                          <option value="2">Excel</option>
                      </select>
                <br>
                Diinput Oleh <select name="CARI[DIINPUT_OLEH]" id="CARI_DIINPUT_OLEH">
                          <option>-- Diinput Oleh --</option>
                          <option value="1">Data Entry</option>
                          <option value="2">PN</option>
                      </select>
                <br> -->
        </form>
    </div>
    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
</div>


<!-- <div class="box-tools"> -->
<!-- </div> -->
</div><!-- /.box-header -->
<div class="box-body">
    <?php
    if ($total_rows) {
        ?>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom tabelBody" id='tbl-verif'>
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>No Agenda</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Lembaga</th>
                    <th>Periode Pemeriksaan </th>
                    <!-- <th class="hidden-xs hidden-sm">Eselon</th> -->
                    <!-- <th class="hidden-xs hidden-sm">Unit Kerja</th> -->
                    <!-- <th>Lembaga</th> -->
                    <!-- <th class="hidden-xs hidden-sm">Jenis</th> -->
                    <!-- <th class="hidden-xs hidden-sm">Status</th> -->
                    <th>Status Pemeriksaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0 + $offset;
                $start = $i + 1;
                $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
                $aStatus = ['0' => 'Draft', '1' => 'Proses Verifikasi', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi', '4' => 'Diumumkan', '5' => 'Terverifikasi tidak lengkap', '7' => 'Ditolak'];

                $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
                foreach ($items as $item) {
                    $agenda = date('Y', strtotime($item->tgl_kirim_final)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;

                    $tolak = $item->STATUS == '2' ? true : false;
                    $limit = $item->entry_via == '0' ? 14 : 30;
                    $tgl_now = date_create(date('Y-m-d'));
                    $tgl_set = date_create($item->TANGGAL);
                    $diff = date_diff($tgl_set, $tgl_now);
                    $v_h = $tolak ? $diff->format("%R%a") : 0;

                    if ($v_h >= $limit) {
                        if (@$CARI['STATUS'] == 2){
                            $bgcolor = 'style="background-color: #dc4735"';
                            $ctk = TRUE;
                        }
                    } else {
                        $bgcolor = "";
                        $ctk = FALSE;
                    }
                    ?>
                    <tr <?= $bgcolor ?> >
                        <td ><?= ++$i; ?>.</td>
                        <td><?php
						if ($item->status_periksa == '1') {
                                echo "<img src='" . base_url() . "img/new2.gif'/><br>";
                            }
						?>
						<a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($item->ID_LHKPN), 5, 8); ?>" onclick="return tracking(this)"><?php echo $agenda; ?></a></td>
                        <td><a href="index.php/ever/verification/getInfoPn/<?php echo $item->ID_PN; ?>" onClick="return getPn(this);"><?php echo $item->NAMA_LENGKAP; ?></a></td>
                        <td><?php echo $item->NAMA_JABATAN; ?></td>
                        <td><?php echo $item->INST_NAMA; ?></td>
                        <td><?php echo "<strong>Tanggal Mulai Periksa :</strong> &nbsp"; echo date('d/m/Y', strtotime($item->tgl_mulai_periksa)); echo "<br>"; echo "<strong>Tanggal Selesai Periksa :</strong> &nbsp"; echo date('d/m/Y', strtotime($item->tgl_selesai_periksa));?>

                        </td>

                        <td>
                            <?php

                            if ($item->status_periksa == '1') {
                                echo '<strong>Pemeriksaan Baru</strong>';
                            } elseif($item->status_periksa == '2')  {
                                echo '<strong>Proses Pemeriksaan</strong>';
                            } else{
								 echo '<strong>Pemeriksaan Selesai</strong>';
							}
                            ?>
                        </td>


                        <td width="120" nowrap="">
                            <input type="hidden" class="key" value="<?php echo $item->$pk; ?>">
							<?php $is_riksa = $this->Pemeriksaan_model->is_riksa($item->$pk); ?>
							<?php if ($is_riksa == TRUE) { ?>
                                <button type="button" class="btn btn-sm btn-success btnVerifikasi" title="Input Klarifikasi"><i class="fa fa-check-square-o"></i></button>
                            <?php } ?>

                            <!-- cetak excel -->
							<?php if ($is_riksa <> TRUE) { ?>
								<button type="button" class="btn btn-sm btn-info btnCetakSurat" title="Masukkan Tanggal Klarifikasi" href="index.php/eaudit/pemeriksaan/add"><i class="fa fa-calendar-check-o"></i></button>
							<?php } ?>
								<a id="btn-cetak-kkp" data-idlhkpn="<?php echo $item->ID_LHKPN; ?>" class="btn btn-warning btn-xs btn-kkp" type="button" name="btn-cetak-kkp">Cetak KKP</a>
                        </td>
                    </tr>
                    <?php
                    $end = $i;
                }
                ?>
                <?php
                echo (count($items) == 0 ? '<tr><td colspan="7" class="items-null">Tidak ada data</td></tr>' : '');
                ?>
            </tbody>
        </table>
        <?php
    } else {
        echo 'Tidak ada data';
    }
    ?>
</div><!-- /.box-body -->

<script type="text/javascript">
    function returntovalidation(param) {
        confirm("Apakah anda akan mengembalikan laporan ke Validator?", function () {
            $('#loader_area').show();
            $.ajax({
                url: '<?php echo base_url();?>ever/verification/returntovalidation/'+param,
                dataType: 'json',
                success: function (data) {
                    $('#loader_area').hide();
                    alert(data.msg);
                    url = 'index.php/ever/verification/index/lhkpn/';
                    ng.LoadAjaxContent(url);
                }
            });

        }, "Konfirmasi Pengembalian Laporan", undefined, "YA", "TIDAK");
    };
    function getPn(ele) {
        var url = $(ele).attr('href');
        $.get(url, function(html) {
            OpenModalBox('Detail PN', html, '', 'standart');
        });

        return false;
    }
    function getConfm(ele) {
        var url = $(ele).attr('href');
        $.get(url, function(html) {
            OpenModalBox('Konfirmasi Status lhkpn', html, '', 'standart');
        });

        return false;
    }

    $(document).ready(function() {
		 $('.date-picker').datepicker({
			format: 'dd/mm/yyyy'
	    });

        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });

        $(".btnVerifikasi").click(function() {
            var key = $(this).parents('td').children('.key').val();
            // var url = '<?php echo $urlDisplay; ?>' + key + '/pemeriksaan';
            var url = 'index.php/eaudit/klarifikasi/index/' + key;
            ng.LoadAjaxContent(url);
            return false;
			///$("#myModal2").modal('show');
        });
        $(".btnHistory").click(function() {
            var key = $(this).parents('td').children('.key').val();
            var url = '<?php echo $urlDisplay; ?>' + key + '/history';
            $.post(url, function(html) {
                // OpenModalBox('History <?php echo $title; ?>', html, '', 'large');
                OpenModalBox('History LHKPN', html, '', 'large');
            });
            return false;
        });

        $(".btnCetakSurat").click(function() {
			$('#loader_area').show();
			var key = $(this).parents('td').children('.key').val();
			var url = '<?php echo base_url();?>eaudit/pemeriksaan/add/'+key;
            //f_close = '<button class="btn btn-sm btn-primary" onClick="test()">Kirim Email</button><input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
            $.post(url, function (html) {
                OpenModalBox('Pemeriksaan', html, '', 'medium');
            });
            return false;
        });

        $(".btnTandaTerima").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            f_close = '<button class="btn btn-sm btn-primary" href="index.php/ever/verification/kirim_tandaterima/<?php echo $item->$pk;?>">Kirim Email</button><input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
            $.post(url, function (html) {
                OpenModalBox('Kirim Tanda Terima', html, '', 'large');
            });
            return false;
        });

        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });

        $('input[name="CARI[PN]"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/ereg/pn/getUser') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/ereg/pn/getUser') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
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
    });
    function tracking(ele)
    {
       var url = $(ele).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
        });
        return false;
    }
    $(document).click(function (e) {
        if ($(e.target).is('#myModal')) {
            CloseModalBox2();
        }

    });

    // $('#btn-cetak-kkp').click( function(e) {
    $('.btn-kkp').click( function(e) {
      e.preventDefault();
      console.log($(this).data('idlhkpn'));
      var id_lhkpn = $(this).data('idlhkpn');
      // window.location.href = <?php echo site_url('/index.php/eaudit/CetakKKP/testPHPSpreadsheet/'); ?> + '/index.php/eaudit/CetakKKP/testPHPSpreadsheet/'+ id_lhkpn;
      var url = '<?php echo site_url("/index.php/eaudit/CetakKKP/export_kkp"); ?>/'+ id_lhkpn;
      window.location.href = url;
    }) ;

</script>
