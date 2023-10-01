<?php
$ID_LHKPN = isset($ID_LHKPN) ? $ID_LHKPN : FALSE;
$STATUS = isset($STATUS) ? $STATUS : FALSE;
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$tahun = substr($TGL_LAPOR, 0, 4);
$bulan = substr($TGL_LAPOR, 5, 2);
$tgl = substr($TGL_LAPOR, 8, 2);
$almrumah = $alamatrumah;
?>
<nav id="menu-nav" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="row" id="main-menu">
            <div id="wrapper-menu" class="col-lg-12">
                <ul>
                    <li><a href="<?php echo base_url(); ?>portal/filing"  class="active">E-Filing</a></li>
                    <li><a href="<?php echo base_url(); ?>portal/mailbox">Mailbox</a></li>
                    <li>Tanggal/Tahun Lapor : <?php
                        if ($JENIS_PELAPORAN == '4' || $JENIS_PELAPORAN == 'Periodik')
                            echo $tahun;
                        else
                            echo $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
                        ?></li>
                    <li>Jenis Pelaporan : <?php
                        if ($JENIS_PELAPORAN == '4' || $JENIS_PELAPORAN == 'Periodik') {
                                echo 'Periodik';
                             }elseif($JENIS_PELAPORAN==5){
                                echo 'Klarifikasi';
                             } else { 
                                echo 'Khusus';
                             }
                            ?></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<section id="wrapper">
    <div class="container-fluid">
        <div class="row">
            <input type="hidden" name="IS_PRINT" id="IS_PRINT" value="<?php echo $IS_PRINT; ?>" />
            <?php if ($skip_to_review_harta): ?>
                <input type="hidden" name="skip_to_review_harta" id="skip_to_review_harta" value="ok_do_it" />
<?php endif; ?>
            <div id="wrapper-main" class="my_wizard">
                <div class="col-lg-2">
                    <ul class="nav nav-stacked affix nav-pills" id="sidebar">
                        <li>
                            <div class="action" id="btn-prev">
                                <i alt="Sebelumnya" title="Sebelumnya" class="fa fa-arrow-circle-up fa-3x"></i>
                            </div>
                        </li>
                        <li class="tab1"><a href="javascript:void(0)"  onclick="View(1, 'DATA PRIBADI')">Data Pribadi</a></li>
                        <li class="tab2"><a href="javascript:void(0)"  onclick="View(2, 'DATA JABATAN')">Jabatan</a></li>
                        <li class="tab3"><a href="javascript:void(0)"  onclick="View(3, 'DATA KELUARGA')">Data Keluarga</a></li>
                        <li class="tab4"><a href="javascript:void(0)"  onclick="View(4, 'DATA HARTA')">Harta</a></li>
                        <li class="tab5"><a href="javascript:void(0)"  onclick="View(5, 'DATA PENERIMAAN')">Penerimaan</a></li>
                        <li class="tab6"><a href="javascript:void(0)"  onclick="View(6, 'DATA PENGELUARAN')">Pengeluaran</a></li>
                        <li class="tab8"><a href="javascript:void(0)"  onclick="View(8, 'REVIEW LAMPIRAN')">Lampiran Penjualan/Pelepasan</a></li>
                        <li class="tab7"><a href="javascript:void(0)"  onclick="View(7, 'DATA FASIILITAS')">Lampiran Fasilitas</a></li>
                        <li class="tab9"><a href="javascript:void(0)"  onclick="View(9, 'REVIEW HARTA')">Review Harta</a></li>
                        <li>
                            <div class="action" id="btn-next" hidden>
                                <i alt="Lanjut" title="Lanjut" class="fa fa-arrow-circle-down fa-3x"></i>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-10">
                    <div class="progress">
                        <input type="hidden" id="idlhkpn" value="<?php echo $ID_LHKPN; ?>">
                        <div class="progress-bar">

                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border" style="overflow:hidden">
                        	<h3 class="box-title"></h3>
                            <input type="hidden" id="current"/>
                            <!--<button id="btn-finished" onclick="FINISHED_()" target="_blank" class="btn btn-primary btn-sm pull-right" style="display:none;">-->
                                <!--  KOMEN BY WAHYU -->
                            <!--<button id="btn-finished" onclick="FINISHED()" target="_blank" class="btn btn-primary btn-sm pull-right" style="display:none;">-->
                                <!--<i class="fa fa-share"></i> Kirim LHKPN-->
                            <!--</button>-->
                            <?php
                                if($JENIS_PELAPORAN==5){
                            ?>
                                <a id="cetak_final" target="_blank" href="<?php echo base_url(); ?>portal/ikthisar/cetak_klarifikasi/<?php echo $ID_LHKPN; ?>" class="btn btn-success btn-sm pull-right" style="display:none;">
                            <?php
                                }else{
                            ?>
                                <a id="cetak_final" target="_blank" href="<?php echo base_url(); ?>portal/ikthisar/cetak/<?php echo $ID_LHKPN; ?>" class="btn btn-success btn-sm pull-right" style="display:none;">
                            <?php
                                }
                            ?>
                            
                                <i class="fa fa-print"></i>  Cetak Ikhtisar Harta</a>
                            <a id="btn-kembali" href="javascript:void(0)" onclick="pindah(7)" class="btn btn-warning btn-sm pull-right" style="margin-right:5px; display:none;">
                                <i class="fa fa-backward"></i>   Sebelumnya
                            </a>
                    		<br><br>
                            <a id="btn-finished" onclick="FINISHED()" target="_blank" class="btn btn-primary btn-sm pull-right" style="display:none;">
                                <i class="fa fa-share"></i> Proses Permintaan Token Untuk Pengiriman LHKPN
                            </a>
                        </div>
                        <div id="container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="ModalLoading" class="modal-custom">
    <div class='modal-body loader'>    
    </div>
