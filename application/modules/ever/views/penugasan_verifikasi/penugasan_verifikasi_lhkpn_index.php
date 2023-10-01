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
 * @package Views/ever/penugasan_verifikasi
 */

?>
<div class="box-header with-border">
<div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <!-- <button type="button" id="btnAdd" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Tambah Data</button> -->
            <!-- <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
            <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
            <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button> -->

        </div>      
    </div>   
    <!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
    <div class="col-md-12">
        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/ever/penugasan_verifikasi/index/lhkpn/">
            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
            <input type="hidden" name="limit" value="<?php echo @$limit; ?>" />
            <div class="box-body">
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Status Penugasan :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[STAT]" id="CARI_STAT">
                                    <option value="">-pilih Status-</option>
                                    <option value="1" <?php
                                    if (@$CARI['STAT'] == 1) {
                                        echo 'selected';
                                    };
                                    ?>>Belum Ditugaskan</option>
                                    <option value="2" <?php
                                    if (@$CARI['STAT'] == 2) {
                                        echo 'selected';
                                    };
                                    ?>>Sudah Ditugaskan</option>
                                        <!--                                    <option value="3" <?php
                                        //                                    if (@$CARI['STAT'] == 3) {
                                        //                                        echo 'selected';
                                        //                                    };
                                    ?>>Sudah Diverifikasi</option>-->
                                </select>
                                    <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" id="label_filter"></label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[FILTER_BELUM_DITUGASKAN]" id="CARI_FILTER_BELUM_DITUGASKAN">
                                    <option value="0" <?php
                                    if (@$CARI['FILTER_BELUM_DITUGASKAN'] == '0') {
                                        echo 'selected';
                                    };
                                    ?>>Semua</option>
                                    <option value="1" <?php
                                    if (@$CARI['FILTER_BELUM_DITUGASKAN'] == '1') {
                                        echo 'selected';
                                    };
                                    ?>>Kurang dari sama dengan 5 hari</option>
                                    <option value="2" <?php
                                    if (@$CARI['FILTER_BELUM_DITUGASKAN'] == '2') {
                                        echo 'selected';
                                    };
                                    ?>>lebih dari 5 hari</option>
                                    <option value="3" <?php
                                    if (@$CARI['FILTER_BELUM_DITUGASKAN'] == '3') {
                                        echo 'selected';
                                    };
                                    ?>>lebih dari 10 hari</option>
                                </select>

                                <select class="form-control" name="CARI[FILTER_SUDAH_DITUGASKAN]" id="CARI_FILTER_SUDAH_DITUGASKAN">
                                    <option value="0" <?php
                                    if (@$CARI['FILTER_SUDAH_DITUGASKAN'] == '0') {
                                        echo 'selected';
                                    };
                                    ?>>Semua</option>
                                    <option value="1" <?php
                                    if (@$CARI['FILTER_SUDAH_DITUGASKAN'] == '1') {
                                        echo 'selected';
                                    };
                                    ?>>Kurang dari sama dengan 3 hari</option>
                                    <option value="2" <?php
                                    if (@$CARI['FILTER_SUDAH_DITUGASKAN'] == '2') {
                                        echo 'selected';
                                    };
                                    ?>>Lebih dari 3 hari</option>
                                    <option value="3" <?php
                                    if (@$CARI['FILTER_SUDAH_DITUGASKAN'] == '3') {
                                        echo 'selected';
                                    };
                                    ?>>Lebih dari 7 hari</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="filter_via_entry">
                            <label class="col-sm-4 control-label">Entry Via :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[ENTRY_VIA]" id="CARI_ENTRY_VIA">
                                    <option value="">-pilih Entry Via-</option>
                                    <option value="0" <?php
                                    if (@$CARI['ENTRY_VIA'] == '0') {
                                        echo 'selected';
                                    };
                                    ?>>Online</option>
                                    <option value="1" <?php
                                    if (@$CARI['ENTRY_VIA'] == '1') {
                                        echo 'selected';
                                    };
                                    ?>>Excel</option>
                                    <!--                                    <option value="3" <?php
                                    //                                    if (@$CARI['STAT'] == 3) {
                                    //                                        echo 'selected';
                                    //                                    };
                                    ?>>Sudah Diverifikasi</option>-->
                                </select>
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group" id="filter_status_lhkpn">
                            <label class="col-sm-4 control-label">Status lhkpn :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[STATUS_LHKPN]" id="STATUS_LHKPN">
                                    <option value="">-pilih Status lhkpn-</option>
                                    <option value="1" <?php
                                    if (@$CARI['STATUS_LHKPN'] == '1') {
                                        echo 'selected';
                                    };
                                    ?>>Proses Verifikasi</option>
                                    <option value="2" <?php
                                    if (@$CARI['STATUS_LHKPN'] == '2') {
                                        echo 'selected';
                                    };
                                    ?>>Perlu Perbaikan</option>
                                    <option value="3" <?php
                                    if (@$CARI['STATUS_LHKPN'] == '3') {
                                        echo 'selected';
                                    };
                                    ?>>Sudah Diperbaiki</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Formulir Aktifasi :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[AKTIFASI]" id="CARI_AKTIFASI">
                                    <option value="">-pilih Status-</option>
                                    <option value="0" <?php
                                    if (@$CARI['AKTIFASI'] == '0') {
                                        echo 'selected';
                                    };
                                    ?>>Belum</option>
                                    <option value="1" <?php
                                    if (@$CARI['AKTIFASI'] == '1') {
                                        echo 'selected';
                                    };
                                    ?>>Sudah</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Verifikator :</label>
                            <div class="col-sm-5">
                                <!-- <select name="CARI[PETUGAS]" id="CARI_PETUGAS">
                                </select> -->
                                <input type="text" class="form-control" name="CARI[PETUGAS]" id="CARI_PETUGAS" placeholder="Verifikator" value="<?php echo @$CARI['PETUGAS']; ?>">
                                <!--<input type="text" class="form-control" name="CARI[PETUGAS]" id="CARI_PETUGAS" placeholder="Verifikator" style="border: none; padding: 6px 0px;" value="<?php echo @$CARI['PETUGAS']; ?>">-->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Lembaga :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA']; ?>" id="CARI_LEMBAGA">
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Unit Kerja :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="CARI[UNIT_KERJA]" placeholder="UNIT KERJA" value="<?php echo @$CARI['UNIT_KERJA']; ?>" id="CARI_UNIT_KERJA">
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Status Pelaporan Sebelumnya :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[STATUS_LHKPN_SEBELUMNYA]" id="CARI_STATUS_LHKPN_SEBELUMNYA">
                                    <option value="0" <?php
                                    if (@$CARI['STATUS_LHKPN_SEBELUMNYA'] == '0') {
                                        echo 'selected';
                                    };
                                    ?>>Semua</option>
                                    <option value="1" <?php
                                    if (@$CARI['STATUS_LHKPN_SEBELUMNYA'] == '1') { 
                                        echo 'selected';
                                    };
                                    ?>>Diumumkan Lengkap</option>
                                    <option value="2" <?php
                                    if (@$CARI['STATUS_LHKPN_SEBELUMNYA'] == '2') {
                                        echo 'selected';
                                    };
                                    ?>>Diumumkan Tidak Lengkap</option>
                                    <option value="3" <?php
                                    if (@$CARI['STATUS_LHKPN_SEBELUMNYA'] == '3') {
                                        echo 'selected';
                                    };
                                    ?>>Terverifikasi Lengkap</option>
                                    <option value="4" <?php
                                    if (@$CARI['STATUS_LHKPN_SEBELUMNYA'] == '4') {
                                        echo 'selected';
                                    };
                                    ?>>Terverifikasi Tidak Lengkap</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tahun Lapor :</label>
                            <div class="col-sm-5">
                                <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN LAPOR" value="<?php echo @$CARI['TAHUN']; ?>" id="CARI_TAHUN">
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tahun Kirim Final :</label>
                            <div class="col-sm-5">
                                <input type="text" class="year-picker form-control" name="CARI[TAHUN_KIRIM_FINAL]" placeholder="TAHUN KIRM FINAL" value="<?php echo @$CARI['TAHUN_KIRIM_FINAL']; ?>" id="CARI_TAHUN_KIRIM_FINAL">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Eselon :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[ESELON]" id="CARI_ESELON">
                                    <option value="">-pilih Eselon-</option>
                                    <option value="1" <?php
                                    if (@$CARI['ESELON'] == 1) {
                                        echo 'selected';
                                    };
                                    ?>>0</option>
                                    <option value="12" <?php
                                    if (@$CARI['ESELON'] == 12) {
                                        echo 'selected';
                                    };
                                    ?>>I</option>
                                    <option value="13" <?php
                                    if (@$CARI['ESELON'] == 13) {
                                        echo 'selected';
                                    };
                                    ?>>II</option>
                                    <option value="14" <?php
                                    if (@$CARI['ESELON'] == 14) {
                                        echo 'selected';
                                    };
                                    ?>>III</option>
                                    <option value="15" <?php
                                    if (@$CARI['ESELON'] == 15) {
                                        echo 'selected';
                                    };
                                    ?>>IV</option>
                                    <option value="11" <?php
                                    if (@$CARI['ESELON'] == 11) {
                                        echo 'selected';
                                    };
                                    ?>>Non Eselon</option>
                                </select>
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">UU :</label>
                            <div class="col-sm-5">
                                <!--<select class="form-control" name="CARI[ESELON]">-->
                                    <label class="radio-inline">
                                        <input type="radio" name="CARI[UU]" id="CARI_UU" value="1" <?php echo ($CARI['UU'] == '1') ? 'checked' : ''; ?>>UU
                                        <!--<input type="radio" name="CARI[UU]" value="1" <?php if (@$CARI['UU'] == 1) { echo 'checked';}; ?> >UU-->
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="CARI[UU]" id="CARI_UU" value="0" <?php echo ($CARI['UU'] == '0') ? 'checked' : ''; ?>>NON UU
                                        <!--<input type="radio" name="CARI[UU]" value="2" <?php if (@$CARI['UU'] == 2) { echo 'checked';}; ?> >NON UU-->
                                    </label>
                                <!--</select>-->
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Rangkap Jabatan :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[RANGKAP]" id="CARI_RANGKAP">
                                    <option value="">-pilih Rangkap-</option>
                                    <option value="YA" <?php
                                    if (@$CARI['RANGKAP'] == "YA") {
                                        echo 'selected';
                                    };
                                    ?>>YA</option>
                                    <option value="TIDAK" <?php
                                    if (@$CARI['RANGKAP'] == "TIDAK") {
                                        echo 'selected';
                                    };
                                    ?>>TIDAK</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Cari :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                            <div class="form-group">
                                <div class="col-col-sm-3 col-sm-offset-4-2">
                                    <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
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
<!-- </div> -->
<!-- /.box-header -->
<div class="col-md-12">
    <div class="col-md-6">
        <div class="form-inline form-group">
            <label>Tampilkan</label>
            <select class="form-control" name="limit" id="limit">
                <option value="10" <?= ($limit == '10') ? 'selected' : ''; ?>>10</option>
                <option value="50" <?= ($limit == '50') ? 'selected' : ''; ?>>50</option>
                <option value="100" <?= ($limit == '100') ? 'selected' : ''; ?>>100</option>
            </select>
            <label>baris/halaman</label>
        </div>
    </div>
