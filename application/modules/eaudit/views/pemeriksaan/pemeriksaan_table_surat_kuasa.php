<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }

    .inputFile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .inputFile + label {
        cursor: pointer;
        margin-left: 10px;
        /*font-size: 1.25em;*/
    }

    .inputFile:focus + label,
    .inputFile + label:hover {
        cursor: pointer;
        /*background-color: red;*/
    }

    .td-lhkpn-excel, .td-aksi{
        text-align: center;
    }

    .td-lhkpn-excel, .td-aksi, .td-nama-file{
        font-size: 12px;
        margin: 5px;
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Upload Surat Kuasa Mengumumkan dan Surat Kuasa"</h5>
</div>
<div class="box-body" id="wrapperDokumenPendukung">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="row">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Kuasa Mengumumkan</h3>
                </div>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <div class="row">
            <input type="file" id="file1" name="file1" class="inputFile" data-allowed-file-extensions='["pdf", "jpg", "png", "jpeg", "doc", "docx", "tif", "tiff"]'  accept='.pdf,.jpg,.png,.jpeg,.doc,.docx,.tif,.tiff'  data-show-preview="true" required multiple />
            <label for="file1" class="btn btn-sm btn-primary">Pilih File</label>
        </div>
        <div class="row">
            &nbsp;
            <input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN; ?>" required>
            <input type="hidden" name="ID" value="<?php echo $ID; ?>" required>
        </div>
        <table class="table table-bordered table-hover table-striped" id="tableSKM">
            <thead class="table-header">
                <tr>
                    <th>Upload Dokumen (pdf/doc(x)/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                <?php $ex_file = json_decode($LHKPN->FILE_BUKTI_SKM); ?>
                <?php foreach ($ex_file as $file): ?>
                    <tr fnm="<?php echo $file; ?>">
                        <td class="td-nama-file"><?php echo $file; ?></td>
                        <td class="td-lhkpn-excel">

                            <a class="btn btn-sm remFile" href="#"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                            <a class="btn btn-sm" target="_blank" href="<?php echo base_url('uploads'); ?>/data_skm/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/<?php echo $file; ?>"><span class="glyphicon glyphicon-new-window text-danger"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="row">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Kuasa</h3>
                </div>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <div class="row">
            <input type="file" id="file2" name="file2" class="inputFile"  data-allowed-file-extensions='["pdf", "jpg", "png", "jpeg", "doc", "docx", "tif", "tiff"]'  accept='.pdf,.jpg,.png,.jpeg,.doc,.docx,.tif,.tiff'  data-show-preview="true" required multiple />
            <label for="file2" class="btn btn-sm btn-primary">Pilih File</label>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <table class="table table-bordered table-hover table-striped" id="tableSK">
            <thead class="table-header">
                <tr>
                    <th>Surat Kuasa (pdf/doc(x)/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php $ex_file = json_decode($LHKPN->FILE_BUKTI_SK); ?>
                <?php foreach ($ex_file as $file): ?>
                    <tr fnm="<?php echo $file; ?>">
                        <td class="td-nama-file"><?php echo $file; ?></td>
                        <td class="td-lhkpn-excel">
                            <a class="btn btn-sm remFile" href="#"><span class="glyphicon glyphicon-remove text-danger"></span></a>
                            <a class="btn btn-sm" target="_blank" href="<?php echo base_url('uploads'); ?>/data_sk/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/<?php echo $file; ?>"><span class="glyphicon glyphicon-new-window text-danger"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div><!-- /.box-body -->
<div class="box-footer">
    <div class="col-md-2 col-md-offset-10">
        <a id="btn-simpan-upload" href="#" class="btn btn-sm btn-primary">Simpan</a>
    </div>
</div><!-- /.box-footer -->

<script type="text/javascript">

    var skmidx = 0;

    function UploadFileSk(file, actionUrl, progressBar, allowedFileType, callbackIfDone) {

        if (!isDefined(allowedFileType) || (isDefined(allowedFileType) && allowedFileType != false)) {
            allowedFileType = [
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/pdf",
                "text/plain",
                "image/jpeg",
                "image/png",
                "image/bmp",
                "image/gif",
                "image/tiff",
            ];
        }

        var fileTypeOk = jQuery.inArray(file.type, allowedFileType) !== -1;

        // following line is not necessary: prevents running on SitePoint servers
        if (location.host.indexOf("sitepointstatic") >= 0) {
            return;
        }

        var xhr = new XMLHttpRequest();
//        if (xhr.upload && fileTypeOk && file.size <= $id("MAX_FILE_SIZE").value) {
        if (xhr.upload && fileTypeOk) {

            // create progress bar
//            var o = $id("progress");
//            var progress = o.appendChild(document.createElement("p"));
//            progress.appendChild(document.createTextNode("upload " + file.name));


            // progress bar
            if (isDefined(progressBar)) {
                xhr.upload.addEventListener("progress", function (e) {
                    var pc = parseInt(100 - (e.loaded / e.total * 100));
//                    progress.style.backgroundPosition = pc + "% 0";
                    $(progressBar).attr("aria-valuenow", pc);
                }, false);
            }

            // file received/failed

            xhr.onreadystatechange = function (e) {
                if (xhr.readyState == 4) {

                    var isSuccess = (xhr.status == 200 ? "success" : "failure");
                    if (isDefined(progressBar)) {
                        var progressBarParent = $(progressBar).parent();
                        $(progressBarParent).html('');
                        $(progressBarParent).text(isSuccess);
                    }

                    if (isDefined(callbackIfDone)) {
                        callbackIfDone(xhr.status, xhr, progressBarParent);
                    }
                }
            };


            // start upload
//            xhr.open("POST", $id("upload").action, true);
            xhr.open("POST", actionUrl, true);

            var formData = new FormData();
            formData.append("file_import_excel_temp", file);
            formData.append("file_id", $("#random_id").val());

            xhr.send(formData);

        }

    }

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