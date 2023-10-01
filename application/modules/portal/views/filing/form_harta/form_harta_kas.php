<!---HARTA KAS -->
<div id="ModalHarta" class="modal fade container-fluid" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormKas" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FORM DATA KAS DAN SETARA KAS</h4>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-4">
                        <input type="hidden" name="ID" id="ID"/>
                        <input type="hidden" name="ID_HARTA" id="ID_HARTA"/>
                        <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                        <input type="hidden" name="status_harta" id="status_harta"/>
                        <input type="hidden" name="is_load_harta" id="is_load_harta"/>
                        <input type="hidden" name="is_pelepasan_harta" id="is_pelepasan_harta"/>
                        <!-- <div class="form-group">
                              <label>Jenis File <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_file_ksk'); ?>
                              <select class="form-control" id="file_type" required>
                                  <option></option>
                                  <option value="1">Single File (Pdf)</option>
                                  <option value="2">Multiple File (Jpg,Png,Jpeg)</option>
                              </select> 
                         </div> -->
                        <div class="form-group">
                            <label>Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_ksk'); ?>
                            <select class="form-control" id="KODE_JENIS" name="KODE_JENIS" required>
                            </select>  
                        </div>

                        <div class="form-group form-file">

                            <label class="control-label">Bukti Dokumen/Rekening (pdf/jpg/png/jpeg/tif)</label> <?= FormHelpPopOver('bukti_dokumen_ksk'); ?>
                            <div style="float:left; margin-right:10px;" id="show-download"></div>
                            <input type="file" id="file1" name="file1[]" class="form-control" multiple data-allowed-file-extensions='["pdf", "jpg", "png","jpeg","tif","tiff"]'  data-show-preview="true"/>
                            Tekan 'CTRL' dan click beberapa file yang akan di upload (Maksimal 3 File)
                            <small class="help-block notif-file" style="color:#a94442; display:none;">Maksimal 3 File</small> 
                        </div>
                        <!--<div class="form-group file2">
                             <label class="control-label">Bukti Dokumen/Rekening <span class="red-label">*</span></label> <?= FormHelpPopOver('bukti_dokumen_ksk'); ?>
                             <input type="file" id="file2" name="file2[]" multiple="true" data-allowed-file-extensions='["jpg", "png","jpeg"]' class="form-control"  data-show-preview="false" required/>
                        </div>-->

                        <div class="form-group">
                            <label>Nama Bank/Lembaga Keuangan <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_bank_ksk'); ?>
                            <input type="text" name="NAMA_BANK" id="NAMA_BANK" maxlength="32" class="form-control input_capital" required/>  
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening <span class="red-label">*</span> </label> <?= FormHelpPopOver('no_rek_ksk'); ?>
                            <input type="text" name="NOMOR_REKENING" id="NOMOR_REKENING" maxlength="32" class="form-control input_capital" required>   
                        </div>
                        <div class="form-group form-tahun-perolehan">
                            <label>Tahun Buka Rekening <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                            <input type="text" name="TAHUN_BUKA_REKENING" id="TAHUN_BUKA_REKENING" onkeydown="return false" class="form-control year" autocomplete="off"/> 
                            <small class="help-block notif-tahun-perolehan" style="color:#a94442; display:none;">Data ini wajib di isi</small>    
                        </div> 
						<br>
					</br>
							<br>
						</br>
					<!--	<br>
						</br>-->
						<div class="form-group">*) Anda dapat mengirimkan Bukti Dokumen/Rekening melalui POS atau diantarkan langsung ke Direktorat Pendaftaran dan Pemeriksaan LHKPN Komisi Pemberantasan Korupsi, Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta Selatan 12950.</div>
                    </div>
                    <div class="col-sm-4">

                     <!--   <div class="form-group">
                            <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_ksk'); ?>
                            <select name="ATAS_NAMA_REKENING" id="ATAS_NAMA_REKENING" class="form-control" required>
                                <option></option>
                                <option value="PN YANG BERSANGKUTAN">PN YANG BERSANGKUTAN</option>  
                                <option value="PASANGAN / ANAK">PASANGAN / ANAK</option>  
                                <option value="LAINNYA">LAINNYA</option>
                            </select>
                        </div>
                        <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                            <label>Keterangan Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_ksk'); ?>
                            <textarea class="form-control input_capital" name="KET_LAINNYA_AN" id="KET_LAINNYA_AN" rows="2" required></textarea>
                            <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>

                        </div> -->

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
<!--                        <div class="form-group">
                            <label>Keterangan</label> <?= FormHelpPopOver('keterangan_ksk'); ?>
                            <textarea name="KETERANGAN" id="KETERANGAN" class="form-control" rows="2" ></textarea>  
                        </div>-->
                        <div class="form-group form-asal">
                            <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_ksk'); ?>
                            <table class="table" id="table-asal-usul" required></table>
                            <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Jenis Mata Uang <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_mata_uang_ksk'); ?>
                            <select class="form-control" id="MATA_UANG" name="MATA_UANG" required>
                            </select>  
                        </div>
                        <div class="form-group">
                            <label>Nilai Kurs <span class="red-label">*</span></label> <?= FormHelpPopOver('kurs_ksk'); ?>
                            <input type="text" id="NILAI_KURS" name="NIlAI_KURS" class="form-control money" required/>  
                        </div>
                        <!--<div class="form-group group-1">
                            <label>Nilai Saldo<span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_saldo_ksk'); ?>
                            <div class="input-group">
                            <input type="text" id="NILAI_SALDO" name="NILAI_SALDO" class="form-control money" required/>
                            <div class="input-group-addon" id="labelRupiah" style="font-weight:bold;padding-right:200px"></div>
                            </div>   
                        </div>-->
                        <div class="form-group">
                            <label>Nilai Saldo<span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_saldo_ksk'); ?>
                            <input type="text" id="NILAI_SALDO" name="NILAI_SALDO" autocomplete="off" class="form-control money" required/>
                        </div>

                        
                        <div class="form-group">
                            <label>Ekuivalen ke kurs Rp.</label> <?= FormHelpPopOver('ekuivalen_ksk'); ?>
                            <input type="text" id="NILAI_EQUIVALEN" name="NILAI_EQUIVALEN" class="form-control money" readonly="true">  
                            <input type="text" style="font-weight:bold;background-color:#92d0fe" name="TERBILANG_NILAI_PEROLEHAN" id="TERBILANG_NILAI_PEROLEHAN" placeholder="" class="form-control"    required/> 
                        </div>
                    </div>
                </div><!--end of modal-->
                <div class="modal-footer">
                    <button type="submit" id="button-saved" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
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
                        <input type="text" name="besar_nilai_asal" id="besar_nilai_asal"  class="form-control money"required/>
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
                        <input type="text" name="nama_pihak2_asal" id="nama_pihak2_asal"  class="form-control input_capital" required/>
                    </div>
                    <div class="form-group">
                        <label>Alamat <span class="red-label">*</span></label> <?= FormHelpPopOver('alamat_pihak_kedua_popup'); ?>
                        <textarea class="form-control" name="alamat2_asal" id="alamat2_asal" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" id="button-saved"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="Cancelparent()"><i class="fa fa-remove"></i> Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END HARTA TIDAK BERGERAK -->


