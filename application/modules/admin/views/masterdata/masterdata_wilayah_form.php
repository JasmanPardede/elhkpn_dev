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
 * @author Gunaones - PT.Mitreka Solusi Indonesia || Capt. Irfan Kiddo - Pirate.net
 * @package Views/Wilayah
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormAdd" action="<?php echo $urlSave;?>" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">Provinsi <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name='IDPROV' style="border:none;padding:6px 0px;" id='PROVi' class="form-control select" onchange="kabkot();" placeholder="Provinsi">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kota <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name='IDKOT' id='KAB_KOTi' disabled="disabled" style="border:none;padding:6px 0px;display:none" class="form-control select" placeholder="Kabupaten Kota">    
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kecamatan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="IDKEC" value="" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kelurahan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="IDKEL" value="" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Name <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='NAME' value='' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Level <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name="LEVEL" class="form-control" >
                        <option value="1">-Pilih Level-</option>
                    	<option value="1">1</option>
                    	<option value="2">2</option>
                    	<option value="3">3</option>
                    	<option value="4">4</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', '', sumbitAjaxFormCari);
        $.post("index.php/efill/lhkpn/daftar_provinsi", function(html) {
            $.each(html, function(index, value) {
				/*
                select = '<?=@$DATA_PRIBADI->PROVINSI?>';
                if (index == select) {
                    
                    $("#PROVi").append("<option value='" + value[0] + "' selected>" + value[1] + "</option>");
                    kabkot();
                } else {
                    
                    $("#PROVi").append("<option value='" + value[0] + "'>" + value[1] + "</option>");
                };
				*/
				$("#PROVi").append("<option value='"+value['ID_PROV']+"'>"+value['PROV']+"</option>");
            });
            $("#PROVi").select2();
        }, 'json');
    });
    function kabkot(){
        $("#KAB_KOTi").prop('disabled', false);
        $("#KAB_KOTi").empty();
        $("#KAB_KOTi").show();
        $.post("index.php/efill/lhkpn/daftar_kabkot/"+$("#PROVi").val(), function(html){
            $("#KAB_KOTi").append("<option value=''>-Pilih Kabupaten/Kota-</option>");
            $.each(html, function(index, value){
                $("#KAB_KOTi").append("<option value='"+index+"'>"+value+"</option>");
            });
            $("#KAB_KOTi").select2();
        }, 'json');
    }
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
            <div class="form-group">
                <label class="col-sm-4 control-label">Provinsi <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input required name='IDPROV' value="<?php echo $item->IDPROV?>" style="border:none;padding:6px 0px;" class="form-control select" placeholder="Provinsi" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kota <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input required name='IDKOT' value="<?php echo $item->IDKOT?>" style="border:none;padding:6px 0px;" class="form-control select" placeholder="Kabupaten Kota" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kecamatan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="IDKEC" value="<?= @$item->IDKEC; ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Kelurahan <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="IDKEL" value="<?= @$item->IDKEL; ?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Name <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <input type='text' class="form-control" name='NAME' value='<?= @$item->NAME; ?>' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Level <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <select name="LEVEL" class="form-control" >
                    	<?php 
                    		if($item->LEVEL == '1'){ 
                				$ket1='selected';$ket2='';$ket3='';$ket4=''; 
            				}else if($item->LEVEL == '2'){
            					$ket1='';$ket2='selected';$ket3='';$ket4=''; 
            				}else if($item->LEVEL == '3'){
            					$ket1='';$ket2='';$ket3='selected';$ket4=''; 
            				}else{
            					$ket1='';$ket2='';$ket3='';$ket4='selected'; 
            				}
        				?>
                    	<option <?= @$ket1; ?> value="1">1</option>
                    	<option <?= @$ket2; ?> value="2">2</option>
                    	<option <?= @$ket3; ?> value="3">3</option>
                    	<option <?= @$ket4; ?> value="4">4</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_AREA" value="<?php echo $item->ID_AREA;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
        </div>
        
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name="IDPROV"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?php echo base_url('index.php/efill/lhkpn/getProvinsi')?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                     return {
                         q: term
                     };
                 },
                 results: function (data, page) {
                     return { results: data.item };
                 },
                 cache: true
             },
             initSelection: function(element, callback) {
                 var id = $(element).val();
                 if (id !== "") {
                     $.ajax("<?php echo base_url('index.php/efill/lhkpn/getProvinsi')?>/"+id, {
                         dataType: "json"
                     }).done(function(data) { callback(data[0]); });
                 }
             },
             formatResult: function (state) {
                 return state.name;
             },
             formatSelection:  function (state) {
                 prov = state.id;
                 return state.name;
             }
         });
    
        $('input[name="IDPROV"]').on("change", function (e) {
            $('input[name="IDKOT"]').select2("val", "");
        });

         $('input[name="IDKOT"]').select2({
             minimumInputLength: 0,
             ajax: {
                 url: "<?php echo base_url('index.php/efill/lhkpn/getKabupaten')?>",
                 dataType: 'json',
                 quietMillis: 250,
                 data: function (term, page) {
                     return {
                         q: term,
                         prov: prov
                     };
                 },
                 results: function (data, page) {
                     return { results: data.item };
                 },
                 cache: true
             },
             initSelection: function(element, callback) {
                 var id = $(element).val();
                 if (id !== "") {
                     $.ajax("<?php echo base_url('index.php/efill/lhkpn/getKabupaten')?>/"+prov+'/'+id, {
                         dataType: "json"
                     }).done(function(data) { callback(data[0]); });
                 }
             },
             formatResult: function (state) {
                 return state.name;
             },
             formatSelection:  function (state) {
                 kab = state.id;
                 return state.name;
             }
         });
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete" class="form-horizontal">
    
    Benarkah Akan Menghapus Wilayah dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="<?php echo $urlSave;?>">
        <div class="box-body">
           
<div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Kecamatan :</label>
                <label class="col-sm-8">
                    <?php echo $item->IDKEC;?>
                </label>
            </div><div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Kelurahan :</label>
                <label class="col-sm-8">
                    <?php echo $item->IDKEL;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Nama Wilayah :</label>
                <label class="col-sm-8">
                    <?php echo $item->NAME;?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Level :</label>
                <label class="col-sm-8">
                    <?php echo $item->LEVEL;?>
                </label>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID_AREA" value="<?php echo $item->ID_AREA;?>">
            <input type="hidden" name="act" value="<?php echo $act;?>">
			<button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
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
if($form=='detail'){
?>
<div id="wrapperFormDetail" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Provinsi :</label>
            <label class="col-sm-8">
                <?php echo $item->IDPROV;?>
            </label>
        </div><div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Kota :</label>
            <label class="col-sm-8">
                <?php echo $item->IDKOT;?>
            </label>
        </div><div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Kecamatan :</label>
            <label class="col-sm-8">
                <?php echo $item->IDKEC;?>
            </label>
        </div><div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Kelurahan :</label>
            <label class="col-sm-8">
                <?php echo $item->IDKEL;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Nama Wilayah :</label>
            <label class="col-sm-8">
                <?php echo $item->NAME;?>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-4" style="text-align:right !important;">Level :</label>
            <label class="col-sm-8">
                <?php echo $item->LEVEL;?>
            </label>
        </div>
    </div>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>