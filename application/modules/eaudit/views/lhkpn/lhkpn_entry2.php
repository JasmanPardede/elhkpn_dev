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
<style type="text/css">
    .fieldset {
        padding: 0.35em 0.625em 0.75em;
        margin: 0px 2px;
        border: 1px solid #C0C0C0;
    }
    .legend_kk {
        border: 0px none;
        margin-bottom: 0;
        font-size: 16px;
        border-style: none;
        width: auto;
        font-weight: bold;
        padding: 0 5px;
    }
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
<!-- Content Header (Page header) -->
<?php if (!$show) { ?>
    <section class="content-header">
        <h1>
            <i class="fa <?php echo $icon; ?>"></i> <?php echo $title; ?>
            <small><?php //echo $title;                         ?></small>
        </h1>
        <?php echo $breadcrumb; ?>
    </section>
<?php } ?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
                <div class="row">
                    <div class="col-md-6">
                        <label>Di terima Melalui : </label> <?php echo beautify_str($lhkpn_offline_melalui[$info_penerimaan_offline->MELALUI]); ?>
                        <br />
                        <label>Tanggal Terima : </label> <?php echo tgl_format($info_penerimaan_offline->TANGGAL_PENERIMAAN); ?>
                        &nbsp;<button type="button" id="btnEditTglTerima" class="btn btn-xsm btn-primary btn-xs"  href="index.php/efill/validasi_penerimaan/edit_tgl_terima/<?php echo make_secure_text($LHKPN->id_imp_xl_lhkpn); ?>" title="Edit Tanggal Terima" onclick="onButton.go(this, 'medium', true);" >Edit Tanggal Terima</button>
                    </div>
                    <div class="col-md-6">
                        <label>Jenis Laporan : </label>&nbsp;
                        <?php echo map_jenis_laporan_xl($info_penerimaan_offline->JENIS_LAPORAN); ?><br />
                        <label><?php echo $info_penerimaan_offline->JENIS_LAPORAN != '4' ? "Tanggal" : "Tahun"; ?>&nbsp;Pelaporan : </label>
                        <?php echo $info_penerimaan_offline->JENIS_LAPORAN != '4' ? tgl_format($info_penerimaan_offline->TANGGAL_PELAPORAN) : $info_penerimaan_offline->TAHUN_PELAPORAN; ?>
                        <?php echo $info_penerimaan_offline->JENIS_LAPORAN != '4' ? "&nbsp;<button type=\"button\" id=\"btnEditTglPelaporan\" class=\"btn btn-xsm btn-primary btn-xs\"  href=\"index.php/efill/validasi_penerimaan/edit/" . make_secure_text($LHKPN->id_imp_xl_lhkpn) . "\" title=\"Edit Data\" onclick=\"onButton.go(this, 'medium', true);\" >Edit Tanggal</button>" : ""; ?>
                        <br />
                        <label>Versi Excel : </label><?php echo $info_penerimaan_offline->VERSI_EXCEL; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
                <label>
                    <input type="checkbox" onclick="if ($(this).is(':checked')) {
                                $('.navTab span').show();
                            } else {
                                $('.navTab span').hide();
                            }" checked="" /> Tampilkan Tab Label</label>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="nav-tabs" role="tablist">
                        <li id="li1" role="presentation"><a href="#datapribadi" aria-controls="datapribadi" role="tab" data-toggle="tab" class="navTab navTabPribadi" title="Data Pribadi"><?php echo ICON_pribadi; ?> <span>Data Pribadi</span></a></li>
                        <li id="li2" role="presentation"><a href="#keluarga" aria-controls="keluarga" role="tab" data-toggle="tab" class="navTab navTabKeluarga" title="Data Keluarga"><?php echo ICON_keluarga; ?> <span>Keluarga</span></a></li>
                        <li id="li3" role="presentation"><a href="#harta" id="link_harta" aria-controls="harta" role="tab" data-toggle="tab" class="navTab navTabHarta" title="Data Harta"><?php echo ICON_harta; ?> <span>Harta</span></a></li>
                        <li id="li4" role="presentation"><a href="#penerimaankas" id="link_penerimaan" aria-controls="penerimaankas" role="tab" data-toggle="tab" class="navTab navTabPenerimaan" title="Data Penerimaan Kas"><?php echo ICON_penerimaankas; ?> <span>Penerimaan</span></a></li>
                        <li id="li5" role="presentation"><a href="#pengeluarankas" id="link_pengeluaran" aria-controls="pengeluarankas" role="tab" data-toggle="tab" class="navTab navTabPengeluaran" title="Data Pengeluaran Kas"><?php echo ICON_pengeluarankas; ?> <span>Pengeluaran</span></a></li>
                        <li id="li6" role="presentation"><a href="#fasilitas" aria-controls="fasilitas" role="tab" data-toggle="tab" class="navTab navTabFasilitas" title="Penerimaan Fasilitas"><?php echo ICON_fasilitas; ?> <span>Fasilitas</span></a></li>
                        <li id="li7" role="presentation"><a href="#reviewlampiran" aria-controls="reviewlampiran" role="tab" data-toggle="tab" class="navTab navTabReview" title="Review lampiran"><?php echo ICON_lampiran; ?> <span>Pelepasan Harta</span></a></li>
                        <li id="li8" role="presentation"><a href="#final" aria-controls="final" role="tab" data-toggle="tab" class="navTab" title="Review Harta"><?php echo ICON_final; ?> <span>Review Harta</span></a></li>
                        <li id="li9" role="presentation"><a href="#dokuploaded" aria-controls="final" role="tab" data-toggle="tab" class="navTab" title="Daftar Dokumen Terupload"><?php echo ICON_final; ?> <span>Daftar Upload</span></a></li>
                        <li id="li10" role="presentation"><a href="#kirimlhkpn" aria-controls="kirimlhkpn" role="tab" data-toggle="tab" class="navTab" title="Review Harta"><?php echo ICON_final; ?> <span>Kirim LHKPN</span></a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                        <!--  -->
                        <div role="tabpanel" <?php echo ($LHKPN->STATUS == 2 ? 'class="tab-pane"' : 'class="tab-pane active"' ) ?>  id="datapribadi">

                            <div class="contentTab">
                                <div class="pull-right">
                                    <!--
                                    <button type="button" class="btn btn-warning btnvpribadi" href="index.php/ereg/all_pn/editpn_daftar_wl/2/daftarindividu/203249/undefined" title="Edit Data" onclick="onButton.edit_v_pribadi.click(this);">Edit</button>
                                    -->
                                    <button id="btn-edit-data_pribadi" type="button" class="btn btn-warning btnvpribadi" href="index.php/efill/validasi_data_pribadi/edit/<?php echo $DATA_PRIBADI->id_imp_xl_lhkpn_data_pribadi_secure ?>/<?php echo rand(123121, 923121); ?>" title="Edit Data" onclick="onButton.go(this, 'large', true);">Edit</button>
                                    <div class="clearfix"></div>
                                </div>
                                <br />
                                <?php require_once('lhkpn_table_pribadi.php'); ?>
                            </div>

                            <br>
                            <div class="pull-right">

                                <?php if ($LHKPN->STATUS == 2) { ?>
                                    <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-danger">Batal</button>
                                <?php } ?>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNext">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->

                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="keluarga">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_keluarga.php'); ?>
                            </div>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNext">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="harta">
                            <ul class="nav nav-tabs" role="tablist">
                                <li id="li11" role="presentation" class="active"><a id="link_harta_pertama" href="#hartatidakbergerak" aria-controls="hartatidakbergerak" role="tab" data-toggle="tab" class="navTab navTabHartaTidakBergerak" title="Tanah / Bangunan"><?php echo ICON_hartatidakbergerak; ?> <span>Tanah / Bangunan</span></a></li>
                                <li id="li12" role="presentation"><a href="#hartabergerak" aria-controls="hartabergerak" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak" title="Alat Transportasi / Mesin"><?php echo ICON_hartabergerak; ?> <span>Mesin / Alat Transport</span></a></li>
                                <li id="li13" role="presentation"><a href="#hartabergerakperabot" aria-controls="hartabergerakperabot" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak2" title="Perabot"><?php echo ICON_hartabergerakperabot; ?> <span>Harta Bergerak</span></a></li>
                                <li id="li14" role="presentation"><a href="#suratberharga" aria-controls="suratberharga" role="tab" data-toggle="tab" class="navTab navTabHartaSurat" title="Surat Berharga"><?php echo ICON_suratberharga; ?> <span>Surat Berharga</span></a></li>
                                <li id="li15" role="presentation"><a href="#kas" aria-controls="kas" role="tab" data-toggle="tab" class="navTab navTabHartaKas" title="Kas / Setara Kas"><?php echo ICON_kas; ?> <span>KAS / Setara KAS</span></a></li>
                                <li id="li16" role="presentation"><a href="#hartalainnya" aria-controls="hartalainnya" role="tab" data-toggle="tab" class="navTab navTabHartaLainnya" title="Harta Lainnya"><?php echo ICON_hartalainnya; ?> <span>Harta Lainnya</span></a></li>
                                <li id="li17" role="presentation"><a href="#hutang" aria-controls="hutang" role="tab" data-toggle="tab" class="navTab navTabHartaHutang" title="Data Hutang"><?php echo ICON_hutang; ?> <span>Hutang</span></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                <!--  -->
                                <div role="tabpanel" class="tab-pane active" id="hartatidakbergerak">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_harta_bangunan.php'); ?>
                                    </div>
                                    <br />
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="hartabergerak">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_harta_alat.php'); ?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="hartabergerakperabot">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_harta_perabotan.php'); ?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="suratberharga">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_surat.php'); ?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="kas">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_kas.php'); ?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="hartalainnya">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_harta_lainnya.php'); ?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="hutang">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_hutang.php'); ?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHartaAkhir">Selanjutnya <i class="fa fa-forward"></i> </button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- close Harta -->
                        <div role="tabpanel" class="tab-pane" id="fasilitas">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_lampiran_2.php'); ?>
                            </div>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPreviousFasilitas"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNextFasilitas">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="reviewlampiran">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_lampiran_1.php'); ?>
                            </div>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPreviousFasilitas"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNextFasilitas">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="penerimaankas">
                            <div class="contentTab">
                                <div class="pull-right">
                                    <button id="btn-edit-penerimaan_kas" type="button" class="btn btn-warning btnvpenerimaankas" href="index.php/efill/validasi_penerimaan_kas/edit/<?php echo $getPemka->id_imp_xl_lhkpn_penerimaan_kas_secure; ?>" title="Edit Data" onclick="onButton.go(this, 'large', true);">Edit</button>
                                    <div class="clearfix"></div>
                                </div>
                                <br />
                                <?php require_once('lhkpn_table_penerimaan_kas.php'); ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="pengeluarankas">
                            <div class="contentTab">
                                <div class="pull-right">
                                    <button id="btn-edit-pengeluaran_kas" type="button" class="btn btn-warning btnvpengeluarankas" href="index.php/efill/validasi_pengeluaran_kas/edit/<?php echo $getPenka->id_imp_xl_lhkpn_pengeluaran_kas_secure; ?>" title="Edit Data" onclick="onButton.go(this);">Edit</button>
                                    <div class="clearfix"></div>
                                </div>
                                <br />
                                <?php require_once('lhkpn_table_pengeluaran_kas.php'); ?>
                            </div>
                            <br />
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="final">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_ringkasan.php'); ?>
                            </div>
                            <br />
                            <div class="pull-right">
                                <input type="hidden" name="act" value="doverify">
                                <input type="hidden" name="show" value="<?php echo $show ?>" />
                                <input type="hidden" name="ID_LHKPN" value="<?php echo $LHKPN->ID_LHKPN; ?>">
                                <input type="hidden" name="ENTRY_VIA" value="<?php echo $LHKPN->ENTRY_VIA; ?>">
                            </div>
                            <br />
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNextFasilitas">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="dokuploaded">
                            <div class="contentTab">
                                <?php require_once('lhkpn_daftar_file_upload.php'); ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="kirimlhkpn">
                            <div class="contentTab">
                                <?php require_once('lhkpn_kirim_lhkpn.php'); ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <br />
                    <div class="pull-right">
                        <a href="<?php echo base_url(); ?>index.php#index.php/efill/lhkpnoffline/index/penerimaan" class="btn btn-danger">Kembali></a>
                    </div>
                    <div class="clearfix"></div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">

