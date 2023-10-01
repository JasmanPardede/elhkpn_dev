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
            PN/WL Pindah
            <small></small>
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
                  <br>
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
                <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th colspan="1" rowspan="2" style="vertical-align: middle; text-align: center;">No.</th>
                            <th colspan="1" rowspan="2" style="vertical-align: middle; text-align: center;">NIK</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center;">Nama</th>
                            <th colspan="2" rowspan="1" style="vertical-align: middle; text-align: center;">Sebelum</th>
                            <th colspan="2" rowspan="1" style="vertical-align: middle; text-align: center;">Tujuan</th>
                            <th colspan="1" rowspan="2" style="vertical-align: middle; text-align: center;">Status Approval</th>
                            <th colspan="1" rowspan="2" style="vertical-align: middle; text-align: center;">Status Mutasi</th>
                            <th colspan="1" rowspan="2" style="vertical-align: middle; text-align: center;">Aksi</th>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle; text-align: center;">Jabatan</th>
                            <th style="vertical-align: middle; text-align: center;">Instansi</th>
                            <th style="vertical-align: middle; text-align: center;">Jabatan</th>
                            <th style="vertical-align: middle; text-align: center;">Instansi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            
                            $i = 0 + $offset;
                            $start = $i + 1;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><?php echo  ++$i; ?>.</td>
                            <td><?php echo  $item->NIK; ?></td>
                            <td><?php echo  $item->NAMA; ?></td>
                            <td><?php echo  $this->mjabatan->get_nama_jabatan($item->ID_JABATAN); ?></td>
                            <td><?php echo  $this->mmutasi->get_nm_instansi($item->ID_INST_ASAL); ?></td>
                            <td><?php echo  $this->mjabatan->get_nama_jabatan($item->ID_JABATAN_BARU); ?></td>
                            <td><?php echo  $this->mmutasi->get_nm_instansi($item->ID_INST_TUJUAN); ?></td>
                            <td><?php 
								if($item->STATUS_APPROVAL==0){	
									echo "MENUNGGU"; 
								}else if($item->STATUS_APPROVAL==-1){	
									echo "DITOLAK"; 	
								}else{
									"APPROVED"; 
								}?></td>
                            <td><?=$item->STATUS_JABAT?></td>
                            <td width="120" nowrap="" style="vertical-align: middle; text-align: center;">
                                <?php if($this->mmutasi->get_nm_instansi($item->ID_INST_ASAL) == $this->session->userdata('INST_NAMA')){ ?>
                                    <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/all_pn/tolakmutasi/<?php echo $item->ID_MUTASI.'/2';?>" title="Batal Mutasi">
                                    <img src="<?= base_url('img/stop.png');?>" style="width:15px;height15px;">
                                <?php }else{ echo ''; } ?>
                                </button>
                            </td>
                        </tr>
                        <?php
                                $end = $i;
                            }
                        ?>
                        <?php
                        // echo (count($items) == 0 ? '<tr><td colspan="10" class="items-null">Data tidak ditemukan!</td></tr>' : '');
                        ?>
                    </tbody>
                </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php
                        if($total_rows){
                    ?>
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo  $start; ?> to <?php echo (isset($end)) ? $end : 0; ?>
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
                OpenModalBox('Detail Pejabat', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
				OpenModalBox('Tambah Mutasi Penyelenggara', html, '');
			   
            });            
            return false;              
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
               OpenModalBox('Edit Mutasi Pejabat', html, '');
			   
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/ereg/pn/');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Batal Mutasi', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/ereg/pn/');
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


