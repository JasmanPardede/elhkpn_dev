<?php

/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */
/**
 * Controller Penyelenggara Negara
 *
 * @author Rizki Nanda Mustaqim - PT.Akhdani Reka Solusi
 * @package Views/admin
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
         <h1>
            <i class="fa fa-code"></i>
            Monitoring API AI
          </h1>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
		<div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group" style="margin-bottom:10px">
                                        <label class="col-sm-4 control-label">Status Response :</label>
                                        <div id="inpCariStatusPlaceHolder" class="col-sm-6">
                                            <input type='text' class="input-sm form-control" name='CARI[STATUS]' style="border:none;padding:1px 0px;" id='CARI_STATUS' value='' placeholder="-- Pilih Status --">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group" style="margin-bottom:10px">
                                        <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>             
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div  class="box-body table-responsive">
                                <table id="dt_table_monitoring_ai" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <thead>
                                        <tr>
                                            <th align="center" width="2%">No</th>
                                            <th>ID LHKPN</th>
                                            <th>Kode response</th>
                                            <th>Diakses</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script language="javascript">

var gtblMonotoringAI;

var tblDaftarAI = {
        tableId: 'dt_table_monitoring_ai',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarAI'},
        conf: {
			"cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "fnRowCallback":function(row, data, dataIndex, dataIndexFull) {
                if(data.HTTP_CODE != 200) {
                    $(row).css('background-color', '#F0DDDD');
                } 
            },
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('admin/monitoring_ai/load_ajax_monitoring_ai/'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT"},
                {"mDataProp": "ID_LHKPN"},
                {"mDataProp": "HTTP_CODE"},
                {"mDataProp": "CREATED_TIME_INDONESIA"},
            ],
        }
    };  

var submitCari = function() {
    reloadTableDoubleTime(gtblMonotoringAI);
};

var clearPencarian = function() {
        $('#TEXT').val('');
        $('#CARI_STATUS').val(9).trigger('change');  
        reloadTableDoubleTime(gtblMonotoringAI);
    };

$(document).ready(function() {

    data_is_active = [{id: 9, text: '--Semua Data--'},{id: 1, text: 'Sukses'},{id: 0, text: 'Gagal'}];
        $('#CARI_STATUS').select2({data: data_is_active});
        $('#CARI_STATUS').val(9).trigger('change');  

    gtblMonotoringAI = initDtTbl(tblDaftarAI);
      
});

</script>