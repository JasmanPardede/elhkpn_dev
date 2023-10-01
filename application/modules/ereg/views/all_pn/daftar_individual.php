<?php
$show_form_entry = isset($show_form_entry) ? $show_form_entry : FALSE;
?>
<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>DAFTAR PN/WL INDIVIDUAL</strong></div>
    </div>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-body" >
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo htmlspecialchars(stripcslashes(@$thisPageUrl), ENT_QUOTES); ?>">
                <div class="box-body">

                    <div class="col-md-5">
                        <div class="row">

                            <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Instansi:</label>
                                    <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3 || $this->session->userdata('ID_ROLE') == 31): ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Unit Kerja:</label>
                                    <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='' placeholder="-- Pilih Unit Kerja --">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Kata Kunci" name="CARI[TEXT]" value="<?php echo htmlspecialchars(stripcslashes(@$CARI['TEXT']), ENT_QUOTES); ?>" id="inp_dt_completeNEW_cari"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <?php if ($view_form != 'verifikasi') { ?>
                        <button class="btn btn-sm btn-primary btn-add"  id="btn-add" href="index.php/ereg/all_pn/addpn_daftar/2/daftarindividu"><i class="fa fa-plus"></i> Tambah Data</button>
                        <?php
                    } else {
                        echo '<br/>';
                    }
                    ?>
                    <button class="btn btn-sm btn-primary" style="display: none" id="btn-add-exc" href="index.php/ereg/all_pn/DownUpExcels"><i class="fa fa fa-file-excel-o"></i> Excel</button>
                    <button class="btn btn-sm btn-primary" style="display: none" id="btn-add-webs" href="index.php/ereg/all_pn/UpWebService"><i class="fa fa fa-link"></i> Web Services</button>

                    <div class="box-tools">

                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->

                    <table role="grid" id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">    <thead>
                            <tr>
                                <th width="10">No</th>
                                <th width="120px">NIK</th>
                                <th width="180px">Nama</th>
                                <th>Jabatan</th>
                                <th width="50px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div><!-- /.box-body -->


            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">
    function cek_email(email, id) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_user_instansi/cek_email/" + encodeURIComponent(email) + '/' + id;
        $.post(url, function(data) {
            loading.hide();
            if (data == '0') {
                $('#fail', div).show();
                $('#email_ada').hide();
                $('#email_salah').show();
            } else if (data == '1') {
                $('#fail', div).show();
                $('#email_ada').show();
                $('#email_salah').hide();
            } else {
                $('#success', div).show();
                $('#email_ada').hide();
                $('#email_salah').hide();
            }
        });
    }

    function cek_nomor_hp(nohp, id) {
        var div = $('#div-hp');
        var loading = $('#loading_hp', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_pn/cek_nomor_hp/" + nohp + '/' + id;
        $.post(url, function(data) {
            loading.hide();
            if (data == '1') {
                $('#hp_ada').hide();
            } else if (data == '2') {
                $('#hp_ada').hide();
            } else if (data == '3') {
                $('#hp_ada').show();
            } else {
                $('#success_hp', div).show();
                $('#hp_ada').hide();
            }
        });
    }

    $(function() {
        $('#dt_complete, #dt_complete1, #dt_complete2').dataTable({
        });
    });

    var msg = {
        success: 'Data Berhasil Disimpan!',
        error: 'Data Gagal Disimpan!'
    };
    function saveUbahJabPNWL(idpn) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_ver_pn/ajax_update_jabpnwl/' + idpn;
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function(htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    alertify.success(msg.success);
                    $('#refup').replaceWith($('#refup', $(htmldata)));
                    $('#loader_area').hide();
                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    }

    function approve(idpn) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/all_pn/approve_daftar_pnwl/' + idpn;
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function(htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    alertify.success(msg.success);
                    $('#refup').replaceWith($('#refup', $(htmldata)));
                    $('#loader_area').hide();
                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    }

    function savePenambahanPNWL(idpn) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_ver_pn/ajax_save_add_pnwl/' + idpn;
        url = 'index.php/ereg/All_ver_pn';
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function(htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    alertify.success(msg.success);
                    $('#refadd').replaceWith($('#refadd', $(htmldata)));
                    $('#loader_area').hide();
                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    }

    function yesnoCheck() {
        if (document.getElementById('satu').checked) {
            $("#btn-add").show();
            $("#btn-add-exc").hide();
            $("#btn-add-webs").hide();
        } else {
            $("#btn-add").hide();
            $("#btn-add-exc").show();
            $("#btn-add-webs").show();
        }

    }
<?php
$vVer = $view_form == 'verifikasi' ? TRUE : FALSE;
if (!$vVer) {
    $style = 'style="color:red";';
    $btn = 'btn-default';
} else {
    $style = '';
    $btn = 'btn-primary';
}

echo "var btnStyle = '" . $style . "', btnClass = '" . $btn . "', isVerifikasi = " . ($vVer ? "true" : "false") . "; ";
?>

    var btnEditOnClick = function(self) {
        var url = $(self).attr('href');
        $('#loader_area').show();

        console.log(url);
        $.post(url, function(html) {
            OpenModalBox('Edit PN/WL Individual', html, '', 'large');
        });
        return false;
    };
    var btnDeleteOnClick = function(self) {
        var url = $(self).attr('href');
        $('#loader_area').show();
        $.post(url, function(html) {
            OpenModalBox('Hapus Data PN/WL Individual', html, '','standart');
        });
        return false;
    };

    var tblDaftarIndividual = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividual'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_pn_individu'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari").val()});
//                if ($('#CARI_INSTANSI').val() === "" || $('#CARI_INSTANSI').val() === "3122") {
                //if ($('#CARI_INSTANSI').val() === "") {
                    //passData.push({"name": "CARI[INSTANSI]", "value": "1081"});
                    //passData.push({"name": "CARI[UNIT_KERJA]", "value": "590"})
                //}
                //else {
                    passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                    passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()})
                //}
                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });

                // $.getJSON(sSource, passData, function (json) {
                //  fnCallback(json);
                //});
            },
            "aoColumns": [
                /*{
                 "mDataProp": function (source, type, val) {
                 return "";
                 },
                 bSearchable: false
                 },*/
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUB_UNIT_KERJA') && !isEmpty(source.SUB_UNIT_KERJA)) {
                            arr_showed_string.push(source.SUB_UNIT_KERJA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function(source, type, val) {

                        var btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/ereg/all_pn/editpn_daftar/2/daftarindividu/' + source.ID_PN + '" title="Edit" onclick="btnEditOnClick(this);"><i class="fa fa-pencil"></i></button>';
                        var btnHapus = '<button type="button" class="btn btn-sm btn-danger btn-delete btn-hapus" href="index.php/ereg/all_pn/delete_daftar_pn_individual/' + source.ID_PN + '" title="Delete" onclick="btnDeleteOnClick(this);"><i class="fa fa-trash" style="color:white;"></i></button>';

                        var disable = '', btnApprove = '';
                        if (source.IS_ACTIVE == 1) {
                            disable = 'disabled';
                        }

                        if (isVerifikasi) {
                            btnApprove = '<button ' + disable + ' class="btn btn-sm btn-primary" onclick="approve(' + source.ID_PN + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
                        }

                        return (btnApprove + " " + btnEdit + " " + btnHapus).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ]
                    /**,
                     "fnRowCallback": function (nRow, aData) {
                     //                var actionLink = '';
                     //                actionLink += '<a href="<?php echo base_url('administrator/master_refpasal/detail'); ?>/' + aData.id_pasal + '" class="title-east" original-title="Ubah Data"><span class="icon-pen"></span></a>'
                     //                actionLink += '<a href="javascript:;" onclick="remMasterRefPasal(' + aData.id_pasal + ')" class="title-east" original-title="Hapus Data"><span class="icon-trash-stroke"></span></a>'

                     //                $('td:eq(4)', nRow).html(actionLink);
                     return nRow;
                     }*/
        }
    };
    var gtblDaftarIndividual;

    var clearPencarian = function() {
        $('#CARI_INSTANSI').val('');
        $('#inp_dt_completeNEW_cari').val('');
        gtblDaftarIndividual.fnClearTable(0);
        gtblDaftarIndividual.fnDraw();
        reloadTableDoubleTime(gtblDaftarIndividual);

    };
    var submitCari = function() {
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    var initiateCariInstansi = function() {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();
<?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
            initiateSelect2CariUnitKerja(1081);
<?php else: ?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
<?php endif; ?>
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
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
                var id = $('#CARI_INSTANSI').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
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
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI').select2(cari_instansi_cfg);

                if (iins != null) {
                    $("#CARI_INSTANSI").val(iins).trigger("change");
                    // initiateSelect2CariUnitKerja(iins);
                }
            }
        });

    };


    var initiateSelect2CariUnitKerja = function(LEMBAGA) {

        $("#CARI_UNIT_KERJA").remove();
        $("#inpCariUnitKerjaPlaceHolder").empty();

        var set_default_null = "PENCEGAHAN";

        if (LEMBAGA !== 1081) {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
        }
        else {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
            set_default_null = "PENCEGAHAN";
        }
        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null="+set_default_null,
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
                var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null="+set_default_null, {
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
        };

        var dsuk = null;
        if (isDefined(LEMBAGA)) {
            var __UNIT_KERJA = $('#CARI_UNIT_KERJA').val();

            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null="+set_default_null, {
                dataType: "json"
            }).success(function(data) {
//                    console.log(data, data.item, !isEmpty(data.item));
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

                    $('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function(e) {
gtblDaftarIndividual.fnClearTable(0);
        gtblDaftarIndividual.fnDraw();
        reloadTableDoubleTime(gtblDaftarIndividual);
                    });

                    if (dsuk != null) {
                        $("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
                    }
                }

            });
        }
    };

    $(function() {

        $('.btn-delete').click(function(e) {
            btnDeleteOnClick(this);
        });
        $('.btn-edit').click(function(e) {
            btnEditOnClick(this);
        });

        gtblDaftarIndividual = initDtTbl(tblDaftarIndividual);

<?php if ($show_form_entry): ?>
            $('.btn-add').click();
<?php endif; ?>

    });
    $(document).ready(function() {
        $('.select2').select2();
        $(".pagination").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });
        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });
        $('#CARI_IS_CALON').change(function() {
            $("#ajaxFormCari").trigger('submit');
        });
        $('#CARI_INSTANSI').change(function() {
            $("#ajaxFormCari").trigger('submit');
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('.over').popover();
        $('.over').on('click', function(e) {
            $('.over').not(this).popover('hide');
        });
        $('.btn-add').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah PN/WL Individual', html, '', 'large');
            });
            return false;
        });
        initiateCariInstansi();
        $('#CARI_INSTANSI').change(function(event) {
            initiateSelect2CariUnitKerja($(this).val());
        });
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
            initiateSelect2CariUnitKerja(1081);
        <?php else: ?>
            initiateSelect2CariUnitKerja(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
        <?php endif; ?>

    });

</script>
