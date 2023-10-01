<div id="wrapperFormEdit">
    <form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>" >
    	<input type='hidden' name='ID' id='ID'  value='<?php echo $ID; ?>'>
        <div class="form-group">
            <label> Lembaga <span class="red-label">*</span>: </label>
            <input required type='text' id='lembaga' name='LEMBAGA'  class='form-control' value='<?php echo !is_null($lhkpn_jabatan) ? $lhkpn_jabatan->LEMBAGA : ""; ?>' />
         </div>
        <div class="form-group">
            <label> Unit Kerja <span class="red-label">*</span>: </label>
            <input required type='text' id='uk' name='UNIT_KERJA'   class='form-control' value='<?php echo !is_null($lhkpn_jabatan) ? $lhkpn_jabatan->UK_ID : ""; ?>' />
        </div>
        <div class="form-group">
            <label>Sub Unit Kerja : </label>
            <input type="text" id="sub_uk" name="SUB_UNIT_KERJA" value="<?php echo !is_null($lhkpn_jabatan) ? @$lhkpn_jabatan->SUK_ID : ""; ?>"  class="form-control" />
        </div>
        <div class="form-group">
            <label>Jabatan <span class="red-label">*</span>: </label>
            <input type="text" id="jabatan" name="ID_JABATAN" value="<?php echo !is_null($lhkpn_jabatan) ? @$lhkpn_jabatan->ID_JABATAN : ""; ?>"   class="form-control" />
        </div>
        <div class="form-group">
            <label>Alamat Kantor : </label>
            <textarea rows="3" cols="50" class="form-control" name="ALAMAT_KANTOR" id="alamat_kantor" rows="2"><?php echo !is_null($lhkpn_jabatan) ? @$lhkpn_jabatan->ALAMAT_KANTOR : ""; ?></textarea>
        </div>
        <div class="pull-right">
        	<button id="btnsimpan" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
        	<button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
<script type="text/javascript">
	var LEMBAGA_ID = '<?php echo @$lhkpn_jabatan->LEMBAGA; ?>';
	var UK_ID = '<?php echo @$lhkpn_jabatan->UNIT_KERJA; ?>';
	var SUK_ID = '<?php echo @$lhkpn_jabatan->SUB_UNIT_KERJA; ?>';
	var JABATAN_ID = '<?php echo @$lhkpn_jabatan->ID_JABATAN; ?>';
	var LEMBAGA_NAMA = '<?php echo @$lhkpn_jabatan->LEMBAGA_NAMA; ?>';
	var UK_NAMA = '<?php echo @$lhkpn_jabatan->UNIT_KERJA_NAMA; ?>';
	var SUK_NAMA = '<?php echo @$lhkpn_jabatan->SUB_UNIT_KERJA_NAMA; ?>';
	var JABATAN_NAMA = '<?php echo @$lhkpn_jabatan->JABATAN_NAMA; ?>';
	var EDIT = '<?php echo $lhkpn_jabatan->edit; ?>';
    $(document).ready(function () {

//     	console.log(ID_LEMBAGA);
//     	console.log(LEMBAGA_NAMA);
//     	console.log(UNIT_KERJA_NAMA);
//     	console.log(SUB_UNIT_KERJA_NAMA);

    	 
    	
    	// $('#alamat_kantor').val(alamat_kantor);


        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li2&bottomli=0';
        
        
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
            var ID_LEMBAGA = $('#lembaga').val();
            GetUK(ID_LEMBAGA);
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
//////SAMPAI SINI, DIBUATKAN SELECT DEFAULT UNTUK EDIT
//         $('#lembaga').select2("data", {id: '5', text: 'asdsa'});
//         $('#uk').select2("data", {id: '5', text: 'apa tuh'});

        
    });
    function GetUK(ID_LEMBAGA) {
        $('#uk').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getuk/' + ID_LEMBAGA,
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
    }
    ;

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
//            console.log($(this).val());
            GetJabatan(UK_ID, SUK_ID);
        });
    }
    ;

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
    }
    ;
</script>