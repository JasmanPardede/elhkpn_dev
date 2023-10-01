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
<style type="text/css">
    .form-select2 {
        padding: 6px 0px !important;
        margin: 0px !important;
    }
</style>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
	<form class="form-horizontal" method="post" id="ajaxFormAddhartabergerak" action="index.php/efill/lhkpn/savehartabergerak" enctype="multipart/form-data">
        <div class="con-body">
            <div class="box-body">
			<div class="col-md-6">
				<input class="form-control" name="ID_HARTA" id='ID_HARTA' placeholder="Id Harta" type="hidden">
				<input class="form-control" name="ID_HARTA" id='ID_HARTA_LAMA' placeholder="Id Harta Lama" type="hidden">
				<input class="form-control" name="ID_LHKPN" id='ID_LHKPN' placeholder="Id Lhkpn" type="hidden">
				<input class="form-control" name="GOLONGAN_HARTA" id='GOLONGAN_HARTA' placeholder="Golongan Harta" type="hidden">
				<div class="form-group">
					<label> Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_hbl'); ?>
					
						<select class="form-control" name="KODE_JENIS" id="KODE_JENIS" required>
							<option value=''></option>
							<?php
                                $ia = 0;
							    foreach ($harta as $key => $value) {
                                    $ia++;
							        ?>
							            <option value="<?=$key?>"><?=$value?></option>
							        <?php
							    }
							?>
						</select>
					
				</div>
				<div class="form-group">
					<label> Merek <span class="red-label">*</span> </label><?= FormHelpPopOver('merek_hb'); ?>
				
						<!--<input class="form-control" name="MEREK" id='MEREK' placeholder="Merek" type="text" required>-->
						<!-- <input class="form-control form-select2" name="MEREK" id='MEREK' placeholder="Merek" type="text"  style="border:0px;"> -->
					    <input class="form-control input_capital" name="MEREK" id='MEREK'  type="text" required>
				</div>
				<div class="form-group">
					<label> Model <span class="red-label">*</span> </label> <?= FormHelpPopOver('tipe_hb'); ?>
				
						<input class="form-control input_capital" name="MODEL" id='MODEL'  type="text" required>
					
				</div>
				<div class="form-group">
					<label > Tahun Pembuatan :</label> <?= FormHelpPopOver('thn_pembuatan_hb'); ?>
					
						<input class="form-control year-picker" name="TAHUN_PEMBUATAN" id='TAHUN_PEMBUATAN'  type="text">
					
				</div>
				<div class="form-group">
					<label> No. Pol / Registrasi <span class="red-label">*</span> :</label> <?= FormHelpPopOver('no_registrasi_hb'); ?>
					
						<input class="form-control input_capital" name="NOPOL_REGISTRASI" id='NOPOL_REGISTRASI'  type="text" required>
				
				</div>
				<div class="form-group">
					<label> Jenis Bukti <span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_bukti_hb'); ?>
					
						<select name="JENIS_BUKTI" id="JENIS_BUKTI" class="form-control" required>
							<option value=''></option>
                            <?php
                                $ia = 0;
                                foreach ($bukti as $key => $value) {
                                    $ia++;
                                    ?>
                                        <option value="<?=$key?>"><?=$value?></option>
                                    <?php
                                }
                            ?>
						 </select>
					
				</div>
				<!-- <div class="form-group">
					<label class="col-md-5 control-label"> Nomor Bukti <span class="red-label">*</span> :</label>
					<div class="col-md-7">
						<input class="form-control" name="NOMOR_BUKTI" id='NOMOR_BUKTI' placeholder="Nomor Bukti" type="text" required>
					</div>
				</div> -->

			 <div class="form-group">
                    <label>Atas Nama  <span class="red-label">*</span>:</label><?= FormHelpPopOver('atas_nama_htg'); ?>
                   
                        <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                            <option></option>
                            <option value="PN YANG BERSANGKUTAN">PN YANG BERSANGKUTAN</option>  
                            <option value="PASANGAN / ANAK">PASANGAN / ANAK</option>  
                            <option value="LAINNYA">LAINNYA</option>
                        </select>
                   
                </div>
                 </div>
              <div class="col-md-1"></div>
                <div class="col-md-5">
				<div class="form-group">
					<label > Asal Usul <span class="red-label">*</span> :</label> <?= FormHelpPopOver('asal_usul_harta_hb'); ?>
				
					<?php 
                    $ia = 0;
                    foreach($asalusuls as $asalusul){ 
                        $ia++;
                        ?>
						<div class="checkbox">
                            <div class="col-sm-5">
                                <label>
                                <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" class='asalusul' name='ASAL_USUL[]' value="<?php echo $asalusul->ID_ASAL_USUL?>" required>
                                <?php echo $ia.'. '.$asalusul->ASAL_USUL; ?>
                                </label>
                            </div>
						</div>
					<?php } ?>
					
				</div>
		
				<div class="form-group">
					<label > Pemanfaatan <span class="red-label">*</span> </label><?= FormHelpPopOver('pemanfaatan_hb'); ?>
					
						<select class="form-control" name="PEMANFAATAN" id="PEMANFAATAN" required>
						  <option value=''></option>
                                <?php 
                                $ia = 0;
                                foreach ($manfaat as $key => $value) {
                                $ia++;
                                 ?>
                                    <option value="<?=$key?>"><?=$value?></option>
                                <?php } ?>
                           
						</select>
				
				</div>
				<div class="form-group">
					<label >Ket Lainnya :</label> <?= FormHelpPopOver('keterangan_hb'); ?>
				
						<input class="form-control" name="KET_LAINNYA" id='KET_LAINNYA'  type="text">
					
				</div>	
				<!--<div class="form-group">
                    <label class="col-md-5 control-label"> Tahun Perolehan <span class="red-label">*</span> :</label>
                    <div class="col-md-3">
                        <input class="form-control dari" name="TAHUN_PEROLEHAN_AWAL" placeholder="Tahun Perolehan Awal" type="text" required>
                    </div>
                    <div class="col-md-1">
                        s/d
                    </div>
                    <div class=" col-md-3">
                        <input class="form-control ke" name="TAHUN_PEROLEHAN_AKHIR" placeholder="Tahun Perolehan Akhir" type="text">
                    </div>
                </div>-->		
				<div class="form-group">
					<label > Nilai Perolehan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_hb'); ?>
					<!--<div class="col-md-3">
						<select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANG" required>
                            <?php
                                foreach ($uang as $key => $value) {
                                    ?>
                                        <option <?php echo ($key == '1') ? 'selected' : ''; ?> value="<?=$key?>"><?=$value?></option>
                                    <?php
                                }
                            ?>
						</select>
					</div>-->
					
						<input class="form-control int" name="NILAI_PEROLEHAN" id='NILAI_PEROLEHAN'  type="text" required>
				
				</div>
				<div class="form-group">
				    <label > Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_hb'); ?>
				    <!--<div class="col-sm-7">
				        <div class='col-sm-6'>
				            <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="1" > Appraisal 
				        </div>
				        <div class='col-sm-6'>
				            <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="2" chacked> Perkiraan Pasar
				        </div>
				    </div>
				</div>-->
				<!--<div class="form-group">
				    <label class="col-sm-5 control-label">Rp. <span class="red-label">*</span></label>-->
				    
				        <input required type="text"  name='NILAI_PELAPORAN' id='NILAI_PELAPORAN' class="int form-control" >
				   
				</div>
			</div>
            </div>
            <div class="pull-right">
                <input type='hidden' readonly id="idLhkpn" name='ID_LHKPN' value="<?=@$id_lhkpn;?>">
                <input type="hidden" name="act" value="doinsert">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
        </div>
        <div id="pelepasan-con"></div>
    </form>

    <div id="hidden-pelepasan" style="display: none;">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <input type="hidden" class="" name="" id="dumptitle" style="" value="" placeholder="">
                <div class="form-group">
                    <label>Tanggal Transaksi </label>
                  
                        <input type="text"  name='TGL[]' class="form-control datepicker" placeholder="DD/MM/YYYY">
                  
                </div>

                <div class="form-group">
                    <label>Nilai(Rp) <span class="red-label">*</span> </label>
                   
                        <input type="text" name='NILAI[]' class="form-control int title2 required">
                  
                </div>

                <div class="form-group">
                    <label>Keterangan </label>
                   
                        <input type="text" name='KETERANGAN_PELEPASAN[]' class="form-control" placeholder="Keterangan">
                   
                </div>

                <div class="form-group">
                    <label>Pihak Kedua</label>
                </div>

                <div class="form-group">
                    <label>Nama <span class="red-label">*</span> </label>
                   
                        <input type="text" name='NAMA[]' class="form-control required" >
                    
                </div>

                <div class="form-group">
                    <label>Alamat </label>
                   
                        <textarea name='ALAMAT[]' class="form-control"></textarea>
                   
                </div>
            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="button" class="btn btn-sm btn-primary" id="btnSimpan" value="Simpan" onclick="f_close(this);">
            <input type="button" class="btn btn-sm btn-default btn-kembali" value="Kembali" onclick="f_kembali(this)">
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        function select2MerekKendaraan(jenis){
            next = false;
            if(jenis == 'mobil'){
                urlAjax = "<?=base_url('index.php/share/reff/getMerkMobil')?>/"
            }else if(jenis == 'motor'){
                urlAjax = "<?=base_url('index.php/share/reff/getMerkMotor')?>/"
            }else{
                urlAjax = "<?=base_url('index.php/share/reff/getMerkJenisKendaraan')?>/"
            }

            // $('#MEREK').select2({
            //     minimumInputLength: 0,
            //     ajax: {
            //         url: urlAjax,
            //         dataType: 'json',
            //         quietMillis: 250,
            //         data: function (term, page) {
            //             return {
            //                 q: term
            //             };
            //         },
            //         results: function (data, page) {
            //             return { results: data.item };
            //         },
            //         cache: true
            //     },
            //     initSelection: function(element, callback) {
            //         str = $(element).val();
            //         bfval = str.split("(");
            //         bfval = bfval[0].trim();
            //         var id = encodeURIComponent(bfval);
            //         if (id !== "") {
            //             $.ajax(urlAjax+id, {
            //                 dataType: "json"
            //             }).done(function(data) { callback(data[0]); }); 
            //         }
            //     },
            //     formatResult: function (state) {
            //         return state.name;
            //     },
            //     formatSelection:  function (state) {
            //         return state.name;
            //     }
            // });
        }
          $('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });
        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        $('#KODE_JENIS').change(function(e){
            if($(this).val()==2){
                select2MerekKendaraan('mobil');
            }else if($(this).val()==3){
                select2MerekKendaraan('motor');
            }
        });
        select2MerekKendaraan('other');

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false
        });
        $("#ajaxFormAddhartabergerak").submit(function(){
            $('#btnSimpan').prop('disabled', true);
            var url = $(this).attr('action');
            var data = $(this).serializeArray();
            $.post(url, data, function(res){
                 msg = {
                    success : 'Data Berhasil Disimpan!',
                    error : 'Data Gagal Disimpan!'
                 };
                 if (data == 0) {
                    alertify.error(msg.error);
                 } else {
                    alertify.success(msg.success);
                 }
                 CloseModalBox();
                 var ID = $('#idLhkpn').val();
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/5/'+ID + '/edit', block:'#block', container:$('#hartabergerak').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
            })
            return false;
        })
        // var ID
		// var ID = $('#idLhkpn').val();
  //       ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/5/'+ID, block:'#block', container:$('#hartabergerak').find('.contentTab')});
    });

    function f_pelepasan(ele)
    {
        var val = $(ele).val();
        if($(ele).is(':checked')){
            $(ele).closest('.checkbox').append('<div class="col-sm-2"><a class="btn btn-xs btn-primary" href="javascript:;" onClick="f_show('+val+')">Info</a></div><div class="col-sm-5 text-right"><strong>Rp. 0</strong></div>');
            var html = $('#hidden-pelepasan').html();
            var text = $(ele).closest('label').text();
            var ttle = $('.modal-title').html();
            $('.modal-title').html('Asal Usul '+text);
            $('#pelepasan-con').append('<div class="pelepasan" data-id="'+val+'" style="display: none;">'+html+'</div>');
            $('div[data-id="'+val+'"] .datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('div[data-id="'+val+'"] .int').inputmask("integer", {
                'groupSeparator' : '.',
                'autoGroup': true,
                'removeMaskOnSubmit': false
            });

            $('div[data-id="'+val+'"] .title').text(text);
            var text2 = 'Nilai '+text.split(' ').join('');
            $('.title2').attr('placeholder', text2);

            $('.con-body').slideUp('slow', function(){
                $('#dumptitle').attr('value',ttle);
                $('.modal-dialog').animate({
                    width: '-=500'
                })
                $('div[data-id="'+val+'"]').slideDown('slow');
            });
        }else{
            $('div[data-id="'+val+'"]').remove();
            var e = $(ele).closest('.checkbox');
            $('a', e).remove();
            $('div:gt(0)', e).remove();
        }
    }

    function f_close(ele)
    {
        var jdul = $('#dumptitle').val();
        if(valid_pelepasan(ele)) {
            $(ele).closest('.pelepasan').slideUp('slow', function () {
                $('.modal-title').html(jdul);
                $('.modal-dialog').animate({
                    width: '+=500'
                });
                $('.con-body').slideDown('slow');
            });

            var element = $(ele).closest('.pelepasan');
            var val = $('input[name="NILAI[]"]', element).val();
            var data_id = $(ele).closest('.pelepasan').attr('data-id');

            var element = $('.checkbox input[value="' + data_id + '"]').closest('.checkbox');
            if (val == '') {
                val = '0';
            }
            $('div:eq(2)', element).children('strong').text('Rp. ' + val);
        }
    }

    function f_kembali(ele, stat){
        var jdul = $('#dumptitle').val();
        var val = $(ele).closest('.pelepasan').attr('data-id');
        $(ele).closest('.pelepasan').slideUp('slow', function () {
            $('.modal-title').html(jdul);
            $('.modal-dialog').animate({
                width: '+=500'
            });
            $('.con-body').slideDown('slow');
            if(stat != '1') {
                var e = $('form input[name="ASAL_USUL[]"][value="' + val + '"]');
                e.prop('checked', false);
                var e = e.closest('.checkbox');
                $('div:gt(0)', e).remove();
                $(ele).closest('.pelepasan').remove();
            }
        });
    }

    function f_show(val)
    {
        if(val == '2'){
            $('.modal-title').html('Asal Usul Warisan');
        }else if(val == '2'){
            $('.modal-title').html('Asal Usul Hibah');
        }else{
            $('.modal-title').html('Asal Usul Hadiah');
        }

        $('.con-body').slideUp('slow', function(){
            $('.modal-dialog').animate({
                width: '-=500'
            })
            var ele = $('div[data-id="'+val+'"]');
            ele.slideDown('slow');
            $('.btn-kembali', ele).attr('onclick', 'f_kembali(this, 1)')
        });
    }
