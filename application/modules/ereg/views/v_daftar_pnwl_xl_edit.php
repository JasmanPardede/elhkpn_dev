<?php
$instansi_found = isset($instansi_found) ? $instansi_found : FALSE;
?>
<div class="container" >

    <div class="panel panel-default">
        <div class="panel-heading"><strong>Daftar PN/WL</strong> <small>Via Excel</small> <?php echo!$instansi_found ? '(Admin ini tidak terkait dalam satu instansi manapun)' : ''; ?></div>
        <div class="box-header with-border">
            <button class="btn btn-sm btn-primary" id="btn-add-exc" href="index.php/ereg/all_pn/DownUpExcels"><i class="fa fa fa-file-excel-o"></i> Upload Excel</button>
        </div>

        <div class="panel-body" id="allpage">

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $cek_temp_pn = count($list_cek_temp);
                        $up_pn = count($list_ver_pnwl);
                        $add_pn = count($list_ver_pnwl_tambah);
                        $non_pn = count($list_ver_pnwl_non_aktif);
                        $non_pn = ($cek_temp_pn > 0) ? $non_pn : 0;
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
                                                    </tr>
                                                </thead>
                                                <tbody>

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


            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();

                $('.over').popover();
                $('.over').on('click', function (e) {
                    $('.over').not(this).popover('hide');
                });


                $('.btn-edit').click(function (e) {
                    url = $(this).attr('href');
                    $('#loader_area').show();
                    $.post(url, function (html) {
                        OpenModalBox('Edit Verifikasi PN/WL', html, '', 'standart');
                    });
                    return false;
                });


                $("#btn-add-exc").click(function () {
                    url = $(this).attr('href');
                    $('#loader_area').show();
                    $.post(url, function (html) {
                        OpenModalBox('Penambahan/Perubahan Data PN/WL Melalui File Excel', html, '', 'large');
                    });
                    return false;
                });


            });
            var msg = {
                success: 'Data Berhasil Disimpan!',
                error: 'Data Gagal Disimpan!'
            };

            function DeleteTempXls(idpn) {
                confirm('Apakah Anda Yakin ?', function () {
                    $('#loader_area').show();
                    server_url = 'index.php/ereg/All_ver_pn/DeleteTempXls/' + idpn;
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
                                $.get(location.href.split('#')[1], function (html) {
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
                });
            }

            function savePenambahanPNWL(idpn) {
                confirm('Apakah Anda Yakin ?', function () {
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
                });
            }
        </script>

    </div>
</div>
</div> <!-- /container -->
