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
 * @package Views/menu
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Daftar Menu
            <small>daftar menu</small>
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
                  <button type="button" class="btn btn-sm btn-primary" id="btn-add" href="index.php/admin/menu/addmenu"><i class="fa fa-plus"></i> Tambah Data</button>
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

<?php
/** View Tree Menu List
 * 
 * @todo Marge with other location different parent and subtree view
 * @package Views
 */
class mytreemenu
{
    
    function __construct()
    {
        $CI = get_instance();
        $cari = $CI->input->post('cari')?$this->input->post('cari', TRUE):'';
        $sql = "SELECT 0 AS parent, GROUP_CONCAT( c.ID_MENU ORDER BY c.WEIGHT) AS children
         FROM T_MENU AS c 
         WHERE c.PARENT = 0
         GROUP BY c.PARENT 
        UNION
        SELECT p.ID_MENU AS parent, GROUP_CONCAT( c.ID_MENU ORDER BY c.WEIGHT) AS children
         FROM T_MENU AS p
         JOIN T_MENU AS c ON c.PARENT = p.ID_MENU
         WHERE p.MENU LIKE '%$cari%' OR p.SYSNAME LIKE '%$cari%' OR c.MENU LIKE '%$cari%' OR c.SYSNAME LIKE '%$cari%'
         GROUP BY p.ID_MENU
        ";
        // echo $sql;
        $res = $CI->db->query($sql);
        $parents = Array();
        foreach ($res->result() as $row){
            $parents[$row->parent] = explode(',',$row->children);   
        }
        $this->parents = $parents;

        $sql = "SELECT * FROM T_MENU";
        $res2 = $CI->db->query($sql);
        $rows = Array();
        foreach ($res2->result() as $row){
            $rows[$row->ID_MENU] = $row;
        }  
        $this->rows = $rows;      
    }

    function subtree($id, $n=0) { 
        $parents = $this->parents;
        // print_r($this->rows);
        $rows = $this->rows;
        $ulclass = $liclass = $aclass = '';
        if($id>0){
            $row = $rows[$id];
            if (isset($parents[$id])){$ulclass='dropdown-menu';$liclass='dropdown';$aclass='dropdown-toggle';}else{$aclass='ajax-link';}
            // echo '<li class="'.$liclass.'">';
            // echo '<a href="'.(isset($row->URL)?$row->URL:'#')
            // .'" class="'.$aclass.'"><i class="fa '
            // .(isset($row->ICON)?$row->ICON:'')
            // .'" style="color:'.$row->ICON_COLOR.'"></i> '
            // .$row->MENU.'</a>';
            ?>
            <tr>
                <td><?php echo str_repeat('__', $n);?> <i class="fa <?php echo $row->ICON; ?>" style="color:<?php echo $row->ICON_COLOR; ?>;"></i> <?php echo $row->MENU; ?></td>
                <td style="font-size:10px;"><?php echo $row->SYSNAME; ?></td>
                <td style="font-size:10px;"><?php echo $row->URL; ?></td>
                <td style="font-size:10px;"><?php echo $row->MODULE; ?></td>
                <td style="font-size:10px;"><?php echo $row->CONTROLLER; ?></td>
                <td style="font-size:10px;"><?php echo $row->METHOD; ?></td>
                <td style="font-size:10px;"><?php echo $row->IS_ACTIVE == '1' ? 'active' : '<font color="red">inactive</font>'; ?></td>
                <td width="100">
                    <button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/admin/menu/editmenu/<?php echo $row->ID_MENU;?>" title="Edit"><i
                            class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/admin/menu/deletemenu/<?php echo $row->ID_MENU;?>" title="Delete"><i 
                            class="fa fa-trash"></i></button>
                </td>
            </tr>
            <?php

            if (isset($parents[$id])){
                $n++;
                // echo '<ul class="'.$ulclass.'">';
                foreach ($parents[$id] as $child) 
                {
                    // echo str_repeat('--', $n-1);
                    $this->subtree($child, $n);
                }
                // echo '</ul>';
            } 
            // echo '</li>';
        }else{
            foreach ($parents[$id] as $child) 
            {
                // echo str_repeat('--', $n-1);
                $this->subtree($child, $n);
            }   
        }
    }
}

$mytreemenu = new mytreemenu();
// echo '<ul class="nav main-menu">';
// $mytreemenu->subtree(0);
// echo '</ul>';

?>  
                <!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                <table class="table">
                    <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Sysname</th>
                        <th>Link</th>
                        <th>Module</th>
                        <th>Controller</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $mytreemenu->subtree(0); ?>
                    </tbody>
                </table>

<?php
/*
?>                
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                        <td width="30">No.</td>
                        <td>Parent</td>
                        <td>Weight</td>
                        <td>Menu</td>
                        <td>Sysname</td>
                        <td>Link</td>
                        <td>Status</td>
                        <td>Aksi</td>
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
                            <td><?php echo $item->PARENT; ?></td>
                            <td><?php echo $item->WEIGHT; ?></td>
                            <td><i class="fa <?php echo $item->ICON; ?>" style="color:<?php echo $item->ICON_COLOR; ?>;"></i> <?php echo $item->MENU; ?></td>
                            <td style="font-size:10px;"><?php echo $item->SYSNAME; ?></td>
                            <td style="font-size:10px;"><?php echo $item->URL; ?></td>
                            <td style="font-size:10px;"><?php echo $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?></td>
                            <td width="100">
                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/admin/menu/editmenu/<?php echo $item->ID_MENU;?>" title="Edit"><i
                                        class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/admin/menu/deletemenu/<?php echo $item->ID_MENU;?>" title="Delete"><i 
                                        class="fa fa-trash" style="color:red;"></i></button>
                            </td>
                        </tr>
                        <?php
                        $end = $i;
                    }
                    ?>
                    </tbody>
                </table>

                <div class="box-content">
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

<?php
*/
?>

                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php
                    /*
                        if($total_rows){
                    ?>
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo  $start; ?> to <?php echo  $end; ?>
                            of <?php echo  $total_rows; ?> entries
                        </div>
                    </div>
                    <?php
                        }
                        */
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
        
        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Menu', html, '');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/admin/menu');
            });            
            return false;            
        }); 

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Menu', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/admin/menu');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Menu', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/admin/menu');
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