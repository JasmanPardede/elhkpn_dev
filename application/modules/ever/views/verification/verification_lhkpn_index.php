<style type="text/css">
    .harta-header {
        padding-bottom: 2px;
        padding-top: 2px;
        padding-left: 11px;
        background-color: #2B4458;
        color: white;
    }

    .judul-header {
        margin-left: -15px;
        margin-right: -15px;
        background-color: rgb(240, 240, 240);
        color: #000;
    }
    .pembilang[data-highlight="1"]{
        background-color:green;
        color:white;
    }
    .pembilang[data-highlight="2"]{
        background-color:yellow;
        color:black;
    }
    .pembilang[data-highlight="3"]{
        background-color: red;
        color:white;
    }

</style>

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
 * @package Views/ever/verification
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
        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/ever/verification/index/lhkpn/">
            <div class="box-body">
            <?php if($this->session->userdata('msg_verifikasi_cepat')){?>
                <input type="hidden" id="msg_verifikasi_cepat" value="<?=$this->session->userdata('msg_verifikasi_cepat'); ?>">
            <?php } ?>
            <div class="confirm"></div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Jenis Laporan :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[JENIS]">
                                    <option value="">-pilih Jenis-</option>
                                    <option value="1" <?php
                                    if (@$CARI['JENIS'] == 1) {
                                        echo 'selected';
                                    };
                                    ?>>Khusus, Calon</option>
                                    <option value="2" <?php
                                    if (@$CARI['JENIS'] == 2) {
                                        echo 'selected';
                                    };
                                    ?>>Khusus, Awal menjabat</option>
                                    <option value="3" <?php
                                    if (@$CARI['JENIS'] == 3) {
                                        echo 'selected';
                                    };
                                    ?>>Khusus, Akhir menjabat</option>
                                    <option value="4" <?php
                                    if (@$CARI['JENIS'] == 4) {
                                        echo 'selected';
                                    };
                                    ?>>Periodik tahunan</option>
                                </select>
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Status Laporan :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[STATUS]">
                                    <!--<option value="">-pilih Status-</option>-->
                                    <option value="1" <?php
                                    if (@$CARI['STATUS'] == 1) {
                                        echo 'selected';
                                    };
                                    ?>>Proses Verifikasi</option>
                                    <option value="9" <?php
                                    if (@$CARI['STATUS'] == 9) {
                                        echo 'selected';
                                    };
                                    ?>>Proses Verifikasi (Dikembalikan)</option>
                                    <option value="2" <?php
                                    if (@$CARI['STATUS'] == 2) {
                                        echo 'selected';
                                    };
                                    ?>>Perlu Perbaikan</option>
                                    <option value="8" <?php
                                    if (@$CARI['STATUS'] == 8) {
                                        echo 'selected';
                                    };
                                    ?>>Sudah Diperbaiki</option>
                                    <option value="3" <?php
                                    if (@$CARI['STATUS'] == 3) {
                                        echo 'selected';
                                    };
                                    ?>>Terverifikasi Lengkap</option>
                                    <option value="5" <?php
                                    if (@$CARI['STATUS'] == 5) {
                                        echo 'selected';
                                    };
                                    ?>>Terverifikasi tidak lengkap</option>
                                    <option value="7" <?php
                                    if (@$CARI['STATUS'] == 7) {
                                        echo 'selected';
                                    };
                                    ?>>Dikembalikan</option>
                                </select>
                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                            </div>
                        </div>
                        <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Verifikator :</label>
                            <div class="col-sm-5">
                                <!-- <select name="CARI[PETUGAS]" id="CARI_PETUGAS">
                                </select> -->
                                <input type="text" class="form-control" name="CARI[PETUGAS]" id="CARI_PETUGAS" placeholder="Verifikator" value="<?php echo @$CARI['PETUGAS']; ?>">
                                <!--<input type="text" class="form-control" name="CARI[PETUGAS]" id="CARI_PETUGAS" placeholder="Verifikator" style="border: none; padding: 6px 0px;" value="<?php echo @$CARI['PETUGAS']; ?>">-->
                            </div>
                        </div>
                        <?php } ?>
                        <?php if ($this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31 || $this->session->userdata('ID_ROLE') == 7) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Yang Menugaskan :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="CARI[MENUGASKAN]" id="CARI_MENUGASKAN" placeholder="Yang Menugaskan" value="<?php echo @$CARI['MENUGASKAN']; ?>">
                            </div>
                        </div>
                        <?php } ?>
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
                <!--div class="row">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Entri Via :</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="CARI[VIA]">
                                <option value="">-pilih Via-</option>
                                <option value="0" <?php
                if (@$CARI['VIA'] == '0') {
                    echo 'selected';
                };
                ?>>WL, Online</option>
                                <option value="1" <?php
                if (@$CARI['VIA'] == 1) {
                    echo 'selected';
                };
                ?>>Entry Hard Copy</option>
                                <option value="2" <?php
                if (@$CARI['VIA'] == 2) {
                    echo 'selected';
                };
                ?>>Import Excel</option>
                            </select>
                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                <!--/div>
                <div class="col-sm-3">

                </div>
            </div>
        </div-->
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
                                <select class="form-control" name="CARI[ESELON]">
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
                                <label class="radio-inline">
                                    <input type="radio" name="CARI[UU]" value="1">UU
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="CARI[UU]" value="0">NON UU
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Rangkap Jabatan :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[RANGKAP]">
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
                            <label class="col-sm-4 control-label">Info Surat Kuasa :</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="CARI[INFO_SK]" id="CARI_INFO_SK">
                                    <option value="">-pilih Surat Kuasa-</option>
                                    <option value="Lengkap" <?php
                                    if (@$CARI['INFO_SK'] == "Lengkap") {
                                        echo 'selected';
                                    };
                                    ?>>LENGKAP</option>
                                    <option value="Tidak Lengkap" <?php
                                    if (@$CARI['INFO_SK'] == "Tidak Lengkap") {
                                        echo 'selected';
                                    };
                                    ?>>BELUM LENGKAP</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Cari :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama/NIK/Email" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">
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
                    <th class="col-md-0" style="text-align:'center'">No.</th>
                    <th class="col-md-2">Nama / No Agenda</th>
                    <th class="col-md-2">Jabatan / Eselon</th>
                    <th class="col-md-2">Unit Kerja</th>
                    <th class="col-md-2">Lembaga</th>
                    <th class="col-md-2">Tanggal Kirim Final</th>
                    <!-- <th class="hidden-xs hidden-sm">Eselon</th> -->
                    <!-- <th class="hidden-xs hidden-sm">Unit Kerja</th> -->
                    <!-- <th>Lembaga</th> -->
                    <!-- <th class="hidden-xs hidden-sm">Jenis</th> -->
                    <!-- <th class="hidden-xs hidden-sm">Status</th> -->
                    <th class="col-md-2">Status LHKPN</th>
                    <?php if (@$CARI['STATUS'] == 2 || @$CARI['STATUS'] == 8) { ?>
                    <th class="col-md-1">Batas Akhir Respon </th>
                    <?php } ?>
                    <th class="col-md-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0 + $offset;
                $start = $i + 1;
                $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
                $aStatus = ['0' => 'Draft', '1' => 'Proses Verifikasi', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi', '4' => 'Diumumkan', '5' => 'Terverifikasi tidak lengkap', '7' => 'Dikembalikan'];

                $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
                foreach ($items as $item) {
                    $agenda = date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;



                    // $sql_history_lhkpn = "SELECT * FROM t_lhkpn_status_history where ID_LHKPN like $item->ID_LHKPN and ID_STATUS like 7 ORDER BY ID DESC LIMIT 1";
                    // $history_result = $this->db->query($sql_history_lhkpn)->result();
                    // if($history_result){
                    //     $days_history = "14 days";
                    //     $tgl_ver_history = date_create($history_result[0]->DATE_INSERT);
                    //     date_add($tgl_ver_history, date_interval_create_from_date_string($days_history));
                    //     $tgl_ver_diff = date_create(date_format($tgl_ver_history, 'Y-m-d'));
                    //     $tgl_ver_history = date_format($tgl_ver_history, 'd-m-Y');
                    //     $date_history = $tgl_ver_history;
                    // }else{
                    //     $date_history = "---";
                    // }
                    $date_history =  (!property_exists($item, 'DUE_DATE2') || $item->DUE_DATE2 == NULL) ? '---' : $item->DUE_DATE2;
                    $date_now = date_create(date('Y-m-d'));
                    if($date_history!='---'){
                        $diff_proses = date_diff(date_create($date_history),$date_now);
                        $diff_output = $diff_proses->format("%R%a");
                    }else{
                        $diff_output = 0;
                    }

                    
                    $v_h = isset($tolak) ? $diff->format("%R%a") : 0;

                    $tolak = $item->STATUS == '2' ? true : false;
                    $limit = $item->entry_via == '0' ? 14 : 14;
                    $tgl_now = date_create(date('Y-m-d'));
                    $tgl_set = date_create($item->TANGGAL);
                    $diff = date_diff($tgl_set, $tgl_now);
                    $v_h = $tolak ? $diff->format("%R%a") : 0;

                    //if ($v_h >= $limit) {
                    if ($diff_output > 0) {
                    // if (isset($item->DUE_DATE)) {
                        if (@$CARI['STATUS'] == 2){
                            $bgcolor = 'style="background-color: #dc4735"';
                            $ctk = TRUE;
                        } else if (@$CARI['STATUS'] == 8){
                            $bgcolor = 'style="background-color: #EAB543"';
                        }
                    } else {
                        $bgcolor = "";
                        $ctk = FALSE;
                    }
                    ?>
                    <tr <?= $bgcolor ?> >
                        <td style="text-align: center"><?= ++$i; ?>. </td>
                        <td>
                            <a href="index.php/eano/announ/getInfoPn/<?php echo $item->ID_PN; ?>" onClick="return getPn(this);"><?php echo $item->NAMA_LENGKAP; ?></a><br>
                            <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo $item->ID_LHKPN; ?>" onclick="return tracking(this)"><?php echo $agenda; ?></a>
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
                                $entry = "<img src='" . base_url() . "img/entry.png' title='di entry oleh'/> &nbsp;";
                            } else if ($item->entry_via == '1') {
                                echo "&nbsp; <img src='" . base_url() . "img/excel-icon.png' title='Via Excel'/>";
                                $entry = "<img src='" . base_url() . "img/validate.png' title='di validasi oleh'/> &nbsp;";
                            } else if ($item->entry_via == '2') {
                                echo "&nbsp; <img src='" . base_url() . "img/hard-copy.png' title='Via Hardcopy'/>";
                            }
                            ?>
                            <br />
                            <?php echo $entry; ?><?php echo ($item->entry_via == '0' ? $item->NAMA_LENGKAP : $item->USERNAME_ENTRI); ?>
                        </td>
                        <!--                        <td>
                            <?php
                            //                echo $item->NAMA_JABATAN.'<br/><br/><br/>';
                            if ($item->NAMA_JABATAN) {
                                $j = explode('|', $item->NAMA_JABATAN);
                                //                                display($j);
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
                        <!-- <td class="hidden-xs hidden-sm"><?php echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus'; ?></td> -->
            <!--                             <td class="hidden-xs hidden-sm"><?php
                        echo ($item->IS_SUBMITED == '1' ? 'Diterima <small>' . date('d-m-Y', strtotime($item->SUBMITED_DATE)) . '</small></br>' : '');
                        echo ($item->IS_VERIFIED == '1' ? 'Verifikasi <small>' . date('d-m-Y', strtotime($item->VERIFIED_DATE)) . '</small></br>' : '');
                        echo ($item->IS_PUBLISHED == '1' ? 'Announcement <small>' . date('d-m-Y', strtotime($item->PUBLISHED_DATE)) . '</small></br>' : '');
                        ?>
                        </td> -->
                        <td>
                            <?php
                            //                            if (@$item->STATUS_VERIFIKASI != '' && @$item->STATUS_VERIFIKASI == '0') {
                            //                                echo '<strong>Sedang Verifikasi</strong>';
                            ////                                echo $aStatus[5];
                            //                            } else
                            if ($item->STATUS == '1' && ($item->ALASAN == '1' || $item->ALASAN == '2') && $item->DIKEMBALIKAN == '0') {
                                echo '<strong>Sudah Diperbaiki</strong>';
                            } else if ($item->STATUS == '1' && $item->DIKEMBALIKAN > '0') {
                                echo '<strong>Proses Verifikasi (Dikembalikan)</strong>';
                            } else {
                                $count_dikembalikan = ($item->STATUS == '7') ? '('.$item->DIKEMBALIKAN.' kali)' : '';
                                if ($item->STATUS == '7') {
                                    echo '<strong>'.$aStatus[$item->STATUS].' ('.$item->DIKEMBALIKAN.' kali)</strong>';
                                } else {
                                    echo '<strong>'.$aStatus[$item->STATUS].'</strong>';
                                }
                            }
                            ?>
                            <br />
                            <?php
                            echo "<img src='" . base_url() . "img/Assignment.png' title='Koordinator Verifikasi'/> &nbsp;" . @$item->UPDATED_BY;
                            ?>
                            <br/>
                            <?php
                            echo "<img src='" . base_url() . "img/user-check.png' title='Verifikator'/> &nbsp;". @$item->USERNAME;
                            ?>
                            <br/>
                            <strong>Due Date:</strong> 
                            <?php
                            $days = "0 days";
                            $tgl_ver = date_create(@$item->DUE_DATE);
                            date_add($tgl_ver, date_interval_create_from_date_string($days));
                            $tgl_ver = date_format($tgl_ver, 'd-m-Y');
                            echo $tgl_ver;
                            ?>
                            <?php
                            //if ($item->STATUS == '2') {
                            //    echo '(' . @$abStatus[@$item->ALASAN] . getDataToVerif')';
                            //}
                            ?>
                        </td>

                        <?php if (@$CARI['STATUS'] == 2 || @$CARI['STATUS'] == 8) { ?>
                        <td>
                            <?php echo $date_history; ?>
                        </td>
                        <?php } ?>

                        <td width="120" nowrap="">
                            <input type="hidden" class="key" value="<?php echo $item->$pk; ?>">
                            <?php if ($item->STATUS != '2' && $item->STATUS != '3' && @$CARI['STATUS'] != '5' && @$CARI['STATUS'] != 7) { ?>
                                <?php if ($item->STATUS == '1' && $item->ALASAN == NULL && $item->DIKEMBALIKAN == '0') :  // tombol 'verif cepat' hanya muncul saat proses verif pertama kali  //  ?> 
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" onclick="getDataToVerif(<?= $item->ID_LHKPN ?>, <?= $item->$pk; ?>)"data-target="#myNewModal" title="Verif Cepat"><i class="fa fa-bolt"></i></button>
                                <?php endif ?>
                                <button type="button" class="btn btn-sm btn-success btnVerifikasi" title="Proses Verifikasi"><i class="fa fa-check-square-o"></i></button>
                                <?php $anu = $this->mglobal->get_oflline_status_by_id($item->$pk); ?>
                                <?php if ($item->entry_via == 1 && $anu == NULL): ?>
                                    <button class="btn btn-sm btn-info" onclick="returntovalidation('<?php echo $item->$pk; ?>')" title="Kembalikan ke Validator"><i class="fa fa-history"></i></button>
                                <?php endif ?>
                            <?php } ?>
                            <?php if ($item->STATUS == '3' || $item->STATUS == '5') { ?>
                                <button type="button" class="btn btn-sm btn-info btnTandaTerima" title="Kirim Ulang Tanda Terima" href="index.php/ever/verification/kirim_tandaterima/<?php echo $item->$pk;?>/<?php echo $item->entry_via;?>"><i class="fa fa-send"></i></button>
                                <a id="DownloadPDFII" class="btn btn-sm btn-success yesdownl" target="_blank" title="Download Tanda Terima" href="index.php/ever/verification/preview_tandaterima/<?php echo $item->$pk;?>/<?php echo $item->entry_via;?>"><i class="fa fa-download"></i></a>
                                <a id="cetak_final" class="btn btn-sm btn-success cetakikhtisar" title="Cetak ikhtisar" target="_blank" href="<?php echo base_url(); ?>ever/ikthisar/cetak/<?php echo $item->$pk; ?>"><i class="fa fa-print"></i></a>
                            <?php } ?>
                            <?php if ($tolak) { ?>
                                <?php /* if($item->STATUS == '2') : ?>   
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" onclick="getDataToVerif(<?= $item->ID_LHKPN ?>, <?= $item->$pk; ?>)"data-target="#myNewModal" title="Verif Cepat"><i class="fa fa-bolt"></i></button>
                                <?php endif */ ?>
                                <button type="button" class="btn btn-sm btn-info btnCetakSurat" title="Cetak Surat Verifikasi LHKPN Offline" href="index.php/ever/verification/pdf_detail/<?php echo $item->$pk;?>/<?php echo $item->entry_via; ?>"><i class="fa fa-file-pdf-o"></i></button>
                                <?php //if ($ctk) { ?>
                                    <!--<a href="index.php/ever/verification/ConfrStatusBerkas/<?= $item->ID_LHKPN ?>" onClick="return getConfm(this);" type="button" class="btn btn-sm btn-info btnSetData" title="Set Status Data"><i class="fa fa-edit"></i></a>-->
                                    <button type="button" class="btn btn-sm btn-success btnVerifikasi" title="Proses Verifikasi"><i class="fa fa-check-square-o"></i></button>
                                    <?php //}
                                } ?>
                            <!-- <button type="button" class="btn btn-sm btn-default btnHistory" title="Preview">History</button> -->
                            <!-- <button type="button" class="btn btn-sm btn-default btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button> -->
                            <!-- <button type="button" class="btn btn-sm btn-default btnEdit" title="Edit"><i class="fa fa-pencil"></i></button> -->
                            <!-- <button type="button" class="btn btn-sm btn-default btnDelete" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button> -->
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

    <!-- Modal -->
    <div id="myNewModal" class="modal fade" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
            <form action="<?php echo base_url();?>ever/verification/saveFinalVerif" method="post" id="myForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <!-- <p>Some text in the modal.</p> -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box-header with-border portlet-header judul-header">
                                    <h3 class="box-title">Surat Kuasa</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <!-- <table class="table table-bordered table-hover table-striped" id="tableSK">
                            <thead class="table-header">
                                <tr>
                                    <th>Surat Kuasa (pdf/doc(x)/jpg/png/jpeg)</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> -->
                        <?php 
                        $no = 1;
                        ?>
                        <table id="maintable" class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
                            <thead>
                                <tr>
                                    <th width="1%">No.</th>
                                    <th width="20%">Nama</th>
                                    <th width="14%">Keterangan</th>
                                    <th width="18%">Hubungan</th>
                                    <th width="12%">Status SK</th>
                                </tr>
                            </thead>
                            <tbody id="thedata"> 
                                    <tr> 
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-left" id="nama_lengkap"></td>
                                        <td class="text-center">PN</td>                   
                                        <td class="text-center">-</td>
                                        <td class="text-center"><input type="hidden" id="statusSK"><span id="stsSK"></span></td>
                                    </tr>
                            </tbody>
                        </table> 
                </div>
                </div>
                <div class="row">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
                <div class="harta-header">
                    <h3 class="box-title">I. Ringkasan Laporan Harta Kekayaan Penyelenggara Negara</h3>
                </div>
                <div class="box-body">
                    <div class="row justify-content-md-center">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                            <div>
                                <b id="jabatan_pn"></b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row justify-content-md-center">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                            <div class="box box-danger">
                                <div class="box-header">
                                    <h3 class="box-title"><b>I.1 REKAPITULASI HARTA KEKAYAAN</b></h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table class="table table-hover custom-hover">
                                        <tr>
                                            <td width="2%"></td>
                                            <td></td>
                                            <td width="8%" align="center"><b>Jumlah Asset</b></td>
                                            <td width="2%"></td>
                                            <td width="10%"><b>Total Nilai Asset</b></td>
                                            <td width="2%"></td>
                                            <td width="45%"><b>Terbilang</b></td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)</td>
                                            <td nowrap="" align="center"><p id="jml_asset_hartirak"></p></td>
                                            <td></td>
                                            <td nowrap="" align="right">
                                                <p id="HARTA1"></p>
                                            </td>
                                            <td></td>
                                            <td class="pembilang" id="TERBILANG_HARTA1"></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>HARTA BERGERAK(ALAT TRANSPORTASI DAN MESIN)</td>
                                            <td nowrap="" align="center"><p id="jml_asset_harger"></p></td>
                                            <td></td>
                                            <td nowrap="" align="right">
                                            <p id="HARTA2"></p>
                                            </td>
                                            <td></td>
                                            <td id="TERBILANG_HARTA2" class="pembilang"></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>HARTA BERGERAK LAINNYA</td>
                                            <td nowrap="" align="center"><p id="jml_asset_harger2"></td>
                                            <td></td>
                                            <td nowrap="" align="right">
                                                <p id="HARTA3"></p>
                                            </td>
                                            <td></td>
                                            <td id="TERBILANG_HARTA3" class="pembilang"></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>SURAT BERHARGA</td>
                                            <td nowrap="" align="center"><p id="jml_asset_suberga"></td>
                                            <td></td>
                                            <td nowrap="" align="right">
                                                <p id="HARTA4"></p>
                                            </td>
                                            <td></td>
                                            <td id="TERBILANG_HARTA4" class="pembilang"></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>KAS DAN SETARA KAS</td>
                                            <td nowrap="" align="center"><p id="jml_asset_kaseka"></td>
                                            <td></td>
                                            <td nowrap="" align="right">
                                                <p id="HARTA5"></p>
                                            </td>
                                            <td></td>
                                            <td id="TERBILANG_HARTA5" class="pembilang"></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>HARTA LAINNYA</td>
                                            <td nowrap="" align="center"><p id="jml_asset_harlin"></td>
                                            <td></td>
                                            <td nowrap="" align="right">
                                                <p id="HARTA6"></p>
                                            </td>
                                            <td></td>
                                            <td id="TERBILANG_HARTA6" class="pembilang"></td>
                                        </tr>
                                        <tr class="none">
                                            <td></td>
                                            <td><b>SUB-TOTAL HARTA</b></td>
                                            <td nowrap="" style="border-top: 1px solid #000;" align="center"><b></b></td>
                                            <td></td>
                                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b id="SUBTOTAL"></b></td>
                                            <td></td>
                                            <td id="TERBILANG_SUBTOTAL" class="pembilang"></td>
                                        </tr>
                                        <tr onclick="ITEM('link_harta', 'navTabHartaHutang');">
                                            <td>7</td>
                                            <td>HUTANG</td>
                                            <td nowrap="" align="center"><p id="jml_hutang"></p></td>
                                            <td></td>
                                            <td nowrap="" align="right">
                                                <p id="HUTANG"></p>
                                            </td>
                                            <td></td>
                                            <td id="TERBILANG_HUTANG" class="pembilang"></td>
                                        </tr>
                                        <tr class="none">
                                            <td></td>
                                            <td><b>TOTAL HARTA KEKAYAAN</b></td>
                                            <td nowrap="" style="border-top: 1px solid #000;" align="center"><b></b></td>
                                            <td></td>
                                            <td nowrap="" style="border-top: 1px solid #000;" align="right"><b id="TOTAL_HARTA"></b></td>
                                            <td></td>
                                            <td id="TERBILANG_TOTAL_HARTA" class="pembilang"></td>
                                        </tr>
                                    </table>
                                </div><!-- /.box-body -->
                            </div>
                            
                            <div class="box box-warning">
                                <div class="box-header">
                                    <h3 class="box-title"><b>I.2 REKAPITULASI PENERIMAAN KAS</b></h3>
                                </div>
                                <table class="table table-hover custom-hover">
                                    <tr>
                                        <td width="2%"></td>
                                        <td></td>
                                        <td width="10%"><b>Total Per Penerimaan</b></td>
                                        <td width="2%"></td>
                                        <td width="45%"><b>Terbilang</b></td>
                                    </tr>
                                    <?php
                                    // $totalPenerimaanPemasukanBeneran = 0;
                                    // $tot1 = '0';
                                    // $namecoden = 'A';
                                    // $nc = 'A';
                                    // foreach ($getGolongan1 as $key) :
                                    //     $nameClass = $nc++ . '_PENERIMAAN';
                                    // $keycode = $namecoden++;
                                    // $totalPenerimaanPemasukanBeneran += $totaln[$keycode];
                                        ?>
                                        <!-- <tr onclick="ITEM('link_penerimaan', '<?php //echo $nameClass; ?>');">
                                            <td></td>
                                            <td><?php // echo @$key->NAMA_GOLONGAN; ?></td>
                                            <td nowrap="" align="right">Rp. <?php //echo @number_format($totaln[$keycode], 0, ",", "."); ?></td>
                                        </tr> -->
                                    <tr>
                                        <td>A</td>
                                        <td>PENERIMAAN PEKERJAAN</td>
                                        <td nowrap="" align="right" id="A"></td>
                                        <td></td>
                                        <td id="TERBILANG_A" class="pembilang"></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>PENERIMAAN USAHA ATAU KEKAYAAN</td>
                                        <td nowrap="" align="right" id="B"></td>
                                        <td></td>
                                        <td id="TERBILANG_B" class="pembilang"></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>PENERIMAAN LAINNYA</td>
                                        <td nowrap="" align="right" id="C"></td>
                                        <td></td>
                                        <td id="TERBILANG_C" class="pembilang"></td>
                                    </tr>
                                    <tr class="none">
                                        <td></td>
                                        <td><b>TOTAL PENERIMAAN</b></td>
                                        <!--<td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp.</b></td>-->
                                        <td nowrap="" style="border-top: 1px solid #000;" align="right"><b id="TOTAL_PENERIMAAN"></b></td>
                                        <td></td>
                                        <td id="TERBILANG_TOTAL" class="pembilang"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title"><b>I.3 REKAPITULASI PENGELUARAN KAS</b></h3>
                                </div>
                                <table class="table table-hover custom-hover">
                                    <tr>
                                        <td width="2%"></td>
                                        <td></td>
                                        <td width="10%"><b>Total Per Pengeluaran</b></td>
                                        <td width="2%"></td>
                                        <td width="45%"><b>Terbilang</b></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>PENGELUARAN RUTIN</td>
                                        <td nowrap="" align="right" id="A2"></td>
                                        <td></td>
                                        <td id="TERBILANG_A2" class="pembilang"></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>PENGELUARAN HARTA</td>
                                        <td nowrap="" align="right" id="B2"></td>
                                        <td></td>
                                        <td id="TERBILANG_B2" class="pembilang"></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>PENGELUARAN LAINNYA</td>
                                        <td nowrap="" align="right" id="C2"></td>
                                        <td></td>
                                        <td id="TERBILANG_C2" class="pembilang"></td>
                                    </tr>
                                    <tr class="none">
                                        <td></td>
                                        <td><b>TOTAL PENGELUARAN</b></td>
                                        <td nowrap="" style="border-top: 1px solid #000;" align="right"><b id="TOTAL_PENGELUARAN"></b></td>
                                        <td></td>
                                        <td id="TERBILANG_TOTAL2" class="pembilang"></td>
                                    </tr>
                                    <tr class="none">
                                        <td></td>
                                        <td><b>PENERIMAAN BERSIH</b></td>
                                        <td  align="right" nowrap="" style="border-top: 1px solid #000;"><b id="BERSIH"></b></td>
                                        <td></td>
                                        <td id="TERBILANG_BERSIH" class="pembilang"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="pull-right" style="padding-right:5px;">
                                Hasil Verifikasi : 
                                <button type="button" class="btn-sm btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off" id="hasil-verifikasi">
                                    Yes to All
                                </button>
                            </div>
                            <br><br><br>
                            <div class="pull-right" style="padding-right:5px;">
                                <button type="button" class="btn btn-sm btn-warning btn-save-data" disabled="" >Simpan <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                            </div>
                            <br><br><br><br><br>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 modal-warning" style="padding: 0;">
                                <div class="modal-body col-md-12">
                                    <div class="col-md-2" align="right"><label>Hasil Verifikasi Final : </label></div>
                                    <div class="col-md-4">
                                        <div><label><input type="radio" name="final" class="terverif-lengkap" value="1" disabled> Terverifikasi Lengkap</label></div>
                                        <div id="verif-tdk-lengkap" style="display:none"><label><input type="radio" name="final" class="tidak-lengkap" value="3" disabled>
                                        Terverifikasi Tidak Lengkap
                                        </label></div>
                                    </div>
                                </div>
                            </div>
                            <br><br><br>          
                        </div>
                    </div>
                </div><!-- /.box-body -->
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnFinal" class="btn btn-primary" disabled=""><i class="fa fa-send-o"></i> Simpan Final Verifikasi</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-right: 10px;">Batal</button>
                    <br><br>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p style="float: left;"><strong>NB :</strong> Jika terdapat ketidak sesuaian pada data ikhtisar harta serta lampiran Surat Kuasa dan ingin melihat rincian harta lebih detail silahkan klik tombol &nbsp; 
                        <input type="hidden" id="key_verif" value="">
                        <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="">
                        <input type="hidden" id="tgl_ver_" name="tgl_ver_" value="">
                        
                        <!-- data for message -->
                        <input type="hidden" id="nama_lengkap_msg" name="nama_lengkap_msg" value="">
                        <input type="hidden" id="nama_instansi_msg" name="nama_instansi_msg" value="">
                        <input type="hidden" id="nama_jabatan_msg" name="nama_jabatan_msg" value="">
                        <input type="hidden" id="nama_bidang_msg" name="nama_bidang_msg" value="">
                        <input type="hidden" id="tahun_pelaporan_msg" name="tahun_pelaporan_msg" value="">
                        
                        <button type="button" class="btn btn-sm btn-success" title="Proses Verifikasi" onclick="toVerifDetail()"><i class="fa fa-check-square-o"></i></button></p>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div><!-- /.box-body -->

<script type="text/javascript">
    returntovalidation

    function getDataToVerif(param1, param2){

        event.preventDefault();

        $('.new-tr').remove();
        
        $.ajax({
            url: '<?php echo base_url();?>ever/verification/getDataToVerif/'+param1,
            type: 'GET',
            dataType: 'json',
            data: { 
                id_lhkpn : param1,
                },
            async: false,
            cache: false,
            success: function(result) {

                $('#key_verif').val(param2);

                //tombol tidak-lengkap 
                let status_dp = result.DATA_PRIBADI.STATUS;
                let alasan_dp = result.DATA_PRIBADI.ALASAN; 

                if((status_dp == '1' || status_dp == '2') && (alasan_dp == '1' || alasan_dp == '2')){
                    $("#verif-tdk-lengkap").show();
                }

                 //sinkron button yes to all or done
                if (result.VERIFICATIONS.length > 0) {
                    const dt_verif = JSON.parse(result.VERIFICATIONS[0].HASIL_VERIFIKASI);
                    const verif_val = dt_verif.VAL;

                    for (var key in verif_val) {
                        if(key != "SURATKUASAMENGUMUMKAN"){

                            const objValues = Object.values(verif_val);

                            if (objValues.indexOf("-1") != -1) {   //jika tidak lengkap
                                $('#hasil-verifikasi').removeClass('btn-success').addClass('btn-danger').html('Yes to All');
                                $('.terverif-lengkap').val("").prop('disabled', true);
                                $('.tidak-lengkap').val("").prop('disabled', true);
                                $('.btn-save-data').prop('disabled', true);
                            } else {
                                $('#hasil-verifikasi').removeClass('btn-danger').addClass('btn-success').html('Done <i class="fa fa-check"></i>');
                                $('.terverif-lengkap').prop('disabled', false);
                                $('.tidak-lengkap').prop('disabled', false);
                                $('.btn-save-data').prop('disabled', false);
                            }
                                                            
                        }
                    }
                }else{
                    $('#hasil-verifikasi').removeClass('btn-success').addClass('btn-danger').html('Yes to All');
                    $('.terverif-lengkap').val("").prop('disabled', true);
                    $('.tidak-lengkap').val("").prop('disabled', true);
                    $('.btn-save-data').prop('disabled', true);
                }

                var jenis_laporan = result.DATA_PRIBADI.JENIS_LAPORAN;

                var thn_lapor = '';
                if(jenis_laporan == "4"){   
                    date_lapor = result.DATA_PRIBADI.tgl_lapor;
                    thn_lapor = date_lapor.substring(0, 4);
                }else{
                    date_lapor = result.DATA_PRIBADI.tgl_lapor;
                    thn_lapor = date_lapor;
                } 
                
                $('#nama_lengkap_msg').val(result.DATA_PRIBADI.NAMA_LENGKAP);
                $('#nama_instansi_msg').val(result.JABATANS[0].INST_NAMA);
                $('#nama_jabatan_msg').val(result.JABATANS[0].NAMA_JABATAN);
                $('#nama_bidang_msg').val(result.JABATANS[0].BDG_NAMA);
                $('#tahun_pelaporan_msg').val(thn_lapor);

                ////////////      SURAT KUASA    //////////////
                var arr_hubungan = [];
                var no = 2;
                $.each(result.data_keluarga, function(index, value) {
                
                    if(value.umur_lapor != null && value.umur_lapor >= 17){
                        if (result.lhkpn_ver == '1.6' || result.lhkpn_ver == '1.8' || result.lhkpn_ver == '1.11'|| result.lhkpn_ver == '2.1') {
                        var pn = '-';
                            if(value.PN == 1){
                                if(value.HUBUNGAN == 2 || value.HUBUNGAN == 3){
                                    pn = "PN";
                                }else{
                                    pn = "-"
                                }
                            }
                            var arr_hubungan = { "2": "SUAMI", "3": "ISTRI", "4": "ANAK TANGGUNGAN", "5": "ANAK BUKAN TANGGUNGAN" };
                            if (value.HUBUNGAN == 2 || value.HUBUNGAN == 3 ||  value.HUBUNGAN == 4 && value.umur_lapor >= 17){
                                var myTr= "";
                                $('#maintable tbody').each(function (i, row) {
                                    myTr += "<tr class='new-tr'><td class='text-center'>"+ (no++) +"</td><td class='text-left'>"+ value.NAMA +"</td><td class='text-center'>"+ pn +"</td><td class='text-center'>"+ arr_hubungan[value.HUBUNGAN] +"</td><td class='text-center' id='stsSK_keluarga_"+index+"'><input type='hidden' id='statusSk_keluarga_"+index+"' value='"+value.FLAG_SK+"'></td></tr>";
                                
                                    $('#thedata').append(myTr);
                                });
                            }
                        } else {
                            var pn = '-';
                            if(value.PN == 1){
                                if(value.HUBUNGAN == 1 || value.HUBUNGAN == 2){
                                    pn = "PN";
                                }else{
                                    pn = "-"
                                }
                            }
                            var arr_hubungan = { "1": "ISTRI", "2": "SUAMI", "3": "ANAK TANGGUNGAN", "4": "ANAK BUKAN TANGGUNGAN", "5": "LAINNYA" }; 
                            if(value.HUBUNGAN == 1 || value.HUBUNGAN == 2 ||  value.HUBUNGAN == 3 && value.umur_lapor >= 17){
                                var myTr = "";
                                $('#maintable tbody').each(function (i, row) {
                                    myTr += "<tr class='new-tr'><td class='text-center'>"+ (no++) +"</td><td class='text-left'>"+ value.NAMA +"</td><td class='text-center'>"+ pn +"</td><td class='text-center'>"+ arr_hubungan[value.HUBUNGAN] +"</td><td class='text-center' id='stsSK_keluarga_"+index+"'><input type='hidden' id='statusSk_keluarga_"+index+"' value='"+value.FLAG_SK+"'></td></tr>";
                                
                                    $('#thedata').append(myTr);

                                });
                            } 
                        }
                    }
                    
                    var stat_sk_keluarga = $('#statusSk_keluarga_'+index).val();
                    if(stat_sk_keluarga == 0){
                        $('#stsSK_keluarga_'+index).text("Belum");
                    }else{
                        $('#stsSK_keluarga_'+index).text("Sudah");
                    }
                });

                $('#nama_lengkap').text(result.DATA_PRIBADI.NAMA_LENGKAP);
                $('#statusSK').val(result.DATA_PRIBADI.FLAG_SK);
                
                var verif_date = new Date();
                verif_date.setDate(verif_date.getDate() + 14);

                var dd = verif_date.getDate();
                var mm = verif_date.getMonth()+1; 
                var yyyy = verif_date.getFullYear();
                if(dd<10) 
                {
                    dd='0'+dd;
                } 

                if(mm<10) 
                {
                    mm='0'+mm;
                }

                verif_date = dd+'-'+mm+'-'+yyyy;
                $('#tgl_ver_').val(verif_date);
                $('#ID_LHKPN').val(result.DATA_PRIBADI.ID_LHKPN);

                var stat_sk =  $('#statusSK').val();
                if(stat_sk == 0){
                    $('#stsSK').text("Belum");
                }else{
                    $('#stsSK').text("Sudah");
                } 

                //////////   REVIEW HARTA  ///////////
                $('#jabatan_pn').html('Jabatan PN : '+result.JABATANS[0].NAMA_JABATAN);

                /// Rekapitulasi Penerimaan Kas ///
                var PN = JSON.parse(result.getPemka ? result.getPemka.NILAI_PENERIMAAN_KAS_PN : {});
                var jenis = result.jenis_penerimaan_kas_pn;
                
                var PA = JSON.parse(result.getPemka ? result.getPemka.NILAI_PENERIMAAN_KAS_PASANGAN : {});

                var jAPN = 0;
                var label = ['A', 'B', 'C']
                
                var jAP = 0;
                var JB = 0;
                var jC = 0;

                var code = "";
                var i;
                var total_A = 0;
                var total_B = 0;
                var total_C = 0;
                for (i = 0; i < jenis.length; i++) {
                    for(j = 0; j < jenis[i].length; j++){
                        // var PA_val = PA + j;
                        code = label[i] + j;
                        if(i == 0){

                            jAP = PN[label[i]][j][code];
                            //jAP = jAP + jAPN;
                            if ((typeof jAP) == 'string') {
                                jAP = jAP.replaceAll('.','');
                            }
                            total_A += parseInt(jAP);

                        }else if(i == 1){
                            JB = PN[label[i]][j][code];
                            if ((typeof JB) == 'string') {
                                JB = JB.replaceAll('.','');
                            }
                            total_B += parseInt(JB);
                        }else if(i == 2){
                            jC = PN[label[i]][j][code];
                            if ((typeof jC) == 'string') {
                                jC = jC.replaceAll('.','');
                            }
                            total_C += parseInt(jC);
                        }    
                                                
                    }
                }

                PA.forEach((item,index) => {
                    const code = 'PA'+index;
                    if ((typeof item[code]) == 'string') {
                        jC = jC.replaceAll('.','');
                        item[code] = item[code].replaceAll('.','');
                    }

                    jAPN += parseInt(item[code]);
                });

                jAP = jAP + jAPN;

                if(total_A != '' || jAPN != 0){

                    total_A = total_A + jAPN;

                    var total_pA = formatRupiah(''+total_A, 'Rp. ')
                }else{
                    var total_pA = 'Rp. 0';
                }
                if(total_B != ''){
                    var total_pB = formatRupiah(''+total_B, 'Rp. ')
                }else{
                    var total_pB = 'Rp. 0';
                }
                if(total_C != ''){
                    var total_pC = formatRupiah(''+total_C, 'Rp. ')
                }else{
                    var total_pC = 'Rp. 0';
                }

                var tot_penerimaan = total_A + total_B + total_C; 
                if(tot_penerimaan != ''){
                    var total_terima = formatRupiah(''+tot_penerimaan, 'Rp. ')
                }else{
                    var total_terima = 'Rp. 0';
                }

                $('#A').html(total_pA);
                $('#B').html(total_pB);
                $('#C').html(total_pC);
                $('#TOTAL_PENERIMAAN').html(total_terima);

                var terbilang_A = terbilang(''+total_A);
                document.getElementById("TERBILANG_A").setAttribute('data-highlight',nominal_in_milyar(total_A));
                $('#TERBILANG_A').html(terbilang_A);

                var terbilang_B= terbilang(''+total_B);
                document.getElementById("TERBILANG_B").setAttribute('data-highlight',nominal_in_milyar(total_B));
                $('#TERBILANG_B').html(terbilang_B);

                var terbilang_C= terbilang(''+total_C);
                document.getElementById("TERBILANG_C").setAttribute('data-highlight',nominal_in_milyar(total_C));
                $('#TERBILANG_C').html(terbilang_C);

                var terbilang_tot_terima= terbilang(''+tot_penerimaan);
                document.getElementById("TERBILANG_TOTAL").setAttribute('data-highlight',nominal_in_milyar(tot_penerimaan));
                $('#TERBILANG_TOTAL').html(terbilang_tot_terima);

                /// Rekapitulasi Pengeluaran Kas ///
                var PN_keluar = JSON.parse(result.getPenka ? result.getPenka.NILAI_PENGELUARAN_KAS : {});                
                var jenis_keluar = result.jenis_pengeluaran_kas_pn;

                var jpA = 0;
                var jpB = 0;
                var jpC = 0;
                var total_A2 = 0;
                var total_B2 = 0;
                var total_C2 = 0;
                
                for (i = 0; i < jenis_keluar.length; i++) {
                    for(j = 0; j < jenis_keluar[i].length; j++){
                        code = label[i] + j;
                        if(i == 0){
                            jpA = PN_keluar[label[i]][j][code];
                            if ((typeof jpA) == 'string') {
                                jpA = jpA.replaceAll('.','');
                            }
                            total_A2 += parseInt(jpA);
                        }else if(i == 1){
                            jpB = PN_keluar[label[i]][j][code];
                            if ((typeof jpB) == 'string') {
                                jpB = jpB.replaceAll('.','');
                            }
                            total_B2 += parseInt(jpB);
                        }else if(i == 2){
                            jpC = PN_keluar[label[i]][j][code];
                            if ((typeof jpC) == 'string') {
                                jpC = jpC.replaceAll('.','');
                            }
                            total_C2 += parseInt(jpC);
                        }    
                    }
                }

                if(total_A2 != ''){
                    var total_jpA = formatRupiah(''+total_A2, 'Rp. ')
                }else{
                    var total_jpA = 'Rp. 0';
                }
                if(total_B2 != ''){
                    var total_jpB = formatRupiah(''+total_B2, 'Rp. ')
                }else{
                    var total_jpB = 'Rp. 0';
                }
                if(total_C2 != ''){
                    var total_jpC = formatRupiah(''+total_C2, 'Rp. ')
                }else{
                    var total_jpC = 'Rp. 0';
                }
                var tot_pengeluaran = total_A2 + total_B2 + total_C2; 
                if(tot_pengeluaran != ''){
                    var total_keluar = formatRupiah(''+tot_pengeluaran, 'Rp. ')
                }else{
                    var total_keluar = 'Rp. 0';
                }

                var penerimaan_bersih = tot_penerimaan - tot_pengeluaran;
                if(penerimaan_bersih != ''){
                    var total_bersih = formatRupiah(''+penerimaan_bersih, 'Rp. ')
                }else{
                    var total_bersih = 'Rp. 0';
                }

                $('#A2').html(total_jpA);
                $('#B2').html(total_jpB);
                $('#C2').html(total_jpC);
                $('#TOTAL_PENGELUARAN').html(total_keluar);
                $('#BERSIH').html(total_bersih);

                var terbilang_A2 = terbilang(''+total_A2);
                document.getElementById("TERBILANG_A2").setAttribute('data-highlight',nominal_in_milyar(total_A2));
                $('#TERBILANG_A2').html(terbilang_A2);

                var terbilang_B2= terbilang(''+total_B2);
                document.getElementById("TERBILANG_B2").setAttribute('data-highlight',nominal_in_milyar(total_B2));
                $('#TERBILANG_B2').html(terbilang_B2);

                var terbilang_C2= terbilang(''+total_C2);
                document.getElementById("TERBILANG_C2").setAttribute('data-highlight',nominal_in_milyar(total_C2));
                $('#TERBILANG_C2').html(terbilang_C2);

                var terbilang_tot_keluar= terbilang(''+tot_pengeluaran);
                document.getElementById("TERBILANG_TOTAL2").setAttribute('data-highlight',nominal_in_milyar(tot_pengeluaran));
                $('#TERBILANG_TOTAL2').html(terbilang_tot_keluar);
                
                var terbilang_bersih= terbilang(''+penerimaan_bersih);
                console.log('angka_penerimaan_bersih',penerimaan_bersih);
                document.getElementById("TERBILANG_BERSIH").setAttribute('data-highlight',nominal_in_milyar(penerimaan_bersih));
                console.log('terbilang_penerimaan_bersih',terbilang_bersih);
                $('#TERBILANG_BERSIH').html(terbilang_bersih);

                ///////// Jumlah //////////
                var jml_asset_hartirak;
                if(result.hartirak[0].jumlah == 0){
                    jml_asset_hartirak = '-';
                }else{
                    jml_asset_hartirak = result.hartirak[0].jumlah ;
                }
                $('#jml_asset_hartirak').html(jml_asset_hartirak);

                if(result.harger[0].jumlah == 0){
                    jml_asset_harger = '-';
                }else{
                    jml_asset_harger = result.harger[0].jumlah ;
                }
                $('#jml_asset_harger').html(jml_asset_harger);
                
                if(result.harger2[0].jumlah == 0){
                    jml_asset_harger2 = '-';
                }else{
                    jml_asset_harger2 = result.harger2[0].jumlah ;
                }
                $('#jml_asset_harger2').html(jml_asset_harger2);
               
                if(result.suberga[0].jumlah == 0){
                    jml_asset_suberga = '-';
                }else{
                    jml_asset_suberga = result.suberga[0].jumlah ;
                }
                $('#jml_asset_suberga').html(jml_asset_suberga);
               
                if(result.kaseka[0].jumlah == 0){
                    jml_asset_kaseka = '-';
                }else{
                    jml_asset_kaseka = result.kaseka[0].jumlah ;
                }
                $('#jml_asset_kaseka').html(jml_asset_kaseka);
                
                if(result.harlin[0].jumlah == 0){
                    jml_asset_harlin = '-';
                }else{
                    jml_asset_harlin = result.harlin[0].jumlah ;
                }
                $('#jml_asset_harlin').html(jml_asset_harlin);

                if(result._hutang[0].jumlah == 0){
                    jml_asset__hutang = '-';
                }else{
                    jml_asset__hutang = result._hutang[0].jumlah ;
                }
                $('#jml_hutang').html(jml_asset__hutang);

                //////// Total Asset /////////

                if(result.hartirak[0].sum_hartirak != null){
                    var harta1 = result.hartirak[0].sum_hartirak;
                    var total_harta1 = formatRupiah(harta1, 'Rp. ')
                }else{
                    var harta1 = 0;
                    var total_harta1 = 'Rp. 0';
                }
                if(result.harger[0].sum_harger != null){
                    var harta2 = result.harger[0].sum_harger;
                    var total_harta2 = formatRupiah(harta2, 'Rp. ')
                }else{
                    var harta2 = 0;
                    var total_harta2 = 'Rp. 0';
                }
                if(result.harger2[0].sum_harger2 != null){
                    var harta3 = result.harger2[0].sum_harger2;
                    var total_harta3 = formatRupiah(harta3, 'Rp. ')
                }else{
                    var harta3 = 0;
                    var total_harta3 = 'Rp. 0';
                }
                if(result.suberga[0].sum_suberga != null){
                    var harta4 = result.suberga[0].sum_suberga;
                    var total_harta4 = formatRupiah(harta4, 'Rp. ')
                }else{
                    var harta4 = 0;
                    var total_harta4 = 'Rp. 0';
                }
                if(result.kaseka[0].sum_kaseka != null){
                    var str = result.kaseka[0].sum_kaseka;
                    var harta5 = str.replace(".00", "");
                    var total_harta5 = formatRupiah(harta5, 'Rp. ')
                }else{
                    var harta5 = 0;
                    var total_harta5 = 'Rp. 0';
                }
                if(result.harlin[0].sum_harlin != null){
                    var harta6 = result.harlin[0].sum_harlin;
                    var total_harta6 = formatRupiah(harta6, 'Rp. ')
                }else{
                    var harta6 = 0;
                    var total_harta6 = 'Rp. 0';
                }
                if(result._hutang[0].sum_hutang != null){
                    var harta7 = result._hutang[0].sum_hutang;
                    var total_harta7 = formatRupiah(harta7, 'Rp. ')
                }else{
                    var harta7 = 0;
                    var total_harta7 = 'Rp. 0';
                }
                
                $('#HARTA1').html(total_harta1);
                $('#HARTA2').html(total_harta2);
                $('#HARTA3').html(total_harta3);
                $('#HARTA4').html(total_harta4);
                $('#HARTA5').html(total_harta5);
                $('#HARTA6').html(total_harta6);
                $('#HUTANG').html(total_harta7);

                var subtotal = parseInt(harta1) + parseInt(harta2) + parseInt(harta3) + parseInt(harta4) + parseInt(harta5) + parseInt(harta6) ;
                var jml_subtotal = formatRupiah(''+subtotal, 'Rp. ');
                $('#SUBTOTAL').html(jml_subtotal);

                var total = subtotal - parseInt(harta7);
                var jml_total = formatRupiah(''+total, 'Rp. ');
                $('#TOTAL_HARTA').html(jml_total);

                //////// Terbilang //////////

                var terbilang_harta1 = terbilang(result.hartirak[0].sum_hartirak);
                document.getElementById("TERBILANG_HARTA1").setAttribute('data-highlight',nominal_in_milyar(result.hartirak[0].sum_hartirak));
                $('#TERBILANG_HARTA1').html(terbilang_harta1);
                
                var terbilang_harta2 = terbilang(result.harger[0].sum_harger);
                document.getElementById("TERBILANG_HARTA2").setAttribute('data-highlight',nominal_in_milyar(result.harger[0].sum_harger));
                $('#TERBILANG_HARTA2').html(terbilang_harta2);
                
                var terbilang_harta3 = terbilang(result.harger2[0].sum_harger2);
                document.getElementById("TERBILANG_HARTA3").setAttribute('data-highlight',nominal_in_milyar(result.harger[0].sum_harger));
                $('#TERBILANG_HARTA3').html(terbilang_harta3);

                var terbilang_harta4 = terbilang(result.suberga[0].sum_suberga);
                document.getElementById("TERBILANG_HARTA4").setAttribute('data-highlight',nominal_in_milyar(result.suberga[0].sum_suberga));
                $('#TERBILANG_HARTA4').html(terbilang_harta4);

                var str_kaseka = result.kaseka[0].sum_kaseka;
                var res_kaseka = str_kaseka.replace(".00", "");
                var terbilang_harta5 = terbilang(res_kaseka.toString());
                document.getElementById("TERBILANG_HARTA5").setAttribute('data-highlight',nominal_in_milyar(res_kaseka));
                $('#TERBILANG_HARTA5').html(terbilang_harta5);

                var terbilang_harta6 = terbilang(result.harlin[0].sum_harlin);
                document.getElementById("TERBILANG_HARTA6").setAttribute('data-highlight',nominal_in_milyar(result.harlin[0].sum_harlin));
                $('#TERBILANG_HARTA6').html(terbilang_harta6);
                
                var terbilang_subtotal = terbilang(''+subtotal);
                document.getElementById("TERBILANG_SUBTOTAL").setAttribute('data-highlight',nominal_in_milyar(subtotal));
                $('#TERBILANG_SUBTOTAL').html(terbilang_subtotal);
               
                var terbilang_hutang = terbilang(result._hutang[0].sum_hutang);
                document.getElementById("TERBILANG_HUTANG").setAttribute('data-highlight',nominal_in_milyar(result._hutang[0].sum_hutang));
                $('#TERBILANG_HUTANG').html(terbilang_hutang);
                
                var terbilang_total = terbilang(''+total);
                document.getElementById("TERBILANG_TOTAL_HARTA").setAttribute('data-highlight',nominal_in_milyar(total));
                $('#TERBILANG_TOTAL_HARTA').html(terbilang_total);

            }
        });

    }

    function terbilang(jumlah){;
        var bilangan=''+Math.abs(jumlah);
        console.log(bilangan);
        var kalimat="";
        var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
        var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
        var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');
        
        if(jumlah == null || jumlah == 0){ 
            return "Nol Rupiah";
        }
        var panjang_bilangan = bilangan.length;
        
        /* pengujian panjang bilangan */
        if(panjang_bilangan > 15){
            kalimat = "Diluar Batas";
        }else{ 
            /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
            for(i = 1; i <= panjang_bilangan; i++) {
                angka[i] = bilangan.substr(-(i),1);
            }
            
            var i = 1;
            var j = 0;
            
            /* mulai proses iterasi terhadap array angka */
            while(i <= panjang_bilangan){
                subkalimat = "";
                kata1 = "";
                kata2 = "";
                kata3 = "";
                
                /* untuk Ratusan */
                if(angka[i+2] != "0"){
                    if(angka[i+2] == "1"){
                        kata1 = "Seratus";
                    }else{
                        kata1 = kata[angka[i+2]] + " Ratus";
                    }
                }
                
                /* untuk Puluhan atau Belasan */
                if(angka[i+1] != "0"){
                    if(angka[i+1] == "1"){
                        if(angka[i] == "0"){
                            kata2 = "Sepuluh";
                        }else if(angka[i] == "1"){
                            kata2 = "Sebelas";
                        }else{
                            kata2 = kata[angka[i]] + " Belas";
                        }
                    }else{
                        kata2 = kata[angka[i+1]] + " Puluh";
                    }
                }
                
                /* untuk Satuan */
                if (angka[i] != "0"){
                    if (angka[i+1] != "1"){
                        kata3 = kata[angka[i]];
                    }
                }
                
                /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
                if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")){
                    subkalimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
                }
                
                /* gabungkan variabe sub kalimat (untuk Satu blok 3 angka) ke variabel kalimat */
                kalimat = subkalimat + kalimat;
                i = i + 3;
                j = j + 1;
            }
            
            /* mengganti Satu Ribu jadi Seribu jika diperlukan */
            if ((angka[5] == "0") && (angka[6] == "0")){
                kalimat = kalimat.replace("Satu Ribu","Seribu");
            }
        }
       
        return kalimat+" Rupiah";
    }

    function nominal_in_milyar(nominal){
        const temp_nominal = Math.abs(nominal);
        
        if(temp_nominal >= 1000000000 && temp_nominal <= 100000000000){        // 1M-100M highlight warna hijau
            return 1;                 
        }else if(temp_nominal > 100000000000 && temp_nominal <= 500000000000){ // 100M-500M highlight warna kuning
            return 2;                
        }else if(temp_nominal > 500000000000){                             // > 500M highlight warna merah
            return 3;                
        }else{
            return 0;
        }
    }

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = '';
        var isNegative = false;
        if (angka<0) {
            number_string = ''+Math.abs(angka);
            isNegative = true;
        }else{
            number_string = angka.toString();
        }
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        rupiah = isNegative ? '-'+rupiah : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

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

    function toVerifDetail(){
        var key = $('#key_verif').val();
        var url = 'index.php/ever/verification/display/lhkpn/'+key+'/verifikasi';
        ng.LoadAjaxContent(url);
        location.reload(true);
        return false;
    }

    $(document).ready(function() {

        var msg_verif = $('#msg_verifikasi_cepat');

        if(msg_verif){
            swal(msg_verif.val());
        }

        $('.btn-save-data').click(function(event){
            event.preventDefault();

            $.ajax({
                url:'<?php echo base_url();?>ever/verification/saveFinalVerif/',
                method:"POST",
                data:{
                    ID_LHKPN: $('#ID_LHKPN').val(),
                    nama_lengkap_msg: $('#nama_lengkap_msg').val(),
                    nama_instansi_msg: $('#nama_instansi_msg').val(),
                    nama_jabatan_msg: $('#nama_jabatan_msg').val(),
                    nama_bidang_msg: $('#nama_bidang_msg').val(),
                    tahun_pelaporan_msg: $('#tahun_pelaporan_msg').val(),
                    simpan: 'draft',
                },
                success:function(response) {
                   $('.terverif-lengkap').val('1').prop('disabled', false);
                   $('.tidak-lengkap').val('3').prop('disabled', false);
                },
                error:function(){
                    console.log("error");
                }

            });
        });

        $('#hasil-verifikasi').click(function(){
            $('#hasil-verifikasi').removeClass('btn-danger').addClass('btn-success').html('Done <i class="fa fa-check"></i>');
            $('.btn-save-data').prop('disabled', false);
        });

        $("#btnFinal").click(function(){ 
            $('#myForm').submit(function() {
                $('#btnFinal').prop('disabled', true);
                $('#loader_area').show();
            });
        });

        $('input:radio[name="final"]').change(function(){
            if ($(this).is(':checked')) {
                $('#btnFinal').prop('disabled', false);
            }
        });

        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });

        $(function() {
            $('#tbl-verif').dataTable({
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": true,
                "bInfo": false,
                "bAutoWidth": false,
            });
        });

        $(".btnVerifikasi").click(function() {
            var key = $(this).parents('td').children('.key').val();
            var url = '<?php echo $urlDisplay; ?>' + key + '/verifikasi';
            ng.LoadAjaxContent(url);
            return false;
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
        /* wahyu
        $(".btnCetakSurat").click(function() {
            var key = $(this).parents('td').children('.key').val();
            var url = '<?php echo $urlDisplay; ?>' + key + '/cetaksurat';
           var  f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
            $.post(url, function(html) {
                // OpenModalBox('History <?php echo $title; ?>', html, '', 'large');
                OpenModalBox('Cetak Surat', html, f_close, 'large');
            });
            return false;
        });
        */
        $(".btnCetakSurat").click(async function() {
            url = $(this).attr('href');
            const {value: email_cc} = await swal({
                type: 'info',
                titleText: 'Masukkan Alamat Email yang akan di CC',
                input: 'email',
                inputPlaceholder: 'Enter your email address',
                showCancelButton: true,
                cancelButtonText: 'Tanpa CC',
                cancelButtonColor: '#ff8000',
                width: '30%'
            });
            if (email_cc) {
                $('#loader_area').show();
                f_close = '<button class="btn btn-sm btn-primary" onClick="test(\''+email_cc+'\')">Kirim Email</button><input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
                $.post(url, function (html) {
                    OpenModalBox('Cetak Surat', html, f_close, 'large');
                });
            } else {
                $('#loader_area').show();
                f_close = '<button class="btn btn-sm btn-primary" onClick="test()">Kirim Email</button><input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
                $.post(url, function (html) {
                    OpenModalBox('Cetak Surat', html, f_close, 'large');
                });
            };
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
        $('#CARI_MENUGASKAN').select2({
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
                //     return '-- Pilih Yang Menugaskan --';
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
                //     return '-- Pilih Yang Menugaskan --';
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
</script>