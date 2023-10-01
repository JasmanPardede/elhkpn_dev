<html>
    <head>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                padding: 1px;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #4CAF50;
                color: white;
            }

        </style>
    </head>
    <body>
        <div>
            <div class="row">
                <div class="col-lg-12">
                    <div>Yth. Sdr <?php echo $datapn->NAMA; ?></div>
                </div>
            </div>

        </div>
        <div><br />
        </div>
        <div><?php echo $datapn->INST_NAMA; ?></div>
        <div><br />

        </div>
        <div>Di Tempat </div>
        <div> 
            <br />
            <div>Bersama ini kami informasikan bahwa LHKPN yang Bapak/Ibu kirimkan telah kami terima dengan ringkasan sebagai berikut : </div><br/><br/>
            <div> 
                <table>
                    <tr>
                        <td align="center" valign="right"><img src="https://elhkpn.kpk.go.id/images/KPK_Logo.svg.png" align="middle"></td>
                        <td colspan="2" ><p><strong><H4><center><br />KOMISI PEMBERANTASAN KORUPSI<br />REPUBLIK INDONESIA</center></H4></strong><br /><center>Jl. Kuningan Persada Kav. 4, Setiabudi<br />Jakarta 12950</center></p></td>
                    </tr>
                    <tr>
                        <td colspan="3"><H4><p><strong><center>LEMBAR PENYERAHAN FORMULIR<BR>LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</center></strong></p></H4><br /></td>
                    </tr>
                    <tr>
                        <td><strong>Atas Nama</strong></td>
                        <td width="5%" align="center">:</td>
                        <td width="72%"><?php echo $datapn->NAMA; ?></td>	
                    </tr>
                    <tr>
                        <td><strong>Jabatan</strong></td>
                        <td align="center">:</td>
                        <td><?php echo $datapn->NAMA_JABATAN . ' - ' . $datapn->SUK_NAMA . ' - ' . $datapn->UK_NAMA; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Bidang</strong></td>
                        <td align="center">:</td>
                        <td><?php echo $datapn->BDG_NAMA; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Lembaga</strong></td>
                        <td align="center">:</td>
                        <td><?php echo $datapn->INST_NAMA; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal / Tahun Pelaporan</strong></td>
                        <td align="center">:</td>
                        <td><?php echo $tahun; ?></td>
                    </tr>
                </table>				
            </div>
        </div><br />
        <?php if ($STATUS_LHKPN_PREV == '2' || $STATUS_LHKPN_PREV == '5' || $STATUS_LHKPN_PREV == '6') { ?>
        <div> Berdasarkan data yang kami miliki bahwa laporan LHKPN terdahulu Saudara tercatat tidak lengkap, oleh karena itu mohon segera mencetak dan menandatangani di atas meterai setiap nama dalam Surat Kuasa yang terlampir dalam email ini dan mengirimkannya ke Direktorat Pendaftaran dan Pemeriksaan LHKPN KPK. Apabila Saudara tidak mendapatkan lampiran, silakan mengunduh di halaman Riwayat Harta dan Data Keluarga aplikasi e-Filing LHKPN.</div>
        <br />
        <?php } ?>
        <div> Email konfirmasi Lembar Penyerahan LHKPN ini bukan merupakan Tanda Terima LHKPN. Tanda terima LHKPN akan kami kirimkan setelah Dokumen Kelengkapan telah kami terima dan LHKPN telah diverifikasi oleh Direktorat Pendaftaran dan Pemeriksaan LHKPN.</div>
        <br />
        <div> Terlampir rincian harta kekayaan dalam ikhtisar LHKPN merupakan dokumen yang dicetak secara otomatis dari <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a>. Seluruh data dan informasi yang tercantum dalam Dokumen ini sesuai dengan LHKPN yang diisi dan dikirimkan sendiri oleh Saudara melalui <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a> serta tidak dapat dijadikan dasar oleh Saudara atau siapapun juga untuk menyatakan bahwa harta yang bersangkutan tidak terkait tindak pidana. Ikhtisar LHKPN bukan merupakan dokumen final dan masih dapat berubah setelah dilakukan verifikasi oleh Direktorat Pendaftaran dan Pemeriksaan LHKPN.</div>
        <br />
        <div> Setelah mengirimkan LHKPN, Lampiran 4. Surat Kuasa dapat segera dikirimkan dengan batas waktu 2 minggu (jika Surat Kuasa sudah pernah disampaikan tidak perlu dikirim kembali).</div>
        <br />
        <div> Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a> atau call center 198.</div>
        <br />
        <div> Atas kerjasama yang diberikan, Kami ucapkan terima kasih</div>
        <br/>
        <div> Direktorat Pendaftaran dan Pemeriksaan LHKPN</div><br/>
        <hr style="border: 0; border-bottom: 1px dashed #000;">
        <div> Email ini dikirimkan secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</div><br/>
        <div> &copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</div>

        <!--<div> Email konfirmasi Lembar Penyerahan LHKPN ini  bukan merupakan Tanda Terima LHKPN. Tanda terima LHKPN akan kami kirimkan setelah Dokumen Kelengkapan telah kami terima dan LHKPN telah diverifikasi oleh Direktorat Pendaftaran dan Pemeriksaan LHKPN. Untuk informasi lebih lanjut, silakan menghubungi  kami kembali melalui email elhkpn@kpk.go.id atau call center 198.</div>
        <br />
        <div>  Atas kerjasama yang diberikan, Kami ucapkan terima kasih</div>
        <br/><br/>
        <div>  Direktorat Pendaftaran dan Pemeriksaan LHKPN</div><br/>
        <hr style="border: 0; border-bottom: 1px dashed #000;">
        <div><i>Email ini dikirimkan secara otomatis oleh sistem e-LHKPN, kami tidak melakukan pengecekan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi call center 198 atau <a href="mailto:elhkpn@kpk.go.id">elhkpn@kpk.go.id</a></i></div><br/><br/><br/>
        <div>  Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198</div>-->

    </body>
</html>