<form role="form" id="FormHarta">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HARTA BERGERAK LAINNYA</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-6">
            <input type="hidden" name="is_load" value="<?php echo $onAdd ? $harta->Previous_ID : ''; ?>">
            <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?> " />
			<input type="hidden" name="ID_LHKPN" value='<?php echo $ID_LHKPN; ?>' id="ID_LHKPN"/>
            <div class="form-group">
                <label>Jenis <span class="red-label">*</span></label>
                <select name="KODE_JENIS" id="KODE_JENIS" class="form-control" required></select>  
            </div>
            <div class="form-group">
                <label>Jumlah <span class="red-label">*</span></label>
                <input type="text" name="JUMLAH" id="JUMLAH" class="form-control" required value="<?php echo $onAdd ? str_replace('.', ',', $harta->JUMLAH) : ''; ?>" onkeypress="return allowOnlyAmountAndComma(this.id);" />
            </div>
            <div class="form-group">
                <label>Satuan <span class="red-label">*</span></label>
                <input type="text"  name="SATUAN" id="SATUAN" class="form-control input_capital" required value="<?php echo $onAdd ? $harta->SATUAN : ''; ?>" />   
            </div>
            <div class="form-group">
                <label>Keterangan Harta</label>
                <textarea class="form-control" name="KETERANGAN" id="KETERANGAN" rows="2"><?php echo $onAdd ? $harta->KETERANGAN : ''; ?></textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label>
                <table class="table" id="table-asal-usul" required></table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
            <div class="form-group">
                <label>Nilai Perolehan (Rp)<span class="red-label">*</span></label>
                <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" class="form-control money" required value="<?php echo $onAdd ? $harta->NILAI_PEROLEHAN : ''; ?>" <?php //echo $is_load ? 'readonly' : '' ?> />   
            </div>
            <div class="form-group">
                <label>Nilai Pelaporan (Rp) <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_hbl'); ?>
                <input type="text" name="NILAI_PELAPORAN_OLD" id="NILAI_PELAPORAN_OLD" class="form-control money" required value="<?php echo $onAdd ? $harta->NILAI_PELAPORAN_OLD : '0'; ?>" <?php echo $is_load ? 'readonly' : 'readonly' ?> />
            </div>
            <div class="form-group">
                <label>Nilai Klarifikasi (Rp) <span class="red-label">*</span> </label>
                <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" placeholder="" class="form-control money" required value="<?php echo $onAdd ? $harta->NILAI_PELAPORAN : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Keterangan Pemeriksaan <span class="red-label">*</span> </label>
                <textarea class="form-control" name="KET_PEMERIKSAAN" id="KET_PEMERIKSAAN" rows="2" placeholder="Keterangan" required ><?php echo $onAdd ? $harta->KET_PEMERIKSAAN : ''; ?></textarea>
            </div>
        </div>
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit" id="button-saved" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
    </div>
</form>

<form role="form" id="formAsalUsul">
    <div >
        <h4 class="modal-title" id="asal_usul_title"></h4>
    </div>
    <div class="modal-body row">
        <div class="form-group">
            <input type="hidden" id="id_checkbox"/>
            <label>Tanggal Transaksi <span class="red-label">*</span></label> <?= FormHelpPopOver('tgl_transaksi_popup'); ?>
            <div class="input-group date">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </div>
                <input type="text" name="tgl_transaksi_asal"  id="tgl_transaksi_asal" placeholder="( tgl/bulan/tahun )" class="form-control" required/>
            </div>
        </div>
        <div class="form-group">
            <label id="label-info">Nilai <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_popup'); ?>
            <input type="text" name="besar_nilai_asal" id="besar_nilai_asal"  class="form-control money" required/>
        </div>
        <div class="form-group">
            <label>Keterangan </label> <?= FormHelpPopOver('keterangan_popup'); ?>
            <textarea class="form-control" name="keterangan_asal" id="keterangan_asal" rows="2" ></textarea>
        </div>
        <div class="form-group">
            <label><strong>Pihak Kedua</strong></label> 
        </div>
        <div class="form-group">
            <label>Nama <span class="red-label">*</span></label> <?= FormHelpPopOver('nama_pihak_kedua_popup'); ?>
            <input type="text" name="nama_pihak2_asal" id="nama_pihak2_asal"  class="form-control input_capital" required />
        </div>
        <div class="form-group">
            <label>Alamat <span class="red-label">*</span></label> <?= FormHelpPopOver('alamat_pihak_kedua_popup'); ?>
            <textarea class="form-control" name="alamat2_asal" id="alamat2_asal" rows="2" required></textarea>
        </div>
        
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" onclick="Cancelparent()"><i class="fa fa-remove"></i> Batal</button>
    </div>
