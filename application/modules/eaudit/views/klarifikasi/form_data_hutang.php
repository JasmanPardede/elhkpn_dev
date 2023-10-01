<form role="form" id="FormHutang">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HUTANG</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-6">
            <div class="form-group">
                <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />
		    	<input type="hidden" name="ID_LHKPN" value='<?php echo $ID_LHKPN; ?>' id="ID_LHKPN"/>
                <label>Jenis <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('jenis_htg'); ?>
                <select name="KODE_JENIS" id="KODE_JENIS" class="form-control" required></select>  
            </div>
            <!--<div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('atas_nama_ksk'); ?>
                <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $onAdd ? ($hutang->ATAS_NAMA == '1' ? "selected" : "") : ''; ?>>PN YANG BERSANGKUTAN</option>
                    <option value="2" <?php echo $onAdd ? ($hutang->ATAS_NAMA == '2' ? "selected" : "") : ''; ?>>PASANGAN / ANAK</option>
                    <option value="3" <?php echo $onAdd ? ($hutang->ATAS_NAMA == '3' ? "selected" : "") : ''; ?>>LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span> <?php echo FormHelpPopOver('keterangan_hb'); ?>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo $onAdd ? $hutang->KET_LAINNYA : ''; ?>' />
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>-->
            <div class="form-group form-atas-nama">
                <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_htb'); ?>
                <table class="table">  
                    <tbody>
                    	<tr>
                            <td><input type="checkbox" id="ATAS_NAMA_CHECK_PN" name="ATAS_NAMA[]" value="1"  /> PN YANG BERSANGKUTAN</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="ATAS_NAMA_CHECK_PASANGAN" name="ATAS_NAMA[]" value="2" /> PASANGAN / ANAK</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="ATAS_NAMA_CHECK_LAINNYA" name="ATAS_NAMA[]" value="3"  /> LAINNYA</td>
                        </tr>
                    </tbody> 
                </table>
                <small class="help-block notif-atas-nama" style="color:#a94442; display:none;">Pilih Atas Nama Harta</small>
            </div>            
            <div class="form-group form-pasangan-anak" id="ket_pasangan_anak_div">
                <label>Nama Pasangan / Anak </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_pasangan_anak'); ?>
                <select class="selectpicker show-menu-arrow form-control" multiple name="PASANGAN_ANAK[]" id="KET_PASANGAN_ANAK" required> 
                </select>
                <!-- <small class="help-block notif-pasangan-anak" style="color:#a94442; display:none;">Data ini wajib di isi</small> -->
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Nama Orang Lain / Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                <textarea class="form-control input_capital" name="ATAS_NAMA_LAINNYA" id="KET_LAINNYA_AN" rows="2" required></textarea>
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nama Kreditur <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('nama_kreditur_htg'); ?>
                <input type="text" placeholder="" id="NAMA_KREDITUR" name="NAMA_KREDITUR"  class="form-control input_capital" required value="<?php echo $onAdd ? $hutang->NAMA_KREDITUR : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Bentuk Agunan</label> <?php echo FormHelpPopOver('bentuk_agunan_htg'); ?>
                <input type="text" placeholder="" id="AGUNAN" name="AGUNAN"  class="form-control input_capital" value="<?php echo $onAdd ? $hutang->AGUNAN : ''; ?>" />
            </div> 
            <div class="form-group">
                <label>Nilai Awal Hutang (Rp) <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('nilai_awal_hutang_htg'); ?>
                <input type="text" placeholder="" id="AWAL_HUTANG" name="AWAL_HUTANG"  class="form-control money" required value="<?php echo $onAdd ? $hutang->AWAL_HUTANG : ''; ?>" />
            </div> 
            <div class="form-group">
                <label>Nilai Saldo Hutang (Rp) <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('nilai_saldo_hutang_htg'); ?>
                <input type="text" placeholder="" id="SALDO_HUTANG_OLD" name="SALDO_HUTANG_OLD"  class="form-control money" required value="<?php echo $onAdd ? $hutang->SALDO_HUTANG_OLD : '0'; ?>" />
            </div> 
            <div class="form-group">
                <label>Nilai Klarifikasi Hutang (Rp) <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('nilai_klarifikasi_ksk'); ?>
                <input type="text" placeholder="" id="SALDO_HUTANG" name="SALDO_HUTANG"  class="form-control money" required value="<?php echo $onAdd ? $hutang->SALDO_HUTANG : ''; ?>" />
            </div> 
            <div class="form-group">
                <label>Keterangan Pemeriksaan <span class="red-label">*</span> </label>
                <textarea class="form-control" name="KET_PEMERIKSAAN" id="KET_PEMERIKSAAN" rows="2" placeholder="Keterangan" required ><?php echo $onAdd ? $hutang->KET_PEMERIKSAAN : ''; ?></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="button-saved" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // if ($("#ATAS_NAMA").val() == '3') {
        //     $('#ket_lainnya_an_div').show();
        //     $('#ATAS_NAMA_LAINNYA').prop('required', true);
        // } else {
        //     $('#ATAS_NAMA_LAINNYA').removeAttr('required');
        //     $('#ket_lainnya_an_div').hide();
        // }

        // $("#ATAS_NAMA").change(function() {
        //     $("#ATAS_NAMA_LAINNYA").val('');

        //     if ($("#ATAS_NAMA").val() == '3') {
        //         $('#ket_lainnya_an_div').show();
        //         $('#ATAS_NAMA_LAINNYA').prop('required', true);
        //     } else {
        //         $('#ATAS_NAMA_LAINNYA').removeAttr('required');
        //         $('#ket_lainnya_an_div').hide();
        //     }
        // });
        var ID = $('#ID').val();
		var ID_LHKPN = $('#ID_LHKPN').val();

        $('#ket_lainnya_an_div').hide();
        $('#ket_pasangan_anak_div').hide();

         $('#ATAS_NAMA_CHECK_PN').click(function(){
            if($(this).is(':checked')){

            } else {
				
            }
        });
        $('#ATAS_NAMA_CHECK_PASANGAN').click(function(){
            if($(this).is(':checked')){
            	$('#ket_pasangan_anak_div').show();
            	$('select').select2();
                $("#KET_PASANGAN_ANAK").prop('required',true);
            } else {
            	$('#ket_pasangan_anak_div').hide();
            	$('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
                $("#KET_PASANGAN_ANAK").prop('required',false);
            }
        });
        $('#ATAS_NAMA_CHECK_LAINNYA').click(function(){
            if($(this).is(':checked')){
            	$('#ket_lainnya_an_div').show();
                $("#KET_LAINNYA_AN").prop('required',true);
                $('.notif-atas-nama').hide();
                $('.form-atas-nama').removeClass('has-error').addClass('has-success');
            } else {
            	$('#ket_lainnya_an_div').hide();
            	$('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $("#KET_LAINNYA_AN").prop('required',false);
            }
        });
		
		$("#ATAS_NAMA").change(function() {
            $("#KET_LAINNYA_AN").val('');
            $("#KET_PASANGAN_ANAK").val('');
            var isKeteranganLainnyaExists = document.getElementById("KET_LAINNYA_AN");
            var isKeteranganPasanganAnakExists = document.getElementById("KET_PASANGAN_ANAK");
            if ($("#ATAS_NAMA").val() == '3') {
                $('#ket_lainnya_an_div').show();
                $('#ket_pasangan_anak_div').hide();
                $('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('addField', isKeteranganLainnyaExists);
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganPasanganAnakExists);
            } else if($("#ATAS_NAMA").val() == '2') {
                $('#ket_pasangan_anak_div').show();

                $('#ket_lainnya_an_div').hide();
                $('.notif-ket-lainnya').hide();
                $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
                $('#FormHarta').bootstrapValidator('addField', isKeteranganPasanganAnakExists);
            }else {
            	$('#ket_pasangan_anak_div').hide();
                $('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
                $('#ket_lainnya_an_div').hide();
                $('.notif-ket-lainnya').hide();
                $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganPasanganAnakExists);
            }
        });
        var list_pasangan_anak = load_html('eaudit/klarifikasi/get_pasangan_anak_by_id/6/'+ID_LHKPN); 
        $('#KET_PASANGAN_ANAK').html(list_pasangan_anak);





        var list_jenis_harta = load_html('portal/data_harta/get_jenis_hutang_with_data', '<?php echo $onAdd ? $hutang->KODE_JENIS : ''; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);

        // var url = location.href.split('#')[1];
        // url = url.split('?')[0] + '?upperli=li3&bottomli=lii6';
        // ng.formProcess($("#FormHutang"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});

        
        ////////////////////KODE_JENIS///////////////////////////
        var id_kode_jenis = '<?php echo $hutang->KODE_JENIS ?>';
        $('#KODE_JENIS').val(id_kode_jenis).trigger('change');


        ////////////////////ATAS_NAMA///////////////////////////
        var state_atas_nama = '<?php echo $hutang->ATAS_NAMA ?>';
        if (state_atas_nama.indexOf("1") >= 0) {
            $('#ATAS_NAMA_CHECK_PN').prop('checked', true);
        }
        if (state_atas_nama.indexOf("2") >= 0) {
            $('#ket_pasangan_anak_div').show();
            $('#ATAS_NAMA_CHECK_PASANGAN').prop('checked', true);
            var state_pasangan_anak = '<?php echo $hutang->PASANGAN_ANAK ?>';
            if(state_pasangan_anak!==null){
                $.each(state_pasangan_anak.split(","), function(i,e){
                    $('#KET_PASANGAN_ANAK option[value="'+e+'"]').prop('selected', true); 
                }); 
            } 
        }
        $('select').select2(); 
        if (state_atas_nama.indexOf("3") >= 0) {
            var state_ket_lainnya = '<?php echo $hutang->KET_LAINNYA ?>';
            $('#ket_lainnya_an_div').show();
            $('#KET_LAINNYA_AN').val(state_ket_lainnya);
            $('#ATAS_NAMA_CHECK_LAINNYA').prop('checked', true);
        }


        ////////////////////MATA_UANG dan NILAI_SALDO READONLY///////////////////////////
        var id_lama = '<?php echo $hutang->ID_HUTANG ?>';
        var saldo_hutang_klarifikasi = '<?php echo $hutang->SALDO_HUTANG ?>';
        if(id_lama){
            $('#AWAL_HUTANG').attr('readonly', true);
            $("#SALDO_HUTANG_OLD").attr("readonly", true);
            $('#SALDO_HUTANG').val(numeral(saldo_hutang_klarifikasi).format('0,0').replace(/,/g, '.'));
        } else {
            $("#SALDO_HUTANG_OLD").attr("readonly", true);
        }

    });

    $('#FormHutang').bootstrapValidator({
            fields: {}
        }).on('error.form.bv', function(e, data) {

            onChangeAtasNama();

        }).on('success.form.bv', function(e, data) {
            CustomValidation();
            onChangeAtasNama();
            
            var is_atas_nama = $('input[name="ATAS_NAMA[]"]').is(':checked');

            if(is_atas_nama ==  false){  
                $('#button-saved').prop('disabled', true);
                $('.notif-atas-nama').show();
                $('.form-atas-nama').removeClass('has-success').addClass('has-error');
                return false;
            }else{ 
                $('.notif-atas-nama').hide();
                $('.form-atas-nama').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }

            var action = "/eaudit/klarifikasi/<?php echo $action; ?>"; 
           if(e.type == 'success'){
               do_submit('#FormHutang', action, 'Data Hutang Berhasil Disimpan', '#myModal');
           };
           
            return false;
        });
    
    function onChangeAtasNama(){
        $('input[name="ATAS_NAMA[]"]').on('change', function(){
                
            var is_atas_nama = $('input[name="ATAS_NAMA[]"]').is(':checked');
            if(is_atas_nama ==  false){  
                $('#button-saved').prop('disabled', true);
                $('.notif-atas-nama').show();
                $('.form-atas-nama').removeClass('has-success').addClass('has-error');
                return false;
            }else{ 
                $('.notif-atas-nama').hide();
                $('.form-atas-nama').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        });
    }

    function notif(t, at) {
		if (isDefined(at)) {
			t = t + at;
		}
		$('#ModalWarning #notif-text').text(t);
		$('#ModalWarning').modal('show');
	}

	function stf(t) {
		if (isDefined(t)) {
			setTimeout(function () {
				Loading('hide');
			}, parseInt(t * TIMEOUT_BROWSER));
		}
	}

	function success(t) {
		$('#ModalSuccess #notif-text').text(t);
		$('#ModalSuccess').modal('show');
	}

    function do_submit(form, url, text, modal) {
    if (modal) {
        $(modal).modal('hide');
    }
    $("#modal-success").removeClass("modal-lg"); 
    $("#modal-warning").removeClass("modal-lg"); 

    var ajaxTime = new Date().getTime();
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url + 'index.php' + url,
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'html',
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            Loading('hide');
        },
        success: function (data) { 
            if (data == 1) {
                success(text); 
            } else {
                notif('Mohon Maaf, Ada kesalahan pada system !!');
            }
            var totalTime = new Date().getTime() - ajaxTime; 
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
    }
    
    function Loading(t) {
		var m = document.getElementById('loader_area');
		if (t == 'hide') {
			m.style.display = "none";
		} else {
			m.style.display = "block";
		}
    }
    $("#KET_PASANGAN_ANAK").change(function() {
            CustomValidation();
     });

    function CustomValidation() {

        var is_check_pasangan_anak =  $("#ATAS_NAMA_CHECK_PASANGAN").is(':checked');
        if(is_check_pasangan_anak){ 
            var check_pasangan_anak = $('#KET_PASANGAN_ANAK').val();
            if(check_pasangan_anak == null){
                $('.notif-pasangan-anak').show();
                $('.form-pasangan-anak').removeClass('has-success').addClass('has-error');
                // $('#button-saved').prop('disabled', true);
                return false;
            }else{
                $('.notif-pasangan-anak').hide();
                $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
                // $('#button-saved').prop('disabled', false);
                return true;
            }
        }else{
            $('.notif-pasangan-anak').hide();
            $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
            return true;
        }

        return false;
    }
</script>