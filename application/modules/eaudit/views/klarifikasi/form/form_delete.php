<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Rizky Awlia Fajrin (Evan Sumangkut) - PT.Waditra Reka Cipta
 * @package Views/user
*/
?>

<div id="wrapperFormDelete" class='form-horizontal'>
    Apakah anda yakin akan menghapus data ?
    <form method="post" id="ajaxFormDelete" action="index.php/eaudit/klarifikasi/soft_delete/<?php echo $id; ?>/harta_tidak_bergerak">
        <div class="box-body">
                        
            <div class="form-group">
            <br>
            </div>
           
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_REGULASI" value="<?php echo $item->ID_REGULASI;?>">
            <input type="hidden" name="act" value="dodelete">
           <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>Hapus</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        //ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
        // var dtTable = $('#dt_completeNEW').DataTable();
        // ng.formProcess($("#ajaxFormDelete"), 'delete', '');
        // dtTable.ajax.reload( null, false );
        // $('#ajaxFormDelete').submit(function (e) {
        //     dtTable.ajax.reload( null, false );
        // });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li3&bottomli=lii0';
        ng.formProcess($("#ajaxFormDelete"), 'delete', url);
        
    });
</script>