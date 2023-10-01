<style type="text/css">
.div-pribadi-dalam,.div-pribadi-luar{margin-top:20px;border-style:solid;border-width:1px;border-radius:5px;overflow:hidden}.div-pribadi-luar{border-color:rgba(158,158,158,.49)}.div-pribadi-dalam{background-color:#F7F7F7}.form-select2{background-color:rgba(255,255,255,0);margin-left:-10px}.judul-header,.judul-header-dalam{margin-left:-15px;margin-right:-15px;color:#000}.judul-header{background-color:#f0f0f0}.judul-header-dalam{background-color:rgba(192,192,192,0)}.bold{padding:0;margin:0;border:1px solid}.title-pribadi{background-color:rgba(191,30,46,0);font-style:italic;color:#000}.labelisi{text-align:left!important}
.depan {font-size: 13px}
.duatujuh {
    position: absolute;
    top: 65px;
    padding: 20px;
}
</style>
<section class="content-header">
    <h1><i class="fa <?php echo $icon; ?>"></i> <?php echo $title; ?></h1>
    <?php echo $breadcrumb; ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
                <div class="col-md-12" style="padding: 0;">
                    <div class="col-md-6" style="padding: 0;">
                        <h4><?php echo $header_laporan; ?></h4>
                        <label><?php echo $jenis_laporan; ?></label>
                    </div>
                    <div class="col-md-6 text-right" style="padding: 0;">
                        <h4><?php echo $tanggal_klarifikasi; ?></h4>
                        <button class="btn btn-success btn-sm btn-edit" title="Edit" id="btnEditTglKlarifikasi" href="<?php echo str_replace('http://', 'http://', site_url('index.php/eaudit/periksa/ajax_input_tanggal/')).'/';?>" data-idlhkpn="<?= $t_lhkpn_klarifikasi->ID_LHKPN; ?>" ><i class="fa fa-edit"></i> Edit Tanggal Klarifikasi</button>
                        <button class="btn btn-danger btn-sm btn-cancel" title="Cancel" id="btnCancelKlarifikasi" data-idlhkpn="<?= $new_id_lhkpn; ?>" data-idaudit="<?= $id_audit; ?>" ><i class="fa fa-close"></i> Batalkan Klarifikasi</button>
                    </div>
                </div>
                <?php 
                    $roles = explode(',', $this->session->userdata()['ID_ROLE_AUDIT']);
                    // $role = $this->session->userdata('ID_ROLE');
                    if (in_array('25', $roles)) {
                    // if ( $role == '25') {
                ?>
<!--                <div class="col-md-12 text-right duatujuh" >
                    <button class="btn btn-danger btn-sm btn-cancel" title="Cancel" id="btnCancelKlarifikasi" data-idlhkpn="<?= $new_id_lhkpn; ?>" data-idaudit="<?= $id_audit; ?>" ><i class="fa fa-close"></i> Batalkan Klarifikasi</button>
                </div>-->
                <?php 
                    }
                ?>
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
                    <li id="li10"><a data-toggle="tab" href="#reviewharta"><?php echo ICON_final; ?> Review Harta</a></li>
                    <li id="li8"><a data-toggle="tab" href="#catatan"><i class="fa fa-edit"></i> Catatan</a></li>
                    <li id="li9"><a data-toggle="tab" href="#final"><?php echo ICON_final; ?> Final</a></li>
                </ul>
                <div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
                    <div id="pribadi" class="tab-pane fade in active">
                        <?php require_once('klarifikasi_tabel_pribadi.php'); ?>
                        <div class="pull-right">
                            <!-- <button type="button" class="btn btn-sm btn-default btnCancel"><i class="fa fa-arrow-left"></i> Cancel</button> -->
                            <button type="button" class="btn btn-sm btn-info btnNext">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="jabatan" class="tab-pane fade">
                        <?php require_once('klarifikasi_tabel_jabatan.php'); ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevious"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info btnNext">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="keluarga" class="tab-pane fade">
                        <?php require_once('klarifikasi_tabel_keluarga.php'); ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevious"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
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
                                    <button type="button" class="btn btn-sm btn-info btnNextHarta">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="hartabergerak">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hartabergerak.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHarta"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHarta">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="hartabergerakperabot">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hartabergeraklain.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHarta"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHarta">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="suratberharga">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_suratberharga.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHarta"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHarta">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="kas">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_kas.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHarta"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHarta">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="hartalainnya">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hartalainnya.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHarta"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextHarta">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="hutang">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_hutang.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevHarta"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNext">Selanjutnya <i class="fa fa-arrow-right"></i></button>
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
                            <button type="button" class="btn btn-sm btn-info" id="btnPrevPenerimaan"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info" id="btnNextPenerimaan">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="pengeluaran" class="tab-pane fade">
                        <div class="contentTab">
                            <?php require_once('klarifikasi_tabel_pengeluarankas2.php'); ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info" id="btnPrevPengeluaran"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info" id="btnNextPengeluaran">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="fasilitas" class="tab-pane fade">
                        <?php require_once('klarifikasi_tabel_fasilitas.php'); ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevious"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
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
                                    <button type="button" class="btn btn-sm btn-info btnNextLampiran">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <div role="tabpanel" class="tab-pane" id="penerimaanhibah">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_lampiran_1_1.php'); ?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevLampiran"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNextLampiran">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="suratkuasamengumumkan">
                                <div class="contentTab">
                                    <?php require_once('klarifikasi_tabel_surat_kuasa.php');?>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-sm btn-info btnPrevLampiran"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                                    <button type="button" class="btn btn-sm btn-info btnNext">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div id="reviewharta" class="tab-pane fade">
                        <div class="contentTab">
                            <?php require_once('klarifikasi_tabel_review.php'); ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevReviewHarta"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-info btnNext">Selanjutnya <i class="fa fa-arrow-right"></i></button>
                        </div>
                        <div class="clearfix"></div>
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
                            <button type="button" class="btn btn-sm btn-info btnPrevious"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <!-- <button type="button" class="btn btn-sm btn-warning btnSaveDraft"><i class="fa fa-save"></i> Simpan Draft</button> -->
                            <button type="button" class="btn btn-sm btn-info btnNext"><i class="fa fa-arrow-right"></i> Selanjutnya</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="final" class="tab-pane fade">
                        <div class="final-panel final-note">
                            <br>
                            <p>&nbsp; Sebelum melakukan Simpan & Kirim, lakukan pengecekan ikhtisar harta yang telah diinput.</p>
                            <p>&nbsp; <i class="fa fa-exclamation-triangle danger"></i> Untuk mencetak dan melihat ikhtisar harta klarifikasi, silahkan klik logo ini. <a class="btn btn-xs btn-modal-ikhtisar btn-success" title="cetak ikhtisar" href="<?php echo site_url("/index.php/ever/ikthisar/cetak_klarifikasi"); ?>/<?php echo $new_id_lhkpn; ?>"><i class="fa fa-print"></i> Ikhtisar Harta Klarifikasi</a>
                            <!--<p>&nbsp; <i class="fa fa-exclamation-triangle danger"></i> Untuk mencetak dan melihat berita acara klarifikasi, silahkan klik logo ini. <a class="btn btn-xs btn-modal-ikhtisar btn-success" title="cetak ikhtisar" href="<?php // echo site_url("/index.php/eaudit/klarifikasi/cetak_bak"); ?>/<?php // echo $new_id_lhkpn.'/'.$id_audit; ?>"><i class="fa fa-print"></i> BAK</a>-->
							</p> 
							<br>
							<p>&nbsp; Mohon pastikan kembali semua data telah sesuai dengan data hasil klarifikasi yang telah dilakukan sebelum melakukan Simpan & Kirim.</p>
                            <p>&nbsp; <i class="fa fa-exclamation-triangle danger"></i> Anda tidak dapat menginput hasil klarifikasi kembali apabila anda sudah klik tombol "Simpan & Kirim"</p>
							
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-sm btn-info btnPrevious"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                            <!-- <button type="button" class="btn btn-sm btn-warning btnSaveDraft"><i class="fa fa-save"></i> Simpan Draft</button> -->
                            <button type="button" class="btn btn-sm btn-success btnSaveFinal"><i class="fa fa-send-o"></i> Simpan & Kirim</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ModalWarning" class="modal fade" role="dialog">
            <div id="modal-warning" class='modal-dialog' style="margin:15% auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Pemberitahuan</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <i class="fa fa-warning" aria-hidden"true"=""></i>
                            <span id="notif-text"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                            <i class="fa fa-remove"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="ModalSuccess" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div id="modal-success" class='modal-dialog' style="margin:15% auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Pemberitahuan</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fa fa-check" aria-hidden"true"=""></i>
                            <span id="notif-text"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger" id="btn-close" data-dismiss="modal">
                            <i class="fa fa-remove"></i> Tutup
                        </button>
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
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js?v=<?=$this->config->item('cke_version');?>"></script>
<script src="<?php echo base_url(); ?>plugins/ckeditor/additional-setting.js?v=<?=$this->config->item('cke_version');?>"></script>
<script>
var title_harta = [];
var notif_harta = [];
var direct_harta = [];
var total_harta = [];

function checkNol(total_harta) {
    return total_harta == 0;
}

$(document).ready(function() {
    $.post('index.php/eaudit/klarifikasi/data_harta_tetap/<?php echo $new_id_lhkpn; ?>',function(data){
        data = $.parseJSON(data);
        $.each(data, function(index, value) {
            title_harta.push(value.title);
            notif_harta.push(value.notif_status);
            direct_harta.push(value.view);
            total_harta.push(value.total_harta);
        })
    });
    
<?php if ($upperli): ?>
            $("li#<?php echo $upperli; ?>").find('a').trigger('click');

    <?php if ($bottomli): ?>
                $("li#<?php echo $bottomli; ?>").find('a').trigger('click');
    <?php endif; ?>
<?php endif; ?>
    
    
    $("#btn-simpan-catatan").click(function(e) {
            e.preventDefault();
            var data = {
              'id_lhkpn': $('#id_catatan').val(),
            //   'catatan_pemeriksaan': CKEDITOR.instances.catatan_pemeriksaan.getData()
              'catatan_pemeriksaan': $('#catatan_pemeriksaan').val()
            }
            var url = '<?php echo base_url() ?>eaudit/klarifikasi/saveCatatan';
            var sub = $.post(url, data);
            sub.done(function (data) {
                data == 1 ? alertify.success('Data Berhasil Disimpan') : alertify.error('Data Gagal Disimpan');
            });
        });
    
    $('#btnEditTglKlarifikasi').click(function (e) {
        e.preventDefault();
        url = $(this).attr('href');
        $('#loader_area').show();
        data = {
            'id_lhkpn_pemeriksaan': $(this).data('idlhkpn'),
        }
        $.post(url, data, function(html) {
            OpenModalBox('Edit Tanggal BAK', html, '', 'standart');
        });
        
        return false;
    });

    $('#btnCancelKlarifikasi').click(function (e) {
        e.preventDefault();
        var id_lhkpn = $(this).data('idlhkpn');
        var id_audit = $(this).data('idaudit');
        var url = '<?php echo site_url('eaudit/klarifikasi/nonActive')?>/'+ id_lhkpn + '/' + id_audit;
        var msg = {
            success: 'Data Berhasil Dihapus!',
            error: 'Data Gagal Dihapus!',
        };
        
        alertify.confirm('Cancel Data Klarifikasi', 'Apakah anda yakin akan membatalkan data klarifikasi ini?', 
            function() { 
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        CloseModalBox2();
                        if (data == '1') {
                            var url = location.href.split('#')[1];
                            url = url.split('eaudit')[0] + 'eaudit/periksa/index';
                            window.location.hash = url;
                            ng.LoadAjaxContent(url);
                            alertify.success(msg.success);
                        } else {
                            alertify.error(msg.error);
                        }
                    }
                });
            }, 
            function() {
                alertify.confirm().close();
                // alertify.error('Cancel')
            }
        ).set('labels', {
            ok:'Ya',
            cancel:'Tidak'
        });;
    });

    $('.btnSaveFinal').click(function(e) {
        e.preventDefault();
        var status_klarifikasi = <?= $status_klarifikasi; ?>;
        var i;

        if (total_harta.every(checkNol)) {
            $('.btnSaveFinal').prop('disabled', true);
            $('#loader_area').show();
//            swal({
//                type: 'question',
//                title: 'Pilih Status',
//                input: 'radio',
//                inputOptions: {
//                    '3': "Diumumkan Lengkap",
//                    '5': "Diumumkan Tidak Lengkap"
//                },
//                inputValue: status_klarifikasi,
//                confirmButtonText: 'Simpan',
//                width: '25%',
//                footer: 'Catatan:<br>- Diumumkan Lengkap, apabila Surat Kuasa a.n. PN dan Anggota Keluarga yang wajib SK sudah lengkap. <br>- Diumumkan Tidak Lengkap, apabila Surat Kuasa a.n. PN dan Anggota Keluarga yang wajib SK belum lengkap. <br>'
//            }).then(
//                result => {
//                    if (result.value) {
            var url_update = "index.php/eaudit/klarifikasi/update_status_klarifikasi/<?= $new_id_lhkpn.'/'.$id_audit; ?>";
            var url_send_klarif = "index.php/eaudit/klarifikasi/send_email_klarifikasi/<?= $new_id_lhkpn.'/'.$id_audit; ?>";
            var redirect = 'index.php/eaudit/periksa/index/';
            var url_cetak = "index.php/ever/ikthisar/cetak_klarifikasi/<?php echo $new_id_lhkpn; ?>/"+0+"/"+'1';
            var msg = {
                            success: 'Data Berhasil Disimpan!',
                            error: 'Data Gagal Disimpan, Ada item yang belum diverifikasi!'
                        };
            //do update
            $.post(url_update, function(data){
                if (data == "success") {
                    //do cetak
                    $.post(url_cetak,function(data){
                        if (data == '1') {
                            //do send mail
                            $.post(url_send_klarif,function(data){
                                if (data == 'success') {
                                    //notif success
                                    swal("Klarifikasi berhasil disimpan !", "Ikhtisar LHKPN (softcopy) akan dikirim melalui email ke <?= $tampil2; ?>", "success");
                                        ng.LoadAjaxContent(redirect);
                                } else {
                                    //notif error kirim
                                    alertify.error("Data gagal dikirim")
                                }
                            })
                        } else {
                            //notif error cetak data
                            alertify.error("Data gagal dicetak")
                        }
                    })
                } else {
                    //notif error update
                    alertify.error("Data gagal diperbarui")
                }
                $('#loader_area').hide();
            })
                        
//                    } else {
//                        swal({
//                            type: 'warning',
//                            html: 'Anda belum memilih statusnya'
//                        })
                        // console.log(`dialog was dismissed by ${result.dismiss}`)
//                    }
//            })
        } else {
            for (i = 0; i < total_harta.length; i++) {
                if (total_harta[i] != 0) {
                    alertify.confirm (title_harta[i], notif_harta[i],
                        function () {
                            alertify.confirm().close();
                            var url = location.href.split('#')[1];
                            url = url.split('?')[0] + direct_harta[i];
                            window.location.hash = url;
                            ng.LoadAjaxContent(url);
                            return false;
                        },
                        function () {
                            alertify.confirm().close();
                        }
                    ).set('labels', {
                        ok:'Lihat Data',
                        cancel:'Kembali'
                    });
                    break;
                }
            }
        }

    });

    $('.btnPrevious').click(function(e) {
        e.preventDefault();
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
    });

    $('.btnNext').click(function(e) {
        e.preventDefault();
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
    });

    $('.btnNextKeluarga').click(function(e) {
        e.preventDefault();
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
        $('#harta > .nav-tabs').find('a[href="#hartatidakbergerak"]').trigger('click');
    });

        $('.btnPrevHTB').click(function(e) {
            e.preventDefault();
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        $('.btnPrevHarta').click(function(e) {
            e.preventDefault();
            $('#harta > .nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        
        $('.btnNextHarta').click(function(e) {
            e.preventDefault();
            $('#harta > .nav-tabs > .active').next('li').find('a').trigger('click');
        });
    
    $('.btnPrevPenerimaan').click(function(e) {
        e.preventDefault();
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        $('#harta > .nav-tabs').find('a[href="#hutang"]').trigger('click');
    });

    $('.btnNextFasilitas').click(function(e) {
        e.preventDefault();
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
        $('#lampiran > .nav-tabs').find('a[href="#pelepasanharta"]').trigger('click');
    });

        $('.btnPrevPelepasan').click(function(e) {
            e.preventDefault();
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        $('.btnPrevLampiran').click(function(e) {
            e.preventDefault();
            $('#lampiran > .nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        
        $('.btnNextLampiran').click(function(e) {
            e.preventDefault();
            $('#lampiran > .nav-tabs > .active').next('li').find('a').trigger('click');
        });
    
    $('.btnPrevReviewHarta').click(function(e) {
        e.preventDefault();
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        $('#lampiran > .nav-tabs').find('a[href="#suratkuasamengumumkan"]').trigger('click');
    });


 });
</script>
