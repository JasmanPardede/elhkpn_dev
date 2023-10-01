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
<script type="text/javascript">
    <?php
    $arr_status = array();
    foreach ( $status_akhir as $status ) {
        $arr_status[$status->ID_STATUS_AKHIR_JABAT] = $status;
    }
    ?>
        var status_akhir = '<?php echo json_encode(array('result' => $arr_status)) ?>';
        var data_status_akhir = JSON.parse(status_akhir);
    </script>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <div id="wrapperFormAddPN">
            
        <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
            <div role="tabpanel" class="tab-pane active" id="a">
            <div class="contentTab">
            <div class="form-group">
                <label class="col-sm-3 control-label">NIK <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <input required class="form-control" type='text' maxlength="16" size='50' name='NIK' id='NIK' placeholder="NIK" onkeypress="return isNumber(event)">
                    <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-nik">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon addon-custom"><input type="text" placeholder="Dr." name="GELAR_DEPAN"></div>
                        <input required class="form-control" type='text' size='40' name='NAMA' id='NAMA' placeholder="Nama">
                        <div class="input-group-addon addon-custom"><input type="text" placeholder=",SH" name="GELAR_BELAKANG"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Jenis Kelamin <span class="red-label">*</span>:</label>
                <div class="col-sm-5">
                    <select required class="form-control" name="JNS_KEL" id="JNS_KEL">
                        <option value="">-- Pilih --</option>
                        <option value="1">Laki - Laki</option>
                        <option value="2">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Tempat / Tanggal Lahir <span class="red-label">*</span>:</label>
                <div class="col-sm-4">
                    <input required class="form-control" type='text'name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat">
                </div>
                <div class="col-sm-3">
                    <input required class="form-control" type='text'name='TGL_LAHIR' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Agama <span class="red-label">*</span>:</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="ID_AGAMA" id="ID_AGAMA">
                        <option value="">-- Pilih --</option>
                        <?php foreach ($agama as $agamas): ?>
                            <option value="<?php echo @$agamas->ID_AGAMA; ?>"><?php echo @$agamas->AGAMA; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Status Nikah <span class="red-label">*</span>:</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="ID_STATUS_NIKAH" id="ID_STATUS_NIKAH">
                        <option value="">-- Pilih --</option>
                        <?php foreach ($sttnikah as $sttnikahs): ?>
                            <option value="<?php echo @$sttnikahs->ID_STATUS; ?>"><?php echo @$sttnikahs->STATUS_NIKAH; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Pendidikan Terakhir <span class="red-label">*</span>:</label>
                <div class="col-sm-3">
                    <select required class="form-control" name="ID_PENDIDIKAN" id="ID_PENDIDIKAN">
                        <option value="">-- Pilih --</option>
                        <?php foreach ($penhir as $penhirs): ?>
                            <option value="<?php echo @$penhirs->ID_PENDIDIKAN; ?>"><?php echo @$penhirs->PENDIDIKAN; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">NPWP <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <input required class="form-control" type='text' name='NPWP' id='NPWP' placeholder="NPWP">
                </div>
            </div>
            </div>
            <br>
            <div class="pull-right">
            <a href="#b" aria-controls="final" role="tab" data-toggle="tab" class="navTab">
            <button type="button" class="btn btn-sm btn-primary btnNext">Selanjutnya <i class="fa fa-chevron-circle-right"></i></button>
            </a>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
            </div>
            <div class="clearfix"></div>
            </div>

            <div role="tabpanel" class="tab-pane" id="b">
            <div class="contentTab">
            <div class="form-group">
                <label class="col-sm-3 control-label">Negara  <span class="red-label">*</span> :</label>
                <div class="col-sm-7">
                    <div class='col-sm-6'>
                        <label>
                            <input required type="radio" name='NEGARA' id='NEGARA' onClick="dalam();" value="2"> Indonesia
                        </label>
                    </div>
                    <div class='col-sm-6'>
                        <label>
                            <input required type="radio" name='NEGARA' onClick="luar();" id='NEGARA' value="1" > Luar Negeri
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group luarlokasi" style="display:none;">
                <label class="col-sm-3 control-label">Nama Negara<font color='red'>*</font> :</label>
                <div class="col-sm-7">
                    <!-- <div class='col-sm-6'> -->
                        <input type='text' class="form-control form-select2 luarnegeri" name='KD_ISO3_NEGARA' style="border:none;" id='KD_ISO3_NEGARA' value='' placeholder="Negara">
<!--                     </div> -->
                </div>
            </div>

            <div class="form-group lokasi">
                <label class="col-sm-3 control-label">Provinsi  <span class="red-label">*</span> :</label>
                <div class="col-sm-7">
                    <input name='PROV' class="form-control form-select2 dalamnegeri" style="border:0px;" placeholder="Provinsi"/>
<!--                     <select name='PROV' style="border:0px;" id='PROV' class="form-control form-select2 dalamnegeri" onchange="kabkot();" placeholder="Provinsi">
                        <option value=""></option>
                    </select> -->
                </div>
            </div>

            <div class="form-group lokasi">
                <label class="col-sm-3 control-label">Kabupaten/Kota <span class="red-label">*</span> :</label>
                <div class="col-sm-7">
                    <input name='KAB_KOT' style="border:0px;" class="form-control form-select2 dalamnegeri" placeholder="Kabupaten Kota" disabled/>
<!--                     <select name='KAB_KOT' id='KAB_KOT' disabled="disabled" style="border:0px;" class="form-control form-select2 dalamnegeri" onchange="kec();" placeholder="Kabupaten Kota">
                        <option value=""></option>
                    </select> -->
                </div>
            </div>

            <div class="form-group lokasi">
                <label class="col-sm-3 control-label">Kecamatan <span class="red-label">*</span> :</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control dalamnegeri" value="" name="KEC" placeholder="Kecamatan" required>
                    <!-- <input name='KEC' style="border:0px;" class="form-control form-select2 dalamnegeri" placeholder="Kecamatan" disabled/> -->
                    <!-- <input type="text"  name='KEC' id='KEC' style="border:none;" class="form-control" placeholder="Kecamatan"> -->
<!--                     <select name='KEC' id='KEC' disabled="disabled" style="border:0px;" class="form-control form-select2 dalamnegeri" onchange="kel();" placeholder="Kecamatan">
                        <option value=""></option>
                    </select> -->
                </div>
            </div>

            <div class="form-group lokasi">
                <label class="col-sm-3 control-label">Kelurahan <span class="red-label">*</span> :</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control dalamnegeri" value="" name="KEL" placeholder="Kelurahan" required>
                    <!-- <input name='KEL' class="form-control form-select2 dalamnegeri" style="border:0px;" placeholder="Kelurahan" disabled/> -->
<!--                <select name='KEL' id='KEL' disabled="disabled" style="border:0px;" class="form-control form-select2 dalamnegeri" placeholder="Kelurahan">
                        <option value=""></option>
                    </select> -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Alamat Tinggal <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <textarea required class="form-control" type='text' name='ALAMAT_TINGGAL' id='ALAMAT_TINGGAL' placeholder="Alamat Tinggal"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Email <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <input required class="form-control" type='email' size='40' name='EMAIL' onblur="cek_email_pn(this.value);" id='EMAIL' placeholder="johnsmith@email.com">
                    <span class="help-block"><font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font></span>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-email">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">No HP <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <input required class="form-control numbersOnly" type='text' onkeypress="validate(event)" size='40' name='NO_HP' id='NO_HP' placeholder="NO HP">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Foto <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <input type='file' size='40' name='FILE_FOTO' class="FILE_FOTO" id='FOTO' required>
                    <span class=' help-block'>Type File: png, jpg, jpeg, tif .  Max File: 500KB</span>
                </div>
            </div>
            </div>
            <br>
            <div class="pull-right">
            <a href="#a" aria-controls="final" role="tab" data-toggle="tab" class="navTab">
            <button type="button" class="btn btn-sm btn-primary btnNext"><i class="fa fa-chevron-circle-left"></i> Sebelumnya</button>
            </a>
            <input type="hidden" name="ID_PN" id="ID_PN">
                <input type="hidden" name="act" id="act" value="doinsert">
                <button type="button" class="btn btn-sm btn-success" onClick="toFormJabatan()">Lanjut</button>
                 <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
            </div>
            <div class="clearfix"></div>
            </div>

        </div>

            
            
            <div class="pull-right">
                <!-- <input type="hidden" name="ID_PN" id="ID_PN">
                <input type="hidden" name="act" id="act" value="doinsert">
                <button type="button" class="btn btn-sm btn-success" onClick="toFormJabatan()">Lanjut</button>
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();"> -->
            </div>
        </div>
        <div id="wrapperFormJabatan" class="wrap" style="display: none;">
            <!-- <input type="hidden" name="iscln" value="<?php echo @$iscln ?>" /> -->
            <div class="form-group">
            <label class="col-sm-3 control-label">Status Jabatan<font color='red'>*</font> :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <?php if($iscln) { ?>
                            <div class="col-sm-4">
                                <label>
                                    <input required type="radio" name="iscln" value="1"> Calon
                                <label>
                            </div>
                        <?php } ?>
                        <div class="col-sm-4">
                            <label>
                                <input required type="radio" name="iscln" value="0"> Menjabat
                            <label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Lembaga<font color='red'>*</font> :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <input type='text' class="form-control form-select2" name='LEMBAGA' style="border:none;" <?php echo $isInstansi ? "value='$isInstansi' readonly='readonly'" : '' ?> id='LEMBAGA'  placeholder="lembaga" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Unit Kerja<font color='red'>*</font> :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Jabatan<font color='red'>*</font> :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Deskripsi Jabatan<font color='red'>*</font> :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <input type="text" class="form-control" name="DESKRIPSI_JABATAN" value="" placeholder="Deskripsi Jabatan" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Eselon<font color='red'>*</font> :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                            <option value='1'>I</option>
                            <option value='2'>II</option>
                            <option value='3'>III</option>
                            <option value='4'>IV</option>
                            <option value='5'>Non-Eselon</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Alamat Kantor :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <textarea class='form-control' name="ALAMAT_KANTOR" placeholder="Alamat Kantor"><?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR != '') { echo @json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR;}else{ echo '';}?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Email Kantor :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <input type='email' class='form-control' value="<?=@json_decode(@$DATA_PRIBADI->JABATAN)->EMAIL_KANTOR?>" name="EMAIL_KANTOR" placeholder="Email Kantor">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">SK:</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <input type="file" name="FILE_SK">
                        <span class='help-block'>Type File: xls, xlsx, doc, docx, pdf .  Max File: 500KB</span>
                    </div>
                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">TMT<font color='red'>*</font> :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12'>
                        <div class="col-md-4" style="margin-left: -15px;">
                            <input type="text" class="form-control datepicker" name="TMT" value="<?=date('d/m/Y')?>" required>
                        </div>