</div>
<div id="ModalWarning" class="modal fade" role="dialog">
    <div class='modal-dialog' style="margin:15% auto">
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
<div id="ModalSuccess" class="modal fade" role="dialog">
    <div class='modal-dialog' style="margin:15% auto">
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
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div id="ModalAlert" class="modal fade" role="dialog">
    <div class='modal-dialog' style="margin:15% auto">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pemberitahuan</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fa fa-remove" aria-hidden"true"=""></i>
                    <input type="hidden" name="judul" id="judultext"/>
                    <span id="notif-text"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-final-next" type="button" class="btn btn-sm btn-primary">
                    <i class="fa fa-share"></i> Lanjutkan
                </button>
                <button id="btn-final-isi" type="button" class="btn btn-sm btn-success">
                    <i class="fa fa-edit"></i> Isi Data
                </button>
            </div>
        </div>
    </div>
</div>
<div id="ModalAlertPelaporan" class="modal fade" role="dialog">
    <div class='modal-dialog' style="margin:15% auto">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pemberitahuan</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="text-align:justify">
                    <!--<i class="fa fa-remove" aria-hidden"true"=""></i>-->
                    <!--<input type="hidden" name="judul" id="judultext"/>-->
                    Tahun/Tanggal Pelaporan untuk LHKPN Anda belum sesuai. Silahkan melakukan edit Tahun/Tanggal Pelaporan di tabel Riwayat Harta dengan cara klik tombol 
                    <button class="btn btn-warning btn-sm"><i class="fa fa-bars" style="color:white;"></i></button> (Edit Jenis Laporan).<br><br>
                    
                    Anda bisa memilih Tahun/Tanggal pelaporan dengan kondisi sebagai berikut.<br>
                    
                    <div id="thn-berjalan" style="display: none;">
                        1. Jika jenis laporan Khusus, dapat memilih Tanggal Pelaporan dari tanggal  <span id="pelaporan-tanggal"></span> sampai  <span id="pelaporan-today"></span>.
                    </div>
                    <div id="not-thn-berjalan">
                        1. Jika jenis laporan Periodik, dapat memilih Tahun Pelaporan <span id="pelaporan-tahun"></span>.<br>
                        2. Jika jenis laporan Khusus, dapat memilih Tanggal Pelaporan dari tanggal  <span id="pelaporan-tanggal"></span> sampai  <span id="pelaporan-today"></span>.
                    </div>
                    
                    <!--<span id="notif-text"></span>-->
                </div>
            </div>
            <div class="modal-footer">
                <!--<button id="btn-final-check-pelaporan" type="button" class="btn btn-sm btn-success" onclick="btnEditJenisFillingOnClick(this);">
                    <i class="fa fa-edit"></i> Edit Tanggal
                </button>-->
                <a type="button" class="btn btn-sm btn-success" href="<?php echo base_url(); ?>portal/filing" ><i class="fa fa-edit"></i> Edit Tanggal</a>
            </div>
        </div>
    </div>
</div>

