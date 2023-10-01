<html>
  <head>
    <title>SURAT KUASA</title>
  </head>
  <body>
      
                 <p style="text-align: center; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  <h2>LAMPIRAN 4 - SURAT KUASA</h2>
                </p><br><br>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  Dengan menyetujui pernyataan di bawah ini, Saya menyatakan bahwa 	:
                </p>
                <table style="width:100%;">
                   <tbody><tr>
                      <td style="width:30%;">Nama (Sesuai dengan KTP)</td>
                      <td style="width:1%;">:</td>
                      <td id="KELUARGA_NAMA"><?php echo $data['NAMA'];?></td>
                   </tr>
                   <tr>
                      <td style="width:30%;">Tempat / Tanggal Lahir</td>
                      <td style="width:1%;">:</td>
                      <td id="KELUARGA_TTL"><?php echo $data['TTL'];?></td>
                   </tr>
                   <tr>
                      <td style="width:30%;">Nomor KTP / NIK</td>
                      <td style="width:1%;">:</td>
                      <td id="KELUARGA_KTP"><?= $data['NOMOR_KTP'] ?></td>
                   </tr>
                   <tr>
                      <td style="width:30%;">Alamat</td>
                      <td style="width:1%;">:</td>
                      <td id="KELUARGA_ALAMAT"><?php echo $data['ALAMAT'];?></td>
                   </tr>
                </tbody></table>
                 <br>
                 <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                    (Selanjutnya disebut sebagai / "Pemberi Kuasa")---------------------------------------------------------------------------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                    Dengan ini memberikan kuasa dengan hak substitusi kepada :--------------------------------------------------------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                   Pimpinan Komisi Pemberantasan Korupsi ("KPK"), beralamat di Jl. Kuningan Persada Kav. 4, Jakarta Selatan, 12950, Indonesia,
                   yang bertindak baik secara bersama-sama maupun sendiri-sendiri (selanjutnya disebut "Penerima Kuasa").----------------------------------------------------------------------------------------------------------------------------------------------
                </p>

                <div style="width:100%;text-align:center"><b>---KHUSUS---</b></div>

                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  Untuk dan atas nama Pemberi Kuasa : ------------------------------------------------------------------------------------------------------------------
                </p>

                <ol style="text-align: justify; font-size: 13px; line-height: 1.5; color: rgb(0, 0, 0); margin: 0px;">
                   <li>
                      1. Mengetahui,memperoleh,memeriksa dan mengklarifikasi termasuk namun tidak terbatas pada keberadaan dan kebenaran data dan/atau informasi
                      keuangan Pemberi Kuasa yang berada pada:-----------------------------------------------
                       <ol style="list-style-type: lower-alpha; text-align: justify; font-size: 13px; line-height: 1.5; color: rgb(0, 0, 0); margin: 0px;">
                         <li>a. Lembaga keuangan bank maupun lembaga keuangan non bank;</li>------------------------------------------------------------------
                         <li>b. Lembaga/ pihak/ profesi/ instansi pemerintah yang terkait efek;</li>-----------------------------------------------------------------
                         <li>c. Badan usaha dan/ atau perusahaan.</li>-------------------------------------------------------------------------------------------------------------
                      </ol> 
                   </li>
                   <li>
                     2. Mengetahui dan memperoleh laporan mengenai data keuangan Pemberi Kuasa yang berada namun tidak terbatas pada:-------------------------------------------------------------------------------------------------------------------------------------------------
                     <ol style="list-style-type: lower-alpha; text-align: justify; font-size: 13px; line-height: 1.5; color: rgb(0, 0, 0); margin: 0px;">
                         <li>a. Lembaga keuangan bank maupun lembaga keuangan non bank;</li>-----------------------------------------------------------------
                         <li>b. Lembaga/ pihak/ profesi/ instansi pemerintah yang terkait efek;</li>-----------------------------------------------------------------
                         <li>c. Badan usaha dan/ atau perusahaan.</li>-------------------------------------------------------------------------------------------------------------
                     </ol>
                   </li>
                </ol>
                <br>

                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  Sehubungan dengan itu, Penerima Kuasa berwenang menghadap kepada semua lembaga keuangan bank maupun lembaga keuangan non bank
                  dan/ atau pejabat-pejabat yang berwenang maupun pihak-pihak terkait untuk mendapatkan keterangan-keterangan, dokumen-dokumen
                  dan/ atau laporan setiap akhir tahun (baik asli maupun fotocopy) Pemberi Kuasa, melakukan segala tindakan
                  hukum yang dianggap perlu dan penting serta berguna bagi Pemberi Kuasa sesuai dengan peraturan perundang-undangan
                  yang berlaku.---------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  Surat Kuasa ini berlaku sejak ditandatangani kecuali apabila Pemberi Kuasa meninggal dunia atau setelah 5 (lima) tahun tidak lagi menjabat sebagai
                  Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada dibawah pengampuan atau setelah mendapatkan persetujuan
                  tertulis mengenai pencabutannya dari Penerima Kuasa.--------------------------------------------------------------------------------------------------------------------------------------------------
                </p>
                <table style="width:100%;">
                    <tbody><tr>
                       <td style="width:80%"></td>
                       <td id="KELUARGA_TGL_KIRIM" style="width:20%; text-align:center;">
                          <?php echo tgl_format($data['tgl_kirim']);?>
                       </td>
                    </tr>
                    <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%; text-align:center;"><b>Pemberi Kuasa,</b></td>
                    </tr>
                </tbody></table>
                <table style="width:100%; margin-top:50px;">
                    <tbody><tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                    <tr>
                       <td style="width:80%"></td>
                       <td id="KELUARGA_TTD" style="width:20%; text-align:center;"><?php echo $data['NAMA'];?></td>
                    </tr>
                </tbody></table>
                <?php 
                  if($OPTION!='-1'){
                   if($OPTION=='1'){
                      echo "<b>Setuju (v)</b>";
                    }else{
                      echo "<b>Tidak Setuju (v)</b>";
                    }
                  }
                ?>  

  </body>
</html>