<!--                         <div class="col-md-4">
                            <input type="text" class="form-control datepicker" name="SD" value="">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-sm btn-primary">Simpan data <?php echo (@$iscln == '1'? 'Calon ' : '') ?>PN/WL</button>
                <button type="button" class="btn btn-sm btn-success" onClick="toFormPN(this)">Ke Form <?php echo (@$iscln == '1'? 'Calon ' : '') ?>PN/WL</button>
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
            </div>
        </div>
    </form>
    <div id="wrapperFormPNExist" class="wrap" style="display: none;">
        PN Sudah Terdaftar, tekan lanjut untuk mengubah data!<br>
        <button type="button" class="btn btn-sm btn-default" onClick="toFormPN(this)">Kembali Ke Form</button>
        <button type="button" class="btn btn-sm btn-default" id="btnLanjut">Lanjut</button>
    </div>
</div>
<script type="text/javascript">
    // index.php/ereg/all_pn/addjabatan/$item->ID_PN

    function toFormJabatan(){
        var required = [];
        $( ':input[required]', "#wrapperFormAddPN" ).each( function () {
            // alert(this.value.trim());
            if ( this.value.trim() !== '' ) {
                required.push('yes');
            }
            else
            {
                required.push('no');
            }
        });
        if (inArray('no',required) == false)
        {
            $("#wrapperFormAddPN").hide('slow', function(){
                $('#wrapperFormJabatan').show('slow');
            });
        }
        else
        {
            alertify.error('Mohon melengkapi data wajib !');
        };
        
    }

    function inArray(needle, haystack) {
        var length = haystack.length;
        for(var i = 0; i < length; i++) {
            if(haystack[i] == needle) return true;
        }
        return false;
    }

    function toFormPN(ele){
        var wrap = $(ele).closest('.wrap');

        wrap.hide('fast', function(){
            $("#wrapperFormAddPN").show('slow');
            $("#NIK").focus();
        });
    }

    $(document).ready(function() {
        var idWrapFormJabatan = $('#wrapperFormJabatan');

        var valA = $('#LEMBAGA').val();
        if(valA != ''){
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).prop('disabled', false);
            $('input[name="JABATAN"]', idWrapFormJabatan).prop('disabled', false);

            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2('val', '');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('val', '');
            LEMBAGA = valA;
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2({
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

            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>/"+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>/"+LEMBAGA+"/"+id, {
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
        }
        // $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
        // $('#INST_SATKERKD').select2();
        // $('#INST_SATKERKD').change(function() {
        //     $.post("<?php echo base_url();?>index.php/ereg/all_pn/get_unit_kerja", {INST_SATKERKD: $(this).val()})
        //             .done(function (data) {
        //                 var uk      = JSON.parse(data);
        //                 var html    = '<option value=""> - Pilih Unit Kerja - </option> ';
        //                 for ( var i=0; i<uk.result.length; i++ ) {
        //                     html += '<option value="'+ uk.result[i].UK_ID +'">';
        //                     html += uk.result[i].UK_NAMA;
        //                     html += '</option>';
        //                 }
        //                 $('#UNIT_KERJA').html(html);
        //             });
        // });

         // $('#NIK').keypress(validateNumber);

        $('.FILE_FOTO').change(function(){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var arr     = ['tiff', 'tif','jpg','png','jpeg'];
            var maxsize = 500000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });

        var form = $("#ajaxFormAdd");

        var msg = {
            success : 'Data Berhasil Disimpan!',
            error : 'Data Gagal Disimpan!'
        };

        $("#ajaxFormAdd").submit(function () {
            $('#loader_area').show();
            var urll = form.attr('action');
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function (htmldata) {
                    htmldata = JSON.parse(htmldata);
                    if(htmldata.status == 0){
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    }else if(htmldata.status == 2){
                        alertify.error('Nik sudah terdaftar sebagai user!!!');
                        $('#loader_area').hide();
                    }else{
                        alertify.success(msg.success);

                        $.get(location.href.split('#')[1], function(html){
                            $('#ajax-content').html(html);
                            CloseModalBox();

//                            setTimeout(function(){
//                                $.post('index.php/ereg/all_pn/addjabatan/'+htmldata.id+'?calon=<?php //echo @$iscln; ?>//', function (html) {
//                                    OpenModalBox('Riwayat Jabatan', html, '', 'large');
//
//                                    $('#wrapperKllJabatan').ready(function(){
//                                        showaddkll();
//                                    });
//
//                                });
//                            }, 1000)
                            $('#loader_area').hide();
                        })
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

//        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1], asd);

        $("#NIK").blur(function(){
            var val = $(this).val();
            if(val != ''){
                $.post('index.php/ereg/all_pn/cekNIK/'+$(this).val()+'/<?=$status?>', {redirect : window.location.href.split('#')[1]}, function(data, textStatus, xhr) {
                    if(data != 0){
                        $('.modal-dialog').animate({
                            width: '+=500'
                        })

                        $("#wrapperFormAddPN").hide('fast');
                        $("#wrapperFormPNExist").html(data);
                        $("#wrapperFormPNExist").show('fast');
                    }
                });
            }
        });

        $('input[name="KD_ISO3_NEGARA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getNegara')?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getNegara')?>/"+id, {
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

        $('input[name="PROV"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getProvinsi')?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getProvinsi')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                prov = state.id;
                return state.name;
            }
        });
        $('input[name="PROV"]').on("change", function (e) {
            $('input[name="KAB_KOT"]').prop("disabled", false);

            $('input[name="KAB_KOT"]').select2("val", "");
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });

        $('input[name="KAB_KOT"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getKabupatenKota')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term,
                        prov: prov
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getKabupatenKota')?>/"+prov+'/'+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                kab = state.id;
                return state.name;
            }
        });
        $('input[name="KAB_KOT"]').on("change", function (e) {
            $('input[name="KEC"]').prop("disabled", false);

            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });

        // $('input[name="KEC"]').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?php echo base_url('index.php/share/reff/getKecamatan')?>",
        //         dataType: 'json',
        //         quietMillis: 250,
        //         data: function (term, page) {
        //             return {
        //                 q: term,
        //                 prov: prov,
        //                 kab: kab
        //             };
        //         },
        //         results: function (data, page) {
        //             return { results: data.item };
        //         },
        //         cache: true
        //     },
        //     initSelection: function(element, callback) {
        //         var id = $(element).val();
        //         if (id !== "") {
        //             $.ajax("<?php echo base_url('index.php/share/reff/getKecamatan')?>/"+prov+'/'+kab+'/'+id, {
        //                 dataType: "json"
        //             }).done(function(data) { callback(data[0]); });
        //         }
        //     },
        //     formatResult: function (state) {
        //         return state.name;
        //     },
        //     formatSelection:  function (state) {
        //         kec = state.id;
        //         return state.name;
        //     }
        // });
        // $('input[name="KEC"]').on("change", function (e) {
        //     $('input[name="KEL"]').prop("disabled", false);
            
        //     $('input[name="KEL"]').select2("val", "");
        // });


        // $('input[name="KEL"]').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?php echo base_url('index.php/share/reff/getKelurahan')?>",
        //         dataType: 'json',
        //         quietMillis: 250,
        //         data: function (term, page) {
        //             return {
        //                 q: term,
        //                 prov: prov,
        //                 kab: kab,
        //                 kec: kec
        //             };
        //         },
        //         results: function (data, page) {
        //             return { results: data.item };
        //         },
        //         cache: true
        //     },
        //     initSelection: function(element, callback) {
        //         var id = $(element).val();
        //         if (id !== "") {
        //             $.ajax("<?php echo base_url('index.php/share/reff/getKelurahan')?>/"+prov+'/'+kab+'/'+kec+'/'+id, {
        //                 dataType: "json"
        //             }).done(function(data) { callback(data[0]); });
        //         }
        //     },
        //     formatResult: function (state) {
        //         return state.name;
        //     },
        //     formatSelection:  function (state) {
        //         return state.name;
        //     }
        // });

        $("#btnLanjut").click(function(e) {
            $("#CARI_INST").val();
            $("#CARI_STATUS_PN").val();
            $("#CARI_TEXT").val($("#NIK").val());
            $("#CARI_TEXT").after( '<input type="text" id="CARI_USEWHEREONLY" name="CARI[USEWHEREONLY]" value="1">' );
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

        $('#LEMBAGA', idWrapFormJabatan).change(function(event) {
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).prop('disabled', false);
            $('input[name="JABATAN"]', idWrapFormJabatan).prop('disabled', false);

            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2('val', '');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('val', '');
            LEMBAGA = $(this).val();
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2({
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

            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan')?>/"+LEMBAGA,
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan')?>/"+LEMBAGA+"/"+id, {
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
        });
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
<?php
}
?>
<script type="text/javascript">
    function luar(){
        // alert('luar');
        $('.dalamnegeri').attr('required', false);
        $('.luarnegeri').attr("required", true);
        $(".luarlokasi").show();
        $(".lokasi").hide();
    }

    function dalam(){
        // alert('dalam');
        $('.dalamnegeri').attr("required", true);
        $('.luarnegeri').attr('required', false);
        $(".luarlokasi").hide();
        $(".lokasi").show();
    }
</script>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <input type='hidden' name='ID_PN' id='ID_PN'  value='<?php echo $item->ID_PN;?>'>
        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
        <input type="hidden" name="HIDDEN_NIK" id="HIDDEN_NIK" value="<?php echo $item->NIK; ?>">
        
    <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
            <div role="tabpanel" class="tab-pane active" id="a">
            <div class="contentTab">
            <div class="form-group">
            <label class="col-sm-3 control-label">NIK <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' name='NIK' id='NIK' placeholder="NIK" value='<?php echo $item->NIK;?>' onblur="cek_user_edit(this.value, $('#HIDDEN_NIK').val())" readonly />
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_edit" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
            </div>
            <div class="col-sm-1" style="margin-top: 5px;" id="div-nik">
                <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <div class="input-group">
                    <div class="input-group-addon addon-custom"><input type="text" placeholder="Dr." name="GELAR_DEPAN" value="<?php echo @$item->GELAR_DEPAN ?>"></div>
                    <input required class="form-control" type='text' size='40' name='NAMA' id='NAMA' placeholder="Nama" value='<?php echo $item->NAMA;?>'>
                    <div class="input-group-addon addon-custom"><input type="text" placeholder=",SH" name="GELAR_BELAKANG" value="<?php echo @$item->GELAR_BELAKANG ?>"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jenis Kelamin <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <select class="form-control" name="JNS_KEL">
                    <option value="1" <?php if($item->JNS_KEL == 1) echo "selected"; ?> >Laki - Laki</option>
                    <option value="2" <?php if($item->JNS_KEL == 2) echo "selected"; ?>>Perempuan</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Tempat / Tanggal Lahir <span class="red-label">*</span>:</label>
            <div class="col-sm-4">
                <input required class="form-control" type='text' name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat Lahir" value='<?php echo $item->TEMPAT_LAHIR;?>'>
            </div>
            <div class="col-sm-3">
                <input required class="form-control date-picker" type='text' name='TGL_LAHIR' id='TGL_LAHIR' placeholder='DD/MM/YYYY' value="<?php echo date('d/m/Y', strtotime($item->TGL_LAHIR)); ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Agama <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
                <select name="ID_AGAMA" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach ($agama as $agamas): ?>
                        <option <?php echo ($item->ID_AGAMA == $agamas->ID_AGAMA ? 'selected' : ''); ?> value="<?php echo @$agamas->ID_AGAMA; ?>"><?php echo @$agamas->AGAMA; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Status Nikah <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
                <select name="ID_STATUS_NIKAH" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach ($sttnikah as $sttnikahs): ?>
                        <option <?php echo ($item->ID_STATUS_NIKAH == $sttnikahs->ID_STATUS ? 'selected' : ''); ?> value="<?php echo @$sttnikahs->ID_STATUS; ?>"><?php echo @$sttnikahs->STATUS_NIKAH; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Pendidikan Terakhir <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
                <select name="ID_PENDIDIKAN" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach ($penhir as $penhirs): ?>
                        <option <?php echo ($item->ID_PENDIDIKAN == $penhirs->ID_PENDIDIKAN ? 'selected' : ''); ?> value="<?php echo @$penhirs->ID_PENDIDIKAN; ?>"><?php echo @$penhirs->PENDIDIKAN; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">NPWP <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' name='NPWP' id='NPWP' placeholder="NPWP" value='<?php echo $item->NPWP;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Negara <span class="red-label">*</span> :</label>
            <div class="col-sm-5">
                <div class='col-sm-6'>
                    <label>
                        <input required type="radio" name='NEGARA' id='NEGARA' onClick="dalam();" value="2" <?php echo $item->NEGARA == '2' ? 'checked' : '' ;?>> Indonesia
                    </label>
                </div>
                <div class='col-sm-6'>
                    <label>
                        <input required type="radio" name='NEGARA' id='NEGARA' onClick="luar();" value="1" <?php echo $item->NEGARA == '1' ? 'checked' : '' ;?>> Luar Negeri
                    </label>
                </div>
            </div>
        </div>
            </div>
            <br>
            <div class="pull-right">
            <a href="#b" aria-controls="final" role="tab" data-toggle="tab" class="navTab">
            <button type="button" class="btn btn-sm btn-primary btnNext">Selanjutnya <i class="fa fa-chevron-circle-right"></i></button>
            </a>
           <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
            </div>
            <div class="clearfix"></div>
            </div>

            <div role="tabpanel" class="tab-pane" id="b">
            <div class="contentTab">
            
        <div class="form-group luarlokasi">
            <label class="col-sm-3 control-label">Nama Negara<font color='red'>*</font> :</label>
            <!-- <div class="col-sm-9"> -->
                <div class='col-sm-5'>
                    <input type='text' class="form-control form-select2 luarnegeri" name='KD_ISO3_NEGARA' style="border:none;" id='KD_ISO3_NEGARA' value='<?php echo @$item->LOKASI_NEGARA;?>' placeholder="Negara">
                <!-- </div> -->
            </div>
        </div>
        <div class="form-group lokasi">
            <label class="col-sm-3 control-label">Provinsi <span class="red-label">*</span> :</label>
            <div class="col-sm-5">
                <input name='PROV' class="form-control form-select2 dalamnegeri" style="border:0px;" placeholder="Provinsi" value="<?php echo $item->PROV; ?>" />
            </div>
        </div>
        <div class="form-group lokasi">
            <label class="col-sm-3 control-label">Kabupaten/Kota <span class="red-label">*</span> :</label>
            <div class="col-sm-5">
                <input name='KAB_KOT' value="<?php echo $item->KAB_KOT?>" style="border:0px;" class="form-control form-select2 dalamnegeri" placeholder="Kabupaten Kota" />
            </div>
        </div>
        <div class="form-group lokasi">
            <label class="col-sm-3 control-label">Kecamatan <span class="red-label">*</span> :</label>
            <div class="col-sm-5">
                <input required type="text" class="form-control dalamnegeri" value="<?php echo $item->KEC ?>" name="KEC" placeholder="Kelurahan">
                <!-- <input name='KEC' value="<?php echo $item->KEC ?>" style="border:0px;" class="form-control form-select2 dalamnegeri" placeholder="Kecamatan" /> -->
            </div>
        </div>
        <div class="form-group lokasi">
            <label class="col-sm-3 control-label">Kelurahan <span class="red-label">*</span> :</label>
            <div class="col-sm-5">
                <input required type="text" class="form-control dalamnegeri" value="<?php echo $item->KEL;?>" name="KEL" placeholder="Kelurahan">
                <!-- <input name='KEL' class="form-control form-select2 dalamnegeri" style="border:0px;" placeholder="Kelurahan" value="<?php echo $item->KEL;?>" /> -->
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Tinggal <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
            <textarea required class="form-control" type='text' name='ALAMAT_TINGGAL' id='ALAMAT_TINGGAL' placeholder="Alamat Tinggal"><?php echo $item->ALAMAT_TINGGAL;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='email' size='40' name='EMAIL' onblur="val = this.value; if(val!='<?php echo $item->EMAIL;?>'){cek_email_pn(val, '<?php echo $item->EMAIL;?>');}" id='EMAIL' placeholder="johnsmith@email.com" value='<?php echo $item->EMAIL;?>'>
                <span class="help-block"><font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font></span>
            </div>
            <div class="col-sm-1" style="margin-top: 5px;" id="div-email">
                <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">No HP <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control numbersOnly" onkeypress="validate(event)" type='text' name='NO_HP' id='NO_HP' placeholder="NO HP" value='<?php echo $item->NO_HP;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-5">
                <img src="./uploads/data_pribadi/<?php echo$item->NIK?>/<?php echo$item->FOTO?>" width="100%">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Foto :</label>
            <div class="col-sm-5">
                <input type='file' size='40' class="FILE_FOTO" name='FILE_FOTO' id='FOTO'>
                <span class=' help-block'>Type File: png, jpg, jpeg, tiff .  Max File: 500KB</span>
                <input type="hidden" name="OLD_FILE" value="<?php echo$item->FOTO?>">
            </div>
        </div>
            </div>
            <br>
            <div class="pull-right">
            <a href="#a" aria-controls="final" role="tab" data-toggle="tab" class="navTab">
            <button type="button" class="btn btn-sm btn-primary btnNext"><i class="fa fa-chevron-circle-left"></i> Sebelumnya</button>
            </a>
             <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
            </div>
            <div class="clearfix"></div>
            </div>

        </div>




        
      
        <div class="pull-right">
           <!--  <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();"> -->
        </div>
    </form>
</div>
<script type="text/javascript">
    var prov    = '';
    var kab     = '';
    var kec     = '';
    var kel     = '';
    $(document).ready(function() {
        // $('.numbersOnly').mask("(+99) 9999?-9999?-9999");

        $('.FILE_FOTO').change(function(){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var arr     = ['tiff', 'tif','jpg','png','jpeg'];
            var maxsize = 500000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });

        if('<?php echo $item->NEGARA?>' != 2)
        {
            $(".lokasi").hide();
            $(".luarlokasi").show();
        }
        else
        {
            $(".luarlokasi").hide();
            $(".lokasi").show();
        }
        var prov    = $('input[name="PROV"]').val();
        var kab     = $('input[name="KAB_KOT"]').val();
        var kec     = $('input[name="KEC"]').val();
        var kel     = $('input[name="KEL"]').val();

        ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);

        $('input[name="KD_ISO3_NEGARA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getNegara')?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getNegara')?>/"+id, {
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

        $('input[name="PROV"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getProvinsi')?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getProvinsi')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                prov = state.id;
                return state.name;
            }
        });
        $('input[name="PROV"]').on("change", function (e) {
            $('input[name="KAB_KOT"]').prop("disabled", false);

            $('input[name="KAB_KOT"]').select2("val", "");
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });

        $('input[name="KAB_KOT"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getKabupatenKota')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term,
                        prov: prov
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getKabupatenKota')?>/"+prov+'/'+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                kab = state.id;
                return state.name;
            }
        });
        $('input[name="KAB_KOT"]').on("change", function (e) {
            $('input[name="KEC"]').prop("disabled", false);

            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });

        // $('input[name="KEC"]').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?php echo base_url('index.php/share/reff/getKecamatan')?>",
        //         dataType: 'json',
        //         quietMillis: 250,
        //         data: function (term, page) {
        //             return {
        //                 q: term,
        //                 prov: prov,
        //                 kab: kab
        //             };
        //         },
        //         results: function (data, page) {
        //             return { results: data.item };
        //         },
        //         cache: true
        //     },
        //     initSelection: function(element, callback) {
        //         var id = $(element).val();
        //         if (id !== "") {
        //             $.ajax("<?php echo base_url('index.php/share/reff/getKecamatan')?>/"+prov+'/'+kab+'/'+id, {
        //                 dataType: "json"
        //             }).done(function(data) { callback(data[0]); });
        //         }
        //     },
        //     formatResult: function (state) {
        //         return state.name;
        //     },
        //     formatSelection:  function (state) {
        //         kec = state.id;
        //         return state.name;
        //     }
        // });
        // $('input[name="KEC"]').on("change", function (e) {
        //     $('input[name="KEL"]').prop("disabled", false);
            
        //     $('input[name="KEL"]').select2("val", "");
        // });


        // $('input[name="KEL"]').select2({
        //     minimumInputLength: 0,
        //     ajax: {
        //         url: "<?php echo base_url('index.php/share/reff/getKelurahan')?>",
        //         dataType: 'json',
        //         quietMillis: 250,
        //         data: function (term, page) {
        //             return {
        //                 q: term,
        //                 prov: prov,
        //                 kab: kab,
        //                 kec: kec
        //             };
        //         },
        //         results: function (data, page) {
        //             return { results: data.item };
        //         },
        //         cache: true
        //     },
        //     initSelection: function(element, callback) {
        //         var id = $(element).val();
        //         if (id !== "") {
        //             $.ajax("<?php echo base_url('index.php/share/reff/getKelurahan')?>/"+prov+'/'+kab+'/'+kec+'/'+id, {
        //                 dataType: "json"
        //             }).done(function(data) { callback(data[0]); });
        //         }
        //     },
        //     formatResult: function (state) {
        //         return state.name;
        //     },
        //     formatSelection:  function (state) {
        //         return state.name;
        //     }
        // });
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menonaktifkan PN/WL dibawah ini ?
    <!-- <form method="post" id="ajaxFormDelete" action="index.php/ereg/all_pn/savepn"> -->
    <form method="post" id="ajaxFormDelete" action="index.php/ereg/all_pn/do_nonact_pn/<?php echo $item->ID_PN.'/'.$idjpn; ?>">
    <table class="table-detail">
			<tr><td>NIK</td><td>:</td><td><?php echo $item->NIK;?></td></tr>
			<tr><td>Nama</td><td>:</td><td><?php echo @$item->GELAR_DEPAN.' '.$item->NAMA.' '.@$item->GELAR_BELAKANG; ?></td></tr>
			<tr><td>Jenis Kelamin</td><td>:</td><td>
				<?php if($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
				<?php if($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
			</td></tr>
			<tr><td>Tempat , Tanggal Lahir</td><td>:</td><td>
				<?php echo $item->TEMPAT_LAHIR; ?> , <?php echo @date('d/m/Y',strtotime(@$item->TGL_LAHIR));?>
				</td></tr>
			<!-- <tr><td>Agama</td><td>:</td><td>
				<?php if($item->ID_AGAMA==1) echo "ISLAM";?>
				<?php if($item->ID_AGAMA==2) echo "KRISTEN";?>
				<?php if($item->ID_AGAMA==3) echo "KALOTIK";?>
				<?php if($item->ID_AGAMA==4) echo "HINDU";?>
				<?php if($item->ID_AGAMA==5) echo "BUDHA";?>
			<td></tr> -->
<!-- 			<tr><td>Status Nikah</td><td>:</td><td>
				<?php if($item->ID_STATUS_NIKAH==1) echo "KAWIN";?>
				<?php if($item->ID_STATUS_NIKAH==2) echo "TIDAK KAWIN";?>
				<?php if($item->ID_STATUS_NIKAH==3) echo "JANDA";?>
				<?php if($item->ID_STATUS_NIKAH==4) echo "DUDA";?>
			</td></tr> -->
	<!-- 		<tr><td>Pendidikan Terakhir</td><td>:</td><td>
				<?php if($item->ID_PENDIDIKAN==1) echo "SD";?>
				<?php if($item->ID_PENDIDIKAN==2) echo "SLTP";?>
				<?php if($item->ID_PENDIDIKAN==3) echo "STLA";?>
				<?php if($item->ID_PENDIDIKAN==4) echo "D3";?>
				<?php if($item->ID_PENDIDIKAN==5) echo "S1/D4";?>
				<?php if($item->ID_PENDIDIKAN==6) echo "S2";?>
				<?php if($item->ID_PENDIDIKAN==7) echo "S4";?>
			</td></tr> -->
			<!-- <tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr> -->
			<!-- <tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr> -->
			<tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
			<tr><td>NO HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
			<!-- <tr><td>FOTO</td><td>:</td><td> -->
				<?php //if($item->FOTO != "")
						// echo "<img src='./uploads/data_pribadi/".$item->NIK."/".$item->FOTO."' width='64'>";
				?>
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary">Non Aktif</button>
             <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
    });
