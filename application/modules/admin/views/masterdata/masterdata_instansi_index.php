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
 * @package Views/masterdata/instansi
*/
?>
<div class="box box-primary">
<div class="box-header with-border">
    <!--<button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>-->
    <div class="col-md-12">    
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group">
                                 <button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div></div>
                            <div class="form-group">
                                <label class="col-sm-7 control-label">Status :</label>
                                <div class="col-sm5">
                                    <div class="col-sm-5">
                    <select name='CARI[STATUS]' id='CARI_STATUS' class='form-control'>
                        <!--<option value='0'>All</option>-->
                        <option value='1' selected="">Aktif</option>
                        <option value='-1'>Tidak Aktif</option>
                    </select>
                </div>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
<!--    <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
    <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
    <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>-->
<!--  <div class="box-tools">
    <form method="post" id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
    <div class="input-group pull-right">
        <div class="col-sm-8">
             <select name="CARI[IS_ACTIVE]" id="IS_ACTIVE" class="select2" style="width: 80px;">
                <option>Set</option>
                <option <?= (@$this->CARI['IS_ACTIVE'] == '1' ? 'selected' : ''); ?> value="1">Active</option>
                <option <?= (@$this->CARI['IS_ACTIVE'] == '0' ? 'selected' : ''); ?> value="0">Non Active</option>
            </select> 
            <input type="text" class="form-control input-sm pull-right" style="width:300px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
        </div>
        <div class="input-group-btn col-sm-3">
          <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
          <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val(''); $('#IS_ACTIVE').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
        </div>
    </div>
    </form>
  </div>-->
</div><!-- /.box-header -->
<div class="box-body">

<!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
<table id="dt_masterinstansi" class="table table-striped">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>Instansi</th>
            <th>Akronim</th>
            <th>Level</th>
            <!-- <th>Is Active</th> -->
            <th style="width: 15%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /**
            $i = 0 + $offset;
            $start = $i + 1;
            foreach ($items as $item) {
        ?>
        <?php
                            if($item->IS_ACTIVE==1){
                                ?>
            <tr>
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $item->INST_NAMA; ?></td>
            <td><?php echo $item->INST_AKRONIM; ?></td>
            <td><?php if($item->INST_LEVEL == 1){
                        echo "Pusat";
                    }else{
                        echo "Daerah";
                        }?> 
           <!--  <td><?= ($item->IS_ACTIVE == '1' ? 'Active' : 'Nonactive'); ?></td> -->
            <td align="center"width="120" nowrap="">
                <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                <button type="button" class="btn btn-sm btn-info btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button>
                <button type="button" class="btn btn-sm btn-success btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-danger btnDelete" title="Hapus"><i class="fa fa-trash"></i></button>
            </td>
        <?php
              }
         ?>            
        </tr>
        <?php
                $end = $i;
            }
         * 
         */
        ?>
    </tbody>
</table>

</div><!-- /.box-body -->

<script type="text/javascript">

        var btn_kembalikan='none';
        var btn_edit = 'inline-block';
        var btn_hapus = 'inline-block';

        $("#CARI_STATUS").change(function() {
            var x = this.value;
            
            if (x == 1) { btn_edit ='inline-block'; btn_hapus = 'inline-block'; btn_kembalikan='none'; } 
            else if (x == -1) { btn_edit ='inline-block'; btn_hapus = 'none'; btn_kembalikan='inline-block'; }

         });

        var fnEdit = function (self, k) {
//            var key = $(self).parents('td').children('.key').val();
            var url = '<?php echo $urlEdit; ?>' + k;
            ng.postOpenModalBox(url, 'Edit <?php echo $title; ?>', '', 'standart');
            return false;
        };

        var fnDel = function (self, k) {
//            var key = $(self).parents('td').children('.key').val();
            var url = '<?php echo $urlDelete; ?>' + k;
            ng.postOpenModalBox(url, 'Delete <?php echo $title; ?>', '', 'standart');
            return false;
        };
        
        var fnKembalikan = function (self, k) {
//            var key = $(self).parents('td').children('.key').val();
            var url = '<?php echo $urlKembalikan; ?>' + k;
            ng.postOpenModalBox(url, 'Kembalikan <?php echo $title; ?>', '', 'standart');
            return false;
        };

        var tblMasterInstansi = {
            tableId: 'dt_masterinstansi',
            reloadFn: {tableReload: true, tableCollectionName: 'tblMasterInstansi'},
            conf: {
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "bServerSide": true,
                "sAjaxSource": "<?php echo base_url('admin/masterdata/load_data_daftar_master_instansi/'); ?>",
                "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                    var passData = getRecordDtTbl(sSource, aoData, oSettings);

                    passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_masterinstansi_cari").val()});
                    passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});

                    $.getJSON(sSource, passData, function (json) {
                        fnCallback(json);
                    });
                },
                "aoColumns": [
                    {"mDataProp": "NO_URUT", bSearchable: false},
                    {"mDataProp": "INST_NAMA", bSearchable: true},
                    {"mDataProp": "INST_AKRONIM", bSearchable: true},
                    {"mDataProp": "INST_LEVEL", bSearchable: true},
                    {
                        "mDataProp": function (source, type, val) {

                            var BtnHapus = '<button style="display:'+btn_hapus+'" type="button" class="btn btn-sm btn-danger btnDelete" onclick="fnDel(this, ' + source.INST_SATKERKD + ');" title="Delete"><i class="fa fa-trash" ></i></button>';
                            var BtnEdit = '<button style="display:'+btn_edit+'" type="button" class="btn btn-sm btn-success btnEdit" onclick="fnEdit(this, ' + source.INST_SATKERKD + ');" title="Edit"><i class="fa fa-pencil"></i></button>';
                            var BtnKembalikan = '<button style="display:'+btn_kembalikan+'" type="button" class="btn btn-sm btn-success btnEdit" onclick="fnKembalikan(this, ' + source.INST_SATKERKD + ');" title="Kembalikan"><i class="fa fa-repeat"></i></button>';
                            
                            return (BtnEdit + " " + BtnHapus +" " + BtnKembalikan).toString();
                        },
                        bSortable: false,
                        bSearchable: false
                    }
                ],
                //                    "fnRowCallback": function(nRow, aData) {
                //
                //                        return nRow;
                //                    }
            }
        };
        var gtblMasterInstansi;
        var clearPencarian = function () {
            $('#CARI_TEXT').val('');
            reloadTableDoubleTime(gtblMasterInstansi);
        };

        var submitCari = function () {
            reloadTableDoubleTime(gtblMasterInstansi);
        };

        $(document).ready(function () {
            gtblMasterInstansi = initDtTbl(tblMasterInstansi);
        });

    </script>