//    function valid_pelepasan(ele) {
//        var stat = true;
//        var form = $(ele).closest('.pelepasan');
//        $('.required', form).each(function () {
//            if ($(this).val() == '') {
//                if (!$(this).next('label.error').length) {
//                    $(this).after('<label class="error">Field ini harus diisi!</label>');
//                } else {
//                    $(this).next().show();
//                }
//                stat = false;
//            } else {
//                if ($(this).next('label.error').length) {
//                    $(this).next().hide();
//                }
//            }
//        });
//
//        return stat;
//    }

    function callback(id)
    {
        $.ajax({
            url: 'index.php/efill/lhkpn/pesan_pdf/',
            method: 'POST',
            data: {ID_LHKPN: id.id, ENTRY_VIA: id.id2},
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
    }

    $(document).ready(function () {
        var ID = '<?php echo isset($id_lhkpn) ? @$id_lhkpn : ""; ?>';

        /**
         * Lahir WS next show
         */
//        ng.LoadAjaxTabContent({url: 'index.php/efill/lhkpn/showTable/17/' + ID + '/edit', block: '#block', container: $('#dokumenpendukung').find('.contentTab')});
        function loadContentTab(url, obj) {
            url = url + '/<?php echo $LHKPN->ID_LHKPN; ?>';
            $.get(url, function (html) {
                $(obj).find('.contentTab').html(html);
            });
        }

        function redrawTab(tab) {
            switch (tab) {
                case '#datapribadi' :
                    tabIndex = 2;
                    break;
                case '#keluarga' :
                    tabIndex = 3;
                    break;
                case '#hartatidakbergerak' :
                    tabIndex = 4;
                    break;
                case '#hartabergerak' :
                    tabIndex = 5;
                    break;
                case '#hartabergerakperabot' :
                    tabIndex = 6;
                    break;
                case '#suratberharga' :
                    tabIndex = 7;
                    break;
                case '#kas' :
                    tabIndex = 8;
                    break;
                case '#hartalainnya' :
                    tabIndex = 9;
                    break;
                case '#hutang' :
                    tabIndex = 10;
                    break;
                case '#penerimaankas' :
                    tabIndex = 11;
                    break;
                case '#pengeluarankas' :
                    tabIndex = 12;
                    break;
                case '#fasilitas' :
                    tabIndex = 13;
                    break;
                case '#reviewlampiran' :
                    tabIndex = 14;
                    break;
                case '#dokpendukung' :
                    tabIndex = 18;
                    break;
            }
            url = 'index.php/efill/lhkpn/showTable/' + tabIndex;
            loadContentTab(url, tab);
        }
        // redrawTab('#datapribadi');
        $('.navTab').tooltip();
        /* IBO ADD */

        /* -- End --*/
        $('.btnNext').click(function () {
            $('#nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btnPrevious').click(function () {
            $('#nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        $('.btnNextHartaAkhir').click(function () {
            $('#nav-tabs > .active').next('li').find('a').trigger('click');
            $('#wrapperPenerimaan > .form-horizontal > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
        });
        $('.btnPreviousFinal').click(function () {
            $('#nav-tabs > .active').prev('li').find('a').trigger('click');
            $('#reviewlampiran > .nav-tabs').find('a[href="#dokumenpendukung"]').trigger('click');
        });


        $('.btnNextKeluarga').click(function () {
            $('#link_harta').click();
            $('#link_harta_pertama').click();
        });

        $('.btnCancel').click(function () {
            url = 'index.php/efill/lhkpn/';
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });
        /*
         $('.btnPrevious').click(function(){
         $('#nav-tabs > .active').prev('li').find('a').trigger('click');
         });	
         */

        $('.btnNextHarta').click(function (e) {
            e.preventDefault();
            $('#harta > .nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btnPreviousHarta').click(function (e) {
            e.preventDefault();
            $('#harta > .nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnNextLampiran').click(function (e) {
            e.preventDefault();
            if ($('#reviewlampiran > .nav-tabs > .active').next('li').find('a').attr('href') == undefined) {
                $('#nav-tabs > .active').next('li').find('a').trigger('click');
            } else {
                $('#reviewlampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
            }
        });
        $('.btnPreviousLampiran').click(function (e) {
            e.preventDefault();
            if ($('#reviewlampiran > .nav-tabs > .active').prev('li').find('a').attr('href') == undefined) {
                $('#nav-tabs > .active').prev('li').find('a').trigger('click');
            } else {
                $('#reviewlampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
            }
        });



        $('.btnNextReviewLampiran').click(function (e) {
            e.preventDefault();
            $('#lampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btnPreviousReviewLampiran').click(function (e) {
            e.preventDefault();
            $('#lampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        $('.btnNextFasilitas').click(function (e) {
            e.preventDefault();
            $('#nav-tabs > .active').next('li').find('a').trigger('click');
            $('#reviewlampiran > .nav-tabs').find('a[href="#pelepasanharta"]').trigger('click');
        });

        $('.btnPreviousFasilitas').click(function (e) {
            e.preventDefault();
            $('#nav-tabs > .active').prev('li').find('a').trigger('click');
            $('#wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3C"]').trigger('click');
        });


        $('.btnNextHutang').click(function (e) {
            e.preventDefault();
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });

        // ng.formProcess($("#ajaxFormSubmit"), 'insert', location.href.split('#')[1]);

        $('.contentTab').on('click', '.label-tetap', function () {
            var ele = $(this);
            $.post(ele.attr('data-url'), function (data) {
                if (data == '1') {
                    ele.removeClass('label-tetap');
                    $('.chk-tetap', ele.closest('tr')).remove();
                    $('span[data-class="label-tetap"]', ele.closest('tr')).show();
                    ele.remove();
                    alertify.success('Data berhasil di Cek!');
                }
            });
        })

        $('.contentTab').on('click', '.chk-all', function () {
            var ele = $(this);
            if (ele.is(':checked')) {
                $('.chk-tetap', ele.closest('table')).prop('checked', true);
            } else {
                $('.chk-tetap', ele.closest('table')).prop('checked', false);
            }

            count(ele);
        });

//        $('.contentTab').on('click', '.chk-tetap', chkHarta);
//
//        $('.contentTab').on('click', '.btn-remove-harta', function () {
//            var id = $('input[name="ID_LHKPN"]').val();
//
//            var ele = $(this);
//            var data = $('.chk-tetap', ele.closest('.box-body')).serializeArray();
//            $.post(ele.attr('href'), data, function (res) {
//                if (res.status == '1') {
//                    alertify.success('Data harta berhasil di hapus!');
//                    $.get('index.php/efill/lhkpn/showTable/' + res.table + '/' + id + '/edit', function (html) {
//                        ele.closest('.contentTab').html(html);
//                    });
//                }
//            }, 'json')
//        });

    });

    function chkHarta(e)
    {
        var ele = $(e.currentTarget);
        count(ele);
    }

    function count(ele)
    {
        var table = ele.closest('table');
        var box = ele.closest('.box-body');
        if ($('.chk-tetap:checked', table).length > 0) {
            $('.btn-remove-harta', box).show();
        } else {
            $('.btn-remove-harta', box).hide();
        }
    }

    function preview_lp(rel) {
        var url = base_url + 'index.php/efill/lhkpnoffline_send/preview_lp/' + rel;
        window.open(url, '_blank');
    }

    function cekTetap()
    {
        var res = true;

        var array = [
            {
                'class': 'hartatidak',
                'title': 'Harta Tanah/Bangunan'
            },
            {
                'class': 'hartaalat',
                'title': 'Harta Mesin/Alat Transport'
            },
            {
                'class': 'hartapra',
                'title': 'Harta Bergerak'
            },
            {
                'class': 'hartasurat',
                'title': 'Surat Berharga'
            },
            {
                'class': 'hartakas',
                'title': 'KAS/Setara KAS'
            },
            {
                'class': 'hartalain',
                'title': 'Harta Lainnya'
            }
        ];

        $.each(array, function (k, v) {
            if ($('.chk-tetap.' + v.class + ':not(:checked)').length > 0) {
                res = {
                    message: 'Terdapat data di tab "' + v.title + '" yang belum Anda periksa',
                    title: v.title
                };

                return false;
            }
        });

        return res;
    }

    var resHarta = [];
    function cekHarta()
    {
        if (resHarta.length == 0) {
            resHarta[0] = true;

            var array = [
                {
                    'class': '#hartatidakbergerak',
                    'title': 'Harta Tanah/Bangunan',
                    'nilai': '<?= @$hartirak[0]->sum_hartirak ?>'
                },
                {
                    'class': '#hartabergerak',
                    'title': 'Harta Mesin/Alat Transport',
                    'nilai': '<?= @$harger[0]->sum_harger ?>'
                },
                {
                    'class': '#hartabergerakperabot',
                    'title': 'Harta Bergerak',
                    'nilai': '<?= @$harger2[0]->sum_harger2 ?>'
                },
                {
                    'class': '#suratberharga',
                    'title': 'Surat Berharga',
                    'nilai': '<?= @$suberga[0]->sum_suberga ?>'
                },
                {
                    'class': '#kas',
                    'title': 'KAS/Setara KAS',
                    'nilai': '<?= @$kaseka[0]->sum_kaseka ?>'
                },
                {
                    'class': '#hartalainnya',
                    'title': 'Harta Lainnya',
                    'nilai': '<?= @$harlin[0]->sum_harlin ?>'
                },
                {
                    'class': '#hutang',
                    'title': 'Harta Hutang',
                    'nilai': '<?= @$_hutang[0]->sum_hutang ?>'
                }
            ];
            var i = 1;
            $.each(array, function (k, v) {
                if (v.nilai == "") {
                    resHarta[i] = {
                        // message: 'Apakah benar , tidak ada Harta pada tab "' + v.title + '" ? ',
                        message: 'Anda belum mengisikan harta pada tab "' + v.title + '", apakah memang tidak ada? ',
                        title: v.title,
                        clas: v.class
                    };
                    resHarta[0] = false;
                    i++;
                }
            });
        }

        return resHarta;
    }

    var kirim_lhkpnoffline = function (rel, tomail) {
        confirm("Anda menyetujui bahwa data yang terupload ini adalah valid.", function () {

            $('#loader_area').show();

            var sendmail = "";
            if (isDefined(tomail)) {
                sendmail = "/" + tomail;
            }

            $.ajax({
                url: 'index.php/efill/lhkpnoffline_send/send/' + rel + sendmail,
//                    method: 'POST',
//                    data: {ID_LHKPN: id.id, ENTRY_VIA: id.id2},
                dataType: 'json',
                success: function (res) {

                    $('#loader_area').hide();

                    if (res.success == 1) {
                        alertify.success("Kirim data berhasil");
                    } else {
                        $('#btn-terima-lhkpn').hide();

                        $('#loader_area').show();

                        alert(res.msg);

//                            window.location = 'index.php/efill/lhkpnoffline/index/penerimaan';
                    }
                    ng.LoadAjaxContent('index.php/efill/lhkpnoffline/index/penerimaan');
                }
            });

        }, "Konfirmasi Kirim LHKPN", undefined, "YA", "TIDAK");
    };

    $(document).ready(function () {

<?php if ($upperli): ?>
            $("li#<?php echo $upperli; ?>").find('a').trigger('click');

    <?php if ($bottomli): ?>
                $("li#<?php echo $bottomli; ?>").find('a').trigger('click');
    <?php endif; ?>
<?php endif; ?>

        $('#btn-terima-lhkpn').click(function () {

            var rel = $(this).attr('rel');

            confirm("Apakah anda akan mengirimkan tanda terima melalui email ?", function () {
                kirim_lhkpnoffline(rel);
            }, "Konfirmasi pengiriman menggunakan email", function () {
                kirim_lhkpnoffline(rel, "0");
            }, "YA", "TIDAK");
        });

        $('#btn-preview-lp').click(function () {
            var rel = $(this).attr('rel');

            preview_lp(rel);
        });

        $("#btn-kembali-besar").click(function () {
            window.location = 'index.php#index.php/efill/lhkpnoffline/index/penerimaan';
        });
    });
</script>
<?php
$js_page = isset($js_page) ? $js_page : '';
if (is_array($js_page)) {
    foreach ($js_page as $page_js) {
        echo $page_js;
    }
} else {
    echo $js_page;
}
?>