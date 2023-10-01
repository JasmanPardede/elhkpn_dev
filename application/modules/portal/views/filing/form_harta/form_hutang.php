<div id="ModalHarta" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormHutang">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FORM DATA HUTANG</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="ID" id="ID"/>
                        <input type="hidden" name="ID_HARTA" id="ID_HARTA"/>
                        <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                        <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_htg'); ?>
                        <select name="KODE_JENIS" id="KODE_JENIS" class="form-control" required>
                        </select>  
                    </div>
                    <div class="form-group">
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
                            <small class="help-block notif-pasangan-anak" style="color:#a94442; display:none;">Data ini wajib di isi</small>
                        </div>
                        <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                            <label>Nama Orang Lain / Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                            <textarea class="form-control input_capital" name="KET_LAINNYA_AN" id="KET_LAINNYA_AN" rows="2" required></textarea>
                            <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
                        </div>
                     <div class="form-group">
                        <label>Nama Kreditur <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_kreditur_htg'); ?>
                        <input type="text" placeholder="" id="NAMA_KREDITUR" name="NAMA_KREDITUR"  class="form-control input_capital" required/>
                    </div>
                    <div class="form-group">
                        <label>Bentuk Agunan</label> <?= FormHelpPopOver('bentuk_agunan_htg'); ?>
                        <input type="text" placeholder="" id="AGUNAN" name="AGUNAN"  class="form-control input_capital" />
                    </div> 
                    <div class="form-group">
                        <label>Nilai Awal Hutang (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_awal_hutang_htg'); ?>
                            <input type="text" placeholder="" id="AWAL_HUTANG" name="AWAL_HUTANG"  autocomplete="off" class="form-control money" required/>
                            <input type="text" style="font-weight:bold;background-color:#92d0fe" name="TERBILANG_NILAI_PEROLEHAN" id="TERBILANG_NILAI_PEROLEHAN" placeholder="" class="form-control"    required/>
                    </div> 
                     <div class="form-group">
                        <label>Nilai Saldo Hutang (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_saldo_hutang_htg'); ?>
                            <input type="text" placeholder="" id="SALDO_HUTANG" name="SALDO_HUTANG" autocomplete="off"  class="form-control money" required/>
                            <input type="text" style="font-weight:bold;background-color:#92d0fe" name="TERBILANG_NILAI_PELAPORAN" id="TERBILANG_NILAI_PELAPORAN" placeholder="" class="form-control"    required/>
                    </div>
                    <!--<div class="form-group group-1">
                        <label>Nilai Awal Hutang (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_awal_hutang_htg'); ?>
                        <div class="input-group">
                            <input type="text" placeholder="" id="AWAL_HUTANG" name="AWAL_HUTANG"  class="form-control money" required/>
                            <div class="input-group-addon" id="labelRupiah" style="font-weight:bold;padding-right:200px"></div>
                        </div>
                    </div> 
                     <div class="form-group group-1">
                        <label>Nilai Saldo Hutang (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_saldo_hutang_htg'); ?>
                        <div class="input-group">
                            <input type="text" placeholder="" id="SALDO_HUTANG" name="SALDO_HUTANG"  class="form-control money" required/>
                            <div class="input-group-addon" id="labelRupiah2" style="font-weight:bold;padding-right:200px"></div>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" id="button-saved"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

        $("#TERBILANG_NILAI_PEROLEHAN").hide();
        $("#AWAL_HUTANG").focusin(function() {
            $("#TERBILANG_NILAI_PEROLEHAN").show();
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PEROLEHAN").val(string_nominal);
            $("#AWAL_HUTANG").keyup(function(){
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PEROLEHAN").val(string_nominal);
            });

        });
        $("#AWAL_HUTANG").focusout(function() {
            $("#TERBILANG_NILAI_PEROLEHAN").hide();
        });


        $("#TERBILANG_NILAI_PELAPORAN").hide();
        $("#SALDO_HUTANG").focusin(function() {
            $("#TERBILANG_NILAI_PELAPORAN").show();
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PELAPORAN").val(string_nominal);
            $("#SALDO_HUTANG").keyup(function(){
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PELAPORAN").val(string_nominal);
            });

        });
        $("#SALDO_HUTANG").focusout(function() {
            $("#TERBILANG_NILAI_PELAPORAN").hide();
        });








        // var si_kelapkelip;
        // var si_kelapkelip2;
        // var si_kelapkelip_2;
        // var si_kelapkelip2_2;
        // $("#AWAL_HUTANG").focusout(function(){
        //         clearInterval(si_kelapkelip);
        //         clearInterval(si_kelapkelip2);
        //          $('#labelRupiah').css("background-color", "#e5e5e5");
        // });
        // function si_kelap_kelip(){
        //         clearInterval(si_kelapkelip);
        //         clearInterval(si_kelapkelip2);
        //         si_kelapkelip = setInterval(function(){
        //                 $('#labelRupiah').css("background-color", "#92d0fe");
        //             },300);
        //         si_kelapkelip2 = setInterval(function(){
        //                 $('#labelRupiah').css("background-color", "white");
        //             },600);
        //         return;
        // }
        // $("#AWAL_HUTANG").keyup(function(){
        //     var state_perolehan = $(this).val();
        //     var convert_perolehan = state_perolehan.replace(/\./g, "");
        //     var length_rupiah_sementara = convert_perolehan.length;
        //     var name_rupiah;
        //     var length_rupiah = length_rupiah_sementara -1;
        //     if(length_rupiah>=6 && length_rupiah<9){
        //         name_rupiah = "Juta";
        //         si_kelap_kelip();
        //     }else if(length_rupiah>=9 && length_rupiah<12){
        //         name_rupiah = "Milyar";
        //         si_kelap_kelip();
        //     }else if(length_rupiah>=12 && length_rupiah<15){
        //         name_rupiah = "Triliun";
        //         si_kelap_kelip();
        //     }else if(length_rupiah>=15 && length_rupiah<18){
        //         name_rupiah = "Kuadriliun";
        //         si_kelap_kelip();
        //     }else if(length_rupiah>=18 && length_rupiah<21){
        //         name_rupiah = "Kuantiliun";
        //         si_kelap_kelip();
        //     }else if(length_rupiah>=21){
        //         name_rupiah = "Sekstiliun";
        //         si_kelap_kelip();
        //     }else{
        //         clearInterval(si_kelapkelip);
        //         clearInterval(si_kelapkelip2);
        //          $('#labelRupiah').css("background-color", "#e5e5e5");
        //         name_rupiah="";
        //     }
        //     $('#labelRupiah').text(name_rupiah);
        // });
        // $("#SALDO_HUTANG").focusout(function(){
        //         clearInterval(si_kelapkelip_2);
        //         clearInterval(si_kelapkelip2_2);
        //          $('#labelRupiah2').css("background-color", "#e5e5e5");
        // });
        // function si_kelap_kelip2(){
        //         clearInterval(si_kelapkelip_2);
        //         clearInterval(si_kelapkelip2_2);
        //         si_kelapkelip_2 = setInterval(function(){
        //                 $('#labelRupiah2').css("background-color", "#92d0fe");
        //             },300);
        //         si_kelapkelip2_2 = setInterval(function(){
        //                 $('#labelRupiah2').css("background-color", "white");
        //             },600);
        //         return;
        // }
        // $("#SALDO_HUTANG").keyup(function(){
        //     var state_perolehan = $(this).val();
        //     var convert_perolehan = state_perolehan.replace(/\./g, "");
        //     var length_rupiah_sementara = convert_perolehan.length;
        //     var name_rupiah;
        //     var length_rupiah = length_rupiah_sementara -1;
        //     if(length_rupiah>=6 && length_rupiah<9){
        //         name_rupiah = "Juta";
        //         si_kelap_kelip2();
        //     }else if(length_rupiah>=9 && length_rupiah<12){
        //         name_rupiah = "Milyar";
        //         si_kelap_kelip2();
        //     }else if(length_rupiah>=12 && length_rupiah<15){
        //         name_rupiah = "Triliun";
        //         si_kelap_kelip2();
        //     }else if(length_rupiah>=15 && length_rupiah<18){
        //         name_rupiah = "Kuadriliun";
        //         si_kelap_kelip2();
        //     }else if(length_rupiah>=18 && length_rupiah<21){
        //         name_rupiah = "Kuantiliun";
        //         si_kelap_kelip2();
        //     }else if(length_rupiah>=21){
        //         name_rupiah = "Sekstiliun";
        //         si_kelap_kelip2();
        //     }else{
        //         clearInterval(si_kelapkelip_2);
        //         clearInterval(si_kelapkelip2_2);
        //          $('#labelRupiah2').css("background-color", "#e5e5e5");
        //         name_rupiah="";
        //     }
        //     $('#labelRupiah2').text(name_rupiah);
        // });






    $(document).ready(function(){
        $('#ket_lainnya_an_div').hide();
        $('#ket_pasangan_anak_div').hide();


        $('#ATAS_NAMA_CHECK_PN').click(function(){
            AtasNamaValidation();
            if($(this).is(':checked')){
            } else {
            }
        });
        $('#ATAS_NAMA_CHECK_PASANGAN').click(function(){
            AtasNamaValidation();
            if($(this).is(':checked')){
            	$('#ket_pasangan_anak_div').show();
            	$('select').select2();
            } else {
            	$('#ket_pasangan_anak_div').hide();
            	$('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
            }
        });
        $('#ATAS_NAMA_CHECK_LAINNYA').click(function(){
            AtasNamaValidation();
            if($(this).is(':checked')){
            	$('#ket_lainnya_an_div').show();
            } else {
            	$('#ket_lainnya_an_div').hide();
            	$('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
            }
        });




        
        $("#ATAS_NAMA").change(function() {
            $("#KET_LAINNYA_AN").val('');
            var isKeteranganLainnyaExists = document.getElementById("KET_LAINNYA_AN");

            if ($("#ATAS_NAMA").val() == 'LAINNYA') {
                $('#ket_lainnya_an_div').show();
                $('#FormHarta').bootstrapValidator('addField', isKeteranganLainnyaExists);
            } else {
                $('#ket_lainnya_an_div').hide();
                $('.notif-ket-lainnya').hide();
                $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
            }
        });
        
       if(STATUS == '0' || STATUS == '2' || STATUS == '7'){
            $('input[type=submit],button[type=submit]').show();
        }else{
            $('input[type=submit],button[type=submit]').hide();
        }

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        var list_pasangan_anak = load_html('portal/data_harta/get_pasangan_anak');
        $('#KET_PASANGAN_ANAK').html(list_pasangan_anak);
        
        var list_jenis_harta = load_html('portal/data_harta/get_option_hutang/m_jenis_hutang/NAMA/ID_JENIS_HUTANG');
        $('#KODE_JENIS').html(list_jenis_harta);

        $('select').select2();
        
        $('.date').datetimepicker({
            format: "DD/MM/YYYY",
//            maxDate: 'now'
        });

        $('.date').datetimepicker('option', {maxDate: 'now'});

        $('#ID_LHKPN').val(ID_LHKPN);

        $(function () {
        $('.over').popover();
        $('.over')
                .mouseenter(function (e) {
                    $(this).popover('show');
                })
                .mouseleave(function (e) {
                    $(this).popover('hide');
                });
    });
       $('.money').mask('000.000.000.000.000', {reverse: true});
      $(".input").maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
        // $('#FormHutang').submit(function(){
        //     $('.input').maskMoney('unmasked')[0];
        //     var ID = $('#ID').val();
        //     var text;
        //     if(ID==''){
        //         text = 'Data Hutang Berhasil Di Tambahkan';
        //     }else{
        //         text = 'Data Hutang Berhasil Di Update';
        //     }
        //     do_submit('#FormHutang','portal/data_harta/update_hutang',text,'#ModalHarta');
        //     $('#TableHutang').DataTable().ajax.reload();
        //     return false;
        // });
        
         $('#FormHutang').bootstrapValidator({
            fields: {
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
            AtasNamaValidation();
        }).on('success.form.bv', function(e) {
             e.preventDefault();
             AtasNamaValidation();
            var atas_nama = $('.notif-atas-nama').is(":visible");
            var check_validation = CustomValidation();
             if(check_validation == false){
                  $('#FormHutang').removeClass('has-error').addClass('has-success');
                //   $('#button-saved').prop('disabled', false);
                 return;
             }
             $('.input').maskMoney('unmasked')[0];
             var ID = $('#ID').val();
            var text;
            if(ID==''){
                text = 'Data Hutang berhasil ditambahkan';
            }else{
                text = 'Data Hutang berhasil diperbaharui';
            }
            if (($('#AWAL_HUTANG').val() == 0 || $('#AWAL_HUTANG').val() == '0') || ($('#SALDO_HUTANG').val() == 0 || $('#SALDO_HUTANG').val() == '0') ){
                notif('Maaf, Isian nilai awal hutang / saldo hutang Anda 0');
            } else {
                do_submit('#FormHutang','portal/data_harta/update_hutang',text,'#ModalHarta');
            }
            $('#TableHutang').DataTable().ajax.reload(null,false);

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

        $('#ModalHarta').modal({
            backdrop: 'static',
            keyboard: false,
            show:true
        });
        
        if ($("#ATAS_NAMA").val() != 'LAINNYA') {
            $('.notif-ket-lainnya').hide();
            $('.ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
        } else {
            $('.notif-ket-lainnya').show();
            $('.ket_lainnya_an_div').removeClass('has-success').addClass('has-error');
        }

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

    });


    $("#NAMA_KREDITUR").change(function() {
            CustomValidation();
     });

     $("#AGUNAN").change(function() {
            CustomValidation();
     });

     $("#AWAL_HUTANG").change(function() {
            CustomValidation();
     });

     $("#SALDO_HUTANG").change(function() {
            CustomValidation();
     });

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
                return false;
            }else{
                $('.notif-pasangan-anak').hide();
                $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
                return true;
            }
        }else{
            $('.notif-pasangan-anak').hide();
            $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
            return true;
        }
    }




function terbilang(bilangan) {

 bilangan    = String(bilangan);
 var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
 var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
 var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

 var panjang_bilangan = bilangan.length;

 /* pengujian panjang bilangan */
 if (panjang_bilangan > 15) {
   kaLimat = "Diluar Batas";
   return kaLimat;
 }

 /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
 for (i = 1; i <= panjang_bilangan; i++) {
   angka[i] = bilangan.substr(-(i),1);
 }

 i = 1;
 j = 0;
 kaLimat = "";


 /* mulai proses iterasi terhadap array angka */
 while (i <= panjang_bilangan) {

   subkaLimat = "";
   kata1 = "";
   kata2 = "";
   kata3 = "";

   /* untuk Ratusan */
   if (angka[i+2] != "0") {
     if (angka[i+2] == "1") {
       kata1 = "Seratus";
     } else {
       kata1 = kata[angka[i+2]] + " Ratus";
     }
   }

   /* untuk Puluhan atau Belasan */
   if (angka[i+1] != "0") {
     if (angka[i+1] == "1") {
       if (angka[i] == "0") {
         kata2 = "Sepuluh";
       } else if (angka[i] == "1") {
         kata2 = "Sebelas";
       } else {
         kata2 = kata[angka[i]] + " Belas";
       }
     } else {
       kata2 = kata[angka[i+1]] + " Puluh";
     }
   }

   /* untuk Satuan */
   if (angka[i] != "0") {
     if (angka[i+1] != "1") {
       kata3 = kata[angka[i]];
     }
   }

   /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
   if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
     subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
   }

   /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
   kaLimat = subkaLimat + kaLimat;
   i = i + 3;
   j = j + 1;

 }

 /* mengganti Satu Ribu jadi Seribu jika diperlukan */
 if ((angka[5] == "0") && (angka[6] == "0")) {
   kaLimat = kaLimat.replace("Satu Ribu","Seribu");
 }

 return kaLimat + "Rupiah";
}

</script>