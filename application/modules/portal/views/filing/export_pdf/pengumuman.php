<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Pengumuman</title>

<style>
@page { margin: 100px 45px 30px; }
header { position: fixed; top: -70px; left: 0px; right: 0px;height: 50px; }
header:before{
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
    /* border-style: double;
    border-width: 1px; */
    border: 1px solid rgba(0, 0, 0, 0);
    height:50em;
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
#header{
    text-align:center;
    color:black;
    /*background-color:black;*/
    font-weight:bold;
    margin:0 1.2em 0 1.2em;
    line-height:0.5;
    padding:0.1em 0 0.1em 0;
    font-size:1.1em;
}
#form_biodata{
    padding:1.5em 3em 1.5em 2em;
    font-size:0.8em;
    line-height:25px;
}
#table_instansi{ 
    width:100%;
    font-weight:bold;
}
#table_instansi tr td:nth-child(2) { 
    width:3%;
}
#table_instansi tr td:first-child{
    width:14%;
}

#table_filling{ 
    width:100%;
    font-weight:bold;
}
#table_filling tr td:first-child{
    width:3%;
}
#table_filling tr td:nth-child(2) { 
    width:77%;
}
#table_filling tr td:nth-child(3) { 
    width:10%;
}
#table_filling tr td:nth-child(4) { 
    width:20%;
    text-align:right;
}

#table_pribadi{ 
    width:100%;
    font-weight:none;
}
#table_pribadi tr td:first-child{
    width:2%;
}
#table_pribadi tr td:nth-child(2) { 
    width:8%;
}
#table_pribadi tr td:nth-child(3) { 
    width:1%;
}
#table_pribadi tr td:nth-child(4) { 
    text-align:left;
    font-weight:bold;
}


#garis_pembatas{
    border-bottom:solid 3px black; 
    margin:0 1.2em 0em 1.2em;
}
#note{
    margin:0 1.5em 0 1.5em;
}
#note span{
    font-size:0.8em;
}
#table_catatan{
    width:100%;
    font-size:0.8em;
}
#table_catatan tr td:first-child{
    width:2%;
    vertical-align: text-top;
}
#table_catatan tr td:nth-child(2) { 
    text-align:justify;
}

#kop{
    height:2.3em;
    padding:0em 0 1em 0;
    font-family: Sans-serif;
    border-bottom:1px solid black;
    margin-bottom:1em;
}
#logo{
    margin:0 0em 0 0.5em;
    width:70%;
}

#title{
    vertical-align:middle;
    width:30%;
    text-align:right;
}
#title_year{
    vertical-align:middle;
    font-size:1.8em;
    font-weight:bold;
    margin-right:0.3em;
}
.sub_data_harta{
    margin-right:0.5em;
}
#table_list_data{ 
    width:100%;
    font-weight:none;
}
#table_list_data tr td:first-child{
    width:1%;
}
#table_list_data tr td:nth-child(2) { 
    width:1%;
    vertical-align: text-top;
}


#column_number{
    width:2%;
}
#column_body{
    width:98%;text-align:justify;
    float:right;
}

#div_list_data{ 
    padding-left:4em;
    padding-right:13em;
    font-weight:none;
}
#column_number_harta{
    width:7%;
}
#column_body_harta{
    width:93%;text-align:justify;
}

#column_number_bab{
    width:3%;
    font-weight:bold;
    padding-left:3px;
}
#column_body_bab{
    width:70.5%;text-align:justify;font-weight:bold;
}

#column_equals_bab{
    width:4.5%;
    font-weight:bold;
}

#column_total_bab{
    width:22%;
    font-weight:bold;
    text-align:right;
}

/*Bab data pribadi*/
#column_number_bab_pribadi{
    width:4%;
}
#column_title_bab_pribadi{
    width:14%;
    
}
#column_equals_bab_pribadi{
    width:2%;
    
}
#column_body_bab_pribadi{
    width:80%;
    font-weight:bold;
}

#row_menu{
    padding-left:2em;
}
/*Bab data pribadi*/

/*Sub Bab*/
#column_number_sub_bab{
    width:4%;
}
#column_title_sub_bab{
    width:69%;
}
#column_equals_sub_bab{
    width:5%;
}
#column_body_sub_bab{
    width:22%;
    text-align:right;
}

