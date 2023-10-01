
<form role="form" id="FormHarta">
    <div class="modal-body row">
        <div class="col-sm-6">
            <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="<?php echo $ID_LHKPN; ?>" />
            <input type="hidden" name="ID_HARTA" id="ID_HARTA" value='<?php echo $ID_HARTA; ?>' />
            <input type="hidden" name="TABLE" id="TABLE" value='<?php echo $TABLE; ?>' />
            <div class="form-group group-0">
                <label>Jenis Pelepasan Harta <span class="red-label">*</span> </label>
                <select class="form-control" id="JENIS_PELEPASAN_HARTA" name="JENIS_PELEPASAN_HARTA" required>
                    <option></option>
                    <?php foreach ($JENIS_PELEPASAN_HARTA as $JP): ?>
                        <option value="<?php echo $JP->ID; ?>"><?php echo $JP->JENIS_PELEPASAN_HARTA; ?></option>
                    <?php EndForeach; ?>
                </select>
            </div>
            <div class="form-group group-0">
                <label>Tanggal Transaksi<span class="red-label">*</span> </label>
                <input type="text" onkeydown="return false" autocomplete="off" id="TANGGAL_TRANSAKSI" name="TANGGAL_TRANSAKSI" class="form-control tgl" required/>
            </div>
            <div class="form-group group-0">
                <label>Uraian Harta <span class="red-label">*</span> </label>
                <textarea class="form-control" name="URAIAN_HARTA" id="URAIAN_HARTA" rows="2"  required></textarea>
            </div>
            <div class="form-group group-0">
                <label>Nilai Pelepasan <span class="red-label">*</span> </label>
                <input type="text" id="NILAI_PELEPASAN" name="NILAI_PELEPASAN"  class="form-control money" value="<?= $NILAI_PELAPORAN_OLD ?>" required/>
            </div>
        
        </div>
        <div class="col-sm-6">
            <div class="form-group group-0">
                <label>Nama (Pihak Ke-2 )<span class="red-label">*</span> </label>
                <input type="text" id="NAMA" name="NAMA" class="form-control" required/>
            </div>
            <div class="form-group group-0">
                <label>Alamat (Pihak Ke-2 )<span class="red-label">*</span> </label>
                <textarea class="form-control" name="ALAMAT" id="ALAMAT" rows="2"  required ></textarea>
            </div>
     
        </div>
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit"  id="button-saved"  class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> <i class="fa fa-remove"></i> Batal</button>
    </div>
</form>


<script type="text/javascript">

// Opera 8.0+
var OPERA = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
// Firefox 1.0+
var FIREFOX = typeof InstallTrigger !== 'undefined';
// At least Safari 3+: "[object HTMLElementConstructor]"
var SAFARI = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
// Internet Explorer 6-11
var IE = /*@cc_on!@*/false || !!document.documentMode;
// Edge 20+
var EDGE = !IE && !!window.StyleMedia;
// Chrome 1+
var CHROME = !!window.chrome && !!window.chrome.webstore;
// Blink engine detection
var BLINK = (CHROME || OPERA) && !!window.CSS;

var TIMEOUT_BROWSER = 0;

