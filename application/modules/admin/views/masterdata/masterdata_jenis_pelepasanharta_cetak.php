<h3><?php echo $titleCetak;?></h3>
<?php echo $filterCetak;?>
<?php
	if($total_rows){
?>
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>Pelepasan Harta</th>
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
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->JENIS_PELEPASAN_HARTA);?></td>
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