#row_sub_bab{
    padding-left:2em;font-weight:bold;
}

</style>
</head>
<body>

  <header>
    <div id="kop" class="row">
        <div class="column" id="logo">
           <img src="portal-assets/img/kpk.png" width="150px"/>
        </div>
        <div class="column" id="title">
            <span id="title_year">
                <?php 
                    if($JENIS_LAPORAN=="Klarifikasi"){
                        echo "P-";
                    }
                ?>
                <?php echo $TAHUN; ?>
            </span>
            <img src="<?= $qr_code_name ?>" width="65px"/>
              
        </div>
    </div>
  </header>
<main>
<div id="kotak">
     <!--<img class="img-watermark" src="portal-assets/img/watermark2.jpeg">-->
    <div id="header">
        <h2>PENGUMUMAN
            <?php 
                if($JENIS_LAPORAN=="Klarifikasi"){
                    echo "HASIL KLARIFIKASI";
                }
            ?>
        </h2>
        <h2>LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</h2>
        <p>(Tanggal Penyampaian/Jenis Laporan - Tahun: <?php echo $TANGGAL.'/'.$JENIS.' - '.$KHUSUS ?>)</p>
    </div>
    <div id="form_biodata">
        <table id="table_instansi">
            <tr>
                <td style="width:10em">BIDANG</td>
                <td>:</td>
                <td><?php echo $BIDANG; ?></td>
            </tr>
            <tr>
                <td>LEMBAGA</td>
                <td>:</td>
                <td><?php echo $LEMBAGA; ?></td>
            </tr>
            <tr>
                <td>UNIT KERJA</td>
                <td>:</td>
                <td><?php echo $UNIT_KERJA; ?></td>
            </tr>

            <?php if($TAHUN_KIRIM < "2021") : ?>
                <tr>
                    <td>SUB UNIT KERJA</td>
                    <td>:</td>
                    <td><?php echo $SUB_UNIT_KERJA; ?></td>
                </tr>
            <?php endif; ?>
            
        </table>
        <div class="row">
            <div class="column" id="column_number_bab">
                I.
            </div>
            <div class="column" id="column_body_bab">
                DATA PRIBADI
            </div>
        </div>
                <div class="row" id="row_menu">
                    <div class="column" id="column_number_bab_pribadi">
                        1.
                    </div>
                    <div class="column" id="column_title_bab_pribadi">
                        Nama
                    </div>
                    <div class="column" id="column_equals_bab_pribadi">
                        :
                    </div>
                    <div class="column" id="column_body_bab_pribadi">
                        <?= $NAMA_LENGKAP; ?>
                    </div>
                </div>
                <div class="row" id="row_menu">
                    <div class="column" id="column_number_bab_pribadi">
                        2.
                    </div>
                    <div class="column" id="column_title_bab_pribadi">
                        Jabatan
                    </div>
                    <div class="column" id="column_equals_bab_pribadi">
                        :
                    </div>
                    <div class="column" id="column_body_bab_pribadi">
                        <?= $JABATAN; ?>
                    </div>
                </div>
                <div class="row" id="row_menu">
                    <div class="column" id="column_number_bab_pribadi">
                        3.
                    </div>
                    <div class="column" id="column_title_bab_pribadi">
                        NHK
                    </div>
                    <div class="column" id="column_equals_bab_pribadi">
                        :
                    </div>
                    <div class="column" id="column_body_bab_pribadi">
                        <?= $NHK; ?>
                    </div>
                </div>
        <div class="row">
            <div class="column" id="column_number_bab">
                II.
            </div>
            <div class="column" id="column_body_bab">
                DATA HARTA
            </div>
        </div>
                <div class="row" id="row_sub_bab">
                    <div class="column" id="column_number_sub_bab">
                        A.
                    </div>
                    <div class="column" id="column_title_sub_bab">
                        TANAH DAN BANGUNAN
                    </div>
                    <div class="column" id="column_equals_sub_bab">
                        Rp.
                    </div>
                    <div class="column" id="column_body_sub_bab">
                        <?= $HTB; ?>
                    </div>
                </div>

                                    <?php if($DHTB){ ?>
                                        <?php 
                                        $no = 1;
                                        foreach($DHTB as $s){
                                        ?>
                                        <div class="row" id="div_list_data">
                                            
                                            <div class="column" id="column_number_harta">
                                                <?= $no++ ?>.
                                            </div>
                                            <div class="column" id="column_body_harta">
                                                <?= $s ?>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                    <?php } ?>

                <div class="row" id="row_sub_bab">
                    <div class="column" id="column_number_sub_bab">
                        B.
                    </div>
                    <div class="column" id="column_title_sub_bab">
                        ALAT TRANSPORTASI DAN MESIN
                    </div>
                    <div class="column" id="column_equals_sub_bab">
                        Rp.
                    </div>
                    <div class="column" id="column_body_sub_bab">
                        <?= $HB; ?>
                    </div>
                </div>
                                    <?php if($DHB){ ?>
                                        <?php 
                                        $no = 1;
                                        foreach($DHB as $s){
                                        ?>
                                            <div class="row" id="div_list_data">
                                                
                                                <div class="column" id="column_number_harta">
                                                    <?= $no++ ?>.
                                                </div>
                                                <div class="column" id="column_body_harta">
                                                    <?= $s ?>
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            ?>
                                        <?php } ?>

                <div class="row" id="row_sub_bab">
                    <div class="column" id="column_number_sub_bab">
                        C.
                    </div>
                    <div class="column" id="column_title_sub_bab">
                        HARTA BERGERAK LAINNYA 
                    </div>
                    <div class="column" id="column_equals_sub_bab">
                        Rp.
                    </div>
                    <div class="column" id="column_body_sub_bab">
                        <?= $HBL; ?>
                    </div>
                </div>
                <div class="row" id="row_sub_bab">
                    <div class="column" id="column_number_sub_bab">
                        D.
                    </div>
                    <div class="column" id="column_title_sub_bab">
                        SURAT BERHARGA
                    </div>
                    <div class="column" id="column_equals_sub_bab">
                        Rp.
                    </div>
                    <div class="column" id="column_body_sub_bab">
                        <?= $SB; ?>
                    </div>
                </div>
                <div class="row" id="row_sub_bab">
                    <div class="column" id="column_number_sub_bab">
                        E.
                    </div>
                    <div class="column" id="column_title_sub_bab">
                        KAS DAN SETARA KAS
                    </div>
                    <div class="column" id="column_equals_sub_bab">
                        Rp.
                    </div>
                    <div class="column" id="column_body_sub_bab">
                        <?= $KAS; ?>
                    </div>
                </div>
                <div class="row" id="row_sub_bab">
                    <div class="column" id="column_number_sub_bab">
                        F.
                    </div>
                    <div class="column" id="column_title_sub_bab">
                        HARTA LAINNYA 
                    </div>
                    <div class="column" id="column_equals_sub_bab">
                        Rp.
                    </div>
                    <div class="column" id="column_body_sub_bab">
                        <?= $HL; ?>
                    </div>
                </div>
                <div class="row" id="row_sub_bab">
                    <div class="column" id="column_number_sub_bab">
                        
                    </div>
                    <div class="column" id="column_title_sub_bab">
                        Sub Total
                    </div>
                    <div class="column" id="column_equals_sub_bab">
                        Rp.
                    </div>
                    <div class="column" id="column_body_sub_bab">
                        <?= $subtotal; ?>
                    </div>
                </div>
        <div class="row">
            <div class="column" id="column_number_bab">
                III.
            </div>
            <div class="column" id="column_body_bab">
                HUTANG
            </div>
            <div class="column" id="column_equals_bab">
                Rp.
            </div>
            <div class="column" id="column_total_bab">
                <?php echo $HUTANG; ?>
            </div>
        </div>
        <div class="row">
            <div class="column" id="column_number_bab">
                IV.
            </div>
            <div class="column" id="column_body_bab">
                TOTAL HARTA KEKAYAAN (II-III)
            </div>
            <div class="column" id="column_equals_bab">
                Rp.
            </div>
            <div class="column" id="column_total_bab">
                <?php echo $TOTAL; ?>
            </div>
        </div>
        
    </div>
    <!-- <hr width="94%"> -->
    <div id="garis_pembatas"></div>
    <div id="note"><br>
        <?php 
            if($JENIS_LAPORAN=="Klarifikasi"){
                $catatan_1 = "Rincian harta kekayaan dalam lembar ini merupakan dokumen yang dicetak secara otomatis dari <span style='font-style:italic;color:blue;text-decoration:underline;font-size:1em'>elhkpn.kpk.go.id.</span>  Seluruh data dan informasi yang tercantum dalam dokumen ini sesuai dengan LHKPN yang diisi dan dikirimkan sendiri oleh Penyelenggara Negara melalui <span style='font-style:italic;color:blue;text-decoration:underline;font-size:1em'>elhkpn.kpk.go.id</span> dan atas persetujuan Penyelenggara Negara setelah dilakukan klarifikasi. Pengumuman ini tidak dapat dijadikan dasar oleh Penyelenggara Negara yang bersangkutan atau siapapun juga untuk menyatakan bahwa harta kekayaan yang bersangkutan tidak terkait tindak pidana. Apabila dikemudian hari terdapat harta kekayaan milik Penyelenggara Negara dan/atau Keluarganya yang tidak dilaporkan dalam LHKPN, maka Penyelenggara Negara wajib untuk bertanggung jawab sesuai dengan peraturan perundang-undangan yang berlaku.";
                $catatan_3 = "Pengumuman ini diumumkan dengan catatan <b>$STATUS</b> berdasarkan hasil klarifikasi  tanggal <b>$TANGGAL_BAK</b>.";
            }else{
                $catatan_1 = "Rincian harta kekayaan dalam lembar ini merupakan dokumen yang dicetak secara otomatis dari <span style='font-style:italic;color:blue;text-decoration:underline;font-size:1em'>elhkpn.kpk.go.id.</span>  Seluruh data dan informasi yang tercantum dalam dokumen ini sesuai dengan LHKPN yang diisi dan dikirimkan sendiri oleh Penyelenggara Negara melalui <span style='font-style:italic;color:blue;text-decoration:underline;font-size:1em'>elhkpn.kpk.go.id</span>, serta tidak dapat dijadikan dasar oleh Penyelenggara Negara yang bersangkutan atau siapapun juga untuk menyatakan bahwa harta kekayaan yang bersangkutan tidak terkait tindak pidana. Apabila dikemudian hari terdapat harta kekayaan milik Penyelenggara Negara dan/atau Keluarganya yang tidak dilaporkan dalam LHKPN, maka Penyelenggara Negara wajib untuk bertanggung jawab sesuai dengan peraturan perundang-undangan yang berlaku.";
                $catatan_3 = "Pengumuman ini diumumkan dengan catatan <b>$STATUS</b> berdasarkan hasil verifikasi tanggal <b>$PENGESAHAN</b>.";
            }
        ?>
        <span>Catatan:</span>
        <div id="table_catatan">
            <div class="row">
                <div class="column" id="column_number">
                    1.
                </div>
                <div class="column" id="column_body">
                    <?= $catatan_1 ?>
                </div>
            </div>
            <div class="row">
                <div class="column" id="column_number">
                    2.
                </div>
                <div class="column" id="column_body">
                    Pengumuman ini telah ditempatkan dalam media pengumuman resmi KPK dalam rangka memfasilitasi pemenuhan kewajiban Penyelenggara Negara untuk mengumumkan harta kekayaan sesuai dengan Undang-Undang Nomor 28 Tahun 1999 tentang Penyelenggara Negara yang Bersih dan Bebas dari Korupsi, Kolusi dan Nepotisme.
                </div>
            </div>

            <?php if($TAHUN_KIRIM < "2021") : ?>
                <div class="row">
                    <div class="column" id="column_number">
                        3.
                    </div>
                    <div class="column" id="column_body">
                        <?= $catatan_3 ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="column" id="column_number">
                <?php echo ($TAHUN_KIRIM < "2021") ? '4.' : '3.'; ?>
                </div>
                <div class="column" id="column_body">
                    Pengumuman ini tidak memerlukan tanda tangan karena dicetak secara otomatis.
                </div>
            </div>
        </div>

    </div>
</div>

  </main>
</body>
</html>