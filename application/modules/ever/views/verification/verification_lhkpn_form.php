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
 * @package Views/ever/verification
 */
?>
<?php
if ($display == 'verifikasi') {
    $ID_LHKPN = $LHKPN->ID_LHKPN;
    $veritemnoks = $this->mglobal->get_data_all("T_VERIFICATION_ITEM", NULL, ['ID_LHKPN' => $ID_LHKPN, 'HASIL' => '-1']);

    foreach ($veritemnoks as $veritemnok) {
        if ($veritemnok->ITEMVER == 'hartabergerakperabot') {
            $veritemnok->ITEMVER = 'HARTABERGERAK2';
        }
        $veritemnoktext[strtoupper($veritemnok->ITEMVER)][] = $veritemnok->CATATAN;
    }
    
    $stat = true;
    if (!empty($tmpData)) {
        foreach ($tmpData->VAL as $row) {
            if ($row == '' || $row == '-1') {
                $stat = false;
                break(1);
            }
        }
    } 
    else {
        $stat = false;
    }
    $pribadis = $DATA_PRIBADI;
    ?>
    <style>
        #subtab{
            background-color: #FFFFFF; background-image: linear-gradient(-45deg, #F6F6F6 25%, transparent 15%, transparent 50%, #F6F6F6 50%, #F6F6F6 75%, transparent 75%, transparent); background-size: 12px 12px;
        }
    </style>
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/ckeditor/contents.css?v=<?=$this->config->item('cke_version');?>" type="text/css"/>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/adapters/jquery.js?v=<?=$this->config->item('cke_version');?>"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa <?php echo $icon; ?>"></i> <?php echo $title; ?></h1>
        <?php echo $breadcrumb; ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box" style="padding: 10px;">
                    <h4><?php echo $tampil; ?></h4>
                    <label><?php echo $tampil3; ?></label>
                    <br>
                    <label>Legenda : 
                        <i class="fa fa-check-square" style="cursor: pointer; color: blue;" title="Diterima"></i> Diterima
                        <i class="fa fa-minus-square" style="cursor: pointer; color: red;" title="Ditolak"></i> Ditolak
                    </label>
                    <div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li id="li10" role="presentation">
                                <a href="#datapribadi" aria-controls="datapribadi" role="tab" data-toggle="tab" class="navTab datapribadi navTabPribadi" title="Data Pribadi"><?php echo ICON_pribadi; ?> 
                                    <span>Data Pribadi</span>
                                </a>
                            </li>
                            <li id="li2" role="presentation">
                                <a href="#jabatan" aria-controls="jabatan" role="tab" data-toggle="tab" class="navTab jabatan navTabJabatan" title="Data Jabatan"><?php echo ICON_jabatan; ?> 
                                    <span>Jabatan</span>
                                </a>
                            </li>
                            <li id="li3" role="presentation">
                                <a href="#keluarga" aria-controls="keluarga" role="tab" data-toggle="tab" class="navTab keluarga navTabKeluarga" title="Data Keluarga"><?php echo ICON_keluarga; ?> 
                                    <span>Keluarga</span>
                                </a>
                            </li>
                            <li id="li4" role="presentation">
                                <a href="#harta" aria-controls="harta" role="tabharta" data-toggle="tab" class="navTab harta navTabHarta" title="Data Harta"><?php echo ICON_harta; ?> 
                                    <span>Harta</span>
                                </a>
                            </li>
                            <li id="li5" role="presentation">
                                <a href="#penerimaankas" aria-controls="penerimaankas" role="tab" data-toggle="tab" class="navTab penerimaankas navTabPenerimaan" title="Data Penerimaan Kas"><?php echo ICON_penerimaankas; ?> 
                                    <span>Penerimaan</span>
                                </a>
                            </li>
                            <li id="li6" role="presentation">
                                <a href="#pengeluarankas" aria-controls="pengeluarankas" role="tab" data-toggle="tab" class="navTab pengeluarankas navTabPengeluaran" title="Data Pengeluaran Kas"><?php echo ICON_pengeluarankas; ?> 
                                    <span>Pengeluaran</span>
                                </a>
                            </li>
                            <li id="li7" role="presentation">
                                <a href="#penerimaanfasilitas" aria-controls="penerimaanfasilitas" role="tab" data-toggle="tab" class="navTab penerimaanfasilitas navTabFasilitas" title="Penerimaan Fasilitas"><?php echo ICON_fasilitas; ?> 
                                    <span>Fasilitas</span>
                                </a>
                            </li>
                            <li id="li8" role="presentation">
                                <a href="#lampiran" aria-controls="lampiran" role="tab" data-toggle="tab" class="navTab lampiran navTabLampiran" title="Data Lampiran Transaksi Pelepasan Harta, Penerimaan Fasilitas"><?php echo ICON_lampiran; ?> 
                                    <span>Lampiran</span>
                                </a>
                            </li>
                            <li id="li1" role="presentation" class="active">
                                <a href="#reviewharta" aria-controls="reviewharta" role="tab" data-toggle="tab" class="navTab reviewharta navTabReview" title="Review Harta"><?php echo ICON_final; ?> 
                                    <span>Review Harta</span>
                                </a>
                            </li>
                            <li id="li19" role="presentation">
                                <a href="#outlier" aria-controls="outlier" role="tab" data-toggle="tab" class="navTab outlier navTabOutlier" title="Outlier"><?php echo ICON_final; ?> 
                                    <span>Outlier</span>
                                </a>
                            </li>
                            <li id="li11" role="presentation">
                                <a href="#suratkuasamengumumkan" aria-controls="suratkuasamengumumkan" role="tab" data-toggle="tab" class="navTab suratkuasa navTabSuratKuasa" title="Data Lampiran Surat Kuasa"><?php echo ICON_suratkuasamengumumkan; ?> 
                                    <span>Surat Kuasa</span>
                                </a>
                            </li>
                            <li id="li9" role="presentation">
                                <a href="#final" aria-controls="final" role="tab" data-toggle="tab" class="navTab final" title="Hasil Verifikasi"><?php echo ICON_final; ?> 
                                    <span>Final Verifikasi</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="datapribadi">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_pribadi.php'); ?>
                                </div>
                                <div class="pull-right" style="padding-right:20px;">
                                    Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->DATAPRIBADI == '1') ? 'checked' : ''; ?> name="datapribadi" class="checkboxVer" value="1"> Ya</label>
                                    <label><input type="radio" <?php echo (@$tmpData->VAL->DATAPRIBADI == '-1') ? 'checked' : ''; ?> name="datapribadi" class="checkboxVer" value="0"> Tidak</label>
                                </div>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer" <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->DATAPRIBADI; ?></textarea>
                                </div>
                                <!-- <br> -->
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnCancel"><i class="fa fa-backward"></i> Cancel</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextPribadi">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="jabatan">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_jabatan.php'); ?>
                                </div>
                                <div class="pull-right" style="padding-right:20px;">
                                    Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->JABATAN == '1') ? 'checked' : ''; ?> name="jabatan" class="checkboxVer" value="1"> Ya</label>
                                    <label><input type="radio" <?php echo (@$tmpData->VAL->JABATAN == '-1') ? 'checked' : ''; ?> name="jabatan" class="checkboxVer" value="0"> Tidak</label>
                                </div>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->JABATAN; ?></textarea>
                                </div>
                                <!-- <br> -->
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousJabatan"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextJabatan">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="keluarga">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_keluarga.php'); ?>
                                </div>
                                <div class="pull-right" style="padding-right:20px;">
                                    Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->KELUARGA == '1') ? 'checked' : ''; ?> name="keluarga" class="checkboxVer" value="1"> Ya</label>
                                    <label><input type="radio" <?php echo (@$tmpData->VAL->KELUARGA == '-1') ? 'checked' : ''; ?> name="keluarga" class="checkboxVer" value="0"> Tidak</label>
                                </div>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->KELUARGA; ?></textarea>
                                </div>
                                <!-- <br> -->
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousKeluarga"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextKeluarga">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="harta">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li id="0" role="presentation" class="active"><a href="#hartatidakbergerak" aria-controls="hartatidakbergerak" role="tab" data-toggle="tab" class="navTab navTabHartaTidakBergerak" title="Tanah / Bangunan"><?php echo ICON_hartatidakbergerak; ?> <span>Tanah / Bangunan</span></a></li>
                                    <li  id="1" role="presentation"><a href="#hartabergerak" aria-controls="hartabergerak" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak" title="Alat Transportasi / Mesin"><?php echo ICON_hartabergerak; ?> <span>Mesin / Alat Transport</span></a></li>
                                    <li id="2" role="presentation"><a href="#hartabergerakperabot" aria-controls="hartabergerakperabot" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak2" title="Perabot"><?php echo ICON_hartabergerakperabot; ?> <span>Bergerak</span></a></li>
                                    <li id="3" role="presentation"><a href="#suratberharga" aria-controls="suratberharga" role="tab" data-toggle="tab" class="navTab navTabHartaSurat" title="Surat Berharga"><?php echo ICON_suratberharga; ?> <span>Surat Berharga</span></a></li>
                                    <li id="4" role="presentation"><a href="#kas" aria-controls="kas" role="tab" data-toggle="tab" class="navTab navTabHartaKas" title="Kas / Setara Kas"><?php echo ICON_kas; ?> <span>KAS / Setara KAS</span></a></li>
                                    <li id="5" role="presentation"><a href="#hartalainnya" aria-controls="hartalainnya" role="tab" data-toggle="tab" class="navTab navTabHartaLainnya" title="Harta Lainnya"><?php echo ICON_hartalainnya; ?> <span>Harta Lainnya</span></a></li>
                                    <li id="6" role="presentation"><a href="#hutang" aria-controls="hutang" role="tab" data-toggle="tab" class="navTab navTabHartaHutang" title="Data Hutang"><?php echo ICON_hutang; ?> <span>Hutang</span></a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane active" id="hartatidakbergerak">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartatidakbergerak.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTATIDAKBERGERAK == '1') ? 'checked' : ''; ?> name="hartatidakbergerak" id="hartatidakbergerakselectYes" class="checkboxVer hartatidakbergerakYes" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->HARTATIDAKBERGERAK == '-1') ? 'checked' : ''; ?> name="hartatidakbergerak" class="checkboxVer hartatidakbergerakNo" value="0"> Tidak</label>
                                        </div>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->HARTATIDAKBERGERAK; ?></textarea>
                                        </div>
                                        <!-- <br> -->
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHartaTidakBergerak"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHarta">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="hartabergerak">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartabergerak.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK == '1') ? 'checked' : ''; ?> name="hartabergerak" class="checkboxVer hartabergerakYes" id="hartabergerakselectYes" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK == '-1') ? 'checked' : ''; ?> name="hartabergerak" class="checkboxVer hartabergerakNo" value="0"> Tidak</label>
                                        </div>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->HARTABERGERAK; ?></textarea>
                                        </div>
                                        <!-- <br> -->
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHarta">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="hartabergerakperabot">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartabergerakperabot.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK2 == '1') ? 'checked' : ''; ?> name="hartabergerakperabot" class="checkboxVer hartabergerakperabotYes" id="hartabergerakperabotselectYes" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK2 == '-1') ? 'checked' : ''; ?> name="hartabergerakperabot" class="checkboxVer hartabergerakperabotNo" value="0"> Tidak</label>
                                        </div>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->HARTABERGERAK2; ?></textarea>
                                        </div>
                                        <!-- <br> -->
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHarta">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="suratberharga">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_suratberharga.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->SURATBERHARGA == '1') ? 'checked' : ''; ?> name="suratberharga" id="suratberhargaselectYes" class="checkboxVer suratberhargaYes" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->SURATBERHARGA == '-1') ? 'checked' : ''; ?> name="suratberharga" class="checkboxVer suratberhargaNo" value="0"> Tidak</label>
                                        </div>
                                        <!-- <br> -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer sb"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->SURATBERHARGA; ?></textarea>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHarta">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="kas">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_kas.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->KAS == '1') ? 'checked' : ''; ?> name="kas" class="checkboxVer kasYes" id="KasselectYes" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->KAS == '-1') ? 'checked' : ''; ?> name="kas" class="checkboxVer kasNo" value="0"> Tidak</label>
                                        </div>
                                        <!-- <br> -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer kas"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->KAS; ?></textarea>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHarta">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="hartalainnya">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartalainnya.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTALAINNYA == '1') ? 'checked' : ''; ?> name="hartalainnya" class="checkboxVer hartalainnyaYes" id="hartalainnyaselectYes" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->HARTALAINNYA == '-1') ? 'checked' : ''; ?> name="hartalainnya" class="checkboxVer hartalainnyaNo" value="0"> Tidak</label>
                                        </div>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer"  <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->HARTALAINNYA; ?></textarea>
                                        </div>
                                        <!-- <br> -->
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHarta">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="hutang">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hutang.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HUTANG == '1') ? 'checked' : ''; ?> name="hutang" class="checkboxVer hutangYes" id="hutangselectYes" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->HUTANG == '-1') ? 'checked' : ''; ?> name="hutang" class="checkboxVer hutangNo" value="0"> Tidak</label>
                                        </div>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->HUTANG; ?></textarea>
                                        </div>
                                        <!-- <br> -->
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHutang">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div><!-- close Harta -->
                            <!--  -->
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="penerimaankas">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_penerimaankas.php'); ?>
                                </div>
                                <br>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="pengeluarankas">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_pengeluarankas.php'); ?>
                                </div>
                                <br>
                                <div class="clearfix"></div>
                            </div>
                            <!-- -->
                            <div role="tabpanel" class="tab-pane" id="penerimaanfasilitas">
                                <div class="contentTab">
                                    <?php require_once('verification_table_lampiran_2.php'); ?>
                                </div>
                                <div class="pull-right" style="padding-right:20px;">
                                    Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANFASILITAS == '1') ? 'checked' : ''; ?> name="penerimaanfasilitas" class="checkboxVer" value="1"> Ya</label>
                                    <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANFASILITAS == '-1') ? 'checked' : ''; ?> name="penerimaanfasilitas" class="checkboxVer" value="0"> Tidak</label>
                                </div>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer" <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->PENERIMAANFASILITAS; ?></textarea>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousFasilitas"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextFasilitas">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="lampiran">
                                <?php //require_once('verification_tabel_lampiran.php');?>

                                <ul class="nav nav-tabs" role="tablist">
                                    <li id="subtab" role="presentation" class="active"><a href="#pelepasanharta" aria-controls="pelepasanharta" role="tab" data-toggle="tab" class="navTab navTabPelepasan" title="Pelepasan Harta"><?php echo ICON_pelepasanharta; ?> <span>Penjualan/Pelepasan Harta </span></a></li>
                                    <li id="subtab" role="presentation"><a href="#penerimaanhibah" aria-controls="penerimaanhibah" role="tab" data-toggle="tab" class="navTab navTabPenerimaanHibah" title="Penerimaan Hibah"><?php echo ICON_penerimaanhibah; ?> <span>Penerimaan Hibah</span></a></li>
                                    <!-- <li id="subtab" role="presentation"><a href="#suratkuasamengumumkan" aria-controls="suratkuasamengumumkan" role="tab" data-toggle="tab" class="navTab navTabSuratKuasaMengumumkan" title="Surat Kuasa"><?php echo ICON_suratkuasamengumumkan; ?> <span>Surat Kuasa</span></a></li>  -->
                                    <!--<li id="subtab" role="presentation"><a href="#dokumenpendukung" aria-controls="dokumenpendukung" role="tab" data-toggle="tab" class="navTab navTabDokumenPendukung" title="Dokumen Pendukung"><?php echo ICON_dokumenpendukung; ?> <span>Dokumen Pendukung</span></a></li>-->
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane active" id="pelepasanharta">
                                        <div class="contentTab">
                                            <?php require_once('verification_table_lampiran_1.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PELEPASANHARTA == '1') ? 'checked' : ''; ?> name="pelepasanharta" class="checkboxVer" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->PELEPASANHARTA == '-1') ? 'checked' : ''; ?> name="pelepasanharta" class="checkboxVer" value="0"> Tidak</label>
                                        </div>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->PELEPASANHARTA; ?></textarea>
                                        </div>
                                        <!-- <br> -->
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousPelepasanHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextLampiran">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="penerimaanhibah">
                                        <div class="contentTab">
                                            <?php require_once('verification_table_lampiran_1_1.php'); ?>
                                        </div>
                                        <div class="pull-right" style="padding-right:20px;">
                                            Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANHIBAH == '1') ? 'checked' : ''; ?> name="penerimaanhibah" class="checkboxVer" value="1"> Ya</label>
                                            <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANHIBAH == '-1') ? 'checked' : ''; ?> name="penerimaanhibah" class="checkboxVer" value="0"> Tidak</label>
                                        </div>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->PENERIMAANHIBAH; ?></textarea>
                                        </div>
                                        <!-- <br> -->
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousLampiran"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextSuratKuasa">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
<!--                                    <div role="tabpanel" class="tab-pane" id="dokumenpendukung">
                                        <div class="contentTab">
                                            <?php // require_once('verification_table_dokumen_pendukung.php'); ?>
                                        </div>

                                        Terverfikasi ? : <label><input type="radio" <?php // echo (@$tmpData->VAL->DOKUMENPENDUKUNG == '1') ? 'checked' : ''; ?> name="dokumenpendukung" class="checkboxVer" value="1"> Ya</label>
                                        <label><input type="radio" <?php // echo (@$tmpData->VAL->DOKUMENPENDUKUNG == '-1') ? 'checked' : ''; ?> name="dokumenpendukung" class="checkboxVer" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php // echo @$tmpData->MSG->DOKUMENPENDUKUNG; ?></textarea>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousLampiran"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextDokumenPendukung">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>-->
                                </div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane active" id="reviewharta">
                                <div class="contentTab">
                                    <?php require_once('verification_table_review.php'); ?>
                                </div>
                                <?php if(true) { ?>
                                    <div class="pull-right" style="padding-right:55px;">
                                        Hasil Verifikasi : 
                                        <button type="button" class="btn-sm btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off" id="hasil-verifikasi">
                                            Yes to All
                                        </button>
                                    </div>
                                    <br>
                                <?php } ?>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousReviewHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextFasilitas">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="suratkuasamengumumkan">
                                <div class="contentTab">
                                    <?php // require_once('verification_tabel_skm.php');?>
                                    <?php require_once('verification_table_surat_kuasa.php');?>
                                </div>
                                <div class="pull-right" style="padding-right:20px;">
                                    Terverifikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->SURATKUASAMENGUMUMKAN == '1') ? 'checked' : ''; ?> name="suratkuasamengumumkan" class="checkboxVer" value="1"> Ya</label>
                                    <label><input type="radio" <?php echo (@$tmpData->VAL->SURATKUASAMENGUMUMKAN == '-1') ? 'checked' : ''; ?> name="suratkuasamengumumkan" class="checkboxVer" value="0"> Tidak</label>
                                </div>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer skm" <?php if($LHKPN->STATUS==2){ ?> disabled <?php } ?> style="width: 100%;"><?php echo @$tmpData->MSG->SURATKUASAMENGUMUMKAN; ?></textarea>
                                </div>
                                <!-- <br> -->
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousSuratKuasa"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextSuratKuasa">Simpan & Lanjut<i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div> 

                            <!-- Outlier -->
                            <div role="tabpanel" class="tab-pane " id="outlier">
                                <div class="contentTab">
                                    <?php require_once('verification_table_outlier.php'); ?>
                                    
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousReviewHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextFasilitas">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- End  -->

                            <!-- End -->

                            <div role="tabpanel" class="tab-pane" id="final">
                                <?php require_once('verification_tabel_historyverifikasi.php'); ?>
                                <form method="post" action="index.php/ever/verification/save/lhkpn/" id="ajaxFormFinal">
                                    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                        <thead>
                                            <tr>
                                                <th width="250">Item</th>
                                                <th  width="150">Hasil Verifikasi</th>
                                                <th>Catatan</th>
                                                <th width="370">Standar Verifikasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr id="datapribadiFinal">
                                                <td><span class="headerVerFinal">Data Pribadi</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->DATAPRIBADI)) ? (($tmpData->VAL->DATAPRIBADI == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->DATAPRIBADI == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->DATAPRIBADI)) ?></span>
                                                    <input type="hidden" name="VER[VAL][DATAPRIBADI]" class="valVerFinal" value="<?php echo @$tmpData->VAL->DATAPRIBADI; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][DATAPRIBADI]" class="msgVerFinal"><?php echo @$tmpData->MSG->DATAPRIBADI ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Wajib diisi</span></td>
                                            </tr>

                                            <tr id="jabatanFinal">
                                                <td><span class="headerVerFinal">Jabatan</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->JABATAN)) ? (($tmpData->VAL->JABATAN == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->JABATAN == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->JABATAN)) ?></span>
                                                    <input type="hidden" name="VER[VAL][JABATAN]" class="valVerFinal" value="<?php echo @$tmpData->VAL->JABATAN; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][JABATAN]" class="msgVerFinal"><?php echo @$tmpData->MSG->JABATAN ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Wajib diisi</span></td>
                                            </tr>

                                            <tr id="keluargaFinal">
                                                <td><span class="headerVerFinal">Keluarga</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->KELUARGA)) ? (($tmpData->VAL->KELUARGA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->KELUARGA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->KELUARGA)) ?></span>
                                                    <input type="hidden" name="VER[VAL][KELUARGA]" class="valVerFinal" value="<?php echo @$tmpData->VAL->KELUARGA; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][KELUARGA]" class="msgVerFinal"><?php echo @$tmpData->MSG->KELUARGA ?></textarea></td>
                                                <td><span class="headerVerFinal">Tidak wajib diisi</span></td>
                                            </tr>

                                            <tr id="hartatidakbergerakFinal">
                                                <td><span class="headerVerFinal">Tanah / Bangunan</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->HARTATIDAKBERGERAK)) ? (($tmpData->VAL->HARTATIDAKBERGERAK == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->HARTATIDAKBERGERAK == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->HARTATIDAKBERGERAK)) ?></span>
                                                    <input type="hidden" name="VER[VAL][HARTATIDAKBERGERAK]" class="valVerFinal" value="<?php echo @$tmpData->VAL->HARTATIDAKBERGERAK; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][HARTATIDAKBERGERAK]" class="msgVerFinal"><?php echo @$tmpData->MSG->HARTATIDAKBERGERAK ?></textarea></td>
                                                <td rowspan="6" class="headerVerFinal" style="vertical-align:middle;">Wajib diisi (minimal satu jenis harta)</td>
                                            </tr>

                                            <tr id="hartabergerakFinal">
                                                <td><span class="headerVerFinal">Alat Transportasi / Mesin</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->HARTABERGERAK)) ? (($tmpData->VAL->HARTABERGERAK == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->HARTABERGERAK == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->HARTABERGERAK)) ?></span>
                                                    <input type="hidden" name="VER[VAL][HARTABERGERAK]" class="valVerFinal" value="<?php echo @$tmpData->VAL->HARTABERGERAK; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][HARTABERGERAK]" class="msgVerFinal"><?php echo @$tmpData->MSG->HARTABERGERAK ?></textarea>
                                                </td>
                                                <!--<td><span class="headerVerFinal">&nbsp;<b>|</b></span></td>-->
                                            </tr>

                                            <tr id="hartabergerakperabotFinal">
                                                <td><span class="headerVerFinal">Bergerak Lain</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->HARTABERGERAK2)) ? (($tmpData->VAL->HARTABERGERAK2 == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->HARTABERGERAK2 == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->HARTABERGERAK2)) ?></span>
                                                    <input type="hidden" name="VER[VAL][HARTABERGERAK2]" class="valVerFinal" value="<?php echo @$tmpData->VAL->HARTABERGERAK2; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][HARTABERGERAK2]" class="msgVerFinal"><?php echo @$tmpData->MSG->HARTABERGERAK2 ?></textarea>
                                                </td>
                                                <!--<td><span class="headerVerFinal">&nbsp;|</span></td>-->
                                            </tr>

                                            <tr id="suratberhargaFinal">
                                                <td><span class="headerVerFinal">Surat Berharga</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->SURATBERHARGA)) ? (($tmpData->VAL->SURATBERHARGA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->SURATBERHARGA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT sbFinal"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->SURATBERHARGA)) ?></span>
                                                    <input type="hidden" name="VER[VAL][SURATBERHARGA]" class="valVerFinal" value="<?php echo @$tmpData->VAL->SURATBERHARGA; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][SURATBERHARGA]" class="msgVerFinal"><?php echo @$tmpData->MSG->SURATBERHARGA ?></textarea>
                                                </td>
                                                <!--<td><span class="headerVerFinal">&nbsp;&nbsp;&nbsp;> Wajib diisi (minimal satu jenis harta)</span></td>-->
                                            </tr>

                                            <tr id="kasFinal">
                                                <td><span class="headerVerFinal">Kas / Setara Kas</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->KAS)) ? (($tmpData->VAL->KAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->KAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT kasFinal"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->KAS)) ?></span>
                                                    <input type="hidden" name="VER[VAL][KAS]" class="valVerFinal" value="<?php echo @$tmpData->VAL->KAS; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][KAS]" class="msgVerFinal"><?php echo @$tmpData->MSG->KAS ?></textarea>
                                                </td>
                                                <!--<td><span class="headerVerFinal">&nbsp;|</span></td>-->
                                            </tr>

                                            <tr id="hartalainnyaFinal">
                                                <td><span class="headerVerFinal">Harta Lainnya</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->HARTALAINNYA)) ? (($tmpData->VAL->HARTALAINNYA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->HARTALAINNYA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->HARTALAINNYA)) ?></span>
                                                    <input type="hidden" name="VER[VAL][HARTALAINNYA]" class="valVerFinal" value="<?php echo @$tmpData->VAL->HARTALAINNYA; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][HARTALAINNYA]" class="msgVerFinal"><?php echo @$tmpData->MSG->HARTALAINNYA ?></textarea>
                                                </td>
                                                <!--<td><span class="headerVerFinal">&nbsp;&rfloor;</span></td>-->
                                            </tr>

                                            <tr id="hutangFinal">
                                                <td><span class="headerVerFinal">Hutang</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->HUTANG)) ? (($tmpData->VAL->HUTANG == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->HUTANG == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->HUTANG)) ?></span>
                                                    <input type="hidden" name="VER[VAL][HUTANG]" class="valVerFinal" value="<?php echo @$tmpData->VAL->HUTANG; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][HUTANG]" class="msgVerFinal"><?php echo @$tmpData->MSG->HUTANG ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Tidak Wajib diisi</span></td>
                                            </tr> 

                                            <tr id="penerimaankasFinal">
                                                <td><span class="headerVerFinal">Penerimaan Kas</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->PENERIMAANKAS)) ? (($tmpData->VAL->PENERIMAANKAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->PENERIMAANKAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->PENERIMAANKAS)) ?></span>
                                                    <input type="hidden" name="VER[VAL][PENERIMAANKAS]" class="valVerFinal" value="<?php echo @$tmpData->VAL->PENERIMAANKAS; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][PENERIMAANKAS]" class="msgVerFinal"><?php echo @$tmpData->MSG->PENERIMAANKAS ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Wajib diisi</span></td>
                                            </tr>

                                            <tr id="pengeluarankasFinal">
                                                <td><span class="headerVerFinal">Pengeluaran Kas</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->PENGELUARANKAS)) ? (($tmpData->VAL->PENGELUARANKAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->PENGELUARANKAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->PENGELUARANKAS)) ?></span>
                                                    <input type="hidden" name="VER[VAL][PENGELUARANKAS]" class="valVerFinal" value="<?php echo @$tmpData->VAL->PENGELUARANKAS; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][PENGELUARANKAS]" class="msgVerFinal"><?php echo @$tmpData->MSG->PENGELUARANKAS ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Wajib diisi</span></td>
                                            </tr>

                                                        <!--                                         <tr id="lampiranFinal">
                                                                                                    <td><span class="headerVerFinal">Lampiran</span></td>
                                                                                                    <td align="center"><span class="checkboxVerFinal"></span></td>
                                                                                                    <td><span class="msgVerFinalTXT"></span>
                                                                                                        <input type="hidden" name="VER[VAL][LAMPIRAN]" class="valVerFinal" value="">
                                                                                                        <textarea style="display: none;" name="VER[MSG][LAMPIRAN]" class="msgVerFinal"></textarea>
                                                                                                    </td>
                                                                                                </tr> -->

                                            <tr id="pelepasanhartaFinal">
                                                <td><span class="headerVerFinal">Pelepasan Harta</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->PELEPASANHARTA)) ? (($tmpData->VAL->PELEPASANHARTA == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->PELEPASANHARTA == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->PELEPASANHARTA)) ?></span>
                                                    <input type="hidden" name="VER[VAL][PELEPASANHARTA]" class="valVerFinal" value="<?php echo @$tmpData->VAL->PELEPASANHARTA; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][PELEPASANHARTA]" class="msgVerFinal"><?php echo @$tmpData->MSG->PELEPASANHARTA ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Tidak Wajib diisi</span></td>
                                            </tr>

                                            <tr id="penerimaanhibahFinal">
                                                <td><span class="headerVerFinal">Penerimaan Hibah</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->PENERIMAANHIBAH)) ? (($tmpData->VAL->PENERIMAANHIBAH == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->PENERIMAANHIBAH == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->PENERIMAANHIBAH)) ?></span>
                                                    <input type="hidden" name="VER[VAL][PENERIMAANHIBAH]" class="valVerFinal" value="<?php echo @$tmpData->VAL->PENERIMAANHIBAH; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][PENERIMAANHIBAH]" class="msgVerFinal"><?php echo @$tmpData->MSG->PENERIMAANHIBAH ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Tidak Wajib diisi</span></td>
                                            </tr>

                                            <tr id="penerimaanfasilitasFinal">
                                                <td><span class="headerVerFinal">Penerimaan Fasilitas</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->PENERIMAANFASILITAS)) ? (($tmpData->VAL->PENERIMAANFASILITAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->PENERIMAANFASILITAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->PENERIMAANFASILITAS)) ?></span>
                                                    <input type="hidden" name="VER[VAL][PENERIMAANFASILITAS]" class="valVerFinal" value="<?php echo @$tmpData->VAL->PENERIMAANFASILITAS; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][PENERIMAANFASILITAS]" class="msgVerFinal"><?php echo @$tmpData->MSG->PENERIMAANFASILITAS ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Tidak Wajib diisi</span></td>
                                            </tr>
                                            <tr id="suratkuasamengumumkanFinal">
                                                <td><span class="headerVerFinal">Surat Kuasa</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->SURATKUASAMENGUMUMKAN)) ? (($tmpData->VAL->SURATKUASAMENGUMUMKAN == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->SURATKUASAMENGUMUMKAN == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT skmFinal"></span><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->SURATKUASAMENGUMUMKAN)) ?></span>
                                                    <input type="hidden" name="VER[VAL][SURATKUASAMENGUMUMKAN]" class="valVerFinal" value="<?php echo @$tmpData->VAL->SURATKUASAMENGUMUMKAN; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][SURATKUASAMENGUMUMKAN]" class="msgVerFinal"><?php echo @$tmpData->MSG->SURATKUASAMENGUMUMKAN ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Hardcopy wajib dikirim untuk laporan pertama kali</span></td>
                                            </tr> 

<!--                                                                                                <tr id="dokumenpendukungFinal">
                                                                                                    <td><span class="headerVerFinal">Dokumen Pendukung</span></td>
                                                                                                    <td align="center"><span class="checkboxVerFinal"><i class="fa <?php // echo (!empty($tmpData->VAL->DOKUMENPENDUKUNG)) ? (($tmpData->VAL->DOKUMENPENDUKUNG == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php // echo (@$tmpData->VAL->DOKUMENPENDUKUNG == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                                                                    <td><span class="msgVerFinalTXT"><?php // echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->DOKUMENPENDUKUNG)) ?></span>
                                                                                                        <input type="hidden" name="VER[VAL][DOKUMENPENDUKUNG]" class="valVerFinal" value="<?php // echo @$tmpData->VAL->DOKUMENPENDUKUNG; ?>">
                                                                                                        <textarea style="display: none;" name="VER[MSG][DOKUMENPENDUKUNG]" class="msgVerFinal"><?php // echo @$tmpData->MSG->DOKUMENPENDUKUNG ?></textarea>
                                                                                                    </td>
                                                                                                    <td><span class="headerVerFinal">Hardcopy tidak wajib dikirim</span></td>
                                                                                                </tr>-->

                                        </tbody>
                                    </table>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 modal-warning">
                                        <div class="modal-body col-md-12">
                                            <div class="col-md-2" align="right"><label>Hasil Verifikasi Final : </label></div>
                                            <div class="col-md-4">
                                                <div><label><input type="radio" name="final" class="terverif-lengkap" value="1" onchange="$('#mail_false').hide();
                                                        $('#mail_ditolak').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_true').show(); $('#pesan_reject').hide();" required> Terverifikasi Lengkap</label></div>
                                                <?php //if (($LHKPN->STATUS == '1' || $LHKPN->STATUS == '2') && ($LHKPN->ALASAN == '1' || $LHKPN->ALASAN == '2')) {?>
                                                <!-- <div><label><input type="radio" name="final" class="tidak-lengkap" value="3" onchange="$('#mail_false').hide();
                                                        $('#mail_true').hide(); $('#mail_ditolak').hide(); $('#mail_tidak_lengkap').show(); $('#pesan_reject').hide();" required> Terverifikasi Tidak Lengkap</label></div> -->
                                                <?php //}
                                                //else{
                                                 ?>
                                                 <!--
                                                 <div><label><input type="radio" name="final" class="" value="1" onchange="$('#mail_false').hide();
                                                        $('#mail_ditolak').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_true').hide(); $('#pesan_reject').show();"> Dikembalikan ke Validator</label></div> -->
                                                <?php //} ?>
                                                <div><label><input type="radio" name="final" class="ditolak" value="2" onchange="
                                                <?php if(($LHKPN->STATUS == '1' || $LHKPN->STATUS == '2') && ($LHKPN->ALASAN == '1' || $LHKPN->ALASAN == '2')) { ?>
                                                    $('#mail_false').hide();$('#mail_true').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_ditolak').show(); $('#pesan_reject').hide(); 
                                                <?php } else if($LHKPN->STATUS == '1' && $LHKPN->ALASAN == NULL && $STATUS_VERIFIKASI == '1') { ?>
                                                    $('#mail_false').hide();$('#mail_true').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_ditolak').show(); $('#pesan_reject').hide(); 
                                                <?php } else { ?>
                                                    $('#mail_false').show();$('#mail_true').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_ditolak').hide(); $('#pesan_reject').hide();
                                                    reloadCatatan(<?php echo $ID_LHKPN; ?>); 
                                                <?php } ?>" 
                                                    required>
                                                <?php if(($LHKPN->STATUS == '1' || $LHKPN->STATUS == '2') && ($LHKPN->ALASAN == '1' || $LHKPN->ALASAN == '2')) { ?> 
                                                        Dikembalikan
                                                <?php } else if($LHKPN->STATUS == '1' && $LHKPN->ALASAN == NULL && $STATUS_VERIFIKASI == '1') { ?>
                                                        Dikembalikan
                                                <?php } else { ?> 
                                                        Perlu Perbaikan
                                                <?php } ?>
                                                </label></div>
                                            </div>
                                        </div>

                                        <div class="modal-body col-md-12 alasan_verifikasi" style="display:none;">
                                            <div class="col-md-2" align="right"><label>Alasan : </label></div>
                                            <div class="col-md-4">
                                                <div class="col-md-6"><label><input type="radio" name="alasan" class="" value="1" required> Salah entry</label></div>
                                                <div class="col-md-6"><label><input type="radio" name="alasan" class="" value="2" checked required> Tidak lulus</label></div>
                                            </div>
                                        </div>
                                    </div>

                                    <br> <br> <br> <br> <br>
                                    <br>
                                    <div <?php // echo ($stat === true ? 'style="display: none;"' : '')        ?> style="display: none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_false">
                                        <!--Email template untuk PN-->
                                        <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI">Preview</button>
                                        <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                        <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                        <?php
                                        $days = ($LHKPN->entry_via == '1') ? "30 days" : "30 days";
                                        $tgl_ver = date_create(@$LHKPN->TANGGAL);
                                        date_add($tgl_ver, date_interval_create_from_date_string($days));
                                        $tgl_ver = date_format($tgl_ver, 'd-m-Y');
                                        $entry_via = $LHKPN->entry_via;
                                        foreach ($JABATANS_P as $jabatan) {
                                            $jab = $jabatan->NAMA_JABATAN . ' - ' . $jabatan->UK_NAMA . ' - ' . $jabatan->INST_NAMA . '<br>';
                                        }
                                        if ($LHKPN->entry_via == '1') {
                                            $content = "Bersama ini disampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara $LHKPN->NAMA_LENGKAP yang perlu dilengkapi sebagaimana terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data disertai salinan surat ini  paling lambat tanggal $tgl_ver , dan menyampaikan ke Komisi Pemberantasan Korupsi";
                                        } else {
                                            $content = "Bersama ini kami sampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara yang perlu dilengkapi sebagaimana daftar terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data dan menyampaikan ke Komisi Pemberantasan Korupsi tidak melampaui tanggal $tgl_ver.";
                                        }
                                        $content_ditolak = "Bersama ini kami sampaikan bahwa LHKPN Tanggal $LHKPN->tgl_lapor atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.";
                                        ?>
                                        <?php // if ($LHKPN->STATUS == 1 && ($LHKPN->ALASAN == 1 || $LHKPN->ALASAN == 2)) { ?>
<!--                                            <textarea id="MSG_VERIFIKASI" name="MSG_VERIFIKASI" rows="10" style="width: 100%;" class="ckeditor">
                                                                    <pre>
                                                                       <table style="width:100%" >
                                                                            <tr>
                                                                              <td width="8%">Nomor</td>
                                                                              <td width="2%">:</td>
                                                                              <td width="59%">R- /XX/YY/ZZZZ </td>
                                                                              <td width="31%">Jakarta, <?= date('d F Y') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Sifat</td>
                                                                              <td>:</td>
                                                                              <td>Segera</td>
                                                                              <td>&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Hal</td>
                                                                              <td>:</td>
                                                                              <td>Laporan Ditolak</td>
                                                                              <td>&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4">Yth. Sdr. <?= $LHKPN->NAMA; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><?= $jab; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4">di Tempat</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><?= $content_ditolak; ?> <div align="justify"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">Apabila memerlukan informasi lebih lanjut, silakan menghubungi Direktorat Pendaftaran dan Pemeriksaan Laporan Harta Kekayaan Penyelenggara Negara pada call center 198. </div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4">Atas kerjasama yang diberikan diucapkan terimakasih.</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">Terimakasih</div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"> <div align="justify">Direktorat Pendaftarandan Pemeriksaan LHKPN</div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"> <div align="justify">--------------------------------------------------------------</div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">Email ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila hasil verifikasi menyatakan laporan e-LHKPN Saudara dinyatakan lengkap.</div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">&copy; 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                              <td>&nbsp;</td>
                                                                            </tr>

                                                                        </table>
                                                                    </pre>
                                        </textarea>-->
                                            <?php // } else { ?>
                                        <input type="hidden" id="tgl_ver_" name="tgl_ver_" value="<?php echo $tgl_ver; ?>">
                                        <input type="hidden" id="entry_via_" value="<?php echo $entry_via; ?>">
                                        <textarea id="MSG_VERIFIKASI" name="MSG_VERIFIKASI" rows="10" style="width: 100%;" class="ckeditor">
                                                                       <table style="width:100%" >
                                                                            <tr>
                                                                              <td colspan="4">Yth. Sdr. <?= $LHKPN->NAMA_LENGKAP; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><?= $jabatan->INST_NAMA; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4">di Tempat</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><?= $content; ?> <div align="justify"></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">Email pemberitahuan permintaan kelengkapan ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila Saudara telah melengkapi daftar permintaan kelengkapan dan telah diverifikasi oleh KPK. </div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta aplikasi e-Filing LHKPN. </div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4"><div align="justify">Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198. </div></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4">Atas kerjasama yang diberikan diucapkan terimakasih.</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>
                                                                                      Direktorat Pendaftaran dan Pemeriksaan LHKPN<br>
                                                                                      --------------------------------------------------------------<br>
                                                                                      Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                                                      &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198
                                                                              </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4">&nbsp;</td>
                                                                            </tr>

                                                                        </table>
                                        </textarea>

                                        <br>
                                        <br>

                                        <div >   
                                            <button type="button" class="btn btn-default" target="_blank" id="btnPreviewMSG_VERIFIKASI_INSTANSI">Preview</button>
                                            <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                            <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                            <textarea id="MSG_VERIFIKASI_INSTANSI" name="MSG_VERIFIKASI_INSTANSI" rows="10" style="width: 100%;" class="ckeditor">

                                                <?php
                                                $NAMA_JABATAN = '';
                                                foreach ($JABATANS as $jabatan) {
                                                    $NAMA_JABATAN = $jabatan->NAMA_JABATAN . '  ' . $jabatan->INST_NAMA;
                                                }
                                                ?>

                                                                        Daftar kekurangan kelengkapan yang harus diisi dan dilengkapi oleh Sdr. <?= $LHKPN->NAMA_LENGKAP . ', ' . $NAMA_JABATAN; ?> :
                                                                       <table id="tblInnerMessage" class="tb-1 tb-1a" border="1" cellspacing="0" cellpadding="0" height="100px" width="650px">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>JENIS</th>
                                                                                    <th>URAIAN</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody class="body-table">
                                                        <?php
                                                        if (!empty($tmpData)) {
//                                                            $Jenis = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'DOKUMENPENDUKUNG' => 'Dokumen Pendukung', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];
                                                            $Jenis = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];
                                                            foreach ($tmpData->VAL as $key => $val) {
                                                                if ($val == '-1') {
                                                                    ?>
                                                                        <tr style="border: 1px solid black;">
                                                                            <td style="border: 1px solid black;"><?php echo $Jenis[$key] ?></td>
                                                                            <td style="border: 1px solid black;"><?php echo @$tmpData->MSG->$key ?> 
                                                                            <?php echo @implode('<br> - ', $veritemnoktext[strtoupper($key)]); ?></td>
                                                                        </tr>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <p>Team Verifikasi KPK</p>
                                            </textarea>
                                        </div>
                                            <?php // } ?>
                                    </div>

                                    <!-- DIKEMBALIKAN KE VALIDATOR -->
                                    <!--
                                    <div style="display:none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="pesan_reject">
                                        <textarea id="PESAN_KEMBALIKAN" name="PESAN_KEMBALIKAN" rows="10" style="width: 100%;" class="ckeditor"></textarea>
                                    </div> -->

                                    <!--yo add-->

                                    <div style="display:none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_true">
                                        <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI_TRUE">Preview</button>
                                        <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                        <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                        <textarea id="MSG_VERIFIKASI_TRUE" name="MSG_VERIFIKASI_TRUE" rows="10" style="width: 100%;" class="ckeditor">
                                                                    <table>
                                                                         <tr>
                                                                                 <td>
                                                                                    <?php if ($LHKPN->JENIS_LAPORAN == '4') {
                                                                                        $thn_pelaporan = date('Y', strtotime($LHKPN->tgl_lapor));
                                                                                    }
                                                                                    else{
                                                                                        $thn_pelaporan = date('d-m-Y', strtotime($LHKPN->tgl_lapor));
                                                                                    }
                                                                                    ?>
                                                                         Yth. Sdr. <?= $LHKPN->NAMA_LENGKAP; ?> <br/>
                                                            <?php
                                                            $ar_ins = array();
                                                            $ar_jab = array();
                                                            $ar_bdg = array();
                                                            foreach ($JABATANS_P as $jabatan) {
                                                                $ar_ins[] = $jabatan->INST_NAMA;
                                                                $ar_jab[] = $jabatan->NAMA_JABATAN;
                                                                $ar_bdg[] = $jabatan->BDG_NAMA;
                                                            }
                                                            $ins = implode(", ", $ar_ins);
                                                            $jab = implode(", ", $ar_jab);
                                                            $bdg = implode(", ", array_unique($ar_bdg));
                                                            echo $ins;
                                                            ?>
                                                                         <br/>
                                                                        Di Tempat
                                                                             </td>
                                                                        </tr>
                                                                        <table>
                                                                                       

                                                               Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan lengkap dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                                                <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                                                                    
                                                                    <tbody class="body-table">
                                                                    
                                                                                                            <tr>
                                                                                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= $LHKPN->NAMA_LENGKAP; ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</td>
                                                                                                                <td > <?= $jab; ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= $bdg; ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= $ins ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Tanggal / Tahun Pelaporan</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= $thn_pelaporan; ?></td>
                                                																<td></td>
                                                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                <p>&nbsp;Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau call center 198.
                                    <table><table>
                                                                         <tr>
                                                                                 <td>                                                      
                                                                        Terima kasih<br/>

                                Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                --------------------------------------------------------------<br/>
                                Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                © 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198

                                                                             </td>
                                                                        </tr>
                                                                        </table>
                                                        </textarea>

                                                        </div>
                                    
                                    <div style="display:none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_tidak_lengkap">
                                        Email template Setuju
                                        <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI_TIDAK_LENGKAP">Preview</button>
                                        <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                        <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                        <textarea id="MSG_VERIFIKASI_TIDAK_LENGKAP" name="MSG_VERIFIKASI_TIDAK_LENGKAP" rows="10" style="width: 100%;" class="ckeditor">
                                                                    <table>
                                                                         <tr>
                                                                                 <td>
                                                                         Yth. Sdr. <?= $LHKPN->NAMA_LENGKAP; ?> <br/>
                                                            <?php
                                                            $ar_ins = array();
                                                            $ar_jab = array();
                                                            $ar_bdg = array();
                                                            foreach ($JABATANS_P as $jabatan) {
                                                                $ar_ins[] = $jabatan->INST_NAMA;
                                                                $ar_jab[] = $jabatan->NAMA_JABATAN;
                                                                $ar_bdg[] = $jabatan->BDG_NAMA;
                                                            }
                                                            $ins = implode(", ", $ar_ins);
                                                            $jab = implode(", ", $ar_jab);
                                                            $bdg = implode(", ", array_unique($ar_bdg));
                                                            echo $ins;
                                                            ?>
                                                                         <br/>
                                                                        Di Tempat
                                                                             </td>
                                                                        </tr>
                                                                        <table>
                                                                                       

                                                               Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan Tidak Lengkap dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :
                                                                <table class="tb-1 tb-1a" border="0" cellspacing="0" cellpadding="5" width="100%" style="margin-left: 20px;">
                                                                    
                                                                    <tbody class="body-table">
                                                                    
                                                                                                            <tr>
                                                                                                                <td width="20%" valign="top"><b>Atas Nama</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= $LHKPN->NAMA_LENGKAP; ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Jabatan</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</td>
                                                                                                                <td > <?= $jab; ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Bidang</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= $bdg; ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Lembaga</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= $ins ?></td>
                                                                                                            </tr>
                                                															<tr>
                                                                                                                <td width="20%" valign="top"><b>Tahun Pelaporan</b></td>
                                                                                                                <td width="5%" valign="top"><b>:</b></td>
                                                                                                                <td><?= date('Y', strtotime($LHKPN->tgl_lapor)); ?></td>
                                                																<td></td>
                                                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                    <p>&nbsp;Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau call center 198.
                                    <table><table>
                                                                         <tr>
                                                                                 <td>
                                                                        Terima kasih<br/>

                                Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                --------------------------------------------------------------<br/>
                                Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                © 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198

                                                                             </td>
                                                                        </tr>
                                                                        </table>
                                                        </textarea>

                                                        </div>
                                    <div style="display:none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_ditolak">
                                        Email template Setuju
                                        <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI_DITOLAK">Preview</button>
                                        <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                        <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                        <input type="hidden" id="tgl_ver" value="<?php echo $tgl_ver; ?>">
                                        <textarea id="MSG_VERIFIKASI_DITOLAK" name="MSG_VERIFIKASI_DITOLAK" rows="10" style="width: 100%;" class="ckeditor">
                                                                    <table>
                                                                         <tr>
                                                                                 <td>
                                                                         Yth. Sdr. <?= $LHKPN->NAMA_LENGKAP; ?> <br/>
                                                            <?php
                                                            $ar_ins = array();
                                                            $ar_jab = array();
                                                            $ar_bdg = array();
                                                            foreach ($JABATANS_P as $jabatan) {
                                                                $ar_ins[] = $jabatan->INST_NAMA;
                                                                $ar_jab[] = $jabatan->NAMA_JABATAN;
                                                                $ar_bdg[] = $jabatan->BDG_NAMA;
                                                            }
                                                            $ins = implode(", ", $ar_ins);
                                                            $jab = implode(", ", $ar_jab);
                                                            $bdg = implode(", ", array_unique($ar_bdg));
                                                            echo $ins;
                                                            ?>
                                                                         <br/>
                                                                        Di Tempat
                                                                             </td>
                                                                        </tr>
                                                                        <table>
                                    <p>Bersama ini kami sampaikan bahwa LHKPN Tanggal <?php echo tgl_format($LHKPN->tgl_lapor);?> atas nama Saudara dinyatakan DIKEMBALIKAN dikarenakan KPK belum menerima kekurangan dokumen kelengkapan atas nama Saudara sesuai dalam jangka waktu yang telah ditentukan.
                                    <p>Sehubungan dengan hal tersebut,  harap agar Saudara segera mengisi kembali LHKPN melalui elhkpn.kpk.go.id dan menyampaikannya kepada KPK.  Untuk informasi lebih lanjut, silakan menghubungi kami melalui email elhkpn@kpk.go.id  atau call center 198.
                                    <table><table>
                                                                         <tr>
                                                                                 <td>
                                                                        Terima kasih<br/>

                                Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                --------------------------------------------------------------<br/>
                                Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                © 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198

                                                                             </td>
                                                                        </tr>
                                                                        </table>
                                                        </textarea>

                                                        </div>

                                                        <!--yo end-->

                                                        <br>

                                                        <input type="hidden" name="act" value="doverify">
                                                        <input type="hidden" name="ID_LHKPN" value="<?php echo $LHKPN->ID_LHKPN; ?>">
                                                        <input type="hidden" name="DIKEMBALIKAN" value="<?php echo $LHKPN->DIKEMBALIKAN; ?>">
                                                        <div class="clearfix" style="margin-bottom: 20px;"></div>
                                                        <div class="pull-right">
                                                            <button type="button" class="btn btn-sm btn-warning btnPreviousFInal"><i class="fa fa-backward"></i> Sebelumnya</button>
                                                            <!--
                                                            <?php //if ($LHKPN->JENIS_LAPORAN == 1): ?>
                                                                <button type="button" id="btnKembalikan" class="btn btn-sm btn-danger"><i class="fa fa-undo"></i> Kembalikan</button>
                                                            <?php //endif ?>
                                                        -->
                                                            <!--<button type="button" id="btnDraft" class="btn btn-sm btn-success btn-save-data"><i class="fa fa-save"></i> Simpan Draft</button>-->
                                                            <button type="submit" id="btnFinal" class="btn btn-sm btn-primary"><i class="fa fa-send-o"></i> Simpan Final Verifikasi</button>
                                                        </div>
                                                        <div class="clearfix"></div>

                                                        </form>
                                                        </div>

                                                        </div>
                                                        </div>
                                                        </div><!-- /.box -->
                                                        </div><!-- /.col -->
                                                        </div><!-- /.row -->
                                                        </section><!-- /.content -->
                                                        <div id="conhidden" style="display: none;"></div>
                                                        <!--<script src="<?php // echo base_url(); ?>plugins/sweet-alert/dist/sweetalert.min.js" type="text/javascript"></script>-->

                                                        <script type="text/javascript">
                                            var verifAll = {
                                                yesToAll: function() {
                                                    $(this).toggleClass('activated');
                                                    if($(this).hasClass('activated')) {
                                                        verifAll.changeButton($(this));
                                                        verifAll.action($('[name="datapribadi"][value="1"]'));
                                                        verifAll.action($('[name="jabatan"][value="1"]'));
                                                        verifAll.action($('[name="keluarga"][value="1"]'));
                                                        verifAll.action($('[name="hartatidakbergerak"][value="1"]'));
                                                        verifAll.action($('[name="hartabergerak"][value="1"]'));
                                                        verifAll.action($('[name="hartabergerakperabot"][value="1"]'));
                                                        verifAll.action($('[name="suratberharga"][value="1"]'));
                                                        verifAll.action($('[name="kas"][value="1"]'));
                                                        verifAll.action($('[name="hartalainnya"][value="1"]'));
                                                        verifAll.action($('[name="hutang"][value="1"]'));
                                                        verifAll.action($('[name="penerimaankas"][value="1"]'));
                                                        verifAll.action($('[name="pengeluarankas"][value="1"]'));
                                                        verifAll.action($('[name="penerimaanfasilitas"][value="1"]'));
                                                        verifAll.action($('[name="pelepasanharta"][value="1"]'));
                                                        verifAll.action($('[name="penerimaanhibah"][value="1"]'));
                                                    }
                                                },
                                                changeButton: function( e ) {
                                                    $(e).attr('class', 'btn-sm btn-success');
                                                    $(e).html('Done <i class="fa fa-check"></i>');
                                                    $(e).prop("disabled", true);
                                                },
                                                action: function( e ) {
                                                    $(e).prop("checked", true).trigger("click");
                                                },
                                                restoreButton: function( e ) {
                                                    $(e).attr('class', 'btn-sm btn-danger');
                                                    $(e).text('Yes to All');
                                                    $(e).prop("disabled", false);
                                                }
                                            }

                                            function checkValidasiVerif(){
                                                var data2 = $('.valVerFinal').serializeArray();
                                                var check_disabled_ver = 0;
                                                var check_mines_ver = 0;
                                                var checkYesToAll = 0;
                                                $.each(data2, function(i, item) {
                                                    // if (item.value !== '') {
                                                    //     $('#btnFinal').prop('disabled', false);
                                                    //     $('input[name="final"]').prop('disabled', false);
                                                    // }
                                                    if (item.value !== '') {
                                                        // nothing process
                                                    }else{
                                                        check_disabled_ver++;
                                                    }

                                                    if (item.value == '-1' || item.value == '' || item.value == null) {
                                                        check_mines_ver++;
                                                        if(item.name != "VER[VAL][SURATKUASAMENGUMUMKAN]") {
                                                            checkYesToAll++;
                                                        }
                                                    }
                                                    
                                                });
                                                if(check_disabled_ver > 0){
                                                    $('#btnFinal').prop('disabled', true);
                                                    $('input[name="final"]').prop('disabled', true);
                                                }else{
                                                    $('#btnFinal').prop('disabled', false);
                                                    $('input[name="final"]').prop('disabled', false);
                                                }

                                                if(check_mines_ver > 0){
                                                    $('.terverif-lengkap').prop('disabled', true);
                                                }else{
                                                    $('.terverif-lengkap').prop('disabled', false);
                                                }

                                                if(check_mines_ver == 0){
                                                    $('.tidak-lengkap').prop('disabled', true);
                                                    $('.ditolak').prop('disabled', true);
                                                }else{
                                                    $('.tidak-lengkap').prop('disabled', false);
                                                    $('.ditolak').prop('disabled', false);
                                                }

                                                if(checkYesToAll > 0){
                                                    verifAll.restoreButton($('#hasil-verifikasi'));
                                                } else {
                                                    verifAll.changeButton($('#hasil-verifikasi'));
                                                }
                                                return;
                                            }



                                            function modifCKEDITOR(val, cla)
                                            {
                                                if (val == '1') {
                                                    val = 'Iya';
                                                } else {
                                                    val = 'Tidak';
                                                }

                                                var text = $.parseHTML(CKEDITOR.instances['MSG_VERIFIKASI_INSTANSI'].getData(true));
                                                var html = $('#conhidden');
                                                html.html(text);

                                                $('.' + cla, html).text(val);
                                                CKEDITOR.instances['MSG_VERIFIKASI_INSTANSI'].setData(html.html());
                                            }

                                            function reloadCatatan(id)
                                            {
                                                $.get('index.php/ever/verification/getTableCatatan/' + id, function(htmlA) {
                                                    var text = $.parseHTML(CKEDITOR.instances['MSG_VERIFIKASI_INSTANSI'].getData(true));
                                                    var html = $('#conhidden');
                                                    html.html(text);

                                                    $('.body-table', html).html(htmlA);
                                                    CKEDITOR.instances['MSG_VERIFIKASI_INSTANSI'].setData(html.html());
                                                });
                                            }
                                            
                                            function hideLah(){
                                                $('#mail_true').hide();
                                                    $('#mail_false').hide();
                                                    $('#mail_ditolak').hide();
                                                    $('#mail_tidak_lengkap').hide();
                                            }
                                            
                                            function f_checkboxVer(ele) {
                                                v = $(ele).val();
                                                t = $(ele).parents('.tab-pane').attr('id');
                                                f = t + 'Final';

                                                if ($(ele).is(':checked') && $(ele).val() == 1) {
                                                    $('#' + f).find('.checkboxVerFinal').html('<i class="fa fa-check-square" style="color: blue;"></i>');
                                                    $('#' + f).find('.valVerFinal').val('1');
                                                } else {
                                                    $('#' + f).find('.checkboxVerFinal').html('<i class="fa fa-minus-square" style="color: red;"></i>');
                                                    $('#' + f).find('.valVerFinal').val('-1');
                                                }
                                            }
                                            
                                            $(document).ready(function() {
                                                <?php if ($upperli): ?>
            $("li#<?php echo $upperli; ?>").find('a').trigger('click');

    <?php if ($bottomli): ?>
                $("li#<?php echo $bottomli; ?>").find('a').trigger('click');
    <?php endif; ?>
<?php endif; ?>
                                            
                                                $('.checkboxVer').click(function() {
                                                    f_checkboxVer(this);

//                                                    var stat = true;
//                                                    $('.valVerFinal').each(function() {
//                                                        if ($(this).val() == '-1') {
//                                                            stat = false;
//                                                        }
//                                                    });

//                                                    if (stat) {
//                                                        $('.div-msg').hide();
//                                                    } else {
//                                                        $('.div-msg').show();
//                                                    }
                                                });
                                            });

                                            $(document).ready(function() {
                                                $('input:radio[name="suratberharga"]').change(function(){
                                                    var status = <?php echo $LHKPN->STATUS; ?>;
                                                    var alasan = <?php echo empty($LHKPN->ALASAN) ? 0 : $LHKPN->ALASAN; ?>;
                                                    if (status == '1' && alasan == '0') {
                                                        if (this.checked && this.value == '0') {
                                                            var id = $('input[name="ID_LHKPN"]').val();
                                                            var msg1 = 'Mohon diunggah ke dalam aplikasi e-Filing LHKPN/dikirimkan ke KPK salinan dokumen kepemilikan Surat Berharga (halaman identitas dan halaman saldo pada saat pelaporan) atas item:\n';
                                                            $.get('index.php/ever/verification/sbuncomplete/'+id,function(data){
                                                                var msg = JSON.parse(data);
                                                                if($.trim(msg)) {
                                                                    var msg2 = msg.map(function(val, i) {
                                                                        return val.no+'. Penerbit/Perusahaan '+val.penerbit+', Custodian/Sekuritas '+val.custodian+' dengan Nomor Rekening/Nomor Nasabah '+val.norek+' atas nama '+val.atas_nama;
                                                                    }).join('\n')
                                                                    var msg2html = msg.map(function(val, i) {
                                                                        return '<b>'+val.no+'.</b> Penerbit/Perusahaan <b>'+val.penerbit+'</b>, Custodian/Sekuritas <b>'+val.custodian+'</b> dengan Nomor Rekening/Nomor Nasabah <b>'+val.norek+'</b> atas nama <b>'+val.atas_nama+'</b>';
                                                                    }).join('\n')
                                                                    // $(".sb").val(msg1+msg2);
                                                                    // $("textarea[name='VER[MSG][SURATBERHARGA]']").val(msg1+msg2);
                                                                    // $(".sbFinal").html(msg1+msg2html);
                                                                } else {
                                                                    false;
                                                                }
                                                            })
                                                        } else {
                                                            $('.sb').val('');
                                                            $("textarea[name='VER[MSG][SURATBERHARGA]']").val('');
                                                            $(".sbFinal").html('');
                                                        }
                                                    } else {
                                                        false;
                                                    }
                                                });
                                                $('input:radio[name="kas"]').change(function(){
                                                    var status = <?php echo $LHKPN->STATUS; ?>;
                                                    var alasan = <?php echo empty($LHKPN->ALASAN) ? 0 : $LHKPN->ALASAN; ?>;
                                                    if (status == '1' && alasan == '0') {
                                                        if (this.checked && this.value == '0') {
                                                            var id = $('input[name="ID_LHKPN"]').val();
                                                            var msg1 = 'Mohon diunggah ke dalam aplikasi e-Filing LHKPN/dikirimkan ke KPK salinan dokumen kepemilikan Kas dan Setara Kas (halaman identitas dan halaman saldo pada saat pelaporan) atas item:\n';
                                                            $.get('index.php/ever/verification/kasuncomplete/'+id,function(data){
                                                                var msg = JSON.parse(data);
                                                                if($.trim(msg)) {
                                                                    var msg2 = msg.map(function(val, i) {
                                                                        return val.no+'. '+val.jenis+' pada '+val.nama_bank+' dengan nomor rekening '+val.norek+' atas nama '+val.atas_nama;
                                                                    }).join('\n')
                                                                    var msg2html = msg.map(function(val, i) {
                                                                        return '<b>'+val.no+'. '+val.jenis+'</b> pada <b>'+val.nama_bank+'</b> dengan nomor rekening <b>'+val.norek+'</b> atas nama <b>'+val.atas_nama+'</b>';
                                                                    }).join('\n')
                                                                    // $(".kas").val(msg1+msg2);
                                                                    // $("textarea[name='VER[MSG][KAS]']").val(msg1+msg2);
                                                                    // $(".kasFinal").html(msg1+msg2html);
                                                                } else {
                                                                    false;
                                                                }
                                                            })
                                                        } else {
                                                            $('.kas').val('');
                                                            $("textarea[name='VER[MSG][KAS]']").val('');
                                                            $(".kasFinal").html('');
                                                        }
                                                    } else {
                                                        false;
                                                    }
                                                });
                                                $('input:radio[name="suratkuasamengumumkan"]').change(function(){
                                                    var status = <?php echo $LHKPN->STATUS; ?>;
                                                    var alasan = <?php echo empty($LHKPN->ALASAN) ? 0 : $LHKPN->ALASAN; ?>;
                                                    if (status == '1' && alasan == '0') {
                                                        if (this.checked && this.value == '0') {
                                                            var id = $('input[name="ID_LHKPN"]').val();
                                                            var msg1 = 'Mohon dikirimkan ke KPK Lampiran IV Surat Kuasa yang dicetak dan ditandatangan diatas meterai Rp. 10.000 atas nama:\n';
                                                            var msg3 = '\n\n(Jika sudah mengirimkan Surat Kuasa, maka koreksi atas Surat Kuasa ini dapat diabaikan, jika ada kesulitan silakan konfirmasi ke nomor telepon Call Center LHKPN 198)';
                                                            $.get('index.php/ever/verification/skuncomplete/'+id,function(data){
                                                                var msg = JSON.parse(data);
                                                                if($.trim(msg)) {
                                                                    var msg2 = msg.map(function(val, i) {
                                                                        return val.no+'. '+val.name;
                                                                    }).join('\n')
                                                                    var msg2html = msg.map(function(val, i) {
                                                                        return '<b>'+val.no+'. '+val.name+'</b>';
                                                                    }).join('\n')
                                                                    $(".skm").val(msg1+msg2+msg3);
                                                                    $("textarea[name='VER[MSG][SURATKUASAMENGUMUMKAN]']").val(msg1+msg2+msg3);
                                                                    $(".skmFinal").html(msg1+msg2html+msg3);
                                                                } else {
                                                                    false;
                                                                }
                                                            })
                                                        } else {
                                                            $('.skm').val('');
                                                            $("textarea[name='VER[MSG][SURATKUASAMENGUMUMKAN]']").val('');
                                                            $(".skmFinal").html('');
                                                        }
                                                    } else {
                                                        false;
                                                    }
                                                });
                                                $('#btnFinal').prop('disabled', true);
                                                $('input[name="final"]').prop('disabled', true);
                                                $('.navTab').tooltip();

                                                $('#btnPreviewMSG_VERIFIKASI').click(function() {
                                                    var url = 'index.php/ever/verification/previewmsg';
                                                    var msg = CKEDITOR.instances['MSG_VERIFIKASI'].getData(true);
//                              		    var msg = $('#MSG_VERIFIKASI').val();
                                                    var id = $('#ID_LHKPN_TRUE').val();
                                                    var tgl = $('#tgl_ver_').val();
                                                    var entry_via = $('#entry_via_').val();
                                                    var html = '<iframe src="" width="100%" height="'
                                                            + ($(window).height() - 140 + 'px')
                                                            + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
                                                    var f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
//                                                    OpenModalBox('Preview', html, f_close, 'large');
                                                    $("<form action='" + url + "' method='post' target='iframeCetak'></form>")
                                                            .append($("<input type='hidden' name='msg' />").attr('value', msg))
                                                            .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                                                            .append($("<input type='hidden' name='verif' />").attr('value', 'perbaikan'))
                                                            .append($("<input type='hidden' name='tgl_ver' />").attr('value', tgl))
                                                            .append($("<input type='hidden' name='entry_via' />").attr('value', entry_via))
                                                            .appendTo('body')
                                                            .submit()
                                                            .remove();
                                                    return false;
                                                });

                                                $('#btnPreviewMSG_VERIFIKASI_INSTANSI').click(function() {
                                                    url = 'index.php/ever/verification/previewmsg';
                                                    msg = $('#MSG_VERIFIKASI_INSTANSI').val();
                                                    id = $('#ID_LHKPN_TRUE').val();
                                                    html = '<iframe src="" width="100%" height="'
                                                            + ($(window).height() - 140 + 'px')
                                                            + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
                                                    f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
//                                                    OpenModalBox('Preview', html, f_close, 'large');
                                                    $("<form action='" + url + "' method='post' target='iframeCetak'></form>")
                                                            .append($("<input type='hidden' name='msg' />").attr('value', msg))
                                                            .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                                                            .append($("<input type='hidden' name='verif' />").attr('value', 'kekurangan'))
                                                            .appendTo('body')
                                                            .submit()
                                                            .remove();
                                                    return false;
                                                });
                                                
                                                $('#btnPreviewMSG_VERIFIKASI_TRUE').click(function() {
                                                    url = 'index.php/ever/verification/previewmsg';
                                                    msg = $('#MSG_VERIFIKASI_TRUE').val();
                                                    id = $('#ID_LHKPN_TRUE').val();
                                                    html = '<iframe src="" width="100%" height="'
                                                            + ($(window).height() - 140 + 'px')
                                                            + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
                                                    f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
//                                                    OpenModalBox('Preview', html, f_close, 'large');
                                                    $("<form action='" + url + "' method='post' target='iframeCetak'></form>")
                                                            .append($("<input type='hidden' name='msg' />").attr('value', msg))
                                                            .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                                                            .append($("<input type='hidden' name='verif' />").attr('value', 'lengkap'))
                                                            .appendTo('body')
                                                            .submit()
                                                            .remove();
                                                    return false;
                                                });
                                                
                                                $('#btnPreviewMSG_VERIFIKASI_TIDAK_LENGKAP').click(function() {
                                                    url = 'index.php/ever/verification/previewmsg';
                                                    msg = $('#MSG_VERIFIKASI_TIDAK_LENGKAP').val();
                                                    id = $('#ID_LHKPN_TRUE').val();
                                                    html = '<iframe src="" width="100%" height="'
                                                            + ($(window).height() - 140 + 'px')
                                                            + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
                                                    f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
//                                                    OpenModalBox('Preview', html, f_close, 'large');
                                                    $("<form action='" + url + "' method='post' target='iframeCetak'></form>")
                                                            .append($("<input type='hidden' name='msg' />").attr('value', msg))
                                                            .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                                                            .append($("<input type='hidden' name='verif' />").attr('value', 'tidaklengkap'))
                                                            .appendTo('body')
                                                            .submit()
                                                            .remove();
                                                    return false;
                                                });
                                                
                                                $('#btnPreviewMSG_VERIFIKASI_DITOLAK').click(function() {
                                                    url = 'index.php/ever/verification/previewmsg';
                                                    msg = $('#MSG_VERIFIKASI_DITOLAK').val();
                                                    id = $('#ID_LHKPN_TRUE').val();
                                                    tgl = $('#tgl_ver').val();
                                                    html = '<iframe src="" width="100%" height="'
                                                            + ($(window).height() - 140 + 'px')
                                                            + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
                                                    f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
//                                                    OpenModalBox('Preview', html, f_close, 'large');
                                                    $("<form action='" + url + "' method='post' target='iframeCetak'></form>")
                                                            .append($("<input type='hidden' name='msg' />").attr('value', msg))
                                                            .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                                                            .append($("<input type='hidden' name='verif' />").attr('value', 'ditolak'))
                                                            .append($("<input type='hidden' name='tgl_ver' />").attr('value', tgl))
                                                            .appendTo('body')
                                                            .submit()
                                                            .remove();
                                                    return false;
                                                });

                                                $('.btnCancel').click(function() {
                                                    url = 'index.php/ever/verification/index/lhkpn';
                                                    window.location.hash = url;
                                                    ng.LoadAjaxContent(url);
                                                    return false;
                                                });

                                                /* return to validation */
                                                $('#btnKembalikan').click(function() {
                                                    var id = $('input[name="ID_LHKPN"]').val();
                                                    var pesan = $('#PESAN_KEMBALIKAN').val();
                                                    if (pesan == '') {
                                                        alert('Pesan tidak boleh kosong');
                                                    }
                                                    else{
                                                        confirm("Anda akan mengembalikan ke penerimaan e-Filing", function () {
                                                            $('#loader_area').show();
                                                            $.ajax({
                                                                url: 'index.php/ever/verification/return_to_validation',
                                                                method: "POST",
                                                                data: {id: id, pesan: pesan},
                                                                dataType: "html",
                                                                success: function(res) {
                                                                    if (res == 1) {
                                                                        alertify.success('Data Berhasil Dikembalikan');
                                                                    }
                                                                    else{
                                                                        alertify.error('Data Gagal Dikembalikan');
                                                                    }
                                                                    $('#loader_area').hide();
                                                                    var link = 'index.php/ever/verification/index/lhkpn/';
                                                                    ng.LoadAjaxContent(link);
                                                                }
                                                            });
                                                        }, "Konfirmasi Pengembalian", undefined, "YA", "TIDAK");
                                                    }
                                                });

                                                $('.btnNext').click(function() {
                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                                });

                                                $('.btnPrevious').click(function() {
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                });

                                                $('.harta').click(function(e) {
                                                    e.preventDefault();
                                                    $('#harta > .nav-tabs').find('a[href="#hartatidakbergerak"]').trigger('click');
                                                });

                                                $('.penerimaankas').click(function(e) {
                                                    e.preventDefault();
                                                    $('#penerimaankas > .contentTab > #wrapperPenerimaan > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
                                                });

                                                $('.pengeluarankas').click(function(e) {
                                                    e.preventDefault();
                                                    $('#pengeluarankas > .contentTab > #wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3A"]').trigger('click');
                                                });

                                                $('.btnNextPribadi').click(function(e) {
                                                    e.preventDefault();
                                                    var val = $('input[name="datapribadi"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_datapribadi');
                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnNextJabatan').click(function(e) {
                                                    e.preventDefault();
                                                    var val = $('input[name="jabatan"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_jabatan');
                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousJabatan').click(function(e) {
                                                    e.preventDefault();
                                                    var val = $('input[name="jabatan"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_jabatan');
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnNextKeluarga').click(function(e) {
                                                    e.preventDefault();
                                                    var val = $('input[name="keluarga"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_keluarga');
                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                                    $('#harta > .nav-tabs').find('a[href="#hartatidakbergerak"]').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousKeluarga').click(function(e) {
                                                    e.preventDefault();
                                                    var val = $('input[name="keluarga"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_keluarga');
                                                    // var valkel = $('input[name="keluarga"]:checked').val();
                                                    // modifCKEDITOR(valkel, 'ck_keluarga');

                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousHartaTidakBergerak').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnNextHarta').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);

                                                    $('#harta > .nav-tabs > .active').next('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousHarta').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('#harta > .nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnNextHutang').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    // alert(clas);
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                                    $('#penerimaankas > #wrapperPenerimaan > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
                         
                                                    checkValidasiVerif();
                                                });

                                                $('.btnNextFasilitas').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                                    $('#lampiran > .nav-tabs').find('a[href="#pelepasanharta"]').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousFasilitas').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    $('#pengeluarankas > .contentTab > #wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3C"]').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousPelepasanHarta').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnNextLampiran').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('#lampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousLampiran').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('#lampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });
                                                
                                                $('.btnPreviousReviewHarta').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    $('#lampiran > .nav-tabs').find('a[href="#penerimaanhibah"]').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnPreviousSuratKuasa').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.btnNextSuratKuasa').click(function(e) {
                                                    e.preventDefault();
                                                    var clas = $(this).parent().parent().get(0).id;
                                                    var val = $('input[name="' + clas + '"]:checked').val();
                                                    modifCKEDITOR(val, 'ck_' + clas);
                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                                                    
                                                    checkValidasiVerif();
                                                });

//                                                $('.btnNextDokumenPendukung').click(function(e) {
//                                                    e.preventDefault();
//                                                    var clas = $(this).parent().parent().get(0).id;
//                                                    var val = $('input[name="' + clas + '"]:checked').val();
//                                                    modifCKEDITOR(val, 'ck_' + clas);
//                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
//                                                });

                                                $('.btnPreviousFInal').click(function() {
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
//                                                    $('#lampiran > .nav-tabs').find('a[href="#dokumenpendukung"]').trigger('click');
                                                    $('#lampiran > .nav-tabs').find('a[href="#lampiran"]').trigger('click');
                                                    checkValidasiVerif();
                                                });

                                                $('.final > .btnPrevious').click(function() {
                                                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                                                });

    <?php if (@$hasilVerifikasiitem->VAL->DATAPRIBADI == '-1') { ?>
                                                    $('.navTabPribadi').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->JABATAN == '-1') { ?>
                                                    $('.navTabJabatan').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->KELUARGA == '-1') { ?>
                                                    $('.navTabKeluarga').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->HARTATIDAKBERGERAK == '-1') { ?>
                                                    $('.navTabHarta').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabHartaTidakBergerak').css({'background': 'red', 'color': 'white'});

    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->HARTABERGERAK == '-1') { ?>
                                                    $('.navTabHarta').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabHartaBergerak').css({'background': 'red', 'color': 'white'});

    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->HARTABERGERAK2 == '-1') { ?>
                                                    $('.navTabHarta').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabHartaBergerak2').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->SURATBERHARGA == '-1') { ?>
                                                    $('.navTabHarta').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabHartaSurat').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->KAS == '-1') { ?>
                                                    $('.navTabHarta').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabHartaKas').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->HARTALAINNYA == '-1') { ?>
                                                    $('.navTabHarta').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabHartaLainnya').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->HUTANG == '-1') { ?>
                                                    $('.navTabHarta').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabHartaHutang').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->PENERIMAANKAS == '-1') { ?>
                                                    $('.navTabPenerimaan').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->PENGELUARANKAS == '-1') { ?>
                                                    $('.navTabPengeluaran').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->PENERIMAANFASILITAS == '-1') { ?>
                                                    $('.navTabFasilitas').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->PELEPASANHARTA == '-1') { ?>
                                                    $('.navTabLampiran').css({'background': 'red', 'color': 'white'});
                                                    // $('.navTabReview').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabPelepasan').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->PENERIMAANHIBAH == '-1') { ?>
                                                    $('.navTabLampiran').css({'background': 'red', 'color': 'white'});
                                                    // $('.navTabReview').css({'background': 'red', 'color': 'white'});
                                                    $('.navTabPenerimaanHibah').css({'background': 'red', 'color': 'white'});
    <?php } ?>
    <?php if (@$hasilVerifikasiitem->VAL->SURATKUASAMENGUMUMKAN == '-1') { ?>
                                                    $('.navTabSuratKuasa').css({'background': 'red', 'color': 'white'});
                                                    // $('.navTabReview').css({'background': 'red', 'color': 'white'});
    <?php } ?>    
    <?php  foreach ($hasilAI as $key => $outlierAI) {  ?>
    <?php $jsons = json_decode($outlierAI); if ($jsons->manual_verification == true) { ?>
                                                    $('.navTabOutlier').css({'background': 'red ', 'color': 'white'});
                                                    // $('.navTabReview').css({'background': 'red', 'color': 'white'});
    <?php } ?>    
    <?php } ?>  
    <?php // if (@$hasilVerifikasiitem->VAL->DOKUMENPENDUKUNG == '-1') { ?>
//                                                    $('.navTabReview').css({'background': 'red', 'color': 'white'});
//                                                    $('.navTabDokumenPendukung').css({'background': 'red', 'color': 'white'});
    <?php // } ?>

                                                $('.msgVer').change(function() {
                                                    var v = $(this).val();
                                                    var t = $(this).parents('.tab-pane').attr('id');
                                                    var f = t + 'Final';
                                                    // $('#'+f).html('Hallo'+t);
                                                    $('#' + f).find('.msgVerFinalTXT').html(v);
                                                    $('#' + f).find('.msgVerFinal').val(v);
                                                }).keydown(function(e){
                                                    if (e.keyCode == 65 && e.ctrlKey) {
                                                        e.target.select()
                                                    }
                                                });


                                                //TODO: buat fungsi recek semua, dan buat hasil nya di tab final verifikasi
                                                // tiap kali modal on close, jalankan recek
                                                // jika sudah di verify, ga bisa diubah
                                                // ubah modal tab jdi 1 saja

                                                //var abc = $('#kel-7 > i').attr('class');
                                                var abc = $('#wrapperHartaTidakBergerak > table > tr');
                                                /*if (abc.indexOf('check-square') >= 0) {
                                                 abc = 'benar';
                                                 } else {
                                                 abc = 'salah';
                                                 }
                                                 console.log(abc);*/

                                                //$("#MSG_VERIFIKASI").wysihtml5();
                                                //$("#MSG_VERIFIKASI_INSTANSI").wysihtml5();
                                                //$('#MSG_VERIFIKASI').data("wysihtml5").editor.setValue('ya <strong>isinya</strong><br>bawah');

                                                // ng.formProcess($("#ajaxFormFinal"), 'insert', 'index.php/ever/verification/index/lhkpn/');
//                                                $('.checkboxVer').click(function() {
                                                    $('.btn-save-data').click(function() {
    //                                                    $('#mail_true').hide();
    //                                                    $('#mail_false').hide();
    //                                                    $('#mail_ditolak').hide();
    //                                                    $('#mail_tidak_lengkap').hide();

//                                                            f_checkboxVer(this);

                                                        $('#loader_area').show();
                                                        var id = $(this).attr('id');
                                                        var url = $('#ajaxFormFinal').attr('action');
                                                        var data = $('#ajaxFormFinal').serializeArray();

                                                        var data2 = {};
                                                        data.push({name: 'simpan', value: 'draft'});
                                                        $.ajax({
                                                            url: url,
                                                            method: "POST",
                                                            data: data,
                                                            dataType: "html",
                                                            success: function(res) {
                                                                $('#loader_area').hide();
                                                                if (id == 'btnDraft') {
                                                                    var link = 'index.php/ever/verification/index/lhkpn/';
                                                                    ng.LoadAjaxContent(link);
                                                                }
                                                            }
                                                        });

                                                        return false;

                                                    });
//                                                });
                                                //check apakah ada yg belum di verifikasi ?
                                                checkValidasiVerif();

                                                if ($('input[name=final]:checked', '#ajaxFormFinal').val() == undefined){
                                                    $('#btnFinal').prop('disabled', true);
                                                }
                                                
                                                $('#ajaxFormFinal input').on('change', function() {
                                                    $('#btnFinal').prop('disabled', false);
                                                 });
                                                
                                                $('#ajaxFormFinal').submit(function() {
                                                    $('#btnFinal').prop('disabled', true);
                                                    $('#loader_area').show();
                                                    var id = $('input[name="ID_LHKPN"]').val();
                                                    var tgl_ver = $('#tgl_ver').val();
                                                    var entry_via = $('#entry_via_').val();
                                                    var url = $(this).attr('action');
                                                    var data = $(this).serializeArray();
                                                    var data2 = $('.valVerFinal').serializeArray();
                                                    var vfinal = $('input[name="final"]:checked').val();
                                                    var status = '0';
                                                    var MSG_VERIFIKASI_INSTANSI = $('#MSG_VERIFIKASI_INSTANSI').val();
                                                    var msg = {
                                                                success: 'Data Berhasil Disimpan!',
                                                                error: 'Data Gagal Disimpan, Ada item yang belum diverifikasi!'
                                                            };
                                                    $.each(data2, function(i, item) {
                                                        if (item.value == '') {
                                                            status = '1';
                                                            alertify.error(msg.error);
                                                            return false;
                                                        }
                                                    });

                                                    if (status == '0') {
                                                        $.ajax({
                                                            url: url,
                                                            method: "POST",
                                                            data: data,
                                                            dataType: "html",
                                                            success: function(res) {

                                                                if (res == '01') {
                                                                    if (vfinal == '1'){
                                                                        swal("LHKPN Terverifikasi Lengkap !", "Surat Tanda Terima (softcopy) akan dikirim melalui email ke <?= $tampil2; ?>", "success");
                                                                    }else if (vfinal == '3'){
                                                                        swal("LHKPN Terverifikasi Tidak Lengkap !", "Surat Tanda Terima (softcopy) akan dikirim melalui email ke <?= $tampil2; ?>", "success");
                                                                    }else{
                                                                        "";
                                                                    }    
//                                                                    (vfinal == '1') ? swal("LHKPN Terverifikasi Lengkap !", "Surat Tanda Terima (softcopy) akan dikirim melalui email ke <?= $tampil2; ?>", "success") : '';
//                                                                    (vfinal == '5') ? swal("LHKPN Terverifikasi Tidak Lengkap !", "Surat Tanda Terima (softcopy) akan dikirim melalui email ke <?= $tampil2; ?>", "success") : '';
                                                                    msg = {
                                                                        success: 'Data Berhasil Disimpan!',
                                                                        error: 'Data Gagal Disimpan!'
                                                                    };
                                                                    if (data == 0) {
                                                                        alertify.error(msg.error);
                                                                    } else {
                                                                        alertify.success(msg.success);
                                                                    }

                                                                    var link = 'index.php/ever/verification/index/lhkpn/';
                                                                    $.get(link, function(data) {
                                                                        ng.LoadAjaxContent(link);
                                                                    }).done(function() {
                                                                        $.ajax({
                                                                            url: 'index.php/ever/verification/pesan_pdf/',
                                                                            method: 'POST',
                                                                            data: {id_lhkpn: id, MSG_VERIFIKASI_INSTANSI: MSG_VERIFIKASI_INSTANSI, tgl_ver: tgl_ver, entry_via: entry_via},
                                                                            dataType: 'html',
                                                                            success: function(res) {
                                                                                msg = {
                                                                                    success: 'Pesan dan email berhasil dikirim !',
                                                                                    error: 'Pesan dan email gagal dikirim !'
                                                                                };
                                                                                if (res == 0) {
                                                                                    alertify.error(msg.error);
                                                                                } else {
                                                                                    alertify.success(msg.success);
                                                                                }
                                                                            }
                                                                        });
                                                                    });

//                                                                } else if (res == '11') {
//                                                                    swal("Jabatan PN/WL yang dilaporkan lebih dari 1", "mohon dipilih dahulu mana yang jabatan primary untuk kebutuhan laporan !", "error");
                                                                } else {
                                                                    // return false;
                                                                }
                                                                $('#loader_area').hide();
                                                            }
                                                        });
                                                    } else {
                                                        $('#loader_area').hide();
                                                        swal("Data Belum Terverifikasi", "Mohon untuk memeriksa semua data !", "error");
                                                    }

                                                    return false;
                                                });

                                                $('.ckeditor').ckeditor();
                                                $('#hasil-verifikasi').click(verifAll.yesToAll);
                                            });

                                                        </script>

                                                        <style type="text/css">
                                                            .nav-tabs > li.active > a,
                                                            .nav-tabs > li.active > a:hover,
                                                            .nav-tabs > li.active > a:focus{
                                                                color: #fff;
                                                                background-color: #3C8DBC !important;  
                                                            } 
                                                            .headerVerFinal{
                                                                font-weight: bold;
                                                            }
                                                        </style>

                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($display == 'history') {
                                                        // echo 'Halaman History LHKPN yang sedang dikerjakan';
                                                        // display($VERIFICATIONS);
                                                        ?>

                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Tanggal</th>
                                                                    <th>Oleh</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 0;
                                                                foreach ($VERIFICATIONS as $verificaton) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= ++$i ?>.</td>
                                                                        <td><?php echo date('d/m/Y', strtotime($verificaton->TANGGAL)); ?></td>
                                                                        <td><?php echo $verificaton->CREATED_BY; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3">

                                                                            <table class="table table-bordered table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="100">Item Divalidasi</th>
                                                                                        <th width="10">Hasil</th>
                                                                                        <th>Catatan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $hasil = json_decode($verificaton->HASIL_VERIFIKASI);
                                                                                    foreach ($hasil->VAL as $key => $value) {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?php echo $key; ?></td>
                                                                                            <td align="center"><?php
                                                                                                if (@$value == 1) {
                                                                                                    echo '<i class="fa fa-check-square" style="cursor: pointer; color: blue;"></i>';
                                                                                                } else {
                                                                                                    echo '<i class="fa fa-minus-square" style="cursor: pointer; color: red;"></i>';
                                                                                                }
                                                                                                ?>      
                                                                                            </td>
                                                                                            <td><?php echo $hasil->MSG->$key; ?></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>

                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                Masuk Tanggal
                                Verifikasi 1 tgl Kirim Pemberitahuan(msgbox);
                                Submit Perbaikan tgl
                                Verifikasi 2 tgl Kirim Pemberitahuan(msgbox);
                                Submit Perbaikan tgl
                                Selesai dan Verified tgl


                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($display == 'cetaksurat') {
                                                        ?>
                                                        <!-- Nav tabs -->
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li role="presentation" class="active"><a href="#emailpn" aria-controls="emailpn" role="tab" data-toggle="tab" class="navTab" title="Email PN"><i class="fa fa-envelope"></i> <span>Surat</span></a></li>
                                                            <li role="presentation" class=""><a href="#emailinstansi" aria-controls="emailinstansi" role="tab" data-toggle="tab" class="navTab" title="Email Instansi"><i class="fa fa-envelope"></i> <span>Draft lampiran</span></a></li>
                                                        </ul>
                                                        <!-- Tab panes -->
                                                        <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                                            <!--  -->
                                                            <div role="tabpanel" class="tab-pane active" id="emailpn">
                                                                <iframe style="font-family: Arial;" src="index.php/ever/verification/display/lhkpn/<?php echo $id; ?>/suratpn" width="100%" height="500px"></iframe>
                                                                <div class="clearfix"></div>
                                                            </div>    
                                                            <!--  -->
                                                            <div role="tabpanel" class="tab-pane" id="emailinstansi">
                                                                <iframe style="font-family: Arial;" src="index.php/ever/verification/display/lhkpn/<?php echo $id; ?>/suratinstansi" width="100%" height="500px "></iframe>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
//                                                    $Jenis = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'DOKUMENPENDUKUNG' => 'Dokumen Pendukung', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];
                                                    $Jenis = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];
                                                    if ($display == 'table') {
                                                        ?>
                                                        <?php
                                                        foreach ($tmpData->VAL as $key => $val) {
                                                            if ($val == '-1') {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $Jenis[$key] ?></td>
                                                                    <td><?php echo @$tmpData->MSG->$key ?> 
                                                                        <?php echo @implode('<br> - ', $veritemnoktext[strtoupper($key)]); ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    <?php } ?>
                                                    <?php
// MSG_VERIFIKASI
// MSG_VERIFIKASI_INSTANSI
                                                    if ($display == 'suratpn') {
                                                        $CI = & get_instance();
//                                                        $CI->load->library('pdf');

                                                        $html = $VERIFICATIONS[0]->MSG_VERIFIKASI;

//                                                        $pdf = $CI->pdf->load();
//                                                        $pdf->SetFont('Arial', 'B', 11);
//                                                        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
//                                                        $pdf->WriteHTML($html); // write the HTML into the PDF
                                                        // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
//                                                        $pdf->Output();
                                                        ob_start();
                                                        try {
                                                                include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
                                                                $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
                                                                $pdf->SetFont('dejavusans', '', 9);
//                                                                $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822));
                                                                $pdf->AddPage();
                                                                $pdf->writeHTML($html, true, false, true, false, '');
                                                                $pdf->lastPage();
                                                                $pdf->Output('cetak.pdf', 'I');
//                                                                $pdf->Output();
                                                            } catch (Exception $e) {}
                                                            ob_end_flush();
                                                        ?>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($display == 'suratinstansi') {
                                                        $CI = & get_instance();
//                                                        $CI->load->library('pdf');

                                                        $html = $VERIFICATIONS[0]->MSG_VERIFIKASI_INSTANSI;
                                                        // var_dump($html);

//                                                        $pdf = $CI->pdf->load();
//                                                        $pdf->SetFont('Arial', 'B', 11);
//                                                        $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
//                                                        $pdf->WriteHTML($html); // write the HTML into the PDF
                                                        // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
//                                                        $pdf->Output();
                                                        
                                                        ob_start();
                                                        try {
                                                                include_once APPPATH . 'third_party/TCPDF/tcpdf.php';
                                                                $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
                                                                $pdf->SetFont('dejavusans', '', 9);
//                                                                $pdf->SetFooter($_SERVER['HTTP_HOST'] . '|{PAGENO}|' . date(DATE_RFC822));
                                                                $pdf->AddPage();
                                                                $pdf->writeHTML($html, true, false, true, false, '');
                                                                $pdf->lastPage();
                                                                $pdf->Output('cetak.pdf', 'I');
//                                                                $pdf->Output();
                                                            } catch (Exception $e) {}
                                                            ob_end_flush();
                                                        ?>
                                                        <?php
                                                    }
                                                    ?>