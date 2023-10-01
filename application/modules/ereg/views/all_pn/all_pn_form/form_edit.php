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