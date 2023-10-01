
<div class="container" >
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Verifikasi PN/WL</strong> <small>Via Excel</small></div>
        <?php
        $cek_temp_pn = $list_cek_temp;
        $up_pn = $list_ver_pnwl;
        $add_pn = $list_ver_pnwl_tambah;
        $non_pn = $list_ver_pnwl_non_aktif;
        $non_pn = ($cek_temp_pn > 0) ? $non_pn : 0;

        if ($cek_temp_pn > 0 && $up_pn == 0 && $add_pn == 0 && $non_pn == 0) :
            ?>
            <div class="box-header with-border">
                <button class="btn btn-sm btn-social" id="btn-email-exc" onclick="KirimEmail()"><i class="fa fa fa-mail-reply-all"></i> Kirim email</button>
            </div>
        <?php endif; ?>

        <div class="panel-body" >

            <section class="content">
                <div class="row" id="allpage">
                    <div class="col-md-12">
                        <?php
                        $tab1 = NULL;
                        $tab2 = NULL;
                        $tab3 = NULL;
                        $cls_act1 = NULL;
                        $cls_act2 = NULL;
                        $cls_act3 = NULL;
                        $clr_font1 = ' style="color: wheat"';
                        $clr_font2 = ' style="color: wheat"';
                        $clr_font3 = ' style="color: wheat"';

                        if ($up_pn > 0):
                            $tab1 = 'data-toggle="tab"';
                            $clr_font1 = '';
                        endif;
                        if ($add_pn > 0):
                            $tab2 = 'data-toggle="tab"';
                            $clr_font2 = '';
                        endif;
                        if ($non_pn > 0 && $cek_temp_pn > 0):
                            $tab3 = 'data-toggle="tab"';
                            $clr_font3 = '';
                        endif;


                        if ($up_pn > 0):
                            $cls_act1 = "active";
                        elseif ($add_pn > 0):
                            $cls_act2 = "active";
                        elseif ($non_pn > 0 && $cek_temp_pn > 0):
                            $cls_act3 = "active";
                        endif;
                        ?>
                        <div class="nav-tabs-custom" >
                            <ul class="nav nav-tabs">
                                <li class="<?= $cls_act1 ?>" id="tabone"><a href="#tab_1" <?= $tab1 . $clr_font1 ?> >Perubahan Jabatan (<b><?= $up_pn ?></b>)</a></li>
                                <li class="<?= $cls_act2 ?>" id="tabtwo"><a href="#tab_2" <?= $tab2 . $clr_font2 ?>>Penambahan PN/WL (<b><?= $add_pn ?></b>)</a></li>
                                <li class="<?= $cls_act3 ?>" id="tabtre"><a href="#tab_3" <?= $tab3 . $clr_font3 ?>>PN/WL Non Aktif (<b><?= $non_pn ?></b>)</a></li>

                            </ul>
                            <div class="tab-content" >
                                <div class="tab-pane <?= $cls_act1 ?>" id="tab_1">
                                    <b>Perubahan Jabatan</b>
                                    <div class="box">

                                        <div class="box-body">
                                            <table id="dt_complete" class="table">
                                                <thead>
                                                    <tr>
                                                        <?php /*<th width="30px">No.</th>*/?>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN LAMA</th>
                                                        <th>JABATAN BARU</th>
                                                        <th width="80px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="refup"></tbody>
                                            </table>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                                <div class="tab-pane <?= $cls_act2 ?>" id="tab_2">
                                    <b>Penambahan PN/WL</b>
                                    <div class="box" >

                                        <div class="box-body" >
                                            <table id="dt_complete1" class="table">
                                                <thead>
                                                    <tr>
                                                        <?php /*<th width="30px">No.</th>*/?>
                                                        <th>NIK</th>
                                                        <th>NIP</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN</th>
                                                        <th width="80px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="refadd"></tbody>
                                            </table>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                                <div class="tab-pane <?= $cls_act3 ?>" id="tab_3">
                                    <b>PN/WL Non Aktif</b>
                                    <div class="box">

                                        <div class="box-body">
                                            <table id="dt_complete2" class="table">
                                                <thead>
                                                    <tr>
                                                        <?php /*<th width="30px">No.</th>*/?>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN LAMA</th>
                                                        <th width="80px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->
                        <div id="resultdiv"></div>

                    </div><!-- /.box -->
                </div><!-- /.col -->
            </section><!-- /.content -->
        </div><!-- /.row -->

        <script type="text/javascript">


            $(function() {
                $('#dt_complete, #dt_complete1, #dt_complete2').dataTable({
                });
            });
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();

                $('.over').popover();
                $('.over').on('click', function(e) {
                    $('.over').not(this).popover('hide');
                });

                $('.btn-edit').click(function(e) {
                    url = $(this).attr('href');
                    $('#loader_area').show();
                    $.post(url, function(html) {
                        OpenModalBox('Edit Verifikasi PN/WL', html, '', 'standart');
                    });
                    return false;
                });

            });
            var msg = {
                success: 'Data Berhasil Disimpan!',
                error: 'Data Gagal Disimpan!'
            };

            function saveUbahJabPNWL(idpn) {
                if (confirm('Apakah Anda Yakin ?')) {
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
//                                $('#refup').replaceWith($('#refup', $(htmldata)));
//                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
//                                $('#loader_area').hide();
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
                }
            }

            function savePenambahanPNWL(idpn) {
                if (confirm('Apakah Anda Yakin ?')) {
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
//                                $('#refadd').replaceWith($('#refadd', $(htmldata)));
//                                $('#tabtwo').replaceWith($('#tabtwo', $(htmldata)));
//                                $('#loader_area').hide();
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
                }
            }

            function cancelVerPN(idpn, st) {
//                alert(st);
                if (confirm('Apakah Anda Yakin ?')) {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_ver_pn/cancelVerPN/' + idpn;
                    $.ajax({
                        url: server_url,
                        type: "POST",
                        data: {"idtemp": idpn},
                        success: function(htmldata) {

                            if (htmldata.status == 0) {
                                alertify.error(msg.error);
                                $('#loader_area').hide();
                            } else {
//                              $('#allpage').replaceWith($('#allpage', $(htmldata)));
//                            if (st === 1) {
//                                $('#refup').replaceWith($('#refup', $(htmldata)));
//                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
//                            } else if (st === 2) {
//                                $('#refadd').replaceWith($('#refadd', $(htmldata)));
//                                $('#tabtwo').replaceWith($('#tabtwo', $(htmldata)));
//                            }
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

                }
            }

            function ApproveNonActPN(idpn) {
                if (confirm('Apakah Anda Yakin ?')) {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_ver_pn/ApproveNonActPN/' + idpn;
                    $.ajax({
                        url: server_url,
                        type: "POST",
                        data: {"idtemp": idpn},
                        success: function(htmldata) {

                            if (htmldata.status == 0) {
                                alertify.error(msg.error);
                                $('#loader_area').hide();
                            } else {
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
                }
            }

            function cancel_nonactxls(idpn, idjb) {
                if (confirm('Apakah Anda Yakin ?')) {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_ver_pn/cancel_nonactxls/' + idpn + '/' + idjb;
                    $.ajax({
                        url: server_url,
                        type: "POST",
                        data: {"idtemp": idpn},
                        success: function(htmldata) {

                            if (htmldata.status == 0) {
                                alertify.error(msg.error);
                                $('#loader_area').hide();
                            } else {
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
                }
            }

            var msgmail = {
                success: 'Email Berhasil Dikirim!',
                error: 'Email Gagal Dikirim!'
            };

            function KirimEmail(idpn) {
                $('#loader_area').show();
                server_url = 'index.php/ereg/All_ver_pn/kirim_email/';
                $.ajax({
                    url: server_url,
                    type: "POST",
                    data: {"idtemp": idpn},
                    success: function(htmldata) {

                        if (htmldata.status == 0) {
                            alertify.error(msgmail.error);
                            $('#loader_area').hide();
                        } else {
                            alertify.success(msgmail.success);
                            $('#loader_area').hide();

                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }

        var autoInc = 1;
        var tblDaftarComplete = {
                tableId: 'dt_complete',
                reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarComplete'},
                conf: {
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers",
                    "bServerSide": true,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/load_data_list_ver_pnwl'); ?>",
                    "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                        var passData = getRecordDtTbl(sSource, aoData, oSettings);
                        $.getJSON(sSource, passData, function (json) {
                            fnCallback(json);
                        });
                    },
                    "aoColumns": [
                        {"mDataProp": "NIK", bSearchable: true},
                        {"mDataProp": "NAMA", bSearchable: true},
                        {"mDataProp": "NAMA_JABATAN", bSearchable: true},
                        {
                            "mDataProp": function (source, type, val) {
                                var strike = "";
                                var back_col = '#b2dba1';
                                var ket_dis = "";;
                                if(source.CEK != 'ada'){
                                    back_col = 'salmon';
                                    strike = '<s>';
                                    ket_dis = "<?php FormHelpPopOver('nomatchdb')?>";
                                }
                                
                                var columnData = ket_dis +' <small>'+ strike +' '+source.NAMA_JABATAN_TEMP+'</small>';
                                return  columnData;
                            },
                            bSortable: false
                        },
                        {
                            "mDataProp": function (source, type, val) {
                                var disableFlag = '';
                                if(source.CEK != 'ada'){
                                    disableFlag = 'disabled';
                                }
                                
                                var btnInfo = '<button '+ disableFlag +' class="btn btn-sm btn-info" onclick="saveUbahJabPNWL('+ source.ID +')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
                                var btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/ereg/All_ver_pn/popUpAdd/1/'+ source.ID +'/xl" title="Edit"><i class="fa fa-pencil"></i></button>';
                                var btnCancel = '<button class="btn btn-sm btn-danger" onclick="cancelVerPN('+ source.ID +', 1)" data-toggle="tooltip" title="cancel"><i class="fa fa-close " style="color:white;"></i></button>';
                                return (btnInfo + " " + btnEdit + " " + btnCancel).toString();
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

            var tblDaftarComplete1 = {
                tableId: 'dt_complete1',
                reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarComplete1'},
                conf: {
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers",
                    "bServerSide": true,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/load_data_list_ver_pnwl_tambah'); ?>",
                    "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                        var passData = getRecordDtTbl(sSource, aoData, oSettings);
                        $.getJSON(sSource, passData, function (json) {
                            fnCallback(json);
                        });
                    },
                    "aoColumns": [
                        {"mDataProp": "NIK", bSearchable: true},
                        {"mDataProp": "NIP_NRP", bSearchable: true},
                        {
                            "mDataProp": function (source, type, val) {
                                var columnData = source.NAMA.toUpperCase();
                                return  columnData;
                            },
                            bSearchable: true
                        },
                        {
                            "mDataProp": function (source, type, val) {
                                var strike = "";;
                                var ket_dis = "";;
                                if(source.CEK != 'ada'){
                                    strike = '<s>';
                                    ket_dis = "<?php FormHelpPopOver('nomatchdb')?>";
                                }
                                
                                var columnData = '<b>' + ket_dis + strike + source.NAMA_JABATAN + '</b> - ' + source.NAMA_SUB_UNIT_KERJA + ' - ' + source.NAMA_UNIT_KERJA;
                                return  columnData;
                            },
                            bSearchable: true
                        },
                        {
                            "mDataProp": function (source, type, val) {
                                var disableFlag = '';
                                if(source.CEK != 'ada'){
                                    disableFlag = 'disabled';
                                }
                                
                                var btnApprove = '<button '+ disableFlag +' class="btn btn-sm btn-info" onclick="savePenambahanPNWL('+ source.ID +')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
                                var btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/ereg/All_ver_pn/popUpAdd/2/'+ source.ID +'/xl" title="Edit"><i class="fa fa-pencil"></i></button>';
                                var btnCancel = '<button class="btn btn-sm btn-danger" data-toggle="tooltip" onclick="cancelVerPN('+ source.ID +', 2)" title="cancel"><i class="fa fa-close " style="color:white;"></i></button>';
                                return (btnApprove + " " + btnEdit + " " + btnCancel).toString();
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

            var tblDaftarComplete2 = {
                tableId: 'dt_complete2',
                reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarComplete2'},
                conf: {
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers",
                    "bServerSide": true,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo base_url('ereg/all_ver_pn/load_data_list_ver_pnwl_non_aktif'); ?>",
                    "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                        var passData = getRecordDtTbl(sSource, aoData, oSettings);
                        $.getJSON(sSource, passData, function (json) {
                            fnCallback(json);
                        });
                    },
                    "aoColumns": [
                        {"mDataProp": "NIK", bSearchable: true},
                        {
                            "mDataProp": function (source, type, val) {
                                var columnData = source.NAMA.toUpperCase();
                                return columnData;
                            },
                            bSearchable: true
                        },
                        {
                            "mDataProp": function (source, type, val) {
                                var columnData = '<b>'+ source.NAMA_JABATAN +'</b> - '+ source.SUK_NAMA +' - ' + source.UK_NAMA;
                                return  columnData;
                            },
                            bSearchable: true
                        },
                        {
                            "mDataProp": function (source, type, val) {
                                var disableFlag = '';
                                if(source.CEK != 'ada'){
                                    disableFlag = 'disabled';
                                }
                                
                                var btnApprove = '<button class="btn btn-sm btn-info" onclick="ApproveNonActPN('+ source.ID_PN_JAB +')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
                                var btnCancel = '<button type="button" class="btn btn-sm btn-danger"  onclick="cancel_nonactxls('+ source.ID_PN + ',' + source.ID_PN_JAB +')" title="Cancel Non Active "><i class="fa fa-repeat"></i></button>';
                                
                                //var btnNotUse = '<button type="button" class="btn btn-sm btn-primary btn-edit" href="index.php/ereg/All_ver_pn/popUpAdd/'+ source.NIK + '" title="Edit"><i class="fa fa-pencil"></i></button>';
                                //var btnNotUse = '<button class="btn btn-sm btn-primary" data-toggle="tooltip" title="cancel"><i class="fa fa-close " ></i></button>';
                                
                                return (btnApprove + " " + btnCancel).toString();
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
            
            $(function () {
                var gtblDaftarComplete = initDtTbl(tblDaftarComplete);
                var gtblDaftarComplete1 = initDtTbl(tblDaftarComplete1);
                var gtblDaftarComplete2 = initDtTbl(tblDaftarComplete2);
            });
        </script>

    </div>
</div>
</div> <!-- /container -->
