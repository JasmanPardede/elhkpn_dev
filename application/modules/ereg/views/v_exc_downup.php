<?php
$query_url_download = isset($query_url_download) ? $query_url_download : TRUE;
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Download & Upload Files Excel</strong></div>
        <div class="panel-body">
            <?php
            $dir = 'file/file_result/file_result.xls';
            ?>
            <div class="js-upload-finished">
                <h3>Download files</h3>
                <div class="list-group">
                    <h4>Penambahan/Perubahan Data PNWL Melalui File Excel </h4>


                    Penambahan atau perubahan data PN/WL melalui file excel dapat dilakukan dengan tahapan sebagai berikut:
                    <ul>
                        <li>Mendownload data PN/WL Instansi melalui link ini
                            <a id="anchorDownFileExcel" href="index.php/ereg/all_pn_down_excel/downloadFileExcel<?php echo $query_url_download; ?>" class="btn btn-sm btn-primary">Klik untuk Download</a>
                        </li>
                        <li>Lakukan penambahan, pengurangan atau perubahan terhadap data PN/WL yang telah di download sesuai master unit kerja dan jabatan yang tersedia</li>
                        <li>Upload file yang telah berubah</li>
                        <li> Lakukan urutan tahapan diatas untuk penambahan, pengurangan atau perubahan data PN/WL melalui file excel,
                            serta template file tidak boleh dirubah, apabila ada perubahan template, maka aplikasi e-LHKPN tidak dapat memproses data tersebut</li>
                    </ul>



                    <!--<a href="<?= $dir ?>" class="list-group-item list-group-item-success"><span class="badge alert-success pull-right">file_result.xls</span> <button class="btn btn-sm btn-success">Download</button></a>-->
                </div>

            </div>
            <?php
//            $cek_temp_pn = $list_cek_temp;
            $up_pn = $list_ver_pnwl;
            $add_pn = $list_ver_pnwl_tambah;
//            $non_pn = $list_ver_pnwl_non_aktif;
//            $non_pn = ($cek_temp_pn > 0) ? $non_pn : 0;
            if ($up_pn == 0 && $add_pn == 0) {
                ?>
                <!-- Standar Form -->
                <h3 >Upload file Excel yang sudah diperbaharui</h3>
                <form class="form-horizontal" method="post" id="ajaxFormAdd" action="javascript:void(0);" relaction="index.php/ereg/all_pn/saveUploadExl<?php echo $query_url_download; ?>" enctype="multipart/form-data">

                    <div class="form-inline">
                        <div class="form-group">
                            <label for="exampleInputFile">Pilih File</label>
                            <input type="hidden" id="CARI_INSTANSI" name="CARI[INSTANSI]" value="<?php echo $INST_SATKERKD; ?>" />
                            <input type="hidden" id="CARI_UNIT_KERJA" name="CARI[UNIT_KERJA]" value="<?php echo $UK_ID; ?>" />
                            <input type="file" id="file_xls" name="file_xls" required data-allowed-file-extensions='["xls", "xlsx", "xml"]'>
                            <input type="text" value="100" name="test" hidden/>
                        </div>

                    </div>
                    <br/>
                    <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">Upload files</button>
                </form>
                <?php
            } else {
                echo "<h4 style='color: red'>File Excel yang sudah di upload masih dalam proses Verifikasi </h4>";
            }
            ?>

            <div class="form-group">
                <div class="col-sm-10">
                    <div class="pull-right">
                        <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->
    </div><!-- /.row -->



</div> <!-- /container -->

<div id="ModalWarning" class="modal fade" role="dialog">
    <div class='modal-dialog' style="margin:15% auto">    
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pemberitahuan</h4>
            </div> 
            <div class="modal-body">
                <div class="">
                    <div id="notif-text"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<script>

    function notif(t, at) {
        if (isDefined(at)) {
            t = t + at;
        }
        $('#ModalWarning #notif-text').html(t);
        $('#ModalWarning').modal('show');
    }

    function makeBlockId()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    $(document).ready(function () {
        $("input[type=file]").fileinput({
            showUpload: false,
        });

        var form = $("#ajaxFormAdd");

        var msg = {
            success: 'Data Berhasil Diupload!',
            error: 'Data Gagal Disimpan!'
        };

        $("#anchorDownFileExcel").click(function (e) {
            e.preventDefault();
            $('#loader_area').show();

            var anchorUrl = $(this).attr("href"), cfnm = makeBlockId(), recfnm = makeBlockId();

            $.ajax({
                url: anchorUrl,
                type: 'get',
                data: {
                    cfnm: cfnm,
                    recfnm: recfnm,
                },
                dataType: 'text',
                success: function (response) {
                    $('#loader_area').hide();
                    if (response != '0') {

                        if (response == '2') {
                            alert("URL file hasil download excel dikirim ke Mailbox pada aplikasi ini.");
                        } else {
                            window.location = '<?php echo base_url(); ?>download/pnwl_excel/' + response;
                        }

                    } else {
                        alert("Maaf gagal download Excel. Silahkan coba beberapa saat lagi.");
                    }

                    CloseModalBox();

                },
                cache: false,
            });

        });

        $("#ajaxFormAdd").submit(function () {
            $('#loader_area').show();
            var urll = form.attr('relaction');
            var formData = new FormData($(this)[0]);

//            $('#loader_area').hide();
//            window.top.top.location = "<?php echo base_url(); ?>index.php#index.php/ereg/All_ver_pn/daftar_xl";
//            return false;
//console.log(window.top);
//return false;
            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                dataType: 'json',
//                async: false,
                success: function (htmldata) {

//                    htmldata = JSON.parse(htmldata);
//                    alert(htmldata.status);
                    $('#loader_area').hide();
                    if (htmldata.success) {
                        alertify.success(htmldata.msg);

                        ExecDatasss();

                        CloseModalBox();

//                        top.location.href = "index.php#index.php/ereg/All_ver_pn/daftar_xl";
                        //window.location = "<?php echo base_url(); ?>index.php#index.php/ereg/All_ver_pn/daftar_xl";
//                        $('#allpage').replaceWith($('#allpage', $(htmldata)));

                    } else {
                        alert(htmldata.msg);
                    }
                    /**
                     top.location.href = "index.php#index.php/ereg/All_ver_pn/daftar_xl";//redirection
                     
                     
                     CloseModalBox();
                     
                     */

                },
                cache: false,
                contentType: false,
                processData: false
            });
//            redirect('#index.php/ereg/All_ver_pn/daftar_xl');


            return false;
        });

    });

</script>

