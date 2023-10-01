<?php
/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/user
 */
$INSATNSI = array();
foreach ($instansis as $instansi) {
    $INSATNSI[$instansi->INST_SATKERKD]['INST_NAMA'] = $instansi->INST_NAMA;
}

$role_yang_diijinkan_instansi = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_KELOLA_ADMIN_UNIT_KERJA')['filter_instansi']);
$role_yang_diijinkan_unit_kerja = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_KELOLA_ADMIN_UNIT_KERJA')['filter_unit_kerja']);

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>DAFTAR ADMIN UNIT KERJA</strong></div>
    </div>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-body" >
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">
                    <div class="col-md-5">
                        <div class="row">
                            <?php //if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 7 || $this->session->userdata('ID_ROLE') == 10 || $this->session->userdata('ID_ROLE') == 13 || $this->session->userdata('ID_ROLE') == 14 || $this->session->userdata('ID_ROLE') == 18 || $this->session->userdata('ID_ROLE') == 60): 
                                if($role_yang_diijinkan_instansi): ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Instansi:</label>
                                    <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php //if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3 || $this->session->userdata('ID_ROLE') == 7 || $this->session->userdata('ID_ROLE') == 10 || $this->session->userdata('ID_ROLE') == 13 || $this->session->userdata('ID_ROLE') == 14 || $this->session->userdata('ID_ROLE') == 18 || $this->session->userdata('ID_ROLE') == 60): 
                                if($role_yang_diijinkan_unit_kerja): ?>
                                <div class="form-group" style="margin-bottom:10px">
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
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="col-sm-4 control-label">Status:</label>
                                <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                    <input type='text' class="input-sm form-control" name='CARI[STATUS]' style="border:none;padding:6px 0px;" id='CARI_STATUS' value='' placeholder="-- Pilih Status --">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_completeNEW_cari"/>
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
                    <a class="btn-cetak btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                        <span class="logo-mini">
                            <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                        </span> Print to Excel 
                    </a>
                </div>
            </form>

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
              <div class="col-md-2">
                  <?php
                  if ($this->makses->is_write)
                      echo '<button class="btn btn-sm btn-primary" id="btn-add" href="index.php/ereg/all_user_instansi/adduser"><i class="fa fa-plus"></i> Tambah Data</button>';
                  ?>
              </div>
              </div>
              <div class="box-body">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th align="center" width="30">No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Last Login</th>
                                <th width="250px">Email / Handphone</th>
                                <th>Instansi (Unit Kerja)</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div><!-- /.box-body -->
                <?php /*
                  <div class="box-footer clearfix">
                  <?php
                  if($total_rows){
                  ?>
                  <div class="col-sm-6">
                  <div class="dataTables_info" id="datatable-1_info">Showing <?php echo $start; ?> to <?php echo $end; ?>
                  of <?php echo $total_rows; ?> entries
                  </div>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="col-sm-6 text-right">
                  <div class="dataTables_paginate paging_bootstrap">
                  <?php echo $pagination; ?>
                  </div>
                  </div>
                  <div class="clearfix"></div>
                  </div>
                 */ ?>
            </div><!-- /.box -->
        </div>
        </div><!-- /.col -->
      </div>
    </div>
</section><!-- /.content -->

