<div id="wrapperFormEdit">
    <?php 
    $save = ($cr == 'xl') ? 'save_edit' : 'save_edit_ws';
    ?>
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/ereg/All_ver_pn/<?= $save ?>" enctype="multipart/form-data">
        <input type="hidden" name="ID_TEMP" value="<?php echo isset($data_temp->ID) ? $data_temp->ID : ""; ?>">
        <input type="hidden" name="STS" value="<?php echo isset($sts) ? $sts : ""; ?>">
        <input type="hidden" name="ID" value="<?php echo isset($data_temp->ID_LEMBAGA) ? $data_temp->ID_LEMBAGA : ""; ?>">
                <!-- <input type="text" name="iscln" value="<?php echo $iscln; ?>"> -->
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>NIK <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('nik'); ?>
                <input type='text' class="form-control" size='40' name='NIK' onblur="cek_user(this.value);" id='NIK' value='<?php echo isset($data_temp->NIK) ? $data_temp->NIK : ""; ?>' placeholder="NIK" required <?= FormHelpPlaceholderToolTip('nik') ?>>
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>NIP/NRP <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('nip'); ?>
                <input type='text' class="form-control" size='40' name='NIP_NRP' onblur="cek_user(this.value);" id='NIP' value='<?php echo isset($data_temp->NIP_NRP) ? $data_temp->NIP_NRP : ""; ?>' placeholder="NIK" required <?= FormHelpPlaceholderToolTip('nik') ?>>
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Nama Lengkap <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('nama'); ?>
                <input type='text' class="form-control" name='NAMA' id='NAMA' value='<?php echo isset($data_temp->NAMA) ? $data_temp->NAMA : ""; ?>' required <?= FormHelpPlaceholderToolTip('nama') ?> placeholder="Nama">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Instansi <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('instansi'); ?>
                <?php if ($this->session->userdata('ID_ROLE') == '4' || $this->session->userdata('ID_ROLE') == '3'): ?>
                    <input readonly  type="text" id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;" />
                    <?php Else: ?>
                    <input required type="text" id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;" />
                <?php EndIf; ?>    
            </div>
            <div class="col-sm-1">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Unit Kerja <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('unit_kerja'); ?>
                <input required type="text" id="UNIT_KERJA" name="UNIT_KERJA" class="select" style="width: 100%;" />
            </div>
            <div class="col-sm-1"></div>
        </div>

        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Sub Unit Kerja </label> 
                <?= FormHelpPopOver('sub_unit_kerja'); ?>
                <input  type="text" id="SUB_UNIT_KERJA" name="SUB_UNIT_KERJA" class="select" style="width: 100%;" />
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Jabatan <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('nama'); ?>
                <input required type="text" id="JABATAN" name="JABATAN" class="select" style="width: 100%;" />
            </div>
            <div class="col-sm-1"></div>
        </div>
        <!-- </div> -->
        <div class="form-group">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label>Email <span class="red-label">*</span></label> 
                <?= FormHelpPopOver('nama'); ?>
                <input required class="form-control" value='<?php echo isset($data_temp->EMAIL) ? $data_temp->EMAIL : ""; ?>' type='email' size='40' name='EMAIL' onblur="cek_email_pn(this.value);" id='EMAIL' placeholder="johnsmith@email.com">
            </div>
            <div class="col-sm-1"></div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan<?php // echo (@$iscln == '1' ? 'Calon ' : '')        ?></button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {


        var msg = {
            success: 'Data Berhasil Disimpan!',
            error: 'Data Gagal Disimpan!'
        };

        var form = $("#ajaxFormEdit");
        $("#ajaxFormEdit").submit(function() {
            $('#loader_area').show();
            var urll = form.attr('action');
            var formData = new FormData($(this)[0]);


            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function(htmldata) {
                    htmldata = JSON.parse(htmldata);
                    if (htmldata.status == 0) {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {
                        alertify.success(msg.success);

                        $.get(location.href.split('#')[1], function(html) {
                            $('#ajax-content').html(html);
                            CloseModalBox();

                            $('#loader_area').hide();
                        })
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
            alertify.success(msg.success);

            $.get(location.href.split('#')[1], function(html) {
                $('#ajax-content').html(html);
                CloseModalBox();

                $('#loader_area').hide();
            })


            return false;
        });




        $('#INST_TUJUAN').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getLembaga') ?>/" + id, {
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
        }).on("change", function(e) {
            var lembid = $('#INST_TUJUAN').val();


            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + lembid,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function(term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function(data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + lembid, {
                            // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/"+lembid+'/'+id, {
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
        });

<?php if ($this->session->userdata('ID_ROLE') == '4' || $this->session->userdata('ID_ROLE') == '3'): ?>
            $('.instansi').fadeIn('slow');
            $('#INST_TUJUAN').select2("data", {id: <?php echo $this->session->userdata('INST_SATKERKD'); ?>, name: "<?php echo strtoupper($this->session->userdata('INST_NAMA')); ?>"});
    <?php EndIf; ?>



        var x = $('#INST_TUJUAN').val();
        $('input[name="UNIT_KERJA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + x,
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term,
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + x, {
                        // $.ajax("<?php echo base_url('index.php/share/reff/getJabatan') ?>/"+lembid+'/'+id, {
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
        }).on("change", function(e) { //UNIT KERJA
        
            var UNIT_KERJA = $('#UNIT_KERJA').val();

            $('input[name="SUB_UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function(term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function(data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                },
                formatResult: function(state) {
                    return state.name;
                },
                formatSelection: function(state) {
                    return state.name;
                }
            })

            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA ,
                    dataType: 'json',
                    quietMillis: 250,
                    data: function(term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function(data, page) {
                        return {results: data.item};
                    },
                    cache: true
                },
                initSelection: function(element, callback) {
                    var id = $(element).val();
                },
                formatResult: function(state) {
                    return state.name;
                },
                formatSelection: function(state) {
                    return state.name;
                }
            })

        });
<?php if ($unit_kerja): ?>
            $('#UNIT_KERJA').select2("data", {id: <?php echo $unit_kerja->UK_ID; ?>, name: "<?php echo $unit_kerja->UK_NAMA; ?>"});
    <?php EndIf; ?>

        var UNIT_KERJA = $('#UNIT_KERJA').val();
        $('input[name="SUB_UNIT_KERJA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA,
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        }).on("change", function(e) {
        var SUB_UNIT_KERJA = $('#SUB_UNIT_KERJA').val();
        
        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UNIT_KERJA + "/" + SUB_UNIT_KERJA,
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        })
        
        });
<?php if ($sub_unit_kerja): ?>
            $('#SUB_UNIT_KERJA').select2("data", {id: <?php echo $sub_unit_kerja->SUK_ID; ?>, name: "<?php echo $sub_unit_kerja->SUK_NAMA; ?>"});
    <?php EndIf; ?>

        var UK_ID = $('#UNIT_KERJA').val();
        var SUK_ID = $('#SUB_UNIT_KERJA').val();
        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + UK_ID + "/" + SUK_ID,
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        });
<?php if ($jabatan): ?>
            $('#JABATAN').select2("data", {id: <?php echo $jabatan->ID_JABATAN; ?>, name: "<?php echo $jabatan->NAMA_JABATAN; ?>"});
    <?php EndIf; ?>

    });
</script>