<div id="ModalPelpasan" class="modal fade" role="dialog" >
    <div class='modal-dialog' style="width:70%; margin-top:10px;">
        <div class="modal-content">
            <form role="form" id="FORM_PELEPASAN">
                <div class="modal-header">
                    <h4 class="modal-title">FORM PELEPASAN </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="<?php echo $ID_LHKPN; ?>"/>
                            <input type="hidden" name="ID_HARTA" id="ID_HARTA"/>
                            <input type="hidden" name="TABLE" id="TABLE"/>
                            <input type="hidden" name="TABLE_GRID" id="TABLE_GRID"/>
                            <input type="hidden" name="NOTIF" id="NOTIF"/>
                            <input type="hidden" name="MAIN_TABLE" id="MAIN_TABLE"/>
                            <div class="form-group group-0">
                                <label>Jenis Pelepasan Harta <span class="red-label">*</span> </label>
                                <select class="form-control" id="JENIS_PELEPASAN_HARTA" name="JENIS_PELEPASAN_HARTA" required>
                                    <option></option>
                                    <?php foreach ($JENIS_PELEPASAN_HARTA as $JP): ?>
                                        <option value="<?php echo $JP->ID; ?>"><?php echo $JP->JENIS_PELEPASAN_HARTA; ?></option>
                <?php EndForeach; ?>
                                </select>
                            </div>
                            <div class="form-group group-0">
                                <label>Tanggal Transaksi<span class="red-label">*</span> </label>
                                <input type="text" onkeydown="return false" autocomplete="off" id="TANGGAL_TRANSAKSI" name="TANGGAL_TRANSAKSI" class="form-control tgl" required/>
                            </div>
                            <div class="form-group group-0">
                                <label>Uraian Harta <span class="red-label">*</span> </label>
                                <textarea class="form-control" name="URAIAN_HARTA" id="URAIAN_HARTA" rows="2"  required></textarea>
                            </div>
                            <div class="form-group group-0">
                                <label>Nilai Pelepasan <span class="red-label">*</span> </label>
                                <input type="text" id="NILAI_PELEPASAN" name="NILAI_PELEPASAN"  class="form-control" required/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group group-0">
                                <label>Nama (Pihak Ke-2 )<span class="red-label">*</span> </label>
                                <input type="text" id="NAMA" name="NAMA" class="form-control" required/>
                            </div>
                            <div class="form-group group-0">
                                <label>Alamat (Pihak Ke-2 )<span class="red-label">*</span> </label>
                                <textarea class="form-control" name="ALAMAT" id="ALAMAT" rows="2"  required ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm group-0" >
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-danger btn-sm group-0" data-dismiss="modal">
                        <i class="fa fa-remove"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="ModalSuratKuasa" class="modal fade" role="dialog">
    <div class='modal-dialog'>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">LAMPIRAN 3 - SURAT PERNYATAAN</h4>
            </div>
            <div class="modal-body">
                <div class="peringatan">
                    <div class="alert alert-danger">
                        <span>
                            Apabila Saudara tidak setuju, Saudara wajib mengumumkan LHKPN paling lambat 2 (dua) bulan setelah Saudara menyampaikan LHKPN kepada KPK.
                            Pengumuman sebagaimana dimaksud diatas dilakukan dengan menggunakan format yang ditetapkan oleh KPK melalui media elektronik maupun non elektronik sebagai berikut :
                        </span>
                        <ol style="list-style-type: lower-alpha;">
                            <li>Media pengumuman KPK;</li>
                            <li>Media pengumuman resmi instansi; dan / atau</li>
                            <li>Surat Kabar yang memiliki peredaran secara nasional</li>
                        </ol>
                        <span>
                            Apabila Saudara tidak melaksanakan sesuai ketentuan diatas maka Saudara dianggap tidak mematuhi Undang-undang Nomor 28 Tahun 1999 tentang Penyelenggaraan Negara yang Bersih dan Bebas Dari Korupsi dan Nepotisme pasal 5 angka 3 “melaporkan dan mengumumkan kekayaan sebelum dan setelah menjabat
                        </span>
                    </div>
                </div>
                <p>
                    Dengan menyetujui pernyataan di bawah ini, Saya menyatakan bahwa 	:
                </p>
                <table style="width:100%;">
                    <tr>
                        <td style="width:20%;">Nama</td>
                        <td>:</td>
                        <td id="KUASA_NAMA"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Tempat / Tanggal Lahir</td>
                        <td>:</td>
                        <td id="KUASA_TTL"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Nomor KTP / NIK</td>
                        <td>:</td>
                        <td id="KUASA_KTP"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Alamat</td>
                        <td>:</td>
                        <td id="KUASA_ALAMAT"></td>
                    </tr>
                </table>
                <br>
                <p>
                    (Selanjutnya disebut sebagai / "Pemberi Kuasa")
                </p>
                <p>
                    Dengan ini memberikan kuasa dengan hak subsitusi kepada:
                </p>
                <p>
                    Pimpinan Komisi Pemberantasan Korupsi ("KPK"), beralamat di Jl. Kuningan Persada Kav. 4, Jakarta Selatan 12950, Indonesia,
                    yang bertindak baik secara bersama-sama maupun sendiri-sendiri (selanjutnya disebut "Penerima Kuasa").
                </p>
                <p>
                    Untuk dan atas nama Pemberi Kuasa mengumumkan seluruh harta kekayaan Pemberi Kuasa yang dilaporkan kepada Penerima Kuasa dalam media pengumuman
                    yang ditetapkan oleh Penerima Kuasa.
                </p>
                <p>
                    Sehubungan dengan itu Penerima Kuasa berwenang menghadap dan/atau menghubungi lembaga baik di tingkat pusat maupun daerah dan/atau
                    pejabat yang berwenang maupun pihak-pihak lain yang terkait, melaksanakan segala tindakan yang dianggap perlu dan penting serta berguna bagi Penerima Kuasa
                    sesuai dengan peraturan perundang-undangan yang berlaku.
                </p>
                <p>
                    Surat Pernyataan ini berlaku sejak ditandatangani kecuali apabila Pemberi Kuasa meninggal dunia atau setelah 5 (lima) tahun tidak lagi menjabat sebagai
                    Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada dibawah pengampuan atau setelah mendapatkan persetujuan
                    tertulis mengenai pencabutannya dari Penerima Kuasa.
                </p>
                <table style="width:100%;">
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;" id="KUASA_TGL_KIRIM"></td>
                    </tr>
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;"><b>Pemberi Kuasa,</b></td>
                    </tr>
                </table>
                <table style="width:100%; margin-top:50px;">
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%"></td>
                    </tr>
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;" id="KUASA_TTD"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <ul style="margin:0; list-style-type:none; float:left; text-align:left;">
                    <li><input type="radio" value="1" class="hasil" name="hasil_surat_kuasa3" checked/> Setuju</li>
                    <li><input type="radio" value="0" class="hasil" name="hasil_surat_kuasa3" /> Tidak Setuju</li>
                </ul>
                <button style="display:none;" id="btn-final-next" onclick="KUASA_KELUARGA(1, '#ModalKuasaKeluarga')" type="button" class="btn btn-sm btn-warning">
                    Selanjutnya <i class="fa fa-forward"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times; Batal</button>

                <!--  <button id="btn-final-isi" type="button" onclick="KUASA_KELUARGA(1,'#ModalKuasaKeluarga','no')"  class="btn btn-sm btn-danger" data-dismiss="modal">
                  <i class="fa fa-remove"></i> Tidak Setuju
                </button> -->
            </div>
        </div>
    </div>
