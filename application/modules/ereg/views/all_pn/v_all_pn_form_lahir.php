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
    <form class="form-horizontal" name="forminput" method="post" id="ajaxFormAdd" actrel="index.php/ereg/all_pn/savepnwl" action="#" enctype="multipart/form-data">
        <div id="wrapperFormAddPN"> 
            <input type="hidden" name="iscln" value="<?php echo $iscln; ?>">
            <div class="col-md-6">

                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Nomor Induk Kependudukan (NIK) <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('nik_pnwl'); ?>
                        <input name='NIK' id='NIK' onkeyup='HitungText()' onkeypress="return isNumber(event)" maxlength="16" required type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nik_pnwl') ?>>               
                    </div>
                    <div class="col-sm-1">
                    </div>                
                </div>                
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <span align='center' id='NIK1'></span>
                        <span align='center' id='NIK2'></span>
                        <span align='center' id='NIK3'></span>

                    </div>
                    <div class="col-sm-1"></div>

                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Nama Lengkap <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('nama_pnwl'); ?>
                        <input required class="form-control" onkeyup="this.value = this.value.toUpperCase()" type='text' size='40' name='NAMA' id='NAMA' <?php echo FormHelpPlaceholderToolTip('nama_pnwl') ?>>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Tempat / Tanggal Lahir <span class="red-label">*</span></label>
                        <?php echo FormHelpPopOver('tempat_tanggal_lahir_pnwl'); ?>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">
                        <input required class="form-control" onkeyup="this.value = this.value.toUpperCase()" type='text'name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' <?php echo FormHelpPlaceholderToolTip('tempat_tanggal_lahir_pnwl') ?> >
                    </div>
                    <div class="col-sm-5">
                        <input required class="form-control TGL_LAHIR" type='text'name='TGL_LAHIR' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>Jenis Kelamin <span class="red-label">*</span></label> 
                        <?php echo FormHelpPopOver('jenis_kelamin_pnwl'); ?>
                        <select required class="form-control" name="JNS_KEL" id="JNS_KEL">
                            <option value="">-- Pilih --</option>
                            <option value="1">LAKI - LAKI</option>
                            <option value="2">PEREMPUAN</option>
                        </select>
                        <div class="col-sm-1"></div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label>NRP/NIP/Nomor Pegawai </label> 
                        <?php echo FormHelpPopOver('nip_pnwl'); ?>
                        <input name='NIP' id='NIP'  type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nip_pnwl', false) ?>>
                    </div>
                    <div class="col-sm-1">
                    </div>
                </div>

            </div>


            <div class="col-md-6">
                <div id="wrapperFormJabatan">
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <label>Instansi <span class="red-label">*</span></label> 
                            <?php echo FormHelpPopOver('instansi_pnwl'); ?>
                            <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" <?php echo $isInstansi ? "value='$isInstansi' readonly='readonly'" : '' ?> id='LEMBAGA'  placeholder="lembaga" required onchange="clearvalue(this)">
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>

                    <?php if ($iscln == 1) { // Untuk Calon PNWL ?>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>Unit Kerja <span class="red-label"></span></label> 
                                <?php echo FormHelpPopOver('unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" <?php echo $is_uk ? "value='$is_uk' readonly='readonly'" : '' ?> id='UNIT_KERJA'  placeholder="Unit Kerja">
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>Sub Unit Kerja <span class="red-label"></span></label> 
                                <?php echo FormHelpPopOver('sub_unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' placeholder="Sub Unit Kerja">
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>Jabatan<span class="red-label">*</span></label> 
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
                                <label>Unit Kerja <span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' <?php echo $is_uk ? "value='$is_uk' readonly='readonly'" : '' ?>   placeholder="Unit Kerja" required>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>Sub Unit Kerja <span class="red-label"></span></label> 
                                <?php echo FormHelpPopOver('sub_unit_kerja_pnwl'); ?>
                                <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' placeholder="Sub Unit Kerja">
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label>Jabatan<span class="red-label">*</span></label> 
                                <?php echo FormHelpPopOver('jabatan_pnwl'); ?>
                                <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>

                    <?php } ?>
                </div>

                <div class="form-group">
                    <div class="col-sm-1"></div>

                    <div class="col-sm-10">
                        <label>Pelaporan Online<span class="red-label">*</span></label> 
                        <input type='checkbox' name='is_aplikasi'checked onclick='AutoCalculateMandateOnChange(this);'>
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
                        <label>Alamat Email<span class="red-label"  id="span1">*</span></label> 
                        <?php echo FormHelpPopOver('email_pnwl'); ?>
                        <input required  type='email' class="form-control" name='EMAIL' size='40' id='EMAIL' onblur="cek_emails(this.value);" value='' <?php echo FormHelpPlaceholderToolTip('email_pnwl') ?> placeholder="">
                        <?php
                        /**
                          <span class="help-block">
                          <font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font>
                          <font id='email_salah' style='display:none;' color='red'>Format Email Salah</font>
                          </span>
                         */
                        ?>
                        <div class="help-block">
                            <div id='email_ada' style='display:none;' color='red'><font color='red'>Email sudah terdaftar</font></div>
                            <div id='email_salah' style='display:none;' color='red'><font color='red'>Format Email Salah</font></div>
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
                        <label>Nomor Handphone<span class="red-label" id="span2">*</span></label> 
                        <?php echo FormHelpPopOver('handphone_pnwl'); ?>
                        <input required class="form-control" type='text' onkeypress="validate(event)" size='40' name='NO_HP' id='NO_HP' <?php echo FormHelpPlaceholderToolTip('handphone_pnwl') ?> >
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
                            <button type="button" class="btn btn-sm btn-primary" id="btnSubmit"><i class="fa fa-share"></i>Simpan <?php echo (@$iscln == '1' ? 'Calon ' : '') ?>PN/WL</button>
                            <!--<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan <?php echo (@$iscln == '1' ? 'Calon ' : '') ?>PN/WL</button>-->
                            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
                        </div></div>
                    <div class="col-sm-1"></div>
                </div>

            </div>
        </div>
    </form>
    <div id="wrapperFormPNExist" class="wrap" style="display: none;">
        PN Sudah Terdaftar, tekan lanjut untuk mengubah data!<br>
        <button type="button" class="btn btn-sm btn-default" onClick="toFormPN(this)">Kembali Ke Form</button>
        <button type="button" class="btn btn-sm btn-default" id="btnLanjut">Lanjut</button>
    </div>
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
    
    function HitungText(needResponse) {
        
        
        
        var Teks = document.forminput.NIK.value.length;
        var total = document.getElementById('NIK2');
        var valid = document.getElementById('NIK3');

        var cekverifikasinik = document.getElementById('NIK1');
        // total.innerHTML = Teks + ' Karakter';  
        
        if(!isDefined(needResponse)){
            needResponse = false;
        }
        var isNikOk = testDigitNik(Teks, total);
        
        if(needResponse){
            return isNikOk;
        }
        

        var nik = $('#NIK').val();
        var url = "index.php/ereg/all_pn/cek_nik/" + nik;
        $.post(url, function (data) {
            if (data == '0') {
                //valid.innerHTML = '<br/><img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> NIK tidak boleh sama';
                $.post('index.php/ereg/all_pn/cekNIK/' + nik + '/<?php echo $status ?>', {redirect: window.location.href.split('#')[1]}, function (data, textStatus, xhr) {
                    $('.modal-dialog').animate({
                    })

                    $("#wrapperFormAddPN").hide('fast');
                    $("#wrapperFormPNExist").html(data);
                    $("#wrapperFormPNExist").show('fast');
                });
                $('#NIK').val('');
            } else if (data == '2') {
                //$('#NIK').val('');
                $('#NIK2').hide('fast');
                $.post('index.php/ereg/all_pn/cekNIK/' + nik + '/<?php echo $status ?>', {redirect: window.location.href.split('#')[1]}, function (data, textStatus, xhr) {
                    $('.modal-dialog').animate({
                    })

                    $("#wrapperFormAddPN").hide('fast');
                    $("#wrapperFormPNExist").html(data);
                    $("#wrapperFormPNExist").show('fast');
                });
                //cekverifikasinik.innerHTML = '<img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> NIK : '+nik+'  dalam Proses Verifikasi/Approval Perubahan ';
                //document.getElementById("NIK").focus();
            } else {
                valid.innerHTML = '';
            }
            ;
        });

    }



    $(document).ready(function () {

        valA = $('#LEMBAGA').val();
        valUK = $('#UNIT_KERJA').val();

        if (valA != '') {
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).prop('disabled', false);
            // $('input[name="JABATAN"]', idWrapFormJabatan).prop('disabled', false);



            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2('val', '');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('val', '');
            LEMBAGA = valA;
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + '/' + id, {
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

        if (valUK != '') {
            UNIT_KERJA = valUK;
            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA,
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
                    // alert(id);
                    if (id !== "") {
                        $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + id, {
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
                    url: "<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
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
            uk_ = $('#UNIT_KERJA').val();
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
                        alert(id);
                        if (id !== "") {
                            $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + SUB_UNIT_KERJA + "/" + id, {
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

    });
</script>