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
    <form class="form-horizontal" method="post" id="ajaxFormAddhartabergeraklain" action="index.php/efill/lhkpn/savehartabergerak2" enctype="multipart/form-data">
        <div class="con-body">
            <div class="box-body">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_hbl'); ?>
                      
                            <select class="form-control" name="KODE_JENIS" id="KODE_JENIS" required>
                            <option value=''></option>
                            <?php
                                $ia = 0;
                                foreach ($hartabergeraklain as $key => $value) {
                                    $ia++;
                                    ?>
                                        <option value="<?=$key?>"><?=$value?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                   <!--  <div class="form-group">
                        <label class="col-sm-4 control-label">Nama Harta Bergerak <span class="red-label">*</span> :</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="NAMAA" placeholder="Nama Harta Bergerak" type="text" required>
                        </div>
                    </div> -->
                     <div class="form-group">
                            <label>Jumlah <span class="red-label">*</span></label> <?= FormHelpPopOver('jumlah_hbl'); ?>
                            <input type="text" name="JUMLAH" id="JUMLAH" class="form-control int" required>   
                        </div>
                        <div class="form-group">
                            <label>Satuan <span class="red-label">*</span></label> <?= FormHelpPopOver('satuan_hbl'); ?>
                            <input type="text"  name="SATUAN" id="SATUAN" class="form-control input_capital" required>   
                        </div>
                       <div class="form-group">
                            <label>Keterangan</label> <?= FormHelpPopOver('keterangan_hbl'); ?>
                            <textarea class="form-control" name="KETERANGAN" id="KETERANGAN" rows="2"></textarea>
                        </div>
                   <!--  <div class="form-group">
                        <label class="col-sm-4 control-label">Atas Nama <span class="red-label">*</span> :</label>
                        <div class="col-sm-8">
                            <input class="form-control atasnama form-select2" style="border:0px;" name="ATAS_NAMA" placeholder="Atas Nama" type="text" required>
                        </div>
                    </div> -->
                       </div>
                  <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Asal Usul <span class="red-label">*</span>  :</label><?= FormHelpPopOver('asal_usul_harta_hbl'); ?>
                       
                        <?php 
                        $ia = 0;
                        foreach($asalusuls as $asalusul){ 
                            $ia++;
                            ?>
                            <div class="checkbox">
                                <div class="col-sm-6">
                                    <label>
                                        <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" name='ASAL_USUL[]' value="<?php echo $asalusul->ID_ASAL_USUL?>" class="CB_ASAL_USUL">
                                        <?php echo $ia.'. '.$asalusul->ASAL_USUL; ?>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                       
                    </div>
             
                  <!--   <div class="form-group">
                        <label class="col-sm-4 control-label">Pemanfaatan <span class="red-label">*</span> :</label>
                        <div class="col-sm-8">
                        <select class="form-control" name="PEMANFAATAN[]" id="PEMANFAATAN" >
                          <option value=''>-- Pilih Pemanfaatan --</option>
                                <?php 
                                $ia = 0;
                                foreach ($manfaat as $key => $value) { 
                                    $ia++;
                                    ?>
                                    <option value="<?=$key?>"><?=$ia.'. '.str_replace('0.', '', $value)?></option>
                                <?php } ?>
                            <option value='0'>4. Lainnya</option>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Ket Lainnya : </label>
                        <div class="col-sm-8">
                            <input class="form-control" name="KET_LAINNYA" placeholder="Ket Lainnya" type="text">
                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label class="col-sm-4 control-label">Tahun Perolehan <span class="red-label">*</span> :</label>
                        <div class="col-sm-3">
                            <input class="form-control dari" placeholder="Tahun" name="TAHUN_PEROLEHAN_AWAL" required>
                        </div>
                        <div class="col-sm-1">
                            s/d
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control ke" placeholder="Tahun" name="TAHUN_PEROLEHAN_AKHIR">
                        </div>
                    </div>--> 
                    <div class="form-group">
                        <label>Nilai Perolehan (Rp)<span class="red-label">*</span> :</label> <?= FormHelpPopOver('nilai_perolehan_hbl'); ?>
                        <!--<div class="col-sm-3">
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
                       
                            <input class="form-control int" name="NILAI_PEROLEHAN" id='NILAI_PEROLEHAN'  type="text">
                      
                    </div>
                    <div class="form-group">
                        <label >Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span>  :</label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_hbl'); ?>
                        <!--<div class="col-sm-7">
                            <div class='col-sm-6'>
                                <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="1" > Appraisal
                            </div>
                            <div class='col-sm-6'>
                                <input required type="radio" name='JENIS_NILAI_PELAPORAN' id='JENIS_NILAI_PELAPORAN' value="2" chacked> Perkiraan Pasar
                            </div>
                        </div>-->
                    <!--</div>-->
                    <!--<div class="form-group">
                        <label class="col-sm-4 control-label">Rp. </label>-->
                      
                            <input required type="text" class="form-control int" name="NILAI_PELAPORAN"  />
                       
                    </div>
                </div>
            </div>
            <div class="pull-right">
            <input name="ID_HARTA" type="hidden">
            <input name="ID_HARTA_LAMA" type="hidden">
            <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?=@$id_lhkpn;?>">
            <input name="GOLONGAN_HARTA" type="hidden">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary" id="btnSimpan">Simpan</button>
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
                   
                        <input type="text" name='NAMA[]' class="form-control required">
                    
                </div>

                <div class="form-group">
                    <label>Alamat </label>
                   
                        <textarea name='ALAMAT[]' class="form-control" ></textarea>
                   
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
        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        $("#ajaxFormAddhartabergeraklain").submit(function(){
            $('#btnSimpan').prop('disabled', true);
            IS_CHECKED_ASAL_USUL = false;
            $('.CB_ASAL_USUL').each(function(index, el) {
                if($(this).prop('checked')){
                    IS_CHECKED_ASAL_USUL = true;
                }
            });
            if(IS_CHECKED_ASAL_USUL == false){
                alertify.error('Silahkan mengisi asal usul!');
                return false;
            }

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
                 var ID = $('#id_lhkpn').val();
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/6/'+ID + '/edit', block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
            })
            return false;
        })
        // var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/6/'+ID, block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});

    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdithartabergeraklain" action="index.php/efill/lhkpn/savehartabergerak2">
        <div class="con-body">
            <div class="box-body">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label><?= FormHelpPopOver('jenis_hbl'); ?>
                       
                            <select class="form-control" name="KODE_JENIS" id="KODE_JENIS">
                                <option value=''></option>
                                <?php
                                    $ia = 0 ;
                                    foreach ($hartabergeraklain as $key => $value) {
                                        $ia++;
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->KODE_JENIS){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                     
                    </div>
                   <!--  <div class="form-group">
                        <label >Nama Harta Bergerak <span class="red-label">*</span> </label>
                      
                            <input required value="<?php echo $item->NAMA ?>" class="form-control" name="NAMAA" placeholder="Nama Harta Bergerak" type="text">
                       
                    </div> -->
                      <div class="form-group">
                            <label>Jumlah <span class="red-label">*</span></label> <?= FormHelpPopOver('jumlah_hbl'); ?>
                            <input type="text" name="JUMLAH" id="JUMLAH" value="<?php echo $item->JUMLAH ?>" class="form-control money" required>   
                        </div>
                        <div class="form-group">
                            <label>Satuan <span class="red-label">*</span></label> <?= FormHelpPopOver('satuan_hbl'); ?>
                            <input type="text"  name="SATUAN" id="SATUAN" value="<?php echo $item->SATUAN ?>" class="form-control input_capital" required>   
                        </div>
                <!--     <div class="form-group">
                        <label>Jumlah / Satuan <span class="red-label">*</span> :</label>
                      
                            <input required value="<?php echo $item->JUMLAH ?>" class="form-control int" name="JUMLAH" placeholder="Jumlah" type="text">
                        
                            <input required value="<?php echo $item->SATUAN ?>" class="form-control" name="SATUAN" placeholder="Satuan" type="text">
                       
                    </div> -->
                    <div class="form-group">
                        <label>Keterangan :</label> <?= FormHelpPopOver('keterangan_hbl'); ?>
                      
                            <textarea class="form-control" name="KETERANGAN" placeholder="Keterangan" ><?php echo $item->KETERANGAN ?></textarea>
                     
                    </div>
                    <!-- <div class="form-group">
                        <label>Atas Nama <span class="red-label">*</span> </label>
                       
                            <input required value="<?php echo $item->ATAS_NAMA ?>" class="form-control atasnama form-select2" style="border:0px;" name="ATAS_NAMA" placeholder="Atas Nama" type="text">
                       
                    </div> -->
                     </div>
                 <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label >Asal Usul <span class="red-label">*</span>  </label><?= FormHelpPopOver('asal_usul_harta_hbl'); ?>
                      
                        <?php
                        $asalusulselected = explode(',',$item->ASAL_USUL);
                        $i = 0;
                        $ia = 0;
                        foreach($asalusuls as $asalusul){ 
                            $ia++;
                            ?>
                            <div class="checkbox">
                                <div class="col-sm-6">
                                    <label>
                                        <input <?php echo ($asalusul->IS_OTHER === '1' ? 'onClick="f_pelepasan(this)"' : ''); ?> type="checkbox" name='ASAL_USUL[]' value="<?php echo $asalusul->ID_ASAL_USUL?>" <?php echo in_array($asalusul->ID_ASAL_USUL, $asalusulselected)?'checked':''; ?> class="CB_ASAL_USUL">
                                        <?php echo $ia.'. '.$asalusul->ASAL_USUL; ?>
                                    </label>
                                </div>
                                <?php if(in_array($asalusul->ID_ASAL_USUL, $asalusulselected) && $asalusul->IS_OTHER === '1'){ ?>
                                    <div class="col-sm-2">
                                        <a class="btn btn-primary btn-xs" href="javascript:;" onClick="f_show(<?php echo $asalusul->ID_ASAL_USUL ?>)">Info</a>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <strong>Rp. <?php echo number_format($pelaporan[$i]->NILAI_PELEPASAN, 0, '.', '.') ?></strong>
                                    </div>
                                    <?php $i++;} ?>
                            </div>
                        <?php } ?>
                      
                    </div>
               
               
                  <!--   <div class="form-group">
                        <label >Ket Lainnya : </label>
                       
                            <input value="<?php echo $item->KET_LAINNYA ?>" class="form-control" name="KET_LAINNYA" placeholder="Ket Lainnya" type="text">
                      
                    </div> -->
                  <!--   <div class="form-group">
                        <label>Tahun Perolehan <span class="red-label">*</span> </label>
                       
                            <input required type="text" value="<?php echo $item->TAHUN_PEROLEHAN_AWAL ?>" class="form-control dari" name="TAHUN_PEROLEHAN_AWAL" />
                     
                       
                            s/d
                      
                            <input type="text" value="<?php echo $item->TAHUN_PEROLEHAN_AKHIR ?>" class="form-control ke" name="TAHUN_PEROLEHAN_AKHIR" />
                      
                    </div> -->
                    <div class="form-group">
                        <label>Nilai Perolehan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_hbl'); ?>
                       
                          <!--   <select class='form-control form-select2' name="MATA_UANG" style="border:0px;" id="UANG1" required>
                                <?php
                                    foreach ($uang as $key => $value) {
                                        ?>
                                            <option value="<?=$key?>" <?php if($key == $item->MATA_UANG){ echo 'selected';} ?> ><?=$value?></option>
                                        <?php
                                    }
                                ?>
                            </select> -->
                     
                       
                            <input value="<?php echo $item->NILAI_PEROLEHAN ?>" class="form-control int" name="NILAI_PEROLEHAN" placeholder="Nilai Perolehan" type="text">
                     
                    </div>
                    <div class="form-group">
                        <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_hbl'); ?>
                        
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
                <input type="hidden"  name='ID' id='ID' class="form-control" value='<?php echo $item->ID;?>' placeholder="Id Harta">
                <input value="<?php echo $item->ID_HARTA; ?>" name="ID_HARTA" type="hidden">
                <input value="<?php echo $item->ID ?>" name="ID" type="hidden">
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
                                <div class="col-sm-1"><p class="form-control-static">Rp.</p></div>
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
                    <label class="col-sm-4 control-label">Nilai <span class="title"></span> <span class="red-label">*</span> :</label>
                    <div class="col-sm-1"><p class="form-control-static">Rp.</p></div>
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
        $('#ajaxFormEdithartabergeraklain :input').change(function () {
            $('#ajaxFormEdithartabergeraklain button[type="submit"]').prop('disabled', false);
        })

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('.modal-dialog').width('95%');
        })

        $('.int').inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false
        });

        // ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]); 
        $("#ajaxFormEdithartabergeraklain").submit(function(){
            IS_CHECKED_ASAL_USUL = false;
            $('.CB_ASAL_USUL').each(function(index, el) {
                if($(this).prop('checked')){
                    IS_CHECKED_ASAL_USUL = true;
                }
            });
            if(IS_CHECKED_ASAL_USUL == false){
                alertify.error('Silahkan mengisi asal usul!');
                return false;
            }            
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
                 var ID = $('#id_lhkpn').val();
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/6/'+ID + '/edit', block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/19/'+ID + '/edit', block:'#block', container:$('#penerimaanhibah').find('.contentTab')});
            })
            return false;
        })
        // var ID = $('#id_lhkpn').val();
        // ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/6/'+ID, block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});
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
    $aKode = [
        '1' => 'Perabotan Rumah Tangga',
        '2' => 'Barang Elektronik',
        '3' => 'Perhisan & Logam / Batu Mulia',
        '4' => 'Baranga Seni / Antik',
        '5' => 'Persediaan',
        '6' => 'Harta Bergerak Lainnya'];


    $aMata = ['1' => 'Rp.', '2' => 'USD', '3' => 'YEN'];

