<script type="text/javascript">
//var gtblDaftarPenambahan = null, gtblDaftarIndividualPerubahan = null, gtblDaftarIndividualNonAktive = null;

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
            }}
    };

    /**
     * lahir ganteng
     * 09 - 02 - 2017
     
     * @type type */


    var tblDaftarPenambahan2 = {

        tableId: 'tblVerPnwlXl2_new',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarPenambahan'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/get_ver_pnwl_tambahan_three/'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                $.getJSON(sSource, passData, function (json) {
                    $("#spanTabPenambahan").html('(<b>' + json.iTotalRecords + '</b>)');
                    fnCallback(json);
                });
            },
            "aoColumns": [

                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed_3 = [];
                        // if (isObjectAttributeExists(source, 'ID_PN_JAB') && !isEmpty(source.ID_PN_JAB)) {
                        //     arr_showed_3.push(source.ID_PN_JAB);
                        // }
                        if (isObjectAttributeExists(source, 'ID') && !isEmpty(source.ID)) {
                            arr_showed_3.push(source.ID);
                        }
                        // arr_showed_3.join(',');
                        var btchk3 = '<input class="chk3" type="checkbox" onclick="chkPenambahan(this);" value="' + arr_showed_3 + '" name="chk3">';
                        return (btchk3).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                },
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NIP_NRP", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var strike = "";
                        var ket_dis = "";
                        if (source.CEK != 'ada') {
                            strike = '<s>';
                            ket_dis = "<?php FormHelpPopOver('nomatchdb'); ?>";
                        }

                        var columnData = '<b>' + ket_dis + strike + source.NAMA_JABATAN + '</b> - ' + source.NAMA_SUB_UNIT_KERJA + ' - ' + source.NAMA_UNIT_KERJA;
                        return  columnData;
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {
                        var vdisable = source.CEK != 'ada' ? 'disabled' : '';

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_vertambah/' + source.ID + '/' + source.ID + '/' + vdisable + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var btnSavePenambahan = '<button ' + vdisable + ' class="btn btn-sm btn-info" onclick="savePenambahanPNWL(' + source.ID + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';

                        var btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" onclick="editPerubahanJabatan(this);" href="index.php/ereg/All_ver_pn/popUpAdd/2/' + source.ID + '/xl" title="Edit"><i class="fa fa-pencil"></i></button>';

                        var btnCancel = '<button class="btn btn-sm btn-danger" data-toggle="tooltip" onclick="cancelPenambahan(' + source.ID + ', 2)" title="cancel"><i class="fa fa-close " style="color:white;"></i></button>';

                        return (btnDetail + " " + btnSavePenambahan + " " + btnEdit + " " + btnCancel).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                return nRow;
            }
        }
    };

    var tblDaftarIndividualPerubahan2 = {
        tableId: 'tblVerPnwlXl1_new',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualPerubahan'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/get_ver_pnwl_jabatan_two'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                $.getJSON(sSource, passData, function (json) {
                    $("#spanTabPerubahan").html('(<b>' + json.iTotalRecords + '</b>)');
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'ID') && !isEmpty(source.ID_PN)) {
                            arr_showed_string.push(source.ID);
                        }
                        if (isObjectAttributeExists(source, 'INST_SATKERKD') && !isEmpty(source.ID)) {
                            arr_showed_string.push(source.INST_SATKERKD);
                        }
                        arr_showed_string.join(',');
                        var btxchk = '<input class="chk" type="checkbox" onclick="chk(this);" value="' + arr_showed_string + '"  name="chk1">';
                        return (btxchk).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                },
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {"mDataProp": "NAMA_JABATAN", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var strike = "";
                        var back_col = '#b2dba1';
                        var ket_dis = "";
                        if (source.CEK != 'ada') {
                            back_col = 'salmon';
                            strike = '<s>';
                            ket_dis = "<?php FormHelpPopOver('nomatchdb') ?>";
                        }

                        var columnData = ket_dis + ' <small>' + strike + ' ' + source.NAMA_JABATAN_TEMP + '</small>';
                        return  columnData;
                    },
                    bSortable: false
                },
                {
                    "mDataProp": function (source, type, val) {
                        var vdisable = source.CEK != 'ada' ? 'disabled' : '';

                        var btnApprovePerubahan = '<button ' + vdisable + ' class="btn btn-sm btn-info" onclick="saveUbahJabPNWL(' + source.ID + ',' + source.INST_SATKERKD + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button> ';

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_verrubah/' + source.ID_PN + '/' + source.ID + '/' + vdisable + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" onclick="editPerubahanJabatan(this);" href="index.php/ereg/All_ver_pn/popUpAdd/1/' + source.ID + '/xl/isperub" title="Edit"><i class="fa fa-pencil"></i></button>';

                        var btnCancel = '<button class="btn btn-sm btn-danger" onclick="cancelPenambahan(' + source.ID + ', 1)" data-toggle="tooltip" title="cancel"><i class="fa fa-close " style="color:white;"></i></button>';
                        return (btnDetail + " " + btnApprovePerubahan + " " + btnEdit + " " + btnCancel).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                return nRow;
            }
        }
    };

    var tblDaftarIndividualNonAktive2 = {
        tableId: 'tblVerPnwlXl3_new',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualNonAktive'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/get_ver_pnwl_nonact_two'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
//                passData = passData.concat(getDataInstansiAndUnitKerja());
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                if ($("#CARI_UNIT_KERJA").val() !== '') {
                    passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                } else {
                    document.getElementById("CARI_UNIT_KERJA").value = "-";
                    passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                }
                $.getJSON(sSource, passData, function (json) {
                    $("#spanTabNonAktif").html('(<b>' + json.iTotalRecords + '</b>)');
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed = [];
                        if (isObjectAttributeExists(source, 'ID_PN_JAB') && !isEmpty(source.ID_PN_JAB)) {
                            arr_showed.push(source.ID_PN_JAB);
                        }
                        if (isObjectAttributeExists(source, 'ID_PN') && !isEmpty(source.ID_PN)) {
                            arr_showed.push(source.ID_PN);
                        }
                        arr_showed.join(',');
                        var btchk = '<input class="chk2" type="checkbox" onclick="chkNonWajibLapor(this);" value="' + arr_showed + '" name="chk2">';
                        return (btchk).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                },
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var columnData = '<b>' + source.NAMA_JABATAN + '</b> - ' + source.SUK_NAMA + ' - ' + source.UK_NAMA;
                        return  columnData;
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_vernonaktif/' + source.ID_PN + '/' + source.ID_PN_JAB + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var btnApproveNonActPN = '<button class="btn btn-sm btn-info" onclick="ApproveNonActPN(' + source.ID_PN_JAB + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';

                        var btnCancel = '<button type="button" class="btn btn-sm btn-danger"  onclick="cancel_nonactxls(' + source.ID_PN + ',' + source.ID_PN_JAB + ', \'' + source.NAMA + '\')" title="Cancel Non Active "><i class="fa fa-repeat"></i></button>';

                        return (btnDetail + " " + btnApproveNonActPN + " " + btnCancel).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                return nRow;
            }
        }
    };
    
     var LoadDtTablePenambahan = function () {
//    function LoadDtTablePenambahan() {
        if (gtblDaftarPenambahan == null) {
            gtblDaftarPenambahan = initDtTbl(tblDaftarPenambahan2);
        } else {
            reloadTableDoubleTime(gtblDaftarPenambahan);
        }
    }

     var LoadDtTablePerubahan = function () {
//    function LoadDtTablePerubahan() {
        if (gtblDaftarIndividualPerubahan == null) {
            gtblDaftarIndividualPerubahan = initDtTbl(tblDaftarIndividualPerubahan2);
        } else {
            reloadTableDoubleTime(gtblDaftarIndividualPerubahan);
        }
    }

    var LoadDtTableNonAktive = function () {
//    function LoadDtTableNonAktive() {
        if (gtblDaftarIndividualNonAktive == null) {
            gtblDaftarIndividualNonAktive = initDtTbl(tblDaftarIndividualNonAktive2);
        } else {
            reloadTableDoubleTime(gtblDaftarIndividualNonAktive);
        }
    }
    
    function ExecDatasss() {
        LoadDtTablePenambahan();
        LoadDtTablePerubahan();
        LoadDtTableNonAktive();
    }

</script>