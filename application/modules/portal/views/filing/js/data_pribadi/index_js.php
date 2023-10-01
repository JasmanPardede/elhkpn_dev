<script type="text/javascript">
    /**
     * 
     * @param {type} inputName
     * @param {type} inputValue
     * @param {type} irule Rule didapat dari attribute input box
     * @returns {Boolean}
     */
    var validateRequiredInput = function (inputName, inputValue, irule) {
        if (isDefined(inputName) && isDefined(inputValue) && isDefined(irule)) {
            var itemRequireFound = inArray("required", irule);

            if (!isEmpty(irule) && itemRequireFound !== false && isEmpty(inputValue)) {
//                var inpParent = $("input[name=" + inputName + "]").parent();
                var inpParent = $("#" + inputName).parent();
                $(inpParent).append("<span class=\"cls-harus-diisi\" style=\"color: red;\">Harus diisi.</span>");
            } else {
                console.log(inputName, 'true');
                return true;
            }
            console.log(inputName, 'false');

        }
        return false;
    };

    var get_detail;

    var real_foto = null, uploadFileOpt = {

        showUpload: false,
        showRemove: false,
        initialPreview: [],
        showBrowse: true,
        //button: false,
        overwriteInitial: true,
        initialCaption: false,
        showCaption: false
    }

    var initiate_data_pribadi = function () {

        $('html, body').animate({
            scrollTop: 0
        }, 2000);

        real_foto = $("#js_real_photo").val();

        $(function () {
        $('.over').popover();
        $('.over')
                .mouseenter(function (e) {
                    $(this).popover('show');
                })
                .mouseleave(function (e) {
                    $(this).popover('hide');
                });
    });

        uploadFileOpt.initialPreview = [
            "<img id='imgFotoProfil' src='" + base_url + "" + real_foto + "' class='file-preview-image' alt='Upload Foto' title='Upload Foto'>",
        ];
//		if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || STATUS == '7') {
//			document.getElementsByClassName('btn btn-primary btn-file').style.visibility = "hidden"; 
//		}
        $('#TANGGAL_LAHIR').datetimepicker({
            viewMode: 'years',
            format: "DD/MM/YYYY",
            maxDate: $.now()
        });

        $('.btn-sebelum').hide();
        $('.group-1,.btn-submit,.btn-do_next').hide();

        $("#foto").fileinput(uploadFileOpt);

        if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
            $('#btn-save').remove();
            var maybeDivButton = $("input#foto").parent();
            $(maybeDivButton).remove();
            $(".form-control").prop("disabled", true);
            $('select').select2("enable", false);
            $('#ID_PROPINSI').select2("enable", false);
            $('#ID_NEGARA').select2("enable", false);
        }

        $('.btn-lanjut').click(function () {
            $('.group-0,.btn-lanjut').hide();
            $('.group-1,.btn-submit,.btn-sebelum,.btn-do_next').fadeIn('slow');
            $('.lokal,.luar').hide();
            var negara = $('#NEGARA').val();
            if (negara == '1') {
                $('.luar').hide();
                $('.lokal').fadeIn('slow');
            } else if (negara == '2') {
                $('.lokal').hide();
                $('.luar').fadeIn('slow');
            } else {
                $('.lokal,.luar').hide();
            }

            if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
                $('#btn-save').remove();
                $(".select2-container .form-control .select2-allowclear").prop("disabled", true);
            }

        });


        $('.btn-sebelum').click(function () {
            $('.group-1,.btn-sebelum,.btn-submit,.btn-do_next').hide();
            $('.group-0,.btn-lanjut').fadeIn('slow');
        });

        var msg = {
            success: 'Data Berhasil Disimpan!',
            error: 'Data Gagal Disimpan!'
        };


        $(':file').change(function () {
            /* var file = this.files[0];
             name = file.name;
             size = file.size;
             type = file.type;*/
        });

        get_detail();
//        $.ajax({
//            url: base_url + 'portal/data_pribadi/data/' + ID_LHKPN,
//            dataType: 'json',
//            async: false,
//            success: function (data) {
//                if (!isEmpty(data)) {
//                    GetKota(data.ID_PROV);
//                }
//            }
//        });

//        GetKota($('#ID_PROPINSI').val());
        $('#ID_LHKPN').val(ID_LHKPN);

    };

    function GetKota(id) {
        $('#ID_KOTA').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>admin/apicontrol/getkota/' + id,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            /*var ID_KOTA = $('#ID_KOTA').val();
             var ID_PROPINSI = $('#ID_PROPINSI').val();*/
        });
    }

</script>
