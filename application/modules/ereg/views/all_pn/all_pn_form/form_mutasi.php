<?php
$idRole = $this->session->userdata('ID_ROLE');
if ($idRole == 3 || $idRole == 4) {
    $dis = 'disabled';
} else {
    $dis = '';
}
?>
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
    <form class="form-horizontal" method="post" id="ajaxFormMutasi" action="index.php/ereg/all_pn/save_perubahan_jabatan/<?php echo $item_pn->ID_PN; ?>">
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
<?php echo $textINST; ?>
            </div>
        </div>
        <hr>
        <div class="form-group div_pindah">
            <label class="col-sm-4 control-label">Instansi Tujuan <span class="red-label">*</span>:</label>
            <div class="col-sm-8">
                <input type="text" <?php echo $dis; ?> id="INST_TUJUAN" name="INST_TUJUAN" value="<?php echo $jabatan->LEMBAGA ?>" class="select" style="width: 100%;" />
            </div>
        </div>
        <div class="form-group div_jabatan">
            <label class="col-sm-4 control-label">Unit Kerja<font color='red'>*</font> :</label>
            <div class="col-sm-8">
                <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
            </div>
        </div>
        <div class="form-group div_jabatan">
            <label class="col-sm-4 control-label">Sub Unit Kerja<font color='red'></font> :</label>
            <div class="col-sm-8">
                <input type='text' class="form-control form-select2" name='SUB_UNIT_KERJA' style="border:none;" id='SUB_UNIT_KERJA' value='' placeholder="Sub Unit Kerja">
            </div>
        </div>
        <div class="form-group div_jabatan">
            <label class="col-sm-4 control-label">Jabatan<font color='red'>*</font> :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
            </div>
        </div>
        <!-- <div class="form-group div_jabatan">
            <label class="col-sm-4 control-label">Deskripsi jabatan :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="DESKRIPSI_JABATAN" name="deskripsi" value="" placeholder="Deskripsi Jabatan">
            </div>
        </div>
        <div class="form-group div_jabatan">
            <label class="col-sm-4 control-label">Eselon<font color='red'>*</font> :</label>
            <div class="col-sm-8">
                <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                    <option value='1'>I</option>
                    <option value='2'>II</option>
                    <option value='3'>III</option>
                    <option value='4'>IV</option>
                    <option value='5'>Non-Eselon</option>
                </select>
            </div>
        </div>
        <div class="form-group div_jabatan">
            <label class="col-sm-4 control-label">SK<font color='red'>*</font> :</label>
            <div class="col-sm-8">
                <div class='col-sm-12'>
                    <input type="file" id="FILE_SK" name="FILE_SK" class="FILE_SK">
                </div>
            </div>
            <span class='help-block'>Type File: xls, xlsx, doc, docx, pdf, png, jpg, jpeg .  Max File: 500KB</span>
        </div> -->
        <!-- <div class="form-group div_jabatan">
            <label class="col-sm-4 control-label">TMT<font color='red'>*</font> :</label>
            <div class="col-sm-8">
                <div class='col-sm-12'>
                    <div class="col-xs-3 col-sm-4 col-md-3 col-lg-7">
                        <input type="text" class="form-control datepicker" id="TMT" name="TMT" value="" required>
                    </div> -->
        <!-- <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
            s/d
        </div>
        <div class="col-xs-3 col-sm-4 col-md-3 col-lg-3">
            <input type="text" class="form-control datepicker" name="SD" value="">
        </div>
    </div>
</div>
</div>
<?php if ($jenis == '3') { ?>
        <div class="form-group div_sd">
            <label class="col-sm-4 control-label">TMT <span class="red-label">*</span>:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control datepicker" id="SD_MENJABAT" name="SD_MENJABAT" value="" required>
            </div>
        </div>
<?php } else { ?>
        <div class="form-group div_sd">
            <label class="col-sm-4 control-label">TMT <span class="red-label">*</span>:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control datepicker" id="SD_MENJABAT" name="SD_MENJABAT" value="" required>
            </div>
        </div>
<?php } ?>
<div class="form-group div_keterangan_akhir_jabat">
<label class="col-sm-4 control-label">Keterangan <?= ($jenis == '3' ? "" : "<span class='red-label'>*</span>"); ?>:</label>
<div class="col-sm-8">
    <textarea <?= ($jenis == '3' ? 'required' : ''); ?> class="form-control" id="KETERANGAN_AKHIR_JABAT" name="KETERANGAN_AKHIR_JABAT"></textarea>
</div>
</div> -->
        <div class="pull-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
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


        $('.FILE_SK').change(function () {
            var nil = $(this).val().split('.');
            nil = nil[nil.length - 1].toLowerCase();
            var file = $(this)[0].files[0].size;
            var arr = ['xls', 'xlsx', 'doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg'];
            var maxsize = 500000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_SK').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_SK').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });
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
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA,
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
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + '/' + id, {
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

        $('#UNIT_KERJA').change(function (event) {
            UNIT_KERJA = $(this).val();
            $('input[name="SUB_UNIT_KERJA"]').select2({
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
            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA,
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
        $('#SUB_UNIT_KERJA').change(function (event) {
            SUB_UNIT_KERJA = $(this).val();
            $('input[name="JABATAN"]').select2({
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