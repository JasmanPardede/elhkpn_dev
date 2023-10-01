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
 * @author Arif Kurniawan - PT.Mitreka Solusi Indonesia
 * @package Views/role
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Daftar Role
            <small>daftar Role</small>
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
                  <!-- <button class="btn btn-sm btn-default" id="btn-add" href="index.php/role/addrole"><i class="fa fa-plus"></i> Tambah Data</button> -->
<!--                   <div class="box-tools">
                    <div class="input-group">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div> -->
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th width="250">Username</th>
                        <th>Role</th>
                        <?php
                        if ( $this->makses->is_write ) {
                            ?>
                            <th>Hak Akses</th>
                            <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0 + $offset;
                    $start = $i + 1;
                    foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td><?php echo $item->USERNAME; ?></td>
                            <td><?php echo $this->mrole->get_nama_role($item->ID_ROLE); ?></td>
                            <?php
                            if ( $this->makses->is_write ) {
                                ?>
                                <td style="text-align: center;">

                                    <button type="button" class="btn btn-sm btn-default btn-edit"
                                            href="index.php/userakses/userakses_permission/<?php echo $item->ID_USER ?>" title="Edit Permission">
                                        <i class="fa fa-pencil"></i></button> &nbsp;Edit Permission
                                </td>
                                <?php
                            }
                            ?>
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
            // alert('aaaa');
            var url = $(this).attr('href');
            $(this).attr('href','#');
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });


        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Permission', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/userakses');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Role', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/userakses');
            });            
            return false;
        });

    });
    function select_read(obj) {
        if ( obj.checked ) {
            document.getElementById('read_'+obj.id).checked = true;
        }
    }
    function select_write(obj) {
        if ( obj.checked == false ) {
            var split_id = obj.id.split('_');
            var id = split_id[1];
            document.getElementById(id).checked = false;
        }
    }
    function inArray(needle, haystack) {
        var length = haystack.length;
        for(var i = 0; i < length; i++) {
            if(haystack[i] == needle) return true;
        }
        return false;
    }
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>
