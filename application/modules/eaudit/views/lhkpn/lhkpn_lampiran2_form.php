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
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill//lhkpn/save_lampiran2" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Kode Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_fas'); ?>
                  
                        <select name="JENIS_FASILITAS" id="" class="form-control" required>
                            <option value="">-- Pilih Kode Jenis --</option>
                            <option value="1">Rumah Dinas</option>
                            <option value="2">Biaya Hidup</option>
                            <option value="3">Jaminan Kesehatan</option>
                            <option value="4">Mobil Dinas</option>
                            <option value="5">Opsi Pembelian Saham</option>
                            <option value="6">Fasilitas Lainnya</option>
                        </select>
                   
                </div>
             <!--    <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Fasilitas <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <input required class="form-control" name="NAMA_FASILITAS" placeholder="Nama Fasilitas" type="text">
                    </div>
                </div> -->
                 <div class="form-group">
                    <label>Keterangan</label> <?= FormHelpPopOver('keterangan_fas'); ?>
                   
                        <textarea class="form-control" name="KETERANGAN"  ></textarea>
                  
                </div>
                <div class="form-group">
                    <label>Nama Pemberi Fasilitas <span class="red-label">*</span></label><?= FormHelpPopOver('nama_pihak_pemberi_fas'); ?>
                   
                        <input required class="form-control" name="PEMBERI_FASILITAS"  type="text">
                   
                </div>
                <div class="form-group">
                    <label>Keterangan Lain</label><?= FormHelpPopOver('keterangan_lain_fas'); ?>
                  
						<textarea class="form-control" name="KETERANGAN_LAIN"  ></textarea>
                   
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input id='idLhkpn' type='hidden' readonly name='ID_LHKPN' value="<?=@$id_lhkpn;?>">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
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
        var ID = $('#idLhkpn').val();
        ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill//lhkpn/showTable/14/'+ID + '/edit', block:'#block', container:$('#fasilitas').find('.contentTab')});
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="FormLampFasi" action="index.php/efill//lhkpn/save_lampiran2">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Kode Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_fas'); ?>
                  
                        <select name="JENIS_FASILITAS" id="" class="form-control" required>
                            <option value="">-- Pilih Kode Jenis --</option>
                            <option <?= ($item->JENIS_FASILITAS == '1' ? 'selected' : ''); ?> value="1">Rumah Dinas</option>
                            <option <?= ($item->JENIS_FASILITAS == '2' ? 'selected' : ''); ?> value="2">Biaya Hidup</option>
                            <option <?= ($item->JENIS_FASILITAS == '3' ? 'selected' : ''); ?> value="3">Jaminan Kesehatan</option>
                            <option <?= ($item->JENIS_FASILITAS == '4' ? 'selected' : ''); ?> value="4">Mobil Dinas</option>
                            <option <?= ($item->JENIS_FASILITAS == '5' ? 'selected' : ''); ?> value="5">Opsi Pembelian Saham</option>
                            <option <?= ($item->JENIS_FASILITAS == '6' ? 'selected' : ''); ?> value="6">Fasilitas Lainnya</option>
                        </select>
                   
                </div>
              <!--   <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Fasilitas <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <input value='<?php echo $item->NAMA_FASILITAS;?>' required class="form-control" name="NAMA_FASILITAS" placeholder="Nama Fasilitas" type="text">
                    </div>
                </div> -->
                <div class="form-group">
                    <label>Keterangan </label> <?= FormHelpPopOver('keterangan_fas'); ?>
                   
                        <textarea class="form-control" name="KETERANGAN"  placeholder="Keterangan"><?php echo $item->KETERANGAN;?></textarea>
                  
                </div>
                <div class="form-group">
                    <label>Nama Pemberi Fasilitas <span class="red-label">*</span></label><?= FormHelpPopOver('nama_pihak_pemberi_fas'); ?>
                    
                        <input value='<?php echo $item->PEMBERI_FASILITAS;?>' required class="form-control" name="PEMBERI_FASILITAS" placeholder="Nama Pemberi Fasilitas" type="text">
                  
                </div>
                <div class="form-group">
                    <label>Keterangan Lain</label><?= FormHelpPopOver('keterangan_lain_fas'); ?>
                   
						<textarea class="form-control" name="KETERANGAN_LAIN"  placeholder="Keterangan"><?php echo $item->KETERANGAN_LAIN;?></textarea>
                  
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input value='<?php echo $item->ID_LHKPN;?>' name="ID_LHKPN" id="idLhkpn" type="hidden">
            <input value='<?php echo $item->ID;?>' name="ID" type="hidden">
            <input type="hidden" name="ID_FASILITAS" value="<?php echo $item->ID_FASILITAS;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
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
        var ID = $('#idLhkpn').val();        
        ng.formProcess($("#FormLampFasi"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/efill//lhkpn/showTable/14/'+ID + '/edit', block:'#block', container:$('#fasilitas').find('.contentTab')});
    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
    $aKode = [
        '1' => 'Rumah Dinas',
        '2' => 'Biaya Hidup',
        '3' => 'Jaminan Kesehatan',
        '4' => 'Mobil Dinas',
        '5' => 'Opsi Pembelian Saham',
        '6' => 'Fasilitas Lainnya'
    ];
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Informasi Penerimaan Fasilitas dalam Setahun dibawah ini ?
    <form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill//lhkpn/save_lampiran2">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                <label class="col-sm-3 control-label">Kode Jenis : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $aKode[$item->JENIS_FASILITAS];?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Fasilitas : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $item->NAMA_FASILITAS;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Pemberi Fasilitas : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $item->PEMBERI_FASILITAS;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Keterangan : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $item->KETERANGAN;?></p>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input value='<?php echo $item->ID_LHKPN;?>' name="ID_LHKPN" id="idLhkpn" type="hidden">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
            <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var ID = $('#idLhkpn').val();
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/14/'+ID + '/edit', block:'#block', container:$('#fasilitas').find('.contentTab')});
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
                <label class="col-sm-3 control-label">Kode Jenis : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $item->JENIS_FASILITAS;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Fasilitas : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $item->NAMA_FASILITAS;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Pemberi Fasilitas : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $item->PEMBERI_FASILITAS;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Keterangan : </label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $item->KETERANGAN;?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-dange" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker();
    })
</script>