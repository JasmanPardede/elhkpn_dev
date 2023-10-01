
<script>
$(document).ready(function() {
		/*$( "#pejabat" ).autocomplete({
			source: '<?php echo $url_load_pejabat; ?>',
			focus: function(event, ui) {
				//$(idField).val(ui.item.value);
				$(this).val(ui.item.value+'|'+ui.item.label);
				return false;
			},
			select : function (event,ui){
				console.log(ui.item.id);
				$(this).val(ui.item.value+'|'+ui.item.label);
				return false;
			}
		}).data("autocomplete")._renderItem = function(ul, item) {
			return $("<li></li>").data("item.autocomplete", item).append(
				'<a>' + item.value + ' | ' + item.l + '</a>').appendTo(ul);
		};*/
	
		$("#pejabat").autocomplete("<?php echo $url_load_pejabat; ?>"  , {
		width: 400,
		autoFill: true,
		mustMatch: true,
		matchContains: true,
		scrollHeight: 220,
		autoFocus : true,
		selectFirst: true,
		
	});
	$('#pejabat').flushCache();
	
	$("#pejabat").result(function(event, data, formatted) {		
			$("#pejabat").val(data[0]);
			$("#id_pejabat").val(data[1]);
			// console.log(data);
			//$("#hidden_id_kldi").val(data[1]);
			
			
    });
	$('#instansi').flushCache();
		$("#instansi").autocomplete("<?php echo $url_load_instansi; ?>"  , {
		width: 400,
		autoFill: true,
		mustMatch: true,
		matchContains: true,
		scrollHeight: 220,
		autoFocus : true,
		selectFirst: true,
		
	});
	
	
	$("#instansi").result(function(event, data, formatted) {		
			$("#instansi").val(data[0]);
			$("#id_instansi").val(data[1]);
			// console.log(data);
			//$("#hidden_id_kldi").val(data[1]);
			
			
    });
        ng.formProcess($("#ajaxFormAddMutasiKeluar"), 'insert', location.href.split('#')[1]);
		/* load data pegawai*/
		
   })
</script>
<?php
if($form == 'addmutasi_keluar'){
?>
<div id="wrapperFormAdd">
	<form class="form-horizontal" method="POST" action="index.php/ereg/all_pn/savemutasi" id="ajaxFormAddMutasiKeluar">
		<div class="form-group ">
			<label class="col-sm-4 control-label">Nama Penyelenggara <span class="red-label">*</span>:</label>
			<div class="col-sm-5">
				<input required type="text" name="pejabat" id="pejabat" class="form-control">
				<input type="hidden" name="id_pejabat" id="id_pejabat">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Instansi Mutasi <span class="red-label">*</span>:</label>
			<div class="col-sm-5">
				<input required type="text" name="instansi" id="instansi" class="form-control">
				<input type="hidden" name="id_instansi" id="id_instansi">
			</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">Jabatan Mutasi <span class="red-label">*</span>:</label>
			<div class="col-sm-5">
				<input required type="text" name="jabatan" class="form-control" >
			</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">NO SK <span class="red-label">*</span>:</label>
			<div class="col-sm-5">
				<input required type="text" name="no_sk" class="form-control" >
			</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">TMT <span class="red-label">*</span>:</label>
			<div class="col-sm-5">
				<input required type="text" name="tmt" class="form-control datepicker" value="<?=date('d-m-Y')?>">
			</div>
		</div>
		<div class="form-group ">
			<label class="col-sm-4 control-label">S/D <span class="red-label">*</span>:</label>
			<div class="col-sm-5">
				<input required type="text" name="sd" class="form-control" >
			</div>
		</div>
		<div class="pull-right">
		<input type="hidden" name="act" value="doinsert">
	        <input type="submit" value="simpan" class="btn btn-sm btn-primary">
			<input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
	    </div>
	</form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });
    });
</script>
<?php }

