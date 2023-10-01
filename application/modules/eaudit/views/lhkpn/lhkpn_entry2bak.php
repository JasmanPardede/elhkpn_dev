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
// display($item);

// display($LHKPN);
// display($DATA_PRIBADI);
// $pribadis = $DATA_PRIBADI;
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

    $aStatus    = [
        1 =>  [
            'label' => 'info',
            'name' => 'Tetap'
        ],
        2 => [
            'label' => 'warning',
            'name' => 'Ubah'
        ],
        3 => [
            'label' => 'success',
            'name' => 'Baru'
        ],
        4 => [
            'label' => 'danger',
            'name' => 'Lapor'
        ]
    ];
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
</style>
<!-- Content Header (Page header) -->
<?php if(!$show){ ?>
<section class="content-header">
    <h1>
    <i class="fa <?php echo $icon;?>"></i> <?php echo $title;?>
    <small><?php //echo $title;?></small>
    </h1>
    <?php echo $breadcrumb;?>
</section>
<?php } ?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
<!--                 <pre>
                Nama PN : <?php echo $LHKPN->NAMA;?><br>
                Jabatan : <?php echo $LHKPN->JABATAN;?><br>
                Laporan : <?php echo $LHKPN->JENIS_LAPORAN;?> <?php echo $LHKPN->TGL_LAPOR;?><br>
                </pre> -->
                <label><input type="checkbox" onclick="if($(this).is(':checked')){$('.navTab span').show();}else{$('.navTab span').hide();}" checked=""> Tampilkan Tab Label</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label><?php echo $tampil;?></label>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="nav-tabs" role="tablist">
                        <!-- <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li> -->
                        <?php if($LHKPN->STATUS == 2 ) { ?>
                            <li role="presentation" class="active"><a href="#hasilverifikasi" aria-controls="hasilverifikasi" role="tab" data-toggle="tab" class="navTab" title="Hasil Verifikasi"><?php echo ICON_final;?> <span>Hasil Verifikasi</span></a></li>
                        <?php } ?>
                        <li role="presentation" <?php echo ($LHKPN->STATUS != 2 ? 'class="active"' : '');?>><a href="#datapribadi" aria-controls="datapribadi" role="tab" data-toggle="tab" class="navTab navTabPribadi" title="Data Pribadi"><?php echo ICON_pribadi;?> <span>Data Pribadi</span></a></li>
                        <li role="presentation"><a href="#jabatan" aria-controls="jabatan" role="tab" data-toggle="tab" class="navTab navTabJabatan" title="Data Jabatan"><?php echo ICON_jabatan;?> <span>Jabatan</span></a></li>
                        <li role="presentation"><a href="#keluarga" aria-controls="keluarga" role="tab" data-toggle="tab" class="navTab navTabKeluarga" title="Data Keluarga"><?php echo ICON_keluarga;?> <span>Keluarga</span></a></li>
                        <li role="presentation"><a href="#harta" id="link_harta" aria-controls="harta" role="tab" data-toggle="tab" class="navTab navTabHarta" title="Data Harta"><?php echo ICON_harta;?> <span>Harta</span></a></li>
                        <li role="presentation"><a href="#penerimaankas" id="link_penerimaan" aria-controls="penerimaankas" role="tab" data-toggle="tab" class="navTab navTabPenerimaan" title="Data Penerimaan Kas"><?php echo ICON_penerimaankas;?> <span>Penerimaan</span></a></li>
                        <li role="presentation"><a href="#pengeluarankas" id="link_pengeluaran" aria-controls="pengeluarankas" role="tab" data-toggle="tab" class="navTab navTabPengeluaran" title="Data Pengeluaran Kas"><?php echo ICON_pengeluarankas;?> <span>Pengeluaran</span></a></li>
                        <li role="presentation"><a href="#fasilitas" aria-controls="fasilitas" role="tab" data-toggle="tab" class="navTab navTabFasilitas" title="Penerimaan Fasilitas"><?php echo ICON_fasilitas;?> <span>Fasilitas</span></a></li>
                        <li role="presentation"><a href="#reviewlampiran" aria-controls="reviewlampiran" role="tab" data-toggle="tab" class="navTab navTabReview" title="Review lampiran"><?php echo ICON_lampiran;?> <span>Review Lampiran</span></a></li>
                        <li role="presentation"><a href="#final" aria-controls="final" role="tab" data-toggle="tab" class="navTab" title="Review Harta"><?php echo ICON_final;?> <span>Review Harta</span></a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                        <?php if($LHKPN->STATUS == 2 ) { ?>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane active" id="hasilverifikasi">
                                
                                <div class="contentTab">
                                    <?php require_once('lhkpn_hasil_verifikasi.php');?>
                                </div>

                                <br>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-cross"></i> Batal</button>
                                    <button type="button" class="btn btn-warning btn-sm pull-right btnNext">Selanjutnya <i class="fa fa-forward"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php } ?>
                        <!--  -->
                        <div role="tabpanel" <?php echo ($LHKPN->STATUS == 2 ? 'class="tab-pane"': 'class="tab-pane active"' ) ?>  id="datapribadi">
                            
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_pribadi.php');?>
                            </div>

                            <br>
                            <div class="pull-right">
                                <?php if($LHKPN->STATUS == 2 ) { ?>
                                    <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-danger">Batal</button>
                                <?php } ?>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNext">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="jabatan">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_jabatan.php');?>
                            </div>
                            <br>
                            <div align="right">
                                <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNext">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <br>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="keluarga">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_keluarga.php');?>
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
                                <li role="presentation" class="active"><a id="link_harta_pertama" href="#hartatidakbergerak" aria-controls="hartatidakbergerak" role="tab" data-toggle="tab" class="navTab navTabHartaTidakBergerak" title="Tanah / Bangunan"><?php echo ICON_hartatidakbergerak;?> <span>Tanah / Bangunan</span></a></li>
                                <li role="presentation"><a href="#hartabergerak" aria-controls="hartabergerak" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak" title="Alat Transportasi / Mesin"><?php echo ICON_hartabergerak;?> <span>Mesin / Alat Transport</span></a></li>
                                <li role="presentation"><a href="#hartabergerakperabot" aria-controls="hartabergerakperabot" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak2" title="Perabot"><?php echo ICON_hartabergerakperabot;?> <span>Harta Bergerak</span></a></li>
                                <li role="presentation"><a href="#suratberharga" aria-controls="suratberharga" role="tab" data-toggle="tab" class="navTab navTabHartaSurat" title="Surat Berharga"><?php echo ICON_suratberharga;?> <span>Surat Berharga</span></a></li>
                                <li role="presentation"><a href="#kas" aria-controls="kas" role="tab" data-toggle="tab" class="navTab navTabHartaKas" title="Kas / Setara Kas"><?php echo ICON_kas;?> <span>KAS / Setara KAS</span></a></li>
                                <li role="presentation"><a href="#hartalainnya" aria-controls="hartalainnya" role="tab" data-toggle="tab" class="navTab navTabHartaLainnya" title="Harta Lainnya"><?php echo ICON_hartalainnya;?> <span>Harta Lainnya</span></a></li>
                                <li role="presentation"><a href="#hutang" aria-controls="hutang" role="tab" data-toggle="tab" class="navTab navTabHartaHutang" title="Data Hutang"><?php echo ICON_hutang;?> <span>Hutang</span></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                <!--  -->
                                <div role="tabpanel" class="tab-pane active" id="hartatidakbergerak">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_harta_bangunan.php');?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPrevious"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="hartabergerak">
                                    <div class="contentTab">
                                        <?php require_once('lhkpn_table_harta_alat.php');?>
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
                                        <?php require_once('lhkpn_table_harta_perabotan.php');?>
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
                                        <?php require_once('lhkpn_table_surat.php');?>
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
                                        <?php require_once('lhkpn_table_kas.php');?>
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
                                        <?php require_once('lhkpn_table_harta_lainnya.php');?>
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
                                        <?php require_once('lhkpn_table_hutang.php');?>
                                    </div>

                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-warning btn-sm pull-right btnNextHartaAkhir">Selanjutnya <i class="fa fa-forward"></i> </button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div><!-- close Harta -->
                        <div role="tabpanel" class="tab-pane" id="fasilitas">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_lampiran_2.php');?>
                            </div>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPreviousFasilitas"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-warning btn-sm pull-right btnNextFasilitas">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="reviewlampiran">
                            <?php // require_once('verification_tabel_lampiran.php');?>

                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#pelepasanharta" aria-controls="pelepasanharta" role="tab" data-toggle="tab" class="navTab navTabPelepasanHarta" title="Pelepasan Harta"><?php echo ICON_pelepasanharta;?> <span>Penjualan/Pelepasan/Pemberian Harta </span></a></li>
                                        <li role="presentation"><a href="#penerimaanhibah" aria-controls="penerimaanhibah" role="tab" data-toggle="tab" class="navTab navTabPenerimaanHibah" title="Penerimaan Hibah"><?php echo ICON_penerimaanhibah;?> <span>Penerimaan Harta</span></a></li>
                                        <!-- <li role="presentation"><a href="#penerimaanfasilitas" aria-controls="penerimaanfasilitas" role="tab" data-toggle="tab" class="navTab" title="Penerimaan Fasilitas"><?php echo ICON_penerimaanfasilitas;?> <span>Penerimaan Fasilitas</span></a></li> -->
                                        <!-- <li role="presentation"><a href="#suratkuasamengumumkan" aria-controls="suratkuasamengumumkan" role="tab" data-toggle="tab" class="navTab" title="Surat Kuasa Mengumumkan"><?php echo ICON_suratkuasamengumumkan;?> <span>Surat Kuasa Mengumumkan</span></a></li> -->
                                        <!-- <li role="presentation"><a href="#dokumenpendukung" aria-controls="dokumenpendukung" role="tab" data-toggle="tab" class="navTab navTabDokumenPendukung" title="Dokumen Pendukung"><?php echo ICON_dokumenpendukung;?> <span>Dokumen Pendukung</span></a></li> -->
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                        <!--  -->
                                        <div role="tabpanel" class="tab-pane active" id="pelepasanharta">
                                            <div class="contentTab">
                                                <?php require_once('lhkpn_table_lampiran_1.php');?>
                                            </div>

                                            <br>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-sm btn-warning btnPreviousLampiran"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                                <button type="button" class="btn btn-warning btn-sm pull-right btnNextLampiran">Selanjutnya <i class="fa fa-forward"></i></button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <!--  -->
                                        <div role="tabpanel" class="tab-pane" id="penerimaanhibah">
                                            <div class="contentTab">
                                                <?php require_once('lhkpn_table_lampiran_1_1.php');?>
                                            </div>
                                            <br>
                                            <div class="pull-right">
                                                <button type="button" class="btn-warning btn btn-sm btnPreviousLampiran"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                                <button type="button" class="btn btn-warning btn-sm pull-right btnNextLampiran">Selanjutnya <i class="fa fa-forward"></i> </button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <!--
                                        <div role="tabpanel" class="tab-pane" id="penerimaanfasilitas">
                                            <div class="contentTab">
                                                <?php //require_once('lhkpn_table_lampiran_2.php');?>
                                            </div>
                                            <br>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-sm btn-primary btnPreviousLampiran">Previous</button>
                                                <button type="button" class="btn btn-sm btn-primary btnNextLampiran">Next</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        -->
                                        <!-- <div role="tabpanel" class="tab-pane" id="suratkuasamengumumkan">
                                            <div class="contentTab">
                                                <?php //require_once('lhkpn_table_lampiran_4.php');?>
                                            </div>
                                            <br>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-sm btn-primary btnPreviousLampiran">Previous</button>
                                                <button type="button" class="btn btn-sm btn-primary btnNextLampiran">Next</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div> -->
                                        <!--  -->
                                        <div role="tabpanel" class="tab-pane" id="dokumenpendukung">
                                            <div class="contentTab">
                                                <?php require_once('lhkpn_table_dokumen_pendukung.php');?>
                                            </div>
                                            <br>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-warning btn-sm btnPreviousLampiran"> <i class="fa fa-backward"></i> Sebelumnya</button>
                                                <button type="button" class="btn btn-warning btn-sm pull-right btnNextLampiran">Selanjutnya <i class="fa fa-forward"></i> </button>
                                            </div>
                                            <div class="clearfix"></div>
                                            
                                            <br>
                                        </div>
                                    </div>

