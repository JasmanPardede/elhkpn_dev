<script type="text/javascript">

    var idWrapFormJabatan, uk_ = null, form;

    var valA = null, valUK = null, valSUK = null, valJAB = null;
    var Lengkap = true;

    function testDigitNik(Teks, total) {
        if (Teks < 16) {
            total.innerHTML = '<img id="fail1" src="<?php echo base_url('img/fail.png') ?>" width="24" /> tidak boleh kurang dari 16 Digit';
            document.getElementById("NIK").focus();
            return false;
        } else {
            total.innerHTML = ' <img id="nik_ada1" src="<?php echo base_url('img/success.png') ?>" width="24" /> sudah benar 16 digit';
        }
        return true;
    }

    function cek_emails(email, id) {

        var div = $('#div-email');

        if (!isDefined(email) || email.trim() == '' || email.trim().length == 0) {
            __email_failed_show(div);
            return false;
        } else {

            var loading = $('#loading', div);
            $('img', div).hide();
            loading.show();
            var url = "index.php/ereg/all_pn/cek_emails/" + encodeURIComponent(email);
            $.post(url, function (data) {
                loading.hide();
                if (data == '0') {
                    __email_failed_show(div);
                } else if (data == '1') {
                    __email_ok_show(div);
                } else {
                    __email_success_show(div);
                }
            });
        }
    }

    function cek_nohp(self, isSubmit) {
        var attrR = $(self).attr('required'), val = $(self).val();
        if (isDefined(attrR)) {

            if (((isDefined(val) && !$.isNumeric(val)) && !isEmpty(val)) || (isDefined(isSubmit) && isEmpty(val))) {
                if (!isDefined(isSubmit)) {
                    showNoHPAlert(self);
                }
                return false;
            }
        }
        return true;
    }

    function showNoHPAlert(selectorIdentification) {
        alertify.closeAll();
        alertify.alert('PERHATIAN!!', 'No HP tidak boleh Kosong dan Harus Angka').set('closable', true);

        if (!isDefined(selectorIdentification)) {
            selectorIdentification = $("#NO_HP");
        }
        var elementId = $(selectorIdentification).attr("id");

        document.getElementById(elementId).focus();
        $(selectorIdentification).val("");
    }

    function __email_failed_show(div) {
        $('#fail', div).show();
        $('div#email_ada').hide();
//                $('#email_ada').hide().css("visibility", "hidden").css("display", "none");

        $('div#email_salah').show();
//                $('#email_salah').show().css("visibility", "").css("display", "");
    }

    function __email_ok_show(div) {
        $('#fail', div).show();

        $('div#email_ada').show();
//                $('#email_ada').show().css("visibility", "").css("display", "");

        $('div#email_salah').hide();
//                $('#email_salah').hide().css("visibility", "hidden").css("display", "none");
    }


    function __email_success_show(div) {
        $('#success', div).show();

        $('div#email_ada').hide();
//                $('#email_ada').hide().css("visibility", "hidden").css("display", "none");
        $('div#email_salah').hide();
//                $('#email_salah').hide().css("visibility", "hidden").css("display", "none");
    }

    function clearvalue(element) {
        var pLEMBAGA = document.getElementById("LEMBAGA").id;
        if (element.change) {
            if (pLEMBAGA.id == 'LEMBAGA')
            {
                $("#LEMBAGA").val("");
            }
        }
    }

    function AutoCalculateMandateOnChange(element) {
        if (element.checked) {
            document.getElementById("EMAIL").required = true;
            document.getElementById("NO_HP").required = true;
            document.getElementById("span1").style.visibility = "visible";
            document.getElementById("span2").style.visibility = "visible";

            if ($("#EMAIL").val() !== '') {
                $("#email_salah").show();
            }

            $("#EMAIL, #NO_HP").addClass("field-required");
//            document.getElementById("email_salah").style.visibility = "visible";
        } else {
            document.getElementById("EMAIL").required = false;
            document.getElementById("NO_HP").required = false;
            document.getElementById("span1").style.visibility = "hidden";
            document.getElementById("span2").style.visibility = "hidden";
            $("#email_salah").hide();

            $("#EMAIL, #NO_HP").removeClass("field-required");
//            document.getElementById("email_salah").style.visibility = "hidden";
        }
    }

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

    function toFormJabatan() {
        var required = [];
        $(':input[required]', "#wrapperFormAddPN").each(function () {
            if (this.value.trim() !== '') {
                required.push('yes');
            } else {
                required.push('no');
            }
        });
        if (inArray('no', required) == false)
        {
            $("#wrapperFormAddPN").hide('slow', function () {
                $('#wrapperFormJabatan').show('slow');
            });
        } else {
            alertify.error('Mohon melengkapi data wajib !');
        }
    }

    function inArray(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] == needle)
                return true;
        }
        return false;
    }

    function toFormPN(ele) {
        var wrap = $(ele).closest('.wrap');

        wrap.hide('fast', function () {
            $("#wrapperFormAddPN").show('slow');
            $("#NIK").focus();
        });
    }

    var setLembagaOnchange = function () {

    };

    function dobIsNotValid17(self, isElement, needResponse) {
        var valOk = false, arrDob = [], dob = new Date();
//        if (this.value != '') {
        if (isDefined(self) != '') {
            valOk = true;
            if (isDefined(isElement) && isElement == true && $(self).val() != '') {
                arrDob = ($(self).val()).split('/');
            } else if (self != '' && isString()) {
                arrDob = (self).split('/');
            } else {
                valOk = false;
            }

        }

        if (valOk && isArray(arrDob) && !isEmpty(arrDob)) {
            dob = new Date(arrDob[2], arrDob[1], arrDob[0]);

            var today = new Date();
            var age = Math.ceil((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            return (age <= 17 && age <= 100);
        }

        return false;
    }

    function showDob17Alert(selectorIdentification) {
        alertify.closeAll();
        alertify.alert('PERHATIAN!!', 'Usia Tidak boleh kurang dari 17 Tahun').set('closable', true);

        if (!isDefined(selectorIdentification)) {
            selectorIdentification = $("#date-picker");
        }
        var elementId = $(selectorIdentification).attr("id");

        document.getElementById(elementId).focus();
        $(selectorIdentification).val("");
    }

    function evaluateIsSelect2(self) {
        if (isDefined(self) && $(self).data('select2')) {
            return true;
        }
        return false;
    }

    function formcheck() {
        return lhkpnFormCheck("#ajaxFormAdd");
    }

    function cekapliaksi(element) {
        if (element.checked) {
            if ($('#EMAIL').val() == '' || $('#NO_HP').val() == '') {
                Lengkap = false;
                alertify.alert('PERHATIAN!!', 'Periksa Kembali Email dan No HP').set('closable', true);
            } else {
                alertify.alert('PERHATIAN!!', 'Periksa Kembali Email dan No HP').set('closable', true);
            }
        }
    }

    $(document).ready(function () {

        $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
        var idWrapFormJabatan = $('#wrapperFormJabatan');
        $("#TGL_LAHIR").datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            maxDate: '-17y'
        });
//        $("#TGL_LAHIR" ).datepicker( "option", "maxDate", "+1m +1w" );
        $('#TGL_LAHIR').on('change', function () {
            if (dobIsNotValid17(this, true)) {
                showDob17Alert($('#TGL_LAHIR'));
            }
        });
        $("#TGL_LAHIR").attr("readonly", "readonly").attr("style", "    background-color: white;");
        form = $("#ajaxFormAdd");
        var msg = {
            success: 'Data Berhasil Disimpan!',
            error: 'Data Gagal Disimpan!'
        };

        $('input[name="KD_ISO3_NEGARA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getNegara') ?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getNegara') ?>/" + id, {
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
        $('input[name="PROV"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getProvinsi') ?>",
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getProvinsi') ?>/" + id, {
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
                prov = state.id;
                return state.name;
            }
        });
        $('input[name="PROV"]').on("change", function (e) {
            $('input[name="KAB_KOT"]').prop("disabled", false);

            $('input[name="KAB_KOT"]').select2("val", "");
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });
        $('input[name="KAB_KOT"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getKabupatenKota') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term,
                        prov: prov
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
                    $.ajax("<?php echo base_url('index.php/share/reff/getKabupatenKota') ?>/" + prov + '/' + id, {
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
                kab = state.id;
                return state.name;
            }
        });
        $('input[name="KAB_KOT"]').on("change", function (e) {
            $('input[name="KEC"]').prop("disabled", false);

            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });
        $("#btnLanjut").click(function (e) {
            $("#CARI_INST").val();
            $("#CARI_STATUS_PN").val();
            $("#CARI_TEXT").val($("#NIK").val());
            $("#CARI_TEXT").after('<input type="text" id="CARI_USEWHEREONLY" name="CARI[USEWHEREONLY]" value="1">');
            $("#ajaxFormCari").submit();
            $("#CARI_USEWHEREONLY").remove();
            CloseModalBox();
        });
        // jQuery Form Jabatan
        $('.datepicker', idWrapFormJabatan).datepicker({
            format: 'dd/mm/yyyy'
        });

        $('.date-picker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('input[name="LEMBAGA"]', idWrapFormJabatan).select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
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


        /**
         * UnitKerja onChange
         * @type jQuery|UNIT_KERJA
         */

        $('#UNIT_KERJA', idWrapFormJabatan).change(function (event) {
            var UNIT_KERJA = $(this).val();

            freeSelectionSelect2(["SUB_UNIT_KERJA", "JABATAN"]);

            if (evaluateIsSelect2(this)) {
                $('input[name="SUB_UNIT_KERJA"]', idWrapFormJabatan).select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
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
                            $.ajax("<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
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

                $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA,
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

                        // alert(id);
                        if (id !== "") {
                            $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + id, {
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
            } else {
                $(this).val('');
            }

        });

        /**
         * End Unit Kerja onChange
         
         * @param {type} event
         * @returns {undefined}         */

        /**
         * Lembaga onChange
         * @param {type} event
         * @returns {undefined}
         */
        $('#LEMBAGA', idWrapFormJabatan).change(function (event) {
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).prop('disabled', false);
            $('input[name="JABATAN"]', idWrapFormJabatan).prop('disabled', false);
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2('val', '');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('val', '');

            freeSelectionSelect2(["UNIT_KERJA", "SUB_UNIT_KERJA", "JABATAN"]);
            $('input[name="SUB_UNIT_KERJA"]', idWrapFormJabatan).select2('destroy');
            $('input[name="JABATAN"]', idWrapFormJabatan).select2('destroy');

            LEMBAGA = $(this).val();
            $('input[name="UNIT_KERJA"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + '/' + id, {
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

        });
        /**
         * End Lembaga onChange
         */

        $('#JABATAN', idWrapFormJabatan).change(function (event) {
            if (evaluateIsSelect2(this) == false) {
                $(this).val('');
            }
        });

    });

    var numberOnly = {
        pasteNIK:  function( e ) {
            var ele = $(this);
            setTimeout(function () {
                ele.val(ele.val().replace(/[^0-9]/g, ''));
            }, 5);
        }
    }

</script>