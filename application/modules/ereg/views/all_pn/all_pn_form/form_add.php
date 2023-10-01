<div id="wrapperFormAdd">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <div id="wrapperFormAddPN">
            
        <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
            <div role="tabpanel" class="tab-pane active" id="a">
            <div class="contentTab">
            <div class="form-group">
                <label class="col-sm-3 control-label">NIK <span class="red-label">*</span>:</label>
                <div class="col-sm-7">
                    <input required class="form-control" type='text' maxlength="16" size='50' name='NIK' id='NIK' placeholder="NIK" onkeypress="return isNumber(event)" >
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
		/*
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
*/
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