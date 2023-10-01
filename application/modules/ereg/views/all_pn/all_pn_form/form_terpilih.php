Penetapan PN/WL
<form class="form-horizontal" method="post" id="ajaxFormTerpilih" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-3 control-label">Nama PN :</label>
        <div class="col-sm-7">
            <?php echo $item->NAMA; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Jabatan :</label>
        <div class="col-sm-7">
            <?php
            if ($item->IS_CALON == 1) {
                echo '<span class="label label-warning">Calon</span>';
            }
            ?>  <?php echo $item->NAMA_JABATAN; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Instansi :</label>
        <div class="col-sm-7">
            <?php echo $item->INST_NAMA; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Unit Kerja :</label>
        <div class="col-sm-7">
            <?php echo $item->UK_NAMA; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">TMT <font color='red'>*</font>:</label>
        <div class="col-sm-7">
            <input type="text" class="form-control datepicker" id="TMT" name="TMT" value="<?php echo date('d/m/Y'); ?>" required>
        </div>
    </div>
    <div class="pull-right">
        <input type="hidden" name="ID" id="ID" value="<?php echo $item->ID; ?>">
        <input type="hidden" name="act" id="act" value="doterpilih">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Batal</button>
    </div>
</form>

<script type="text/javascript">
    function f_redirect() {
        $.post('index.php/ereg/all_pn/cekNIK/' + $('#NIK').val(), function (data) {
            if (data != 0) {
                $("#wrapperFormPNExist").html(data);
            }
        });
    }

    $(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });
<?php if ($stat == '1') { ?>
            $("form#ajaxFormTerpilih").submit(function (event) {
                var urll = $(this).attr('action');
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: urll,
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (html) {
                        html = jQuery.parseJSON(html);
                        msg = {
                            success: 'Data Berhasil Disimpan!',
                            error: 'Data Gagal Disimpan!'
                        };
                        if (html.status == 0) {
                            alertify.error(msg.error);
                        } else {
                            alertify.success(msg.success);
                        }

                        if (html.status == 1) {
                            $.post('index.php/ereg/all_pn/cekNIK/' + $('#NIK').val(), function (data) {
                                if (data != 0) {
                                    $('.modal-dialog').animate({
                                        width: '+=500'
                                    });
                                    $("#wrapperFormPNExist").html(data);
                                    alertify.success('Jabatan berhasil di tambahkan!');
                                }
                            });
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
                return false;
            });
<?php } else { ?>
            ng.formProcess($("#ajaxFormTerpilih"), 'insert', location.href.split('#')[1]);
<?php } ?>
    });
</script>