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
 * @package Views/pejabat
*/
?>
<?php
if($form=='add'){
?>
<div id="wrapperFormAdd">
   
<!-- Tambah Data -->
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ereg/pn/savepn" enctype="multipart/form-data">
    <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
            <div role="tabpanel" class="tab-pane active" id="a">
            <div class="contentTab">
            
        <div class="form-group">
            <label class="col-sm-3 control-label">NIK <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' maxlength="16" size='40' name='NIK' id='NIK' placeholder="NIK" onblur="cek_user(this.value)">
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_add" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' size='40' name='NAMA' id='NAMA' placeholder="Nama">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jenis Kelamin <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <select required class="form-control" name="JNS_KEL">
                    <option value="1">Laki - Laki</option>
                    <option value="2">Perempuan</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Tempat / Tanggal Lahir <span class="red-label">*</span>:</label>
            <div class="col-sm-4">
                <input required class="form-control" type='text'name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat">
            </div>
            <div class="col-sm-3">
                <input required class="form-control date-picker" type='text'name='TGL_LAHIR' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Agama <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
                <select required class="form-control" name="ID_AGAMA">
                    <option value="1">ISLAM</option>
                    <option value="2">KRISTEN</option>
                    <option value="3">KALOTIK</option>
                    <option value="4">HINDU</option>
                    <option value="5">BUDHA</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Status Nikah <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
                <select required class="form-control" name="ID_STATUS_NIKAH">
                    <option value="1">KAWIN</option>
                    <option value="2">TIDAK KAWIN</option>
                    <option value="3">JANDA</option>
                    <option value="4">DUDA</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Pendidikan Terakhir <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
                <select required class="form-control" name="ID_PENDIDIKAN">
                    <option value="1">SD</option>
                    <option value="2">SLTP</option>
                    <option value="3">SLTA</option>
                    <option value="4">D3</option>
                    <option value="5">S1/D4</option>
                    <option value="6">S2</option>
                    <option value="7">S4</option>
                </select>
            </div>
        </div>
            </div>
            <br>
            <div class="pull-right">
            <a href="#b" aria-controls="final" role="tab" data-toggle="tab" class="navTab">
            <button type="button" class="btn btn-sm btn-primary btnNext">Selanjutnya <i class="fa fa-chevron-circle-right"></i></button>
            </a>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
            </div>
            <div class="clearfix"></div>
            </div>

            <div role="tabpanel" class="tab-pane" id="b">
            <div class="contentTab">
            <div class="form-group">
            <label class="col-sm-3 control-label">NPWP <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' name='NPWP' id='NPWP' placeholder="NPWP">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Tinggal <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' name='ALAMAT_TINGGAL' id='ALAMAT_TINGGAL' placeholder="Alamat Tinggal">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='email' size='40' name='EMAIL' onblur="cek_email(this.value);" id='EMAIL' placeholder="Email">
                <span class="help-block"><font id='email_ada' style='display:none;' color='red'>Email sudah terdaftar</font></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">NO HP <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control numbersOnly" type='text' size='40' name='NO_HP' id='NO_HP' placeholder="NO HP">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Foto <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input type='file' size='40' name='FOTO' id='FOTO'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Status PN <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <select name="STATUS_PN">
                    <option value="1">PN/WL</option>
                    <option value="2">Calon PN</option>
                </select>
            </div>
        </div>
            </div>
            <br>
            <div class="pull-right">
            <a href="#a" aria-controls="final" role="tab" data-toggle="tab" class="navTab">
            <button type="button" class="btn btn-sm btn-primary btnNext"><i class="fa fa-chevron-circle-left"></i> Sebelumnya</button>
            </a>
            <input type="hidden" name="ID_PN" id="ID_PN">
            <input type="hidden" name="act" value="doinsert">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
            </div>
            <div class="clearfix"></div>
            </div>
    </div>
<!-- End Tambah Data -->



        
        <!-- <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <select required class="form-control" name="JABATAN" id="JABATAN" style="width: 100%;">
                    <option value=""> - Pilih Jabatan - </option>
                    <?php
                    foreach ( $jabatans as $jabatan ) {
                        ?>
                        <option value="<?php echo $jabatan->ID_JABATAN ?>"><?php echo $jabatan->NAMA_JABATAN ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Bidang <span class="red-label">*</span>:</label>
            <div class="col-sm-9" style="padding-left: 30px;">
            	<div class="form-group">
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="Eksekutif" type="radio" checked="checked"> Eksekutif
                    	</label>
                  	</div>
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="Legislatif" type="radio"> Legislatif
                    	</label>
                  	</div>
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="Yudikatif" type="radio"> Yudikatif
                    	</label>
                  	</div>
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="BUMN-D" type="radio"> BUMN-D
                    	</label>
                  	</div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Tingkat <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' size='40' name='TINGKAT' id='TINGKAT' placeholder="Tingkat">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <select class="form-control" name="UNIT_KERJA" id="UNIT_KERJA">
                    <option value=""> - Pilih Unit Kerja - </option>
                    <?php
                    foreach ( $unitkerjas as $unitkerja ) {
                        ?>
                        <option value="<?php echo $unitkerja->UK_ID; ?>">
                            <?php echo $unitkerja->UK_NAMA; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Kantor <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' size='40' name='ALAMAT_KANTOR' id='ALAMAT_KANTOR' placeholder="Alamat Kantor">
            </div>
        </div> -->

        <div class="pull-right">
            
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
?>
<?php
if($form=='edit'){
?>
<div id="wrapperFormEdit">

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#datapn" aria-controls="emailpn" role="tab" data-toggle="tab" class="navTab" title="Data PN"><i class="fa fa-user"></i> <span>Data PN</span></a></li>
<li role="presentation" class=""><a href="#jabatan" aria-controls="jabatan" role="tab" data-toggle="tab" class="navTab" title="Jabatan"><i class="fa fa-certificate"></i> <span>Jabatan</span></a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
    <!--  -->
    <div role="tabpanel" class="tab-pane active" id="datapn">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/ereg/pn/savepn" enctype="multipart/form-data">
        <input type='hidden' name='ID_PN' id='ID_PN'  value='<?php echo $item->ID_PN;?>'>
        <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
        <input type="hidden" name="HIDDEN_NIK" id="HIDDEN_NIK" value="<?php echo $item->NIK; ?>">
        <div class="form-group">
            <label class="col-sm-3 control-label">NIK <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' maxlength="16" name='NIK' id='NIK' placeholder="NIK" value='<?php echo $item->NIK;?>' onblur="cek_user_edit(this.value, $('#HIDDEN_NIK').val())">
                <span class="help-block"><font id='username_ada' style='display:none;' color='red'>User PN dengan NIK <span id="check_uname_edit" style="font-style: italic; font-weight: bold;"></span> sudah terdaftar</font></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' name='NAMA' id='NAMA' placeholder="Nama" value='<?php echo $item->NAMA;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jenis Kelamin <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
				<select class="form-control" name="JNS_KEL">
					<option value="1" <?php if($item->JNS_KEL == 1) echo "selected"; ?> >Laki - Laki</option>
					<option value="2" <?php if($item->JNS_KEL == 2) echo "selected"; ?>>Perempuan</option>
				</select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
            <div class="col-sm-4">
                <input required class="form-control" type='text' name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat Lahir" value='<?php echo $item->TEMPAT_LAHIR;?>'>
            </div>
            <div class="col-sm-3">
                <input required class="form-control" type='text' name='TGL_LAHIR' id='TGL_LAHIR' placeholder="Tanggal Lahir" value='<?php echo $item->TGL_LAHIR;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Agama <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
				<select name="ID_AGAMA" class="form-control">
					<option value="1" <?php if($item->ID_AGAMA==1) echo "selected";?> >ISLAM</option>
					<option value="2" <?php if($item->ID_AGAMA==2) echo "selected";?> >KRISTEN</option>
					<option value="3" <?php if($item->ID_AGAMA==3) echo "selected";?>>KALOTIK</option>
					<option value="4" <?php if($item->ID_AGAMA==4) echo "selected";?>>HINDU</option>
					<option value="5" <?php if($item->ID_AGAMA==5) echo "selected";?>>BUDHA</option>
				</select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Statys Nikah <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
				<select name="ID_STATUS_NIKAH" class="form-control">
					<option value="1" <?php if($item->ID_STATUS_NIKAH==1) echo "selected";?>  >KAWIN</option>
					<option value="2" <?php if($item->ID_STATUS_NIKAH==2) echo "selected";?> >TIDAK KAWIN</option>
					<option value="3" <?php if($item->ID_STATUS_NIKAH==3) echo "selected";?>  >JANDA</option>
					<option value="4" <?php if($item->ID_STATUS_NIKAH==4) echo "selected";?> >DUDA</option>
				</select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Pendidikan Terakhir <span class="red-label">*</span>:</label>
            <div class="col-sm-3">
				<select name="ID_PENDIDIKAN" class="form-control">
					<option value="1" <?php if($item->ID_PENDIDIKAN==1) echo "selected";?> >SD</option>
					<option value="2" <?php if($item->ID_PENDIDIKAN==2) echo "selected";?>>SLTP</option>
					<option value="3" <?php if($item->ID_PENDIDIKAN==3) echo "selected";?>>SLTA</option>
					<option value="4" <?php if($item->ID_PENDIDIKAN==4) echo "selected";?>>D3</option>
					<option value="5" <?php if($item->ID_PENDIDIKAN==5) echo "selected";?>>S1/D4</option>
					<option value="6" <?php if($item->ID_PENDIDIKAN==6) echo "selected";?>>S2</option>
					<option value="7" <?php if($item->ID_PENDIDIKAN==7) echo "selected";?>>S4</option>
				</select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">NPWP <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' name='NPWP' id='NPWP' placeholder="NPWP" value='<?php echo $item->NPWP;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Tinggal <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' name='ALAMAT_TINGGAL' id='ALAMAT_TINGGAL' placeholder="Alamat Tinggal" value='<?php echo $item->ALAMAT_TINGGAL;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control" type='text' name='EMAIL' id='EMAIL' placeholder="Email" value='<?php echo $item->EMAIL;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">No HP <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input required class="form-control numbersOnly" type='text' name='NO_HP' id='NO_HP' placeholder="NO HP" value='<?php echo $item->NO_HP;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Foto <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <input type='file' size='40' name='FOTO' id='FOTO'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Status PN <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <select name="STATUS_PN">
                    <option value="1" <?php echo $item->STATUS_PN==1?'selected':'';?>>PN/WL</option>
                    <option value="2" <?php echo $item->STATUS_PN==2?'selected':'';?>>Calon PN</option>
                </select>
            </div>
        </div>
        <!-- <div class="form-group">
            <label class="col-sm-3 control-label">Bidang <span class="red-label">*</span>:</label>
            <div class="col-sm-9" style="padding-left: 30px;">
            	<div class="form-group">
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="Eksekutif" type="radio" <?php echo $item->BIDANG=='Eksekutif'?'checked':'';?>> Eksekutif
                    	</label>
                  	</div>
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="Legislatif" type="radio" <?php echo $item->BIDANG=='Legislatif'?'checked':'';?>> Legislatif
                    	</label>
                  	</div>
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="Yudikatif" type="radio" <?php echo $item->BIDANG=='Yudikatif'?'checked':'';?>> Yudikatif
                    	</label>
                  	</div>
                  	<div class="radio">
                    	<label>
                      		<input name="BIDANG" value="BUMN-D" type="radio" <?php echo $item->BIDANG=='BUMN-D'?'checked':'';?>> BUMN-D
                    	</label>
                  	</div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Tingkat <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' size='40' name='TINGKAT' id='TINGKAT' placeholder="Tingkat" value='<?php echo $item->TINGKAT;?>'>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <select class="form-control" name="UNIT_KERJA" id="UNIT_KERJA">
                    <option value=""> - Pilih Unit Kerja - </option>
                    <?php
                    foreach ( $unitkerjas as $unitkerja ) {
                        $selected = ($unitkerja->UK_ID == $item->UNIT_KERJA ) ? 'selected' : NULL;
                        ?>
                        <option value="<?php echo $unitkerja->UK_ID; ?>" <?php echo $selected ?>>
                            <?php echo $unitkerja->UK_NAMA; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Eselon <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <select required class="form-control" name="ESELON" id="ESELON" style="width: 100%;">
                    <option value="">- Pilih Eselon -</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan <span class="red-label">*</span>:</label>
            <div class="col-sm-5">
                <select required class="form-control" name="JABATAN" id="JABATAN" style="width: 100%;">
                    <option value=""> - Pilih Jabatan - </option>
                    <?php
                    foreach ( $jabatans as $jabatan ) {
                        $selected = ($jabatan->ID_JABATAN == $item->ID_JABATAN ) ? 'selected' : NULL;
                        ?>
                        <option value="<?php echo $jabatan->ID_JABATAN ?>" <?php echo $selected; ?>>
                            <?php echo $jabatan->NAMA_JABATAN ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Kantor <span class="red-label">*</span>:</label>
            <div class="col-sm-7">
                <input required class="form-control" type='text' size='40' name='ALAMAT_KANTOR' id='ALAMAT_KANTOR' placeholder="Alamat Kantor" value='<?php echo $item->ALAMAT_KANTOR;?>'>
            </div>
        </div> -->
        <div class="pull-right">
            <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" value="doupdate">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
        </div>
    </form>
            <div class="clearfix"></div>
        </div>    
        <!--  -->
        <div role="tabpanel" class="tab-pane" id="jabatan">
            <div id="wrapperJabatanList">
                
            <button type="button" class="btn btn-sm btn-default btn-add btn-primary btn-addJabatan" href="index.php/ereg/pn/addjabatan/<?php echo $item->ID_PN;?>"><i class="fa fa-plus"></i> Tambah</button>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th>Lembaga</th>
                        <th>Unit Kerja</th>
                        <th>Jabatan/Eselon</th>
                        <th>Utama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($jabatans as $jabatan) {
                    ?>
                    <tr>
                        <td><?php echo ++$i; ?>.</td>
                        <td><?php echo $jabatan->INST_NAMA; ?></td>
                        <td><?php echo $jabatan->UNIT_KERJA; ?></td>
                        <td><?php echo $jabatan->JABATAN; ?>/<?php echo $jabatan->ESELON; ?></td>
                        <td><input type="radio" name="utama" value="<?php echo $jabatan->ID; ?>"></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-default btn-editJabatan" href="index.php/ereg/pn/editjabatan/<?php echo $item->ID;?>" title="Edit"><i class="fa fa-pencil"></i></button>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            </div>
            <div id="wrapperJabatanFormAdd" style="display: none;">
                <!--  -->
                <form class="form-horizontal" method="post" id="ajaxFormAddJabatan" action="index.php/ereg/pn/savejabatan" enctype="multipart/form-data">
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Lembaga<font color='red'>*</font> :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-12'>
                            <input type='text' class="form-control form-select2" name='LEMBAGA1' onchange="LEMBAGA();" style="border:none;" id='LEMBAGA1' value='<?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->LEMBAGA != '') { echo json_decode(@$DATA_PRIBADI->JABATAN)->LEMBAGA;}else{ echo $LHKPN->LEMBAGA;}?>' placeholder="lembaga">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Unit Kerja<font color='red'>*</font> :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-12'>
                            <input type='text' class="form-control form-select2" name='UNIT_KERJA1' style="border:none;" id='UNIT_KERJA1' value='<?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->UNIT_KERJA != '') { echo json_decode(@$DATA_PRIBADI->JABATAN)->UNIT_KERJA;}else{ echo $LHKPN->UNIT_KERJA;}?>' placeholder="Unit Kerja">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Alamat Kantor :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-12'>
                            <textarea class='form-control' name="ALAMAT_KANTOR1" placeholder="Alamat Kantor"><?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR != '') { echo @json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR;}else{ echo $LHKPN->NAMA;}?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Email Kantor :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-12'>
                            <input type='email' class='form-control' value="<?=@json_decode(@$DATA_PRIBADI->JABATAN)->EMAIL_KANTOR?>" name="EMAIL_KANTOR1" placeholder="Email Kantor">
                        </div>
                    </div>
                </div>
                  <div class="pull-right">
                      <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
                      <input type="hidden" name="act" value="doaddjabatan">
                      <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                      <input type="reset" class="btn btn-sm btn-default btn-batalAddJabatan" value="Batal">
                  </div>
                </form>
                <!--  -->
            </div>
            <div id="wrapperJabatanFormEdit" style="display: none;">
                
            </div>
            <div class="clearfix"></div>
        </div>
    </div>    
</div>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEdit"), 'update', location.href.split('#')[1]);
        function backtoJabatanList(){
            $('#wrapperJabatanList').show();
            $('#wrapperJabatanFormAdd').hide();
            $('#wrapperJabatanFormEdit').hide();
                        
        }
        $('.btn-addJabatan').click(function(){
            $('#wrapperJabatanList').hide();
            $('#wrapperJabatanFormAdd').show();
            $('#wrapperJabatanFormEdit').hide();
            ng.formProcess($("#ajaxFormAddJabatan"), 'insert', location.href.split('#')[1], backtoJabatanList, '', false);
        });
        $('.btn-editJabatan').click(function(){
            $('#wrapperJabatanList').hide();
            $('#wrapperJabatanFormAdd').hide();
            $('#wrapperJabatanFormEdit').show();
            url = $(this).attr('href');
            $.post(url, function(html) {
                $('#wrapperJabatanFormEdit').html(html);
            });
        });
        $('.btn-batalAddJabatan').click(function(){
            $('#wrapperJabatanList').show();
            $('#wrapperJabatanFormAdd').hide();
            $('#wrapperJabatanFormEdit').hide();
        });

       $('input[name="LEMBAGA1"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/efill/lhkpn/getLembaga')?>",
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
                    $.ajax("<?=base_url('index.php/efill/lhkpn/getLembaga')?>/"+id, {
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
        // var lembaga1 = $('input[name="LEMBAGA1"]').val();
        $('input[name="UNIT_KERJA1"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val(),
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
                    $.ajax("<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val()+'/'+id, {
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

        $('input[name="JABATAN1"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/ereg/pn/getJabatan')?>",
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
                    $.ajax("<?=base_url('index.php/ereg/pn/getJabatan')?>/"+id, {
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
        function LEMBAGA()
        {
            $('input[name="UNIT_KERJA1"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val(),
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
                        $.ajax("<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val()+'/'+id, {
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
        }

    });
</script>
<?php
}
?>
<?php
if($form=='delete'){
?>
<div id="wrapperFormDelete">
    Benarkah Akan Menghapus Pejabat dibawah ini ?
    <form method="post" id="ajaxFormDelete" action="index.php/ereg/pn/savepn">
        <table class="table-detail">
			<tr><td>NIK</td><td>:</td><td><?php echo $item->NIK;?></td></tr>
			<tr><td>Nama</td><td>:</td><td><?php echo $item->NAMA;?></td></tr>
			<tr><td>Jenis Kelamin</td><td>:</td><td>
				<?php if($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
				<?php if($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
			</td></tr>
			<tr><td>Tempat / Tanggal Lahir</td><td>:</td><td>
				<?php echo $item->TEMPAT_LAHIR; ?> / <?php echo $item->TGL_LAHIR;?>
				</td></tr>
			<tr><td>Agama</td><td>:</td><td>
				<?php if($item->ID_AGAMA==1) echo "ISLAM";?>
				<?php if($item->ID_AGAMA==2) echo "KRISTEN";?>
				<?php if($item->ID_AGAMA==3) echo "KALOTIK";?>
				<?php if($item->ID_AGAMA==4) echo "HINDU";?>
				<?php if($item->ID_AGAMA==5) echo "BUDHA";?>
			<td></tr>
			<tr><td>Status Nikah</td><td>:</td><td>
				<?php if($item->ID_STATUS_NIKAH==1) echo "KAWIN";?>
				<?php if($item->ID_STATUS_NIKAH==2) echo "TIDAK KAWIN";?>
				<?php if($item->ID_STATUS_NIKAH==3) echo "JANDA";?>
				<?php if($item->ID_STATUS_NIKAH==4) echo "DUDA";?>
				
			</td></tr>	
			<tr><td>Pendidikan Terakhir</td><td>:</td><td>
				<?php if($item->ID_PENDIDIKAN==1) echo "SD";?>
				<?php if($item->ID_PENDIDIKAN==2) echo "SLTP";?>
				<?php if($item->ID_PENDIDIKAN==3) echo "STLA";?>
				<?php if($item->ID_PENDIDIKAN==4) echo "D3";?>
				<?php if($item->ID_PENDIDIKAN==5) echo "S1/D4";?>
				<?php if($item->ID_PENDIDIKAN==6) echo "S2";?>
				<?php if($item->ID_PENDIDIKAN==7) echo "S4";?>
			</td></tr>
			<tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr>
			<tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr>
			<tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
			<tr><td>NO HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
			<tr><td>FOTO</td><td>:</td><td>
				<?php if($item->FOTO != "")
						echo "<img src='".$item->FOTO."' width='64'>";
					?>
            </td>
            </tr>            
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary">Hapus</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
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
<div id="wrapperFormDetail">


<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#datapn" aria-controls="emailpn" role="tab" data-toggle="tab" class="navTab" title="Data PN"><i class="fa fa-user"></i> <span>Data PN</span></a></li>
<li role="presentation" class=""><a href="#jabatan" aria-controls="jabatan" role="tab" data-toggle="tab" class="navTab" title="Jabatan"><i class="fa fa-certificate"></i> <span>Jabatan</span></a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
    <!--  -->
    <div role="tabpanel" class="tab-pane active" id="datapn">
            <table class="table-detail">
                <tr><td width="150px">Id Penyelenggara Negara</td><td width="5px">:</td><td><?php echo $item->ID_PN;?></td></tr>
                    <tr><td>NIK</td><td>:</td><td><?php echo $item->NIK;?></td></tr>
                    <tr><td>Nama</td><td>:</td><td><?php echo $item->NAMA;?></td></tr>
                    <tr><td>Jenis Kelamin</td><td>:</td><td>
                        <?php if($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
                        <?php if($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
                    </td></tr>
                    <tr><td>Tempat / Tanggal Lahir</td><td>:</td><td>
                        <?php echo $item->TEMPAT_LAHIR; ?> / <?php echo $item->TGL_LAHIR;?>
                        </td></tr>
                    <tr><td>Agama</td><td>:</td><td>
                        <?php if($item->ID_AGAMA==1) echo "ISLAM";?>
                        <?php if($item->ID_AGAMA==2) echo "KRISTEN";?>
                        <?php if($item->ID_AGAMA==3) echo "KALOTIK";?>
                        <?php if($item->ID_AGAMA==4) echo "HINDU";?>
                        <?php if($item->ID_AGAMA==5) echo "BUDHA";?>
                    <td></tr>
                    <tr><td>Status Nikah</td><td>:</td><td>
                        <?php if($item->ID_STATUS_NIKAH==1) echo "KAWIN";?>
                        <?php if($item->ID_STATUS_NIKAH==2) echo "TIDAK KAWIN";?>
                        <?php if($item->ID_STATUS_NIKAH==3) echo "JANDA";?>
                        <?php if($item->ID_STATUS_NIKAH==4) echo "DUDA";?>
                        
                    </td></tr>  
                    <tr><td>Pendidikan Terakhir</td><td>:</td><td>
                        <?php if($item->ID_PENDIDIKAN==1) echo "SD";?>
                        <?php if($item->ID_PENDIDIKAN==2) echo "SLTP";?>
                        <?php if($item->ID_PENDIDIKAN==3) echo "STLA";?>
                        <?php if($item->ID_PENDIDIKAN==4) echo "D3";?>
                        <?php if($item->ID_PENDIDIKAN==5) echo "S1/D4";?>
                        <?php if($item->ID_PENDIDIKAN==6) echo "S2";?>
                        <?php if($item->ID_PENDIDIKAN==7) echo "S4";?>
                    </td></tr>
                    <tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr>
                    <tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr>
                    <tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
                    <tr><td>NO HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
                    <tr><td>FOTO</td><td>:</td><td>
                        <?php if($item->FOTO != "")
                                echo "<img src='".$item->FOTO."' width='64'>";
                            ?>
                    
                    <tr><td>Jabatan</td><td>:</td><td><?php echo $item->JABATAN;?></td></tr>
                    <tr><td>Bidang</td><td>:</td><td><?php echo $item->BIDANG;?></td></tr>
                    <tr><td>Lembaga</td><td>:</td><td><?php echo $item->LEMBAGA;?></td></tr>
                    <tr><td>Tingkat</td><td>:</td><td><?php echo $item->TINGKAT;?></td></tr>
                    <tr><td>Unit Kerja</td><td>:</td><td><?php echo $item->UNIT_KERJA;?></td></tr>
                    <tr><td>Alamat Kantor</td><td>:</td><td><?php echo $item->ALAMAT_KANTOR;?></td></tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                    <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
                    </td>
                </tr>        
                <tr>
                    <td colspan="3" style="text-align:right;">
                    </td>
                </tr>
            </table>
            <div class="pull-right">
                <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
            </div>
            <div class="clearfix"></div>
        </div>    
        <!--  -->
        <div role="tabpanel" class="tab-pane" id="jabatan">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th>Lembaga</th>
                        <th>Unit Kerja</th>
                        <th>Jabatan/Eselon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($jabatans as $jabatan) {
                    ?>
                    <tr>
                        <td><?php echo ++$i; ?>.</td>
                        <td><?php echo $jabatan->INST_NAMA; ?></td>
                        <td><?php echo $jabatan->UNIT_KERJA; ?></td>
                        <td><?php echo $jabatan->JABATAN; ?>/<?php echo $jabatan->ESELON; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="clearfix"></div>
        </div>
=======
    <table class="table-detail">
        <tr><td width="150px">Id Pejabat</td><td width="5px">:</td><td><?php echo $item->ID_PN;?></td></tr>
			<tr><td>NIK</td><td>:</td><td><?php echo $item->NIK;?></td></tr>
			<tr><td>Nama</td><td>:</td><td><?php echo $item->NAMA;?></td></tr>
			<tr><td>Jenis Kelamin</td><td>:</td><td>
				<?php if($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
				<?php if($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
			</td></tr>
			<tr><td>Tempat / Tanggal Lahir</td><td>:</td><td>
				<?php echo $item->TEMPAT_LAHIR; ?> / <?php echo $item->TGL_LAHIR;?>
				</td></tr>
			<tr><td>Agama</td><td>:</td><td>
				<?php if($item->ID_AGAMA==1) echo "ISLAM";?>
				<?php if($item->ID_AGAMA==2) echo "KRISTEN";?>
				<?php if($item->ID_AGAMA==3) echo "KALOTIK";?>
				<?php if($item->ID_AGAMA==4) echo "HINDU";?>
				<?php if($item->ID_AGAMA==5) echo "BUDHA";?>
			<td></tr>
			<tr><td>Status Nikah</td><td>:</td><td>
				<?php if($item->ID_STATUS_NIKAH==1) echo "KAWIN";?>
				<?php if($item->ID_STATUS_NIKAH==2) echo "TIDAK KAWIN";?>
				<?php if($item->ID_STATUS_NIKAH==3) echo "JANDA";?>
				<?php if($item->ID_STATUS_NIKAH==4) echo "DUDA";?>
				
			</td></tr>	
			<tr><td>Pendidikan Terakhir</td><td>:</td><td>
				<?php if($item->ID_PENDIDIKAN==1) echo "SD";?>
				<?php if($item->ID_PENDIDIKAN==2) echo "SLTP";?>
				<?php if($item->ID_PENDIDIKAN==3) echo "STLA";?>
				<?php if($item->ID_PENDIDIKAN==4) echo "D3";?>
				<?php if($item->ID_PENDIDIKAN==5) echo "S1/D4";?>
				<?php if($item->ID_PENDIDIKAN==6) echo "S2";?>
				<?php if($item->ID_PENDIDIKAN==7) echo "S4";?>
			</td></tr>
			<tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr>
			<tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr>
			<tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
			<tr><td>NO HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
			<tr><td>FOTO</td><td>:</td><td>
				<?php if($item->FOTO != "")
						echo "<img src='".$item->FOTO."' width='64'>";
					?>

            <tr><td>Jabatan</td><td>:</td><td><?php echo $this->mjabatan->get_nama_jabatan($item->ID_JABATAN);?></td></tr>
            <tr><td>Bidang</td><td>:</td><td><?php echo $item->BIDANG;?></td></tr>
            <tr><td>Lembaga</td><td>:</td><td><?php echo $this->minstansi->get_nama_instansi($item->LEMBAGA);?></td></tr>
            <tr><td>Tingkat</td><td>:</td><td><?php echo $item->TINGKAT;?></td></tr>
            <tr><td>Unit Kerja</td><td>:</td><td><?php echo $this->munitkerja->get_nama_unit_kerja($item->UNIT_KERJA);?></td></tr>
            <tr><td>Alamat Kantor</td><td>:</td><td><?php echo $item->ALAMAT_KANTOR;?></td></tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td>
            <?php echo  $item->IS_ACTIVE == '1' ? 'active' : 'inactive'; ?>
            </td>
        </tr>        
        <tr>
            <td colspan="3" style="text-align:right;">
            </td>
        </tr>
    </table>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>
</div>
<?php
}
?>
<?php
if($form=='reset_password'){
    ?>
<form method="post" id="ajaxFormResetPassword" action="index.php/ereg/pn/do_reset_password">
    <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
    <div class="col-lg-12 text-center">
        <p>Apakah anda yakin untuk mereset password?</p>
        <button type="submit" class="btn btn-sm btn-primary">Ya</button>
        <input type="button" class="btn btn-sm btn-default" value="Tidak" onclick="CloseModalBox();">
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormResetPassword"), 'reset_password', location.href.split('#')[1]);
    });
</script>
<?php
}
?>
<?php
if($form=='_mutasi'){
    ?>
    <div id="wrapperFormAdd">

            <form class="form-horizontal" method="post" id="ajaxFormMutasi" action="index.php/ereg/pn/savemutasi">
                <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item->ID_USER; ?>">
                <input type="hidden" name="USERNAME" id="USERNAME" value="<?php echo $item->USERNAME; ?>">
                <input type="hidden" name="ID_INST_ASAL" id="ID_INST_ASAL" value="<?php echo $item->INST_SATKERKD; ?>">
                <div class="form-group">
                    <label class="col-sm-4 control-label">INSTANSI TUJUAN <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <select id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;">
                            <option value="">Tidak Pindah Instansi</option>
                            <?php
                            foreach ($instansis as $item) {
                                ?>
                                <option value="<?php echo $item->INST_SATKERKD ?>"><?php echo $item->INST_NAMA;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">UNIT KERJA <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="UNIT_KERJA" id="UNIT_KERJA">
                            <option value=""> - Pilih Unit Kerja - </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">JABATAN <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <select required id="JABATAN" name="JABATAN" class="select" style="width: 100%;">
                            <option value=""> -- Pilih Jabatan -- </option>
                            <?php
                            foreach ($jabatans as $item) {
                                ?>
                                <option value="<?php echo $item->ID_JABATAN ?>"><?php echo $item->NAMA_JABATAN;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="pull-right">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
                </div>
        </form>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#JABATAN').select2();
        $('#INST_TUJUAN').select2();
        $('#INST_TUJUAN').change(function() {
            $.post("<?php echo base_url();?>index.php/ereg/pn/get_unit_kerja", {INST_SATKERKD: $(this).val()})
                    .done(function (data) {
                        var uk      = JSON.parse(data);
                        var html    = '<option value=""> - Pilih Unit Kerja - </option> ';
                        for ( var i=0; i<uk.result.length; i++ ) {
                            html += '<option value="'+ uk.result[i].UK_ID +'">';
                            html += uk.result[i].UK_NAMA;
                            html += '</option>';
                        }
                        $('#UNIT_KERJA').html(html);
                    });
        });
        ng.formProcess($("#ajaxFormMutasi"), 'mutasi', location.href.split('#')[1]);
    });
</script>
<?php
}
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
	    $('.date-picker').datepicker({
	    format: 'dd/mm/yyyy'
	    })         
	});
</script>

<?php
if($form == 'editJabatan'){
?>

<form class="form-horizontal" method="post" id="ajaxFormEditJabatan" action="index.php/ereg/pn/savejabatan" enctype="multipart/form-data">
  <div class="form-group">
    <label class="col-sm-3 control-label">Lembaga<font color='red'>*</font> :</label>
    <div class="col-sm-9">
        <div class='col-sm-12'>
            <input type='text' class="form-control form-select2" name='LEMBAGA1' onchange="LEMBAGA();" style="border:none;" id='LEMBAGA1' value='<?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->LEMBAGA != '') { echo json_decode(@$DATA_PRIBADI->JABATAN)->LEMBAGA;}else{ echo $LHKPN->LEMBAGA;}?>' placeholder="lembaga">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Unit Kerja<font color='red'>*</font> :</label>
    <div class="col-sm-9">
        <div class='col-sm-12'>
            <input type='text' class="form-control form-select2" name='UNIT_KERJA1' style="border:none;" id='UNIT_KERJA1' value='<?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->UNIT_KERJA != '') { echo json_decode(@$DATA_PRIBADI->JABATAN)->UNIT_KERJA;}else{ echo $LHKPN->UNIT_KERJA;}?>' placeholder="Unit Kerja">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Alamat Kantor :</label>
    <div class="col-sm-9">
        <div class='col-sm-12'>
            <textarea class='form-control' name="ALAMAT_KANTOR1" placeholder="Alamat Kantor"><?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR != '') { echo @json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR;}else{ echo $LHKPN->NAMA;}?></textarea>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Email Kantor :</label>
    <div class="col-sm-9">
        <div class='col-sm-12'>
            <input type='email' class='form-control' value="<?=@json_decode(@$DATA_PRIBADI->JABATAN)->EMAIL_KANTOR?>" name="EMAIL_KANTOR1" placeholder="Email Kantor">
        </div>
    </div>
</div>
  <div class="pull-right">
      <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN;?>">
      <input type="hidden" name="act" value="doaddjabatan">
      <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
      <input type="reset" class="btn btn-sm btn-default btn-batalEditJabatan" value="Batal">
  </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        ng.formProcess($("#ajaxFormEditJabatan"), 'update', location.href.split('#')[1], '');

        $('.btn-batalEditJabatan').click(function(){
            $('#wrapperJabatanList').show();
            $('#wrapperJabatanFormAdd').hide();
            $('#wrapperJabatanFormEdit').hide();
        });

        $('input[name="LEMBAGA1"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/efill/lhkpn/getLembaga')?>",
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
                    $.ajax("<?=base_url('index.php/efill/lhkpn/getLembaga')?>/"+id, {
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
        // var lembaga1 = $('input[name="LEMBAGA1"]').val();
        $('input[name="UNIT_KERJA1"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val(),
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
                    $.ajax("<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val()+'/'+id, {
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

        $('input[name="JABATAN1"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/ereg/pn/getJabatan')?>",
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
                    $.ajax("<?=base_url('index.php/ereg/pn/getJabatan')?>/"+id, {
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
        function LEMBAGA()
        {
            $('input[name="UNIT_KERJA1"]').select2({
                minimumInputLength: 0,
                ajax: {
                    url: "<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val(),
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
                        $.ajax("<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+$('#LEMBAGA1').val()+'/'+id, {
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
        }

    });
</script>
<?php
}
?>

<?php
if($form=='mutasi'){
    ?>
    <script type="text/javascript">
    <?php
    $arr_status = array();
    foreach ( $status_akhir as $status ) {
        $arr_status[$status->ID_STATUS_AKHIR_JABAT] = $status;
    }
    ?>
        var status_akhir = '<?php echo json_encode(array('result' => $arr_status)) ?>';
        var data_status_akhir = JSON.parse(status_akhir);
    </script>
    <div id="wrapperFormAdd">
           

            <form class="form-horizontal" method="post" id="ajaxFormMutasi" action="index.php/ereg/all_pn/savemutasi">
                <input type="hidden" name="ID_USER" id="ID_USER" value="<?php echo $item_data->ID_USER; ?>">
                <input type="hidden" name="USERNAME" id="USERNAME" value="<?php echo $item_data->USERNAME; ?>">
                <input type="hidden" name="pn" id="pn" value="<?php echo $item_pn->ID_PN; ?>">
                <input type="hidden" name="ID_INST_ASAL" id="ID_INST_ASAL" value="<?php echo $item_data->INST_SATKERKD; ?>">
                <input type="hidden" name="ID_JABATAN_ASAL" id="ID_JABATAN_ASAL" value="<?php echo $jabatan->ID; ?>">

                <div class="form-group">
                    <label class="col-sm-4 control-label">Jenis Mutasi :</label>
                    <div class="col-sm-8">
                        <select id="JENIS_MUTASI" name="JENIS_MUTASI" class="select" style="width: 100%;">
                            <?php
                            foreach ( $status_akhir as $status ) {
                                ?>
                                <option value="<?php echo $status->ID_STATUS_AKHIR_JABAT; ?>">
                                    <?php echo $status->STATUS; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama PN<span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <div class="control-label" style="text-align:left !important;"><?=$item_pn->NAMA?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Jabatan <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <div class="control-label" id="nama_jabatan" style="text-align:left !important;"><?=$jabatan->NAMA_JABATAN?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Instansi Asal <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <select required id="INST_ASAL" name="INST_ASAL" class="select" style="width: 100%;">
                            <?php
                            foreach ($instansi_asal as $item) {
                                ?>
                                <option value="<?php echo $item->INST_SATKERKD ?>"><?php echo $item->INST_NAMA;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group div_pindah">
                    <label class="col-sm-4 control-label">Instansi Tujuan <span class="red-label">*</span>:</label>
                    <div class="col-sm-8">
                        <select id="INST_TUJUAN" name="INST_TUJUAN" class="select" style="width: 100%;">
                            <option value="">Tidak Pindah Instansi</option>
                            <?php
                            foreach ($instansis as $item) {
                                ?>
                                <option value="<?php echo $item->INST_SATKERKD ?>"><?php echo $item->INST_NAMA;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Unit Kerja<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <input type='text' class="form-control form-select2" name='UNIT_KERJA' style="border:none;" id='UNIT_KERJA' value='' placeholder="Unit Kerja" required>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Jabatan<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-select2" name="JABATAN" style="border:none;" id='JABATAN' value="" placeholder="Jabatan" required>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Deskripsi Jabatan<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="DESKRIPSI_JABATAN" value="" placeholder="Deskripsi Jabatan" required>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Eselon<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <select class="form-control" name='ESELON' id='ESELON' value='' required placeholder="ESELON">
                            <option value='1'>I</option>
                            <option value='2'>II</option>
                            <option value='3'>III</option>
                            <option value='4'>IV</option>
                            <option value='5'>Non-Eselon</option>
                        </select>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Alamat Kantor :</label>
                    <div class="col-sm-8">
                        <div class='col-sm-12'>
                            <textarea class='form-control' name="ALAMAT_KANTOR" placeholder="Alamat Kantor"><?php if(@json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR != '') { echo @json_decode(@$DATA_PRIBADI->JABATAN)->ALAMAT_KANTOR;}else{ echo '';}?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">Email Kantor :</label>
                    <div class="col-sm-8">
                        <div class='col-sm-12'>
                            <input type='email' class='form-control' value="<?=@json_decode(@$DATA_PRIBADI->JABATAN)->EMAIL_KANTOR?>" name="EMAIL_KANTOR" placeholder="Email Kantor">
                        </div>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">SK<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <div class='col-sm-12'>
                            <input type="file" name="FILE_SK">
                        </div>
                    </div>
                </div>
                <div class="form-group div_jabatan">
                    <label class="col-sm-4 control-label">TMT/SD<font color='red'>*</font> :</label>
                    <div class="col-sm-8">
                        <div class='col-sm-12'>
                            <div class="col-xs-3 col-sm-4 col-md-3 col-lg-3">
                                <input type="text" class="form-control datepicker" name="TMT" value="<?=date('d/m/Y')?>" required>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                s/d
                            </div>
                            <div class="col-xs-3 col-sm-4 col-md-3 col-lg-3">
                                <input type="text" class="form-control datepicker" name="SD" value="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pull-right">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox2();">
                </div>
        </form>
    </div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#INST_ASAL').select2();
        $('#INST_TUJUAN').select2();
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });
        //ng.formProcess($("#ajaxFormMutasi"), 'mutasi', location.href.split('#')[1]);

        $('input[name="JABATAN"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/ereg/pn/getJabatan')?>",
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
                    $.ajax("<?=base_url('index.php/ereg/pn/getJabatan')?>/"+id, {
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

        $('#INST_ASAL').change(function(event) {
             if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH != 1 ) {
                 LEMBAGA = $(this).val();
                 $('input[name="UNIT_KERJA"]').select2({
                     minimumInputLength: 0,
                     ajax: {
                         url: "<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+LEMBAGA,
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
                             $.ajax("<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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
             }
        });

        $('#INST_TUJUAN').change(function(event) {
            if ( data_status_akhir.result[$('#JENIS_MUTASI').val()].IS_PINDAH == 1 ) {
                LEMBAGA = $(this).val();
                $('input[name="UNIT_KERJA"]').select2({
                    minimumInputLength: 0,
                    ajax: {
                        url: "<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+LEMBAGA,
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
                            $.ajax("<?=base_url('index.php/ereg/pn/getUnitKerja')?>/"+LEMBAGA+'/'+id, {
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
            }
        });
        $('#JENIS_MUTASI').change(function() {
            if ( data_status_akhir.result[$(this).val()].IS_PINDAH == 1 ) {
                $('.div_pindah input').prop('required', true);
                $('.div_pindah select').prop('required', true);
                $('.div_jabatan input').prop('required', true);
                $('.div_jabatan select').prop('required', true);
                $('.div_pindah').show();
                $('.div_jabatan').show();
                $('#INST_TUJUAN').change();
            } else if ( data_status_akhir.result[$(this).val()].IS_AKHIR == 1 ) {
                $('.div_pindah input').removeAttr('required');
                $('.div_pindah select').removeAttr('required');
                $('.div_jabatan input').removeAttr('required');
                $('.div_jabatan select').removeAttr('required');
                $('.div_pindah').hide();
                $('.div_jabatan').hide();
                $('#INST_ASAL').change();
            } else {
                $('.div_pindah input').removeAttr('required');
                $('.div_pindah select').removeAttr('required');
                $('.div_jabatan input').prop('required', true);
                $('.div_jabatan select').prop('required', true);
                $('.div_pindah').hide();
                $('.div_jabatan').show();
                $('#INST_ASAL').change();
            }
        });
        /*$('input[name="UNIT_KERJA"]').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/ereg/pn/getUnitKerja/')?>"+$('#INST_TUJUAN').val(),
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
                    $.ajax("<?=base_url('index.php/ereg/efill/lhkpn')?>/"+$('#INST_TUJUAN').val(), {
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
        }); */
    });
</script>
<?php
}
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('.date-picker').datepicker({
        format: 'dd/mm/yyyy'
        }) 
       $('.numbersOnly').mask("(+99) 9999?-9999?-9999");    
    });
</script>
