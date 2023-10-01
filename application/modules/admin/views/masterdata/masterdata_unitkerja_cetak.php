<h3><?php echo $titleCetak;?></h3>
<?php echo $filterCetak;?>
<?php
	if($total_rows){
?>
<table border="1" class="table table-striped table-bordered table-hover table-heading no-border-bottom" width="100%">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>Bidang</th>
            <th>Instansi</th>
            <th>Unit Kerja</th>
            <th>Staus</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 0 + $offset;
            $start = $i + 1;
            foreach ($items as $item) {
        ?>
        <tr style="background-color: <?php echo $item->IS_ACTIVE==-1?'#FE91A8':'';?>">
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $item->BDG_NAMA; ?></td>
            <td><?php echo $item->INST_NAMA; ?></td>
            <td><?php echo $item->UK_NAMA; ?></td>
            <td><?= ($item->UK_STATUS == 1 ? 'Active' : 'Nonactive'); ?></td>
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