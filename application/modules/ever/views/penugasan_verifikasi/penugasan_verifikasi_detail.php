<div id="wrapperFormAdd">
    <div class="col-md-8 detail-form form-horizontal">
        <div class="form-group">
            <label class="col-sm-4 control-label">Nik :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $data->NIK?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Nama :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $data->NAMA?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Jenis Kelamin :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo ($data->JNS_KEL == '1' ? 'Laki-Laki' : 'Perempuan')?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Tempat/Tanggal Lahir :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $data->TEMPAT_LAHIR.', '.date('d/m/Y',strtotime($data->TGL_LAHIR))?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">NPWP :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $data->NPWP?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Alamat :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $data->ALAMAT_TINGGAL?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Email :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $data->EMAIL?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">HP :</label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo $data->NO_HP?></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-7">
                <input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">
            </div>
        </div>    
    </div>
    <div class="col-md-4">
        <div style="width: 100%;">
            <img src="<?php echo $data->FOTO == '' ? base_url('images/no_available_image.png') : base_url('uploads/data_pribadi/'.$data->NIK.'/'.$data->FOTO.'') ?>" width="100%" />
        </div>
    </div>
</div>
