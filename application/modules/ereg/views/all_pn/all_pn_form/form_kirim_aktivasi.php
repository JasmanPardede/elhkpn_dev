<form method="post" id="ajaxFormKirimAktivasi" action="index.php/ereg/all_pn/dokirimaktivasi">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER ?>">
    <p>Apakah anda yakin akan mengirim ulang aktivasi?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormKirimAktivasi"), 'kirim_aktivasi', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormKirimAktivasi').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });
    });
</script>