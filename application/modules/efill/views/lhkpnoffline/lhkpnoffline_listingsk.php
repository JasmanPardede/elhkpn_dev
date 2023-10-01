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
 * @package Views/efill/penerimaan
 */

function nominal_in_milyar($nominal){
    $nominal = abs($nominal);
    if($nominal >= 1000000000 && $nominal <= 100000000000){        // 1M-100M highlight warna hijau
        return 1;                 
    }else if($nominal > 100000000000 && $nominal <= 500000000000){ // 100M-500M highlight warna kuning
        return 2;                
    }else if($nominal > 500000000000){                             // > 500M highlight warna merah
        return 3;                
    }else{
        return 0;
    }
}
?>
<link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>plugins/barcode/jquery-barcode.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa <?php echo $icon; ?>"></i>
        <?php echo $title; ?>
        <small><?php echo $title; ?> </small>
    </h1>
    <?php echo $breadcrumb; ?>
</section>


<!-- Main content -->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

                        </div>         
                        <!--    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"> -->
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/efill/lhkpnoffline/index/listingsk/">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars(@$id, ENT_QUOTES); ?>" />
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="row">
                                      
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Cari :</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="CARI[NAMA]" placeholder="NIK" value="<?php echo htmlspecialchars(@$CARI["NAMA"], ENT_QUOTES); ?>" id="CARI_NAMA">
                                                <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                            </div>
                                            <div class="form-group">
                                                <div class="col-col-sm-3 col-sm-offset-4-2">
                                                    <button type="submit" class="btn btn-sm btn-primary"  >Cari </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>


                <!-- <div class="box-tools"> -->
                <!-- </div> -->
            </div><!-- /.box-header -->
            <div class="box-body">
          
            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                                <th width="1%">No.</th>
                                <th width="20%">Nama</th>
                                <th width="14%">Hubungan Keluarga</th>
                                <th width="18%">NIK</th>
                                <th width="18%">Surat Kuasa</th>
                                <th width="12%">Aksi Penerimaan SK</th>
                            </tr>
                    </thead>
                    <tbody>
                    <?php 
                            $i = 1;
                            // display($results,1);
                        foreach($results as $index => $list){
                         ?>
                          <tr>
                              <td class="text-center"><?= @$i++; ?></td>
                              <td><?php echo $list->NAMA?></td>
                              <td>
                                <?php
                                echo $list->HUBUNGAN_DESC 
                                ?>
                              </td>
                              <td><?php echo $list->NIK?></td>
                              <td id="FLAG<?= $index; ?>"><?php
                              if($list->FLAG_SK == '0' && $list->UMUR_LAPOR > 17){
                                echo '<strong>Belum Diterima</strong>';
                                }else if($list->UMUR_LAPOR < 17){
                                  echo '<strong>Belum Wajib</strong>';
                                }else if($list->HUBUNGAN == '4' || $list->HUBUNGAN == '5'){
                                  echo '<strong>Tidak Wajib</strong>';
                                }else{
                                   echo '<strong>Sudah Diterima</strong>';
                                }                          
                              ?></td>
                              <td class="text-center">
                              <?php
                              if($list->FLAG_SK == '0' && $list->UMUR_LAPOR > 17 && $list->HUBUNGAN_DESC=='PN'){?>
                                <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="<?php echo $list->ID_LHKPN; ?>">
                                <input type="hidden" name="selector" id="selectors" value="<?php echo $index ?>">
                                <button type="button"  title="Ubah FLAG SK" class="btn btn-sm btn-primary btn-save-data" ><i class="fa fa-check"></i></button>
                              <?php } else if($list->FLAG_SK == '0' && $list->UMUR_LAPOR > 17){?>
                                <input type="hidden" name="ID_KELUARGA" id="ID_KELUARGA" value="<?php echo $list->ID_KELUARGA; ?>">
                                <input type="hidden" name="selector" id="selector" value="<?php echo $index ?>">
                                <button type="button" title="Ubah FLAG SK" class="btn btn-sm btn-primary btn-save-data-fm" ><i class="fa fa-check"></i></button>
                              <?php } ?></td>
                          
                          </tr>
                        <?php } ?>
                        </tbody>
                </table>
<?php echo isset($content_js)?$content_js:''; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->
<!-- /.content -->
<script>
          $('.btn-save-data').click(function(event){
            event.preventDefault();
            var index = $('#selectors').val();
            $.ajax({
                url:'<?php echo base_url();?>index.php/efill/lhkpnoffline/update_flag_sk/',
                method:"POST",
                data:{
                    ID_LHKPN: $('#ID_LHKPN').val(),
                    FLAG : 'PN'
                },
                success:function(response) {
                   $('.btn-save-data').addClass("hidden");
                   document.getElementById('FLAG'+index).innerHTML = "<strong>Sudah Diterima</strong>";
                },
                error:function(){
                    console.log("error");
                }

            });
        });
        
        $('.btn-save-data-fm').click(function(event){
            event.preventDefault();
            var index = $('#selector').val();
            $.ajax({
                url:'<?php echo base_url();?>index.php/efill/lhkpnoffline/update_flag_sk/',
                method:"POST",
                data:{
                    ID_KELUARGA: $('#ID_KELUARGA').val(),
                    FLAG : 'FM'
                },
                success:function(response) {
                  $('.btn-save-data-fm').addClass("hidden");
                  document.getElementById('FLAG'+index).innerHTML = "<strong>Sudah Diterima</strong>";
                },
                error:function(){
                    console.log("error");
                }

            });
        });

        </script>
<script>

$('#btn-cari').click(function(e){
                e.preventDefault();
                $('#table-periksa').DataTable().ajax.reload();
                table.ajax.reload();
            });

    var table = $('#table-periksa').DataTable({
    "destroy": true,
    "processing": false, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    "ajax": {
      "url": '<?php echo site_url("index.php/efill/lhkpnoffline/listingsk_ajax/"); ?>',
      "type" : "POST",
      "data" : function (CARI) {
        CARI.NAMA= $('#CARI_NAMA').val();
      }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
      {
        "targets": [ 0,5 ], //first column / numbering column
        "orderable": false, //set not orderable
      }
    ],
    "columns": [
      { "width": "25%" },
      { "width": "25%" },
      { "width": "25%" },
      { "width": "25%" },
      { "width": "5%" },
      { "width": "5%" },
    ]
  });
 
// setInterval( function () {
//     table.ajax.reload( null, false ); // user paging is not reset on reload
// }, 1000 );

</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>

<?php exit(); ?>
