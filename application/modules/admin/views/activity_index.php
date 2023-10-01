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
 * @package Views/activity
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Activity
            <small>Activity</small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
            <div class="box box-primary">
			  	<div class="box-header with-border">
                    <!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    </div> -->                    
                    <!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
                      <div class="col-md-12">  
                        <form method="post"  class='form-horizontal' id="ajaxFormCari" action="index.php/admin/activity/index">
                            <div class="box-body">
                                <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Role :</label>
                                        <div class="col-sm-6">
                                            <select class='form-control' onchange="kabkot();" name='role' id='id_role' >
                                                <option value=''>-Pilih Role-</option>
                                                <?php foreach($roles as $role){ ?>
                                                <option <?php echo ($ROLE == $role->ID_ROLE ? 'selected="selected"' : '') ?> value="<?php echo $role->ID_ROLE; ?>"><?php echo $role->ROLE; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Username :</label>
                                        <div class="col-sm-6">
                                            <select id='cari_username' name="cari_username" class="form-control" placeholder="Search by Username" >
                                                <option value="<?php echo @$USERNAME;?>"><?php echo @$USERNAME;?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Activity :</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cari" class="form-control" placeholder="Search by  Activity" value="<?php echo @$ACTIVITY;?>" id="cari" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Waktu :</label>
                                        <div class="col-sm-4"  >
                                            <input type="text"style="width:190px;" name="cari_waktu" class="daterange form-control" placeholder="Search by Waktu" value='<?=@$CREATED_TIME?>' id="cari_waktu" />
                                        </div>
                                        <!-- <div class="col-sm-5"> input-group-btn -->
                                        <div class="col-sm-5" style="padding-left: 30px">
                                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#cari_waktu').val(''); $('#cari_username').val(''); $('#cari').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                    
                    <!-- <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div> -->
                </div><!-- /.box-header -->
                <div class="box-header with-border">
                </div><!-- /.box-header -->
                <div class="box-body">

                <!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th align="center" width="30">No.</th>
			                 <th width="170">Waktu</th>
                             <th width="170">Username</th>
                             <th>Activity</th>
                            <!-- <th>Aksi</th> -->
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
                			<td><small><?php echo date('d/m/Y H:i:s', $item->CREATED_TIME); ?></small></td>
                            <td><small><?php echo $item->USERNAME; ?></small></td>
                            <td><small><?php echo $item->ACTIVITY; ?></small></td>
<!--                             <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/admin/activity/detailactivity/<?php echo $item->ID_ACTIVITY; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/admin/activity/editactivity/<?php echo $item->ID_ACTIVITY;?>" title="Edit"><i
                                class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/admin/activity/deleteactivity/<?php echo $item->ID_ACTIVITY;?>" title="Delete"><i
                                class="fa fa-trash" style="color:red;"></i></button>
                            </td> -->
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
	jQuery(document).ready(function() {
		/*       
        $.post("index.php/admin/activity/daftar_role", function(html) {
            $.each(html, function(index, value) {
                select = '<?=@$DATA_PRIBADI->PROVINSI?>';
                if (index == select) {
                    $("#id_role").append("<option value='" + index + "' selected>" + value + "</option>");
                    kabkotedit();
                } else {
                    $("#id_role").append("<option value='" + index + "'>" + value + "</option>");
                };
            });
            //$("#id_role").select2();
        }, 'json');
		*/
    });

    function kabkot() {
        $("#cari_username").empty();
        $.post("index.php/admin/activity/daftar_user/" + $("#id_role").val(), function(html) {
            $.each(html, function(index, value) {
                $("#cari_username").append("<option value='" + index + "'>" + value + "</option>");
            });
            $("#cari_username").show();
            //$("#cari_username").select2();
        }, 'json');
    }

    function kabkotedit() {
        $("#cari_username").empty();
        $.post("index.php/admin/activity/daftar_user/" + $("#id_role").val(), function(html) {
            $.each(html, function(index, value) {
                select = '<?=@$DATA_PRIBADI->KABKOT?>';
                if (index == select) {
                    $("#cari_username").append("<option value='" + index + "' selected>" + value + "</option>");
                } else {
                    $("#cari_username").append("<option value='" + index + "'>" + value + "</option>");
                };
            });
            $("#cari_username").show();
            $("#cari_username").select2();
        }, 'json');
    }
	
    $(document).ready(function () {
        $('.datepicker').datepicker({
        format: 'dd/mm/yyyy'
        });

        $('.daterange').daterangepicker({
                format: 'YYYY/MM/DD',
                opens: 'left',
            },
            function (start, end) {
                $('#daterange').val(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
            }
        );

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
                OpenModalBox('Detail Activity', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Activity', html, '');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/admin/activity');
            });            
            return false;              
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Activity', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/admin/activity');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Activity', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/admin/activity');
            });            
            return false;
        });

        $('#id_role').ready(function () {
            kabkot();
        })

    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>


