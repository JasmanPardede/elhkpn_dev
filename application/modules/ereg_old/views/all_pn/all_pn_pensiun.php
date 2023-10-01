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

$INSTANSI = array();
foreach ($instansis as $instansi) {
    $INSTANSI[$instansi->INST_SATKERKD]['NAMA'] = $instansi->INST_NAMA;
}

?>

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Pensiun
            <small>daftar Pensiun</small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                  <button class="btn btn-sm btn-default" id="btn-add" href="index.php/ereg/pn/addpn"><i class="fa fa-plus"></i> Tambah Data</button>
                  <div class="box-tools">
                    <div class="input-group">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Bidang</th>
                            <th>Lembaga</th>
                            <th>Tingkat</th>
                            <th>Unit Kerja</th>
                            <!-- <th>LHKPN</th> -->
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            $end = 0;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><?php echo ++$i; ?>.</td>
                            <td><?php echo $item->NIK; ?></td>
                            <td><?php echo $item->NAMA; ?></td>
                            <td><?php echo $item->JABATAN; ?></td>
                            <td><?php echo $item->BIDANG; ?></td>
                            <td><?php echo $INSTANSI[$item->LEMBAGA]['NAMA']; ?></td>
                            <td><?php echo $item->TINGKAT; ?></td>
                            <td><?php echo $item->UNIT_KERJA; ?></td>
                            <!-- <td>7x LHKPN Verified, LHKPN TERAKHIR, CREATE Laporan</td> -->
                            <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/ereg/pn/detailpn/<?php echo $item->ID_PN; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/ereg/pn/editpn/<?php echo $item->ID_PN;?>" title="Edit"><i
                                class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/pn/deletepn/<?php echo $item->ID_PN;?>" title="Delete"><i
                                class="fa fa-trash" style="color:red;"></i></button>
                            </td>
                        </tr>
                        <?php
                                $end = $i;
                            }
                        ?>
                    </tbody>
                </table>
                </div><!-- /.box-body -->
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
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->


<script language="javascript">
    $(document).ready(function () {
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

        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').trigger('submit');
        });

        $(".btn-detail").click(function () {
           url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Detail Penyelenggara Negara', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Tambah Penyelenggara Negara', html, '');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/ereg/pn');
            });            
            return false;              
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Edit Penyelenggara Negara', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/ereg/pn');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Delete Penyelenggara Negara', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/ereg/pn');
            });            
            return false;
        });

    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>


