
<form method="post" id="ajaxFormEdit" action="index.php/userakses/savepermission">
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
        <thead><tr><td align="center">Menu</td><td align="center">IS READ</td><td align="center">IS WRITE</td></tr></thead>
        <tbody>
        <?php
        echo $menu;
        ?>
        </tbody>
    </table>
    <div class="pull-right">
        <input type="hidden" name="ID_USER" value="<?php echo $id_user ?>">
        <input type="hidden" name="act" value="doinsert">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <input type="reset" class="btn btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', 'index.php/userakses/');
    });
</script>
