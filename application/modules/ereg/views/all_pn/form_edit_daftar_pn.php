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
    <form class="form-horizontal forminput" name="forminput" method="post" id="ajaxFormAdd" actrel="index.php/ereg/all_pn/save_edit_daftar/<?php echo isset($daftar_pn->ID_PN) ? $daftar_pn->ID_PN : ""; ?>" action="#" enctype="multipart/form-data">
        <div id="wrapperFormAddPN">
            <input type="hidden" name="iscln" value="<?php echo $iscln; ?>">
            <div class="col-md-6">

                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Nomor Induk Kependudukan (NIK) <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('nik_pnwl'); ?>
                        <input readonly="readonly" disabled="disabled" name='NIK' id='NIK' onKeyUp='HitungText()' onkeypress="return isNumber(event)" value="<?php echo isset($daftar_pn->NIK) ? $daftar_pn->NIK : ""; ?>" maxlength="16" required type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nik'); ?> >
                    </div>
                    <div class="col-sm-1">
                    </div>                
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
                        <label>Nama Lengkap <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('nama_pnwl'); ?>
                        <input required class="form-control" value="<?php echo isset($daftar_pn->NAMA) ? $daftar_pn->NAMA : ""; ?>" onkeyup="this.value = this.value.toUpperCase()" type='text' size='40' name='NAMA' id='NAMA' <?php echo FormHelpPlaceholderToolTip('nama') ?>>
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
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Tempat/ Tanggal Lahir <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('tempat_tanggal_lahir_pnwl'); ?>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">
                        <input required class="form-control" onkeyup="this.value = this.value.toUpperCase()" value="<?php echo isset($daftar_pn->TEMPAT_LAHIR) ? $daftar_pn->TEMPAT_LAHIR : ""; ?>" data-toggle="tooltip" title="Tempat Lahir" type='text'name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat">
                    </div>
                    <div class="col-sm-5">
                        <input required class="form-control" value="<?php echo isset($daftar_pn->TGL_LAHIR) ? $this->lib_date->set_date_format($this->lib_date->set_tanggal($daftar_pn->TGL_LAHIR)) : ""; //isset($daftar_pn->TGL_LAHIR) ? $daftar_pn->TGL_LAHIR : "";                           ?>" data-toggle="tooltip" title="Taggal Lahir" type='text'name='TGL_LAHIR' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Jenis Kelamin <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('jenis_kelamin_pnwl'); ?>
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
                        <label>NRP/NIP/Nomor Pegawai </label> 
                        <?php echo FormHelpPopOver('nip_pnwl'); ?>
                        <input name='NIP' id='NIP'  type="text" value="<?php echo isset($daftar_pn->NIP_NRP) ? $daftar_pn->NIP_NRP : ""; ?>" class="form-control" <?php echo FormHelpPlaceholderToolTip('nip', true) ?>>
                    </div>
                    <div class="col-sm-1">
                    </div>
                </div>

                <div id="divFieldInputNikBaru" class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>NIK Baru</label> 
                        <?php echo FormHelpPopOver('nik_baru'); ?>
                        <div class="input-group">
                            <input  maxlength="16" name='NIK_BARU' id='NIK_BARU' onkeyup="HitungText(false, true);"  type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nik_baru', false); ?>>
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

            </div>

            <div class="col-md-6">
                <div id="wrapperFormJabatan">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>INSTANSI <span class="red-label">*</span></label> 
                            <?php echo FormHelpPopOver('instansi_pnwl'); ?>
                            <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" <?php echo $isInstansi ? "value='$isInstansi' readonly='readonly'" : '' ?> value="<?php echo isset($daftar_pn->LEMBAGA) ? $daftar_pn->LEMBAGA : ""; ?>" id='LEMBAGA'  placeholder="lembaga" required>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>

                    <?php if ($iscln == 1) { // Untuk Calon PNWL ?>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>UNIT KERJA <span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='UNIT_KERJA' value="<?php echo isset($daftar_pn->unit_kerja_kd) ? $daftar_pn->unit_kerja_kd : ""; ?>" style="border:none;" <?php echo $isUnitKerja ? "value='$isUnitKerja' readonly='readonly'" : '' ?>" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>SUB UNIT KERJA <span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('sub_unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' placeholder="Sub Unit Kerja" required>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>JABATAN<span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('jabatan_pnwl'); ?>
                                <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                    <?php } else { ?>

                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>UNIT KERJA <span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='UNIT_KERJA' value="<?php echo isset($daftar_pn->unit_kerja_kd) ? $daftar_pn->unit_kerja_kd : ""; ?>" style="border:none;" <?php echo $isUnitKerja ? "value='$isUnitKerja' readonly='readonly'" : '' ?>" id='UNIT_KERJA' value='' placeholder="Unit Kerja">
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>SUB UNIT KERJA <span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('sub_unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='<?php echo isset($daftar_pn->SUK_ID) ? $daftar_pn->SUK_ID : ""; ?>' placeholder="Sub Unit Kerja" required>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>JABATAN<span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('jabatan_pnwl'); ?>
                                <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value='<?php echo isset($daftar_pn->ID_JABATAN) ? $daftar_pn->ID_JABATAN : ""; ?>' placeholder="Jabatan" required>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>

                    <?php } ?>
                </div>
                <!-- <div class="form-group">
                    <div class="col-sm-1"></div>

                    <div class="col-sm-10">
                        <label>Pelaporan Online<span class="red-label">*</span></label> 
                        <?php
//                        $a = $daftar_pn->EMAIL;
                        //$a = $daftar_pn->IS_APLIKASI;
                        //echo $a;
                        //if ($a != "" || $a != NULL) {
                            ?>
                            <input id="is_aplikasi" value="1" onblur ="AutoCalculateMandateOnChange(this);" type='checkbox' name='is_aplikasi' checked onclick="AutoCalculateMandateOnChange(this);">
                            <?php
                        //} else {
                            ?>
                            <input id="is_aplikasi" value="1" onblur ="AutoCalculateMandateOnChange(this);" type='checkbox' name='is_aplikasi' onclick="AutoCalculateMandateOnChange(this);">
                            <?php
                        // }
                        ?>

                    </div>

                    <div class="col-sm-1">
                        <img class="show-hide" id="fail" src="<?php //echo base_url('img/fail.png') ?>" width="24" />
                        <img class="show-hide" id="success" src="<?php //echo base_url('img/success.png') ?>" width="24" />
                        <img class="show-hide" id="loading" src="<?php //echo base_url('img/loading.gif') ?>" width="24" />
                    </div>
                </div> -->
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <label>WL Tahun</label> 
                        <?php echo FormHelpPopOver('tahun_laporan_create'); ?>
                        <select required class="form-control field-required" name="TAHUN_WL" id="TAHUN_WL">
                            <option value="<?= getdate()['year']; ?>" <?= ($daftar_pn->tahun_wl == getdate()['year']) ? 'selected' : ''; ?>><?= getdate()['year']; ?></option>
                            <!--<option value="<?= getdate()['year']-1; ?>" <?= ($daftar_pn->tahun_wl == getdate()['year']-1) ? 'selected' : ''; ?>><?= getdate()['year']-1; ?></option>-->
                        </select>
                        <div class="col-sm-1"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Alamat Email<span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('email_pnwl'); ?>
                        <input required type='email' class="form-control" 
                               name='EMAIL' size='40' id='EMAIL' onblur="cek_emails(this.value, <?php echo $daftar_pn->ID_USER; ?>);" 
                               value="<?php echo isset($daftar_pn->EMAIL) ? $daftar_pn->EMAIL : ""; ?>" <?php echo FormHelpPlaceholderToolTip('email') ?> placeholder="">
                        <div class="help-block">
                            <div id='email_ada' style="display: none;" color='red'><font color='red'>Email sudah terdaftar</font></div>
                            <div id='email_salah' style="display: none;" color='red'><font color='red'>Format Email Salah</font></div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                        <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                        <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Nomor Handphone<span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('handphone_pnwl'); ?>
                        <input required class="form-control" type='text' value="<?php echo isset($daftar_pn->NO_HP) ? $daftar_pn->NO_HP : ""; ?>" onkeypress="validate(event)" size='40' name='NO_HP' id='NO_HP' placeholder="NO HP">
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
                            <!--<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan data <?php echo (@$iscln == '1' ? 'Calon ' : '') ?>PN/WL</button>-->
                            <button type="button" class="btn btn-sm btn-primary" id="btnSubmit"><i class="fa fa-share"></i>Simpan data <?php echo (@$iscln == '1' ? 'Calon ' : '') ?>PN/WL</button>
                            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
                        </div></div>
                    <div class="col-sm-1"></div>
                </div>

            </div>
            <?php
            // end colom 2
            ?>
        </div>
    </form>
</div>

<?php
$js_page = isset($js_page) ? $js_page : '';
if (is_array($js_page)) {
    foreach ($js_page as $page_js) {
        echo $page_js;
    }
} else {
    echo $js_page;
}
?>
<script type="text/javascript">
    function HitungText(needResponse, nikBaru) {

        if (!isDefined(nikBaru)) {
            nikBaru = false;
        }

        var Teks = nikBaru ? document.forminput.NIK_BARU.value.length : document.forminput.NIK.value.length;
        var total = nikBaru ? document.getElementById('NIKBaru2') : document.getElementById('NIK2');
        var valid = nikBaru ? document.getElementById('NIKBaru3') : document.getElementById('NIK3');

        if (!isDefined(needResponse)) {
            needResponse = false;
        }

        if ((isHidden(document.getElementById('divFieldInputNikBaru')) && nikBaru) || document.forminput.NIK_BARU.value.trim() == '') {
            return true;
        }

        var isNikOk = testDigitNik(Teks, total);

        if (needResponse) {
            return isNikOk;
        }

        var nik = nikBaru ? $('#NIK_BARU').val() : $('#NIK').val();
        if (nik.trim() != '') {
            var url = "index.php/ereg/all_pn/cek_nik/" + nik;
            $.post(url, function (data) {
                if (data != '1') {
                    valid.innerHTML = '<br/><img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> NIK tidak boleh sama';
                } else {
                    valid.innerHTML = '';
                }
            });
        }
        //alert(nik);
    }

    function showHideFieldNikBaru(hide) {
        if (isDefined(hide) && hide) {
            $("div#divFieldInputNikBaru").hide();
            $("div#divButtonShowFieldInputNikBaru").show();

            $("#NIKBaru2").empty();
            $("#NIK_BARU").val('');

        } else {
            $("div#divFieldInputNikBaru").show();
            $("div#divButtonShowFieldInputNikBaru").hide();
        }
    }

    $(document).ready(function () {

        showHideFieldNikBaru(true);

        $("button.btn-cancel-nik-baru").click(function (e) {
            e.preventDefault();

            showHideFieldNikBaru(true);
        });

        $("button.btn-edit-nik-baru").click(function (e) {
            e.preventDefault();
            showHideFieldNikBaru();
        });

        valA = $('#LEMBAGA').val();
        valUK = $('#UNIT_KERJA').val();
        valSUK = $('#SUB_UNIT_KERJA').val();
        valJAB = $('#JABATAN').val();

        if (valA != '') {
//			 $('#JABATAN').select2("data", {id: valJAB, name: "<?php //echo $daftar_pn->NAMA_JABATAN; ?>"});
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).prop('disabled', false);
            // $('input[name="JABATAN"]', idWrapFormJabatan).prop('disabled', false);

            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2('val', '');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('val', '');
            LEMBAGA = valA;
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerjaTanpaDefault') ?>/" + LEMBAGA,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerjaTanpaDefault') ?>/" + LEMBAGA + '/' + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });

            $('input[name="SUB_UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK + '/' + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });

            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getJabatan_maret') ?>/" + valUK + '/' + valSUK,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getJabatan3') ?>/" + valUK + '/' + valSUK + '/' + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });
        }

        $('#SUB_UNIT_KERJA', idWrapFormJabatan).change(function (event) {
            SUB_UNIT_KERJA = $(this).val();
            UNIT_KERJA = $("#UNIT_KERJA").val();
            uk_ = UNIT_KERJA;

            freeSelectionSelect2(["JABATAN"]);

            var isSelect2Ok = evaluateIsSelect2(this);

            if (isSelect2Ok) {
                $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + SUB_UNIT_KERJA,
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
//                    alert(id);
                        if (id !== "") {
                            $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + SUB_UNIT_KERJA, {
                                dataType: "json"
                            }).done(function (data) {
                                callback(data[0]);
                            });
                        }
                    },
                    formatResult: function (state) {
                        return state.name;
                    },
                    formatSelection: function (state) {
                        return state.name;
                    }
                });
            } else {
                $(this).val('');
            }

        });


        // AutoCalculateMandateOnChange('#is_aplikasi')

    });




</script>