if($form == 'editmutasi_keluar'){
//var_dump($sk_jab);
?>
<div id="wrapperFormAdd">
    <form method="POST" class="form-horizontal" action="index.php/ereg/all_pn/save_edit_mutasi" id="ajaxFormAddMutasiKeluar">
        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $this->muser->get_id_by_username($data_pn->NIK); ?>">
            <input type="hidden" name="USERNAME" id="USERNAME" value="<?php echo $data_pn->NIK; ?>">
            <input type="hidden" name="ID_INST_ASAL" id="ID_INST_ASAL" value="<?php echo $items->ID_INST_ASAL; ?>">
            <div class="form-group">
                <label class="col-sm-4 control-label">INSTANSI TUJUAN <span class="red-label">*</span>:</label>
                <div class="col-sm-8">
                    <select id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;">
                        <option value="">Tidak Pindah Instansi</option>
                        <?php
                        foreach ($instansis as $list) {
                            $selected = ($list->INST_SATKERKD == $items->ID_INST_TUJUAN) ? 'selected' : NULL;
                            ?>
                            <option value="<?php echo $list->INST_SATKERKD ?>" <?php echo $selected; ?>><?php echo $list->INST_NAMA;?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">JABATAN <span class="red-label">*</span>:</label>
                <div class="col-sm-8">
                    <select required id="JABATAN" name="JABATAN" class="select" style="width: 100%;">
                        <option value=""> -- Pilih Jabatan -- </option>
                        <?php
                        foreach ($jabatans as $list) {
                            $selected = ($list->ID_JABATAN == $items->ID_JABATAN) ? 'selected' : NULL;
                            ?>
                            <option value="<?php echo $list->ID_JABATAN ?>" <?php echo $selected; ?>><?php echo $list->NAMA_JABATAN;?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#JABATAN').select2();
            $('#INST_TUJUAN').select2();
            ng.formProcess($("#ajaxFormMutasi"), 'mutasi', location.href.split('#')[1]);
        });
    </script>
<?php }