?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Harta Tidak Bergerak dibawah ini ?
    <form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/savehartabergerak2">
        <!-- <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Kode Jenis : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $aKode[$item->KODE_JENIS] ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Harta Bergerak : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->NAMA ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Jumlah : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->JUMLAH ?> <?php echo $item->SATUAN ?></p> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Keterangan : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->KETERANGAN ?></p>
                    </div>
                </div>
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
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Pemanfaatan : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static">
                        <?php 
                            foreach ($manfaat as $key => $value) {
                                if($key==$item->PEMANFAATAN){ echo $value;}
                            }
                        ?>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Ket Lainnya : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->KET_LAINNYA ?></p>
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
                        <p class="form-control-static">
                            <?php 
                                foreach ($uang as $key => $value) {
                                    if($key==$item->MATA_UANG){ echo $value;}
                                }
                            ?>
                        </p>
                        <p class="form-control-static text-right">
                            <?php echo number_format($item->NILAI_PEROLEHAN, 0,'','.'); ?>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nilai Pelaporan : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo ($item->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : 'Perkiraan Pasar') ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-4">
                        <p class="form-control-static text-right">Rp. <?php echo number_format($item->NILAI_PELAPORAN, 0,'','.') ?></p>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="pull-right">
			<input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-md btn-danger">Hapus</button>
            <input type="reset" class="btn btn-md btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
        var ID = $('#id_lhkpn').val();
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/6/'+ID + '/edit', block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});

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
    $aMata = ['1' => 'Rp.', '2' => 'USD', '3' => 'YEN'];
