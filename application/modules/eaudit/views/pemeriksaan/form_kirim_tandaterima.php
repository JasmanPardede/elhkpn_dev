<form method="post" id="ajaxFormKirimTandaTerima" action="index.php/ever/verification/dokirimtandaterima">
    <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="<?php echo $item->ID_LHKPN ?>">
    <input type="hidden" name="entry_via" id="entry_via" value="<?php echo $item->entry_via ?>">
    <p>Apakah anda yakin akan mengirim ulang tanda terima ke email <?php echo $item->EMAIL ?>?</p>
    <div class="pull-right">
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Tidak</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        ng.formProcess($("#ajaxFormKirimTandaTerima"), 'kirim_tandaterima', location.href.split('#')[1]);
    });
</script>