</script>
<?php
}
?>

<?php
if($form=='update'){
?>
<div id="wrapperFormDelete">
    Benarkah Akan Mengaktifkan Pejabat dibawah ini ?
    <!-- <form method="post" id="ajaxFormDelete" action="index.php/ereg/all_pn/savepn"> -->
    <form method="post" id="ajaxFormUpdate" action="index.php/ereg/all_pn/do_update_pn/<?php echo $item->ID_PN.'/'.$idjpn; ?>">
    <table class="table-detail">
            <tr><td>NIK</td><td>:</td><td><?php echo $item->NIK;?></td></tr>
            <tr><td>Nama</td><td>:</td><td><?php echo @$item->GELAR_DEPAN.' '.$item->NAMA.' '.@$item->GELAR_BELAKANG; ?></td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td>
                <?php if($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
                <?php if($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
            </td></tr>
            <tr><td>Tempat , Tanggal Lahir</td><td>:</td><td>
                <?php echo $item->TEMPAT_LAHIR; ?> , <?php echo @date('d/m/Y',strtotime(@$item->TGL_LAHIR));?>
                </td></tr>
            <!-- <tr><td>Agama</td><td>:</td><td>
                <?php if($item->ID_AGAMA==1) echo "ISLAM";?>
                <?php if($item->ID_AGAMA==2) echo "KRISTEN";?>
                <?php if($item->ID_AGAMA==3) echo "KALOTIK";?>
                <?php if($item->ID_AGAMA==4) echo "HINDU";?>
                <?php if($item->ID_AGAMA==5) echo "BUDHA";?>
            <td></tr> -->
<!--            <tr><td>Status Nikah</td><td>:</td><td>
                <?php if($item->ID_STATUS_NIKAH==1) echo "KAWIN";?>
                <?php if($item->ID_STATUS_NIKAH==2) echo "TIDAK KAWIN";?>
                <?php if($item->ID_STATUS_NIKAH==3) echo "JANDA";?>
                <?php if($item->ID_STATUS_NIKAH==4) echo "DUDA";?>
            </td></tr> -->
    <!--        <tr><td>Pendidikan Terakhir</td><td>:</td><td>
                <?php if($item->ID_PENDIDIKAN==1) echo "SD";?>
                <?php if($item->ID_PENDIDIKAN==2) echo "SLTP";?>
                <?php if($item->ID_PENDIDIKAN==3) echo "STLA";?>
                <?php if($item->ID_PENDIDIKAN==4) echo "D3";?>
                <?php if($item->ID_PENDIDIKAN==5) echo "S1/D4";?>
                <?php if($item->ID_PENDIDIKAN==6) echo "S2";?>
                <?php if($item->ID_PENDIDIKAN==7) echo "S4";?>
            </td></tr> -->
            <!-- <tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr> -->
            <!-- <tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr> -->
            <tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
            <tr><td>NO HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
            <!-- <tr><td>FOTO</td><td>:</td><td> -->
                <?php //if($item->FOTO != "")
                        // echo "<img src='./uploads/data_pribadi/".$item->NIK."/".$item->FOTO."' width='64'>";
                ?>
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Aktif</button>
             <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormUpdate"), 'update', location.href.split('#')[1]);
    });
</script>
<?php
}
?>


