<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormAdd" action="index.php/admin/masterdata/rekap_pnwl_proses" enctype="multipart/form-data">
        <div class="box-body">
            <p>
                    Apakah anda yakin akan melanjutkan proses ini?
                </p>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Ya</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">    
    $(function() {
        $('.over').popover();
           $('.over').on('click', function(e){
              $('.over').not(this).popover('hide'); 
           });
    })

    $(document).ready(function() {
        $('.numbersOnly').mask("(+99) 9999?-9999?-9999");
        // $('#INST_SATKERKD').select2();
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>