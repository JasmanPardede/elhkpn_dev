<script type="text/javascript">
    var uploadInput = {
        init: function () {
            this.initFrmUpload();
            $("a.remFile").click(function () {
                uploadInput.removeFile(this);
            });
        },
        removeFile: function (anchorObjRem) {
            if (isDefined(anchorObjRem)) {

                var upl = 'sk', tableName = $(anchorObjRem).parent().parent().parent().parent().attr("id");
                var trr = $(anchorObjRem).parent().parent(), fnm = $(trr).attr("fnm");


                if (tableName == 'tableSKM') {
                    upl = 'skm';
                }

                $.ajax({
                    url: "<?php echo base_url('ever/verification/removeFileLampiran'); ?>?ikin=<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>&inpkhl=<?php echo encrypt_username($ID_LHKPN, 'e'); ?>&upl=" + upl,
                    data: {
                        inpkhl: '<?php echo $item->ID_LHKPN; ?>',
                        fnm: fnm,
                    },
                    type: "POST",
                    success: function (data) {
                        $(trr).remove();
                    }
                });

            }
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

                var tr = $("<tr fnm=\"" + rowUpload[0] + "\"></tr>");
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
            var removeButton = $("<a href=\"#\" class=\"btn btn-sm\" ><span class=\"glyphicon glyphicon-remove text-danger\"></span></a>");
            var previewButton = $("<a href=\"#\" class=\"btn btn-sm\" ><span class=\"glyphicon glyphicon-new-window text-danger\"></span></a>");
            var self = this;
            $(progressBarcell).append(removeButton).append(previewButton);

            $(removeButton).click(function () {
                //$(progressBarcell).parent().remove();
                self.removeFile(this);
            });
            $(previewButton).click(function () {
                var trr = $(progressBarcell).parent(), fnm = $(trr).attr("fnm");
                var tableId = $(trr).parent().parent().attr("id"), url = "";

                if (tableId == "tableSKM") {
                    url = "<?php echo base_url('uploads'); ?>/data_skm/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/" + fnm;
                }
                if (tableId == "tableSK") {
                    url = "<?php echo base_url('uploads'); ?>/data_sk/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/" + fnm;
                }
                window.open(url, '_blank');
            });
        },
        afterUpload: function (status, xhr, progressBarcell) {
            if (status == 200) {
                $(progressBarcell).html('');

                uploadInput.addRemoveButton(progressBarcell);
            }
        },
        sendUpload: function (self, fileName, file, tableName) {
            var divProgressbarProgress = $("<div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 30%\"><span class=\"sr-only\">40% Complete (success)</span></div>");

            var divProgressbar = $("<div class=\"progress\"></div>");

            $(divProgressbar).append(divProgressbarProgress);

            var hiddenFileList = $("<input name=\"uploadedFiles" + tableName + "[]\" type=\"hidden\" value=\"" + fileName + "\">");
            self.addFileUpload([
                fileName,
                $(divProgressbar),
                $("<a href=\"#\" class=\"removeFile text-danger\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></a>"),
                hiddenFileList,
            ], tableName);
            var upl = 'sk';
            if (tableName == 'tableSKM') {
                upl = 'skm';
            }
            
            return UploadFileSk(file, "<?php echo base_url("efill/lhkpnoffline/temp_upload"); ?>?ikin=<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>&inpkhl=<?php echo encrypt_username($ID_LHKPN, 'e'); ?>&upl=" + upl, divProgressbar, undefined, self.afterUpload, true);

        },
        getFilename: function (value) {
            return value.split('\\').pop();
        },
        initFrmUpload: function () {
            var inputFileSKM = document.getElementById("file1"), self = this;
            var inputFileSK = document.getElementById("file2"), self = this;

            inputFileSKM.addEventListener('change', function (e) {

                if ($('#tableSKM tr').length < 3) {
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

                if (this.files.length > 0) {
                    var i = 0;
                    while (i < this.files.length) {
                        self.sendUpload(self, this.files[i].name, this.files[i], "tableSK");
                        i++;
                    }
                }

            });
        }
    };

    var simpanUpload = {
        collectedDataSKM: [],
        collectedDataSK: [],
        init: function () {
            var self = this;
            $("#btn-simpan-upload").click(function () {
                self.sendUploadData();
            });
        },
        collectData: function () {
            var self = this;
            $("table#tableSK td.td-nama-file").each(function (index) {
                self.collectedDataSK.push($(this).text());
            });
            $("table#tableSKM td.td-nama-file").each(function (index) {
                self.collectedDataSKM.push($(this).text());
            });
        },
        sendUploadData: function () {
            var self = this;
            self.collectedDataSKM = [];
            self.collectedDataSK = [];
            self.collectData();
            $.ajax({
                url: '<?php echo base_url('ever/verification/uploadLampiran'); ?>',
                data: {
                    inpkhl: '<?php echo $item->ID_LHKPN; ?>',
                    skm: self.collectedDataSKM,
                    sk: self.collectedDataSK,
                },
                type: "POST",
                success: function (data) {

                }
            });
        }
    };

    $(document).ready(function () {
        uploadInput.init();
        simpanUpload.init();
    });
</script>