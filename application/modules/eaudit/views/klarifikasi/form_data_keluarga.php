<?php 
if ($mode == 'new') {
    $onAdd = true;
}
else{
    $onAdd = false;
}
?>
<div id="wrapperFormEdit">
    <form role="form" id="ajaxFormEdit" action="index.php/eaudit/klarifikasi/do_update_data_keluarga/<?php echo $mode; ?>" onsubmit="return validateForm()" >
        <div class="modal-body row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>NIK <span class="red-label">*</span></label>
                    <input required onkeypress="return isNumber(event);" type="text" name="NIK" id="NIK" class="form-control input_capital" value="<?php echo $keluarga->NIK; ?>" maxlength="16" onkeyup='HitungText()' autocomplete="off" />
                </div>
                <div class="form-group">
                    <span align='center' id='NIK1'></span>
                </div>
                <div class="form-group">
                    <?php if (!$onAdd): ?>
                        <input type="hidden" name="ID_KELUARGA" id="ID_KELUARGA" value="<?php echo $keluarga->ID_KELUARGA; ?>"/>
                    <?php endif; ?>
                    <input type="hidden" name="ID" id="ID" value="<?php echo $id_lhkpn; ?>" />
                    <label>Nama <span class="red-label">*</span> </label>
                    <input type="text" name="NAMA" id="NAMA" class="form-control input_capital" value="<?php echo $onAdd ? "" : $keluarga->NAMA; ?>" required/>
                </div>
                <div class="form-group hubungan">
                    <label>Hubungan <span class="red-label">*</span> </label>
                    <select name="HUBUNGAN" id="HUBUNGAN" class="form-control" required="">
                        <?php
                            if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11'|| $lhkpn_ver == '2.1') {
                        ?>
                            <option></option>
                            <option value="3" <?php echo !$onAdd && $keluarga->HUBUNGAN == '3' ? 'selected' : ''; ?>>ISTRI</option>
                            <option value="2" <?php echo !$onAdd && $keluarga->HUBUNGAN == '2' ? 'selected' : ''; ?>>SUAMI</option>
                            <option value="4" <?php echo !$onAdd && $keluarga->HUBUNGAN == '4' ? 'selected' : ''; ?>>ANAK TANGGUNGAN</option>
                            <option value="5" <?php echo !$onAdd && $keluarga->HUBUNGAN == '5' ? 'selected' : ''; ?>>ANAK BUKAN TANGGUNGAN</option>
                            <option value="1" <?php echo !$onAdd && $keluarga->HUBUNGAN == '1' ? 'selected' : ''; ?>>LAINNYA</option>            
                        <?php
                            }else{
                        ?>
                            <option></option>
                            <option value="1" <?php echo !$onAdd && $keluarga->HUBUNGAN == '1' ? 'selected' : ''; ?>>ISTRI</option>
                            <option value="2" <?php echo !$onAdd && $keluarga->HUBUNGAN == '2' ? 'selected' : ''; ?>>SUAMI</option>
                            <option value="3" <?php echo !$onAdd && $keluarga->HUBUNGAN == '3' ? 'selected' : ''; ?>>ANAK TANGGUNGAN</option>
                            <option value="4" <?php echo !$onAdd && $keluarga->HUBUNGAN == '4' ? 'selected' : ''; ?>>ANAK BUKAN TANGGUNGAN</option>
                            <option value="5" <?php echo !$onAdd && $keluarga->HUBUNGAN == '5' ? 'selected' : ''; ?>>LAINNYA</option>
                        <?php
                            }
                        ?>

                    </select>
                </div>
                <div class="form-group">
                    <label>Tempat Lahir <span class="red-label">*</span> </label>
                    <input type="text" name="TEMPAT_LAHIR" id="TEMPAT_LAHIR" class="form-control input_capital" required value="<?php echo !$onAdd ? $keluarga->TEMPAT_LAHIR : ''; ?>"/>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir <span class="red-label">*</span> </label>
                    <div class="input-group date">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </div>
                        <input type="text" onkeypress="return validateInput(event);" name="TANGGAL_LAHIR" id="TANGGAL_LAHIR" placeholder="( tgl/bulan/tahun )" class="form-control date" required value="<?php echo !$onAdd ? show_date_with_format($keluarga->TANGGAL_LAHIR, 'd/m/Y') : ''; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <span id="hitungUmur"></span>
                </div>
            </div>
            <div class="col-sm-6">
                  <div class="form-group jenis_kelamin">
                  <label>Jenis Kelamin <span class="red-label">*</span></label>
                  <select class="form-control" id="jenis_kelamin" name="JENIS_KELAMIN" required>
                  <option></option>
                  <option value="LAKI-LAKI" <?php echo !$onAdd && $keluarga->JENIS_KELAMIN == 'LAKI-LAKI' ? 'selected' : ''; ?>>LAKI-LAKI</option>
                  <option value="PEREMPUAN" <?php echo !$onAdd && $keluarga->JENIS_KELAMIN == 'PEREMPUAN' ? 'selected' : ''; ?>>PEREMPUAN</option>
                  </select>
                  </div>
                <div class="form-group">
                    <label>Pekerjaan </label> <?= FormHelpPopOver('pekerjaan_dkel'); ?>
                    <input type="text" name="PEKERJAAN" id="PEKERJAAN" class="form-control input_capital" value="<?php echo !$onAdd ? $keluarga->PEKERJAAN : ''; ?>" />
                </div>
                <div class="form-group">
                    <label>Nomor Telepon/Handphone </label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                        <input type="text" id="NOMOR_TELPON" name="NOMOR_TELPON" class="form-control" onkeypress="return isNumber(event)"  placeholder="Isikan Nomor Handphone" value="<?php echo !$onAdd ? $keluarga->NOMOR_TELPON : ''; ?>"  />
                    </div>
                </div>
                <div class="form-group">    
                    <label>Alamat<span class="red-label">*</span> </label>
                    <textarea class="form-control input_capital" rows="3" name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" required ><?php echo !$onAdd ? $keluarga->ALAMAT_RUMAH:''; ?></textarea>
					<input id="alamat_pn" type="button" class="btn  btn-sm btn-primary" value="sama dengan PN">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn-submit" class="btn btn-primary btn-sm">
                <i class="fa fa-save"></i> Simpan
            </button>
            <button type="button" id="btn-cancel" class="btn btn-danger btn-sm" data-dismiss="modal">
                <i class="fa fa-remove"></i> Batal
            </button>
        </div>
    </form>
