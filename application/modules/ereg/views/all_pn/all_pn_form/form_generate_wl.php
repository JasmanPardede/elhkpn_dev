<form method="post" id="ajaxFormGenerateWL" action="index.php/ereg/all_pn/generatewl">
    <input type="hidden" name="T_TAHUN_WL" id="T_TAHUN_WL" value="<?php echo $item; ?>">
    <p>Apakah anda yakin generate wl untuk tahun depan dengan total daftar WL <?php echo $wlAktif; ?> ?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
            ng.formProcess($("#ajaxFormGenerateWL"), 'generate_wl', location.href.split('#')[1]);
    });
</script>