</div>
<div id="ModalKuasaKeluarga" class="modal fade" role="dialog" >
    <div class='modal-dialog'>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">LAMPIRAN 4 - SURAT KUASA</h4>
            </div>
            <div class="modal-body">
                <div class="peringatan">
                    <div class="alert alert-danger">
                        <span>
                            Apabila Saudara tidak setuju, Saudara wajib mengumumkan LHKPN paling lambat 2 (dua) bulan setelah Saudara menyampaikan LHKPN kepada KPK.
                            Pengumuman sebagaimana dimaksud diatas dilakukan dengan menggunakan format yang ditetapkan oleh KPK melalui media elektronik maupun non elektronik sebagai berikut :
                        </span>
                        <ol style="list-style-type: lower-alpha;">
                            <li>Media pengumuman KPK;</li>
                            <li>Media pengumuman resmi instansi; dan / atau</li>
                            <li>Surat Kabar yang memiliki peredaran secara nasional</li>
                        </ol>
                    </div>
                </div>
                <p>
                    Dengan menyetujui pernyataan di bawah ini, Saya menyatakan bahwa 	:
                </p>
                <table style="width:100%;">
                    <tr>
                        <td style="width:20%;">Nama (Sesuai dengan KTP)</td>
                        <td>:</td>
                        <td id="KELUARGA_NAMA"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Tempat / Tanggal Lahir</td>
                        <td>:</td>
                        <td id="KELUARGA_TTL"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Nomor KTP / NIK</td>
                        <td>:</td>
                        <td id="KELUARGA_KTP"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Alamat</td>
                        <td>:</td>
                        <td id="KELUARGA_ALAMAT"></td>
                    </tr>
                </table>
                <br>
                <p>
                    (Selanjutnya disebut sebagai / "Pemberi Kuasa")
                </p>
                <p>
                    Dengan ini memberikan kuasa dengan hak subsitusi kepada:
                </p>
                <p>
                    Pimpinan Komisi Pemberantasan Korupsi ("KPK"), beralamat di Gedung Merah Putih KPK - Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta 12950, Indonesia
                    yang bertindak baik secara bersama-sama maupun sendiri-sendiri (selanjutnya disebut "Penerima Kuasa")
                </p>

                <div style="width:100%;text-align:center"><b>---KHUSUS---</b></div>

                <p>
                    Untuk dan atas nama Pemberi Kuasa
                </p>

                <ol>
                    <li>
                        Mengetahui,memperoleh,memeriksa yang mengklarifikasi termasuk namun tidak terbatas pada keberadaan dan kebenaran data dan atau informasi
                        keuangan Pemberi Kuasa yang berada pada:
                        <ol style="list-style-type: lower-alpha;">
                            <li>Lembaga keuangan bank maupun lembaga keuangan non bank;</li>
                            <li>Lembaga / pihak /profesi /instansi pemerintah yang terkait efek;</li>
                            <li>Badan usaha dan/atau perusahaan.</li>
                        </ol>
                    </li>
                    <li>
                        Mengetahui dan memperoleh laporan mengenai data keuangan Pemberi Kuasa yang berada namun tidak terbatas pada:
                        <ol style="list-style-type: lower-alpha;">
                            <li>Lembaga keuangan bank maupun lembaga keuangan non bank;</li>
                            <li>Lembaga / pihak /profesi /instansi pemerintah yang terkait efek;</li>
                            <li>Badan usaha dan/atau perusahaan.</li>
                        </ol>
                    </li>
                </ol>
                <br>

                <p>
                    Sehubungan dengan itu, Penerima Kuasa berwenang menghadap kepada semua lembaga keuangan bank maupun lembaga keuangan non bank
                    dan/atau pejabat - pejabat yang berwenang maupun pihak - pihak terkait untuk mendapatkan keterangan - keterangan, dokumen - dokumen
                    dan/atau laporan setiap akhir tahun (baik asli maupun fotocopy) Pemberi Kuasa, melakukan segala tindakan
                    hukum yang dianggap perlu dan penting serta berguna bagi Pemberi Kuasa sesuai dengan peraturan perundang - undangan
                    yang berlaku.
                </p>
                <p>
                    Surat Kuasa ini berlaku sejak ditandatangani kecuali apabila Pemberi Kuasa meninggal dunia atau setelah 5 (lima) tahun tidak lagi menjabat sebagai
                    Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada dibawah pengampuan atau setelah mendapatkan persetujuan
                    tertulis mengenai pencabutannya dari Penerima Kuasa.
                </p>
                <table style="width:100%;">
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;" id="KELUARGA_TGL_KIRIM"></td>
                    </tr>
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;"><b>Pemberi Kuasa,</b></td>
                    </tr>
                </table>
                <table style="width:100%; margin-top:50px;">
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%"></td>
                    </tr>
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;" id="KELUARGA_TTD"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <ul style="margin:0; list-style-type:none; float:left; text-align:left;">
                    <li><input type="radio" value="1" class="hasil" name="hasil_surat_kuasa4" checked/> Setuju</li>
                    <!-- <li><input type="radio" value="0" class="hasil" name="hasil_surat_kuasa4"/> Tidak Setuju</li> -->
                </ul>
                <button id="btn-print" style="display:none;"  type="button" class="btn btn-sm btn-success">
                    <i class="fa fa-print"></i> Print
                </button>
                <button style="display:none" id="btn-final-next"  type="button" class="btn btn-sm btn-warning">
                    Selanjutnya <i class="fa fa-forward"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times; Batal</button>
                <!--  <button id="btn-final-isi" type="button" class="btn btn-sm btn-danger"  >
                  <i class="fa fa-remove"></i> Tidak Setuju
                </button> -->
            </div>
        </div>
    </div>
