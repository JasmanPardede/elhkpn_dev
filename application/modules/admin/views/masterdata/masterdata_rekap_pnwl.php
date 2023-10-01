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

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Rekap Riwayat Jabatan PN/WL Tahunan
            <small></small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                <p>
                    Proses berikut ini melakukan rekapitulasi untuk jabatan PN/WL selama setahun.
                </p>
                <?php
                    echo '<button class="btn btn-sm btn-primary" id="btn-add" href="index.php/admin/masterdata/rekap_pnwl_popup"><i class="fa fa-refresh"></i> Proses</button>';
                ?>
                </div><!-- /.box-header -->
                
                <div class="box-body with-border"></div>
                <div class="box-footer clearfix"><div class="clearfix"></div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

<script language="javascript">
$(document).ready(function () {
    
    $('#btn-clear').click(function(event) {
        $('#ajaxFormCari').find('input:text').val('');
        $('#ajaxFormCari').trigger('submit');
    });

    $("#btn-add").click(function () {
        url = $(this).attr('href');
        $('#loader_area').show();
        $.post(url, function (html) {
            $('#loader_area').hide();
            OpenModalBox('Rekap Riwayat Jabatan PN/WL Tahunan', html, '');
        });            
        return false;              
    });
    
});
</script>

<!-- <div class="box box-primary">
<div class="box-header with-border">
<p>Proses berikut ini melakukan rekapitulasi untuk jabatan PN/WL selama setahun.</p>
    <?php 
    // $proses = '<button class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Proses</button>';
    $proses = '<i class="fa fa-refresh"></i> Proses';    
    echo anchor('index.php/admin/masterdata/rekap_pnwl_proses', $proses, 'class="btn btn-sm btn-primary"');
    ?>
    <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Batal</button>
</div>
<div class="box-body">
</div> -->