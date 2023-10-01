<div id="wrapperFormEdit" class="form-horizontal">
    <div id="wrapperFormPenerimaan">
        <ol class="breadcrumb">
            <li>Form Penerimaan</li>
        </ol>
        <form method="post" id="ajaxFormEditPenerimaan" action="<?php echo $urlSave; ?>" enctype="multipart/form-data">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-left: 0px; padding-right: 0px;">PN <font color='red'>*</font> :</label>
                    <div class="col-sm-3" style="padding-right: 0px;">
                        <!-- list pn jika belum terdaftar maka ditambahkan -->
                        <input type='hidden' class="form-control" name='ID_PN' id='ID_PN' value='<?php echo $item->ID_PN; ?>' required readonly>
                        <input type='hidden' class="form-control" name='NIK' id='NIK' value='<?php echo $item->NIK; ?>' required readonly>
                        <input type='text' class="form-control" name='NAMA' id='NAMA' value='<?php echo $item->NAMA; ?>' required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-left: 0px; padding-right: 0px;">Tanggal Penerimaan <font color='red'>*</font> :</label>
                    <div class="col-sm-2">
                        <input type='text' class="form-control date-picker" name='TANGGAL_PENERIMAAN' id='TANGGAL_PENERIMAAN' placeholder='DD/MM/YYYY' value='<?php echo date('d/m/Y', strtotime($item->TANGGAL_PENERIMAAN)); ?>' required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-left: 0px; padding-right: 0px;">Jenis Laporan <span class="red-label">*</span> :</label>
                    <div class="col-sm-4" style="border-right: 1px solid #cfcfcf;">
                        <h4>Khusus</h4>
                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="1" <?php echo ($item->JENIS_LAPORAN == '1') ? 'checked' : ''; ?>> Calon Penyelenggara Negara</label><br>
                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="2" <?php echo ($item->JENIS_LAPORAN == '2') ? 'checked' : ''; ?>> Awal Menjabat</label><br>
                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="3" <?php echo ($item->JENIS_LAPORAN == '3') ? 'checked' : ''; ?>> Akhir Menjabat</label><br>
                        <span>Tanggal Pelaporan</span> <input type="text" name="TANGGAL_PELAPORAN" id="TANGGAL_PELAPORAN" placeholder='DD/MM/YYYY' class="TANGGAL_PELAPORAN date-picker" value="<?php echo ($item->TANGGAL_PELAPORAN !== '1970-01-01') ? date('d/m/Y', strtotime($item->TANGGAL_PELAPORAN)) : ''; ?>">
                    </div>
                    <div class="col-sm-4">
                        <h4>Periodik</h4>
                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="4" <?php echo ($item->JENIS_LAPORAN == '4') ? 'checked' : ''; ?>> Sedang Menjabat</label><br>
                        <span>Tahun Pelaporan</span> 
                        <select name="TAHUN_PELAPORAN" class="TAHUN_PELAPORAN" id="TAHUN_PELAPORAN" style="width: 160px;">
                            <option value="">Pilih Tahun</option>
                            <?php
                            for ($i = date('Y') - 1; $i > (date('Y') - 10); $i--) {
                                echo "<option " . (($item->TAHUN_PELAPORAN !== 0) ? (($item->TAHUN_PELAPORAN == $i) ? 'selected' : '') : '' ) . " value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                        <br>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-left: 0px; padding-right: 0px;">Jenis Dokumen <font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <div class="radio">
                            <label><input type="radio" class="JENIS_DOKUMEN" required name="JENIS_DOKUMEN" <?php echo ($item->JENIS_DOKUMEN == 'hardcopy') ? 'checked' : ''; ?> value="hardcopy"> Hardcopy</label>&nbsp;&nbsp;
                        </div>                        
                        <div class="radio">
                            <label><input type="radio" class="JENIS_DOKUMEN" required name="JENIS_DOKUMEN" <?php echo ($item->JENIS_DOKUMEN == 'excel') ? 'checked' : ''; ?> value="excel"> Excel</label>
                        </div>
                    </div>
                </div>  
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-left: 0px; padding-right: 0px;">Diterima Melalui <font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <div class="radio">
                            <label><input type="radio" class="MELALUI" required name="MELALUI" value="langsung" <?php echo ($item->MELALUI == 'langsung') ? 'checked' : ''; ?>> Langsung</label>&nbsp;&nbsp;
                        </div>
                        <div class="radio">
                            <label><input type="radio" class="MELALUI" required name="MELALUI" value="pos" <?php echo ($item->MELALUI == 'pos') ? 'checked' : ''; ?>> Pos</label>&nbsp;&nbsp;
                        </div>
                        <div class="radio">
                            <label><input type="radio" class="MELALUI" required name="MELALUI" value="email" <?php echo ($item->MELALUI == 'email') ? 'checked' : ''; ?>> Email</label>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>                
            </div>
            <div class="pull-right">
                <input type="hidden" name="act" value="<?php echo $act; ?>">
                <input type="hidden" name="ID_PENERIMAAN" value="<?php echo $item->ID_PENERIMAAN; ?>">
                <button type="submit" class="btn btn-sm btn-primary" id="btnSimpanPenerimaan">Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <div id="wrapperCariPN" style="display: none;">
        <ol class="breadcrumb">
            <li>Form Penerimaan</li>
            <li>Cari PN</li>
        </ol>
        <!-- <button type="button" class="btn btn-sm btn-default" id="btnTambahPN"><i class="fa fa-plus"></i> Tambah Data PN</button> -->
        <div class="pull-right">
            <form method="post" id="ajaxFormCariPN" action="index.php/efill/lhkpnoffline/hasilcaripn/">
                <div class="input-group col-sm-push-5">
                    <div class="col-sm-3">
                        <input type="text" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT_PN"/>
                    </div>
                    <div class="input-group-btn col-sm-3">
                        <button type="submit" class="btn btn-sm btn-default" id="btn-cari"><i class="fa fa-search"></i></button>
                        <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PN').val('');
                                $('#CARI_TEXT_PN').focus();
                                $('#ajaxFormCariPN').trigger('submit');">Clear</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
        <div class="clearfix"></div>
        <div id="wrapperHasilCariPN">
            <!-- draw here -->
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-default" id="btnKembaliKePenerimaan">Kembali Ke form</button>
        </div>
    </div>
    <div id="wrapperFormAddPN" style="display: none;">
        <ol class="breadcrumb">
            <li>Form Penerimaan</li>
            <li>Cari PN</li>
            <li>Tambah PN</li>
        </ol>
        <form method="post" id="ajaxFormAddPN" action="<?php echo 'index.php/efill/lhkpnoffline/save/pn/'; ?>">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label">NIK <font color='red'>*</font>:</label>
                    <div class="col-sm-8">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="NIK" id="NIK" placeholder="NIK" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Lengkap <font color='red'>*</font>:</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="col-sm-2">
                                <input type="text" class='form-control' name='GELAR_DEPAN' placeholder="Gelar depan" value="" >
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class='form-control' name='NAMA_LENGKAP' id='NAMA_LENGKAP' placeholder="Nama Lengkap" value="" required>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class='form-control' name='GELAR_BELAKANG' placeholder="Gelar Belakang" value="" >
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tempat Lahir / Tanggal Lahir <font color='red'>*</font>:</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="TEMPAT_LAHIR" placeholder="Tempat Lahir" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control date-picker" name="TGL_LAHIR"placeholder='DD/MM/YYYY' required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Email <font color='red'>*</font>:</label>
                    <div class="col-sm-8">
                        <div class="col-sm-12">
                            <input type="email" class="form-control" name="EMAIL" placeholder="email" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nomor HP <font color='red'>*</font>:</label>
                    <div class="col-sm-8">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="NO_HP" placeholder="Nomor HP" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input type="hidden" name="act" value="doinsertpn">
                <button type="submit" class="btn btn-sm btn-primary" id="btnSimpanAddPN">Simpan</button>
            </div>
        </form>
        <br>
        <br>
        <div class="clearfix"></div>
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-default" id="btnKembaliKeCariPN">Kembali Ke Cari PN</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    penerimaan = {
        year: <?php echo date('Y'); ?>,
        today: <?php echo date('Ymd'); ?>,
        eventClickPeriodik: function () {
            NIK = $('#ajaxFormEditPenerimaan').find('#NIK').val();
            jQuery.post('index.php/efill/lhkpnoffline/infopn', {
                NIK: NIK
            }, function (json, textStatus, xhr) {
                json = JSON && JSON.parse(json) || $.parseJSON(json);
                if (json.error == 0) {
                    $('#ajaxFormEditPenerimaan').find('#ID_PN').val(json.data.ID_PN);
                    // $('#ajaxFormEditPenerimaan').find('#JABATAN').val(json.data.JABATAN);
                    // $('#ajaxFormEditPenerimaan').find('#BIDANG').val(json.data.BIDANG);
                    // $('#ajaxFormEditPenerimaan').find('#LEMBAGA').val(json.data.LEMBAGA);
                    $('.TAHUN_PELAPORAN').prop('required', true);
                    $('.TANGGAL_PELAPORAN').prop('required', false);
                } else {
                    alertify.error(json.msg);
                    $('#btnCariPN').trigger('click');
                }
            });
        },
        eventClickKhusus: function () {
            $('.TANGGAL_PELAPORAN').prop('required', true);
            $('.TAHUN_PELAPORAN').prop('required', false);
        },
        showFormPenerimaan: function () {
            $('#wrapperFormPenerimaan').slideDown('fast', function () {});
        },
        hideFormPenerimaan: function () {
            $('#wrapperFormPenerimaan').slideUp('fast');
        },
        showCariPN: function () {
            $('#wrapperCariPN').slideDown('fast', function () {
                $('#wrapperCariPN').find('#CARI_TEXT_PN').focus();
            });
            $("#ajaxFormCariPN").submit(function (e) {
                e.preventDefault();
                var url = $(this).attr('action');
                ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
                return false;
            });
        },
        eventShowHasilCariPN: function () {
            $(".paginationPN").find("a").click(function () {
                var url = $(this).attr('href');
                // window.location.hash = url;
                ng.LoadAjaxContentPost(url, $('#ajaxFormCariPN'), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
                return false;
            });
            $('.btnSelectPN').click(function () {
                DATAPN = $(this).attr('data-pn');
                PN = DATAPN.split('::');
                $('#wrapperFormPenerimaan').find('#ID_PN').val(PN[0]);
                $('#wrapperFormPenerimaan').find('#NIK').val(PN[1]);
                $('#wrapperFormPenerimaan').find('#NAMA').val(PN[2]);
                _this.eventClickPeriodik();
                _this.showFormPenerimaan();
                _this.hideCariPN();

                $('#ajaxFormAddPenerimaan').find('#JABATAN').select2('val', '');
                $('#ajaxFormAddPenerimaan').find('#DESC_JABATAN').val('');
                $('#ajaxFormAddPenerimaan').find('#LEMBAGA').select2('val', '');
                $('#ajaxFormAddPenerimaan').find('#UNIT_KERJA').select2('destroy').val('');
                $('#ajaxFormAddPenerimaan').find('input[name="JENIS_DOKUMEN"]').prop('checked', false);
                $('#ajaxFormAddPenerimaan').find('input[name="MELALUI"]').prop('checked', false);
                $('#ajaxFormAddPenerimaan').find('input[name="JENIS_LAPORAN"]').prop('checked', false);
                $('#ajaxFormAddPenerimaan').find('#TANGGAL_PELAPORAN').val('');
                $('#ajaxFormAddPenerimaan').find('#TAHUN_PELAPORAN').val('');
            });
        },
        hideCariPN: function () {
            $('#wrapperCariPN').slideUp('fast');
        },
        showFormAddPN: function () {
            $('#wrapperFormAddPN').slideDown('fast', function () {
                ng.formProcess($("#ajaxFormAddPN"), 'insert', '',
                        function () {
                            NEW_NIK = $('#wrapperFormAddPN').find('#NIK').val();
                            NEW_NAMA_LENGKAP = $('#wrapperFormAddPN').find('#NAMA_LENGKAP').val();
                            $('#wrapperFormPenerimaan').find('#NIK').val(NEW_NIK);
                            $('#wrapperFormPenerimaan').find('#NAMA').val(NEW_NAMA_LENGKAP);
                            _this.eventClickPeriodik();
                            _this.showFormPenerimaan();
                            _this.hideFormAddPN();
                        }, null, false);
            });
        },
        hideFormAddPN: function () {
            $('#wrapperFormAddPN').slideUp('fast');
        },
        init: function () {
            _this = penerimaan;
            ng.formProcess($("#ajaxFormEditPenerimaan"), 'edit', location.href.split('#')[1]);
            // ng.formProcess($("#ajaxFormAddPN"), 'insert', '');
            $("#btnSimpanPenerimaan").click(function (e) {
                if ($('#wrapperFormPenerimaan').find('#ID_PN').val().trim() == '') {
                    alertify.error('Silahkan Pilih PN!');
                    $('#btnCariPN').trigger('click');
                    return false;
                }
            });

            $('#btnCariPN').click(function () {
                _this.showCariPN();
                _this.hideFormPenerimaan();
            });
            $('#btnKembaliKePenerimaan').click(function () {
                _this.showFormPenerimaan();
                _this.hideCariPN();
            });
            $('#btnTambahPN').click(function () {
                _this.showFormAddPN();
                _this.hideCariPN();
            });
            $('#btnKembaliKeCariPN').click(function () {
                _this.showCariPN();
                _this.hideFormAddPN();
            });
            $('.JENIS_LAPORAN').each(function (e) {
                $(this).click(function (e) {
                    if ($(this).val() == 4) {
                        _this.eventClickPeriodik();
                    } else {
                        _this.eventClickKhusus();
                    }
                });
            });
            $('#TANGGAL_PELAPORAN').change(function (e) {
                v = $(this).val();
                v = v.split('-');
                v = v[2] + v[1] + v[0];
                if (v > _this.today) {
                    alertify.error('Tanggal Tidak Boleh lebih dari hari ini!');
                }
            });
            $('#TAHUN_PELAPORAN').change(function (e) {
                if ($(this).val() >= _this.year) {
                    $(this).val('');
                    alertify.error('Laporan Periodik belum dibuka, Silahkan Pilih tahun sebelumnya!');
                }
            });
        }
    }
    $(document).ready(function () {
        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
        $('.date-picker').datepicker({
            orientation: "left",
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        penerimaan.init();

        $('input[name="LEMBAGA"]').select2({
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
        });

        $('#LEMBAGA').change(function (event) {
            $('input[name="UNIT_KERJA"]').val('');
            LEMBAGA = $(this).val();
            $('input[name="UNIT_KERJA"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA,
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
                        $.ajax("<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + '/' + id, {
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

        var LEMBAGA = $('#LEMBAGA').val();

        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getJabatan') ?>/" + LEMBAGA,
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
                    $.ajax("<?= base_url('index.php/share/reff/getJabatan') ?>/" + LEMBAGA + '/' + id, {
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

        $('input[name="UNIT_KERJA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA,
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
                    $.ajax("<?= base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + '/' + id, {
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
</script>