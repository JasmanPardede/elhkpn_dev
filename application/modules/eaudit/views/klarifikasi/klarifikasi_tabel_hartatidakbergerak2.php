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
                                <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_TANAH" id="LUAS_TANAH" placeholder="" class="form-control money" required/> 
                                <label style="display:inline;">m<sup>2</sup></label>
                                <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_BANGUNAN" id="LUAS_BANGUNAN" placeholder="" class="form-control money" required/>
                                <label style="display:inline;">m<sup>2</sup></label>
                            </div>
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
                            <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                                <option></option>
                                <option value="1">PN YANG BERSANGKUTAN</option>  
                                <option value="2">PASANGAN / ANAK</option>  
                                <option value="3">LAINNYA</option>
                            </select>
                        </div>
                        <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                            <label>Keterangan Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
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
                            <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" placeholder="" class="form-control money"    required/>
                        </div>
                        <div class="form-group">
                            <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_htb'); ?>
                            <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" placeholder="" class="form-control money" required/>
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


<script type="text/javascript">

    $(document).ready(function() {
        $('#ket_lainnya_an_div').hide();
        $("#ATAS_NAMA").change(function() {
            $("#KET_LAINNYA_AN").val('');
            var isKeteranganLainnyaExists = document.getElementById("KET_LAINNYA_AN");

            if ($("#ATAS_NAMA").val() == '3') {
                $('#ket_lainnya_an_div').show();
                $('#FormHarta').bootstrapValidator('addField', isKeteranganLainnyaExists);
            } else {
                $('#ket_lainnya_an_div').hide();
                $('.notif-ket-lainnya').hide();
                $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
            }
        });

        if (STATUS == '0' || STATUS == '2') {
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


        $('#NEGARA').val('1');

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
            console.log("form submit");
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

        $('select').select2();


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
                        do_submit('#FormHarta', 'portal/data_harta/update_harta_tidak_bergerak', text, '#ModalHarta');
                    }
                }else{
                    do_submit('#FormHarta', 'portal/data_harta/update_harta_tidak_bergerak', text, '#ModalHarta');
                }
                $('#TableTanah').DataTable().ajax.reload();
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



        $('.money').mask('000.000.000.000.000.000', {reverse: true});
        $(".input").maskMoney({prefix: '', thousands: '.', decimal: ',', precision: 0});
        $('#ModalHarta').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

    });

    function CustomValidation() {
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

        if ($("#ATAS_NAMA").val() != '3') {
            $('.notif-ket-lainnya').hide();
            $('.ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
        } else {
            $('.notif-ket-lainnya').show();
            $('.ket_lainnya_an_div').removeClass('has-success').addClass('has-error');
        }

        AsalUsulValidation();
    }

</script>