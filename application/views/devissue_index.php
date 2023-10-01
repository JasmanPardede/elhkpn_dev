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
 * @package Views/devissue
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Issue
            <small>daftar issue</small>
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
                  <!-- <button class="btn btn-sm btn-default" id="btn-add" href="index.php/issue/addissue"><i class="fa fa-plus"></i> Tambah Data</button> -->
                    Filter Status : 
                    <button type="button" class="btn btn-sm btn-default btnAll">All</button>
                    <button type="button" class="btn btn-sm btn-default btnDone"><i class="fa fa-check" title="DONE" style="text-shadow: 1px 1px 1px #cfcfcf;"></i></button>
                    <button type="button" class="btn btn-sm btn-default btnUndone"><i class="fa fa-cogs" title="UNDONE" style="color:red;text-shadow: 1px 1px 1px #cfcfcf;"></i></button>
                  
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
        $cari = $CI->input->post('cari')?$CI->input->post('cari'):'';
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

    function issueList($ID_MENU, $MENU){
    	$CI = get_instance();
    	$sql = "SELECT * FROM T_DEV_ISSUE WHERE ID_MENU = '".$ID_MENU."'";
    	$issues = $CI->db->query($sql)->result();

    	?>
			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom tbl_issue">
				<?php
				foreach ($issues as $issue) {
				?>
				<tr class="issue <?php echo $issue->IS_DONE==1?'issueDone':'issueUndone';?>">
					<td>
                        <?php
                        if ($issue->PHOTO) {
                        ?>
                        <div class="avatar">
                            <img src="<?php echo base_url();?>upload/<?php echo $issue->PHOTO; ?>" class="img-rounded" alt="">
                        </div>
                        <?php
                        }
                        ?>
                        <?php echo $issue->TITLE;?>
                        <?php 
                            $TAGS = explode(' ', $issue->TAGS);
                            foreach ($TAGS as $TAG) {
                                echo '<span style="background-color: rgb(35, 82, 124);" class="badge itemBadge">'.$TAG.'</span>';
                            }
                        ?>
        			</td>
                    <td><?php echo $issue->DESCRIPTION;?></td>
					<td><?php echo $issue->RESOLUTION;?></td>
					<td><?php echo $issue->IS_DONE==1?'<i class="fa fa-check" title="DONE" style="text-shadow: 1px 1px 1px #cfcfcf;"></i>'.' <span style="font-size:70%;">('.date('d-m-Y H:i:s', strtotime($issue->DONE_TIME)).')</span> ':'<i class="fa fa-cogs" title="UNDONE" style="color:red;text-shadow: 1px 1px 1px #cfcfcf;"></i>';?></td>
					<td width="120" nowrap="">
			            <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/devissue/editdevissue/<?php echo $issue->ID_ISSUE;?>" title="Edit" relname="<?php echo $MENU; ?>"><i
			                    class="fa fa-pencil"></i></button>
			            <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/devissue/deletedevissue/<?php echo $issue->ID_ISSUE;?>" title="Delete"><i 
			                    class="fa fa-trash" style="color:red;"></i></button>
					</td>
				</tr>
				<?php
				}
				?>
			</table>
    	<?php
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
                <!-- <td><?php // echo str_repeat('__', $n);?> <?php echo $row->WEIGHT; ?></td> -->
                <td width="200"><?php echo str_repeat('__', $n);?> <i class="fa <?php echo $row->ICON; ?>" style="color:<?php echo $row->ICON_COLOR; ?>;text-shadow: 1px 1px 1px #cfcfcf;"></i> <a href="<?php echo $row->URL; ?>" class="linkmenu"><?php echo $row->MENU; ?></a></td>
                <!-- <td style="font-size:10px;"><?php // $row->SYSNAME; ?></td> -->
                <!-- <td style="font-size:10px;"><?php // $row->URL; ?></td> -->
                <!-- <td style="font-size:10px;"><?php // $row->IS_ACTIVE == '1' ? 'active' : '<font color="red">inactive</font>'; ?></td> -->
                <td>
                	<button type="button" class="btn btn-sm btn-default btn-add" href="index.php/devissue/adddevissue" rel="<?php echo $row->ID_MENU;?>" relname="<?php echo $row->MENU; ?>"><i class="fa fa-plus"></i></button>
                	<?php $this->issueList($row->ID_MENU, $row->MENU);?>
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
?>  
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                        <!-- <th>Weight</th> -->
                        <th>Menu</th>
                        <!-- <th>Sysname</th> -->
                        <!-- <th>Link</th> -->
                        <!-- <th>Status</th> -->
                        <th>Issue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $mytreemenu->subtree(0); ?>
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

        $(".linkmenu").click(function (e) {
            e.preventDefault(); //Prevent Default action.
            url = $(this).attr('href');
            window.location.hash = url;
            LoadAjaxContent(url);
            e.unbind();	            
            return false;
        });
        
        $(".btn-add").click(function () {
            $.ajaxSetup({
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            });

            url = $(this).attr('href');
            rel = $(this).attr('rel');
            relname = $(this).attr('relname');
            $.post(url, function (html) {
                OpenModalBox('Tambah Issue', html, '');
                $('#ID_MENU').val(rel);
                $('#MENUNAME').html(relname);
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/devissue');
            });            
            return false;            
        }); 

        $('.btn-edit').click(function (e) {
            $.ajaxSetup({
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            });
                        
            url = $(this).attr('href');
            relname = $(this).attr('relname');
            $.post(url, function (html) {
                OpenModalBox('Edit Issue', html, '');
                $('#MENUNAME').html(relname);                
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/devissue');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Issue', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/devissue');
            });            
            return false;
        });


        $('.btnAll').click(function(){
            $('.issueDone').show();
            $('.issueUndone').show();
        });        

        $('.btnDone').click(function(){
            $('.issueDone').show();
            $('.issueUndone').hide();
        });        

        $('.btnUndone').click(function(){
            $('.issueDone').hide();
            $('.issueUndone').show();
        });

    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
    .tbl_issue{
    	font-size: 11px;
    }
    .itemBadge {
        font-size: 7px;
        box-shadow: 1px 1px 3px #CFCFCF;
    }
    .btn-edit, .btn-delete{
        /*font-size: 7px;*/
    }
</style>

