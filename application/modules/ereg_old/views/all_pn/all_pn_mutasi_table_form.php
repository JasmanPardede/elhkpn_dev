
<script>
    $(document).ready(function() {
        $("#ajaxFormAddMutasiKeluar").submit(function () {
            var url = $(this).attr('action');
            $.post(url, $(this).serialize(), function(data){
                if(data == 0){
                    alertify.error('Jabatan berhasil di approve!');
                }else{
                    $.post('index.php/ereg/all_pn/cekNIK/'+$('#NIK').val(), function (data) {
                        if(data != 0){
                            $('.modal-dialog').animate({
                                width: '+=500'
                            });

                            $("#wrapperFormPNExist").html(data);
                            $.post('index.php/ereg/all_pn', function (data) {
                                $('#ajax-content').html(data);
                            });

                            alertify.success('Jabatan berhasil di approve!');
                        }
                    });
                }
            })

            return false;
        });
    })
</script>
<?php if($form=="approvmutasi"){
 ?>
    <div id="wrapperFormAdd">
         <form class="form-horizontal" method="POST" action="index.php/ereg/all_pn/saveapprove" id="ajaxFormAddMutasiKeluar">
             <input type="hidden" name="id_mutasi" id="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>" class="form-control">
             <div class="form-group ">
                 <label class="col-sm-4">Nama Pejabat</label>
                 <?php echo $this->mmutasi->get_nm_pejabat($items->ID_PN); ?>
                 <input type="hidden" name="id_pejabat" id="id_pejabat" value="<?php echo $items->ID_PN; ?>" class="form-control">
             </div>
             <div class="form-group ">
                 <label class="col-sm-4">Instansi Asal</label>
                 <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_ASAL); ?>
                 <input type="hidden" name="id_instansi_asal" id="id_instansi_asal" value="<?php echo $items->ID_INST_ASAL; ?>" class="form-control">
             </div>
             <div class="form-group ">
                 <label class="col-sm-4">Instansi Mutasi</label>
                 <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_TUJUAN); ?>
                 <input type="hidden" name="id_instansi" id="id_instansi" value="<?php echo $items->ID_INST_TUJUAN; ?>" class="form-control">
             </div>
             <div class="form-group ">
                 <label class="col-sm-4">Jabatan Mutasi</label>
                 <?php echo $this->mmutasi->get_nm_jabatan($items->ID_JABATAN_BARU); ?>
                 <input type="hidden" name="id_jabatan" class="form-control" value="<?php echo $items->ID_JABATAN_BARU; ?>">
             </div>
             <div class="pull-right">
                 <input type="hidden" name="STATUS_AKHIR_JABAT" value="<?php echo $items->ID_STATUS_AKHIR_JABAT;?>">
                 <input type="hidden" name="SD_MENJABAT" value="<?php echo date('Y-m-d');?>">
                 <input type="hidden" name="act" value="doapprove">
                 <input type="hidden" name="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>">
                 <input type="submit" value="approve" class="btn btn-sm btn-primary">
                 <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="backForm();">
             </div>
        </form>
     </div>
<?php } ?>