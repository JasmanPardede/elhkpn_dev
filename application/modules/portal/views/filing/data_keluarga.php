<?= FormHelpAccordionEfiling('data_keluarga'); ?>
<div class="box-body">
    <span class="block-body">
        <a href="javascript:void(0)" id="add" class="btn btn-info btn-sm">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </span>
    <span class="block-body">
        <table id="TKeluarga" class="table table-striped table-bordered table-hover table-heading no-border-bottom table-filing">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>HUBUNGAN DENGAN PN</th>
                    <th>TEMPAT, TANGGAL LAHIR / JENIS KELAMIN DAN UMUR</th>
                    <th>PEKERJAAN</th>
                    <th>ALAMAT</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </span>
    <span class="block-body" style="overflow:hidden;">
        <div class="pull-right">
            <a href="javascript:void(0)" onclick="pindah(2)" class="btn btn-warning btn-sm" style="margin-left:5px;">
                <i class="fa fa-backward"></i>   Sebelumnya
            </a>
            <a href="javascript:void(0)" onclick="pindah(4)" class="btn btn-warning btn-sm" style="margin-left:5px;">
                Selanjutnya <i class="fa fa-forward"></i>
            </a>
        </div>
    </span>
</div>
<div class="box-footer"></div>

<div id="myModal" class="modal fade container-fluid" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormKeluarga">
            	<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FORM DATA KELUARGA</h4>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Kewarganegaraan <span class="red-label">*</span></label> 
                            <?php echo FormHelpPopOver('kewarganegaraan_pnwl'); ?>
                            <br>
                            <label class="radio-inline">
                            <input type="radio" id="WNI" name="IS_WNA" value="0" checked>WNI
                            </label>
                            <label class="radio-inline">
                            <input type="radio" id="WNA" name="IS_WNA" value="1">WNA
                            </label>
                        </div>
                        <div class="form-group">
                            <label id="label_info">Nomor Induk Kependudukan (NIK) <span class="red-label">*</span></label> <span id="text-tooltip"> <?php echo FormHelpPopOver('nik_dp'); ?> </span>
                            <input name='NIK_KELUARGA' id='NIK_KELUARGA' onkeyup='HitungText()' autocomplete="off" onkeypress="return isNumber(event)" maxlength="16" type="text" class="form-control" required <?php echo FormHelpPlaceholderToolTip('nik') ?>> 
                            
                            <input name='NO_KITAS' id='NO_KITAS' autocomplete="off" type="text" class="form-control" required <?php echo FormHelpPlaceholderToolTip('nik') ?> style="display: none;">               
                        </div>
                        <div class="form-group">
                            <span align='center' id='NIK1'></span>
                            <!--<span align='center' id='NIK2'></span>
                            <span align='center' id='NIK3'></span>-->
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                            <input type="hidden" name="ID" id="ID"/>
                            <label>Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_dkel'); ?>
                            <input type="text" name="NAMA" id="NAMA" class="form-control input_capital" required/>
                        </div>
                        <div class="form-group hubungan">
                            <label>Hubungan <span class="red-label">*</span> </label> <?= FormHelpPopOver('hubungan_dkel'); ?>
                            <select name="HUBUNGAN" id="hubungan" class="form-control"  required>
                                <option></option>
                                <option value="1">ISTRI</option>
                                <option value="2">SUAMI</option>
                                <option value="3">ANAK TANGGUNGAN</option>
                                <option value="4">ANAK BUKAN TANGGUNGAN</option>
                                <option value="5">LAINNYA</option>
                            </select>
                        </div>
                        <!--  <div class="form-group status_nikah">
                             <label>Status Pernikahan <span class="red-label">*</span> </label> <?= FormHelpPopOver('status_nikah_dkel'); ?>
                             <select name="STATUS_HUBUNGAN" id="status_nikah" class="form-control" required >
                                 <option></option>
                                 <option value="1">NIKAH</option>
                                 <option value="-1">CERAI</option>
                             </select>
                         </div>
                         <div class="form-group status_tempat">
                             <label id="label_status_tempat">Tempat <span class="red-label">*</span> </label> <?= FormHelpPopOver('tempat_status_dkel'); ?>
                             <input type="text" name="TEMPAT" id="TEMPAT" class="form-control input_capital" required/>

                         </div>
                         <div class="form-group status_tanggal">
                             <label id="label_status_tanggal">Tanggal <span class="red-label">*</span> </label> <?= FormHelpPopOver('tgl_status_dkel'); ?>
                             <div class="input-group date">
                                 <div class="input-group-btn">
                                     <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                 </div>
                                 <input type="text" name="TANGGAL" id="TANGGAL"  placeholder="( tgl/bulan/tahun )" class="form-control date" required/>
                             </div>
                         </div> -->
                        <div class="form-group">
                            <label>Tempat Lahir <span class="red-label">*</span> </label> <?= FormHelpPopOver('tpt_lahir_dkel'); ?>
                            <input type="text" name="TEMPAT_LAHIR" id="TEMPAT_LAHIR" class="form-control input_capital" required/>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir <span class="red-label">*</span> </label> <?= FormHelpPopOver('tgl_lahir_dkel'); ?>
                            <div class="input-group date">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </div>
                                <input type="text" name="TANGGAL_LAHIR" id="TANGGAL_LAHIR" onkeydown="return false" autocomplete="off" placeholder="( tgl/bulan/tahun )" autocomplete="off" class="form-control date" required/>
                                <input type="hidden" name="TANGGAL_LAPOR" id="TANGGAL_LAPOR" class="form-control date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <span align='center' id='hitungUmur'></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group jenis_kelamin">
                            <label>Jenis Kelamin <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_kelamin_dkel'); ?>
                            <select class="form-control" id="jenis_kelamin" name="JENIS_KELAMIN" required>
                                <option></option>
                                <option value="LAKI-LAKI">LAKI-LAKI</option>
                                <option value="PEREMPUAN">PEREMPUAN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pekerjaan </label> <?= FormHelpPopOver('pekerjaan_dkel'); ?>
                            <input type="text" name="PEKERJAAN" id="PEKERJAAN" class="form-control input_capital" />
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon/Handphone </label> <?= FormHelpPopOver('no_telp_dkel'); ?>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                <input type="text" id="NOMOR_TELPON" name="NOMOR_TELPON" class="form-control" onkeypress="return isNumber(event)"  placeholder="Isikan Nomor Handphone" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat<span class="red-label">*</span> </label> <?= FormHelpPopOver('alamat_rmh_dkel'); ?>
                            <textarea class="form-control" rows="3" name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" required ></textarea>
                            <input id="alamat_pn" type="button" class="btn  btn-sm btn-primary" value="sama dengan PN">
                            </textarea>
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
    </div>
