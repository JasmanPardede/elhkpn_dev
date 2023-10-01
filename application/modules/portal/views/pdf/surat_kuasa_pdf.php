<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Surat Kuasa</title>

    <style>
        @page {
            margin: 0px 45px 0px;
        }

        header {
            position: fixed;
            top: -70px;
            left: 0px;
            right: 0px;
            height: 0px;
        }

        header:before {
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            background-image: url(portal-assets/img/watermark2.jpeg);
            width: 100%;
            height: 100%;
            opacity: 0.1 ;
            z-index: -1;
        }

        body {
            font-family: Calibri;
            /*font-size:0.8em;*/
        }



        .column {
            float: left;
            width: 50%;
        }

        /* Clear floats after the columns */

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /*h2 {
            font-size: 1em;
        }

        h3 {
            font-size: 0.9em;
        }*/

        /*p {
            font-size: 0.8em;
        }*/

        #kotak {
            /*border-style: double;
            border-width: 3px;*/
            height: 49em;
            position: relative;
            border:5px double black;
            margin-top:10px;
            padding:0 20px 0;
            line-height:0.95em;
        }

        /*#kotak:before {
    content : "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    background-image: url(portal-assets/img/watermark2.jpeg);
    width: 100%;
    height: 100%;
    opacity : 0.15;
    z-index: -1;
}*/



        /*#note {
            margin: 0 1.5em 0 1.5em;
        }

        #note > div {
            font-size: 0.8em;
            text-decoration:underline;
        }

        #table_catatan {
            width: 100%;
            font-size: 0.8em;
            margin:1em 0 0;
            padding-left:1.5em;
            line-height:1.5em;
        }

        #table_catatan tr td:first-child {
            width: 2%;
            vertical-align: text-top;
        }

        #table_catatan tr td:nth-child(2) {
            text-align: justify;*/
        }

        #kop {
            /*height: 3em;*/
            font-weight:bold;
            padding: 0em 0 1em 0;
            font-family: Calibri;
            text-align:center;
            margin-top:1em;
        }



        /*#title_perlu_perbaikan {
            font-family: Calibri;
            font-size:0.8em;
            margin: 0 1.5em 0 1.5em;
        }

        #div_table{
            font-size: 0.8em;
            font-family: Calibri;
            margin: 1.5em 1.5em;
        }

        #table_uraian{
            border-collapse: collapse;
        }

        #table_uraian tr th{
            border:1px black solid;
            text-align:center;
            padding:3px;
        }

        #table_uraian tr td:first-child {
            vertical-align: text-top;
        }

        #table_uraian tr td{
            border:1px black solid;
            width:50%;
            padding:3px 10px;
        }*/

        #barcode{
            margin:1.5em 1.5em;
        }

        div{
            font-size:0.95em;
        }

    </style>
</head>