</div>
<div id="ModalPersetujuanIntegrasiData" class="modal fade" role="dialog">
    <div class='modal-dialog'>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">LAMPIRAN 5 – SURAT PERSETUJUAN INTEGRASI DATA ISIAN LHKPN DENGAN ALPHA</h4>
            </div>
            <div class="modal-body">
                <p>
                    Yang bertanda-tangan di bawah ini : 
                </p>
                <table style="width:100%;">
                    <tr>
                        <td style="width:20%;">Nama</td>
                        <td>:</td>
                        <td id="PERSETUJUAN_NAMA"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Tempat / Tanggal Lahir</td>
                        <td>:</td>
                        <td id="PERSETUJUAN_TTL"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Nomor KTP / NIK</td>
                        <td>:</td>
                        <td id="PERSETUJUAN_KTP"></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Alamat</td>
                        <td>:</td>
                        <td id="PERSETUJUAN_ALAMAT"></td>
                    </tr>
                </table>
                <br>
                <p>
                    selanjutnya disebut sebagai “Pemberi Persetujuan”, dengan ini menyatakan bahwa:
                </p>
                <ol start="1">
                    <li><p>persetujuan untuk mengintegrasikan seluruh data dan Informasi isian Laporan Harta Kekayaan Penyelenggara Negara (LHKPN) yang disampaikan kepada Komisi Pemberantasan Korupsi dengan data laporan harta kekayaan pada Kementerian Keuangan melalui Aplikasi Laporan Perpajakan dan Harta Kekayaan Pegawai Kementerian Keuangan (ALPHA);.</p></li>
                    <li><p>untuk keperluan tersebut, Komisi Pemberantasan Korupsi dapat memberikan seluruh data dan Informasi isian LHKPN atas nama Pemberi Persetujuan kepada Kementerian Keuangan baik secara elektronik maupun non-elektronik, serta melakukan segala tindakan untuk mencapai maksud dan tujuan sebagaimana dimaksud pada angka 1; dan</p></li>
                    <li><p>melepaskan Komisi Pemberantasan Korupsi dari segala tuntutan hukum jika di kemudian hari Kementerian Keuangan menggunakan data dan informasi isian LHKPN untuk kepentingan selain tujuan sebagaimana dimaksud pada angka 1 tanpa persetujuan Komisi Pemberantasan Korupsi ataupun Pemberi Persetujuan.</p></li>
                </ol>
                <p>
                    Demikian Persetujuan ini dibuat dengan sebenarnya dalam keadaan bebas dan tanpa paksaan untuk dapat dipergunakan sebagaimana mestinya.
                </p>
                <table style="width:100%;">
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;"><?php $now = date('Y-m-d');
                        echo tgl_format($now); ?></td>
                        <input type="hidden" id="PERSETUJUAN_DATE" value="<?=date('Y-m-d');?>">
                    </tr>
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;">Pemberi Persetujuan</td>
                    </tr>
                </table>
                <table style="width:100%; margin-top:50px;">
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%"></td>
                    </tr>
                    <tr>
                        <td style="width:80%"></td>
                        <td style="width:20%; text-align:center;" id="PERSETUJUAN_TTD"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <ul style="margin:0; list-style-type:none; float:left; text-align:left;">
                    <li><input type="radio" value="1" class="hasil" name="hasil_surat_persetujuan" /> Setuju</li>
                    <!-- <li><input type="radio" value="0" class="hasil" name="hasil_surat_persetujuan" /> Tidak Setuju</li> -->
                </ul>
                <button style="display:none;" id="btn-final-next" onclick="SAVE_INTEGRASI_DATA()" type="button" class="btn btn-sm btn-warning">
                    Selanjutnya <i class="fa fa-forward"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times; Batal</button>
            </div>
        </div>
    </div>
