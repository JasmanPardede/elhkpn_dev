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
 * @author 
 * @package Views/ever/verification
 */
?>
<?php
if ($display == 'infoLHKPN') {
?>
    <style>
        #subtab{
            background-color: #FFFFFF; background-image: linear-gradient(-45deg, #F6F6F6 25%, transparent 15%, transparent 50%, #F6F6F6 50%, #F6F6F6 75%, transparent 75%, transparent); background-size: 12px 12px;
        }
    </style>
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/ckeditor/contents.css?v=<?=$this->config->item('cke_version');?>" type="text/css"/>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/adapters/jquery.js?v=<?=$this->config->item('cke_version');?>"></script>
    <script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
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
                            <li id="li1" role="presentation" class="active">
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
                            <li id="li10" role="presentation">
                                <a href="#reviewharta" aria-controls="reviewharta" role="tab" data-toggle="tab" class="navTab reviewharta navTabReview" title="Review Harta"><?php echo ICON_final; ?> 
                                    <span>Review Harta</span>
                                </a>
                            </li>
                            <li id="li19" role="presentation">
                                <a href="#outlier" aria-controls="outlier" role="tab" data-toggle="tab" class="navTab outlier navTabOutlier" title="Outlier"><?php echo ICON_final; ?> 
                                    <span>Outlier</span>
                                </a>
                            </li>
                            <li id="li8" role="presentation">
                                <a href="#lampiran" aria-controls="lampiran" role="tab" data-toggle="tab" class="navTab lampiran navTabReview" title="Data Lampiran Transaksi Pelepasan Harta, Penerimaan Fasilitas, Surat Kuasa, Dokumen Pendukung"><?php echo ICON_lampiran; ?> 
                                    <span>Lampiran</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                            <!--  -->
                            <div role="tabpanel" class="tab-pane active" id="datapribadi">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_pribadi.php'); ?>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnCancel"><i class="fa fa-backward"></i> Kembali</button>
                                    <button type="button" class="btn btn-sm btn-warning btnNextPribadi">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="jabatan">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_jabatan.php'); ?>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnPreviousJabatan"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btnNextJabatan">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="keluarga">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_keluarga.php'); ?>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnPreviousKeluarga"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btnNextKeluarga">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- Harta -->
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
                                        <br>
                                        <?php 
                                            require('verification_tabel_harta_tidak_bergerak_telusur.php');
                                        ?>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousHartaTidakBergerak"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextHarta">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="hartabergerak">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartabergerak.php'); ?>
                                        </div>
                                        <br>
                                        <?php
                                            require('verification_tabel_harta_bergerak_telusur.php');
                                        ?>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextHarta">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="hartabergerakperabot">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartabergerakperabot.php'); ?>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextHarta">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="suratberharga">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_suratberharga.php'); ?>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextHarta">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="kas">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_kas.php'); ?>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextHarta">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="hartalainnya">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hartalainnya.php'); ?>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextHarta">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="hutang">
                                        <div class="contentTab">
                                            <?php require_once('verification_tabel_hutang.php'); ?>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextHutang">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- close Harta -->
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="penerimaankas">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_penerimaankas.php'); ?>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnPreviousPenerimaan"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btnNextPenerimaan">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="pengeluarankas">
                                <div class="contentTab">
                                    <?php require_once('verification_tabel_pengeluarankas.php'); ?>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnPreviousPengeluaran"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btnNextPengeluaran">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="penerimaanfasilitas">
                                <div class="contentTab">
                                    <?php require_once('verification_table_lampiran_2.php'); ?>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnPreviousFasilitas"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btnNextFasilitas">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- -->
                            <div role="tabpanel" class="tab-pane" id="reviewharta">
                                <div class="contentTab">
                                    <?php require_once('verification_table_review.php'); ?>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-warning btnPreviousFasilitas"><i class="fa fa-backward"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btnNextFasilitas">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- -->
                                <!-- Outlier -->
                                <div role="tabpanel" class="tab-pane " id="outlier">
                                <div class="contentTab">
                                    <?php require_once('verification_table_outlier.php'); ?>
                                    
                                </div>
                                <br>
                                <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPreviousPelepasanHarta"><i class="fa fa-backward"></i>  Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-warning btn-save-data btnNextFasilitas">Berikutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- End  -->
                            <div role="tabpanel" class="tab-pane" id="lampiran">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li id="subtab" role="presentation" class="active"><a href="#pelepasanharta" aria-controls="pelepasanharta" role="tab" data-toggle="tab" class="navTab navTabPelepasan" title="Pelepasan Harta"><?php echo ICON_pelepasanharta; ?> <span>Penjualan/Pelepasan Harta </span></a></li>
                                    <li id="subtab" role="presentation"><a href="#penerimaanhibah" aria-controls="penerimaanhibah" role="tab" data-toggle="tab" class="navTab navTabPenerimaanHibah" title="Penerimaan Hibah"><?php echo ICON_penerimaanhibah; ?> <span>Penerimaan Hibah</span></a></li>
                                    <li id="subtab" role="presentation"><a href="#suratkuasamengumumkan" aria-controls="suratkuasamengumumkan" role="tab" data-toggle="tab" class="navTab navTabSuratKuasaMengumumkan" title="Surat Kuasa"><?php echo ICON_suratkuasamengumumkan; ?> <span>Surat Kuasa</span></a></li> 
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane active" id="pelepasanharta">
                                        <div class="contentTab">
                                            <?php require_once('verification_table_lampiran_1.php'); ?>
                                        </div>
                                        <br>
                                        <input type="hidden" name="ID_LHKPN" value="<?php echo $LHKPN->ID_LHKPN; ?>">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousPelepasanHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextLampiran">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--  -->
                                    <div role="tabpanel" class="tab-pane" id="penerimaanhibah">
                                        <div class="contentTab">
                                            <?php require_once('verification_table_lampiran_1_1.php'); ?>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousLampiran"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnNextLampiran">Berikutnya <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                     <div role="tabpanel" class="tab-pane" id="suratkuasamengumumkan">
                                        <div class="contentTab">
                                            <?php require_once('verification_table_surat_kuasa.php');?>
                                        </div>
                                        <br>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-sm btn-warning btnPreviousLampiran"><i class="fa fa-backward"></i> Sebelumnya</button>
                                            <button type="button" class="btn btn-sm btn-warning btnCancel">Tutup <i class="fa fa-forward"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div> 
                                </div>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
    <div id="conhidden" style="display: none;"></div>

    <script type="text/javascript">
        $(document).ready(function() {
            <?php if ($upperli): ?>
                $("li#<?php echo $upperli; ?>").find('a').trigger('click');
                <?php if ($bottomli): ?>
                    $("li#<?php echo $bottomli; ?>").find('a').trigger('click');
                <?php endif; ?>
            <?php endif; ?>
                                            
        });

        $(document).ready(function() {
            
            $(".aksi").hide();
            $('.navTab').tooltip();
            
            $('.btnCancel').click(function() {
                window.top.close();
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
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
            });

            $('.btnNextJabatan').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
            });

            $('.btnPreviousJabatan').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
            });

            $('.btnNextKeluarga').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                $('#harta > .nav-tabs').find('a[href="#hartatidakbergerak"]').trigger('click');
            });

            $('.btnPreviousKeluarga').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
            });

            $('.btnPreviousHartaTidakBergerak').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
            });

            $('.btnNextHarta').click(function(e) {
                e.preventDefault();
                $('#harta > .nav-tabs > .active').next('li').find('a').trigger('click');
            });

            $('.btnPreviousHarta').click(function(e) {
                e.preventDefault();
                $('#harta > .nav-tabs > .active').prev('li').find('a').trigger('click');
            });

            $('.btnNextHutang').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                $('#penerimaankas > #wrapperPenerimaan > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
            });

            $('.btnNextPenerimaan').click(function(e) {
                e.preventDefault();
                if ($('#wrapperPenerimaan > .nav-tabs > .active').next('li').find('a').attr('href') == undefined) {
                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                    $('#wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3A"]').trigger('click');
                } else {
                    $('#wrapperPenerimaan > .nav-tabs > .active').next('li').find('a').trigger('click');
                }
            });
            
            $('.btnPreviousPenerimaan').click(function(e) {
                e.preventDefault();
                if ($('#wrapperPenerimaan > .nav-tabs > .active').prev('li').find('a').attr('href') == undefined) {
                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                    $('#harta > .nav-tabs').find('a[href="#hutang"]').trigger('click');
                } else {
                    $('#wrapperPenerimaan > .nav-tabs > .active').prev('li').find('a').trigger('click');
                }
            });

            $('.btnNextPengeluaran').click(function(e) {
                e.preventDefault();
                if ($('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').next('li').find('a').attr('href') == undefined) {
                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                } else {
                    $('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').next('li').find('a').trigger('click');
                }
            });

            $('.btnPreviousPengeluaran').click(function(e) {
                e.preventDefault();
                if ($('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').prev('li').find('a').attr('href') == undefined) {
                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                    $('#wrapperPenerimaan > .nav-tabs').find('a[href="#tabsC"]').trigger('click');
                } else {
                    $('#wrapperPengeluaran > .form-horizontal > .nav-tabs > .active').prev('li').find('a').trigger('click');
                }
            });

            $('.btnNextFasilitas').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').next('li').find('a').trigger('click');
                $('#lampiran > .nav-tabs').find('a[href="#pelepasanharta"]').trigger('click');
            });

            $('.btnPreviousFasilitas').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                $('#pengeluarankas > .contentTab > #wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3C"]').trigger('click');
            });

            $('.btnPreviousPelepasanHarta').click(function(e) {
                e.preventDefault();
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
            });

            $('.btnNextLampiran').click(function(e) {
                e.preventDefault();
                $('#lampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
            });

            $('.btnPreviousLampiran').click(function(e) {
                e.preventDefault();
                $('#lampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
            });
            
            $('.final > .btnPrevious').click(function() {
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
            });

            $('.ckeditor').ckeditor();
        });

    </script>

    <style type="text/css">
        .nav-tabs > li.active > a,
        .nav-tabs > li.active > a:hover,
        .nav-tabs > li.active > a:focus{
            color: #fff;
            background-color: #3C8DBC !important;  
        } 
    </style>

<?php
}
?>