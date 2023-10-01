<!---HARTA TIDAK BERGERAK -->
<style type="text/css">
    .help-block-red{
        color: #c93840;
    }
</style>
<form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-4">
            <input type="hidden" name="ID" id="ID" value='<?php echo $id; ?>' />
            <div class="form-group">
                <label>Negara Asal <span class="red-label">*</span> </label>
                <select name="NEGARA" id="NEGARA" class="form-control" required>
                    <option></option>
                    <!-- <option value="1" <?php echo !$onAdd && $harta->NEGARA == '1' ? 'selected' : ''; ?>>INDONESIA</option>
                    <option value="2" <?php echo !$onAdd && $harta->NEGARA == '2' ? 'selected' : ''; ?>>LUAR NEGERI</option> -->
                    <option value="1">INDONESIA</option>
                    <option value="2">LUAR NEGERI</option>
                </select>
            </div>
            <div class="form-group luar form-negara">
            <label>Negara <span class="red-label">*</span> </label>
            <input type="text" name="ID_NEGARA" id="ID_NEGARA" class="form-control" required/>
            </div>
            <div class="form-group lokal form-prov">
                <label>Provinsi <span class="red-label">*</span> </label>
                <input type="text" name="PROV" id="NAMA_PROV" class="form-control"/>
            </div>
<!--             <div class="form-group lokal form-prov"> -->
<!--                 <label>Provinsi <span class="red-label">*</span> </label> -->
<!--                 <input type="text" name="ID_PROV" id="PROV" class="form-control" required="" /> -->
   <!--                <input type="text" name="PROV" id="NAMA_PROV" class="form-control" value='<?php echo!$onAdd ? $harta->PROV : ''; ?>' />
          </div> -->
            <div class="form-group lokal form-kota">
                <label>Kabupaten/Kota <span class="red-label">*</span> </label>
                <input type="text" name="KAB_KOT" id="KAB_KOT" class="form-control input_capital" value='<?php echo!$onAdd ? $harta->KAB_KOT : ''; ?>' />
            </div>
            <div class="form-group lokal">
                <label>Kecamatan <span class="red-label">*</span> </label>
                <input type="text" id="KEC" name="KEC" class="form-control input_capital"  value='<?php echo!$onAdd ? $harta->KEC : ''; ?>' />
            </div>
            <div class="form-group lokal">
                <label>Desa/Kelurahan <span class="red-label">*</span> </label>
                <input type="text" name="KEL" id="KEL" class="form-control input_capital "  value='<?php echo!$onAdd ? $harta->KEL : ''; ?>' />
            </div>
            <div class="form-group alamat">
                <label class="lbl_alamat">Jalan <span class="red-label">*</span> </label>
                <textarea class="form-control input_capital" name="JALAN" id="JALAN" rows="2" required ><?php echo!$onAdd ? $harta->JALAN : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Luas Tanah / Bangunan </label>
                <div style="overflow:hidden; clear:both;">
                    <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_TANAH" id="LUAS_TANAH" onkeypress="return fun_AllowOnlyAmountAndDot(event,this.id);" class="form-control" <?php echo strlen($harta->LUAS_TANAH) > 0 || strlen($harta->LUAS_BANGUNAN) > 0 ? "" : "required"; ?> value='<?php echo!$onAdd ? $harta->LUAS_TANAH : '0'; ?>' />
                    <label style="display:inline;">m<sup>2</sup></label>
                    <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_BANGUNAN" id="LUAS_BANGUNAN" onkeypress="return fun_AllowOnlyAmountAndDot(event,this.id);" class="form-control" <?php echo strlen($harta->LUAS_BANGUNAN) > 0 || strlen($harta->LUAS_TANAH) > 0 ? "" : "required"; ?> value='<?php echo!$onAdd ? $harta->LUAS_BANGUNAN : '0'; ?>' />
                    <label style="display:inline;">m<sup>2</sup></label>
                </div>
                <small class="help-block-red"><i>Gunakan tikik (.) untuk angka desimal, maksimal 2 angka di belakang titik</i></small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Jenis Bukti <span class="red-label">*</span> </label>
                <select name="JENIS_BUKTI" id="JENIS_BUKTI" class="form-control" required>
                </select>
            </div>
            <div class="form-group">
                <label>Nomor Bukti <span class="red-label">*</span> </label>
                <input type="text" name="NOMOR_BUKTI" id="NOMOR_BUKTI" placeholder="" class="form-control input_capital" required value='<?php echo!$onAdd ? $harta->NOMOR_BUKTI : ''; ?>' />
            </div>
            <div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label>
                <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $harta->JENIS_BUKTI == '1' ? "selected" : ""; ?>>PN YANG BERSANGKUTAN</option>
                    <option value="2" <?php echo $harta->JENIS_BUKTI == '2' ? "selected" : ""; ?>>PASANGAN / ANAK</option>
                    <option value="3" <?php echo $harta->JENIS_BUKTI == '3' ? "selected" : ""; ?>>LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo!$onAdd ? $harta->ATAS_NAMA_LAINNYA : ''; ?>' />
            </div>
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label>
                <table class="table" id="table-asal-usul">
                </table>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pemanfaatan<span class="red-label">*</span> </label>
                <table class="table" id="table-pemanfaatan">
                </table>
            </div>
            <div class="form-group">
                <label>Nilai Perolehan (Rp) <span class="red-label">*</span> </label>
                <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" placeholder="" class="form-control money"    required value='<?php echo!$onAdd ? $harta->NILAI_PEROLEHAN : ''; ?>' />
            </div>
            <div class="form-group">
                <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label>
                <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" placeholder="" class="form-control money" required value='<?php echo!$onAdd ? $harta->NILAI_PELAPORAN : ''; ?>' />
            </div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker10'>
                    <label>Tahun Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                    <input type="text" name="TAHUN_PEROLEHAN_AWAL" id="TAHUN_PEROLEHAN_AWAL"  class="form-control year" required value='<?php echo!$onAdd ? $harta->TAHUN_PEROLEHAN_AWAL : ''; ?>'/>  
                </div>
            </div> 
                 
        </div>
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit"  id="button-saved"  class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> <i class="fa fa-remove"></i> Batal</button>
    </div>
    <?php
    /////////////sampai sini, mengirim value PROV
    // if($harta->PROV!=null){
    //   echo '<input type="text" value="'. $harta->PROV.'" name="PROV" id="NAMA_PROV"/>';
    // }
    ?>