if($form == 'deletemutasi_keluar'){
?>
<h5>Apakah Anda Yakin Untuk Menghapus Mutasi Ini ? </h5>
<form method="POST" action="index.php/ereg/all_pn/dodeletemutasi" id="ajaxFormAddMutasiKeluar">
	<div class="form-group col-sm-12">
		<label class="col-sm-4">Nama Pejabat</label>
		<div class="col-sm-8">
			<input type="text" name="pejabat" id="pejabat" disabled value="<?php echo $this->mmutasi->get_nm_pejabat($items->ID_PN); ?>" class="form-control">
			<input type="hidden" name="id_pejabat" id="id_pejabat" value="<?php echo $items->ID_PN; ?>" class="form-control">
		</div>
	</div>
	<div class="form-group col-sm-12">
		<label class="col-sm-4"></label>
		<label class="col-sm-4">Sebelum</label>
		<label class="col-sm-4">Tujuan</label>
	</div>
	<div class="form-group col-sm-12">
		<label class="col-sm-4">Instansi Mutasi </label>
		<div class="col-sm-4">
			<?php echo  $this->mjabatan->get_nama_jabatan($items->ID_JABATAN); ?>
		</div>
		<div class="col-sm-4">
			<?php echo  $this->mjabatan->get_nama_jabatan($items->ID_JABATAN_BARU); ?>
		</div>
	</div>
	<div class="form-group col-sm-12">
		<label class="col-sm-4">Jabatan Mutasi </label>
		<div class="col-sm-4">
			<?php echo  $this->mmutasi->get_nm_instansi($items->ID_INST_ASAL); ?>
		</div>
		<div class="col-sm-4">
			<?php echo  $this->mmutasi->get_nm_instansi($items->ID_INST_TUJUAN); ?>
		</div>
	</div>

	<div class="pull-right">
	<input type="hidden" name="act" value="dodelete">
	<input type="hidden" name="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>">
        <input type="submit" value="delete" class="btn btn-sm btn-primary">
		<input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</form>
<?php }
if($form=="approvmutasi"){
 ?>
    <style type="text/css">
        .form-select2 {
            padding: 6px 0px !important;
            margin: 0px !important;
        }
    </style>
 <div id="wrapperFormAdd">
 <form class="form-horizontal" method="POST" action="index.php/ereg/all_pn/saveapprove" id="ajaxFormAddMutasiKeluar">
     <input type="hidden" name="id_mutasi" id="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>" class="form-control">
     <div class="form-group ">
         <label class="col-sm-4">Nama Pejabat</label>
         <div class="col-sm-8">
             <?php echo $this->mmutasi->get_nm_pejabat($items->ID_PN); ?>
         </div>
         <input type="hidden" name="id_pejabat" id="id_pejabat" value="<?php echo $items->ID_PN; ?>" class="form-control">
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Instansi Asal</label>
         <div class="col-sm-8">
             <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_ASAL); ?>
         </div>
         <input type="hidden" name="id_instansi_asal" id="id_instansi_asal" value="<?php echo $items->ID_INST_ASAL; ?>" class="form-control">
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Instansi Mutasi</label>
         <div class="col-sm-8">
             <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_TUJUAN); ?>
         </div>
         <input type="hidden" name="id_instansi" id="id_instansi" value="<?php echo $items->ID_INST_TUJUAN; ?>" class="form-control">
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Jabatan Mutasi</label>
         <div class="col-sm-8">
             <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="<?php echo $items->ID_JABATAN_BARU; ?>" placeholder="Jabatan Mutasi" required>
        </div>
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Deskripsi Jabatan Mutasi</label>
         <div class="col-sm-8">
             <input type="text" class="form-control" name="DESKRIPSI_JABATAN" value="<?php echo $items->DESKRIPSI; ?>" placeholder="Deskripsi Jabatan Mutasi" required>
         </div>
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Eselon Mutasi</label>
         <div class="col-sm-8">
             <select class="form-control" name='ESELON' required placeholder="ESELON">
                 <option <?php echo ($items->ESELON_BARU == '1' ? 'selected="selected"' : '') ?> value='1'>I</option>
                 <option <?php echo ($items->ESELON_BARU == '2' ? 'selected="selected"' : '') ?> value='2'>II</option>
                 <option <?php echo ($items->ESELON_BARU == '3' ? 'selected="selected"' : '') ?> value='3'>III</option>
                 <option <?php echo ($items->ESELON_BARU == '4' ? 'selected="selected"' : '') ?> value='4'>IV</option>
                 <option <?php echo ($items->ESELON_BARU == '5' ? 'selected="selected"' : '') ?> value='5'>Non-Eselon</option>
             </select>
         </div>
     </div>
     <div class="pull-right">
     	 <input type="hidden" name="STATUS_AKHIR_JABAT" value="<?php echo $items->ID_STATUS_AKHIR_JABAT;?>">
     	 <input type="hidden" name="SD_MENJABAT" value="<?php echo date('Y-m-d');?>">
         <input type="hidden" name="act" value="doapprove">
         <input type="hidden" name="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>">
         <input type="submit" value="approve" class="btn btn-sm btn-primary">
         <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
     </div>
</form>
     </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getJabatan/'.$items->ID_INST_TUJUAN.'/')?>",
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
                    $.ajax("<?=base_url('index.php/share/reff/getJabatan/'.$items->ID_INST_TUJUAN)?>/"+id, {
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

    })
