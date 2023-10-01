<script type="text/javascript">

    var uploadInput = {
        tableIds: {"tableSKM": "skm", "tableSKB": "skb", "tableIkhtisarHarta": "ikhtisar", "tableDokLainnya": "dok"},
        uploadProcessIds: {"tableSKM": "data_skm", "tableSKB": "data_skb", "tableIkhtisarHarta": "data_ikhtisar", "tableDokLainnya": "data_dok"},
        init: function () {
            this.initFrmUpload();
            $("a.remFile").click(function () {
                uploadInput.removeFile(this);
            });
        },
        removeFile: function (anchorObjRem, tblName) {
            if (isDefined(anchorObjRem) && isDefined(tblName)) {
                confirm("Apakah anda yakin menghapus data ? ", function () {
                    var tableName = tblName, upl = uploadInput.tableIds[tableName];
                    var trr = $(anchorObjRem).parent().parent(), fnm = $(trr).attr("fnm");

                    $.ajax({
                        url: "<?php echo base_url('efill/lhkpnoffline/removeFileLampiran'); ?>?ikin=<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>&inpkhl=<?php echo $id_imp_xl_lhkpn_secured; ?>&upl=" + upl,
                        data: {
//                            inpkhl: '<?php echo $item->ID_LHKPN; ?>',
                            fnm: fnm,
                            rid: '<?php echo $rand_id_path ?>',
                            origin: 'xlvalidation',
                        },
                        type: "POST",
                        success: function (data) {
                            if (data == '1') {
                                $(trr).remove();
                                alert("berkas berhasil dihapus.");
                            } else {
                                alert("berkas tidak dapat dihapus.");
                            }

                        }
                    });
                });


            }
            return false;
        },
        addFileUpload: function (rowUpload, tableName) {

            if (!isDefined(tableName)) {
                return;
            }

            if (isDefined(rowUpload)) {
                var td1 = $("<td class=\"td-nama-file\"></td>").append(document.createTextNode(rowUpload[0]));
                var td2 = $("<td class=\"td-lhkpn-excel\"></td>")
                        .append(rowUpload[1])
                        .append(rowUpload[3]);

                var tr = $("<tr fnm=\"" + rowUpload[0] + "\" dtp=\"" + uploadInput.tableIds[tableName] + "\" inpkhl=\"<?php echo $id_imp_xl_lhkpn_secured; ?>\"></tr>");
                $(tr).append(td1);
                $(tr).append(td2);

                $("#" + tableName + " tbody").prepend(tr);
            }
        },
        resetAllRemoveButton: function () {
            $(".td-aksi a.remove-button").remove();

            $(".td-aksi").each(function (index) {
                uploadInput.addRemoveButton(this);
            });
        },
        addRemoveButton: function (progressBarcell) {

            var self = this;
            var trr = $(progressBarcell).parent(), fnm = $(trr).attr("fnm");
            var tableId = $(trr).parent().parent().attr("id"), url = "", uri = self.tableIds[tableId];
//                url = "<?php echo base_url('uploads'); ?>/" + uri + "/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/" + fnm;
            var url = "<?php echo base_url('uploads/lhkpn_import_excel/temp/'); ?>/" + uri + "/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/" + fnm;
            if (uri == 'dok') {
                url = "<?php echo base_url(); ?><?php echo $path_temp_upload . $rand_id_path; ?>/" + fnm;
            }

//            console.log(url);
//            return;

            var removeButton = $("<a href=\"#\" class=\"btn btn-sm\" onclick=\"uploadInput.removeFile(this, '"+tableId+"');\" ><span class=\"glyphicon glyphicon-remove text-danger\"></span></a>");
            var previewButton = $("<a href=\"" + url + "\" class=\"btn btn-sm\" target=\"_blank\" ><span class=\"glyphicon glyphicon-new-window\"></span></a>");
            $(progressBarcell).append(removeButton).append(previewButton);

            $(removeButton).click(function () {
                self.removeFile(this);
            });
            $(previewButton).click(function () {

                console.log(url, "atas");
                if (uri == 'data_dok') {
                    url = "<?php echo base_url(); ?><?php echo $path_temp_upload . $rand_id_path; ?>/" + fnm;
                }
                console.log(url, "bawah");
                window.open(url, '_blank');
            });
        },
        saveAfterUpload: function (progressBarcell) {
            var tr = $(progressBarcell).parent();
            $.ajax({
                url: '<?php echo base_url("efill/lhkpnoffline/simpan_upload"); ?>',
                data: {
                    fnm: $(tr).attr('fnm'),
                    inpkhl: $(tr).attr('inpkhl'),
                    dtp: $(tr).attr('dtp')
                },
                method: 'POST',
                success: function (response) {

                }
            });
        },
        afterUpload: function (status, xhr, progressBarcell) {
            if (status == 200) {
                uploadInput.saveAfterUpload(progressBarcell);

                $(progressBarcell).html('');
                uploadInput.addRemoveButton(progressBarcell);
            }
        },
        sendUpload: function (self, fileName, file, tableName) {
            var divProgressbar = $("<progress id=\"progress\" value=\"0\"></progress>");
            var divCancelUpload = $("<a href=\"#\" class=\"cancelUpload-button\" ></a>");

            $(divCancelUpload).append("<span class=\"glyphicon glyphicon-remove-circle text-danger\"></span>");

            var hiddenFileList = $("<input name=\"uploadedFiles" + tableName + "[]\" type=\"hidden\" value=\"" + fileName + "\">");

            self.addFileUpload([
                fileName,
                $(divProgressbar),
                $("<a href=\"#\" class=\"removeFile text-danger\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></a>"),
                hiddenFileList,
                divCancelUpload
            ], tableName);

//            var upl = 'skb';
//            if (tableName == 'tableSKM') {
//                upl = 'skm';
//            }
            var upl = self.tableIds[tableName];

            return UploadFile(file, "<?php echo base_url("efill/lhkpnoffline/temp_upload"); ?>?upt=<?php echo encrypt_username(rand(200, 340), 'e'); ?>&ikin=<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>&inpkhl=<?php echo encrypt_username($ID_LHKPN, 'e'); ?>&upl=" + upl, divProgressbar, undefined, self.afterUpload, true);

        },
        getFilename: function (value) {
            return value.split('\\').pop();
        },
        initFrmUpload: function () {
            var inputFileSKM = document.getElementById("file1"), self = this;
            var inputFileSK = document.getElementById("file2");
            var inputFileIkhtisarharta = document.getElementById("file3");
            var inputFileDokLainnya = document.getElementById("file4");

            inputFileSKM.addEventListener('change', function (e) {
                if ($('#tableSKM tr').length < 2) {
                    if (this.files.length > 0) {
                        var i = 0;
                        while (i < this.files.length) {
                            self.sendUpload(self, this.files[i].name, this.files[i], "tableSKM");
                            i++;
                        }
                    }
                }
            });
            inputFileSK.addEventListener('change', function (e) {
//                if ($('#tableSKB tr').length < 3) {
                if (this.files.length > 0) {
                    var i = 0;
                    while (i < this.files.length) {
                        self.sendUpload(self, this.files[i].name, this.files[i], "tableSKB");
                        i++;
                    }
                }
//                }

            });
            inputFileIkhtisarharta.addEventListener('change', function (e) {
                if (this.files.length > 0) {
                    var i = 0;
                    while (i < this.files.length) {
                        self.sendUpload(self, this.files[i].name, this.files[i], "tableIkhtisarHarta");
                        i++;
                    }
                }


            });
            inputFileDokLainnya.addEventListener('change', function (e) {
//                if ($('#tableDokLainnya tr').length < 3) {
                if (this.files.length > 0) {
                    var i = 0;
                    while (i < this.files.length) {
                        self.sendUpload(self, this.files[i].name, this.files[i], "tableDokLainnya");
                        i++;
                    }
                }
//                }

            });
        }
    };

    $(document).ready(function () {
        uploadInput.init();
    });



</script>