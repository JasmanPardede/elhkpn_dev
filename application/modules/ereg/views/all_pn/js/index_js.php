<script language="javascript">

    function cek_email(email) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/admin/user/cek_email/" + encodeURIComponent(email);
        $.post(url, function(data) {
            loading.hide();

            if (data == '1')
            {
                $('#fail', div).show();
                $("#email_ada").show();
                document.getElementById('EMAIL').value = "";
            }
            else
            {
                $('#success', div).show();
                $("#email_ada").hide();
            }
            ;
        });
    };
    function cek_email_pn(email) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/admin/user/cek_email_pn/" + encodeURIComponent(email);
        $.post(url, function(data) {
            loading.hide();
            $('#loader_area').show();
            if (data == '1')
            {
                $('#fail', div).show();
                $("#email_ada").show();
                document.getElementById('EMAIL').value = "";
            }
            else
            {
                $('#success', div).show();
                $("#email_ada").hide();
            }
            ;
        }).done(function() {
            $('#loader_area').hide();
        });
    };
    function cek_user(username) {
        // alert(username);
        var url = "index.php/ereg/all_pn/cek_user/" + username;
        // alert(url);
        console.log(url);
        $.post(url, function(data) {
            var msg = JSON.parse(data);
            if (msg.success == '1')
            {
                confirm('PN dengan NIK ' + username + ' telah tersedia. Apakah anda ingin merangkap jabatan?', function(){
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
            }
            else
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
            ;
        });
    };

    function cek_user_edit(username, current_username) {
        var div = $('#div-nik');
        var loading = $('#loading', div);
        $('img', div).hide();

        loading.show();
        var url = "index.php/ereg/all_pn/cek_user_edit/" + username + "/" + current_username;
        $.post(url, function(data) {
            loading.hide();
            if (data == '1')
            {
                $('#fail', div).show();
                $("#username_ada").show();
                document.getElementById('NIK').value = current_username;
                document.getElementById('check_uname_edit').innerHTML = username;
            }
            else
            {
                $('#success', div).show();
                $("#username_ada").hide();
            }
            ;
        });
    };

    function cek_nomor_hp(nohp, id) {
        alert(nohp);
        var div = $('#div-hp');
        var loading = $('#loading_hp', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_pn/cek_nomor_hp/" + nohp + '/' + id;
        $.post(url, function(data) {
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


    };

    function yesnoCheck() {
        if (document.getElementById('satu').checked) {
            $("#btn-add").show();
            $("#btn-add-exc").hide();
            $("#btn-add-webs").hide();
        }
        else {
            $("#btn-add").hide();
            $("#btn-add-exc").show();
            $("#btn-add-webs").show();
        }

    }

    var btnAktivasiOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Kirim Ulang Aktivasi', html, '');
        });
        return false;
    };
    var btnTerimaFormulirOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Terima Formulir Efilling', html, '');
        });
        return false;
    };
    var btnBatalTerimaFormulirOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Batal Terima Formulir Efilling', html, '');
        });
        return false;
    };

    function submitCetak() {
        var instansi = '';
        var uk = '';
        var text = ($('#inp_dt_completeNEW_cari_').val() == '') ? 'ALL' : $('#inp_dt_completeNEW_cari_').val();
        var tahun = ($('#CARI_TAHUN_WL').val() == '') ? 'ALL' : $('#CARI_TAHUN_WL').val();
        var is_wl = ($('#CARI_IS_WL').val() == '') ? 'ALL' : $('#CARI_IS_WL').val();
        if($('#CARI_INSTANSI').val()){
            instansi = ($('#CARI_INSTANSI').val() == '') ? 'ALL' : $('#CARI_INSTANSI').val();
        }else{instansi = 'ALL';}
        uk = ($('#CARI_UNIT_KERJA').val() == '') ? 'ALL' : $('#CARI_UNIT_KERJA').val();

        var url = '<?php echo site_url("/index.php/ereg/Cetak/export_pn_wl_online"); ?>/'+ text +'/' + tahun +'/' + instansi +'/' + uk +'/' + is_wl;
        window.location.href = url;
        return;
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
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_page_index'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari_").val()});
                passData.push({"name": "CARI[IS_WL]", "value": $("#CARI_IS_WL").val()});
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
                /* Update untuk Verifikasii*/
                // update kondisi user AI / AUK
                <?php if ($this->session->userdata('ID_ROLE') != "3" && $this->session->userdata('ID_ROLE') != "4"): ?>
                {
                    "mDataProp": function (source, type, val) {
                        var Status_Efilling;
                        if (source.IS_FORMULIR_EFILLING == 0) {
                            Status_Efilling = '<b><font color="red">Formulir Aktifasi Efilling Belum Diterima</font></b>';
                        }
                        else if (source.IS_FORMULIR_EFILLING == 1) {
                            Status_Efilling = '<b><font color="green">Formulir Aktifasi Efilling Sudah Diterima</font></b>';
                        }
                        return  Status_Efilling;
                    }
                },
                <?php endif; ?>
                {"mDataProp": "TAHUN_WL", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var yearMin1 = (new Date).getFullYear() - 1;
                        var stl = false;
                        if (source.STS_J == 10 || source.STS_J == 11 || source.STS_J == 15) {
                            stl = true;
                        }
//                        var btnHapus = '<button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/ereg/all_pn/deletepn/' + source.ID_PN + '/' + source.ID + '" title="Offlinekan PN/WL" onclick="onButton.delete.click(this);"><i class="fa fa-user-times"style="color:white;"></i></button>';


                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn/' + source.ID_PN + '/' + source.ID +  '/' + source.TAHUN_WL + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var btnDetailEdit = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/editpn_daftar_wl/2/daftarindividu/' + source.ID_PN + '/' + source.ID + '" title="Edit Data" onclick="onButton.detail2.click(this);"><i class="fa fa-archive"></i></button>';
                        /*
						var btnDetail2 = '<button type="button" class="btn btn-sm btn-success btn-detail2" href="index.php/ereg/all_pn/mts/' + source.ID + '/1" title="Perubahan Jabatan" onclick="onButton.detail2.click(this);"><i class="fa fa-archive"></i></button>';
						*/
                        var btnDetail3 = '<button type="button" class="btn btn-sm btn-success btn-detail" style="background-color: gray; color: white;"  href="index.php/ereg/all_pn/reset_password/' + source.ID_USER + '" title="Reset Password" onclick="onButton.detail3.click(this);"><i class="fa fa-reddit-square"style="color:white;"></i></button>';
                        var btnAktivasi = '<button type="button" class="btn btn-sm btn-warning btn-aktivasi" href="index.php/ereg/all_pn/kirimaktivasi/' + source.ID_USER + '" title="kirim ulang aktivasi" onclick="btnAktivasiOnClick(this);"><i class="fa fa-send"style="color:white;"></i></button>';
                        var btnTerimaFormulir ;
                        <?php if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2" || $this->session->userdata('ID_ROLE') == "31"): ?>
                            if(source.IS_FORMULIR_EFILLING == 0){
                                btnTerimaFormulir ='<button type="button" class="btn btn-sm btn-success btn-terima" href="index.php/ereg/all_pn/TerimaFormulir/' + source.ID_PN + '" title="Formulir Efilling Sudah Diterima" onclick="btnTerimaFormulirOnClick(this);"><i class="fa fa-check"style="color:white;"></i></button>';
                            }
                            else{
                                btnTerimaFormulir ='<button type="button" class="btn btn-sm btn-danger btn-terima" href="index.php/ereg/all_pn/BatalTerimaFormulir/' + source.ID_PN + '" title="Formulir Efilling Belum Diterima" onclick="btnBatalTerimaFormulirOnClick(this);"><i class="fa fa-close"style="color:white;"></i></button>';
                            }
                        <?php else: ?>
                            btnTerimaFormulir='';
                        <?php endif; ?>

                        var disable = '', btnApprove = '', btnAksi = '';

                        if (!stl) {
                            btnAksi += btnDetail ;
                            if (source.TAHUN_WL >= yearMin1) {
                                btnAksi += ' ' + btnDetailEdit;
                                <?php // if ($this->makses->is_write): ?>
                                //                                btnAksi += ' ' + btnHapus;
                                <?php // endif; ?>
                                if (source.ID_USER) {
                                    btnAksi += ' ' + btnDetail3;
                                    btnAksi += ' ' + btnAktivasi;
                                    // btnAksi += ' ' + btnTerimaFormulir;
                                }
                            }
                            if (source.ID_USER) {
                                btnAksi += ' ' + btnTerimaFormulir;
                            }
                            
                        }

                        return (btnAksi).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {


                var stl = false;
                if (aData.STS_J == 10 || aData.STS_J == 11 || aData.STS_J == 15) {
                    stl = true;
                }

                if (stl) {
                    $('td:eq(1)', nRow).parent().css('color', 'red');
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
                OpenModalBox('Wajib Lapor', html, '', size);
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
        }
    };
    var gtblDaftarIndividual;
    $(function () {

        $('.btn-edit').click(function (e) {
            onButton.edit.click(this);
        });

        $('.btn-delete').click(function (e) {
            onButton.delete.click(this);
        });

        gtblDaftarIndividual = initDtTbl(tblDaftarIndividual);
        if ($("#CARI_UNIT_KERJA").val() === '') {
            reloadTableDoubleTime(gtblDaftarIndividual);
        }
    });
    var clearPencarian = function () {
//        $('#CARI_IS_WL').val('');
//        $('#CARI_TAHUN_WL').val('');
//	$('#CARI_INSTANSI').val('');
//        initiateCariInstansi();
        $('#inp_dt_completeNEW_cari_').val('');
        reloadTableDoubleTime(gtblDaftarIndividual);
    };
    var submitCari = function () {
        reloadTableDoubleTime(gtblDaftarIndividual);
    };
    var initiateCariInstansi = function() {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();

        var LEMBAGA = '1081';
<?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "7" || $this->session->userdata('ID_ROLE') == "10" || $this->session->userdata('ID_ROLE') == "13" || $this->session->userdata('ID_ROLE') == "14" || $this->session->userdata('ID_ROLE') == "18" || $this->session->userdata('ID_ROLE') == "31" || $this->session->userdata('ID_ROLE') == "108"): ?>
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
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $('#CARI_INSTANSI').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga'); ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
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

    }/* End initiate Cari Instansi */
    var initiateSelect2CariUnitKerja = function(LEMBAGA) {
        var set_default_to_null = '';
        if (LEMBAGA !== '1081') {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
        }
        else {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
            set_default_to_null = 'pencegahan';
        }

        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null=" + set_default_to_null,
                dataType: 'json',
                quietMillis: 1000,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
//                LEMBAGA = isDefined($('#CARI_INSTANSI').val()) ? $('#CARI_INSTANSI').val() : '<?php echo $default_instansi; ?>';
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null=" + set_default_to_null, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var dsuk = null;

        if (isDefined(LEMBAGA)) {
            var __UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
//            LEMBAGA = isDefined($('#CARI_INSTANSI').val()) ? $('#CARI_INSTANSI').val() : '<?php echo $default_instansi; ?>';

            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null=" + set_default_to_null, {
                dataType: "json"
            }).success(function(data) {
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

                    $('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function(e) {
                        reloadTableDoubleTime(gtblDaftarIndividual);
                    });

                    if (dsuk != null) {
                        $("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
                    }
                }

            });
            set_default_to_null = '';
        }
    };

