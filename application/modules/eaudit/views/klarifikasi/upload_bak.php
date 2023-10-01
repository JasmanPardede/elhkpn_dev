<style type="text/css">
    .final-note { background-color: #ffffcc; border-left: 6px solid #ffeb3b; }
    .final-panel { margin-top: 16px; margin-bottom: 16px; }
    [hidden] {
  display: none !important;
}
</style>
<section class="content-header">
    <h1><i class="fa <?php echo $icon; ?>"></i> <?php echo $title; ?></h1>
    <?php echo $breadcrumb; ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
                <h4><?php echo $header_laporan; ?></h4>
                <label><?php echo $jenis_laporan; ?></label>
                <div class="final-panel final-note">
                    <br>
                    <p>&nbsp; <i class="fa fa-exclamation-triangle danger"></i> Anda dapat mengunggah semua file yang terkait dengan BAK dengan ekstensi <strong>jpg, png, pdf, tiff, doc, docx, xls, xlsx, xlsm, zip dan rar</strong> dengan ukuran maksimal <strong>50 MB</strong></p>
                    <br>
                </div>
                <form class="form-inline" id="data" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="upload_id" value="<?php echo $new_id_lhkpn; ?>" />
                    <div class="form-group">
                        <label for="staticEmail2" class="sr-only">Nama Dokumen</label>
                        <input type="text" class="form-control" id="file_name" name="file_name" placeholder="Nama Dokumen">
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <input type="file" name="userfile" id="userfile" class="file">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control" readonly="" placeholder="Pilih File">
                                <span class="input-group-btn">
                                    <button class="btn btn-danger browse" type="button"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-danger"><i class="fa fa-save"></i> Simpan File</button>
                </form>
                <table class="table table-bordered table-hover table-striped" id="tableSKM">
                    <thead class="table-header">
                        <tr>
                            <th width="30px">No</th>
                            <th>Nama Dokumen</th>
                            <th>Nama File</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                <div align="right">
                    <button class="btn btn-warning" onclick="go_back()" ><span class="fa fa-arrow-left"></span> Kembali</button>
                </div>
            </div>
        </div>
    </div>
</section>
<style type="text/css">
    .file {
  visibility: hidden;
  position: absolute;
}
</style>

<script type="text/javascript">
    function go_back() {
    url = 'index.php/eaudit/periksa/index/';
    $('#loader_area').show();
    ng.LoadAjaxContent(url);

    return false;
  }
    $(document).on('click', '.browse', function(){
  var file = $(this).parent().parent().parent().find('.file');
  file.trigger('click');
});
$(document).on('change', '.file', function(){
  $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
});
    $(document).ready(function () {

        $('#tableSKM').dataTable({
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
            'sAjaxSource': '<?php echo base_url(); ?>eaudit/klarifikasi/load_file_bak/<?php echo $new_id_lhkpn ?>',
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "searching": false,
            "bAutoWidth": false,
            "columnDefs": [
            {
                "targets": 0, // your case first column
                "className": "text-center",
            },
            {
                "targets": 3,
                "className": "text-center",
            }],
            'fnServerData': function(sSource, aoData, fnCallback) {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }

        });

        $("form#data").submit(function(e) {
            e.preventDefault();    
            var formData = new FormData(this);
            var file_name = $('#file_name').val();
            var file = $('#userfile').val();

            if (file == '') {
                alertify.error('Pilih File Terlebih Dahulu');
                return false;
            }

            if (file_name == '') {
                alertify.error('Nama Dokumen Tidak Boleh Kosong');
                return false;
            }

            $.ajax({
                url: '<?php echo base_url() ?>eaudit/klarifikasi/saveDocuments',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                        if (obj.status == 'success') {
                            $('#tableSKM').DataTable().ajax.reload();
                            document.getElementById("data").reset();
                            alertify.success(obj.msg);
                        }
                        else{
                            alertify.error(obj.msg);
                        }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>