<?php
if($form=='detail_old'){
?>
<div id="wrapperFormDetail">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-3">
            <?php if(file_exists("uploads/data_pribadi/".$item->NIK."/".$item->FOTO) && !empty($item->FOTO)) { ?>
                <img src='<?php echo base_url("uploads/data_pribadi/".$item->NIK."/".$item->FOTO);?>' class="img-rounded col-md-12"/>
            <?php }else{ ?>
                <img src="<?php echo base_url();?>images/no_available_image.gif" class="img-rounded col-md-12"/>
            <?php } ?>
        </div>
        <div class="col-md-8">
            <h3>Detail</h3>
            <table class="table-detail">
                <tr>
                    <td>NIK</td><td>:</td><td><?php echo $item->NIK;?></td>
                    <!-- <td><b>Negara</b></td><td>:</td><td>
                        <?php if($item->NEGARA==2) echo "Indonesia";?>
                        <?php if($item->NEGARA!=2){
                            $sql = "SELECT * FROM M_NEGARA WHERE ID = '$item->LOKASI_NEGARA'";
                            $negara = $this->db->query($sql)->row();
                            echo $negara->NAMA_NEGARA;
                        } ?>
                    </td> -->
                </tr>
                <tr>
                    <td>Nama</td><td>:</td><td><?php echo $item->NAMA;?></td>
                    <?php if($item->NEGARA==2) { ?>
                        <td><b>Provinsi</b></td>
                        <td>:</td>
                        <td>
                            <?php
                                $prov = explode(" ", $item->PROVINSI);
                                $provi = "";
                                foreach ($prov as $key) {
                                    if($key == 'DKI' || $key == "DI"){
                                        $provi .= ucfirst($key)." ";
                                    } else {
                                        $provi .= ucfirst(strtolower($key))." ";
                                    }
                                }
                                echo $provi;
                            ?>
                        </td>
                    <?php }else{ ?>
                            <td><b>Alamat Tinggal</b></td>
                            <td>:</td>
                            <td><?php echo $item->ALAMAT_TINGGAL ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td><td>:</td>
                    <td>
                        <?php if($item->JNS_KEL == 1) echo "Laki - laki "; ?>
                        <?php if($item->JNS_KEL == 2) echo "Perempuan"; ?>
                    </td>
                    <?php if($item->NEGARA == 2) { ?>
                        <td><b>Kabupaten / Kota</b></td>
                        <td>:</td>
                        <td>
                            <?php
                                $kabkot = explode(" ", $item->KABKOT);
                                foreach ($kabkot as $key) {
                                    echo ucfirst(strtolower($key))." ";
                                }
                            ?>
                        </td>
                    <?php }else{ ?>
                        <td><b>Email</b></td><td>:</td><td><?php echo $item->EMAIL ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Tempat / Tanggal Lahir</td><td>:</td><td>
                        <?php echo $item->TEMPAT_LAHIR; ?> / <?php echo tgl_format($item->TGL_LAHIR);?>
                    </td>
                    <?php if($item->NEGARA == 2) { ?>
                    <td><b>Kecamatan</b></td>
                    <td>:</td>
                    <td>
                        <?php
                            echo $item->KEC;
                            // $kec = explode(" ", $item->KEC);
                            // foreach ($kec as $key) {
                            //     echo ucfirst(strtolower($key))." ";
                            // }
                        ?>
                    </td>
                    <?php }else{ ?>
                        <td><b>NO HP</b></td><td>:</td><td><?php echo $item->NO_HP; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <!-- <td>Agama</td><td>:</td>
                    <td>
                        <?php echo $agama[0]->AGAMA; ?>
                    </td> -->
                    <?php if($item->NEGARA==2) { ?>
                        <td><b>Kelurahan</b></td>
                        <td>:</td>
                        <td>
                            <?php
                                echo $item->KEL;
                                // $kel = explode(" ", $item->KEL);
                                // foreach ($kel as $key) {
                                //     echo ucfirst(strtolower($key))." ";
                                // }
                            ?>
                        </td>
                    <?php } ?>

                </tr>
                <tr>
                    <td>Status Nikah</td><td>:</td><td>
                        <?php echo ucfirst(strtolower($sttnikah[0]->STATUS_NIKAH)) ?>
                    </td>
                    <?php if($item->NEGARA == 2) { ?>
                        <td><b>Alamat Tinggal</b></td>
                        <td>:</td>
                        <td><?php echo $item->ALAMAT_TINGGAL ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Pendidikan Terakhir</td><td>:</td>
                    <td>
                        <?php echo $penhir[0]->PENDIDIKAN ?>
                    </td>
                    <?php if($item->NEGARA == 2) { ?>
                        <td><b>Email</b></td><td>:</td><td><?php echo $item->EMAIL ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>NPWP</td><td>:</td>
                    <td><?php echo $item->NPWP ?></td>
                    <?php if($item->NEGARA == 2) { ?>
                        <td><b>NO HP</b></td><td>:</td><td><?php echo $item->NO_HP; ?></td>
                    <?php } ?>
                </tr>
            </table>
        </div>
    </div>
    <br />
    <br />
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <table class="table table-bordered table-hover table-striped" >
                <thead class="table-header">
                    <tr>
                        <th width="30">No.</th>
                        <th>Jabatan/Eselon</th>
                        <th>Lembaga</th>
                        <th>Unit Kerja</th>
                        <th>TMT/SD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach ($detJabatan as $key): ?>
                        <tr>
                            <td><?php echo @$i++.'.'; ?></td>
                            <td><?php echo @$key->IS_CALON.' '.@$key->NAMA_JABATAN.' / '.@$key->ESELON; ?></td>
                            <td><?php echo @$key->INST_NAMA; ?></td>
                            <td><?php echo @$key->UK_NAMA; ?></td>
                            <td align="center">
                                <?php if(@$key->SD == '' || date('Y',strtotime(@$key->SD)) == '1970') { ?>
                                    <?php echo @date('d/m/Y',strtotime(@$key->TMT)); ?>
                                <?php } else { ?>
                                    <?php echo @date('d/m/Y',strtotime(@$key->TMT)); ?> - <?php echo date('d/m/Y',strtotime(@$key->SD)); ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-1"></div>

    </div>
    <div class="pull-right">
         <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</div>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-3">
            <?php if(file_exists("uploads/data_pribadi/".$item->NIK."/".$item->FOTO) && !empty($item->FOTO)) { ?>
                <img src='<?php echo base_url("uploads/data_pribadi/".$item->NIK."/".$item->FOTO);?>' class="img-rounded col-md-12"/>
            <?php }else{ ?>
                <img src="<?php echo base_url();?>images/no_available_image.gif" class="img-rounded col-md-12"/>
            <?php } ?>
        </div>
        <div class="col-md-8">
            <h3>Detail</h3>
            <div class="col-md-12">
            
            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">NIK </label>
                <div class="col-sm-9"> :
                    <?php echo isset($item->NIK) ? $item->NIK : ""; ?>
                </div>
            </div>
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama </label>
                <div class="col-sm-9"> :
                    <?php echo isset($item->NAMA) ? $item->NAMA : ""; ?>
                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Tempat / Tanggal Lahir </label>
                <div class="col-sm-9"> :
                    <?php echo isset($item->TEMPAT_LAHIR) ? $item->TEMPAT_LAHIR : "-"; ?>
                    <?php echo isset($item->TGL_LAHIR) ? tgl_format($item->TGL_LAHIR) : "-"; ?>
                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Jenis Kelamin </label>
                <div class="col-sm-9"> :
                    <?php 
                    if($item->JNS_KEL != NULL || $item->JNS_KEL != ''){
                        if($item->JNS_KEL == 1) {
                            $jk = "Laki - laki";
                        }elseif($item->JNS_KEL == 2){
                            $jk = "Perempuan";
                        }else{
                            $jk = "";
                        }                        
                    }
                    ?>

                    <?php echo isset($item->JNS_KEL) ? $jk : ""; ?>

                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">NIP </label>
                <div class="col-sm-9"> :
                    <?php echo isset($item->NIP_NRP) ? $item->NIP_NRP : ""; ?>
                </div>
            </div> 
            </div>     
            
           <!--  </div>

            <div class="col-md-6"> -->
            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Instansi </label>
                <div class="col-sm-9"> :
                    <?php //echo isset($lembaga->INST_NAMA) ? $lembaga->INST_NAMA : "-"; ?>
                    <?php echo isset($dt_detail->LEMBAGA) ? strtoupper($dt_detail->NAMA_LEMBAGA) : ""; ?>
                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Unit Kerja </label>
                <div class="col-sm-9"> :
                    <?php echo isset($dt_detail->UNIT_KERJA) ? $dt_detail->UNIT_KERJA : ""; ?>
                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Sub Unit Kerja</label>
                <div class="col-sm-9"> :
                    <?php echo isset($dt_detail->SUB_UNIT_KERJA) ? $dt_detail->SUB_UNIT_KERJA : ""; ?>
                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Jabatan </label>
                <div class="col-sm-9"> :
                    <?php echo isset($dt_detail->DESKRIPSI_JABATAN) ? $dt_detail->DESKRIPSI_JABATAN : ""; ?>
                    <?php //echo isset($dt_detail->ID_JABATAN) ? $dt_detail->ID_JABATAN : ""; ?>
                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Email </label>
                <div class="col-sm-9"> :
                    <?php echo isset($item->EMAIL) ? $item->EMAIL : ""; ?>
                </div>
            </div> 
            </div>

            <div class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label">Nomor Handphone </label>
                <div class="col-sm-9"> :
                    <?php echo isset($item->NO_HP) ? $item->NO_HP : ""; ?>
                </div>
            </div> 
            </div>


            </div>

        </div>
    </div>
    <br />
    <br />

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <!-- <table class="table table-bordered table-hover table-striped" > -->
            <table id="dt_completeNEW" class="table table-striped" >
                <!-- <thead class="table-header"> -->
                <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th>Jabatan/Eselon</th>
                        <th>Lembaga</th>
                        <th>Unit Kerja</th>
                        <th>Sub Unit Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                        $i=0;
                        foreach($riwayat_jabatan as $val){
                        $i++;
                            echo '<tr>
                            <td align="center">'.$i.'</td>
                            <td>'.$val->DESKRIPSI_JABATAN.'</td>
                            <td>'.$val->NAMA_LEMBAGA.'</td>
                            <td>'.$val->UNIT_KERJA.'</td>
                            <td>'.$val->SUB_UNIT_KERJA.'</td>
                            </tr>';
                        }
                    ?>

                    <?php //$i=1;foreach ($detJabatan as $key): ?>
                        <!-- <tr>
                            <td><?php echo @$i++.'.'; ?></td>
                            <td><?php echo @$key->IS_CALON.' '.@$key->NAMA_JABATAN.' / '.@$key->ESELON; ?></td>
                            <td><?php echo @$key->INST_NAMA; ?></td>
                            <td><?php echo @$key->UK_NAMA; ?></td>
                            <td align="center">
                                <?php if(@$key->SD == '' || date('Y',strtotime(@$key->SD)) == '1970') { ?>
                                    <?php echo @date('d/m/Y',strtotime(@$key->TMT)); ?>
                                <?php } else { ?>
                                    <?php echo @date('d/m/Y',strtotime(@$key->TMT)); ?> - <?php echo date('d/m/Y',strtotime(@$key->SD)); ?>
                                <?php } ?>
                            </td>
                            <td><?php if(@$key->FILE_SK != ''){ ?><a href="<?php echo base_url('uploads/data_jabatan/'.$key->NIK.'/'.$key->FILE_SK); ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/'.$key->NIK.'/'.@$key->FILE_SK); ?></a><?php } ?></td>
                            <td></td>
                            <td></td>
                        </tr> -->
                    <?php //endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-1"></div>

    </div>
    <div class="pull-right">
         <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</div>
<?php
}
?>

<?php
if($form=='reset_password'){
    ?>
<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/do_reset_password">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo substr(md5($item->ID_USER),5,8); ?>">
    <p>Apakah anda yakin akan mereset password?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormResetPassword"), 'reset_password', location.href.split('#')[1]);
    });
</script>
<?php
}
?>
<?php
if($form=='mutasi'){
    // display($jabatan);
    $idRole = $this->session->userdata('ID_ROLE');
    if($idRole == 3 || $idRole == 4){
        $dis = 'disabled';
    }else{
        $dis = '';
    }
    ?>
    <script type="text/javascript">
    <?php
    $arr_status = array();
    foreach ( $status_akhir as $status ) {
        $arr_status[$status->ID_STATUS_AKHIR_JABAT] = $status;
    }
    ?>
        var status_akhir = '<?php echo json_encode(array('result' => $arr_status)) ?>';
        var data_status_akhir = JSON.parse(status_akhir);
    </script>
    <div id="wrapperFormMutasi">
            <form class="form-horizontal" method="post" id="ajaxFormMutasi" action="index.php/ereg/all_pn/save_perubahan_jabatan/<?php echo $item_pn->ID_PN; ?>">
                <input type="hidden" name="pn" id="pn" value="<?php echo $item_pn->ID_PN; ?>">
                <input type="hidden" name="ID_JABATAN_ASAL" id="ID_JABATAN_ASAL" value="<?php echo $jabatan->ID; ?>">
                <input type="hidden" name="ID_PN_JABATAN" id="ID_PN_JABATAN" value="<?php echo $jabatan->ID; ?>">

                <!-- <div class="form-group">
                    <label class="col-sm-4 control-label">Jenis Mutasi :</label>
                    <div class="col-sm-8" style="margin-top: 5px;">
                        <?php
                        foreach ( $status_akhir as $status ) {
                            $selected = $status->ID_STATUS_AKHIR_JABAT==$jenis?'selected':'';
                            if($jenis==$status->ID_STATUS_AKHIR_JABAT){
                            ?>
                                <input type="hidden" name="JENIS_MUTASI" value="<?php echo $status->ID_STATUS_AKHIR_JABAT; ?>">
                                <strong><?php echo $status->STATUS; ?></strong>
                            <?php
                            }
                        }
                        ?>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama PN<span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <div class="control-label" style="text-align:left !important;"><?php echo $item_pn->NAMA?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Jabatan <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <div class="control-label" id="nama_jabatan" style="text-align:left !important;"><?php if($jabatan->IS_CALON == 1){echo '<span class="label label-warning">Calon</span>';}?> <?php echo$jabatan->NAMA_JABATAN?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Instansi Asal <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <select required id="INST_ASAL" name="INST_ASAL" class="select" style="width: 100%;display:none;">
                            <?php
                            foreach ($instansi_asal as $item) {
                                $selected = $item->INST_SATKERKD==$jabatan->LEMBAGA?'selected':'';
                                if($selected){
                                    $textINST = $item->INST_NAMA;
                                }
                                ?>
                                <option value="<?php echo $item->INST_SATKERKD ?>" <?php echo $selected;?>><?php echo $item->INST_NAMA;?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <?php echo $textINST;?>
                    </div>
                </div>
                <hr>
                <div class="form-group div_pindah">
                    <label class="col-sm-4 control-label">Instansi Tujuan <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <input type="text" <?php echo $dis; ?> id="INST_TUJUAN" name="INST_TUJUAN" value="<?php echo $jabatan->LEMBAGA ?>" class="select" style="width: 100%;" />
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Unit Kerja<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Sub Unit Kerja<font color='red'></font> :</label>
                    <div class="col-sm-8">
                        <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' placeholder="Sub Unit Kerja">
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Jabatan<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                    </div>
                </div>
                <!-- <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Deskripsi jabatan :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="DESKRIPSI_JABATAN" name="deskripsi" value="" placeholder="Deskripsi Jabatan">
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Eselon<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                            <option value='1'>I</option>
                            <option value='2'>II</option>
                            <option value='3'>III</option>
                            <option value='4'>IV</option>
                            <option value='5'>Non-Eselon</option>
                        </select>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">SK<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <div class='col-sm-12'>
                            <input type="file" id="FILE_SK" name="FILE_SK" class="FILE_SK">
                        </div>
                    </div>
                    <span class='help-block'>Type File: xls, xlsx, doc, docx, pdf, png, jpg, jpeg .  Max File: 500KB</span>
                </div> -->
                <!-- <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">TMT<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <div class='col-sm-12'>
                            <div class="col-xs-3 col-sm-4 col-md-3 col-lg-7">
                                <input type="text" class="form-control datepicker" id="TMT" name="TMT" value="" required>
                            </div> -->
                            <!-- <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                s/d
                            </div>
                            <div class="col-xs-3 col-sm-4 col-md-3 col-lg-3">
                                <input type="text" class="form-control datepicker" name="SD" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($jenis == '3'){ ?>
                    <div class="form-group div_sd">
                        <label class="col-sm-4 control-label">TMT <span class="red-label">*</span>:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" id="SD_MENJABAT" name="SD_MENJABAT" value="" required>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="form-group div_sd">
                        <label class="col-sm-4 control-label">TMT <span class="red-label">*</span>:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" id="SD_MENJABAT" name="SD_MENJABAT" value="" required>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group div_keterangan_akhir_jabat">
                    <label class="col-sm-4 control-label">Keterangan <?= ($jenis=='3' ? "" : "<span class='red-label'>*</span>"); ?>:</label>
                    <div class="col-sm-8">
                        <textarea <?= ($jenis=='3' ? 'required' : ''); ?> class="form-control" id="KETERANGAN_AKHIR_JABAT" name="KETERANGAN_AKHIR_JABAT"></textarea>
                    </div>
                </div> -->
                <div class="pull-right">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
                     <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
                </div>
        </form>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#INST_ASAL').select2();
//        $('#INST_TUJUAN').select2();
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
        ng.formProcess($("#ajaxFormMutasi"), 'mutasi', location.href.split('#')[1]);

        var elements = document.getElementsByTagName("INPUT");
        for (var i = 0; i < elements.length; i++) {
            elements[i].oninvalid = function(e) {
                e.target.setCustomValidity("");
                if (!e.target.validity.valid) {
                    id = e.target.getAttribute("id");
                    e.target.setCustomValidity(id+" required");
                }
            };
            elements[i].oninput = function(e) {
                e.target.setCustomValidity("");
            };
        }


        $('.FILE_SK').change(function(){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var arr     = ['xls','xlsx','doc','docx','pdf','jpg','png','jpeg'];
            var maxsize = 500000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_SK').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_SK').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });

        $('#INST_TUJUAN').select2({
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
        }).on("change", function(e) {
            var lembid = $('#INST_TUJUAN').val();

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getJabatan')?>"+'/'+lembid,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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
        });

        var lembid = $('#INST_TUJUAN').val();

         $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getJabatan')?>"+'/'+lembid,
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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

        function selUnitKerja(LEMBAGA){
             $('input[name="UNIT_KERJA"]').select2({
                 minimumInputLength: 0,
                 ajax: {
                     url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA,
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
                         $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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
        }

        $('#UNIT_KERJA').change(function(event) {
            UNIT_KERJA = $(this).val();

            $('input[name="SUB_UNIT_KERJA"]').select2({
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
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA, {
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

            $('input[name="JABATAN"]').select2({
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
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA, {
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

        $('#SUB_UNIT_KERJA').change(function(event) {
            SUB_UNIT_KERJA = $(this).val();

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA,
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
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA, {
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






        f_mutasi(<?php echo $jenis; ?>);

        function f_mutasi(jenis){
            if ( data_status_akhir.result[jenis].IS_PINDAH == 1 ) {
                // mutasi
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_TUJUAN').change();
                // LEMBAGA = $('#INST_ASAL').val();
                // selUnitKerja(LEMBAGA);

            } else if (data_status_akhir.result[jenis].IS_PINDAH == 0 && data_status_akhir.result[jenis].IS_AKTIF == 1){
                // promosi
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_TUJUAN').change();
                // LEMBAGA = $('#INST_ASAL').val();
                // selUnitKerja(LEMBAGA);
            } else if (
                data_status_akhir.result[jenis].IS_AKHIR == 1 ||
                (
                    data_status_akhir.result[jenis].IS_AKHIR == 0 &&
                    data_status_akhir.result[jenis].IS_PINDAH == 0 &&
                    data_status_akhir.result[jenis].IS_AKTIF == 0 &&
                    data_status_akhir.result[jenis].IS_MENINGGAL == 0
                )
            ) {
                // meninggal / pensiun
                objRequired = [
                    ['#INST_TUJUAN', 0],
                    ['#UNIT_KERJA', 0],
                    ['#JABATAN', 0],
                    ['#ESELON', 0],
                    ['#FILE_SK', 0],
                    ['#TMT', 0],
                    ['#SD_MENJABAT', 1],
                    ['#KETERANGAN_AKHIR_JABAT', 1]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').hide('fast');
                $('.div_jabatan').hide('fast');
                $('.div_sd').show('fast');
                $('.div_keterangan_akhir_jabat').show('fast');
                // $('#INST_ASAL').change();
                // $('#INST_TUJUAN').change();
            } else {
                // non wl
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_ASAL').change();
                // $('#INST_TUJUAN').change();
            }

            $('#INST_ASAL').change(function(event) {
                // if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH != 1 ) {
                LEMBAGA = $(this).val();
                selUnitKerja(LEMBAGA);
                // }
            });

            $('#INST_TUJUAN').change(function(event) {
                // if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH == 1 ) {
                LEMBAGA = $(this).val();
                selUnitKerja(LEMBAGA);
                // }
            });

            LEMBAGA = $('#INST_ASAL').val();
            selUnitKerja(LEMBAGA);
        }
    });
</script>
<?php
}
?>


<?php
if($form=='mutasi_calon'){
    // display($jabatan);
    ?>
    <script type="text/javascript">
    <?php
    $arr_status = array();
    foreach ( $status_akhir as $status ) {
        $arr_status[$status->ID_STATUS_AKHIR_JABAT] = $status;
    }
    ?>
        var status_akhir = '<?php echo json_encode(array('result' => $arr_status)) ?>';
        var data_status_akhir = JSON.parse(status_akhir);
    </script>
    <div id="wrapperFormMutasi">
            <form class="form-horizontal" method="post" id="ajaxFormMutasi" action="index.php/ereg/all_pn/save_perubahan_calon/<?php echo $item_pn->ID_PN; ?>">
                <input type="hidden" name="pn" id="pn" value="<?php echo $item_pn->ID_PN; ?>">
                <input type="hidden" name="ID_JABATAN_ASAL" id="ID_JABATAN_ASAL" value="<?php echo $jabatan->ID; ?>">
                <input type="hidden" name="ID_PN_JABATAN" id="ID_PN_JABATAN" value="<?php echo $jabatan->ID; ?>">

                <!-- <div class="form-group">
                    <label class="col-sm-4 control-label">Jenis Mutasi :</label>
                    <div class="col-sm-8" style="margin-top: 5px;">
                        <?php
                        foreach ( $status_akhir as $status ) {
                            $selected = $status->ID_STATUS_AKHIR_JABAT==$jenis?'selected':'';
                            if($jenis==$status->ID_STATUS_AKHIR_JABAT){
                            ?>
                                <input type="hidden" name="JENIS_MUTASI" value="<?php echo $status->ID_STATUS_AKHIR_JABAT; ?>">
                                <strong><?php echo $status->STATUS; ?></strong>
                            <?php
                            }
                        }
                        ?>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama PN<span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <div class="control-label" style="text-align:left !important;"><?php echo $item_pn->NAMA?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Jabatan <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <div class="control-label" id="nama_jabatan" style="text-align:left !important;"><?php if($jabatan->IS_CALON == 1){echo '<span class="label label-warning">Calon</span>';}?> <?php echo$jabatan->NAMA_JABATAN?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Instansi Asal <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <select required id="INST_ASAL" name="INST_ASAL" class="select" style="width: 100%;display:none;">
                            <?php
                            foreach ($instansi_asal as $item) {
                                $selected = $item->INST_SATKERKD==$jabatan->LEMBAGA?'selected':'';
                                if($selected){
                                    $textINST = $item->INST_NAMA;
                                }
                                ?>
                                <option value="<?php echo $item->INST_SATKERKD ?>" <?php echo $selected;?>><?php echo $item->INST_NAMA;?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <?php echo $textINST; $id_lembaga = $jabatan->LEMBAGA; ?>
                    </div>
                </div>
                <hr>

                <div id="wrapperFormJabatan">
                <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>UNIT KERJA <span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('unit_kerja'); ?>
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA2' style="border:none;" id='UNIT_KERJA2' value='' placeholder="Unit Kerja" required>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>SUB UNIT KERJA <span class="red-label"></span></label> 
                    <?= FormHelpPopOver('sub_unit_kerja'); ?>
                    <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA3' style="border:none;" id='SUB_UNIT_KERJA3' value='' placeholder="Sub Unit Kerja">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>JABATAN<span class="red-label">*</span></label> 
                    <?= FormHelpPopOver('jabatan'); ?>
                    <input type="text" class="form-control form-select2" name="JABATAN3" style="border:none;" id='JABATAN3' value="" placeholder="Jabatan" required>
                </div>
                <div class="col-sm-1">
                </div>
            </div>    

           <!--      <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Unit Kerja<font color='red'></font> :</label>
                    <div class="col-sm-8">
                        <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Sub Unit Kerja<font color='red'></font> :</label>
                    <div class="col-sm-8">
                        <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' placeholder="Sub Unit Kerja" required>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Jabatan<font color='red'></font> :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                    </div>
                </div>
                </div> -->

              <div class="pull-right">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
                      <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
                </div>
        </form>
    </div>
<script type="text/javascript">

        $(document).ready(function() {
        var idWrapFormJabatan = $('#wrapperFormJabatan');

        var valUK = $('#UNIT_KERJA2').val();

        $('input[name="UNIT_KERJA2"]', idWrapFormJabatan).select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+ <?php echo $id_lembaga; ?>,
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+<?php echo $id_lembaga; ?>+'/'+id, {
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

        $('#UNIT_KERJA2', idWrapFormJabatan).change(function(event) {
            UNIT_KERJA = $(this).val();

               $('input[name="JABATAN3"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan2') ?>/" + UNIT_KERJA,
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
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan2') ?>/24/" + UNIT_KERJA + "/" + id, {
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

        
        var valUK = $('#UNIT_KERJA2').val();

        $('input[name="SUB_UNIT_KERJA2"]', idWrapFormJabatan).select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK,
                // url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+ <?php echo $id_lembaga; ?>,
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
                    $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK + "/" + id, {
                    // $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+<?php echo $id_lembaga; ?>+'/'+id, {
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



        // var uk_ = null;

        // $('#UNIT_KERJA2', idWrapFormJabatan).change(function(event) {
        //     UNIT_KERJA = $(this).val();
        //     var valUK = $('#UNIT_KERJA2').val();

        //     uk_ = UNIT_KERJA;

            $('input[name="JABATAN2"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan2') ?>/24/" + valUK,
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
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan2') ?>/24/" + UNIT_KERJA + "/" + id, {
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

        //     $('input[name="SUB_UNIT_KERJA2"]', idWrapFormJabatan).select2({
        //         minimumInputLength: 0,
        //         ajax: {
        //             url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
        //             dataType: 'json',
        //             quietMillis: 250,
        //             data: function(term, page) {
        //                 return {
        //                     q: term
        //                 };
        //             },
        //             results: function(data, page) {
        //                 return {results: data.item};
        //             },
        //             cache: true
        //         },
        //         initSelection: function(element, callback) {
        //             var id = $(element).val();
        //             if (id !== "") {
        //                 $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
        //                     dataType: "json"
        //                 }).done(function(data) {
        //                     callback(data[0]);
        //                 });
        //             }
        //         },
        //         formatResult: function(state) {
        //             return state.name;
        //         },
        //         formatSelection: function(state) {
        //             return state.name;
        //         }
        //     });

        // });



        $('#INST_ASAL').select2();
//        $('#INST_TUJUAN').select2();
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
        ng.formProcess($("#ajaxFormMutasi"), 'mutasi', location.href.split('#')[1]);

        var elements = document.getElementsByTagName("INPUT");
        for (var i = 0; i < elements.length; i++) {
            elements[i].oninvalid = function(e) {
                e.target.setCustomValidity("");
                if (!e.target.validity.valid) {
                    id = e.target.getAttribute("id");
                    e.target.setCustomValidity(id+" required");
                }
            };
            elements[i].oninput = function(e) {
                e.target.setCustomValidity("");
            };
        }

        $('#INST_TUJUAN').select2({
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
        }).on("change", function(e) {
            var lembid = $('#INST_TUJUAN').val();

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getJabatan')?>"+'/'+lembid,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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
        });

        var lembid = $('#INST_TUJUAN').val();

         $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getJabatan')?>"+'/'+lembid,
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getJabatan')?>/"+lembid+'/'+id, {
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

        function selUnitKerja(LEMBAGA){
             $('input[name="UNIT_KERJA"]').select2({
                 minimumInputLength: 0,
                 ajax: {
                     url: "<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+ <?php echo $id_lembaga; ?>,
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
                         $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja')?>/"+<?php echo $id_lembaga; ?>+'/'+id, {
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
        }

        $('#UNIT_KERJA2').change(function(event) {
            UNIT_KERJA = $(this).val();

            $('input[name="SUB_UNIT_KERJA3"]').select2({
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
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA, {
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

        $('#SUB_UNIT_KERJA3').change(function(event) {
            SUB_UNIT_KERJA = $(this).val();

            $('input[name="JABATAN3"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA,
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
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA, {
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

        f_mutasi(<?php echo $jenis; ?>);

        function f_mutasi(jenis){
            if ( data_status_akhir.result[jenis].IS_PINDAH == 1 ) {
                // mutasi
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_TUJUAN').change();
                // LEMBAGA = $('#INST_ASAL').val();
                // selUnitKerja(LEMBAGA);

            } else if (data_status_akhir.result[jenis].IS_PINDAH == 0 && data_status_akhir.result[jenis].IS_AKTIF == 1){
                // promosi
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_TUJUAN').change();
                // LEMBAGA = $('#INST_ASAL').val();
                // selUnitKerja(LEMBAGA);
            } else if (
                data_status_akhir.result[jenis].IS_AKHIR == 1 ||
                (
                    data_status_akhir.result[jenis].IS_AKHIR == 0 &&
                    data_status_akhir.result[jenis].IS_PINDAH == 0 &&
                    data_status_akhir.result[jenis].IS_AKTIF == 0 &&
                    data_status_akhir.result[jenis].IS_MENINGGAL == 0
                )
            ) {
                // meninggal / pensiun
                objRequired = [
                    ['#INST_TUJUAN', 0],
                    ['#UNIT_KERJA', 0],
                    ['#JABATAN', 0],
                    ['#ESELON', 0],
                    ['#FILE_SK', 0],
                    ['#TMT', 0],
                    ['#SD_MENJABAT', 1],
                    ['#KETERANGAN_AKHIR_JABAT', 1]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').hide('fast');
                $('.div_jabatan').hide('fast');
                $('.div_sd').show('fast');
                $('.div_keterangan_akhir_jabat').show('fast');
                // $('#INST_ASAL').change();
                // $('#INST_TUJUAN').change();
            } else {
                // non wl
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);

                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_ASAL').change();
                // $('#INST_TUJUAN').change();
            }

            $('#INST_ASAL').change(function(event) {
                // if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH != 1 ) {
                LEMBAGA = $(this).val();
                selUnitKerja(LEMBAGA);
                // }
            });

            $('#INST_TUJUAN').change(function(event) {
                // if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH == 1 ) {
                LEMBAGA = $(this).val();
                selUnitKerja(LEMBAGA);
                // }
            });

            LEMBAGA = $('#INST_ASAL').val();
            selUnitKerja(LEMBAGA);
        }
    });
</script>
<?php
}
?>


<?php if($form=='delete_calon_pn'){ ?>
<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/do_delete_calon_pn/<?php echo $id; ?>">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $id; ?>">
    <p>Apakah anda yakin akan menghapus data ini?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
          <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormResetPassword"), 'delete', location.href.split('#')[1]);
    });
</script>
<?php
}
?>
<?php if($form=='delete_verifikasi_data_individu'){ ?>
<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/delete_vi2/<?php echo $id; ?>">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $id; ?>">
    <p>Apakah anda yakin akan membatalkan verifikasi data individual ini?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
          <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormResetPassword"), 'delete', location.href.split('#')[1]);
    });
</script>
<?php
}
?>


<?php if($form=='delete_daftar_pn_individual'){ ?>
<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/do_delete_daftar_pn_individual/<?php echo $id; ?>">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $id; ?>">
    <p>Apakah anda yakin akan menghapus data ini?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
         <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormResetPassword"), 'delete', location.href.split('#')[1]);
    });
</script>
<?php
}
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
	    $('.date-picker').datepicker({
	       format: 'dd/mm/yyyy'
	    });
        // $('#KAB_KOT').select2();
        // $('#KEC').select2();
        // $('#KEL').select2();
        // prov();
	});
    // function prov () {
    //     $.post("index.php/efill/lhkpn/daftar_provinsi", function(html){
    //         $.each(html, function(index, value){
    //             $("#PROV").append("<option value='"+value['ID_PROV']+"'>"+value['PROV']+"</option>");
    //         });
    //         $("#PROV").select2({
    //             maximumSelectionLength: 2
    //         });
    //     }, 'json');
    // }
    // function kabkot(){
    //     $("#KAB_KOT").prop('disabled', false);
    //     $("#KAB_KOT").empty();
    //     $("#KEC").select2('val', '');
    //     $("#KEL").select2('val', '');
    //     $.post("index.php/efill/lhkpn/daftar_kabkot/"+$("#PROV").val(), function(html){
    //         $("#KAB_KOT").append("<option value=''>-Pilih Kabupaten/Kota-</option>");
    //         $("#KEC").append("<option value=''>-Pilih Kecamatan-</option>");
    //         $("#KEL").append("<option value=''>-Pilih Kelurahan-</option>");
    //         $.each(html, function(index, value){
    //             $("#KAB_KOT").append("<option value='"+index+"'>"+value+"</option>");
    //         });
    //         kec();
    //         $("#KAB_KOT").select2();
    //     }, 'json');
    // }
    // function kec() {
    //     $("#KEC").prop('disabled', false);
    //     $("#KEC").empty();
    //     $.post("index.php/efill/lhkpn/daftar_kec/"+$("#PROV").val()+'/'+$("#KAB_KOT").val(), function(html){
    //         $.each(html, function(index, value){
    //             $("#KEC").append("<option value='"+index+"'>"+value+"</option>");
    //         });
    //         kel();
    //         $("#KEC").select2();
    //     }, 'json');
    // }
    // function kel() {
    //     $("#KEL").prop('disabled', false);
    //     $("#KEL").empty();
    //     $.post("index.php/efill/lhkpn/daftar_kel/"+$("#PROV").val()+'/'+$("#KAB_KOT").val()+'/'+$("#KEC").val(), function(html){
    //         $.each(html, function(index, value){
    //             $("#KEL").append("<option value='"+index+"'>"+value+"</option>");
    //         });
    //         $("#KEL").select2();
    //     }, 'json');
    // }

</script>
<?php
if($form == 'nonaktifkan'){
?>
<div id="wrapperFormNonaktifkan">
    <div class="row">

        <div class="col-md-3">
            <?php if($item != ''){ ?>
                <img src="./uploads/data_pribadi/<?php echo$item->NIK?>/<?php echo$item->FOTO?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
            <?php }else{ ?>
                <img src="http://localhost:8181/lhkpn_public/uploads/users/avatar.png" class="img-rounded" alt="avatar" style="max-width:100px;"/>
            <?php } ?>
        </div>

        <div class="col-md-9">
            <h3>Detail</h3>
            <table class="table-detail">
                <tr><td>NIK</td><td>:</td><td><?php echo $item->NIK;?></td></tr>
                <tr><td>Nama</td><td>:</td><td><?php echo $item->NAMA;?></td></tr>
                <tr><td>Jenis Kelamin</td><td>:</td><td>
                    <?php if($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
                    <?php if($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
                </td></tr>
                <tr><td>Tempat / Tanggal Lahir</td><td>:</td><td>
                    <?php echo $item->TEMPAT_LAHIR; ?> / <?php echo $item->TGL_LAHIR;?>
                    </td></tr>
                <tr><td>Agama</td><td>:</td><td>
                    <?php if($item->ID_AGAMA==1) echo "ISLAM";?>
                    <?php if($item->ID_AGAMA==2) echo "KRISTEN";?>
                    <?php if($item->ID_AGAMA==3) echo "KALOTIK";?>
                    <?php if($item->ID_AGAMA==4) echo "HINDU";?>
                    <?php if($item->ID_AGAMA==5) echo "BUDHA";?>
                <td></tr>
                <tr><td>Status Nikah</td><td>:</td><td>
                    <?php if($item->ID_STATUS_NIKAH==1) echo "KAWIN";?>
                    <?php if($item->ID_STATUS_NIKAH==2) echo "TIDAK KAWIN";?>
                    <?php if($item->ID_STATUS_NIKAH==3) echo "JANDA";?>
                    <?php if($item->ID_STATUS_NIKAH==4) echo "DUDA";?>

                </td></tr>
                <tr><td>Pendidikan Terakhir</td><td>:</td><td>
                    <?php if($item->ID_PENDIDIKAN==1) echo "SD";?>
                    <?php if($item->ID_PENDIDIKAN==2) echo "SLTP";?>
                    <?php if($item->ID_PENDIDIKAN==3) echo "STLA";?>
                    <?php if($item->ID_PENDIDIKAN==4) echo "D3";?>
                    <?php if($item->ID_PENDIDIKAN==5) echo "S1/D4";?>
                    <?php if($item->ID_PENDIDIKAN==6) echo "S2";?>
                    <?php if($item->ID_PENDIDIKAN==7) echo "S4";?>
                </td></tr>
                <tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr>
                <tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr>
                <tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
                <tr><td>No HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
                <tr>
                    <td>Status PN</td>
                    <td>:</td>
                    <td>
                    <?php echo $item->STATUS_PN == '1' ? 'PN/WL' : ($item->STATUS_PN == '2' ? 'Calon PN' : ''); ?>
                    </td>
                </tr>
            </table>
        </div>
        <table class="table table-bordered table-hover table-striped" >
            <thead class="table-header">
                <tr>
                    <th width="30">No.</th>
                    <th>Jabatan/Eselon</th>
                    <th>Lembaga</th>
                    <th>Unit Kerja</th>
                    <th>TMT/SD</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1;foreach ($detJabatan as $key): ?>
                    <tr>
                        <td><?php echo @$i++; ?></td>
                        <td><?php echo @$key->JABATAN; ?></td>
                        <td><?php echo @$key->INST_NAMA; ?></td>
                        <td><?php echo @$key->UK_NAMA; ?></td>
                        <td align="center"><?php echo @$key->TMT; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <form class="form-horizontal" method="post" id="ajaxFormNonaktifkan" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <div class="pull-right">
            <input type="hidden" name="ID_PN" id="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" id="act" value="dononaktif">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
              <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormNonaktifkan"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php
}

if($form == 'terpilih'){
    ?>
    Penetapan PN/WL
    <form class="form-horizontal" method="post" id="ajaxFormTerpilih" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama PN :</label>
            <div class="col-sm-7">
                <?php echo $item->NAMA;?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan :</label>
            <div class="col-sm-7">
                <?php if($item->IS_CALON == 1){echo '<span class="label label-warning">Calon</span>';}?>  <?php echo $item->NAMA_JABATAN;?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Instansi :</label>
            <div class="col-sm-7">
                <?php echo $item->INST_NAMA;?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja :</label>
            <div class="col-sm-7">
                <?php echo $item->UK_NAMA;?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">TMT <font color='red'>*</font>:</label>
            <div class="col-sm-7">
                <input type="text" class="form-control datepicker" id="TMT" name="TMT" value="<?php echo date('d/m/Y'); ?>" required>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" id="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="act" id="act" value="doterpilih">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Batal</button>
        </div>
    </form>

    <script type="text/javascript">
        function f_redirect(){
            $.post('index.php/ereg/all_pn/cekNIK/'+$('#NIK').val(), function (data) {
                if(data != 0){
                    $("#wrapperFormPNExist").html(data);
                }
            });
        }

        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy'
            });

            <?php if($stat == '1'){ ?>
                $("form#ajaxFormTerpilih").submit(function(event) {
                    var urll = $(this).attr('action');
                    var formData = new FormData($(this)[0]);

                    $.ajax({
                        url: urll,
                        type: 'POST',
                        data: formData,
                        async: false,
                        success: function (html) {
                            html = jQuery.parseJSON(html);

                            msg = {
                                success : 'Data Berhasil Disimpan!',
                                error  : 'Data Gagal Disimpan!'
                            };
                            if (html.status == 0) {
                                alertify.error(msg.error);
                            }else{
                                alertify.success(msg.success);
                            }

                            if(html.status == 1){
                                $.post('index.php/ereg/all_pn/cekNIK/'+$('#NIK').val(), function (data) {
                                    if(data != 0){
                                        $('.modal-dialog').animate({
                                            width: '+=500'
                                        });
                                        $("#wrapperFormPNExist").html(data);
                                        alertify.success('Jabatan berhasil di tambahkan!');
                                    }
                                });
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                    return false;
                });
            <?php }else{ ?>
            ng.formProcess($("#ajaxFormTerpilih"), 'insert', location.href.split('#')[1]);
            <?php } ?>
        });
    </script>
    <?php
} ?>
<?php
    if($form == 'actMeninggal'){
?>
    Apakah anda yakin akan mengaktifkan data ini ? <br><br>
    <form class="form-horizontal" method="post" id="ajaxFormTerpilih" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama PN :</label>
            <div class="col-sm-7">
                <?php echo $item->NAMA;?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan :</label>
            <div class="col-sm-7">
                <?php if($item->IS_CALON == 1){echo '<span class="label label-warning">Calon</span>';}?>  <?php echo $item->NAMA_JABATAN;?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Instansi :</label>
            <div class="col-sm-7">
                <?php echo $item->INST_NAMA;?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja :</label>
            <div class="col-sm-7">
                <?php echo $item->UK_NAMA;?>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" id="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="act" id="act" value="doActive">
            <button type="submit" class="btn btn-sm btn-primary">Aktifkan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>

    <script type="text/javascript">
        function f_redirect(){
            $.post('index.php/ereg/all_pn/cekNIK/'+$('#NIK').val(), function (data) {
                if(data != 0){
                    $("#wrapperFormPNExist").html(data);
                }
            });
        }

        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy'
            });

            <?php if($stat == '1'){ ?>
                $("form#ajaxFormTerpilih").submit(function(event) {
                    var urll = $(this).attr('action');
                    var formData = new FormData($(this)[0]);

                    $.ajax({
                        url: urll,
                        type: 'POST',
                        data: formData,
                        async: false,
                        success: function (html) {
                            html = jQuery.parseJSON(html);

                            msg = {
                                success : 'Data Berhasil Disimpan!',
                                error  : 'Data Gagal Disimpan!'
                            };
                            if (html.status == 0) {
                                alertify.error(msg.error);
                            }else{
                                alertify.success(msg.success);
                            }

                            if(html.status == 1){
                                $.post('index.php/ereg/all_pn/cekNIK/'+$('#NIK').val(), function (data) {
                                    if(data != 0){
                                        $('.modal-dialog').animate({
                                            width: '+=500'
                                        });
                                        $("#wrapperFormPNExist").html(data);
                                        alertify.success('Jabatan berhasil di tambahkan!');
                                    }
                                });
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                    return false;
                });
            <?php }else{ ?>
            ng.formProcess($("#ajaxFormTerpilih"), 'insert', location.href.split('#')[1]);
            <?php } ?>
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
                        $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
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
//alert(uk_);

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
                    alert(id);
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

    </script>
    <?php
} ?>

<script type="text/javascript">
    $(document).ready(function() {
        // $('.numbersOnly').mask("+");
    });
    function validate(evt) {
      var theEvent = evt || window.event;
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode( key );
      var regex = /[0-9\b]|\./;
      if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
      }
    }
</script>