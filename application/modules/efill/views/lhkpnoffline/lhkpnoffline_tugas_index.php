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
 * @package Views/ever/penugasan
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <i class="fa <?php echo $icon;?>"></i> <?php echo $title;?>
            <small><?php echo $title;?></small>
          </h1>
         <?php echo $breadcrumb;?>
        </section>

		
        <!-- Main content -->
        <section class="content">
            <?php
/*            if($total_rows){
            ?>
			<div class="callout callout-danger">
			<h4>Anda Mendapat Penugasan Entry sebanyak <?php echo $total_rows;?> yang belum diselesaikan!</h4>
            <p><a href="index.php/ever/verification/penugasan/">selengkapnya</a></p>
			</div>
            <?php
            }*/
            ?>

          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                  <!-- <button class="btn btn-sm btn-default" id="btn-add" href="<?php echo $urlEdit;?>"><i class="fa fa-plus"></i> Tambah Data</button> -->
                  &nbsp;
                  <div class="box-tools">
                    <form method="post" id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
                    <div class="input-group col-sm-push-6">
                        <div class="col-sm-3">
                        </div>
                        
                        <div class="input-group-btn col-sm-2">
                            <input type="text" class="form-control input-sm pull-right" style="width: 300px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
                            <button type="submit" class="btn btn-sm btn-default" id="btn-cari"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                        </div>
                        
                    </div>
                    </form>
                    
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php
                    if($total_rows){
                ?>
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th>PN</th>
                            <th>USERNAME</th>
                            <th>TANGGAL_PENUGASAN</th>
                            <th>DUE_DATE</th>
                            <th>STATUS</th>
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
                           <td><?php echo $item->NAMA; ?> (<?php echo $item->NIK; ?>)<br>
                                <?php 
                                switch ($item->JENIS_LAPORAN) {
                                    case '1':
                                        echo 'Calon Penyelenggara Negara ('.date('d-m-Y',strtotime($item->TANGGAL_PELAPORAN)).')';
                                        break;
                                    case '2':
                                        echo 'Awal Menjabat ('.date('d-m-Y',strtotime($item->TANGGAL_PELAPORAN)).')';
                                        break;
                                    case '3':
                                        echo 'Akhir Menjabat ('.date('d-m-Y',strtotime($item->TANGGAL_PELAPORAN)).')';
                                        break;
                                    case '4':
                                        echo 'Sedang Menjabat ('.$item->TAHUN_PELAPORAN.')';
                                        break;
                                    
                                    default:
                                        break;
                                }
                                ?>
                            </td>
                            <td><?php echo $item->USERNAME; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($item->TANGGAL_PENUGASAN)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($item->DUE_DATE)); ?></td>
                            <td>Ditugaskan 2015-08-07/<br>Proses/<br>Selesai 2015-08-10<br></td>
                            <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="<?php echo $urlEdit.'/'.$item->ID_TUGAS.'/proses'; ?>" title="Proses"><i
                                class="fa fa-share"></i></button>
                            </td>
                        </tr>
                        <?php
                                $end = $i;
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                    }else{
                        echo 'Data Not Found...';
                    }
                ?>  
				
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

		$(".breadcrumb").find("a").click(function () {
			var url = $(this).attr('href');
			window.location.hash = url;
			ng.LoadAjaxContent(url);
			return false;
		});

		$(".callout").find("a").click(function () {
			var url = $(this).attr('href');
			window.location.hash = url;
			ng.LoadAjaxContent(url);
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
                OpenModalBox('Detail Data Entry', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Data Entry', html, '', 'standart');
                ng.formProcess($("#ajaxFormAdd"), 'add', '<?php echo $thisPageUrl;?>');
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
                OpenModalBox('Edit Data Entry', html, '', 'standart');
                ng.formProcess($("#ajaxFormEdit"), 'edit', '<?php echo $thisPageUrl;?>');
            });            
            return false;
        });

        $('.btn-proses').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Proses Data Entry', html, '', 'standart');
                ng.formProcess($("#ajaxFormEdit"), 'edit', '<?php echo $thisPageUrl;?>');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Penugasan', html, '', 'standart');
                ng.formProcess($("#ajaxFormDelete"), 'delete', '<?php echo $thisPageUrl;?>');
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
