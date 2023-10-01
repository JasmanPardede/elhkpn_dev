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
 * @package Views/efill/lhkpn_view
 */
?>
<style type="text/css">
    .div-pribadi-luar
    {
        margin-top: 20px;
        border-style: solid;
        border-width: 1px;
        background-color: #F7F7F7;
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
        background-color: rgba(192, 192, 192, 1.0);
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
    <h5 class="">"Isian data pribadi"</h5>
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


        <div class="col-sm-8">

            <div class="form-group">
                <label class="col-sm-3 control-label">NO KK :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php
                        echo beautify_text($DATA_PRIBADI->NO_KK);
//                        echo $LHKPN->NO_KK;
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">NIK :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php
                        echo beautify_text($DATA_PRIBADI->NIK);
//                        echo $LHKPN->NIK;
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Lengkap / Gelar :</label>
                <div class="col-sm-9">
                    <div class="col-sm-12 control-label labelisi">
                        <?php echo beautify_text($DATA_PRIBADI->GELAR_DEPAN); ?>
                        <?php
                        echo beautify_text($DATA_PRIBADI->NAMA_LENGKAP);
                        ?>
                        <?php echo beautify_text($DATA_PRIBADI->GELAR_BELAKANG); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Jenis Kelamin :</label>
                <div class="col-sm-9">
                    <div class='col-sm-6 control-label labelisi'>
                        <?php
                        if (@$DATA_PRIBADI->JENIS_KELAMIN != '') {
                            if (is_numeric($DATA_PRIBADI->JENIS_KELAMIN) && $DATA_PRIBADI->JENIS_KELAMIN == '1') {
                                echo 'Laki Laki';
                            } elseif (is_numeric($DATA_PRIBADI->JENIS_KELAMIN) && $DATA_PRIBADI->JENIS_KELAMIN == '2') {
                                echo 'Perempuan';
                            } else {
                                echo $DATA_PRIBADI->JENIS_KELAMIN;
                            }
                        } else {
                            if (@$LHKPN->JNS_KEL == '1') {
                                echo 'Laki Laki';
                            } elseif (@$LHKPN->JNS_KEL == '2') {
                                echo 'Perempuan';
                            } else {
                                echo $DATA_PRIBADI->JNS_KEL;
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
                        echo beautify_text($DATA_PRIBADI->TEMPAT_LAHIR);
                        ?>
                        , <?php
                        echo tgl_format($DATA_PRIBADI->TANGGAL_LAHIR, "-");
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">NPWP :</label>
                <div class="col-sm-9">
                    <div class='col-sm-12 control-label labelisi'>
                        <?php
                        echo beautify_text($DATA_PRIBADI->NPWP);
                        ?>
                    </div>
                </div>
            </div>

            <?php /**
              <div class="form-group">
              <label class="col-sm-3 control-label">Status Perkawinan :</label>
              <div class="col-sm-9">
              <div class="col-sm-3 control-label labelisi">
              <!-- <label align='left'> -->
              <?php echo show_status_perkawinan($DATA_PRIBADI->STATUS_PERKAWINAN); ?>
              <!-- </label> -->
              </div>
              </div>
              </div>
             * 
             */
            ?>

            <div class="form-group">
                <label class="col-sm-3 control-label">Agama :</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 control-label labelisi">
                        <?php
                        // $aAgama = ['', 'ISLAM', 'KATOLIK', 'KRISTEN PROTESTAN', 'HINDU', 'BUDDHA', 'KONGHUCHU'];
                        // echo $aAgama[$DATA_PRIBADI->id_agama];
                        if (strlen($DATA_PRIBADI->AGAMA) == 16) {
                            echo "";
                        }
                        else{
                            echo $DATA_PRIBADI->AGAMA;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-sm-4">
            <div class="form-group" <?php
            if (@$DATA_PRIBADI->FOTO == '' && @$LHKPN->FOTO == '') {
                echo 'style="display:none;"';
            }
            ?>>
                <div class='col-sm-12'>
                    <p class="form-control-static">
                        <img src="<?php echo base_url('uploads/data_pribadi/' . encrypt_username($LHKPN->NIK, 'e') . '/' . $DATA_PRIBADI->FOTO); ?>" width="60%"/>
                    </p>
                </div>
            </div>
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
        <!-- LCL -->
        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Rumah :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php
                    echo $DATA_PRIBADI->ALAMAT_RUMAH;
                    ?>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-3 control-label">No. Tel. Rumah :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php echo @trim(@$DATA_PRIBADI->TELPON_RUMAH) != "" ? @$DATA_PRIBADI->TELPON_RUMAH : "-"; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Email Pribadi (Aktif) :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php
                    echo $DATA_PRIBADI->EMAIL_PRIBADI;
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">No. Handphone :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php
                    echo @$DATA_PRIBADI->HP;
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
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">

        <div class="form-group">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Data Jabatan</h3>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Jabatan/Sub-Unit Kerja :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <div class="row">
                        <div class="col-sm-9">
                            <?php echo $IMP_LHKPN_JABATAN != NULL ? (trim($IMP_LHKPN_JABATAN->NAMA_JABATAN) != "" ? $IMP_LHKPN_JABATAN->NAMA_JABATAN : "-") . "/" . (trim($IMP_LHKPN_JABATAN->SUK_NAMA) != "" ? $IMP_LHKPN_JABATAN->SUK_NAMA : "-") : "-"; ?>
                        </div>
                        <div class="col-sm-3">
                            <small><small><i><b>(dari Master Jabatan)</b></i></small></small>        
                        </div>
                    </div>
                    <hr style="margin: 1px;border-top: 1px solid #000;">
                    <div class="row">
                        <div class="col-sm-9">
                            <small><i><?php echo $IMP_LHKPN_JABATAN != NULL ? (trim($IMP_LHKPN_JABATAN->JABATAN_TERDETEKSI) != "" ? $IMP_LHKPN_JABATAN->JABATAN_TERDETEKSI : "-") . "/" . (trim($IMP_LHKPN_JABATAN->SUK_TERDETEKSI) != "" ? $IMP_LHKPN_JABATAN->SUK_TERDETEKSI : "-") : "-"; ?></i></small>
                        </div>
                        <div class="col-sm-3">
                            <small><small><i><b>(dari Excel)</b></i></small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Unit Kerja/Lembaga :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <div class="row">
                        <div class="col-sm-9">
                            <?php echo $IMP_LHKPN_JABATAN != NULL ? (trim($IMP_LHKPN_JABATAN->UK_NAMA) != "" ? $IMP_LHKPN_JABATAN->UK_NAMA : "-") . "/" . (trim($IMP_LHKPN_JABATAN->INST_NAMA) != "" ? $IMP_LHKPN_JABATAN->INST_NAMA : "-") : "-"; ?>
                        </div>
                        <div class="col-sm-3">
                            <small><small><i><b>(dari Master Jabatan)</b></i></small></small>
                        </div>
                    </div>
                    <hr style="margin: 1px;border-top: 1px solid #000;">
                    <div class="row">
                        <div class="col-sm-9">
                            <small><i><?php echo $IMP_LHKPN_JABATAN != NULL ? (trim($IMP_LHKPN_JABATAN->UK_TERDETEKSI) != "" ? $IMP_LHKPN_JABATAN->UK_TERDETEKSI : "-") . "/" . (trim($IMP_LHKPN_JABATAN->LEMBAGA_TERDETEKSI) != "" ? $IMP_LHKPN_JABATAN->LEMBAGA_TERDETEKSI : "-") : "-"; ?></i></small>
                        </div>
                        <div class="col-sm-3">
                            <small><small><i><b>(dari Excel)</b></i></small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Alamat Kantor :</label>
            <div class="col-sm-9">
                <div class='col-sm-12 control-label labelisi'>
                    <?php echo $IMP_LHKPN_JABATAN != NULL ? $IMP_LHKPN_JABATAN->ALAMAT_KANTOR : "-"; ?>
                </div>
            </div>
        </div>

    </div>
    <div class="col-sm-12"></div>
</div><!-- /.box-body -->

<div class="box-footer"></div><!-- /.box-footer -->
