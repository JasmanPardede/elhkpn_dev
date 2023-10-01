<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Pengumuman</title>

    <style>
        @page {
            margin: 115px 45px;
        }

        header {
            position: fixed;
            top: -70px;
            left: 0px;
            right: 0px;
            height: 50px;
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
            opacity: 0.15;
            z-index: -1;
        }

        body {
            font-family: Sans-serif;
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

        h2 {
            font-size: 1em;
        }

        h3 {
            font-size: 0.9em;
        }

        p {
            font-size: 0.8em;
        }

        #kotak {
            /*border-style: double;
    border-width: 3px;*/
            height: 50em;
            position: relative;
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

        #note {
            margin: 0 1.5em 0 1.5em;
        }

        #note>span {
            font-size: 0.8em;
            text-decoration: underline;
        }

        #table_catatan {
            width: 100%;
            font-size: 0.8em;
            margin: 1em 0 0;
            padding-left: 1.5em;
            line-height: 1.5em;
        }

        #table_catatan tr td:first-child {
            width: 2%;
            vertical-align: text-top;
        }

        #table_catatan tr td:nth-child(2) {
            text-align: justify;
        }

        #kop {
            height: 2.3em;
            padding: 0em 0 1em 0;
            font-family: Sans-serif;
        }



        #title_perlu_perbaikan {
            font-family: Sans-serif;
            font-size: 0.8em;
            margin: 0 1.5em 0 1.5em;
        }

        #div_table {
            font-size: 0.8em;
            font-family: Sans-serif;
            margin: 1.5em 1.5em;
        }

        #table_uraian {
            border-collapse: collapse;
            width: 100%;
        }

        #table_uraian tr th {
            border: 1px black solid;
            text-align: center;
            padding: 3px;
        }

        #table_uraian tr td:first-child {
            vertical-align: text-top;
        }

        #table_uraian tr td {
            border: 1px black solid;
            width: 50%;
            padding: 3px 10px;
        }

        #barcode {
            margin: 1.5em 1.5em;
        }
    </style>
</head>

<body>

    <header>
        <div id="kop" class="row">

        </div>
    </header>
    <main>
        <div id="kotak">
            <!--<img class="img-watermark" src="portal-assets/img/watermark2.jpeg">-->
            <!--"NAMA_LENGKAP" => $datapn->NAMA_LENGKAP,
                "NIK" => $datapn->NIK,
                "LEMBAGA" => $datapn->INST_NAMA,
                "JABATAN" => $datapn->NAMA_JABATAN,
                "BC_CODE" =>  $bc_image_location,-->
            <div id="title_perlu_perbaikan">
                <span>Daftar kekurangan kelengkapan yang harus diisi dan dilengkapi oleh Sdr. <?php echo $NAMA_LENGKAP.' ('.$NIK.'), '.$JABATAN.' - '.$LEMBAGA; ?>:</span>
            </div>


            <div id="div_table">
                <table id="table_uraian">
                    <thead>
                        <tr>
                            <th>JENIS</th>
                            <th>URAIAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                    $jenis_data_lhkpn_2 = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'DOKUMENPENDUKUNG' => 'Dokumen Pendukung', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];
                    foreach($msg_verifikasi->MSG as $key => $mv){
                        if($mv!=null){
                            $jenis_name = array_search($key, array_flip($jenis_data_lhkpn_2));
                            ?>
                        <tr>
                            <td>
                                <?php echo $jenis_name ?>
                            </td>
                            <td>
                                <?php echo textarea_to_html($msg_verifikasi->MSG->$key) ?>
                            </td>
                        </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <div id="note">
                <span>Keterangan :</span>
                <table id="table_catatan">
                    <tr>
                        <td>1.</td>
                        <td>Jika terdapat kekurangan kelengkapan Surat Kuasa dalam <b>tabel daftar kekurangan kelengkapan di atas</b>,
                            mohon  Surat Kuasa dicetak melalui aplikasi e-Filing LHKPN dan ditandatangan diatas meterai Rp. 10.000
                            oleh setiap nama yg disebutkan dalam uraian dan dikirimkan format aslinya ke alamat :
                            <br><br> Direktorat Pendaftaran dan Pemeriksaan LHKPN<br> Komisi Pemberantasan Korupsi<br> Gedung
                            Merah Putih KPK – Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta 12950
                            <br><br>
                        </td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Bagi yang melaporkan LHKPN dengan menggunakan aplikasi e-Filing LHKPN (<i>online</i>) daftar kekurangan
                            (selain surat kuasa) dapat dilengkapi dan diperbaiki melalui melalui elhkpn.kpk.go.id
                            <br><br>
                        </td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Wajib LHKPN mengirimkan kekurangan dokumen tersebut maksimal 30 hari kalender sejak diterimanya pemberitahuan bahwa LHKPN yang disampaikan masih perlu dilengkapi oleh Wajib LHKPN. Dalam hal Wajib LHKPN belum menyampaikan dokumen sampai dengan waktu yang telah ditentukan, maka LHKPN yang disampaikan tidak dapat diproses lebih lanjut dan yang bersangkutan dianggap belum menyampaikan LHKPN.</td>
                    </tr>
                    <?php /* ?>
                    <tr>
                        <td>3.</td>
                        <td>Bagi yang melaporkan LHKPN dengan format excel (<i>offline</i>-mulai tanggal 1 Juli 2018 KPK tidak
                            menerima penyampaian LHKPN format excel) daftar kekurangan (selain surat kuasa) dapat dikirimkan
                            melalui email elhkpn@kpk.go.id atau dikirimkan langsung atau via pos ke alamat sesuai nomor 1
                            (satu).</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Mohon lampirkan daftar kekurangan kelengkapan ini disertakan dalam pengiriman.</td>
                    </tr>
                    <?php */ ?>
                </table>
            </div>
            <?php /* ?>
            <div id="barcode">
                <img src="<?= $QR_IMAGE_LOCATION  ?>" width="100px" height="100px"style="margin:12px 10px 0 40px"/>
            </div>

            <div id="title_perlu_perbaikan">
                <span>Mohon lampiran daftar kekurangan kelengkapan ini disertakan dalam pengiriman baik melalui email atau via pos.</span>
            </div>
            <?php */ ?>
            <br><br>
            <div id="title_perlu_perbaikan">
                <span>Tim Verifikasi KPK</span>
            </div>



        </div>

    </main>
</body>

</html>