</div>
<div id="Modal_Ikthisar" class="modal fade" role="dialog" >
    <div class='modal-dialog'>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">IKHTISAR HARTA KEKAYAAN PERIODE <?php echo $NOW_YEARS; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <iframe src="" style="height:450px; width:100%; border: 1px solid #ddd;" allowfullscreen webkitallowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <ul style="margin:0; list-style-type:none; float:left; text-align:left;">
                    <li><input type="radio" value="1" class="hasil" name="hasil_ikthisar"  checked/> Setuju</li>
                    <!-- <li><input type="radio" value="0" class="hasil" name="hasil_ikthisar"/> Tidak Setuju</li> -->
                </ul>

                <button style="display:none;" id="btn-final-next" data-dismiss="modal" data-target="#Modal_Ikthisar" onclick="SURAT_KUASA(1)"  type="button" class="btn btn-sm btn-warning">
                    Selanjutnya <i class="fa fa-forward"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times; Batal</button>
                <!--  <button id="btn-final-isi" type="button" class="btn btn-sm btn-danger" onclick="SURAT_KUASA(0)" data-dismiss="modal" data-target="#Modal_Ikthisar" >
                     <i class="fa fa-remove"></i> Tidak Setuju
                 </button> -->
            </div>
        </div>
    </div>
