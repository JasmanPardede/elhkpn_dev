
<div class="container" >
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Verifikasi Data Individual</strong></div>


        <div class="panel-body" >
            <?php
            $j_add_pn = $daftar_pn ? $daftar_pn : 0;
            $j_up_jpn = $daftar_pn_perubahan ? $daftar_pn_perubahan : 0;
            $j_non_pn = $daftar_pn_nonact ? $daftar_pn_nonact : 0;
            $tab1 = NULL;
            $tab2 = NULL;
            $tab3 = NULL;
            $cls_act1 = NULL;
            $cls_act2 = NULL;
            $cls_act3 = NULL;
            $clr_font1 = ' style="color: wheat"';
            $clr_font2 = ' style="color: wheat"';
            $clr_font3 = ' style="color: wheat"';
            if ($j_up_jpn > 0):
                $tab1 = 'data-toggle="tab"';
                $clr_font1 = '';
            endif;
            if ($j_add_pn > 0):
                $tab2 = 'data-toggle="tab"';
                $clr_font2 = '';
            endif;
            if ($j_non_pn > 0):
                $tab3 = 'data-toggle="tab"';
                $clr_font3 = '';
            endif;

            if ($j_up_jpn > 0):
                $cls_act1 = "active";
            elseif ($j_add_pn > 0):
                $cls_act2 = "active";
            elseif ($j_non_pn > 0):
                $cls_act3 = "active";
            endif;
            ?>

            <section class="content">
                <div class="row" id="allpage">
                    <div class="col-md-12">

                        <div class="nav-tabs-custom" >
                            <ul class="nav nav-tabs">
                                <li class="<?= $cls_act1 ?>"><a href="#tab_1" <?= $tab1 . $clr_font1 ?> >Perubahan Jabatan (<b><?= $j_up_jpn ?></b>)</a></li>
                                <li class="<?= $cls_act2 ?>"><a href="#tab_2" <?= $tab2 . $clr_font2 ?> >Penambahan PN/WL (<b><?= $j_add_pn ?></b>)</a></li>
                                <li class="<?= $cls_act3 ?>"><a href="#tab_3" <?= $tab3 . $clr_font3 ?> >PN/WL Non Aktif (<b><?= $j_non_pn ?></b>)</a></li>

                            </ul>
                            <div class="tab-content" >
                                <div  class="tab-pane <?= $cls_act1 ?>" id="tab_1">
                                    <b>Perubahan Jabatan</b>
                                    <div class="box">

                                        <div class="box-body">
                                            <table id="dt_completeUPDATE" class="table">
                                                <thead>
                                                    <tr>
                                                        <!--<th width="5%">No.</th>-->
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN </th>
                                                        <th width="10%">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
//                                                    if ($daftar_pn_perubahan != false) {
//                                                        if ($view_form != 'verifikasi') {
//                                                            $style = 'style="color:red;';
//                                                            $btn = 'btn-default';
//                                                        } else {
//                                                            $style = '';
//                                                            $btn = 'btn-primary';
//                                                        }
//                                                        $i = 0;
//                                                        foreach ($daftar_pn_perubahan as $val) {
//                                                            $hapus = '<button type="button" class="btn btn-sm btn-danger"  href="index.php/ereg/all_pn/delete_vi/' . $val->ID_PN . '"" data-toggle="tooltip" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';
//                                                            $approve = '<button onclick="app_vi_up(' . $val->ID_PN . ',' . $val->ID . ')" class="btn btn-sm btn-info"  data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
//
//
//                                                            $i++;
//                                                            echo '<tr>
//                                                                <td align="center"><small>' . $i . '</small></td>
//                                                                <td><small>' . $val->NIK . '</small></td>
//                                                                <td><small>' . $val->NAMA . '</small></td>
//                                                                <td><small>' . $val->DESKRIPSI_JABATAN . ' - ' . $val->SUB_UNIT_KERJA . ' -  ' . $val->UNIT_KERJA . '</small></td>
//                                                                <td><small>' . $approve . ' ' . $hapus . '</small></td>
//                                                                </tr>';
//                                                        }
//                                                    }
//                                                    
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                                <div class="tab-pane  <?= $cls_act2 ?>" id="tab_2">
                                    <b>Penambahan PN/WL</b>
                                    <div class="box" >

                                        <div class="box-body" >
                                            <table role="grid" id="dt_completeNEW" class="table">    <thead>
                                                    <tr>
                                                        <!--<th width="5%">No.</th>-->
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN </th>
                                                        <th width="10%">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php
