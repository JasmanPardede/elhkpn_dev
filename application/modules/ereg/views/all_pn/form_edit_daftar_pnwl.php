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
 * @package Views/pejabat
 */
?>
<style type="text/css">
    .form-select2 {
        padding: 6px 0px !important;
        margin: 0px !important;
    }
    .addon-custom {padding: 0px;}
    .addon-custom input {
        border: medium none;
        height: 32px;
        width: 60px;
        padding: 6px 12px;
    }
</style>

<div id="wrapperFormAdd">
    <form class="form-horizontal forminput" method="post" id="ajaxFormAdd" action="index.php/ereg/all_pn/save_edit_daftar_wl/<?php echo isset($daftar_pn->ID_PN) ? $daftar_pn->ID_PN : ""; ?>/<?php echo isset($daftar_pn->ID) ? $daftar_pn->ID : ""; ?>" enctype="multipart/form-data">
        <input type="hidden" name="iscln" value="<?php echo $iscln; ?>">
        <div class="col-md-6">
            <div id="wrapperFormAddPN">
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>NIK <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('nik_pnwl'); ?>
                        <input readonly name='NIK' id='NIK' onKeyUp='HitungText()' onkeypress="return isNumber(event)" value="<?php echo isset($daftar_pn->NIK) ? $daftar_pn->NIK : ""; ?>" maxlength="16" required type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nik') ?>>
                    </div>
                    <div class="col-sm-1"></div>
                </div>                
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <span align='center' id='NIK2'></span>
                        <span align='center' id='NIK3'></span>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <label>Gelar Depan</label> 
                        <?php echo FormHelpPopOver('gelar_dpn_dp'); ?>
                        <input class="form-control" value="<?php echo isset($daftar_pn->GELAR_DEPAN) ? $daftar_pn->GELAR_DEPAN : ""; ?>" onkeyup="this.value = this.value.toUpperCase()" type='text' size='40' name='GELARDEPAN' id='GELARDEPAN' <?php echo FormHelpPlaceholderToolTip('gelar_dpn_dp') ?>>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Nama<span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('nama_pnwl'); ?>
                        <input <?php //echo $this->session->userdata('ID_ROLE') == '2' ? '' : 'readonly'; ?> class="form-control" value="<?php echo $daftar_pn->NAMA; ?>" onkeyup="this.value = this.value.toUpperCase()" type='text' size='40' name='NAMA' id='NAMA' <?= FormHelpPlaceholderToolTip('nama') ?>>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <label>Gelar Belakang</label> 
                        <?php echo FormHelpPopOver('gelar_blk_dp'); ?>
                        <input class="form-control" value="<?php echo isset($daftar_pn->GELAR_BELAKANG) ? $daftar_pn->GELAR_BELAKANG : ""; ?>" onkeyup="this.value = this.value.toUpperCase()" type='text' size='40' name='GELARBELAKANG' id='GELARBELAKANG' <?php echo FormHelpPlaceholderToolTip('gelar_blk_dp') ?>>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Tempat/ Tanggal Lahir <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('tempat_tanggal_lahir_pnwl'); ?>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-5">
                    <input required class="form-control" onkeyup="this.value = this.value.toUpperCase()" value="<?php echo isset($daftar_pn->TEMPAT_LAHIR) ? $daftar_pn->TEMPAT_LAHIR : ""; ?>" data-toggle="tooltip" title="Tempat Lahir" type='text'name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat">
                </div>
                <div class="col-sm-5">
                    <input required class="form-control" value="<?php echo isset($daftar_pn->TGL_LAHIR) ? $this->lib_date->set_date_format($this->lib_date->set_tanggal($daftar_pn->TGL_LAHIR)) : ""; //isset($daftar_pn->TGL_LAHIR) ? $daftar_pn->TGL_LAHIR : "";      ?>" data-toggle="tooltip" title="Taggal Lahir" type='text'name='TGL_LAHIR' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
                </div>
                <div class="col-sm-1"></div>
            </div>


            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Jenis Kelamin <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('jenis_kelamin_pnwl'); ?>
                    <?php $jk = isset($daftar_pn->JNS_KEL) ? $daftar_pn->JNS_KEL : ""; ?>
                    <select required class="form-control" name="JNS_KEL" id="JNS_KEL">
                        <option value="">-- Pilih --</option>
                        <?php
                        if ($jk == 1) {
                            echo '<option value="1" selected>LAKI - LAKI</option>
                        <option value="2">PEREMPUAN</option>';
                        } else if ($jk == 2) {
                            echo '<option value="1">LAKI - LAKI</option>
                        <option value="2" selected>PEREMPUAN</option>';
                        } else {
                            echo '<option value="1">LAKI - LAKI</option>
                        <option value="2">Perempuan</option>';
                        }
                        ?>


                    </select>
                    <div class="col-sm-1"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>NRP/NIP/ No Pegawai </label> 
                    <?= FormHelpPopOver('nip_pnwl'); ?>
                    <input name='NIP' id='NIP'  type="text" value="<?php echo isset($daftar_pn->NIP_NRP) ? $daftar_pn->NIP_NRP : ""; ?>" class="form-control" <?= FormHelpPlaceholderToolTip('nip', true) ?>>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <?PHP