$(document).ready(function() {

        $(".pagination").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });

        $("#btn-detail").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Detail PN/WL', html, '', 'large');
            });
            return false;
        })
        $("#btn-detail2").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Perubahan Jabatan', html, '', 'large');
            });
            return false;
        })
        $("#btn-add").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah PN/WL', html, '', 'large');
            });
            return false;
        });
        $("#btn-add-exc").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah PN/WL', html, '', 'large');
            });
            return false;
        });
        $("#btn-add-webs").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah PN/WL', html, '', 'large');
            });
            return false;
        });
        $('.btn-edit').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Pembaharuan PN/WL', html, '', 'large');
            });
            return false;
        });
        $('.btn-keljab').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Riwayat Jabatan', html, '', 'large');
            });
            return false;
        });
        $('.btnNonaktifkan').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Non Aktifkan PN/WL', html, '', 'standart');
            });
            return false;
        });
        $('.btn-delete').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Set Offline PN/WL', html, '');
            });
            return false;
        });
        $('.btn-reset').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Reset Password', html, '', 'standart');
            });
            return false;
        });
        $('.btn-mutasi').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Mutasi Penyelenggara Negara', html, '', 'standart');
            });
            return false;
        });
        $('#btnPrintPDF').click(function() {
            var url = '<?php // echo $linkCetak; ?>/pdf';
            ng.exportTo('pdf', url, 'Cetak <?php // echo $titleCetak; ?>');
        });
        $('#btnPrintEXCEL').click(function() {
            var url = '<?php // echo $linkCetak; ?>/excel';
            ng.exportTo('excel', url);
        });
        $('#btnPrintWORD').click(function() {
            var url = '<?php // echo $linkCetak; ?>/word';
            ng.exportTo('word', url);
        });

        $('.btn-cetak').click(function(e) {
            e.preventDefault();
            <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
                if ($('#CARI_INSTANSI').val()){
                    submitCetak();
                }
                else{
                    alert('Mohon pilih salah satu instansi terlebih dahulu sebelum Export to Excel.');
                    return false;
                }
            <?php else: ?>
                submitCetak();
            <?php endif; ?>
        });

        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });
        $('#CARI_IS_CALON').change(function() {
            $("#ajaxFormCari").trigger('submit');
        });
        $('#CARI_INST').change(function() {
            $("#ajaxFormCari").trigger('submit');
        });
        var data_is_wl = [{id: 0, text: 'Belum'}, {id: 1, text: 'Sudah'}];
        $('#CARI_IS_WL').select2({
            data: data_is_wl
        });
        $("#CARI_TAHUN_WL").select2();
        initiateCariInstansi();
        $('#CARI_INSTANSI').change(function (event) {
        initiateSelect2CariUnitKerja($(this).val());
        });
        <?php // if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2"): ?>
//                initiateSelect2CariUnitKerja(1081);
        <?php // else: ?>
//                initiateSelect2CariUnitKerja(<?php // echo $this->session->userdata('INST_SATKERKD');?>);
        <?php // endif; ?>
    });
</script>
