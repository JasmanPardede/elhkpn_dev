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
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/instansi
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label">Bidang <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name="UK_BIDANG_ID" id="" class="form-control" >
                        <option value="">-Pilih Bidang-</option>
                        <?php foreach ($bidang as $bdg): ?>
                            <option value="<?= @$bdg->BDG_ID; ?>"><?= @$bdg->BDG_NAMA; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div> -->
            <div class="form-group">
                <label class="col-sm-4 control-label">Provinsi <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input required type='text' class="form-control form-select2" name='ID_PROV' style="border:none;  padding:6px 0px;" id='ID_PROV' placeholder="Select Provinsi">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kabupaten <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='NAME_KAB' id='NAME_KAB' value='' required>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit" class="form-horizontal">
    <form method="post" id="ajaxFormEdit" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label">Bidang <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name="UK_BIDANG_ID" id="" class="form-control" >
                        <option value="">-Pilih Bidang-</option>
                        <?php foreach ($bidang as $bdg): ?>
                            <option <?= ($item->UK_BIDANG_ID == $bdg->BDG_ID ? 'selected' : ''); ?> value="<?= @$bdg->BDG_ID; ?>"><?= @$bdg->BDG_NAMA; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div> -->
            <div class="form-group">
                <label class="col-sm-4 control-label">Provinsi <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input value="<?= @$item->ID_PROV; ?>" required type='text' class="form-control form-select2" name='ID_PROV' style="border:none;  padding:6px 0px;" id='ID_PROV' placeholder="Select Provinsi">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kabupaten<font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='NAME_KAB' id='NAME_KAB' value='<?= @$item->NAME_KAB; ?>' required>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_KAB" value="<?php echo $item->ID_KAB;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus Provinsi dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Kabupaten :</label>
                <label class="col-sm-8">
                    <?php echo $item->NAME_KAB;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_KAB" value="<?php echo $item->ID_KAB;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Hapus</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>
<?php
if($form=='kembalikan'){
?>
<div id="wrapperFormKembalikan" class="form-horizontal">
    Benarkah Akan Mengaktifkan kembali data Kabupaten dibawah ini ?
    <form method="post" id="ajaxFormKembalikan" action="<?php echo $urlSave;?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Kabupaten :</label>
                <label class="col-sm-8">
                    <?php echo $item->NAME_KAB;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_KAB" value="<?php echo $item->ID_KAB;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Kembalikan</button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormKembalikan"), 'kembalikan', '', sumbitAjaxFormCari);
    });
</script>
<?php
}
?>
<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail" class="form-horizontal">
        <!-- <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Bidang :</label>
            <label class="col-sm-8">
                <?php echo $item->BDG_NAMA;?>
            </label>
        </div> -->
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Provinsi :</label>
            <label class="col-sm-8">
                <?php echo $item->NAME_PROV;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Kabupaten</label>
            <label class="col-sm-8">
                <?php echo $item->NAME_KAB;?>
            </label>
        </div>
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
    </div>
</div>
<?php
}
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#ID_PROV').select2({
            minimumInputLength: 0,
            ajax: {                             
                url: "<?= base_url('index.php/share/reff/get_propinsi') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/get_propinsi') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        });
    });
</script>