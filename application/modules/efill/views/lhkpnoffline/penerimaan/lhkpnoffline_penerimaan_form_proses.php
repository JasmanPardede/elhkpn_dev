Proses Entry LHKPN<br>
<div id="wrapperFormProses" class="form-horizontal">
    <form method="post" id="ajaxFormProses" action="<?php echo $urlSave; ?>">
        <div class="box-body">
            Item Entry dan methodenya :<br>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_PENERIMAAN" value="<?php echo $item->ID_PENERIMAAN; ?>">
            <input type="hidden" name="act" value="<?php echo $act; ?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        ng.formProcess($("#ajaxFormProses"), 'proses', location.href.split('#')[1]);
    });
</script>