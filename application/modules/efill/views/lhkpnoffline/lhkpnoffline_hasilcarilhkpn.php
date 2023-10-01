<?php
	if($total_rows){
?>
<div class="box-body">
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>PN</th>
            <th>Tgl Lapor</th>
            <th>Jabatan</th>
            <th class="hidden-xs hidden-sm">Jenis</th>
            <!-- <th class="hidden-xs hidden-sm">Status</th> -->
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0 + $offset;
        $start = $i + 1;
        $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
        foreach ($items as $item) {
        ?>
        <tr>
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $item->NIK; ?><br><?php echo $item->NAMA; ?></td>
            <td><?php echo date('d-m-Y',strtotime($item->TGL_LAPOR)); ?></td>
            <td>                                
            <?php
                if($item->NAMA_JABATAN){
                    $j = explode(',',$item->NAMA_JABATAN);
                    echo '<ul>';
                    foreach ($j as $ja) {
                        $jb = explode(':||:', $ja);
                        $idjb = $jb[0];
                        $statakhirjb = $jb[1];
                        $statakhirjbtext = $jb[2];
                        $statmutasijb = $jb[3];
                        echo '<li>'.$jb[4].'</li>';
                    }
                    echo '</ul>';
                }
            ?>
            </td>
            <td class="hidden-xs hidden-sm"><?php echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus'; ?></td>
            <!--                             <td class="hidden-xs hidden-sm"><?php
                echo ($item->IS_SUBMITED == '1' ? 'Diterima <small>'.date('d-m-Y', strtotime($item->SUBMITED_DATE)).'</small></br>' : '');
                echo ($item->IS_VERIFIED == '1' ? 'Verifikasi <small>'.date('d-m-Y', strtotime($item->VERIFIED_DATE)).'</small></br>' : '');
                echo ($item->IS_PUBLISHED == '1' ? 'Announcement <small>'.date('d-m-Y', strtotime($item->PUBLISHED_DATE)).'</small></br>' : '');
                ?>
            </td> -->
            <td width="120" nowrap="">
                <input type="checkbox" name="lhkpn">
                <button type="button" class="btn btn-sm btn-default btnSelectLHKPN" data-lhkpn="<?php echo $item->ID_LHKPN; ?>">pilih</button>
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
            <div class="dataTables_paginate paging_bootstrap paginationLHKPN">
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