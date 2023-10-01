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
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/efill/lhkpn/save_lampiran1" enctype="multipart/form-data">
        <div class="box-body">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Jenis Pelepasan <span class="red-label">*</span> </label>
                  
                        <select required name='JENIS' class="form-control" placeholder="Jenis Pelepasan">
                            <option value="">Jenis Pelepasan</option>
                            <option value="1">Penjualan</option>
                            <option value="2">Pelepasan Hibah</option>
                            <option value="3">Pelepasan Lainnya</option>
                        </select>
                 
                </div>

                <div class="form-group">
                    <label>Tanggal Transaksi  <span class="red-label">*</span> </label>
                   
                        <input required type="text"  name='TGL' class="form-control datepicker" placeholder="DD/MM/YYYY">
                   
                </div>

                <div class="form-group">
                    <label>Nilai Pelepasan(Rp) <span class="red-label">*</span> </label>
                   
                        <input required type="text" name='NILAI' class="form-control int" placeholder="Nilai Pelepasan">
                  
                </div>

                <div class="form-group">
                    <label style="font-size: 15px;">Pihak Kedua</label>
                </div>

                <div class="form-group">
                    <label>Nama <span class="red-label">*</span> </label>
                   
                        <input required type="text" name='NAMA' class="form-control" placeholder="Nama">
                   
                </div>

                <div class="form-group">
                    <label>Alamat <span class="red-label">*</span> :</label>
                   
                        <textarea required name='ALAMAT' class="form-control" placeholder="Alamat"></textarea>
                   
                </div>

                <div class="form-group">
                    <label>Keterangan <span class="red-label">*</span> :</label>
                   
                        <input required type="text" name='KETERANGAN' class="form-control" placeholder="Keterangan">
                  
                </div>
            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsert">
            <input type='hidden' readonly name='ID_LHKPN' id="id_lhkpn" value="<?=@$id_lhkpn;?>">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormAdd"), 'insert', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/13/<?= @$id_lhkpn; ?>/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
    });
</script>
<?php
}
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/efill/lhkpn/save_lampiran1">
        <div class="box-body">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Jenis Pelepasan <span class="red-label">*</span> :</label>
                   
                        <select required name='JENIS' class="form-control" placeholder="Jenis Pelepasan">
                            <option value="">Jenis Pelepasan</option>
                            <option <?= ($item->JENIS_PELEPASAN_HARTA == '1' ? 'selected' : '' ); ?> value="1">Penjualan</option>
                            <option <?= ($item->JENIS_PELEPASAN_HARTA == '2' ? 'selected' : '' ); ?> value="2">Pelepasan Hibah</option>
                            <option <?= ($item->JENIS_PELEPASAN_HARTA == '3' ? 'selected' : '' ); ?> value="3">Pelepasan Lainnya</option>
                        </select>
                   
                </div>

                <div class="form-group">
                    <label>Tanggal Transaksi  <span class="red-label">*</span> :</label>
                    
                        <input value="<?= date('d/m/Y', strtotime($item->TANGGAL_TRANSAKSI)); ?>" required type="text" name='TGL' class="form-control datepicker" placeholder="DD/MM/YYYY">
                   
                </div>

                <div class="form-group">
                    <label>Nilai Pelepasan(Rp) <span class="red-label">*</span> </label>
                  
                        <input value="<?= @$item->NILAI_PELEPASAN; ?>" required type="text" name='NILAI' class="form-control int" placeholder="Nilai Pelepasan">
                  
                </div>

                <div class="form-group">
                    <label style="font-size: 15px;">Pihak Kedua</label>
                </div>

                <div class="form-group">
                    <label>Nama <span class="red-label">*</span> </label>
                   
                        <input value="<?= @$item->NAMA; ?>" required type="text" name='NAMA' class="form-control" placeholder="Nama">
                   
                </div>

                <div class="form-group">
                    <label>Alamat <span class="red-label">*</span> </label>
                   
                        <textarea required name='ALAMAT' class="form-control" placeholder="Alamat"><?= @$item->ALAMAT; ?></textarea>
                  
                </div>

                <div class="form-group">
                    <label>Keterangan <span class="red-label">*</span> </label>
                   
                        <input value="<?= @$item->URAIAN_HARTA; ?>" required type="text" name='KETERANGAN' class="form-control" placeholder="Keterangan">
                   
                </div>
            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="hidden" name="act" value="doupdate">
            <input type='hidden' readonly name='ID' value='<?=@$item->ID;?>'>
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {           
        ng.formProcess($("#ajaxFormEdit"), 'update', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/13/<?= @$item->ID_LHKPN; ?>/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
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
    <form class="form-horizontal" method="post" id="ajaxFormDelete" action="index.php/efill/lhkpn/save_lampiran1">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Jenis Pelepasan</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo ($item->JENIS_PELEPASAN_HARTA == '1' ? ($item->JENIS_PELEPASAN_HARTA == '2' ? 'Pelepasan Hibah' : 'Penjualan' ) : 'Pelepasan Lainnya');?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tanggal Transaksi</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $item->TANGGAL_TRANSAKSI;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nilai Pelepasan</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">Rp. <?php echo $item->NILAI_PELEPASAN;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="font-size: 15px;">Pihak Kedua</label>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama </label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $item->NAMA;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Alamat</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $item->ALAMAT;?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Keterangan</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php echo $item->URAIAN_HARTA;?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="ID" value="<?php echo $item->ID;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
            <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormDelete"), 'delete', '', ng.LoadAjaxTabContent, {url:'index.php/efill/lhkpn/showTable/13/<?= @$item->ID_LHKPN; ?>/edit', block:'#block', container:$('#pelepasanharta').find('.contentTab')});
    });
</script>
<?php
}
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker();

        $(".int").inputmask("integer", {
            'groupSeparator' : '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false
        });
    })
</script>