//                                                    if ($daftar_pn != false) {
//                                                        if ($view_form != 'verifikasi') {
//                                                            $style = 'style="color:red;';
//                                                            $btn = 'btn-default';
//                                                        } else {
//                                                            $style = '';
//                                                            $btn = 'btn-primary';
//                                                        }
//                                                        $i = 0;
//                                                        foreach ($daftar_pn as $val) {
//                                                          $hapus = '<button type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_vi/'.$val->ID_PN.'"" data-toggle="tooltip" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';
//                                                            $approve = '<button  class="btn btn-sm btn-info" onclick="app_vi_add(' . $val->ID_PN . ',' . $val->ID . ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
//
//                                                            $i++;
//                                                            echo '<tr>
//                                                            <td align="center"><small>' . $i . '</small></td>
//                                                            <td><small>' . $val->NIK . '</small></td>
//                                                            <td><small>' . $val->NAMA . '</small></td>
//                                                            <td><small>' . $val->DESKRIPSI_JABATAN . ' - ' . $val->SUB_UNIT_KERJA . ' -  ' . $val->UNIT_KERJA . '</small></td>
//                                                            <td><small>' . $approve . ' ' . $hapus . '</small></td>
//                                                            </tr>';
//                                                        }
//                                                    }
//                                                    
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                                <div class="tab-pane  <?= $cls_act3 ?>" id="tab_3">
                                    <b>PN/WL Non Aktif</b>
                                    <div class="box">
                                        <div class="box-body">
                                            <table id="dt_completeNON" class="table">
                                                <thead>
                                                    <tr>
                                                        <!--<th width="5%">No.</th>-->
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN </th>
                                                        <th width="10%">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
