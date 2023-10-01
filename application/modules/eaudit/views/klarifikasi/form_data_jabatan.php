<div id="wrapperFormEdit">
    <?php //display($lhkpn_jabatan['result']); ?>
    <form role="form" id="ajaxFormEdit" action="index.php/eaudit/klarifikasi/<?php echo $action; ?>" >
    	<input type='hidden' name='ID' id='ID'  value='<?php echo $ID; ?>'>
        <div class="form-group">
            <label> Lembaga <span class="red-label">*</span>: </label>
            <input required type='text' id='lembaga' name='LEMBAGA' class='form-control' value='<?php echo $onAdd ? $lhkpn_jabatan['result']->LEMBAGA : ""; ?>' />
         </div>
        <div class="form-group">
            <label> Unit Kerja <span class="red-label">*</span>: </label>
            <input required type='text' id='uk' name='UNIT_KERJA' class='form-control' value='<?php echo $onAdd ? $lhkpn_jabatan['result']->UK_ID : ""; ?>' />
        </div>
        <div class="form-group">
            <label>Sub Unit Kerja : </label>
            <input type="text" id="sub_uk" name="SUB_UNIT_KERJA" class="form-control" value="<?php echo $onAdd ? $lhkpn_jabatan['result']->SUK_ID : ""; ?>" />
        </div>
        <div class="form-group">
            <label>Jabatan <span class="red-label">*</span>: </label>
            <input type="text" id="jabatan" name="ID_JABATAN" class="form-control" value="<?php echo $onAdd ? $lhkpn_jabatan['result']->ID_JABATAN : ""; ?>" />
        </div>
        <div class="form-group">
            <label>Alamat Kantor : </label>
            <textarea rows="3" cols="50" class="form-control" name="ALAMAT_KANTOR" id="alamat_kantor" rows="2"><?php echo $onAdd ? $lhkpn_jabatan['result']->ALAMAT_KANTOR : ""; ?></textarea>
        </div>
        <div class="pull-right">
        	<button id="btnsimpan" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
        	<button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
<script type="text/javascript">
	var LEMBAGA_ID = '<?php echo $onAdd ? $lhkpn_jabatan['result']->LEMBAGA : ''; ?>';
	var UK_ID = '<?php echo $onAdd ? $lhkpn_jabatan['result']->UNIT_KERJA : ''; ?>';
	var SUK_ID = '<?php echo $onAdd ? $lhkpn_jabatan['result']->SUB_UNIT_KERJA : ''; ?>';
	var JABATAN_ID = '<?php echo $onAdd ? $lhkpn_jabatan['result']->ID_JABATAN : ''; ?>';
	var LEMBAGA_NAMA = '<?php echo $onAdd ? $lhkpn_jabatan['result']->LEMBAGA_NAMA : ''; ?>';
	var UK_NAMA = '<?php echo $onAdd ? $lhkpn_jabatan['result']->UNIT_KERJA_NAMA : ''; ?>';
	var SUK_NAMA = '<?php echo $onAdd ? $lhkpn_jabatan['result']->SUB_UNIT_KERJA_NAMA : ''; ?>';
	var JABATAN_NAMA = '<?php echo $onAdd ? $lhkpn_jabatan['result']->JABATAN_NAMA : ''; ?>';
	var EDIT = '<?php echo $onAdd ? $lhkpn_jabatan['result']->edit : false; ?>';
    
    $(document).ready(function () {

        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li1&bottomli=0';
        
        $("#btnsimpan").click(function(){

            var vallembaga = $("#lembaga").val();
            var valuk = $("#uk").val();
            var valjabatan = $("#jabatan").val();
            
            if(vallembaga =='' || valuk =='' || valjabatan == ''){
                alert("Lembaga, Unit Kerja, dan Jabatan harus diisi");
                return false;
            }

        });
        
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

        $('#lembaga').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getlembaga',
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                	console.log(data);
                    var myResults = [];
                    $.each(data, function (index, item) {
                    	
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            var LEMBAGA_ID = $('#lembaga').val();
            GetUK(LEMBAGA_ID);
        });

        GetUK(0);
        SubUK(0);
        GetJabatan(0, 0);

        if(EDIT==true){
        	GetUK(LEMBAGA_ID);
            SubUK(UK_ID);
            GetJabatan(UK_ID, SUK_ID);
            $('#lembaga').select2("data", {id: LEMBAGA_ID, text: LEMBAGA_NAMA});
        	$('#uk').select2("data", {id: UK_ID, text: UK_NAMA});
        	$('#sub_uk').select2("data", {id: SUK_ID, text: SUK_NAMA});
        	$('#jabatan').select2("data", {id: JABATAN_ID, text: JABATAN_NAMA });
   	 	}

    });

    function GetUK(LEMBAGA_ID) {
        $('#uk').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getuk/' + LEMBAGA_ID,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            var ID_UK = $('#uk').val();
            SubUK(ID_UK);
            GetJabatan(ID_UK);
        });
    };

    function SubUK(ID_UK) {
        $('#sub_uk').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getsubuk/' + ID_UK,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            var UK_ID = $('#uk').val();
            var SUK_ID = $('#sub_uk').val();
            GetJabatan(UK_ID, SUK_ID);
        });
    };

    function GetJabatan(UK_ID, SUK_ID) {
        SUK_ID = SUK_ID ? SUK_ID : 0;
        $('#jabatan').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getjabatan/' + UK_ID + '/' + SUK_ID,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        });
    };


</script>