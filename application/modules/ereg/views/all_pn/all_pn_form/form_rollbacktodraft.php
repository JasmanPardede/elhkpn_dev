<form method="post" id="ajaxFormRollbackToDraft" action="index.php/ereg/all_pn/SettoDraft">
    <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="<?php echo $item->ID_LHKPN ?>">
    <p>Apakah anda yakin akan mengembalikan status laporan menjadi Draft?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        var dtTable = $('#dt_completeNEW').DataTable();
        ng.formProcess($("#ajaxFormRollbackToDraft"), 'rollbacktodraft', '');
        dtTable.ajax.reload( null, false );
        $('#ajaxFormRollbackToDraft').submit(function (e) {
            dtTable.ajax.reload( null, false );
        });
//            ng.formProcess($("#ajaxFormKirimAktivasi"), 'kirim_aktivasi', dtTable.ajax.reload( null, false ));
    });
</script>

