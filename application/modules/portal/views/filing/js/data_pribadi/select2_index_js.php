<script type="text/javascript">
    var initiate_select2 = function () {
        $("#JENIS_KELAMIN").select2({
            allowClear: true
        });

        $("#STATUS_PERKAWINAN").select2({
            allowClear: true
        });

        $("#AGAMA").select2({
            allowClear: true
        });

        $("#NEGARA").select2({
            allowClear: true
        }).on("change", function () {
            if ($(this).val() == '1') {
                $('#ID_PROPINSI').select2('data', null);
                GetKota(0);
                $('.luar').hide();
                $('.lokal').fadeIn('slow');
            } else if ($(this).val() == '2') {
                $('.lokal').hide();
                $('.luar').fadeIn('slow');
            } else {
                $('.lokal,.luar').hide();
            }
        });

        $('#ID_NEGARA').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>admin/apicontrol/getnegara',
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
        });

        $('#ID_PROPINSI').select2({
            allowClear: true,
            ajax: {
//                url: '<?php echo base_url(); ?>portal/filing/getprovinsi',
                url: '<?php echo base_url(); ?>admin/apicontrol/GetProvinsi',
                dataType: 'json',
                quietMillis: 100,
                data: function (term, page) {
                    return {
                        q: term, // search term
                        //pageLimit: 10,
                        //page: page
                    };
                },
                results: function (data, page) {
                    var myResults = [], more = (page * 10) < data.total;
//                    $.each(data.province, function (index, item) {
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults,
//                        more: more
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            var value = $('#ID_PROPINSI').val();
            GetKota(value);
        });
    };
</script>