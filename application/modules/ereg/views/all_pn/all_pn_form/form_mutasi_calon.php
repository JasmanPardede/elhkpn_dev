<script type="text/javascript">
<?php
$arr_status = array();
foreach ($status_akhir as $status) {
    $arr_status[$status->ID_STATUS_AKHIR_JABAT] = $status;
}
?>
    var status_akhir = '<?php echo json_encode(array('result' => $arr_status)) ?>';
    var data_status_akhir = JSON.parse(status_akhir);</script>
<div id="wrapperFormMutasi">
    <form class="form-horizontal" method="post" id="ajaxFormMutasi" action="index.php/ereg/all_pn/save_perubahan_calon/<?php echo $item_pn->ID_PN; ?>">
        <input type="hidden" name="pn" id="pn" value="<?php echo $item_pn->ID_PN; ?>">
        <input type="hidden" name="ID_JABATAN_ASAL" id="ID_JABATAN_ASAL" value="<?php echo $jabatan->ID; ?>">
        <input type="hidden" name="ID_PN_JABATAN" id="ID_PN_JABATAN" value="<?php echo $jabatan->ID; ?>">

        <!-- <div class="form-group">
            <label class="col-sm-4 control-label">Jenis Mutasi :</label>
            <div class="col-sm-8" style="margin-top: 5px;">
        <?php
        foreach ($status_akhir as $status) {
            $selected = $status->ID_STATUS_AKHIR_JABAT == $jenis ? 'selected' : '';
            if ($jenis == $status->ID_STATUS_AKHIR_JABAT) {
                ?>
                                        <input type="hidden" name="JENIS_MUTASI" value="<?php echo $status->ID_STATUS_AKHIR_JABAT; ?>">
                                        <strong><?php echo $status->STATUS; ?></strong>
                <?php
            }
        }
        ?>
            </div>
        </div> -->
        <div class="form-group">
            <label class="col-sm-4 control-label">Nama PN<span class="red-label">*</span>:</label>
            <div class="col-sm-8">
                <div class="control-label" style="text-align:left !important;"><?php echo $item_pn->NAMA ?></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Nama Jabatan <span class="red-label">*</span>:</label>
            <div class="col-sm-8">
                <div class="control-label" id="nama_jabatan" style="text-align:left !important;"><?php
                    if ($jabatan->IS_CALON == 1) {
                        echo '<span class="label label-warning">Calon</span>';
                    }
                    ?> <?php echo$jabatan->NAMA_JABATAN ?></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Instansi Asal <span class="red-label">*</span>:</label>
            <div class="col-sm-8">
                <select required id="INST_ASAL" name="INST_ASAL" class="select" style="width: 100%;display:none;">
                    <?php
                    foreach ($instansi_asal as $item) {
                        $selected = $item->INST_SATKERKD == $jabatan->LEMBAGA ? 'selected' : '';
                        if ($selected) {
                            $textINST = $item->INST_NAMA;
                        }
                        ?>
                        <option value="<?php echo $item->INST_SATKERKD ?>" <?php echo $selected; ?>><?php echo $item->INST_NAMA; ?></option>
                        <?php
                    }
                    ?>
                </select>
<?php echo $textINST;
$id_lembaga = $jabatan->LEMBAGA;
?>
            </div>
        </div>
        <hr>

        <div id="wrapperFormJabatan">
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>UNIT KERJA <span class="red-label">*</span></label> 
<?= FormHelpPopOver('unit_kerja'); ?>
                    <input type='text' class="form-control form-select2" name='UNIT_KERJA2' style="border:none;" id='UNIT_KERJA2' value='' placeholder="Unit Kerja" required>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>SUB UNIT KERJA <span class="red-label"></span></label> 
<?= FormHelpPopOver('sub_unit_kerja'); ?>
                    <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA3' style="border:none;" id='SUB_UNIT_KERJA3' value='' placeholder="Sub Unit Kerja">
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label>JABATAN<span class="red-label">*</span></label> 
<?= FormHelpPopOver('jabatan'); ?>
                    <input type="text" class="form-control form-select2" name="JABATAN3" style="border:none;" id='JABATAN3' value="" placeholder="Jabatan" required>
                </div>
                <div class="col-sm-1">
                </div>
            </div>    
            <div class="pull-right">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
                <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        var idWrapFormJabatan = $('#wrapperFormJabatan');
        var valUK = $('#UNIT_KERJA2').val();
        $('input[name="UNIT_KERJA2"]', idWrapFormJabatan).select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + <?php echo $id_lembaga; ?>,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" +<?php echo $id_lembaga; ?> + '/' + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        });
        var uk_ = null;
        $('#UNIT_KERJA2', idWrapFormJabatan).change(function (event) {
            UNIT_KERJA = $(this).val();
            $('input[name="JABATAN3"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan2') ?>/" + UNIT_KERJA,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan2') ?>/24/" + UNIT_KERJA + "/" + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });
        });
        var valUK = $('#UNIT_KERJA2').val();
        $('input[name="SUB_UNIT_KERJA2"]', idWrapFormJabatan).select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK,
                // url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/"+ <?php echo $id_lembaga; ?>,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + valUK + "/" + id, {
                        // $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/"+<?php echo $id_lembaga; ?>+'/'+id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        });
        // var uk_ = null;

        // $('#UNIT_KERJA2', idWrapFormJabatan).change(function(event) {
        //     UNIT_KERJA = $(this).val();
        //     var valUK = $('#UNIT_KERJA2').val();

        //     uk_ = UNIT_KERJA;

        $('input[name="JABATAN2"]', idWrapFormJabatan).select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getJabatan2') ?>/24/" + valUK,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                alert(id);
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getJabatan2') ?>/24/" + UNIT_KERJA + "/" + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        });
        //     $('input[name="SUB_UNIT_KERJA2"]', idWrapFormJabatan).select2({
        //         minimumInputLength: 0,
        //         ajax: {
        //             url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
        //             dataType: 'json',
        //             quietMillis: 250,
        //             data: function(term, page) {
        //                 return {
        //                     q: term
        //                 };
        //             },
        //             results: function(data, page) {
        //                 return {results: data.item};
        //             },
        //             cache: true
        //         },
        //         initSelection: function(element, callback) {
        //             var id = $(element).val();
        //             if (id !== "") {
        //                 $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
        //                     dataType: "json"
        //                 }).done(function(data) {
        //                     callback(data[0]);
        //                 });
        //             }
        //         },
        //         formatResult: function(state) {
        //             return state.name;
        //         },
        //         formatSelection: function(state) {
        //             return state.name;
        //         }
        //     });

        // });



        $('#INST_ASAL').select2();