//			$a = $daftar_pn->EMAIL;
            $a = $daftar_pn->ID_STATUS_AKHIR_JABAT;
            //echo $a;
//			if ($a == "") {
            if ($a == 5 || $a == '5') {
                ?>
                <div id="divFieldInputNikBaru" class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>NIK Baru</label> 
                        <?php echo FormHelpPopOver('nik_baru'); ?>
                        <div class="input-group">
                            <input name='NIK_BARU' id='NIK_BARU' onkeypress="return isNumber(event)" onkeyup="HitungTextBaru();"  type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nik_baru', false); ?> maxlength="16">
                            <div class="input-group-btn">
                                <button id="batalSimpanNikBaru" class="btn btn-sm btn-danger btn-cancel-nik-baru"><i class="glyphicon glyphicon-remove-sign"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">

                        <span align='center' id='NIKBaru2'></span>
                        <span align='center' id='NIKBaru3'></span>

                    </div>
                    <div class="col-sm-1"></div>

                </div>
                <div id="divButtonShowFieldInputNikBaru" class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <button id="showFieldNikBaru" class="btn btn-sm btn-warning btn-edit-nik-baru"><i class="glyphicon glyphicon-pencil"></i> Edit NIK.</button>
                    </div>
                </div>
                <?PHP
            }
            ?>

            <?PHP
//			$a = $daftar_pn->EMAIL;
            $a = $daftar_pn->ID_STATUS_AKHIR_JABAT;
            //echo $a;
//			if ($a == "") {
            if (($a == 0 || $a == '0') && ($this->session->userdata('ID_ROLE') == '2' || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == '31' || $this->session->userdata('ID_ROLE') == 31)) {
                ?>
                <div id="divFieldInputNikBaru" class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>NIK Baru</label> 
                        <?php echo FormHelpPopOver('nik_baru'); ?>
                        <div class="input-group">
                            <input name='NIK_BARU' id='NIK_BARU' onkeypress="return isNumber(event)" onkeyup="HitungTextBaru();"  type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nik_baru', false); ?> maxlength="16">
                            <div class="input-group-btn">
                                <button id="batalSimpanNikBaru" class="btn btn-sm btn-danger btn-cancel-nik-baru"><i class="glyphicon glyphicon-remove-sign"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">

                        <span align='center' id='NIKBaru2'></span>
                        <span align='center' id='NIKBaru3'></span>

                    </div>
                    <div class="col-sm-1"></div>

                </div>
                <div id="divButtonShowFieldInputNikBaru" class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <button id="showFieldNikBaru" class="btn btn-sm btn-warning btn-edit-nik-baru"><i class="glyphicon glyphicon-pencil"></i> Edit NIK.</button>
                    </div>
                </div>
                <?PHP
            }
            ?>
        </div>

        <div class="col-md-6">
            <div id="wrapperFormJabatan">
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>INSTANSI <span class="red-label">*</span></label> 
                        <?= FormHelpPopOver('instansi_pnwl'); ?>
                        <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" <?php echo $isInstansi ? "value='$isInstansi' readonly='readonly'" : '' ?> value="<?php echo isset($daftar_pn->INST_SATKERKD) ? $daftar_pn->INST_SATKERKD : ""; ?>" id='LEMBAGA'  placeholder="lembaga" required >
                    </div>
                    <div class="col-sm-1">
                    </div>
                </div>

                <?php if ($iscln == 1) { // Untuk Calon PNWL ?>
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>UNIT KERJA <span class="red-label"></span></label> 
                            <?= FormHelpPopOver('unit_kerja_pnwl'); ?>
                            <input type='text' class="form-control form-select2" name='UNIT_KERJA' value="<?php echo isset($daftar_pn->UK_ID) ? $daftar_pn->UK_ID : ""; ?>" style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja">
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>SUB UNIT KERJA <span class="red-label"></span></label> 
                            <?= FormHelpPopOver('sub_unit_kerja_pnwl'); ?>
                            <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' placeholder="Sub Unit Kerja">
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>JABATAN<span class="red-label">*</span></label> 
                            <?= FormHelpPopOver('jabatan_pnwl'); ?>
                            <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                <?php } else {
                    ?>

                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>UNIT KERJA <span class="red-label">*</span></label> 
                            <?= FormHelpPopOver('unit_kerja_pnwl'); ?>
                            <?php echo $isUnitKerja; ?>
                            <!--<input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='<?php echo isset($daftar_pn->unit_kerja_kd) ? $daftar_pn->unit_kerja_kd : ""; ?>' placeholder="Unit Kerja" required>-->
                            <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" <?php echo $isUnitKerja ? "value='$isUnitKerja' readonly='readonly'" : '' ?> id='UNIT_KERJA' value='<?php echo isset($daftar_pn->unit_kerja_kd) ? $daftar_pn->unit_kerja_kd : ""; ?>' placeholder="Unit Kerja" required>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>SUB UNIT KERJA <span class="red-label"></span></label> 
                            <?= FormHelpPopOver('sub_unit_kerja_pnwl'); ?>
                            <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='<?php echo isset($daftar_pn->SUK_ID) ? $daftar_pn->SUK_ID : ""; ?>' placeholder="Sub Unit Kerja">
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>JABATAN<span class="red-label">*</span></label> 
                            <?= FormHelpPopOver('jabatan_pnwl'); ?>
                            <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value='<?php echo isset($daftar_pn->ID_JABATAN) ? $daftar_pn->ID_JABATAN : ""; ?>' placeholder="Jabatan" required>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>

                <?php } ?>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <!--                <div class="col-sm-10">
                                    <label>Pelaporan Online<span class="red-label">*</span></label> 
                <?php
