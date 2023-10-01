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
 * @package Views/mail
*/
?>

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Mail
            <small>Mail</small>
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
                  <button type="button" class="btn btn-sm btn-default" id="btn-add" href="index.php/mail/addmail"><i class="fa fa-plus"></i> Tambah</button>
                  <div class="box-tools">
                    <form method="post" id="ajaxFormCari" action="index.php/mail/index">
                    <div class="input-group">
                      <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" value="<?php echo $cari;?>" id="cari"/>
                      <div class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                    </form>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">

                <table id="example1" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th>Id Mail</th>
                            <th>Nomor/Tanggal</th>
                            <th>Dari</th>
                            <th>Kepada</th>
                            <th>Perihal</th>
                            <th>Need Response</th>
                            <th>Response Status</th>
                            <th>Response</th>
                            <th>Mail Status</th>
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
                            <td><?php echo  ++$i; ?></td>
                            <td><?php echo  $item->ID_MAIL; ?></td>
                            <td><?php echo  $item->NOMOR; ?><br>
                                <?php echo  $item->TANGGAL; ?>
                                <button type="button" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
                                <?php echo  $item->ATTACHMENT; ?>
                            </td>
                            <td><?php echo  $item->DARI; ?></td>
                            <td><?php echo  $item->KEPADA; ?></td>
                            <td><?php echo  $item->PERIHAL; ?></td>
                            <td><?php echo  $item->NEED_RESPONSE; ?></td>
                            <td><?php echo  $item->RESPONSE_STATUS; ?></td>
                            <td>
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <tr>
                                        <th>#</th>
                                        <th>Tgl</th>
                                        <th>Nomor</th>
                                        <th>Perihal</th>
                                        <th>Attachment</th>
                                    </tr>                                    
                                    <tr>
                                        <td>#</td>
                                        <td>Tgl</td>
                                        <td>Nomor</td>
                                        <td>Perihal</td>
                                        <td><button type="button" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button></td>
                                    </tr>                                    
                                    <tr>
                                        <td>#</td>
                                        <td>Tgl</td>
                                        <td>Nomor</td>
                                        <td>Perihal</td>
                                        <td><button type="button" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button></td>
                                    </tr>
                                </table>
                            </td>
                            <td><?php echo $item->MAIL_STATUS == '1' ? 'active' : '<font color="red">inactive</font>'; ?></td>
                            <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/mail/detailmail/<?php echo  $item->ID_MAIL; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/mail/editmail/<?php echo $item->ID_MAIL;?>" title="Edit"><i
                                class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/mail/deletemail/<?php echo $item->ID_MAIL;?>" title="Delete"><i
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
            $.post(url, function (html) {
                OpenModalBox('Detail Mail', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Mail', html, '');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/mail');
            });            
            return false;              
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Mail', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/mail');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Mail', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/mail');
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


