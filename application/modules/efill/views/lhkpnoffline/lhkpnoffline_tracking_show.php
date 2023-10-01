<link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>plugins/barcode/jquery-barcode.js"></script>
<div class="">
    <div class="bkd-cntn">
        <div id="barcodeTarget" class="barcodeTarget"></div>
        <canvas id="canvasTarget" width="150" height="150"></canvas>
    </div>
    <table width="85%" class="tbl-bd" align="center" style="margin-top: 0; margin-left: 0px;">
        <tr>
            <td width="5%"><strong>Bidang</strong></td>
            <td width="1%" align="center">:</td>
            <td width="30%"><?= (!empty($items[0]->BDG_NAMA)) ? @$items[0]->BDG_NAMA : '-'; ?></td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td>
            <td align="center">:</td>
            <td><?= strtoupper(@$items[0]->NAMA); ?></td>
        </tr><tr>
            <td><strong>Tempat, Tanggal Lahir</strong></td>
            <td align="center">:</td>
            <td><?= strtoupper(@$items[0]->TEMPAT_LAHIR) . ', ' . strtoupper(tgl_format(@$items[0]->TGL_LAHIR)); ?></td>
        </tr><tr>
            <td><strong>Jabatan</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->NAMA_JABATAN)) ? @$items[0]->NAMA_JABATAN : '-'; ?></td>
        </tr><tr>
            <td><strong>Lembaga</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->INST_NAMA)) ? @$items[0]->INST_NAMA : '-'; ?></td>
        </tr><tr>
            <td><strong>Unit Kerja</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->UK_NAMA)) ? @$items[0]->UK_NAMA : @$items[0]->UNIT_KERJA; ?></td>
        </tr><tr>
            <td><strong>Sub Unit Kerja</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->SUK_NAMA)) ? @$items[0]->SUK_NAMA : @$items[0]->SUB_UNIT_KERJA; ?></td>
        </tr><tr>
            <td><strong>Status Lapor</strong></td>
            <td align="center">:</td>
            <?php $arr_stat = ['0' => 'DRAFT', '1' => 'PROSES VERIFIKASI', '2' => 'PERLU PERBAIKAN', '3' => 'TERVERFIKASI LENGKAP', '4' => 'DIUMUMKAN LENGKAP', '5' => 'TERVERIFIKASI TIDAK LENGKAP', '6' => 'DIUMUMKAN TIDAK LENGKAP', '7' => 'DIKEMBALIKAN']; ?>
            <td><?= @$items[0]->STATUS == '1' && @$items[0]->ALASAN != NULL && @$items[0]->DIKEMBALIKAN == '0' ? 'SUDAH DIPERBAIKI' : (@$items[0]->STATUS == '1' && @$items[0]->DIKEMBALIKAN > '0' ? 'PROSES VERIFIKASI (DIKEMBALIKAN)' : $arr_stat[@$items[0]->STATUS]); ?></td>
        </tr><tr>
            <td><strong>Tanggal Lapor</strong></td>
            <td align="center">:</td>
            <td><?= (!empty($items[0]->tgl_lapor)) ? strtoupper(tgl_format(@$items[0]->tgl_lapor)) : strtoupper(tgl_format($items[0]->TANGGAL_PELAPORAN)); ?></td>
        </tr><tr>
            <td><strong>Tanggal Penyampaian</strong></td>
            <td align="center">:</td>
            <!--<td><?= (!empty($items[0]->TANGGAL_PENERIMAAN)) ? strtoupper(tgl_format(@$items[0]->TANGGAL_PENERIMAAN)) : '-'; ?></td>-->
            <td><?= (!empty($items[0]->tgl_kirim_final)) ? strtoupper(tgl_format(@$items[0]->tgl_kirim_final)) : '-'; ?></td>
        </tr><tr>
            <td><strong>NHK</strong></td>
            <td align="center">:</td>
            <td><?php echo (!empty(@$items[0]->NHK)) ? @$items[0]->NHK : '-'; ?></td>
        </tr><tr>
            <td><strong>No. Agenda</strong></td>
            <td align="center">:</td>
            <td><?php echo @$id; ?></td>
        </tr><tr>
            <td><strong>Jumlah Total Lapor</strong></td>
            <td align="center">:</td>
            <td><?php echo @$getTotalLapor; ?></td>
        </tr>
        </tr><tr>
            <td><strong>Konfirmasi Penerimaan SK PN</strong></td>
            <td align="center">:</td>
            <td><?= @$items[0]->FLAG_SK == 0 ? 'Belum' : 'Sudah'; ?></td>
        </tr>
        <?php
        if(@$items[0]->STATUS != '0' && (@$items[0]->entry_via == '0' || @$items[0]->entry_via == '1')) {
        ?>
        <tr>
            <td><strong>Cetak Surat Kuasa</strong></td>
            <td align="center">:</td>
            <td>
                <?php 
                $exp = explode('/', @$id);
                ?>
                <a id="<?php echo $exp[3]; ?>" href='javascript:void(0)' class='btn btn-success btn-sm cetakSKPN-action' title='Cetak SK PN'><i class='fa fa-file'></i></a>
            </td>
        </tr>
        <tr>
            <td><strong>Cetak SKM</strong></td>
            <td align="center">:</td>
            <td style="padding-top: 5px;">
                <?php 
                $exp = explode('/', @$id);
                ?>
                <a id="<?php echo $exp[3]; ?>" href='javascript:void(0)' class='btn btn-warning btn-sm cetakSKM-action' title='Cetak SK Mengumumkan'><i class='fa fa-file'></i></a>
            </td>
        <?php
        }
        ?>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="18%">Pengirim</th>
                <th width="18%">Penerima</th>
                <th width="18%">Date Insert</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arr = array();
            if ($getHistory == $arr) {
                ?>
                <tr>
                    <td colspan="5"><center>Data Not Found...</center></td>
            </tr>
            <?php
        } else {
            $i = 1;
            foreach ($getHistory as $hist) {
                ?>
                <tr>
                    <td><?= @$i++; ?></td>
                    <td><?= !is_null($hist->PENGIRIM)?@$hist->PENGIRIM:@$hist->USERNAME_PENGIRIM; ?></td>
                    <td><?= !is_null($hist->PENERIMA)?@$hist->PENERIMA:@$hist->USERNAME_PENERIMA; ?></td>
                    <td><?= @date('d/m/Y H:i:s', strtotime(@$hist->DATE_INSERT)); ?></td>
                    <td><?= @$hist->STATUS; ?> <?= (!empty(@$hist->ALASAN_BTD)) ? ' ('.@$hist->ALASAN_BTD.')' : ''; ?></td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th width="18%">Jabatan</th>
                <th width="18%">Lembaga</th>
                <th width="18%">Unit Kerja</th>
                <th width="18%">Sub Unit Kerja</th>
                <th width="18%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arr2 = array();
            if ($getJabatan == $arr2) {
            ?>
                <tr>
                    <td colspan="6">Data Not Found...</td>
                </tr>
            <?php
            } else {
                $i = 1;
                foreach($getJabatan as $jab) {
            ?>
                <tr>
                    <td class="text-center"><?= @$i++; ?></td>
                    <td class="text-left"><?= @$jab->JABATAN; ?></td>
                    <td class="text-left"><?= @$jab->INSTANSI; ?></td>
                    <td class="text-center"><?= @$jab->UK; ?></td>
                    <td class="text-center"><?= @$jab->SUK; ?></td>
                    <td class="text-center">RANGKAP</td>
                    
                </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th width="18%">Item Kekurangan</th>
                <th width="18%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arr = array();
            if ($getVerification == $arr) {
                ?>
                <tr>
                    <td colspan="5">Data Not Found...</td>
                </tr>
                <?php
            } else {
                $i = 1;
                //											foreach ($getVerification as $ver) { var_dump($ver);exit;
                $Jenis = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];
                foreach ($getVerification->VAL as $key => $val) {
                    if ($getVerification->MSG->$key != '') {
                        ?>
                        <tr>
                            <td><?= @$i++; ?></td>
                            <td><?= $Jenis[$key]; ?></td>
                            <td><?= $getVerification->MSG->$key; ?></td>
                        </tr>
                    <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th width="20%">Nama</th>
                <th width="18%">Hubungan</th>
                <th width="18%">NIK</th>
                <th width="12%">Status PN</th>
                <th width="12%">Status WL / NON WL</th>
                <th width="14%">Konfirmasi Penerimaan SK</th>
                <th width="5%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arr2 = array();
            if ($getKeluarga == $arr2) {
            ?>
                <tr>
                    <td colspan="8">Data Not Found...</td>
                </tr>
            <?php
            } else {
                $i = 1;
                foreach($getKeluarga as $kel) {
                    $cetakSK = "<a id='" . $kel->ID_KELUARGA  . "' data-id='".$kel->ID_LHKPN."'  href='javascript:void(0)' class='btn btn-success btn-sm cetakSK-action' title='Cetak SK'><i class='fa fa-file'></i></a>";
            ?>
                <tr>
                    <td class="text-center"><?= @$i++; ?></td>
                    <td class="text-left"><?= @$kel->NAMA; ?></td>
                    <?php                         
                    if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11'|| $lhkpn_ver == '2.1') {
                        $arr_hubungan = ['2' => 'SUAMI', '3' => 'ISTRI', '4' => 'ANAK TANGGUNGAN', '5' => 'ANAK BUKAN TANGGUNGAN'];
                    }
                    else{
                        $arr_hubungan = ['1' => 'ISTRI', '2' => 'SUAMI', '3' => 'ANAK TANGGUNGAN', '4' => 'ANAK BUKAN TANGGUNGAN', '5' => 'LAINNYA']; 
                    }
                    ?>
                    <td class="text-left"><?= $arr_hubungan[@$kel->HUBUNGAN];?></td>
                    <td class="text-center"><?= (!empty(@$kel->NIK)) ? @$kel->NIK : '-'; ?></td>
                    <td class="text-center"><?= (!empty(@$kel->ID_PN)) ? 'PN' : '-'; ?></td>
                    <?php $arr_wl = ['0' => 'NON WL', '1' => 'WL', '2' => 'NON WL ( DELETED )', '90' => 'VERIF WL', '99' => 'VARIF NON WL']; ?>
                    <td class="text-center"><?= (!empty(@$kel->IS_WL)) ? $arr_wl[@$kel->IS_WL]."<br>(".$kel->DESKRIPSI_JABATAN." - ".$kel->NAMA_LEMBAGA.")" : '-'; ?></td>
                    <td class="text-center">
                    <?= (@$kel->HUBUNGAN < 4) ? ((@$kel->UMUR2 >= 17) ? ((@$kel->FLAG_SK == 0) ? 'Belum' : 'Sudah') : 'Belum Wajib SK') : 'Tidak Wajib SK'; ?>
                    </td>
                    <td class="text-center">
                    <?php 
                    if($kel->STATUS != '0' && $kel->ENTRY_VIA == '0' && $kel->UMUR2 >= '17' && ($kel->HUBUNGAN == '1' || $kel->HUBUNGAN == '2' || $kel->HUBUNGAN == '3')) {
                        echo $cetakSK;
                    }
                    ?>    
                    </td>
                </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php
    if ($dataSKM) { ?>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="table-SKM" style="margin-bottom: 40px;">
        <thead>
            <tr>
                <th width="80%">Surat Kuasa Mengumumkan (pdf/doc(x)/jpg/png/jpeg)</th>
                <th width="20%">File</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <?php } ?>
    <?php 
    if ($data_sk_elo) { ?>
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="table-SK" style="margin-bottom: 40px;">
        <thead>
            <tr>
                <th width="80%">Surat Kuasa (pdf/doc(x)/jpg/png/jpeg)</th>
                <th width="20%">File</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <?php } ?>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">
    </div>
</div>


<script language="javascript">
    var showSK = {
        loopSKM: function( data ) {
            $.each(data, function( index, value ) {
                var fileSKM = JSON.parse( value );
                $.each(fileSKM, function ( index2, value2 ) {
                    var hrefSKM = "<?php echo base_url("uploads"); ?>/data_skm/<?php echo $path1; ?>/" + index + "/" + value2;
                    if(showSK.checkExist( hrefSKM )) {
                        $( "#table-SKM tbody" ).append(
                            "<tr>" + 
                                "<td>" + value2 + "</td>" +
                                "<td class='text-center'>" +
                                    "<a class='btn btn-sm' target='_blank' href='" + hrefSKM + "'><span class='glyphicon glyphicon-new-window text-danger'></span></a>" +
                                "</td>" +
                            "</tr>"
                        );
                    }
                });
            });
        },
        loopSK: function( data ) {
            $.each(data, function( index, value ) {
                var fileSK = JSON.parse( value );
                $.each(fileSK, function ( index2, value2 ) {
                    var hrefSK = "<?php echo base_url("uploads"); ?>/data_sk/<?php echo $path1; ?>/" + index + "/" + value2;
                    if(showSK.checkExist( hrefSK )) {
                        $( "#table-SK tbody" ).append(
                            "<tr>" + 
                                "<td>" + value2 + "</td>" +
                                "<td class='text-center'>" +
                                    "<a class='btn btn-sm' target='_blank' href='" + hrefSK + "'><span class='glyphicon glyphicon-new-window text-danger'></span></a>" +
                                "</td>" +
                            "</tr>"
                        );
                    }
                });
            });
        },
        loopSKElo: function( data ) {
            var fileSK = JSON.parse( data );
            $.each(fileSK, function ( index2, value2 ) {
                $( "#table-SK tbody" ).append(
                    "<tr>" + 
                        "<td>" + value2.NAME + "</td>" +
                        "<td class='text-center'>" +
                            "<a class='btn btn-sm' target='_blank' href='" + value2.URL + "'><span class='glyphicon glyphicon-new-window text-danger'></span></a>" +
                        "</td>" +
                    "</tr>"
                );
            });
        },
        checkExist: function ( url ) {
            var res = false;
            $.ajax({
                url: url,
                type: "HEAD",
                async: false,
                success: function() {
                    res = true;
                },
            });
            return res;
        }
    };

    $(document).ready(function() {
        var dataSK = <?php echo json_encode($dataSK); ?>;
        var dataSKM = <?php echo json_encode($dataSKM); ?>;
        var dataSKElo = <?php echo json_encode($data_sk_elo); ?>;
        // if (dataSK) {
        //     $( "#table-SK tbody" ).empty();
        //     showSK.loopSK( dataSK );
        // }
        // if (dataSKM) {
        //     $( "#table-SKM tbody" ).empty();
        //     showSK.loopSKM( dataSKM );
        // }
        if(dataSKElo){
            $( "#table-SK tbody" ).empty();
            showSK.loopSKElo( dataSKElo );
        }
        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });

        $('.btn-print').click(function(e) {
            url = $(this).attr('href');
            html = '<iframe src="' + url + '" width="100%" height="500px"></iframe>';
            OpenModalBox('Print Tracking LHKPN', html, '', 'large');
            return false;
        });

        $(".cetakSK-action").click(function(e) {
            var id = $(this).attr('id');
            var id_lhkpn = $(this).attr('data-id');
            var LINK = '<?php echo base_url(); ?>portal/review_harta/cetak_surat_kuasa_individual/' + id + '/' + id_lhkpn + '/1';
            window.open(LINK, '_blank');
        });

        $(".cetakSKPN-action").click(function(e) {
            var id_lhkpn = $(this).attr('id');
            var LINK = '<?php echo base_url(); ?>portal/review_harta/surat_kuasa_pdf2/' + id_lhkpn + '/1/1';
            window.open(LINK, '_blank');
        });
        $(".cetakSKM-action").click(function(e) {
            var id_lhkpn = $(this).attr('id');
            var LINK = '<?php echo base_url(); ?>portal/review_harta/surat_kuasa_pdf/' + id_lhkpn + '/1';
            window.open(LINK, '_blank');
        });
    });

    function generateBarcode() {
        var value = '<?php echo @$id; ?>';
        var btype = 'code93';
        var renderer = $("input[name=renderer]:checked").val();

        var quietZone = false;
        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')) {
            quietZone = true;
        }

        var settings = {
            output: renderer,
            bgColor: $("#bgColor").val(),
            color: $("#color").val(),
            barWidth: $("#barWidth").val(),
            barHeight: $("#barHeight").val(),
            moduleSize: $("#moduleSize").val(),
            posX: $("#posX").val(),
            posY: $("#posY").val(),
            addQuietZone: $("#quietZoneSize").val()
        };

        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')) {
            value = {code: value, rect: true};
        }

        if (renderer == 'canvas') {
            clearCanvas();
            $("#barcodeTarget").hide();
            $("#canvasTarget").show().barcode(value, btype, settings);
        } else {
            $("#canvasTarget").hide();
            $("#barcodeTarget").html("").show().barcode(value, btype, settings);
        }
    }

    $(function() {
        generateBarcode();
    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>