<html>
  <head>
     <title>SURAT PERSETUJUAN INTEGRASI DATA ISIAN LHKPN DENGAN ALPHA</title>
  </head>
  <body>
  <div style="font-family:Arial">

                 <p style="text-align: center; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                 <h2>LAMPIRAN 5 - SURAT PERSETUJUAN INTEGRASI DATA ISIAN LHKPN DENGAN ALPHA</h2>
                </p><br><br>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                    Yang bertanda-tangan di bawah ini	:
                </p>
                <table style="width:100%; font-family:Arial;">
                   <tbody><tr>
                      <td style="width:30%; font-family:Arial;">Nama</td>
                      <td style="width:1%; font-family:Arial;">:</td>
                      <td id="PERSETUJUAN_NAMA" style="font-family:Arial"><?php echo $data->NAMA_LENGKAP;?></td>
                   </tr>
                   <tr>
                      <td style="width:30%; font-family:Arial;">Tempat / Tanggal Lahir</td>
                      <td  style="width:1%; font-family:Arial;">:</td>
                      <td id="PERSETUJUAN_TTL" style="font-family:Arial"><?php echo $data->TEMPAT_LAHIR;?> - <?php echo tgl_format($data->TANGGAL_LAHIR);?></td>
                   </tr>
                   <tr>
                      <td style="width:30%; font-family:Arial;">Nomor KTP / NIK</td>
                      <td  style="width:1%; font-family:Arial;">:</td>
                      <td id="PERSETUJUAN_KTP" style="font-family:Arial"><?php echo $data->NIK;?></td>
                   </tr>
                   <tr>
                      <td style="width:30%; font-family:Arial;">Alamat</td>
                      <td  style="width:1%; font-family:Arial;">:</td>
                      <td id="PERSETUJUAN_ALAMAT" style="font-family:Arial">
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
                    selanjutnya disebut sebagai “Pemberi Persetujuan”, dengan ini menyatakan bahwa:
                </p>
                <ol start="1">
                    <li><p>persetujuan untuk mengintegrasikan seluruh data dan Informasi isian Laporan Harta Kekayaan Penyelenggara Negara (LHKPN) yang disampaikan kepada Komisi Pemberantasan Korupsi dengan data laporan harta kekayaan pada Kementerian Keuangan melalui Aplikasi Laporan Perpajakan dan Harta Kekayaan Pegawai Kementerian Keuangan (ALPHA);.</p></li>
                    <li><p>untuk keperluan tersebut, Komisi Pemberantasan Korupsi dapat memberikan seluruh data dan Informasi isian LHKPN atas nama Pemberi Persetujuan kepada Kementerian Keuangan baik secara elektronik maupun non-elektronik, serta melakukan segala tindakan untuk mencapai maksud dan tujuan sebagaimana dimaksud pada angka 1; dan</p></li>
                    <li><p>melepaskan Komisi Pemberantasan Korupsi dari segala tuntutan hukum jika di kemudian hari Kementerian Keuangan menggunakan data dan informasi isian LHKPN untuk kepentingan selain tujuan sebagaimana dimaksud pada angka 1 tanpa persetujuan Komisi Pemberantasan Korupsi ataupun Pemberi Persetujuan.</p></li>
                </ol>
                <p style="text-align: justify; font-size: 13px; line-height: 1.5; margin: 0px 0px 10px;">
                    Demikian Persetujuan ini dibuat dengan sebenarnya dalam keadaan bebas dan tanpa paksaan untuk dapat dipergunakan sebagaimana mestinya.
                </p>
				
                <table style="width:100%;">
                    <tbody><tr>
                       <td style="width:80%"></td>
                       <td style="width:20%; text-align:center; font-family:Arial;" id="PERSETUJUAN_DATE">
                         <?php echo tgl_format($data->persetujuan_date);?>
                       </td>
                    </tr>
                    <tr>
                       <td style="width:80%"></td>
                       <td style="width:20%; text-align:center; font-family:Arial;">Pemberi Persetujuan</td>
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
                       <td style="width:20%; text-align:center; font-family:Arial;" id="PERSETUJUAN_TTD"><?php echo $data->NAMA_LENGKAP;?></td>
                    </tr>
                </tbody></table><br>
                <?php 
                  if($data->option!='-1'){
                   if($data->option=='1'){
          						echo "<b>Setuju (v)</b>";
          					}else{
          						echo "<b>Tidak Setuju (v)</b>";
          					}
                  }
                ?>  
</div>
  </body>
</html>
