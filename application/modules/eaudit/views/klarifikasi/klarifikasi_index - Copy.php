<style type="text/css">
.div-pribadi-dalam,.div-pribadi-luar{margin-top:20px;border-style:solid;border-width:1px;border-radius:5px;overflow:hidden}.div-pribadi-luar{border-color:rgba(158,158,158,.49)}.div-pribadi-dalam{background-color:#F7F7F7}.form-select2{background-color:rgba(255,255,255,0);margin-left:-10px}.judul-header,.judul-header-dalam{margin-left:-15px;margin-right:-15px;color:#000}.judul-header{background-color:#f0f0f0}.judul-header-dalam{background-color:rgba(192,192,192,0)}.bold{padding:0;margin:0;border:1px solid}.title-pribadi{background-color:rgba(191,30,46,0);font-style:italic;color:#000}.labelisi{text-align:left!important}
.depan {font-size: 13px}
</style>
<section class="content-header">
    <h1><i class="fa <?php echo $icon; ?>"></i> <?php echo $title; ?></h1>
    <?php echo $breadcrumb; ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
                <h4><?php echo $header_laporan; ?></h4>
                <label><?php echo $jenis_laporan; ?></label>
                <br>
                <ul class="nav nav-tabs depan">
                    <li class="active" id="li0"><a data-toggle="tab" href="#pribadi"><?php echo ICON_pribadi; ?> Pribadi</a></li>
                    <li id="li1"><a data-toggle="tab" href="#jabatan"><?php echo ICON_jabatan; ?> Jabatan</a></li>
                    <li id="li2"><a data-toggle="tab" href="#keluarga"><?php echo ICON_keluarga; ?> Keluarga</a></li>
                    <li id="li3"><a data-toggle="tab" href="#harta"><?php echo ICON_harta; ?> Harta</a></li>
                    <li id="li4"><a data-toggle="tab" href="#penerimaan"><?php echo ICON_penerimaankas; ?> Penerimaan</a></li>
                    <li id="li5"><a data-toggle="tab" href="#pengeluaran"><?php echo ICON_pengeluarankas; ?> Pengeluaran</a></li>
                    <li id="li6"><a data-toggle="tab" href="#fasilitas"><?php echo ICON_fasilitas; ?> Fasilitas</a></li>
                    <li id="li7"><a data-toggle="tab" href="#lampiran"><?php echo ICON_lampiran; ?> Lampiran</a></li>
                    <li id="li8"><a data-toggle="tab" href="#catatan"><i class="fa fa-edit"></i> Catatan</a></li>
                    <li id="li9"><a data-toggle="tab" href="#final"><?php echo ICON_final; ?> Final</a></li>
                </ul>
                <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                    <div id="pribadi" class="tab-pane fade in active">
                        <?php require_once('klarifikasi_tabel_pribadi.php'); ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-default btnCancel"><i class="fa fa-arrow-left"></i> Cancel</button>
                            <button type="button" class="btn btn-sm btn-info btnNextPribadi">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="jabatan" class="tab-pane fade">
                        <?php require_once('klarifikasi_tabel_jabatan.php'); ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevJabatan"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info btnNextJabatan">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="keluarga" class="tab-pane fade">
                        <?php require_once('klarifikasi_tabel_keluarga.php'); ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevKeluarga"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info btnNextKeluarga">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="harta" class="tab-pane fade">
                        <ul class="nav nav-tabs" role="tablist">
                            <li id="lii0" role="presentation" class="active"><a href="#hartatidakbergerak" aria-controls="hartatidakbergerak" role="tab" data-toggle="tab" class="navTab navTabHartaTidakBergerak" title="Tanah / Bangunan"><?php echo ICON_hartatidakbergerak; ?> <span>Tanah / Bangunan</span></a></li>
                            <li  id="lii1" role="presentation"><a href="#hartabergerak" aria-controls="hartabergerak" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak" title="Alat Transportasi / Mesin"><?php echo ICON_hartabergerak; ?> <span>Mesin / Alat Transport</span></a></li>
                            <li id="lii2" role="presentation"><a href="#hartabergerakperabot" aria-controls="hartabergerakperabot" role="tab" data-toggle="tab" class="navTab navTabHartaBergerak2" title="Perabot"><?php echo ICON_hartabergerakperabot; ?> <span>Bergerak</span></a></li>
                            <li id="lii3" role="presentation"><a href="#suratberharga" aria-controls="suratberharga" role="tab" data-toggle="tab" class="navTab navTabHartaSurat" title="Surat Berharga"><?php echo ICON_suratberharga; ?> <span>Surat Berharga</span></a></li>
                            <li id="lii4" role="presentation"><a href="#kas" aria-controls="kas" role="tab" data-toggle="tab" class="navTab navTabHartaKas" title="Kas / Setara Kas"><?php echo ICON_kas; ?> <span>KAS / Setara KAS</span></a></li>
                            <li id="lii5" role="presentation"><a href="#hartalainnya" aria-controls="hartalainnya" role="tab" data-toggle="tab" class="navTab navTabHartaLainnya" title="Harta Lainnya"><?php echo ICON_hartalainnya; ?> <span>Harta Lainnya</span></a></li>
                            <li id="lii6" role="presentation"><a href="#hutang" aria-controls="hutang" role="tab" data-toggle="tab" class="navTab navTabHartaHutang" title="Data Hutang"><?php echo ICON_hutang; ?> <span>Hutang</span></a></li>
                        </ul>

                        <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                            <div role="tabpanel" class="tab-pane active" id="hartatidakbergerak">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hartatidakbergerak.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHTB"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHTB">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="hartabergerak">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hartabergerak.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHB"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHB">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="hartabergerakperabot">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hartabergeraklain.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHBL"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHBL">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="suratberharga">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_suratberharga.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevSB"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextSB">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="kas">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_kas.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevKas"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextKas">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="hartalainnya">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hartalainnya.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHL"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHL">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="hutang">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hutang.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHutang"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHutang">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div id="penerimaan" class="tab-pane fade">
                        <div class="contentTab">
                            <?php require_once('klarifikasi_tabel_penerimaankas2.php'); ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevPenerimaan"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info btnNextPenerimaan">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="pengeluaran" class="tab-pane fade">
                        <div class="contentTab">
                            <?php require_once('klarifikasi_tabel_pengeluarankas2.php'); ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevPengeluaran"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info btnNextPengeluaran">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="fasilitas" class="tab-pane fade">
                        <?php require_once('klarifikasi_tabel_fasilitas.php'); ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevFasilitas"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info btnNextFasilitas">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="lampiran" class="tab-pane fade">
                        <ul class="nav nav-tabs" role="tablist">
                            <li id="liii0" role="presentation" class="active"><a href="#pelepasanharta" aria-controls="pelepasanharta" role="tab" data-toggle="tab" class="navTab navTabPelepasan" title="Pelepasan Harta"><?php echo ICON_pelepasanharta; ?> <span>Penjualan/Pelepasan Harta </span></a></li>
                            <li id="liii1" role="presentation"><a href="#penerimaanhibah" aria-controls="penerimaanhibah" role="tab" data-toggle="tab" class="navTab navTabPenerimaanHibah" title="Penerimaan Hibah"><?php echo ICON_penerimaanhibah; ?> <span>Penerimaan Hibah</span></a></li>
                            <li id="liii2" role="presentation"><a href="#suratkuasamengumumkan" aria-controls="suratkuasamengumumkan" role="tab" data-toggle="tab" class="navTab navTabSuratKuasaMengumumkan" title="Surat Kuasa"><?php echo ICON_suratkuasamengumumkan; ?> <span>Surat Kuasa</span></a></li>
                        </ul>

                        <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                            <div role="tabpanel" class="tab-pane active" id="pelepasanharta">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_lampiran_1.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevPelepasan"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextPelepasan">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="penerimaanhibah">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_lampiran_1_1.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHibah"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHibah">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="suratkuasamengumumkan">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_surat_kuasa.php');?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevSKM"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextSKM">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div id="catatan" class="tab-pane fade">
                        <div class="contentTab">
                            <div class="box-header with-border portlet-header title-pribadi">
                                <h5 class="">"Catatan Pemeriksaan"</h5>
                            </div>
                            <div class="box-body" id="wrapperHutang">
                                <form class="form-horizontal" id="frmcatatan">
                                    <input type="hidden" id="id_catatan" value="<?php echo $new_id_lhkpn; ?>">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <textarea id="catatan_pemeriksaan" style="width: 100%" rows="10" name="catatan_pemeriksaan"><?php echo $t_lhkpn->CATATAN_PEMERIKSAAN; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12" align="right">
                                            <button id="btn-simpan-catatan" class="btn btn-sm btn-danger"><i class="fa fa-save"></i> Simpan Catatan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevCatatan"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <!-- <button type="button" class="btn btn-sm btn-warning btnSaveDraft"><i class="fa fa-save"></i> Simpan Draft</button> -->
                            <button type="button" class="btn btn-sm btn-info btnNextCatatan"><i class="fa fa-arrow-right"></i> Selanjutnya</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="final" class="tab-pane fade">
                        <div class="final-panel final-note">
                            <br>
                            <p>&nbsp; Mohon pastikan kembali semua data telah sesuai dengan data hasil klarifikasi yang telah dilakukan sebelum melakukan simpan final klarifikasi.</p>
                            <p>&nbsp; <i class="fa fa-exclamation-triangle danger"></i> Anda tidak dapat menginput hasil klarifikasi kembali apabila anda sudah mengeklik tombol "Simpan Final Klarifikasi"</p>
                            <br>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevFinal"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-warning btnSaveDraft"><i class="fa fa-save"></i> Simpan Draft</button>
                            <button type="button" class="btn btn-sm btn-success btnSaveFinal"><i class="fa fa-send-o"></i> Simpan Final Klarifikasi</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style type="text/css">
    .final-note { background-color: #ffffcc; border-left: 6px solid #ffeb3b; }
    .final-panel { margin-top: 16px; margin-bottom: 16px; }
</style>
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replace( 'catatan_pemeriksaan' );

        $("#btn-simpan-catatan").click(function(e) {
            e.preventDefault();
            var data = {
              'id_lhkpn': $('#id_catatan').val(),
              'catatan_pemeriksaan': CKEDITOR.instances.catatan_pemeriksaan.getData()
            }
            var url = '<?php echo base_url() ?>eaudit/klarifikasi/saveCatatan';
            var sub = $.post(url, data);
            sub.done(function (data) {
                data == 1 ? alertify.success('Data Berhasil Disimpan') : alertify.error('Data Gagal Disimpan');
            });
        });

        <?php if ($upperli): ?>
            $("li#<?php echo $upperli; ?>").find('a').trigger('click');
        <?php if ($bottomli): ?>
            $("li#<?php echo $bottomli; ?>").find('a').trigger('click');
        <?php endif; ?>
        <?php endif; ?>
        $('.btnCancel').click(function() {
            url = 'index.php/eaudit/pemeriksaan/index/lhkpn';
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });
        $('.btnNextPribadi').click(function() {
            $( "li#li0").next('li').find('a').trigger('click');
        });
        $('.btnPrevJabatan').click(function() {
            $( "li#li1").prev('li').find('a').trigger('click');
        });
        $('.btnNextJabatan').click(function() {
            $( "li#li1").next('li').find('a').trigger('click');
        });
        $('.btnPrevKeluarga').click(function() {
            $( "li#li2").prev('li').find('a').trigger('click');
        });
        $('.btnNextKeluarga').click(function() {
            $( "li#li2").next('li').find('a').trigger('click');
        });
        $('.btnPrevHTB').click(function() {
            $( "li#li3").prev('li').find('a').trigger('click');
        });
        $('.btnNextHTB').click(function() {
            $( "li#lii0").next('li').find('a').trigger('click');
        });
        $('.btnPrevHB').click(function() {
            $( "li#lii1").prev('li').find('a').trigger('click');
        });
        $('.btnNextHB').click(function() {
            $( "li#lii1").next('li').find('a').trigger('click');
        });
        $('.btnPrevHBL').click(function() {
            $( "li#lii2").prev('li').find('a').trigger('click');
        });
        $('.btnNextHBL').click(function() {
            $( "li#lii2").next('li').find('a').trigger('click');
        });
        $('.btnPrevSB').click(function() {
            $( "li#lii3").prev('li').find('a').trigger('click');
        });
        $('.btnNextSB').click(function() {
            $( "li#lii3").next('li').find('a').trigger('click');
        });
        $('.btnPrevKas').click(function() {
            $( "li#lii4").prev('li').find('a').trigger('click');
        });
        $('.btnNextKas').click(function() {
            $( "li#lii4").next('li').find('a').trigger('click');
        });
        $('.btnPrevHL').click(function() {
            $( "li#lii5").prev('li').find('a').trigger('click');
        });
        $('.btnNextHL').click(function() {
            $( "li#lii5").next('li').find('a').trigger('click');
        });
        $('.btnPrevHutang').click(function() {
            $( "li#lii6").prev('li').find('a').trigger('click');
        });
        $('.btnNextHutang').click(function() {
            $( "li#li3").next('li').find('a').trigger('click');
        });
        $('.btnPrevPenerimaan').click(function() {
            $( "li#li4").prev('li').find('a').trigger('click');
        });
        $('.btnNextPenerimaan').click(function() {
            $( "li#li4").next('li').find('a').trigger('click');
        });
        $('.btnPrevPengeluaran').click(function() {
            $( "li#li5").prev('li').find('a').trigger('click');
        });
        $('.btnNextPengeluaran').click(function() {
            $( "li#li5").next('li').find('a').trigger('click');
        });
        $('.btnPrevFasilitas').click(function() {
            $( "li#li6").prev('li').find('a').trigger('click');
        });
        $('.btnNextFasilitas').click(function() {
            $( "li#li6").next('li').find('a').trigger('click');
        });
        $('.btnPrevPelepasan').click(function() {
            $( "li#li7").prev('li').find('a').trigger('click');
        });
        $('.btnNextPelepasan').click(function() {
            $( "li#liii0").next('li').find('a').trigger('click');
        });
        $('.btnPrevHibah').click(function() {
            $( "li#liii1").prev('li').find('a').trigger('click');
        });
        $('.btnNextHibah').click(function() {
            $( "li#liii1").next('li').find('a').trigger('click');
        });
        $('.btnPrevSKM').click(function() {
            $( "li#liii2").prev('li').find('a').trigger('click');
        });
        $('.btnNextSKM').click(function() {
            $( "li#li7").next('li').find('a').trigger('click');
        });
        $('.btnPrevCatatan').click(function() {
            $( "li#li8").prev('li').find('a').trigger('click');
        });
        $('.btnNextCatatan').click(function() {
            $( "li#li8").next('li').find('a').trigger('click');
        });
        $('.btnPrevFinal').click(function() {
            $( "li#li9").prev('li').find('a').trigger('click');
        });

        $('.btnSaveDraft').click(function(e) {
          e.preventDefault();
          var id = '<?php echo $ID_LHKPN; ?>';
          var no_st = '<?php echo $NO_ST; ?>';
          confirm("Simpan data input klarifikasi sebagai <b><i>Draft</i></b>?", function () {
              $('#loader_area').show();
              $.ajax({
                  url: '<?php echo base_url(); ?>eaudit/klarifikasi/saveDraftKlarifikasi',
                  type: 'POST',
                  data: { 'id':id, 'no_st': no_st },
                  success: function (data) {
                      $('#loader_area').hide();
                      var obj = jQuery.parseJSON(data);
                      if (obj.success == 1) {
                          alertify.success(obj.msg);
                          // window.location = "<?php echo base_url(); ?>#index.php/eaudit/periksa/index/lhkpn";
                          // window.location.reload(windows.history.back());
                          url = 'index.php/eaudit/periksa/index/';
                          ng.LoadAjaxContent(url);
                      }
                      else{
                          alertify.error(obj.msg);
                      }
                  }
              });

          }, "Konfirmasi simpan data Klarifikasi", undefined, "YA", "TIDAK");
        });

        $('.btnSaveFinal').click(function() {
            var id = '<?php echo $ID_LHKPN; ?>';
            var no_st = '<?php echo $NO_ST; ?>';
            confirm("Anda yakin akan menyimpan hasil klarifikasi ini sebagai final?", function () {
                $('#loader_area').show();
                $.ajax({
                    url: '<?php echo base_url(); ?>eaudit/klarifikasi/savefinalklarifikasi',
                    type: 'POST',
                    data: { 'id':id, 'no_st': no_st },
                    success: function (data) {
                        $('#loader_area').hide();
                        var obj = jQuery.parseJSON(data);
                        if (obj.success == 1) {
                          alertify.success(obj.msg);
                          // window.location = "<?php echo base_url(); ?>#index.php/eaudit/periksa/index/lhkpn";
                          url = 'index.php/eaudit/periksa/index/';
                          ng.LoadAjaxContent(url);
                        }
                        else{
                            alertify.error(obj.msg);
                        }
                    }
                });

            }, "Konfirmasi simpan data Klarifikasi", undefined, "YA", "TIDAK");
        });

    });
</script>
