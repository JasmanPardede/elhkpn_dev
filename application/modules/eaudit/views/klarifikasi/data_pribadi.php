<!--<form role="role" id="form-data-pribadi" enctype="multipart/form-data">-->
<form role="form" enctype="multipart/form-data" name="submitdatapribadi" id="submitdatapribadi" method="POST" relaction="<?php echo base_url() . 'portal/data_pribadi/update'; ?>" action="javascript:void(0);">
    <div class="box-body row">
        <div class="col-lg-4">
            <div class="form-group group-0">
                <input type="hidden" name="ID" id="ID" irule="required"/>
                <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                <input type="hidden" name="type" id="type"/>
                <label for="exampleInputFile">Foto <!-- <span class="red-label">*</span> --></label> <?= FormHelpPopOver('foto_dp'); ?>

                <input type="file" id="foto" name="FILE_FOTO" data-allowed-file-extensions='["jpg", "jpeg","png"]' />
                <input type="hidden" id="js_real_photo" value="<?php echo file_exists('uploads/data_pribadi/' . $NIKLengkap . '/foto.jpg') ? 'uploads/data_pribadi/' . $NIKLengkap . '/foto.jpg' : 'images/no_available_image.png'; ?>">

            </div>
            <div class="form-group group-1">
                <label id="LBL_STATUS_PERKAWINAN">Status Nikah<span class="red-label">*</span></label>  <?= FormHelpPopOver('status_nikah_dp'); ?>
                <select class="form-control" id="STATUS_PERKAWINAN" name="STATUS_PERKAWINAN">
                    <?php echo $nikah; ?>
                </select>
            </div>
            <div class="form-group group-1">
                <label id="LBL_AGAMA">Agama <span class="red-label">*</span></label> <?= FormHelpPopOver('agama_dp'); ?>
                <select class="form-control" name="AGAMA" irule="required" id="AGAMA"  >
                    <?php echo $agama; ?>
                </select>
            </div>
            <div class="form-group group-1">
                <label id="LBL_HP">Nomor Handphone <span class="red-label">*</span></label> <?= FormHelpPopOver('no_handphone_dp'); ?>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                    <input type="text" id="HP" name="HP" irule="required" class="form-control"  onkeypress="return isNumber(event)" />
                </div>
            </div>
            <div class="form-group group-1">
                <label id="LBL_EMAIL_PRIBADI">Email <span class="red-label">*</span></label> <?= FormHelpPopOver('email_dp'); ?>
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                    <input type="email" id="EMAIL_PRIBADI" irule="required" name="EMAIL_PRIBADI" class="form-control"  />
                </div>
            </div>


        </div>
        <div class="col-lg-4">
            <div class="form-group group-0">
                <label id="LBL_NIK">NIK <span class="red-label">*</span> </label> <?= FormHelpPopOver('nik_dp'); ?>
                <input type="text" readonly name="NIK" id="NIK" irule="required" placeholder="Isikan NIK" class="form-control"  />
            </div>
            <div class="form-group group-0">
                <label id="LBL_KK">No. KK</label> <?= FormHelpPopOver('kk_dp'); ?>
                <input type="text" name="KK" id="KK" placeholder="Nomor Kartu Keluarga" class="form-control" onkeypress="return isNumber(event)"  />
            </div>
            <div class="form-group group-0">
                <label id="LBL_NAMA_LENGKAP">Nama Lengkap <span class="red-label">*</span></label> <?= FormHelpPopOver('nama_dp'); ?>
                <input type="text" onkeyup="this.value = this.value.toUpperCase()" irule="required" name="NAMA_LENGKAP" id="NAMA_LENGKAP"  class="form-control"  />
            </div>
            <div class="form-group group-0">
                <label id="LBL_GELAR_DEPAN">Gelar Depan </label> <?= FormHelpPopOver('gelar_dpn_dp'); ?>
                <input type="text" name="GELAR_DEPAN" id="GELAR_DEPAN"  class="form-control" />
            </div>
            <div class="form-group group-0">
                <label id="LBL_GELAR_BELAKANG">Gelar Belakang </label> <?= FormHelpPopOver('gelar_blk_dp'); ?>
                <input type="text" name="GELAR_BELAKANG" id="GELAR_BELAKANG"  class="form-control" />
            </div>

            <div class="form-group group-1">
                <label id="LBL_NEGARA">Negara Asal <span class="red-label">*</span></label> <?= FormHelpPopOver('negara_asal_dp'); ?>
                <select class="form-control" id="NEGARA" irule="required" name="NEGARA">
                    <option></option>
                    <option value="1">INDONESIA</option>
                    <option value="2">LUAR NEGERI</option>
                </select>
            </div>
            <div class="form-group group-1 luar">
                <label id="LBL_ID_NEGARA">Negara <span class="red-label">*</span></label> <?= FormHelpPopOver('negara_dp'); ?>
                <input type="hidden" name="ID_NEGARA" irule="required" id="ID_NEGARA" class="form-control" >
            </div>
            <div class="form-group group-1 lokal">
                <label id="LBL_ID_PROPINSI">Provinsi <span class="red-label">*</span></label> <?= FormHelpPopOver('provinsi_dp'); ?>
                <input type="hidden" name="ID_PROPINSI" irule="required" id="ID_PROPINSI" class="form-control" >
            </div>
            <div class="form-group group-1 lokal">
                <label id="LBL_ID_KOTA">Kabupaten / Kota <span class="red-label">*</span></label> <?= FormHelpPopOver('kabupaten_dp'); ?>
                <input type="hidden" name="ID_KOTA" irule="required" id="ID_KOTA" class="form-control" >
            </div>
            <div class="form-group group-1 lokal">
                <label id="LBL_KECAMATAN">Kecamatan <span class="red-label">*</span></label> <?= FormHelpPopOver('kecamatan_dp'); ?>
                <input type="text" onkeyup="this.value = this.value.toUpperCase()" irule="required" id="KECAMATAN" name="KECAMATAN" class="form-control input_capital" >
            </div>

        </div>
        <div class="col-lg-4">
            <div class="form-group group-0">
                <label id="LBL_NPWP">NPWP <span class="red-label">*</span></label> <?= FormHelpPopOver('npwp_dp'); ?>
                <input type="text" name="NPWP" id="NPWP" irule="required"  class="form-control" >
            </div>
            <div class="form-group group-0">
                <label id="LBL_JENIS_KELAMIN">Jenis Kelamin <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_kelamin_dp'); ?>
                <select class="form-control" id="JENIS_KELAMIN" irule="required" name="JENIS_KELAMIN" >
                    <option></option>
                    <option value="1">LAKI-LAKI</option>
                    <option value="2">PEREMPUAN</option>
                </select>
            </div>
            <div class="form-group group-0">
                <label id="LBL_TEMPAT_LAHIR">Tempat Lahir <span class="red-label">*</span></label> <?= FormHelpPopOver('tmp_lahir_dp'); ?>
                <input type="text" onkeyup="this.value = this.value.toUpperCase()" irule="required" name="TEMPAT_LAHIR" id="TEMPAT_LAHIR"  class="form-control"  />
            </div>
            <div class="form-group group-0">
                <label id="LBL_TANGGAL_LAHIR">Tanggal Lahir <span class="red-label">*</span></label> <?= FormHelpPopOver('tgl_lahir_dp'); ?>
                <div class="input-group date">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </div>
                    <input type="text" name="TANGGAL_LAHIR"  irule="required" id="TANGGAL_LAHIR" placeholder="( tgl/bulan/tahun )" class="form-control"  />
                </div>
            </div>

            <div class="form-group group-1 lokal">
                <label id="LBL_KELURAHAN">Desa/Kelurahan <span class="red-label">*</span></label> <?= FormHelpPopOver('kelurahan_dp'); ?>
                <input type="text" onkeyup="this.value = this.value.toUpperCase()" irule="required" name="KELURAHAN" id="KELURAHAN" class="form-control input_capital" >
            </div>
            <div class="form-group group-1 lokal">
                <label id="LBL_ALAMAT_RUMAH">Alamat di Indonesia <span class="red-label">*</span></label> <?= FormHelpPopOver('alamat_dp'); ?>
                <textarea class="form-control" rows="2" irule="required" name="ALAMAT_RUMAH" id="ALAMAT_RUMAH"  />
                </textarea>
            </div>
            <div class="form-group group-1 luar">
                <label id="LBL_ALAMAT_NEGARA">Alamat Di Luar Negeri <span class="red-label">*</span></label> <?= FormHelpPopOver('alamat_luarnegri_dp'); ?>
                <textarea class="form-control" rows="2" irule="required" name="ALAMAT_NEGARA" id="ALAMAT_NEGARA"  />
                </textarea>
            </div>

        </div>
    </div>
    <div class="box-footer">
        <a href="javascript:void(0)" class="btn-warning btn btn-sm btn-sebelum">
            <i class="fa fa-backward"></i> Sebelumnya
        </a>
        <a href="javascript:void(0);" onclick='submitFormDataPribadi();' class="btn btn-warning btn-sm pull-right btn-lanjut">
            Selanjutnya <i class="fa fa-forward"></i>
        </a>

        <a href="javascript:void(0)" onclick="submitFormDataPribadi();pindah(2);" class="btn btn-warning btn-sm pull-right btn-do_next" style="margin-left:5px;">
            Selanjutnya <i class="fa fa-forward"></i>
        </a>
        <button id="btn-save" type="submit" class="btn btn-primary btn-submit btn-sm">
            <i class="fa fa-save"></i>
            Simpan
        </button>
    </div>
</form>

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
