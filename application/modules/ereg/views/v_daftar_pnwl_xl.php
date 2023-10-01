<?php
$instansi_found = isset($instansi_found) ? $instansi_found : FALSE;
$sUnitKerja = isset($sUnitKerja) ? $sUnitKerja : 0;
?>

<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>DAFTAR PN/WL</strong> <small>VIA EXCEL</small></div>
    </div>
    <?php echo $breadcrumb; ?>
    <div class="panel panel-default">
        <div class="panel-body" >
          <div class="col-md-8">
              <div class="row">
                    <form method="post"  class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                      <?php echo $v_cb_instansi; ?>
                    </form>
              </div>
            </div>
            <div class="col-md-0">
              <br>
              </br>
              <br>
              </br>
              <div class="row">
                <button class="btn btn-sm btn-primary" id="btn-add-exc" href="index.php/ereg/all_pn_down_excel/DownUpExcels"><i class="fa fa fa-file-excel-o"></i> Upload Excel</button>
              </div>
              </div>
    </div>
    </div>
</section>

<section class="content">
 <div class="row">
   <div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body" >
        <?php
        $cek_temp_pn = $list_cek_temp;
        $up_pn = (is_array($list_ver_pnwl) && count($list_ver_pnwl)) ? count($list_ver_pnwl) : 0;
        $add_pn = count($list_ver_pnwl_tambah) ? count($list_ver_pnwl_tambah) : 0;
        $non_pn = count($list_ver_pnwl_non_aktif) ? count($list_ver_pnwl_non_aktif) : 0;
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
        <section class="content" style="background:#ffffff">
            <div class="row" id="allpage">
                <!-- <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                    <?php echo $v_cb_instansi; ?>
                </form> -->
                <div class="col-md-12">

                    <div class="nav-tabs-custom" >
                        <ul class="nav nav-tabs">
                            <li class="<?php echo $cls_act1 ?>"><a href="#tab_1" data-toggle="tab" >Penambahan PN/WL <span id="spanTabPenambahan" ></span></a></li>
                            <li class="<?php echo $cls_act2 ?>"><a href="#tab_2" data-toggle="tab" >Perubahan Jabatan <span id="spanTabPerubahan" ></span></a></li>
                            <li class="<?php echo $cls_act3 ?>"><a href="#tab_3" data-toggle="tab" >Non Wajib Lapor<span id="spanTabNonAktif" ></span></a></li>
                        </ul>
                        <div class="tab-content" >
                            <div class="tab-pane  active divTab1" id="tab_1">
                                <b>Penambahan PN/WL</b>
                                <div class="box" >
                                    <div class="box-body" >
                                        <table role="grid" id="dt_complete1" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th width="130px">NIP</th>
                                                    <th width="170px">NAMA</th>
                                                    <th>JABATAN</th>
                                                    <th width="50px">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div><!-- /.box-body -->

                                </div>
                            </div><!-- /.tab-pane -->

                            <div  class="tab-pane divTab2 " id="tab_2">
                                <b>Perubahan Jabatan</b>
                                <div class="box">

                                    <div class="box-body">
                                        <table id="dt_complete" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th width="170px">NAMA</th>
                                                    <th>JABATAN LAMA</th>
                                                    <th>JABATAN BARU</th>
                                                    <th width="50px">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div><!-- /.box-body -->

                                </div>
                            </div><!-- /.tab-pane -->
                            <div class="tab-pane divTab3" id="tab_3">
                                <b>Wajib Lapor Non Aktif</b>
                                <div class="box">
                                    <div class="box-body">
                                        <table id="dt_complete2" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th>NAMA</th>
                                                    <th>JABATAN LAMA</th>
                                                    <th width="50px">AKSI</th>
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
            </section>
    </div>    <!-- /.content -->
    </div>
    </div>
    </div><!-- /.row -->
</section>
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