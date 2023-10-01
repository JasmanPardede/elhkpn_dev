
<style type="text/css">
    .div-pribadi-luar
    {
        margin-top: 20px;
        border-style: solid;
        border-width: 1px;
        /*background-color: #F7F7F7;*/
        border-color: rgba(158, 158, 158, 0.49);
        border-radius: 5px;
        overflow: hidden;
    }
    .div-pribadi-dalam
    {
        margin-top: 20px;
        border-style: solid;
        border-width: 1px;
        background-color: #F7F7F7;
        border-radius: 5px;
        overflow: hidden;
    }
    .form-select2
    {
        background-color: rgba(255, 255, 255, 0.0);
        margin-left: -10px;
    }
    .judul-header
    {
        margin-left: -15px;
        margin-right: -15px;
        background-color: rgb(240, 240, 240);
        color:#000;
    }
    .judul-header-dalam
    {
        margin-left: -15px;
        margin-right: -15px;
        background-color: rgba(192, 192, 192, 0.0);
        color:#000;
    }
    .bold
    {
        padding: 0px;
        margin: 0px;
        border:1px solid ;
    }
    .title-pribadi
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
    }
    .labelisi
    {
        text-align:left !important;
    }
</style>

<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Isian data pribadi, jabatan, dan kontak"</h5>
        <!--<div class="pull-right">
            <button class="btn btn-warning" id="riksaEditDataPribadi" href="index.php/eaudit/pemeriksaan_edit/index/lhkpn/<?php echo $LHKPN->ID_LHKPN;?>"><span class="fa fa-edit"></span> Edit Data Pribadi</button>
        </div> -->

