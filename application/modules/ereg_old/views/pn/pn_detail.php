<div id="wrapperFormAdd">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ereg/pn/savepn" enctype="multipart/form-data">
        <div class="col-md-8 detail-form">
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
                    <p class="form-control-static"><?php echo $data->TEMPAT_LAHIR.'/'.$data->TGL_LAHIR?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">NPWP :</label>
                <div class="col-sm-7">
                    <p class="form-control-static"><?php echo $data->NPWP?></p>
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <label class="col-sm-4 control-label">Jabatan :</label>-->
<!--                <div class="col-sm-7">-->
<!--                    <p class="form-control-static">--><?php //echo $data->JABATAN?><!--</p>-->
<!--                </div>-->
<!--            </div>-->
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
        </div>
        <div class="col-md-4">
            <div style="width: 100%;">
                <img src="<?php echo $data->FOTO == '' ? base_url('images/no_available_image.gif') : '' ?>" width="100%" />
            </div>
        </div>
    </form>
</div>