</div>
<div id="modal_token" class="modal fade" role="dialog" >
    <div class='modal-dialog' style="width:35%;">
        <div class="modal-content">
            <form role="form" id="FormToken">
                <div class="modal-header">
                    <!--  <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Finalisasi Pengiriman LHKPN & Kode Token</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="token_salah" style="display:none;">
                        <i class="fa fa-warning" aria-hidden"true"=""></i>
                        <span id="notif-text">Maaf kode token yang Anda masukan salah, silahkan cek dan masukkan kembali (pastikan server code sesuai dengan yang ada di email token). </span>
                    </div>
                    <div class="alert alert-success" id="token_reset" style="display:none;">
                        <i class="fa fa-check" aria-hidden"true"=""></i>
                        <span id="notif-text">Kode token telah berhasil dikirimkan kembali.</span>
                    </div>
                    <div class="form-group group-0">
                        <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                            Lampiran-4 surat kuasa yang dicetak oleh aplikasi e-Filing LHKPN pada saat proses pengiriman wajib ditandatangani oleh masing-masing baik penyelenggara negara, pasangan (istri/suami) dan seluruh anak dalam tanggungan diatas meterai Rp.10000 dan dikirimkan ke alamat :
                        </p>
                        <br>
                        <p style="text-align: justify; font-size: 13px; margin: 0px 0px 0px;">
                            Direktorat Pendaftaran dan Pemeriksaan LHKPN
                        </p>
                        <p style="text-align: justify; font-size: 13px; margin: 0px 0px 0px;">
                            Komisi Pemberantasan Korupsi
                        </p>
                        <p style="text-align: justify; font-size: 13px; margin: 0px 0px 0px;">
                            Gedung Merah Putih KPK - Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta 12950.
                        </p>
                        <br>
                        <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                            Anda akan menerima Kode Token yang dikirim aplikasi e-LHKPN melalui SMS dengan pengirim <b>e-LHKPN KPK</b> dan email <label style="color: blue;">statistik@kpk.go.id</label>.
                        </p>
                        <br>
                        <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                            <!-- Kode Token akan dikirimkan ke Nomor HP dan email Anda yang telah didaftarkan dalam aplikasi e-LHKPN yaitu <label style="color: blue;"><?php //echo $NO_HP; ?></label> dan <label style="color: blue;"><?php //echo $EMAIL; ?></label>. Silakan masukkan pada kotak yang tersedia di bawah ini (pastikan server code sesuai dengan yang ada di email/sms token).<span class="red-label">*</span> </label> <?= FormHelpPopOver('kode_token'); ?> -->
                            Kode Token akan dikirimkan ke Nomor HP dan email Anda yang telah didaftarkan dalam aplikasi e-LHKPN yaitu <label style="color: blue;"><span id="nohp"></span></label> dan <label style="color: blue;"><span id="email"></span></label>. Silakan masukkan pada kotak yang tersedia di bawah ini (pastikan server code sesuai dengan yang ada di email/sms token).<span class="red-label">*</span> </label> <?= FormHelpPopOver('kode_token'); ?>
                        </p>
                        <br>
                        <?php if($STATUS_LHKPN_PREV == '2' || $STATUS_LHKPN_PREV == '5' || $STATUS_LHKPN_PREV == '6') { ?>
                        <p style="text-align: justify;font-size: 13px; margin: 0px 0px 5px;">
                            Catatan :
                        </p>
                        <p style="background-color:#eeeeee;font-style: italic;text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;border-radius:5px;padding:10px 10px;">
                            LHKPN Saudara dengan tanggal lapor <?= $TGL_LAPOR_LHKPN_PREV; ?> memiliki status <strong>Tidak Lengkap</strong>, silakan kirimkan Surat Kuasa untuk Pasangan dan Anak dalam tanggungan yang ditandatangan oleh setiap nama diatas meterai Rp. 10.000 ke KPK yang dapat anda cetak dari Mailbox atau Halaman Riwayat Harta atau Data Keluarga setelah Saudara memasukkan kode token.
                        </p>
                        <br>
                        <?php } ?>                        
                        <label>Kode Token<span class="red-label">*</span> </label> <?= FormHelpPopOver('kode_token'); ?>
                        <input type="text" id="nomor_token" name="nomor_token"  class="form-control " required/>
                        Server Code : <span id="server_code"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-final-next"  onclick="RESET_TOKEN(<?php echo $ID_LHKPN; ?>)"   type="button" class="btn btn-sm btn-info kirim-ulang-token">
                      <span id="timeout_txt"></span> <i class="fa fa-refresh"></i>  Belum menerima Token?
                    </button>
                    <button id="btn-final-next"  onclick="CHECK_TOKEN(<?php echo $ID_LHKPN; ?>)"   type="button" class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i>  Kirim
                    </button>
                    <button type="button" id="btn-cancel" class="btn btn-sm btn-danger group-0" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times; Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modal_lagi" class="modal fade" role="dialog" >
    <div class='modal-dialog'>
        <div class="modal-content">
            <form role="form" id="FormToken">
                <div class="modal-header">
                    <h4 class="modal-title">Pemberitahuan</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <span>
                            Apabila Saudara tidak setuju, Saudara wajib mengumumkan LHKPN paling lambat 2 (dua) bulan setelah Saudara menyampaikan LHKPN kepada KPK.
                            Pengumuman sebagaimana dimaksud diatas dilakukan dengan menggunakan format yang ditetapkan oleh KPK melalui media elektronik maupun non elektronik sebagai berikut :
                        </span>
                        <ol style="list-style-type: lower-alpha;">
                            <li>Media pengumuman KPK;</li>
                            <li>Media pengumuman resmi instansi; dan / atau</li>
                            <li>Surat Kabar yang memiliki peredaran secara nasional</li>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-final-next"  onclick=""  data-dismiss="modal" data-target="#modal_lagi"  type="button" class="btn btn-sm btn-info">
                        OK
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
//////////////////////////parsing data//////////////////////////////////////
var state_jenis_pelepasan_harta = <?php echo json_encode($JENIS_PELEPASAN_HARTA) ?>;
</script>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var date_now = '<?php echo date("d/m/Y"); ?>';
    var ID_LHKPN = '<?php echo $ID_LHKPN; ?>';
    var IS_COPY = '<?php echo $IS_COPY; ?>';
    var STATUS = '<?php echo $STATUS; ?>';
    var NOW = '<?php echo $NOW_YEARS; ?>';
    var LAST_YEARS = '<?php echo $LAST_YEARS; ?>';
    var LAST_ID_LHKPN = '<?php echo $LAST_ID_LHKPN; ?>';
    var TGL_LAPOR = '<?php echo $TGL_LAPOR; ?>';
    var USERNAME = '<?php echo $USERNAME; ?>';
    var TOKEN_PENGIRIMAN = '<?php echo $TOKEN_PENGIRIMAN ?>';
    var VIA_VIA = '<?php echo $VIA_VIA ?>';
    var JENIS_PELAPORAN = '<?php echo $JENIS_PELAPORAN ?>';
    var TAHUN_LAPOR = '<?php echo ($JENIS_PELAPORAN == '4') ? date('Y')-1 : substr($TGL_LAPOR,0,4); ?>';

    function FINISHED_() {
        if (TOKEN_PENGIRIMAN == '')
            FINISHED();
        else
            SHOW_TOKEN(ID_LHKPN);
    }

    $(document).ready(function() {
    var detik = 0;

    function hitung() {
        var timeout = setInterval(function() {
                        $('.kirim-ulang-token').prop('disabled', true);
                        $('#timeout_txt').text(detik);
        
                        /** Melakukan Hitung Mundur dengan Mengurangi variabel detik - 1 */
                        detik --;

                    if(detik < 0) { 
                        
                            $('.kirim-ulang-token').prop('disabled', false);
                        $('#timeout_txt').text('');                              
                        clearInterval(timeout); 
                        // alert('habis');
                    }

            }, 1000);
        }
        hitung();
    });

</script>