</div>
<div class="box-body form-horizontal">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">

        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Data Pribadi</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <!--            <div class="form-group" <?php
            if (@$DATA_PRIBADI->FOTO == '' && @$LHKPN->FOTO == '') {
                echo 'style="display:none;"';
            }
            ?>>
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <div class='col-sm-12'>
                                <p class="form-control-static"><img src="./uploads/data_pribadi/<?php
            if (@$DATA_PRIBADI->NIK != '') {
                echo @$DATA_PRIBADI->NIK;
            } else {
                echo @$LHKPN->NIK;
            }
            ?>/<?php
            if (@$DATA_PRIBADI->FOTO != '') {
                echo @$DATA_PRIBADI->FOTO;
            } else {
                echo @$LHKPN->FOTO;
            }
            ?>" width="30%"/></p></br>
                            </div>
                        </div>
                    </div>-->

            <!--             <div class="form-group">
                            <label class="col-sm-3 control-label">Foto :</label>
                            <div class="col-sm-9">
                                <div class='col-sm-12'>
            <?php
            if (@$DATA_PRIBADI->FOTO != '') {
                echo $DATA_PRIBADI->FOTO;
            } else {
                echo @$LHKPN->FOTO;
            }
            ?>
                                </div>
                            </div>
                        </div> -->
            <!--  YC          <div class="form-group">
                            <label class="col-sm-3 control-label">NPWP:</label>
                            <div class="col-sm-9">
                                <div class='col-sm-12 control-label labelisi'>
            <?php
            if (@$DATA_PRIBADI->FILE_NPWP != '') {
                ?>
                                                            <a href="<?php echo 'uploads/data_pribadi/' . $DATA_PRIBADI->NIK . '/' . $DATA_PRIBADI->FILE_NPWP; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_pribadi/' . $DATA_PRIBADI->NIK . '/' . $DATA_PRIBADI->FILE_NPWP); ?></a><?php
            }
            ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">KTP:</label>
                            <div class="col-sm-9">
                                <div class='col-sm-12 control-label labelisi'>
            <?php
            if (@$DATA_PRIBADI->FILE_KTP != '') {
                ?>
                                                            <a href="<?php echo 'uploads/data_pribadi/' . $DATA_PRIBADI->NIK . '/' . $DATA_PRIBADI->FILE_KTP; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_pribadi/' . $DATA_PRIBADI->NIK . '/' . $DATA_PRIBADI->FILE_KTP); ?></a><?php
            }
            ?>
                                </div>
                            </div>
                        </div>-->

            <div class="form-group">
                <label class="col-sm-3 control-label">NIK :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php
                        if (@$DATA_PRIBADI->NIK != '') {
                            echo $DATA_PRIBADI->NIK;
                        } else {
                            echo $LHKPN->NIK;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Lengkap / Gelar :</label>
                <div class="col-sm-9">
                    <div class="col-sm-12 control-label labelisi">
                        <?php echo @$DATA_PRIBADI->GELAR_DEPAN; ?>
                        <?php
                        if (@$DATA_PRIBADI->NAMA_LENGKAP != '') {
                            echo $DATA_PRIBADI->NAMA_LENGKAP;
                        } else {
                            echo $LHKPN->NAMA;
                        }
                        ?>
                        <?php echo @$DATA_PRIBADI->GELAR_BELAKANG; ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Jenis Kelamin :</label>
                <div class="col-sm-9">
                    <div class='col-sm-6 control-label labelisi'>
                        <?php
                        if (@$DATA_PRIBADI->JENIS_KELAMIN != '') {
                            if ($DATA_PRIBADI->JENIS_KELAMIN == '1') {
                                echo 'Laki Laki';
                            }
                            elseif ($DATA_PRIBADI->JENIS_KELAMIN == '2') {
                                echo 'Perempuan';
                            }
                            else{
                                echo $DATA_PRIBADI->JENIS_KELAMIN;
                            }
                        } else {
                            if (@$LHKPN->JNS_KEL == '1') {
                                echo 'Laki Laki';
                            }
                            elseif (@$LHKPN->JNS_KEL == '2') {
                                echo 'Perempuan';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Tempat & Tanggal Lahir:</label>
                <div class="col-sm-9">
                    <div class='col-sm-6 control-label labelisi'>
                        <?php
                        if (@$DATA_PRIBADI->TEMPAT_LAHIR != '') {
                            echo $DATA_PRIBADI->TEMPAT_LAHIR;
                        } else {
                            echo $LHKPN->TEMPAT_LAHIR;
                        }
                        ?>
                        , <?php
                        if (@$DATA_PRIBADI->TANGGAL_LAHIR != '') {
                            echo tgl_format($DATA_PRIBADI->TANGGAL_LAHIR);
                        } else {
                            echo tgl_format($LHKPN->TGL_LAHIR);
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">NPWP :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php
                        if (@$DATA_PRIBADI->NPWP != '') {
                            echo $DATA_PRIBADI->NPWP;
                        } else {
                            echo $LHKPN->NPWP;
                        }
                        ?>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">Agama :</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 control-label labelisi">
                        <?php
                        $aAgama = ['', 'Islam', 'Katolik', 'Kristen Protestan', 'Hindu', 'Buddha', 'Konghuchu'];
                        if (@$DATA_PRIBADI->AGAMA != '') {
                            if (strlen($DATA_PRIBADI->AGAMA) == 16) {
                                echo "";
                            }
                            else{
                                echo @$DATA_PRIBADI->AGAMA;
                            }
                        } else {
                            echo $aAgama[$LHKPN->ID_AGAMA];
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Status Perkawinan :</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 control-label labelisi">
                        <?php
                        if (@$DATA_PRIBADI->STATUS_PERKAWINAN != '' || @$DATA_PRIBADI->STATUS_PERKAWINAN != NULL) {
                            echo strtoupper(@$DATA_PRIBADI->STATUS_PERKAWINAN);
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <?php 
            $ffoto = "uploads/data_pribadi/".encrypt_username($LHKPN->NIK_PN)."/".$DATA_PRIBADI->FOTO;
            ?>
            <label class="control-label">Foto :</label>
            <?php
//            $ffoto = "uploads/data_pribadi/$LHKPN->NIK/foto.jpg";
            if (file_exists($ffoto))
                $ffoto = $ffoto;
            else
                $ffoto = "images/no_available_image.png";
            ?>
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

        <!-- <div class="form-group">
            <label class="col-sm-3 control-label">Negara :</label>
            <div class="col-sm-9">
                <div class='col-sm-5'>
                    <div class='col-sm-6'>
                        <label>
                            <input <?= (@$DATA_PRIBADI->NEGARA == 2 ? 'checked' : (@$DATA_PRIBADI->NEGARA == '' ? 'checked' : '' ) ); ?> class="negara cekedDalam" type="radio" name='NEGARA' id='NEGARA'  value="2"> Dalam Negeri                                
                        </label>
                    </div>
                    <div class='col-sm-6'>
                        <label>
                            <input <?= (@$DATA_PRIBADI->NEGARA == 1 ? 'checked' : '' ); ?> class="negara cekedLuar" type="radio" name='NEGARA' id='NEGARA' value="1" > Luar Negeri 
                        </label>
                    </div>
                </div>
            </div>
        </div> -->

        <!--Luar Negri Start-->
        <?php
        if (@$DATA_PRIBADI->NEGARA == 2) {
            ?>
            <div id="showLuar">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama Negara :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-6 control-label labelisi'>
                            <?php
                            if (@$DATA_PRIBADI->KD_ISO3_NEGARA != '') {
                                echo @$DATA_PRIBADI->KD_ISO3_NEGARA;
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
                            <?= @$DATA_PRIBADI->ALAMAT_NEGARA; ?>
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
                        if (@$DATA_PRIBADI->ALAMAT_RUMAH != '') {
                            echo @$DATA_PRIBADI->ALAMAT_RUMAH;
                        } else {
                            echo $LHKPN->ALAMAT_TINGGAL;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php
            if (@$DATA_PRIBADI->entry_via == 0){
                if (@$DATA_PRIBADI->NEGARA == 1) {
                    ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Provinsi :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $DATA_PRIBADI->PROVINSI; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kabupaten/Kota :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $DATA_PRIBADI->KABKOT; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kecamatan :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $DATA_PRIBADI->KECAMATAN ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kelurahan :</label>
                        <div class="col-sm-9">
                            <div class='col-sm-6 control-label labelisi'>
                                <?php echo $DATA_PRIBADI->KELURAHAN ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>


        </div>
        <!--Dalam Negri End-->

        <!--        <div class="form-group">
                    <label class="col-sm-3 control-label">No. Tel. Rumah :</label>
                    <div class="col-sm-9">
                        <div class='col-sm-12 control-label labelisi'>
        <?= @$DATA_PRIBADI->TELPON_RUMAH; ?>
                        </div>
                    </div>
                </div>-->

        <div class="form-group">
            <label class="col-sm-3 control-label">Email Pribadi (Aktif) :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php
                    if (@$DATA_PRIBADI->EMAIL_PRIBADI != '') {
                        echo @$DATA_PRIBADI->EMAIL_PRIBADI;
                    } else {
                        echo $LHKPN->EMAIL;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">No. Handphone :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php
                    if (@$DATA_PRIBADI->HP != '') {
                        echo @$DATA_PRIBADI->HP;
                    } else {
                        echo $LHKPN->NO_HP;
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?= @json_decode(@$DATA_PRIBADI->HP_LAINNYA)->HP_ETC1 ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?= @json_decode(@$DATA_PRIBADI->HP_LAINNYA)->HP_ETC2 ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?= @json_decode(@$DATA_PRIBADI->HP_LAINNYA)->HP_ETC3 ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12"></div>
</div><!-- /.box-body -->

<div class="box-footer"></div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#riksaEditDataPribadi").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Pemeriksaan Data Pribadi', html, null, 'large');
            });            
            return false;
        });
    });
</script>