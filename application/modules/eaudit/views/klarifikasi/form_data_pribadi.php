<style type="text/css">

</style>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/eaudit/klarifikasi/do_update_data_pribadi" enctype="multipart/form-data">
        <?php //display($data_pribadi); ?>
        <input type='hidden' name='ID' id='ID'  value='<?php echo $data_pribadi->ID; ?>' />
        <div class="box-body form-horizontal">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="box-header with-border portlet-header judul-header">
                            <h3 class="box-title">Data Pribadi</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nomor KK:</label>
                        <div class="col-sm-4">
                            <input class="form-control" type='text' name='NO_KK' id='NO_KK' placeholder="Nomor Kartu Keluarga" value='<?php echo $data_pribadi->NO_KK; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NIK <span class="red-label">*</span>:</label>
                        <div class="col-sm-4">
                            <input required class="form-control" type='text' name='NIK' id='NIK' placeholder="NIK" value='<?php echo $data_pribadi->NIK; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NPWP:</label>
                        <div class="col-sm-4">
                            <input class="form-control" type='text' name='NPWP' id='NPWP' placeholder="NPWP" value='<?php echo $data_pribadi->NPWP; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Gelar Depan:</label>
                        <div class="col-sm-4">
                            <input class="form-control" type='text' name='GELAR_DEPAN' id='GELAR_DEPAN' placeholder="Ir." value='<?php echo $data_pribadi->GELAR_DEPAN; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nama Lengkap <span class="red-label">*</span>:</label>
                        <div class="col-sm-7">
                            <input required class="form-control input_capital" type='text' name='NAMA_LENGKAP' id='NAMA_LENGKAP' placeholder="NAMA LENGKAP" value='<?php echo $data_pribadi->NAMA_LENGKAP; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Gelar Belakang:</label>
                        <div class="col-sm-4">
                            <input class="form-control" type='text' name='GELAR_BELAKANG' id='GELAR_BELAKANG' placeholder="S.Kom." value='<?php echo $data_pribadi->GELAR_BELAKANG; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Jenis Kelamin:</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="JENIS_KELAMIN">
                                <option value="">-</option>
                                <option value="1" <?php echo $data_pribadi->JENIS_KELAMIN == "1" ? "selected" : ""; ?> >Laki - Laki</option>
                                <option value="2" <?php echo $data_pribadi->JENIS_KELAMIN == "2" ? "selected" : ""; ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tempat, Tanggal Lahir <span class="red-label">*</span>:</label>
                        <div class="col-sm-4">
                            <input required class="form-control input_capital" type='text' name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat Lahir" value='<?php echo $data_pribadi->TEMPAT_LAHIR; ?>'>
                        </div>
                        <div class="col-sm-3">
                            <input required class="form-control date-picker" type='text' name='TANGGAL_LAHIR' id='TANGGAL_LAHIR' placeholder='DD/MM/YYYY' value="<?php echo date('d/m/Y', strtotime($data_pribadi->TANGGAL_LAHIR)); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Agama:</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="AGAMA">
                                <option value="ISLAM" <?php echo strtoupper($data_pribadi->AGAMA) == "ISLAM" ? "selected" : ""; ?> >ISLAM</option>
                                <option value="KATOLIK" <?php echo strtoupper($data_pribadi->AGAMA) == "KATOLIK" ? "selected" : ""; ?>>KATOLIK</option>
                                <option value="KRISTEN PROTESTAN" <?php echo strtoupper($data_pribadi->AGAMA) == "KRISTEN PROTESTAN" ? "selected" : ""; ?>>KRISTEN PROTESTAN</option>
                                <option value="HINDU" <?php echo strtoupper($data_pribadi->AGAMA) == "HINDU" ? "selected" : ""; ?>>HINDU</option>
                                <option value="BUDDHA" <?php echo strtoupper($data_pribadi->AGAMA) == "BUDDHA" ? "selected" : ""; ?>>BUDDHA</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Status Perkawinan:</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="STATUS_PERKAWINAN">
                                <option value="">-</option>
                                <option value="Menikah" <?php echo $data_pribadi->STATUS_PERKAWINAN == "Menikah" ? "selected" : ""; ?> >Menikah</option>
                                <option value="Belum Kawin" <?php echo $data_pribadi->STATUS_PERKAWINAN == "Belum Kawin" ? "selected" : ""; ?>>Belum Kawin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php //$ffoto = ''; // = "uploads/data_pribadi/".encrypt_username($LHKPN->NIK_PN)."/".$DATA_PRIBADI->FOTO; ?>
                    <label class="control-label">Foto :</label>
                    <?php
                        // if (file_exists($ffoto)) 
                        //     $ffoto = $ffoto;
                        // else
                            $ffoto = "images/no_available_image.png";
                    ?>
                    <img class="file-preview-image" style="width: 100px; height: 100px;"  src="<?php echo base_url() . $ffoto; ?>">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="box-header with-border portlet-header judul-header">
                            <h3 class="box-title">Data Kontak</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Alamat Rumah:</label>
                        <div class="col-sm-7">
                            <textarea rows="3" cols="50" class="form-control input_capital" name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" placeholder="Alamat Lengkap"><?php echo $data_pribadi->ALAMAT_RUMAH; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Provinsi:</label>
                        <div class="col-sm-7">
                        <input class="form-control depan" type="text" name="PROV" id="PROV" value="<?php echo $data_pribadi->PROVINSI; ?>" />
                        <input class="form-control" type="hidden" name="PROVINSI" id="PROVINSI" placeholder="Nama Provinsi" value="<?php echo $data_pribadi->PROVINSI; ?>" />
                        <!-- <input class="form-control" type='text' name='PROVINSI' id='PROVINSI' placeholder="Nama Provinsi" value='<?php //echo $data_pribadi->PROVINSI; ?>'> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Kabupaten/Kota:</label>
                        <div class="col-sm-7">
                        <input class="form-control depan" type="text" name="KABKOT" id="KABKOT" value="<?php echo $data_pribadi->KABKOT; ?>" />
                        <input class="form-control" type="hidden" name="KABKOT_NAME" id="KABKOT_NAME" value="<?php echo $data_pribadi->KABKOT; ?>" />
                        <!-- <input class="form-control" type='text' name='KABKOT' id='KABKOT' placeholder="Nama Kabupaten/Kota" value='<?php //echo $data_pribadi->KABKOT; ?>'> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Kecamatan:</label>
                        <div class="col-sm-7">
                            <input class="form-control" type='text' name='KECAMATAN' id='KECAMATAN' placeholder="Nama Kecamatan" value='<?php echo $data_pribadi->KECAMATAN; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Kelurahan:</label>
                        <div class="col-sm-7">
                            <input class="form-control" type='text' name='KELURAHAN' id='KELURAHAN' placeholder="Nama Kelurahan" value='<?php echo $data_pribadi->KELURAHAN; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Email Pribadi (Aktif) <span class="red-label">*</span>:</label>
                        <div class="col-sm-7">
                            <input required class="form-control input_capital" type='text' name='EMAIL_PRIBADI' id='EMAIL_PRIBADI' placeholder="elhkpn@kpk.go.id" value='<?php echo $data_pribadi->EMAIL_PRIBADI; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">No. Tel. Rumah:</label>
                        <div class="col-sm-7">
                            <input class="form-control" type='text' name='TELPON_RUMAH' id='TELPON_RUMAH' placeholder="(021) 123 4567" value='<?php echo $data_pribadi->TELPON_RUMAH; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nomor Handphone <span class="red-label">*</span>:</label>
                        <div class="col-sm-7">
                            <input required class="form-control" type='text' name='HP' id='HP' placeholder="081 234 567 890" value='<?php echo $data_pribadi->HP; ?>'>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <br>
                <div class="pull-right">
                    <button id="btnsimpan" type="submit" class="btn btn-sm btn-primary"><span class="fa fa-save"></span> Simpan Perubahan</button>
                    <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i> Batal</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#TANGGAL_LAHIR').datepicker({
            maxViewMode: "-17 years",
            format: "dd/mm/yyyy",
            autoclose: true
        });
        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li0&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        $('#PROV').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/filing/getprovinsi',
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        q: term, // search term
    //                        pageLimit: 10,
    //                        page: page
                    };
                },
                results: function(data, page) {
                    var myResults = [], more = (page * 10) < data.total;
                    $.each(data, function(index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults,
    //                        more: more
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function(e) {
            var value = $('#PROV').val();
            var data = $('#PROV').select2('data');
            if(data) {
                $('#PROVINSI').val(data.text);
            }

            if (isDefined(value) && value != '') {
                GetKota(value);
            }
        });
        $('#PROV').select2("data", {id: '', text: '<?php echo $data_pribadi->PROVINSI; ?>'});
        
    });

    function GetKota(id) {
        $('#KABKOT').select2({
            // placeholder: "Pilih Kota",
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/filing/getkota/' + id,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function(e) {
            var data = $('#KABKOT').select2('data');
            if(data) {
            $('#KABKOT_NAME').val(data.text);
            }
        });
    }
    
</script>