<script language="javascript">
    var gtblDaftarIndividual;
    var data_is_active;
    function cek_user(username, id) {
        var div = $('#div-username');
        var loading = $('#loading', div);
        $('img', div).hide();
        username = username.replace(/^\s+|\s+$/g, '');
        if (username == '') {
            $('#fail', div).show();
            return;
        }
        loading.show();
        var url = "index.php/ereg/all_user_instansi/cek_user/" + username + "/" + id;
        $.post(url, function (data) {
            loading.hide();
            if (data == '1')
            {
                $('#fail', div).show();
                $("#username_ada").show();
                document.getElementById('USERNAME').value = "";
                document.getElementById('check_uname_add').innerHTML = username;
            } else
            {
                $('#success', div).show();
                $("#username_ada").hide();
            }
            ;
        });
    }

    function cek_user_edit(username, current_username) {
        // alert(username);
        var url = "index.php/ereg/all_user_instansi/cek_user_edit/" + username + "/" + current_username;
        // alert(url);
        $.post(url, function (data) {
            if (data == '1')
            {
                $("#username_ada").show();
                document.getElementById('USERNAME').value = current_username;
                document.getElementById('check_uname_edit').innerHTML = username;
            } else
            {
                $("#username_ada").hide();
            }
            ;
        });
    }

    function cek_email(email, id) {
        var div = $('#div-email');
        var loading = $('#loading', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_user_instansi/cek_email/" + encodeURIComponent(email) + '/' + id;
        $.post(url, function (data) {
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

    var tblDaftarInstansi = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarInstansi'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "responsive": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_user_instansi/load_data_index'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari").val()});
                passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "USERNAME", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var columnData = "";
                        if (source.LAST_LOGIN) {
                            var unixTime = source.LAST_LOGIN;
                            var date = new Date(unixTime * 1000);
                            columnData = moment(date).format('DD-MM-YYYY h:mm:ss');
                        } else {
                            columnData = "";
                        }
                        return  columnData;
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {
                        var columnData = "";
                        if (source.EMAIL) {
                            columnData += '<a href="mailto:' + source.EMAIL + '"><i class="fa fa-envelope"></i> ' + source.EMAIL + '</a><br>';
                        }
                        if (source.HANDPHONE) {
                            columnData += '<a href="tel:' + source.HANDPHONE + '"><i class="fa fa-phone"></i> ' + source.HANDPHONE + '</a>';
                        }
                        return  columnData;
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {
                        var columnData = "";
                        if (source.UK_ID) {
                            columnData = source.INST_NAMA + " (<b>" + source.UK_NAMA + "</b>)";
                        } else {
                            columnData = source.INST_NAMA;
                        }
                        return  columnData;
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {
                        var is_active = $('#CARI_STATUS').val();
                        var btnEdit = "";
                        var flagData = "<?php echo $this->makses->is_write ?>";
                        if (flagData) {
                            btnEdit = '<button type="button" class="btn btn-success btn-sm btn-edit" href="index.php/ereg/all_user_instansi/edituser/' + source.ID_USER + '" title="Edit" onclick="btnEditOnClick(this);"><i class="fa fa-pencil"></i></button>';
                        }
                        var btnDelete = "";
                        if (flagData) {
                            btnDelete = '<button type="button" class="btn btn-danger btn-sm btn-delete" href="index.php/ereg/all_user_instansi/deleteuser/' + source.ID_USER + '" title="Delete" onclick="btnDeleteOnClick(this);"><i class="fa fa-trash" style="color:white;"></i></button>';
                        }
                        var btnReset = '<button type="button" class="btn btn-sm btn-success btn-reset" style="background-color: gray; color: white;" href="index.php/ereg/all_user_instansi/reset_password/' + source.ID_USER + '" title="reset password" onclick="btnResetOnClick(this);"><i class="fa fa-reddit-square"style="color:white;"></i></button>';
                        var btnAktivasi = '<button type="button" class="btn btn-sm btn-warning btn-aktivasi" href="index.php/ereg/all_user_instansi/kirimaktivasi/' + source.ID_USER + '" title="kirim ulang aktivasi" onclick="btnAktivasiOnClick(this);"><i class="fa fa-send"style="color:white;"></i></button>';
                        var btnActive = '<button type="button" class="btn btn-sm btn-primary btn-active" style="background-color: #ff6600;" href="index.php/ereg/all_user_instansi/activeuser/' + source.ID_USER + '" title="aktifkan user" onclick="btnActiveOnClick(this);"><i class="fa fa-cloud-upload" aria-hidden="true" style="color:white;"></i></button>';
                        if (is_active == -1){
                            return (btnActive).toString();
                        }else{
                            return (btnEdit + " " + btnDelete + " " + btnReset + " " + btnAktivasi).toString();
                        }
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function (nRow, aData) {
                return nRow;
            }
        }
    };

    var btnEditOnClick = function (self) {
        url = $(self).attr('href');
        $('#loader_area').show();
        $.post(url, function (html) {
            OpenModalBox('Edit Admin Unit Kerja', html, '');
        });
        return false;
    };

    var btnActiveOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Aktifkan Admin Unit Kerja', html, 'standart');
        });
        return false;
    };

    var btnDeleteOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Hapus Admin Unit Kerja', html, '');
        });
        return false;
    };

    var btnResetOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Reset Password Admin Unit Kerja', html, '');
        });
        return false;
    };

    var btnAktivasiOnClick = function (self) {
        url = $(self).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Kirim Ulang Aktivasi', html, '');
        });
        return false;
    };

    var clearPencarian = function () {
        $('#inp_dt_completeNEW_cari').val('');
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    var submitCari = function () {
        reloadTableDoubleTime(gtblDaftarIndividual);
    };

    var initiateCariInstansi = function () {

        $("#CARI_INSTANSI").remove();
        $("#inpCariInstansiPlaceHolder").empty();


        var LEMBAGA = '1081';
<?php // if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2"): 
            $role = !in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_KELOLA_ADMIN_INSTANSI')['filter_instansi_except']);
            if($role) :
?>
            $("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
            initiateSelect2CariUnitKerja('1081');
<?php else: ?>
            //var pidInstansi = <?php echo $this->session->userdata('INST_SATKERKD'); ?>;
            //initiateSelect2CariUnitKerja(pidInstansi);
            LEMBAGA = '<?php echo $default_instansi; ?>';
<?php endif; ?>

        initiateSelect2CariUnitKerja(LEMBAGA);

        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
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
                var id = $('#CARI_INSTANSI').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
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
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function (data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI').select2(cari_instansi_cfg);

                if (iins != null) {
                    $("#CARI_INSTANSI").val(iins).trigger("change");
//                    initiateSelect2CariUnitKerja(iins);
                }
            }
        });

    };

    var initiateSelect2CariUnitKerja = function (LEMBAGA) {

        var set_default_null = '';

        if (LEMBAGA !== '1081') {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
        } else {
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
            set_default_null = "pencegahan";
        }
        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null=" + set_default_null,
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
                var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null=" + set_default_null, {
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
        };

        var dsuk = null;
        if (isDefined(LEMBAGA)) {
            var __UNIT_KERJA = $('#CARI_UNIT_KERJA').val();

            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null=" + set_default_null, {
                dataType: "json"
            }).success(function (data) {
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

                    $('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function (e) {
                        reloadTableDoubleTime(gtblDaftarIndividual);
                    });

                    if (dsuk != null) {
                        $("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
                    }
                }

            });
        }
    };

    $(document).ready(function () {

        $('.btn-cetak').click(function(e) {
            e.preventDefault();
            var instansi = '';
            var unit_kerja = '';
            var status = '';
            var text = '';
            
            instansi = ($('#CARI_INSTANSI').val() == '') ? 'ALL' : $('#CARI_INSTANSI').val();
            unit_kerja = ($('#CARI_UNIT_KERJA').val() == '') ? 'ALL' : $('#CARI_UNIT_KERJA').val();
            status = $('#CARI_STATUS').val();
            if($('#CARI_STATUS').val() == ''){
                status = 'ALL';
            }else if($('#CARI_STATUS').val() == '1,0'){
                status = '1';
            }
            text = ($('#inp_dt_completeNEW_cari').val() == '') ? 'ALL' : $('#inp_dt_completeNEW_cari').val();

            var url = '<?php echo site_url("/index.php/ereg/Cetak/export_auk"); ?>/' + instansi +'/' + unit_kerja +'/' + status +'/' + text;
            window.location.href = url;
            return;
        });

        data_is_active = [{id: (1+','+0), text: 'Active'}, {id: -1, text: 'Inactive'}];
        $('#CARI_STATUS').select2({data: data_is_active});

        initiateCariInstansi();
        $('#CARI_INSTANSI').change(function () {
            initiateSelect2CariUnitKerja($(this).val());
        });
        $('#CARI_INST').select2({
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

        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            url = url.replace('<?= base_url(); ?>', '');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });

        $("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $(".btn-detail").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Detail Admin Unit Kerja', html, '');
            });
            return false;
        })
        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Tambah Admin Unit Kerja', html, '');
            });
            return false;
        });
        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Edit Admin Unit Kerja', html, '');
            });
            return false;
        });
        $('.btn-reset').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Reset Password Admin Unit Kerja', html, '');
            });
            return false;
        });
        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Hapus User instansi/Unit Kerja', html, '');
            });
            return false;
        });
        gtblDaftarIndividual = initDtTbl(tblDaftarInstansi);
    });

</script>