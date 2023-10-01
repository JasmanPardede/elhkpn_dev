
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
                    <h4>Penambahan/Perubahan Data PNWL Melalui File Excel</h4>


                    Penambahan atau perubahan data PN/WL melalui file excel dapat dilakukan dengan tahapan sebagai berikut:
                    <ul>
                        <li>Mendownload data PN/WL Instansi melalui link ini
                            <a href="index.php/ereg/all_pn/downloadFileExcel" class="btn btn-sm btn-primary">Klik untuk Download</a>
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
            $cek_temp_pn = $list_cek_temp;
            $up_pn = $list_ver_pnwl;
            $add_pn = $list_ver_pnwl_tambah;
            $non_pn = $list_ver_pnwl_non_aktif;
            $non_pn = ($cek_temp_pn > 0) ? $non_pn : 0;
            if ($up_pn == 0 && $add_pn == 0) {
                ?>
                <!-- Standar Form -->
                <h3 >Upload file Excel yang sudah diperbaharui </h3>
                <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ereg/all_pn/saveUploadExl" enctype="multipart/form-data">

                    <div class="form-inline">
                        <div class="form-group">
                            <label for="exampleInputFile">Pilih File</label>
                            <input type="file" id="file_xls" name="file_xls" required data-allowed-file-extensions='["xls", "xlsx"]'>
                            <input type="text" value="100" name="test" hidden/>
                        </div>

                    </div>
                    <br/>
                    <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">Upload files</button>
                </form>
            <?php 
            }else{
                echo "<h4 style='color: red'>File Excel yang sudah di upload masih dalam proses Verifikasi </h4>";
                
            }
                
            ?>

        </div><!-- /.row -->
    </div><!-- /.row -->



</div> <!-- /container -->

<script>
    $(document).ready(function() {
        $("input[type=file]").fileinput({
            showUpload: false,
        });

        var form = $("#ajaxFormAdd");

        var msg = {
            success: 'Data Berhasil Diupload!',
            error: 'Data Gagal Disimpan!'
        };

        $("#ajaxFormAdd").submit(function() {
            $('#loader_area').show();
            var urll = form.attr('action');
            var formData = new FormData($(this)[0]);


            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function(htmldata) {
                    htmldata = JSON.parse(htmldata);
                    alert(htmldata.status);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            top.location.href = "index.php#index.php/ereg/All_ver_pn/daftar_xl";//redirection
            $('#allpage').replaceWith($('#allpage', $(htmldata)));
            alertify.success(msg.success);
            CloseModalBox();
            $('#loader_area').hide();

            return false;
        });

    });

</script>