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
                            <td>' . $val->DESKRIPSI_JABATAN . '</td>
                            <td>' . $val->NAMA_LEMBAGA . '</td>
                            <td>' . $val->UNIT_KERJA . '</td>
                            <td>' . $val->SUB_UNIT_KERJA . '</td>
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
    <div class="pull-right">
        <button type="button" class="btn btn-success btn-sm "  onclick="app_vi_up(<?php echo $item->ID_PN; ?>,<?php echo $idjb; ?>,<?php echo $item->ID_USER; ?>); CloseModalBox2();"><i class="fa fa-check"></i> Approve</button>

        <button type="button" class="btn btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_vi/<?php echo $item->ID_PN; ?>/<?php echo $idjb; ?>"><i class="fa fa-undo"></i> Decline</button>

        <button type="reset" class="btn btn-warning btn-sms " onclick="CloseModalBox2();"><i class="fa fa-remove"></i> Close</button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Batal Verifikasi Data Individual', html, '');
            });
            return false;
        });
        function app_vi_up(idpn, idj,iduser) {
            confirm('Apakah Anda Yakin ?', function () {
                $('#loader_area').show();
                server_url = 'index.php/ereg/All_pn/app_vi_up/' + idpn + '/' + idj;
                $.ajax({
                    url: server_url,
                    type: "POST",
                    data: {"idtemp": idpn},
                    success: function (response) {

                        if (response == '0') {
                            alertify.error("gagal");
                            $('#loader_area').hide();
                        } else {

                            send_mail_to(idpn, idj, response, iduser, 'up');
                            $('#loader_area').hide();
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });
                //$('#loader_area').hide();
            });
            $('#loader_area').hide();
        }
        ;
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