</div>

<script type="text/javascript">
    function testDigitNik(Teks, total) {
        if (Teks < 16) {
            total.innerHTML = '<img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> tidak boleh kurang dari 16 Digit';
            document.getElementById("NIK_KELUARGA").focus();
            return false;
        } else {
            total.innerHTML = ' <img id="nik_ada1" src="<?php echo base_url('img/success.png') ?>" width="24" /> sudah benar 16 digit';
        }
        return true;
    }

    function HitungText(needResponse) {
        $('#NIK1').show();
        var Teks = document.getElementById('FormKeluarga').NIK_KELUARGA.value.length;
        var total = document.getElementById('NIK1');
        // var valid = document.getElementById('NIK3');

        // var cekverifikasinik = document.getElementById('NIK2');
        // total.innerHTML = Teks + ' Karakter';  

        if (!isDefined(needResponse)) {
            needResponse = false;
        }
        var isNikOk = testDigitNik(Teks, total);

        if (needResponse) {
            return isNikOk;
        }

    }
    var alamat_rumah;
    $(document).ready(function() {

        $("#btn-cancel").click(function(){ 
            var tab_3 = $('li.tab3').hasClass('active');
            if(tab_3){
                View(3, 'DATA KELUARGA');
            }
        });

        $("input[name='IS_WNA']").change(function(){
            var radioValue = $("input[name='IS_WNA']:checked").val();
            if(radioValue == 1){
                $("#NIK_KELUARGA").hide();
                $("#NO_KITAS").show();
                $("#NO_KITAS").val("");
                $('#NIK1').hide();
                $('#label_info').html("Nomor KITAS/ITAS/Passport/Lainnya <span class='red-label'>*</span>");
            }else{
                $("#NIK_KELUARGA").show();
                $("#NIK_KELUARGA").val("");
                $("#NO_KITAS").hide();
                $('#label_info').html("Nomor Induk Kependudukan (NIK) <span class='red-label'>*</span>");
            }
        });

    	var alamat_rumah = "<?php echo $alamat_rumah; ?>";

        <?php /* //        var ID_PN = "<?php echo($ID_PN); ?>"; */ ?>

        $('#ID_LHKPN').val(ID_LHKPN);
        $('#TANGGAL_LAPOR').val(TGL_LAPOR);
        $('.status_nikah,.status_tempat,.status_tanggal').hide();

        $('#alamat_pn').on('click', function(e) {
            $('#ALAMAT_RUMAH').val(alamat_rumah).change();
        });

        $('html, body').animate({
            scrollTop: 0
        }, 2000);

        $('.date')
        .on('dp.change dp.show', function(e) {
            // Revalidate the date when user change it
            $('#FormKeluarga').bootstrapValidator('revalidateField', 'TANGGAL_LAHIR');
        });

        $('#TANGGAL_LAHIR').datetimepicker({
            viewMode: 'years',
            format: "DD/MM/YYYY",
            maxDate: 'now'
        }).on('dp.change', function(e){
            $("#hitungUmur").show();
            var z = $("#TANGGAL_LAHIR").data("date");
            var tgl_lapor = new Date($('#TANGGAL_LAPOR').val());
            var dob = e.date._d;
//            var today = new Date();
            var today = tgl_lapor;
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));

            var year = today.getYear() - dob.getYear();
            var month = today.getMonth() - dob.getMonth();
            var date = today.getDate() - dob.getDate();
            if (age > 0) {
                if (month < 0) {
                    if (date < 0) {
                		var year = (today.getYear() - 1) - dob.getYear();
                		var month = 11 - (dob.getMonth() - today.getMonth()) ;
                		$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun " + month + " Bulan</strong>");
                	} else {
                		var year = (today.getYear() - 1) - dob.getYear();
                    	var month = 12 - (dob.getMonth() - today.getMonth());
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun " + month + " Bulan</strong>");
                	}
                } else if (month == 0) {
                    if (date < 0) {
                    	var year = (today.getYear() - 1) - dob.getYear();
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun 11 Bulan</strong>" );
                    } else {
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun</strong>" );
                    }
                } else if (month == 1) {
                    if (date < 0) {
                    	var month = today.getMonth() - dob.getMonth() - 1 ;
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun</strong>");
                    } else {
                    	var month = today.getMonth() - dob.getMonth() ;
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun " + month + " Bulan</strong>");
                    }
                } else if (month > 1) {
                    if (date < 0) {
                    	var month = today.getMonth() - dob.getMonth() - 1 ;
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun " + month + " Bulan</strong>");
                    } else {
                    	var month = today.getMonth() - dob.getMonth() ;
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + year + " Tahun " + month + " Bulan</strong>");
                    }
                }
            } else if (age == 0) {
                var month = Math.floor(diff_in_days(dob, today) / 30);

                if (month < 0) {
	                $("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + month + " Bulan</strong>");

					// if (date < 0) {
	                // 	var month = 11 - (dob.getMonth() - today.getMonth());
	                // 	$("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");
					// } else {
					// 	$("#hitungUmur").html("<strong>Umur : 11 Bulan</strong>");
					// }
                } else if (month == 0) {
                    if (date < 0 && month > 0) {
                        $("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + month + " Bulan</strong>");
						// $("#hitungUmur").html("<strong>Umur : 11 Bulan</strong>");
                    } else {
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : Belum 1 Bulan</strong>");
                    }
                } else if (month == 1) {
                    if (date < 0) {
                    	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : Belum 1 Bulan</strong>");
                    } else {
                        $("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + month + " Bulan</strong>");
                    }
                } else if (month > 1) {
                	$("#hitungUmur").html("<strong>Umur Saat lapor LHKPN : " + month + " Bulan</strong>");

                    // if (date < 0) {
                	// 	var month = today.getMonth() - dob.getMonth() - 1;
                	// 	$("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");
                    // } else {
                	// 	var month = today.getMonth() - dob.getMonth();
                	// 	$("#hitungUmur").html("<strong>Umur : " + month + " Bulan</strong>");
                    // }
                }
            }
        });

        $('#TANGGAL').datetimepicker({
            format: "DD/MM/YYYY",
            maxDate: 'now'
        });

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

        $('select').select2();
        
        // GET TABLE DATA SOURCE
        $('#TKeluarga').dataTable({
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
//            'sAjaxSource': '<?php echo base_url(); ?>portal/data_keluarga/tablekeluarga/' + ID_LHKPN + '/' + ID_PN,
            'sAjaxSource': '<?php echo base_url(); ?>portal/data_keluarga/tablekeluarga/' + ID_LHKPN,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": true,
            "bAutoWidth": false,
            'aoColumns': [{sWidth: "2%"}, {sWidth: "15%"}, {sWidth: "15%"}, {sWidth: "27%"}, {sWidth: "10%"}, {sWidth: "22%"}, {sWidth: "9%"}],
            'fnServerData': function(sSource, aoData, fnCallback) {
            	$.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }

        });


        // ADD NEW FORM
        $('#add').click(function() {
        	$('#FormKeluarga')[0].reset();
            $('select').select2('data', null);
            $('#ID').val('');
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });


        $('#myModal .modal-dialog').css({
            'margin-top': '5px',
            'width': '70%',
            'height': '100%'
        });

        $('#myModal .form-group').css({
            'margin-bottom': '7.5px'
        });

        $('#myModal .modal-footer').css({
            'padding': '10px'
        });

        $('#hubungan').change(function() {
            if ($(this).val() == '1') {
                //$('.status_nikah').fadeIn('slow');
                //$('#status_nikah').select2("val", "");
                $('#jenis_kelamin').select2("val", "2");
            } else if ($(this).val() == '2') {
                $('#jenis_kelamin').select2("val", "1");
            } else {
                $('.status_nikah,.status_tempat,.status_tanggal').fadeOut('slow');
                $('#jenis_kelamin').select2("val", "");
            }
        });

        $('#status_nikah').change(function() {
            var keterangan = $(this).val();
            var val;
            if (keterangan == 1) {
                val = 'NIKAH';
            } else {
                val = 'CERAI';
            }
            $('.status_tempat,.status_tanggal').fadeIn('slow');
            $('#label_status_tempat').text('Tempat ' + val);
            $('#label_status_tanggal').text('Tanggal ' + val);
        });

        // SUBMIT FORM

        $('#FormKeluarga').bootstrapValidator({
            fields: {
                ALAMAT_RUMAH: {
                     trigger: 'change keyup',
                },
                TANGGAL_LAHIR: {
                    validators: {
                        notEmpty: {
                        message: 'Data ini wajib di isi'
                    }
                 }
               },
               NIK_KELUARGA: {
                    validators: {
                        notEmpty: {
                        message: 'Data ini wajib di isi'
                    }
                 }
               },
            }
        }).on('success.form.bv', function(e) {
            
            var is_wna = $("input[name='IS_WNA']:checked").val();
            var nik_kel = $("#NIK_KELUARGA").val();
            if(is_wna == 0 && nik_kel != ''){
                var isianNikBermasalah = !HitungText(true);
                if (isianNikBermasalah) {
                    $('#btn-submit').prop('disabled', true);
                    return false;
                }
            }
            
            e.preventDefault();
            var ID = $('#ID').val();
            var text;
            if (ID == '') {
                text = 'Data Keluarga Berhasil Di Tambahkan';
            } else {
                text = 'Data Keluarga Berhasil Di Update';
            }
            
            do_submit('#FormKeluarga', 'portal/data_keluarga/update', text, '#myModal');

            $('#NIK1').hide();
            $("#hitungUmur").hide();
            // $('#TKeluarga').DataTable().ajax.reload();
        })

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        // $('#FormKeluarga').submit(function(){
        //     var ID = $('#ID').val();
        //     var text;
        //     if(ID==''){
        //         text = 'Data Keluarga Berhasil Di Tambahkan';
        //     }else{
        //         text = 'Data Keluarga Berhasil Di Update';
        //     }
        //     do_submit('#FormKeluarga','portal/data_keluarga/update',text,'#myModal');
        //     $('#TKeluarga').DataTable().ajax.reload();
        //     return false;
        // });

        // DELETE ACTION
        $("#TKeluarga tbody").on('click', '.delete-action', function(event) {
            var id = $(this).attr('id');
            confirm("Apakah anda yakin akan menghapus data ? ", function(){
                do_delete('portal/data_keluarga/delete/' + id, 'Data Keluarga Berhasil Di Hapus ');
                $('#TKeluarga').DataTable().ajax.reload();
            });
        });

        // CETAK ACTION
        $("#TKeluarga tbody").on('click', '.cetakSK-action', function(event) {
            var id = $(this).attr('id');
            var id_lhkpn = $(this).attr('data-id');
            var LINK = '<?php echo base_url(); ?>portal/review_harta/cetak_surat_kuasa_individual/' + id + '/' + id_lhkpn + '/1';
            window.open(LINK, '_blank');
        });

        // EDIT ACTION
        $("#TKeluarga tbody").on('click', '.edit-action', function(event) {
            event.preventDefault();
            $('#FormKeluarga').data('bootstrapValidator').resetForm(true);
            $('#FormKeluarga')[0].reset();
            $('.status_nikah,.status_tempat,.status_tanggal').hide();
            var id = $(this).attr('id');
            var data = do_edit('portal/data_keluarga/edit/' + id);
            ////////output security////////
            if(data.result=='alert_security'){
                notif('Anda tidak memiliki akses pada data ini');
                return;
            }
            ////////output security////////
            if (data.HUBUNGAN == '1' || data.HUBUNGAN == '2') {
                $('.status_nikah,.status_tempat,.status_tanggal').show();
            } else {
                $('.status_nikah,.status_tempat,.status_tanggal').hide();
            }
            $('#ID').val(data.ID_KELUARGA);
            $('#NAMA').val(data.NAMA);
            $('#IS_WNA').val(data.IS_WNA);
            
            if(data.IS_WNA == '1'){
                $("#NIK_KELUARGA").hide();
                $("#WNI").prop('checked',false);
                $("#NO_KITAS").show();
                $("#WNA").prop('checked',true);
                $("#NO_KITAS").val(data.NIK);
                $('#NIK1').hide();
                $('#label_info').text("Nomor KITAS/ITAS/Passport/Lainnya");
            }else{
                $("#NIK_KELUARGA").show();
                $("#NIK_KELUARGA").val(data.NIK);
                $("#NO_KITAS").hide();
                $('#label_info').text("Nomor Induk Kependudukan (NIK)");
            }

            $('#hubungan').select2('val', data.HUBUNGAN);
            $('#status_nikah').select2('val', data.STATUS_HUBUNGAN);
            var val;
            if (data.STATUS_HUBUNGAN == '1') {
                val = 'Nikah';
                $('#TANGGAL').val(dateConvert(data.TANGGAL_NIKAH));
                $('#TEMPAT').val(data.TEMPAT_NIKAH);
            } else {
                val = 'Cerai';
                $('#TANGGAL').val(dateConvert(data.TANGGAL_CERAI));
                $('#TEMPAT').val(data.TEMPAT_CERAI);
            }

            if (data.JENIS_KELAMIN == '2'){
                var JenKel = 'LAKI-LAKI';
            }else if (data.JENIS_KELAMIN == '3'){
                var JenKel = 'PEREMPUAN';
            }else{
                var JenKel = data.JENIS_KELAMIN;
            }

            $('#label_status_tempat').text('Tempat ' + val);
            $('#label_status_tanggal').text('Tanggal ' + val);
            $('#TEMPAT_LAHIR').val(data.TEMPAT_LAHIR);
            $('#TANGGAL_LAHIR').val(dateConvert(data.TANGGAL_LAHIR));
            $('#jenis_kelamin').select2('val', JenKel);
            $('#PEKERJAAN').val(data.PEKERJAAN);
            $('#NOMOR_TELPON').val(data.NOMOR_TELPON);
            $('#ALAMAT_RUMAH').val(data.ALAMAT_RUMAH);
            $('#TANGGAL_LAPOR').val(data.tgl_lapor);
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
            //alamat_rumah = data.Alamatrumah;

            $('#btn-submit').prop('disabled', false);
        });

        $('#btn-cancel').click(function() {
            $('#NIK1').hide();
            $("#hitungUmur").hide();
        });

        if (STATUS == '1'  || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
            $('#add').remove();
        }


    });

    var diff_in_days = function(date1, date2) {
        return Math.floor((Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate()) - Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate())) / (1000 * 60 * 60 * 24));
    }

    function do_submit(form, url, text, modal) {
        if (modal) {
            $(modal).modal('hide');
        }
        var ajaxTime = new Date().getTime();
        var formData = new FormData($(form)[0]);
        $.ajax({
            url: base_url + '' + url,
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
                // Loading('hide');
            },
            success: function (data) {
                if (data == 1) {
                     if(form == '#FormKeluarga'){
                        View(3, 'DATA KELUARGA');
                     }
                    success(text);
                }else if(data=='9' || data==9){
                    notif('Anda tidak memiliki akses pada data ini !!!');
                }else {
                    notif('Silahkan refresh halaman ini atau login ulang.');
                }
                var totalTime = new Date().getTime() - ajaxTime;
                stf(totalTime);
            },
            error: function (jqXHR, exception) {
                ajax_error_xhr(jqXHR, exception);
            },
        });
    }
</script>
