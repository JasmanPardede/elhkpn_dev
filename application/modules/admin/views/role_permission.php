
<form method="post" id="ajaxFormEdit" action="index.php/admin/role/saverole">
    <b><?php echo $role;?></b>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
        <thead><tr><td align="center">Menu</td><td align="center">IS READ</td><td align="center">IS WRITE</td></tr></thead>
        <tbody>
        <?php
        echo $menu;
        ?>
        </tbody>
    </table>
    <div class="pull-right">
        <input type="hidden" name="ID_ROLE" value="<?php echo $id_role ?>">
        <input type="hidden" name="act" value="doupdaterolepermission">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', 'index.php/admin/role/');
    });
</script>
