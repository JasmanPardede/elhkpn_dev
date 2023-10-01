<?php if ($mode == 'viewBap') { ?>
    <div id="wrapperBAP">
 <button id="gen<?php echo $items->ID_LHKPN; ?>" type="button" class="btn btn-sm btn-success btnGenPDFIIALL nodownl" href="index.php/eano/announ/CreatePdfFinalALL/<?php echo $ID_BAP; ?>" value="<?php echo $items->STATUS_CETAK_PENGUMUMAN_PDF; ?>">KIRIM ALL</button><br/><br/>
        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>No. Agenda</th>
                    <th>PN</th>
                    <th>Tgl Lapor</th>
                    <th>Jabatan</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($item)) {
                    ?>
                    <tr>
                        <td colspan="6" align="center"><i> Data tidak ditemukan !!! </i></td>
                    </tr>
                    <?php
                } else {
                    $no = 1;
                    foreach ($item as $items):
                        ?>
                        <tr>
                            <td><?= @$no++; ?>. </td>
                            <td class="text-center"><?=
                                date('Y', strtotime($items->tgl_lapor)) . '/' . ($items->JENIS_LAPORAN == '4' ? 'R' : ($items->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $items->NIK . '/' . $items->ID_LHKPN;
                                ;
                                ?></td>                            
                            <td class="text-center"><?= @$items->NAMA_LENGKAP; ?></td>
                            <td class="text-center"><?= @$items->tgl_kirim_final; ?></td>
                            <td class="text-center"><?= '<ul><li>'.@$items->NAMA_JABATAN.' - '.@$items->UK_NAMA.' - '.@$items->INST_NAMA.'</li></ul>'; ?></td>
<!--                            <td class="text-center">
                                <?php
                                if ($items->NAMA_JABATAN) {
                                    $j = explode(',', $items->NAMA_JABATAN);
                                    $c_j = count($j);
                                    echo '<ul>';
                                    foreach ($j as $ja) {
                                        $jb = explode(':58:', $ja);
                                        $idjb = $jb[0];
                                        $statakhirjb = @$jb[1];
                                        $statakhirjbtext = @$jb[2];
                                        $statmutasijb = @$jb[3];
                                        if (@$jb[4] != '') {
                                            if ($c_j > 1) {
                                                if (@$jb[5] == 1)
                                                    echo '<li>' . @$jb[4] . '</li>';
                                            }else {
                                                echo '<li>' . @$jb[4] . '</li>';
                                            }
                                        }
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </td>-->
                            <td width="120" class="text-center" >
                                <div id="body_all" >
                                    <?php
                                    $no_bap = str_replace("/", "_", @$items->NOMOR_BAP);
                                    $th = date('Y'); 
                                    $output_filename = "Pengumuman_Harta_Kekayaan_LHKPN_" . $items->NHK . ".docx";
                                    ?>
                                <!--<button type="button" class="btn btn-sm btn-success btnGenPDF" href="index.php/eano/announ/genpdff/<?php echo substr(md5($items->ID_BAP), 5, 8); ?>">Generate</button>-->
                                    <?php // if ($items->STATUS_CETAK_PENGUMUMAN_PDF != 1) { ?>
                                        <button id="gen<?php echo $items->ID_LHKPN; ?>" type="button" class="btn btn-sm btn-success btnGenPDFII nodownl" href="index.php/eano/announ/CreatePdfFinalWithEmail/<?php echo $items->ID_LHKPN; ?>" value="<?php echo $items->STATUS_CETAK_PENGUMUMAN_PDF; ?>">Kirim</button>
                                    <?php // if (@$items->STATUS_CETAK_PENGUMUMAN_PDF == '1') { ?>    
                                            <!--<a id="DownloadPDFII" class="btn btn-sm btn-success yesdownl" target="_blank" href="<?php // echo base_url() . 'uploads/FINAL_LHKPN/' . '/'. $no_bap . '/' . @$items->NIK . "/$output_filename"; ?>">Download</a>-->
                                            <a id="DownloadPDFII" class="btn btn-sm btn-success yesdownl" target="_blank" href="index.php/eano/announ/PreviewAnnoun/<?php echo $items->ID_LHKPN; ?>">Preview</a>
                                    <?php // } ?>
                                        <!--<a id="don<?php echo $items->ID_LHKPN; ?>" style="display: none" class="btn btn-sm btn-success yesdownl" target="_blank" href="<?php echo base_url() . 'uploads/FINAL_LHKPN/' . @$items->NIK . "/$th.pdf"; ?>">Download</a>-->
                                    <?php // } else { ?>
                                        <!--<a id="DownloadPDFII" class="btn btn-sm btn-success yesdownl" target="_blank" href="<?php echo base_url() . 'uploads/FINAL_LHKPN/' . @$items->NIK . "/$th.pdf"; ?>">Download</a>-->
                                    <?php // } ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="BAP" data-id="10" style="display: none;">
        <div id="ctnDltBap"></div>
    </div>
<?php } else if ($mode == 'addBap') { ?>
    <form class="form-horizontal" method="post" id="formAddBap" action="javascript:;" enctype="multipart/form-data">
        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th><input type="checkbox" onClick="chk_all(this);" title="Check ALL"></th>
                    <th>No. Agenda</th>
                    <th>Tgl Lapor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($LHKPN)) {
                    ?>
                    <tr>
                        <td colspan="6" align="center"><i> Data tidak ditemukan !!! </i></td>
                    </tr>
                    <?php
                } else {
                    $no = 1;
                    foreach ($LHKPN as $items):
                        ?>
                        <tr>
                            <td><?= @$no++; ?>. </td>
                            <td class="text-center"><input name="cekIDLhkpn[]" class="chk" type="checkbox" value="<?= @$items->IDLHKPN; ?>" onclick="chk(this);" /></td>
                            <td class="text-center"><?= date('Y', strtotime($items->TGL_LAPOR)) . '/' . ($items->JENIS_LAPORAN == '4' ? 'R' : ($items->JENIS_LAPORAN == '5' ? 'P' : 'K')) . '/' . $items->NIK . '/' . $items->IDLHKPN; ?></td>                            
                            <td class="text-center"><?= @$items->TGL_LAPOR; ?></td>
                        </tr>
                        <?php
                    endforeach;
                }
                ?>
            </tbody>
        </table>
        <div class="pull-right">
            <input type="hidden" class="" name="ID_BAP" id="" style="" value="<?= @$ID_BAP; ?>" placeholder="">
            <?= (empty($LHKPN) ? '' : '<input type="submit" class="btn btn-sm btn-success" value="Simpan">' ); ?>
            <input type="button" class="btn btn-sm btn-default aa" value="Kembali" onclick="f_close(this);">
            <input type="hidden" class="" name="act" id="" style="" value="doinsert">
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $("form#formAddBap").submit(function(event) {
                var urll = 'index.php/eano/announ/saveBap';
                var formData = new FormData($(this)[0]);
                var tex = '<?php echo $IDBAP ?>';

                $.ajax({
                    url: urll,
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function(html) {
                        msg = {
                            success: 'Data Berhasil Disimpan!',
                            error: 'Data Gagal Disimpan!'
                        };
                        if (html == 0) {
                            alertify.error(msg.error);
                        } else {
                            alertify.success(msg.success);
                        }
                        if (html == 1) {
                            f_close();

                            var uuu = "index.php/eano/announ/bap_detail/" + tex;
                            $.post(uuu, function(data) {
                                $('#wrapperBAP').html(data);
                            });

                        } else {
                            console.log('error');
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            });
        });

        var idChk = [];

        function chk_all(ele) {
            if ($(ele).is(':checked')) {
                $('.chk').prop('checked', true);
            } else {
                $('.chk').prop('checked', false);
            }

            $('.chk:visible').each(function() {
                chk(this);
            });
        }

        function chk(ele) {
            var val = $(ele).val();
            idChk.push(val);
        }
    </script>
<?php }else { ?>
    <form class="form-horizontal" method="post" id="formdltBap" action="javascript:;" enctype="multipart/form-data">
        <div class="box-body form-horizontal">
            <div class="col-sm-12">
                <input type="hidden" class="" name="ID" style="" value="<?= @$item->ID; ?>" placeholder="">

                <div class="form-group">
                    <label class="col-sm-4 control-label">No Agenda :</label>
                    <div class="col-sm-5">
                        <?= @$agenda; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">No BAP :</label>
                    <div class="col-sm-5">
                        <?= @$item->NOMOR_BAP; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Lapor :</label>
                    <div class="col-sm-5">
                        <?= @$item->TGL_LAPOR; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Tanggal Bast :</label>
                    <div class="col-sm-5">
                        <?= @$item->TGL_PENYERAHAN; ?>
                    </div>
                </div>

            </div>
        </div><!-- /.box-body -->
        <div class="pull-right">
            <input type="hidden" name="ID" id="ID" style="" value="<?php echo substr(md5($item->ID), 5, 8); ?>" placeholder="">
            <input type="hidden" name="ID_BAP" id="ID_BAP" style="" value="<?php echo substr(md5($item->ID_BAP), 5, 8); ?>" placeholder="">
            <button type="button" class="btn btn-sm btn-success btnGenPDF" href="index.php/eano/announ/genpdff/<?php echo substr(md5($item->ID_BAP), 5, 8); ?>">Kirim</button>
            <input type="button" class="btn btn-sm btn-default aa" value="Kembali" onclick="f_close(this);">
            <input type="hidden" class="" name="act" id="" style="" value="dodelete">
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() {
            $("form#formdltBap").submit(function(event) {
                var urll = 'index.php/eano/announ/saveBap';
                var formData = new FormData($(this)[0]);
                var tex = $('#ID_BAP').val();

                $.ajax({
                    url: urll,
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function(html) {
                        msg = {
                            success: 'Data Berhasil Dihapus!',
                            error: 'Data Gagal Dihapus!'
                        };
                        if (html == 0) {
                            alertify.error(msg.error);
                        } else {
                            alertify.success(msg.success);
                        }
                        if (html == 1) {
                            f_close();

                            var uuu = "index.php/eano/announ/bap_detail/" + tex;
                            $.post(uuu, function(data) {
                                $('#wrapperBAP').html(data);
                            });

                        } else {
                            console.log('error');
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            });
            $('.btnGenPDF').click(function(event) {
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        msg = {
                            success: 'Pesan dan email berhasil dikirim !',
                            error: 'Pesan dan email gagal dikirim !'
                        };
                        if (res.hasil == 1) {
                            swal({
                                title: 'Data berhasil disimpan',
                                text:
                                        'File bisa didownload di ' +
                                        '<a href="<?= base_url("uploads/FINAL_LHKPN") ?>' + '/' + res.name + '">disini</a>',
                                html: true
                            });
                        } else {
                            // alertify.success(msg.success);
                        }
                    }
                });

            });
        })
    </script>
<?php } ?>

<script type="text/javascript">
    function showForm(tipe, param) {
        $('#wrapperBAP').slideUp('slow', function() {
            $('div[data-id="10"]').slideDown('slow');
            var target = "index.php/eano/announ/showFormBap/" + tipe + "/" + param;
            $('div[data-id="10"]').load(target);
        });
    }

    function f_close()
    {
        $('.BAP').slideUp('slow', function() {
            $('#wrapperBAP').slideDown('slow');
        })
    }

    $(document).ready(function() {

        $('.btnGenPDFII').click(function(event) {
            var url = $(this).attr('href');
            var v = $(this);
            $('#loader_area').show();
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                success: function(htmldata) {
                    htmldata = JSON.parse(htmldata);
//                    console.log(htmldata);
                    msg = {
                        success: 'Data Berhasih di Kirim !',
                        error: 'Data Tidak Berhasih di Kirim !'
                    };
                    if (htmldata.status == 0) {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {

                        $("#don" + htmldata).show();
                        $("#gen" + htmldata).hide();
                        alertify.success(msg.success);
                        $('#loader_area').hide();
                    }

                },
                cache: false,
                contentType: false,
                processData: false
            });

        });
        
        
        $('.btnGenPDFIIALL').click(function(event) {
            var url = $(this).attr('href');
  
            $('#loader_area').show();
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                success: function(htmldata) {
                    htmldata = JSON.parse(htmldata);
                    msg = {
                        success: 'Semua Data Berhasil di Kirim !',
                        error: 'Semua Data Tidak Berhasil di Kirim !'

                    };
                    if (htmldata.status == 0) {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {
                        alertify.success(msg.success);
                        $(".yesdownl").show();
                        $(".nodownl").hide();
                        $('#loader_area').hide();
                    }

                },
                cache: false,
                contentType: false,
                processData: false
            });

        });


    });
</script>