<!--                             <br>
                            <button type="button" class="btn btn-sm btn-primary btnPrevious">Previous</button>
                            <button type="button" class="btn btn-sm btn-primary btnNext">Next</button>
                            <br> -->
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="penerimaankas">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_penerimaan_kas.php');?>
                            </div>

                            
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="pengeluarankas">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_pengeluaran_kas.php');?>
                            </div>

                            
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="final">
                            <div class="contentTab">
                                <?php require_once('lhkpn_table_ringkasan.php');?>
                            </div>

                            <!-- <form id="ajaxFormSubmit" method="post" action="index.php/efill/lhkpn/submitlhkpn/"> -->
                                <br>
                                <div class="pull-right">
                                    <input type="hidden" name="act" value="doverify">
                                    <input type="hidden" name="show" value="<?php echo $show ?>" />
                                    <input type="hidden" name="ID_LHKPN" value="<?php echo $LHKPN->ID_LHKPN;?>">
                                    <input type="hidden" name="ENTRY_VIA" value="<?php echo $LHKPN->ENTRY_VIA;?>">
                                    <?php if($mode == 'edit') { ?>
                                        <button id="btn-submit" type="submit" class="btn btn-sm btn-success">Kirim LHKPN</button>
                                    <?php } ?>
                                </div>
                                <div class="clearfix">
                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">

    function valid_pelepasan(ele){
        var stat = true;
        var form = $(ele).closest('.pelepasan');
        $('.required', form).each(function(){
            if($(this).val() == ''){
                if(!$(this).next('label.error').length){
                    $(this).after('<label class="error">Field ini harus diisi!</label>');
                }else{
                    $(this).next().show();
                }
                stat = false;
            }else{
                if($(this).next('label.error').length) {
                    $(this).next().hide();
                }
            }
        });

        return stat;
    }

    function callback(id)
    {
         $.ajax({
             url: 'index.php/efill/lhkpn/pesan_pdf/',
             method: 'POST',
             data: {ID_LHKPN : id.id, ENTRY_VIA : id.id2},
             dataType: 'html',
             success: function(res){
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

$(document).ready(function(){
    var ID = '<?= @$id_lhkpn; ?>';
    ng.LoadAjaxTabContent({url:'index.php/efill/lhkpn/showTable/17/'+ID + '/edit', block:'#block', container:$('#dokumenpendukung').find('.contentTab')});
	function loadContentTab(url, obj){
		url = url+'/<?php echo $LHKPN->ID_LHKPN;?>';
		$.get(url, function (html) {
			$(obj).find('.contentTab').html(html);
		});
	}

	function redrawTab(tab){
		switch(tab){
			case '#datapribadi' : tabIndex = 2; break;
			case '#keluarga' : tabIndex = 3; break;
			case '#hartatidakbergerak' : tabIndex = 4; break;
			case '#hartabergerak' : tabIndex = 5; break;
			case '#hartabergerakperabot' : tabIndex = 6; break;
			case '#suratberharga' : tabIndex = 7; break;
			case '#kas' : tabIndex = 8; break;
			case '#hartalainnya' : tabIndex = 9; break;
			case '#hutang' : tabIndex = 10; break;
			case '#penerimaankas' : tabIndex = 11; break;
			case '#pengeluarankas' : tabIndex = 12; break;
            case '#fasilitas' : tabIndex = 13; break;
			case '#reviewlampiran' : tabIndex = 14; break;
			case '#dokpendukung' : tabIndex = 18; break;
		}
		url = 'index.php/efill/lhkpn/showTable/'+tabIndex;
		loadContentTab(url, tab);
	}


    // redrawTab('#datapribadi');
    // redrawTab('#keluarga');
    // redrawTab('#hartatidakbergerak');
    // redrawTab('#hartabergerak');
    // redrawTab('#hartabergerakperabot');
    // redrawTab('#suratberharga');
    // redrawTab('#kas');
    // redrawTab('#hartalainnya');
    // redrawTab('#hutang');
    // redrawTab('#penerimaankas');
    // redrawTab('#pengeluarankas');
    // redrawTab('#lampiran');
    // redrawTab('#dokpendukung');


    $('.navTab').tooltip();

	$('.btnNext').click(function(){
	  $('#nav-tabs > .active').next('li').find('a').trigger('click');
	});

	$('.btnPrevious').click(function(){
	  $('#nav-tabs > .active').prev('li').find('a').trigger('click');
	});
	
	$('.btnNextHartaAkhir').click(function(){
		$('#nav-tabs > .active').next('li').find('a').trigger('click');
		$('#wrapperPenerimaan > .form-horizontal > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
	});
	$('.btnPreviousFinal').click(function(){
		$('#nav-tabs > .active').prev('li').find('a').trigger('click');
		$('#reviewlampiran > .nav-tabs').find('a[href="#dokumenpendukung"]').trigger('click');
	});
	
	
	$('.btnNextKeluarga').click(function(){
	  $('#link_harta').click();
	  $('#link_harta_pertama').click();
	});

    $('.btnCancel').click(function(){
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
	$('.btnNextHarta').click(function(e){
	  e.preventDefault();
	  $('#harta > .nav-tabs > .active').next('li').find('a').trigger('click');
	});

	$('.btnPreviousHarta').click(function(e){
		e.preventDefault();
	  $('#harta > .nav-tabs > .active').prev('li').find('a').trigger('click');
	});
	$('.btnNextLampiran').click(function(e){
	  e.preventDefault();
	  if($('#reviewlampiran > .nav-tabs > .active').next('li').find('a').attr('href')==undefined){
	  	$('#nav-tabs > .active').next('li').find('a').trigger('click');
	  }else{
	  	$('#reviewlampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
	  }
	});
	$('.btnPreviousLampiran').click(function(e){
	  e.preventDefault();
	  if($('#reviewlampiran > .nav-tabs > .active').prev('li').find('a').attr('href')==undefined){
	  	$('#nav-tabs > .active').prev('li').find('a').trigger('click');
	  }else{
	  	$('#reviewlampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
	  }
	});

	

    $('.btnNextReviewLampiran').click(function(e){
      e.preventDefault();
      $('#lampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
    });

    $('.btnPreviousReviewLampiran').click(function(e){
        e.preventDefault();
      $('#lampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
    });
    $('.btnNextFasilitas').click(function(e){
	  e.preventDefault();
	  	$('#nav-tabs > .active').next('li').find('a').trigger('click');
		$('#reviewlampiran > .nav-tabs').find('a[href="#pelepasanharta"]').trigger('click');
	});

	$('.btnPreviousFasilitas').click(function(e){
		e.preventDefault();
	  	$('#nav-tabs > .active').prev('li').find('a').trigger('click');
		$('#wrapperPengeluaran > .form-horizontal > .nav-tabs').find('a[href="#tabs3C"]').trigger('click');
	});


	$('.btnNextHutang').click(function(e){
	  e.preventDefault();
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	});

    // ng.formProcess($("#ajaxFormSubmit"), 'insert', location.href.split('#')[1]);

    $('#btn-submit').click(function() {
        var id = $('input[name="ID_LHKPN"]').val();
        var id2 = $('input[name="ENTRY_VIA"]').val();

        var res      = cekTetap();
        var cekharta = cekHarta();
        if (res == true){
            if (cekharta[0] == true)
            {
                $.ajax({
                    url: 'index.php/efill/lhkpn/ceK_kk',
                    method: "POST",
                    data: {id: id},
                    dataType: "html",
                    success: function (data) {
                     
                            url2 = 'index.php/efill/lhkpn/getUserAgreement<?php echo ($show == "announ" ? "/announ" : '') ?>';
                            $.post(url2, {id: id, id2: id2}, function (html) {
                                OpenModalBox('Kirim LHKPN', html, '', 'standart');
                                ng.formProcess($("#ajaxFormSubmit"), 'insert', '<?php echo ($show == "announ" ? "index.php/eano/announ/index/perbaikan/" : "index.php/efill/lhkpn/") ?>');
                            });
                       
                    }
                });
            }else{
                hartaalert(cekharta , 1);

            };
            
        }else{
            swal(res['title'], res['message'], "error");
        }

        return false;
    });

    $('.contentTab').on('click', '.label-tetap', function(){
        var ele = $(this);
        $.post(ele.attr('data-url'), function(data){
            if(data == '1'){
                ele.removeClass('label-tetap');
                $('.chk-tetap', ele.closest('tr')).remove();
                $('span[data-class="label-tetap"]', ele.closest('tr')).show();
                ele.remove();
                alertify.success('Data berhasil di Cek!');
            }
        });
    })

    $('.contentTab').on('click', '.chk-all', function(){
        var ele = $(this);
        if(ele.is(':checked')) {
            $('.chk-tetap', ele.closest('table')).prop('checked', true);
        }else{
            $('.chk-tetap', ele.closest('table')).prop('checked', false);
        }

        count(ele);
    });

    $('.contentTab').on('click', '.chk-tetap', chkHarta);

    $('.contentTab').on('click', '.btn-remove-harta', function(){
        var id = $('input[name="ID_LHKPN"]').val();

        var ele = $(this);
        var data = $('.chk-tetap', ele.closest('.box-body')).serializeArray();
        $.post(ele.attr('href'), data, function (res) {
            if(res.status == '1'){
                alertify.success('Data harta berhasil di hapus!');
                $.get('index.php/efill/lhkpn/showTable/'+res.table + '/'+id+'/edit', function(html){
                    ele.closest('.contentTab').html(html);
                })
            }
        }, 'json')
    })

    <?php if($this->uri->segment('5') == 'edit') { ?>
        <?php if(@$hasilVerifikasi->VAL->DATAPRIBADI == '-1') {  ?>
            $('.navTabPribadi').css({ 'background': 'red', 'color': 'white' });
        <?php }else{ ?> 
            
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->JABATAN == '-1'){ ?>
            $('.navTabJabatan').css({ 'background': 'red', 'color': 'white' });
        <?php }else{ ?> 
            
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->KELUARGA == '-1'){ ?>
            $('.navTabKeluarga').css({ 'background': 'red', 'color': 'white' });
        <?php }else{ ?> 
            
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->HARTATIDAKBERGERAK == '-1'){ ?>
            $('.navTabHarta').css({ 'background': 'red', 'color': 'white' });
            $('.navTabHartaTidakBergerak').css({ 'background': 'red', 'color': 'white' });
        <?php }elseif(@$hasilVerifikasi->VAL->HARTATIDAKBERGERAK == '1'){ ?> 
            $('.HartaTidakBergerakverif').css({ 'display': 'none' });
            $('.HartaTidakBergerakspan').attr( 'colspan', '7' );
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->HARTABERGERAK == '-1'){ ?>
            $('.navTabHarta').css({ 'background': 'red', 'color': 'white' });
            $('.navTabHartaBergerak').css({ 'background': 'red', 'color': 'white' });
        <?php }elseif(@$hasilVerifikasi->VAL->HARTABERGERAK == '1'){ ?> 
            $('.HartaBergerakverif').css({ 'display': 'none' });
            $('.HartaBergerakspan').attr( 'colspan', '7' );
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->HARTABERGERAK2 == '-1'){ ?>
            $('.navTabHarta').css({ 'background': 'red', 'color': 'white' });
            $('.navTabHartaBergerak2').css({ 'background': 'red', 'color': 'white' });
        <?php }elseif(@$hasilVerifikasi->VAL->HARTABERGERAK2 == '1'){ ?> 
            $('.HartaBergerak2verif').css({ 'display': 'none' });
            $('.HartaBergerak2span').attr( 'colspan', '7' );
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->SURATBERHARGA == '-1'){ ?>
            $('.navTabHarta').css({ 'background': 'red', 'color': 'white' });
            $('.navTabHartaSurat').css({ 'background': 'red', 'color': 'white' });
        <?php }elseif(@$hasilVerifikasi->VAL->SURATBERHARGA == '1'){ ?> 
            $('.HartaSuratverif').css({ 'display': 'none' });
            $('.HartaSuratspan').attr( 'colspan', '9' );
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->KAS == '-1'){ ?>
            $('.navTabHarta').css({ 'background': 'red', 'color': 'white' });
            $('.navTabHartaKas').css({ 'background': 'red', 'color': 'white' });
        <?php }elseif(@$hasilVerifikasi->VAL->KAS == '1'){ ?> 
            $('.HartaKasverif').css({ 'display': 'none' });
            $('.HartaKasspan').attr( 'colspan', '7' );
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->HARTALAINNYA == '-1'){ ?>
            $('.navTabHarta').css({ 'background': 'red', 'color': 'white' });
            $('.navTabHartaLainnya').css({ 'background': 'red', 'color': 'white' });
        <?php }elseif(@$hasilVerifikasi->VAL->HARTALAINNYAS == '1'){ ?> 
            $('.HartaLainnyaverif').css({ 'display': 'none' });
            $('.HartaLainnyaspan').attr( 'colspan', '7' );
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->HUTANG == '-1'){ ?>
            $('.navTabHarta').css({ 'background': 'red', 'color': 'white' });
            $('.navTabHartaHutang').css({ 'background': 'red', 'color': 'white' });
        <?php }elseif(@$hasilVerifikasi->VAL->HUTANG == '1'){ ?> 
            $('.HartaHutangverif').css({ 'display': 'none' });
            $('.HartaHutangspan').attr( 'colspan', '6' );
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->PENERIMAANKAS == '-1'){ ?>
            $('.navTabPenerimaan').css({ 'background': 'red', 'color': 'white' });
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->PENGELUARANKAS == '-1'){ ?>
            $('.navTabPengeluaran').css({ 'background': 'red', 'color': 'white' });
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->PENERIMAANFASILITAS == '-1'){ ?>
            $('.navTabFasilitas').css({ 'background': 'red', 'color': 'white' });
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->PELEPASANHARTA == '-1'){ ?>
            $('.navTabReview').css({ 'background': 'red', 'color': 'white' });
            $('.navTabPelepasanHarta').css({ 'background': 'red', 'color': 'white' });
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->PENERIMAANHIBAH == '-1'){ ?>
            $('.navTabReview').css({ 'background': 'red', 'color': 'white' });
            $('.navTabPenerimaanHibah').css({ 'background': 'red', 'color': 'white' });
        <?php } ?>
        <?php if(@$hasilVerifikasi->VAL->DOKUMENPENDUKUNG == '-1'){ ?>
            $('.navTabReview').css({ 'background': 'red', 'color': 'white' });
            $('.navTabDokumenPendukung').css({ 'background': 'red', 'color': 'white' });
        <?php } ?>
    <?php } ?>
});

    function hartaalert(cekharta , i){
        link = cekharta[i].clas;
        var next = false;
        swal({
              title: cekharta[i].title,
              text: cekharta[i].message,
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Tidak!",
              cancelButtonText: "Ya!",
              closeOnConfirm: true,
              closeOnCancel: true
            },
            function(isConfirm){
              if (isConfirm) {
                $('#nav-tabs').find('a[href="#harta"]').trigger('click');
                $('#harta > .nav-tabs').find('a[href="'+link+'"]').trigger('click');
              } else {
                  resHarta.splice(i, 1);
                  if(resHarta.length == 1){
                      resHarta[0] = true;
                      $('#btn-submit').trigger('click');
                  }
                  if (typeof cekharta[i] !== 'undefined') {
                      urutan = i;
                      callhartaalert(cekharta , urutan);
                  };
              }
            });
    }

    function callhartaalert( array , i){

        setTimeout(function() { hartaalert(array , i); }, 500);
    }

    function chkHarta(e)
    {
        var ele = $(e.currentTarget);
        count(ele);
    }

    function count(ele)
    {
        var table = ele.closest('table');
        var box = ele.closest('.box-body');
        if($('.chk-tetap:checked', table).length > 0){
            $('.btn-remove-harta', box).show();
        }else{
            $('.btn-remove-harta', box).hide();
        }
    }

    function cekTetap()
    {
        var res = true;

        var array = [
            {
                'class' : 'hartatidak',
                'title' : 'Harta Tanah/Bangunan'
            },
            {
                'class' : 'hartaalat',
                'title' : 'Harta Mesin/Alat Transport'
            },
            {
                'class' : 'hartapra',
                'title' : 'Harta Bergerak'
            },
            {
                'class' : 'hartasurat',
                'title' : 'Surat Berharga'
            },
            {
                'class' : 'hartakas',
                'title' : 'KAS/Setara KAS'
            },
            {
                'class' : 'hartalain',
                'title' : 'Harta Lainnya'
            }
        ];

        $.each(array, function (k, v) {
            if($('.chk-tetap.'+ v.class +':not(:checked)').length > 0) {
                res = {
                    message: 'Terdapat data di tab "'+ v.title +'" yang belum Anda periksa',
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
        if(resHarta.length == 0) {
            resHarta[0] = true;

            var array = [
                {
                    'class': '#hartatidakbergerak',
                    'title': 'Harta Tanah/Bangunan',
                    'nilai': '<?=@$hartirak[0]->sum_hartirak?>'
                },
                {
                    'class': '#hartabergerak',
                    'title': 'Harta Mesin/Alat Transport',
                    'nilai': '<?=@$harger[0]->sum_harger?>'
                },
                {
                    'class': '#hartabergerakperabot',
                    'title': 'Harta Bergerak',
                    'nilai': '<?=@$harger2[0]->sum_harger2?>'
                },
                {
                    'class': '#suratberharga',
                    'title': 'Surat Berharga',
                    'nilai': '<?=@$suberga[0]->sum_suberga?>'
                },
                {
                    'class': '#kas',
                    'title': 'KAS/Setara KAS',
                    'nilai': '<?=@$kaseka[0]->sum_kaseka?>'
                },
                {
                    'class': '#hartalainnya',
                    'title': 'Harta Lainnya',
                    'nilai': '<?=@$harlin[0]->sum_harlin?>'
                },
                {
                    'class': '#hutang',
                    'title': 'Harta Hutang',
                    'nilai': '<?=@$_hutang[0]->sum_hutang?>'
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