</form>
<!---END HARTA TIDAK BERGERAK -->
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js?v=<?php echo rand(4, 80); ?>"></script>
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

	function Cancelparent() {
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
                    //alert("Only one decimal point is allowed !!");
                    return false;
                }

                if(count==1)
                {
                    var lastdigits=amount.substring(amount.indexOf(",")+1,amount.length);
                    if(lastdigits.length>=2)
                    {
                        //alert("Two decimal places only allowed");
                        event.keyCode=0;
                        return false;
                    }
                }
                return true;
            }

            else
            {
                event.keyCode=0;
                //alert("Only Numbers with dot allowed !!");
                return false;
            }

        }
    $(document).ready(function () {

        var list_jenis_harta = load_html('eaudit/klarifikasi/get_jenis_harta_with_data/3');
        $('#KODE_JENIS').html(list_jenis_harta);
        
       //var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo $onAdd ? $harta->ASAL_USUL : ''; ?>');
       //$('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');
		
		$('#tgl_transaksi_asal').datetimepicker({
            format: "DD/MM/YYYY",
//            maxDate: TGL_LAPOR
        });
		
		// var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data_p/3', '{"asal_usul" : [<?php echo $onAdd ? $harta->ASAL_USUL : ''; ?>],  "ID" : <?php echo $harta->ID ?>}' ); 
		// $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>'); 
        
        ///////////////////////ASAL_USUL//////////////////////////
        var list_asal_usul = load_html('eaudit/klarifikasi/get_asal_usul_with_data_p/3', '{"asal_usul" : [<?php echo $onAdd ? $harta->ASAL_USUL : ''; ?>],  "ID" : <?php echo $harta->ID ?>}' );
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');
        var state_asal_usul = '<?php echo $harta->ASAL_USUL ?>';
        if (state_asal_usul.indexOf("1") >= 0) {
            $('#pilih-hasil-sendiri').prop('checked', true);
        }
        if (state_asal_usul.indexOf("2") >= 0) {
            $('#pilih-warisan').prop('checked', true);
        }
        if (state_asal_usul.indexOf("3") >= 0) {
            $('#pilih-hibah-dengan-akta').prop('checked', true);
        }
        if (state_asal_usul.indexOf("4") >= 0) {
            $('#pilih-hibah-tanpa-akta').prop('checked', true);
        }
        if (state_asal_usul.indexOf("5") >= 0) {
            $('#pilih-hadiah').prop('checked', true);
        }
        if (state_asal_usul.indexOf("6") >= 0) {
            $('#pilih-lainnya').prop('checked', true);
        }


		$('#formAsalUsul').hide();
        $('#myModal .pilih').click(function() {
            var id = $(this).attr('id'); 
            var ID = $('#ID').val(); 
            var title = GetTitle(id); 
            if ($(this).is(':checked') && $(this).hasClass("order-1")) { 
                $('#formAsalUsul')[0].reset();
                view(id, title);
                $('.valid-asal').hide();
            } else { 
                $('#result-' + id).html('');
                $('#view-' + id).html('');
                $('.valid-asal').show();
            }
            //CustomValidation();
        });

		$("body").on('click', '.btn-view', function (event) { 
            var id_btn = $(this).attr('id'); 
            var id_checkbox = id_btn.replace("view-to-", ""); 
            var title = GetTitle(id_checkbox);
            var v1 = $('#asal-tgl_transaksi-' + id_checkbox).val(); 
            var v2 = $('#asal-besar_nilai-' + id_checkbox).val();
            var v3 = $('#asal-keterangan-' + id_checkbox).val();
            var v4 = $('#asal-pihak2_nama-' + id_checkbox).val();
            var v5 = $('#asal-pihak2_alamat-' + id_checkbox).val();
            $('#tgl_transaksi_asal').val(v1);
            $('#besar_nilai_asal').val(v2);
            $('#keterangan_asal').val(v3);
            $('#nama_pihak2_asal').val(v4);
            $('#alamat2_asal').val(v5);
            view(id_checkbox, title);
        });
		
        $('#formAsalUsul').submit(function() {
            $('.input').maskMoney('unmasked')[0];
            var tgl_transaksi_asal = $('#tgl_transaksi_asal').val();
            var besar_nilai_asal = $('#besar_nilai_asal').val();
            var keterangan_asal = $('#keterangan_asal').val();
            var nama_pihak2_asal = $('#nama_pihak2_asal').val();
            var alamat2_asal = $('#alamat2_asal').val();
            var title = $('#asal_usul_title').text();

            $('#formAsalUsul').fadeOut('fast', function() {
                $('#FormHarta').fadeIn('fast', function() {
                    $('#myModal .modal-content').animate({
                        'width': '100%',
                        'margin-left': '0'
                    });
                    var id_checkbox = $('#id_checkbox').val();
                    $('#view-' + id_checkbox).html('<a href="javascript:void(0)" id="view-to-' + id_checkbox + '" class="btn btn-view btn-xs btn-info">Lihat</a>');
                    $('#result-' + id_checkbox).html('<label class="label label-primary">' + besar_nilai_asal + '</label>');
                    $('#asal-tgl_transaksi-' + id_checkbox).val(tgl_transaksi_asal);
                    $('#asal-besar_nilai-' + id_checkbox).val(besar_nilai_asal);
                    $('#asal-keterangan-' + id_checkbox).val(keterangan_asal);
                    $('#asal-pihak2_nama-' + id_checkbox).val(nama_pihak2_asal);
                    $('#asal-pihak2_alamat-' + id_checkbox).val(alamat2_asal);
                    $('.valid-asal').hide();
                    //CustomValidation();
                });
            });
            return false;
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
            AsalUsulValidation();
        }).on('success.form.bv', function(e, data) {
            CustomValidation();
            AsalUsulValidation();

            var pilih_asal_usul = $("[name='ASAL_USUL[]']:checked").is(':checked');
            if (pilih_asal_usul) {
                $('.notif-asal').hide();
                $('#button-saved').prop('disabled', false);
            } else {
                $('.notif-asal').show();
                $('#button-saved').prop('disabled', true);
                return false;
            }

            var action = "/eaudit/klarifikasi/<?php echo $action; ?>";
           
           if(e.type == 'success'){
               do_submit('#FormHarta', action, 'Data Berhasil Disimpan', '#myModal');
           };

            /*
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
                        do_submit('#FormHarta', '/eaudit/klarifikasi/do_update_harta_bergerak_lain/edit', text, '#myModal');
                    }
                } else if ($('#NILAI_PELAPORAN').val() == 0 || $('#NILAI_PELAPORAN').val() == '0'){
                    notif('Maaf, Isian nilai estimasi pelaporan harta Anda 0');
                } else {
                    do_submit('#FormHarta', '/eaudit/klarifikasi/do_update_harta_bergerak_lain/new', text, '#myModal');
                }
                //$('#TableHBL').DataTable().ajax.reload(null,false);
				// location.reload();
                var url = location.href.split('#')[1];
                url = url.split('?')[0] + '?upperli=li3&bottomli=lii2';
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

    function AsalUsulValidation() {
        $('input[name="ASAL_USUL[]"]').on('change', function(){
            var pilih_asal_usul = $("[name='ASAL_USUL[]']:checked").is(':checked');
            if (pilih_asal_usul) {
                $('.notif-asal').hide();
                $('#button-saved').prop('disabled', false);
            } else {
                $('.notif-asal').show();
                $('#button-saved').prop('disabled', true);
                return false;
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
	
	

	function view(id, title) {
        $('#FormHarta').hide();
        $('#formAsalUsul').fadeIn('fast', function () {
            $('#asal_usul_title').text('ASAL USUL ' + title.toUpperCase());
            $('#label-info').html('Besar Nilai (Rp) <span class="red-label">*</span>');
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
        
        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li3&bottomli=lii2';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});

        ////////////////////KODE_JENIS///////////////////////////
        var id_kode_jenis = '<?php echo $harta->KODE_JENIS ?>';
        $('#KODE_JENIS').val(id_kode_jenis).trigger('change');
    });
</script>