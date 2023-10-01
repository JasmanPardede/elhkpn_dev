
        <?php
        $cek_temp_pn = count($list_cek_temp);
        $up_pn = count($list_ver_pnwl);
        $add_pn = count($list_ver_pnwl_tambah);
        $non_pn = count($list_ver_pnwl_non_aktif);
        $non_pn = ($cek_temp_pn > 0) ? $non_pn : 0;

        //if ($cek_temp_pn > 0 && $up_pn == 0 && $add_pn == 0 && $non_pn == 0) :
        ?>
        <!--            <div class="box-header with-border">
                        <button class="btn btn-sm btn-social" id="btn-email-exc" onclick="KirimEmail()"><i class="fa fa fa-mail-reply-all"></i> Kirim email</button>
                    </div>-->
        <?php //endif; ?>
        <section class="content-header">
          <div class="panel panel-default">
            <div class="panel-heading"><strong>VERIFIKASI PN/WL</strong> <small>VIA EXCEL</small></div>
          </div>
            <?php echo $breadcrumb; ?>
            <div class="panel panel-default">
              <div class="panel-body" >
                <div class="col-md-8">
                    <div class="row">
                      <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                          <?php echo $v_cb_instansi; ?>
                      </form>
                    </div>
                </div>
          </section>

            <section class="content">
                <!-- <div class="row" id="allpage">
                    <!-- <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                        <?php echo $v_cb_instansi; ?>
                    </form> -->
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                      <div class="panel-body" >
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

                                <li class="active" id="tabtwo"><a href="#tab_2" data-toggle="tab">Penambahan PN/WL  <span id="spanTabPenambahan" ></span></a></li>
                                <li class="" id="tabone"><a href="#tab_1" data-toggle="tab">Perubahan Jabatan <span id="spanTabPerubahan" ></span></a></li>
                                <li class="" id="tabtre"><a href="#tab_3" data-toggle="tab">Non Wajib Lapor<span id="spanTabNonAktif" ></span></a></li>

                            </ul>
                            <div class="tab-content" >
                                <div class="tab-pane active" id="tab_2">
                                    <b>Penambahan PN/WL</b>
                                    <div class="box" >

                                        <div class="box-body" >
                                            <table id="tblVerPnwlXl2_new" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th width="20"><input type="checkbox" onClick="chkPenambahan_all(this)" /></th>
                                                        <th>No</th>
                                                        <th>NIK</th>
                                                        <th>NIP</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN</th>
                                                        <th width="90px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="refadd">

                                                </tbody>
                                            </table>
                                            <div id="veriftombolpenambahan" name="veriftombolpenambahan">
                                                <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="verifPenambahan_all();" title="Verifikasi"><i class="fa fa-plus-square"></i> Verifikasi Semua</button>
                                            </div>
                                            <div id="canceltombolpenambahan" name="canceltombolpenambahan">
                                                <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="cancelPenambahan_all();" title="Cancel Verifikasi"><i class="fa fa-minus-square"></i> Cancel Verifikasi Semua</button>
                                            </div>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_1">
                                    <b>Perubahan Jabatan</b>
                                    <div class="box">

                                        <div class="box-body">
                                            <table id="tblVerPnwlXl1_new" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" onClick="chk_all(this);" /></th>
                                                        <th>No</th>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN LAMA</th>
                                                        <th>JABATAN BARU</th>
                                                        <th width="90px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="refup">

                                                </tbody>
                                            </table>
                                            <div id="tombolverifperubahan" name="tombolverifperubahan">
                                                <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="verif_all();"title="Verifikasi"><i class="fa fa-plus-square"></i> Verifikasi Semua</button>
                                            </div>
                                            <div id="tombolcancelperubahan" name="tombolcancelperubahan">
                                                <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="cancel_all();"title="Cancel Verifikasi"><i class="fa fa-minus-square"></i> Cancel Verifikasi Semua</button>
                                            </div>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_3">
                                    <b>PN/WL Non Aktif</b>
                                    <div class="box">

                                        <div class="box-body">
                                            <table id="tblVerPnwlXl3_new" class="table table-striped table-bordered table-hover table-heading no-border-bottom" role="grid">
                                                <thead>
                                                    <tr>
                                                        <th width="20"><input type="checkbox" onClick="chkNonWajibLapor_all(this);" /></th>
                                                        <th>No</th>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>JABATAN LAMA</th>
                                                        <th width="90px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                            <div id="veriftommbolnwl" name="veriftommbolnwl">
                                                <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="verifNonWajibLapor_all();"title="Verifikasi"><i class="fa fa-plus-square"></i> Verifikasi Semua</button>
                                            </div>
                                            <div id="canceltommbolnwl" name="canceltommbolnwl">
                                                <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="cancelNonWajibLapor_all();"title="Cancel Verifikasi"><i class="fa fa-minus-square"></i> Cancel Verifikasi Semua</button>
                                            </div>
                                        </div><!-- /.box-body -->

                                    </div>
                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->
                        <div id="resultdiv"></div>

                    </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.box -->
          </div><!-- /.col -->
        </section><!-- /.content -->



<?php
$js_page = isset($js_page) ? $js_page : '';
if (is_array($js_page)) {
    foreach ($js_page as $page_js) {
        echo $page_js;
    }
} else {
    echo $js_page;
}
?>