</script>
<?php
}
?>
<?php
if($form=='edit'){ 
	// display($item);
?>
<div id="wrapperFormEdit">
	<form class="form-horizontal" method="post" id="ajaxFormEdithartabergerak" action="index.php/efill/lhkpn/savehartabergerak" enctype="multipart/form-data">
        <div class="con-body">
            <div class="box-body">
                <div class="col-md-6">
                    <input class="form-control" name="ID" id='ID' placeholder="Id Harta" type="hidden" value='<?php echo $item->ID;?>'>
                    <input class="form-control" name="ID_HARTA" id='ID_HARTA_LAMA' placeholder="Id Harta Lama" type="hidden" value='<?php echo $item->ID_HARTA;?>'>
                    <input class="form-control" name="ID_LHKPN" id='idLhkpn' placeholder="Id Lhkpn" type="hidden" value='<?php echo $item->ID_LHKPN;?>'>
                    <input class="form-control" name="GOLONGAN_HARTA" id='GOLONGAN_HARTA' placeholder="Golongan Harta" type="hidden" value='<?php echo $item->GOLONGAN_HARTA;?>'>
                    <div class="form-group">
                        <label> Jenis </label><?= FormHelpPopOver('jenis_hb'); ?>
                       
                            <select class="form-control" name="KODE_JENIS" id="KODE_JENIS">
                                <option value=''></option>
                                <?php
                                    $ia = 0 ;
                                    foreach ($harta as $key => $value) {
                                        $ia++;
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->KODE_JENIS){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                       
                    </div>
                    <div class="form-group">
                        <label > Merek </label> <?= FormHelpPopOver('merek_hb'); ?>
                       
                            <input class="form-control input_capital" name="MEREK" id='MEREK' placeholder="Merek" type="text" value='<?php echo $item->MEREK;?>'>
                       
                    </div>
                    <div class="form-group">
                        <label> Model </label><?= FormHelpPopOver('tipe_hb'); ?>
                       
                            <input class="form-control input_capital" name="MODEL" id='MODEL' placeholder="Model" type="text" value='<?php echo $item->MODEL;?>'>
                       
                    </div>
                    <div class="form-group">
                        <label> Tahun Pembuatan </label> <?= FormHelpPopOver('thn_pembuatan_hb'); ?>
                      
                            <input class="form-control year-picker" name="TAHUN_PEMBUATAN" id='TAHUN_PEMBUATAN' placeholder="Tahun Pembuatan" type="text" value='<?php echo $item->TAHUN_PEMBUATAN;?>'>
                      
                    </div>
                    <div class="form-group">
                        <label> Nopol Registrasi </label> <?= FormHelpPopOver('no_registrasi_hb'); ?>
                       
                            <input class="form-control" name="NOPOL_REGISTRASI" id='NOPOL_REGISTRASI' placeholder="Nopol Registrasi" type="text" value='<?php echo $item->NOPOL_REGISTRASI;?>'>
                       
                    </div>
                    <div class="form-group">
                        <label> Jenis Bukti </label> <?= FormHelpPopOver('jenis_bukti_hb'); ?>
                       
                            <select name="JENIS_BUKTI" id="JENIS_BUKTI" class="form-control">
                                <option value=''></option>
                                <?php
                                    $ia = 0 ;
                                    foreach ($bukti as $key => $value) {
                                        $ia++;
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->JENIS_BUKTI){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                             </select>
                     
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-5 control-label"> Nomor Bukti :</label>
                        <div class="col-md-7">
                            <input class="form-control" name="NOMOR_BUKTI" id='NOMOR_BUKTI' placeholder="Nomor Bukti" type="text" value='<?php echo $item->NOMOR_BUKTI;?>'>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label > Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_hb'); ?>
                            <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                            <option></option>
                                <option <?php echo ($item->ATAS_NAMA == 'PN YANG BERSANGKUTAN' ? 'selected="selected"' : '') ?> value="PN YANG BERSANGKUTAN">PN YANG BERSANGKUTAN</option>
                                <option <?php echo ($item->ATAS_NAMA == 'PASANGAN / ANAK' ? 'selected="selected"' : '') ?> value="PASANGAN / ANAK">PASANGAN / ANAK</option>
                                <option <?php echo ($item->ATAS_NAMA == 'LAINNYA' ? 'selected="selected"' : '') ?> value="LAINNYA">LAINNYA</option>
                               
                            </select>
                     
                    </div>
                      </div>
                 <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label> Asal Usul </label><?= FormHelpPopOver('asal_usul_harta_hb'); ?>
                       
                        <?php
                        $asalusulselected = explode(',',$item->ASAL_USUL);
                        $i = 0;
                        foreach($asalusuls as $asalusul){ ?>
                            <div class="checkbox">
                                <div class="col-sm-5">
                                    <label>
                                        <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" name='ASAL_USUL[]' value="<?php echo $asalusul->ID_ASAL_USUL?>" <?php echo in_array($asalusul->ID_ASAL_USUL, $asalusulselected)?'checked':''; ?>>
                                        <?php echo $asalusul->ASAL_USUL; ?>
                                    </label>
                                </div>
                                <?php if(in_array($asalusul->ID_ASAL_USUL, $asalusulselected) && $asalusul->IS_OTHER === '1'){ ?>
                                    <div class="col-sm-2">
                                        <a class="btn btn-primary btn-xs" href="javascript:;" onClick="f_show(<?php echo $asalusul->ID_ASAL_USUL ?>)">Info</a>
                                    </div>
                                    <div class="col-sm-5 text-right">
                                        <strong>Rp. <?php echo number_format($pelaporan[$i]->NILAI_PELEPASAN, 0, '.', '.') ?></strong>
                                    </div>
                                    <?php $i++;} ?>
                            </div>
                        <?php } ?>
                       
                    </div>
              
                    <div class="form-group">
                        <label> Pemanfaatan </label> <?= FormHelpPopOver('pemanfaatan_hb'); ?>
                       
                            <select class="form-control" name="PEMANFAATAN" id="PEMANFAATAN">
                                <option value=''></option>
                                <?php 
                                    $ia = 0;
                                    foreach ($manfaat as $key => $value) { 
                                        $ia++;
                                    ?>
                                     <option value="<?=$key?>" <?php if($key == $item->PEMANFAATAN){ echo 'selected';} ?> ><?=$value?></option>
                                    <!-- <input type="checkbox" name="PEMANFAATAN[]" value="<?=$key?>" <?php echo in_array($key, $pemanfaatanselected)?'checked':''; ?>> -->
                                <?php } ?>
                              <!--  -->
                            </select>
                        
                    </div>
                  <!--   <div class="form-group">
                        <label >Ket Lainnya </label>
                       
                            <input class="form-control" name="KET_LAINNYA" id='KET_LAINNYA' placeholder="Ket Lainnya" type="text" value='<?php echo $item->KET_LAINNYA;?>'>
                      
                    </div> -->
                   <!--  <div class="form-group">
                        <label > Tahun Perolehan </label>
                       
                            <input class="form-control dari" name="TAHUN_PEROLEHAN_AWAL" placeholder="Tahun Perolehan Awal" type="text" value='<?php echo $item->TAHUN_PEROLEHAN_AWAL;?>' required>
                       
                            s/d
                      
                            <input class="form-control ke" name="TAHUN_PEROLEHAN_AKHIR" placeholder="Tahun Perolehan Akhir" type="text" value='<?php echo $item->TAHUN_PEROLEHAN_AKHIR;?>'>
                       
                    </div> -->
                    <div class="form-group">
                        <label> Nilai Perolehan (Rp)<span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_hb'); ?>
                      
                          <!--   <select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANG1" required>
                                <?php
                                    foreach ($uang as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->MATA_UANG){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select> -->
                      
                    
                            <input  required class="form-control int" name="NILAI_PEROLEHAN" id='NILAI_PEROLEHAN' placeholder="Nilai Perolehan" type="text" value='<?php echo number_format($item->NILAI_PEROLEHAN, 0,'','.');?>'>
                      
                    </div>
                    <div class="form-group">
                        <label> Nilai Estimasi Saat Pelaporan (Rp)<span class="red-label">*</span> </label><?= FormHelpPopOver('nilai_estimasi_pelaporan_hb'); ?>
                       
                              <!--   <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' <?php echo $item->JENIS_NILAI_PELAPORAN == '1' ? 'checked' : '' ;?> value="1" > Appraisal
                          
                                <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' <?php echo $item->JENIS_NILAI_PELAPORAN == '2' ? 'checked':'';?> value="2" > Perkiraan Pasar
                           
                    </div>

                    <div class="form-group">
                        <label>Rp. </label> -->
                     
                            <input type="text"  name='NILAI_PELAPORAN' id='NILAI_PELAPORAN' class="int form-control" value="<?php echo number_format($item->NILAI_PELAPORAN, 0,'','.');?>" placeholder="Nilai Pelaporan">
                     
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input type="hidden" name="act" value="doupdate">
                <button type="submit" class="btn btn-sm btn-primary" disabled>Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
        </div>
        <div id="pelepasan-con">
            <?php foreach($pelaporan as $row): ?>
                <div class="pelepasan" data-id="<?php echo $row->ID_ASAL_USUL; ?>" style="display: none;">
                    <div class="box-body form-horizontal">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Tanggal Transaksi :</label>
                                <div class="col-sm-4">
                                    <input value="<?php echo date('d/m/Y', strtotime($row->TANGGAL_TRANSAKSI)) ?>" type="text"  name='TGL[]' class="form-control datepicker" placeholder="DD/MM/YYYY">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nilai <?php echo $row->ASAL_USUL; ?> <span class="red-label">*</span> :</label>
                                <div class="col-sm-1"><p class="form-control-static">Rp</p></div>
                                <div class="col-sm-4">
                                    <input value="<?php echo $row->NILAI_PELEPASAN ?>" type="text" name='NILAI[]' class="required form-control int" placeholder="Nilai <?php echo $row->ASAL_USUL; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Keterangan :</label>
                                <div class="col-sm-5">
                                    <input value="<?php echo $row->URAIAN_HARTA ?>" type="text" name='KETERANGAN_PELEPASAN[]' class="form-control" placeholder="Keterangan">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label" style="font-size: 15px;">Pihak Kedua</label>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Nama <span class="red-label">*</span> :</label>
                                <div class="col-sm-5">
                                    <input value="<?php echo $row->NAMA ?>" type="text" name='NAMA[]' class="required form-control" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Alamat :</label>
                                <div class="col-sm-5">
                                    <textarea name='ALAMAT[]' class="form-control" ><?php echo $row->ALAMAT ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="pull-right">
                        <input type="button" class="btn btn-sm btn-primary" value="Simpan" onclick="f_close(this);">
                        <input type="button" class="btn btn-sm btn-default btn-kembali" value="Kembali" onclick="f_kembali(this)">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </form>

    <div id="hidden-pelepasan" style="display: none;">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <input type="hidden" class="" name="" id="dumptitle" style="" value="" placeholder="">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Transaksi  <span class="red-label">*</span> :</label>
                    <div class="col-sm-4">
                        <input type="text"  name='TGL[]' class="form-control datepicker" placeholder="DD/MM/YYYY">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai <span class="title"></span> <span class="red-label">*</span> :</label>
                    <div class="col-sm-1"><p class="form-control-static">Rp</p></div>
                    <div class="col-sm-4">
                        <input type="text" name='NILAI[]' class="required form-control int title2">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Keterangan <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input type="text" name='KETERANGAN_PELEPASAN[]' class="form-control" placeholder="Keterangan">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label" style="font-size: 15px;">Pihak Kedua</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <input type="text" name='NAMA[]' class="required form-control" placeholder="Nama">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Alamat <span class="red-label">*</span> :</label>
                    <div class="col-sm-5">
                        <textarea name='ALAMAT[]' class="form-control" placeholder="Alamat"></textarea>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="button" class="btn btn-sm btn-primary" value="Simpan" onclick="f_close(this);">
            <input type="button" class="btn btn-sm btn-default btn-kembali" value="Kembali" onclick="f_kembali(this)">
        </div>
    </div>
</div>
<script type="text/javascript">
    function f_pelepasan(ele)
    {
        var val = $(ele).val();
        if($(ele).is(':checked')){
            $(ele).closest('.checkbox').append('<div class="col-sm-2"><a class="btn btn-xs btn-primary" href="javascript:;" onClick="f_show('+val+')">Info</a></div><div class="col-sm-5 text-right"><strong>Rp. 0</strong></div>');
            var html = $('#hidden-pelepasan').html();
            var text = $(ele).closest('label').text();
            var ttle = $('.modal-title').html();
            $('.modal-title').html('Asal Usul '+text);
            $('#pelepasan-con').append('<div class="pelepasan" data-id="'+val+'" style="display: none;">'+html+'</div>');
            $('div[data-id="'+val+'"] .datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('div[data-id="'+val+'"] .int').inputmask("integer", {
                'groupSeparator' : '.',
                'autoGroup': true,
                'removeMaskOnSubmit': false
            });

            $('div[data-id="'+val+'"] .title').text(text);
            var text2 = 'Nilai '+text.split(' ').join('');
            $('.title2').attr('placeholder', text2);

            $('.con-body').slideUp('slow', function(){
                $('#dumptitle').attr('value',ttle);
                $('.modal-dialog').animate({
                    width: '-=500'
                });
                $('div[data-id="'+val+'"]').slideDown('slow');
            });
        }else{
            $('div[data-id="'+val+'"]').remove();
            var e = $(ele).closest('.checkbox');
            $('div:gt(0)', e).remove();
        }
    }

    function f_close(ele)
    {
        var jdul = $('#dumptitle').val();
        if(valid_pelepasan(ele)) {
            $(ele).closest('.pelepasan').slideUp('slow', function () {
                $('.modal-title').html(jdul);
                $('.modal-dialog').animate({
                    width: '+=500'
                });
                $('.con-body').slideDown('slow');
            });

            var element = $(ele).closest('.pelepasan');
            var val = $('input[name="NILAI[]"]', element).val();
            var data_id = $(ele).closest('.pelepasan').attr('data-id');

            var element = $('.checkbox input[value="' + data_id + '"]').closest('.checkbox');
            if (val == '') {
                val = '0';
            }
            $('div:eq(2)', element).children('strong').text('Rp. ' + val);
        }
    }

    function f_kembali(ele, stat){
        var jdul = $('#dumptitle').val();
        var val = $(ele).closest('.pelepasan').attr('data-id');
        $(ele).closest('.pelepasan').slideUp('slow', function () {
            $('.modal-title').html(jdul);
            $('.modal-dialog').animate({
                width: '+=500'
            });
            $('.con-body').slideDown('slow');
            if(stat != '1') {
                var e = $('form input[name="ASAL_USUL[]"][value="' + val + '"]');
                e.prop('checked', false);
                var e = e.closest('.checkbox');
                $('div:gt(0)', e).remove();
                $(ele).closest('.pelepasan').remove();
            }
        });
    }

    function f_show(val)
    {
        if(val == '2'){
            $('.modal-title').html('Asal Usul Warisan');
        }else if(val == '2'){
            $('.modal-title').html('Asal Usul Hibah');
        }else{
            $('.modal-title').html('Asal Usul Hadiah');
        }

        $('.con-body').slideUp('slow', function(){
            $('.modal-dialog').animate({
                width: '-=500'
            })
            var ele = $('div[data-id="'+val+'"]');
            ele.slideDown('slow');
            $('.btn-kembali', ele).attr('onclick', 'f_kembali(this, 1)')
        });
    }

    $(document).ready(function() {
        $('#ajaxFormEdithartabergerak :input').change(function () {
            $('#ajaxFormEdithartabergerak button[type="submit"]').prop('disabled', false);
        })

        function select2MerekKendaraan(jenis){
            next = false;
            if(jenis == 'mobil'){
                urlAjax = "<?=base_url('index.php/share/reff/getMerkMobil')?>/"
            }else if(jenis == 'motor'){
                urlAjax = "<?=base_url('index.php/share/reff/getMerkMotor')?>/"
            }else{
                urlAjax = "<?=base_url('index.php/share/reff/getMerkJenisKendaraan')?>/"
            }

            // $('#MEREK').select2({
            //     minimumInputLength: 0,
            //     ajax: {
            //         url: urlAjax,
            //         dataType: 'json',
            //         quietMillis: 250,
            //         data: function (term, page) {
            //             return {
            //                 q: term
            //             };
            //         },
            //         results: function (data, page) {
            //             return { results: data.item };
            //         },
            //         cache: true
            //     },
            //     initSelection: function(element, callback) {
            //         str = $(element).val();
            //         bfval = str.split("(");
            //         bfval = bfval[0].trim();
            //         var id = encodeURIComponent(bfval);
            //         if (id !== "") {
            //             $.ajax(urlAjax+id, {
            //                 dataType: "json"
            //             }).done(function(data) { callback(data[0]); }); 
            //         }
            //     },
            //     formatResult: function (state) {
            //         return state.name;
            //     },
            //     formatSelection:  function (state) {
            //         return state.name;
            //     }
            // });
        }

        $('#KODE_JENIS').change(function(e){
            if($(this).val()==2){
                select2MerekKendaraan('mobil');
            }else if($(this).val()==3){
                select2MerekKendaraan('motor');
            }
        });
        if($('#KODE_JENIS').val()==2){
            select2MerekKendaraan('mobil');
        }else if($('#KODE_JENIS').val()==3){
            select2MerekKendaraan('motor');
        }

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })
          $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
             $('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false
        });

        $("#ajaxFormEdithartabergerak").submit(function(){
            var url = $(this).attr('action');
            var data = $(this).serializeArray();
            $.post(url, data, function(res){
                 msg = {
                    success : 'Data Berhasil Disimpan!',
                    error : 'Data Gagal Disimpan!'
                 };
                 if (data == 0) {
                    alertify.error(msg.error);
                 } else {
                    alertify.success(msg.success);
                 }
                 CloseModalBox();
                 var ID = $('#idLhkpn').val();
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/5/'+ID + '/edit', block:'#block', container:$('#hartabergerak').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
            })
            return false;
        })

		// var ID = $('#idLhkpn').val();
  //       ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/5/'+ID, block:'#block', container:$('#hartabergerak').find('.contentTab')});          
    
        // $.post("index.php/efill/lhkpn/daftar_uang", function(html){
        //     $.each(html, function(index, value){
        //         select = '<?=@$item->MATA_UANG?>';
        //         if (index == select)
        //         {
        //             $("#UANG1").append("<option value='"+index+"' selected>"+value+"</option>");
        //             kabkotedit();
        //         }else{
        //             $("#UANG1").append("<option value='"+index+"'>"+value+"</option>");
        //         };
                
        //     });
            $("#UANG1").select2();
        // }, 'json'); 
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
	Benarkah Akan Menghapus Harta Bergerak dibawah ini ?
	<form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/savehartabergerak">
		<!-- <!-- <div class="box-body">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-5 control-label">Kode Jenis :</label>
					<div class="col-md-7">
						<p class="form-control-static"><?php 
                        if($item->KODE_JENIS == '2'){ echo 'Mobil';}
                        elseif($item->KODE_JENIS == '3'){ echo 'Motor' ;}
                        elseif($item->KODE_JENIS == '4'){ echo 'Kapal Laut';}
                        elseif($item->KODE_JENIS == '5'){ echo 'Perahu';}
                        elseif($item->KODE_JENIS == '6'){ echo 'Pesawat Terbang';}
                        elseif($item->KODE_JENIS == '7'){ echo 'Lainnya';}?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">Merek :</label>
					<div class="col-md-7">
						<p class="form-control-static"><?php echo $item->MEREK;?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">Model :</label>
					<div class="col-md-7">
						<p class="form-control-static"><?php echo $item->MODEL;?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">Tahun Pembuatan :</label>
					<div class="col-md-7">
						<p class="form-control-static"><?php echo $item->TAHUN_PEMBUATAN;?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">Nopol Registrasi :</label>
					<div class="col-md-7">
						<p class="form-control-static"><?php echo $item->NOPOL_REGISTRASI;?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-5 control-label">Jenis Bukti :</label>
					<div class="col-md-7">
						<p class="form-control-static"><?php if($item->JENIS_BUKTI == '6'){ echo 'BPKB';}elseif($item->JENIS_BUKTI == '7'){ echo 'STNK' ;}elseif($item->JENIS_BUKTI == '8'){ echo 'Lainnya';}?></p>
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="col-md-5 control-label">Nomor Bukti :</label>
					<div class="col-md-7">
						<p class="form-control-static"><?php echo $item->NOMOR_BUKTI;?></p>
					</div>
				</div> -->
			
		
        <div class="pull-right">
			<input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" id="idLhkpn" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-md btn-danger">Hapus</button>
            <input type="reset" class="btn btn-md btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
		var ID = $('#idLhkpn').val();
        // ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/5/'+ID, block:'#block', container:$('#hartabergerak').find('.contentTab')});

        $("#ajaxFormDelete").submit(function(){
            var url = $(this).attr('action');
            var data = $(this).serializeArray();
            $.post(url, data, function(res){
                 msg = {
                    success : 'Data Berhasil Dihapus!',
                    error : 'Data Gagal Dihapus!'
                 };
                 if (data == 0) {
                    alertify.error(msg.error);
                 } else {
                    alertify.success(msg.success);
                 }
                 CloseModalBox();
                 var ID = $('#idLhkpn').val();
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/5/'+ID + '/edit', block:'#block', container:$('#hartabergerak').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
            })
            return false;
        });

    });