</form>

<!---END HARTA TIDAK BERGERAK -->
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js"></script>

<script type="text/javascript">


    function fun_AllowOnlyAmountAndDot(event,txt)
        {
            if(event.charCode > 47 && event.charCode < 58 || event.charCode == 46)
            {
                var txtbx=document.getElementById(txt);
                var amount = document.getElementById(txt).value;
                var present=0;
                var count=0;

                if(amount.indexOf(".",present)||amount.indexOf(".",present+1));
                {
                }

                do
                {
                    present=amount.indexOf(".",present);
                    if(present!=-1)
                    {
                        count++;
                        present++;
                    }
                }
                while(present!=-1);

                if(present==-1 && amount.length==0 && event.keyCode == 46)
                {
                    event.charCode=0;
                    return false;
                }

                if(count>=1 && event.charCode == 46)
                {
                    event.charCode=0;
                    //alert("Only one decimal point is allowed !!");
                    return false;
                }

                if(count==1)
                {
                    var lastdigits=amount.substring(amount.indexOf(".")+1,amount.length);
                    if(lastdigits.length>=2)
                    {
                        //alert("Two decimal places only allowed");
                        event.charCode=0;
                        return false;
                    }
                }
                return true;
            }

            else
            {
                event.charCode=0;
                //alert("Only Numbers with dot allowed !!");
                return false;
            }

        }


      $('.luar').hide();

