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
 * @package Views/pn
*/

$INSTANSI = array();
foreach ($instansis as $instansi) {
    $INSTANSI[$instansi->INST_SATKERKD]['NAMA'] = $instansi->INST_NAMA;
}

?>

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Non Aktif
            <small>daftar Non Aktif</small>
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
                    <form id="ajaxFormCari" method="post" action="index.php/ereg/pn/nonaktif">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search PN" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
                            <div class="input-group-btn">
                              <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                              <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                            </div>
                        </div>
                    </form>
                  </div>
                </div><!-- /.box-header -->
                  <p>&nbsp;</p>
                <div class="box-body">
                <!-- <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                <table id="dt_completeNEW" class="table table-striped">
                    <thead>
                        <tr>
                            <th align="center" width="30">No.</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <!-- <th>Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = $i + 1;
                            $end = 0;
                            foreach ($items as $item) {
                        ?>
                        <tr>
                            <td><small><?php echo ++$i; ?>.</small></td>
                            <td><small><?php echo $item->NIK; ?></small></td>
                            <td><small><?php echo $item->NAMA; ?></small></td>
                            <td><small>
                                <?php
                                if($item->NAMA_JABATAN){
                                    $j = explode(',',$item->NAMA_JABATAN);
                                    echo '<ul>';
                                    foreach ($j as $ja) {
                                        $jb = explode(':58:', $ja);
                                        $idjb = $jb[0];
                                        $statakhirjb = $jb[1];
                                        $statakhirjbtext = $jb[2];
                                        $statmutasijb = $jb[3];
                                        // $linkMutasi = $jb[4] == $this->session->userdata('INST_SATKERKD') && $statakhirjb == ''?' <a href="index.php/ereg/pn/mts/'.$idjb.'" class="btn-mutasi">[mutasi]</a> ':' - '.$statakhirjbtext;
                                        
                                        $linkMutasi = $statakhirjb == '0' || $statakhirjb == '' || $statakhirjb == null?'  ':' - <span class="badge">'.$statakhirjbtext.'</span>';
                                        if($statmutasijb != null && $statmutasijb != ''){
                                            $linkMutasi = ' - <span class="badge">sedang proses mutasi</span>';
                                        }
                                        echo '<li>'.' '.$jb[5].$linkMutasi.'</li>';
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </small></td>
<!--                             <td width="120" nowrap="">
                                <button type="button" class="btn btn-sm btn-default btn-detail"
                                href="index.php/ereg/pn/detailpn/<?php echo $item->ID_PN; ?>" title="Preview"><i
                                class="fa fa-search-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/ereg/pn/editpn/<?php echo $item->ID_PN;?>" title="Edit"><i
                                class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/pn/deletepn/<?php echo $item->ID_PN;?>" title="Delete"><i
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
                OpenModalBox('Detail Penyelenggara Negara', html, '');
            });            
            return false;
        })

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Tambah Penyelenggara Negara', html, '');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/ereg/pn');
            });            
            return false;              
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Edit Penyelenggara Negara', html, '');
                ng.formProcess($("#ajaxFormEdit"), 'edit', 'index.php/ereg/pn');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Delete Penyelenggara Negara', html, '');
                ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/ereg/pn');
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