</script>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail"  class="form-horizontal">
    <div class="box-body">
		<div class="col-md-6">
			<div class="form-group">
				<label class="col-md-5 control-label">Kode Jenis :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php if($item->KODE_JENIS == '1'){ echo 'Mobil';}elseif($item->KODE_JENIS == '2'){ echo 'Motor' ;}elseif($item->KODE_JENIS == '3'){ echo 'Kapal Laut';}elseif($item->KODE_JENIS == '4'){ echo 'Perahu';}elseif($item->KODE_JENIS == '5'){ echo 'Pesawat Terbang';}elseif($item->KODE_JENIS == '6'){ echo 'Lainnya';}?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Merek :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php echo $item->MEREK;?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Model :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php echo $item->MODEL;?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Tahun Pembuatan :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php echo $item->TAHUN_PEMBUATAN;?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Nopol Registrasi :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php echo $item->NOPOL_REGISTRASI;?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Jenis Bukti :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php if($item->JENIS_BUKTI == '1'){ echo 'BPKB';}elseif($item->JENIS_BUKTI == '2'){ echo 'STNK' ;}elseif($item->JENIS_BUKTI == '3'){ echo 'Lainnya';}?></p>
				</div>
			</div>
			<!-- <div class="form-group">
				<label class="col-md-5 control-label">Nomor Bukti :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php echo $item->NOMOR_BUKTI;?></p>
				</div>
			</div> -->
			<div class="form-group">
				<label class="col-md-5 control-label">Atas Nama :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php echo $item->NAMA;?></p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="col-md-5 control-label">Asal Usul :</label>
				<div class="col-md-7">
				
					<p class="form-control-static"><?php if($item->ASAL_USUL == '1'){ echo 'Hasil Sendiri';}elseif($item->ASAL_USUL == '2'){ echo 'Warisan' ;}elseif($item->ASAL_USUL == '3'){ echo 'Hibah';}else{echo 'Hadiah' ;}?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Pemanfaatan :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php if($item->PEMANFAATAN == '1'){ echo 'Digunakan Sendiri';}elseif($item->PEMANFAATAN == '2'){ echo 'Tidak digunakan sendiri & menghasilkan' ;}elseif($item->PEMANFAATAN == '3'){ echo 'Tidak digunakan sendiri & tidak menghasilkan';}elseif($item->PEMANFAATAN == '4'){ echo 'Lainnya';}?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Ket Lainnya :</label>
				<div class="col-md-7">
					<p class="form-control-static"><?php echo $item->KET_LAINNYA;?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Tahun Perolehan :</label>
				<div class="col-md-3" style="text-align:left !important;">
					<label><?php echo $item->TAHUN_PEROLEHAN_AWAL;?></label>
				</div>
				<div class="col-md-1">
					s/d
				</div>
				<div class=" col-md-3" style="text-align:left !important;">
					<label><?php echo $item->TAHUN_PEROLEHAN_AKHIR;?></label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Nilai Perolehan : </label>
				<div class="col-md-3">
					<p class="form-control-static"><?php if($item->MATA_UANG == '1'){ echo 'IDR';}elseif($item->MATA_UANG == '2'){ echo 'USD' ;}elseif($item->PEMANFAATAN == '3'){ echo 'Yen';}?></p>
				</div>
				<div class="col-md-4" align="right">
					<p class="form-control-static"><?php echo number_format($item->NILAI_PEROLEHAN, 0,'','.');?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Nilai Pelaporan : </label>
				<div class="col-md-7" align="right">
					<p class="form-control-static"><?php echo ($item->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : 'Perkiraan Pasar') ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label"></label>
				<div class="col-md-7" align="right">
					<p class="form-control-static"><?php echo number_format($item->NILAI_PELAPORAN, 0,'','.'); ?></p>
				</div>
			</div>
		</div>
	</div>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