if (OPERA) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (FIREFOX) {
    TIMEOUT_BROWSER = 10 / 100;
} else if (SAFARI) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (IE) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (EDGE) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (CHROME) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (BLINK) {
    TIMEOUT_BROWSER = 50 / 100;
} else {
    TIMEOUT_BROWSER = 50 / 100;
}

    function allowOnlyAmountAndComma(txt)
    {
        if(event.keyCode > 47 && event.keyCode < 58 || event.keyCode == 44)
        {
            var txtbx=document.getElementById(txt);
            var amount = document.getElementById(txt).value;
            var present=0;
            var count=0;

            if(amount.indexOf(",",present)||amount.indexOf(",",present+1));
            {
            }

            do
            {
                present=amount.indexOf(",",present);
                if(present!=-1)
                {
                    count++;
                    present++;
                }
            }
            while(present!=-1);

            if(present==-1 && amount.length==0 && event.keyCode == 44)
            {
                event.keyCode=0;
                return false;
            }

            if(count>=1 && event.keyCode == 44)
            {
                event.keyCode=0;
                return false;
            }

            if(count==1)
            {
                var lastdigits=amount.substring(amount.indexOf(",")+1,amount.length);
                if(lastdigits.length>=2)
                {
                    event.keyCode=0;
                    return false;
                }
            }
            return true;
        }

        else
        {
            event.keyCode=0;
            return false;
        }

    }

    $(document).ready(function() {



        $('.money').mask('000.000.000.000.000.000', {reverse: true});
        $('.tgl').datetimepicker({
            format: "DD/MM/YYYY",
            maxDate: 'now'
        }).on('dp.change dp.show', function (e) {
            $('#FormHarta').bootstrapValidator('revalidateField', 'TANGGAL_TRANSAKSI');    
        });
        
        $('#ModalHarta .modal-dialog').css({
            'margin-top': '5px',
            'width': '100%',
            'height': '100%'
        });

        $('#ModalHarta .form-group').css({
            'margin-bottom': '7.5px'
        });

        $('#ModalHarta .modal-footer').css({
            'padding': '10px'
        });
		
		
		$('#FormHarta').bootstrapValidator({
            fields: {
                'PEMANFAATAN[]': {
                    validators: {
                        notEmpty: {
                            message: 'Pilih Pemanfaatan Harta'
                        }
                    }
                },
                'KET_LAINNYA_AN': {
                    validators: {
                        notEmpty: {
                            message: 'Data ini wajib di isi'
                        }
                    }
                }

            }
        }).on('error.form.bv', function(e, data) {
            CustomValidation();
        }).on('success.form.bv', function(e, data) {
            CustomValidation();

            var action = "/eaudit/klarifikasi/do_pelepasan";
           
           if(e.type == 'success'){
               do_submit('#FormHarta', action, 'Harta Berhasil Dilepas', '#myModal');
           };

           /*
            console.log('masuk sini');
            var error = $('.has-error').length;
            var asal_usul = $('.notif-asal').is(":visible");
            if (error == 0 && !asal_usul) {
                e.preventDefault();
                $('.input').maskMoney('unmasked')[0];
                var ID = $('#ID').val();
                var text;
                if (ID == '') {
                    text = 'Data Harta Tanah/Bangunan berhasil ditambahkan';
                } else {
                    text = 'Data Harta Tanah/Bangunan berhasil diperbaharui';
                }
                if ((($('#status_harta').val() == '3' && $('#is_load_harta').val() == '1') || $('#status_harta').val() == '2' || $('#status_harta').val() == '1') && $('#is_pelepasan_harta').val() !== '1'){
                    if ($('#NILAI_PELAPORAN').val() == 0 || $('#NILAI_PELAPORAN').val() == '0'){
                        notif('Isian nilai estimasi pelaporan harta Anda 0, apabila anda bermaksud menghapus/melepas harta silakan gunakan tombol lepas');
                    }else{
                        do_submit('#FormHarta', '/eaudit/klarifikasi/do_pelepasan/edit', text, '#myModal');
                    }
                } else if ($('#NILAI_PELAPORAN').val() == 0 || $('#NILAI_PELAPORAN').val() == '0'){
                    notif('Maaf, Isian nilai estimasi pelaporan harta Anda 0');
                } else {
                    do_submit('#FormHarta', '/eaudit/klarifikasi/do_pelepasan/edit', text, '#myModal');
                }
                //$('#TableTanah').DataTable().ajax.reload(null,false);
				//location.reload();
                var url = location.href.split('#')[1];
                var upperli_state,bottomli_state;
                upperli_state = '<?php echo $REDIRECT['upperli'] ?>';
                bottomli_state = '<?php echo $REDIRECT['bottomli'] ?>';
                url = url.split('?')[0] + '?upperli='+upperli_state+'&bottomli='+bottomli_state;
                window.location.hash = url;
                ng.LoadAjaxContent(url);
            } */
           
            return false;
        }).on('added.field.fv', function(e, data) {
            // data.field   --> The field name
            // data.element --> The new field element
            // data.options --> The new field options

            if (data.field === 'KET_LAINNYA_AN') {
                if ($('#FormHarta').find(':visible[name="KET_LAINNYA_AN"').val() == '') {
//                    $('#surveyForm').find('.addButton').attr('disabled', 'disabled');
                }
            }
        }).on('removed.field.fv', function(e, data) { // Called after removing the field
            if (data.field === 'option[]') {
                if ($('#FormHarta').find(':visible[name="KET_LAINNYA_AN"').val() == '') {
//                    $('#surveyForm').find('.addButton').removeAttr('disabled');
                }
            }
        });
	 
        // $('#ID_LHKPN').val(ID_LHKPN);
   
				
    });
	
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
	
	function Cancelparent() { console.log('ferry call');
        $('#formAsalUsul').fadeOut('fast', function () {
            $('#FormHarta').fadeIn('fast', function () {
                $('#myModal .modal-content').animate({
                    'width': '100%',
                    'margin-left': '0'
                });
                var ID = $('#ID').val();
                var id_checkbox = $('#id_checkbox').val();
                if (ID) {
                    if ($('#view-to-' + id_checkbox).is(':visible')) {
                        $('#' + id_checkbox).prop('checked', true);
                    } else {
                        $('#' + id_checkbox).prop('checked', false);
                    }
                } else {
                    $('#' + id_checkbox).prop('checked', false);
                    $('#view-' + id_checkbox).html('');
                    $('#result-' + id_checkbox).html('');
                }

            });
        });
    }
	
	function view(id, title) {
        $('#FormHarta').hide();
        $('#formAsalUsul').fadeIn('fast', function () {
            $('#asal_usul_title').text('ASAL USUL ' + title.toUpperCase());
            $('#label-info').text('Besar Nilai (Rp)');
            $('#formAsalUsul #id_checkbox').val(id);
            $('#myModal .modal-content').animate({
                'width': '50%',
                'margin-left': '25%'
            });
        });
    }
	
	function GetTitle(id) {
        var res = id.split("-");
        var resCount = res.length;
        var arr_title = new Array();
        for (i = 0; i < resCount; i++) {
            if (i > 0) {
                arr_title[i] = res[i];
            }
        }
        return arr_title.join(" ");
    }
	
	function CustomValidation() {

        var is_check_pasangan_anak =  $("#ATAS_NAMA_CHECK_PASANGAN").is(':checked');
        if(is_check_pasangan_anak){
            var check_pasangan_anak = $('#KET_PASANGAN_ANAK').val();
            if(check_pasangan_anak == null){
                $('.notif-pasangan-anak').show();
                $('.form-pasangan-anak').removeClass('has-success').addClass('has-error');
                // $('#button-saved').prop('disabled', true);
                return;
            }else{
                $('.notif-pasangan-anak').hide();
                $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        }else{
            $('.notif-pasangan-anak').hide();
            $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
            $('#button-saved').prop('disabled', false);
        }

        var TAHUN_PEROLEHAN_AWAL = $("#TAHUN_PEROLEHAN_AWAL").val();
        if(TAHUN_PEROLEHAN_AWAL == ''){
            $('.notif-tahun-perolehan').show();
            $('.form-tahun-perolehan').removeClass('has-success').addClass('has-error');
        }else{
            $('.notif-tahun-perolehan').hide();
            $('.form-tahun-perolehan').removeClass('has-error').addClass('has-success');
        }



        var NEGARA = $('#NEGARA').val();
        if (NEGARA == '1') {
            var PROV = $('#PROV').val();
            var KAB_KOT = $('#KAB_KOT').val();
            if (PROV == '') {
                $('.notif-prov').show();
                $('.form-prov').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-prov').hide();
                $('.form-prov').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
            if (KAB_KOT == '') {
                $('.notif-kota').show();
                $('.form-kota').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-kota').hide();
                $('.form-kota').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        } else {
            var ID_NEGARA = $('#ID_NEGARA').val();
            if (ID_NEGARA == '') {
                $('.notif-negara').show();
                $('.form-negara').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-negara').hide();
                $('.form-negara').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        }

//         if ($("#ATAS_NAMA").val() != '3') {
//             console.log('dibawah');
//             $('.notif-ket-lainnya').hide();
//             $('.ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
//         } else {
//             $('.notif-ket-lainnya').show();
//             $('.ket_lainnya_an_div').removeClass('has-success').addClass('has-error');
//         }

        AsalUsulValidation();
    }

    function GetKota(id) {
        $('#KAB_KOT').select2({
            //placeholder: "Pilih Kota",
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
            var data = $('#KAB_KOT').select2('data');
            if(data) {
              $('#KAB_KOT_NAME').val(data.text);
            }
        });
    }

</script>