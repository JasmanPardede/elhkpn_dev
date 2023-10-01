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
    <form class="form-horizontal" method="post" id="ajaxFormAddsuratberharga" action="index.php/efill/lhkpn/savesuratberharga" enctype="multipart/form-data">
        <div class="con-body">
            <div class="box-body">
                <div class="col-md-5">
                    <!-- <div class="form-group">
                        <label class="col-sm-4 control-label">Bukti Surat Berharga <span class="red-label">*</span> :</label>
                        <div class="col-sm-8">
                            <div class='col-sm-12'>
                                <input type="file" name="FILE1" class="FILE_BUKTI" required> <span class=" help-block">Type File: png, jpg, jpeg.  Max File: 2000KB</span>
                                <input type="file" name="FILE2" class="FILE_BUKTI"> <span class=" help-block">Type File: png, jpg, jpeg.  Max File: 2000KB</span>
                                <input type="file" name="FILE3" class="FILE_BUKTI"> <span class=" help-block">Type File: png, jpg, jpeg.  Max File: 2000KB</span>
                            </div>
                        </div>
                    </div> -->
                   
                 <!--    <div class="form-group">
                      
                            <input name="type_file" value="0" checked="" type="radio"> Single File [pdf] &nbsp;&nbsp;&nbsp;
                            <!-- <input name="type_file" value="1" type="radio"> Multiple File [jpg, jpeg, png] -->
                       
                 <!--    </div> -->
					  <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_sb'); ?>
                      
                            <select name="KODE_JENIS" class="form-control" required>
                                 <option value=''></option>
                                <?php
                                $ia = 0;
                                foreach ($jenissurat as $key => $value) {
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
                            <label >Bukti Dokumen/Rekening (pdf/jpg/png/jpeg) </label> <?= FormHelpPopOver('bukti_dokumen_sb'); ?>
                            
                                
                                    <input type="file" name="FILE_FOTO[]" class="FILE_BUKTI" />
                            
                                <!-- <span class='help-block'>Type File: <span class="type-of"</span> Max File: 500KB</span> -->
                         
                            <div class="col-sm-1" style="display: none;" id="con-btn">
                                <button class="btn btn-success input-xs" type="button" onclick="addForm();"><i class="fa fa-plus"></i> </button>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label>Nomor Rekening / No Nasabah <span class="red-label">*</span> </label> <?= FormHelpPopOver('email'); ?>
                      
                           <input type="text" name="NOMOR_REKENING" id="NOMOR_REKENING" class="form-control  " required/>
                       
                    </div>
                   <!--  <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_sb'); ?>
                      
                            <select name="KODE_JENIS" class="form-control" required>
                                 <option value=''></option>
                                <?php
                                $ia = 0;
                                foreach ($jenissurat as $key => $value) {
                                    $ia++;
                                    ?>
                                        <option value="<?=$key?>"><?=$value?></option>
                                    <?php
                                }
                            ?>
                            </select>
                      
                    </div> -->
                  <!--   <div class="form-group">
                        <label class="col-sm-4 control-label">Nama Surat Berharga <span class="red-label">*</span> :</label>
                        <div class="col-sm-8">
                            <input required class="form-control" name="NAMA_SURAT_BERHARGA" placeholder="Nama Surat Berharga" type="text">
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label >Penerbit / Perusahaan <span class="red-label">*</span> </label> <?= FormHelpPopOver('penerbit_sb'); ?>
                      
                            <input required class="form-control input_capital" name="NAMA_PENERBIT"  type="text">
                      
                    </div>
                      <div class="form-group">
                        <label > Custodian / Sekuritas <span class="red-label">*</span> </label> <?= FormHelpPopOver('custodian_sb'); ?>
                     
                           <input type="text" name="CUSTODIAN" id="CUSTODIAN" class="form-control input_capital" required/>
                     
                    </div>
                   <!--  <div class="form-group">
                        <label class="col-sm-4 control-label">Jumlah / Satuan <span class="red-label">*</span> :</label>
                        <div class="col-sm-5">
                            <input required class="form-control int" name="JUMLAH" placeholder="Jumlah" type="text">
                        </div>
                        <div class="col-sm-3">
                            <input required class="form-control" name="SATUAN" placeholder="Satuan" type="text">
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
                 <div class="form-group">
                        <label><span class="red-label test">***</span>Bukti Dokumen dapat dikirim Langsung ke KPK <span class="red-label test">***</span> </label> 
                      
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Asal Usul <span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_sb'); ?>
                      
                        <?php $i = 1; foreach($asalusuls as $asalusul){ ?>
                            <div class="checkbox">
                                <div class="col-sm-6">
                                    <label>
                                        <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" class='asalusul' name='ASAL_USUL[]' value="<?php echo $asalusul->ID_ASAL_USUL?>" required>
                                        <?php echo $i++ . '.' . $asalusul->ASAL_USUL; ?>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                     
                    </div>
                    <!--<div class="form-group">
                        <label class="col-sm-4 control-label">Tahun Perolehan <span class="red-label">*</span> :</label>
                        <div class="col-sm-3">
                            <input required class="form-control dari" name="TAHUN_PEROLEHAN_AWAL" placeholder="Tahun Perolehan Awal" type="text">
                        </div>
                        <div class="col-sm-1">
                            s/d
                        </div>
                        <div class=" col-sm-3">
                            <input class="form-control ke" name="TAHUN_PEROLEHAN_AKHIR" placeholder="Tahun Perolehan Akhir" type="text">
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label >Nilai Perolehan (Rp) <span class="red-label">*</span> </label><?= FormHelpPopOver('nilai_perolehan_sb'); ?>
                        <!--<div class="col-sm-3">
                            <select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANGSURAT" required>
                                <?php
                                    foreach ($uang as $key => $value) {
                                        ?>
                                            <option <?php echo ($key == '1') ? 'selected' : ''; ?> value="<?=$key?>"><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>-->
                      
                            <input style="text-align: left;" required class="form-control int" name="NILAI_PEROLEHAN" id='NILAI_PEROLEHAN'  type="text">
                       
                    </div>
                    <div class="form-group">
                        <label >Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_sb'); ?>
                        <!--<div class='col-sm-4'>
                            <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="1" > Appraisal
                        </div>
                        <div class='col-sm-4'>
                            <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="2" chacked> Perkiraan Pasar
                        </div>
                    </div>-->
                    <!--<div class="form-group">
                        <label class="col-sm-4 control-label">Rp. <span class="red-label">*</span></label>-->
                        
                            <input style="text-align: left;" required type="text"  name='NILAI_PELAPORAN' id='NILAI_PELAPORAN' class="int form-control">
                      
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <input class="form-control" name="ID_HARTA" placeholder="Id Harta" type="hidden">
                <input class="form-control" name="ID_HARTA_LAMA" placeholder="Id Harta Lama" type="hidden">
                <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?=@$id_lhkpn;?>">
                <input class="form-control" name="GOLONGAN_HARTA" placeholder="Golongan Harta" type="hidden">
                <input type="hidden" name="act" value="doinsert">
                <button type="submit" class="btn btn-sm btn-primary" id="btnSimpan">Simpan</button>
                <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
            </div>
            <div id="hid-sk" style="display: none;">
                <div class="form-group con-temp">
                    <div class="col-sm-offset-4 col-sm-7">
                        <div class='col-sm-12'>
                            <input type="file" name="FILE_FOTO[]" class="FILE_BUKTI" />
                        </div>
                        <span class='help-block'>Type File: <span class="type-of">pdf</span> Max File: 500KB</span>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-danger input-xs" type="button" onclick="removeRow(this);"><i class="fa fa-times"></i> </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="pelepasan-con"></div>
    </form>

    <div id="hidden-pelepasan" style="display: none;">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <input type="hidden" class="" name="" id="dumptitle" style="" value="" placeholder="">
                <div class="form-group">
                    <label >Tanggal Transaksi </label>
                 
                        <input type="text"  name='TGL[]' class="form-control datepicker" placeholder="DD/MM/YYYY">
                   
                </div>

                <div class="form-group">
                    <label >Nilai(Rp) <span class="title"></span> <span class="red-label">*</span> </label>
                   
                        <input type="text" name='NILAI[]' class="required form-control int title2">
                  
                </div>

                <div class="form-group">
                    <label>Keterangan </label>
                   
                        <input type="text" name='KETERANGAN_PELEPASAN[]' class="form-control" placeholder="Keterangan">
                   
                </div>

                <div class="form-group">
                    <label style="font-size: 15px;">Pihak Kedua</label>
                </div>

                <div class="form-group">
                    <label>Nama <span class="red-label">*</span> </label>
                
                        <input type="text" name='NAMA[]' class="required form-control">
                  
                </div>

                <div class="form-group">
                    <label >Alamat </label>
                   
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
         $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });


        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })
          $('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
             'rightAlign': false,
            'removeMaskOnSubmit': false
        });

        $("#ajaxFormAddsuratberharga").submit(function(event) {
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
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/7/'+ID + '/edit', block:'#block', container:$('#suratberharga').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
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

        // $("#ajaxFormAddsuratberharga").submit(function(){
        //     var url = $(this).attr('action');
        //     var data = $(this).serializeArray();
        //     $.post(url, data, function(res){
        //          msg = {
        //             success : 'Data Berhasil Disimpan!',
        //             error : 'Data Gagal Disimpan!'
        //          };
        //          if (data == 0) {
        //             alertify.error(msg.error);
        //          } else {
        //             alertify.success(msg.success);
        //          }
        //          CloseModalBox();
        //          var ID = $('#id_lhkpn').val();
        //          ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/7/'+ID + '/edit', block:'#block', container:$('#suratberharga').find('.contentTab')});
        //          ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
        //          ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
        //     })
        //     return false;
        // })
        // var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/7/'+ID, block:'#block', container:$('#suratberharga').find('.contentTab')});

        $('.con-sk').on('change', '.FILE_BUKTI', function(e){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var maxsize = 5000000;
            if (arr.indexOf(nil) < 0)
            {
                $(this).val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $(this).val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });        
    });

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
            $(ele).closest('.checkbox').append('<div class="col-sm-2"><a class="btn btn-xs btn-primary" href="javascript:;" onClick="f_show('+val+')">Info</a></div><div class="col-sm-4 text-right"><strong>Rp. 0</strong></div>');
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

    function isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEditsuratberharga" action="index.php/efill/lhkpn/savesuratberharga">
        <div class="con-body">
            <div class="box-body">
                <div class="col-md-6">
                     <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_sb'); ?>
                      
                            <select name="KODE_JENIS" class="form-control" required>
                                <?php
                                    foreach ($jenissurat as $key => $value) {
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
                              
                                        <a href="<?=base_url('uploads/data_suratberharga/'.$nik.'/'.$item->FILE_BUKTI)?>" target="_blank"><i class="fa fa-download btn btn-default btn-sm"></i></a>
                                        <?=$item->FILE_BUKTI?>
                                  
                            </div>
                            <?php
                        }
                    ?>
                  <!--   <div class="form-group">
                       
                            <input name="type_file" value="0" checked="" type="radio"> Single File [pdf] &nbsp;&nbsp;&nbsp;
                            <!-- <input name="type_file" value="1" type="radio"> Multiple File [jpg, jpeg, png] -->
                      
                  <!--   </div> -->
                    <div class="con-sk">
                        <div class="form-group">
                            <label>Bukti Dokumen/Rekening (pdf/jpg/png/jpeg)</label> <?= FormHelpPopOver('bukti_dokumen_sb'); ?>
                          
                                    <input class="FILE_BUKTI" type="file" name="FILE_FOTO[]" <?= (@$item->FILE_BUKTI != '' ? '' : 'required' ); ?> >
                                    <input type='hidden' name="OLD_FILE" value="<?php if(@$item->FILE_BUKTI != '') { echo $item->FILE_BUKTI;}else{ echo '';}?>">
                               
                                <!-- <span class='help-block'>Type File <span class="type-of">pdf</span> Max File: 500KB</span> -->
                        
                          
                                <button class="btn btn-success input-xs" type="button" onclick="addForm();"><i class="fa fa-plus"></i> </button>
                          
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label">Bukti Surat Berharga:</label>
                        <div class="col-sm-9">
                            <div class='col-sm-12'>
                                <input type='hidden' name="OLD_FILE" value="<?php if(@$item->FILE_BUKTI != '') { echo $item->FILE_BUKTI;}else{ echo '';}?>">

                                <input type="file" name="FILE1" class="FILE_BUKTI"> <span class=" help-block">Type File: png, jpg, jpeg.  Max File: 2000KB</span>
                                <input type="file" name="FILE2" class="FILE_BUKTI"> <span class=" help-block">Type File: png, jpg, jpeg.  Max File: 2000KB</span>
                                <input type="file" name="FILE3" class="FILE_BUKTI"> <span class=" help-block">Type File: png, jpg, jpeg.  Max File: 2000KB</span>                                
                            </div>
                        </div>
                    </div> -->
					<div class="form-group">
                        <label>Nomor Rekening / No Nasabah <span class="red-label">*</span> </label> <?= FormHelpPopOver('email'); ?>
						   <input required value="<?php echo $item->NOMOR_REKENING ?>" class="form-control" name="NOMOR_REKENING" type="text">                     
                    </div>
                   <!--  <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_sb'); ?>
                      
                            <select name="KODE_JENIS" class="form-control" required>
                                <?php
                                    foreach ($jenissurat as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->KODE_JENIS){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                      
                    </div> -->
                   <!--  <div class="form-group">
                        <label >Nama Surat Berharga <span class="red-label">*</span> </label>
                    
                            <input required value="<?php echo $item->NAMA_SURAT_BERHARGA ?>" class="form-control" name="NAMA_SURAT_BERHARGA" placeholder="Nama Surat Berharga" type="text">
                       
                    </div> -->
                    <div class="form-group">
                        <label >Penerbit/Perusahaan <span class="red-label">*</span> </label> <?= FormHelpPopOver('penerbit_sb'); ?>
                     
                            <input required value="<?php echo $item->NAMA_PENERBIT ?>" class="form-control" name="NAMA_PENERBIT" placeholder="Penerbit / Pengelola" type="text">
                       
                    </div>
                     <div class="form-group">
                        <label > Custodian / Sekuritas <span class="red-label">*</span> </label> <?= FormHelpPopOver('custodian_sb'); ?>
                     
                           <input type="text" name="CUSTODIAN" id="CUSTODIAN" value="<?php echo $item->CUSTODIAN ?>"class="form-control" required/>
                     
                    </div>
                   <!--  <div class="form-group">
                        <label>Jumlah / Satuan <span class="red-label">*</span> </label>
                       
                            <input required value="<?php echo $item->JUMLAH ?>" class="form-control int" name="JUMLAH" placeholder="Jumlah" type="text">
                      
                      
                            <input required value="<?php echo $item->SATUAN ?>" class="form-control" name="SATUAN" placeholder="Satuan" type="text">
                        
                    </div> -->
                    <div class="form-group">
                        <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_sb'); ?>
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
                        <label>Asal Usul <span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_sb'); ?>
                       
                        <?php
                        $asalusulselected = explode(',',$item->ASAL_USUL);
                        $i  = 0;
                        $i2 = 1;
                        foreach($asalusuls as $asalusul){ ?>
                            <div class="checkbox">
                               
                                    <label>
                                        <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" name='ASAL_USUL[]' value="<?php echo $asalusul->ID_ASAL_USUL?>" <?php echo in_array($asalusul->ID_ASAL_USUL, $asalusulselected)?'checked':''; ?>>
                                        <?php echo $i2++ . '.' .  $asalusul->ASAL_USUL; ?>
                                    </label>
                                
                                <?php if(in_array($asalusul->ID_ASAL_USUL, $asalusulselected) && $asalusul->IS_OTHER === '1'){ ?>
                                   
                                        <a class="btn btn-primary btn-xs" href="javascript:;" onClick="f_show(<?php echo $asalusul->ID_ASAL_USUL ?>)">Info</a>
                                   
                                    <div class="col-sm-4 text-right">
                                        <strong>Rp. <?php echo number_format($pelaporan[$i]->NILAI_PELEPASAN, 0, '.', '.') ?></strong>
                                    </div>
                                    <?php $i++;} ?>
                            </div>
                        <?php } ?>
                       
                    </div>
                   <!--  <div class="form-group">
                        <label>Tahun Perolehan <span class="red-label">*</span> </label>
                       
                            <input required type="text" value="<?php echo $item->TAHUN_PEROLEHAN_AWAL ?>" class="form-control dari" name="TAHUN_PEROLEHAN_AWAL" />
                     
                      
                            s/d
                       
                      
                            <input type="text" value="<?php echo $item->TAHUN_PEROLEHAN_AKHIR ?>" class="form-control ke" name="TAHUN_PEROLEHAN_AKHIR" />
                       
                    </div> -->
                    <div class="form-group">
                        <label >Nilai Perolehan (Rp) <span class="red-label">*</span> </label><?= FormHelpPopOver('nilai_perolehan_sb'); ?>
                       
                         <!--    <select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANGSURAT1" required>
                                <?php
                                    foreach ($uang as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->MATA_UANG){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select> -->
                       
                       
                            <input required value="<?php echo $item->NILAI_PEROLEHAN ?>" class="form-control int" name="NILAI_PEROLEHAN" placeholder="Nilai Perolehan" type="text">
                       
                    </div>
                         <div class="form-group">
                        <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label><?= FormHelpPopOver('nilai_estimasi_pelaporan_sb'); ?>
                        
                          <!--   <label>
                                <input required <?php echo ($item->JENIS_NILAI_PELAPORAN == '1' ? 'checked="checked"' : '')?> name="JENIS_NILAI_PELAPORAN" value="1" type="radio">
                                Appraisal
                            </label>
                     
                            <label>
                                <input required <?php echo ($item->JENIS_NILAI_PELAPORAN == '2' ? 'checked="checked"' : '')?> name="JENIS_NILAI_PELAPORAN" value="2" type="radio">
                                Perkiraan Pasar
                            </label>
                       
                    </div>
                    <div class="form-group">
                        <label>Rp. </label> -->
                      
                            <input type="text" class="form-control int" name="NILAI_PELAPORAN" placeholder="Nilai Pelaporan"  value="<?php echo $item->NILAI_PELAPORAN ?>"/>
                     
                    </div>
                </div>
            </div>
            <div class="pull-right">
            <input value="<?php echo $item->ID; ?>" name="ID" type="hidden">
            <input value="<?php echo $item->ID_HARTA ?>" name="ID_HARTA" type="hidden">
            <input type="hidden" name="ID_LHKPN" id="id_lhkpn" value="<?php echo $item->ID_LHKPN;?>">
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
                                    <input value="<?php echo $row->URAIAN_HARTA ?>" type="text" name='KETERANGAN_PELEPASAN[]' class="form-control">
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
        <div id="hid-sk" style="display: none;">
            <div class="form-group con-temp">
                <div class="col-sm-offset-3 col-sm-6">
                    <div class='col-sm-12'>
                        <input type="file" name="FILE_FOTO[]" class="FILE_FOTO" />
                    </div>
                    <span class='help-block'>Type File: <span class="type-of">pdf</span> Max File: 500KB</span>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-danger input-xs" type="button" onclick="removeRow(this);"><i class="fa fa-times"></i> </button>
                </div>
            </div>
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
                    <label class="col-sm-4 control-label">Nilai <span class="title"></span> <span class="red-label">*</span> :</label>
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
    function f_pelepasan(ele)
    {
        var val = $(ele).val();
        if($(ele).is(':checked')){
            $(ele).closest('.checkbox').append('<div class="col-sm-2"><a class="btn btn-xs btn-primary" href="javascript:;" onClick="f_show('+val+')">Info</a></div><div class="col-sm-4 text-right"><strong>Rp. 0</strong></div>');
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

    function addForm()
    {
        var form_sk = $('#hid-sk').html();
        $('.con-sk').append(form_sk);
    }

    function removeRow(ele)
    {
        $(ele).closest('.form-group').remove();
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
        $('#ajaxFormEditsuratberharga :input').change(function () {
            $('#ajaxFormEditsuratberharga button[type="submit"]').prop('disabled', false);
        })

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false
        });

        // $.post("index.php/efill/lhkpn/daftar_uang", function(html){
        //     $.each(html, function(index, value){
        //         select = '<?=@$item->MATA_UANG?>';
        //         if (index == select)
        //         {
        //             $("#UANGSURAT1").append("<option value='"+index+"' selected>"+value+"</option>");
        //             kabkotedit();
        //         }else{
        //             $("#UANGSURAT1").append("<option value='"+index+"'>"+value+"</option>");
        //         };
                
        //     });
            $("#UANGSURAT1").select2();
        // }, 'json');

        $("#ajaxFormEditsuratberharga").submit(function(event) {
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
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/7/'+ID + '/edit', block:'#block', container:$('#suratberharga').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                        ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
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

        // $("#ajaxFormEditsuratberharga").submit(function(){
        //     var url = $(this).attr('action');
        //     var data = $(this).serializeArray();
        //     $.post(url, data, function(res){
        //          msg = {
        //             success : 'Data Berhasil Disimpan!',
        //             error : 'Data Gagal Disimpan!'
        //          };
        //          if (data == 0) {
        //             alertify.error(msg.error);
        //          } else {
        //             alertify.success(msg.success);
        //          }
        //          CloseModalBox();
        //          var ID = $('#id_lhkpn').val();
        //          ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/7/'+ID + '/edit', block:'#block', container:$('#suratberharga').find('.contentTab')});
        //          ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
        //          ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
        //     })
        //     return false;
        // })
        // var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormEdit"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/7/'+ID, block:'#block', container:$('#suratberharga').find('.contentTab')});          

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

        $('#ajaxFormEditsuratberharga .FILE_BUKTI').change(function(){
            var nil     = $(this).val().split('.');
            nil         = nil[nil.length - 1].toLowerCase();
            var file    = $(this)[0].files[0].size;
            var maxsize = 5000000;
            if (arr.indexOf(nil) < 0)
            {
                $(this).val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $(this).val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });        
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
    $aKode = [
        '1' => 'Penyertaan Modal pada Badan Hukum',
        '2' => 'Investasi'];
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Harta Tidak Bergerak dibawah ini ?
    <form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/savesuratberharga">
       <!--  <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Kode Jenis : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $aKode[$item->KODE_JENIS] ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Surat Berharga : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->NAMA_SURAT_BERHARGA ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Jumlah : </label>
                    <div class="col-sm-3">
                        <p class="form-control-static"><?php echo $item->JUMLAH ?> <?php echo $item->SATUAN ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Atas Nama : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->ATAS_NAMA ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Asal Usul : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $asal_usul->ASAL_USUL ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tahun Perolehan : </label>
                    <div class="col-sm-4">
                        <p class="form-control-static"><?php echo $item->TAHUN_PEROLEHAN_AWAL ?> s/d <?php echo $item->TAHUN_PEROLEHAN_AKHIR ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai Perolehan : </label>
                    <div class="col-sm-4">
                        <p class="form-control-static"><?php echo $item->SIMBOL ?></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="form-control-static"><?php echo number_format($item->NILAI_PEROLEHAN,0,'','.'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai Pelaporan : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo ($item->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : 'Perkiraan Pasar') ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <p class="form-control-static"><?php echo $item->SIMBOL ?> <?php echo number_format($item->NILAI_PELAPORAN,0,'','.') ?></p>
                    </div>
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
        // ng.formProcess($("#ajaxFormDelete"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/7/'+ID, block:'#block', container:$('#suratberharga').find('.contentTab')});
        
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
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/7/'+ID + '/edit', block:'#block', container:$('#suratberharga').find('.contentTab')});
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
    $aKode = [
        '1' => 'Perabotan Rumah Tangga',
        '2' => 'Barang Elektronik',
        '3' => 'Perhisan & Logam / Batu Mulia',
        '4' => 'Baranga Seni / Antik',
        '5' => 'Persediaan',
        '6' => 'Harta Bergerak Lainnya'];
?>
<div id="wrapperFormDetail">
    <form class="form-horizontal">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Kode Jenis : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $aKode[$item->KODE_JENIS] ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Surat Berharga : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->NAMA_SURAT_BERHARGA ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Jumlah : </label>
                    <div class="col-sm-5">
                        <p class="form-control-static"><?php echo $item->JUMLAH ?></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="form-control-static"><?php echo $item->SATUAN ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Atas Nama : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->ATAS_NAMA ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Asal Usul : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $asal_usul->ASAL_USUL ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tahun Perolehan : </label>
                    <div class="col-sm-4">
                        <p class="form-control-static"><?php echo $item->TAHUN_PEROLEHAN_AWAL ?> s/d <?php echo $item->TAHUN_PEROLEHAN_AKHIR ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai Perolehan : </label>
                    <div class="col-sm-4">
                        <p class="form-control-static"><?php echo $item->MATA_UANG ?></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="form-control-static"><?php echo $item->NILAI_PEROLEHAN ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai Pelaporan : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo ($item->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : 'Perkiraan Pasar') ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <p class="form-control-static"><?php echo $item->NILAI_PELAPORAN ?></p>
                    </div>
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

if($form == 'pelepasan'){
?>


    <div id="wrapperFormpelepasansuratberharga">
        <form class='form-horizontal' method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savesuratberharga" enctype="multipart/form-data">

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
                        <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/deletepelepasansuratberharga/<?=$id?>/<?php echo $data->ID; ?>" title="Delete">Hapus</button>
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
            // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/7/'+ID, block:'#block', container:$('#suratberharga').find('.contentTab')});
            $("#ajaxFormAdd").submit(function(){
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
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/7/'+ID + '/edit', block:'#block', container:$('#suratberharga').find('.contentTab')});
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
                    ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/1/' + ID + '/edit', block: '#block', container: $('#final').find('.contentTab')});
                })
                return false;
            })

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
        $("#wrapperFormpelepasansuratberharga .btn-delete").click(function (e) {
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
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/7/'+ID + '/edit', block:'#block', container:$('#suratberharga').find('.contentTab')});
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
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemA[0]->TGL_LAPOR?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->TGL_LAPOR?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Kode Jenis</label></td>    
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <?php
                            switch(@$itemA[0]->KODE_JENIS){
                                case '1' : echo 'Penyertaan Modal pada Badan Hukum'; break;
                                case '2' : echo 'Investasi'; break;
                            }; ?>
                    </div>
                </td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <?php
                            switch(@$itemB[0]->KODE_JENIS){
                                case '1' : echo 'Penyertaan Modal pada Badan Hukum'; break;
                                case '2' : echo 'Investasi'; break;
                            }; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label class="control-label" style="text-align:left !important;">Surat Berharga</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemA[0]->NAMA_SURAT_BERHARGA?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->NAMA_SURAT_BERHARGA?></div></td>
            </tr>
            <tr>
                <td><label class="control-label" style="text-align:left !important;">Name Penerbit</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemA[0]->NAMA_PENERBIT?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->NAMA_PENERBIT?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Kuantitas</label></td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>Jumlah</strong> <?php echo @$itemA[0]->JUMLAH; ?></br>
                                <strong>Satuan</strong> <?php echo @$itemA[0]->SATUAN; ?></br>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>Jumlah</strong> <?php echo @$itemB[0]->JUMLAH; ?></br>
                                <strong>Satuan</strong> <?php echo @$itemB[0]->SATUAN; ?></br>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label class="control-label">Kepemilikan</label></td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>Atas Nama</strong> 
                                <?php echo @$itemA[0]->ATAS_NAMA; ?>
                            </div>
                            <div class="col-sm-12">
                                <strong>Asal Usul</strong> 
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
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>Atas Nama</strong> 
                                <?php echo @$itemB[0]->ATAS_NAMA; ?>
                            </div>
                            <div class="col-sm-12">
                                <strong>Asal Usul</strong> 
                                <?php
                                $exp  = explode(',', $itemB[0]->ASAL_USUL);
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
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label class="control-label" style="text-align:left !important;">Tahun Investasi</label></td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        Dari <?php echo @$itemA[0]->TAHUN_PEROLEHAN_AWAL; ?> 
                        s/d <?php echo @$itemA[0]->TAHUN_PEROLEHAN_AKHIR; ?>
                    </div>
                </td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        Dari <?php echo @$itemB[0]->TAHUN_PEROLEHAN_AWAL; ?>
                        s/d <?php echo @$itemB[0]->TAHUN_PEROLEHAN_AKHIR; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label class="control-label" style="text-align:left !important;">Nilai Perolehan</label></td>
                <td><div class="control-label" style="text-align:right !important;"><?php echo @$itemA[0]->SIMBOL.' '.@number_format( @$itemA[0]->NILAI_PEROLEHAN , 2 , '.' , ',' ); ?></div></td>
                <td><div class="control-label" style="text-align:right !important;"><?php echo @$itemB[0]->SIMBOL.' '.@number_format( @$itemB[0]->NILAI_PEROLEHAN , 2 , '.' , ',' ); ?></div></td>
            </tr>
            <tr>
                <td><label class="control-label" style="text-align:left !important;">Nilai Pelaporan</label></td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php switch(@$itemA[0]->JENIS_NILAI_PELAPORAN){
                                    case '1' : echo 'Appraisal'; break;
                                    case '2' : echo 'Perkiraan Pasar'; break;
                                }; ?>
                            </div>
                            <div class="col-sm-12" align="right">
                                Rp. <?php echo @number_format( @$itemA[0]->NILAI_PELAPORAN , 2 , ',' , '.' ); ?>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="control-label" style="text-align:left !important;">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php switch(@$itemB[0]->JENIS_NILAI_PELAPORAN){
                                    case '1' : echo 'Appraisal'; break;
                                    case '2' : echo 'Perkiraan Pasar'; break;
                                }; ?>
                            </div>
                            <div class="col-sm-12" align="right">
                                Rp. <?php echo @number_format( @$itemB[0]->NILAI_PELAPORAN , 2 , ',' , '.' ); ?>
                            </div>
                        </div>
                    </div>
                </td>
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
        //         $("#UANGSURAT").append("<option value='"+index+"'>"+value+"</option>");
        //     });
        $("#UANGSURAT").select2();
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

        var requiredCheckboxesasalusul = $('.asalusul');

            requiredCheckboxesasalusul.change(function(){

            if(requiredCheckboxesasalusul.is(':checked')) {
                requiredCheckboxesasalusul.removeAttr('required');
            }

            else {
                requiredCheckboxesasalusul.attr('required', 'required');
            }
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
    });
</script>