if($form == 'pelepasan'){
?>
    <div id="wrapperFormpelepasanhartabergerak">
        <form class='form-horizontal' method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savehartabergerak" enctype="multipart/form-data">
            <div class="box-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Jenis Pelepasan <span class="red-label">*</span> :</label>
                        <div class="col-sm-4">
                            <select required name='JENIS' class="form-control" placeholder="Jenis Pelepasan">
                                <option value="">Jenis Pelepasan</option>
                                <option <?php echo (@$data->JENIS_PELEPASAN_HARTA == '1' ? 'selected="selected"' : '') ?> value="1">Penjualan</option>
                                <option <?php echo (@$data->JENIS_PELEPASAN_HARTA == '2' ? 'selected="selected"' : '') ?> value="2">Pelepasan Hibah</option>
                                <option <?php echo (@$data->JENIS_PELEPASAN_HARTA == '3' ? 'selected="selected"' : '') ?> value="3">Pelepasan Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tanggal Transaksi  <span class="red-label">*</span> :</label>
                        <div class="col-sm-4">
                            <input  value="<?php echo ( @$data->TANGGAL_TRANSAKSI != '' ? date('d/m/Y', strtotime(@$data->TANGGAL_TRANSAKSI)) : '' );?>"required type="text"  name='TGL' class="form-control datepicker" placeholder="DD/MM/YYYY">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nilai Pelepasan <span class="red-label">*</span> :</label>
                        <div class="col-sm-1"><p class="form-control-static">Rp</p></div>
                        <div class="col-sm-4">
                            <input value="<?= ($do == 'update' ? $data->NILAI_PELEPASAN : $nipor->NILAI_PELAPORAN); ?>" required type="text" name='NILAI' class="form-control int" placeholder="Nilai Pelepasan">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" style="font-size: 15px;">Pihak Kedua</label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nama <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <input value="<?php echo @$data->NAMA;?>" required type="text" name='NAMA' class="form-control" placeholder="Nama">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Alamat <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <textarea required name='ALAMAT' class="form-control" placeholder="Alamat"><?php echo @$data->ALAMAT;?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Keterangan :</label>
                        <div class="col-sm-5">
                            <input value="<?php echo @$data->URAIAN_HARTA;?>" type="text" name='KETERANGAN' class="form-control" placeholder="Keterangan">
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="pull-right">
                <input type="hidden" name="act" value="dopelepasan">
                <input type='hidden' name='ID_HARTA' value="<?=$id;?>">
                <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?=@$id_lhkpn;?>">
                <input type="hidden" id="idLhkpn" name="id_lhkpn" value="<?=$id_lhkpn?>">
                <input type="hidden" name="type" value="<?=$do;?>">
                <?php
                    if($do == 'update'){
                        echo '<input type="hidden" name="ID" value="'.$data->ID.'">';
                        ?>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/deletepelepasanhartabergerak/<?=$id?>/<?php echo $data->ID; ?>" title="Delete">Hapus</button>
                        <?php
                    }
                ?>

                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            // var ID = $('#idLhkpn').val();
            // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/5/'+ID, block:'#block', container:$('#hartabergerak').find('.contentTab')});
        	$("#ajaxFormAdd").submit(function(){
        	    var url = $(this).attr('action');
        	    var data = $(this).serializeArray();
        	    $.post(url, data, function(res){
                    msg = {
                        success : 'Data Berhasil Disimpan!',
                        error : 'Data Gagal Disimpan!'
                    };
                    if (res == 0) {
                        alertify.error(msg.error);
                    } else {
                        alertify.success(msg.success);
                    }
        	        CloseModalBox();
        	        var ID = $('#idLhkpn').val();
        	        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/5/'+ID + '/edit', block:'#block', container:$('#hartabergerak').find('.contentTab')});
        	     	ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
        	    })
        	    return false;
        	})

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
        $("#wrapperFormpelepasanhartabergerak .btn-delete").click(function (e) {
            urls = $(this).attr('href');
            var data = $(this).serializeArray();
            $.post(urls, function(res) {
                msg = {
                    success : 'Data Berhasil Disimpan!',
                    error : 'Data Gagal Disimpan!'
                };
                if (res == 0) {
                    alertify.error(msg.error);
                } else {
                    alertify.success(msg.success);
                }
                CloseModalBox();
                var ID = $('#idLhkpn').val();
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/5/'+ID + '/edit', block:'#block', container:$('#hartabergerak').find('.contentTab')});
            	ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
            });

            return false;
        });
    </script>
<?php } ?>

