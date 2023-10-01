<script type="text/javascript">

<?php
$v_inst = is_null($item_data_jabatan->LEMBAGA) ? 0 : $item_data_jabatan->LEMBAGA;
$v_uk = is_null($item_data_jabatan->UNIT_KERJA) ? 0 : $item_data_jabatan->UNIT_KERJA;
$v_suk = is_null($item_data_jabatan->SUB_UNIT_KERJA) ? 0 : $item_data_jabatan->SUB_UNIT_KERJA;
$v_id_jab = is_null($item_data_jabatan->ID_JABATAN) ? 0 : $item_data_jabatan->ID_JABATAN;
?>

    $(document).ready(function () {

        $('#lembaga').select2({
            data: [],
            allowClear: true,
            ajax: {
                url: '<?php echo base_url('index.php/share/reff/getLembaga') ?>',
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true,
                minimumInputLength: 3
            },
            initSelection: function (element, callback) {
                var LEMBAGA = $('#lembaga').val();
                if (LEMBAGA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getInstansi') ?>/" + LEMBAGA, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }

        }).on("change", function (e) {
            var ID_LEMBAGA = $('#lembaga').val();
            GetUK(ID_LEMBAGA);
        });

<?php if (!is_null($item_data_jabatan)): ?>
    <?php if ($v_inst): ?>
                $("#lembaga").val('<?php echo $item_data_jabatan->LEMBAGA; ?>').trigger("change");
    <?php endif; ?>
            //            $("#uk").val(<?php echo $item_data_jabatan->UK_ID; ?>).trigger("change");
            //            $("#sub_uk").val(<?php echo $item_data_jabatan->SUK_ID; ?>).trigger("change");
            //            $("#jabatan").val(<?php echo $item_data_jabatan->ID_JABATAN; ?>).trigger("change");



            GetUK(<?php echo $v_inst; ?>);
            SubUK(<?php echo $v_uk; ?>);
            GetJabatan(<?php echo $v_uk; ?>, <?php echo $v_suk; ?>);
<?php else: ?>
            GetUK(0);
            SubUK(0);
            GetJabatan(0, 0);
<?php endif; ?>

    });

    function GetUK(ID_LEMBAGA) {
        $('#uk').select2({
            data: [],
            allowClear: true,
            ajax: {
                url: '<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/' + ID_LEMBAGA,
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true,
                minimumInputLength: 3
            },
            initSelection: function (element, callback) {
                var LEMBAGA = $('#lembaga').val();
                var UNIT_KERJA = $('#uk').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        }).on("change", function (e) {
            var ID_UK = $('#uk').val();
            SubUK(ID_UK);
            GetJabatan(ID_UK);
        });

        $('#uk').val('<?php echo $v_uk; ?>').trigger('change');
    }

    function SubUK(ID_UK) {
        $('#sub_uk').select2({
            data: [],
            allowClear: true,
            ajax: {
                url: '<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/' + ID_UK,
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true,
                minimumInputLength: 3
            },
            initSelection: function (element, callback) {
                var SUK = $('#sub_uk').val();
                var UNIT_KERJA = $('#uk').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + SUK, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        }).on("change", function (e) {
            var UK_ID = $('#uk').val();
            var SUK_ID = $('#sub_uk').val();
//            console.log($(this).val());
            GetJabatan(UK_ID, SUK_ID);
        });

        $('#sub_uk').val('<?php echo $v_suk; ?>').trigger('change');
    }

    function GetJabatan(UK_ID, SUK_ID) {
        SUK_ID = SUK_ID ? SUK_ID : 0;
        $('#jabatan').select2({
            data: [],
            allowClear: true,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + UK_ID + "/" + SUK_ID,
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true,
                minimumInputLength: 3
            },
            initSelection: function (element, callback) {
                var JAB = $('#jabatan').val();
                var SUK = $('#sub_uk').val();
                var UNIT_KERJA = $('#uk').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getJabatan3') ?>/" + UNIT_KERJA + "/" + SUK + "/" + JAB, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        });

        $('#jabatan').val('<?php echo $v_id_jab; ?>').trigger('change');
    }
</script>