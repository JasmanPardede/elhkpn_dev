<html>
  <head>
     <title>SURAT KUASA LHKPN</title>
  </head>
  <body>
  <div style="font-family:Arial">

                 <p style="text-align: center; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  <h2>LAMPIRAN 3 - SURAT KUASA MENGUMUMKAN</h2>
                </p><br><br>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  Dengan menyetujui pernyataan di bawah ini, Saya menyatakan bahwa 	:
                </p>
                <table style="width:100%; font-family:Arial;">
                   <tbody><tr>
                      <td style="width:30%; font-family:Arial;">Nama</td>
                      <td style="width:1%; font-family:Arial;">:</td>
                      <td id="KUASA_NAMA" style="font-family:Arial"><?php echo $data->NAMA_LENGKAP;?></td>
                   </tr>
                   <tr>
                      <td style="width:30%; font-family:Arial;">Tempat / Tanggal Lahir</td>
                      <td  style="width:1%; font-family:Arial;">:</td>
                      <td id="KUASA_TTL" style="font-family:Arial"><?php echo $data->TEMPAT_LAHIR;?> - <?php echo tgl_format($data->TANGGAL_LAHIR);?></td>
                   </tr>
                   <tr>
                      <td style="width:30%; font-family:Arial;">Nomor KTP / NIK</td>
                      <td  style="width:1%; font-family:Arial;">:</td>
                      <td id="KUASA_KTP" style="font-family:Arial"><?php echo $data->NIK;?></td>
                   </tr>
                   <tr>
                      <td style="width:30%; font-family:Arial;">Alamat</td>
                      <td  style="width:1%; font-family:Arial;">:</td>
                      <td id="KUASA_ALAMAT" style="font-family:Arial">
                        <?php

                          if($data->NEGARA=='2'){
                            echo strtoupper($data->ALAMAT_NEGARA);
                          }else{
                             echo strtoupper($data->ALAMAT_RUMAH.' , '.$data->KELURAHAN.' , '.$data->KECAMATAN.' ,  '.$data->KABKOT.' , '.$data->PROVINSI);
                          }
                        ?>
                      </td>
                   </tr>
                </tbody></table>
                 <br>
                 <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                    (Selanjutnya disebut sebagai / "Pemberi Kuasa")---------------------------------------------------------------------------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                    Dengan ini memberikan kuasa dengan hak subsitusi kepada:--------------------------------------------------------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                   Pimpinan Komisi Pemberantasan Korupsi ("KPK"), beralamat di Gedung Merah Putih KPK - Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta 12950, Indonesia
                   yang bertindak baik secara bersama-sama maupun sendiri-sendiri (selanjutnya disebut "Penerima Kuasa").----------------------------------------------------------------------------------------------------------------------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  Untuk dan atas nama Pemberi Kuasa mengumumkan seluruh harta kekayaan Pemberi Kuasa yang dilaporkan kepada Penerima Kuasa dalam Berita Negara
                  dan Tambahan Berita Negara Republik Indonesia dan/atau media yang ditetapkan oleh Penerima Kuasa.------------------------------------------------------------------------------------------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                   Sehubungan dengan itu Penerima Kuasa berwenang menghadap dan/atau mengubungi lembaga baik ditingkat pusat maupun daerah dan/atau
                   pejabat yang berwenang maupun pihak  - pihak lain yang terkait, melaksanakan segala tindakan yang dianggap perlu dan penting serta berguna bagi Penerima Kuasa
                   sesuai dengan peraturan perundang - undangan yang berlaku.--------------------------------------------------------------------------------------------------------------------
                </p>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                  Surat Kuasa ini berlaku sejak ditandatangani kecuali apabila Pemberi Kuasa meninggal dunia atau setelah 5 (lima) tahun tidak lagi menjabat sebagai
                  Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada dibawah pengampunan atau setelah mendapatkan persetujuan
                  tertulis mengenai pencabutannya dari Penerima Kuasa.--------------------------------------------------------------------------------------------------------------------------------------------------
                </p>
				
                <table style="width:100%;">
                    <tbody><tr>
                       <td style="width:80%"></td>
                       <td style="width:20%; text-align:center; font-family:Arial;" id="KUASA_TGL_KIRIM">
                         <?php echo tgl_format($data->tgl_kirim);?>
                       </td>
                    </tr>
                    <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%; text-align:center; font-family:Arial;"><b>Pemberi Kuasa,</b></td>
                    </tr>
                </tbody></table>
                <table style="width:100%; margin-top:50px;">
                    <tbody>
                    <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                     <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                     <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                     <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                     <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                     <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                     <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%"></td>
                    </tr>
                    <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%; text-align:center; font-family:Arial;" id="KUASA_TTD"><?php echo $data->NAMA_LENGKAP;?></td>
                    </tr>
                </tbody></table><br>
                <?php 
                  if($OPTION!='-1'){
                   if($OPTION=='1'){
          						echo "<b>Setuju (v)</b>";
          					}else{
          						echo "<b>Tidak Setuju (v)</b>";
          					}
                  }
                ?>  
</div>
  </body>
</html>