//        $('#INST_TUJUAN').select2();
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
        ng.formProcess($("#ajaxFormMutasi"), 'mutasi', location.href.split('#')[1]);
        var elements = document.getElementsByTagName("INPUT");
        for (var i = 0; i < elements.length; i++) {
            elements[i].oninvalid = function (e) {
                e.target.setCustomValidity("");
                if (!e.target.validity.valid) {
                    id = e.target.getAttribute("id");
                    e.target.setCustomValidity(id + " required");
                }
            };
            elements[i].oninput = function (e) {
                e.target.setCustomValidity("");
            };
        }

        $('#INST_TUJUAN').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        }).on("change", function (e) {
            var lembid = $('#INST_TUJUAN').val();
            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getJabatan') ?>" + '/' + lembid,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + lembid + '/' + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });
        });
        var lembid = $('#INST_TUJUAN').val();
        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getJabatan') ?>" + '/' + lembid,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/" + lembid + '/' + id, {
                        dataType: "json"
                    }).done(function (data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection: function (state) {
                return state.name;
            }
        });
        function selUnitKerja(LEMBAGA) {
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + <?php echo $id_lembaga; ?>,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" +<?php echo $id_lembaga; ?> + '/' + id, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });
        }

        $('#UNIT_KERJA2').change(function (event) {
            UNIT_KERJA = $(this).val();
            $('input[name="SUB_UNIT_KERJA3"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });
        });
        $('#SUB_UNIT_KERJA3').change(function (event) {
            SUB_UNIT_KERJA = $(this).val();
            $('input[name="JABATAN3"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    alert(id);
                    if (id !== "") {
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA, {
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                },
                formatResult: function (state) {
                    return state.name;
                },
                formatSelection: function (state) {
                    return state.name;
                }
            });
        });
        f_mutasi(<?php echo $jenis; ?>);
        function f_mutasi(jenis) {
            if (data_status_akhir.result[jenis].IS_PINDAH == 1) {
                // mutasi
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);
                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_TUJUAN').change();
                // LEMBAGA = $('#INST_ASAL').val();
                // selUnitKerja(LEMBAGA);

            } else if (data_status_akhir.result[jenis].IS_PINDAH == 0 && data_status_akhir.result[jenis].IS_AKTIF == 1) {
                // promosi
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);
                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_TUJUAN').change();
                // LEMBAGA = $('#INST_ASAL').val();
                // selUnitKerja(LEMBAGA);
            } else if (
                    data_status_akhir.result[jenis].IS_AKHIR == 1 ||
                    (
                            data_status_akhir.result[jenis].IS_AKHIR == 0 &&
                            data_status_akhir.result[jenis].IS_PINDAH == 0 &&
                            data_status_akhir.result[jenis].IS_AKTIF == 0 &&
                            data_status_akhir.result[jenis].IS_MENINGGAL == 0
                            )
                    ) {
                // meninggal / pensiun
                objRequired = [
                    ['#INST_TUJUAN', 0],
                    ['#UNIT_KERJA', 0],
                    ['#JABATAN', 0],
                    ['#ESELON', 0],
                    ['#FILE_SK', 0],
                    ['#TMT', 0],
                    ['#SD_MENJABAT', 1],
                    ['#KETERANGAN_AKHIR_JABAT', 1]
                ];
                ng.setRequired(objRequired);
                $('.div_pindah').hide('fast');
                $('.div_jabatan').hide('fast');
                $('.div_sd').show('fast');
                $('.div_keterangan_akhir_jabat').show('fast');
                // $('#INST_ASAL').change();
                // $('#INST_TUJUAN').change();
            } else {
                // non wl
                objRequired = [
                    ['#INST_TUJUAN', 1],
                    ['#UNIT_KERJA', 1],
                    ['#JABATAN', 1],
                    ['#ESELON', 1],
                    ['#FILE_SK', 1],
                    ['#TMT', 1],
                    ['#SD_MENJABAT', 0],
                    ['#KETERANGAN_AKHIR_JABAT', 0]
                ];
                ng.setRequired(objRequired);
                $('.div_pindah').show('fast');
                $('.div_jabatan').show('fast');
                $('.div_sd').hide('fast');
                $('.div_keterangan_akhir_jabat').hide('fast');
                // $('#INST_ASAL').change();
                // $('#INST_TUJUAN').change();
            }

            $('#INST_ASAL').change(function (event) {
                // if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH != 1 ) {
                LEMBAGA = $(this).val();
                selUnitKerja(LEMBAGA);
                // }
            });
            $('#INST_TUJUAN').change(function (event) {
                // if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH == 1 ) {
                LEMBAGA = $(this).val();
                selUnitKerja(LEMBAGA);
                // }
            });
            LEMBAGA = $('#INST_ASAL').val();
            selUnitKerja(LEMBAGA);
        }
    });
</script>