<link class="iniicon" href="<?php echo base_url('img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon">
<!-- Content Header (Page header) -->

<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>Selamat Datang Di Aplikasi e-LHKPN</strong></div>
    </div>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
    </ol>
</section>

<?php
$btn = ' btn-default ';
if (@$this->session->userdata('IS_KPK') == '1' || @$this->session->userdata('IS_INSTANSI') == '1') {
    $btn = ' btn-primary ';
}
?>
<!-- Main content -->
<section class="content">
    <h1></h1>
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-body" >
            <div class="box box-primary ">
                <div class="box-body">
                    <div class="col-md-6">
                    <!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                        <thead>
                            <tr>
                                <th colspan="3"><center>Informasi</center></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="200px">Nama</th>
                                <td>
                                    <span style="margin-right: 10px;">:</span> <?= $item[0]->NAMA ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <span style="margin-right: 10px;">:</span> <?= (!empty($item[0]->EMAIL)) ? $item[0]->EMAIL : ' -'; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Last Login</th>
                                <td>
                                    <span style="margin-right: 10px;">:</span> <?= indonesian_date(strtotime($item[0]->LAST_LOGIN)) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                    <!-- <div class="box-body"> -->
                        <div class="col-md-6">
                            <!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                <thead>
                                    <tr>
                                       <th colspan="2"><center>MAILBOX</center></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php if ($mailbox > 0) : ?>
                                            <td>Anda memiliki <strong><?= $mailbox ?> pesan masuk</strong>.</td>
                                            <td width="50px"><a href="<?= base_url('#index.php/mailbox/inbox/') ?>"><button class="btn <?= $btn ?>"><i class="fa fa-envelope"></i> Lihat</button></a></td>
                                        <?php else : ?>
                                            <td colspan="2"><center>Anda tidak memiliki pesan masuk.</center></td>
                                <?php endif; ?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="col-md-6">
                            <?php if ($this->session->userdata('ID_ROLE') == 5) { ?>
                                <button class="btn btn-lg btn-success" id="btn-add" href="index.php/efill/lhkpn/addlhkpn">Isi LHKPN Baru</button>
                            <?php } ?>
                            <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3 || $this->session->userdata('ID_ROLE') == 4) { ?>
                                <!-- <table class="table table-striped table-bordered table-hover table-heading no-border-bottom"> -->
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                           <!-- <th colspan="2"><center>KELOLA PN / WL</center></th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                         <!--<?php if ($pn_wl_masuk > 0) : ?>
                                                <td>Anda memiliki <strong><?= $pn_wl_masuk ?> PN / WL masuk</strong>.</td>
                                                <td width="50px"><a href="<?= base_url('#index.php/ereg/all_pn/mutasimasuk/') ?>"><button class="btn <?= $btn ?>"><i class="fa fa-envelope"></i> Lihat</button></a></td>
                                            <?php else : ?>
                                                <td colspan="2"><center>Anda tidak memiliki PN / WL masuk.</center></td>
                                    <?php endif; ?>-->
                                    </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                    </div>
                    </div>
                    <?php
                    $items = '';
                    if ($items) { ?>
                        <?php if ($this->session->userdata('ID_ROLE') == 5) { ?>
                            <p><i>"LHKPN ini perlu di perbaiki !"</i></p>
                            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                <thead>
                                    <tr>
                                        <th width="30">No.</th>
                                        <th>No. Agenda</th>
                                        <th>PN</th>
                                        <th>Tgl Lapor</th>
                                        <th>Jabatan</th>
                                        <th>Status Laporan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0 + $offset;
                                    $start = $i + 1;
                                    $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
                                    $aStatus = ['0' => 'Draft', '1' => 'Masuk', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi', '4' => 'Diumumkan'];
                                    $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
                                    foreach ($items as $item) {
                                        $agenda = date('Y', strtotime($item->TGL_LAPOR)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                                        ?>
                                        <tr>
                                            <td><?php echo ++$i; ?>.</td>
                                            <td>
                                                <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($item->ID_LHKPN), 5, 8) ?>" class="btn-tracking"><?php echo $agenda; ?></a>
                                            </td>
                                            <td><a href="index.php/efill/lhkpn/getInfoPn/<?php echo $item->ID_PN; ?>" onClick="return getPn(this);"><?php echo $item->NAMA; ?></a></td>
                                            <td><?php echo date('d/m/Y', strtotime($item->TGL_LAPOR)); ?>
                                                <?php
                                                if ($item->ENTRY_VIA == '0') {
                                                    echo "&nbsp; <img src='" . base_url() . "img/online.png' title='Via Online'/>";
                                                } else if ($item->ENTRY_VIA == '1') {
                                                    echo "&nbsp; <img src='" . base_url() . "img/hard-copy.png' title='Via Hardcopy'/>";
                                                    if ($this->session->userdata('IS_PN') != '1') {
                                                        echo "<p>Ditugaskan oleh <b>" . $item->USERNAME_KOORD_ENTRY . "</b> ke <b>" . $item->USERNAME_ENTRI . "</b></p>";
                                                    }
                                                } else if ($item->ENTRY_VIA == '2') {
                                                    echo "&nbsp; <img src='" . base_url() . "img/excel-icon.png' title='Via Excel'/>";
                                                    if ($this->session->userdata('IS_PN') != '1') {
                                                        echo "<p>Ditugaskan oleh <b>" . $item->USERNAME_KOORD_ENTRY . "</b> ke <b>" . $item->USERNAME_ENTRI . "</b></p>";
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($item->NAMA_JABATAN) {
                                                    $j = explode(',', $item->NAMA_JABATAN);
                                                    echo '<ul>';
                                                    foreach ($j as $ja) {
                                                        $jb = explode(':58:', $ja);
                                                        $idjb = $jb[0];
                                                        $statakhirjb = @$jb[1];
                                                        $statakhirjbtext = @$jb[2];
                                                        $statmutasijb = @$jb[3];
                                                        if (@$jb[4] != '') {
                                                            echo '<li>' . @$jb[4] . '</li>';
                                                        }
                                                    }
                                                    echo '</ul>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                echo $aStatus[$item->STATUS];
                                                if ($item->STATUS == '2') {
                                                    echo '(' . @$abStatus[@$item->ALASAN] . ')';
                                                }
                                                ?>
                                            </td>
                                            <td width="120" align="center">
                                                <input type="hidden" class="key" value="<?php echo substr(md5($item->ID_LHKPN), 5, 8); ?>">
                                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/efill/lhkpn/entry/<?php echo substr(md5($item->ID_LHKPN), 5, 8); ?>/edit" title="Proses"><i class="fa fa-pencil"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        $end = $i;
                                    }
                                    ?>
                                    <?php
                                    echo (count($items) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($this->session->userdata('ID_ROLE') == 5) { ?>
        <?php
        //penerimaan kas

        $PnN = @json_decode($getPenka[0]->NILAI_PENERIMAAN_KAS_PN);
        $PnA = @json_decode($getPenka[0]->NILAI_PENERIMAAN_KAS_PASANGAN);

        $totaln = [];
        $totalnALL = 0;
        if (!empty($PnN)) {
            foreach ($PnN as $key => $value) {
                $totaln[$key] = 0;
                foreach ($value as $hasil => $nilai) {
                    $totaln[$key] = $totaln[$key] + str_replace(".", "", $nilai);
                }
                if ($key == 'A') {
                    foreach ($PnA as $hasilPA => $nilaiPA) {
                        $totaln[$key] = $totaln[$key] + str_replace(".", "", $nilaiPA);
                    }
                }
                $totalnALL = $totalnALL + $totaln[$key];
            }
        }


        //pengeluaran kass

        $PmN = @json_decode($getPemka[0]->NILAI_PENGELUARAN_KAS);
        // $PmA        = @json_decode($getPemka[0]->NILAI_PENERIMAAN_KAS_PASANGAN);

        $totalm = [];
        $totalmALL = 0;
        if (!empty($PmN)) {
            foreach ($PmN as $key => $value) {
                $totalm[$key] = 0;
                foreach ($value as $hasil => $nilai) {
                    $totalm[$key] = $totalm[$key] + str_replace(".", "", $nilai);
                }
                // if ($key == 'A') {
                //     foreach ($PmA as $hasilPA => $nilaiPA) {
                //         $totalm[$key] = $totalm[$key] + str_replace(".","",$nilaiPA);
                //     }
                // }
                $totalmALL = $totalmALL + $totalm[$key];
            }
        }
        ?>
        <script>
            $(document).ready(function() {
                $("#btn-add").click(function() {
                    url = $(this).attr('href');
                    $.post(url, function(html) {
                        OpenModalBox('Tambah Lhkpn', html, '', 'large');
                    });
                    return false;
                });

                $('.btn-tracking').click(function(e) {
                    url = $(this).attr('href');
                    $.post(url, function(html) {
                        OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
                    });
                    return false;
                });

                $('.btn-edit').click(function(e) {
                    var url = $(this).attr('href');
                    ng.LoadAjaxContent(url, '');
                    return false;
                });

                $('.btn-delete').click(function(e) {
                    url = $(this).attr('href');
                    $.post(url, function(html) {
                        OpenModalBox('Delete Lhkpn', html, '', 'standart');
                        // ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/efill/lhkpn');
                    });
                    return false;
                });
            });
            function getPn(ele) {
                var url = $(ele).attr('href');
                $.get(url, function(html) {
                    OpenModalBox('Detail PN', html, '', 'standart');
                });

                return false;
            }
            $(function() {
                $("#tahun").change(function() {
                    var url = 'index.php/welcome/dashboard/' + $(this).val();
                    window.location.hash = url;
                    ng.LoadAjaxContentPost(url, $(this));
                    return false;
                });
            });

            $(document).ready(function() {
                $('#dt_xy').DataTable({

                    "scrollX": true
                });
            });

        </script>
        <?php if (!empty($tahuns)) { ?>
            <!-- <h1>Rekapitulasi Laporan Harta Kekayaan</h1> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border portlet-header">
                            <h3 class="box-title">I. Ringkasan Laporan Harta Kekayaan Penyelenggara Negara Tahun :
                                <select name="tahun" id="tahun" style="color:#000000;">
                                    <?php foreach ($tahuns as $thn_lapor) { ?>
                                        <option value="<?php echo $thn_lapor->tahun; ?>" <?php echo $thn_lapor->tahun == $tahun ? 'selected' : ''; ?>><?php echo $thn_lapor->tahun; ?></option>
                                    <?php } ?>
                                </select>
                            </h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="box box-danger">
                                        <div class="box-header">
                                            <h3 class="box-title"><b>I.1 REKAPITULASI HARTA KEKAYAAN</b></h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body no-padding">
                                            <table class="table table-hover">
                                                <tr>
                                                    <td>1</td>
                                                    <td>HARTA TIDAK BERGERAK (Tanah/Bangunan)</td>
                                                    <td nowrap="" align="right">
                                                        Rp. <?php
                                                        $h1 = $hartirak[0]->sum_hartirak;
                                                        echo number_format($h1, 0, ",", ".");
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>HARTA BERGERAK</td>
                                                    <td nowrap="" align="right">
                                                        Rp. <?php
                                                        @$h2 = @$harger[0]->sum_harger;
                                                        @$h2b = @$harger2[0]->sum_harger2;
                                                        echo number_format($h2 + $h2b, 0, ",", ".");
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SURAT BERHARGA</td>
                                                    <td nowrap="" align="right">
                                                        Rp. <?php
                                                        @$h3 = @$suberga[0]->sum_suberga;
                                                        echo number_format($h3, 0, ",", ".");
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>KAS DAN SETARA KAS</td>
                                                    <td nowrap="" align="right">
                                                        Rp. <?php
                                                        @$h4 = @$kaseka[0]->sum_kaseka;
                                                        echo number_format($h4, 0, ",", ".");
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>HARTA LAINNYA</td>
                                                    <td nowrap="" align="right">
                                                        Rp. <?php
                                                        @$h5 = @$harlin[0]->sum_harlin;
                                                        echo number_format($h5, 0, ",", ".");
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><b>SUB-TOTAL HARTA</b></td>
                                                    <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php
                                                            $subTtlHarta = $h1 + $h2 + $h2b + $h3 + $h4 + $h5;
                                                            echo number_format($subTtlHarta, 0, ",", ".");
                                                            ?></b></td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>HUTANG</td>
                                                    <td nowrap="" align="right">Rp. <?php
                                                        @$h6 = @$_hutang[0]->sum_hutang;
                                                        echo number_format($h6, 0, ",", ".");
                                                        ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><b>TOTAL HARTA KEKAYAAN</b></td>
                                                    <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($subTtlHarta - $h6, 0, ",", "."); ?></b></td>
                                                </tr>
                                            </table>
                                        </div><!-- /.box-body -->
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="box box-warning">
                                        <div class="box-header">
                                            <h3 class="box-title"><b>I.2 REKAPITULASI PENERIMAAN KAS</b></h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body no-padding">
                                            <table class="table table-hover">
                                                <?php
                                                $tot1 = '0';
                                                $namecoden = 'A';
                                                foreach ($getGolongan1 as $key) :
                                                    ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><?php echo @$key->NAMA_GOLONGAN; ?></td>
                                                        <td nowrap="" align="right">Rp. <?php echo @number_format($totaln[$namecoden++], 0, ",", "."); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr>
                                                    <td></td>
                                                    <td><b>TOTAL PENERIMAAN</b></td>
                                                    <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo number_format($totalnALL, 0, ",", "."); ?></b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title"><b>I.3 REKAPITULASI PENGELUARAN KAS</b></h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body no-padding">
                                            <table class="table table-hover">
                                                <?php
                                                $tot2 = '0';
                                                $namecodem = 'A';
                                                foreach ($getGolongan2 as $yek) :
                                                    $nilai = 0;
                                                    // if (!empty($totalm[$namecodem++])) {
                                                    //     $nilai = $totalm[$namecodem++];
                                                    // }
                                                    ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><?php echo @$yek->NAMA_GOLONGAN; ?></td>
                                                        <td nowrap="" align="right">Rp. <?php echo @number_format($totalm[$namecodem++], 0, ",", "."); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr>
                                                    <td></td>
                                                    <td><b>TOTAL PENGELUARAN</b></td>
                                                    <td nowrap="" style="border-top: 1px solid #000;" align="right"><b>Rp. <?php echo @number_format($totalmALL, 0, ",", "."); ?></b></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><b>PENERIMAAN BERSIH</b></td>
                                                    <td  align="right" nowrap="" style="border-top: 1px solid #000;"><b>Rp. <?php echo @number_format($totalnALL - $totalmALL, 0, ",", "."); ?></b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                        </div><!-- /.box-footer -->
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border portlet-header">
                        <h3 class="box-title">Laporan Yang Belum Terkirim
                        </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="dt_xy" class="table table-bordered table-hover dataTable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="10%">No. Agenda</th>
                                    <th width="10%">PN</th>
                                    <th width="10%">Tgl Lapor</th>
                                    <th width="50%">Jabatan</th>
                                    <th width="10%">Status Laporan</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0 + $offset;
                                $start = $i + 1;

                                $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
                                $aStatus = ['0' => 'Draft', '1' => 'Masuk', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi', '4' => 'Diumumkan'];
                                $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
                                foreach ($itemsdraft as $item) {
                                    $agenda = date('Y', strtotime($item->TGL_LAPOR)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                                    ?>
                                    <tr>
                                        <td><?php echo ++$i; ?>.</td>
                                        <td>
                                            <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($item->ID_LHKPN), 5, 8) ?>"
                                               class="btn-tracking"><?php echo $agenda; ?></a>
                                        </td>
                                        <td><a href="index.php/efill/lhkpn/getInfoPn/<?php echo $item->ID_PN; ?>"
                                               onClick="return getPn(this);"><?php echo $item->NAMA; ?></a></td>
                                        <td><?php echo date('d/m/Y', strtotime($item->TGL_LAPOR)); ?>
                                            <?php
                                            if ($item->ENTRY_VIA == '0') {
                                                echo "&nbsp; <img src='" . base_url() . "img/online.png' title='Via Online'/>";
                                            } else if ($item->ENTRY_VIA == '1') {
                                                echo "&nbsp; <img src='" . base_url() . "img/hard-copy.png' title='Via Hardcopy'/>";
                                                if ($this->session->userdata('IS_PN') != '1') {
                                                    echo "<p>Ditugaskan oleh <b>" . $item->USERNAME_KOORD_ENTRY . "</b> ke <b>" . $item->USERNAME_ENTRI . "</b></p>";
                                                }
                                            } else if ($item->ENTRY_VIA == '2') {
                                                echo "&nbsp; <img src='" . base_url() . "img/excel-icon.png' title='Via Excel'/>";
                                                if ($this->session->userdata('IS_PN') != '1') {
                                                    echo "<p>Ditugaskan oleh <b>" . $item->USERNAME_KOORD_ENTRY . "</b> ke <b>" . $item->USERNAME_ENTRI . "</b></p>";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($item->NAMA_JABATAN) {
                                                $j = explode(',', $item->NAMA_JABATAN);
                                                echo '<ul>';
                                                foreach ($j as $ja) {
                                                    $jb = explode(':58:', $ja);
                                                    $idjb = $jb[0];
                                                    $statakhirjb = @$jb[1];
                                                    $statakhirjbtext = @$jb[2];
                                                    $statmutasijb = @$jb[3];
                                                    if (@$jb[4] != '') {
                                                        echo '<li>' . @$jb[4] . '</li>';
                                                    }
                                                }
                                                echo '</ul>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($item->STATUS == '2' && $item->ALASAN == '1' && $item->ENTRY_VIA != '0' && $isPN) {
                                                echo 'Sedang Diverifikasi';
                                            } else {
                                                echo $aStatus[$item->STATUS];
                                                if ($item->STATUS == '2') {
                                                    echo '(' . @$abStatus[@$item->ALASAN] . ')';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td width="120" nowrap="">
                                            <input type="hidden" class="key" value="<?php echo substr(md5($item->ID_LHKPN), 5, 8); ?>">
                                            <?php if ($item->STATUS == '0' || $item->STATUS == '2') { ?>
                                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/efill/lhkpn/entry/<?php echo substr(md5($item->ID_LHKPN), 5, 8); ?>/edit" title="Proses"><i class="fa fa-pencil"></i></button>
                                                <?php if ($item->ENTRY_VIA != '1') { ?>
                                                    <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/efill/lhkpn/deletelhkpn/<?php echo substr(md5($item->ID_LHKPN), 5, 8); ?>" title="Delete"><i class="fa fa-trash" style="color:red;"></i></button>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/efill/lhkpn/entry/<?php echo substr(md5($item->ID_LHKPN), 5, 8); ?>/view" title="Preview"><i class="fa fa-search-plus"></i></button>
                                                <?php
                                            }

                                            if ($this->session->userdata('IS_PN') !== '1') {
                                                ?>
                                                <button type="button" class="btn btn-sm btn-default btnGenPDF" href="index.php/efill/lhkpn_view/genpdf">Cetak Draft TBN</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $end = $i;
                                }
                                ?>
                                <?php
                                echo (count($itemsdraft) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
                                ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                    </div><!-- /.box-footer -->
                </div>
            </div>
        </div>
    <?php } ?>
</section><!-- /.content -->
<?php exit; ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <?php
    if ($this->session->userdata('ID_ROLE') == ID_ROLE_ADMAPP) {
        ?>
        Admin app
        <?php
    }
    if ($this->session->userdata('ID_ROLE') == ID_ROLE_AK) {
        ?>
        Admin KPK
        <?php
    }
    if ($this->session->userdata('ID_ROLE') == ID_ROLE_AI) {
        ?>
        admin instansi
        <?php
    }
    if ($this->session->userdata('ID_ROLE') == ID_ROLE_UI) {
        ?>
        user instansi
        <?php
    }
    if ($this->session->userdata('ID_ROLE') == ID_ROLE_PN) {
        ?>
        <div class="callout callout-danger">
            <h4>Anda Belum Mengirimkan Laporan LHKPN tahun 2014!</h4>
            <p>untuk mengisi lhkpn silahkan klik <a href="#index.php/efill/lhkpn/entry/">disini</a></p>
        </div>
        <?php
    }
    if ($this->session->userdata('ID_ROLE') == ID_ROLE_VER) {
        ?>
        user verifikator
        <?php
    }
    ?>


    <?php if ($this->session->userdata('ID_ROLE') == '2') { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Admin KPK</h3>
                        <div class="box-tools"></div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <?php
                            $total_admin_instansi = 0;
                            foreach ($admin_instansi as $item) {
                                $total_admin_instansi += $item->JML;
                            }
                            ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Admin Instansi Seluruh Indonesia</span>
                                        <span class="info-box-number">Total <?php echo $total_admin_instansi ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">User Instansi di KPK</span>
                                        <span class="info-box-number">Total <?php echo $user_instansi->JML ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $total_user_pn = 0;
                            foreach ($user_pn as $item) {
                                $total_user_pn += $item->JML;
                            }
                            ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">PN/WL KPK</span>
                                        <span class="info-box-number">Total <?php echo $total_user_pn ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>Admin Instansi</b>
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <thead>
                                        <tr>
                                            <th>Instansi</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $total_admin_instansi = 0;
                                    foreach ($admin_instansi as $item) {
                                        $total_admin_instansi += $item->JML;
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $item->INST_NAMA ?></td>
                                                <td><?php echo $item->JML ?></td>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <b>User Instansi</b>
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <thead>
                                        <tr>
                                            <th>Instansi</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $total_user_pn = 0;
                                    foreach ($user_pn as $item) {
                                        $total_user_pn += $item->JML;
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $item->INST_NAMA ?></td>
                                                <td><?php echo $item->JML ?></td>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix"></div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    <?php } elseif ($this->session->userdata('ID_ROLE') == '3') { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Admin Instansi</h3>
                        <div class="box-tools"></div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php
                        $total_user_instansi = 0;
                        foreach ($user_instansi as $item) {
                            $total_user_instansi += $item->JML;
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">User Instansi</span>
                                        <span class="info-box-number">Total <?php echo $total_user_instansi ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <tr>
                                        <th>Instansi</th>
                                        <th>Jumlah</th>
                                    </tr>
                                    <?php
                                    $total_user_instansi = 0;
                                    foreach ($user_instansi as $item) {
                                        $total_user_instansi += $item->JML;
                                        ?>
                                        <tr>
                                            <td><?php echo $item->INST_NAMA ?></td>
                                            <td><?php echo $item->JML ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix"></div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    <?php } elseif ($this->session->userdata('ID_ROLE') == '4') { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">User Instansi</h3>
                        <div class="box-tools"></div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        PN/WL di Instansinya dia<br>
                        Mutasi Dalam Proses <br>
                        Mutasi Masuk <br>
                        Mutasi Keluar <br>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix"></div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    <?php } elseif ($this->session->userdata('ID_ROLE') == '5') { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">User PN/WL</h3>
                        <div class="box-tools"></div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        lhkpnnya si PN <br>
                        Status lhkpnnya si PN <br>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix"></div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    <?php } else { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Administrator</h3>
                        <div class="box-tools"></div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Admin KPK</span>
                                        <span class="info-box-number">Total <?php echo $admin_kpk->JML ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $total_admin_instansi = 0;
                            foreach ($admin_instansi as $item) {
                                $total_admin_instansi += $item->JML;
                            }
                            ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Admin Instansi</span>
                                        <span class="info-box-number">Total <?php echo $total_admin_instansi ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">User Instansi</span>
                                        <span class="info-box-number">Total <?php echo $user_instansi->JML ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $total_user_pn = 0;
                            foreach ($user_pn as $item) {
                                $total_user_pn += $item->JML;
                            }
                            ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">PN/WL</span>
                                        <span class="info-box-number">Total <?php echo $total_user_pn ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <tr>
                                        <th>Instansi</th>
                                        <th>Jumlah</th>
                                    </tr>
                                    <?php
                                    $total_admin_instansi = 0;
                                    foreach ($admin_instansi as $item) {
                                        $total_admin_instansi += $item->JML;
                                        ?>
                                        <tr>
                                            <td><?php echo $item->INST_NAMA ?></td>
                                            <td><?php echo $item->JML ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <tr>
                                        <th>Instansi</th>
                                        <th>Jumlah</th>
                                    </tr>
                                    <?php
                                    $total_user_pn = 0;
                                    foreach ($user_pn as $item) {
                                        $total_user_pn += $item->JML;
                                        ?>
                                        <tr>
                                            <td><?php echo $item->INST_NAMA ?></td>
                                            <td><?php echo $item->JML ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix"></div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    <?php } ?>

</section><!-- /.content -->
