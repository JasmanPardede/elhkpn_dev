<script type="text/javascript">

    /**
     * 
     * @returns {undefined}
<?php echo json_encode($input_baru); ?>
     */

    $(document).ready(function () {
        $("#btnBatalDataDoubleLhkpnOffline").click(function () {
            ng.LoadAjaxContent("index.php/efill/lhkpnoffline/index/penerimaan");
        });
        $("#btnLanjutkanDataDoubleLhkpnOffline").click(function () {

            var formURL = "index.php/efill/lhkpnoffline/simpan_penerimaan_add";
            $.ajax({
                url: formURL,
                type: 'POST',
                data: <?php echo json_encode($input_baru); ?>,
                method: 'POST',
                dataType: 'json',
                cache: false,
                success: function (data, textStatus, jqXHR) {
                    if (data.msg != "success" && data.msg != "failed") {
                        alert(data.msg);
                        return false;
                    }

                    if (data.msg != "success") {
                        alertify.error("Data Gagal diupload");
                        return false;
                    }

                    if (data.tmp_idx != '0') {

                        $('#loader_area').show();

                        setTimeout(function () {
                            ng.LoadAjaxContent('index.php/efill/lhkpnoffline_verifikasi/view_uploaded/' + data.tmp_idx);
                        }, 500);

                        return false;
                    }

                    if (data.tmp_idx == '0' && data.msg == 'success') {
                        alert(data.msg);
                        ng.LoadAjaxContent("index.php/efill/lhkpnoffline/index/penerimaan");
                    }
                }
            });
        });
    });
</script>