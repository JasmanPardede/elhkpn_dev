<script language="javascript">
    var gtblDaftarIndividual;
    var data_is_wl;
    var data_is_deleted;

    function cek_email(email) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/admin/user/cek_email/" + encodeURIComponent(email);
        $.post(url, function (data) {
            loading.hide();

            if (data == '1') {
                $('#fail', div).show();
                $("#email_ada").show();
                document.getElementById('EMAIL').value = "";
            } else {
                $('#success', div).show();
                $("#email_ada").hide();
            }
        });
    }

    function cek_email_pn(email) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/admin/user/cek_email_pn/" + encodeURIComponent(email);
        $.post(url, function (data) {
            loading.hide();
            $('#loader_area').show();
            if (data == '1')
            {
                $('#fail', div).show();
                $("#email_ada").show();
                document.getElementById('EMAIL').value = "";
            } else
            {
                $('#success', div).show();
                $("#email_ada").hide();
            }
        }).done(function () {
            $('#loader_area').hide();
        });
    }

    function cek_user(username) {
        var url = "index.php/ereg/all_pn/cek_user/" + username;
        $.post(url, function (data) {
            var msg = JSON.parse(data);
            if (msg.success == '1')
            {
                confirm('PN dengan NIK ' + username + ' telah tersedia. Apakah anda ingin merangkap jabatan?', function () {
                    $('#NAMA').val(msg.result.NAMA);
                    $('#NAMA').attr('readonly', 'readonly');
                    $('#JNS_KEL [value="' + msg.result.JNS_KEL + '"]').attr('selected', 'selected');
                    $('#JNS_KEL').attr('readonly', 'readonly');
                    $('#TEMPAT_LAHIR').val(msg.result.TEMPAT_LAHIR);
                    $('#TEMPAT_LAHIR').attr('readonly', 'readonly');
                    $('#TGL_LAHIR').val(msg.result.TGL_LAHIR);
                    $('#TGL_LAHIR').attr('readonly', 'readonly');
                    $('#ID_AGAMA [value="' + msg.result.ID_AGAMA + '"]').attr('selected', 'selected');
                    $('#ID_AGAMA').attr('readonly', 'readonly');
                    $('#ID_STATUS_NIKAH [value="' + msg.result.ID_STATUS_NIKAH + '"]').attr('selected', 'selected');
                    $('#ID_STATUS_NIKAH').attr('readonly', 'readonly');
                    $('#ID_PENDIDIKAN [value="' + msg.result.ID_PENDIDIKAN + '"]').attr('selected', 'selected');
                    $('#ID_PENDIDIKAN').attr('readonly', 'readonly');
                    $('#NPWP').val(msg.result.NPWP);
                    $('#NPWP').attr('readonly', 'readonly');
                    $('#ALAMAT_TINGGAL').val(msg.result.ALAMAT_TINGGAL);
                    $('#ALAMAT_TINGGAL').attr('readonly', 'readonly');
                    $('#EMAIL').val(msg.result.EMAIL);
                    $('#EMAIL').attr('readonly', 'readonly');
                    $('#NO_HP').val(msg.result.NO_HP);
                    $('#NO_HP').attr('readonly', 'readonly');
                    $('input [name="BIDANG"]').attr('readonly', 'readonly');
                    $('#FOTO').attr('readonly', 'readonly');
                    $('#TINGKAT').val(msg.result.TINGKAT);
                    $('#TINGKAT').attr('readonly', 'readonly');
                    $('#act').val('dorangkapjabatan');
                    $('#ID_PN').val(msg.result.ID_PN);
                });
            } else
            {
                $('#ajaxFormAdd input:text').val('');
                $('#ajaxFormAdd [type="email"]').val('');
                $('#ajaxFormAdd input:radio').removeAttr('checked');
                $('#ajaxFormAdd select').prop('selectedIndex', 0);
                $('#ajaxFormAdd select').removeAttr('readonly');
                $('#ajaxFormAdd input').removeAttr('readonly');
                $('#NIK').val(username);
                $("#username_ada").hide();
                $('#act').val('doinsert');
                $('#ID_PN').val('');
            }
        });
    }

    function cek_user_edit(username, current_username) {
        var div = $('#div-nik');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/ereg/all_pn/cek_user_edit/" + username + "/" + current_username;
        $.post(url, function (data) {
            loading.hide();
            if (data == '1')
            {
                $('#fail', div).show();
                $("#username_ada").show();
                document.getElementById('NIK').value = current_username;
                document.getElementById('check_uname_edit').innerHTML = username;
            } else
            {
                $('#success', div).show();
                $("#username_ada").hide();
            }
        });
    }

    function cek_nomor_hp(nohp, id) {
        alert(nohp);
        var div = $('#div-hp');
        var loading = $('#loading_hp', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_pn/cek_nomor_hp/" + nohp + '/' + id;
        $.post(url, function (data) {
            loading.hide();
            if (data == '1') {
                $('#hp_ada').hide();
            } else if (data == '2') {
                $('#hp_ada').hide();
            } else if (data == '3') {
                $('#hp_ada').show();
            }
            // else{
            //     $('#success_hp', div).show();
            //     $('#hp_ada').hide();
            // }
        });


    }
    ;
    function yesnoCheck() {
        if (document.getElementById('satu').checked) {
            $("#btn-add").show();
            $("#btn-add-exc").hide();
            $("#btn-add-webs").hide();
        } else {
            $("#btn-add").hide();
            $("#btn-add-exc").show();
            $("#btn-add-webs").show();
        }

    }
    var tblDaftarIndividual = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividual'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_page_index_wl/0'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari").val()});
                passData.push({"name": "CARI[IS_WL]", "value": $("#CARI_IS_WL").val()});
                passData.push({"name": "CARI[IS_DELETED]", "value": $("#CARI_IS_DELETED").val()});
                passData.push({"name": "CARI[TAHUN_WL]", "value": $("#CARI_TAHUN_WL").val()});
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed_string = [];

                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUK_NAMA') && !isEmpty(source.SUK_NAMA)) {
                            arr_showed_string.push(source.SUK_NAMA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {"mDataProp": "IS_WL", bSearchable: true},
                {"mDataProp": "TAHUN_WL", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed_string;
                        if (!isEmpty(source.tgl_kirim)) {
                            arr_showed_string = source.tgl_kirim;
                        } else {
                            arr_showed_string = "";
                        }
                        return  arr_showed_string;
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {
                        var is_del = $('#CARI_IS_DELETED').val();
                        var stl = false;
                        if (source.STS_J == 10 || source.STS_J == 11 || source.STS_J == 15) {
                            stl = true;
                        }

                        // var btnHapus = '<button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/ereg/all_pn/deletepn_wl_non/' + source.ID_PN + '/' + source.ID + '" title="Delete Non WL" onclick="onButton.delete.click(this);"><i class="fa fa-user-times"style="color:white;"></i></button>';
                        var btnHapus = '';

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/' + source.ID_PN + '/' + source.ID + '/' + source.TAHUN_WL + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var btnDetail2 = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/editpn_daftar_wl_nonaktifx/2/daftarindividu/' + source.ID_PN + '/' + source.ID + '" title="Aktifkan Non WL" onclick="onButton.detail2.click(this);"><i class="fa fa-upload"></i></button>';

                        var btnUndeleted = '<button type="button" class="btn btn-sm btn-success btn-undelete" href="index.php/ereg/all_pn/undeletepn_wl_non/' + source.ID_PN + '/' + source.ID + '" title="Aktifkan PN/WL" onclick="onButton.undelete.click(this);"><i class="fa fa-cloud-upload"></i></button>';

                        var btnDetail3 = '';
                        var disable = '', btnApprove = '', btnAksi = '';

                        if (is_del == 2){
                            if (!stl) {
                                btnAksi += btnDetail + ' ' + btnUndeleted;
                                if (source.ID_USER) {
                                    btnAksi += ' ' + btnDetail3;
                                }
                            }
                        }else{
                            if (!stl) {
                                btnAksi += btnDetail + ' ' + btnDetail2;
    <?php if ($this->makses->is_write): ?>
                                    btnAksi += ' ' + btnHapus;
    <?php endif; ?>
                                if (source.ID_USER) {
                                    btnAksi += ' ' + btnDetail3;
                                }
                            }
                        }

                        return (btnAksi).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                },
            ],
            "fnRowCallback": function (nRow, aData) {
                var stl = false;
                if (aData.STS_J == 10 || aData.STS_J == 11 || aData.STS_J == 15) {
                    stl = true;
                }
                return nRow;
            }
        }
    };

    var onButton = {
        go: function (obj, size) {

            if (!isDefined(size)) {
                size = 'large';
            }

            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Data Non Wajib Lapor', html, '', size);
            });
            return false;
        },
        detail: {
            click: function (self) {
                onButton.go(self);
            }},
        detail2: {
            click: function (self) {
                onButton.go(self);
            }},
        detail3: {
            click: function (self) {
                onButton.go(self);
            }
        },
        edit: {
            click: function (self) {
                onButton.go(self, 'large');
            }
        },
        delete: {
            click: function (self) {
                onButton.go(self);
            }
        },
        undelete: {
            click: function (obj, size) {
                //onButton.go(self);
            if (!isDefined(size)) {
                size = 'large';
            }

            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Data PN/WL Non Aktif', html, '', size);
            });
            return false;
            }
        }
    };

    var clearPencarian = function () {
        $('#CARI_IS_WL').val('');
        $('#CARI_IS_DELETED').val('');
        $('#CARI_INSTANSI').val('');
        $("#CARI_TAHUN_WL").val(dfcthn).trigger('change');
        $('#inp_dt_completeNEW_cari').val('');
        reloadTableDoubleTime(gtblDaftarIndividual);
    };
    var submitCari = function () {
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    var initiateCariInstansi = function () {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();
        var LEMBAGA = '1081';
<?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "7" || $this->session->userdata('ID_ROLE') == "10" || $this->session->userdata('ID_ROLE') == "13" || $this->session->userdata('ID_ROLE') == "14" || $this->session->userdata('ID_ROLE') == "18" || $this->session->userdata('ID_ROLE') == "31"): ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
    //            initiateSelect2CariUnitKerja('1081');
<?php else: ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
            LEMBAGA = '<?php echo $default_instansi; ?>';
<?php endif; ?>
        initiateSelect2CariUnitKerja(LEMBAGA);


        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
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
                var id = $('#CARI_INSTANSI').val();
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
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function (data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI').select2(cari_instansi_cfg);

                if (iins != null) {
                    $("#CARI_INSTANSI").val(iins).trigger("change");
//                    initiateSelect2CariUnitKerja(iins);
                }
            }
        });

    };
    var initiateSelect2CariUnitKerja = function (LEMBAGA) {

        var set_default_null = '';

        if (LEMBAGA !== '1081') {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
        } else {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
            set_default_null = "pencegahan";
        }

//        LEMBAGA = isDefined($('#CARI_INSTANSI').val()) ? $('#CARI_INSTANSI').val() : '<?php echo $default_instansi; ?>';

        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null=" + set_default_null,
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
                var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null=" + set_default_null, {
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
        };

        var dsuk = null;
        if (isDefined(LEMBAGA)) {
            var __UNIT_KERJA = $('#CARI_UNIT_KERJA').val();

            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null=" + set_default_null, {
                dataType: "json"
            }).success(function (data) {
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

                    $('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function (e) {
                        reloadTableDoubleTime(gtblDaftarIndividual);
                    });

                    if (dsuk != null) {
                        $("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
                    }
                }

            });
        }
    };

    $(document).ready(function () {
        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();
        data_is_wl = [{id: "", text: 'All'}, {id: 0, text: 'Belum'}, {id: 1, text: 'Sudah'}];
        $('#CARI_IS_WL').select2({data: data_is_wl});
        $("#CARI_IS_WL").val('');
        data_is_deleted = [{id: "", text: 'Non WL'}, {id: 2, text: 'Deleted'}];
        $('#CARI_IS_DELETED').select2({data: data_is_deleted});
        $("#CARI_IS_DELETED").val('');
        $("#CARI_TAHUN_WL").select2();

        initiateCariInstansi();

        $('#CARI_INSTANSI').change(function () {
            initiateSelect2CariUnitKerja($(this).val());
        });

        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });

        $("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('#CARI_IS_CALON').change(function () {
            $("#ajaxFormCari").trigger('submit');
        });

        $(".btn-detail").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Detail PN/WL', html, '', 'large');
            });
            return false;
        });

        $(".btn-detail2").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Data Non Wajib Lapor', html, '', 'large');
            });
            return false;
        });

        gtblDaftarIndividual = initDtTbl(tblDaftarIndividual);

        if ($("#CARI_UNIT_KERJA").val() === '') {
            reloadTableDoubleTime(gtblDaftarIndividual);
        }
    });

    $(function () {
        $('.btn-edit').click(function (e) {
            onButton.edit.click(this);
        });

        $('.btn-delete').click(function (e) {
            onButton.delete.click(this);
        });
        $('.btn-undelete').click(function (e) {
            onButton.delete.click(this);
        });
        $("#dt_completeNEW_filter").empty();

    });
</script>
