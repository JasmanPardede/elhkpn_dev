<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Tanda Terima</title>

<style>
body{
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
h2{
    font-size:1em;
}
h3{
    font-size:0.9em;
}
p{
    font-size:0.8em;
}
#kotak{
    border-style: double;
    border-width: 3px;
    /*height:53em;*/
    margin-top:2em;
    position: relative;
}
#kotak:before {
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
}
#kop{
    height:6em;
    padding:1.5em 0 1em 0;
    font-family: Sans-serif;
}
#title{
    text-align:center;
    line-height:0.5;
}
#logo{
    margin:0 4.3em 0 1.2em;
    padding:0.7em 0 0;
    width:13%;
}
#header{
    text-align:center;
    color:white;
    background-color:black;
    font-weight:bold;
    margin:0 1.2em 0 1.2em;
    line-height:0.5;
    padding:0.1em 0 0.1em 0;
    font-size:1.1em;
}
#form_biodata{
    /*height:17em;*/
    padding:1.5em 3em 0em 4em;
    font-size:1em;
    line-height:30px;
}
#table_biodata{ 
    width:100%;
}
#table_biodata tr td:nth-child(3) { 
    border-bottom:solid 1px black;
}
#table_biodata tr td:nth-child(2) { 
    width:3%;
}
#table_biodata tr td:first-child{
    width:22%;
}
#catatan{
    padding:1.5em 3em 0em 4em;
}
#hasil_verifikasi{
    width:450px;
}
#hasil_verifikasi_v2{
    width:520px;
    font-size:0.9em;
}
#qr_code{
    width:130px;
}
#checkbox_lengkap{
    width:30px;
    height:25px;
    border:solid 1px black;
    text-align:center;
}
#text_lengkap{
    margin-left:3em;
    line-height:5px;
}
#garis_pembatas{
    border-bottom:solid 3px black; 
    margin:0 1.5em 0em 1.5em;
}
#note{
    margin:0 1.2em 0 1.2em;
    font-style:italic;
}
#footer{
    margin:0 1.2em 0 1.2em;
    font-size:1.1em;
    line-height:8px;
}
#catatan_footer{
    margin-top:1em;
}
#garis_footer{
    border-top:dashed 0.8px black;
}
#tgl_verif{
    font-size:10;
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    text-align: right;
}

#note_bukti{
    font-style: italic;
    margin:0 1.2em 0 1.2em
}

</style>
</head>
<body>
<div id="kotak">
    <div id="kop" class="row">
        <div class="column" id="logo">
        <?php $img_src = ($TAHUN_KIRIM < "2021") ? "portal-assets/img/KPK_Logo.svg.png" : "portal-assets/img/kpk_new_logo.png" ?>
            <img src=<?=$img_src ?> width= <?= ($TAHUN_KIRIM < "2021") ? "80px" : "140px" ;?> />
        </div>
        <div class="column" id="title">
            <h2>KOMISI PEMBERANTASAN KORUPSI</h2>
            <h2>REPUBLIK INDONESIA</h2>
            <p>Jl. Kuningan Persada Kav. 4, Setiabudi</p>
            <p>Jakarta 12950</p>
        </div>
    </div>
    <div id="header">
        <h2>TANDA TERIMA</h2>
        <h2>LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</h2>
    </div>
    <div id="form_biodata">
        <table id="table_biodata">
            <tr>
                <td>Atas Nama</td>
                <td>:</td>
                <td><?php echo $NAMA_LENGKAP ?></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?php echo $NIK ?></td>
            </tr>
            <tr>
                <td>Lembaga</td>
                <td>:</td>
                <td><?php echo $LEMBAGA ?></td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td><?php echo $UNIT_KERJA ?></td>
            </tr>
            <tr>
                <td>Sub Unit Kerja</td>
                <td>:</td>
                <td><?php echo $SUB_UNIT_KERJA ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?php echo $JABATAN ?></td>
            </tr>
            <tr>
                <td>Jenis Laporan</td>
                <td>:</td>
                <td><?php echo $JENIS.' - '.$KHUSUS ?></td>
            </tr>
            <tr>
                <td>Tanggal Kirim</td>
                <td>:</td>
                <td><?php echo $TANGGAL ?></td>
            </tr>
        </table>
    </div>
    <br><br>
    <div id="garis_pembatas"></div>
    
    <?php if($TAHUN_KIRIM < "2021") : ?>
        <div id="catatan" class="row">
            <div class="column" id="hasil_verifikasi">
            <h3>Catatan Hasil Verifikasi Administrasi: </h3>
            <div class="row">
                <div class="column" id="checkbox_lengkap"><?php echo $LKP ?></div>
                <div class="column" id="text_lengkap"><p>Lengkap</p></div>
            </div><br><br>
            <div class="row">
                <div class="column" id="checkbox_lengkap"><?php echo $TLKP ?></div>
                <div class="column" id="text_lengkap"><p>Tidak Lengkap</p></div>
            </div>
            </div>
            <div class="column" id="qr_code">
                <img src="<?php echo $qr_code ?>" width="130px"/>
            </div>
        </div>
        <br><br>
    <?php endif; ?>
    
    <?php if($TAHUN_KIRIM >= "2021") : ?>
        <div id="note" class="row">
            <div class="column" id="hasil_verifikasi_v2">
                <p>Catatan:</p>
                <p>Tanda Terima ini diberikan sebagai bukti bahwa Penyelenggara Negara telah memenuhi kewajiban penyampaian LHKPN.</p>
            </div>
            <div class="column" id="qr_code">
                <img src="<?php echo $qr_code ?>" width="130px"/>
            </div>
        </div>
    <?php endif; ?>
    <br>
    <br>
    <?php if($TAHUN_KIRIM < "2021") : ?>
        <div id="note_bukti"><p>*) Lembar ini adalah bukti resmi penyampaian LHKPN setelah melalui proses verifikasi administrasi. </p></div>
    <?php endif; ?>
    <div id="footer">
        <div id="catatan_footer"><p>Direktorat PP LHKPN | Call Center: 198 | email: elhkpn@kpk.go.id | https://elhkpn.kpk.go.id </p></div>
        <div id="garis_footer"></div>
        <div id="catatan_footer"><p>Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini.</p></div>
    </div>
</div>
<div id="tgl_verif"><p>Tanggal Verifikasi : <?php echo $TGL_VERIFIKASI ?></p></div>
</body>
</html>