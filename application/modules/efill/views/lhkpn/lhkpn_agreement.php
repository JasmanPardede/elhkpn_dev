<form method="post" class="form-horizontal" id="ajaxFormSubmit" action="index.php/efill/lhkpn/submitlhkpn/<?= ($res->STATUS_PERBAIKAN_NASKAH == '2' ? 'announ' : $mode); ?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            Anda Yakin Kirim LHKPN ?
        </div>
      <!--   <div class="col-md-12" style="margin-top: 15px;">
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-top: 0px;">Nama :</label>
                    <div class="col-sm-5">
                        <p class="form-control-static" style="padding: 0px; min-height: 20px;"><?php echo $res->NAMA; ?></p> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-top: 0px;">Tempat , Tanggal Lahir :</label>
                    <div class="col-sm-5">
                        <p class="form-control-static" style="padding: 0px; min-height: 20px;"><?php echo $res->TEMPAT_LAHIR.' , '.date('d/m/Y',strtotime($res->TGL_LAHIR)); ?></p> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-top: 0px;">NIK :</label>
                    <div class="col-sm-5">
                        <p class="form-control-static" style="padding: 0px; min-height: 20px;"><?php echo $res->NIK; ?></p> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" style="padding-top: 0px;">Alamat :</label>
                    <div class="col-sm-5">
                        <p class="form-control-static" style="padding: 0px; min-height: 20px;"><?php echo $res->ALAMAT_TINGGAL; ?></p> 
                    </div>
                </div>
            </div>
        </div> -->
       <!--  <di --<!-- <!--  -->
        </div>
   <!--  </div> -->
  <!--   <div class="row">
        <div style="width: 95%;line-height: 1.5; text-align: justify;font-size: 12px; max-height: 220px; overflow-y: scroll;border: 1px solid #E3E3E3; padding: 10px;margin: 10px;">
            <p>Dengan ini saya bertindak sebagai Pemberi Kuasa, memberi Kuasa Mengumumkan dengan hak substitusi kepada: Pimpinan Komisi Pemberantasan Korupsi (&ldquo;KPK&rdquo;), beralamat di Jl. HR Rasuna Said Kav. C-1, Jakarta Selatan, 12920, Indonesia, yang bertindak baik secara bersama-sama maupun sendiri-sendiri untuk dan atas nama Pemberi Kuasa:</p>

            <p>
                <ol>
                    <li>mengumumkan seluruh harta kekayaan Pemberi Kuasa yang dilaporkan kepada Penerima Kuasa dalam Berita Negara dan Tambahan Berita Negara Republik Indonesia dan/atau media lain yang ditetapkan oleh Penerima Kuasa.</li>
                    <li>Mengetahui, memperoleh, memeriksa dan mengklarifikasi termasuk namun tidak terbatas pada keberadaan dan kebenaran data dan/atau informasi keuangan Pemberi Kuasa yang berada pada:
                        <ol class="li-alpha">
                            <li>Lembaga keuangan bank maupun lembaga keuangan non bank;</li>
                            <li>Lembaga/ pihak/ profesi/ instansi pemerintah yang terkait efek;</li>
                            <li>Badan usaha dan/ atau perusahaan.</li>
                        </ol>
                    </li>
                    <li>Mengetahui dan memperoleh laporan mengenai data keuangan Pemberi Kuasa yang berada namun tidak terbatas pada:
                        <ol class="li-alpha">
                            <li>Lembaga keuangan bank maupun lembaga keuangan non bank;</li>
                            <li>Lembaga/ pihak/ profesi/ instansi pemerintah yang terkait efek;</li>
                            <li>Badan usaha dan/ atau perusahaan.</li>
                        </ol>
                    </li>
                </ol>
            </p>
            <p>
                 Sehubungan dengan itu, Penerima Kuasa berwenang menghadap dan/atau menghubungi lembaga baik di tingkat pusat maupun daerah dan/ atau pejabat yang berwenang maupun pihak-pihak lain yang terkait, menghadap kepada semua lembaga keuangan bank maupun lembaga keuangan non bank dan/ atau pejabat-pejabat yang berwenang maupun pihak-pihak terkait untuk mendapatkan keterangan-keterangan, dokumen-dokumen dan/ atau laporan setiap akhir tahun (baik asli maupun fotocopy) Pemberi Kuasa, melakukan segala tindakan hukum yang dianggap perlu dan penting serta berguna bagi Pemberi Kuasa sesuai dengan peraturan perundang-undangan yang berlaku.
             </p>
             <p>
                Surat Kuasa ini berlaku sejak LHKPN dikirim, kecuali apabila Pemberi Kuasa meninggal dunia atau setelah 5 (lima) tahun tidak lagi menjabat sebagai Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada di bawah pengampuan atau setelah mendapatkan persetujuan tertulis mengenai pencabutannya dari Penerima Kuasa.
             </p>
        </div>
        <div class="col-md-12" style="margin-top: 10px;">
            <label><input required type="checkbox" name="chk" value="1"> Saya Setuju</label>
        </div>
    </div> -->
    <div class="row">
        <div class="col-md-offset-8 col-md-2 text-right">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tunda</button>
            <input type="hidden" name="act" value="doverify" />
            <input type="hidden" name="ID_LHKPN" value="<?php echo $id?>" />
            <input type="hidden" name="ENTRY_VIA" value="<?php echo $id2?>" />
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-sm btn-success">Kirim</button>
        </div>
    </div>
</form>