<script type="text/javascript">
    var gtblDaftarPenambahan = null, gtblDaftarIndividualPerubahan = null, gtblDaftarIndividualNonAktive = null;

	var tblDaftarPenambahan2 = {

        tableId: 'dt_complete1',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarPenambahan'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/get_ver_pnwl_tambahan_three/'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);

                passData = passData.concat(getDataInstansiAndUnitKerja());
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
				passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                $.getJSON(sSource, passData, function (json) {
                    $("#spanTabPenambahan").html('(<b>' + json.iTotalRecords + '</b>)');
                    fnCallback(json);
                });
            },
            "aoColumns": [

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
                        var btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" onclick="editPerubahanJabatan(this);" href="index.php/ereg/All_ver_pn/popUpAdd/2/' + source.ID + '/xl" title="Edit"><i class="fa fa-pencil"></i></button>';

						var btnCancel = '<button class="btn btn-sm btn-danger" onclick="cancelPenambahan(' + source.IDUPLTEMP + ', 2)" data-toggle="tooltip" title="cancel"><i class="fa fa-close " style="color:white;"></i></button>';
                        return (btnEdit + " " + btnCancel).toString();
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
        tableId: 'dt_complete',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualPerubahan'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/get_ver_pnwl_jabatan_two'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData = passData.concat(getDataInstansiAndUnitKerja());
				passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
				passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                $.getJSON(sSource, passData, function (json) {
                    $("#spanTabPerubahan").html('(<b>' + json.iTotalRecords + '</b>)');
                    fnCallback(json);
                });
            },
            "aoColumns": [
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

                        var btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" onclick="editPerubahanJabatan(this);" href="index.php/ereg/All_ver_pn/popUpAdd/2/' + source.ID + '/xl/perub" title="Edit"><i class="fa fa-pencil"></i></button>';

						var btnCancel = '<button class="btn btn-sm btn-danger" onclick="cancelPenambahan(' +
						source.ID + ', 1)" data-toggle="tooltip" title="cancel"><i class="fa fa-close " style="color:white;"></i></button>';

						return (btnEdit + " " + btnCancel).toString();
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
        tableId: 'dt_complete2',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualNonAktive'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/get_ver_pnwl_nonact_two'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData = passData.concat(getDataInstansiAndUnitKerja());
				passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
				passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                $.getJSON(sSource, passData, function (json) {
                    $("#spanTabNonAktif").html('(<b>' + json.iTotalRecords + '</b>)');
                    fnCallback(json);
                });
            },
            "aoColumns": [
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
                        var btnCancel = '<button class="btn btn-sm btn-danger" onclick="cancelVerPN(' + source.ID_PN + ', ' + source.ID_PN_JAB + ', \'' + source.NAMA + '\')" data-toggle="tooltip" title="cancel"><i class="fa fa-close " style="color:white;"></i></button>';
                        return (btnCancel).toString();
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
        if (gtblDaftarPenambahan == null) {
            gtblDaftarPenambahan = initDtTbl(tblDaftarPenambahan2);
        } else {
            reloadTableDoubleTime(gtblDaftarPenambahan);
        }
    };

    var LoadDtTablePerubahan = function () {
        if (gtblDaftarIndividualPerubahan == null) {
            gtblDaftarIndividualPerubahan = initDtTbl(tblDaftarIndividualPerubahan2);
        } else {
            reloadTableDoubleTime(gtblDaftarIndividualPerubahan);
        }
    };

    var LoadDtTableNonAktive = function () {
        if (gtblDaftarIndividualNonAktive == null) {
            gtblDaftarIndividualNonAktive = initDtTbl(tblDaftarIndividualNonAktive2);
        } else {
            reloadTableDoubleTime(gtblDaftarIndividualNonAktive);
        }
    };

    var ExecDatasss = function () {
        LoadDtTablePenambahan();
        LoadDtTablePerubahan();
        LoadDtTableNonAktive();
    };


</script>