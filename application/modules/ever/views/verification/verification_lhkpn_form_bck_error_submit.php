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
    } else {
        $stat = false;
    }
    // display($tmpData);
    // display($item);
    // display($LHKPN);
    // display($DATA_PRIBADI);
    $pribadis = $DATA_PRIBADI;
    // display($KELUARGAS);
    // display($HARTA_TIDAK_BERGERAKS);
    // display($HARTA_BERGERAKS);
    // display($HARTA_BERGERAK_LAINS);
    // display($HARTA_SURAT_BERHARGAS);
    // display($HARTA_KASS);
    // display($HARTA_LAINNYAS);
    // display($HUTANGS);
    // display($PENERIMAAN_KASS);
    // display($PENGELUARAN_KASS);
    ?>
    <!-- bootstrap wysihtml5 - text editor -->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"> -->
    <!-- CKEDITOR -->
    <style>
        #subtab{
            background-color: #FFFFFF; background-image: linear-gradient(-45deg, #F6F6F6 25%, transparent 15%, transparent 50%, #F6F6F6 50%, #F6F6F6 75%, transparent 75%, transparent); background-size: 12px 12px;
        }
    </style>
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/ckeditor/contents.css" type="text/css"/>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/adapters/jquery.js"></script>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa <?php echo $icon; ?>"></i> <?php echo $title; ?>
            <small><?php //echo $title;                       ?></small>
        </h1>
        <?php echo $breadcrumb; ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box" style="padding: 10px;">
    <!--                 <pre>
                    Nama PN : <?php echo $LHKPN->NAMA; ?><br>
                    Jabatan : <?php echo $LHKPN->JABATAN; ?><br>
                    Laporan : <?php echo $LHKPN->JENIS_LAPORAN; ?> <?php echo $LHKPN->TGL_LAPOR; ?><br>
                    Tanggal Submit : <?php echo $LHKPN->SUBMITED_DATE; ?><br>
                    </pre> -->
                    <label><input type="checkbox" onclick="if ($(this).is(':checked')) {
                                $('.navTab span').show();
                                } else {
                                $('.navTab span').hide();
                                }" checked=""> Tampilkan Tab Label</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><?php echo $tampil; ?></label>
                    <br>
                    <label>Legenda : 
                        <i class="fa fa-check-square" style="cursor: pointer; color: blue;" title="Diterima"></i> Diterima
                        <i class="fa fa-minus-square" style="cursor: pointer; color: red;" title="Ditolak"></i> Ditolak
                    </label>
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <!-- <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li> -->
                            <li role="presentation" class="active"><a href="#datapribadi" aria-controls="datapribadi" role="tab" data-toggle="tab" class="navTab datapribadi navTabPribadi" title="Data Pribadi"><?php echo ICON_pribadi; ?> <span>Data Pribadi</span></a></li>
                            <li role="presentation"><a href="#jabatan" aria-controls="jabatan" role="tab" data-toggle="tab" class="navTab jabatan navTabJabatan" title="Data Jabatan"><?php echo ICON_jabatan; ?> <span>Jabatan</span></a></li>
                            <li role="presentation"><a href="#keluarga" aria-controls="keluarga" role="tab" data-toggle="tab" class="navTab keluarga navTabKeluarga" title="Data Keluarga"><?php echo ICON_keluarga; ?> <span>Keluarga</span></a></li>
                            <li role="presentation"><a href="#harta" aria-controls="harta" role="tabharta" data-toggle="tab" class="navTab harta navTabHarta" title="Data Harta"><?php echo ICON_harta; ?> <span>Harta</span></a></li>
                            <li role="presentation"><a href="#penerimaankas" aria-controls="penerimaankas" role="tab" data-toggle="tab" class="navTab penerimaankas navTabPenerimaan" title="Data Penerimaan Kas"><?php echo ICON_penerimaankas; ?> <span>Penerimaan</span></a></li>
                            <li role="presentation"><a href="#pengeluarankas" aria-controls="pengeluarankas" role="tab" data-toggle="tab" class="navTab pengeluarankas navTabPengeluaran" title="Data Pengeluaran Kas"><?php echo ICON_pengeluarankas; ?> <span>Pengeluaran</span></a></li>
                            <li role="presentation"><a href="#penerimaanfasilitas" aria-controls="penerimaanfasilitas" role="tab" data-toggle="tab" class="navTab penerimaanfasilitas navTabFasilitas" title="Penerimaan Fasilitas"><?php echo ICON_fasilitas; ?> <span>Fasilitas</span></a></li>
                            <li role="presentation"><a href="#lampiran" aria-controls="lampiran" role="tab" data-toggle="tab" class="navTab lampiran navTabReview" title="Data Lampiran Transaksi Pelepasan Harta, Penerimaan Fasilitas, Surat Kuasa, Dokumen Pendukung"><?php echo ICON_lampiran; ?> <span>Lampiran</span></a></li>
                            <li role="presentation"><a href="#final" aria-controls="final" role="tab" data-toggle="tab" class="navTab final" title="Hasil Verifikasi"><?php echo ICON_final; ?> <span>Final Verifikasi</span></a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                            <!--  -->
                            <div role="tabpanel" class="tab-pane active" id="datapribadi">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_pribadi.php'); ?>
                                </div>
                                Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->DATAPRIBADI == '1') ? 'checked' : ''; ?> name="datapribadi" class="checkboxVer" value="1"> Ya</label>
                                <label><input type="radio" <?php echo (@$tmpData->VAL->DATAPRIBADI == '-1') ? 'checked' : ''; ?> name="datapribadi" class="checkboxVer" value="0"> Tidak</label>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->DATAPRIBADI; ?></textarea>
                                </div>
                                <br>
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
                                Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->JABATAN == '1') ? 'checked' : ''; ?> name="jabatan" class="checkboxVer" value="1"> Ya</label>
                                <label><input type="radio" <?php echo (@$tmpData->VAL->JABATAN == '-1') ? 'checked' : ''; ?> name="jabatan" class="checkboxVer" value="0"> Tidak</label>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->JABATAN; ?></textarea>
                                </div>
                                <br>
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
                                Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->KELUARGA == '1') ? 'checked' : ''; ?> name="keluarga" class="checkboxVer" value="1"> Ya</label>
                                <label><input type="radio" <?php echo (@$tmpData->VAL->KELUARGA == '-1') ? 'checked' : ''; ?> name="keluarga" class="checkboxVer" value="0"> Tidak</label>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->KELUARGA; ?></textarea>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousKeluarga"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextKeluarga">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="harta">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li id="subtab" role="presentation" class="active"><a href="#hartatidakbergerak" aria-controls="hartatidakbergerak" role="tab" data-toggle="tab" class="navTab navTabHartaTidakBergerak" title="Tanah / Bangunan"><?php echo ICON_hartatidakbergerak; ?> <span>Tanah / Bangunan</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#hartabergerak" aria-controls="hartabergerak" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak" title="Alat Transportasi / Mesin"><?php echo ICON_hartabergerak; ?> <span>Mesin / Alat Transport</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#hartabergerakperabot" aria-controls="hartabergerakperabot" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak2" title="Perabot"><?php echo ICON_hartabergerakperabot; ?> <span>Bergerak</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#suratberharga" aria-controls="suratberharga" role="tab" data-toggle="tab" class="navTab navTabHartaSurat" title="Surat Berharga"><?php echo ICON_suratberharga; ?> <span>Surat Berharga</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#kas" aria-controls="kas" role="tab" data-toggle="tab" class="navTab navTabHartaKas" title="Kas / Setara Kas"><?php echo ICON_kas; ?> <span>KAS / Setara KAS</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#hartalainnya" aria-controls="hartalainnya" role="tab" data-toggle="tab" class="navTab navTabHartaLainnya" title="Harta Lainnya"><?php echo ICON_hartalainnya; ?> <span>Harta Lainnya</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#hutang" aria-controls="hutang" role="tab" data-toggle="tab" class="navTab navTabHartaHutang" title="Data Hutang"><?php echo ICON_hutang; ?> <span>Hutang</span></a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane active" id="hartatidakbergerak">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartatidakbergerak.php'); ?>
                                        </div>
                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTATIDAKBERGERAK == '1') ? 'checked' : ''; ?> name="hartatidakbergerak" id="hartatidakbergerakselectYes" class="checkboxVer hartatidakbergerakYes" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->HARTATIDAKBERGERAK == '-1') ? 'checked' : ''; ?> name="hartatidakbergerak" class="checkboxVer hartatidakbergerakNo" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->HARTATIDAKBERGERAK; ?></textarea>
                                        </div>
                                        <br>
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
                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK == '1') ? 'checked' : ''; ?> name="hartabergerak" class="checkboxVer hartabergerakYes" id="hartabergerakselectYes" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK == '-1') ? 'checked' : ''; ?> name="hartabergerak" class="checkboxVer hartabergerakNo" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->HARTABERGERAK; ?></textarea>
                                        </div>
                                        <br>
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
                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK2 == '1') ? 'checked' : ''; ?> name="hartabergerakperabot" class="checkboxVer hartabergerakperabotYes" id="hartabergerakperabotselectYes" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->HARTABERGERAK2 == '-1') ? 'checked' : ''; ?> name="hartabergerakperabot" class="checkboxVer hartabergerakperabotNo" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->HARTABERGERAK2; ?></textarea>
                                        </div>
                                        <br>
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
                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->SURATBERHARGA == '1') ? 'checked' : ''; ?> name="suratberharga" id="suratberhargaselectYes" class="checkboxVer suratberhargaYes" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->SURATBERHARGA == '-1') ? 'checked' : ''; ?> name="suratberharga" class="checkboxVer suratberhargaNo" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->SURATBERHARGA; ?></textarea>
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
                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->KAS == '1') ? 'checked' : ''; ?> name="kas" class="checkboxVer kasYes" id="KasselectYes" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->KAS == '-1') ? 'checked' : ''; ?> name="kas" class="checkboxVer kasNo" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->KAS; ?></textarea>
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

                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HARTALAINNYA == '1') ? 'checked' : ''; ?> name="hartalainnya" class="checkboxVer hartalainnyaYes" id="hartalainnyaselectYes" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->HARTALAINNYA == '-1') ? 'checked' : ''; ?> name="hartalainnya" class="checkboxVer hartalainnyaNo" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->HARTALAINNYA; ?></textarea>
                                        </div>
                                        <br>
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
                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->HUTANG == '1') ? 'checked' : ''; ?> name="hutang" class="checkboxVer hutangYes" id="hutangselectYes" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->HUTANG == '-1') ? 'checked' : ''; ?> name="hutang" class="checkboxVer hutangNo" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->HUTANG; ?></textarea>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextHutang">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div><!-- close Harta -->
                            <!--  -->

                            <div role="tabpanel" class="tab-pane" id="lampiran">
                                <?php //require_once('verification_tabel_lampiran.php');?>

                                <ul class="nav nav-tabs" role="tablist">
                                    <li id="subtab" role="presentation" class="active"><a href="#pelepasanharta" aria-controls="pelepasanharta" role="tab" data-toggle="tab" class="navTab navTabPelepasan" title="Pelepasan Harta"><?php echo ICON_pelepasanharta; ?> <span>Penjualan/Pelepasan Harta </span></a></li>
                                    <li id="subtab" role="presentation"><a href="#penerimaanhibah" aria-controls="penerimaanhibah" role="tab" data-toggle="tab" class="navTab navTabPenerimaanHibah" title="Penerimaan Hibah"><?php echo ICON_penerimaanhibah; ?> <span>Penerimaan Hibah</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#suratkuasamengumumkan" aria-controls="suratkuasamengumumkan" role="tab" data-toggle="tab" class="navTab navTabSuratKuasaMengumumkan" title="Surat Kuasa"><?php echo ICON_suratkuasamengumumkan; ?> <span>Surat Kuasa</span></a></li> 
                                    <!--<li id="subtab" role="presentation"><a href="#dokumenpendukung" aria-controls="dokumenpendukung" role="tab" data-toggle="tab" class="navTab navTabDokumenPendukung" title="Dokumen Pendukung"><?php echo ICON_dokumenpendukung; ?> <span>Dokumen Pendukung</span></a></li>-->
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane active" id="pelepasanharta">
                                        <div class="contentTab">
                                            <?php require_once('verification_table_lampiran_1.php'); ?>
                                        </div>

                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PELEPASANHARTA == '1') ? 'checked' : ''; ?> name="pelepasanharta" class="checkboxVer" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->PELEPASANHARTA == '-1') ? 'checked' : ''; ?> name="pelepasanharta" class="checkboxVer" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->PELEPASANHARTA; ?></textarea>
                                        </div>
                                        <br>
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

                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANHIBAH == '1') ? 'checked' : ''; ?> name="penerimaanhibah" class="checkboxVer" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANHIBAH == '-1') ? 'checked' : ''; ?> name="penerimaanhibah" class="checkboxVer" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->PENERIMAANHIBAH; ?></textarea>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousLampiran"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextLampiran">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="suratkuasamengumumkan">
                                        <div class="contentTab">
                                            <?php // require_once('verification_tabel_skm.php');?>
                                            <?php require_once('verification_table_surat_kuasa.php'); ?>
                                        </div>

                                        Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->SURATKUASAMENGUMUMKAN == '1') ? 'checked' : ''; ?> name="suratkuasamengumumkan" class="checkboxVer" value="1"> Ya</label>
                                        <label><input type="radio" <?php echo (@$tmpData->VAL->SURATKUASAMENGUMUMKAN == '-1') ? 'checked' : ''; ?> name="suratkuasamengumumkan" class="checkboxVer" value="0"> Tidak</label>
                                        <br>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->SURATKUASAMENGUMUMKAN; ?></textarea>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousLampiran"><i class="fa fa-backward"></i>Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextSuratKuasa">Simpan & Lanjut<i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div> 
                                    <!--                                    <div role="tabpanel" class="tab-pane" id="dokumenpendukung">
                                                                            <div class="contentTab">
                                    <?php // require_once('verification_table_dokumen_pendukung.php'); ?>
                                                                            </div>
                                    
                                                                            Terverfikasi ? : <label><input type="radio" <?php // echo (@$tmpData->VAL->DOKUMENPENDUKUNG == '1') ? 'checked' : '';          ?> name="dokumenpendukung" class="checkboxVer" value="1"> Ya</label>
                                                                            <label><input type="radio" <?php // echo (@$tmpData->VAL->DOKUMENPENDUKUNG == '-1') ? 'checked' : '';          ?> name="dokumenpendukung" class="checkboxVer" value="0"> Tidak</label>
                                                                            <br>
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <textarea class="msgVer" style="width: 100%;"><?php // echo @$tmpData->MSG->DOKUMENPENDUKUNG;          ?></textarea>
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
                                Terverfikasi ? : <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANFASILITAS == '1') ? 'checked' : ''; ?> name="penerimaanfasilitas" class="checkboxVer" value="1"> Ya</label>
                                <label><input type="radio" <?php echo (@$tmpData->VAL->PENERIMAANFASILITAS == '-1') ? 'checked' : ''; ?> name="penerimaanfasilitas" class="checkboxVer" value="0"> Tidak</label>
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea class="msgVer" style="width: 100%;"><?php echo @$tmpData->MSG->PENERIMAANFASILITAS; ?></textarea>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnPreviousFasilitas"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextFasilitas">Simpan & Lanjut <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
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
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->SURATBERHARGA)) ?></span>
                                                    <input type="hidden" name="VER[VAL][SURATBERHARGA]" class="valVerFinal" value="<?php echo @$tmpData->VAL->SURATBERHARGA; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][SURATBERHARGA]" class="msgVerFinal"><?php echo @$tmpData->MSG->SURATBERHARGA ?></textarea>
                                                </td>
                                                <!--<td><span class="headerVerFinal">&nbsp;&nbsp;&nbsp;> Wajib diisi (minimal satu jenis harta)</span></td>-->
                                            </tr>

                                            <tr id="kasFinal">
                                                <td><span class="headerVerFinal">Kas / Setara Kas</span></td>
                                                <td align="center"><span class="checkboxVerFinal"><i class="fa <?php echo (!empty($tmpData->VAL->KAS)) ? (($tmpData->VAL->KAS == '1') ? 'fa-check-square' : 'fa-minus-square') : ''; ?>" style="color: <?php echo (@$tmpData->VAL->KAS == '1') ? 'blue' : 'red'; ?>;"></i></span></td>
                                                <td><span class="msgVerFinalTXT"><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->KAS)) ?></span>
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
                                                <td><span class="msgVerFinalTXT"></span><?php echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->SURATKUASAMENGUMUMKAN)) ?></span>
                                                    <input type="hidden" name="VER[VAL][SURATKUASAMENGUMUMKAN]" class="valVerFinal" value="<?php echo @$tmpData->VAL->SURATKUASAMENGUMUMKAN; ?>">
                                                    <textarea style="display: none;" name="VER[MSG][SURATKUASAMENGUMUMKAN]" class="msgVerFinal"><?php echo @$tmpData->MSG->SURATKUASAMENGUMUMKAN ?></textarea>
                                                </td>
                                                <td><span class="headerVerFinal">Hardcopy wajib dikirim untuk laporan pertama kali</span></td>
                                            </tr> 

                                    <!--                                                                                                <tr id="dokumenpendukungFinal">
                                                                                                                                        <td><span class="headerVerFinal">Dokumen Pendukung</span></td>
                                                                                                                                        <td align="center"><span class="checkboxVerFinal"><i class="fa <?php // echo (!empty($tmpData->VAL->DOKUMENPENDUKUNG)) ? (($tmpData->VAL->DOKUMENPENDUKUNG == '1') ? 'fa-check-square' : 'fa-minus-square') : '';          ?>" style="color: <?php // echo (@$tmpData->VAL->DOKUMENPENDUKUNG == '1') ? 'blue' : 'red';          ?>;"></i></span></td>
                                                                                                                                        <td><span class="msgVerFinalTXT"><?php // echo @str_replace('\n', '<br/>', @str_replace('\r', '<br/>', @$tmpData->MSG->DOKUMENPENDUKUNG))          ?></span>
                                                                                                                                            <input type="hidden" name="VER[VAL][DOKUMENPENDUKUNG]" class="valVerFinal" value="<?php // echo @$tmpData->VAL->DOKUMENPENDUKUNG;          ?>">
                                                                                                                                            <textarea style="display: none;" name="VER[MSG][DOKUMENPENDUKUNG]" class="msgVerFinal"><?php // echo @$tmpData->MSG->DOKUMENPENDUKUNG          ?></textarea>
                                                                                                                                        </td>
                                                                                                                                        <td><span class="headerVerFinal">Hardcopy tidak wajib dikirim</span></td>
                                                                                                                                    </tr>-->

                                        </tbody>
                                    </table>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 modal-warning">
                                        <div class="modal-body col-md-12">
                                            <div class="col-md-2" align="right"><label>Hal Verifikasi Final : </label></div>
                                            <div class="col-md-4">
                                                <div><label><input type="radio" name="final" class="" value="1" onchange="$('#mail_false').hide();
                                                                    $('#mail_ditolak').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_true').show();" required> Terverifikasi Lengkap</label></div>
                                                    <?php if (($LHKPN->STATUS == '1' || $LHKPN->STATUS == '2') && ($LHKPN->ALASAN == '1' || $LHKPN->ALASAN == '2')) { ?>
                                                    <div><label><input type="radio" name="final" class="" value="3" onchange="$('#mail_false').hide();
                                                                        $('#mail_true').hide(); $('#mail_ditolak').hide(); $('#mail_tidak_lengkap').show();" required> Terverifikasi Tidak Lengkap</label></div>
                                                    <?php } ?>
                                                <div><label><input type="radio" name="final" class="" value="2" onchange=" <?php if (($LHKPN->STATUS == '1' || $LHKPN->STATUS == '2') && ($LHKPN->ALASAN == '1' || $LHKPN->ALASAN == '2')) { ?> $('#mail_false').hide();
                                                                        $('#mail_true').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_ditolak').show(); <?php } else { ?> $('#mail_false').show();
                                                                        $('#mail_true').hide(); $('#mail_tidak_lengkap').hide(); $('#mail_ditolak').hide(); reloadCatatan(<?php echo $ID_LHKPN; ?>); <?php } ?>" required><?php if (($LHKPN->STATUS == '1' || $LHKPN->STATUS == '2') && ($LHKPN->ALASAN == '1' || $LHKPN->ALASAN == '2')) { ?> Ditolak <?php } else { ?> Perlu Perbaikan <?php } ?></label></div>
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
                                    <div <?php // echo ($stat === true ? 'style="display: none;"' : '')                 ?> style="display: none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_false">
                                        <!--Email template untuk PN-->
                                        <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI">Preview</button>
                                        <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                        <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                        <?php
                                        $days = ($LHKPN->entry_via == '1') ? "30 days" : "14 days";
                                        $tgl_ver = date_create($LHKPN->TANGGAL);
                                        date_add($tgl_ver, date_interval_create_from_date_string($days));
                                        $tgl_ver = date_format($tgl_ver, 'd-m-Y');
                                        foreach ($JABATANS_P as $jabatan) {
                                            $jab = $jabatan->NAMA_JABATAN . ' - ' . $jabatan->UK_NAMA . ' - ' . $jabatan->INST_NAMA . '<br>';
                                        }
                                        if ($LHKPN->entry_via == '1') {
                                            $content = "Bersama ini disampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara $LHKPN->NAMA_LENGKAP yang perlu dilengkapi sebagaimana terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data disertai salinan surat ini  paling lambat tanggal $tgl_ver , dan menyampaikan ke Komisi Pemberantasan Korupsi";
                                        } else {
                                            $content = "Bersama ini kami sampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi ternyata masih terdapat kekurangan dalam LHKPN Saudara yang perlu dilengkapi sebagaimana daftar terlampir. Untuk pemrosesan lebih lanjut, Saudara diminta untuk melengkapi kekurangan data dan menyampaikan ke Komisi Pemberantasan Korupsi tidak melampaui tanggal $tgl_ver.";
                                        }
                                        $content_ditolak = "Bersama ini disampaikan bahwa LHKPN atas nama Saudara telah kami verifikasi, dari hasil verifikasi LHKPN Saudara $LHKPN->NAMA_LENGKAP dinyatakan ditolak, dikarenakan Saudara tidak melakukan perbaikan sampai batas waktu yang telah ditentukan.";
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
                                                                              <td colspan="4"><div align="justify">Apabila memerlukan informasi lebih lanjut, silakan menghubungi Direktorat Pendaftaran dan Pemeriksaan Laporan Harta Kekayaan Penyelenggara Negara pada telepon nomor 021-25578396. </div></td>
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
                                                                              <td colspan="4"><div align="justify">&copy; 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN (021) 2557 8396</div></td>
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
                                        <input type="hidden" id="tgl_ver_" value="<?php echo $tgl_ver; ?>">
                                        <textarea id="MSG_VERIFIKASI" name="MSG_VERIFIKASI" rows="10" style="width: 100%;" class="ckeditor" disabled="">
                                                                                                        <pre>
                                                                                                           <table style="width:100%" >
                                    <!--                                                                            <tr>
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
                                                                                                                  <td>Permintaan Perbaikan / Kelengkapan Data</td>
                                                                                                                  <td>&nbsp;</td>
                                                                                                                </tr>-->
                                                                                                                <tr>
                                                                                                                  <td colspan="4">Yth. Sdr. <?= $LHKPN->NAMA_LENGKAP; ?></td>
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
                                                                                                                  <td colspan="4"><?= $content; ?> <div align="justify"></div></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                  <td colspan="4"><div align="justify">Email pemberitahuan permintaan kelengkapan ini tidak dapat digunakan sebagai tanda terima LHKPN, tanda terima akan diberikan apabila Saudara telah melengkapi daftar permintaan kelengkapan dan telah diverifikasi oleh KPK. </div></td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                  <td colspan="4"><div align="justify">Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau telepon nomor 021-2557 8396. </div></td>
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
                                                                                                                          &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN (021) 2557 8396
                                                                                                                  </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                  <td>&nbsp;</td>
                                                                                                                  <td>&nbsp;</td>
                                                                                                                  <td>&nbsp;</td>
                                                                                                                  <td>&nbsp;</td>
                                                                                                                </tr>

                                                                                                            </table>
                                                                                                        </pre>
                                        </textarea>

                                        <br>
                                        <br>

                                        <div >   
                                            <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI_INSTANSI">Preview</button>
                                            <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                            <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                            <textarea id="MSG_VERIFIKASI_INSTANSI" name="MSG_VERIFIKASI_INSTANSI" rows="10" style="width: 100%;" class="ckeditor" disabled="">

                                                <?php
                                                $NAMA_JABATAN = '';
                                                foreach ($JABATANS as $jabatan) {
                                                    $NAMA_JABATAN = $jabatan->NAMA_JABATAN . '  ' . $jabatan->INST_NAMA;
                                                }
                                                ?>

                                                                                                            Daftar kekurangan kelengkapan yang harus diisi dan dilengkapi oleh Sdr. <?= $LHKPN->NAMA_LENGKAP . ', ' . $NAMA_JABATAN; ?> :
                                                                                                           <table id="tblInnerMessage" class="tb-1 tb-1a" border="1" cellspacing="0" cellpadding="0" style="height:100px; width:650px;">
                                                                                                                <thead>
                                                                                                                    <tr style="border: 1px solid black;">
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

                                    <!--yo add-->

                                    <div style="display:none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_true">
                                        Email template Setuju
                                        <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI_TRUE">Preview</button>
                                        <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                        <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                        <textarea id="MSG_VERIFIKASI_TRUE" name="MSG_VERIFIKASI_TRUE" rows="10" style="width: 100%;" class="ckeditor" disabled="">
                                                                                                                     <pre>
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
                                                                                                                           

                                                                                                   Bersama ini kami informasikan kepada Saudara, bahwa Laporan e-LHKPN yang Saudara kirim telah terverifikasi administratif dan dinyatakan lengkap dan siap untuk diumumkan, terlampir bukti Tanda Terima e-LHKPN Saudara sebagai bukti bahwa telah menyampaikan LHKPN ke KPK :

                                                                                            </pre>
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
                                                                                                                                                    <td><?= date('Y', strtotime($LHKPN->tgl_kirim_final)); ?></td>
                                                                                    																<td></td>
                                                                                                                                                </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                            <!--<pre>-->
                                                                    <p>&nbsp;Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau telepon nomor 021-2557 8396.
                                                                        <table><table>
                                                                                                             <tr>
                                                                                                                     <td>                                                      
                                                                                                            Terima kasih<br/>

                                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                                    --------------------------------------------------------------<br/>
                                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                                    © 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN (021) 2557 8396

                                                                                                                 </td>
                                                                                                            </tr>
                                                                                                            </table>
                                                                                                              <!--</pre>-->
                                                            </textarea>

                                                            </div>

                                                            <div style="display:none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_tidak_lengkap">
                                                                Email template Setuju
                                                                <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI_TIDAK_LENGKAP">Preview</button>
                                                                <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                                                <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                                                <textarea id="MSG_VERIFIKASI_TIDAK_LENGKAP" name="MSG_VERIFIKASI_TIDAK_LENGKAP" rows="10" style="width: 100%;" class="ckeditor">
                                                                                                                     <pre>
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

                                                                                            </pre>
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
                                                                                                                                                    <td><?= date('Y', strtotime($LHKPN->tgl_kirim_final)); ?></td>
                                                                                    																<td></td>
                                                                                                                                                </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                            <!--<pre>-->
                                                                        <p>&nbsp;Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau telepon nomor 021-2557 8396.
                                                                        <table><table>
                                                                                                             <tr>
                                                                                                                     <td>
                                                                                                            Terima kasih<br/>

                                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                                    --------------------------------------------------------------<br/>
                                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                                    © 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN (021) 2557 8396

                                                                                                                 </td>
                                                                                                            </tr>
                                                                                                            </table>
                                                                                                              <!--</pre>-->
                                                                                    </textarea>

                                                                                    </div>
                                                                                    <div style="display:none;" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-msg" id="mail_ditolak">
                                                                                        Email template Setuju
                                                                                        <button type="button" class="btn btn-default" id="btnPreviewMSG_VERIFIKASI_DITOLAK">Preview</button>
                                                                                        <button type="button" class="btn btn-warning" onClick="reloadCatatan(<?php echo $ID_LHKPN ?>)">Reload Catatan</button>
                                                                                        <input type="hidden" id="ID_LHKPN_TRUE" value="<?php echo $ID_LHKPN; ?>">
                                                                                        <input type="hidden" id="tgl_ver" value="<?php echo $tgl_ver; ?>">
                                                                                        <textarea id="MSG_VERIFIKASI_DITOLAK" name="MSG_VERIFIKASI_DITOLAK" rows="10" style="width: 100%;" class="ckeditor" disabled="">
                                                                                                                     <pre>
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
                                                                                            </pre>
                                                                                            <!--<pre>-->
                                                                        <p>Bersama ini kami sampaikan bahwa pelaporan LHKPN atas nama Saudara setelah dilakukan verifikasi administratif dinyatakan ditolak dikarenakan tidak memenuhi kriteria yang telah ditetapkan dalam pelaporan LHKPN.
                                                                        <p>Sehubungan dengan hal tersebut silakan mengisi dan menyampaikan LHKPN sesuai petunjuk pengisian kepada Komisi Pemberantasan Korupsi dalam waktu tidak melampaui tanggal <?php echo $tgl_ver; ?>.                        
                                                                        <p>Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id atau telepon nomor 021-2557 8396.
                                                                        <table><table>
                                                                                                             <tr>
                                                                                                                     <td>
                                                                                                            Terima kasih<br/>

                                                                    Direktorat Pendaftaran dan Pemeriksaan LHKPN<br/>
                                                                    --------------------------------------------------------------<br/>
                                                                    Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.
                                                                    © 2016 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN (021) 2557 8396

                                                                                                                 </td>
                                                                                                            </tr>
                                                                                                            </table>
                                                                                                              <!--</pre>-->
                                                                                                            </textarea>

                                                                                                            </div>

                                                                                                            <!--yo end-->

                                                                                                            <br>

                                                                                                            <input type="hidden" name="act" value="doverify">
                                                                                                            <input type="hidden" name="ID_LHKPN" value="<?php echo $LHKPN->ID_LHKPN; ?>">
                                                                                                            <div class="clearfix" style="margin-bottom: 20px;"></div>
                                                                                                            <div class="pull-right">
                                                                                                                <button type="button" class="btn btn-sm btn-warning btnPreviousFInal"><i class="fa fa-backward"></i> Sebelumnya</button>
                                                                                                                <button type="button" id="btnDraft" class="btn btn-sm btn-success btn-save-data"><i class="fa fa-save"></i> Simpan Draft</button>
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
                                                                                                            <script src="<?php echo base_url(); ?>plugins/sweet-alert/dist/sweetalert.min.js" type="text/javascript"></script>
                                                                                                            <script type="text/javascript">

                                                                                                    function modifCKEDITOR(val, cla) {
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
                                                                                            $.get('index.php/ever/verification/getTableCatatan/' + id, function (htmlA) {
                                                                                            var text = $.parseHTML(CKEDITOR.instances['MSG_VERIFIKASI_INSTANSI'].getData(true));
                                                                                                    var html = $('#conhidden');
                                                                                                    html.html(text);
                                                                                                    $('.body-table', html).html(htmlA);
                                                                                                    CKEDITOR.instances['MSG_VERIFIKASI_INSTANSI'].setData(html.html());
                                                                                            });
                                                                                            }

                                                                                            function hideLah() {
                                                                                            $('#mail_true').hide();
                                                                                                    $('#mail_false').hide();
                                                                                                    $('#mail_ditolak').hide();
                                                                                                    $('#mail_tidak_lengkap').hide();
                                                                                            }

                                                                                            var f_checkboxVer = function (ele) {
                                                                                            var v = $(ele).val();
                                                                                                    var t = $(ele).parents('.tab-pane').attr('id');
                                                                                                    var f = t + 'Final';
                                                                                                    if ($(ele).is(':checked') && $(ele).val() == 1) {
                                                                                            $('#' + f).find('.checkboxVerFinal').html('<i class="fa fa-check-square" style="color: blue;"></i>');
                                                                                                    $('#' + f).find('.valVerFinal').val('1');
                                                                                            } else {
                                                                                            $('#' + f).find('.checkboxVerFinal').html('<i class="fa fa-minus-square" style="color: red;"></i>');
                                                                                                    $('#' + f).find('.valVerFinal').val('-1');
                                                                                            }
                                                                                            };
                                                                                             $(document).ready(function () {
                                                                                            $('.checkboxVer').click(function () {
                                                                                            f_checkboxVer(this);
                                                                                            });
                                                                                            });





    $(document).ready(function () {
        $('#btnFinal').prop('disabled', true);
        $('input[name="final"]').prop('disabled', true);
        $('.navTab').tooltip();
        $('#btnPreviewMSG_VERIFIKASI').click(function () {
            url = 'index.php/ever/verification/previewmsg';
            msg = CKEDITOR.instances['MSG_VERIFIKASI'].getData(true);
            id = $('#ID_LHKPN_TRUE').val();
            tgl = $('#tgl_ver_').val();
            html = '<iframe src="" width="100%" height="'
                    + ($(window).height() - 140 + 'px')
                    + '" style="border:1px solid #cfcfcf;" name="iframeCetak"></iframe>';
            f_close = '<input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">';
            //                                                    OpenModalBox('Preview', html, f_close, 'large');
            $("<form action='" + url + "' method='post' target='iframeCetak'></form>")
                    .append($("<input type='hidden' name='msg' />").attr('value', msg))
                    .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                    .append($("<input type='hidden' name='verif' />").attr('value', 'perbaikan'))
                    .append($("<input type='hidden' name='tgl_ver' />").attr('value', tgl))
                    .appendTo('body')
                    .submit()
                    .remove();
            return false;
        });
        $('#btnPreviewMSG_VERIFIKASI_INSTANSI').click(function () {
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
        $('#btnPreviewMSG_VERIFIKASI_TRUE').click(function () {
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
        $('#btnPreviewMSG_VERIFIKASI_TIDAK_LENGKAP').click(function () {
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
        $('#btnPreviewMSG_VERIFIKASI_DITOLAK').click(function () {
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
        $('.btnCancel').click(function () {
            url = 'index.php/ever/verification/index/lhkpn';
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });
        $('.btnNext').click(function () {
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });
        $('.btnPrevious').click(function () {
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.harta').click(function (e) {
            e.preventDefault();
            $('#harta > .nav-tabs').find('a[href="#hartatidakbergerak"]').trigger('click');
        });
        $('.penerimaankas').click(function (e) {
            e.preventDefault();
            $('#penerimaankas > .contentTab > #wrapperPenerimaan > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
        });
        $('.pengeluarankas').click(function (e) {
            e.preventDefault();
            $('#pengeluarankas > .contentTab > #wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3A"]').trigger('click');
        });
        $('.btnNextPribadi').click(function (e) {
            e.preventDefault();
            var val = $('input[name="datapribadi"]:checked').val();
//            modifCKEDITOR(val, 'ck_datapribadi');
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });
        $('.btnNextJabatan').click(function (e) {
            e.preventDefault();
            var val = $('input[name="jabatan"]:checked').val();
            modifCKEDITOR(val, 'ck_jabatan');
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });
        $('.btnPreviousJabatan').click(function (e) {
            e.preventDefault();
            var val = $('input[name="jabatan"]:checked').val();
            modifCKEDITOR(val, 'ck_jabatan');
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnNextKeluarga').click(function (e) {
            e.preventDefault();
            var val = $('input[name="keluarga"]:checked').val();
            modifCKEDITOR(val, 'ck_keluarga');
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
            $('#harta > .nav-tabs').find('a[href="#hartatidakbergerak"]').trigger('click');
        });
        $('.btnPreviousKeluarga').click(function (e) {
            e.preventDefault();
            var val = $('input[name="keluarga"]:checked').val();
            modifCKEDITOR(val, 'ck_keluarga');
            // var valkel = $('input[name="keluarga"]:checked').val();
            // modifCKEDITOR(valkel, 'ck_keluarga');

            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnPreviousHartaTidakBergerak').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnNextHarta').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('#harta > .nav-tabs > .active').next('li').find('a').trigger('click');
        });
        $('.btnPreviousHarta').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('#harta > .nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnNextHutang').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            // alert(clas);
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
            $('#penerimaankas > #wrapperPenerimaan > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
        });
        $('.btnNextFasilitas').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
            $('#lampiran > .nav-tabs').find('a[href="#pelepasanharta"]').trigger('click');
        });
        $('.btnPreviousFasilitas').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
            $('#pengeluarankas > .contentTab > #wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3C"]').trigger('click');
        });
        $('.btnPreviousPelepasanHarta').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnNextLampiran').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('#lampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
        });
        $('.btnPreviousLampiran').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('#lampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnNextSuratKuasa').click(function (e) {
            e.preventDefault();
            var clas = $(this).parent().parent().get(0).id;
            var val = $('input[name="' + clas + '"]:checked').val();
            modifCKEDITOR(val, 'ck_' + clas);
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
            var data2 = $('.valVerFinal').serializeArray();
            $.each(data2, function (i, item) {
                console.log('item1 : ' + item.value);
                if (item.value !== '') {
                    $('#btnFinal').prop('disabled', false);
                    $('input[name="final"]').prop('disabled', false);
                }
            });
        });
        //                                                $('.btnNextDokumenPendukung').click(function(e) {
        //                                                    e.preventDefault();
        //                                                    var clas = $(this).parent().parent().get(0).id;
        //                                                    var val = $('input[name="' + clas + '"]:checked').val();
        //                                                    modifCKEDITOR(val, 'ck_' + clas);
        //                                                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
        //                                                });

        $('.btnPreviousFInal').click(function () {
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
            //                                                    $('#lampiran > .nav-tabs').find('a[href="#dokumenpendukung"]').trigger('click');
            $('#lampiran > .nav-tabs').find('a[href="#lampiran"]').trigger('click');
        });
        $('.final > .btnPrevious').click(function () {
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
<?php if (@$hasilVerifikasiitem->VAL->DATAPRIBADI == '-1') { ?>
            $('.navTabPribadi').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->JABATAN == '-1') { ?>
            $('.navTabJabatan').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->KELUARGA == '-1') { ?>
            $('.navTabKeluarga').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->HARTATIDAKBERGERAK == '-1') { ?>
            $('.navTabHarta').css({'background': 'red', 'color': 'white'});
            $('.navTabHartaTidakBergerak').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->HARTABERGERAK == '-1') { ?>
            $('.navTabHarta').css({'background': 'red', 'color': 'white'});
            $('.navTabHartaBergerak').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->HARTABERGERAK2 == '-1') { ?>
            $('.navTabHarta').css({'background': 'red', 'color': 'white'});
            $('.navTabHartaBergerak2').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->SURATBERHARGA == '-1') { ?>
            $('.navTabHarta').css({'background': 'red', 'color': 'white'});
            $('.navTabHartaSurat').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->KAS == '-1') { ?>
            $('.navTabHarta').css({'background': 'red', 'color': 'white'});
            $('.navTabHartaKas').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->HARTALAINNYA == '-1') { ?>
            $('.navTabHarta').css({'background': 'red', 'color': 'white'});
            $('.navTabHartaLainnya').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->HUTANG == '-1') { ?>
            $('.navTabHarta').css({'background': 'red', 'color': 'white'});
            $('.navTabHartaHutang').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->PENERIMAANKAS == '-1') { ?>
            $('.navTabPenerimaan').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->PENGELUARANKAS == '-1') { ?>
            $('.navTabPengeluaran').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->PENERIMAANFASILITAS == '-1') { ?>
            $('.navTabFasilitas').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->PELEPASANHARTA == '-1') { ?>
            $('.navTabReview').css({'background': 'red', 'color': 'white'});
            $('.navTabPelepasanHarta').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->PENERIMAANHIBAH == '-1') { ?>
            $('.navTabReview').css({'background': 'red', 'color': 'white'});
            $('.navTabPenerimaanHibah').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php if (@$hasilVerifikasiitem->VAL->SURATKUASAMENGUMUMKAN == '-1') { ?>
            $('.navTabReview').css({'background': 'red', 'color': 'white'});
            $('.navTabSuratKuasaMengumumkan').css({'background': 'red', 'color': 'white'});
<?php }
?>
<?php
// if (@$hasilVerifikasiitem->VAL->DOKUMENPENDUKUNG == '-1') {     ? >
//                                                    $('.navTabReview').css({'background': 'red', 'color': 'white'});
//                                                    $('.navTabDokumenPendukung').css({'background': 'red', 'color': 'white'});
?>

        $('.msgVer').change(function () {
            v = $(this).val();
            t = $(this).parents('.tab-pane').attr('id');
            f = t + 'Final';
            console.log(f);
            // $('#'+f).html('Hallo'+t);
            $('#' + f).find('.msgVerFinalTXT').html(v);
            $('#' + f).find('.msgVerFinal').val(v);
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
        $('.btn-save-data').click(function () {
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
                success: function (res) {
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
        var data2 = $('.valVerFinal').serializeArray();
        $.each(data2, function (i, item) {
            if (item.value !== '') {
                $('#btnFinal').prop('disabled', false);
                $('input[name="final"]').prop('disabled', false);
            } else {
                $('#btnFinal').prop('disabled', true);
                $('input[name="final"]').prop('disabled', true);
            }
        });
        if ($('input[name=final]:checked', '#ajaxFormFinal').val() == undefined) {
            $('#btnFinal').prop('disabled', true);
        }

        $('#ajaxFormFinal input').on('change', function () {
            $('#btnFinal').prop('disabled', false);
        });
        $('#ajaxFormFinal').submit(function () {
            $('#loader_area').show();
            var id = $('input[name="ID_LHKPN"]').val();
            var tgl_ver = $('#tgl_ver').val();
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
            $.each(data2, function (i, item) {
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
                    success: function (res) {

                        if (res == '01') {
                            if (vfinal == '1') {
                                swal("LHKPN Terverifikasi Lengkap !", "Surat Tanda Terima (softcopy) akan dikirim melalui email ke <?= $tampil2; ?>", "success");
                            } else if (vfinal == '3') {
                                swal("LHKPN Terverifikasi Tidak Lengkap !", "Surat Tanda Terima (softcopy) akan dikirim melalui email ke <?= $tampil2; ?>", "success");
                            } else {
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
                            $.get(link, function (data) {
                                ng.LoadAjaxContent(link);
                            }).done(function () {
                                $.ajax({
                                    url: 'index.php/ever/verification/pesan_pdf/',
                                    method: 'POST',
                                    data: {id_lhkpn: id, MSG_VERIFIKASI_INSTANSI: MSG_VERIFIKASI_INSTANSI, tgl_ver: tgl_ver},
                                    dataType: 'html',
                                    success: function (res) {
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
                swal("Data Belum Terverfikasi", "Mohon untuk memeriksa semua data !", "error");
            }

            return false;
        });
//        $('.ckeditor').ckeditor();
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


                                                                                                            <pre>
                                                                    Masuk Tanggal
                                                                    Verifikasi 1 tgl Kirim Pemberitahuan(msgbox);
                                                                    Submit Perbaikan tgl
                                                                    Verifikasi 2 tgl Kirim Pemberitahuan(msgbox);
                                                                    Submit Perbaikan tgl
                                                                    Selesai dan Verified tgl
                                                                                                            </pre>


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
                                                                                                            } catch (Exception $e) {
                                                                                                                
                                                                                                            }
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
                                                                                                            } catch (Exception $e) {
                                                                                                                
                                                                                                            }
                                                                                                            ob_end_flush();
                                                                                                            ?>
                                                                                                            <?php
                                                                                                        }
                                                                                                        ?>