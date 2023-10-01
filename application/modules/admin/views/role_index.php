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
               <div class="box box-primary">
                <div class="box-header with-border">
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                  <button class="btn btn-sm btn-primary" id="btn-add" href="index.php/admin/role/addrole"><i class="fa fa-plus"></i> Tambah Data</button>
                    <div id="roleIndex">  
                      <div class="box-tools">
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/admin/role/">
                          
                            <div class="input-group">
                            <input type="text" name="CARI[role]" id="cari" class="form-control input-sm pull-right" value="<?=@$cari?>" style="width: 150px;" placeholder="Search By Role"/>
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                  <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                            </div>
                            </div>
                        </form>
                      </div>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                <table class="table">
                    <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th width="250">Role</th>
                        <th>Deskripsi</th>
                        <th width="100">Aksi</th>
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
                            <td align="center"><small><?php echo ++$i; ?>.</small></td>
                            <!-- <td><span style="color: <?php echo $item->COLOR; ?>"><?php echo $item->ROLE; ?></span></td> -->
                            <td><small><?php echo $item->ROLE?></small></td>
                            <td><small><?php echo $item->DESCRIPTION; ?></small></td>
                            <?php
                            // if ( $this->makses->is_write ) {
                                ?>
                                <td style="text-align: center;"><small>
                                <button type="button" class="btn btn-sm btn-success btn-edit"
                                            href="index.php/admin/role/editrole/<?php echo $item->ID_ROLE ?>" title="Edit">
                                        <i class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-warning btn-editpermission"
                                            href="index.php/admin/role/role_permission/<?php echo $item->ID_ROLE ?>" title="Edit Permission">
                                        <i class="fa fa-unlock-alt"></i></button>
                                </small></td>
                                <?php
                            // }
                            ?>
                        </tr>
                        <?php
                        // $end = $i;
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
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo  $start; ?> to <?php echo  @$end; ?>
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
            url = url.replace('<?=base_url();?>', '');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#roleIndex'));
            return false;
        });

        $("#btn-add").click(function() {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Tambah Role', html, '');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/admin/role');
            });
            return false;
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Permission', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/admin/role');
            });            
            return false;
        });
        
        $('.btn-editpermission').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Permission', html, '');
                // ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/admin/role');
            });            
            return false;
        });

        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            console.log($(this));
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Role', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/admin/role');
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
