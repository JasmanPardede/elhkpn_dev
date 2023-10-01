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
 * @package Views/pn
 */
// $INSTANSI = array();
// foreach ($instansis as $instansi) {
//     $INSTANSI[$instansi->INST_SATKERKD]['NAMA'] = $instansi->INST_NAMA;
// }

$min_tahun = isset($min_tahun) && $min_tahun ? $min_tahun : date('Y');
$default_cari_tahun = isset($default_cari_tahun) && $default_cari_tahun ? $default_cari_tahun : date('Y');

function dropdownMutasi($status_akhir, $idjb) {
    $out = '
    <div class="dropdown pull-right">
        <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    foreach ($status_akhir as $status) {
        $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/' . $status->ID_STATUS_AKHIR_JABAT . '" class="btn-mutasi">' . $status->STATUS . '</a></li>';
    }
    $out .= '    </ul>
    </div>';
    return $out;
}

function dropdownHasilPemilihan($status_akhir, $idjb) {
    $out = '
    <div class="dropdown pull-right">
        <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/58" class="btn-mutasi">Penetapan PN/WL</a></li>';
    foreach ($status_akhir as $status) {
        if ($status->IS_AKHIR == 0 && $status->IS_PINDAH == 0 && $status->IS_AKTIF == 0 && $status->IS_MENINGGAL == 0) {
            $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/' . $status->ID_STATUS_AKHIR_JABAT . '" class="btn-mutasi">' . $status->STATUS . '</a></li>';
        }
    }
    // $out .= '<li><a href="#">Non WL</a></li>';

    $out .= '    </ul>
    </div>';
    return $out;
}

$role_yang_diijinkan_instansi = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_PN_WL_ONLINE')['filter_instansi']);
$role_yang_diijinkan_unit_kerja = in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_PN_WL_ONLINE')['filter_unit_kerja']);

?>

<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>PN/WL ONLINE</strong></div>
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
                            <div class="form-group">
                                <label class="col-sm-4 control-label">WL Tahun :</label>
                                <div class="col-sm-6">
                                    <select id='CARI_TAHUN_WL' name="CARI[TAHUN_WL]">
                                        <option value="">All</option>
                                        <?php while ($min_tahun <= date('Y') + 1): ?>
                                            <option value="<?php echo $min_tahun; ?>" <?php echo $default_cari_tahun == $min_tahun ? "selected=selected" : ""; ?>><?php echo $min_tahun; ?></option>
                                            <?php $min_tahun ++; ?>
                                        <?php endwhile; ?>
                                    </select>



                                </div>
                            </div>

                            <?php /* ?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Status Lapor:</label>
                                <div  class="col-sm-6">
                                    <input type='text' class="input-sm select2 form-control" name='CARI[IS_WL]' style="border:none;padding:6px 0px;" id='CARI_IS_WL' value='<?php echo @$CARI['IS_WL']; ?>' placeholder="-- Pilih Status --">
                                </div>
                            </div>
                            <?php */ ?>

                            <?php //if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 7 || $this->session->userdata('ID_ROLE') == 10 || $this->session->userdata('ID_ROLE') == 13 || $this->session->userdata('ID_ROLE') == 14 || $this->session->userdata('ID_ROLE') == 18 || $this->session->userdata('ID_ROLE') == 31 || $this->session->userdata('ID_ROLE') == 108):
                                if ($role_yang_diijinkan_instansi) : ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Instansi:</label>
                                    <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='<?php echo '1081'; ?>' placeholder="-- Pilih Instasi --">
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">

                            <?php //if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3 || $this->session->userdata('ID_ROLE') == 7 || $this->session->userdata('ID_ROLE') == 10 || $this->session->userdata('ID_ROLE') == 13 || $this->session->userdata('ID_ROLE') == 14 || $this->session->userdata('ID_ROLE') == 18 || $this->session->userdata('ID_ROLE') == 31 || $this->session->userdata('ID_ROLE') == 108):
                                if ($role_yang_diijinkan_unit_kerja) : ?>
                                <div class="form-group" style="margin-bottom:12px">
                                    <label class="col-sm-4 control-label">Unit Kerja:</label>
                                    <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                                        <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='<?php echo '590'; ?>' placeholder="-- Pilih Unit Kerja --">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cari :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_completeNEW_cari_"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3 col-sm-offset-4">
                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn-cetak btn btn-default btn-sm btn-same" style="background-color: #34ac75; margin-right: 0px;">
                        <span class="logo-mini">
                            <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                        </span> Print to Excel 
                    </a>
                </div>
            </form>

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools">
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body"> <br/>
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <!-- <table id="dt_completeNEW" class="table table-striped"> -->
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="120px">NIK</th>
                                <th width="170px">Nama</th>
                                <th>Jabatan</th>
                        <!--        <th width="80">Riwayat Jabatan</th>
                                <?php
                                if ($this->makses->is_write) {
                                    ?>
                                                                    <th>Password</th>
                                <?php } ?>
                         <th>LHKPN</th> -->
                                <?php //if ($this->session->userdata('ID_ROLE') != '3' && $this->session->userdata('ID_ROLE') != '4'):
                                      $role = !in_array($this->session->userdata('ID_ROLE'), $this->config->item('AKSES_ROLE_PAGE_PN_WL_ONLINE')['form_aktivasi_efiling_except']);
                                      if ($role) : ?>
                                <th width="120px">Formulir Aktifasi Efilling</th>
                                <?php endif; ?>
                                <th width="50px">WL Tahun</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <!-- <div class="box-footer clearfix">
                    <?php
                    if ($total_rows) {
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
                </div> -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
    </div>
    </div><!-- /.row -->
</section><!-- /.content -->

<style type="text/css">
    .listjabatan{
        margin: 0px;
        padding:0px;
    }
    .listjabatan li.item{
        list-style: none;
        border: 1px solid #cfcfcf;
        padding-left: 10px;
        padding-bottom: 12px;
        margin-top: -1px;
    }
    ul.dropdown-menu {
        background-color: #99E1F4;
    }
    .listjabatan .dropdown{
        padding-right: 10px;
    }
</style>

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

<style>
    td .btn {
        margin: 0px;
    }
</style>
