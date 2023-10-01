<script src="<?php echo base_url(); ?>/plugins/alertifyjs/alertify.js"></script>
<script src="<?php echo base_url(); ?>/plugins/alertifyjs/alertify.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/alertifyjs/css/alertify.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/alertifyjs/css/alertify.rtl.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/alertifyjs/css/themes/default.min.css" />
<?php
	function func_encrypt( $string, $action = 'e' ) {
		// you may change these values to your own
			$secret_key = 'R@|-|a5iaKPK';
			$secret_iv = 'R@|-|a5ia|/|394124';

			$output = false;
			$encrypt_method = "AES-256-CBC";
			$key = hash( 'sha256', $secret_key );
			$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

			if( $action == 'e' ) {
				$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
			}
			else if( $action == 'd' ){
				$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
			}
			//var_dump($output);exit;
			return $output;
	}
	function deleteDir($dirPath) {
		if (! is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				Vars::deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
?>
<script type="text/javascript">

    var submitFormDataPribadi = function (self) {
        $("form#submitdatapribadi").submit();
    };

    var __reloadProfilPicture = function () {
		<?php
		$NIK_Enc = func_encrypt($NIK,"e");
		$NIKLengkap = $NIK_Enc;
		$NIKLengkap .= $NIK;
		?>
		var myvar = "<?php echo $NIKLengkap; ?>";
        
        var rndTxt = Math.random() * (2000 - 20) + 20;
        var divFilePreview = $("#imgFotoProfil").parent(".file-preview-frame");
        $("#imgFotoProfil").remove();
		var real_foto;

		real_foto = base_url + 'images/no_available_image.png';
		<?php
			$dir = 'uploads/data_pribadi/' . $NIKLengkap . '/';
			if (file_exists('uploads/data_pribadi/' . $NIK . '/foto.jpg')){
				$file = 'uploads/data_pribadi/' . $NIK . '/foto.jpg';
				$newfile = 'uploads/data_pribadi/' . $NIKLengkap . '/foto.jpg';
				if (is_dir($dir) === false){
					mkdir($dir);
				}
				if (copy($file, $newfile)){
					deleteDir('uploads/data_pribadi/' . $NIK . '/');
				}
			}
		//	if (file_exists('uploads/data_pribadi/' . $NIKLengkap . '/foto.jpg')):
		?>
            real_foto = 'uploads/data_pribadi/' + myvar + '/foto.jpg?vimg=' + rndTxt;
			
            //////load image from minio////
            var varMinio = "<?php echo linkFromMinio('uploads/data_pribadi/'.$NIKLengkap.'/foto.jpg',null,'t_pn','NIK',$NIK) ?>";
            var varMinioShortcut = "<?php echo linkFromMinio('uploads/data_pribadi/'.$NIK.'/foto.jpg',null,'t_pn','NIK',$NIK) ?>";
            if(varMinio==0){
                if(varMinioShortcut==0){
                    <?php if(file_exists('uploads/data_pribadi/'.$NIKLengkap.'/foto.jpg') || file_exists('uploads/data_pribadi/'.$NIK.'/foto.jpg')): ?>
                    real_foto = base_url + real_foto;
                    <?php Else: ?>
                    real_foto = base_url + 'images/no_available_image.png';
                    <?php EndIf; ?>
                }else{
                    real_foto = varMinioShortcut;
                }
            }else{
                real_foto = varMinio;
            }
		<?php // EndIf; ?>
        $(divFilePreview).append("<img id='imgFotoProfil' src='" + real_foto + "' class='file-preview-image' alt='Upload Foto' title='Upload Foto'>");
        return true;
    };

    var _submitFormDataPribadi = function (self) {
//        console.log(self);

        /**
         * VARIABLE STATUS :
         * lihat di db tabel t_lhkpn
         *
         * NOTE : untuk Admin/PIC/Rekan KPK, komentar ini tidak perlu diupload di Production
         *        Juga tidak untuk dihapus
         *
         * @type jQuery
         */
        if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
            return false;
        }

        $(".cls-harus-diisi").remove();
        Loading('show');

        var urll = $(self).attr('relaction');

//            document.forms.namedItem("submitdatapribadi")

        var formData = new FormData($(self)[0]), objFrmData = {}, inputRequiredOk = new Array(), i = 0;
//            console.log(formData);
//            return false;

        var dataFalse = [], dfFound = 0;
        for (var pair of formData.entries()) {

            objFrmData[pair[0]] = pair[1];
//            var irules = $("input[name=" + pair[0] + "]").attr('irule'), arrRules = [];
            var irules = $("#" + pair[0]).attr('irule'), arrRules = [];

            if (isDefined(irules)) {

                /**
                 * Rule didapat dari attribute input box
                 */
                arrRules = irules.split('|');
                if (pair[0] == 'ID_NEGARA' && $("#NEGARA").val() == '1') {
                    var idxOfReqRules = $.inArray('required', arrRules);
                    if (idxOfReqRules != -1) {
                        arrRules.splice(idxOfReqRules, 1);
                    }

                    $("#ALAMAT_NEGARA").attr("irule", "");
                }
                var isInputValid = validateRequiredInput(pair[0], pair[1], arrRules);

                inputRequiredOk[i] = isInputValid;

                if (!isInputValid) {
                    if ((pair[0] == 'ALAMAT_NEGARA' && $("#NEGARA").val() == '1') == false) {
                        var txtLbl = $("#LBL_" + pair[0]).text().replace(" *", "");
                        dataFalse.push(txtLbl);
                        dfFound++;
                    }
                }

                i++;
            }
        }

        if (!checkFileSize('foto')) {

            Loading('hide');
            notif('Data batal disimpan, cek kembali upload foto profil anda. Maksimum file yang diperbolehkan adalah 500 kilobyte.');
//            alert('Data batal disimpan, cek kembali upload foto profil anda.<br />Maksimum file yang diperbolehkan adalah 500 kilobyte.');
            return false;
        }

        if ($.inArray(false, inputRequiredOk) != -1) {
            Loading('hide');

            if (dfFound > 0) {
                var strFields = dataFalse.join();
                notif('Data batal disimpan, cek kembali kolom isian ' + strFields + '. kolom tersebut harus diisi.');
            }

        } else {
            text = 'Data Pribadi Berhasil Di Update';
            urll += "/nkiad987a9d87faosjdnfao8sdyfak";
            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                crossDomain: true,
                success: function (htmldata) {
//                        htmldata = JSON.parse(htmldata);
                    Loading('hide');
                    if (htmldata.status == 0) {
                        notif('Silahkan refresh halaman ini atau login ulang.');
                    } else if (htmldata == 2) {
                        notif('Mohon Maaf , email sudah digunakan !!');
                    } else if (htmldata == 9) {
                        notif('Anda tidak memiliki akses pada data ini !!');
                    }  else {
                            // success(text);
                            alert(text, "Sukses Simpan Data");
                            __reloadProfilPicture();
                        }
                    },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        return false;
    };

    var checkFileSize = function (elementInputFIle) {
        var input, file;

        if (!isDefined(elementInputFIle)) {
            elementInputFIle = 'fileinput';
        }

        // (Can't use `typeof FileReader === "function"` because apparently
        // it comes back as "object" on some browsers. So just see if it's there
        // at all.)
        if (!window.FileReader) {
//            bodyAppend("p", "The file API isn't supported on this browser yet.");
            return false;
        }

        input = document.getElementById(elementInputFIle);

        /**
         * if No file selected
         */
        if (input.files.length == 0) {
            return true;
        }

        if (!input || !input.files || !input.files[0]) {
            return false;
        } else {
            file = input.files[0];

            if (file.size > 1000000) {
                alert("Ukuran File yang diunggah terlalu besar.<br />Maksimum file yang diperbolehkan adalah 500 kilobyte.");
                return false;
            }
            return true;
        }
    }

    $(document).ready(function () {
        $("form#submitdatapribadi").submit(function (e) {
//            e.preventDefault();
            _submitFormDataPribadi(this);
            _submitFormDataPribadi(this);
        });

    });
</script>