<body>

    <header>
        <div class="row">
        </div>
    </header>
    <main>
        <div id="kotak">
            <!--<img class="img-watermark" src="portal-assets/img/watermark2.jpeg">-->
            <div id="kop" class="row">


                LAMPIRAN 4 - SURAT KUASA
            </div>
            <div>Yang bertanda-tangan di bawah ini : ---------------------------------------------------------------------------------------------------------------------------------------------------------------
            </div>
            <table style="margin:0.5em 0em;">
                <tr>
                    <td>Nama (sesuai dengan KTP)</td>
                    <td>:</td>
                    <td><?= $NAMA_LENGKAP ?></td>
                </tr>
                <tr>
                    <td>Tempat/Tanggal Lahir</td>
                    <td>:</td>
                    <td><?= $TEMPAT_LAHIR ?> / <?= $TANGGAL_LAHIR ?></td>
                </tr>
                <tr>
                    <td>Nomor KTP/NIK</td>
                    <td>:</td>
                    <td><?= $NIK ?></td>
                </tr>
                <tr>
                    <td style="vertical-align:text-top;">Alamat</td>
                    <td style="vertical-align:text-top;">:</td>
                    <td><?= $ALAMAT_LENGKAP ?></td>
                </tr>
            </table>
            <div>
                (selanjutnya disebut sebagai “Pemberi Kuasa”). -------------------------------------------------------------------------------------------------------------------------------------------------
            </div>
            <div style="margin:0.5em 0em;">
                Dengan ini memberi kuasa dengan hak substitusi kepada: -------------------------------------------------------------------------------------------------------------------------------------
            </div>
            <div>
                Pimpinan Komisi Pemberantasan Korupsi (“KPK”), beralamat di Jl. Kuningan Persada Kav. 4, Jakarta Selatan, 12950, Indonesia, yang bertindak baik secara bersama-sama maupun sendiri-sendiri (selanjutnya disebut “Penerima Kuasa”).
                ----------------------------------------------------------------------------------------------------------------------
            </div>
            <div style="margin:0.5em 0em 0em;">
                 ---------------------------------------------------------------------------------------------- K H U S U S --------------------------------------------------------------------------------------------
            </div>
            <div style="margin:0.5em 0em;">
                Untuk dan atas nama Pemberi Kuasa: -------------------------------------------------------------------------------------------------------------------------------------------------------------
            </div>
            <table>
                <tr>
                    <td style="vertical-align:text-top;">1.</td>
                    <td>Mengetahui, memperoleh, memeriksa dan mengklarifikasi termasuk namun tidak terbatas pada keberadaan dan kebenaran data dan/atau informasi keuangan Pemberi Kuasa yang berada pada:
                    --------------------------------------------------------------------------------------------------------------------------------------</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table>
                            <tr>
                                <td>a.</td>
                                <td>Lembaga keuangan bank maupun lembaga keuangan non bank;</td>
                            </tr>
                            <tr>
                                <td>b.</td>
                                <td>Lembaga/ pihak/ profesi/ instansi pemerintah yang terkait efek;</td>
                            </tr>
                            <tr>
                                <td>c.</td>
                                <td>Badan usaha dan/ atau perusahaan</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:text-top;">2.</td>
                    <td>Mengetahui dan memperoleh laporan mengenai data keuangan Pemberi Kuasa yang berada namun tidak terbatas pada:
                    --------------------------------------------</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                            <table>
                            <tr>
                                <td>a.</td>
                                <td>Lembaga keuangan bank maupun lembaga keuangan non bank;</td>
                            </tr>
                            <tr>
                                <td>b.</td>
                                <td>Lembaga/ pihak/ profesi/ instansi pemerintah yang terkait efek;</td>
                            </tr>
                            <tr>
                                <td>c.</td>
                                <td>Badan usaha dan/ atau perusahaan.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="text-align:justify">Sehubungan dengan itu, Penerima Kuasa berwenang menghadap kepada semua lembaga keuangan bank maupun lembaga keuangan non bank dan/ atau pejabat-pejabat yang berwenang maupun pihak-pihak terkait untuk mendapatkan keterangan-keterangan, dokumen-dokumen dan/ atau laporan setiap akhir tahun (baik asli maupun fotocopy)     Pemberi Kuasa, melakukan segala tindakan hukum yang dianggap perlu dan penting serta berguna bagi Pemberi Kuasa sesuai dengan peraturan perundang-undangan yang berlaku.
                        ----------------------------------------------------------------------------------------------------------------------------------------
                    </td>
                </tr>
                <tr>
                    <td style="text-align:justify">Surat Kuasa ini berlaku sejak ditandatangani kecuali apabila Pemberi Kuasa meninggal dunia atau setelah 5 (lima) tahun tidak lagi menjabat sebagai Penyelenggara Negara terhitung sejak tanggal berakhirnya jabatan atau berada di bawah pengampuan atau setelah mendapatkan persetujuan tertulis mengenai pencabutannya dari Penerima Kuasa.
                        --------------------------------------------------------------------------------------------------------------------------------------------------
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td width="700px">
                        <img src="<?= $QR_IMAGE_LOCATION  ?>" width="100px" height="100px" style="margin:12px 10px 0 40px"/>
                        <img src="<?= $QR2_IMAGE_LOCATION ?>" width="100px" height="100px" style="margin-left: 40px; margin-top: 10px;"/>
                        <br>
                        <table>
                            <tr>
                                <!-- <td></td>
                                <td>Lembaran ini dapat difotokopi dan diperbanyak sesuai dengan kebutuhan</td> -->
                                <td></td><td></td>
                            </tr>
                            <tr>
                                <!-- <td>**)</td>
                                <td>Coret yang tidak perlu</td> -->
                                <td></td><td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Redaksi surat kuasa ini tidak dapat diubah</td>
                            </tr>
                        </table>
                    </td>
                    <td width="200px" style="text-align:center">

                        <?= $TGL_CETAK ?><br>
                        Pemberi Kuasa,
                        <div style="border:1px dashed black;margin: 10px 40px 10px;height:5em;padding-top:1.4em;">Meterai<br>Rp. 10000,-</div>

                        (<?= $NAMA_LENGKAP ?>)

                    </td>
                </tr>
            </table>

        </div>

    </main>
</body>

</html>