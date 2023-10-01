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
 * @package Views/lhkpnhutang
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
    <form class="form-horizontal" method="post" id="ajaxFormAddhutang" action="index.php/efill/lhkpn/savehutang" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label> Jenis <span class="red-label">*</span> :</label> <?= FormHelpPopOver('jenis_htg'); ?>
                   
                        <select class="form-control" name="KODE_JENIS" id="KODE_JENIS" required>
                            <option value=''></option>
                            <?php
                                $ia = 0;
                                foreach ($hutang as $key => $value) {
                                    $ia++;
                                    ?>
                                        <option value="<?=$key?>"><?=$value?></option>
                                    <?php
                                }
                            ?>
                        </select>
                   
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
                    <label>Nama Kreditur <span class="red-label">*</span>:</label><?= FormHelpPopOver('nama_kreditur_htg'); ?>
                   
                        <input class="form-control" name="NAMA_KREDITUR"  type="text" required>
                  
                </div>
               <!--  <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Transaksi :</label>
                    <div class="col-sm-8">
                        <input class="form-control datepicker" name="TANGGAL_TRANSAKSI" placeholder="DD/MM/YYYY" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Jatuhtempo :</label>
                    <div class="col-sm-8">
                        <input class="form-control datepicker" name="TANGGAL_JATUH_TEMPO" placeholder="DD/MM/YYYY" type="text">
                    </div>
                </div> -->
                <div class="form-group">
                    <label>Bentuk Agunan</label> <?= FormHelpPopOver('bentuk_agunan_htg'); ?>
                    
                        <input class="form-control" name="AGUNAN"  type="text">
                   
                </div>
                 <div class="form-group">
                    <label>Nilai Awal Hutang(Rp) <span class="red-label">*</span>:</label> <?= FormHelpPopOver('nilai_awal_hutang_htg'); ?>
                   
                        
                                <input required class="form-control int" name="AWAL_HUTANG"  type="text">
                           
                   
                </div>
                <div class="form-group">
                    <label>Nilai Saldo Hutang(Rp) <span class="red-label">*</span>:</label> <?= FormHelpPopOver('nilai_saldo_hutang_htg'); ?>
                   
                       
                                <input required class="form-control int" name="SALDO_HUTANG"  type="text">
                          
                    
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input name="ID_HUTANG" type="hidden">
            <input type='hidden' readonly id="idLhkpn" name='ID_LHKPN' value="<?=@$id_lhkpn;?>">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary" id="btnSimpan">Simpan</button>
            <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
  $('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });
    $(document).ready(function() {
        // ng.formProcess($("#ajaxFormAdd"), 'insert', location.href.split('#')[1]);
        $("#ajaxFormAddhutang").submit(function(){
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
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/10/'+ID + '/edit', block:'#block', container:$('#hutang').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
            })
            return false;
        })
        // var ID = $('#idLhkpn').val();
        // ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/10/'+ID, block:'#block', container:$('#hutang').find('.contentTab')});
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdithutang" action="index.php/efill//lhkpn/savehutang">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">

                    <label> Jenis <span class="red-label">*</span> :</label> <?= FormHelpPopOver('jenis_htg'); ?>
                   
                        <select class="form-control" name="KODE_JENIS" id="KODE_JENIS" required>
                            <option value=''></option>
                            <?php
                                $ia = 0;
                                foreach ($hutang as $key => $value) {
                                    $ia++;
                                    ?>
                                       <option value="<?=$key?>" <?php if($key == $item->KODE_JENIS){ echo 'selected';} ?> ><?=$value?></option>
                                    <?php
                                }
                            ?>
                        </select>
                   
                </div>
                 <div class="form-group">
                    <label>Atas Nama </label> <?= FormHelpPopOver('atas_nama_htg'); ?>
                  
                        <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                            <option></option>
                                <option <?php echo ($item->ATAS_NAMA == 'PN YANG BERSANGKUTAN' ? 'selected="selected"' : '') ?> value="PN YANG BERSANGKUTAN">PN YANG BERSANGKUTAN</option>
                                <option <?php echo ($item->ATAS_NAMA == 'PASANGAN / ANAK' ? 'selected="selected"' : '') ?> value="PASANGAN / ANAK">PASANGAN / ANAK</option>
                                <option <?php echo ($item->ATAS_NAMA == 'LAINNYA' ? 'selected="selected"' : '') ?> value="LAINNYA">LAINNYA</option>
                               
                            </select>
                   
                </div>
                <div class="form-group">
                    <label>Nama Kreditur <span class="red-label">*</span></label><?= FormHelpPopOver('nama_kreditur_htg'); ?>
                   
                        <input required value='<?php echo $item->NAMA_KREDITUR;?>' class="form-control" name="NAMA_KREDITUR" placeholder="Nama Kreditur" type="text">
                  
                </div>
              <!--   <div class="form-group">
                    <label>Tanggal Transaksi </label>
                   
                        <input value='<?php echo $item->TANGGAL_TRANSAKSI;?>' class="form-control datepicker" name="TANGGAL_TRANSAKSI" placeholder="DD/MM/YYYY" type="text">
                 
                </div>
                <div class="form-group">
                    <label>Tanggal Jatuhtempo </label>
                   
                        <input value='<?php echo $item->TANGGAL_JATUH_TEMPO;?>' class="form-control datepicker" name="TANGGAL_JATUH_TEMPO" placeholder="DD/MM/YYYY" type="text">
                   
                </div> -->

                <div class="form-group">
                    <label>Bentuk Agunan</label><?= FormHelpPopOver('bentuk_agunan_htg'); ?>
                   
                        <input value='<?php echo $item->AGUNAN;?>' class="form-control" name="AGUNAN" placeholder="Bentuk Agunan / No Kartu Kredit" type="text">
                   
                </div>
                 <div class="form-group">
                    <label>Nilai Awal Hutang(Rp) <span class="red-label">*</span>:</label> <?= FormHelpPopOver('nilai_awal_hutang_htg'); ?>
                   
                        
                        <input required value='<?php echo $item->AWAL_HUTANG;?>' class="form-control int" name="AWAL_HUTANG" placeholder="Awal Hutang" type="text">
                           
                   
                </div>
                <div class="form-group">
                    <label>Nilai Saldo Hutang(Rp) <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_saldo_hutang_htg'); ?>
                   
                        <input required value='<?php echo $item->SALDO_HUTANG;?>' class="form-control int" name="SALDO_HUTANG" placeholder="Saldo Hutang" type="text">
                  
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input value='<?php echo $item->ID_LHKPN;?>' id="idLhkpn" name="ID_LHKPN" type="hidden">
            <input value='<?php echo $item->ID_HUTANG;?>' name="ID_HUTANG" type="hidden">
            <input type="hidden" name="ID_HUTANG" value="<?php echo $item->ID_HUTANG;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary" disabled>Simpan</button>
            <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
$('[data-toggle="popover"]').popover({
        }); 
        $('a.over').css('cursor','pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });
    $(document).ready(function() {
        $('#ajaxFormEdithutang :input').change(function () {
            $('#ajaxFormEdithutang button[type="submit"]').prop('disabled', false);
        })

        // ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);  
		$("#ajaxFormEdithutang").submit(function(){
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
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/10/'+ID + '/edit', block:'#block', container:$('#hutang').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
            })
            return false;
        })
        // var ID = $('#idLhkpn').val();
        // ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/10/'+ID, block:'#block', container:$('#hutang').find('.contentTab')});
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus hutang dibawah ini ?
    <form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill//lhkpn/savehutang">
       <!--  <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Atas Nama : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->ATAS_NAMA? $item->ATAS_NAMA : '-'; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Kreditur : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->NAMA_KREDITUR;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Transaksi : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->TANGGAL_TRANSAKSI;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Jatuhtempo : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->TANGGAL_JATUH_TEMPO;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Agunan : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo $item->AGUNAN;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Saldo Hutang : </label>
                    <div class="col-sm-8">
                        <p class="form-control-static"><?php echo number_format(@$item->SALDO_HUTANG, 0,'','.');?></p>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="pull-right">
            <input type="hidden" name="ID_HUTANG" value="<?php echo $item->ID_HUTANG;?>">
			<input type="hidden" name="ID_LHKPN" id="idLhkpn" value="<?php echo $item->ID_LHKPN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
		var ID = $('#idLhkpn').val();
        // ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/10/'+ID, block:'#block', container:$('#hutang').find('.contentTab')});

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
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/10/'+ID + '/edit', block:'#block', container:$('#hutang').find('.contentTab')});
                 ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/1/'+ID + '/edit', block:'#block', container:$('#final').find('.contentTab')});
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
    <div class="box-body form-horizontal">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-sm-4 control-label">Atas Nama : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->ATAS_NAMA;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nama Kreditur : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->NAMA_KREDITUR;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tanggal Transaksi : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->TANGGAL_TRANSAKSI;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tanggal Jatuhtempo : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->TANGGAL_JATUH_TEMPO;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Agunan : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->AGUNAN;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Saldo Hutang : </label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $item->SALDO_HUTANG;?></p>
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
                <td><label class="control-label">Atas Nama / Nama Kreditur</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemA[0]->ATAS_NAMA . ' / ' . @$itemA[0]->NAMA_KREDITUR?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->ATAS_NAMA . ' / ' . @$itemB[0]->NAMA_KREDITUR?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Tanggal Transaksi</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemA[0]->TANGGAL_TRANSAKSI?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->TANGGAL_TRANSAKSI?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Tanggal Jatuh Tempo</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemA[0]->TANGGAL_JATUH_TEMPO?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->TANGGAL_JATUH_TEMPO?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Bentuk Agunan/ No Kartu Kredit</label></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemA[0]->AGUNAN?></div></td>
                <td><div class="control-label" style="text-align:left !important;"><?=@$itemB[0]->AGUNAN?></div></td>
            </tr>
            <tr>
                <td><label class="control-label">Saldo Hutang Saat Pelaporan</label></td>
                <td><div class="control-label" style="text-align:right !important;">Rp. <?=@number_format( @$itemA[0]->SALDO_HUTANG , 2 , ',' , '.' )?></div></td>
                <td><div class="control-label" style="text-align:right !important;"><?php if(@$itemB[0]->SALDO_HUTANG != ''){echo 'Rp. '.@number_format( @$itemB[0]->SALDO_HUTANG , 2 , ',' , '.' );}?></div></td>
            </tr>
        </table>

    </div>

</div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        $(".int").inputmask("integer", {
           groupSeparator : '.',
           'autoGroup': true,
           'removeMaskOnSubmit': false,
           'digits': 0
        });
    })
    function formatAngka(angka) {
       if (typeof(angka) != 'string') angka = angka.toString();
       var reg = new RegExp('([0-9]+)([0-9]{3})');
       while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
       return angka;
    }
</script>