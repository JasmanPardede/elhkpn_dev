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
<!--    <button type="button" id="btnPrintPDF" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i></button>
<button type="button" id="btnPrintEXCEL" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i></button>-->
        <!--  <div class="box-tools">
            <form method="post" id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
            <div class="input-group pull-right">
                <div class="col-sm-8">
                     <select name="CARI[UK_STATUS]" id="UK_STATUS" class="select2" style="width: 80px;">
                        <option value="">Set</option>
                        <option <?= (@$this->CARI['UK_STATUS'] == '1' ? 'selected' : ''); ?> value="1">Active</option>
                        <option <?= (@$this->CARI['UK_STATUS'] == '0' ? 'selected' : ''); ?> value="0">Non Active</option>
                    </select> 
                    <input type="text" class="form-control input-sm pull-right" style="width: 300px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT"/>
                </div>
                <div class="input-group-btn col-sm-3">
                  <button type="submit" id="btnCari" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  <button type="button" id="btnClear" class="btn btn-sm btn-default" onclick="$('#CARI_TEXT').val(''); $('#UK_STATUS').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                </div>
            </div>
            </form>
          </div>-->
    </div><!-- /.box-header -->
    <div class="box-body">

        <table id="dt_masterunitkerja" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>Nama Unit Kerja</th>
                    <th>Lembaga</th>
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
                  if($item->UK_STATUS==1){
                  ?>
                  <tr>
                  <td><?php echo ++$i; ?>.</td>
                  <!--  <td><?php echo $item->BDG_NAMA; ?></td> -->
                  <!-- <td><?php //echo $item->UK_KODE; ?></td> -->
                  <td><?php echo $item->UK_NAMA; ?></td>
                  <td><?php echo $item->INST_NAMA; ?></td>
                  <!-- <td><?= ($item->UK_STATUS == '1' ? 'Active' : 'Nonactive'); ?></td> -->
                  <td width="120" nowrap="">
                  <input type="hidden" class="key" value="<?php echo $item->$pk;?>">
                  <button type="button" class="btn btn-sm btn-info btnDetail" title="Preview"><i class="fa fa-search-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-success btnEdit" title="Edit"><i class="fa fa-pencil"></i></button>
                  <button type="button" class="btn btn-sm btn-danger btnDelete" title="Delete"><i class="fa fa-trash" ></i></button>
                  <!-- <button type="button" class="btn btn-sm btn-default btnDelete" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button> -->
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
    <script language="javascript">
        var stat = 'none';
        function myFunction() {
            var x = document.getElementById("CARI_STATUS").value;

            if (x = 1) {
                stat = ''
            } else if (x = 0) {
                stat = ''
            }
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

        var tblMasterUnitKerja = {
            tableId: 'dt_masterunitkerja',
            reloadFn: {tableReload: true, tableCollectionName: 'tblMasterUnitKerja'},
            conf: {
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "bServerSide": true,
                "sAjaxSource": "<?php echo base_url('admin/masterdata/load_data_daftar_master_unitkerja/'); ?>",
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
                    {"mDataProp": "UK_NAMA", bSearchable: true},
                    {"mDataProp": "INST_NAMA", bSearchable: true},
                    {
                        "mDataProp": function (source, type, val) {

                            var BtnHapus = '<button type="button" class="btn btn-sm btn-danger btnDelete" onclick="fnDel(this, ' + source.UK_ID + ');" title="Delete"><i class="fa fa-trash" ></i></button>';
                            var BtnEdit = '<button type="button" class="btn btn-sm btn-success btnEdit" onclick="fnEdit(this, ' + source.UK_ID + ');" title="Edit"><i class="fa fa-pencil"></i></button>';
                            var BtnKembalikan = '<button style="display:' + stat + '" type="button" class="btn btn-sm btn-success btnEdit" onclick="fnKembalikan(this, ' + source.UK_ID + ');" title="Kembalikan"><i class="fa fa-repeat"></i></button>';

                            return (BtnEdit + " " + BtnHapus + " " + BtnKembalikan).toString();

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
        var gtblMasterUnitkerja;
        var clearPencarian = function () {
            $('#CARI_TEXT').val('');
            reloadTableDoubleTime(gtblMasterUnitkerja);
        };

        var submitCari = function () {
            reloadTableDoubleTime(gtblMasterUnitkerja);
        };

        $(document).ready(function () {
            gtblMasterUnitkerja = initDtTbl(tblMasterUnitKerja);
			
        });

        var ExecDatasss = function () {reloadTableDoubleTime(gtblMasterUnitkerja);}
        var LoadDtTablePenambahan = function () {};
        var LoadDtTablePerubahan = function () {};
        var LoadDtTableNonAktive = function () {};
    </script>
