<?php
    if($total_rows){
?>
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>Nama Sub Unit Kerja</th>
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
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->SUK_NAMA);?></td>
            <td width="120" nowrap="">
                <button type="button" class="btn btn-sm btn-default btn-detail"
                href="<?php echo $urlEdit.'/'.$item->SUK_ID.'/detail'; ?>" title="Preview"><i
                class="fa fa-search-plus"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-edit" href="<?php echo $urlEdit.'/'.$item->SUK_ID; ?>" title="Edit"><i
                class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-delete" href="<?php echo $urlEdit.'/'.$item->SUK_ID.'/delete'; ?>" title="Delete"><i
                class="fa fa-trash" style="color:red;"></i></button>
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
?><?php
    if($total_rows){
?>
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>Nama Sub Unit Kerja</th>
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
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->SUK_NAMA);?></td>
            <td width="120" nowrap="">
                <button type="button" class="btn btn-sm btn-default btn-detail"
                href="<?php echo $urlEdit.'/'.$item->SUK_ID.'/detail'; ?>" title="Preview"><i
                class="fa fa-search-plus"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-edit" href="<?php echo $urlEdit.'/'.$item->SUK_ID; ?>" title="Edit"><i
                class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-delete" href="<?php echo $urlEdit.'/'.$item->SUK_ID.'/delete'; ?>" title="Delete"><i
                class="fa fa-trash" style="color:red;"></i></button>
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