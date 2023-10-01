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
 * @author Rizky Awlia Fajrin (Evan Sumangkut) - PT.Waditra Reka Cipta
 * @package Views/user
 * Hak Cipta
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
         <h1>
            <i class="fa fa-bullhorn"></i>
            Data Analisa KAS
          </h1>
    <?php echo $breadcrumb; ?>
</section>



<!-- Main content -->
<section class="content">
    <div class="row">
		<div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-body" >
            <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                <div class="box-body">
                    <a class="btn-cetak btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                        <span class="logo-mini">
                            <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                        </span> Print to Excel 
                    </a>
                </div>
            </form>

        <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
  
              </div>
				<div class="box-body">
                    <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th align="center" width="2%">No</th>
                                <th>Jenis LHKPN</th>
                                <th>Nama Jenis</th>
                                <th>Asal Usul</th>
                                <th>Atas Nama Rekening</th>
                                <th>Nama Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Keterangan</th>
                                <th>Tahun Buka Rekening</th>
                                <th>Status Harta</th>
                                <th>Mata Uang</th>
                                <th>Nilai Saldo</th>
                                <th>Nilai Kurs</th>
                                <th>Nilai Ekuivalen</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.col -->
</div>
</div><!-- /.row -->
</section><!-- /.content -->

<script language="javascript">

    var gtblDaftarKas;

    var tblDaftarKas = {
        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarKas'},
        conf: {
			"cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('psm/telaah/load_data_kas'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true},
                {"mDataProp": "Jenis_LHKPN", bSearchable: true},
                {"mDataProp": "Nama_Jenis", bSearchable: true},
                {"mDataProp": "ASAL_USUL", bSearchable: true},
                {"mDataProp": "ATAS_NAMA_REKENING", bSearchable: true},
                {"mDataProp": "nama_bank_dekrip", bSearchable: true},
                {"mDataProp": "no_rekening_dekrip", bSearchable: true},
                {"mDataProp": "KETERANGAN", bSearchable: true},
                {"mDataProp": "TAHUN_BUKA_REKENING", bSearchable: true, bSortable: true},
                {"mDataProp": "Status_Harta", bSearchable: true},
                {"mDataProp": "NAMA_MATA_UANG", bSearchable: true},
                {"mDataProp": "NILAI_SALDO", bSearchable: true,  bSortable: true},
                {"mDataProp": "NILAI_KURS", bSearchable: true},
                {"mDataProp": "NILAI_EQUIVALEN", bSearchable: true},
            ],
            "fnRowCallback": function (nRow, aData) {
                var stl = false;
                if (aData.STS_J == 10 || aData.STS_J == 11 || aData.STS_J == 15) {
                    stl = true;
                }
                return nRow;
            }
        }
    };

    $(document).ready(function () {

        gtblDaftarKas = initDtTbl(tblDaftarKas);

        $('.btn-cetak').click(function(e) {
            e.preventDefault();

            var url = '<?php echo site_url("/index.php/ereg/Cetak/export_analisa_kas"); ?>/';
            window.location.href = url;
            return;
        });

    });

</script>
