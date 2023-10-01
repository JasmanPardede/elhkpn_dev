<div id="wrapperFormAdd">
    <form class="form-horizontal" method="post" id="ajaxFormAdd" action="index.php/ever/verification/saveChangeStatus" enctype="multipart/form-data">
        <div class="col-md-8 detail-form form-horizontal">
            <div class="form-group">
                <label class="col-sm-4 control-label">Set Status lhkpn</label>

            </div>
            <div class="col-sm-7">
                <input type="hidden" name="id_lhkpn" value="<?= $id_lhkpn ?>">
                <input name="status" type="radio" value="5" /> Diterima
                <input name="status" type="radio" value="7" /> Ditolak  
            </div>
        </div>
        <div class="pull-right">
            <button type="submit" class="btn btn-sm btn-primary btn-save">Simpan</button>
            <button type="button" class="btn btn-sm btn-danger btnCancel">Cancel</button>
        </div>
    </form>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        
        $('.btnCancel').click(function() {
            url = 'index.php/ever/verification/index/lhkpn';
            window.location.hash = url;
            ng.LoadAjaxContent(url);
            return false;
        });

        $("#ajaxFormAdd").submit(function() {
            $('#loader_area').show();
            var urll = form.attr('action');
            var formData = new FormData($(this)[0]);

            msg = {
                success: 'Data Berhasih di Simpan !',
                error: 'Data Berhasih di Simpan !'
            };

            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function(htmldata) {
                    htmldata = JSON.parse(htmldata);
                    if (htmldata.status == 0) {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {
                        alertify.success(msg.success);
                        CloseModalBox();
                        $('#loader_area').hide();
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });



            return false;
        });


    });
</script>