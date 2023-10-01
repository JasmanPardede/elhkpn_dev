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
 * @package Views/masterdata/pendidikan
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
                    <select name='CARI[STATUS]' id='CARI_STATUS' class='form-control' onchange="myFunction()">
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
    <div class="input-group">
                      <input type="text" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
                      <div class="input-group-btn">
                        <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                        <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val(''); $('#CARI_GOLONGAN').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                      </div>
                    </div>
    </form>
  </div>-->
</div><!-- /.box-header -->
<div class="box-body">

<!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
<table id="dt_masterpendidikan" class="table table-striped">
    <thead>
        <tr>
            <th align="center" width="30">No.</th>
            <th>PENDIDIKAN</th>
            <th style="width: 15%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /**    $i = 0 + $offset;
            $start = $i + 1;
            foreach ($items as $item) {
        ?>
        <tr>
            <td align="center"><small><?php echo ++$i; ?>.</small></td>
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->PENDIDIKAN);?></td>
            <td width="120" nowrap="">
                <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                <!-- <button type="button" class="btn btn-sm btn-default btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button> -->
                <button type="button" class="btn btn-sm btn-success btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-danger btnDelete" title="Delete"><i class="fa fa-trash" ></i></button>
            </td>
        </tr>
        <?php
                $end = $i;
            }
         * **
         */
        ?>
    </tbody>
</table>

</div><!-- /.box-body -->

<script type="text/javascript">
        var stat='none';
        function myFunction() {            
            var x = document.getElementById("CARI_STATUS").value;
            
            if (x = 1) { stat =''}
            else if (x = -1) { stat = '' }
        }

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

        var tblMasterPendidikan = {
            tableId: 'dt_masterpendidikan',
            reloadFn: {tableReload: true, tableCollectionName: 'tblMasterPendidikan'},
            conf: {
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "bServerSide": true,
                "sAjaxSource": "<?php echo base_url('admin/masterdata/load_data_daftar_master_pendidikan/'); ?>",
                "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                    var passData = getRecordDtTbl(sSource, aoData, oSettings);

                    passData.push({"name": "CARI[TEXT]", "value": $("#CARI_TEXT").val()});
                    passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                    $.getJSON(sSource, passData, function (json) {
                        fnCallback(json);
                    });
                },
                "aoColumns": [
                    {"mDataProp": "NO_URUT", bSearchable: false},
                    {"mDataProp": "PENDIDIKAN", bSearchable: true},
                    {
                        "mDataProp": function (source, type, val) {

                            var BtnHapus = '<button type="button" class="btn btn-sm btn-danger btnDelete" onclick="fnDel(this, ' + source.ID_PENDIDIKAN + ');" title="Delete"><i class="fa fa-trash" ></i></button>';
                            var BtnEdit = '<button type="button" class="btn btn-sm btn-success btnEdit" onclick="fnEdit(this, ' + source.ID_PENDIDIKAN + ');" title="Edit"><i class="fa fa-pencil"></i></button>';
                            var BtnKembalikan = '<button style="display:'+stat+'" type="button" class="btn btn-sm btn-success btnEdit" onclick="fnKembalikan(this, ' + source.ID_PENDIDIKAN + ');" title="Kembalikan"><i class="fa fa-repeat"></i></button>';
                            
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
        var gtblMasterPendidikan;
        var clearPencarian = function () {
            $('#CARI_TEXT').val('');
            reloadTableDoubleTime(gtblMasterPendidikan);
        };

        var submitCari = function () {
            reloadTableDoubleTime(gtblMasterPendidikan);
        };

        $(document).ready(function () {
            gtblMasterPendidikan = initDtTbl(tblMasterPendidikan);
        });

    </script>