?>
<div id="wrapperFormDetail" class="form-horizontal">
    <div class="box-body">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-4 control-label">Kode Jenis : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $aKode[$item->KODE_JENIS] ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nama Harta Bergerak : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->NAMA ?></p>
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
            <div class="form-group">
                <label class="col-sm-4 control-label">Keterangan : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->KETERANGAN ?></p>
                </div>
            </div>
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
		</div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-4 control-label">Pemanfaatan : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php if($item->PEMANFAATAN == '1'){ echo 'Digunakan Sendiri';}elseif($item->PEMANFAATAN == '2'){ echo 'Tidak digunakan sendiri & menghasilkan' ;}elseif($item->PEMANFAATAN == '3'){ echo 'Tidak digunakan sendiri & tidak menghasilkan';}elseif($item->PEMANFAATAN == '4'){ echo 'Lainnya';}?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Ket Lainnya : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->KET_LAINNYA ?></p>
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
                    <p class="form-control-static text-right"><?php echo $aMata[$item->MATA_UANG] ?> <?php echo $item->NILAI_PEROLEHAN ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nilai Pelaporan : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo ($item->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : 'Perkiraan Pasar') ?></p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4">
                    <p class="form-control-static text-right">Rp. <?php echo $item->NILAI_PELAPORAN ?></p>
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


    <div id="wrapperFormpelepasanhartabergerak2">
        <form class='form-horizontal' method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/savehartabergerak2" enctype="multipart/form-data">

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
                        <div class="col-sm-1"><p class="form-control-static">Rp.</p></div>
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
                <input type="hidden" name="type" value="<?=$do;?>">
                <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?=@$id_lhkpn;?>">
                <?php
                    if($do == 'update'){
                        echo '<input type="hidden" name="ID" value="'.$data->ID.'">';
                        ?>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/deletepelepasanhartabergerak2/<?=$id?>/<?php echo $data->ID; ?>" title="Delete">Hapus</button>
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
            // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/6/'+ID, block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});
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
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/6/'+ID + '/edit', block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});
                    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
                    // ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/1/' + ID + '/edit', block: '#block', container: $('#final').find('.contentTab')});
                })
                return false;
            })

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
        $("#wrapperFormpelepasanhartabergerak2 .btn-delete").click(function (e) {
            url = $(this).attr('href');
            var data = $(this).serializeArray();
            $.post(url, function(res) {
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
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/6/'+ID + '/edit', block:'#block', container:$('#hartabergerakperabot').find('.contentTab')});
                ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/13/'+ID + '/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
            });
            return false;
        });
    </script>