<script type="text/javascript">
        $("#TERBILANG_NILAI_PEROLEHAN").hide();
        $("#NILAI_SALDO").focusin(function() {
            $("#TERBILANG_NILAI_PEROLEHAN").show();
                var state_perolehan = $('#NILAI_EQUIVALEN').val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PEROLEHAN").val(string_nominal);
            $("#NILAI_SALDO").keyup(function(){
                var state_perolehan = $('#NILAI_EQUIVALEN').val();
                var convert_perolehan = state_perolehan.replace(/\./g, "");
                var string_nominal = terbilang(convert_perolehan);
                $("#TERBILANG_NILAI_PEROLEHAN").val(string_nominal);
            });

        });
        $("#NILAI_SALDO").focusout(function() {
            $("#TERBILANG_NILAI_PEROLEHAN").hide();
        });


        //  var si_kelapkelip;
        // var si_kelapkelip2;
        // $("#NILAI_SALDO").focusout(function(){
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
        // $("#NILAI_SALDO").keyup(function(){
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







    $(document).ready(function() {
        
        $('#NILAI_KURS').val('1');
        $('#NILAI_KURS').attr('readonly', true);
        
    	$('#ket_lainnya_an_div').hide();
        $('#ket_pasangan_anak_div').hide();


        $('#ATAS_NAMA_CHECK_PN').click(function(){
            AtasNamaValidation()
            if($(this).is(':checked')){
            } else {
            }
        });
        $('#ATAS_NAMA_CHECK_PASANGAN').click(function(){
            AtasNamaValidation()
            if($(this).is(':checked')){
            	$('#ket_pasangan_anak_div').show();
            	$('select').select2();
            } else {
            	$('#ket_pasangan_anak_div').hide();
            	$('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
            }
        });
        $('#ATAS_NAMA_CHECK_LAINNYA').click(function(){
            AtasNamaValidation()
            if($(this).is(':checked')){
            	$('#ket_lainnya_an_div').show();
            } else {
            	$('#ket_lainnya_an_div').hide();
            	$('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
            }
        });
        $("#ATAS_NAMA_REKENING").change(function() {
            $("#KET_LAINNYA_AN").val('');
            var isKeteranganLainnyaExists = document.getElementById("KET_LAINNYA_AN");

            if ($("#ATAS_NAMA_REKENING").val() == 'LAINNYA') {
                $('#ket_lainnya_an_div').show();
                $('#FormHarta').bootstrapValidator('addField', isKeteranganLainnyaExists);
            } else {
                $('#ket_lainnya_an_div').hide();
                $('.notif-ket-lainnya').hide();
                $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
            }
        });
        
        $('#MATA_UANG').change(function() {
            if ($(this).val() == '1') {
                $('#NILAI_KURS').val('1');
                $('#NILAI_KURS').attr('readonly', true);
            } else {
                $('#NILAI_KURS').val('0');
                $('#NILAI_KURS').attr('readonly', false);
            }
            $('#NILAI_SALDO').maskMoney('unmasked')[0];
            $('#NILAI_KURS').maskMoney('unmasked')[0];

            var v_saldo = $('#NILAI_SALDO').val();
            var v_kurs = $('#NILAI_KURS').val();
            var NILAI_SALDO = parseFloat(v_saldo.replace(/\./g, ''));
            var NILAI_KURS = parseFloat(v_kurs.replace(/\./g, ''));
            var NILAI_EQUIVALEN = parseInt(NILAI_SALDO * NILAI_KURS) || 0;
            $('#NILAI_EQUIVALEN').val(numeral(NILAI_EQUIVALEN).format('0,0').replace(/,/g, '.'));
            CustomValidation();
        });

        $('#TAHUN_BUKA_REKENING').datetimepicker({
            viewMode: 'years',
            format: "YYYY",
            maxDate: new Date(TAHUN_LAPOR, 11, 31)
        }).on('dp.change dp.show',function(){ 
        });
 
        $('#TAHUN_BUKA_REKENING').datetimepicker('option', {maxDate: 'now'});    
        
        
        var kdJenis = $('#KODE_JENIS').val();
        
        if (kdJenis == 18) {
                $('#NAMA_BANK').attr('requried', false);
                $('#NOMOR_REKENING').attr('requried', false);
                $('#NAMA_BANK').attr('readonly', true);
                $('#NOMOR_REKENING').attr('readonly', true);
				$('#NAMA_BANK').val('-');
				$('#NOMOR_REKENING').val('-');
                $('#TAHUN_BUKA_REKENING').attr('requried', false);
                $('#TAHUN_BUKA_REKENING').attr('readonly', true);
                $('#TAHUN_BUKA_REKENING').val('-');
            } else {
                $('#NAMA_BANK').attr('requried', true);
                $('#NOMOR_REKENING').attr('requried', true);
                $('#NAMA_BANK').attr('readonly', false);
                $('#NOMOR_REKENING').attr('readonly', false)
                $('#TAHUN_BUKA_REKENING').attr('requried', true);
                $('#TAHUN_BUKA_REKENING').attr('readonly', false);
            }

        $('#KODE_JENIS').change(function() {
            if ($(this).val() == 18) {
                $('#NAMA_BANK').attr('requried', false);
                $('#NOMOR_REKENING').attr('requried', false);
                $('#NAMA_BANK').attr('readonly', true);
                $('#NOMOR_REKENING').attr('readonly', true);
				$('#NAMA_BANK').val('-');
				$('#NOMOR_REKENING').val('-');
                $('#TAHUN_BUKA_REKENING').attr('requried', false);
                $('#TAHUN_BUKA_REKENING').attr('readonly', true);
                $('#TAHUN_BUKA_REKENING').val('-');
            } else {
                $('#NAMA_BANK').attr('requried', true);
                $('#NOMOR_REKENING').attr('requried', true);
                $('#NAMA_BANK').attr('readonly', false);
                $('#NOMOR_REKENING').attr('readonly', false);
                $('#TAHUN_BUKA_REKENING').attr('requried', true);
                $('#TAHUN_BUKA_REKENING').attr('readonly', false);
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

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        var list_jenis_harta = load_html('portal/data_harta/get_jenis_harta/5');
        $('#KODE_JENIS').html(list_jenis_harta);

        var list_mata_uang = load_html('portal/data_harta/get_option_matauang/m_mata_uang/SINGKATAN/ID_MATA_UANG');
        $('#MATA_UANG').html(list_mata_uang);


        var list_pasangan_anak = load_html('portal/data_harta/get_pasangan_anak');
        $('#KET_PASANGAN_ANAK').html(list_pasangan_anak);
		
        $('.date').datetimepicker({
            format: "DD/MM/YYYY",
//            maxDate: 'now'
        });
        
        $('.date').datetimepicker('option', {maxDate: 'now'});

        $('#tgl_transaksi_asal').datetimepicker({
            format: "DD/MM/YYYY",
//            maxDate: TGL_LAPOR
        });
        
        $('#tgl_transaksi_asal').datetimepicker('option', {maxDate: TGL_LAPOR});

        $('#formAsalUsul,.file1,.file2').hide();

//         $('select').select2();
        $(".input").maskMoney({prefix: '', thousands: '.', decimal: ',', precision: 0});
        $('#ModalHarta .modal-dialog').css({
            'margin-top': '5px',
            'width': '90%',
            'height': '100%'
        });

        $('#ModalHarta .form-group').css({
            'margin-bottom': '7.5px'
        });

        $('#ModalHarta .modal-footer').css({
            'padding': '10px'
        });
        
        $('#TAHUN_BUKA_REKENING').datetimepicker({
            useCurrent: false, /*ab membuat nilai false pada default value di text box*/  
            viewMode: 'years',
            format: "YYYY",
            maxDate: new Date(TAHUN_LAPOR, 11, 31)
        }).on('dp.change dp.show',function(){ 
        });
 
        $('#TAHUN_BUKA_REKENING').datetimepicker('option', {maxDate: 'now'});        

        
        $('.file1').show();
        $('#file_type').select2('val', '1');
        $('#file_type').change(function() {
            if ($(this).val() == '1') {
                $('.file2').hide();
                $('.file1').fadeIn('slow');
            } else {
                $('.file1').hide();
                $('.file2').fadeIn('slow');
            }
        });
        $("input[type='file']").fileinput({
            showUpload: false,
            showRemove: false,
            //overwriteInitial: false,
            initialCaption: false,
            //showCaption: false
        });
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
        $('#ModalHarta .pilih').click(function() {
            var id = $(this).attr('id');
            var ID = $('#ID').val();
            var title = GetTitle(id);
            if ($(this).is(':checked') && $(this).hasClass("order-1")) {
                $('#formAsalUsul')[0].reset();
                view(id, title);
            } else {
                $('#result-' + id).html('');
                $('#view-' + id).html('');
            }
            CustomValidation();
        });
        $('#formAsalUsul').submit(function() {
            var tgl_transaksi_asal = $('#tgl_transaksi_asal').val();
            var besar_nilai_asal = $('#besar_nilai_asal').val();
            var keterangan_asal = $('#keterangan_asal').val();
            var nama_pihak2_asal = $('#nama_pihak2_asal').val();
            var alamat2_asal = $('#alamat2_asal').val();
            var title = $('#asal_usul_title').text();
            $('#formAsalUsul').fadeOut('fast', function() {
                $('#FormKas').fadeIn('fast', function() {
                    $('#ModalHarta .modal-content').animate({
                        'width': '100%',
                        'margin-left': '0'
                    });
                    var id_checkbox = $('#id_checkbox').val();
                    $('#view-' + id_checkbox).html('<a href="javascript:void(0)"  id="view-to-' + id_checkbox + '" class="btn btn-view btn-xs btn-info">Lihat</a>');
                    $('#result-' + id_checkbox).html('<label class="label label-primary">' + besar_nilai_asal + '</label>');
                    $('#asal-tgl_transaksi-' + id_checkbox).val(tgl_transaksi_asal);
                    $('#asal-besar_nilai-' + id_checkbox).val(besar_nilai_asal);
                    $('#asal-keterangan-' + id_checkbox).val(keterangan_asal);
                    $('#asal-pihak2_nama-' + id_checkbox).val(nama_pihak2_asal);
                    $('#asal-pihak2_alamat-' + id_checkbox).val(alamat2_asal);

                });
            });
            CustomValidation();
            return false;
        });
        $('.money').mask('000.000.000.000.000', {reverse: true});
        $(".input").maskMoney({prefix: '', thousands: '.', decimal: ',', precision: 0});

        $('#file1').change(function() {
            CustomValidation();
        });

        $('#NILAI_SALDO,#NILAI_KURS').on('propertychange change keyup paste input', function() {
            $('#NILAI_SALDO').maskMoney('unmasked')[0];
            $('#NILAI_KURS').maskMoney('unmasked')[0];
            var v_saldo = $('#NILAI_SALDO').val();
            var v_kurs = $('#NILAI_KURS').val();
            var NILAI_SALDO = parseFloat(v_saldo.replace(/\./g, ''));
            var NILAI_KURS = parseFloat(v_kurs.replace(/\./g, ''));
            var NILAI_EQUIVALEN = parseInt(NILAI_SALDO * NILAI_KURS) || 0;
            $('#NILAI_EQUIVALEN').val(numeral(NILAI_EQUIVALEN).format('0,0').replace(/,/g, '.'));
        });



        $('#FormKas').bootstrapValidator({
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
        }).bootstrapValidator().on('success.form.bv', function(e) {
            e.preventDefault();
            CustomValidation();
            AtasNamaValidation();
            var error = $('.has-error').length;
            var atas_nama = $('.notif-atas-nama').is(":visible");
            var asal_usul = $('.notif-asal').is(":visible");
            var notif_file = $('.notif-file').is(":visible");
            var ID = $('#ID').val();
            var text;
            if (ID == '') {
                text = 'Data Harta Kas/Setara Kas berhasil ditambahkan';
            } else {
                text = 'Data Harta Kas/Setara Kas berhasil diperbaharui';
            }
            if (ID == '') {
                if (error == 0 && !atas_nama && !asal_usul && !notif_file) {
                    if ((($('#status_harta').val() == '3' && $('#is_load_harta').val() == '1') || $('#status_harta').val() == '2' || $('#status_harta').val() == '1') && $('#is_pelepasan_harta').val() !== '1'){
                        if ($('#NILAI_SALDO').val() == 0 || $('#NILAI_SALDO').val() == '0'){
                            notif('Isian nilai estimasi pelaporan harta Anda 0, apabila anda bermaksud menghapus/melepas harta silakan gunakan tombol lepas');
                        }else{
                            do_submit('#FormKas', 'portal/data_harta/update_kas', text, '#ModalHarta');
                        }
                    } else if ($('#NILAI_SALDO').val() == 0 || $('#NILAI_SALDO').val() == '0'){
                        notif('Maaf, Isian nilai saldo harta Anda 0');
                    } else {
                        do_submit('#FormKas', 'portal/data_harta/update_kas', text, '#ModalHarta');
                    }
                    
                    $('#TableKas').DataTable().ajax.reload(null,false);
                }
            } else {
                if (error == 0 && !atas_nama && !asal_usul) {
                    if ((($('#status_harta').val() == '3' && $('#is_load_harta').val() == '1') || $('#status_harta').val() == '2' || $('#status_harta').val() == '1') && $('#is_pelepasan_harta').val() !== '1'){
                        if ($('#NILAI_SALDO').val() == 0 || $('#NILAI_SALDO').val() == '0'){
                            notif('Isian nilai saldo harta Anda 0, apabila anda bermaksud menghapus/melepas harta silakan gunakan tombol lepas');
                        }else{
                            do_submit('#FormKas', 'portal/data_harta/update_kas', text, '#ModalHarta');
                        }
                    } else if ($('#NILAI_SALDO').val() == 0 || $('#NILAI_SALDO').val() == '0'){
                        notif('Maaf, Isian nilai saldo harta Anda 0');
                    } else {
                        do_submit('#FormKas', 'portal/data_harta/update_kas', text, '#ModalHarta');
                    }
                    
                    $('#TableKas').DataTable().ajax.reload(null,false);
                }
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

        $('#ModalHarta').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
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

        var TAHUN_PEROLEHAN_AWAL = $("#TAHUN_BUKA_REKENING").val();
        if(TAHUN_PEROLEHAN_AWAL == ''){
            $('.notif-tahun-perolehan').show();
            $('.form-tahun-perolehan').removeClass('has-success').addClass('has-error');
        }else{
            $('.notif-tahun-perolehan').hide();
            $('.form-tahun-perolehan').removeClass('has-error').addClass('has-success');
        }








        var file1 = $('#file1').val();
        var ID = $('#ID').val();
//        if(file1=='' && ID==''){
//         if (ID == '') {
// //            $('.notif-file').show();
//             $('.form-file').removeClass('has-success').addClass('has-error');
//             $('#button-saved').prop('disabled', true);
//         } else {
// //            $('.notif-file').hide();
//             $('.form-file').removeClass('has-error').addClass('has-success');
//             $('#button-saved').prop('disabled', false);
//         }
        
        if ($("#ATAS_NAMA_REKENING").val() != 'LAINNYA') {
            $('.notif-ket-lainnya').hide();
            $('.ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
        } else {
            $('.notif-ket-lainnya').show();
            $('.ket_lainnya_an_div').removeClass('has-success').addClass('has-error');
        }


        var fileUpload = $("#file1")[0].files.length;
        if (fileUpload>3) {
            $('.notif-file').show();
            $('.form-file').removeClass('has-success').addClass('has-error');
            $('#button-saved').prop('disabled', true);
        } else {
            $('.notif-file').hide();
            $('.form-file').removeClass('has-error').addClass('has-success');
            $('#button-saved').prop('disabled', false);
        }

        
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