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
h2{
    font-size:1em;
}
h3{
    font-size:0.9em;
}
p{
    font-size:0.8em;
}
body{
    font-family: Sans-serif;
}
.header-harta {
    display: flex;
    text-align: center;
    height: 45px;
}
.col-05 {
    float: left;
    width: 10px;
}
.col-1 {
    float: left;
    width: 20px;
}
.col-2 {
    float: left;
    width: 40px;
}
.col-3 {
    display: block;
    float: left;
    width: 60px;
}
.col-3-5 {
    display: block;
    float: left;
    width: 70px;
}
.col-4 {
    float: left;
    width: 80px;
}
.col-5 {
    float: left;
    width: 100px;
}
.col-6 {
    float: left;
    width: 120px;
}
.col-7 {
    float: left;
    width: 140px;
}
.col-8 {
    float: left;
    width: 160px;
}
.col-9 {
    float: left;
    width: 180px;
}
.col-10 {
    float: left;
    width: 200px;
}
.col-11 {
    float: left;
    width: 220px;
}
.col-12 {
    float: left;
    width: 240px;
}
.col-13 {
    float: left;
    width: 260px;
}
.col-14 {
    float: left;
    width: 280px;
}
.col-15 {
    float: left;
    width: 300px;
}
.col-container {
    width: 720px;
    /* width: 690px; */
    margin: 3px;
}
.indent-tab-1 {
    text-indent: 15px;
}
.indent-tab-1-5 {
    text-indent: 22px;
}
.indent-tab-2 {
    text-indent: 30px;
}
.indent-tab-2-5 {
    text-indent: 37px;
}
.indent-tab-3 {
    text-indent: 45px;
}
.indent-tab-3-5 {
    text-indent: 52px;
}

.font-em-1 {
    font-size:0.8em;
}
.font-em-1-5 {
    font-size:0.9em;
}
.font-em-2 {
    font-size:1.0em;
}
.font-em-2-5 {
    font-size:1.1em;
}
.font-em-3 {
    font-size:1.2em;
}
.font-em-4 {
    font-size:1.4em;
}
.font-em-5 {
    font-size:1.6em;
}
.font-px-1 {
    font-size: 2px;
}
.text-bold {
    font-weight:bold;
}
/* Clear floats after the columns */
.row:after {
    padding: 2px;
    margin-bottom: 3px;
    content: "";
    display: block;
    clear: both;
}
.text-right {
    text-align: right;
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

#note{
    margin:0 1.5em 0 1.5em;
}
#note span{
    font-size:0.8em;
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
#garis_pembatas{
    border-bottom:solid 3px black; 
    margin:5px 0.1em 0em 0.1em;
}
</style>
</head>
<body>
<?php
    $lhkpn1 = $lhkpn->id_lhkpn_1;
    $lhkpn2 = $lhkpn->id_lhkpn_2 ?: null;
    $aBulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $thn = $pn->tgl_kirim_final;
    $thn_laporan = $thn ? date('d', strtotime($thn)) . ' ' . $aBulan[date('n', strtotime($thn))-1] . ' ' . date('Y', strtotime($thn)): '';
    $thn1 = $data_harta['summary']['lhkpn_perbandingan1'] ?: null;
    $thn_perbandingan1 = $thn1 ? date('d', strtotime($thn1)) . ' ' . $aBulan[date('n', strtotime($thn1))-1] . ' ' . date('Y', strtotime($thn1)): '';
    $thn2 = $data_harta['summary']['lhkpn_perbandingan2'] ?: null;
    $thn_perbandingan2 = $thn2 ? date('d', strtotime($thn2)) . ' ' . $aBulan[date('n', strtotime($thn2))-1] . ' ' . date('Y', strtotime($thn2)): '';
    // print_r($data_harta['dhtb']['summary']['selisih']) && exit;
