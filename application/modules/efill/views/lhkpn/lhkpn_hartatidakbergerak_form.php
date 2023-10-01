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
<script type="text/javascript" src="<?= base_url('js/jquery.validate.min.js');?>"></script>
<script type="text/javascript">
    $('.validasi').validate({
        rules: {
            Kelurahan : "required"
        },
        messages: {
            Kelurahan : {
                required: " Kelurahan harus diisi ",
                maxlength: "&nbsp;&nbsp;&nbsp;No KTP harus terdiri dari 16 digit"
            }
        }
    });
</script>
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
    <form class='form-horizontal' method="post" id="ajaxFormAddtidakbergerak" action="index.php/efill/lhkpn/savehartatidakbergerak" enctype="multipart/form-data">
        <div class="con-body">
            <div class="box-body">
                <div class="col-sm-6">
                   <div class="form-group">
                        <label>Negara Asal  <span class="red-label">*</span> </label> <?= FormHelpPopOver('negara_asal_htb'); ?>
                    </div>
                       <div class="form-group">
                                <label>
                                    <input required type="radio" name='NEGARA' onClick="luar();" id='NEGARA' value="2" > Luar Negeri
                                </label>
                          
                                <label>
                                    <input required type="radio" name='NEGARA' id='NEGARA' onClick="dalam();" checked value="1" chacked> Indonesia
                                </label>
                          
                        </div>
            

                    <div class="form-group" id="showLuar" style="display:none">
                        <label>Nama Negara<span class="red-label">*</span> </label> <?= FormHelpPopOver('negara'); ?>
                      
                            <input type='text' class="luarnegeri form-control form-select2 KD_ISO3_NEGARA" name='KD_ISO3_NEGARA' style="border:none;" id='KD_ISO3_NEGARA' placeholder="Negara">
                      
                    </div>

                    <div class="form-group lokasi">
                        <label>Provinsi  <span class="red-label">*</span> </label> <?= FormHelpPopOver('provinsi_htb'); ?>
                       
                            <input name='PROV' class="dalamnegeri form-control form-select2" style="border:0px;" required />
                      
                    </div>

                    <div class="form-group lokasi">
                        <label>Kabupaten/Kota <span class="red-label">*</span> </label><?= FormHelpPopOver('kab_htb'); ?>
                      
                            <input name='KAB_KOT' style="border:0px;" class="dalamnegeri form-control form-select2" />
                       
                    </div>

                    <div class="form-group lokasi">
                        <label>Kecamatan <span class="red-label">*</span> </label> <?= FormHelpPopOver('kec_htb'); ?>
                     
                            <!-- <input type="text"  name='KEC' id='KEC' style="border:none;" class="form-control" placeholder="Kecamatan"> -->
                            <!-- <select name='KEC' id='KEC' disabled="disabled" style="border:0px;" class="form-control form-select2" onchange="kel();" placeholder="Kecamatan">
                                <option value=""></option>
                            </select> -->
                            <input type="text" class="dalamnegeri form-control input_capital" name="KEC" required>
                       
                    </div>

                    <div class="form-group lokasi">
                        <label >Desa/Kelurahan <span class="red-label">*</span> </label><?= FormHelpPopOver('kel_htb'); ?>
                      
                            <!-- <input type="text"  name='KEL' id='Kelurahan' style="border:none;" class="form-control" placeholder="Kelurahan"> -->
                            <!-- <select name='KEL' id='KEL' disabled="disabled" style="border:0px;" class="form-control form-select2" placeholder="Kelurahan">
                                <option value=""></option>
                            </select> -->
                            <input type="text" class="dalamnegeri form-control input_capital" name="KEL"  required>
                       
                    </div>

                    <div class="form-group">
                        <label>Jalan <span class="red-label">*</span> </label> <?= FormHelpPopOver('jalan_htb'); ?>
                       
                            <textarea name='JALAN' id='JALAN' class="form-control"  required></textarea>
                       
                    </div>

                    <div class="form-group">
                        <label>Luas Tanah/Bangunan <span class="red-label">*</span> </label> <?= FormHelpPopOver('luas_tanah_htb'); ?>
                      
                        <div style="overflow:hidden; clear:both;">
                                <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_TANAH" id="LUAS_TANAH"  class="form-control money" required/> 
                                <label style="display:inline;">m<sup>2</sup></label>
                                <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_BANGUNAN" id="LUAS_BANGUNAN"  class="form-control money" required/>
                                <label style="display:inline;">m<sup>2</sup></label>
                            </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Jenis Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_bukti_htb'); ?>
                      
                            <select name='JENIS_BUKTI' id='JENIS_BUKTI' class="form-control" required>
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
                      </div>
                
                 
                      <div class="col-md-1"></div>
                <div class="col-md-5">

                    <div class="form-group">
                        <label >Nomor Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('no_bukti_htb'); ?>
                       
                            <input type="text"  name='NOMOR_BUKTI' id='NOMOR_BUKTI' class="form-control input_capital" required>
                       
                    </div>

                    <div class="form-group">
                    <label>Atas Nama  <span class="red-label">*</span>:</label><?= FormHelpPopOver('atas_nama_htg'); ?>
                   
                          <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                            <option></option>
                            <option value="PN YANG BERSANGKUTAN">PN YANG BERSANGKUTAN</option>  
                            <option value="PASANGAN / ANAK">PASANGAN / ANAK</option>  
                            <option value="LAINNYA">LAINNYA</option>
                        </select>
                   
                </div>
              
               
                    
                  <div class="form-group">
                        <label >Asal Usul <span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_htb'); ?>
                      
                        <?php $i = 1; foreach($asalusuls as $asalusul){ ?>
                            <div class="checkbox">
                                <div class="col-sm-<?php echo ($asalusul->IS_OTHER !== '1' ? '12' : '5' ) ?>">
                                    <label>
                                    <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" name='ASAL_USUL[]' required class='asalusul' value="<?php echo $asalusul->ID_ASAL_USUL?>">
                                    <?php echo $i++ . '. ' . $asalusul->ASAL_USUL; ?>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                       
                    </div>

                    <div class="form-group">
                        <label >Pemanfaatan <span class="red-label">*</span> </label> <?= FormHelpPopOver('pemanfaatan_htb'); ?>
                      
                            <!-- <select name='PEMANFAATAN' id='PEMANFAATAN' class="form-control" placeholder="Pemanfaatan" required> -->
                                 <!-- <option value=''>-- Pemanfaatan --</option> -->
                                <?php foreach ($manfaat as $key => $value) { ?>
                                    <div class="checkbox">
                                        <div class="col-sm-7">
                                            <label>
                                                <input type="checkbox" name="PEMANFAATAN[]" required value="<?=$key?>">
                                                <?=$value?>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- <option value='0'>Lainnya</option> -->
                            <!-- </select> -->
                     
                    </div>

                      <div class="form-group">
                        <label >Nilai Perolehan(Rp)<span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_htb'); ?>
                        <!--<div class="col-sm-3 ">
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
                      
                            <input required class="form-control int" name="NILAI_PEROLEHAN"  type="text">
                      
                    </div>

                    <div class="form-group">
                        <label >Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_htb'); ?>
                        <!--<div class="col-sm-7">
                            <div class='col-sm-6'>
                                <label>
                                    <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="1" > Appraisal
                                </label>
                            </div>
                            <div class='col-sm-6'>
                                <label>
                                    <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="2" chacked> Perkiraan Pasar
                                </label>
                            </div>
                        </div>
                    </div>-->


                    <!--<div class="form-group">
                        <label class="col-sm-5 control-label">Rp. <span class="red-label">*</span></label>-->
                      
                            <input required type="text"  name='NILAI_PELAPORAN' id='NILAI_PELAPORAN' class="int form-control">
                       
                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="pull-right">
                <input type="hidden" name="act" value="doinsert">
                <input type="hidden"  name='ID_HARTA' id='ID_HARTA' class="form-control" placeholder="Id Harta">
                <!-- <input type="hidden"  name='ID_HARTA_LAMA' id='ID_HARTA_LAMA' class="form-control" placeholder="Id Harta Lama"> -->
                <input type='hidden' readonly name='ID_LHKPN' id="idLhkpn" value="<?=@$id_lhkpn;?>">
                <input type="hidden"  name='GOLONGAN_HARTA' id='GOLONGAN_HARTA' class="form-control" placeholder="Golongan Harta">
                <input type="hidden"  name='ID_JENIS_HARTA' id='ID_JENIS_HARTA' class="form-control" placeholder="Id Jenis Harta">

                <button type="submit" class="btn btn-sm btn-primary" id="btnSimpan">Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
        </div>
        <div id="pelepasan-con"></div>

        <div id="conf" style="display: none;">
            <div class="pull-right">
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                <button type="button" class="btn btn-sm btn-danger" value="Kembali" onclick="f_kembali_conf();">
            </div>
        </div>
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
                   
                        <input type="text" name='KETERANGAN_PELEPASAN[]' class="form-control">
                   
                </div>

                <div class="form-group">
                    <label>Pihak Kedua</label>
                </div>

                <div class="form-group">
                    <label>Nama <span class="red-label">*</span> </label>
                   
                        <input type="text" name='NAMA[]' class="form-control input_capital required">
                    
                </div>

                <div class="form-group">
                    <label>Alamat </label>
                   
                        <textarea name='ALAMAT[]' class="form-control" placeholder="Alamat"></textarea>
                   
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
    var conf = true;
    $(document).ready(function() {
        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false
        });
          $(function () {
        $('.over').popover();
        $('.over')
                .mouseenter(function (e) {
                    $(this).popover('show');
                })
                .mouseleave(function (e) {
                    $(this).popover('hide');
                });
    });
         $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        $("#ajaxFormAddtidakbergerak").submit(function(){
            $('#btnSimpan').prop('disabled', true);
            if(conf) {
                var url = $(this).attr('action');
                var data = $(this).serializeArray();
                $.post(url, data, function (res) {
                    msg = {
                        success: 'Data Berhasil Disimpan!',
                        error: 'Data Gagal Disimpan!'
                    };
                    if (data == 0) {
                        alertify.error(msg.error);
                    } else {
                        alertify.success(msg.success);
                    }
                    CloseModalBox();
                    var ID = $('#idLhkpn').val();
                    ng.LoadAjaxTabContent({
                        url: 'index.php/efill/lhkpn/showTable/4/' + ID + '/edit',
                        block: '#block',
                        container: $('#hartatidakbergerak').find('.contentTab')
                    });
                    ng.LoadAjaxTabContent({
                        url: 'index.php/efill/lhkpn/showTable/1/' + ID + '/edit',
                        block: '#block',
                        container: $('#final').find('.contentTab')
                    });
                    ng.LoadAjaxTabContent({
                        url: 'index.php/efill/lhkpn/showTable/19/' + ID + '/edit',
                        block: '#block',
                        container: $('#penerimaanhibah').find('.contentTab')
                    });
                })
                
            }else{
                $('.con-body').slideUp('slow', function () {
                    $('.modal-dialog').animate({
                        width: '-=500'
                    });
                    $('#pelepasan-con').slideDown('slow');
                })
            }
            return false;
        })

        $('.dalamnegeri').attr("required", true);
        $('.luarnegeri').attr('required', false);

		// var ID = $('#idLhkpn').val();
  //       ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/4/'+ID, block:'#block', container:$('#hartatidakbergerak').find('.contentTab')});
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
            $('.modal-title').html('Asal Usul ');
        }
    }

    function f_close(ele)
    {
        // conf = false;
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
        var val  = $(ele).closest('.pelepasan').attr('data-id');
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

    function f_kembali_conf()
    {
        $('#pelepasan-con').slideUp('slow', function () {
            $('.modal-dialog').animate({
                width: '+=500'
            });
            $('.con-body').slideDown('slow');
        })
    }
         $('input[name="PROV"]').select2({
             minimumInputLength: 0,
             ajax: {
                 url: "<?=base_url('index.php/efill/lhkpn/getProvinsi')?>",
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
                     $.ajax("<?=base_url('index.php/efill/lhkpn/getProvinsi')?>/"+id, {
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
        $('input[name="PROV"]').on("change", function (e) {
            $('input[name="KAB_KOT"]').select2("val", "");
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });

         $('input[name="KAB_KOT"]').select2({
             minimumInputLength: 0,
             ajax: {
                 url: "<?=base_url('index.php/efill/lhkpn/getKabupaten')?>",
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
                     $.ajax("<?=base_url('index.php/efill/lhkpn/getKabupaten')?>/"+prov+'/'+id, {
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
        $('input[name="KAB_KOT"]').on("change", function (e) {
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class='form-horizontal' method="post" id="ajaxFormEdithartatidakbergerak" action="index.php/efill/lhkpn/savehartatidakbergerak">
        <div class="con-body">
            <div class="box-body">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Negara <span class="red-label">*</span> </label>  <?= FormHelpPopOver('negara'); ?>
                    </div>
                     <div class="form-group">
                                <label>
                                    <input required type="radio" name='NEGARA' id='NEGARA' onClick="luar();" value="1" <?php echo $item->NEGARA == '1' ? 'checked' : '' ;?>> Luar Negeri
                                </label>
                       
                                <label>
                                    <input required type="radio" name='NEGARA' id='NEGARA' onClick="dalam();" value="2" <?php echo $item->NEGARA == '2' ? 'checked' : '' ;?>> Indonesia
                                </label>
                          
                    </div>

                    <div class="form-group" id="showLuar" <?php echo $item->NEGARA == '2' ? 'style="display:none"' : '' ;?>>
                        <label>Nama Negara<font color='red'>*</font> </label>
                       
                            <input type='text' class="luarnegeri form-control form-select2 KD_ISO3_NEGARA" name='KD_ISO3_NEGARA' style="border:none;" id='KD_ISO3_NEGARA' value='<?php echo @$item->ID_NEGARA;?>' placeholder="Negara">
                        
                    </div>

                    <div class="form-group lokasi" <?php echo $item->NEGARA == '1' ? 'style="display:none"' : '' ;?>>
                        <label>Provinsi <span class="red-label">*</span> </label> <?= FormHelpPopOver('provinsi_htb'); ?>
                       
                            <input name='PROV' class="dalamnegeri form-control form-select2" style="border:0px;"  value="<?php echo $item->PROV; ?>" />
                        
                    </div>
                    <div class="form-group lokasi" <?php echo $item->NEGARA == '1' ? 'style="display:none"' : '' ;?>>
                        <label>Kabupaten/Kota <span class="red-label">*</span> </label> </label> <?= FormHelpPopOver('kab_htb'); ?>
                      
                            <input name='KAB_KOT' value="<?php echo $item->KAB_KOT?>" style="border:0px;" class="dalamnegeri form-control form-select2" placeholder="Kabupaten Kota" />
                     
                    </div>
                    <div class="form-group lokasi" <?php echo $item->NEGARA == '1' ? 'style="display:none"' : '' ;?>>
                        <label >Kecamatan <span class="red-label">*</span> :</label><?= FormHelpPopOver('kec_htb'); ?>
                       
                            <!-- <input type="text"  name='KEC' id='KEC' style="border:none;" class="form-control" value='<?php //echo $item->KEC;?>' placeholder="Kecamatan"> -->
                            <!-- <input name='KEC' value="<?php echo $item->KEC ?>" style="border:0px;" class="form-control form-select2" placeholder="Kecamatan" /> -->
                            <input type="text" class="dalamnegeri form-control input_capital" name="KEC" value="<?= @$item->KEC; ?>" placeholder="Kecamatan">
                      
                    </div>
                    <div class="form-group lokasi" <?php echo $item->NEGARA == '1' ? 'style="display:none"' : '' ;?>>
                        <label>Kelurahan <span class="red-label">*</span> :</label><?= FormHelpPopOver('kel_htb'); ?>
                       
                            <!-- <input type="text"  name='KEL' id='Kelurahan' style="border:none;" class="form-control" value='<?php //echo $item->KEL;?>' placeholder="Kelurahan"> -->
                            <!-- <input name='KEL' class="form-control form-select2" style="border:0px;" placeholder="Kelurahan" value="<?php echo $item->KEL;?>" /> -->
                            <input type="text" class="dalamnegeri form-control input_capital" name="KEL" value="<?= @$item->KEL; ?>" placeholder="Kelurahan">
                       
                    </div>

                    <div class="form-group">
                        <label>Jalan <span class="red-label">*</span> :</label><?= FormHelpPopOver('jalan_htb'); ?>
                       
                            <textarea name='JALAN' id='JALAN' class="form-control" placeholder="Jalan" required><?php echo $item->JALAN;?></textarea>
                      
                    </div>

                    <div class="form-group">
                        <label>Luas Tanah/Bangunan <span class="red-label">*</span> </label> <?= FormHelpPopOver('luas_tanah_htb'); ?>
                           <div style="overflow:hidden; clear:both;">
                                <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_TANAH" id="LUAS_TANAH" value='<?php echo $item->LUAS_TANAH;?>' placeholder="" class="form-control money" required/> 
                                <label style="display:inline;">m<sup>2</sup></label>
                                <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_BANGUNAN" id="LUAS_BANGUNAN"  value='<?php echo $item->LUAS_BANGUNAN;?>' placeholder="" class="form-control money" required/>
                                <label style="display:inline;">m<sup>2</sup></label>
                            </div>
                       
                           <!--  <input required type="text"  name='LUAS_TANAH' id='LUAS_TANAH' class="form-control int" value='<?php echo $item->LUAS_TANAH;?>' placeholder="Tanah">
                       
                        <label  style='text-align:left;'>M<sup>2</sup></label>
                      
                            <input required type="text"  name='LUAS_BANGUNAN' id='LUAS_BANGUNAN' class="form-control int" value='<?php echo $item->LUAS_BANGUNAN;?>' placeholder="Bangunan">
                     
                        <label style='text-align:left;'>M<sup>2</sup></label> -->
                    </div>

                    <!--<div class="form-group">
                        <label>KETERANGAN </label>
                       
                            <textarea name='KETERANGAN' id='ket' class="form-control" placeholder="Keterangan" ><?php echo $item->KETERANGAN;?></textarea>
                      
                    </div>-->
                    <div class="form-group">
                        <label>Jenis Bukti <span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_bukti_htb'); ?>
                      
                            <select name='JENIS_BUKTI' id='JENIS_BUKTI' class="form-control" placeholder="Jenis Bukti" required>
                                <option value=''></option>
                                <?php
                                    $ia = 0;
                                    foreach ($bukti as $key => $value) {
                                        $ia++;
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->JENIS_BUKTI){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        
                    </div>
                </div>
                 <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Nomor Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('no_bukti_htb'); ?>
                       
                            <input required type="text"  name='NOMOR_BUKTI' id='NOMOR_BUKTI' class="form-control input_capital" value='<?php echo $item->NOMOR_BUKTI;?>' placeholder="Nomor Bukti">
                       
                    </div>

                    <div class="form-group">
                        <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_htb'); ?>
                       
                           <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                            <option></option>
                                <option <?php echo ($item->ATAS_NAMA == 'PN YANG BERSANGKUTAN' ? 'selected="selected"' : '') ?> value="PN YANG BERSANGKUTAN">PN YANG BERSANGKUTAN</option>
                                <option <?php echo ($item->ATAS_NAMA == 'PASANGAN / ANAK' ? 'selected="selected"' : '') ?> value="PASANGAN / ANAK">PASANGAN / ANAK</option>
                                <option <?php echo ($item->ATAS_NAMA == 'LAINNYA' ? 'selected="selected"' : '') ?> value="LAINNYA">LAINNYA</option>
                               
                            </select>
                       
                    </div>
            
                    
                    <div class="form-group">
                        <label>Asal Usul <span class="red-label">*</span> </label><?= FormHelpPopOver('asal_usul_harta_htb'); ?>
                     
                        <?php
                        $asalusulselected = explode(',',$item->ASAL_USUL);
                        $i  = 0;
                        $i2 = 1;
                        foreach($asalusuls as $asalusul){ ?>
                            <div class="checkbox">
                                <div class="col-sm-<?php echo ($asalusul->IS_OTHER !== '1' ? '12' : '5' ) ?>">
                                    <label>
                                    <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" name='ASAL_USUL[]' class='asalusul' value="<?php echo $asalusul->ID_ASAL_USUL?>" <?php echo in_array($asalusul->ID_ASAL_USUL, $asalusulselected)?'checked':''; ?>>
                                    <?php echo $i2++ . '.' . $asalusul->ASAL_USUL; ?>
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
                        <label>Pemanfaatan <span class="red-label">*</span> </label> <?= FormHelpPopOver('pemanfaatan_htb'); ?>
                       
                            <!-- <select required name='PEMANFAATAN' id='PEMANFAATAN' class="form-control" placeholder="Pemanfaatan"> -->
                                <!-- <option value=''>Pemanfaatan</option> -->
                                <?php $pemanfaatanselected = explode(',',$item->PEMANFAATAN); ?>
                                <?php foreach ($manfaat as $key => $value) { ?>
                                    <div class="checkbox">
                                        <div class="col-sm-7">
                                            <label>
                                                <input type="checkbox" name="PEMANFAATAN[]" value="<?=$key?>" <?php echo in_array($key, $pemanfaatanselected)?'checked':''; ?>>
                                                <?=$value?>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- <option value='0' <?php echo $item->PEMANFAATAN == '0' ? 'selected' : '' ;?>>Lainnya</option> -->
                            <!-- </select> -->
                       
                    </div>

                 <!--    <div class="form-group">
                        <label >Ket Lainnya </label>
                       
                            <textarea name='KET_LAINNYA' id='KET_LAINNYA' class="form-control" placeholder="Ket Lainnya"><?php echo $item->KET_LAINNYA;?></textarea>
                       
                    </div> -->

                   <!--  <div class="form-group">
                        <label >Tahun Perolehan <span class="red-label">*</span> </label>
                      
                            <input required class="form-control dari" name="TAHUN_PEROLEHAN_AWAL" value='<?php echo $item->TAHUN_PEROLEHAN_AWAL;?>' placeholder="Tahun Perolehan Awal" type="text">
                       
                        <div class="col-sm-1">
                            s/d
                        </div>
                      
                            <input class="form-control ke" name="TAHUN_PEROLEHAN_AKHIR" value='<?php echo $item->TAHUN_PEROLEHAN_AKHIR;?>' placeholder="Tahun Perolehan Akhir" type="text">
                       
                    </div> -->

                    <div class="form-group">
                        <label>Nilai Perolehan (Rp) <span class="red-label">*</span> </label><?= FormHelpPopOver('nilai_perolehan_htb'); ?>
                      
                           <!--  <select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANG1" required>
                                <?php
                                    foreach ($uang as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->MATA_UANG){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select> -->
                      
                       
                            <input class="form-control int" name="NILAI_PEROLEHAN" value='<?php echo $item->NILAI_PEROLEHAN;?>' placeholder="Nilai Perolehan" type="text">
                      
                    </div>

                    <div class="form-group">
                        <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label><?= FormHelpPopOver('nilai_estimasi_pelaporan_htb'); ?>
                  <!--  </div>
                       <div class="form-group">
                                <label>
                                    <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' <?php echo $item->JENIS_NILAI_PELAPORAN == '1' ? 'checked' : '' ;?> value="1" > Appraisal
                                </label>
                        
                                <label>
                                    <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' <?php echo $item->JENIS_NILAI_PELAPORAN == '2' ? 'checked':'';?> value="2" > Perkiraan Pasar
                                </label>
                           
                        </div>

                    <div class="form-group">
                        <label>Rp. <span class="red-label">*</span></label> -->
                      
                            <input  required type="text"  name='NILAI_PELAPORAN' id='NILAI_PELAPORAN' class="form-control int" value="<?php echo $item->NILAI_PELAPORAN;?>" placeholder="Nilai Pelaporan">
                       
                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="pull-right">
                <input type="hidden"  name='ID' id='ID' class="form-control" value='<?php echo $item->ID;?>' placeholder="Id Harta">
                <input type="hidden"  name='ID_HARTA_LAMA' id='ID_HARTA_LAMA' class="form-control" value='<?php echo $item->ID_HARTA_LAMA;?>' placeholder="Id Harta Lama">
                <input type="hidden"  name='ID_LHKPN' id='idLhkpn' class="form-control" value='<?php echo $item->ID_LHKPN;?>' placeholder="Id Lhkpn">
                <input type="hidden"  name='GOLONGAN_HARTA' id='GOLONGAN_HARTA' class="form-control" value='<?php echo $item->GOLONGAN_HARTA;?>' placeholder="Golongan Harta">
                <input type="hidden"  name='ID_JENIS_HARTA' id='ID_JENIS_HARTA' class="form-control" value='<?php echo $item->ID_JENIS_HARTA;?>' placeholder="Id Jenis Harta">
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
                                    <input value="<?php echo $row->NAMA ?>" type="text" name='NAMA[]' class="form-control required" placeholder="Nama">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Alamat :</label>
                                <div class="col-sm-5">
                                    <textarea name='ALAMAT[]' class="form-control" placeholder="Alamat"><?php echo $row->ALAMAT ?></textarea>
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
                    <label class="col-sm-4 control-label">Tanggal Transaksi  :</label>
                    <div class="col-sm-4">
                        <input type="text"  name='TGL[]' class="form-control datepicker" placeholder="DD/MM/YYYY">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai <span class="title"></span> <span class="red-label">*</span>:</label>
                    <div class="col-sm-1"><p class="form-control-static">Rp</p></div>
                    <div class="col-sm-4">
                        <input type="text" name='NILAI[]' class="required form-control int title2">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Keterangan :</label>
                    <div class="col-sm-5">
                        <input type="text" name='KETERANGAN_PELEPASAN[]' class="form-control" placeholder="Keterangan">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label" style="font-size: 15px;">Pihak Kedua</label>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama <span class="red-label">*</span>:</label>
                    <div class="col-sm-5">
                        <input type="text" name='NAMA[]' class="required form-control" placeholder="Nama">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Alamat :</label>
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
  $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

  $(function () {
        $('.over').popover();
        $('.over')
                .mouseenter(function (e) {
                    $(this).popover('show');
                })
                .mouseleave(function (e) {
                    $(this).popover('hide');
                });
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

    var prov    = '';
    var kab     = '';
    var kec     = '';
    var kel     = '';
    $(document).ready(function() {
        $("#UANG1").select2();

        var prov    = $('input[name="PROV"]').val();
        var kab     = $('input[name="KAB_KOT"]').val();
        var kec     = $('input[name="KEC"]').val();
        var kel     = $('input[name="KEL"]').val();

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false
        });

        $("#ajaxFormEdithartatidakbergerak").submit(function(){
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
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/4/'+ID + '/edit', block:'#block', container:$('#hartatidakbergerak').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
            })
            return false;
        })

         $('input[name="PROV"]').select2({
             minimumInputLength: 0,
             ajax: {
                 url: "<?=base_url('index.php/efill/lhkpn/getProvinsi')?>",
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
                     $.ajax("<?=base_url('index.php/efill/lhkpn/getProvinsi')?>/"+id, {
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
        $('input[name="PROV"]').on("change", function (e) {
            $('input[name="KAB_KOT"]').select2("val", "");
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });

         $('input[name="KAB_KOT"]').select2({
             minimumInputLength: 0,
             ajax: {
                 url: "<?=base_url('index.php/efill/lhkpn/getKabupaten')?>",
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
                     $.ajax("<?=base_url('index.php/efill/lhkpn/getKabupaten')?>/"+prov+'/'+id, {
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
        $('input[name="KAB_KOT"]').on("change", function (e) {
            $('input[name="KEC"]').select2("val", "");
            $('input[name="KEL"]').select2("val", "");
        });

        $('#ajaxFormEdithartatidakbergerak :input').change(function () {
            $('#ajaxFormEdithartatidakbergerak button[type="submit"]').prop('disabled', false);
        })
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Harta Tidak Bergerak dibawah ini ?
    <form class='form-horizontal' method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/savehartatidakbergerak">
       <!--  <div class="box-body">
            <div class="col-sm-6">

                <div class="form-group">
                    <label class="col-sm-4 control-label">Negara :</label>
                    <div class="col-sm-8">
                       <label class='control-label' align='left'><?php if($item[0]->NEGARA == '2'){echo 'Dalam Negeri';}elseif($item[0]->NEGARA == '1'){echo 'Luar Negeri';}?></label>
                    </div>
                </div> 

                <div class="form-group" <?php if($item[0]->NEGARA == '2'){ echo 'style="display:none;"';} ?>>
                    <label class="col-sm-4 control-label">Nama Negara :</label>
                    <div class="col-sm-8" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->NAMA_NEGARA;?></label>
                    </div>
                </div>

                <div class="form-group" <?php if($item[0]->NEGARA == '1'){ echo 'style="display:none;"';} ?>>
                    <label class="col-sm-4 control-label">Provinsi :</label>
                    <div class="col-sm-8" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->PROV;?></label>
                    </div>
                </div>

                <div class="form-group" <?php if($item[0]->NEGARA == '1'){ echo 'style="display:none;"';} ?>>
                    <label class="col-sm-4 control-label">Kabupaten/Kota:</label>
                    <div class="col-sm-8" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->KAB_KOT;?></label>
                    </div>
                </div>

                <div class="form-group" <?php if($item[0]->NEGARA == '1'){ echo 'style="display:none;"';} ?>>
                    <label class="col-sm-4 control-label">Kecamatan :</label>
                    <div class="col-sm-8" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->KEC;?></label>
                    </div>
                </div>

                <div class="form-group" <?php if($item[0]->NEGARA == '1'){ echo 'style="display:none;"';} ?>>
                    <label class="col-sm-4 control-label">Kelurahan :</label>
                    <div class="col-sm-8" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->KEL;?></label>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="col-sm-4 control-label">Jalan :</label>
                    <div class="col-sm-8" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->JALAN;?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Luas Tanah/Bangunan:</label>
                    <div class="col-sm-8">
                        <label class='control-label' align='left'><?php echo $item[0]->LUAS_TANAH;?> M<sup>2</sup> <?php echo $item[0]->LUAS_BANGUNAN;?> M<sup>2</sup></label>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Jenis Bukti :</label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php if($item[0]->JENIS_BUKTI == '1'){ echo 'Sertifikat';}elseif($item[0]->JENIS_BUKTI == '2'){ echo 'Akta Jual Beli' ;}elseif($item[0]->JENIS_BUKTI == '3'){ echo 'Girik';}elseif($item[0]->JENIS_BUKTI == '4'){ echo 'Letter C';}elseif($item[0]->JENIS_BUKTI == '5'){ echo 'Pipil';}elseif($item[0]->JENIS_BUKTI == '6'){ echo 'Lainnya';}?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Nomor Bukti :</label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->NOMOR_BUKTI;?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Atas Nama :</label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->ATAS_NAMA;?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Asal Usul :</label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php if($item[0]->ASAL_USUL == '1'){ echo 'Hasil Sendiri';}elseif($item[0]->ASAL_USUL == '2'){ echo 'Warisan' ;}elseif($item[0]->ASAL_USUL == '3'){ echo 'Hibah';}else{echo 'Hadiah' ;}?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Pemanfaatan :</label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php if($item[0]->PEMANFAATAN == '1'){ echo 'Tempat Tinggal';}elseif($item[0]->PEMANFAATAN == '2'){ echo 'Disewakan' ;}elseif($item[0]->PEMANFAATAN == '3'){ echo 'Pertanian';}elseif($item[0]->PEMANFAATAN == '4'){ echo 'Perkebunan';}elseif($item[0]->PEMANFAATAN == '5'){ echo 'Perikanan';}elseif($item[0]->PEMANFAATAN == '6'){ echo 'Pertambangan';}else{echo 'Lainnya' ;}?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Ket Lainnya :</label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->KET_LAINNYA;?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Tahun Perolehan :</label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo $item[0]->TAHUN_PEROLEHAN_AWAL;?></label>
                        <label class='control-label' align='left' style="padding-left:10px;padding-right:10px;">s/d</label>
                        <label class='control-label' align='left'><?php echo $item[0]->TAHUN_PEROLEHAN_AKHIR;?></label>
                    </div>
                    
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Nilai Perolehan : </label>
                    <div class="col-sm-3">
                        <label class='control-label' align='left'><?php 
								switch($item[0]->MATA_UANG){
									case '1' : echo 'IDR'; break;
									case '2' : echo 'USD'; break;
									case '3' : echo 'Yen'; break;
								}
								?></label>
                    </div>
                    <div class="col-sm-4">
                        <label class='control-label' align='left'><?php echo @number_format( @$item[0]->NILAI_PEROLEHAN , 2 , '.' , ',' );?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Nilai Pelaporan :</label>
                    <div class="col-sm-7">
						<label class='control-label' align='left'><?php echo $item[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : $item[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label"></label>
                    <div class="col-sm-7" style="text-align:left !important;">
                        <label class='control-label' align='left'><?php echo @number_format( @$item[0]->NILAI_PELAPORAN , 2 , '.' , ',' );?></label>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body --> 
        <div class="pull-right">
			<input type="hidden" name="ID" value="<?php echo $item[0]->ID;?>">
            <input type="hidden" id="idLhkpn" name="ID_LHKPN" value="<?php echo $item[0]->ID_LHKPN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-md btn-danger">Hapus</button>
            <input type="reset" class="btn btn-md btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
		var ID = $('#idLhkpn').val();
        // ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/4/'+ID, block:'#block', container:$('#hartatidakbergerak').find('.contentTab')});		

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
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/4/'+ID + '/edit', block:'#block', container:$('#hartatidakbergerak').find('.contentTab')});
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
<div id="wrapperFormDetail" class='form-horizontal'>

    <div class="box-body">

        <div class="col-sm-6">
			<div class="form-group">
				<label class="col-sm-4 control-label">Negara :</label>
				<div class="col-sm-8">
				   <label><?php if($item[0]->NEGARA == '1'){echo 'Dalam Negeri';}elseif($item[0]->NEGARA == '2'){echo 'Luar Negeri';}?></label>
				</div>
			</div> 
            <div class="form-group">
                <label class="col-sm-4 control-label">Provinsi :</label>
                <div class="col-sm-8" style="text-align:left !important;">
                    <label><?php echo $item[0]->PROV;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Kabupaten/Kota:</label>
                <div class="col-sm-8" style="text-align:left !important;">
                    <label><?php echo $item[0]->KAB_KOT;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Kecamatan :</label>
                <div class="col-sm-8" style="text-align:left !important;">
                    <label><?php echo $item[0]->KEC;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Kelurahan :</label>
                <div class="col-sm-8" style="text-align:left !important;">
                    <label><?php echo $item[0]->KEL;?></label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label">Jalan :</label>
                <div class="col-sm-8" style="text-align:left !important;">
                    <label><?php echo $item[0]->JALAN;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Luas Tanah/Bangunan:</label>
                <div class="col-sm-3">
                    <label><?php echo $item[0]->LUAS_TANAH;?></label>
                </div>
                <label class="col-sm-1 control-label" style='text-align:left;'>M<sup>2</sup></label>
                <div class="col-sm-3">
                    <label><?php echo $item[0]->LUAS_BANGUNAN;?></label>
                </div>
                <label class="col-sm-1 control-label" style='text-align:left;'>M<sup>2</sup></label>
            </div>
        </div>
        <div class="col-sm-6">
			<div class="form-group">
				<label class="col-sm-5 control-label">Jenis Bukti :</label>
				<div class="col-sm-7" style="text-align:left !important;">
					<label><?php if($item[0]->JENIS_BUKTI == '1'){ echo 'Sertifikat';}elseif($item[0]->JENIS_BUKTI == '2'){ echo 'Akta Jual Beli' ;}elseif($item[0]->JENIS_BUKTI == '3'){ echo 'Girik';}elseif($item[0]->JENIS_BUKTI == '4'){ echo 'Letter C';}elseif($item[0]->JENIS_BUKTI == '5'){ echo 'Pipil';}elseif($item[0]->JENIS_BUKTI == '6'){ echo 'Lainnya';}?></label>
				</div>
			</div>
            <div class="form-group">
                <label class="col-sm-5 control-label">Nomor Bukti :</label>
                <div class="col-sm-7" style="text-align:left !important;">
                    <label><?php echo $item[0]->NOMOR_BUKTI;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label">Atas Nama :</label>
                <div class="col-sm-7" style="text-align:left !important;">
                    <label><?php echo $item[0]->ATAS_NAMA;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label">Asal Usul :</label>
                <div class="col-sm-7" style="text-align:left !important;">
                    <label><?php if($item[0]->ASAL_USUL == '1'){ echo 'Hasil Sendiri';}elseif($item[0]->ASAL_USUL == '2'){ echo 'Warisan' ;}elseif($item[0]->ASAL_USUL == '3'){ echo 'Hibah';}else{echo 'Hadiah' ;}?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label">Pemanfaatan :</label>
                <div class="col-sm-7" style="text-align:left !important;">
                    <label><?php if($item[0]->PEMANFAATAN == '1'){ echo 'Tempat Tinggal';}elseif($item[0]->PEMANFAATAN == '2'){ echo 'Disewakan' ;}elseif($item[0]->PEMANFAATAN == '3'){ echo 'Pertanian';}elseif($item[0]->PEMANFAATAN == '4'){ echo 'Perkebunan';}elseif($item[0]->PEMANFAATAN == '5'){ echo 'Perikanan';}elseif($item[0]->PEMANFAATAN == '6'){ echo 'Pertambangan';}else{echo 'Lainnya' ;}?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label">Ket Lainnya :</label>
                <div class="col-sm-7" style="text-align:left !important;">
                    <label><?php echo $item[0]->KET_LAINNYA;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label">Tahun Perolehan :</label>
                <div class="col-sm-3" style="text-align:left !important;">
                    <label><?php echo $item[0]->TAHUN_PEROLEHAN_AWAL;?></label>
                </div>
                <div class="col-sm-1">
                    s/d
                </div>
                <div class=" col-sm-3" style="text-align:left !important;">
                    <label><?php echo $item[0]->TAHUN_PEROLEHAN_AKHIR;?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-5 control-label">Nilai Perolehan : </label>
                <div class="col-sm-3">
                    <label><?php 
							switch($item[0]->MATA_UANG){
								case '1' : echo 'IDR'; break;
								case '2' : echo 'USD'; break;
								case '3' : echo 'Yen'; break;
							}
							?></label>
                </div>
                <div class="col-sm-4">
                    <label><?php echo @number_format( @$item[0]->NILAI_PEROLEHAN , 2 , '.' , ',' );?></label>
                </div>
            </div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Nilai Pelaporan :</label>
				<div class="col-sm-7">
					<label><?php echo $item[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : $item[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?></label>
				</div>
			</div>
            <div class="form-group">
                <label class="col-sm-5 control-label"></label>
                <div class="col-sm-7" style="text-align:left !important;">
                    <label><?php echo @number_format( @$item[0]->NILAI_PELAPORAN , 2 , '.' , ',' );?></label>
                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
    <div class="pull-right">
        <input type="reset" class="btn btn-md btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}

if($form == 'pelepasan'){
?>


    <div id="wrapperFormpelepasantanahbangunan">
        <form class='form-horizontal' method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savehartatidakbergerak" enctype="multipart/form-data">

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
                            <input value="<?php echo ( @$data->TANGGAL_TRANSAKSI != '' ? date('d/m/Y', strtotime(@$data->TANGGAL_TRANSAKSI)) : '' );?>"required type="text"  name='TGL' class="form-control datepicker" placeholder="DD/MM/YYYY">
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
                        <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/deletepelepasantanahbangunan/<?=$id?>/<?php echo $data->ID; ?>" title="Delete">Hapus</button>
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
            // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/4/'+ID, block:'#block', container:$('#hartatidakbergerak').find('.contentTab')});
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
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/4/'+ID + '/edit', block:'#block', container:$('#hartatidakbergerak').find('.contentTab')});
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
                    ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/1/' + ID + '/edit', block: '#block', container: $('#final').find('.contentTab')});
                })
                return false;
            })

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
        $("#wrapperFormpelepasantanahbangunan .btn-delete").click(function (e) {
            urls = $(this).attr('href');
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
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/4/'+ID + '/edit', block:'#block', container:$('#hartatidakbergerak').find('.contentTab')});
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
            });

            return false;
        });
    </script>
<?php }

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
                <td><label class="control-label">Negara</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if($itemA[0]->NEGARA == '2'){echo 'Dalam Negeri';}elseif($itemA[0]->NEGARA == '1'){echo 'Luar Negeri';}?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if(@$itemB[0]->NEGARA == '2'){echo 'Dalam Negeri';}elseif(@$itemB[0]->NEGARA == '1'){echo 'Luar Negeri';}?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Provinsi</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->PROV;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->PROV;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Kabupaten/Kota</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->KAB_KOT;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->KAB_KOT;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Kecamatan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->KEC;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->KEC;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Kelurahan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->KEL;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->KEL;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Jalan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->JALAN;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->JALAN;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Luas Tanah/Bangunan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->LUAS_TANAH;?> M<sup>2</sup> <?php echo $itemA[0]->LUAS_BANGUNAN;?> M<sup>2</sup></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->LUAS_TANAH;?> M<sup>2</sup> <?php echo @$itemB[0]->LUAS_BANGUNAN;?> M<sup>2</sup></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Jenis Bukti</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if($itemA[0]->JENIS_BUKTI == '1'){ echo 'Sertifikat';}elseif($itemA[0]->JENIS_BUKTI == '2'){ echo 'Akta Jual Beli' ;}elseif($itemA[0]->JENIS_BUKTI == '3'){ echo 'Girik';}elseif($itemA[0]->JENIS_BUKTI == '4'){ echo 'Letter C';}elseif($itemA[0]->JENIS_BUKTI == '5'){ echo 'Pipil';}elseif($itemA[0]->JENIS_BUKTI == '6'){ echo 'Lainnya';}?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if(@$itemB[0]->JENIS_BUKTI == '1'){ echo 'Sertifikat';}elseif(@$itemB[0]->JENIS_BUKTI == '2'){ echo 'Akta Jual Beli' ;}elseif(@$itemB[0]->JENIS_BUKTI == '3'){ echo 'Girik';}elseif(@$itemB[0]->JENIS_BUKTI == '4'){ echo 'Letter C';}elseif(@$itemB[0]->JENIS_BUKTI == '5'){ echo 'Pipil';}elseif(@$itemB[0]->JENIS_BUKTI == '6'){ echo 'Lainnya';}?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nomor Bukti</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->NOMOR_BUKTI;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->NOMOR_BUKTI;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Atas Nama</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->ATAS_NAMA;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->ATAS_NAMA;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Asal Usul</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if($itemA[0]->ASAL_USUL == '1'){ echo 'Hasil Sendiri';}elseif($itemA[0]->ASAL_USUL == '2'){ echo 'Warisan' ;}elseif($itemA[0]->ASAL_USUL == '3'){ echo 'Hibah';}else{echo 'Hadiah' ;}?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if(@$itemB[0]->ASAL_USUL == '1'){ echo 'Hasil Sendiri';}elseif(@$itemB[0]->ASAL_USUL == '2'){ echo 'Warisan' ;}elseif(@$itemB[0]->ASAL_USUL == '3'){ echo 'Hibah';}else{echo 'Hadiah' ;}?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Pemanfaatan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if($itemA[0]->PEMANFAATAN == '1'){ echo 'Tempat Tinggal';}elseif($itemA[0]->PEMANFAATAN == '2'){ echo 'Disewakan' ;}elseif($itemA[0]->PEMANFAATAN == '3'){ echo 'Pertanian';}elseif($itemA[0]->PEMANFAATAN == '4'){ echo 'Perkebunan';}elseif($itemA[0]->PEMANFAATAN == '5'){ echo 'Perikanan';}elseif($itemA[0]->PEMANFAATAN == '6'){ echo 'Pertambangan';}else{echo 'Lainnya' ;}?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php if(@$itemB[0]->PEMANFAATAN == '1'){ echo 'Tempat Tinggal';}elseif(@$itemB[0]->PEMANFAATAN == '2'){ echo 'Disewakan' ;}elseif(@$itemB[0]->PEMANFAATAN == '3'){ echo 'Pertanian';}elseif(@$itemB[0]->PEMANFAATAN == '4'){ echo 'Perkebunan';}elseif(@$itemB[0]->PEMANFAATAN == '5'){ echo 'Perikanan';}elseif(@$itemB[0]->PEMANFAATAN == '6'){ echo 'Pertambangan';}else{echo 'Lainnya' ;}?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Ket Lainnya</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->KET_LAINNYA;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->KET_LAINNYA;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Tahun Perolehan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->TAHUN_PEROLEHAN_AWAL;?> s/d <?php echo $itemA[0]->TAHUN_PEROLEHAN_AKHIR;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->TAHUN_PEROLEHAN_AWAL;?> s/d <?php echo @$itemB[0]->TAHUN_PEROLEHAN_AKHIR;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nilai Perolehan</label></td>
                <td><div class="control-label" style="text-align:left !important;">
                    <?php 
                        switch($itemA[0]->MATA_UANG){
                            case '1' : echo 'IDR'; break;
                            case '2' : echo 'USD'; break;
                            case '3' : echo 'Yen'; break;
                        }
                    ?>
                     <?php echo @number_format( @$itemA[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
                <td><div class="control-label" style="text-align:left !important;">
                    <?php 
                        switch(@$itemB[0]->MATA_UANG){
                            case '1' : echo 'IDR'; break;
                            case '2' : echo 'USD'; break;
                            case '3' : echo 'Yen'; break;
                        }
                    ?>
                     <?php echo @number_format( @@$itemB[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nilai Pelaporan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : $itemA[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?> </div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @$itemB[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : @$itemB[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?> </div></td>
            </tr>
            <tr>
                <td><label class="control-label"></label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @number_format( @$itemA[0]->NILAI_PELAPORAN , 2 , ',' , '.' );?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo @number_format( @$itemB[0]->NILAI_PELAPORAN , 2 , ',' , '.' );?></div></td>
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
    $(function () {

            $('input[name="KD_ISO3_NEGARA"]').select2({
                placeholder: "Pilih Negara",
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/efill/lhkpn/getNegara')?>",
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
                        $.ajax("<?=base_url('index.php/efill/lhkpn/getNegara')?>/"+id, {
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
           
            $("#UANG").select2();

            // $.post("index.php/efill/lhkpn/daftar_uang", function(html){
            //     $.each(html, function(index, value){
            //         $("#UANG").append("<option value='"+index+"'>"+value+"</option>");
            //     });
            //     $("#UANG").select2();
            // }, 'json');
        

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
                'groupSeparator' : '.',
                'autoGroup': true,
                'removeMaskOnSubmit': false
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
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

            // $('input[name="KEC"]').select2({
            //     minimumInputLength: 0,
            //     ajax: {
            //         url: "<?=base_url('index.php/efill/lhkpn/getKecamatan')?>",
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
            //         var id = $(element).val();
            //         if (id !== "") {
            //             $.ajax("<?=base_url('index.php/efill/lhkpn/getKecamatan')?>/"+id, {
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
            // $('input[name="KEL"]').select2({
            //     minimumInputLength: 0,
            //     ajax: {
            //         url: "<?=base_url('index.php/efill/lhkpn/getKelurahan')?>",
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
            //         var id = $(element).val();
            //         if (id !== "") {
            //             $.ajax("<?=base_url('index.php/efill/lhkpn/getKelurahan')?>/"+id, {
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

            /* new script for provinsi, kabupaten / kota, kecamatan, dan kelurahan */
            $('#KAB_KOT').select2();
            $('#KEC').select2();
            $('#KEL').select2();
            prov();
            var requiredCheckboxesasalusul = $('.asalusul');

            requiredCheckboxesasalusul.change(function(){

                if(requiredCheckboxesasalusul.is(':checked')) {
                    requiredCheckboxesasalusul.removeAttr('required');
                }

                else {
                    requiredCheckboxesasalusul.attr('required', 'required');
                }
            });

            var requiredCheckboxespemanfaatan = $('input[name="PEMANFAATAN[]"]');

            requiredCheckboxespemanfaatan.change(function(){

                if(requiredCheckboxespemanfaatan.is(':checked')) {
                    requiredCheckboxespemanfaatan.removeAttr('required');
                }

                else {
                    requiredCheckboxespemanfaatan.attr('required', 'required');
                }
            });
        });

        function prov () {
            $.post("index.php/efill/lhkpn/daftar_provinsi", function(html){
                $.each(html, function(index, value){
                    $("#PROV").append("<option value='"+value['ID_PROV']+"'>"+value['PROV']+"</option>");
                });
                $("#PROV").select2();
            }, 'json');
        }        
        function kabkot(){
            $("#KAB_KOT").prop('disabled', false);
            $("#KAB_KOT").empty();
            $.post("index.php/efill/lhkpn/daftar_kabkot/"+$("#PROV").val(), function(html){
                $("#KAB_KOT").append("<option value=''>-Pilih Kabupaten/Kota-</option>");
                $.each(html, function(index, value){
                    $("#KAB_KOT").append("<option value='"+index+"'>"+value+"</option>");
                });
                kec();
                $("#KAB_KOT").select2();
            }, 'json');
        }
        function kec() {
            $("#KEC").prop('disabled', false);
            $("#KEC").empty();
            $.post("index.php/efill/lhkpn/daftar_kec/"+$("#PROV").val()+'/'+$("#KAB_KOT").val(), function(html){
                $.each(html, function(index, value){
                    $("#KEC").append("<option value='"+index+"'>"+value+"</option>");
                });
                kel();
                $("#KEC").select2();
            }, 'json');
        }
        function kel() {
            $("#KEL").prop('disabled', false);
            $("#KEL").empty();
            $.post("index.php/efill/lhkpn/daftar_kel/"+$("#PROV").val()+'/'+$("#KAB_KOT").val()+'/'+$("#KEC").val(), function(html){
                $.each(html, function(index, value){
                    $("#KEL").append("<option value='"+index+"'>"+value+"</option>");
                });
                $("#KEL").select2();
            }, 'json');
        }

        function luar(){
            $('.dalamnegeri').attr('required', false);
            $('.luarnegeri').attr("required", true);
            $(".lokasi").hide();
            $('#showLuar').show();
        }

        function dalam(){
            $('.dalamnegeri').attr("required", true);
            $('.luarnegeri').attr('required', false);
            $(".lokasi").show();
            $('#showLuar').hide();
        }

</script>


<?php 
    function deletehartatidakbergerak($id){
        $this->load->model('mglobal', '', TRUE);
        $this->load->model('mlhkpnharta', '', TRUE);

        $join = [
            ['table' => 'M_NEGARA'                  , 'on' => 'M_NEGARA.ID = ID_NEGARA'       ,   'join'  =>  'left'],
            ['table' => 'M_AREA as provinsi'        , 'on' => 'provinsi.IDPROV = '.'data.PROV and provinsi.IDKOT = "" and provinsi.IDKEC = "" and provinsi.IDKEL = ""','join' => 'left'],
            ['table' => 'M_AREA as kabkot'          , 'on' => 'kabkot.IDKOT   = '.'data.KAB_KOT and provinsi.IDPROV = '.'data.PROV and kabkot.IDKEC = "" and kabkot.IDKEL = ""','join' => 'left'],
            ['table' => 'M_MATA_UANG'               , 'on' => 'MATA_UANG  = ID_MATA_UANG'],
        ];
                        
        $where['data.ID' ]         = $id;
        $KABKOT = "(SELECT NAME FROM M_AREA as area WHERE data.PROV = area.IDPROV AND CAST(data.KAB_KOT as UNSIGNED) = area.IDKOT AND '' = area.IDKEC AND '' = area.IDKEL) as KAB_KOT";
        $select = 'data.ID as ID, data.ID_HARTA as ID_HARTA, data.ID_LHKPN as ID_LHKPN, data.NEGARA as NEGARA, data.JALAN as JALAN, data.KEC, data.KEL, '.$KABKOT.', provinsi.NAME as PROV,  data.LUAS_TANAH as LUAS_TANAH, data.LUAS_BANGUNAN as LUAS_BANGUNAN, data.KETERANGAN as KETERANGAN, data.JENIS_BUKTI as JENIS_BUKTI, data.NOMOR_BUKTI as NOMOR_BUKTI, data.ATAS_NAMA as ATAS_NAMA, data.ASAL_USUL as ASAL_USUL, data.PEMANFAATAN as PEMANFAATAN, data.KET_LAINNYA as KET_LAINNYA, data.TAHUN_PEROLEHAN_AWAL as TAHUN_PEROLEHAN_AWAL, data.TAHUN_PEROLEHAN_AKHIR as TAHUN_PEROLEHAN_AKHIR, data.MATA_UANG as MATA_UANG, data.NILAI_PEROLEHAN as NILAI_PEROLEHAN, data.NILAI_PELAPORAN as NILAI_PELAPORAN, data.JENIS_NILAI_PELAPORAN as JENIS_NILAI_PELAPORAN, data.IS_ACTIVE as IS_ACTIVE, data.JENIS_LEPAS as JENIS_LEPAS, data.TGL_TRANSAKSI as TGL_TRANSAKSI, data.NILAI_JUAL as NILAI_JUAL, data.NAMA_PIHAK2 as NAMA_PIHAK2, data.ALAMAT_PIHAK2 as ALAMAT_PIHAK2, data.CREATED_TIME as CREATED_TIME, data.CREATED_BY as CREATED_BY, data.CREATED_IP as CREATED_IP, data.UPDATED_TIME as UPDATED_TIME, data.UPDATED_BY as UPDATED_BY, data.UPDATED_IP as UPDATED_IP, M_NEGARA.NAMA_NEGARA as NAMA_NEGARA';
        $item   = $this->mglobal->get_data_all('T_LHKPN_HARTA_TIDAK_BERGERAK as data', $join, $where, $select);

        $data = array(
            'form'  => 'delete',
            'item'   => $item,
        );
        $this->load->view(strtolower(__CLASS__).'/'.strtolower(__CLASS__).'_hartatidakbergerak_form', $data);
    }
?>