// $('#ATAS_NAMA_LAINNYA').prop('required', true);
//         } else {
//             $('#ATAS_NAMA_LAINNYA').removeAttr('required');
// $('#NILAI_KURS').attr('readonly', true);

        $('#NEGARA').change(function() {
            var val = $(this).val();
            if (val == '1') {
                $('#PROV').select2('data', null);
//                 GetKota(0);
                $('.luar').hide();
                $('.lokal').fadeIn('slow');
                $('#NAMA_PROV').attr('required', true);
                $('#KAB_KOT').attr('required', true);
                $('#KEC').attr('required', true);
                $('#KEL').attr('required', true);
                $('#ID_NEGARA').removeAttr('required');
            } else if (val == '2') {
                console.log('change negara');
                $('.lokal').hide();
                $('.luar').fadeIn('slow');
                $('#ID_NEGARA').attr('required', true);
                $('#NAMA_PROV').removeAttr('required');
                $('#KAB_KOT').removeAttr('required');
                $('#KEC').removeAttr('required');
                $('#KEL').removeAttr('required');
            }
        });


    var resetLuasTanahLuasBangunan = function () {
        $("#LUAS_BANGUNAN").attr("required", "required");
        $("#LUAS_TANAH").attr("required", "required");
    }

    var CheckLuasTanahLuasBangunan = function () {
        var lBangunan = checkLuasBangunan($("#LUAS_BANGUNAN"), "i");
        var lTanah = checkLuasBangunan($("#LUAS_TANAH"), "i");

        if (lBangunan || lTanah) {
            $("#LUAS_BANGUNAN").attr("required", false);
            $("#LUAS_TANAH").attr("required", false);
        }

    }

    var checkLuasBangunan = function (self, i) {
        var valLuasBangunan = $(self).val();

        if (isDefined(i)) {
            return !isEmpty(valLuasBangunan);
        }
    };

    var checkLuasTanah = function (self, i) {
        var valLuasTanah = $(self).val();

        if (isDefined(i)) {
            return !isEmpty(valLuasTanah);
        }
    };

    var VALUE_NAMA_FORM = '<?php echo $harta->PROV; ?>';
    $(document).ready(function () {



        $("#LUAS_BANGUNAN").change(function () {
            var lBangunan = checkLuasBangunan(this, "i");
            var lTanah = checkLuasBangunan(this, "i");

            if (!(lBangunan || lTanah)) {
                resetLuasTanahLuasBangunan();
            }

        });
//
        $("#LUAS_TANAH").change(function () {
            var lBangunan = checkLuasBangunan(this, "i");
            var lTanah = checkLuasBangunan(this, "i");

            if (!(lBangunan || lTanah)) {
                resetLuasTanahLuasBangunan();
            }
        });
        
        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker({
            useCurrent: false, /*ab membuat nilai false pada default value di text box*/  
            viewMode: 'years',
            format: "YYYY",
            maxDate: 'now'
        }).on('dp.change dp.show',function(){ 
        });
 
        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker('option', {maxDate: 'now'});          

        var list_jenis_bukti = load_html('portal/data_harta/get_jenis_bukti_with_data/1', '<?php echo!$onAdd ? $harta->JENIS_BUKTI : ''; ?>');
        $('#JENIS_BUKTI').html(list_jenis_bukti);

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo!$onAdd ? $harta->ASAL_USUL : ''; ?>');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        var list_pemanfaatan = load_html('portal/data_harta/get_pemanfaatan_with_data/1', '<?php echo!$onAdd ? $harta->PEMANFAATAN : ''; ?>');
        $('#table-pemanfaatan').html('<tbody>' + list_pemanfaatan + '</tbody>');

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

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });


        $('#ID_NEGARA').select2({
            //placeholder: "Pilih Negara",
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_harta/getnegara',
                dataType: 'json',
                quietMillis: 100,
                data: function(term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function(data) {
                    var myResults = [];
                    $.each(data, function(index, item) {
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
//             CustomValidation();
        });


        $('#NAMA_PROV').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/filing/getprovinsi',
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
        }).on("change", function (e) {
            var ID_PROVINSI = $('#NAMA_PROV').val();
            GetProvinsi(ID_PROVINSI);
        });


// $('#ATAS_NAMA_LAINNYA').prop('required', true);
//         } else {
//             $('#ATAS_NAMA_LAINNYA').removeAttr('required');
// $('#NILAI_KURS').attr('readonly', true);

        var negara = '<?php echo $harta->NEGARA ?>';
        var prof = '<?php echo $harta->PROV ?>';
  	    $('#NEGARA').val(negara);
        if($('#NEGARA').val()==1){
            console.log('masuk datnya');
          // $('#NAMA_PROV').select2('data', {id: prof, text: prof});
          // $('#NAMA_PROV').val(prof);
          $('.luar').hide();
          $('.lokal').fadeIn('slow');
          $('#NAMA_PROV').attr('required', true);
            $('#KAB_KOT').attr('required', true);
            $('#KEC').attr('required', true);
            $('#KEL').attr('required', true);
            $('#ID_NEGARA').removeAttr('required');
        }else if ($('#NEGARA').val()==2){
          var id_negara = '<?php echo $harta->ID_NEGARA ?>';
          var id_negara_nama = '<?php echo $negara_nama ?>';
          $('.lokal').hide();
          $('.luar').fadeIn('slow');
          // $('#ID_NEGARA').val(id_negara);
          $('#ID_NEGARA').select2("data", {id: id_negara, text: id_negara_nama});
          $('#ID_NEGARA').attr('required', true);
            $('#NAMA_PROV').removeAttr('required');
            $('#KAB_KOT').removeAttr('required');
            $('#KEC').removeAttr('required');
            $('#KEL').removeAttr('required');
        } else {
            $('.lokal').hide();
            $('.luar').hide();
        }
        
//         $('select').select2();
//         var cfg_prov_select = {
//         ajax: {
//         placeholder: 'Provinsi',
//                 method: 'post',
//                 width: '100%',
  //              url: '<?php echo base_url(); ?>portal/filing/getprovinsi',
//                 dataType: 'json',
//                 data: function (term, page) {
//                 return {
//                 q: term, // search term
// //                        pageLimit: 10,
// //                        page: page
//                 };
//                 },
//                 cache: true,
//                 results: function (data, params) {
//                 var myResults = [], more = (params.page * 10) < data.total;
// //                    $.each(data.province, function(index, item) {
//                         $.each(data, function (index, item) {
//                         myResults.push({
//                         'id': item.id,
//                                 'text': item.text
//                         });
//                         });
//                         return {
//                         results: myResults,
// //                        more: more
//                         };
//                 },
//                 minimumInputLength: 3
//         },
// <?php if ($harta->ID_PROV != NULL || $harta->ID_PROV != ''): ?>
//             initSelection: function (element, callback) {

  //          return callback({ id: '<?php echo $harta->ID_PROV; ?>', text: '<?php echo strtoupper($harta->PROV); ?>' });
//             }
// <?php endif; ?>
//         };

//         $('#PROV').select2(cfg_prov_select);

// <?php if ($harta->ID_PROV != NULL || $harta->ID_PROV != ''): ?>
  //          $("#PROV").val('<?php echo $harta->ID_PROV; ?>').trigger("change");
// <?php endif; ?>


//        $('.money').mask('000.000.000.000.000.000', {reverse: true});

        // $('.date').datepicker({  /*ab ini yang ngebuat error tampilan
        //     formatDate: "DD/MM/YYYY",
//            maxDate: 'now'
        // });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li4&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});
        $('#ket_lainnya_an_div').hide();
        $("#ATAS_NAMA").change(function() {
            $("#ATAS_NAMA_LAINNYA").val('');

            if ($("#ATAS_NAMA").val() == '3') {
                $('#ket_lainnya_an_div').show();
                $('#ATAS_NAMA_LAINNYA').prop('required', true);
            } else {
                $('#ATAS_NAMA_LAINNYA').removeAttr('required');
                $('#ket_lainnya_an_div').hide();
            }
        });
        var get_id_prov = '<?php echo $harta->ID_PROV; ?>';
        var get_name_prov = '<?php echo $harta->PROV; ?>';
        var get_name_kabkot = '<?php echo $harta->KAB_KOT; ?>';
        console.log(get_name_prov);
        if(get_id_prov==null || get_id_prov=="" || get_id_prov==0){
            if(get_name_prov!=""){
            alert('mohon isi kembali data provinsi! data provinsi sebelumnya adalah "'+get_name_prov+'" dan data kabupaten/kota adalah "'+get_name_kabkot+'"');
            }
        }else{
      		 $('#NAMA_PROV').select2("data", {id: get_id_prov, text: VALUE_NAMA_FORM});
        } 
    });

    function GetProvinsi(ID_PROVINSI) {
    	$('#KAB_KOT').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/filing/GetKota/'+ID_PROVINSI,
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
                            'id': item.text,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
//             var ID_PROVINSI = $('#PROV').val();
//             alert(ID_PROVINSI);
//             GetUK(ID_LEMBAGA);
        });




//         $('#uk').select2({
//             allowClear: true,
//             ajax: {
//                url: '<?php echo base_url(); ?>portal/data_jabatan/getuk/' + ID_LEMBAGA,
//                 dataType: 'json',
//                 quietMillis: 100,
//                 data: function (term) {
//                     return {
//                         q: term, // search term
//                     };
//                 },
//                 results: function (data) {
//                     var myResults = [];
//                     $.each(data, function (index, item) {
//                         myResults.push({
//                             'id': item.id,
//                             'text': item.text
//                         });
//                     });
//                     return {
//                         results: myResults
//                     };
//                 },
//                 minimumInputLength: 3
//             }
//         }).on("change", function (e) {
//             var ID_UK = $('#uk').val();
//             SubUK(ID_UK);
//             GetJabatan(ID_UK);
//         });
    };
</script>