</div>
<div class="box-body">
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom tabelBody" >
        <thead>
            <tr>
                <th width="30" class="showWhenEditable"><input type="checkbox" onClick="chk_all(this);" title="Check ALL" /></th>
                <th width="30">No.</th>
                <th>Nama / No. Agenda</th>
                <th>Jabatan</th>
                <th>Unit Kerja</th>
                <th>Lembaga</th>
                <th>Tanggal Kirim Final</th>
                <!-- <th class="hidden-xs hidden-sm">Eselon</th> -->
                <!-- <th class="hidden-xs hidden-sm">Unit Kerja</th> -->
                <!-- <th>Lembaga</th> -->
                <!--                <th class="hidden-xs hidden-sm">Jenis</th>-->
                <!-- <th class="hidden-xs hidden-sm">Status</th> -->
                <th>Status LHKPN</th>
                <!--th>Assigner</th-->
                <!--th>Tanggal Penugasan</th-->
                <!--th>Due Date</th-->
                <th>Status Penugasan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total_rows) { ?>
                <?php
                $i = 0 + $offset;
                $start = $i + 1;
                $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
                $aStatus = ['0' => 'Draft', '1' => 'Proses Verifikasi', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi Lengkap', '4' => 'Diumumkan Lengkap',  '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Ditolak'];
                $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
                $aId = @explode(',', $id);

                foreach ($items as $item) {

                    $date = $item->tgl_kirim_final;
                    $tgl_kirim_final = strtotime($date);

                    $tgl_kirim_max_5 = date("Y-m-d", strtotime("+5 days", $tgl_kirim_final)); //5 hari setelah kirim final 
                    $tgl_kirim_max5_to_time = strtotime($tgl_kirim_max_5); //(dalam time)

                    $tgl_tugas_max_10 = date("Y-m-d", strtotime("+10 days", $tgl_kirim_final)); //10 hari setelah ditugaskan 
                    $tgl_kirim_max10_to_time = strtotime($tgl_tugas_max_10); //(dalam time)
                   
                    $now = Date("Y-m-d");
                    $now_in_time = strtotime($now);

                    $tgl_tugas = $item->TANGGAL_PENUGASAN;
                    $tgl_tugas_to_time = strtotime($tgl_tugas);

                    $tgl_tugas_max_3_hari = date("Y-m-d", strtotime("+3 days", $tgl_tugas_to_time)); //3 hari setelah ditugaskan 
                    $tgl_tugas_max3_to_time = strtotime($tgl_tugas_max_3_hari);

                    $tgl_tugas_max_7_hari = date("Y-m-d", strtotime("+7 days", $tgl_tugas_to_time)); //7 hari setelah ditugaskan 
                    $tgl_tugas_max7_to_time = strtotime($tgl_tugas_max_7_hari);

                    $sebelum_ditugaskan = $item->STAT == '' && ($item->STATUS == 1 || $item->STATUS == 2);
                    $sudah_ditugaskan = ($item->STAT == 2 || $item->STATUS == 3) && ($item->IS_ACTIVE_VERIF != 1 || $item->ID_VERIF == null);

                    $agenda = date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                    ?>
                    <tr <?php if($sebelum_ditugaskan && $now_in_time > $tgl_kirim_max5_to_time && $now_in_time <= $tgl_kirim_max10_to_time){ //belum ditugaskan & lebih 5 hari dari tanggal kirim final ?> 
                        style="background: #ffff99";
                   <?php  } else if($sebelum_ditugaskan && $now_in_time > $tgl_tugas_max10_to_time && $now_in_time > $tgl_kirim_max5_to_time){ //belum ditugaskan & lebih 10 hari dari tanggal kirim final ?> 
                        style="background: #FF9999";
                   <?php  } else if($sudah_ditugaskan && $now_in_time > $tgl_tugas_max3_to_time && $now_in_time <= $tgl_tugas_max7_to_time){ //sudah ditugaskan & status belum verifikasi lebih 3 hari dari tgl tugas ?> 
                        style="background: #ffff99";
                   <?php  } else if($sudah_ditugaskan && $now_in_time > $tgl_tugas_max7_to_time){ //sudah ditugaskan & status belum verifikasi lebih 7 hari dari tgl tugas ?> 
                        style="background: #FF9999";
                    <?php } ?> >
                    
                        <td class="showWhenEditable">
                    <!--                            <span style="display: none;" class="lhkpn"><?php echo substr(md5($item->ID_LHKPN), 5, 8); ?></span>
                            <span style="display: none;" class="nik"><?php echo $item->NIK; ?></span>-->
                            <span class="lhkpn" style="display: none;"><?php echo substr(md5($item->ID_LHKPN), 5, 8); ?></span>
                            <span class="lhkpnori" style="display: none;"><?php echo $item->ID_LHKPN; ?></span>
                            <span class="nik" style="display: none;"><?php echo $item->NIK; ?></span>
                            <span class="tgl_lapor" style="display: none;"><?php echo date('Y', strtotime($item->tgl_lapor)); ?></span>
                            <span class="jenis_laporan" style="display: none;"><?php echo $item->JENIS_LAPORAN == '4' ? 'R' : 'K'; ?></span>
                            <?php if ($cari_stat == 1) {
                    //                                echo (in_array($item->ID_LHKPN_DIJABATAN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="' . $item->ID_LHKPN_DIJABATAN . '" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="' . $item->ID_LHKPN_DIJABATAN . '" onclick="chk(this);" />');
                                echo (in_array($item->ID_LHKPN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="' . $item->ID_LHKPN . '" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="' . $item->ID_LHKPN . '" onclick="chk(this);" />');
                            } ?>
                            
                            <?php 
                    //                            echo '<input class="chk" type="checkbox" value="' . $item->ID_LHKPN . '" onclick="chk(this);" />';
                            ?>
                        </td>
                        <td><?php echo ++$i; ?>.</td>
                        <td class="agenda">
                            <a href="index.php/eano/announ/getInfoPn/<?php echo $item->ID_PN; ?>" onClick="return getPn(this);" class="nama"><?php echo $item->NAMA_LENGKAP; ?></a><br>
                            <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo $item->ID_LHKPN; ?>" onclick="return tracking(this)"><?php echo $agenda; ?></a><br>
                            <?php if($item->IS_FORMULIR_EFILLING==0){  ?>
                            <b><font color="red">Formulir Aktifasi Efilling Belum Diterima</font></b>
                            <?php }elseif($item->IS_FORMULIR_EFILLING==1){  ?>
                            <b><font color="green">Formulir Aktifasi Efilling Sudah Diterima</font></b>
                            <?php }elseif($item->IS_FORMULIR_EFILLING==null){ }   ?>
                        </td>
                        <td><?php echo $item->NAMA_JABATAN; ?><br><?php echo '<strong>Rangkap Jabatan : '.$item->RANGKAP.'<strong>'; ?><br><?php echo $item->ESELON == '' ? '' : '<strong>Eselon : '.$item->ESELON.'<strong>'; ?></td>
                        <td><?php echo $item->UK_NAMA; ?></td>
                        <td><?php echo $item->INST_NAMA; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($item->tgl_kirim_final)); ?>
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
                            <br />
                            <?php echo $entry; ?><?php echo ($item->ENTRY_VIA == '0' ? $item->NAMA_LENGKAP : $item->USERNAME_ENTRI); ?>
                            <?php 
                                echo "<br><Strong>Status Sebelumnya:</Strong><br>";

                                if($item->STATUS_LHKPN_SEBELUMNYA != NULL){
                                    echo "<Strong>".$aStatus[$item->STATUS_LHKPN_SEBELUMNYA]."<Strong>";
                                }else{
                                    echo "&nbsp;&nbsp;-";
                                }
                           ?>
                        </td>
                    <!--                        <td>
                            <?php //                            var_dump($item);exit;
                            if ($item->NAMA_JABATAN) {
                                $j = explode('|', $item->NAMA_JABATAN);
                                $c_j = count($j);
                                echo '<ul>';
                                foreach ($j as $ja) {
                                    $jb = explode(':58:', $ja);
                                    if (@$jb[1] != '') {
                                        $idjb = $jb[0];
                                        $statakhirjb = $jb[1];
                                        $statakhirjbtext = $jb[2];
                                        $statmutasijb = $jb[3];
                                        if ($c_j > 1) { // rangkap jabatan
                                            if ($jb[5] == 1) // jabatan utama
                                                echo '<li>' . $jb[4] . '</li>';
                                        }else {
                                            echo '<li>' . $jb[4] . '</li>';
                                        }
                                    }
                                }
                                echo '</ul>';
                            }
                            ?>
                        </td>-->
                        <!-- <td class="hidden-xs hidden-sm"><?php echo $item->ESELON; ?></td> -->
                        <!-- <td class="hidden-xs hidden-sm"><?php echo $item->UNIT_KERJA; ?></td> -->
                        <!-- <td><?php echo $item->INST_NAMA; ?></td> -->
                        <!--                <td class="hidden-xs hidden-sm">--><?php //echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus';       ?><!--</td>-->
                        <!-- <td class="hidden-xs hidden-sm"><?php
                        //echo ($item->IS_SUBMITED == '1' ? 'Diterima <small>'.date('d-m-Y', strtotime($item->SUBMITED_DATE)).'</small></br>' : '');
                        //echo ($item->IS_VERIFIED == '1' ? 'Verifikasi <small>'.date('d-m-Y', strtotime($item->VERIFIED_DATE)).'</small></br>' : '');
                        //echo ($item->IS_PUBLISHED == '1' ? 'Announcement <small>'.date('d-m-Y', strtotime($item->PUBLISHED_DATE)).'</small></br>' : '');
                        ?>
                        </td> -->
                        <td>
                            <?php echo ($item->STATUS == '1' && ($item->ALASAN == '1' || $item->ALASAN == '2') ? 'Sudah diperbaiki' : $aStatus[$item->STATUS]) ?>
                            <?php
                            //if ($item->STATUS == '2') {
                            //     echo '(' . @$abStatus[@$item->ALASAN] . ')';
                            //}
                            ?>
                        </td>
                        <!--td><?php echo $item->USERNAME; ?></td-->
                        <!--td><?php
                        if ($item->TANGGAL_PENUGASAN != '') {
                            echo date('d/m/Y', strtotime($item->TANGGAL_PENUGASAN));
                        }
                        ?></td-->
                        <!--td><?php
                        if ($item->DUE_DATE != '') {
                            echo date('d-m-Y', strtotime($item->DUE_DATE));
                        }
                        ?></td-->
                        <td><?php
                            if ($item->STAT == '' && ($item->STATUS == 1 || $item->STATUS == 2)) {
                                echo 'Belum Ditugaskan';
                            } else {
                                if ($item->STAT == 2 || $item->STATUS == 3) {
                        //                                if ($item->STAT == 2 && ($item->STATUS == 1 || $item->STATUS == 2)) {
                                    echo 'Sudah Ditugaskan.<br />verifikator: ' . $item->USERNAME . ' (' . date('d/m/Y', strtotime($item->TANGGAL_PENUGASAN)) . ')';
                                } else {
                                    echo $item->STAT;
                                }
                            }
                            ?></td>
                        <!--td nowrap="">
                        <?php
                        if ($item->ID_TUGAS) {
                            ?>
                                                        <button type="button" class="btn btn-sm btn-default btn-detail"
                                                        href="<?php echo $urlEdit . '/' . $item->ID_TUGAS . '/detail'; ?>" title="Preview"><i
                                                        class="fa fa-search-plus"></i></button>
                                                        <button type="button" class="btn btn-sm btn-default btn-edit" href="<?php echo $urlEdit . '/' . $item->ID_TUGAS; ?>" title="Edit"><i
                                                        class="fa fa-pencil"></i></button>
                                                        <button type="button" class="btn btn-sm btn-default btn-delete" href="<?php echo $urlEdit . '/' . $item->ID_TUGAS . '/delete'; ?>" title="Delete"><i
                                                        class="fa fa-trash" style="color:red;"></i></button>
                            <?php
                        }
                        ?>
                        </td-->
                    <?php if ($item->entry_via == 0 && $item->back_to_draft != 1 && ($item->STATUS == 1 && ($item->ALASAN == NULL || $item->ALASAN == ''))): ?>
                        <td><button type="button" class="btn btn-sm btn-success" title="Kembalikan ke Draft" onclick="returntodraft('<?php echo $item->ID_LHKPN; ?>');"><i class="fa fa-repeat"></i></button></td>
                    <?php else: ?>
                        <td></td>
                    <?php endif; ?>
                    </tr>
                    <?php
                    $end = $i;
                }
                ?>
            <?php } else { ?>
                            <tr id="not-found">
                                <td colspan="10" align="center"><strong>Belum ada data</strong></td>
                            </tr>
            <?php } ?>
        </tbody>
    </table>
    <br />
    <br />

    <table class="table" style="width:40%;" id="legend_table_belum_ditugaskan">
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">Warna </th>
        <th scope="col">Keterangan </th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <th scope="row">1</th>
        <td style="background: #ffff99"></td>
        <td>Laporan belum ditugaskan lebih dari 5 hari sejak tanggal kirim final (sejak submit)</td>
        </tr>
        <tr>
        <th scope="row">2</th>
        <td style="background: #FF9999"></td>
        <td>Laporan belum ditugaskan lebih dari 10 hari sejak tanggal kirim final (sejak submit)</td>
        </tr>
    </tbody>
    </table>

    <table class="table" style="width:40%;" id="legend_table_sudah_ditugaskan">
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">Warna </th>
        <th scope="col">Keterangan </th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <th scope="row">1</th>
        <td style="background: #ffff99"></td>
        <td>Laporan sudah ditugaskan tapi belum dimulai verifikasi (belum klik tombol proses verifikasi) lebih dari 3 hari sejak ditugaskan</td>
        </tr>
        <tr>
        <th scope="row">2</th>
        <td style="background: #FF9999"></td>
        <td>Laporan sudah ditugaskan tapi belum dimulai verifikasi (belum klik tombol proses verifikasi) lebih dari 7 hari sejak ditugaskan</td>
        </tr>
    </tbody>
    </table>

    <br />
    <br />

    <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="f_bast();"title="Tambahkan ke Daftar Penugasan"><i class="fa fa-plus-square"></i> Daftar Penugasan</button>
        <!--<button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="f_bast();"title="Tambahkan ke BA Pengumuman"><i class="fa fa-plus-square"></i> BA Pengumuman</button>-->
    <br />
    <br />

    <table id="con-bast" class="table dataTable no-footer" style="clear: both;margin: 10px 0;">
        <!-- <table class="table "> -->
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
            <?php
            $i = 1;
            if (isset($item_selected)) {
                foreach (@$item_selected as $row):
                    ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $row->NIK ?></td>
                        <td><?php echo $row->NAMA ?></td>
                        <td><a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($row->ID_LHKPN_DIJABATAN), 5, 8); ?>" onclick="return tracking(this)"><?php echo date('Y', strtotime($row->tgl_kirim_final)) . '/' . ($row->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $row->NIK . '/' . $row->ID_LHKPN_DIJABATAN ?></a></td>
                        <td align="center"><button type="button" class="btn btn-default" onClick="f_batal(this);" data-id="<?php echo $row->ID_LHKPN_DIJABATAN ?>" >Hapus</button> </td>
                    </tr>
                    <?php
                    $i++;
                endforeach;
            }
            ?>
        <?php if (@$id == '') { ?>
                        <tr id="not-found">
                            <td colspan="5" align="center"><strong>Belum ada data</strong></td>
                        </tr>
        <?php } ?>
        </tbody>
    </table>

    <form method="post" name="ajaxFormPenugasan" id="ajaxFormPenugasan" action="index.php/ever/penugasan_verifikasi/lhkpn/save">
        <div id="wrapperFormPenugasan" class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo @$CARI['STATUS'] == 2 ? 'Ganti ' : ''; ?> Verifikator <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type="text" name="PETUGAS" id="PETUGAS" required placeholder="Verifikator" style="width: 200px;">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tanggal Penugasan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type="text" name="TANGGAL_PENUGASAN" value="<?php echo date('d/m/Y'); ?>" required placeholder='DD/MM/YYYY' class="form-control date-picker" style="width: 200px;">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Due Date <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type="text" name="DUE_DATE" value="<?php echo date('d/m/Y'); ?>" required placeholder='DD/MM/YYYY' class="form-control date-picker" style="width: 200px;">
                </div>
            </div>
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label">Keterangan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <textarea name="KETERANGAN"></textarea>
                </div>
            </div> -->
            <div class="form-group">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-8">
                    <input type="hidden" name="status" value="<?php echo @$CARI['STATUS']; ?>">
                    <input type="hidden" name="act" value="doinsert">
                    <input type="submit" name="" value="Simpan" class="btn btn-sm btn-primary simpan">
                    <input type="reset" name="" value="Batal" class="btn btn-sm btn-danger">
                </div>
            </div>
        </div>
    </form>
</div><!-- /.box-body -->

<script type="text/javascript">
    var idChk = [];
    var jml = 0;

    async function returntodraft(param) {
        const {value: alasan} = await swal({
            titleText: 'Apakah anda akan mengembalikan laporan ke Draft?',
            input: 'textarea',
            inputPlaceholder: 'Berikan alasan pada saat mengembalikan laporan ke draft (tidak boleh mengandung symbol)',
            inputAttributes: {
                maxlength: 50
            },
            // showCancelButton: true,
            width: '30%'
        })
        if (!(alasan)) {
            swal({
                type: 'error',
                titleText: 'Apabila proses ingin dilanjut, alasan tidak boleh kosong!',
                width: '30%'
            });
            return false;
        } else {
            string = alasan.replace(/[^a-zA-Z0-9 ]/g,'');
            $('#loader_area').show();
            $.ajax({
                url: '<?php echo base_url();?>ever/penugasan_verifikasi/SettoDraft/'+param+'/'+string,
                dataType: 'json',
                success: function (data) {
                    $('#loader_area').hide();
                    if(data.success == 1) {
                        swal({
                            type: 'success',
                            titleText: data.msg,
                            width: '30%'
                        });
                    } else {
                        swal({
                            type: 'error',
                            titleText: data.msg,
                            width: '30%'
                        });
                    }
                    // alert(data.msg);
                    url = 'index.php/ever/penugasan_verifikasi/index/lhkpn/';
                    ng.LoadAjaxContent(url);
                }
            });
        }
        // confirm("Apakah anda akan mengembalikan laporan ke Draft?", function () {
        //     $('#loader_area').show();
        //     $.ajax({
        //         url: '<?php //echo base_url();?>ever/penugasan_verifikasi/SettoDraft/'+param,
        //         dataType: 'json',
        //         success: function (data) {
        //             $('#loader_area').hide();
        //             alert(data.msg);
        //             url = 'index.php/ever/penugasan_verifikasi/index/lhkpn/';
        //             ng.LoadAjaxContent(url);
        //         }
        //     });
        // }, "Konfirmasi Kirim Email", undefined, "YA", "TIDAK");
    };
    
    function chk_all(ele)
    {
        if ($(ele).is(':checked')) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }

        $('.chk:visible').each(function() {
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

    function f_bast() {
        $('.chk').each(function() {
            var val = $(this).val();
            if ($(this).is(':checked') && $(this).is(':visible')) {
                jml++;
                $(this).hide();
                var table = $(this).closest('tr');
                var nik = $('.nik', table).text();
                var nama = $('.nama', table).text();
                var agenda = $('.agenda', table).text();
                var lhkpn = $('.lhkpn', table).text();

                $('#con-bast tbody').append('<tr><td align="center">' + jml + '</td><td>' + nik + '</td><td>' + nama + '</td><td><a href="index.php/efill/lhkpnoffline/tracking/show/' + lhkpn + '" onclick="return tracking(this);">' + agenda + '</a></td><td align="center"><button class="btn btn-default" data-id="' + val + '" type="button" onClick="f_batal(this);">Hapus</button></td></tr>');
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

    function count() {
        var jml = parseInt($('.chk:checked').length);
        $('#jml').text(jml);
        if (jml > 0) {
            $('#con-bast #not-found').hide();
        } else {
            $('#con-bast #not-found').show();
        }

        var tmo = $('#ajaxFormCari input[name="id"]').val(idChk.join());
    }

    function tracking(ele)
    {
        url = $(ele).attr('href');
        $.post(url, function(html) {
            OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
        });
        return false;
    }

    function getPn(ele) {
        var url = $(ele).attr('href');
        $.get(url, function(html) {
            OpenModalBox('Detail PN', html, '', 'standart');
        });

        return false;
    }

    $(document).ready(function() {


        $('#CARI_STAT').change(function(){

            var selected_value = $('#CARI_STAT').val();
            if(selected_value == 1){
                $('#filter_via_entry').show();
                $('#filter_status_lhkpn').hide();
                $('#CARI_FILTER_BELUM_DITUGASKAN').show();
                $('#CARI_FILTER_SUDAH_DITUGASKAN').hide();
                $("#CARI_FILTER_SUDAH_DITUGASKAN").val("0");

                $('#legend_table_belum_ditugaskan').show();
                $('#legend_table_sudah_ditugaskan').hide();
                $('#label_filter').html("Lama Waktu Belum Ditugaskan :");
            }else if(selected_value == 2){
                $('#filter_status_lhkpn').show();
                $('#filter_via_entry').hide();
                $('#CARI_FILTER_BELUM_DITUGASKAN').hide();
                $('#CARI_FILTER_SUDAH_DITUGASKAN').show();               
                $("#CARI_FILTER_BELUM_DITUGASKAN").val("0");

                $('#legend_table_belum_ditugaskan').hide();
                $('#legend_table_sudah_ditugaskan').show();
                $('#label_filter').html("Waktu Penugasan Verifikasi :");
            }

        }).trigger('change');

        $('#limit').change(function(){         
            var url = $('#ajaxFormCari').attr('action');

            var data = {
                'limit': $('#limit').val(),
                'CARI[STAT]': $('#CARI_STAT').val(),
                'CARI[ENTRY_VIA]': $('#CARI_ENTRY_VIA').val(),
                'CARI[AKTIFASI]': $('#CARI_AKTIFASI').val(),
                'CARI[PETUGAS]': $('#CARI_PETUGAS').val(),
                'CARI[LEMBAGA]': $('#CARI_LEMBAGA').val(),
                'CARI[UNIT_KERJA]': $('#CARI_UNIT_KERJA').val(),
                'CARI[TAHUN]': $('#CARI_TAHUN').val(),
                'CARI[TAHUN_KIRIM_FINAL]': $('#CARI_TAHUN_KIRIM_FINAL').val(),
                'CARI[ESELON]': $('#CARI_ESELON').val(),
                'CARI[UU]': $('input[name="CARI[UU]"]:checked').val(),
                'CARI[RANGKAP]': $('#CARI_RANGKAP').val(),
                'CARI[NAMA]': $('#CARI_NAMA').val(),
            };
            $.post(url, data, function(html){
                $("#ajax-content").html('');
                $("#ajax-content").html(html);
            })
        });

        var tmo = $('#ajaxFormCari input[name="id"]').val();
        if (tmo != '') {
            idChk = tmo.split(',');
        }

        count();
        $(".showWhenEditable").show();
        <?php
        // echo 'alert("'.@$CARI['STATUS'].'");';
        // echo '$("#wrapperFormPenugasan").hide();';
        // if(@$CARI['STAT']==1 || @$CARI['STAT']==2){
        //     echo '$("#wrapperFormPenugasan").show();';
        //     echo '$(".showWhenEditable").show();';
        // }else{
        //     echo '$("#wrapperFormPenugasan").hide();';
        //     echo '$(".showWhenEditable").hide();';
        // }
        ?>

        $('#ajaxFormPenugasan').submit(function() {
            $('#ajaxFormPenugasan input[name="id"]').val(idChk.join());
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
                    success: function(data, textStatus, jqXHR) {
                        msg = {
                            success: 'Data Berhasil Disimpan!',
                            error: 'Data Gagal Disimpan!'
                        };
                        if (data == 0) {
                            alertify.error(msg.error);
                        } else {
                            alertify.success(msg.success);
                        }
                        url = 'index.php/ever/penugasan_verifikasi/index/lhkpn';
                        ng.LoadAjaxContent(url);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
        $('#CEK_ALL').change(function() {
            $('.CEK_PENERIMAAN').prop('checked', $(this).prop("checked"));
        });
        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
        $(".btnVerifikasi").click(function() {
            key = $(this).parents('td').children('.key').val();
            url = '<?php echo $urlDisplay; ?>' + key + '/verifikasi';
            ng.LoadAjaxContent(url);
            return false;
        });
        $(".btnHistory").click(function() {
            key = $(this).parents('td').children('.key').val();
            url = '<?php echo $urlDisplay; ?>' + key + '/history';
            $.post(url, function(html) {
                // OpenModalBox('History <?php echo $title; ?>', html, '', 'large');
                OpenModalBox('History LHKPN', html, '', 'large');
            });
            return false;
        });
        $(".btnCetakSurat").click(function() {
            key = $(this).parents('td').children('.key').val();
            url = '<?php echo $urlDisplay; ?>' + key + '/cetaksurat';
            $.post(url, function(html) {
                // OpenModalBox('History <?php echo $title; ?>', html, '', 'large');
                OpenModalBox('Cetak Surat', html, '', 'large');
            });
            return false;
        });
        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });
        $(".btn-detail").click(function() {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Detail Penugasan', html, '');
            });
            return false;
        })

        $("#btn-add").click(function() {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Tambah Penugasan', html, '', 'large');
                ng.formProcess($("#ajaxFormAdd"), 'add', '<?php echo $thisPageUrl; ?>');
            });
            return false;
        });
        // ctrl + a
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.which === 65 || e.which === 97) {
                e.preventDefault();
                $('#btn-add').trigger('click');
                return false;
            }
        });
        $('.btn-edit').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Edit Penugasan', html, '', 'standart');
                ng.formProcess($("#ajaxFormEdit"), 'edit', '<?php echo $thisPageUrl; ?>');
            });
            return false;
        });
        $('.btn-delete').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Delete Penugasan', html, '', 'standart');
                ng.formProcess($("#ajaxFormDelete"), 'delete', '<?php echo $thisPageUrl; ?>');
            });
            return false;
        });
        $('.date-picker').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        $('#PETUGAS').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getUser/' . $role) ?>",
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
                    $.ajax("<?= base_url('index.php/share/reff/getUser/' . $role) ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                if (state.id == '0') {
                    return '-- Pilih Verifikator --';
                } else {
                    return '<strong>' + state.role + '</strong> : ' + state.name;
                }
            },
            formatSelection: function(state) {
                if (state.id == '0') {
                    return '-- Pilih Verifikator --';
                } else {
                    return '<strong>' + state.role + '</strong> : ' + state.name;
                }
            }
        });
        $('#CARI_PETUGAS').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getUserVerif/' . $role) ?>",
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
                    $.ajax("<?= base_url('index.php/share/reff/getUserVerif/' . $role) ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                // if (state.id == '0') {
                //     return '-- Pilih Verifikator --';
                // } else {
                //     return '<strong>' + state.role + '</strong> : ' + state.name;
                // }
                if (state.role == ''){
                    return state.name;
                }else{
                    return '<strong>' + state.role + '</strong> : ' + state.name;
                }
            },
            formatSelection: function(state) {
                // if (state.id == '0') {
                //     return '-- Pilih Verifikator --';
                // } else {
                //     return '<strong>' + state.role + '</strong> : ' + state.name;
                // }
                if (state.role == ''){
                    return state.name;
                }else{
                    return '<strong>' + state.role + '</strong> : ' + state.name;
                }
            }
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
            // $('input[name="UNIT_KERJA"]').prop('disabled', false);
            // $('input[name="JABATAN"]').prop('disabled', false);

            // $('input[name="UNIT_KERJA"]').select2('val', '');
            // $('input[name="JABATAN"]').select2('val', '');
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
        $(function() {
            $('#dt1, #con').dataTable({
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": true,
                "bInfo": false,
                "bAutoWidth": false,
            });
        });
        $('#ajaxFormPenugasan').submit(function() {
            $('.simpan').prop('disabled',true);
        });
        $('.btn-cetak').click(function(e) {
            e.preventDefault();
            
            var stat = ($('#CARI_STAT').val() == '') ? 'ALL' : $('#CARI_STAT').val();
            var entry = ($('#CARI_ENTRY_VIA').val() == '') ? 'ALL' : $('#CARI_ENTRY_VIA').val();
            var aktifasi = ($('#CARI_AKTIFASI').val() == '') ? 'ALL' : $('#CARI_AKTIFASI').val();
            var petugas = ($('#CARI_PETUGAS').val() == '') ? 'ALL' : $('#CARI_PETUGAS').val();
            var lembaga = ($('#CARI_LEMBAGA').val() == '') ? 'ALL' : $('#CARI_LEMBAGA').val();
            var uk = ($('#CARI_UNIT_KERJA').val() == '') ? 'ALL' : $('#CARI_UNIT_KERJA').val();
            var tahun = ($('#CARI_TAHUN').val() == '') ? 'ALL' : $('#CARI_TAHUN').val();
            var tahun_kirim_final = ($('#CARI_TAHUN_KIRIM_FINAL').val() == '') ? 'ALL' : $('#CARI_TAHUN_KIRIM_FINAL').val();
            var eselon = ($('#CARI_ESELON').val() == '') ? 'ALL' : $('#CARI_ESELON').val();
            var uu = ($('input[name="CARI[UU]"]:checked').val()) ? $('input[name="CARI[UU]"]:checked').val() : 'ALL';
            var rangkap = ($('#CARI_RANGKAP').val() == '') ? 'ALL' : $('#CARI_RANGKAP').val();
            var nama = ($('#CARI_NAMA').val() == '') ? 'ALL' : $('#CARI_NAMA').val();

            var belum_ditugaskan = ($('#CARI_FILTER_BELUM_DITUGASKAN').val() == '') ? 'ALL' : $('#CARI_FILTER_BELUM_DITUGASKAN').val();
            var sudah_ditugaskan = ($('#CARI_FILTER_SUDAH_DITUGASKAN').val() == '') ? 'ALL' : $('#CARI_FILTER_SUDAH_DITUGASKAN').val();
            var status_lhkpn_sebelumnya = ($('#CARI_STATUS_LHKPN_SEBELUMNYA').val() == '') ? 'ALL' : $('#CARI_STATUS_LHKPN_SEBELUMNYA').val();
            var status_lhkpn = ($('#STATUS_LHKPN').val() == '') ? 'ALL' : $('#STATUS_LHKPN').val();
        
            var url = '<?php echo site_url("/index.php/ever/Cetak/export"); ?>/' + stat +'/' + entry +'/' + aktifasi +'/'+ petugas +'/' + lembaga +'/' + uk +'/' + tahun +'/' + tahun_kirim_final +'/' + eselon  +'/' + uu +'/' + rangkap +'/' + nama +'/' + belum_ditugaskan +'/' + sudah_ditugaskan +'/' + status_lhkpn_sebelumnya + '/' + status_lhkpn;
            window.location.href = url;
            return;
        });
    });
</script>