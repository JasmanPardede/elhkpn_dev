
<div class="container" >
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Verifikasi PN/WL</strong> <small> Via Web Service</small></div>

        <?php
        $cek_temp_pn = count($list_cek_temp);
        $up_pn = count($list_ver_pnwl);
        $add_pn = count($list_ver_pnwl_tambah);
        $non_pn = count($list_ver_pnwl_non_aktif);
        $non_pn = ($cek_temp_pn > 0) ? $non_pn : 0;

        if ($cek_temp_pn > 0 && $up_pn == 0 && $add_pn == 0 && $non_pn == 0) :
            ?>
            <div class="box-header with-border">
                <button class="btn btn-sm btn-social" id="btn-email-exc" onclick="KirimEmail()"><i class="fa fa fa-mail-reply-all"></i> Kirim email</button>
            </div>
        <?php endif; ?>

        <div class="panel-body" >

            <section class="content">
                <div class="row">

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
                                <li class="<?= $cls_act1 ?>"><a href="#tab_1" <?= $tab1 . $clr_font1 ?> >Perubahan Jabatan (<b><?= $up_pn ?></b>)</a></li>
                                <li class="<?= $cls_act2 ?>"><a href="#tab_2" <?= $tab2 . $clr_font2 ?>>Penambahan PN/WL (<b><?= $add_pn ?></b>)</a></li>
                                <li class="<?= $cls_act3 ?>"><a href="#tab_3" <?= $tab3 . $clr_font3 ?>>PN/WL Non Aktif (<b><?= $non_pn ?></b>)</a></li>

                            </ul>
                            <div class="tab-content" >
                                <div class="tab-pane <?= $cls_act1 ?>" id="tab_1">
                                    <b>Perubahan Jabatan</b>
                                    <div class="box">

                                        <div class="box-body">
                                            <table id="dt_complete" class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="30px">No.</th>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN LAMA</th>
                                                        <th>JABATAN BARU</th>
                                                        <th width="80px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="refup">
                                                    <?php
                                                    $i = 0;
                                                    foreach ($list_ver_pnwl as $row):
                                                        $i++;
                                                        $strike = NULL;
                                                        $disable = NULL;
                                                        $ket_dis = NULL;
                                                        $back_col = '#b2dba1';
                                                        if ($row->CEK != 'ada'):
                                                            $back_col = 'salmon';
                                                            $strike = "<s>";
                                                            $disable = 'disabled';
                                                            $ket_dis = FormHelpPopOver('nomatchdb');
                                                        endif;
                                                        ?>
                                                        <tr>
                                                            <td><small><?= $i ?></small></td>
                                                            <td><small><?= $row->NIK ?></small></td>
                                                            <td><small><?= strtoupper($row->NAMA) ?></small></td>
                                                            <td><small><?= $row->NAMA_JABATAN ?></small></td>
                                                            <td  style="background-color: <?= $back_col ?>"><?= $ket_dis ?>  <small><?= $strike . $row->NAMA_JABATAN_TEMP ?></small></td>
                                                            <td> 

                                                                <button <?= $disable ?> class="btn btn-sm btn-info" onclick="saveUbahJabPNWL(<?= $row->ID ?>)" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button> 
                                                                <button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/ereg/All_ver_pn/popUpAdd/1/<?= $row->ID ?>/ws" title="Edit"><i class="fa fa-pencil"></i></button>
                                                                <button class="btn btn-sm btn-danger" data-toggle="tooltip" title="cancel" onclick="cancelVerPN(<?= $row->ID ?>, 1)"><i class="fa fa-close " style="color:white;"></i></button>
                                                            </td>
                                                        </tr>

                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </tbody>
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
                                                        <th width="30px">No.</th>
                                                        <th>NIK</th>
                                                        <th>NIP</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN</th>
                                                        <th width="80px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="refadd">
                                                    <?php
                                                    $i = 0;
                                                    foreach ($list_ver_pnwl_tambah as $row):
                                                        $i++;
                                                        ?>
                                                        <tr>
                                                            <td><small><?= $i ?></small></td>
                                                            <td><small><?= $row->NIK ?></small></td>
                                                            <td><small><?= $row->NIP_NRP ?></small></td>
                                                            <td><small><?= strtoupper($row->NAMA) ?></small></td>
                                                            <td><small><?= $row->NAMA_JABATAN . ' - ' . $row->NAMA_SUB_UNIT_KERJA. ' - <b>' . $row->NAMA_UNIT_KERJA . '</b>' ?></small></td>
                                                            <td> 
                                                                <button class="btn btn-sm btn-info" onclick="savePenambahanPNWL(<?= $row->ID ?>)" data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>
                                                                <button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/ereg/All_ver_pn/popUpAdd/2/<?= $row->ID ?>/ws" title="Edit"><i class="fa fa-pencil"></i></button>
                                                                <button class="btn btn-sm btn-danger" data-toggle="tooltip" title="cancel" onclick="cancelVerPN(<?= $row->ID ?>, 2)"><i class="fa fa-close " style="color:white;"></i></button>
                                                            </td>
                                                        </tr>

                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </tbody>
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
                                                        <th width="30px">No.</th>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN LAMA</th>
                                                        <th width="80px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    $list_non = ($cek_temp_pn > 0) ? $list_ver_pnwl_non_aktif : array();
                                                    foreach ($list_ver_pnwl_non_aktif as $row):
                                                        $i++;
                                                        ?>
                                                        <tr>
                                                            <td><small><?= $i ?></small></td>
                                                            <td><small><?= $row->NIK ?></small></td>
                                                            <td><small><?= strtoupper($row->NAMA) ?></small></td>
                                                            <td><small><?= '<b>' . $row->NAMA_JABATAN . '</b> - ' . $row->SUK_NAMA . ' - ' . $row->UK_NAMA ?></small></td>
                                                            <td>
                                                                <button class="btn btn-sm btn-info" onclick="ApproveNonActPN(<?= $row->ID_PN_JAB ?>)"  data-toggle="tooltip" title="approve"><i class="fa fa-check"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger"  onclick="cancel_nonactws(<?= $row->ID_PN . ',' . $row->ID_PN_JAB ?>)" title="Cancel Non Active "><i class="fa fa-repeat"></i></button>
    <!--                                                                <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="edit data"><i class="fa fa-pencil"></i></button>
                                                                <button class="btn btn-sm btn-primary" data-toggle="tooltip" title="cancel"><i class="fa fa-close " ></i></button>-->
                                                            </td>
                                                        </tr>

                                                        <?php
                                                    endforeach;
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
                    server_url = 'index.php/ereg/All_ver_pn/ajax_update_jabpnwl_ws/' + idpn;
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
            }

            function savePenambahanPNWL(idpn) {
                if (confirm('Apakah Anda Yakin ?')) {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_ver_pn/ajax_save_add_pnwl_ws/' + idpn;
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
//                                $('#loader_area').hide();
                                $.get(location.href.split('#')[1], function(html) {
                                    $('#ajax-content').html(html);
//                                    CloseModalBox();

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
                    server_url = 'index.php/ereg/All_ver_pn/cancelVerPNws/' + idpn;
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
//                              $('#allpage').replaceWith($('#allpage', $(htmldata)));
//                            if (st === 1) {
//                                $('#refup').replaceWith($('#refup', $(htmldata)));
//                                $('#tabone').replaceWith($('#tabone', $(htmldata)));
//                            } else if (st === 2) {
//                                $('#refadd').replaceWith($('#refadd', $(htmldata)));
//                                $('#tabtwo').replaceWith($('#tabtwo', $(htmldata)));
//                            }
                                $('#loader_area').hide();
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
                                alertify.success(msg.success);
//                              $('#allpage').replaceWith($('#allpage', $(htmldata)));
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


            function cancel_nonactws(idpn, idjb) {
                if (confirm('Apakah Anda Yakin ?')) {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_ver_pn/cancel_nonactws/' + idpn + '/' + idjb;
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



            function KirimEmail(idpn) {
                $('#loader_area').show();
                server_url = 'index.php/ereg/All_ver_pn/kirim_email/ws';
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
                            $.get(location.href.split('#')[1], function(html) {
                                $('#ajax-content').html(html);

                                $('#loader_area').hide();
                            })
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });

                alertify.success(msgmail.success);
                $.get(location.href.split('#')[1], function(html) {
                    $('#ajax-content').html(html);

                    $('#loader_area').hide();
                })

            }


        </script>

    </div>
</div>
</div> <!-- /container -->
