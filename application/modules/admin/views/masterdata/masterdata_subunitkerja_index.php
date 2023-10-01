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
 * @package Views/masterdata/jabatan
*/
?>
<?php
    $js_page = isset($js_page) ? $js_page : '';
    if (is_array($js_page)) {
        foreach ($js_page as $page_js) {
            echo $page_js;
        }
    } else {
        echo $js_page;
    }
    ?>
<div class="box box-primary">
<div class="box-header with-border">
    
        <!--<button type="button" id="btnAdd" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</button>-->
	<form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
		<div class="col-md-12">        
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
											<option value='0'>Tidak Aktif</option>
										</select>
									</div>
                                </div>
                            </div>            
                        </div>
                    </div>
                </div>
        </div>
		<div class="col-md-12">   
			<?php echo $v_cb_instansi; ?>
		</div>
	</form>
<!--        <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
        <button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>
        <button type="button" id="btnPrintWORD" class="btn btn-sm btn-default"><i class="fa fa-file-word-o"></i></button>-->
  

    <!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
<!--   <div class="col-md-12">    
        <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
            <div class="box-body">

                <div class="col-md-6">
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Instansi :</label>
                         <div class="col-sm-6">
                              <input type='text' class="input-sm select2 form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='<?php echo @$CARI['INSTANSI'];?>' placeholder="-- Pilih Instansi --">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Unit Kerja :</label>
                         <div class="col-sm-6">
                                <input type='text' class="input-sm select2 form-control" name='CARI[UNITKERJA]' style="border:none;padding:6px 0px;" id='CARI_UNITKERJA' value='<?php echo @$CARI['UNITKERJA'];?>' placeholder="-- Pilih Unit Kerja --">
                        </div>
                    </div>
                    </div>
                    </div>

                <div class="col-md-6">
                    <div class="row">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Cari :</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-sm-offset-4">
                            <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_INSTANSI').val(''); $('#CARI_UNITKERJA').val(''); $('#CARI_TEXT').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </form>
    </div>-->
</div><!-- /.box-header -->
<div class="box-body">

<!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
<table id="dt_mastersubunitkerja"  class="table table-striped">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>Instansi</th>
            <th>Unit Kerja</th>
            <th>Sub Unit Kerja</th>
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
        <tr>
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $item->INST_NAMA; ?></td>
            <td><?php echo $item->UK_NAMA; ?></td>
            <td><?php echo str_ireplace(@$CARI['TEXT'], '<span style="border-bottom: 1px dashed #999;text-decoration: none;">'.@$CARI['TEXT'].'</span>', $item->SUK_NAMA);?></td>
            <td align ="center"width="120" nowrap="">
                <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                <!-- <button type="button" class="btn btn-sm btn-default btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button> -->
                <button type="button" class="btn btn-sm btn-success btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-danger btnDelete" title="Delete"><i class="fa fa-trash" ></i></button>
            </td>
        </tr>
        <?php
                $end = $i;
            }
         * 
         */
        ?>
    </tbody>
</table>

<!-- </div>/.box-body
 -->
<script language="javascript">
    var stat='none';
    function myFunction() {            
            var x = document.getElementById("CARI_STATUS").value;
            
            if (x = 1) { stat =''}
            else if (x = 0) { stat = '' }
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
        var tblMasterSubUnitKerja = {
            tableId: 'dt_mastersubunitkerja',
            reloadFn: {tableReload: true, tableCollectionName: 'tblMasterSubUnitKerja'},
            conf: {
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "bServerSide": true,
                "sAjaxSource": "<?php echo base_url('admin/masterdata/load_data_daftar_master_subunitkerja/'); ?>",
                "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                    var passData = getRecordDtTbl(sSource, aoData, oSettings);

                    passData.push({"name": "CARI[TEXT]", "value": $("#CARI_TEXT").val()});
                    passData.push({"name": "CARI[STATUS]", "value": $("#CARI_STATUS").val()});
                    passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()});
                    passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                    $.getJSON(sSource, passData, function (json) {
                        fnCallback(json);
                    });
                },
                "aoColumns": [
                    {"mDataProp": "NO_URUT", bSearchable: false},
                    {"mDataProp": "INST_NAMA", bSearchable: true},
                    {"mDataProp": "UK_NAMA", bSearchable: true},
                    {"mDataProp": "SUK_NAMA", bSearchable: true},
                    {
                        "mDataProp": function (source, type, val) {

                            var BtnHapus = '<button type="button" class="btn btn-sm btn-danger btnDelete" onclick="fnDel(this, ' + source.SUK_ID + ');" title="Delete"><i class="fa fa-trash" ></i></button>';
                            var BtnEdit = '<button type="button" class="btn btn-sm btn-success btnEdit" onclick="fnEdit(this, ' + source.SUK_ID + ');" title="Edit"><i class="fa fa-pencil"></i></button>';
                            var BtnKembalikan = '<button style="display:'+stat+'" type="button" class="btn btn-sm btn-success btnEdit" onclick="fnKembalikan(this, ' + source.SUK_ID + ');" title="Kembalikan"><i class="fa fa-repeat"></i></button>';
                            
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
        var gtblMasterSubUnitKerja;
        var clearPencarian = function () {
            $('#CARI_TEXT').val('');
            reloadTableDoubleTime(gtblMasterSubUnitKerja);
        };
        var submitCari = function () {
            reloadTableDoubleTime(gtblMasterSubUnitKerja);
        };
        $(document).ready(function () {
            gtblMasterSubUnitKerja = initDtTbl(tblMasterSubUnitKerja);
        });
		var ExecDatasss = function () {}
        var LoadDtTablePenambahan = function () {};
        var LoadDtTablePerubahan = function () {};
        var LoadDtTableNonAktive = function () {};
</script>
