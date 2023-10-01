<div id="wrapperFormDetail">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-3">
            <?php if (file_exists("uploads/data_pribadi/" . $item->NIK . "/" . $item->FOTO) && !empty($item->FOTO)) { ?>
                <img src='<?php echo base_url("uploads/data_pribadi/" . $item->NIK . "/" . $item->FOTO); ?>' class="img-rounded col-md-12"/>
            <?php } else { ?>
                <img src="<?php echo base_url(); ?>images/no_available_image.png" class="img-rounded col-md-12"/>
            <?php } ?>
        </div>
        <div class="col-md-8">
            <h3>Detail</h3>
            <div class="col-md-12">

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NIK </label>
                        <div class="col-sm-9"> :
                            <?php echo isset($item->NIK) ? $item->NIK : ""; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama </label>
                        <div class="col-sm-9"> :
                            <?php echo isset($item->NAMA) ? $item->NAMA : ""; ?>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tempat / Tanggal Lahir </label>
                        <div class="col-sm-9"> :
                            <?php echo isset($item->TEMPAT_LAHIR) ? $item->TEMPAT_LAHIR : "-"; ?>
                            <?php echo isset($item->TGL_LAHIR) ? tgl_format($item->TGL_LAHIR) : "-"; ?>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jenis Kelamin </label>
                        <div class="col-sm-9"> :
                            <?php
                            if ($item->JNS_KEL != NULL || $item->JNS_KEL != '') {
                                if ($item->JNS_KEL == 1) {
                                    $jk = "Laki - laki";
                                } elseif ($item->JNS_KEL == 2) {
                                    $jk = "Perempuan";
                                } else {
                                    $jk = "";
                                }
                            }
                            ?>

                            <?php echo isset($item->JNS_KEL) ? $jk : ""; ?>

                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NIP </label>
                        <div class="col-sm-9"> :
                            <?php echo isset($item->NIP_NRP) ? $item->NIP_NRP : ""; ?>
                        </div>
                    </div> 
                </div>     

                <!--  </div>
     
                 <div class="col-md-6"> -->

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email </label>
                        <div class="col-sm-9"> :
                            <?php echo isset($item->EMAIL) ? $item->EMAIL : ""; ?>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nomor Handphone </label>
                        <div class="col-sm-9"> :
                            <?php echo isset($item->NO_HP) ? $item->NO_HP : ""; ?>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cetak Surat Kuasa</label>
                        <div class="col-sm-9"> :
                            <?php 
                            $exp = explode('/', @$id);
                            ?>
                            <a target="_blank" href="<?php echo base_url(); ?>portal/review_harta/surat_kuasa_pdf2/<?= $id_lhkpn ?>/1/1" class='btn btn-success btn-sm cetakSKPN-action' title='Cetak SK PN' <?= $have_lhkpn == false ? 'disabled' : '' ;?> ><i class='fa fa-file'></i></a>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cetak SKM</label>
                        <div class="col-sm-9"> :
                            <?php 
                            $exp = explode('/', @$id);
                            ?>
                            <a target="_blank" href="<?php echo base_url(); ?>portal/review_harta/surat_kuasa_pdf/<?= $id_lhkpn ?>/1" class='btn btn-warning btn-sm cetakSKM-action' title='Cetak SK Mengumumkan'><i class='fa fa-file'></i></a>
                        </div>
                    </div> 
                </div>


            </div>

        </div>
    </div>
    <br /><br />
    <div class="row">
        <!--<div class="col-md-1"></div>-->
        <div>
            <!-- <table class="table table-bordered table-hover table-striped" > -->
            <table id="dt_completeNEW" class="table table-striped">
                <!-- <thead class="table-header"> -->
                <thead>
                    <tr>
                        <th width="7%">No.</th>
                        <th width="21%">Jabatan/Eselon</th>
                        <th width="21%">Lembaga</th>
                        <th width="21%">Unit Kerja</th>
                        <th width="21%">Sub Unit Kerja</th>
                        <th width="7%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($riwayat_jabatan as $val) {
                        $i++;
                        echo '<tr>
                            <td align="center">' . $i . '</td>
                            <td>' . $val->NAMA_JABATAN . '</td>
                            <td>' . $val->INST_NAMA . '</td>
                            <td>' . $val->UK_NAMA . '</td>
                            <td>' . $val->SUK_NAMA . '</td>
							<td>' . $val->ISPRIMARY . '</td>
                            </tr>';
                    }
                    ?>

                    <?php //$i=1;foreach ($detJabatan as $key):  ?>
                            <!-- <tr>
                                <td><?php echo @$i++ . '.'; ?></td>
                                <td><?php echo @$key->IS_CALON . ' ' . @$key->NAMA_JABATAN . ' / ' . @$key->ESELON; ?></td>
                                <td><?php echo @$key->INST_NAMA; ?></td>
                                <td><?php echo @$key->UK_NAMA; ?></td>
                                <td align="center">
                    <?php if (@$key->SD == '' || date('Y', strtotime(@$key->SD)) == '1970') { ?>
                        <?php echo @date('d/m/Y', strtotime(@$key->TMT)); ?>
                    <?php } else { ?>
                        <?php echo @date('d/m/Y', strtotime(@$key->TMT)); ?> - <?php echo date('d/m/Y', strtotime(@$key->SD)); ?>
                    <?php } ?>
                                </td>
                                <td><?php if (@$key->FILE_SK != '') { ?><a href="<?php echo base_url('uploads/data_jabatan/' . $key->NIK . '/' . $key->FILE_SK); ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/data_jabatan/' . $key->NIK . '/' . @$key->FILE_SK); ?></a><?php } ?></td>
                                <td></td>
                                <td></td>
                            </tr> -->
                    <?php //endforeach;  ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-1"></div>

    </div>
    <div class="row">
        <div>
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
                        $id_keluarga = $kel->ID_KELUARGA;
                        $id_lhkpn = $kel->ID_LHKPN;
                        $url_cetak_sk = base_url()."portal/review_harta/cetak_surat_kuasa_individual/".$id_keluarga."/".$id_lhkpn."/1";
                        $cetakSK = "<a target='_blank' href='$url_cetak_sk' class='btn btn-success btn-sm cetakSK-action' title='Cetak SK'><i class='fa fa-file'></i></a>";
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
                        if($kel->STATUS != '0' && $kel->STATUS != '7' && $kel->ENTRY_VIA == '0' && $kel->UMUR2 >= '17' && ($kel->HUBUNGAN == '1' || $kel->HUBUNGAN == '2' || $kel->HUBUNGAN == '3')) {
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
        </div>
        <div class="col-md-1"></div>
    </div>
    <div class="pull-right">
        <button type="reset" class="btn btn-warning btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i> Close</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        function app_vi_add(idpn, idj) {

            confirm('Apakah Anda Yakin ?', function () {
                $('#loader_area').show();
                var server_url = 'index.php/ereg/All_pn/app_vi_add/' + idpn + '/' + idj;
                $.ajax({
                    url: server_url,
                    type: "POST",
                    data: {"idtemp": idpn},
                    success: function (response) {
                        if (response == '0') {
                            alertify.error("Gagal.");
                            $('#loader_area').hide();
                        } else {
                            $('#loader_area').hide();
                            send_mail_to(idpn, idj, response, 'add');
                        }
                        $('#loader_area').hide();
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
                //$('#loader_area').hide();
            });
            $('#loader_area').hide();
        }
        var btnDeleteOnClick = function (self) {
            url = $(self).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Batal Verifikasi Data Individual', html, '');
            });
            return false;
        };


    });
</script>