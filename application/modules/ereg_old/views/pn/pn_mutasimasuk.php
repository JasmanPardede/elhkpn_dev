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
 * @package Views/pejabat
*/
?>

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Mutasi Masuk
            <small>daftar Mutasi Keluar Penyelenggara Negara</small>
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
                    <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
                    <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
                    <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>                      
                      <div class="box-tools">
                          <form id="ajaxFormCari" method="post" action="index.php/ereg/pn/mutasimasuk">

                              <div class="input-group">
                                  <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" value="<?php echo $cari; ?>"/>
                                  <div class="input-group-btn">
                                      <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div><!-- /.box-header -->
                  <p>&nbsp;</p>
                <div class="box-body">
                <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th colspan="1" rowspan="2">No.</th>
                            <th colspan="1" rowspan="2">Nip</th>
                            <th rowspan="2">Nama</th>
                            <th colspan="2" rowspan="1">Jabatan</th>
                            <th colspan="2" rowspan="1">Instansi</th>
                            <th colspan="1" rowspan="2">Status Approval</th>
                            <th colspan="1" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Sebelum</th>
                            <th>Tujuan</th>
                            <th>Sebelum</th>
                            <th>Tujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><?php echo  ++$i; ?></td>
                            <td><?php echo  $item->NIK; ?></td>
                            <td><?php echo  $item->NAMA; ?></td>
                            <td><?php echo  $this->mmutasi->get_nm_jabatan($item->JABATAN_LAMA); ?></td>
                            <td><?php echo  $this->mmutasi->get_nm_jabatan($item->JABATAN_BARU); ?></td>
                            <td><?php echo  $this->mmutasi->get_nm_instansi($item->ID_INST_ASAL); ?></td>
                            <td><?php echo  $this->mmutasi->get_nm_instansi($item->ID_INST_TUJUAN); ?></td>
                            <td><?php echo  ($item->STATUS_APPROVAL==0) ? "MENUNGGU" : "APPROVED"; ?></td>
                            <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/ereg/pn/approvmutasi/<?php echo  $item->ID_MUTASI; ?>" title="Approved"><i
                                class="fa fa-check" style="color:green;"></i></button>
								
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/pn/tolakmutasi/<?php echo $item->ID_MUTASI;?>" title="Delete"><i
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
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo  $start; ?> to <?php echo  $end; ?>
                            of <?php echo  $total_rows; ?> entries
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
                OpenModalBox('Approved Mutasi Penyelenggara Negara', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
				OpenModalBox('Tambah Mutasi Penyelenggara Negara', html, '');
			   
            });            
            return false;              
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
               OpenModalBox('Edit Mutasi Penyelenggara Negara', html, '');
			   
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/pejabat');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Tolak Mutasi Penyelenggara Negara', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'tolak', 'index.php/pejabat');
            });            
            return false;
        });

    });
//DataTables
    $(function() {
        $('#dt_completeNEW').dataTable({
            "bPaginate": false,
            "bLengthChange": true,
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true,
            "scrollY": '50vh',
            "scrollCollapse": true,
        });
    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>


