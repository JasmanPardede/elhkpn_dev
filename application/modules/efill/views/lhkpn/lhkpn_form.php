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
 * @package Views/lhkpn
*/
?>
<?php
if($form=='add'){
	// $role = $this->session->userdata('ID_ROLE');
	// if($role == ID_ROLE_PN){
	if($this->session->userdata('IS_PN')==1){
		$pn = true;
		// $id = $this->session->userdata('NIK');
		$sql = "SELECT * FROM T_PN WHERE NIK = '".$this->session->userdata('NIK')."'";
		$itempn = $this->db->query($sql)->row();
		$id = $itempn->ID_PN;
	}else{
		$pn = false;
	}
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <div id="wrapperFormCreate">
	    <form method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savelhkpn" enctype="multipart/form-data">
	        <div class="row">
	            <div class="form-group">
	                <label class="col-sm-4 control-label">Penyelenggara Negara <span class="red-label">*</span> :</label>
	                <div class="col-sm-8">
	                    <!-- <input type="text" class="form-control" style="border:none;" required name="PN" placeholder="current user pn" <?=($pn ? 'value="'.$id.'" readonly="readonly"' : '')?>> -->
	                    <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                        <?php
                        if($pn){
                        ?>
	                    <div class="col-sm-8">
                            <input type='hidden' class="form-control" name='ID_PN' id='ID_PN' value='<?=$itempn->ID_PN;?>' required readonly>
                            <input type='hidden' class="form-control" name='NIK' id='NIK' value='<?=$itempn->NIK;?>' required readonly>
                            <input type='hidden' name='is_PN' value='1' required readonly>
                            <input type='text' class="form-control" name='NAMA' id='NAMA' value='<?=$itempn->NAMA;?>' required readonly>
                        </div>                        	
                        <?php
                        }else{
                        ?>
	                    <div class="col-sm-8">
                            <input type='hidden' class="form-control" name='ID_PN' id='ID_PN' value='' required readonly>
                            <input type='hidden' class="form-control" name='NIK' id='NIK' value='' required readonly>
                            <input type='hidden' name='is_PN' value='0' required readonly>
                            <input type='text' class="form-control" name='NAMA' id='NAMA' value='' required readonly>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-sm btn-default" id="btnCariPN">...</button>
                        </div>
                        <?php
                    	}
                        ?>
	                </div>
	            </div>
	                
	            <div class="form-group">
	                <label class="col-sm-4 control-label">Jenis Laporan <span class="red-label">*</span> :</label>
	                <div class="col-sm-3">
	                    <h4>Periodik</h4>
	                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="4"> Sedang Menjabat</label><br>
	                    <!-- Tahun Pelaporan <input type="text" name="TAHUN_PELAPORAN" value="" class="TAHUN_PELAPORAN year-picker" style="width: 100px;"> -->
	                    Tahun Pelaporan
	                    <select name="TAHUN_PELAPORAN" value="" class="TAHUN_PELAPORAN" style="width: 100px;">
	                    	<option value="">Pilih Tahun</option>
	                    	<?php
	                    		for($i = date('Y')-1;$i > (date('Y')-10);$i--){
	                    			echo "<option value='$i'>$i</option>";
	                    		}
	                    	?>
	                	</select>
	                    <br>
	                </div>
	                <div class="col-sm-5" style="border-left: 1px solid #cfcfcf;">
	                    <h4>Khusus</h4>
	                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="1"> Calon Penyelenggara Negara</label><br>
	                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="2"> Awal Menjabat</label><br>
	                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="3"> Akhir Menjabat</label><br>
	                    Tanggal Pelaporan <input type="text" name="TANGGAL_PELAPORAN" class="TANGGAL_PELAPORAN date-picker" value="" placeholder='DD/MM/YYYY'>
	                </div>
	            </div>
	        </div>
	        <div class="pull-right">
	            <button type="submit" class="btn btn-sm btn-primary">Lanjut</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Batal</button>
            </div>
	    </form>
	</div>
    <div id="wrapperCariPN" style="display: none;">
        <!-- <button type="button" class="btn btn-sm btn-default" id="btnTambahPN"><i class="fa fa-plus"></i> Tambah Data PN</button> -->
        <div class="pull-right">
            <form method="post" id="ajaxFormCariPN" action="index.php/efill/lhkpnoffline/hasilcaripn/">
            <div class="input-group col-sm-push-5">
                <div class="col-sm-3">
                    <input type="text" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT_PN"/>
                </div>
                <div class="input-group-btn col-sm-3">
                  <button type="submit" class="btn btn-sm btn-default" id="btn-cari"><i class="fa fa-search"></i></button>
                  <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PN').val(''); $('#CARI_TEXT_PN').focus(); $('#ajaxFormCariPN').trigger('submit');">Clear</button>
                </div>
            </div>
            </form>
        </div>
        <br>
        <div class="clearfix"></div>
        <div id="wrapperHasilCariPN">
            <!-- draw here -->
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-sm btn-default" id="btnKembaliKePenerimaan">Kembali Ke form</button>
        </div>
    </div>    
</div>
<script type="text/javascript">

	$("#ajaxFormAdd").submit(function(){
	    var url = $(this).attr('action');
	    var data = $(this).serializeArray();
	    $.post(url, data, function(res){
	        msg = {
	           success : 'Data Berhasil Disimpan!',
	           error : 'Data Gagal Disimpan! Laporan untuk tahun ini sudah ada!'
	        };
	        if (res == '0') {
	           alertify.error(msg.error);
	        } else {
	           alertify.success(msg.success);
	        }
	        CloseModalBox();
            if(res != '0'){
	            ng.LoadAjaxContent('index.php/efill/lhkpn/entry/'+res+'/edit');
            }
	    })
	    return false;
	})

create = {
    showFormCreate: function() {
        $('#wrapperFormCreate').slideDown('fast', function() {});
    },
    hideFormCreate: function() {
        $('#wrapperFormCreate').slideUp('fast');
    },
    showCariPN: function() {
        $('#wrapperCariPN').slideDown('fast', function() {
            $('#wrapperCariPN').find('#CARI_TEXT_PN').focus();
        });
        $("#ajaxFormCariPN").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
            return false;
        });
    },
    eventShowHasilCariPN: function() {
        $(".paginationPN").find("a").click(function() {
            var url = $(this).attr('href');
            // window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCariPN'), '#wrapperHasilCariPN', _this.eventShowHasilCariPN);
            return false;
        });
        $('.btnSelectPN').click(function() {
            DATAPN = $(this).attr('data-pn');
            PN = DATAPN.split('::');
            $('#wrapperFormCreate').find('#ID_PN').val(PN[0]);
            $('#wrapperFormCreate').find('#NIK').val(PN[1]);
            $('#wrapperFormCreate').find('#NAMA').val(PN[2]);
            _this.showFormCreate();
            _this.hideCariPN();
        });
    },
    hideCariPN: function() {
        $('#wrapperCariPN').slideUp('fast');
    },    
    init:function(){
        _this = create;

    $('#btnCariPN').click(function() {
        _this.showCariPN();
        _this.hideFormCreate();
    });
    $('#btnKembaliKePenerimaan').click(function() {
        _this.showFormCreate();
        _this.hideCariPN();
    });

    }
}




    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
		create.init();
		$('.JENIS_LAPORAN').change(function(){
			var val = $(this).val();
			if(val == '4'){			
				 $('.TAHUN_PELAPORAN').prop('required', true);
				 $('.TANGGAL_PELAPORAN').prop('required', false);
			}else{
				 $('.TANGGAL_PELAPORAN').prop('required', true);
				 $('.TAHUN_PELAPORAN').prop('required', false);
			}
		});

		$('input[name="PN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/efill/lhkpn/getUser')?>",
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
                    $.ajax("<?=base_url('index.php/efill/lhkpn/getUser')?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                return state.name;
            },
            formatSelection:  function (state) {
                return state.name;
            }
        });
    });
