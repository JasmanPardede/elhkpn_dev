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
 * @package Views/jabatan
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Jabatan
            <small>Jabatan</small>
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
                  <button class="btn btn-sm btn-default" id="btn-add" href="index.php/admin/jabatan/addjabatan"><i class="fa fa-plus"></i> Tambah Data</button>
                  <div class="box-tools">
                    <form method="post" id="ajaxFormCari" action="index.php/admin/jabatan/index">
                    <div class="input-group col-sm-push-5">
                        <div class="col-sm-3">
                            <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" value="<?php echo @$cari;?>" id="cari"/>
                        </div>
                        
                        <div class="input-group-btn col-sm-3">
                          <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                          <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                        </div>
                        
                    </div>
                    </form>
                    
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th>Nama Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><?php echo ++$i; ?>.</td>
                            <td><?php echo $item->NAMA_JABATAN; ?></td>
                            <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/admin/jabatan/detailjabatan/<?php echo $item->ID_JABATAN; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/admin/jabatan/editjabatan/<?php echo $item->ID_JABATAN;?>" title="Edit"><i
                                class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/admin/jabatan/deletejabatan/<?php echo $item->ID_JABATAN;?>" title="Delete"><i
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
            LoadAjaxContent(url);
            return false;
        });

        $("#ajaxFormCari").submit(function (e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;            
        }); 

        $(".btn-detail").click(function () {
           url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Jabatan', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Jabatan', html, '', 'standart');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/admin/jabatan');
            });            
            return false;              
        });
        // ctrl + a
        $(document).on('keydown', function(e){
            if(e.ctrlKey && e.which === 65 || e.which === 97){
                e.preventDefault();
                $('#btn-add').trigger('click');
                return false;
            }
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Jabatan', html, '', 'standart');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/admin/jabatan');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Jabatan', html, '', 'standart');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/admin/jabatan');
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


