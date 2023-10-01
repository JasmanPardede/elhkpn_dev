<?php
$form_data = isset($form_data) ? $form_data : FALSE;
?>

<script type="text/javascript">
    penerimaan = {
        submitted: false,
        year: <?php echo date('Y'); ?>,
        today: <?php echo date('Ymd'); ?>,
        eventClickPeriodik: function () {
            var NIK = $('#ajaxFormAddPenerimaan').find('#NIK').val();
            jQuery.post('index.php/efill/lhkpnoffline/infopn', {
                NIK: NIK
            }, function (json, textStatus, xhr) {
                json = JSON && JSON.parse(json) || $.parseJSON(json);
                if (json.error == 0) {
                    $('#ajaxFormAddPenerimaan').find('#ID_PN').val(json.data.ID_PN);
                    $('.TAHUN_PELAPORAN').prop('required', true);
                    $('.TANGGAL_PELAPORAN').prop('required', false);
                } else {
                    alertify.error(json.msg);
                    $('#btnCariPN').trigger('click');
                }
            });
        },
        resetFormScreening: function () {
            $("#SCREENING_VALID_YA").prop('checked', false);
            $("#SCREENING_VALID_TIDAK").prop('checked', false);
            $("#div_after_screening").hide();
            $("#divUraianScreening").hide();

            $('.TAHUN_PELAPORAN').prop('required', false);
            $('.TANGGAL_PELAPORAN').prop('required', false);
        },
        eventClickScreeningYa: function () {
            confirm("Anda telah melakukan screening. Konfirm untuk melanjutkan.", function () {

                $("#div_after_screening").show();
                $("#divUraianScreening").hide();
                $("#file_xlsm").prop("required", true);
//                uploadInput.initFrmUploadExcel();
            }, "Konfirmasi Telah Screening", function () {
                penerimaan.resetFormScreening();
            });
        },
        showScreeningTidak: function () {
            penerimaan.resetFormScreening();
            $("#SCREENING_VALID_TIDAK").prop("checked", true);
            $("#file_xlsm").removeAttr("required");
            $("#divUraianScreening").show();

            $('.JENIS_LAPORAN').each(function (e) {
                $(this).click(function (e) {
                    if ($(this).val() == 4) {
                        _this.eventClickPeriodik();
                    } else {
                        _this.eventClickKhusus();
                    }
                });
            });
        },
        eventClickScreeningTidak: function () {
            confirm("Anda telah melakukan screening, dan memilih tidak valid. Konfirm untuk melanjutkan", function () {
                //do nothing
                penerimaan.showScreeningTidak();
            }, "Konfirmasi Telah Screening", function () {
                penerimaan.resetFormScreening();
            });
        },
        eventClickKhusus: function () {
            $('.TANGGAL_PELAPORAN').prop('required', true);
            $('.TAHUN_PELAPORAN').prop('required', false);
        },
        showFormPenerimaan: function () {
            $('#wrapperFormPenerimaan').slideDown('fast');
        },
        hideFormPenerimaan: function () {
            $('#wrapperFormPenerimaan').slideUp('fast');
        },
        showCariPN: function () {
            $('#wrapperCariPN').slideDown('fast', function () {
                $('#wrapperCariPN').find('#CARI_TEXT_PN').focus();
            });
            $("#ajaxFormCariPN").submit(function (e) {
                var val_cari = $('#CARI_TEXT_PN').val().length;
                if (val_cari > 0) {
                    e.preventDefault();
                    var url = $(this).attr('action');
                    ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
                } else {
                    $('#CARI_TEXT_PN').focus();
                    alertify.error('Silahkan Isi Nama atau NIK!');
                }
                return false;
            });
        },
        eventShowHasilCariPN: function () {
            $(".paginationPN").find("a").click(function () {
                var url = $(this).attr('href');
                // window.location.hash = url;
                ng.LoadAjaxContentPost(url, $('#ajaxFormCariPN'), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
                return false;
            });
            $('.btnSelectPN').click(function () {

                var DATAPN = $(this).attr('data-pn');
                var PN = DATAPN.split('::');
                var INST_NAMA = $(this).attr('data-pn-instansi');

                $('#wrapperFormPenerimaan').find('#ID_PN').val(PN[0]);
                $('#wrapperFormPenerimaan').find('#NIK').val(PN[1]);
                $('#wrapperFormPenerimaan').find('#EMAIL').val(PN[3]);
                $('#wrapperFormPenerimaan').find('#INST_NAMA').val(INST_NAMA);
                $('#wrapperFormPenerimaan').find('#NAMA').attr('value', PN[2]);
                _this.eventClickPeriodik();
                _this.showFormPenerimaan();
                _this.hideCariPN();

                $('#ajaxFormAddPenerimaan').find('input[name="JENIS_DOKUMEN"]').prop('checked', false);
            });
        },
        hideCariPN: function () {
            $('#wrapperCariPN').slideUp('fast');
        },
        showFormAddPN: function () {
            $('#wrapperFormAddPN').slideDown('fast', function () {
                ng.formProcess($("#ajaxFormAddPN"), 'insert', '',
                        function () {
                            NEW_NIK = $('#wrapperFormAddPN').find('#NIK').val();
                            NEW_NAMA_LENGKAP = $('#wrapperFormAddPN').find('#NAMA_LENGKAP').val();
                            $('#wrapperFormPenerimaan').find('#NIK').val(NEW_NIK);
                            $('#wrapperFormPenerimaan').find('#NAMA').val(NEW_NAMA_LENGKAP);
                            _this.eventClickPeriodik();
                            _this.showFormPenerimaan();
                            _this.hideFormAddPN();
                        }, null, false);
            });
        },
        hideFormAddPN: function () {
            $('#wrapperFormAddPN').slideUp('fast');
        },
        ajaxFormAddPenerimaanSubmit: function () {

            var msg = {
                success: 'Data Berhasil Disimpan!',
                error: 'Data Gagal Disimpan!'
            }, self = this;

            $("#ajaxFormAddPenerimaan").submit(function (e) {

                var formObj = $(this);
                $(this).children(":submit").prop('disabled', true);
                var formURL = formObj.attr("action");
                var formData = new FormData(this);

                CloseModalBox();

                $('#loader_area').show();
                //$('button').attr('disabled','disabled');
                $.ajax({
                    url: formURL,
                    type: 'POST',
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    success: function (data, textStatus, jqXHR) {

                        penerimaan.submitted = false;

                        $('#loader_area').hide();

                        if (data.data_may_double) {
                            self.showPageExcelFound(data.record_found.i, data.posted_data_penerimaan.j, data.posted_data_penerimaan, data.file_excel);
                            return false;
                        }

                        if (data.msg != "success" && data.msg != "failed") {
                            alert(data.msg);
                            return false;
                        }

                        if (data.msg != "success") {
                            alertify.error("Data Gagal diupload");
                            return false;
                        }

                        if (data.tmp_idx != '0') {
                            self.showFormExcelVerification(data.tmp_idx);
                        }

                        if (data.tmp_idx == '0' && data.msg == 'success') {
                            alert(data.msg);

                            ng.LoadAjaxContent("index.php/efill/lhkpnoffline/index/penerimaan");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //$('button').removeAttr('disabled');

                        penerimaan.submitted = false;

                        $('#loader_area').hide();
                        alertify.error(msg.error + "\n" + jqXHR.statusText);
                    }
                });
//                e.preventDefault(); //Prevent Default action.
                // e.unbind();
            });

            $("#ajaxFormAddPenerimaan").submit();
        },
        showFormExcelVerification: function (id_lhkpn) {
            $('#loader_area').show();

            setTimeout(function () {
                ng.LoadAjaxContent('index.php/efill/lhkpnoffline_verifikasi/view_uploaded/' + id_lhkpn);
            }, 500);

            return false;
        },
        showPageExcelFound: function (i, j, form, fileexcel) {
            $('#loader_area').show();

            var url = 'index.php/efill/lhkpnoffline/data_penerimaan/' + i + '/' + j;
            setTimeout(function () {

                $('#loader_area').show();

                form["data_is_exist"] = true;
                form["isfileexcel"] = fileexcel;

                $.ajax({
                    mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
                    url: url,
                    type: 'POST',
                    data: form,
                    success: function (html) {
                        $('#loader_area').hide();

                        $('#ajax-content').html(html);
                        // $('.preloader').hide();
                        $('.breadcrumb a').click(function (e) {
                            e.preventDefault(); //Prevent Default action.
                            url = $(this).attr('href');
                            window.location.hash = url;
                            ng.LoadAjaxContent(url);
                            // e.unbind();
                            return false;
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alertify.alert(textStatus, errorThrown);
                        $('#loader_area').hide();
                    },
                    dataType: "html",
                    async: false
                });


            }, 500);

            return false;
        },
        setDefaultData: function () {
            $("input[name=JENIS_LAPORAN][value=\"<?php echo $form_data->JENIS_LAPORAN; ?>\"]").attr('checked', 'checked');
            $("input[name=SCREENING_VALID][value=\"<?php echo $form_data->SCREENING_SUCCESS; ?>\"]").attr('checked', 'checked');
            $("input[name=MELALUI][value=\"<?php echo $form_data->MELALUI; ?>\"]").attr('checked', 'checked');

<?php if ($form_data->SCREENING_SUCCESS == '0'): ?>
                penerimaan.showScreeningTidak();
<?php endif; ?>

        },
        init: function () {
            _this = penerimaan;
            ng.stopSubmitOnPressEnter($("#ajaxFormAddPenerimaan"));
//            this.formProcess();

            /**
             
             pesan pada saat simpan :
             apakah isian sudah benar? 
             (jika  tidak ada email)
             Uraian Screening tidak akan terkirim ke PN, karena email tidak diisi
             (jika screening tidak valid)
             Anda memilih Screening tidak valid
             
             
             */

            $("#btnSimpanPenerimaan").click(function (e) {

                e.preventDefault();
                var strKonfirmasiSubmit = "Apakah isian sudah benar?";

                if ($('#SCREENING_VALID_TIDAK').is(':checked')) {
                    strKonfirmasiSubmit += "<br />Anda memilih Screening tidak valid.";
                }

                if ($("#EMAIL").val().length <= 0) {
                    strKonfirmasiSubmit += "<br />Uraian Screening tidak akan terkirim ke PN, karena email tidak terisi.";
                }

                confirm(strKonfirmasiSubmit, function () {
                    var hasProgress = $('progress').length;

                    if (hasProgress > 0) {
                        alert("Tunggu sampai file terupload.");
                        return false;
                    } else {

//
//                        console.log($('input[name=MELALUI]:checked').val());
//                        return false;


                        if (!$('#MELALUI_LANGSUNG').is(':checked') && !$('#MELALUI_POS').is(':checked')  && !$('#MELALUI_EMAIL').is(':checked')) {
                            alertify.error('Pastikan Memilih pilihan diterima MELALUI!');
                            return false;
                        }
                        
                        if (!$('#SCREENING_VALID_YA').is(':checked') && !$('#SCREENING_VALID_TIDAK').is(':checked')) {
                            alertify.error('Pastikan telah melakukan screening File Excel!');
                            return false;
                        }

                        if ($('#wrapperFormPenerimaan').find('#ID_PN').val().trim() == '') {
                            alertify.error('Silahkan Pilih PN!');
                            $('#btnCariPN').trigger('click');
                            return false;
                        }

                        if ($('#TANGGAL_PENERIMAAN').val().trim() == '') {
                            alertify.error('Silahkan Mengisi Tanggal Penerimaan');
                            return false;
                        }


                        var uraian_screening = $('#URAIAN_SCREENING').val();

                        if ($('#SCREENING_VALID_TIDAK').is(':checked') && uraian_screening.length <= 0) {
                            alertify.error('Uraian Screening belum diisi!');
                            return false;
                        }

                        if ($('#SCREENING_VALID_YA').is(':checked') && $("input[name=isfileexcel]").length == 0) {
                            alertify.error('Silahkan Pilih File Excel LHKPN!');
                            return false;
                        }
                    }

                    penerimaan.ajaxFormAddPenerimaanSubmit();
                });
            });

            $('#btnCariPN').click(function () {
                _this.showCariPN();
                _this.hideFormPenerimaan();
            });
            $('#btnKembaliKePenerimaan').click(function () {
                _this.showFormPenerimaan();
                _this.hideCariPN();
            });
            $('#btnTambahPN').click(function () {
                _this.showFormAddPN();
                _this.hideCariPN();
            });
            $('#btnKembaliKeCariPN').click(function () {
                _this.showCariPN();
                _this.hideFormAddPN();
            });
            $("#SCREENING_VALID_YA").click(_this.eventClickScreeningYa);
            $("#SCREENING_VALID_TIDAK").click(_this.eventClickScreeningTidak);
        }
    };

    var uploadInput = {
        uploadInitialized: false,
        init: function () {
            this.initFrmUploadExcel();
        },
        addFileUpload: function (rowUpload) {

            if (isDefined(rowUpload)) {

                var tr = "<tr class=\"tr-upload-file\"><td>" + rowUpload[1] + "</td><td>" + rowUpload[2] + "</td></tr>"

                var td1 = $("<td class=\"td-nama-file\"></td>").append(document.createTextNode(rowUpload[0]));
                var td2 = $("<td class=\"td-lhkpn-excel\"></td>")
                        .append(rowUpload[1])
                        .append(rowUpload[4]);
                var td3 = $("<td class=\"td-aksi\"></td>").append(rowUpload[2]).append("&nbsp;").append(rowUpload[5]);

                var tr = $("<tr></tr>");
                $(tr).append(td1);
                $(tr).append(td2);
                $(tr).append(td3);

                $("#tableListFileUpload tbody").prepend(tr);
            }
        },
        resetAllRemoveButton: function () {
            $(".td-aksi a.remove-button").remove();

            $(".td-aksi").each(function (index) {
                uploadInput.addRemoveButton(this);
            });
        },
        addRemoveButton: function (progressBarcell) {
            var removeButton = $("<a href=\"#\" class=\"remove-button\" ><span class=\"glyphicon glyphicon-trash text-danger\"></span></a>");

            $(progressBarcell).append(removeButton);

            $(removeButton).click(function () {
                $(progressBarcell).parent().remove();
            });
        },
        afterUpload: function (status, xhr, progressBarcell) {
            if (status == 200) {
                $(progressBarcell).html('');

                uploadInput.addRemoveButton(progressBarcell);
            }
        },
        sendUpload: function (self, fileName, file) {
            var divProgressbar = $("<progress id=\"progress\" value=\"0\"></progress>");
            var divCancelUpload = $("<a href=\"#\" class=\"cancelUpload-button\" ></a>");

            $(divCancelUpload).append("<span class=\"glyphicon glyphicon-remove-circle text-danger\"></span>");

            var radioExcel = $("<input name=\"isfileexcel\" type=\"radio\" value=\"" + fileName + "\">");
            var hiddenFileListExcel = $("<input name=\"uploadedFiles[]\" type=\"hidden\" value=\"" + fileName + "\">");
            self.addFileUpload([
                fileName,
                radioExcel,
                $(divProgressbar),
                null,
                hiddenFileListExcel,
                divCancelUpload
            ]);

            $(radioExcel).click(function () {
                uploadInput.resetAllRemoveButton();
                var tr = $(this).parent().parent();
                var lastTd = $(tr).children(":nth-last-child(1)");
                $(lastTd).find("a.remove-button").remove();
            });

            return UploadFile(file, "<?php echo base_url("efill/lhkpnoffline/temp_upload"); ?>", divProgressbar, undefined, self.afterUpload, divCancelUpload);
        },
        getFilename: function (value) {
            return value.split('\\').pop();
        },
        initFrmUploadExcel: function () {

            if (!uploadInput.uploadInitialized) {
                var input = document.getElementById("file_xlsm"), self = this;

                input.addEventListener('change', function (e)
                {
                    if (this.files.length > 0) {
                        var i = 0;
                        while (i < this.files.length) {
                            self.sendUpload(self, this.files[i].name, this.files[i]);
                            i++;
                        }
                    }
                });

                uploadInput.uploadInitialized = true;
            }
        }
    };

    $(document).ready(function () {
        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
        $('.date-picker').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });

        penerimaan.init();
        uploadInput.init();

        $('input[name="LEMBAGA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga'); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        });

        $('#LEMBAGA').change(function (event) {
            var LEMBAGA = $('#LEMBAGA').val();
            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getJabatan'); ?>/" + LEMBAGA,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax("<?php echo base_url('index.php/share/reff/getJabatan'); ?>/" + LEMBAGA + '/' + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + '/' + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });

            $('input[name="JABATAN"]').prop('readonly', false);
            $('input[name="UNIT_KERJA"]').prop('readonly', false);
        });

        $("#btn-add-pn-individual").click(function (e) {
            window.location.href = base_url + 'ereg/all_pn/daftar_pn_individu/0/daftarindividu';
        });

        $("#div_after_screening").hide();
        $("#divUraianScreening").hide();

        $("#apreviewMessage").click(function (e) {
            e.preventDefault();

            var f = document.getElementById('previewMailScreeningForm');
            f.nama.value = $("#NAMA").val();
            f.inst_nama.value = $("#INST_NAMA").val();
            f.body.value = $("#URAIAN_SCREENING").val();

            window.open('', 'TheWindow');

            f.submit();

            return false;
        });

<?php if ($form_data): ?>
            penerimaan.setDefaultData();
<?php endif; ?>
    });

</script>