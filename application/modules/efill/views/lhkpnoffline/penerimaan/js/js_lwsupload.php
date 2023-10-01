<script type="text/javascript">

    /**
     * 
     * @param {type} file
     * @param {type} progress progress bar
     * @returns {undefined}
     */
    function UploadFile(file, actionUrl, progressBar_, allowedFileType, callbackIfDone, btnCancelUpload) {


        var progressBar = document.getElementById($(progressBar_).attr('id')), detachUploadFile, cancelingUploadFile;
        if (!isDefined(allowedFileType) || (isDefined(allowedFileType) && allowedFileType != false)) {
            allowedFileType = [
                "application/vnd.ms-excel",
                "application/vnd.ms-excel.sheet.macroEnabled.12",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "text/xml",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/pdf",
                "application/x-zip-compressed",
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

        detachUploadFile = function () {
            if (isDefined(btnCancelUpload)) {
                $(btnCancelUpload).unbind('click', cancelingUploadFile);
            }
        };

        cancelingUploadFile = function () {
            $(btnCancelUpload).parent().parent().remove();
            detachUploadFile();
            xhr.abort();
        };

        if (isDefined(btnCancelUpload)) {
            $(btnCancelUpload).click(cancelingUploadFile);
        }

        var xhr = new XMLHttpRequest();

        xhr.addEventListener('load', detachUploadFile, false);

//        if (xhr.upload && fileTypeOk && file.size <= $id("MAX_FILE_SIZE").value) {
        if (xhr.upload && fileTypeOk) {

            // create progress bar
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

            // progress bar

            xhr.upload.onloadstart = function (e) {
                progressBar.value = 0;
                progressBar.max = e.total;
            };

            xhr.upload.onprogress = function (pe) {
                if (pe.lengthComputable) {
                    progressBar.max = pe.total;
                    progressBar.value = pe.loaded;
                }
            };
            xhr.upload.onloadend = function (pe) {
                progressBar.value = pe.loaded;
            };

            var formData = new FormData();
            formData.append("file_import_excel_temp", file);
            formData.append("file_id", $("#random_id").val());

            // start upload
            xhr.open("POST", actionUrl, true);
            xhr.send(formData);

        }

    }
</script>