//                        $a = $daftar_pn->EMAIL;
                $a = $daftar_pn->IS_APLIKASI;
                //echo $a;
                if ($a != "" || $a != NULL) {
                    ?>
                                                    <input id="is_aplikasi" value="1" onblur ="AutoCalculateMandateOnChange(this);" type='checkbox' name='is_aplikasi' checked onclick="AutoCalculateMandateOnChange(this);">
                    <?php
                } else {
                    ?>
                                                    <input id="is_aplikasi" value="1" onblur ="AutoCalculateMandateOnChange(this);" type='checkbox' name='is_aplikasi' onclick="AutoCalculateMandateOnChange(this);">
                    <?php
                }
                ?>
                
                                </div>-->
                <div class="col-sm-1">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Email <span class="red-label" id="span1">*</span></label> 
                    <?= FormHelpPopOver('email_pnwl'); ?>
                    <input type='email' class="form-control" name='EMAIL' size='40' id='EMAIL' onblur="cek_email(this.value, <?php echo $daftar_pn->ID_USER; ?>);" value="<?php echo isset($daftar_pn->tpn_email) ? $daftar_pn->tpn_email : ""; ?>" <?= FormHelpPlaceholderToolTip('email') ?> placeholder="Email">
                    <span class="help-block">
                        <font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font>
                        <font id='email_salah' style='display:none;' color='red'>Format Email Salah</font>
                    </span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-email">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>

                <!-- <div class="col-sm-10">
                    <label>EMAIL<span class="red-label">*</span></label> 
                <?= FormHelpPopOver('email'); ?>
                    <input required class="form-control" type='email' size='40' name='EMAIL' value="<?php echo isset($daftar_pn->EMAIL) ? $daftar_pn->EMAIL : ""; ?>" onblur="cek_email_pn(this.value);" id='EMAIL' placeholder="johnsmith@email.com">
                </div>
                <div class="col-sm-1">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
                -->        </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>Nomor Handphone<span class="red-label" id="span2">*</span></label> 
                    <?= FormHelpPopOver('handphone_pnwl'); ?>
                    <input class="form-control" type='text' value="<?php echo isset($daftar_pn->NO_HP) ? $daftar_pn->NO_HP : ""; ?>" onkeypress="validate(event)" size='40' name='NO_HP' id='NO_HP' placeholder="NO HP">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>WL Tahun<span class="red-label" >*</span></label> 

                    <select disabled="" required class="form-control" name="TAHUN_WL" id="JNS_KEL">
                        <option value="<?php echo isset($daftar_pn->TAHUN_WL) ? $daftar_pn->TAHUN_WL : ""; ?>"><?php echo isset($daftar_pn->TAHUN_WL) ? $daftar_pn->TAHUN_WL : ""; ?></option>
                        <?php
                        for ($i = date("Y"); $i <= date("Y") + 1; $i++) {
                            $sel = ($i == $daftar_pn->TAHUN_WL) ? 'selected' : '';
                            echo "<option value=" . $i . " " . $sel . ">" . date("Y", mktime(0, 0, 0, 0, 1, $i + 1)) . "</option>"; // change This Line
                        }
                        ?>                    
                    </select>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <br/>
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="pull-right">
                        <input type="hidden" name="act" id="act" value="doinsert">
                        <input type="hidden" name="ID_PN" id="ID_PN">
                        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $daftar_pn->ID_USER; ?>">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan data <?php echo (@$iscln == '1' ? 'Calon ' : '') ?>PN/WL</button>
                        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
                        <div class="col-sm-1"></div>
                    </div>

                </div>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript">
    function cek_email(email, id) {
        var div = $('#div-email');
        $('img', div).hide();
        if (!isDefined(email) || email.trim() == '' || email.trim().length == 0) {
            $('#fail', div).show();
            $('#email_salah').show();
            $('#email_ada').hide();
            return false;
        } else {
            var loading = $('#loading', div);
            loading.show();
            var url = "index.php/ereg/all_pn/cek_emails/" + encodeURIComponent(email) + "/" + id;
            $.post(url, function (data) {
                loading.hide();
                if (data == '0') {
                    $('#fail', div).show();
                    $('#email_salah').show();
                    $('#email_ada').hide();
                } else if (data == '1') {
                    $('#fail', div).show();
                    $('#email_salah').hide();
                    $('#email_ada').show();
                } else {
                    $('#success', div).show();
                    $('#email_salah').hide();
                    $('#email_ada').hide();
                }
            });
        }
    }

    function AutoCalculateMandateOnChange(element) {
        // if (element.checked) {
        if (element == '1') {
            document.getElementById("EMAIL").required = true;
            document.getElementById("NO_HP").required = true;
            document.getElementById("span1").style.visibility = "visible";
            document.getElementById("span2").style.visibility = "visible";
            document.getElementById("email_salah").style.visibility = "visible";
            $("#EMAIL, #NO_HP").addClass("field-required");
        } else {
            document.getElementById("EMAIL").required = false;
            document.getElementById("NO_HP").required = false;
            document.getElementById("span1").style.visibility = "hidden";
            document.getElementById("span2").style.visibility = "hidden";
            document.getElementById("email_salah").style.visibility = "hidden";
        }
    }
    function HitungText() {
        if (!isDefined(nikBaru)) {
            nikBaru = false;
        }
        var Teks = document.forminput.NIK.value.length;
        var total = document.getElementById('NIK2');
        var valid = document.getElementById('NIK3');

        if (Teks < 16) {
            total.innerHTML = '<img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> tidak boleh kurang dari 16 Digit';
            document.getElementById("NIK").focus();
        } else {
            total.innerHTML = ' <img id="nik_ada1" src="<?php echo base_url('img/success.png') ?>" width="24" /> sudah benar 16 digit';
        }

        var nik = $('#NIK').val();
        var url = "index.php/ereg/all_pn/cek_nik/" + nik;
        $.post(url, function(data) {
            if (data != '1') {
                valid.innerHTML = '<br/><img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> NIK tidak boleh sama';
            } else {
                valid.innerHTML = '';
            }
            ;
        });
        //alert(nik);
    }
    function HitungTextBaru() {
        //var Tekss = document.forminput.NIK_BARU.value.length;
        var Tekss = document.getElementById("NIK_BARU").value.length;
        var total = document.getElementById('NIKBaru2');
        var valid = document.getElementById('NIKBaru3');

        if (Tekss < 16) {
            total.innerHTML = '<img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> tidak boleh kurang dari 16 Digit';
            document.getElementById("NIK_BARU").focus();
        } else {
            //total.innerHTML = ' <img id="nik_ada1" src="<?php echo base_url('img/success.png') ?>" width="24" /> sudah benar 16 digit';
            total.innerHTML = '';
        }

        //var nik = $('#NIK_BARU').val();
        var nik = document.getElementById("NIK_BARU").value;
        var url = "index.php/ereg/all_pn/cek_nik/" + nik;
        $.post(url, function(data) {
            if (data != '1') {
                valid.innerHTML = '<br/><img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> NIK tidak boleh sama';
            } else {
                valid.innerHTML = '';
            }
            ;
        });
        //alert(nik);
    }

    $(function () {
        $('.over').popover();
        $('.over')
                .mouseenter(function (e) {
                    $(this).popover('show');
                })
                .mouseleave(function (e) {
                    $(this).popover('hide');
                });
    });
    function showHideFieldNikBaru(hide) {
        if (isDefined(hide) && hide) {
            $("div#divFieldInputNikBaru").hide();
            $("div#divButtonShowFieldInputNikBaru").show();

            $("#NIKBaru2").empty();
            $("#NIK_BARU").val('');

        }
        else {
            $("div#divFieldInputNikBaru").show();
            $("div#divButtonShowFieldInputNikBaru").hide();
        }
    }
    function toFormJabatan() {
        var required = [];
        $(':input[required]', "#wrapperFormAddPN").each(function() {
            // alert(this.value.trim());
            if (this.value.trim() !== '') {
                required.push('yes');
            } else
            {
                required.push('no');
            }
        });
        if (inArray('no', required) == false)
        {
            $("#wrapperFormAddPN").hide('slow', function() {
                $('#wrapperFormJabatan').show('slow');
            });
        } else
        {
            alertify.error('Mohon melengkapi data wajib !');
        }

    }
    function inArray(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] == needle)
                return true;
        }
        return false;
    }
    function toFormPN(ele) {
        var wrap = $(ele).closest('.wrap');

        wrap.hide('fast', function() {
            $("#wrapperFormAddPN").show('slow');
            $("#NIK").focus();
        });
    }
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    $(function() {
        $("#TGL_LAHIR").datepicker({format: "dd/mm/yyyy"});
        $('#TGL_LAHIR').on('change', function() {
            var dob = new Date(this.value);
            var today = new Date();
            var age = Math.ceil((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            if (age <= 17 && age <= 100) {
                alert("Usia tidak boleh kurang dari 17th");
                document.getElementById("TGL_LAHIR").focus();
                $('#TGL_LAHIR').val("");
            }
        });
    });
    $(document).ready(function() {
        showHideFieldNikBaru(true);
        $("button.btn-cancel-nik-baru").click(function(e) {
            e.preventDefault();

            showHideFieldNikBaru(true);
        });
        $("button.btn-edit-nik-baru").click(function(e) {
            e.preventDefault();
            showHideFieldNikBaru();
        });
        $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
        var idWrapFormJabatan = $('#wrapperFormJabatan');
        var valA = $('#LEMBAGA').val();
        var valUK = $('#UNIT_KERJA').val();
        var valSUK = $('#SUB_UNIT_KERJA').val();
        var valJAB = $('#JABATAN').val();
        if (valA != '') {
//			 $('#JABATAN').select2("data", {id: valJAB, name: "<?php // echo $daftar_pn->NAMA_JABATAN;    ?>"});
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).prop('disabled', false);
            // $('input[name="JABATAN"]', idWrapFormJabatan).prop('disabled', false);

            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2('val', '');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('val', '');
            LEMBAGA = valA;
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getUnitKerjaTanpaDefault') ?>/" + LEMBAGA,
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
                        $.ajax("<?= base_url('index.php/share/reff/getUnitKerjaTanpaDefault') ?>/" + LEMBAGA + '/' + id, {
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
            $('input[name="SUB_UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK,
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
                        $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK + '/' + id, {
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
            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + valUK + '/' + valSUK,
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
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan3') ?>/" + valUK + '/' + valSUK + '/' + id, {
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


        }
        var form = $("#ajaxFormAdd");
        var msg = {
            success: 'Data Berhasil Disimpan!',
            error: 'Data Gagal Disimpan!'
        };
        $("#ajaxFormAdd").submit(function() {
            $('#loader_area').show();
            var urll = form.attr('action');
            var formData = new FormData($(this)[0]);

//            alert(urll);

            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function(htmldata) {
                    htmldata = JSON.parse(htmldata);
                    if (htmldata == 0) {
//                        alertify.error(msg.error);
                        alertify.error('Cek NIK mungkin sudah terdaftar di system!!!');
                        $('#loader_area').hide();
                    } else if (htmldata == 2) {
                        alertify.error('Nik sudah terdaftar sebagai user!!!');
                        $('#loader_area').hide();
                    } else if (htmldata == 8) {
                        alertify.error('Format Email Salah!!!');
                        $('#loader_area').hide();
                    } else if (htmldata == 9) {
                        alertify.error('Email sudah terdaftar!!!');
                        $('#loader_area').hide();
                    } else {
                        alertify.success(msg.success);

//                         $.get(location.href.split('#')[1], function(html) {
//                             $('#ajax-content').html(html);
//                             CloseModalBox();

//                             $('#loader_area').hide();
//                         })

                        var dtTable = $('#dt_completeNEW').DataTable();
                        dtTable.ajax.reload( null, false );
                        CloseModalBox();
                        $('#loader_area').hide();
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });
        $('input[name="KD_ISO3_NEGARA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getNegara') ?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getNegara') ?>/" + id, {
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
        $('input[name="PROV"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getProvinsi') ?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getProvinsi') ?>/" + id, {
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
                prov = state.id;
                return state.name;
            }
        });
        $('input[name="PROV"]').on("change", function(e) {
            $('input[name="KAB_KOT"]').prop("disabled", false);

            $('input[name="KAB_KOT"]').select2("val", "");
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });
        $('input[name="KAB_KOT"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getKabupatenKota') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term,
                        prov: prov
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getKabupatenKota') ?>/" + prov + '/' + id, {
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
                kab = state.id;
                return state.name;
            }
        });
        $('input[name="KAB_KOT"]').on("change", function(e) {
            $('input[name="KEC"]').prop("disabled", false);

            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });
        $("#btnLanjut").click(function(e) {
            $("#CARI_INST").val();
            $("#CARI_STATUS_PN").val();
            $("#CARI_TEXT").val($("#NIK").val());
            $("#CARI_TEXT").after('<input type="text" id="CARI_USEWHEREONLY" name="CARI[USEWHEREONLY]" value="1">');
            $("#ajaxFormCari").submit();
            $("#CARI_USEWHEREONLY").remove();
            CloseModalBox();
        });
        // jQuery Form Jabatan
        $('.datepicker', idWrapFormJabatan).datepicker({
            format: 'dd/mm/yyyy'
        });
        $('input[name="LEMBAGA"]', idWrapFormJabatan).select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getLembaga') ?>",
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
                    $.ajax("<?= base_url('index.php/share/reff/getLembaga') ?>/" + id, {
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

        var uk_ = null;

        $('#UNIT_KERJA', idWrapFormJabatan).on("select2:selecting", function(e) {
            freeSelectionSelect2(["SUB_UNIT_KERJA", "JABATAN"]);
        });
        $('#SUB_UNIT_KERJA', idWrapFormJabatan).on("select2:selecting", function(e) {
            freeSelectionSelect2(["JABATAN"]);
        });
        $('#UNIT_KERJA', idWrapFormJabatan).change(function(event) {
            UNIT_KERJA = $(this).val();

            uk_ = UNIT_KERJA;
            freeSelectionSelect2(["SUB_UNIT_KERJA", "JABATAN"]);

            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA,
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
                    // alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + id, {
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

            $('input[name="SUB_UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
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
                        $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + uk_ + "/" + id, {
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
        $('#SUB_UNIT_KERJA', idWrapFormJabatan).change(function(event) {
            SUB_UNIT_KERJA = $(this).val();
            UNIT_KERJA = $("#UNIT_KERJA").val();

            uk_ = UNIT_KERJA;
            freeSelectionSelect2(["JABATAN"]);
            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + SUB_UNIT_KERJA,
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
//                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + SUB_UNIT_KERJA, {
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
        $('#LEMBAGA', idWrapFormJabatan).change(function(event) {
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).prop('disabled', false);
            $('input[name="JABATAN"]', idWrapFormJabatan).prop('disabled', false);
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2('val', '');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('val', '');
            LEMBAGA = $(this).val();

            var _lb = LEMBAGA;
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA,
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
                        $.ajax("<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + _lb + '/' + id, {
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

            freeSelectionSelect2(["UNIT_KERJA", "SUB_UNIT_KERJA", "JABATAN"]);
            freeSelectionSelect2(["SUB_UNIT_KERJA", "JABATAN"]);
            freeSelectionSelect2(["JABATAN"]);
        });
        // AutoCalculateMandateOnChange('#is_aplikasi')
        AutoCalculateMandateOnChange('1')
        jQuery(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });

    });

</script>
