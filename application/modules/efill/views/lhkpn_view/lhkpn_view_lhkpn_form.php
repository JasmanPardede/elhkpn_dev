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
<?php
if($display=='detail'){
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
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    <i class="fa <?php echo $icon;?>"></i> <?php echo $title;?>
    <small><?php //echo $title;?></small>
    </h1>
    <?php echo $breadcrumb;?>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
<!--                 <pre>
                Nama PN : <?php echo $LHKPN->NAMA;?><br>
                Jabatan : <?php echo $LHKPN->JABATAN;?><br>
                Laporan : <?php echo $LHKPN->JENIS_LAPORAN;?> <?php echo $LHKPN->TGL_LAPOR;?><br>
                Tanggal Submit : <?php echo $LHKPN->SUBMITED_DATE;?><br>
                </pre> -->
                <label><input type="checkbox" onclick="if($(this).is(':checked')){$('.navTab span').show();}else{$('.navTab span').hide();}" checked=""> Tampilkan Tab Label</label>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <!-- <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li> -->
                        <li role="presentation" class="active"><a href="#datapribadi" aria-controls="datapribadi" role="tab" data-toggle="tab" class="navTab" title="Data Pribadi"><?php echo ICON_pribadi;?> <span>Data Pribadi</span></a></li>
                        <li role="presentation"><a href="#jabatan" aria-controls="jabatan" role="tab" data-toggle="tab" class="navTab" title="Data Jabatan"><?php echo ICON_jabatan;?> <span>Jabatan</span></a></li>
                        <li role="presentation"><a href="#keluarga" aria-controls="keluarga" role="tab" data-toggle="tab" class="navTab" title="Data Keluarga"><?php echo ICON_keluarga;?> <span>Keluarga</span></a></li>
                        <li role="presentation"><a id="link_harta" href="#harta" aria-controls="harta" role="tabharta" data-toggle="tab" class="navTab" title="Data Harta"><?php echo ICON_harta;?> <span>Harta</span></a></li>
                        <li role="presentation"><a href="#penerimaankas" aria-controls="penerimaankas" role="tab" data-toggle="tab" class="navTab" title="Data Penerimaan Kas"><?php echo ICON_penerimaankas;?> <span>Penerimaan</span></a></li>
                        <li role="presentation"><a href="#pengeluarankas" aria-controls="pengeluarankas" role="tab" data-toggle="tab" class="navTab" title="Data Pengeluaran Kas"><?php echo ICON_pengeluarankas;?> <span>Pengeluaran</span></a></li>
                        <li role="presentation"><a href="#fasilitas" aria-controls="fasilitas" role="tab" data-toggle="tab" class="navTab" title="Penerimaan Fasilitas"><?php echo ICON_fasilitas;?> <span>Fasilitas</span></a></li>
                        <li role="presentation"><a href="#reviewlampiran" aria-controls="reviewlampiran" role="tab" data-toggle="tab" class="navTab" title="Review lampiran"><?php echo ICON_lampiran;?> <span>Review Lampiran</span></a></li>
                        <li role="presentation"><a href="#final" aria-controls="final" role="tab" data-toggle="tab" class="navTab" title="Review Sebelum Submit"><?php echo ICON_final;?> <span>Review Submit</span></a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                        <!--  -->
                        <div role="tabpanel" class="tab-pane active" id="datapribadi">
                            <?php require_once('lhkpn_view_tabel_pribadi.php');?>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-primary btnPrevious"><i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-sm btn-primary btnNext">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="jabatan">
                            <?php require_once('lhkpn_view_tabel_jabatan.php');?>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPrevious"><i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-sm btn-warning btnNext">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="keluarga">
                            <?php require_once('lhkpn_view_tabel_keluarga.php');?>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPrevious"><i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-sm btn-warning btnNextKeluarga">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="harta">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a id="link_harta_pertama" href="#hartatidakbergerak" aria-controls="hartatidakbergerak" role="tab" data-toggle="tab" class="navTab" title="Tanah / Bangunan"><?php echo ICON_hartatidakbergerak;?> <span>Tanah / Bangunan</span></a></li>
                                <li role="presentation"><a href="#hartabergerak" aria-controls="hartabergerak" role="tab" data-toggle="tab" class="navTab" title="Alat Transportasi / Mesin"><?php echo ICON_hartabergerak;?> <span>Alat Transportasi / Mesin</span></a></li>
                                <li role="presentation"><a href="#hartabergerakperabot" aria-controls="hartabergerakperabot" role="tab" data-toggle="tab" class="navTab" title="Perabot"><?php echo ICON_hartabergerakperabot;?> <span>Bergerak Lainnya</span></a></li>
                                <li role="presentation"><a href="#suratberharga" aria-controls="suratberharga" role="tab" data-toggle="tab" class="navTab" title="Surat Berharga"><?php echo ICON_suratberharga;?> <span>Surat Berharga</span></a></li>
                                <li role="presentation"><a href="#kas" aria-controls="kas" role="tab" data-toggle="tab" class="navTab" title="Kas / Setara Kas"><?php echo ICON_kas;?> <span>KAS / Setara KAS</span></a></li>
                                <li role="presentation"><a href="#hartalainnya" aria-controls="hartalainnya" role="tab" data-toggle="tab" class="navTab" title="Harta Lainnya"><?php echo ICON_hartalainnya;?> <span>Harta Lainnya</span></a></li>
                                <li role="presentation"><a href="#hutang" aria-controls="hutang" role="tab" data-toggle="tab" class="navTab" title="Data Hutang"><?php echo ICON_hutang;?> <span>Hutang</span></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                <!--  -->
                                <div role="tabpanel" class="tab-pane active" id="hartatidakbergerak">
                                    <?php require_once('lhkpn_view_tabel_hartatidakbergerak.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHartaBangunan"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextHarta">Selanjutnya <i class="fa fa-forkward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="hartabergerak">
                                    <?php require_once('lhkpn_view_tabel_hartabergerak.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="hartabergerakperabot">
                                    <?php require_once('lhkpn_view_tabel_hartabergerakperabot.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextHarta">Selanjutnya <i class="fa fa-backward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="suratberharga">
                                    <?php require_once('lhkpn_view_tabel_suratberharga.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="kas">
                                    <?php require_once('lhkpn_view_tabel_kas.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="hartalainnya">
                                    <?php require_once('lhkpn_view_tabel_hartalainnya.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextHarta">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="hutang">
                                    <?php require_once('lhkpn_view_tabel_hutang.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextHutang">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div><!-- close Harta -->
                        <div role="tabpanel" class="tab-pane" id="fasilitas">
                            <div class="contentTab">
                                <?php require_once('lhkpn_view_tabel_penerimaanfasilitas.php');?>
                            </div>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-warning btnPreviousFasilitas"><i class="fa fa-backward"></i> Sebelumnya</button>
                                <button type="button" class="btn btn-sm btn-warning btnNextFasilitas">Selanjutnya <i class="fa fa-forward"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="reviewlampiran">
                            <?php //require_once('lhkpn_view_tabel_lampiran.php');?>

                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#pelepasanharta" aria-controls="pelepasanharta" role="tab" data-toggle="tab" class="navTab" title="Pelepasan Harta"><?php echo ICON_pelepasanharta;?> <span>Penjualan/Pelepasan Harta </span></a></li>
                                <li role="presentation"><a href="#penerimaanhibah" aria-controls="penerimaanhibah" role="tab" data-toggle="tab" class="navTab" title="Penerimaan Hibah"><?php echo ICON_penerimaanhibah;?> <span>Penerimaan Hibah</span></a></li>
                                <!-- <li role="presentation"><a href="#penerimaanfasilitas" aria-controls="penerimaanfasilitas" role="tab" data-toggle="tab" class="navTab" title="Penerimaan Fasilitas"><?php echo ICON_penerimaanfasilitas;?> <span>Penerimaan Fasilitas</span></a></li>
                                <li role="presentation"><a href="#suratkuasamengumumkan" aria-controls="suratkuasamengumumkan" role="tab" data-toggle="tab" class="navTab" title="Surat Kuasa Mengumumkan"><?php echo ICON_suratkuasamengumumkan;?> <span>Surat Kuasa Mengumumkan</span></a></li> -->
                                <li role="presentation"><a href="#dokumenpendukung" aria-controls="dokumenpendukung" role="tab" data-toggle="tab" class="navTab" title="Kas / Setara Kas"><?php echo ICON_dokumenpendukung;?> <span>Dokumen Pendukung</span></a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                                <!--  -->
                                <div role="tabpanel" class="tab-pane active" id="pelepasanharta">
                                    <?php require_once('lhkpn_view_tabel_pelepasanharta.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousPelepasanHarta"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextPelepasanHarta">Selanjutnya <i class="fa fa-backward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--  -->
                                <div role="tabpanel" class="tab-pane" id="penerimaanhibah">
                                    <?php require_once('lhkpn_view_tabel_penerimaanhibah.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-warning btnPreviousPenerimaanHibah"><i class="fa fa-backward"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-sm btn-warning btnNextPenerimaanHibah">Selanjutnya <i class="fa fa-forward"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!-- <div role="tabpanel" class="tab-pane" id="penerimaanfasilitas">
                                    <?php //require_once('lhkpn_view_tabel_penerimaanfasilitas.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-primary btnPreviousLampiran">Previous</button>
                                        <button type="button" class="btn btn-sm btn-primary btnNextLampiran">Next</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="suratkuasamengumumkan">
                                    <?php //require_once('lhkpn_view_tabel_suratkuasamengumumkan.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-primary btnPreviousLampiran">Previous</button>
                                        <button type="button" class="btn btn-sm btn-primary btnNextLampiran">Next</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div> -->
                                <div role="tabpanel" class="tab-pane" id="dokumenpendukung">
                                    <?php require_once('lhkpn_view_tabel_dokumenpendukung.php');?>
                                    <br>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-sm btn-primary btnPreviousDokpendukung">Previous</button>
                                        <button type="button" class="btn btn-sm btn-primary btnNextDokpendukung">Next</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="penerimaankas">
                            <?php require_once('lhkpn_view_tabel_penerimaankas.php');?>
                            <br>

                            <div class="clearfix"></div>
                        </div>
                        <!--  -->
                        <div role="tabpanel" class="tab-pane" id="pengeluarankas">
                            <?php require_once('lhkpn_view_tabel_pengeluarankas.php');?>
                            <br>
                            
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="final">
                            <?php require_once('lhkpn_view_tabel_ringkasan.php');?>
                            <br>
                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-primary btnPreviousFinal">Previous</button>
                            </div>
                            <div class="clearfix">
                        </div>
                    </div>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">
$(document).ready(function(){
    $('.navTab').tooltip();

	$('.btnNext').click(function(){
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	});

    $('.btnPrevious').click(function(){
	  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
	});	
	
	$('.btnNextKeluarga').click(function(){
	  $('#link_harta').click();
	  $('#link_harta_pertama').click();
	});

    $('.btnPreviousHartaBangunan').click(function(e){
        e.preventDefault();
      $('.nav-tabs > .active').prev('li').find('a').trigger('click');
    });

    $('.btnNextHarta').click(function(e){
      e.preventDefault();
      $('#harta > .nav-tabs > .active').next('li').find('a').trigger('click');
    });

    $('.btnPreviousHarta').click(function(e){
        e.preventDefault();
      $('#harta > .nav-tabs > .active').prev('li').find('a').trigger('click');
    });

	$('.btnNextHutang').click(function(e){
	  e.preventDefault();
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
      $('#wrapperPenerimaan > .nav-tabs').find('a[href="#tabsA"]').trigger('click');
	});

    $('.btnNextLampiran').click(function(e){
      e.preventDefault();
      $('#lampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
    });
    
    $('.btnPreviousLampiran').click(function(e){
        e.preventDefault();
      $('#lampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
    });

    $('.btnPreviousPelepasanHarta').click(function(e){
        e.preventDefault();
      $('.nav-tabs > .active').prev('li').find('a').trigger('click');
    });

    $('.btnPreviousPenerimaanHibah').click(function(e){
        e.preventDefault();
        $('#reviewlampiran > .nav-tabs > .active').prev('li').find('a[href="#pelepasanharta"]').trigger('click');
    });

    $('.btnNextPenerimaanHibah').click(function(e){
        e.preventDefault();
        $('#reviewlampiran > .nav-tabs > .active').next('li').find('a[href="#dokumenpendukung"]').trigger('click');
    });

    $('.btnNextPelepasanHarta').click(function(e){
      e.preventDefault();
      $('#reviewlampiran > .nav-tabs > .active').next('li').find('a[href="#penerimaanhibah"]').trigger('click');
    });

    $('.btnPreviousDokpendukung').click(function(e){
        e.preventDefault();
      $('#reviewlampiran > .nav-tabs > .active').prev('li').find('a[href="#penerimaanhibah"]').trigger('click');
    });

    $('.btnNextDokpendukung').click(function(e){
      e.preventDefault();
      $('.nav-tabs > .active').next('li').find('a').trigger('click');
    });

    $('.btnPreviousFinal').click(function(e){
        e.preventDefault();
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        $('#reviewlampiran > .nav-tabs').find('a[href="#dokumenpendukung"]').trigger('click');
    });

    $('.btnPreviousFasilitas').click(function(e){
        e.preventDefault();
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        $('#wrapperPengeluaran > .nav-tabs').find('a[href="#tabs3C"]').trigger('click');
    });

    $('.btnNextFasilitas').click(function(e){
        e.preventDefault();
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
        $('#reviewlampiran > .nav-tabs').find('a[href="#pelepasanharta"]').trigger('click');
    });

});

</script>

<style type="text/css">
 .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus{
        color: #fff;
        background-color: #3C8DBC;  
    } 
 .headerVerFinal{
 	font-weight: bold;
 }
</style>

	<?php
}
?>