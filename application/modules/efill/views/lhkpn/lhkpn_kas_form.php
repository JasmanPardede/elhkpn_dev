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
    <form class="form-horizontal" method="post" id="ajaxFormAddkas" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">
        <div class="con-body">
            <div class="box-body">
                <div class="col-md-6">

                    <!-- <div class="form-group">
                      
                            <input name="type_file" value="0" checked="" type="radio"> Single File [pdf] &nbsp;&nbsp;&nbsp;
                            <!-- <input name="type_file" value="1" type="radio"> Multiple File [jpg, jpeg, png] -->
                      
                    <!-- </div> -->
                     <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_ksk'); ?>
                        
                            <select name="KODE_JENIS" id="" class="form-control Kode-Jenis" required >
                                 <option value=''></option>
                            <?php
                                $ia = 0;
                                foreach ($jenisharta as $key => $value) {
                                    $ia++;
                                    ?>
                                        <option value="<?=$key?>"><?=$value?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        
                    </div>
                    <div class="con-sk">
                        <div class="form-group">
                            <label>Bukti Dokumen/Rekening (pdf/jpg/png/jpeg)  </label><?= FormHelpPopOver('bukti_dokumen_ksk'); ?>
                           
                                    <input type="file" name="FILE_FOTO[]" class="FILE_FOTO" />
                              
                                <!-- <span class='help-block'>Type File: <span class="type-of">pdf</span> Max File: 500KB</span> -->
                           
                            <!-- <div class="col-sm-1" style="display: none;" id="con-btn"> -->
                                <button class="btn btn-success input-xs" type="button" onclick="addForm();"><i class="fa fa-plus"></i> </button>
                            <!-- </div> -->
                        </div>
                    </div>

                   <!--  <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_ksk'); ?>
                        
                            <select name="KODE_JENIS" id="" class="form-control Kode-Jenis" required >
                                <option value=''>--Jenis--</option>
                            <?php
                                $ia = 0;
                                foreach ($jenisharta as $key => $value) {
                                    $ia++;
                                    ?>
                                        <option value="<?=$key?>"><?=$value?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        
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

                    <div class="form-group">
                        <label>Nama Bank / Lembaga Keuangan <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_bank_ksk'); ?>
                       
                                 <input required type='text' size='40' name='NAMA_BANK'  value=''  class="form-control input_capital nama_bank">
                            <span style='display:none;' class='strip'>-</span>
                      
                    </div>
					
					<!--<div class="form-group">
                        <label>Nama Bank / Lembaga Keuangan <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_bank_ksk'); ?>
                       
                            <input required type='text' size='40' name='NAMA_BANK' id='NAMA_BANK' value=''  class="form-control nama_bank">
                            <span style='display:none;' class='strip'>-</span>
                      
                    </div>-->
	
                    <div class="form-group">
                        <label>No Rekening <span class="red-label">*</span> </label> <?= FormHelpPopOver('no_rek_ksk'); ?>
                       
                            <input required type='text' size='40' name='NOMOR_REKENING' id='NOMOR_REKENING' value=''  class="form-control rek_bank">
                            <span style='display:none;' class='strip'>-</span>
                      
                    </div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                      <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>
                     <div class="form-group"></div>

                 <!--    <div class="form-group">
                        <label class="col-sm-4 control-label">Tahun Buka Rekening :</label>
                        <div class="col-sm-8">
                            <input class="form-control year-picker thn_rek" name="TAHUN_BUKA_REKENING" placeholder="Tahun Buka Rekening" type="text">
                        </div>
                    </div> -->
                        
                     <div class="form-group">
                        <label><span class="red-label test">***</span>Bukti Dokumen dapat dikirim Langsung ke KPK <span class="red-label test">***</span> </label> 
                      
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Asal Usul <span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_ksk'); ?>
                       
                        <?php $i = 1; foreach($asalusuls as $asalusul){ ?>
                            <div class="checkbox">
                                <div class="col-sm-<?php echo ($asalusul->IS_OTHER !== '1' ? '12' : '5' ) ?>">
                                    <label>
                                        <input required <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" class="asalusul" name='ASAL_USUL[]' value="<?php echo $asalusul->ID_ASAL_USUL?>">
                                        <?php echo $i++ . '.' .  $asalusul->ASAL_USUL; ?>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                       
                    </div>
                    <div class="form-group">
                        <label>Jenis Mata Uang <span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_mata_uang_ksk'); ?>
                       
                            <select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANG" onchange="mataUang();" required>
                                <?php
                                    foreach ($uang as $key => $value) {
                                        ?>
                                            <option <?php echo ($key == '1') ? 'selected' : ''; ?> value="<?=$key?>"><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                       
                    </div>
                    <div class="form-group">
                        <label>Nilai Kurs  </label>  <?= FormHelpPopOver('kurs_ksk'); ?>
                        
                            <input style="text-align: left;"  type='text' size='40' name='NILAI_KURS' id='NILAI_KURS' value=''  class="form-control int">
                       
                    </div>
                    <div class="form-group">
                        <label>Nilai Saldo <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_saldo_ksk'); ?>
                        
                            <input style="text-align: left;" required type='text' size='40' name='NILAI_SALDO' id='NILAI_SALDO' value=''  class="form-control int">
                       
                    </div>

                    <div class="form-group">
                        <label>Ekuivalen Rp <span class="red-label">*</span> </label> <?= FormHelpPopOver('ekuivalen_ksk'); ?>
                      
                            <input style="text-align: left;" required type='text' size='40' name='NILAI_EQUIVALEN' id='NILAI_EQUIVALEN' value=''  class="form-control int">
                      
                    </div>
                </div>
            </div>
            <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <input type='hidden' name='ID_HARTA' id='ID_HARTA' value='' class="form-control">
            <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?=@$id_lhkpn;?>">
            <input type="hidden"  name='GOLONGAN_HARTA' id='GOLONGAN_HARTA' class="form-control" placeholder="Golongan Harta">
            <button type="submit" class="btn btn-sm btn-primary" id="btnSimpan">Simpan</button>
            <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
        </div>
        </div>
        <div id="pelepasan-con"></div>
    </form>

    <div id="hid-sk" style="display: none;">
         <div class="form-group con-temp">
                
                        <input type="file" name="FILE_FOTO[]" class="FILE_FOTO" />
                    
                
                    <button class="btn btn-danger input-xs" type="button" onclick="removeRow(this);"><i class="fa fa-times"></i> </button>
               
            </div>
    </div>

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
                   
                        <input type="text" name='NAMA[]' class="form-control required">
                    
                </div>

                <div class="form-group">
                    <label>Alamat </label>
                   
                        <textarea name='ALAMAT[]' class="form-control"></textarea>
                   
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
  $('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });
    function mataUang(){
        // var cek = $( "#UANG option:selected" ).val();
        $('#NILAI_SALDO').keypress(function(){
            cek = $( "#UANG" ).val();
            if(cek == 1){
                var $field = $(this);    
                var beforeVal = $field.val();

                setTimeout(function() {
                    var afterVal = $field.val();
                    // var NILAI_EQUIVALEN = parseInt(NILAI_SALDO * NILAI_KURS) || 0;
                    $('#NILAI_EQUIVALEN').val(afterVal);
                }, 0);
            }
            if(cek != 1){
                 var $field = $(this);    
                 var afterVal = $field.val();
                 var v_kurs = $('#NILAI_KURS').val();
                 var NILAI_SALDO = parseFloat(afterVal.replace(/\./g, ''));
                 var NILAI_KURS = parseFloat(v_kurs.replace(/\./g, ''));
                 $('#NILAI_EQUIVALEN').val(NILAI_SALDO*NILAI_KURS);
            //      var NILAI_SALDO = parseFloat(v_saldo.replace(/\./g, ''));
            //      var NILAI_KURS = parseFloat(v_kurs.replace(/\./g, ''));
            //       var NILAI_EQUIVALEN = parseInt(NILAI_SALDO * NILAI_KURS) || 0;
            // $('#NILAI_EQUIVALEN').val(numeral(NILAI_EQUIVALEN).format('0,0').replace(/,/g, '.'));
            }
        });
    }

    function addForm()
    {
        var form_sk = $('#hid-sk').html();
        $('.con-sk').append(form_sk);
    }

    function removeRow(ele)
    {
        $(ele).closest('.form-group').remove();
    }

    $(document).ready(function() {
        var arr     = ['jpg','png','jpeg','pdf'];

        $('input[name="type_file"]').change(function (e) {
            $('input[name="FILE_FOTO[]"]').val('');
            var val = $(this).val();
            if(val == '1'){
                $('#con-btn').show();
                $('.type-of').text('jpg, png, jpeg.');
                arr = ['jpg','png','jpeg'];
            }else{
                $('#con-btn').hide();
                $('.con-sk .con-temp').remove();
                $('.type-of').text('jpg, png, jpeg, pdf.');
                arr = ['jpg','png','jpeg','pdf'];
            }
        });

        mataUang();
        $('.con-sk').on('change', '.FILE_FOTO', function(e){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var maxsize = 5000000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });
        //    var kdJenis = $('.Kode-Jenis').val();
        // if(kdJenis == '1'){
        //     $('.nama_bank').val('-');
        //     $('.rek_bank').val('-');
        //     $('.thn_rek').val('-');
        //     $('.nama_bank').attr('readonly',true);
        //     $('.rek_bank').attr('readonly',true);
        //     $('.thn_rek').attr('readonly',true);
        //     $('.nama_bank').hide;
        //     $('.rek_bank').hide;
        //     $('.strip').show;
        //     $('.FILE_FOTO').attr('required', true);
        // }else{
        //     $('.nama_bank').val('');
        //     $('.rek_bank').val('');
        //     $('.thn_rek').val('');
        //     $('.nama_bank').show;
        //     $('.rek_bank').show;
        //     $('.strip').hide;
        //     $('.nama_bank').attr('readonly',false);
        //     $('.rek_bank').attr('readonly',false);
        //     $('.thn_rek').attr('readonly',false);
        //     $('.FILE_FOTO').attr('required', false);
        // };

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'rightAlign': false,
            'removeMaskOnSubmit': false
        });
          $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        $("#ajaxFormAddkas").submit(function(event) {
            $('#btnSimpan').prop('disabled', true);
            var urll = $(this).attr('action');
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function (html) {
                    msg = {
                       success : 'Data Berhasil Disimpan!',
                       error  : 'Data Gagal Disimpan!'
                    };
                    if (html == 0) {
                       alertify.error(msg.error);
                    }else{
                        alertify.success(msg.success);
                    }
                    if(html == 1 || isJson(html)){
                        CloseModalBox();
                        var ID = $('#id_lhkpn').val();
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/8/'+ID + '/edit', block:'#block', container:$('#kas').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
                        ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/17/' + ID + '/edit', block: '#block', container: $('#dokumenpendukung').find('.contentTab')});
                    }else{
                        console.log('error');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        // var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/8/'+ID, block:'#block', container:$('#kas').find('.contentTab')});
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
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEditkas" action="index.php/efill/lhkpn/savekas">
        <div class="con-body">
            <div class="box-body">
                <div class="col-md-6">
                      <div class="form-group">
                        <label>jenis<span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_ksk'); ?>
                      
                            <select name="KODE_JENIS" id="" class="form-control Kode-Jenis"  required>
                                  <?php
                                    foreach ($jenisharta as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->KODE_JENIS){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                       
                    </div>

                    <?php
                        if ($item->FILE_BUKTI != '') {
                            ?>
                            <div class="form-group">
                                <label>Old FIle</label>
                              
                                        <a href="<?=base_url('uploads/data_kas/'.$nik.'/'.$item->FILE_BUKTI)?>" target="_blank"><i class="fa fa-download btn btn-default btn-sm"></i></a>
                                        <?=$item->FILE_BUKTI?>
                                  
                            </div>
                            <?php
                        }
                    ?>

                    <!-- <div class="form-group"> -->
                   <!--   
                            <input name="type_file" value="0" checked="" type="radio"> Single File [pdf] &nbsp;&nbsp;&nbsp;
                            <!-- <input name="type_file" value="1" type="radio"> Multiple File [jpg, jpeg, png] -->
                      
                    <!-- </div> -->
                    <div class="con-sk">
                        <div class="form-group">
                            <label>Bukti Dokumen/Rekening (pdf/jpg/png/jpeg)</label> <?= FormHelpPopOver('bukti_dokumen_ksk'); ?>
                         
                                    <input class="FILE_FOTO" type="file" name="FILE_FOTO[]">
                                    <input type='hidden' name="OLD_FILE" value="<?php if(@$item->FILE_BUKTI != '') { echo $item->FILE_BUKTI;}else{ echo '';}?>">
                               
                                <!-- <span class='help-block'>Type File: <span class="type-of">pdf</span> Max File: 500KB</span> -->
                           
                           
                                <button class="btn btn-success input-xs" type="button" onclick="addForm();"><i class="fa fa-plus"></i> </button>
                          
                        </div>
                    </div>
                  <!--   <div class="form-group">
                        <label>jenis<span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_ksk'); ?>
                      
                            <select name="KODE_JENIS" id="" class="form-control Kode-Jenis"  required>
                                  <?php
                                    foreach ($jenisharta as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->KODE_JENIS){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                       
                    </div> -->

                   <div class="form-group">
                        <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_ksk'); ?>
                       <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                            <option></option>
                                <option <?php echo ($item->ATAS_NAMA_REKENING == 'PN YANG BERSANGKUTAN' ? 'selected="selected"' : '') ?> value="PN YANG BERSANGKUTAN">PN YANG BERSANGKUTAN</option>
                                <option <?php echo ($item->ATAS_NAMA_REKENING == 'PASANGAN / ANAK' ? 'selected="selected"' : '') ?> value="PASANGAN / ANAK">PASANGAN / ANAK</option>
                                <option <?php echo ($item->ATAS_NAMA_REKENING == 'LAINNYA' ? 'selected="selected"' : '') ?> value="LAINNYA">LAINNYA</option>
                               
                            </select>
                      
                    </div>

                    <div class="form-group">
                        <label>Nama Bank / Lembaga Keuangan <span class="red-label">*</span> </label><?= FormHelpPopOver('nama_bank_ksk'); ?>
                       
                            <input required placeholder="Nama Bank" type='text' size='40' name='NAMA_BANK' value='<?php echo $item->NAMA_BANK;?>' class="form-control input_capital" style="border:0px;">
                            <!-- <span style='display:none;' class='strip'>-</span> -->
                      
                    </div>
                    <div class="form-group">
                        <label>No Rekening <span class="red-label">*</span> </label><?= FormHelpPopOver('no_rek_ksk'); ?>
                       
                            <input required placeholder="No Rekening" type='text' size='40' name='NOMOR_REKENING' id='NOMOR_REKENING' value='<?php echo $item->NOMOR_REKENING;?>' class="form-control rek_bank">
                            <span style='display:none;' class='strip'>-</span>
                     
                    </div>
                   <!--  <div class="form-group">
                        <label>Tahun Buka Rekening :</label>
                        
                            <input class="form-control year-picker thn_rek" name="TAHUN_BUKA_REKENING" value='<?php echo $item->TAHUN_BUKA_REKENING;?>' placeholder="Tahun Buka Rekening" type="text">
                      
                    </div> -->
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Asal Usul <span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_ksk'); ?>
                      
                        <?php
                        $asalusulselected = explode(',',$item->ASAL_USUL);
                        $i  = 0;
                        $i2 = 1;
                        foreach($asalusuls as $asalusul){ ?>
                            <div class="checkbox">
                                <div class="col-sm-<?php echo ($asalusul->IS_OTHER !== '1' ? '12' : '5' ) ?>">
                                    <label>
                                        <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" name='ASAL_USUL[]' class="asalusul" value="<?php echo $asalusul->ID_ASAL_USUL?>" <?php echo in_array($asalusul->ID_ASAL_USUL, $asalusulselected)?'checked':''; ?>>
                                        <?php echo $i2++ . '.' .  $asalusul->ASAL_USUL; ?>
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
                        <label >Nilai Saldo <span class="red-label">*</span> </label><?= FormHelpPopOver('nilai_saldo_ksk'); ?>
                      
                            <select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANG" required onchange="mataUang();">
                                <?php
                                    foreach ($uang as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->MATA_UANG){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        
                       
                            <input required type='text' size='40' name='NILAI_SALDO' id='NILAI_SALDO' value='<?= @$item->NILAI_SALDO; ?>' placeholder='Nilai Saldo' class="form-control int">
                       
                    </div>
                     <div class="form-group">
                        <label>Nilai Kurs </label><?= FormHelpPopOver('ekuivalen_ksk'); ?>
                       
                            <input  required placeholder="Nilai Kurs " type='text' size='40' name='NILAI_KURS' id='NILAI_KURS' value='<?= @$item->NILAI_KURS; ?>' class="form-control int">
                       
                    </div>

                    <div class="form-group">
                        <label>Ekuivalen Rp <span class="red-label">*</span> </label><?= FormHelpPopOver('ekuivalen_ksk'); ?>
                       
                            <input  required placeholder="Ekuivalen Rp" type='text' size='40' name='NILAI_EQUIVALEN' id='NILAI_EQUIVALEN' value='<?= @$item->NILAI_EQUIVALEN; ?>' class="form-control int">
                       
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input type="hidden"  name='ID' id='ID' class="form-control" value='<?php echo $item->ID;?>' placeholder="Id Harta">
                <input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>">
                <input type="hidden" name="act" value="doupdate">
                <input type='hidden' rea name='ID_HARTA' id='ID_HARTA' value='<?php echo $item->ID_HARTA;?>' class="form-control">
                <input type='hidden' rea name='ID_HARTA_LAMA' id='ID_HARTA_LAMA' value='<?php echo $Titem->ID_HARTA_LAMA;?>' class="form-control">
                <input type='hidden' rea name='ID_LHKPN' id="id_lhkpn" value='<?php echo $item->ID_LHKPN;?>' class="form-control">
                <button type="submit" class="btn btn-sm btn-primary" disabled>Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
        </div>

        <div id="hid-sk" style="display: none;">
             <div class="form-group con-temp">
                
                        <input type="file" name="FILE_FOTO[]" class="FILE_FOTO" />
                    
                
                    <button class="btn btn-danger input-xs" type="button" onclick="removeRow(this);"><i class="fa fa-times"></i> </button>
               
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
                                    <input value="<?php echo $row->NAMA ?>" type="text" name='NAMA[]' class="required form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Alamat :</label>
                                <div class="col-sm-5">
                                    <textarea name='ALAMAT[]' class="form-control"><?php echo $row->ALAMAT ?></textarea>
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
                    <label class="col-sm-4 control-label">Tanggal Transaksi :</label>
                    <div class="col-sm-4">
                        <input type="text"  name='TGL[]' class="form-control datepicker" placeholder="DD/MM/YYYY">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai <span class="title"></span> :</label>
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
                    <label class="col-sm-4 control-label">Nama <span class="red-label">*</span> :</label>
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
$('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });
    function mataUang(){
        // var cek = $( "#UANG option:selected" ).val();
        $('#NILAI_SALDO').keypress(function(){
            cek = $( "#UANG" ).val();
            if(cek == 1){
                var $field = $(this);    
                var beforeVal = $field.val();

                setTimeout(function() {
                    var afterVal = $field.val();
                    $('#NILAI_EQUIVALEN').val(afterVal);
                }, 0);
            }
             if(cek != 1){
                 var $field = $(this);    
                 var afterVal = $field.val();
                 var v_kurs = $('#NILAI_KURS').val();
                 var NILAI_SALDO = parseFloat(afterVal.replace(/\./g, ''));
                 var NILAI_KURS = parseFloat(v_kurs.replace(/\./g, ''));
                 $('#NILAI_EQUIVALEN').val(NILAI_SALDO*NILAI_KURS);
            //      var NILAI_SALDO = parseFloat(v_saldo.replace(/\./g, ''));
            //      var NILAI_KURS = parseFloat(v_kurs.replace(/\./g, ''));
            //       var NILAI_EQUIVALEN = parseInt(NILAI_SALDO * NILAI_KURS) || 0;
            // $('#NILAI_EQUIVALEN').val(numeral(NILAI_EQUIVALEN).format('0,0').replace(/,/g, '.'));
            }
        });
    }

    function addForm()
    {
        var form_sk = $('#hid-sk').html();
        $('.con-sk').append(form_sk);
    }

    function removeRow(ele)
    {
        $(ele).closest('.form-group').remove();
    }

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
        $('#ajaxFormEditkas :input').change(function () {
            $('#ajaxFormEditkas button[type="submit"]').prop('disabled', false);
        })

        mataUang();
        var arr     = ['jpg','png','jpeg','pdf'];

        $('input[name="type_file"]').change(function (e) {
            $('input[name="FILE_FOTO[]"]').val('');
            var val = $(this).val();
            if(val == '1'){
                $('#con-btn').show();
                $('.type-of').text('jpg, png, jpeg.');
                arr = ['jpg','png','jpeg'];
            }else{
                $('#con-btn').hide();
                $('.con-sk .con-temp').remove();
                $('.type-of').text('jpg, png, jpeg, pdf.');
                arr = ['jpg','png','jpeg','pdf'];
            }
        });

        $('.con-sk').on('change', '.FILE_FOTO', function(e){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var maxsize = 5000000;
            if (arr.indexOf(nil) < 0)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.FILE_FOTO').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });

        var kdJenis = $('.Kode-Jenis').val();
        if(kdJenis == '18'){
            $('.nama_bank').val('-');
            $('.rek_bank').val('-');
            $('.thn_rek').val('-');
            $('.nama_bank').attr('readonly',true);
            $('.rek_bank').attr('readonly',true);
            $('.thn_rek').attr('readonly',true);
            $('.nama_bank').hide;
            $('.rek_bank').hide;
            $('.strip').show;
            // $('.FILE_FOTO').attr('required', false);
        }else{
            // $('.nama_bank').val('');
            // $('.rek_bank').val('');
            // $('.thn_rek').val('');
            $('.nama_bank').show;
            $('.rek_bank').show;
            $('.strip').hide;
            $('.nama_bank').attr('readonly',false);
            $('.rek_bank').attr('readonly',false);
            $('.thn_rek').attr('readonly',false);
            // $('.FILE_FOTO').attr('required', true);
        };

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'rightAlign': false,
            'removeMaskOnSubmit': false
        });
          $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        $("#ajaxFormEditkas").submit(function(event) {
            var urll = $(this).attr('action');
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function (html) {
                    msg = {
                       success : 'Data Berhasil Disimpan!',
                       error  : 'Data Gagal Disimpan!'
                    };
                    if (html == 0) {
                       alertify.error(msg.error);
                    }else{
                        alertify.success(msg.success);
                    }
                    if(html == 1){
                        CloseModalBox();
                        var ID = $('#id_lhkpn').val();
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/8/'+ID + '/edit', block:'#block', container:$('#kas').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
                        ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/17/' + ID + '/edit', block: '#block', container: $('#dokumenpendukung').find('.contentTab')});
                    }else{
                        console.log('error');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;
        });

        
        // var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormEdit"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/8/'+ID, block:'#block', container:$('#kas').find('.contentTab')});         
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
    Benarkah Akan Menghapus Harta Tidak Bergerak dibawah ini ?
    <form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/savekas">
      <!--   <div class="box-body">
            <div class="col-md-6">

                <div class="form-group">
                    <label class="col-sm-5 control-label">Kode Jenis Harta :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php if($item->KODE_JENIS == '1'){ echo 'Uang Tunai';}elseif($item->KODE_JENIS == '2'){ echo 'Deposito' ;}elseif($item->KODE_JENIS == '3'){ echo 'Giro';}elseif($item->KODE_JENIS == '4'){ echo 'Tabungan';}else{echo 'Lainnya' ;}?></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Atas Nama :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->ATAS_NAMA_REKENING;?></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Nama Bank / Lembaga Keuangan :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->NAMA_BANK;?></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">No Rekening :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->NOMOR_REKENING;?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Tahun Buka Rekening :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->TAHUN_BUKA_REKENING;?></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Asal Usul Harta :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php if($item->ASAL_USUL == '1'){ echo 'Hasil Sendiri';}elseif($item->ASAL_USUL == '2'){ echo 'Warisan' ;}elseif($item->ASAL_USUL == '3'){ echo 'Hibah';}else{echo 'Hadiah' ;}?></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Nilai Saldo :</label>
                    <div class="col-sm-3" style="margin-top:7px;">
						<?php if($item->MATA_UANG == '1'){ echo 'IDR';}elseif($item->MATA_UANG == '2'){ echo 'USD' ;}elseif($item->MATA_UANG == '3'){ echo 'Yen';}?>
					</div>
                    <div class="col-sm-4" style="margin-top:7px;">
						<?= number_format(@$item->NILAI_SALDO, 0,'','.'); ?>
					</div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Ekuivalen :</label>
                    <div class="col-sm-7" style="margin-top:7px;">Rp <?= number_format(@$item->NILAI_EQUIVALENO, 0,'','.'); ?></div>
                </div>
            </div>
        </div> -->
        <div class="pull-right">
            <input type='hidden' name="OLD_FILE" value="<?php if(@$item->FILE_BUKTI != '') { echo $item->FILE_BUKTI;}else{ echo '';}?>">
			<input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="ID_LHKPN" id="id_lhkpn" value="<?php echo $item->ID_LHKPN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-md btn-danger">Hapus</button>
            <input type="reset" class="btn btn-md btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormDelete"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/8/'+ID, block:'#block', container:$('#kas').find('.contentTab')});
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
                 var ID = $('#id_lhkpn').val();
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/8/'+ID + '/edit', block:'#block', container:$('#kas').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
            })
            return false;
        })        
    });
</script>
<?php
}
?>

<?php
if($form=='detail'){
?>
<div id="wrapperFormDetail">
    <form class="form-horizontal" action="javascript:void(0);">
        <div class="box-body">
            <div class="col-md-6">

                <div class="form-group">
                    <label class="col-sm-5 control-label">Kode Jenis Harta :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php if($item->KODE_JENIS == '1'){ echo 'Uang Tunai';}elseif($item->KODE_JENIS == '2'){ echo 'Deposito' ;}elseif($item->KODE_JENIS == '3'){ echo 'Giro';}elseif($item->KODE_JENIS == '4'){ echo 'Tabungan';}else{echo 'Lainnya' ;}?></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Atas Nama :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->ATAS_NAMA_REKENING;?></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Nama Bank :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->NAMA_BANK;?></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">No Rekening :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->NOMOR_REKENING;?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Tahun Buka Rekening :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php echo $item->TAHUN_BUKA_REKENING;?></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Asal Usul Harta :</label>
                    <div class="col-sm-7" style="margin-top:7px;"><?php if($item->ASAL_USUL == '1'){ echo 'Hasil Sendiri';}elseif($item->ASAL_USUL == '2'){ echo 'Warisan' ;}elseif($item->ASAL_USUL == '3'){ echo 'Hibah';}else{echo 'Hadiah' ;}?></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Nilai Saldo :</label>
                    <div class="col-sm-3" style="margin-top:7px;">
						<?php if($item->MATA_UANG == '1'){ echo 'IDR';}elseif($item->MATA_UANG == '2'){ echo 'USD' ;}elseif($item->MATA_UANG == '3'){ echo 'Yen';}?>
					</div>
                    <div class="col-sm-4" style="margin-top:7px;">
						<?= @$item->NILAI_SALDO; ?>
					</div>
                </div>

                <div class="form-group">
                    <label class="col-sm-5 control-label">Ekuivalen :</label>
                    <div class="col-sm-7" style="margin-top:7px;">Rp <?= @$item->NILAI_EQUIVALEN; ?></div>
                </div>
            </div>
        </div> 
        
        <div class="pull-right">
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<?php
}
?>

<?php
if($form=='kaskk'){
?>
<div id="wrapperFormAdd">
    <div class="box-body">
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-sm-12">
                    <p class="form-control-static"><img src="./uploads/data_kas/<?= @$item->NIK."/".@$item->FILE_BUKTI; ?>" width="100%"/></p></br>
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
?>

<?php if($form=='addPenerimaanPekerjaan'){ ?>
<div id="wrapperPenerimaan">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Uraian <span class="red-label">*</span> :</label>
                    <div class="col-sm-6">
                        <input required type='text' size='40' name='URAIAN' id='URAIAN' value='' placeholder="Uraian" class="form-control">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-sm-5 control-label">Jumlah <span class="red-label">*</span> :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon">Rp.</span>
							<input required type='text' size='40' name='JUMLAH' id='JUMLAH' value='' placeholder="Jumlah" class="form-control int">
						</div>
					</div>
                </div>
            </div>
        </div>        

        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php } ?>
<?php if($form=='addPenerimaanKekayaan'){ ?>
<div id="wrapperPenerimaan">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Uraian <span class="red-label">*</span> :</label>
                    <div class="col-sm-6">
                        <input required type='text' size='40' name='URAIAN' id='URAIAN' value='' placeholder="Uraian" class="form-control">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-sm-5 control-label">Jumlah <span class="red-label">*</span> :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon">Rp.</span>
							<input required type='text' size='40' name='JUMLAH' id='JUMLAH' value='' placeholder="Jumlah" class="form-control int">
						</div>
					</div>
                </div>
            </div>
        </div>        

        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php } ?>
<?php if($form=='addPenerimaanLainnya'){ ?>
<div id="wrapperPenerimaan">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Uraian <span class="red-label">*</span> :</label>
                    <div class="col-sm-6">
                        <input required type='text' size='40' name='URAIAN' id='URAIAN' value='' placeholder="Uraian" class="form-control">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-sm-5 control-label">Jumlah <span class="red-label">*</span> :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon">Rp.</span>
							<input required type='text' size='40' name='JUMLAH' id='JUMLAH' value='' placeholder="Jumlah" class="form-control int">
						</div>
					</div>
                </div>
            </div>
        </div>        

        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php } ?>
<!--#####################-->
<?php if($form=='addPengeluaranUmum'){ ?>
<div id="wrapperPengeluaran">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Uraian <span class="red-label">*</span> :</label>
                    <div class="col-sm-6">
                        <input required type='text' size='40' name='URAIAN' id='URAIAN' value='' placeholder="Uraian" class="form-control">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-sm-5 control-label">Jumlah <span class="red-label">*</span> :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon">Rp.</span>
							<input required type='text' size='40' name='JUMLAH' id='JUMLAH' value='' placeholder="Jumlah" class="form-control int">
						</div>
					</div>
                </div>
            </div>
        </div>        

        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php } ?>
<?php if($form=='addPengeluaranHarta'){ ?>
<div id="wrapperPengeluaran">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Uraian <span class="red-label">*</span> :</label>
                    <div class="col-sm-6">
                        <input required type='text' size='40' name='URAIAN' id='URAIAN' value='' placeholder="Uraian" class="form-control">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-sm-5 control-label">Jumlah <span class="red-label">*</span> :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon">Rp.</span>
							<input required type='text' size='40' name='JUMLAH' id='JUMLAH' value='' placeholder="Jumlah" class="form-control int">
						</div>
					</div>
                </div>
            </div>
        </div>        

        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php } ?>
<?php if($form=='addPengeluaranLainnya'){ ?>
<div id="wrapperPengeluaran">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-5 control-label">Uraian <span class="red-label">*</span> :</label>
                    <div class="col-sm-6">
                        <input required type='text' size='40' name='URAIAN' id='URAIAN' value='' placeholder="Uraian" class="form-control">
                    </div>
                </div>
                <div class="form-group">
					<label class="col-sm-5 control-label">Jumlah <span class="red-label">*</span> :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon">Rp.</span>
							<input required type='text' size='40' name='JUMLAH' id='JUMLAH' value='' placeholder="Jumlah" class="form-control int">
						</div>
					</div>
                </div>
            </div>
        </div>        

        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
    });
</script>
<?php
}
if($form == 'pelepasan'){
?>


    <div id="wrapperFormpelepasankas">
        <form class='form-horizontal' method="post" id="ajaxFormAddkas" action="index.php/efill/lhkpn/savekas" enctype="multipart/form-data">

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
                            <input value="<?= ($do == 'update' ? $data->NILAI_PELEPASAN : $nipor->NILAI_EQUIVALEN); ?>" required type="text" name='NILAI' class="form-control int" placeholder="Nilai Pelepasan">
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
                <input type="hidden" id="idLhkpn" name="id_lhkpn" value="<?=$id_lhkpn?>">
                <input type='hidden' name='ID_HARTA' value="<?=$id;?>">
                <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?=@$id_lhkpn;?>">
                <input type="hidden" name="type" value="<?=$do;?>">
                <?php
                    if($do == 'update'){
                        echo '<input type="hidden" name="ID" value="'.$data->ID.'">';
                        ?>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/deletepelepasankas/<?=$id?>/<?php echo $data->ID; ?>" title="Delete">Hapus</button>
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
            // ng.formProcess($("#ajaxFormAddkas"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/8/'+ID, block:'#block', container:$('#kas').find('.contentTab')});
            $("#ajaxFormAddkas").submit(function(){
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
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/8/'+ID + '/edit', block:'#block', container:$('#kas').find('.contentTab')});
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
                    ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/1/' + ID + '/edit', block: '#block', container: $('#final').find('.contentTab')});
                })
                return false;
            })

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
        $("#wrapperFormpelepasankas .btn-delete").click(function (e) {
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
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/8/'+ID + '/edit', block:'#block', container:$('#kas').find('.contentTab')});
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
            });
            
            return false;
        });
    </script>
<?php } ?>

<?php
if($form=='perbandingan'){
    
    $aKode = [
        '1' => 'Uang Tunai',
        '2' => 'Deposito',
        '3' => 'Giro',
        '4' => 'Tabungan',
        '5' => 'Lainnya'];
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
                <td><label class="control-label">Kode Jenis</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$aKode[$itemA[0]->KODE_JENIS]?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$aKode[@$itemB[0]->KODE_JENIS]?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Atas Nama</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$itemA[0]->ATAS_NAMA?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->ATAS_NAMA?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nama Bank</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$itemA[0]->NAMA_BANK?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->NAMA_BANK?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">No Rekening</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$itemA[0]->REKENING?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->REKENING?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Tahun Buka Rekening</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$itemA[0]->TAHUN_BUKA?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->TAHUN_BUKA?></div></td>
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
                <td><label class="control-label">Nilai Saldo</label></td>
                <td><div class="control-label" style="text-align:right !important;">
                    <?php 
                        echo $uang[$itemA[0]->MATA_UANG];
                    ?>
                     <?php echo @number_format( @$itemA[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
                <td><div class="control-label" style="text-align:right !important;">
                    <?php 
                        echo $uang[$itemB[0]->MATA_UANG];
                    ?>
                     <?php echo @number_format( @$itemB[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
            </tr>
            <tr>
                <td><label class="control-label">Saldo</label></td>
                <td><div class="control-label" style="text-align:right !important;">Rp. <?php echo @number_format( @$itemA[0]->NILAI_SALDO , 2 , ',' , '.' );?></div></td>
                <td><div class="control-label" style="text-align:right !important;">Rp. <?php echo @number_format( @$itemB[0]->NILAI_SALDO , 2 , ',' , '.' );?></div></td>
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

        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });     
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
        $(".int").inputmask("integer", {
            groupSeparator : '.',
            'autoGroup': true,
            'rightAlign': false,
            'removeMaskOnSubmit': false,
            'digits': 0
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

        $('.Kode-Jenis').on('change', function() {
          var jenis=this.value;

          if (jenis == 18)
            {
                $('.nama_bank').val('-');
                $('.rek_bank').val('-');
                $('.thn_rek').val('-');
                $('.nama_bank').attr('readonly',true);
                $('.rek_bank').attr('readonly',true);
                $('.thn_rek').attr('readonly',true);
                $('.nama_bank').hide;
                $('.rek_bank').hide;
                $('.strip').show;
                // $('.FILE_FOTO').attr('required', false);
                $('.label-bukti').hide();
            }
            else
            {
                $('.nama_bank').val('');
                $('.rek_bank').val('');
                $('.thn_rek').val('');
                $('.nama_bank').show;
                $('.rek_bank').show;
                $('.strip').hide;
                $('.nama_bank').attr('readonly',false);
                $('.rek_bank').attr('readonly',false);
                $('.thn_rek').attr('readonly',false);
                // $('.FILE_FOTO').attr('required', true);
                $('.label-bukti').show();
            };
        });

        var id_lhkpn = $('#id_lhkpn').val();
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
        $('#NAMA_BANK').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getNamaBank')?>/",
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
                str = $(element).val();
                bfval = str.split("(");
                bfval = bfval[0].trim();
                var id = encodeURIComponent(bfval);
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getNamaBank')?>/"+id, {
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