</script>
<script type="text/javascript">
        jQuery(document).ready(function() {
            $('.year-picker').datepicker({
                orientation: "left",
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });

            $('.date-picker').datepicker({
                orientation: "left",
                format: 'dd/mm/yyyy',
                autoclose: true
            });    
        });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit" class="form-horizontal">
    <form method="post" id="ajaxFormEdit" action="index.php/efill/lhkpn/savelhkpn">
		<div class="row"> 
			<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
				<div class="form-group">
					<label class="col-sm-3 control-label">PN/WL :</label>
					<div class="col-sm-9">
						<input class="form-control" type='text' size='40' name='ID_PEJABAT' id='ID_PEJABAT'  value='<?php echo $item->ID_PN;?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tgl Lapor :</label>
					<div class="col-sm-9">
						<input class="form-control" type='text' size='40' name='TGL_LAPOR' id='TGL_LAPOR'  value='<?php echo date('d/m/Y',strtotime($item->TGL_LAPOR));?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nip :</label>
					<div class="col-sm-9">
						<input class="form-control" type='text' size='40' name='NIP' id='NIP'  value='<?php echo $item->NIP;?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Jabatan :</label>
					<div class="col-sm-9">
						<input class="form-control" type='text' size='40' name='JABATAN' id='JABATAN'  value='<?php echo $item->JABATAN;?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Eselon :</label>
					<div class="col-sm-9">
						<input class="form-control" type='text' size='40' name='ESELON' id='ESELON'  value='<?php echo $item->ESELON;?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Unit Kerja :</label>
					<div class="col-sm-9">
						<input class="form-control" type='text' size='40' name='UNIT_KERJA' id='UNIT_KERJA'  value='<?php echo $item->UNIT_KERJA;?>'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Lembaga :</label>
					<div class="col-sm-9">
						<input class="form-control" type='text' size='40' name='LEMBAGA' id='LEMBAGA'  value='<?php echo $item->LEMBAGA;?>'>
					</div>
				</div>
			</div>
		</div>
		<div class="pull-right">
			<input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>">
			<input type="hidden" name="act" value="doupdate">
			<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
			<input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
		</div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);            
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete" class="form-horizontal">
    Benarkah Akan Menghapus Lhkpn dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/savelhkpn">
		<div class="box-body">
			<div class="pull-right">
				<input type="hidden" name="ID_LHKPN" value="<?php echo substr(md5($item->ID_LHKPN),5,8);?>">
				<input type="hidden" name="act" value="dodelete">
				<button type="submit" class="btn btn-sm btn-primary">Hapus</button>
				<input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
			</div>
		</div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
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
		<div class="col-sm-4" align="right">
			<label>PN/WL :</label>
		</div>
		<div class="col-sm-8"><?php echo $item->ID_PN;?></div>
	</div>
	<div class="form-group">
		<div class="col-sm-4" align="right">
			<label>Tgl Lapor :</label>
		</div>
		<div class="col-sm-8"><?php echo date('d/m/Y',strtotime($item->TGL_LAPOR));?></div>
	</div>
	<div class="form-group">
		<div class="col-sm-4" align="right">
			<label>Nip :</label>
		</div>
		<div class="col-sm-8"><?php echo $item->NIP;?></div>
	</div>
	<div class="form-group">
		<div class="col-sm-4" align="right">
			<label>Jabatan :</label>
		</div>
		<div class="col-sm-8"><?php echo $item->JABATAN;?></div>
	</div>
	<div class="form-group">
		<div class="col-sm-4" align="right">
			<label>Eselon :</label>
		</div>
		<div class="col-sm-8"><?php echo $item->ESELON;?></div>
	</div>
	<div class="form-group">
		<div class="col-sm-4" align="right">
			<label>Unit Kerja :</label>
		</div>
		<div class="col-sm-8"><?php echo $item->UNIT_KERJA;?></div>
	</div>
	<div class="form-group">
		<div class="col-sm-4" align="right">
			<label>Lembaga :</label>
		</div>
		<div class="col-sm-8"><?php echo $item->LEMBAGA;?></div>
	</div>
	<div class="pull-right">
		<input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
	</div>
</div>
<?php
}
?>