//                                                    if ($daftar_pn_nonact != false) {
//                                                        if ($view_form != 'verifikasi') {
//                                                            $style = 'style="color:red;';
//                                                            $btn = 'btn-default';
//                                                        } else {
//                                                            $style = '';
//                                                            $btn = 'btn-primary';
//                                                        }
//                                                        $i = 0;
//                                                        foreach ($daftar_pn_nonact as $val) {
//                                                            $hapus = '<button type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_vi/' . $val->ID_PN . '"" title="Delete"><i class="fa fa-trash" style="color:white;"></i></button>';
//                                                            $cancel = '<button type="button" class="btn btn-sm btn-danger"  onclick="Cancel_VerNon(' . $val->ID_PN . ',' . $val->ID . ')" data-toggle="tooltip" title="Cancel "><i class="fa fa-close" ' . $style . '"></i></button>';
//                                                            $approve = '<button onclick="app_vi_nonact(' . $val->ID_PN . ',' . $val->ID . ')" class="btn btn-sm btn-info" onclick="approve(' . $val->ID_PN . ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
//                                                            $i++;
//                                                            echo '<tr>
//                                                            <td align="center"><small>' . $i . '</small></td>
//                                                            <td><small>' . $val->NIK . '</small></td>
//                                                            <td><small>' . $val->NAMA . '</small></td>
//                                                            <td><small>' . $val->DESKRIPSI_JABATAN . ' - ' . $val->SUB_UNIT_KERJA . ' -  ' . $val->UNIT_KERJA . '</small></td>
//                                                            <td><small>' . $approve . ' ' . $cancel . '</small></td>
//                                                            </tr>';
//                                                        }
//                                                    }
                                                    ?>
                                                </tbody>
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



            function app_vi_add(idpn, idj) {
                 if (confirm('Apakah Anda Yakin ?')) {
                $('#loader_area').show();
                server_url = 'index.php/ereg/All_pn/app_vi_add/' + idpn + '/' + idj;
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

            function app_vi_up(idpn, idj) {

                $('#loader_area').show();
                server_url = 'index.php/ereg/All_pn/app_vi_up/' + idpn + '/' + idj;
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

            function app_vi_nonact(idpn, idj) {
                  if (confirm('Apakah Anda Yakin ?')) {
                $('#loader_area').show();
                server_url = 'index.php/ereg/All_pn/app_vi_nonact/' + idpn + '/' + idj;
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


            function delete_vi(idpn, idj) {
                if (confirm('Apakah Anda Yakin Akan Menghapusnya ?')) {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_pn/delete_vi/' + idpn + '/' + idj;
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
            
            function Cancel_VerNon(idpn, idj) {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_pn/Cancel_VerNon/' + idpn + '/' + idj;
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
            $(document).ready(function () {
                $('.btn-delete').click(function (e) {
                    url = $(this).attr('href');
                    $('#loader_area').show();
                    $.post(url, function (html) {
                        OpenModalBox('Batal Verifikasi Data Individual', html, '');
                    });
                    return false;
                });
            });

            var btnDeleteOnClick = function (self) {
                url = $(self).attr('href');
                $('#loader_area').show();
                $.post(url, function (html) {
                  OpenModalBox('Batal Verifikasi Data Individual', html, '');
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
                        /*{
                         "mDataProp": function (source, type, val) {
                         return "";
                         },
                         bSearchable: false
                         },*/

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

                                var BtnHapus = '<button onclick="btnDeleteOnClick(this);" type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_vi/' + source.ID_PN + '"" data-toggle="tooltip" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';
                                var BtnApprove = '<button  class="btn btn-sm btn-info" onclick="app_vi_add(' + source.ID_PN + ',' + source.ID + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
                                var disable = '', btnApprove = '';
                                if (source.IS_ACTIVE == 1) {
                                    disable = 'disabled';
                                }

                                if (isVerifikasi) {
                                    btnApprove = '<button ' + disable + ' class="btn btn-sm btn-primary" onclick="approve(' + source.ID_PN + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
                                }

                                return (BtnApprove + " " + BtnHapus).toString();
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

            var tblDaftarIndividualPerubahan = {
                tableId: 'dt_completeUPDATE',
                reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualPerubahan'},
                conf: {
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers",
                    "bServerSide": true,
                    "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_pn_individu/1'); ?>",
                    "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                        var passData = getRecordDtTbl(sSource, aoData, oSettings);
                        $.getJSON(sSource, passData, function (json) {
                            fnCallback(json);
                        });
                    },
                    "aoColumns": [
                        /*{
                         "mDataProp": function (source, type, val) {
                         return "";
                         },
                         bSearchable: false
                         },*/

                        {"mDataProp": "NIK", bSearchable: true},
                        {"mDataProp": "NAMA", bSearchable: true},
                        {
                            "mDataProp": function (source, type, val) {
                                var arr_showed_string = [];
                                if (isObjectAttributeExists(source, 'DESKRIPSI_JABATAN') && !isEmpty(source.DESKRIPSI_JABATAN)) {
                                    arr_showed_string.push(source.DESKRIPSI_JABATAN);
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

                                var BtnHapus = '<button onclick="btnDeleteOnClick(this);" type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_vi/' + source.ID_PN + '"" data-toggle="tooltip" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';
                                var BtnApprove = '<button onclick="app_vi_up(' + source.ID_PN + ',' + source.ID + ')" class="btn btn-sm btn-info"  data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';

//                                var disable = '', btnApprove = '';
//                                if (source.IS_ACTIVE == 1) {
//                                    disable = 'disabled';
//                                }
//
//                                if (isVerifikasi) {
//                                    btnApprove = '<button ' + disable + ' class="btn btn-sm btn-primary" onclick="approve(' + source.ID_PN + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';
//                                }

                                return (BtnApprove + " " + BtnHapus).toString();
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

            var tblDaftarIndividualNonAktive = {
                tableId: 'dt_completeNON',
                reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualNonAktive'},
                conf: {
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers",
                    "bServerSide": true,
                    "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_pn_individu/2'); ?>",
                    "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                        var passData = getRecordDtTbl(sSource, aoData, oSettings);
                        $.getJSON(sSource, passData, function (json) {
                            fnCallback(json);
                        });
                    },
                    "aoColumns": [
                        /*{
                         "mDataProp": function (source, type, val) {
                         return "";
                         },
                         bSearchable: false
                         },*/

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

                                var Btncancel = '<button type="button" class="btn btn-sm btn-danger"  onclick="Cancel_VerNon(' + source.ID_PN + ',' + source.ID + ')" data-toggle="tooltip" title="Cancel "><i class="fa fa-close"></i></button>';
                                var BtnApprove = '<button onclick="app_vi_nonact(' + source.ID_PN + ',' + source.ID + ')" class="btn btn-sm btn-info" onclick="approve(' + source.ID_PN + ')" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>';


                                return (BtnApprove + " " + Btncancel).toString();
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
                var gtblDaftarIndividual = initDtTbl(tblDaftarIndividual);
                var gtblDaftarIndividualPerubahan = initDtTbl(tblDaftarIndividualPerubahan);
                var gtblDaftarIndividualNonAktive = initDtTbl(tblDaftarIndividualNonAktive);
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
