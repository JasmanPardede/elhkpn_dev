<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Isian data pribadi, jabatan, dan kontak"</h5>
<!-- <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="klarifikasiDataHTB" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $new_id_lhkpn;?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/new"><span class="fa fa-plus"></span> Tambah</button><br><br> -->
</div>
<div class="box-body form-horizontal">
    <div class="pull-right">
        <button class="btn btn-sm btn-warning aksi-hide" id="klarifEditDataPribadi" href="index.php/eaudit/klarifikasi/update_data_pribadi/<?php echo $new_id_lhkpn;?>"><span class="fa fa-edit"></span> Edit Data Pribadi</button>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">

        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Data Pribadi</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="form-group">
                <label class="col-sm-3 control-label">NIK :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php echo $lhkpn_pribadi->NIK; ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Lengkap / Gelar :</label>
                <div class="col-sm-9">
                    <div class="col-sm-12 control-label labelisi">
                        <?php echo $lhkpn_pribadi->GELAR_DEPAN; ?>
                        <?php echo $lhkpn_pribadi->NAMA_LENGKAP; ?>
                        <?php echo $lhkpn_pribadi->GELAR_BELAKANG; ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Jenis Kelamin :</label>
                <div class="col-sm-9">
                    <div class='col-sm-6 control-label labelisi'>
                        <?php
                        if ($lhkpn_pribadi->JENIS_KELAMIN == '1') {
                            echo 'Laki Laki';
                        }
                        elseif ($lhkpn_pribadi->JENIS_KELAMIN == '2') {
                            echo 'Perempuan';
                        }
                        elseif (strlen($lhkpn_pribadi->JENIS_KELAMIN) == '16') {
                            echo '-';
                        }
                        else{
                            echo $lhkpn_pribadi->JENIS_KELAMIN;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Tempat &amp; Tanggal Lahir:</label>
                <div class="col-sm-9">
                    <div class='col-sm-6 control-label labelisi'>
                        <?php echo is_null($lhkpn_pribadi->TEMPAT_LAHIR) ? '' : $lhkpn_pribadi->TEMPAT_LAHIR.','; ?>
                        <?php echo tgl_format($lhkpn_pribadi->TANGGAL_LAHIR); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">NPWP :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php echo $lhkpn_pribadi->NPWP; ?>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">Agama :</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 control-label labelisi">
                        <?php
                        if (strlen($lhkpn_pribadi->AGAMA) == 16) {
                            echo "-";
                        }
                        else{
                            echo $lhkpn_pribadi->AGAMA;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Status Perkawinan :</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 control-label labelisi">
                        <?php echo strtoupper($lhkpn_pribadi->STATUS_PERKAWINAN); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <label class="control-label">Foto :</label>
            <?php $ffoto = file_exists('uploads/data_pribadi/' . $NIKLengkap . '/foto.jpg') ? 'uploads/data_pribadi/' . $NIKLengkap . '/foto.jpg' : 'images/no_available_image.png'; ?>
            <img class="file-preview-image" style="width: 100px; height: 100px;"  src="<?php echo base_url() . $ffoto; ?>">
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">

        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Data Kontak</h3>
                </div>
            </div>
        </div>
        <!--Luar Negri Start-->
        <?php
        if ($lhkpn_pribadi->NEGARA == 2) {
            ?>
            <div id="showLuar">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama Negara :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-6 control-label labelisi'>
                            <?php
                            if ($lhkpn_pribadi->KD_ISO3_NEGARA != '') {
                                echo $lhkpn_pribadi->KD_ISO3_NEGARA;
                            } else {
                                echo $DATA_PRIBADI->KD_ISO3_NEGARA;
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Jalan :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-12 control-label labelisi'>
                            <?= $lhkpn_pribadi->ALAMAT_NEGARA; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <!--Luar Negri End-->

        <!--Dalam Negri Start-->
        <div id="showDalam">
            <div class="form-group">
                <label class="col-sm-3 control-label">Alamat Rumah :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php
                        if ($lhkpn_pribadi->ALAMAT_RUMAH != '') {
                            echo $lhkpn_pribadi->ALAMAT_RUMAH;
                        } else {
                            echo $LHKPN->ALAMAT_TINGGAL;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php
                if ($lhkpn_pribadi->NEGARA == 1) {
                    ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Provinsi :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $lhkpn_pribadi->PROVINSI; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kabupaten/Kota :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $lhkpn_pribadi->KABKOT; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kecamatan :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $lhkpn_pribadi->KECAMATAN ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kelurahan :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $lhkpn_pribadi->KELURAHAN ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>


        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Email Pribadi (Aktif) :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php echo $lhkpn_pribadi->EMAIL_PRIBADI; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">No. Handphone :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php echo $lhkpn_pribadi->HP; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12"></div>
</div><!-- /.box-body -->

<div class="box-footer"></div><!-- /.box-footer -->
<script type="text/javascript">
    $(document).ready(function() {
        $("#klarifEditDataPribadi").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Klarifikasi Data Pribadi', html, null, 'large');
            });            
            return false;
        });
    });
</script>