<?php } ?>

<?php
if($form=='perbandingan'){
    $aKode = [
        '1' => 'Perabotan Rumah Tangga',
        '2' => 'Barang Elektronik',
        '3' => 'Perhisan & Logam / Batu Mulia',
        '4' => 'Baranga Seni / Antik',
        '5' => 'Persediaan',
        '6' => 'Harta Bergerak Lainnya'];
    $aMata = ['1' => 'Rp.', '2' => 'USD', '3' => 'YEN'];
?>

<div id="wrapperFormDetail" class='form-horizontal'>

    <div class="box-body">

        <table class='table table-striped'>
            <tr>
                <td><label class="control-label">Tanggal Lapor</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$itemA[0]->LAPOR?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$itemB[0]->LAPOR?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Kode Jenis</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$aKode[$itemA[0]->KODE_JENIS]?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$aKode[$itemB[0]->KODE_JENIS]?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nama Harga Bergerak</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->NAMA;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemB[0]->NAMA;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Jumlah Satuan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->JUMLAH;?>  <?php echo $itemA[0]->SATUAN;?></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemB[0]->JUMLAH;?>  <?php echo $itemB[0]->SATUAN;?></td>
            </tr>
            <tr>
                <td><label class="control-label">Keterangan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->KETERANGAN;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemB[0]->KETERANGAN;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Atas Nama</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->ATAS_NAMA;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemB[0]->ATAS_NAMA;?></div></td>
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
                </div></td>
            </tr>
            <tr>
                <td><label class="control-label">Pemanfaatan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$manfaat[$itemA[0]->PEMANFAATAN];?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=$manfaat[$itemB[0]->PEMANFAATAN];?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Ket Lainnya</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->KET_LAINNYA;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemB[0]->KET_LAINNYA;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Tahun Perolehan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->TAHUN_PEROLEHAN_AWAL;?> s/d <?php echo $itemA[0]->TAHUN_PEROLEHAN_AKHIR;?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemB[0]->TAHUN_PEROLEHAN_AWAL;?> s/d <?php echo $itemB[0]->TAHUN_PEROLEHAN_AKHIR;?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nilai Perolehan</label></td>
                <td><div class="control-label" style="text-align:right !important;">
                    <?=$uang[$itemA[0]->MATA_UANG];?>
                     <?php echo @number_format( @$itemA[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
                <td><div class="control-label" style="text-align:right !important;">
                    <?=$uang[$itemB[0]->MATA_UANG];?>
                     <?php echo @number_format( @$itemB[0]->NILAI_PEROLEHAN , 2 , ',' , '.' );?>
                </div></td>
            </tr>
            <tr>
                <td><label class="control-label">Nilai Pelaporan</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemA[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : $itemA[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?> </div></td>
                <td><div class="control-label" style="text-align:left !important;"><?php echo $itemB[0]->JENIS_NILAI_PELAPORAN == '1' ? 'Appraisal' : $itemB[0]->JENIS_NILAI_PELAPORAN == '2' ? 'Perkiraan Pasar' : '' ;?> </div></td>
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

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
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
    });
</script>