</script>
<?php }
if($form=="approvmutasi1"){
    ?>
    <div id="wrapperFormAdd">
        <form class="form-horizontal" method="POST" action="index.php/ereg/all_pn/saveapprove" id="ajaxFormAddMutasiKeluar">
            <input type="hidden" name="id_mutasi" id="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>" class="form-control">
            <div class="form-group ">
                <label class="col-sm-4">Nama Pejabat</label>
                <?php echo $this->mmutasi->get_nm_pejabat($items->ID_PN); ?>
                <input type="hidden" name="id_pejabat" id="id_pejabat" value="<?php echo $items->ID_PN; ?>" class="form-control">
            </div>
            <div class="form-group ">
                <label class="col-sm-4">Instansi Asal</label>
                <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_ASAL); ?>
                <input type="hidden" name="id_instansi_asal" id="id_instansi_asal" value="<?php echo $items->ID_INST_ASAL; ?>" class="form-control">
            </div>
            <div class="form-group ">
                <label class="col-sm-4">Instansi Mutasi</label>
                <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_TUJUAN); ?>
                <input type="hidden" name="id_instansi" id="id_instansi" value="<?php echo $items->ID_INST_TUJUAN; ?>" class="form-control">
            </div>
            <div class="form-group ">
                <label class="col-sm-4">Jabatan Mutasi</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="<?php echo $items->ID_JABATAN_BARU; ?>" placeholder="Jabatan Mutasi" required>
               </div>
            </div>
            <div class="form-group ">
                <label class="col-sm-4">Deskripsi Jabatan Mutasi</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="DESKRIPSI_JABATAN" value="<?php echo $items->DESKRIPSI; ?>" placeholder="Deskripsi Jabatan Mutasi" required>
                </div>
            </div>
            <div class="form-group ">
                <label class="col-sm-4">Eselon Mutasi</label>
                <div class="col-sm-8">
                    <select class="form-control" name='ESELON' required placeholder="ESELON">
                        <option value='1'>I</option>
                        <option value='2'>II</option>
                        <option value='3'>III</option>
                        <option value='4'>IV</option>
                        <option value='5'>Non-Eselon</option>
                    </select>
                </div>
            </div>
            <div class="pull-right">
                <input type="hidden" name="STATUS_AKHIR_JABAT" value="<?php echo $items->ID_STATUS_AKHIR_JABAT;?>">
                <input type="hidden" name="SD_MENJABAT" value="<?php echo date('Y-m-d');?>">
                <input type="hidden" name="act" value="doapprove">
                <input type="hidden" name="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>">
                <input type="submit" value="approve" class="btn btn-sm btn-primary">
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="backForm();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name="JABATAN"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getJabatan/'.$items->ID_INST_TUJUAN.'/')?>",
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
                        $.ajax("<?=base_url('index.php/share/reff/getJabatan/'.$items->ID_INST_TUJUAN)?>/"+id, {
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

        })
    </script>
<?php }
if($form=="tolakmutasi"){
 //var_dump($sk_jab);
 ?>

 <form method="POST" action="index.php/ereg/all_pn/savetolakmutasi" id="ajaxFormAddMutasiKeluar">
     <input type="hidden" name="id_mutasi" id="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>" class="form-control">
     <div class="form-group ">
         <label class="col-sm-4">Nama Pejabat</label>
         <?php echo $this->mmutasi->get_nm_pejabat($items->ID_PN); ?>
         <input type="hidden" name="id_pejabat" id="id_pejabat" value="<?php echo $items->ID_PN; ?>" class="form-control">
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Instansi Asal</label>
         <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_ASAL); ?>
         <input type="hidden" name="id_instansi_asal" id="id_instansi_asal" value="<?php echo $items->ID_INST_ASAL; ?>" class="form-control">
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Instansi Mutasi</label>
         <?php echo $this->mmutasi->get_nm_instansi($items->ID_INST_TUJUAN); ?>
         <input type="hidden" name="id_instansi" id="id_instansi" value="<?php echo $items->ID_INST_TUJUAN; ?>" class="form-control">
     </div>
     <div class="form-group ">
         <label class="col-sm-4">Jabatan Mutasi</label>
         <?php echo $this->mmutasi->get_nm_jabatan($items->ID_JABATAN_BARU); ?>
         <input type="hidden" name="id_jabatan" class="form-control" value="<?php echo $items->ID_JABATAN_BARU; ?>">
     </div>
     <div class="pull-right">
         <input type="hidden" name="act" value="dodelete">
         <input type="hidden" name="id_mutasi" value="<?php echo $items->ID_MUTASI; ?>">
         <input type="submit" value="<?= ($tipe == '1' ? 'Tolak Mutasi' : 'Batal Mutasi'); ?>" class="btn btn-sm btn-primary">
         <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
     </div>
</form>
<?php } ?>