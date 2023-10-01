<!---HARTA TIDAK BERGERAK -->
<div id="ModalHarta" class="modal fade container-fluid" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormHarta">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FORM DATA HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)</h4>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-4">
                        <input type="hidden" name="ID" id="ID"/>
                        <input type="hidden" name="ID_HARTA" id="ID_HARTA"/>
                        <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                        <input type="hidden" name="status_harta" id="status_harta"/>
                        <input type="hidden" name="is_load_harta" id="is_load_harta"/>
                        <input type="hidden" name="is_pelepasan_harta" id="is_pelepasan_harta"/>
                        <div class="form-group">
                            <label>Negara Asal<span class="red-label">*</span> </label> <?= FormHelpPopOver('negara_asal_htb'); ?>
                            <select name="NEGARA" id="NEGARA" class="form-control" required>
                                <option></option>
                                <option value="1">INDONESIA</option>
                                <option value="2">LUAR NEGERI</option>
                            </select> 
                        </div>
                        <div class="form-group luar form-negara">
                            <label>Negara <span class="red-label">*</span> </label> <?= FormHelpPopOver('negara'); ?>
                            <input type="hidden" name="ID_NEGARA" id="ID_NEGARA" class="form-control"/>
                            <small class="help-block notif-negara" style="color:#a94442; display:none;">Data ini wajib di isi</small>
                        </div>
                        <div class="form-group lokal form-prov">
                            <label>Provinsi <span class="red-label">*</span> </label> <?= FormHelpPopOver('provinsi_htb'); ?>
                            <input type="hidden" name="PROV" id="PROV" class="form-control"/>
                            <small class="help-block notif-prov" style="color:#a94442; display:none;">Data ini wajib di isi</small>
                        </div>
                        <div class="form-group lokal form-kota">
                            <label>Kabupaten/Kota <span class="red-label">*</span> </label> <?= FormHelpPopOver('kab_htb'); ?>
                            <input type="text" name="KAB_KOT" id="KAB_KOT" class="form-control input_capital"/>
                            <small class="help-block notif-kota" style="color:#a94442; display:none;">Data ini wajib di isi</small>
                        </div>
                        <div class="form-group lokal">
                            <label>Kecamatan <span class="red-label">*</span> </label> <?= FormHelpPopOver('kec_htb'); ?>
                            <input type="text" id="KEC" name="KEC" class="form-control input_capital"required/>
                        </div>
                        <div class="form-group lokal">
                            <label>Desa/Kelurahan <span class="red-label">*</span> </label> <?= FormHelpPopOver('kel_htb'); ?>
                            <input type="text" name="KEL" id="KEL" class="form-control input_capital "required/>
                        </div>
                        <div class="form-group alamat">
                            <label class="lbl_alamat">Jalan <span class="red-label">*</span> </label> <?= FormHelpPopOver('jalan_htb'); ?>
                            <textarea class="form-control input_capital" name="JALAN" id="JALAN" rows="2" required ></textarea>
                        </div>
                        <div class="form-group">
                            <label>Luas Tanah / Bangunan <span class="red-label">*</span> </label> <?= FormHelpPopOver('luas_tanah_htb'); ?>
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
                            <label>Jenis Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_bukti_htb'); ?>
                            <select name="JENIS_BUKTI" id="JENIS_BUKTI" class="form-control" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nomor Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('no_bukti_htb'); ?>
                            <input type="text" name="NOMOR_BUKTI" id="NOMOR_BUKTI" placeholder="" class="form-control input_capital" required/>    
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
                        <div class="form-group form-asal">
                            <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_htb'); ?>
                            <table class="table" id="table-asal-usul" required>   
                            </table>
                            <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Pemanfaatan<span class="red-label">*</span> </label> <?= FormHelpPopOver('pemanfaatan_htb'); ?>
                            <table class="table" id="table-pemanfaatan" required>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Nilai Perolehan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_htb'); ?>
                            <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" autocomplete="off" placeholder="" class="form-control money"    required/>
                            <input type="text" style="font-weight:bold;background-color:#92d0fe" name="TERBILANG_NILAI_PEROLEHAN" id="TERBILANG_NILAI_PEROLEHAN" placeholder="" class="form-control"    required/>
                        </div>
                        <div class="form-group">
                            <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_htb'); ?>
                            <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" placeholder="" autocomplete="off"  class="form-control money" required/>
                            <input type="text" style="font-weight:bold;background-color:#92d0fe" name="TERBILANG_NILAI_PELAPORAN" id="TERBILANG_NILAI_PELAPORAN" placeholder="" class="form-control"    required/>
                        </div>
                        <!--<div class="form-group group-1">
                            <label>Nilai Perolehan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_htb'); ?>
                            <div class="input-group">
                            <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" autocomplete="off" placeholder="" class="form-control money"    required/>
                            <div class="input-group-addon" id="labelRupiah" style="font-weight:bold;padding-right:200px"></div>
                            </div>
                        </div>-->
                        <!--<div class="form-group group-1">
                            <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_htb'); ?>
                            <div class="input-group">
                                <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" placeholder="" autocomplete="off"  class="form-control money" required/>
                                <div class="input-group-addon" id="labelRupiah2" style="font-weight:bold;padding-right:200px"></div>
                            </div>
                        </div>-->
                       <!-- <div class="form-group">
                            <label>Tahun Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                            <input type="text" name="TAHUN_PEROLEHAN_AWAL" id="TAHUN_PEROLEHAN_AWAL"  class="form-control year" required/>  
                        </div>  -->

                        <!-- /*ab menambahkan inputan tanggal -->
                        <div class="form-group form-tahun-perolehan">
                            <div class='input-group date' id='datetimepicker10'>
                                <label>Tahun Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                                <input type="text" name="TAHUN_PEROLEHAN_AWAL" id="TAHUN_PEROLEHAN_AWAL" onkeydown="return false"  class="form-control year" autocomplete="off"/>  
                                <small class="help-block notif-tahun-perolehan" style="color:#a94442; display:none;">Data ini wajib di isi</small>     
                            </div>
                        </div> 
                        
                    </div>
                </div><!--end of modal-->
                <div class="modal-footer">
                    <button type="submit"  id="button-saved"  class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> <i class="fa fa-remove"></i> Batal</button>
                </div>
            </form>
            <form role="form" id="formAsalUsul">
                <div class="modal-header">
                    <h4 class="modal-title" id="asal_usul_title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="id_checkbox"/>
                        <label>Tanggal Transaksi </label> <?= FormHelpPopOver('tgl_transaksi_popup'); ?>
                        <div class="input-group date">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </div>
                            <input type="text" name="tgl_transaksi_asal"  id="tgl_transaksi_asal" placeholder="( tgl/bulan/tahun )" class="form-control"/>
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
                        <input type="text" name="nama_pihak2_asal" id="nama_pihak2_asal"  class="form-control input_capital" />
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
        </div>
    </div>
</div>
<!---END HARTA TIDAK BERGERAK -->
<!-- <style type="text/css">
.year { width: 40%; padding: .2em .2em 0; }

.year { font-size: 75% }
</style> -->


<script type="text/javascript">
        $("#TERBILANG_NILAI_PEROLEHAN").hide();
        $("#NILAI_PEROLEHAN").focusin(function() {
            $("#TERBILANG_NILAI_PEROLEHAN").show();
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PEROLEHAN").val(string_nominal);
            $("#NILAI_PEROLEHAN").keyup(function(){
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PEROLEHAN").val(string_nominal);
            });

        });
        $("#NILAI_PEROLEHAN").focusout(function() {
            $("#TERBILANG_NILAI_PEROLEHAN").hide();
        });


        $("#TERBILANG_NILAI_PELAPORAN").hide();
        $("#NILAI_PELAPORAN").focusin(function() {
            $("#TERBILANG_NILAI_PELAPORAN").show();
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PELAPORAN").val(string_nominal);
            $("#NILAI_PELAPORAN").keyup(function(){
                var state_perolehan = $(this).val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PELAPORAN").val(string_nominal);
            });

        });
        $("#NILAI_PELAPORAN").focusout(function() {
            $("#TERBILANG_NILAI_PELAPORAN").hide();
        });      



    function fun_AllowOnlyAmountAndDot(event,txt)
        {
            if(event.charCode > 47 && event.charCode < 58 || event.charCode == 46  || event.charCode == 0)
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
                        // event.charCode=0;
                        return false;
                    }
                }
                return true;
            }
            else
            {
                // event.charCode=0;
                //alert("Only Numbers with dot allowed !!");
                return false;
            }

        }

    $(document).ready(function() {
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

        if (STATUS == '0' || STATUS == '2' || STATUS == '7') {
            $('input[type=submit],button[type=submit]').show();
        } else {
            $('input[type=submit],button[type=submit]').hide();
        }

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        var list_jenis_bukti = load_html('portal/data_harta/get_jenis_bukti/1');
        $('#JENIS_BUKTI').html(list_jenis_bukti);

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        var list_pemanfaatan = load_html('portal/data_harta/get_pemanfaatan/1');
        $('#table-pemanfaatan').html('<tbody>' + list_pemanfaatan + '</tbody>');

        var list_pasangan_anak = load_html('portal/data_harta/get_pasangan_anak');
        $('#KET_PASANGAN_ANAK').html(list_pasangan_anak);

        //var list_asal_usul = load_html('portal/data_harta/get_asal_usul');
        //$('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        $('.date').datetimepicker({
            format: "DD/MM/YYYY",
//            maxDate: 'now'
        });

        $('.date').datetimepicker('option', "maxDate", "now");

        $('#tgl_transaksi_asal').datetimepicker({
            format: "DD/MM/YYYY",
//            maxDate: TGL_LAPOR
        });

        $('.date').datetimepicker('option', "maxDate", TGL_LAPOR);
		

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
        


        // $('#NEGARA').val('1');

        $('.luar').hide();
        $('#NEGARA').change(function() {
            var val = $(this).val();
            if (val == '1') {
                $('#PROV').select2('data', null);
                GetKota(0);
                $('.luar').hide();
                $('.lokal').fadeIn('slow');
            } else {
                $('.lokal').hide();
                $('.luar').fadeIn('slow');
            }
        });
        



        $('#formAsalUsul').hide();
        $('#ModalHarta .pilih').click(function() {
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
            CustomValidation();
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
                    $('#ModalHarta .modal-content').animate({
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
                    CustomValidation();
                });
            });
            return false;
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
            AtasNamaValidation();
        }).on('success.form.bv', function(e, data) {
            CustomValidation();
            AtasNamaValidation();
            var error = $('.has-error').length;
            var atas_nama = $('.notif-atas-nama').is(":visible");
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
                        do_submit('#FormHarta', 'portal/data_harta/update_harta_tidak_bergerak', text, '#ModalHarta');
                    }
                } else if ($('#NILAI_PELAPORAN').val() == 0 || $('#NILAI_PELAPORAN').val() == '0'){
                    notif('Maaf, Isian nilai estimasi pelaporan harta Anda 0');
                } else {
                    do_submit('#FormHarta', 'portal/data_harta/update_harta_tidak_bergerak', text, '#ModalHarta');
                }
                $('#TableTanah').DataTable().ajax.reload(null,false);
            }
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
            CustomValidation();
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
//                    $.each(data.province, function(index, item) {
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

            if (isDefined(value) && value != '') {
                GetKota(value);
                CustomValidation();
            }
        });

        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker({
            useCurrent: false, /*ab membuat nilai false pada default value di text box*/
            viewMode: 'years',
            format: "YYYY",
            maxDate: new Date(TAHUN_LAPOR, 11, 31)
        }).on('dp.change dp.show',function(){ 
        });
 
        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker('option', {maxDate: 'now'});

        $('.money').mask('000.000.000.000.000.000', {reverse: true});
        $(".input").maskMoney({prefix: '', thousands: '.', decimal: ',', precision: 0});
        $('#ModalHarta').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
//         $('select').select2();
        

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