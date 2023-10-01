<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/do_delete_daftar_pn_individual/<?php echo $id; ?>/<?php echo $calon; ?>">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $id; ?>">
    <p>Apakah anda yakin akan menghapus data ini?</p>
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