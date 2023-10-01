<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/delete_vi2/<?php echo $id; ?>/<?php echo $idj; ?>"> 
    <!--<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/delete_penambahanwl/<?php echo $id; ?>/<?php echo $idj; ?>">-->
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $id; ?>">
    <input type="hidden" name="ID_PN_JABATAN" id="ID_PN_JABATAN" value="<?php echo $idj; ?>">
    <p>Apakah anda yakin akan membatalkan verifikasi data individual ini?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        ng.formProcess($("#ajaxFormResetPassword"), 'delete', location.href.split('#')[1]);
    });
</script>