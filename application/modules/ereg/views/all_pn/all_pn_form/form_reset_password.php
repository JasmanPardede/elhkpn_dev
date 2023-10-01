<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/all_pn/do_reset_password">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER ?>">
    <p>Apakah anda yakin akan mereset password?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        ng.formProcess($("#ajaxFormResetPassword"), 'reset_password','');
    });
</script>