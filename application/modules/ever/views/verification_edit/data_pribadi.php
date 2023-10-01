<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/ever/verification_edit/update_data_pribadi" enctype="multipart/form-data">
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
                        <label class="col-sm-4 control-label">Nomor KK :</label>
                        <div class="col-sm-4">
                            <input required class="form-control" type='text' name='NO_KK' id='NO_KK' placeholder="Nomor Kartu Keluarga" value='<?php echo $data_pribadi->NO_KK; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NIK <span class="red-label">*</span>:</label>
                        <div class="col-sm-4">
                            <input required class="form-control" type='text' name='NIK' id='NIK' placeholder="NIK" value='<?php echo $data_pribadi->NIK; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NPWP :</label>
                        <div class="col-sm-4">
                            <input class="form-control" type='text' name='NPWP' id='NPWP' placeholder="NPWP" value='<?php echo $data_pribadi->NPWP; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Gelar Depan :</label>
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
                        <label class="col-sm-4 control-label">Gelar Belakang :</label>
                        <div class="col-sm-4">
                            <input class="form-control" type='text' name='GELAR_BELAKANG' id='GELAR_BELAKANG' placeholder="S.Kom." value='<?php echo $data_pribadi->GELAR_BELAKANG; ?>' />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Jenis Kelamin :</label>
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
                        <label class="col-sm-4 control-label">Agama :</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="AGAMA">
                                <option value="">-</option>
                                <option value="Islam" <?php echo $data_pribadi->AGAMA == "Islam" ? "selected" : ""; ?> >Islam</option>
                                <option value="Katolik" <?php echo $data_pribadi->AGAMA == "Katolik" ? "selected" : ""; ?>>Katolik</option>
                                <option value="Kristen Protestan" <?php echo $data_pribadi->AGAMA == "Kristen Protestan" ? "selected" : ""; ?>>Kristen Protestan</option>
                                <option value="Hindu" <?php echo $data_pribadi->AGAMA == "Hindu" ? "selected" : ""; ?>>Hindu</option>
                                <option value="Buddha" <?php echo $data_pribadi->AGAMA == "Buddha" ? "selected" : ""; ?>>Buddha</option>
                                <option value="Konghuchu" <?php echo $data_pribadi->AGAMA == "Konghuchu" ? "selected" : ""; ?>>Konghuchu</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Status Perkawinan :</label>
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
                        <label class="col-sm-4 control-label">Alamat Rumah :</label>
                        <div class="col-sm-7">
                            <textarea rows="3" cols="50" class="form-control input_capital" name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" placeholder="Alamat Lengkap"><?php echo $data_pribadi->ALAMAT_RUMAH; ?></textarea>
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
        $('select').select2();
        $('#TANGGAL_LAHIR').datepicker({
            maxViewMode: "-17 years",
            format: "dd/mm/yyyy",
            autoclose: true
        });
        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li1&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
</script>