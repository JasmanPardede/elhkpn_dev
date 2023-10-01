Apakah anda yakin akan mengaktifkan data ini ? <br><br>
    <form class="form-horizontal" method="post" id="ajaxFormTerpilih" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama PN :</label>
            <div class="col-sm-7">
                <?php echo $item->NAMA; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan :</label>
            <div class="col-sm-7">
                <?php
                if ($item->IS_CALON == 1) {
                    echo '<span class="label label-warning">Calon</span>';
                }
                ?>  <?php echo $item->NAMA_JABATAN; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Instansi :</label>
            <div class="col-sm-7">
                <?php echo $item->INST_NAMA; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja :</label>
            <div class="col-sm-7">
                <?php echo $item->UK_NAMA; ?>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" id="ID" value="<?php echo $item->ID; ?>">
            <input type="hidden" name="act" id="act" value="doActive">
            <button type="submit" class="btn btn-sm btn-primary">Aktifkan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>

    <script type="text/javascript">
        function f_redirect() {
            $.post('index.php/ereg/all_pn/cekNIK/' + $('#NIK').val(), function (data) {
                if (data != 0) {
                    $("#wrapperFormPNExist").html(data);
                }
            });
        }

        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy'
            });
    <?php if ($stat == '1') { ?>
                $("form#ajaxFormTerpilih").submit(function (event) {
                    var urll = $(this).attr('action');
                    var formData = new FormData($(this)[0]);
                    $.ajax({
                        url: urll,
                        type: 'POST',
                        data: formData,
                        async: false,
                        success: function (html) {
                            html = jQuery.parseJSON(html);
                            msg = {
                                success: 'Data Berhasil Disimpan!',
                                error: 'Data Gagal Disimpan!'
                            };
                            if (html.status == 0) {
                                alertify.error(msg.error);
                            } else {
                                alertify.success(msg.success);
                            }

                            if (html.status == 1) {
                                $.post('index.php/ereg/all_pn/cekNIK/' + $('#NIK').val(), function (data) {
                                    if (data != 0) {
                                        $('.modal-dialog').animate({
                                            width: '+=500'
                                        });
                                        $("#wrapperFormPNExist").html(data);
                                        alertify.success('Jabatan berhasil di tambahkan!');
                                    }
                                });
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                    return false;
                });
    <?php } else { ?>
                ng.formProcess($("#ajaxFormTerpilih"), 'insert', location.href.split('#')[1]);
    <?php } ?>
        });
        $('input[name="SUB_UNIT_KERJA"]', idWrapFormJabatan).select2({
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
                        if (id !== "") {
                $.ajax("<?= base_url('index.php/share/reff/getSubUnitKerja') ?>/" + UNIT_KERJA + "/" + id, {
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
        }
        );
        $('#SUB_UNIT_KERJA', idWrapFormJabatan).change(function (event) {
            SUB_UNIT_KERJA = $(this).val();
    //alert(uk_);

            $('input[name="JABATAN"]', idWrapFormJabatan).select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + SUB_UNIT_KERJA,
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
                        $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + uk_ + "/" + SUB_UNIT_KERJA, {
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
        });</script>