<?php
if($form=='perbandingan'){
?>
<div id="wrapperFormDetail" class='form-horizontal'>

    <div class="box-body">
        <table class='table table-striped'>
            <tr>
                <td><label class="control-label">Tanggal Lapor</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$itemA[0]->LAPOR?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->LAPOR?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Jenis Bukti</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if($itemA[0]->JENIS_BUKTI == '6'){ echo 'BPKB';}elseif($itemA[0]->JENIS_BUKTI == '7'){ echo 'STNK' ;}else{ echo 'Lainnya';}?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if(@$itemB[0]->JENIS_BUKTI == '6'){ echo 'BPKB';}elseif(@$itemB[0]->JENIS_BUKTI == '7'){ echo 'STNK' ;}else{ echo 'Lainnya';}?></div></td>
            </tr>
            <!-- <tr>
                <td><label class="control-label">Nomor Bukti</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->NOMOR_BUKTI;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->NOMOR_BUKTI;?></div></td>
            </tr> -->
            <tr>
                <td><label class="control-label">Atas Nama</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->NAMA;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->NAMA;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Asal Usul</label></td>
                <td><div class="control-label" style="text-align:left !important;">
                    <?php
                    $exp  = explode(',', $itemA[0]->ASAL_USUL);
                    $text = '';
                    foreach ($exp as $key) {
                        foreach ($asalusuls as $au) {
                            if($au->ID_ASAL_USUL == $key){
                                $text .= ($au->IS_OTHER === '1' ? '<font color="blue">'.$au->ASAL_USUL.'</font>' : $au->ASAL_USUL).'&nbsp;,&nbsp;&nbsp;';
                            }
                        }
                    }
                    $rinci = substr($text, 0, -19);
                    echo $rinci;
                    ?>
                </div></td>
                <td><div class="control-label" style="text-align:left !important;">
                    <?php
                    $exp  = explode(',', @$itemB[0]->ASAL_USUL);
                    $text = '';
                    foreach ($exp as $key) {
                        foreach ($asalusuls as $au) {
                            if($au->ID_ASAL_USUL == $key){
                                $text .= ($au->IS_OTHER === '1' ? '<font color="blue">'.$au->ASAL_USUL.'</font>' : $au->ASAL_USUL).'&nbsp;,&nbsp;&nbsp;';
                            }
                        }
                    }
                    $rinci = substr($text, 0, -19);
                    echo $rinci;
                    ?>
                </div></td>
            </tr>
            <tr>
                <td><label class="control-label">Pemanfaatan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$manfaat[$itemA[0]->PEMANFAATAN];?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$manfaat[@$itemB[0]->PEMANFAATAN];?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Ket Lainnya</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->KET_LAINNYA;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->KET_LAINNYA;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label" style="text-align:left !important;">Tahun Perolehan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->TAHUN_PEROLEHAN_AWAL;?> s/d <?php echo $itemA[0]->TAHUN_PEROLEHAN_AKHIR;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->TAHUN_PEROLEHAN_AWAL;?> s/d <?php echo @$itemB[0]->TAHUN_PEROLEHAN_AKHIR;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nilai Perolehan</label></td>
                <td><div class="control-label" style="text-align:right !important;">
                    <?=$uang[$itemA[0]->MATA_UANG]?>
                     <?php echo @number_format( @$itemA[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
                <td><div class="control-label" style="text-align:right !important;">
                    <?=$uang[@$itemB[0]->MATA_UANG];?>
                     <?php echo @number_format( @$itemB[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nilai Pelaporan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : $itemA[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?> </div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : @$itemB[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?> </div></td>
            </tr>
            <tr>
                <td><label class="control-label"></label></td>
                <td><div class="control-label" style="text-align:right !important;">Rp. <?php echo @number_format( @$itemA[0]->NILAI_PELAPORAN , 2 , ',' , '.' );?></div></td>
                <td><div class="control-label" style="text-align:right !important;">Rp. <?php echo @number_format( @$itemB[0]->NILAI_PELAPORAN , 2 , ',' , '.' );?></div></td>
            </tr>
        </table>
    <div class="pull-right">
        <input type="reset" class="btn btn-md btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>

<script type="text/javascript">
        jQuery(document).ready(function() {
        	// $.post("index.php/efill/lhkpn/daftar_uang", function(html){
        	//     $.each(html, function(index, value){
        	//         $("#UANG").append("<option value='"+index+"'>"+value+"</option>");
        	//     });
            $("#UANG").select2();
        	// }, 'json');
        	$('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('.dari').datepicker({
                orientation: "left",
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            }).on('hide', function (e) {
                $( ".ke" ).datepicker( "setStartDate", e.date);
            });

            $('.ke').datepicker({
                orientation: "left",
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            }).on('hide', function (e) {
                $( ".dari" ).datepicker( "setEndDate", e.date);
            });

            $('.year-picker').datepicker({
                orientation: "left",
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });    

            $(".int").inputmask("integer", {
                groupSeparator : '.',
                'autoGroup': true,
                'removeMaskOnSubmit': false,
                'digits': 0
            });

            var id_lhkpn = $('#idLhkpn').val();
            $('.atasnama').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/share/reff/getatasnamabylhkpn')?>/"+id_lhkpn,
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
                    var id = encodeURIComponent($(element).val());
                    if (id !== "") {
                        $.ajax("<?=base_url('index.php/share/reff/getatasnamabylhkpn')?>/"+id_lhkpn+"/"+id, {
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

            var requiredCheckboxesasalusul = $('.asalusul');

            requiredCheckboxesasalusul.change(function(){

                if(requiredCheckboxesasalusul.is(':checked')) {
                    requiredCheckboxesasalusul.removeAttr('required');
                }

                else {
                    requiredCheckboxesasalusul.attr('required', 'required');
                }
            });   
        });
</script>