<script type="text/javascript">

    var gtblDataJabatan, __primaryTblJabatan = function (self) {
        var id = $(self).attr('id');
        var data = do_edit('portal/data_jabatan/edit/' + id);
        $('#jab_set').text(data.NAMA_JABATAN);
        $('#IDJAB').val(data.ID);

        $('#myModalPrimary').modal('show');
    }, primaryTblJabatan = function (self) {
        var id = $(self).attr('kid');
        var data = do_edit('portal/data_jabatan/edit/' + id);
        $('#jab_set').text(data.NAMA_JABATAN);
        $('#IDJAB').val(data.ID);

        $('#myModalPrimary').modal('show');

    }, editTblJabatan = function (self) {
        var id = $(self).attr('kid');
        var data = do_edit('portal/data_jabatan/edit/' + id);
        GetUK(data.INST_SATKERKD);
        SubUK(data.UK_ID);
        GetJabatan(data.UK_ID, data.SUK_ID);

//               console.log(data.INST_NAMA);
        $('#ID').val(data.ID);
        $('#ID_LEM').val(data.INST_SATKERKD);

        $('#alamat_kantor').val(data.ALAMAT_KANTOR);
        $('#lembaga').select2("data", {id: data.INST_SATKERKD, text: data.INST_NAMA});
        $('#uk').select2("data", {id: data.UK_ID, text: data.UK_NAMA});
        $('#sub_uk').select2("data", {id: data.SUK_ID, text: data.SUK_NAMA});
        $('#jabatan').select2("data", {id: data.ID_JABATAN, text: data.NAMA_JABATAN});
        $('#lembaga').select2('disable');
        $('#myModal').modal('show');
    }, deleteTblJabatan = function (self) {
        var id = $(self).attr('kid');

        confirm("Apakah anda yakin akan menghapus data? ", function () {
            do_delete('portal/data_jabatan/delete/' + id, 'Data Jabatan Berhasil Di Hapus ');
            $('#TJabatan').DataTable().ajax.reload();
            reloadTableDoubleTime(gtblDataJabatan);
        });
        return false;
    }, tblDataJabatan = {
        tableId: 'TJabatan',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDataJabatan'},

        conf: {
            "sAjaxSource": '<?php echo base_url(); ?>portal/data_jabatan/tablejabatan/' + ID_LHKPN,
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                    if(json.iTotalRecords === 1){
                        $(".btn-danger").hide();
                    }
                });
            },
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
            "bLengthChange": false,
            "bAutoWidth": false,
            //"bSort": false,
            "bFilter": true,
            "aoColumns": [
                /*{
                 "mDataProp": function (source, type, val) {
                 return "";
                 },
                 bSearchable: false
                 },*/
                {"mDataProp": "NO_URUT_TABLE_COL", bSearchable: true, bSortable: false},
//                {"mDataProp": "NAMA_JABATAN", bSearchable: true, bSortable: false},
                {
                    "mDataProp": function (source, type, val) {

                        if (source.IS_PRIMARY == '1') {
                            var columnData = source.NAMA_JABATAN + '(<span class="fa fa-key"></span>)';
                            return  columnData;
                        } else {
                            var columnData = source.NAMA_JABATAN;
                            return  columnData;
                        }

                    },
                    bSearchable: true,
                    bSortable: false
                },
                {"mDataProp": "SUK_NAMA", bSearchable: true, bSortable: false},
                {"mDataProp": "UK_NAMA", bSearchable: true, bSortable: false},
                {"mDataProp": "INST_NAMA", bSearchable: true, bSortable: false},
                {
                    "mDataProp": function (source, type, val) {
                        var btnS = "";

                        $("#myModalPrimary button").removeAttr("disabled");

                        if (!(STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1')) {

                            var btnEdit = "<a kid='" + source.ID + "'  href='javascript:void(0)' onclick='editTblJabatan(this);' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                            var btnHapus = "";
                            btnHapus = "<a kid='" + source.ID + "'  href='javascript:void(0)' onclick='deleteTblJabatan(this);' class='btn btn-danger btn-sm delete-action' title='Delete'><i class='fa fa-trash'></i></a>";

                                if (source.IS_PRIMARY){
                                    btnHapus = "<a kid='" + source.ID + "'  href='javascript:void(0)' onclick='deleteTblJabatan(this);' class='btn btn-danger btn-sm delete-action' title='Delete'><i class='fa fa-trash'></i></a>";
                                    btnHapus = "";
                                }

                            var btnPrimary = "";

                            //                        if (source.TOTAL_ROWS > 1) {
                            btnPrimary = "<a kid='" + source.ID + "' onclick='primaryTblJabatan(this);' title='Set Sebagai Jabatan Utama' class='btn btn-primary btn-sm primary-action'><i class='fa fa-key'></i></a>";
                            //                            console.log(source.IS_PRIMARY);
                            if (source.IS_PRIMARY) {
                                btnPrimary = "<a kid='" + source.ID + "'  href='javascript:void(0)' onclick='primaryTblJabatan(this);' class='btn btn-primary btn-sm primary-action' title='Set Jabatan Utama'><i class='fa fa-check'></i></a>";
                                btnPrimary = "";
                            }
                            //                        }

                            btnS = (btnEdit + " " + btnPrimary + " " + btnHapus).toString();


                        }
                        return btnS;
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ]
        }
    };

    $(document).ready(function () {
        gtblDataJabatan = initDtTblSimpleSearch(tblDataJabatan);
    });
</script>