</div>
<script type="text/javascript">
    function testDigitNik(Teks, total) {
        if (Teks < 16) {
            total.innerHTML = '<img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> tidak boleh kurang dari 16 Digit';
            $('#btn-submit').prop('disabled', true);
            return false;
        } else {
            total.innerHTML = ' <img id="nik_ada1" src="<?php echo base_url('img/success.png') ?>" width="24" /> sudah benar 16 digit';
            $('#btn-submit').prop('disabled', false);
        }
        return true;
    }

    function HitungText(needResponse) {
        $('#NIK1').show();
        var Teks = $('#NIK').val().length;
        var total = $('#NIK1')[0];
        if (!isDefined(needResponse)) {
            needResponse = false;
        }
        var isNikOk = testDigitNik(Teks, total);

        if (needResponse) {
            return isNikOk;
        }

    }

    var alamat_rumah;
    $(document).ready(function () {
        HitungText();

        $("#hitungUmur").hide();
    	var alamat_rumah = "<?php echo $alamat_rumah; ?>";
        $('#alamat_pn').on('click', function(e) {
            $('#ALAMAT_RUMAH').val(alamat_rumah);
        });

        $('#TANGGAL_LAHIR').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }).on('changeDate', function(e){
        	$("#hitungUmur").show();
        	var z = $("#TANGGAL_LAHIR").val();
        	var dob = e.date;
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            
            var year = today.getYear() - dob.getYear();
            var month = today.getMonth() - dob.getMonth();
            var date = today.getDate() - dob.getDate();
            console.log(age + '|' + year + '|' + month + '|' + date);
            if (age > 0) {
                if (month < 0) {
                    if (date < 0) {
                		var year = (today.getYear() - 1) - dob.getYear();
                		var month = 11 - (dob.getMonth() - today.getMonth()) ;
                		$("#hitungUmur").html("<strong>Umur : " + year + " Tahun " + month + " Bulan</strong>");
                	} else {
                		var year = (today.getYear() - 1) - dob.getYear();
                    	var month = 12 - (dob.getMonth() - today.getMonth());
                    	$("#hitungUmur").html("<strong>Umur : " + year + " Tahun " + month + " Bulan</strong>");
                	}
                } else if (month == 0) {
                    if (date < 0) {
                    	var year = (today.getYear() - 1) - dob.getYear();
                    	$("#hitungUmur").html("<strong>Umur : " + year + " Tahun 11 Bulan</strong>" );
                    } else {
                    	$("#hitungUmur").html("<strong>Umur : " + year + " Tahun</strong>" );
                    }
                } else if (month == 1) {
                    if (date < 0) {
                    	var month = today.getMonth() - dob.getMonth() - 1 ;
                    	$("#hitungUmur").html("<strong>Umur : " + year + " Tahun</strong>");
                    } else {
                    	var month = today.getMonth() - dob.getMonth() ;
                    	$("#hitungUmur").html("<strong>Umur : " + year + " Tahun " + month + " Bulan</strong>");
                    }
                } else if (month > 1) {
                    if (date < 0) {
                    	var month = today.getMonth() - dob.getMonth() - 1 ;
                    	$("#hitungUmur").html("<strong>Umur : " + year + " Tahun " + month + " Bulan</strong>");
                    } else {
                    	var month = today.getMonth() - dob.getMonth() ;
                    	$("#hitungUmur").html("<strong>Umur : " + year + " Tahun " + month + " Bulan</strong>");
                    }
                }
            } else if (age == 0) {
                if (month < 0) {
					if (date < 0) {
	                	var month = 11 - (dob.getMonth() - today.getMonth());
                        if (month == 0) {
                            $("#hitungUmur").html("<strong>Umur : Belum 1 Bulan</strong>");
                        } else {
                            $("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");
                        }
					} else {
	                	var month = 12 - (dob.getMonth() - today.getMonth());
	                	$("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");
					}
                } else if (month == 0) {
                    if (date < 0) {
						$("#hitungUmur").html("<strong>Umur : 11 Bulan</strong>");
                    } else {
                    	$("#hitungUmur").html("<strong>Umur : Belum 1 Bulan</strong>");
                    }
                } else if (month == 1) {
                    if (date < 0) {
                    	$("#hitungUmur").html("<strong>Umur : Belum 1 Bulan</strong>");
                    } else {
                    	$("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");                    }
                } else if (month > 1) {
                    if (date < 0) {
                		var month = today.getMonth() - dob.getMonth() - 1;
                		$("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");
                    } else {
                		var month = today.getMonth() - dob.getMonth();
                		$("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");
                    }
                }
            }
        });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li2&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

    });

    function validateInput(event) {
    	return false;
    }

</script>