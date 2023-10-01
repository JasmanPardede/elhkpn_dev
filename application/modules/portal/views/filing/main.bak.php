<nav id="menu-nav" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="row" id="main-menu">
            <div id="wrapper-menu" class="col-lg-12">
                <ul>
                    <li><a href="<?php echo base_url(); ?>portal/filing"  class="active">E-Filing</a></li>
                    <li><a href="<?php echo base_url(); ?>portal/mailbox">Mailbox</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<section id="wrapper">
    <div class="container-fluid">
        <div class="row">
            <input type="hidden" name="IS_PRINT" id="IS_PRINT" value="<?php echo $IS_PRINT; ?>" />
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
                        <input type="hidden" id="idlhkpn" value="<?= $ID_LHKPN ?>">
                        <div class="progress-bar">

                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border" style="overflow:hidden">
                            <h3 class="box-title"></h3>
                            <input type="hidden" id="current"/>
                            <button id="btn-finished" onclick="FINISHED()" target="_blank" class="btn btn-primary btn-sm pull-right" style="display:none;">
                                <i class="fa fa-share"></i> Kirim LHKPN
                            </button>
                            <a id="cetak_final" target="_blank" href="<?php echo base_url(); ?>portal/ikthisar/cetak/<?php echo $ID_LHKPN; ?>" class="btn btn-success btn-sm pull-right" style="margin-right:5px; display:none;">
                                <i class="fa fa-print"></i>  Cetak Ikhtisar Harta
                            </a>
                            <a id="btn-kembali" href="javascript:void(0)" onclick="pindah(7)" class="btn btn-warning btn-sm pull-right" style="margin-right:5px; display:none;">
                                <i class="fa fa-backward"></i>   Sebelumnya 
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
<div id="ModalPelpasan" class="modal fade" role="dialog" >
    <div class='modal-dialog' style="width:70%; margin-top:10px;">    
        <div class="modal-content">
            <form role="form" id="FORM_PELEPASAN">
                <div class="modal-header">
                    <h4 class="modal-title">FORM PELEPASAN</h4>
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
                                <input type="text" id="TANGGAL_TRANSAKSI" name="TANGGAL_TRANSAKSI" class="form-control tgl" required/>
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
                    <button type="submit" class="btn btn-primary btn-sm" >
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
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
                <h4 class="modal-title">LAMPIRAN 3 - SURAT KUASA MENGUMUMKAN</h4>
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
                    Pimpinan Komisi Pemberantasan Korupsi ("KPK"), beralamat di JL. HR Rasuna Said Kav. C-1, Jakarta Selatan, 12920, Indonesia
                    yang bertindak baik secara bersama-sama maupun sendiri-sendiri (selanjutnya disebut "Penerima Kuasa").
                </p>
                <p>
                    Untuk dan atas nama Pemberi Kuasa mengumumkan seluruh harta kekayaan Pemberi Kuasa yang dilaporkan kepada Penerima Kuasa dalam Berita Negara
                    dan Tambahan Berita Negara Republik Indonesia dan/atau media yang ditetapkan oleh Penerima Kuasa.
                </p>
                <p>
                    Sehubungan dengan itu Penerima Kuasa berwenang menghadap dan/atau mengubungi lembaga baik ditingkat pusat maupun daerah dan/atau
                    pejabat yang berwenang maupun pihak  - pihak lain yang terkait, melaksanakan segala tindakan yang dianggap perlu dan penting serta berguna bagi Penerima Kuasa
                    sesuai dengan peraturan perundang - undangan yang berlaku.
                </p>
                <p>
                    Surat Kuasa ini berlaku sejak ditandatangani kecuali apabila Pemberi Kuasa meninggal dunia atau setelah 5 (lima) tahun tidak lagi menjabat sebagai
                    Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada dibawah pengampunan atau setelah mendapatkan persetujuan
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
                    <li><input type="radio" value="0" class="hasil" name="hasil_surat_kuasa3"/> Tidak Setuju</li>
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
                    Pimpinan Komisi Pemberantasan Korupsi ("KPK"), beralamat di JL. HR Rasuna Said Kav. C-1, Jakarta Selatan, 12920, Indonesia
                    yang bertindak baik secara bersama-sama maupun sendiri-sendiri (selanjutnya disebut "Penerima Kuasa")
                </p>

                <div style="width:100%;text-align:center"><b>---KHUSUS---</b></div>

                <p>
                    Untuk dan atas nama Pemberi Kuasa 
                </p>

                <ol>
                    <li>
                        Mengetahui,memperoleh,memeriksa dang mengklarifikasi termasuk namun tidak terbatas pada keberadaan dan kebenaran data dan atau informasi
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
                            <li>Lembaga keuangan bank maupun lembaga keunagan non bank;</li>
                            <li>Lembaga / pihak /profesi /instansi pemerintah yang terkait efek;</li>
                            <li>Badan usaha dan/atau perusahaan.</li>
                        </ol>
                    </li>
                </ol>
                <br>

                <p>
                    Sehubungan dengan itu, Penerima Kuasa berewenang menghadap kepada semua lembaga keuangan bank maupun lembaga keuangan non bank
                    dan/atau pejabat - pejabat yang berwenang maupun pihak - pihak terkait untuk mendapatkan keterangan - keterangan, dokumen - dokumen
                    dan/atau laporan setiap akhir tahun (baik asli maupun fotocopy) Pemberi Kuasa, melakukan segala tindakan
                    hukum yang dianggap perlu dan penting serta berguna bagi Pemberi Kuasa sesuai dengan peraturan perundang - undangan
                    yang berlaku.
                </p>
                <p>
                    Surat Kuasa ini berlaku sejak ditandatangani kecuali apabila Pemberi Kuasa meninggal dunia atay setelah 5 (lima) tahun tidak lagi menjabat sebagai
                    Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada dibawah pengampunan atau setelah mendapatkan persetujuan
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
                        <span id="notif-text">Maaf kode token yang Anda masukan salah, silahkan cek dan masukkan kembali. </span>
                    </div>
                    <div class="alert alert-success" id="token_reset" style="display:none;">
                        <i class="fa fa-check" aria-hidden"true"=""></i>
                        <span id="notif-text">Kode token telah berhasil dikirimkan kembali.</span>
                    </div>
                    <div class="form-group group-0">
                        <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                            Lampiran-4 surat kuasa wajib ditandatangani oleh masing-masing baik penyelenggara negara, pasangan (istri/suami) dan seluruh anak dalam tanggungan diatas meterai Rp.6000 dan dikirimkan ke alamat Direktorat Pendaftaran dan Pemeriksaan LHKPN Komisi Pemberantasan Korupsi JL. HR Rasuna Said Kav. C-1, Jakarta Selatan, 12920.
                        </p>
                        <br></br>
                        <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                            Anda akan menerima SMS Kode Token dari KPK. Silakan masukkan pada kotak yang tersedia di bawah ini.<span class="red-label">*</span> </label> <?= FormHelpPopOver('kode_token'); ?>
                        </p>
                        <br></br>
                        <label>Kode Token<span class="red-label">*</span> </label> <?= FormHelpPopOver('kode_token'); ?>
                        <input type="text" id="nomor_token" name="nomor_token"  class="form-control " required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-final-next"  onclick="RESET_TOKEN(<?php echo $ID_LHKPN; ?>)"   type="button" class="btn btn-sm btn-info">
                        <i class="fa fa-refresh"></i>  Belum menerima SMS? 
                    </button>
                    <button id="btn-final-next"  onclick="CHECK_TOKEN(<?php echo $ID_LHKPN; ?>)"   type="button" class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i>  Kirim 
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times; Batal</button>
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
    
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>portal-assets/js/filing.js"></script>