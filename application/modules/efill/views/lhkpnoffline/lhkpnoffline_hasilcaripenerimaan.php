<?php
	if($total_rows){
?>
<div class="box-body">
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
        <thead>
            <tr>
                <th width="30">No.</th>
                <th>PN</th>
                <th>DOKUMEN</th>
				<th>Status</th>
                <th>Aksi</th>
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
                <td><?php echo $item->JENIS_DOKUMEN; ?><br><?php echo $item->MELALUI; ?></td>
                <td><?php echo $item->STAT_PENUGASAN; ?></td>
                <td width="120" nowrap="">
                    <button type="button" class="btn btn-sm btn-default btnSelectPenerimaan" data-penerimaan="<?php echo $item->ID_PENERIMAAN; ?>">pilih</button>
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
            <div class="dataTables_info" id="datatable-1_info">Showing <?php echo $start; ?> to <?php echo $end; ?>
                of <?php echo $total_rows; ?> entries
            </div>
        </div>
        <?php
        }
        ?>
        <div class="col-sm-6 text-right">
            <div class="dataTables_paginate paging_bootstrap paginationPN">
                <?php echo $pagination; ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
<?php
	}else{
		echo 'Data Not Found...';
	}
?>