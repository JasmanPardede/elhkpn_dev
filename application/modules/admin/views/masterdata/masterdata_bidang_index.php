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
 * @package Views/masterdata/agama
*/
?>
<div class="box-header with-border">
    <button type="button" id="btnAdd" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Tambah Data</button>
<!--    <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
    <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
    <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>-->
  <div class="box-tools">
    <form method="post" id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
    <div class="input-group">
        <div class="col-sm-8">
            <select name="CARI[BDG_STATUS]" id="BDG_STATUS" class="select2" style="width: 80px;">
                <option>Set</option>
                <option <?= (@$this->CARI['BDG_STATUS'] == '1' ? 'selected' : ''); ?> value="1">Active</option>
                <option <?= (@$this->CARI['BDG_STATUS'] == '0' ? 'selected' : ''); ?> value="0">Non Active</option>
            </select>
            <input type="text" class="form-control input-sm pull-right" style="width: 100px;" placeholder="Search Bidang" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
        </div>
        <div class="input-group-btn col-sm-3">
          <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
          <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val(''); $('#BDG_STATUS').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
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
            <th>Kode Bidang</th>
            <th>Nama Bidang</th>
            <th>Status</th>
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
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->BDG_KODE);?></td>
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->BDG_NAMA);?></td>
            <td><?= ($item->BDG_STATUS == '1' ? 'Active' : 'Nonactive'); ?></td>
            <td width="120" nowrap="">
                <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                <button type="button" class="btn btn-sm btn-default btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button>
                <button type="button" class="btn btn-sm btn-default btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
                <!-- <button type="button" class="btn btn-sm btn-default btnDelete" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button> -->
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