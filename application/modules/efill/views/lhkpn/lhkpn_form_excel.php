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
	$role = $this->session->userdata('ID_ROLE');
	if($role == ID_ROLE_PN){
		$pn = true;
		$id = $this->session->userdata('NIK');
	}else{
		$pn = false;
	}
?>
<div id="wrapperFormAdd" class="form-horizontal">
    <form method="post" id="ajaxFormExcel" action="index.php/efill/lhkpn/saveexcel" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-4 control-label">Penyelenggara Negara <span class="red-label">*</span> :</label>
                <div class="col-sm-8">
                    <input type="text" required name="PN" placeholder="current user pn" <?=($pn ? 'value="'.$id.'" readonly="readonly"' : '')?>>
                    <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                </div>
            </div>
                
            <div class="form-group">
                <label class="col-sm-4 control-label">Jenis Laporan <span class="red-label">*</span> :</label>
                <div class="col-sm-4" style="border-right: 1px solid #cfcfcf;">
                    <h4>Khusus</h4>
                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="1"> Calon Penyelenggara Negara</label><br>
                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="2"> Awal Menjabat</label><br>
                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="3"> Akhir Menjabat</label><br>
                    Tanggal Pelaporan <input type="text" name="TANGGAL_PELAPORAN" class="TANGGAL_PELAPORAN date-picker" value="" placeholder='DD/MM/YYYY'>
                </div>
                <div class="col-sm-4">
                    <h4>Periodik</h4>
                    <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="4"> Sedang Menjabat</label><br>
                    Tahun Pelaporan <input type="text" name="TAHUN_PELAPORAN" value="" class="TAHUN_PELAPORAN year-picker">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">File Excel <span class="red-label">*</span> :</label>
                <div class="col-sm-8">
                    <input type="file" required name="file" />
                </div>
            </div>
        </div>
        <div class="pull-right">
            <button type="submit" class="btn btn-sm btn-primary">Lanjut</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {

    var options = { 
    	dataType: 	'json',
        success:    showResponse
    }; 

    $('#ajaxFormExcel').ajaxForm(options); 
		
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
                url: "<?=base_url('index.php/ereg/pn/getUser')?>",
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
                    $.ajax("<?=base_url('index.php/ereg/pn/getUser')?>/"+id, {
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

	function showResponse(responseText, statusText, xhr, $form)  {	 
		console.log(responseText);
	} 
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
				<input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>">
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

