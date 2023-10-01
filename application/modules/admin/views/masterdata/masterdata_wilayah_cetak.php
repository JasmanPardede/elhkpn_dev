<?php
    if($total_rows){
?>
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>IDPROV</th>
            <th>IDKOT</th>
            <th>IDKEC</th>
            <th>IDKEL</th>
            <th>Nama Wilayah</th>
            <th>LEVEL</th>

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
            <td><?= @$item->IDPROV; ?></td>
            <td><?= @$item->IDKOT; ?></td>
            <td><?= @$item->IDKEC; ?></td>
            <td><?= @$item->IDKEL; ?></td>
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->NAME);?></td>
            <td><?= @$item->LEVEL; ?></td>

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