?>

    <header>
            <div id="kop" class="row">
                <div class="column" id="logo">
                    <img src="portal-assets/img/kpk.png" width="150px"/>
                </div>
            </div>
        </header>
    <main>
        <div id="kotak">
            <div id="header">
                <h2>PERBANDINGAN
                    <?php 
                        if($JENIS_LAPORAN=="Klarifikasi"){
                            echo "HASIL KLARIFIKASI";
                        }
                    ?>
                </h2>
                <h2>LAPORAN HARTA KEKAYAAN PENYELENGGARA NEGARA</h2>
                <p>(Tanggal Penyampaian/Jenis Laporan - Tahun: <?php echo $thn_laporan . '/' . $pn->DESKRIPSI_JENIS_LAPORAN; ?>)</p>
            </div>
            <div id="form_biodata">
                <table id="table_instansi">
                    <tr>
                        <td style="width:10em">NAMA</td>
                        <td>:</td>
                        <td><?php echo strtoupper($pn->NAMA); ?></td>
                    </tr>
                    <tr>
                        <td style="width:10em">LEMBAGA</td>
                        <td>:</td>
                        <td><?php echo $pn->INST_NAMA ? strtoupper($pn->INST_NAMA) : '-'; ?></td>
                    </tr>
                    <tr>
                        <td style="width:10em">UNIT KERJA</td>
                        <td>:</td>
                        <td><?php echo $pn->UK_NAMA ? strtoupper($pn->UK_NAMA) : '-'; ?></td>
                    </tr>
                    <tr>
                        <td style="width:10em">SUB UNIT KERJA</td>
                        <td>:</td>
                        <td><?php echo $pn->SUK_NAMA ? strtoupper($pn->SUK_NAMA) : '-'; ?></td>
                    </tr>
                    <tr>
                        <td>JABATAN</td>
                        <td>:</td>
                        <td><?php echo $pn->NAMA_JABATAN ? strtoupper($pn->NAMA_JABATAN) : '-'; ?></td>
                    </tr>
                    <tr>
                        <td>NHK</td>
                        <td>:</td>
                        <td><?php echo $pn->NHK ? ucwords ($pn->NHK) : '-'; ?></td>
                    </tr>
                    <tr>
                        <td>PERBANDINGAN LHKPN</td>
                        <td>:</td>
                        <td><?php echo $thn_perbandingan2 ? date('Y', strtotime($thn2)) . ' (' . $thn_perbandingan2 . ')' : ''; ?></td>
                    </tr>
                    
                </table>
                <br>
                <div class="table_instansi">
                    <small><i>* Situs ini hanya menampilkan Perbandingan Pengumuman Harta Kekayaan Penyelenggara Negara atas LHKPN yang disampaikan kepada KPK menggunakan Aplikasi e-LHKPN (dimulai dari LHKPN Tahun 2018 dan seterusnya).</i></small>
                </div>
            </div>
            <div class="col-container">
                <div class="row header-harta">
                    <div class="col-1 font-em-1 text-bold">I.</div>
                    <div class="col-13 font-em-1 text-bold" style="text-align: left;">DATA HARTA</div>
                    <div class="col-6 font-em-1 text-bold">
                        Pelaporan LHKPN<br>
                        <div class="lhkpn_1"><?= $thn_perbandingan1 ?></div>
                    </div>
                    <div class="col-6 font-em-1 text-bold" style="vertical-align: middle;">
                        Pelaporan LHKPN<br>
                        <div class="lhkpn_2"><?= $thn_perbandingan2 ?></div>
                    </div>
                    <div class="row" style="width: 180px; float:right;">
                        <div class="col-10 font-em-1 text-bold">Kenaikan / (penurunan)</div>
                        <br>
                        <div class="col-6 font-em-1 text-bold" style="float:left; text-align:center">Jumlah</div>
                        <div class="col-3-5 font-em-1 text-bold" style="float:right; text-align:center">%</div>
                    </div>
                    <!-- <div class="col-9 font-em-1 text-bold">Kenaikan / (penurunan)</div> -->
                </div>

                <div class="row">
                    <div class="col-2 font-em-1 indent-tab-1-5 text-bold">A.</div>
                    <div class="col-11 font-em-1 text-bold">TANAH DAN BANGUNAN</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['dhtb']['summary'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhtb']['summary'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhtb']['summary']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['dhtb']['summary']['persentase'] !== null) ?
                            number_format($data_harta['dhtb']['summary']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>

                <?php
                $no = 1;
                foreach ($data_harta['dhtb'] as $key => $hartas) {
                    foreach ($hartas as $k => $harta) {
                        if ($k != 'summary' && $harta != null && $harta['summary'] != null) {
                ?>
                <div class="row" style="display: block; clear:both; page-break-inside: avoid;">
                    <div class="col-3 font-em-1 indent-tab-2-5"><?= $no; ?>.</div>
                    <div class="col-10 font-em-1"><?= $harta['summary']['DESKRIPSI'] ?></div>
                    <div class="col-1"></div>
                    <div class="col-6 font-em-1 text-right">
                        <?= $harta['summary']['LHKPN_1'] ? number_format($harta['summary']['LHKPN_1'], '0', ',', '.') : '0'; ?>
                    </div>
                    <div class="col-6 font-em-1 text-right">
                        <?= $harta['summary']['LHKPN_2'] ? number_format($harta['summary']['LHKPN_2'], '0', ',', '.') : '0'; ?>
                    </div>
                    <div class="col-6 font-em-1 text-right">
                        <?= $harta['summary']['selisih'] ? number_format($harta['summary']['selisih'], '0', ',', '.') : '0'; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-right">
                        <?= $lhkpn2 && ($harta['summary']['persentase'] !== null) ? number_format($harta['summary']['persentase'], '2', ',', '.') . '%' : ''; 
                        ?>
                    </div>
                </div>
                <?php
                        $no++;
                        }
                    }
                }
                ?>
                
                <div class="row">
                    <div class="col-2 font-em-1 indent-tab-1-5 text-bold">B.</div>
                    <div class="col-11 font-em-1 text-bold">ALAT TRANSPORTASI DAN MESIN</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['dhb']['summary'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhb']['summary'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhb']['summary']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['dhb']['summary']['persentase'] !== null) ?
                            number_format($data_harta['dhb']['summary']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>

                <?php
                $no = 1;
                foreach ($data_harta['dhb'] as $key => $hartas) {
                    foreach ($hartas as $k => $harta) {
                        if ($k != 'summary' && $harta != null && $harta['summary'] != null) {
                ?>
                <div class="row" style="display: block; clear:both; page-break-inside: avoid;">
                    <div class="col-3 font-em-1 indent-tab-2-5"><?= $no; ?>.</div>
                    <div class="col-10 font-em-1"><?= $harta['summary']['DESKRIPSI'] ?></div>
                    <div class="col-1"></div>
                    <div class="col-6 font-em-1 text-right">
                        <?= $harta['summary']['LHKPN_1'] ? number_format($harta['summary']['LHKPN_1'], '0', ',', '.') : '0'; ?>
                    </div>
                    <div class="col-6 font-em-1 text-right">
                        <?= $harta['summary']['LHKPN_2'] ? number_format($harta['summary']['LHKPN_2'], '0', ',', '.') : '0'; ?>
                    </div>
                    <div class="col-6 font-em-1 text-right">
                        <?= $harta['summary']['selisih'] ? number_format($harta['summary']['selisih'], '0', ',', '.') : '0'; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-right">
                        <?= $lhkpn2 && ($harta['summary']['persentase'] !== null) ? number_format($harta['summary']['persentase'], '2', ',', '.') . '%' : ''; 
                        ?>
                    </div>
                </div>
                <?php
                        $no++;
                        }
                    }
                }
                ?>

                <div class="row">
                    <div class="col-2 font-em-1 indent-tab-1-5 text-bold">C.</div>
                    <div class="col-11 font-em-1 text-bold">HARTA BERGERAK LAINNYA</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['dhbl']['summary'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhbl']['summary'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhbl']['summary']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['dhbl']['summary']['persentase'] !== null) ?
                            number_format($data_harta['dhbl']['summary']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2 font-em-1 indent-tab-1-5 text-bold">D.</div>
                    <div class="col-11 font-em-1 text-bold">SURAT BERHARGA</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['dhsb']['summary'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhsb']['summary'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhsb']['summary']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['dhsb']['summary']['persentase'] !== null) ?
                            number_format($data_harta['dhsb']['summary']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2 font-em-1 indent-tab-1-5 text-bold">E.</div>
                    <div class="col-11 font-em-1 text-bold">KAS DAN SETARA KAS</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['dhk']['summary'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhk']['summary'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhk']['summary']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['dhk']['summary']['persentase'] !== null) ?
                            number_format($data_harta['dhk']['summary']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2 font-em-1 indent-tab-1-5 text-bold">F.</div>
                    <div class="col-11 font-em-1 text-bold">HARTA LAINNYA</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['dhl']['summary'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhl']['summary'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dhl']['summary']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['dhl']['summary']['persentase'] !== null) ?
                            number_format($data_harta['dhl']['summary']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2 font-em-1 indent-tab-1-5 text-bold"></div>
                    <div class="col-11 font-em-1 text-bold">Sub Total</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['summary']['subtotal'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['summary']['subtotal'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['summary']['subtotal']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['summary']['subtotal']['persentase'] !== null) ?
                            number_format($data_harta['summary']['subtotal']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-1 font-em-1 text-bold">II.</div>
                    <div class="col-12 font-em-1 text-bold">HUTANG</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['dh']['summary'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dh']['summary'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['dh']['summary']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && ($data_harta['dh']['summary']['persentase'] !== null) ?
                            number_format($data_harta['dh']['summary']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-1 font-em-1 text-bold">III.</div>
                    <div class="col-12 font-em-1 text-bold">TOTAL HARTA KEKAYAAN (I-II)</div>
                    <div class="col-1 font-em-1 text-bold">Rp</div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= number_format($data_harta['summary']['total'][$lhkpn1], '0', ',', '.'); ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['summary']['total'][$lhkpn2], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-6 font-em-1 text-bold text-right">
                        <?= $lhkpn2 ? number_format($data_harta['summary']['total']['selisih'], '0', ',', '.') : ''; ?>
                    </div>
                    <div class="col-3-5 font-em-1 text-bold text-right">
                        <?= $lhkpn2 && $data_harta['summary']['total']['persentase'] !== null ?
                            number_format($data_harta['summary']['total']['persentase'], '2', ',', '.').'%' :
                            '';
                        ?>
                    </div>
                </div>
            </div>
            <br>
            <!-- <hr width="94%"> -->
            <div id="garis_pembatas"></div>
        </div>
    </main>
</body>
</html>