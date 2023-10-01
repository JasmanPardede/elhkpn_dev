<div id="wrapperFormAdd" class='form-horizontal'>
    <form method="post" id="ajaxFormEdit"  class="ajaxForm" action="index.php/profile/dochangeEmail">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label">Konfirmasi Password <font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Email Lama <font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <?php echo $email ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Email Baru <font color='red'>*</font>:</label>
                <div class="col-sm-5">
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-sm-1" style="margin-top: 5px;" id="div-pswdLama">
                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="dochangepassword">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
        </div>
    </form>    
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ajaxFormEdit").submit(function () {
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.post(url, data, function (res) {
                if(res.status == 0){
                    alertify.error(res.msg);
                }else{
                    alertify.success(res.msg);
                    CloseModalBox();
                }
            }, 'json');
            return false;
        });

        $('.breadcrumb a').click(function (e) {
            url = $(this).attr('href');
            window.location.hash = url;
            LoadAjaxContent(url);
            e.preventDefault(); //Prevent Default action.
            e.unbind();
            return false;
        });                 

        $('#btnBatal').click(function (e) {
            url = $(this).attr('href');
            window.location.hash = url;
            LoadAjaxContent(url);
            e.preventDefault(); //Prevent Default action.
            e.unbind();
            return false;
        }); 
    })
</script>