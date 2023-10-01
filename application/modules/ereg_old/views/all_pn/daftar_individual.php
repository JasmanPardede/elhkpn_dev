<section class="content-header">
    <h1>
        Daftar PN/WL Individual
    </h1>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
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
                        <?php /* <form id="ajaxFormCari" method="post" action="index.php/ereg/all_pn/daftar_pn_individu">
                          <div class="input-group">
                          <?php
                          // if ($IS_KPK == 1) {
                          ?>
                          <!-- <input type='text' class="input-sm select" name='CARI[INST]' style="border:none;width:300px;" id='CARI_INST' value='<?= @$CARI['INST']; ?>' placeholder="-- Pilih Instansi --"> -->
                          <?php
                          // }
                          ?>
                          &nbsp;
                          <input type="text" class="form-control input-sm pull-right" style="width: 400px;" placeholder="Search by Nama or NIK or Jabatan or Sub Unit Kerja or Unit Kerja" name="CARI_TEXT" value="<?php echo isset($cari) ? $cari : ""; ?>" id="CARI_TEXT"/>
                          <div class="input-group-btn">
                          <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="cariDTbl('dt_completeNEW', $('#CARI_TEXT').val());"><i class="fa fa-search"></i></button>
                          <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val('');
                          $('#CARI_IS_CALON').val('99');
                          $('#CARI_INST').val('');cariDTbl('dt_completeNEW', '');">Clear</button>
                          </div>
                          </div>
                          </form> */ ?>
                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->

                    <table role="grid" id="dt_completeNEW" class="table">    <thead>
                            <tr>
                                <?php /* <th width="30">No.</th> */ ?>
                                <th>NIK</th>
                                <th width="150px">Nama</th>
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
</section><!-- /.content -->




<script language="javascript">
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
    ;
    function cek_nomor_hp(nohp, id) {
        var div = $('#div-hp');
        var loading = $('#loading_hp', div);
        $('img', div).hide();
        loading.show();
        var url = "index.php/ereg/all_pn/cek_nomor_hp/" + nohp + '/' + id;
        $.post(url, function (data) {
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
    ;
    $(function () {
        $('#dt_complete, #dt_complete1, #dt_complete2').dataTable({
        });
    });
    $(document).ready(function () {
        $('.select2').select2();
        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });
        $("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('.over').popover();
        $('.over').on('click', function (e) {
            $('.over').not(this).popover('hide');
        });

        $('.btn-add').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Tambah PN/WL Individual', html, '', 'large');
            });
            return false;
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
            success: function (htmldata) {

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
            success: function (htmldata) {

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
            success: function (htmldata) {

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

    var btnEditOnClick = function (self) {
        var url = $(self).attr('href');
        $('#loader_area').show();
        
        console.log(url);
        $.post(url, function (html) {
            OpenModalBox('Edit PN/WL Individual', html, '', 'large');
        });
        return false;
    };
    var btnDeleteOnClick = function (self) {
        var url = $(self).attr('href');
        $('#loader_area').show();
        $.post(url, function (html) {
            OpenModalBox('Hapus Data PN/WL Individual', html, '');
        });
        return false;
    };

    var tblDaftarIndividual = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividual'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_pn_individu'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function (source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUB_UNIT_KERJA') && !isEmpty(source.SUB_UNIT_KERJA)) {
                            arr_showed_string.push(source.SUB_UNIT_KERJA);
                        }
                        if (isObjectAttributeExists(source, 'UNIT_KERJA') && !isEmpty(source.UNIT_KERJA)) {
                            arr_showed_string.push(source.UNIT_KERJA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function (source, type, val) {

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
            ],
            "fnRowCallback": function (nRow, aData) {
//                var actionLink = '';
//                actionLink += '<a href="<?php echo base_url('administrator/master_refpasal/detail'); ?>/' + aData.id_pasal + '" class="title-east" original-title="Ubah Data"><span class="icon-pen"></span></a>'
//                actionLink += '<a href="javascript:;" onclick="remMasterRefPasal(' + aData.id_pasal + ')" class="title-east" original-title="Hapus Data"><span class="icon-trash-stroke"></span></a>'

//                $('td:eq(4)', nRow).html(actionLink);
                return nRow;
            }
        }
    };

    $(function () {

        $('.btn-delete').click(function (e) {
            btnDeleteOnClick(this);
        });
        $('.btn-edit').click(function (e) {
            btnEditOnClick(this);
        });

        var gtblDaftarIndividual = initDtTbl(tblDaftarIndividual);
//        gtblDaftarIndividual.on('order.dt search.dt', function () {
//            gtblDaftarIndividual.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
//                cell.innerHTML = i + 1;
//            });
//        }).draw();

    });
</script>

    </div>
</div>
</div> <!-- /container -->
